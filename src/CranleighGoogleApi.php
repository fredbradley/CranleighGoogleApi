<?php

namespace fredbradley\CranleighGoogleApi;

use fredbradley\CranleighGoogleApi\Maps\GoogleActivity;

class CranleighGoogleApi
{
    protected $client;
    protected $drive;

    public function getLatestActivity(string $email, string $application) {
        $results = $this->reports->activities->listActivities($email, $application);

        $collection = collect($results);
        $return = $collection->mapInto(GoogleActivity::class);
     return $return;
    }
    // Build wonderful things
    public function test($yearGroup, $house, $forename, $surname)
    {
        $userKey = 'all';
        $applicationName = 'login';

        $results = $this->reports->activities->listActivities($userKey, $applicationName);
        dd($results->getItems());
        $files = $this->drive->files->listFiles([
            'spaces' => 'drive',
            'q' => (sprintf('name contains "%s %s %s %s"', $yearGroup, $house, $forename, $surname)),
        ]);
        dd($files);
    }

    public function setup()
    {
        $thing = $this->reports;
    }

    private function getClient($scopes = [])
    {

        $client = new \Google_Client();
        $client->setApplicationName('Reports API PHP Quickstart');
        $client->setScopes($scopes);
        $client->setAuthConfig(base_path() . '/' . config('cranleighgoogleapi.credentials_json_file'));

        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = base_path('token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(fopen("php://stdin", "r")));


                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (! file_exists(dirname($tokenPath))) {
                mkdir(dirname($tokenPath), 0700, true);
            }
            file_put_contents($tokenPath, json_encode($client->getAccessToken()));
        }

        return $client;
    }

    public function __construct()
    {
        $scopes = [
            \Google_Service_Drive::DRIVE,
            \Google_Service_Reports::ADMIN_REPORTS_USAGE_READONLY,
            \Google_Service_Reports::ADMIN_REPORTS_AUDIT_READONLY,
        ];
        $this->client = $this->getClient($scopes);
        $this->reports = new \Google_Service_Reports($this->client);
    }

}
