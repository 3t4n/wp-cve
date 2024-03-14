<?php

namespace Modular\ConnectorDependencies\Ares\Filesystem\Providers;

use Modular\ConnectorDependencies\Ares\Filesystem\CloudFilesystem;
use Modular\ConnectorDependencies\Illuminate\Contracts\Support\DeferrableProvider;
use Modular\ConnectorDependencies\Illuminate\Support\ServiceProvider;
/** @internal */
class FilesystemProvider extends ServiceProvider
{
    /**
     * Register provider to upload images to AWS S3 service
     */
    public function registerCloudFilesystem()
    {
        $class = \Modular\ConnectorDependencies\app()->make(CloudFilesystem::class);
        if (\Modular\ConnectorDependencies\config()->get('filesystems.default') !== 'local') {
            \add_action('wp_update_attachment_metadata', [$class, 'uploadFile'], 110, 2);
            \add_filter('delete_attachment', [$class, 'destroyFile'], 20, 1);
        }
        \add_filter('wp_get_attachment_url', [$class, 'getUrlFile'], 99, 2);
        // Original URL
        \add_filter('wp_calculate_image_srcset', [$class, 'getResponsiveUrls'], 10, 5);
        // Responsive URLs
    }
    /**
     * Init require functions
     */
    public function register() : void
    {
        if (\function_exists('add_action') && \function_exists('add_filter')) {
            \add_action('after_setup_theme', [$this, 'registerCloudFilesystem']);
        }
    }
}
