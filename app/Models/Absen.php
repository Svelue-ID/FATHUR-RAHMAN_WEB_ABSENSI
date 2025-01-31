<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absen extends Model
{
    use HasFactory;

    protected $table = 'absen';
    protected $fillable = [
        'id_siswa',
        'id_kelas',
        'tanggal', 
        'keterangan_hadir',
        'dokumentasi_kehadiran'
    ];

    /**
     * Get the user that owns the ListVilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function siswa(): BelongsTo
    {
        return $this->belongsTo(Siswa::class, 'id_siswa', 'id');
    }

    /**
     * Get the user that owns the ListVilla
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function kelas(): BelongsTo
    {
        return $this->belongsTo(kelas::class, 'id_kelas', 'id');
    }
}
