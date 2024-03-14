<?php

namespace Modular\ConnectorDependencies\League\Flysystem\Adapter\Polyfill;

use Modular\ConnectorDependencies\League\Flysystem\Config;
use Modular\ConnectorDependencies\League\Flysystem\Util;
/** @internal */
trait StreamedWritingTrait
{
    /**
     * Stream fallback delegator.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config
     * @param string   $fallback
     *
     * @return mixed fallback result
     */
    protected function stream($path, $resource, Config $config, $fallback)
    {
        Util::rewindStream($resource);
        $contents = \stream_get_contents($resource);
        $fallbackCall = [$this, $fallback];
        return \call_user_func($fallbackCall, $path, $contents, $config);
    }
    /**
     * Write using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config
     *
     * @return mixed false or file metadata
     */
    public function writeStream($path, $resource, Config $config)
    {
        return $this->stream($path, $resource, $config, 'write');
    }
    /**
     * Update a file using a stream.
     *
     * @param string   $path
     * @param resource $resource
     * @param Config   $config   Config object or visibility setting
     *
     * @return mixed false of file metadata
     */
    public function updateStream($path, $resource, Config $config)
    {
        return $this->stream($path, $resource, $config, 'update');
    }
    // Required abstract methods
    public abstract function write($pash, $contents, Config $config);
    public abstract function update($pash, $contents, Config $config);
}
