<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class BeanstalkdOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
    }
}
