<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
        ];
    }

    /**
     * Verzije firmvera koje pripadaju projektu.
     */
    public function firmwareVersions(): HasMany
    {
        return $this->hasMany(FirmwareVersion::class);
    }

    /**
     * Korisnici koji imaju pristup projektu (preko pivot tabele).
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_projects');
    }
}
