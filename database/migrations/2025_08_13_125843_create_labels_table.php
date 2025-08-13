<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('labels', function (Blueprint $t) {
            $t->id();
            $t->foreignId('user_id')->constrained()->cascadeOnDelete();
            $t->foreignId('label_template_id')->constrained();
            $t->string('title')->default('Untitled Label');
            $t->json('data');      // editable text fields
            $t->json('theme');     // {green, amber, paper}
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('labels'); }
};