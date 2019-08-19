<?php
namespace Application\Service;

class ImageManager 
{
    private $saveToDir = './data/upload/';
        
    
    public function getSaveToDir() 
    {
        return $this->saveToDir;
    }

    public function getSavedFiles() 
    {
        if(!is_dir($this->saveToDir)) {
            if(!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: ' . 
                             error_get_last());
            }
        }
        
        $files = [];        
        $handle  = opendir($this->saveToDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue;
            
            $files[] = $entry;
        }
        
        return $files;
    } 
}