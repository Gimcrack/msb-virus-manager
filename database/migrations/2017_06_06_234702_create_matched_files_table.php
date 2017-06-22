<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchedFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('matched_files', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('client_id');
            $table->unsignedInteger('pattern_id');
            $table->string('file',255);
            $table->integer('times_matched')->default(1);
            $table->boolean('muted_flag')->default(false);
            $table->boolean('acknowledged_flag')->default(false);
            $table->timestamps();

            $table->unique(['client_id','pattern_id','file']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('matched_files');
    }
}
