<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gym_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gym_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });

        Schema::create('coach_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('coach_id')->constrained()->onDelete('cascade');
            $table->string('image_path');
            $table->string('alt_text')->nullable();
            $table->timestamps();
        });

        Schema::table('gyms', function (Blueprint $table) {
            $table->json('metadata')->nullable();
        });

        Schema::table('coaches', function (Blueprint $table) {
            $table->json('metadata')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('gym_images');
        Schema::dropIfExists('coach_images');
        Schema::table('gyms', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
        Schema::table('coaches', function (Blueprint $table) {
            $table->dropColumn('metadata');
        });
    }
};
