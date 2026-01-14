<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Atributi koji se mogu masovno dodeljivati.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
    ];

    /**
     * Atributi koji treba da budu sakriveni pri serijalizaciji.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Kastovanje atributa.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Proverava da li je korisnik administrator.
     */
    public function isAdmin(): bool
    {
        return $this->role === 'administrator';
    }

    /**
     * Proverava da li je korisnik inženjer.
     */
    public function isEngineer(): bool
    {
        return $this->role === 'engineer';
    }

    /**
     * Proverava da li je korisnik klijent.
     */
    public function isClient(): bool
    {
        return $this->role === 'client';
    }

    /**
     * Projekti kojima korisnik ima pristup (preko pivot tabele).
     */
    public function projects(): BelongsToMany
    {
        return $this->belongsToMany(Project::class, 'user_projects');
    }

    /**
     * Prijave grešaka koje je korisnik kreirao.
     */
    public function createdSupportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class, 'created_by');
    }

    /**
     * Prijave grešaka koje su dodeljene korisniku (inženjeru).
     */
    public function assignedSupportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class, 'assigned_to');
    }
}
