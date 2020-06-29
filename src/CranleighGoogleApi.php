<?php

namespace fredbradley\CranleighGoogleApi;

use fredbradley\CranleighGoogleApi\Traits\Reports;

/**
 * Class CranleighGoogleApi.
 */
class CranleighGoogleApi
{
    use Reports;

    /**
     * @var \Google_Client
     */
    protected $client;
    /**
     * @var \Google_Service_Drive
     */
    public $drive;
    /**
     * @var \Google_Service_Reports
     */
    public $reports;

    /**
     * @var array
     */
    private $scopes = [
        \Google_Service_Drive::DRIVE,
        \Google_Service_Reports::ADMIN_REPORTS_USAGE_READONLY,
        \Google_Service_Reports::ADMIN_REPORTS_AUDIT_READONLY,
    ];

    /**
     * CranleighGoogleApi constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->client = $this->getClient($this->scopes);

        $this->reports = new \Google_Service_Reports($this->client);

        $this->drive = new \Google_Service_Drive($this->client);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return 'Scoped: '.implode(', ', $this->scopes);
    }

    /**
     * @param array $scopes
     * @return \Google_Client
     * @throws \Google_Exception
     */
    private function getClient($scopes = [])
    {
        $client = new \Google_Client();
        $client->setApplicationName('Reports API PHP Quickstart');
        $client->setScopes($scopes);
        $client->setAuthConfig(base_path().'/'.config('cranleighgoogleapi.credentials_json_file'));

        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        // Load previously authorized token from a file, if it exists.
        // The file token.json stores the user's access and refresh tokens, and is
        // created automatically when the authorization flow completes for the first
        // time.
        $tokenPath = base_path('cranleigh-google-api-token.json');
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
                echo 'Enter verification code: ';
                $authCode = trim(fgets(fopen('php://stdin', 'r')));

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new \Exception(implode(', ', $accessToken));
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
}
