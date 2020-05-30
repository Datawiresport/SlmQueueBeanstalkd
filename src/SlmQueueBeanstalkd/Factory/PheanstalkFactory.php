<?php

namespace SlmQueueBeanstalkd\Factory;

use Interop\Container\ContainerInterface;
use Pheanstalk\Pheanstalk;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        /** @var $beanstalkdOptions \SlmQueueBeanstalkd\Options\BeanstalkdOptions */
        $beanstalkdOptions = $container->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
        $connectionOptions = $beanstalkdOptions->getConnection();

        return new Pheanstalk(
            $connectionOptions->getHost(),
            $connectionOptions->getPort(),
            $connectionOptions->getTimeout()
        );
    }
}
