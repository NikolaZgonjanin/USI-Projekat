<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FirmwareVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'project_id',
        'version',
        'is_stable',
        'changelog',
        'file_path',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'project_id' => 'integer',
            'is_stable' => 'boolean',
            'released_at' => 'timestamp',
        ];
    }

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    /**
     * Dokumentacija vezana za ovu verziju firmvera.
     */
    public function documentations(): HasMany
    {
        return $this->hasMany(Documentation::class);
    }

    /**
     * Prijave grešaka za ovu verziju firmvera.
     */
    public function supportRequests(): HasMany
    {
        return $this->hasMany(SupportRequest::class);
    }
}
