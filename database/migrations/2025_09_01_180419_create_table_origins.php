<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('origins', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('payday');
            $table->unsignedBigInteger('member_id')->nullable();
            $table->foreign('member_id')->references('id')->on('members');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('origins');
    }
};
