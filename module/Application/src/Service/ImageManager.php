<?php
namespace Application\Service;

// Service for dealing whith images
class ImageManager 
{
	// File path for uploaded images
    private $saveToDir = './data/upload/';
        
    // Returns root dir for uploaded images
    public function getSaveToDir() 
    {
        return $this->saveToDir;
    }

    public function getSavedFiles() 
    {
    	// Check if dir exists
        // If no create
        if(!is_dir($this->saveToDir)) {
            if(!mkdir($this->saveToDir)) {
                throw new \Exception('Could not create directory for uploads: ' . 
                             error_get_last());
            }
        }
        
        // Read the save path, fill array with names and skip '.' '..'
        $files = [];        
        $handle  = opendir($this->saveToDir);
        while (false !== ($entry = readdir($handle))) {
            
            if($entry=='.' || $entry=='..')
                continue; // Skip '.' '..'
            
            $files[] = $entry;
        }
        
        // return an array with files names
        return $files;
    }

    // Returns uploaded image real path
    public function getImagePathByName($fileName) 
    {
    	// BCS of security reasons
        $fileName = str_replace("/", "", $fileName);
        $fileName = str_replace("\\", "", $fileName);

        // return save dir + filename
        return $this->saveToDir . $fileName;                
    }

    // Returns image contents
    public function getImageFileContent($filePath) 
    {
        return file_get_contents($filePath);
    }
    
    //Returns info about file: MIME-type and size
    public function getImageFileInfo($filePath) 
    {
    	// Try to open save dir
    	if (!is_readable($filePath)) {            
            return false;
        }

        // Get size in bytes
        $fileSize = filesize($filePath);

        // Get MIME-type
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $filePath);
        if($mimeType===false)
            $mimeType = 'application/octet-stream';
    
        return [
            'size' => $fileSize,
            'type' => $mimeType 
        ];
    }

    // Change image size with keeping an image in its propotions
    public  function resizeImage($filePath, $desiredWidth = 240) 
    {
    	// get original image size
        list($originalWidth, $originalHeight) = getimagesize($filePath);

        // calculate ratio
        $aspectRatio = $originalWidth/$originalHeight;
        // get desired height
        $desiredHeight = $desiredWidth/$aspectRatio;

        // get info about image
        $fileInfo = $this->getImageFileInfo($filePath);
        
        // change size of an image
        $resultingImage = imagecreatetruecolor($desiredWidth, $desiredHeight);
        if (substr($fileInfo['type'], 0, 9) =='image/png')
            $originalImage = imagecreatefrompng($filePath);
        else
            $originalImage = imagecreatefromjpeg($filePath);
        imagecopyresampled($resultingImage, $originalImage, 0, 0, 0, 0, 
                $desiredWidth, $desiredHeight, $originalWidth, $originalHeight);

        // save to tmp dir
        $tmpFileName = tempnam("/tmp", "FOO");
        imagejpeg($resultingImage, $tmpFileName, 80);
        
        // return tmp path to image
        return $tmpFileName;
    } 
}