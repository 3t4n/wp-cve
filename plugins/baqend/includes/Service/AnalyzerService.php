<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Client\ClientInterface;
use Baqend\WordPress\Model\LatestComparison;
use Baqend\WordPress\Options;
use GuzzleHttp\Psr7\Request;
use Symfony\Component\Serializer\Serializer;

/**
 * Class AnalyzerService created on 2018-07-03.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Service
 */
class AnalyzerService {

    /**
     * @var ClientInterface
     */
    private $client;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Options
     */
    private $options;

    public function __construct( ClientInterface $client, Serializer $serializer, Options $options ) {
        $this->client     = $client;
        $this->serializer = $serializer;
        $this->options    = $options;
    }

    /**
     * Loads speed up factors for the current domain.
     *
     * @param bool $speed_kit_enabled
     * @param bool $is_exceeded
     *
     * @return LatestComparison|null
     */
    public function load_latest_comparison( $speed_kit_enabled, $is_exceeded ) {
        try {
            $query = http_build_query( [
                'url'      => site_url( '/' ),
                'speedKit' => $is_exceeded ? 'false' : ( $speed_kit_enabled ? 'true' : 'false' ),
            ] );

            $request  = new Request( 'GET', 'https://makefast.app.baqend.com/v1/code/getLatestComparison?' . $query );
            $response = $this->client->sendSyncRequest( $request );

            return $this->serializer->deserialize( $response->getBody(), LatestComparison::class, 'json' );
        } catch ( \Exception $e ) {
            return null;
        }
    }

    /**
     * Starts a comparison.
     *
     * @param bool $speed_kit_enabled
     */
    public function start_comparison( $speed_kit_enabled ) {
        try {
            $prefix = $speed_kit_enabled ? 'test_speed_kit_' : 'test_competitor_';

            // Check if a test has already been started and if so, if it is not older than a day
            $created_at = $this->options->get( $prefix . 'created_at' );
            if ( $this->options->has( $prefix . 'id' ) && not_older_than( $created_at, 86400 ) ) {
                return;
            }

            $body     = json_encode( [ 'url' => site_url( '/' ) ] );
            $request  = new Request( 'POST', 'https://makefast.app.baqend.com/v1/code/startComparison', [ 'content-type' => 'application/json' ], $body );
            $response = $this->client->sendSyncRequest( $request );

            $json       = $this->serializer->decode( $response->getBody(), 'json' );
            $id         = $json['id'];
            $created_at = new \DateTime( $json['createdAt'] );

            $this->options
                ->set( $prefix . 'id', $id )
                ->set( $prefix . 'created_at', $created_at )
                ->save();
        } catch ( \Exception $e ) {
        }
    }
}
