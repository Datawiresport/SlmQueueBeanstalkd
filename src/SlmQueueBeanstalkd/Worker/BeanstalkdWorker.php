<?php

namespace SlmQueueBeanstalkd\Worker;

use Exception;
use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueue\Worker\AbstractWorker;
use SlmQueue\Worker\Event\ProcessJobEvent;
use SlmQueueBeanstalkd\Job\Exception as JobException;
use SlmQueueBeanstalkd\Queue\BeanstalkdQueueInterface;

/**
 * Worker for Beanstalkd
 */
class BeanstalkdWorker extends AbstractWorker
{
    /**
     * {@inheritDoc}
     */
    public function processJob(JobInterface $job, QueueInterface $queue)
    {
        if (!$queue instanceof BeanstalkdQueueInterface) {
            return ProcessJobEvent::JOB_STATUS_UNKNOWN;
        }

        /**
         * In Beanstalkd, if an error occurs (exception for instance), the job
         * is automatically reinserted into the queue after a configured delay
         * (the "visibility_timeout" option). If the job executed correctly, it
         * must explicitly be removed
         */
        try {
            $job->execute();
            $queue->delete($job);

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

            return ProcessJobEvent::JOB_STATUS_FAILURE;
        }
    }
}
