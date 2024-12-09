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
        Schema::table('response_answers', function (Blueprint $table) {
            $table->foreign('response_id', 'fk_response_answers_to_responses')
                  ->references('id')
                  ->on('responses')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');

            $table->foreign('form_submission_id', 'fk_response_answers_to_form_submissions')
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
        Schema::table('response_answers', function (Blueprint $table) {
            $table->dropForeign('fk_response_answers_to_responses');
            $table->dropForeign('fk_response_answers_to_form_submissions');
        });
    }
};
