<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Controller\BeanstalkdWorkerController;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * BeanstalkdWorkerControllerFactory
 */
class BeanstalkdWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $worker  = $container->get('SlmQueueBeanstalkd\Worker\BeanstalkdWorker');
        $manager = $container->get('SlmQueue\Queue\QueuePluginManager');

        return new BeanstalkdWorkerController($worker, $manager);
    }
}
