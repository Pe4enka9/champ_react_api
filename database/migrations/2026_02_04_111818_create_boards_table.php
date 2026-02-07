<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('boards', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignIdFor(User::class, 'owner_id');
            $table->string('hash')->unique()->nullable();
            $table->boolean('is_public')->default(false);
            $table->unsignedInteger('width')->default(1600);
            $table->unsignedInteger('height')->default(900);
            $table->json('objects')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('boards');
    }
};
