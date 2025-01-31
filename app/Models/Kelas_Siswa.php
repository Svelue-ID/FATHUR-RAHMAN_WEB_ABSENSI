<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas_Siswa extends Model
{
    use HasFactory;

    protected $table = 'kelas_siswa';
    protected $fillable = [
        'id_siswa',
        'id_kelas'
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
