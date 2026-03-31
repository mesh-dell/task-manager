<?php

use App\Enums\Priority;
use App\Enums\Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->date('due_date');
            $table->enum('priority', array_column(Priority::cases(), 'value'));
            $table->enum('status', array_column(Status::cases(), 'value'))->default(Status::PENDING->value);
            $table->timestamps();
            $table->unique(['title', 'due_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
