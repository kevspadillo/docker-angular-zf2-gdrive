<?php
/**
 * IndexController
 *
 * @package Upload\Controller
 */
namespace Upload\Controller;

use Upload\Controller\UploadAbstractRestfulController;
use Zend\View\Model\JsonModel;

class IndexController extends UploadAbstractRestfulController
{
    /**
     * Somethig to enjoy with
     * 
     * @return JsonModel
     */
    public function getList()
    {
        return new JsonModel(['message' => "Hoooraay! It Works!"]);
    }
}