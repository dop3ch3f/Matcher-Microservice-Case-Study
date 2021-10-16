<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SetupAllNeededTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('address');
            $table->string('property_type');
            $table->json('fields');
            $table->timestamps();
        });

        Schema::create('search_profile', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('property_type');
            $table->json('search_fields');
            $table->json('return_potential');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
