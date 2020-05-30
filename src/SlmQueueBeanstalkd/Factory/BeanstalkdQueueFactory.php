<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceManager;

/**
 * BeanstalkdQueueFactory
 */
class BeanstalkdQueueFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     *
     * @return BeanstalkdQueue
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $serviceManager = $container->getServiceManager();
        $pheanstalk = $serviceManager->get('SlmQueueBeanstalkd\Service\PheanstalkService');
        $jobPluginManager = $serviceManager->get('SlmQueue\Job\JobPluginManager');

        $queueOptions = $this->getQueueOptions($serviceManager, $requestedName);

        return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
    }

    /**
     * Returns custom beanstalkd options for specified queue
     *
     * @param ServiceManager $serviceManager
     * @param string $queueName
     *
     * @return QueueOptions
     */
    protected function getQueueOptions(ServiceManager $serviceManager, $queueName)
    {
        $config = $serviceManager->get('Config');
        $queuesOptions = isset($config['slm_queue']['queues'])? $config['slm_queue']['queues'] : array();
        $queueOptions = isset($queuesOptions[$queueName])? $queuesOptions[$queueName] : array();

        return new QueueOptions($queueOptions);
    }
}
