<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('label_templates', function (Blueprint $t) {
            $t->id();
            $t->string('name');           // e.g. "13x14 cm (Portrait)"
            $t->string('slug')->unique(); // '13x14', '14_5x10'
            $t->decimal('width_cm', 5, 2);
            $t->decimal('height_cm', 5, 2);
            $t->enum('orientation', ['portrait','landscape']);
            $t->json('defaults');         // default data JSON + theme
            $t->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('label_templates'); }
};
