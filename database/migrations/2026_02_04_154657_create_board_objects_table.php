<?php

use App\Models\Board;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('board_objects', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Board::class)->constrained()->onDelete('cascade');
            $table->foreignIdFor(User::class, 'owner_id')->constrained()->onDelete('cascade');
            $table->string('type');
            $table->text('content');
            $table->integer('x');
            $table->integer('y');
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->decimal('rotation', 5)->default(0);
            $table->integer('z_index')->default(0);
            $table->foreignIdFor(User::class, 'focused_by')->nullable()->constrained();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('board_objects');
    }
};
