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
        //
        schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->unSignedBigInteger('user_id');
            $table->string('role');
            $table->string('action')->comment('The action that was performed .i.e GET, POST, PATCH, DELETE');
            $table->string('path');
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnUpdate()->restictOnDelete();
            // Define composite foreign key on 'role' referencing 'id' and 'group' in 'users'
            $table->foreign('role')->references('role')->on('users')->cascadeOnUpdate()->restictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::dropIfExists('audit_logs');
    }
};
