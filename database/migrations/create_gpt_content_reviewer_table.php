<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('gpt_content_reviewer_table', function (Blueprint $table) {
            $table->id();
            $table->string('content');

            $table->string('review');
            $table->string('status');
            $table->string('confidence');
            $table->string('reviewed_at');

            // add fields

            $table->timestamps();
        });
    }
};
