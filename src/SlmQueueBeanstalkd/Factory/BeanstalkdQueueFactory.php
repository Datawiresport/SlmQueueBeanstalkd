<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Options\QueueOptions;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueue;
use Laminas\ServiceManager\Factory\FactoryInterface;
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
        $pheanstalk = $container->get('SlmQueueBeanstalkd\Service\PheanstalkService');
        $jobPluginManager = $container->get('SlmQueue\Job\JobPluginManager');

        $queueOptions = $this->getQueueOptions($container, $requestedName);

        return new BeanstalkdQueue($pheanstalk, $requestedName, $jobPluginManager, $queueOptions);
    }

    /**
     * Returns custom beanstalkd options for specified queue
     *
     * @param ContainerInterface $container
     * @param string $queueName
     *
     * @return QueueOptions
     */
    protected function getQueueOptions(ContainerInterface $container, $queueName)
    {
        $config = $container->get('Config');
        $queuesOptions = isset($config['slm_queue']['queues'])? $config['slm_queue']['queues'] : array();
        $queueOptions = isset($queuesOptions[$queueName])? $queuesOptions[$queueName] : array();

        return new QueueOptions($queueOptions);
    }
}
