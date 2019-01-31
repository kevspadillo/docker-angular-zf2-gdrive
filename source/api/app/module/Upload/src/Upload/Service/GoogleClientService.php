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
use Google_Service_Drive_DriveFile as GoogleServiceDriveFile;

class GoogleClientService 
{
    private $Client;
    private $ServiceDrive;
    private $ServiceDriveFile;

    private $mimeTypeFolder = 'application/vnd.google-apps.folder';
    private $mimeTypePdf    = 'application/pdf';

    /**
     * @param GoogleClient           $Client
     * @param GoogleServiceDriveFile $ServiceDriveFile
     * @param string                 $tokenPath
     */
    public function __construct(
        GoogleClient $Client,
        GoogleServiceDriveFile $ServiceDriveFile,
        $tokenPath
    ) {
        $this->Client           = $Client;
        $this->tokenPath        = $tokenPath;
        $this->setGoogleService();
    }

    private function resetDrive(array $params = [])
    {
        $this->ServiceDriveFile = new GoogleServiceDriveFile($params);
    }

    /**
     * Set google service 
     *
     * @return void
     */
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

        $this->ServiceDrive = new GoogleServiceDrive($this->Client);
    }

    /**
     * Refresh token
     * 
     * @return void
     */
    public function refreshToken()
    {
        if (file_exists($this->tokenPath)) {
            $accessToken = json_decode(file_get_contents($this->tokenPath), true);

            // print_r($accessToken['refresh_token']); exit;
            $newAccessToken = $this->Client->refreshToken($accessToken['refresh_token']);
            print_r($newAccessToken);
        }
    }

    /**
     * Checks if file exists
     * 
     * @param  $filename
     * @param  $parentFileId
     * @return boolean
     */
    public function folderExists($filename, string $parentFileId = null)
    {
        $this->resetDrive();

        $query = sprintf("mimeType='application/vnd.google-apps.folder' and name='%s'", $filename) ;

        if (!empty($parentFileId)) {
            $query .= sprintf(" and '%s' in parents", $parentFileId);
        }

        $optParams = array(
            'q'        => $query,
            'mimeType' => $this->mimeTypeFolder,
        );

        $results = $this->ServiceDrive->files->listFiles($optParams);
        
        $files = $results->getFiles();

        return (count($files)) ? $files[0]->id : 0;
    }

    /**
     * Create folder
     * 
     * @param  string $foldername
     * @param  array  $parenFolders
     * @return string
     */
    public function createFolder($foldername, array $parenFolders = [])
    {
        $this->resetDrive();

        $this->ServiceDriveFile->setName($foldername);
        $this->ServiceDriveFile->setMimeType($this->mimeTypeFolder);

        if (count($parenFolders)) {
            $this->ServiceDriveFile->setParents($parenFolders);
        }

        $folder = $this->ServiceDrive->files->create($this->ServiceDriveFile, array('fields' => 'id'));

        return $folder->id;
    }

    /**
     * Upload file
     * 
     * @param  array $fileData
     * @param  array $parentFolderIds
     * @return boolean
     */
    public function createFile($fileData, array $parentFolderIds = [])
    {
        $this->resetDrive();

        $this->ServiceDriveFile->setName($fileData['name']);
        $this->ServiceDriveFile->setParents($parentFolderIds);

        $content = file_get_contents($fileData['tmp_name']);
        $file = $this->ServiceDrive->files->create($this->ServiceDriveFile, [
            'data'       => $content,
            'mimeType'   => 'application/pdf',
            'uploadType' => 'multipart',
            'fields'     => 'id'
        ]);

        return ($file ? true : false);
    }
}