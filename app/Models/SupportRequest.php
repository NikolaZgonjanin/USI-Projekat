<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupportRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'firmware_version_id',
        'created_by',
        'assigned_to',
        'title',
        'status',
        'request_text',
        'steps_to_reproduce',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'firmware_version_id' => 'integer',
            'created_by' => 'integer',
            'assigned_to' => 'integer',
            'created_at' => 'timestamp',
            'updated_at' => 'timestamp',
        ];
    }

    public function firmwareVersion(): BelongsTo
    {
        return $this->belongsTo(FirmwareVersion::class);
    }

    /**
     * Korisnik koji je kreirao prijavu.
     * Ovu relaciju koristimo pod imenom createdBy u kontroleru i view-ovima.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Inženjer dodeljen na prijavu (može biti null).
     * Ovu relaciju koristimo pod imenom assignedTo u kontroleru i view-ovima.
     */
    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
