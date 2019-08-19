<?php
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\ImageForm;

// Controller to controle image uploads
class ImageController extends AbstractActionController 
{
    // image manager
    private $imageManager;
  
    // get the image manager
    public function __construct($imageManager)
    {
        $this->imageManager = $imageManager;
    }
  
    //index action
    public function indexAction() 
    {
        // get all saved files
        $files = $this->imageManager->getSavedFiles();
        
        // return view with files
        return new ViewModel([
            'files'=>$files
        ]);
    }
    
    //show upload form
    public function uploadAction() 
    {
        // Create Image Form.
        $form = new ImageForm();
        
        // Check if method is POST
        if($this->getRequest()->isPost()) {
            
            // Merge Files and POST data
            $request = $this->getRequest();
            $data = array_merge_recursive(
                $request->getPost()->toArray(),
                $request->getFiles()->toArray()
            );
            
            // Fill form data.
            $form->setData($data);
            
            // Just validate all.
            if($form->isValid()) {
                
                // save data
                $data = $form->getData();
                
                // redirect to "Image Gallery".
                return $this->redirect()->toRoute('images');
            }                        
        } 
        
        // If not POST show form
        return new ViewModel([
                     'form' => $form
                 ]);
    }
    
    //action for image views and image thumbs    
    public function fileAction() 
    {
        // get file name from get request
        $fileName = $this->params()->fromQuery('name', '');

        //check what to show thumb or full
        $isThumbnail = (bool)$this->params()->fromQuery('thumbnail', false);
    
        //get file path
        $fileName = $this->imageManager->getImagePathByName($fileName);
        
        if($isThumbnail) {
            // change image size
            $fileName = $this->imageManager->resizeImage($fileName);
        }
        
        //get info about file        
        $fileInfo = $this->imageManager->getImageFileInfo($fileName);        
        if ($fileInfo===false) {
            // if error do 404
            $this->getResponse()->setStatusCode(404);            
            return;
        }
        

        // fill response headers        
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine("Content-type: " . $fileInfo['type']);        
        $headers->addHeaderLine("Content-length: " . $fileInfo['size']);
        
        //fill the content with file content    
        $fileContent = $this->imageManager->getImageFileContent($fileName);
        if($fileContent!==false) {                
            $response->setContent($fileContent);
        } else {
            // if error do Boo-Boo        
            $this->getResponse()->setStatusCode(500);
            return;
        }
        
        if($isThumbnail) {
            // delete tmp tumb file
            unlink($fileName);
        }
        
        //return response
        return $this->getResponse();
    }

    public function deleteAction() 
    {
        // Get the file name from GET variable
        $fileName = $this->params()->fromQuery('delete', '');

        // Validate input parameters
        if (empty($fileName) || strlen($fileName)>128) {
            throw new \Exception('File name is empty or too long');
        }

        $fileName = $this->imageManager->getImagePathByName($fileName);

        if(!file_exists($fileName)){
            $this->getResponse()->setStatusCode(404);            
            return;
        }

        unlink($fileName);

        return $this->redirect()->toRoute('images');
    }     
}