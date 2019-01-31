<?php
/**
 * UploadController
 *
 * @package Upload\Controller
 */
namespace Upload\Controller;

use Upload\Controller\UploadAbstractRestfulController;
use Zend\View\Model\JsonModel;

use Google_Service_Drive_DriveFile as GoogleDriveFileService;
use Google_Service_Drive as GoogleDriveService;

class UploadController extends UploadAbstractRestfulController
{
    /**
     * Api to upload files
     * 
     * @param  $uploadData
     * @return JsonModel
     */
    public function create($uploadData)
    {   
        $request = $this->getRequest();
        $file = $request->getFiles()->toArray();

        // Add Filter

        $GoogleClientService = $this->getServiceLocator()->get('Upload\Service\GoogleClientService');
        
        // Add refresh token as middleware
        // $GoogleClientService->refreshToken();

        $GoogleClientService->setGoogleService();

        try {

            // Check for parent folder and get folder id, 
            $parentFileId = $GoogleClientService->folderExists('TestPhp');
            if (!$parentFileId) {
                // if folder does not exists create
                $parentFileId = $GoogleClientService->createFolder('TestPhp');
            }

            // Check for user folder and get user folder id
            $userFolderId = $GoogleClientService->folderExists($uploadData['username'], $parentFileId);
            if (!$userFolderId) {
                // if folder does not exists create
                $userFolderId = $GoogleClientService->createFolder($uploadData['username'], [$parentFileId]);
            }

            // Upload file under the user folder id
            $GoogleClientService->createFile($file['file'], [$userFolderId]);

            return new JsonModel(['message' => 'File Successfully Uploaded']);

        } catch (\Exception $e) {

            $this->response->setStatusCode(500);
            return new JsonModel([
                'error'   => 'Oops! Something went wrong',
                'message' => $e->getMessage()
            ]);
        }
    }
}