<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabelAsset extends Model {
    protected $fillable = ['label_id','slot','path'];
    public function label(){ return $this->belongsTo(Label::class); }
}