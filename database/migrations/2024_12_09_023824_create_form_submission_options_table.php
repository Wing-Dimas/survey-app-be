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
        Schema::create('form_submission_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('form_submission_id')->index('fk_form_submission_options_to_form_submissions');
            $table->string('key')->nullable();
            $table->string('value')->nullable();
            $table->integer('min')->nullable();
            $table->integer('max')->nullable();
            $table->boolean('required')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('form_submission_options');
    }
};
