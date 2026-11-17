<?php

namespace Database\Seeders;

use App\Enums\UserRole;
use App\Models\Activity;
use App\Models\AuditLog;
use App\Models\BoardMember;
use App\Models\CarouselSlide;
use App\Models\ContactMessage;
use App\Models\Document;
use App\Models\LoginLog;
use App\Models\MediaItem;
use App\Models\Publication;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Territory;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Super Admin (Ve todo, gestiona usuarios y encuestas)
        $superAdmin = User::query()->updateOrCreate(
            ['email' => 'superadmin@zanjon.gov.co'],
            [
                'name' => 'Gobernador Mayor Territorial',
                'password' => Hash::make('password123'),
                'role' => UserRole::SuperAdmin,
                'document_number' => '1061728394',
                'phone' => '3128901234',
                'vereda' => 'Zanjón de Garrapatero',
                'email_verified_at' => now(),
            ]
        );

        // 2. Admin (Gestiona encuestas y censos)
        $surveyAdmin = User::query()->updateOrCreate(
            ['email' => 'admin@zanjon.gov.co'],
            [
                'name' => 'Coordinadora de Censo y Territorio',
                'password' => Hash::make('password123'),
                'role' => UserRole::Admin,
                'document_number' => '1061987456',
                'phone' => '3147890123',
                'vereda' => 'San Antonio',
                'email_verified_at' => now(),
            ]
        );

        // 3. Usuario Comunero Estándar
        $usuario = User::query()->updateOrCreate(
            ['email' => 'usuario@zanjon.gov.co'],
            [
                'name' => 'Amalia Mina Lucumí',
                'password' => Hash::make('password123'),
                'role' => UserRole::Usuario,
                'document_number' => '34567890',
                'phone' => '3114567890',
                'vereda' => 'Zanjón de Garrapatero',
                'email_verified_at' => now(),
            ]
        );

        // Comuneros adicionales para alimentar el censo
        $comunero2 = User::query()->updateOrCreate(
            ['email' => 'marcos.balanta@zanjon.gov.co'],
            [
                'name' => 'Marcos Balanta Carabalí',
                'password' => Hash::make('password123'),
                'role' => UserRole::Usuario,
                'document_number' => '1058923412',
                'phone' => '3156789012',
                'vereda' => 'Dominguillo',
                'email_verified_at' => now(),
            ]
        );

        $comunero3 = User::query()->updateOrCreate(
            ['email' => 'clemencia.larrahondo@zanjon.gov.co'],
            [
                'name' => 'Clemencia Larrahondo',
                'password' => Hash::make('password123'),
                'role' => UserRole::Usuario,
                'document_number' => '25890123',
                'phone' => '3109876543',
                'vereda' => 'La Toma',
                'email_verified_at' => now(),
            ]
        );

        // 4. Encuestas de Ejemplo para el Consejo Comunitario
        $survey1 = Survey::query()->updateOrCreate(
            ['slug' => 'censo-demografico-territorial-2026'],
            [
                'title' => 'Censo Demográfico y Familiar Territorial 2026',
                'category' => 'Censo Territorial',
                'description' => 'Levantamiento de caracterización del núcleo familiar, tenencia del predio y condiciones habitacionales en las veredas del Consejo Comunitario.',
                'target_vereda' => null, // Todo el territorio
                'status' => 'active',
                'is_published' => true,
                'created_by' => $superAdmin->id,
                'questions' => [
                    [
                        'id' => 'personas_hogar',
                        'label' => '¿Cuántas personas integran permanentemente su hogar en el territorio?',
                        'type' => 'number',
                        'required' => true,
                    ],
                    [
                        'id' => 'tenencia_predio',
                        'label' => '¿Cuál es la condición de tenencia de la vivienda o predio?',
                        'type' => 'select',
                        'options' => ['Propiedad colectiva con título ancestral', 'Posesión tradicional heredada', 'Arriendo', 'Otro'],
                        'required' => true,
                    ],
                    [
                        'id' => 'acceso_agua',
                        'label' => '¿Cuenta su predio con acceso a acueducto veredal permanente?',
                        'type' => 'radio',
                        'required' => true,
                    ],
                ],
            ]
        );

        $survey2 = Survey::query()->updateOrCreate(
            ['slug' => 'proyectos-productivos-agroecologia-2026'],
            [
                'title' => 'Priorización de Proyectos Productivos Agroecológicos',
                'category' => 'Proyectos Productivos',
                'description' => 'Consulta comunitaria para definir los rubros productivos priorizados en los convenios de soberanía alimentaria y comercialización asociativa.',
                'target_vereda' => null,
                'status' => 'active',
                'is_published' => true,
                'created_by' => $surveyAdmin->id,
                'questions' => [
                    [
                        'id' => 'linea_productiva',
                        'label' => '¿Cuál es la principal vocación productiva de su familia?',
                        'type' => 'select',
                        'options' => ['Cacao tradicional bajo sombra', 'Piscicultura / Tilapia y Cachama', 'Plátano y frutales amazónicos', 'Avicultura campesina', 'Transformación artesanal'],
                        'required' => true,
                    ],
                    [
                        'id' => 'interes_asociacion',
                        'label' => '¿Tiene interés en integrarse a la red asociativa de comercialización del Consejo?',
                        'type' => 'radio',
                        'required' => true,
                    ],
                ],
            ]
        );

        // 5. Respuestas de ejemplo
        SurveyResponse::query()->updateOrCreate(
            [
                'survey_id' => $survey1->id,
                'user_id' => $comunero2->id,
            ],
            [
                'answers' => [
                    'personas_hogar' => '5',
                    'tenencia_predio' => 'Propiedad colectiva con título ancestral',
                    'acceso_agua' => 'Sí',
                ],
                'notes' => 'Requerimos ampliación de la red hacia el sector alto de Dominguillo.',
                'status' => 'verified',
            ]
        );

        SurveyResponse::query()->updateOrCreate(
            [
                'survey_id' => $survey1->id,
                'user_id' => $comunero3->id,
            ],
            [
                'answers' => [
                    'personas_hogar' => '4',
                    'tenencia_predio' => 'Posesión tradicional heredada',
                    'acceso_agua' => 'Parcialmente / En proceso',
                ],
                'notes' => 'Agradecemos el acompañamiento de la Junta de Gobierno.',
                'status' => 'submitted',
            ]
        );

        // 6. Publicaciones Territoriales Iniciales (CMS)
        $pub1 = Publication::query()->updateOrCreate(
            ['slug' => 'padron-comunitario-2026-registro-familiar'],
            [
                'title' => 'Padrón Comunitario 2026: Pasos para registrar a tu núcleo familiar sin intermediarios',
                'category' => 'Comunicado',
                'summary' => 'Guía completa sobre el censo digital y los requisitos para mantener actualizado el libro de comuneros del Consejo.',
                'content' => "El Consejo Comunitario de la Comunidad Negra de Zanjón de Garrapatero abre formalmente la convocatoria para la actualización del padrón poblacional.\n\nEste proceso es fundamental para la defensa del territorio colectivo, la expedición transparente de certificaciones de pertenencia étnica y la postulación a proyectos de vivienda y desarrollo agropecuario bajo la Ley 70 de 1993.\n\nInvitamos a cada jefe de hogar a diligenciar el formulario en línea o acercarse con su cédula a los puntos de atención veredales.",
                'image_url' => 'https://images.unsplash.com/photo-1529156069898-49953e39b3ac?auto=format&fit=crop&w=800&q=85',
                'author_id' => $superAdmin->id,
                'is_visible' => true,
                'published_at' => now()->subDays(1),
            ]
        );

        $pub2 = Publication::query()->updateOrCreate(
            ['slug' => 'jornada-proteccion-microcuencas-garrapatero'],
            [
                'title' => 'Jornada Comunitaria de Protección y Siembra en Microcuencas Hídricas',
                'category' => 'Territorio',
                'summary' => 'La Guardia Cimarrona y comités ambientales avanzaron en la reforestación de riberas con más de 400 árboles nativos.',
                'content' => "Con una alta participación de jóvenes comuneros de Santa Lucía y El Palmar, se llevó a cabo la primera minga ambiental de la temporada.\n\nEl objetivo central es proteger las fuentes de abastecimiento hídrico del territorio y frenar los procesos de degradación en los linderos ancestrales.\n\nSe acordó mantener un cronograma bimestral de seguimiento y monitoreo con el respaldo de las autoridades tradicionales.",
                'image_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=85',
                'author_id' => $surveyAdmin->id,
                'is_visible' => true,
                'published_at' => now()->subDays(3),
            ]
        );

        $pub3 = Publication::query()->updateOrCreate(
            ['slug' => 'taller-saberes-ancestrales-peinados-afro'],
            [
                'title' => 'Taller de Saberes Ancestrales: Memoria y Resistencia en el Cabello Afro',
                'category' => 'Cultura',
                'summary' => 'Encuentro intergeneracional entre mayoras trenzadoras y jóvenes sobre la simbología y los mapas de libertad.',
                'content' => "Las sabedoras del Zanjón de Garrapatero compartieron sus técnicas y memorias vivas con las nuevas generaciones.\n\nDurante dos jornadas se explicaron los orígenes de los tejidos como mapas de escape en la época colonial y la trascendencia de mantener viva la estética y dignidad de nuestro pueblo.",
                'image_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85',
                'author_id' => $surveyAdmin->id,
                'is_visible' => true,
                'published_at' => now()->subDays(5),
            ]
        );

        // 7. Actividades y Agenda Comunitaria
        $act1 = Activity::query()->updateOrCreate(
            ['slug' => 'gran-asamblea-extraordinaria-censo-digital'],
            [
                'title' => 'Gran Asamblea General Extraordinaria de Comuneros',
                'category' => 'Asamblea',
                'description' => 'Socialización plenaria de los módulos de software del Consejo Comunitario, validación de estatutos internos y ruta metodológica para la titulación colectiva.',
                'location' => 'Salón Comunitario El Palmar',
                'event_date' => now()->addDays(8)->setTime(9, 0),
                'status' => 'programada',
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        $act2 = Activity::query()->updateOrCreate(
            ['slug' => 'recorrido-ambiental-cuenca-san-francisco'],
            [
                'title' => 'Recorrido de Protección y Cuidado Ambiental en Cuenca Hídrica',
                'category' => 'Minga',
                'description' => 'Jornada de siembra comunitaria de especies nativas, verificación de linderos ancestrales y limpieza de afluentes.',
                'location' => 'Bajo San Francisco y Santa Lucía',
                'event_date' => now()->addDays(15)->setTime(7, 30),
                'status' => 'programada',
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 8. Elementos Multimedia (Galería y Videos)
        MediaItem::query()->updateOrCreate(
            ['slug' => 'paisaje-montanas-cuencas-garrapatero'],
            [
                'title' => 'Montañas y cuencas hídricas del Zanjón de Garrapatero',
                'type' => 'image',
                'category' => 'Territorio',
                'caption' => 'Territorio ancestral que se cuida, se defiende y se protege.',
                'file_url' => 'https://images.unsplash.com/photo-1516026672322-bc52d61a55d5?auto=format&fit=crop&w=1200&q=85',
                'is_featured' => true,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        MediaItem::query()->updateOrCreate(
            ['slug' => 'video-documental-raices-resistencia'],
            [
                'title' => 'Zanjón de Garrapatero: Raíces, Resistencia y Autonomía Étnica',
                'type' => 'video',
                'category' => 'Territorio',
                'caption' => 'Documental con entrevistas a mayoras y sabedores de El Palmar y Santa Lucía.',
                'file_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'thumbnail_url' => 'https://images.unsplash.com/photo-1470770841072-f978cf4d019e?auto=format&fit=crop&w=1400&q=85',
                'duration' => '12:30 min',
                'is_featured' => true,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 9. Diapositivas del Carrusel Principal (Hero Slides)
        CarouselSlide::query()->updateOrCreate(
            ['title' => 'Territorio de Paz, Memoria Ancestral y Autonomía Étnica'],
            [
                'subtitle' => 'Consejo Comunitario Zanjón de Garrapatero · Norte del Cauca · Gobernanza colectiva, protección del agua y afirmación cultural.',
                'badge_text' => 'Consejo Comunitario Mayor',
                'type' => 'image',
                'media_url' => 'https://images.unsplash.com/photo-1500382017468-9049fed747ef?auto=format&fit=crop&w=1600&q=85',
                'projection_title' => 'Panorámica Territorial del Zanjón',
                'primary_button_text' => 'Explorar Territorio',
                'primary_button_url' => '/territorio',
                'secondary_button_text' => 'Nuestra Historia',
                'secondary_button_url' => '/nosotros',
                'sort_order' => 1,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        CarouselSlide::query()->updateOrCreate(
            ['title' => 'Saberes Ancestrales, Partería y Soberanía Alimentaria'],
            [
                'subtitle' => 'Comité de Sabedoras y Mujeres Negras · Nuestras mayoras transmiten la memoria curativa de las plantas y los cantos ancestrales.',
                'badge_text' => 'Patrimonio Vivo',
                'type' => 'video',
                'media_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'poster_url' => 'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?auto=format&fit=crop&w=800&q=85',
                'projection_title' => 'Cápsula de Saberes Tradicionales',
                'primary_button_text' => 'Ver Videoteca',
                'primary_button_url' => '/videos',
                'secondary_button_text' => 'Conoce Nuestra Identidad',
                'secondary_button_url' => '/nosotros',
                'sort_order' => 2,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        CarouselSlide::query()->updateOrCreate(
            ['title' => 'Censo Territorial y Titulación Colectiva de Tierras'],
            [
                'subtitle' => 'Gobernanza Propia · Ley 70 de 1993 · Fortalecemos el registro unificado de familias, parcelas y reservas naturales comunitarias.',
                'badge_text' => 'Gestión Comunitaria',
                'type' => 'none',
                'projection_title' => null,
                'primary_button_text' => 'Llenar Censo',
                'primary_button_url' => '/login',
                'secondary_button_text' => 'Ver Actividades',
                'secondary_button_url' => '/actividades',
                'sort_order' => 3,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        CarouselSlide::query()->updateOrCreate(
            ['title' => 'Guardia Cimarrona: Custodios de la Vida y la Tierra'],
            [
                'subtitle' => 'Justicia Propia, Convivencia y Protección Integral · Hombres y mujeres que caminan el territorio garantizando la paz.',
                'badge_text' => 'Defensa Colectiva',
                'type' => 'video',
                'media_url' => 'https://www.youtube.com/embed/dQw4w9WgXcQ',
                'poster_url' => 'https://images.unsplash.com/photo-1542601906990-b4d3fb778b09?auto=format&fit=crop&w=800&q=85',
                'projection_title' => 'Guardia Cimarrona en Recorrido',
                'primary_button_text' => 'Conoce la Guardia',
                'primary_button_url' => '/nosotros',
                'secondary_button_text' => 'Canal de Contacto',
                'secondary_button_url' => '/contacto',
                'sort_order' => 4,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 10. Veredas y Sectores Territoriales
        Territory::query()->updateOrCreate(
            ['slug' => 'el-palmar'],
            [
                'name' => 'El Palmar',
                'sector_badge' => 'Sector Alto',
                'description' => 'Cabecera histórica del Consejo y punto de encuentro tradicional de las asambleas generales y comités de sabedores.',
                'estimated_families' => 120,
                'watersheds' => 'Microcuenca El Palmar, Quebrada La Honda',
                'main_activities' => 'Caficultura, huertas familiares y gobernanza',
                'sort_order' => 1,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        Territory::query()->updateOrCreate(
            ['slug' => 'santa-lucia'],
            [
                'name' => 'Santa Lucía',
                'sector_badge' => 'Sector Medio',
                'description' => 'Zona de alta vocación agrícola, trapiches paneleros comunitarios, caña de azúcar y huertas familiares sostenibles.',
                'estimated_families' => 85,
                'watersheds' => 'Río Garrapatero, Quebrada Seca',
                'main_activities' => 'Producción panelera tradicional y plátano',
                'sort_order' => 2,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        Territory::query()->updateOrCreate(
            ['slug' => 'la-toma'],
            [
                'name' => 'La Toma',
                'sector_badge' => 'Sector Ribereño',
                'description' => 'Territorio ancestral de minería artesanal en batea, corredor ecológico hídrico y protección comunitaria de fauna nativa.',
                'estimated_families' => 95,
                'watersheds' => 'Río Ovejas, Caño San José',
                'main_activities' => 'Minería artesanal de batea y pesca ancestral',
                'sort_order' => 3,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        Territory::query()->updateOrCreate(
            ['slug' => 'mazamorrero'],
            [
                'name' => 'Mazamorrero',
                'sector_badge' => 'Sector Veredal',
                'description' => 'Núcleo de tradición oral, medicina botánica ancestral y resguardo de bancos de semillas criollas del territorio.',
                'estimated_families' => 60,
                'watersheds' => 'Quebrada Mazamorrero',
                'main_activities' => 'Medicina tradicional, cacao y cítricos',
                'sort_order' => 4,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 11. Autoridades Tradicionales y Junta de Gobierno
        BoardMember::query()->updateOrCreate(
            ['name' => 'Eustorgio Balanta Lucumí'],
            [
                'role_title' => 'Representante Legal y Gobernador Mayor',
                'committee' => 'Junta de Gobierno',
                'vereda' => 'Zanjón de Garrapatero',
                'bio' => 'Líder histórico con más de 25 años en la defensa de los derechos territoriales, la gobernanza autónoma y la titulación colectiva.',
                'email' => 'gobernador@zanjon.gov.co',
                'phone' => '3128901234',
                'sort_order' => 1,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        BoardMember::query()->updateOrCreate(
            ['name' => 'Rosa Elena Carabalí Mina'],
            [
                'role_title' => 'Coordinadora de Tierras y Territorio',
                'committee' => 'Tierras y Ambiente',
                'vereda' => 'San Antonio',
                'bio' => 'Ingeniera comunitaria especializada en delimitación participativa, cartografía social y monitoreo de microcuencas.',
                'email' => 'tierras@zanjon.gov.co',
                'phone' => '3147890123',
                'sort_order' => 2,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        BoardMember::query()->updateOrCreate(
            ['name' => 'Marcos Aurelio Larrahondo'],
            [
                'role_title' => 'Coordinador General de Guardia Cimarrona',
                'committee' => 'Guardia Cimarrona',
                'vereda' => 'Dominguillo',
                'bio' => 'Guardián del orden y la convivencia comunitaria, custodio de caminos ancestrales y la protección de linderos colectivos.',
                'email' => 'guardia@zanjon.gov.co',
                'phone' => '3156789012',
                'sort_order' => 3,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        BoardMember::query()->updateOrCreate(
            ['name' => 'Dora Inés Balanta Viáfara'],
            [
                'role_title' => 'Sabedora Mayor y Coordinadora de Género',
                'committee' => 'Comité de Mujeres',
                'vereda' => 'La Toma',
                'bio' => 'Partera tradicional afrodescendiente, formadora de nuevas generaciones y custodia de la memoria oral y soberanía alimentaria.',
                'email' => 'mujeres@zanjon.gov.co',
                'phone' => '3114567890',
                'sort_order' => 4,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 12. Buzón de Mensajes y Solicitudes de Ciudadanos / Comuneros
        ContactMessage::query()->firstOrCreate(
            ['email' => 'carlos.mina@example.com', 'territory' => 'El Palmar'],
            [
                'name' => 'Carlos Alberto Mina',
                'email' => 'carlos.mina@example.com',
                'phone' => '3101234567',
                'territory' => 'El Palmar',
                'message' => 'Buenas tardes autoridades del Consejo, solicito orientación para expedir la constancia de pertenencia territorial requerida para un trámite de educación superior.',
                'status' => 'pendiente',
                'admin_notes' => 'Recibido en web. Se contactará para solicitar copia del documento de identidad.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)',
            ]
        );

        // 13. Documentos Institucionales y Repositorio Comunitario
        Storage::disk('public')->put('documents/estatuto-gobierno-propio-zanjon.pdf', "%PDF-1.4\n% Consejo Comunitario Zanjón de Garrapatero - Estatuto de Gobierno Propio\n");
        Storage::disk('public')->put('documents/plan-ambiental-microcuencas-garrapatero.pdf', "%PDF-1.4\n% Plan de Manejo Ambiental y Cuencas\n");
        Storage::disk('public')->put('documents/formato-censo-etnico-garrapatero.pdf', "%PDF-1.4\n% Formato Unico de Padrón y Censo Familiar\n");

        Document::query()->updateOrCreate(
            ['slug' => 'estatuto-gobierno-propio-y-reglamento-interno-territorial'],
            [
                'title' => 'Estatuto de Gobierno Propio y Reglamento Interno Territorial',
                'category' => 'Normatividad y Ley 70',
                'description' => 'Texto aprobado por la Asamblea General que establece las normas de convivencia, guardia cimarrona y cuidado del territorio colectivo.',
                'file_name' => 'estatuto-gobierno-propio-zanjon.pdf',
                'file_path' => 'documents/estatuto-gobierno-propio-zanjon.pdf',
                'file_size' => 2450000,
                'mime_type' => 'application/pdf',
                'download_count' => 14,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        Document::query()->updateOrCreate(
            ['slug' => 'plan-comunitario-de-manejo-ambiental-y-proteccion-de-microcuencas'],
            [
                'title' => 'Plan Comunitario de Manejo Ambiental y Protección de Microcuencas',
                'category' => 'Planes de Manejo Ambiental',
                'description' => 'Directrices colectivas para la reforestación de riberas, veda de tala en nacimientos y monitoreo del Río Garrapatero.',
                'file_name' => 'plan-ambiental-microcuencas-garrapatero.pdf',
                'file_path' => 'documents/plan-ambiental-microcuencas-garrapatero.pdf',
                'file_size' => 3820000,
                'mime_type' => 'application/pdf',
                'download_count' => 28,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        Document::query()->updateOrCreate(
            ['slug' => 'formato-unico-de-caracterizacion-de-nucleo-familiar-censo-etnico'],
            [
                'title' => 'Formato Único de Caracterización de Núcleo Familiar (Censo Étnico)',
                'category' => 'Formatos y Trámites',
                'description' => 'Formato oficial para inscripción en el libro de padrón del Consejo Comunitario de acuerdo a los requisitos de la Ley 70 de 1993.',
                'file_name' => 'formato-censo-etnico-garrapatero.pdf',
                'file_path' => 'documents/formato-censo-etnico-garrapatero.pdf',
                'file_size' => 850000,
                'mime_type' => 'application/pdf',
                'download_count' => 45,
                'is_visible' => true,
                'created_by' => $superAdmin->id,
            ]
        );

        // 14. Registros Iniciales de Auditoría y Acceso
        AuditLog::query()->firstOrCreate(
            ['action' => 'inicializacion_plataforma'],
            [
                'user_id' => $superAdmin->id,
                'user_name' => $superAdmin->name,
                'user_role' => $superAdmin->role->label(),
                'action' => 'inicializacion_plataforma',
                'entity_type' => 'System',
                'entity_id' => 1,
                'entity_title' => 'Portal Comunitario Garrapatero',
                'description' => 'Inicialización exitosa del sistema administrativo, auditoría y control de contenidos.',
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/130.0',
                'properties' => ['version' => '1.0.0', 'environment' => 'production'],
                'created_at' => now(),
            ]
        );

        LoginLog::query()->firstOrCreate(
            ['user_id' => $superAdmin->id, 'login_at' => now()->startOfHour()],
            [
                'user_name' => $superAdmin->name,
                'user_email' => $superAdmin->email,
                'role' => $superAdmin->role->label(),
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) Chrome/130.0',
                'login_at' => now()->startOfHour(),
            ]
        );
    }
}
