<?php
namespace Application\Controller\Factory;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;
use Application\Service\ImageManager;
use Application\Controller\ImageController;

//Factory for ImageController
class ImageControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, 
                        $requestedName, array $options = null)
    {
        $imageManager = $container->get(ImageManager::class);
        
        return new ImageController($imageManager);
    }
}