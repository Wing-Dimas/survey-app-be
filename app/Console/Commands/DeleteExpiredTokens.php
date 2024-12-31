<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tokens:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete expired survey tokens';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deleted = \App\Models\SurveyToken::where('expires_at', '<', now())->delete();
        $this->info("$deleted token kadaluarsa telah dihapus.");
    }
}
