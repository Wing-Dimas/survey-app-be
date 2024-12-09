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
        Schema::table('form_submission_options', function (Blueprint $table) {
            $table->foreign('form_submission_id','fk_form_submission_options_to_form_submissions')
                  ->references('id')
                  ->on('form_submissions')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('form_submission_options', function (Blueprint $table) {
            $table->dropForeign('fk_form_submission_options_to_form_submissions');
        });
    }
};
