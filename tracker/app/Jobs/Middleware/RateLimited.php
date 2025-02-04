<?php

namespace App\Jobs\Middleware;

use Closure;
use Illuminate\Support\Facades\Redis;

class RateLimited
{
    /**
     * Process the queued job.
     *
     * @param  \Closure(object): void  $next
     */
    public function handle(object $job, Closure $next): void
    {
        // $key = 'job_throttle_' . $job->getJobId();
        Redis::throttle('key')
            ->block(0)->allow(1)->every(60)
            ->then(function () use ($job, $next) {
                // Lock obtained...

                $next($job);
            }, function () use ($job) {

                // releases the job back to the queue and delays its retry by 5 seconds
                $job->release(5 );
            });
    }
}
