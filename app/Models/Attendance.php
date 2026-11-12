<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    // Relasi absensi dengan anggota
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi absensi dengan kegiatan
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }
}
