<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JenisSpj extends Model
{
    protected $fillable = ['nama_jenis'];
    public function dokumenPendukungs() { return $this->hasMany(DokumenPendukung::class); }
    public function spjs() { return $this->hasMany(Spj::class); }
}
