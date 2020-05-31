<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueueBeanstalkd\Job\Exception as JobException;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;
use SlmQueueBeanstalkd\Worker\Event\ProcessJobEvent;

/**
 * Worker for Beanstalkd
 */
class BeanstalkdWorker extends AbstractWorker
{
    /**
     * {@inheritDoc}
     */
    public function processJob(JobInterface $job, QueueInterface $queue): int
    {
        if (!$queue instanceof BeanstalkdQueueInterface) {
            return ProcessJobEvent::JOB_STATUS_UNKNOWN;
        }

        try {
            $job->execute();
            $queue->delete($job);

            return ProcessJobEvent::JOB_STATUS_SUCCESS;
        } catch (JobException\ReleasableException $exception) {
            $queue->release($job, $exception->getOptions());

            return ProcessJobEvent::JOB_STATUS_FAILURE_RECOVERABLE;
        } catch (JobException\BuryableException $exception) {
            $queue->bury($job, $exception->getOptions());

            return ProcessJobEvent::JOB_STATUS_FAILURE;
        } catch (Exception $exception) {
            $queue->bury($job, [
                'message' => $exception->getMessage(),
                'trace'   => $exception->getTraceAsString()
            ]);

            return ProcessJobEvent::JOB_STATUS_FAILURE_UNRECOVERABLE;
        }
    }
}
