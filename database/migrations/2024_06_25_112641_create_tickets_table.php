<?php

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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_name');
            $table->enum('priority', ['imprtant','normal'])->default('normal');
            $table->unsignedBigInteger('user_id');
            $table->text('description');
            $table->enum('status',['open','running','answered','closed'])->default('open');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('deleted_at');
        });
    }
};
