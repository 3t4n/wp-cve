<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Baqend;
use Baqend\SDK\Client\RestClient;
use Baqend\WordPress\Model\Stats;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Options;
use Baqend\WordPress\Plugin;
use Symfony\Component\Serializer\Serializer;

/**
 * Service to handle statistical data
 *
 * Date: 10.07.2018
 *
 * @package Baqend\WordPress\Service
 * @author Brigitte Kwasny
 */
class StatsService {

    /**
     * @var Options
     */
    private $options;

    /**
     * @var Baqend
     */
    private $baqend;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Plugin
     */
    private $plugin;

    /**
     * @var Stats|null
     */
    private $cached_stats;

    public function __construct( Options $options, Baqend $baqend, Serializer $serializer, Plugin $plugin ) {
        $this->options      = $options;
        $this->baqend       = $baqend;
        $this->serializer   = $serializer;
        $this->plugin       = $plugin;
        $this->cached_stats = null;
    }

    /**
     * @return Stats|null
     */
    public function load_stats() {
        if ( ! $this->plugin->app_name ) {
            return null;
        }

        if ( $this->cached_stats !== null ) {
            return $this->cached_stats;
        }

        $app_name      = $this->plugin->app_name;
        $authorization = $this->options->get( OptionEnums::AUTHORIZATION );
        if ( $authorization === null ) {
            $authorization = $this->options->get( OptionEnums::API_TOKEN );
            if ( $authorization === null ) {
                return null;
            }
        }
        $client      = $this->baqend->getClient();
        $rest_client = new RestClient( 'bbq', $this->serializer, $client );

        $request = $rest_client->createRequest()
                               ->asPost()
                               ->withPath( '/code/wordpressStats' )
                               ->withJsonBody( [
                                   'appName'       => $app_name,
                                   'authorization' => $authorization,
                               ] )
                               ->build();
        try {
            $response = $client->sendSyncRequest( $request );
            $stats = new Stats( $this->serializer->decode( $response->getBody(), 'json' ) );
            $this->cached_stats = $stats;
            return $stats;
        } catch ( \Exception $e ) {
            return null;
        }
    }
}
