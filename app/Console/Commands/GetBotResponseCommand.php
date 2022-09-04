<?php

namespace App\Console\Commands;
use App\TelegramBot\TelegramBot;

use Illuminate\Console\Command;

class GetBotResponseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'response:bot';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Receive Telegram bot updates and make response';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        new TelegramBot('getUpdates');
    }
}
