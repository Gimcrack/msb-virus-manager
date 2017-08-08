<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MatchedFilesAddFileCreatedAtColumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('matched_files', function (Blueprint $table) {
            $table->datetime('file_created_at')->nullable();
            $table->datetime('file_modified_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('matched_files', function (Blueprint $table) {
            //if( Schema::hasColumn('matched_files','file_created_at') ) $table->dropColumn('file_created_at');
            
            //if( Schema::hasColumn('matched_files','file_modified_at') ) $table->dropColumn('file_modified_at');
        });
    }
}
