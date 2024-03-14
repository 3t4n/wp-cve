<?php

namespace Modular\ConnectorDependencies\Illuminate\Queue\Connectors;

use Modular\ConnectorDependencies\Aws\Sqs\SqsClient;
use Modular\ConnectorDependencies\Illuminate\Queue\SqsQueue;
use Modular\ConnectorDependencies\Illuminate\Support\Arr;
/** @internal */
class SqsConnector implements ConnectorInterface
{
    /**
     * Establish a queue connection.
     *
     * @param  array  $config
     * @return \Illuminate\Contracts\Queue\Queue
     */
    public function connect(array $config)
    {
        $config = $this->getDefaultConfiguration($config);
        if (!empty($config['key']) && !empty($config['secret'])) {
            $config['credentials'] = Arr::only($config, ['key', 'secret', 'token']);
        }
        return new SqsQueue(new SqsClient($config), $config['queue'], $config['prefix'] ?? '', $config['suffix'] ?? '', $config['after_commit'] ?? null);
    }
    /**
     * Get the default configuration for SQS.
     *
     * @param  array  $config
     * @return array
     */
    protected function getDefaultConfiguration(array $config)
    {
        return \array_merge(['version' => 'latest', 'http' => ['timeout' => 60, 'connect_timeout' => 60]], $config);
    }
}
