<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Label extends Model {
    protected $fillable = ['user_id','label_template_id','title','data','theme'];
    protected $casts = [ 'data' => 'array', 'theme' => 'array' ];
    public function template(){ return $this->belongsTo(LabelTemplate::class,'label_template_id'); }
    public function assets(){ return $this->hasMany(LabelAsset::class); }
    public function badgeSrc(int $slot): ?string {
        $a = $this->assets->firstWhere('slot',$slot); return $a? Storage::url($a->path) : null;
    }
}
