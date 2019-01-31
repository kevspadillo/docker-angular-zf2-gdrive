<?php
/**
 * Uplaod Module
 *
 * @author Kevin A. Padilla <kevin.padilla0717@gmail.com>
 */

namespace Upload;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

use Upload\Service\GoogleClientService;
use Upload\Filter\UploadFilter;

use Google_Client as GoogleClient;
use Google_Service_Drive as GoogleServiceDrive;
use Google_Service_Drive_DriveFile as GoogleDriveFileService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager        = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'), 0);
    }

    public function onDispatchError($e)
    {
        return $this->getJsonModelError($e);
    }

    /**
     * Prepare JSON Model object for error reporting
     * 
     * @param  $e
     * @return JsonModel
     */
    public function getJsonModelError($e)
    {
        $error = $e->getError();
        if (!$error) {
            return;
        }

        $errorJson = array(
            'message'   => 'An error occurred during execution; please try again later.',
            'error'     => $error,
            'exception' => $exceptionJson,
        );

        $model = new JsonModel(array('errors' => array($errorJson)));

        $e->setResult($model);

        return $model;
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'Upload\Service\GoogleClientService' => function($sm) {

                    $config = $sm->get('Config');

                    $drive = new GoogleDriveFileService();
                    $client = new GoogleClient();
                    $client->setAuthConfig($config['google_drive']['credentials']);
                    $client->addScope($config['google_drive']['scope']);

                    return new GoogleClientService($client, $config['google_drive']['token']);
                },

                'Upload\Filter\UploadFilter' => function() {
                    return new UploadFilter();
                },
            ]
        ];
    }
}