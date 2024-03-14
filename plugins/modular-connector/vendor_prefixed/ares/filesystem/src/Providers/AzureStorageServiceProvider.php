<?php

namespace Modular\ConnectorDependencies\Ares\Filesystem\Providers;

use Modular\ConnectorDependencies\Ares\Filesystem\AzureBlobStorageAdapter;
use Modular\ConnectorDependencies\Ares\Framework\Setup\Providers\ServiceProvider;
use Modular\ConnectorDependencies\Illuminate\Support\Facades\Storage;
use Modular\ConnectorDependencies\League\Flysystem\Filesystem;
use Modular\ConnectorDependencies\MicrosoftAzure\Storage\Blob\BlobRestProxy;
/** @internal */
class AzureStorageServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return Filesystem
     */
    public function boot()
    {
        if (\class_exists(BlobRestProxy::class)) {
            Storage::extend('azure', function ($app, $config) {
                $endpoint = \sprintf('DefaultEndpointsProtocol=https;AccountName=%s;AccountKey=%s', $config['name'], $config['key']);
                $client = BlobRestProxy::createBlobService($endpoint);
                $adapter = new AzureBlobStorageAdapter($client, $config);
                return new Filesystem($adapter, $config);
            });
        }
    }
}
