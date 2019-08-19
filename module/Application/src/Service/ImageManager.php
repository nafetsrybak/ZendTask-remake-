<?php
namespace Application\Service;

class ImageManager 
{
    private $saveToDir = './data/upload/';
        
    
    public function getSaveToDir() 
    {
        return $this->saveToDir;
    }  
}