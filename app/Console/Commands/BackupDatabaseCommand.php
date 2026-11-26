<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use PDO;
use Throwable;

class BackupDatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:backup {--keep=14 : Número máximo de respaldos recientes a conservar}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Genera un respaldo SQL completo de toda la base de datos compatible con hosting compartido';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $disk = Storage::disk('local');
        $backupDir = 'backups';
        $disk->makeDirectory($backupDir);

        $fileName = 'backup-'.now()->format('Y-m-d_H-i-s').'.sql';
        $relativePath = "{$backupDir}/{$fileName}";
        $fullPath = $disk->path($relativePath);

        try {
            $pdo = DB::connection()->getPdo();
            $driver = DB::connection()->getDriverName();
            $dbName = DB::connection()->getDatabaseName();

            $sql = "-- Respaldo Completo de Base de Datos: {$dbName}\n";
            $sql .= '-- Fecha: '.now()->toIso8601String()."\n";
            $sql .= "-- Driver: {$driver}\n\n";

            if ($driver === 'mysql') {
                $sql .= "SET FOREIGN_KEY_CHECKS=0;\n\n";
                $tables = $pdo->query('SHOW TABLES')->fetchAll(PDO::FETCH_COLUMN);

                foreach ($tables as $table) {
                    $createStmt = $pdo->query("SHOW CREATE TABLE `{$table}`")->fetch(PDO::FETCH_ASSOC);
                    $createSql = $createStmt['Create Table'] ?? array_values($createStmt)[1] ?? '';
                    $sql .= "DROP TABLE IF EXISTS `{$table}`;\n{$createSql};\n\n";

                    $rows = $pdo->query("SELECT * FROM `{$table}`")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row) {
                        $columns = '`'.implode('`, `', array_keys($row)).'`';
                        $values = array_map(static function ($val) use ($pdo): string {
                            if ($val === null) {
                                return 'NULL';
                            }

                            return $pdo->quote((string) $val);
                        }, array_values($row));
                        $sql .= "INSERT INTO `{$table}` ({$columns}) VALUES (".implode(', ', $values).");\n";
                    }
                    $sql .= "\n";
                }

                $sql .= "SET FOREIGN_KEY_CHECKS=1;\n";
            } else {
                // SQLite / genérico compatible
                $tables = $pdo->query("SELECT name, sql FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'")->fetchAll(PDO::FETCH_ASSOC);
                foreach ($tables as $tableInfo) {
                    $table = $tableInfo['name'];
                    $sql .= "DROP TABLE IF EXISTS \"{$table}\";\n{$tableInfo['sql']};\n\n";
                    $rows = $pdo->query("SELECT * FROM \"{$table}\"")->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($rows as $row) {
                        $columns = '"'.implode('", "', array_keys($row)).'"';
                        $values = array_map(static fn ($val): string => $val === null ? 'NULL' : $pdo->quote((string) $val), array_values($row));
                        $sql .= "INSERT INTO \"{$table}\" ({$columns}) VALUES (".implode(', ', $values).");\n";
                    }
                    $sql .= "\n";
                }
            }

            file_put_contents($fullPath, $sql);
            $this->pruneOldBackups($disk, $backupDir, (int) $this->option('keep'));

            Log::info("Respaldo automático de base de datos generado: {$relativePath}");
            $this->info("Respaldo generado exitosamente en: {$fullPath}");

            return self::SUCCESS;
        } catch (Throwable $e) {
            Log::error('Error generando respaldo de base de datos: '.$e->getMessage());
            $this->error('Error generando respaldo: '.$e->getMessage());

            return self::FAILURE;
        }
    }

    /**
     * Conservar únicamente los respaldos más recientes.
     */
    protected function pruneOldBackups($disk, string $dir, int $keep): void
    {
        if ($keep <= 0) {
            return;
        }

        $files = collect($disk->files($dir))
            ->filter(fn (string $path): bool => str_ends_with($path, '.sql'))
            ->sortDesc()
            ->values();

        if ($files->count() > $keep) {
            $files->slice($keep)->each(fn (string $oldFile) => $disk->delete($oldFile));
        }
    }
}
