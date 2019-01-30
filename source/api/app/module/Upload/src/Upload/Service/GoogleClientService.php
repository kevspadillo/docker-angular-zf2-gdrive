<?php
/**
 * GoogleClientService
 *
 * @package Upload\Service
 * @see https://developers.google.com/drive/api/v3/quickstart/php
 */

namespace Upload\Service;

use Google_Client as GoogleClient;
use Google_Service_Drive as GoogleServiceDrive;

class GoogleClientService 
{
    private $Client;

    public function __construct(
        GoogleClient $Client,
        $tokenPath
    ) {
        $this->Client = $Client;
        $this->tokenPath = $tokenPath;
    }

    public function setGoogleService()
    {
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);
            $this->Client->setAccessToken($accessToken);
        }

        // If there is no previous token or it's expired.
        if ($this->Client->isAccessTokenExpired()) {
            // Refresh the token if possible, else fetch a new one.
            if ($this->Client->getRefreshToken()) {
                $this->Client->fetchAccessTokenWithRefreshToken($this->Client->getRefreshToken());
            } else {
                // Request authorization from the user.
                $authUrl = $this->Client->createAuthUrl();
                printf("Open the following link in your browser:\n%s\n", $authUrl);
                print 'Enter verification code: ';
                $authCode = trim(fgets(STDIN));

                // Exchange authorization code for an access token.
                $accessToken = $this->Client->fetchAccessTokenWithAuthCode($authCode);
                $this->Client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($this->tokenPath))) {
                mkdir(dirname($this->tokenPath), 0700, true);
            }
            file_put_contents($this->tokenPath, json_encode($this->Client->getAccessToken()));
        }

        return new GoogleServiceDrive($this->Client);
    }

    public function refreshToken()
    {
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);

            // print_r($accessToken['refresh_token']); exit;
            $newAccessToken = $this->Client->refreshToken($accessToken['refresh_token']);
            print_r($newAccessToken);
        }
        
    }
}