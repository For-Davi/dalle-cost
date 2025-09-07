<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
         Schema::dropIfExists('finances');
    }

    public function down(): void
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();
            $table->string('period'); //   MM/YY
            $table->decimal('value', 15, 2)->default(0);
            $table->timestamps();
        });
    }
};
