<?php
/**
 * UploadController
 *
 * @package Upload\Controller
 */
namespace Upload\Controller;

use Upload\Controller\UploadAbstractRestfulController;
use Zend\View\Model\JsonModel;

use Google_Service_Drive_DriveFile as GoogleDriveService;

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

        $GoogleClientService = $this->getServiceLocator()->get('Upload\Service\GoogleClientService');
        $GoogleClientService->refreshToken();

        exit;


        $service = $GoogleClientService->setGoogleService();

        try {

            $fileMetadata = new GoogleDriveService(array(
                'name' => 'TestPhp',
                'mimeType' => 'application/vnd.google-apps.folder'));
            $folder = $service->files->create($fileMetadata, array(
                'fields' => 'id'));

            $fileMetadata = new GoogleDriveService(
                [
                    'name'    => $file['file']['name'],
                    'parents' => array($folder->id)
                ]
            );
            $content = file_get_contents($file['file']['tmp_name']);
            $file = $service->files->create($fileMetadata, [
                'data'       => $content,
                'mimeType'   => 'image/jpeg',
                'uploadType' => 'multipart',
                'fields'     => 'id'
            ]);

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