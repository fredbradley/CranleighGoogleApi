<?php


namespace fredbradley\CranleighGoogleApi\Commands;

use fredbradley\CranleighGoogleApi\CranleighGoogleApi;
use Illuminate\Console\Command;

class RemoveAndRefreshGoogleToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes the token.json and requests it again';

    /**
     * Execute the console command.
     *
     * @param CranleighGoogleApi $api
     * @return mixed
     */
    public function handle(CranleighGoogleApi $api)
    {
        $tokenFile = base_path('token.json');
        if (file_exists($tokenFile)) {
            unlink($tokenFile);
        } else {
            $this->error("The file didn't exist to start with!");
        }

        $this->comment((new $api));
    }
}
