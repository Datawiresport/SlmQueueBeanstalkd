<?php

namespace SlmQueueBeanstalkd\Factory;

use Pheanstalk\Pheanstalk;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

/**
 * PheanstalkFactory
 */
class PheanstalkFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var $beanstalkdOptions \SlmQueueBeanstalkd\Options\BeanstalkdOptions */
        $beanstalkdOptions = $serviceLocator->get('SlmQueueBeanstalkd\Options\BeanstalkdOptions');
        $connectionOptions = $beanstalkdOptions->getConnection();

        return new Pheanstalk(
            $connectionOptions->getHost(),
            $connectionOptions->getPort(),
            $connectionOptions->getTimeout()
        );
    }
}
