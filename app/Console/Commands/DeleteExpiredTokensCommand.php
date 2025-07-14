<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Token;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;

class DeleteExpiredTokensCommand extends Command
{
    protected $signature = 'tokens:delete-expired';
    protected $description = 'Remove tokens expirados do sistema';

    public function handle()
    {
        $hasExpired = Token::where('confirmed', null)->exists();

        if ($hasExpired) {
            $deleted = Token::where('confirmed', null)->delete();
            $this->info("$deleted tokens expirados removidos com sucesso.");
        } else {
            $this->info('Nenhum token expirado encontrado.');
        }
    }
}
