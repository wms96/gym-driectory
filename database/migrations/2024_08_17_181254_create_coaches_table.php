<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('coaches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('specialization')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('coaches');
    }
};
