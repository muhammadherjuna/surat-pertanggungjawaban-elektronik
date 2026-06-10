<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class DokumenPendukung extends Model
{
    protected $fillable = ['jenis_spj_id', 'nama_dokumen', 'is_wajib'];
    protected $casts = ['is_wajib' => 'boolean'];
    public function jenisSpj() { return $this->belongsTo(JenisSpj::class); }
    public function spjDokumens() { return $this->hasMany(SpjDokumen::class); }
}
