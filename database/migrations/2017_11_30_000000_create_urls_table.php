<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUrlsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('art_urls', function (Blueprint $table) {
        	
            $table->uuid('id');
            $table->primary('id');
            
            $table->string('code');
            $table->string('url');
            
            $table->string('reference')->nullable();
            $table->text('meta')->nullable();
            
            $table->timestamps();
            
            $table->unique('code');
            $table->index('url');
            $table->index('reference');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
