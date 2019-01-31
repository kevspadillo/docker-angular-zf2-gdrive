<?php
/**
 * Composer Script class to request access token to Google API
 *
 * @author Kevin A. Padilla <kevin.padilla0717@gmail.com>
 * @example composer run-script generate-google-token
 */

namespace Api;

use Composer\Script\Event;
use Google_Client;

require './vendor/autoload.php';

class GenerateGoogleToken
{
    public static function generate(Event $event) 
    {
        // Locate config file for Google CLient necessary parameters
        $config = include __DIR__ . '/google-api.php';

        $client = new Google_Client();
        $client->setApplicationName('Google Drive API PHP');
        $client->setScopes($config['google_drive']['scope']);
        $client->setAuthConfig($config['google_drive']['credentials']);
        $client->setAccessType('offline');

        // If token already exists, set as active access token
        if (file_exists($config['google_drive']['token'])) {
            $accessToken = json_decode(file_get_contents($config['google_drive']['token']), true);
            if (!empty($accessToken)) {
                $client->setAccessToken($accessToken);
            }
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

                $io = $event->getIO();
                $authCode = $io->ask('Enter verification code: ');

                // Exchange authorization code for an access token.
                $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
                $client->setAccessToken($accessToken);

                // Check to see if there was an error.
                if (array_key_exists('error', $accessToken)) {
                    throw new Exception(join(', ', $accessToken));
                }
            }
            // Save the token to a file.
            if (!file_exists(dirname($config['google_drive']['token']))) {
                mkdir(dirname($config['google_drive']['token']), 0700, true);
            }
            file_put_contents($config['google_drive']['token'], json_encode($client->getAccessToken()));
        }

        printf("Google Auth Token Generated");
        // exit composer and terminate installation process
        exit;
    }
}