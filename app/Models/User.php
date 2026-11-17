<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\UserRole;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['name', 'email', 'password', 'role', 'document_number', 'phone', 'vereda'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    /**
     * Surveys created by this user (if Admin or SuperAdmin).
     *
     * @return HasMany<Survey, $this>
     */
    public function surveys(): HasMany
    {
        return $this->hasMany(Survey::class, 'created_by');
    }

    /**
     * Survey responses submitted by this user.
     *
     * @return HasMany<SurveyResponse, $this>
     */
    public function surveyResponses(): HasMany
    {
        return $this->hasMany(SurveyResponse::class, 'user_id');
    }

    /**
     * Check if user is Super Admin.
     */
    public function isSuperAdmin(): bool
    {
        return $this->role === UserRole::SuperAdmin;
    }

    /**
     * Check if user is Survey Admin.
     */
    public function isAdmin(): bool
    {
        return $this->role === UserRole::Admin;
    }

    /**
     * Check if user is standard community member.
     */
    public function isUsuario(): bool
    {
        return $this->role === UserRole::Usuario;
    }

    /**
     * Check if user matches any of the given roles.
     */
    public function hasRole(UserRole|string ...$roles): bool
    {
        $currentRoleValue = $this->role instanceof UserRole ? $this->role->value : (string) $this->role;

        foreach ($roles as $role) {
            $expectedRoleValue = $role instanceof UserRole ? $role->value : (string) $role;
            if ($currentRoleValue === $expectedRoleValue) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has already answered a specific survey.
     */
    public function hasAnsweredSurvey(Survey|int $survey): bool
    {
        $surveyId = $survey instanceof Survey ? $survey->id : $survey;

        return $this->surveyResponses()->where('survey_id', $surveyId)->exists();
    }
}
