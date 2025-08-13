<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('label_assets', function (Blueprint $t) {
            $t->id();
            $t->foreignId('label_id')->constrained()->cascadeOnDelete();
            $t->unsignedTinyInteger('slot'); // 1..4
            $t->string('path');              // storage path
            $t->timestamps();
            $t->unique(['label_id','slot']);
        });
    }
    public function down(): void { Schema::dropIfExists('label_assets'); }
};