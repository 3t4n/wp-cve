<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Client\RestClient;
use Baqend\SDK\SpeedKit;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Plugin;

/**
 * Class SpeedKitService created on 2018-07-10.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Service
 */
class SpeedKitService {

    /**
     * @var IOService
     */
    private $IO_service;

    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @var SpeedKit
     */
    private $speed_kit;

    /**
     * SpeedKitService constructor.
     *
     * @param IOService $IO_service
     * @param SpeedKit $speed_kit
     * @param Plugin $plugin
     */
    public function __construct( IOService $IO_service, SpeedKit $speed_kit, Plugin $plugin ) {
        $this->IO_service = $IO_service;
        $this->plugin     = $plugin;
        $this->speed_kit  = $speed_kit;
    }

    /**
     * @return bool True, if an update was performed.
     * @throws \Exception
     */
    public function check_for_updates() {
        // Get updates on Speed Kit
        $sw_path      = $this->plugin->sw_path();
        $snippet_path = $this->plugin->snippet_path();
        $df_path      = $this->plugin->dynamic_fetcher_path();
        $info         = $this->speed_kit->createInfo( $sw_path, $snippet_path, $df_path, '', 'latest', true );

        if ( $info->isLatest() ) {
            return false;
        }

        // Put updates into the directory
        $this->IO_service->write_file_contents( $df_path, $info->getDynamicFetcherContent() );
        $this->IO_service->write_file_contents( $snippet_path, $info->getSnippetContent() );
        $this->IO_service->write_file_contents( $sw_path, $info->getSwContent() );

        return true;
    }

    /**
     * Checks files exist and are up to date.
     *
     * @throws \Exception
     */
    public function ensure_files_up_to_date() {
        // Remove and reinstall the ServiceWorker
        $sw_path    = $this->plugin->sw_path();
        $sw_js_path = $this->plugin->sw_js_path();

        // Is there a Service Worker file to unpack?
        if ( @file_exists( $sw_js_path ) ) {
            $this->IO_service->move_or_copy( $sw_js_path, $sw_path );
        } else {
            $this->IO_service->supply( $sw_path, function () {
                return $this->speed_kit->getServiceWorker();
            } );
        }

        // Install the Snippet
        $snippet_path = $this->plugin->snippet_path();
        $this->IO_service->supply( $snippet_path, function () {
            return $this->speed_kit->getSnippet();
        } );

        // Install the Dynamic Fetcher
        $dynamic_fetcher_path = $this->plugin->dynamic_fetcher_path();
        $this->IO_service->supply( $dynamic_fetcher_path, function () {
            return $this->speed_kit->getDynamicFetcher();
        } );

        $this->check_for_updates();
    }

    /**
     * Sends Speedkit Metadata to BBQ
     *
     * @throws \Exception
     */
    public function save_speedkit_metadata() {
        $php_version       = phpversion();
        $wordpress_version = $GLOBALS['wp_version'];
        $metadata          = [ 'php_version' => $php_version, 'wordpress_version' => $wordpress_version ];
        $domain            = get_home_url();

        $app_name      = $this->plugin->app_name;
        $authorization = $this->plugin->options->get( OptionEnums::AUTHORIZATION );

        $client      = $this->plugin->baqend->getClient();
        $rest_client = new RestClient( 'bbq', $this->plugin->serializer, $client );

        $request = $rest_client->createRequest()
                               ->asPost()
                               ->withPath( '/code/speedKitMetadata' )
                               ->withJsonBody( [
                                   'appName'       => $app_name,
                                   'authorization' => $authorization,
                                   'domain'        => $domain,
                                   'metadata'      => $metadata,
                               ] )
                               ->build();

        $client->sendAsyncRequest( $request );
    }
}
