<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Rekening extends Model
{
    protected $fillable = ['kode_rekening', 'nama_rekening'];
    public function spjs() { return $this->hasMany(Spj::class); }
}
