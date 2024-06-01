<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('events_table', function (Blueprint $table) {
            $table->id();
            $table->string('cast_name');
            $table->string('main_cast_name');
            $table->enum('is_translated', ['yes', 'no']);
            $table->enum('type_of_control', ['Music', 'Movie']);
            $table->string('channel_name');
            $table->integer('duration');
            $table->date('upload_date');
            $table->date('play_date');
            $table->date('end_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
     public function down()
    {
        Schema::dropIfExists('events_table');
    }
};
