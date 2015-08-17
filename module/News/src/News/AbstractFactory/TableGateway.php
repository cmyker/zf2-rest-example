<?php

namespace News\AbstractFactory;

use Zend\Db\Adapter\AdapterAwareInterface;
use Zend\Db\Adapter\AdapterAwareTrait;
use Zend\ServiceManager\AbstractFactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\Db\TableGateway\TableGateway as ZendTableGateway;

/**
 * Factory to create tableGateway instances
 *
 * Class TableGateway
 * @package News\AbstractFactory
 */
class TableGateway implements AbstractFactoryInterface, AdapterAwareInterface
{

    //insert method for db adapter injection
    use AdapterAwareTrait;

    const NAMESPACE_PREFIX = 'News\TableGateway\\';

    public function canCreateServiceWithName(ServiceLocatorInterface $locator, $name, $requestedName)
    {
        if (strpos($requestedName, self::NAMESPACE_PREFIX) === 0) {
            return true;
        }
        return false;
    }

    /**
     * Create a table gateway for specific table
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @param $name
     * @param $requestedName
     * @return ZendTableGateway
     */
    public function createServiceWithName(ServiceLocatorInterface $serviceLocator, $name, $requestedName)
    {
        return new ZendTableGateway(
            strtolower(str_replace(self::NAMESPACE_PREFIX, '', $requestedName)),
            $serviceLocator->get('Zend\Db\Adapter\Adapter')
        );
    }

}
