<?php

namespace SlmQueueBeanstalkd\Factory;

use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * BeanstalkdWorkerControllerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $worker  = $serviceLocator->getServiceLocator()->get('SlmQueueBeanstalkd\Worker\BeanstalkdWorker');
        $manager = $serviceLocator->getServiceLocator()->get('SlmQueue\Queue\QueuePluginManager');

        return new BeanstalkdWorkerController($worker, $manager);
    }
}
