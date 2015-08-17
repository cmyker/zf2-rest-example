<?php

namespace News\AbstractFactory;

use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Factory to create other classes from module
 * Has lowest priority
 *
 * Class TableGateway
 * @package News\AbstractFactory
 */
class Autoload implements AbstractFactoryInterface
{

    const NAMESPACE_PREFIX = 'News\\';

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (strpos($requestedName, self::NAMESPACE_PREFIX) === 0) {
            return true;
        }
        return false;
    }

    /**
     * Create an object
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return ZendTableGateway
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return new $requestedName;
    }

}
