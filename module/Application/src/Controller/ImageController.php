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
        $files = $this->imageManager->getSavedFiles();
        
        return new ViewModel([
            'files'=>$files
        ]);
    }
    
    public function uploadAction() 
    {
        $form = new ImageForm();
        
        if($this->getRequest()->isPost()) {
            
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            $form->setData($data);
            
            if($form->isValid()) {
                
                $data = $form->getData();
                
                return $this->redirect()->toRoute('images');
            }                        
        } 
        
        return new ViewModel([
                     'form' => $form
                 ]);
    }
        
    public function fileAction() 
    {        
    }    
}