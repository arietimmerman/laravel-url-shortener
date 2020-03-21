<?php

namespace ArieTimmerman\Laravel\URLShortener\Console;

use ArieTimmerman\Laravel\URLShortener\URLShortener;
use Illuminate\Console\Command;

class URLShortenerCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'url:shorten {url}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Shortens an url';

    /**
     * Execute the console command.
     *
     * @param  \Laravel\Passport\ClientRepository  $clients
     * @return void
     */
    public function handle()
    {
        $url = URLShortener::shorten($this->argument('url'));

        $this->line(sprintf("Shortened to: %s", (string) $url));
    }
}
