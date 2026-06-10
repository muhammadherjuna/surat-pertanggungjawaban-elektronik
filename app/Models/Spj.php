<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Spj extends Model
{
    protected $fillable = ['uuid', 'user_id', 'jenis_spj_id', 'deskripsi', 'filter_tipe', 'filter_no', 'nominal', 'rekening_id', 'status_level', 'is_rejected'];
    protected $casts = ['is_rejected' => 'boolean'];
    
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function user() { return $this->belongsTo(User::class); }
    public function jenisSpj() { return $this->belongsTo(JenisSpj::class); }
    public function rekening() { return $this->belongsTo(Rekening::class); }
    public function dokumens() { return $this->hasMany(SpjDokumen::class); }
}
