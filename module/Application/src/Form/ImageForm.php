<?php
namespace Application\Form;

use Zend\Form\Form;
use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;

// From for uploading images
class ImageForm extends Form
{     
    public function __construct()
    {
        // Form name
        parent::__construct('image-form');

        // Set Post method
        $this->setAttribute('method', 'post');
                
        // Set enctype for file uploading
        $this->setAttribute('enctype', 'multipart/form-data');
		
        // Add elements to form		
        $this->addElements();

        //Apply validation rules and filters
        $this->addInputFilter();        
    }
    
    // Adds elements to form
    protected function addElements() 
    {
        // File field.
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
         
        // Submit button for upload 
        $this->add([
            'type'  => 'submit',
            'name' => 'submit',
            'attributes' => [                
                'value' => 'Upload',
                'id' => 'submitbutton',
            ],
        ]);               
    }

    private function addInputFilter() 
    {
        $inputFilter = new InputFilter();   
        $this->setInputFilter($inputFilter);
    
        // Validation rules and filters for file input
        $inputFilter->add([
                'type'     => FileInput::class,
                'name'     => 'file',
                'required' => true,   
                'validators' => [
                    ['name'    => 'FileUploadFile'],
                    [
                        'name'    => 'FileMimeType',                        
                        'options' => [                            
                            'mimeType'  => ['image/jpeg', 'image/png']
                        ]
                    ],
                    ['name'    => 'FileIsImage'],
                    [
                        'name'    => 'FileImageSize',
                        'options' => [
                            'minWidth'  => 128,
                            'minHeight' => 128,
                            'maxWidth'  => 4096,
                            'maxHeight' => 4096
                        ]
                    ],
                ],
                'filters'  => [                    
                    [
                        'name' => 'FileRenameUpload',
                        'options' => [  
                            'target'=>'./data/upload',
                            'useUploadName'=>true,
                            'useUploadExtension'=>true,
                            'overwrite'=>true,
                            'randomize'=>false
                        ]
                    ]
                ],   
        ]);                
    }
}