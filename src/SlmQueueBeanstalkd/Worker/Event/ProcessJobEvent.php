<?php

namespace SlmQueueBeanstalkd\Worker\Event;

use SlmQueue\Worker\Event\ProcessJobEvent as BaseProcessJobEvent;

/**
 * ProcessJobEvent
 */
class ProcessJobEvent extends BaseProcessJobEvent
{

    /**
     * Status for job that has failed but can be processed again
     */
    const JOB_STATUS_FAILURE_UNRECOVERABLE = 3;

}
