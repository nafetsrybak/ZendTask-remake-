<?php
namespace Application\Form;

use Zend\Form\Form;

class ImageForm extends Form
{     
    public function __construct()
    {
        parent::__construct('image-form');

        $this->setAttribute('method', 'post');
                
        $this->setAttribute('enctype', 'multipart/form-data');
				
        $this->addElements();        
    }
    
    protected function addElements() 
    {
        $this->add([
            'type'  => 'file',
            'name' => 'file',
            'attributes' => [                
                'id' => 'file'
            ],
            'options' => [
                'label' => 'Image file',
            ],
        ]);        
          
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Upload',
                'id' => 'submitbutton',
            ],
        ]);               
    }
}