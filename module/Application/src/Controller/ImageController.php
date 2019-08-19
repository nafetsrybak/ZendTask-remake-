<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ImageForm;

class ImageController extends AbstractActionController 
{
    private $imageManager;
  
    public function __construct($imageManager)
    {
        $this->imageManager = $imageManager;
    }
  
    public function indexAction() 
    {                
    }
    
    public function uploadAction() 
    {
    }
        
    public function fileAction() 
    {        
    }    
}