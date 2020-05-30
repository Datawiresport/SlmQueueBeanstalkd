<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use SlmQueueBeanstalkd\Options\BeanstalkdOptions;
use Laminas\ServiceManager\Factory\FactoryInterface;

class BeanstalkdOptionsFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $config = $container->get('Config');
        return new BeanstalkdOptions($config['slm_queue']['beanstalkd']);
    }
}
