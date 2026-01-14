<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Documentation extends Model
{
    use HasFactory;

    protected $fillable = [
        'firmware_version_id',
        'title',
        'file_path',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'firmware_version_id' => 'integer',
        ];
    }

    public function firmwareVersion(): BelongsTo
    {
        return $this->belongsTo(FirmwareVersion::class);
    }
}
