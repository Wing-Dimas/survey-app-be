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
        Schema::table('survey_tokens', function (Blueprint $table) {
            $table->foreign('api_key_id', 'fk_survey_tokens_to_api_keys')
                  ->references('id')
                  ->on('api_keys')
                  ->onUpdate('CASCADE')
                  ->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_tokens', function (Blueprint $table) {
            $table->dropForeign('fk_survey_tokens_to_api_keys');
        });
    }
};
