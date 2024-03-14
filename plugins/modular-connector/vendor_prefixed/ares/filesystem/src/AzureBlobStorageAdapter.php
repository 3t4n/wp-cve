<?php

namespace Modular\ConnectorDependencies\Ares\Filesystem;

use Modular\ConnectorDependencies\League\Flysystem\AzureBlobStorage\AzureBlobStorageAdapter as AzureBlobs;
use Modular\ConnectorDependencies\MicrosoftAzure\Storage\Blob\BlobRestProxy;
/** @internal */
class AzureBlobStorageAdapter extends AzureBlobs
{
    /**
     * @var
     */
    private $config;
    /**
     * AzureBlobStorageAdapter constructor.
     * @param BlobRestProxy $client
     * @param $config
     */
    public function __construct(BlobRestProxy $client, $config)
    {
        $this->setConfig($config);
        parent::__construct($client, $config['container'], $config['root'] ?? null);
    }
    /**
     * get config value
     *
     * @return mixed
     */
    public function getConfig()
    {
        return $this->config;
    }
    /**
     * set config value
     *
     * @param mixed $config
     */
    public function setConfig($config) : void
    {
        $this->config = $config;
    }
    /**
     * @param string $path
     * @return string
     */
    public function getUrl(string $path)
    {
        $finalPath = $this->getConfig()['url'] . $this->getConfig()['container'] . '/' . $this->getConfig()['root'] . $path;
        return $finalPath;
    }
}
