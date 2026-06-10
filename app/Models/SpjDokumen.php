<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class SpjDokumen extends Model
{
    protected $fillable = ['spj_id', 'dokumen_pendukung_id', 'file_path', 'komentar_revisi'];
    
    public function spj() { return $this->belongsTo(Spj::class); }
    public function dokumenPendukung() { return $this->belongsTo(DokumenPendukung::class); }
}
