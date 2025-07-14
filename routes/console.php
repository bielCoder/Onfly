<?php

use App\Console\Commands\DeleteExpiredTokensCommand;
use App\Models\Token;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('tokens:clear', function () {
    $this->call('tokens:delete-expired');
})->purpose('Executa limpeza de tokens expirados manualmente')->everyFifteenMinutes();
// Artisan::command('tokens:clear', function () {
//       // Verifica se existem tokens expirados
//         $hasExpired = Token::where('confirmed', null)->exists();

//         if ($hasExpired) {
//             $deleted = Token::where('confirmed', null)->delete();
//             $this->info("$deleted tokens expirados removidos com sucesso.");
//         } else {
//             $this->info('Nenhum token expirado encontrado.');
//         }
//     $this->comment('Tokens expirados removidos com sucesso!');
// })->purpose('Executa limpeza de tokens expirados manualmente');
