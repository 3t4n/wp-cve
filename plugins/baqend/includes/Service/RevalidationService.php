<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Baqend;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\AssetFilter;
use Baqend\WordPress\DisableReasonEnums;
use Baqend\WordPress\Model\RevalidationInfo;
use Baqend\WordPress\OptionEnums;
use Baqend\WordPress\Options;
use Psr\Log\LoggerInterface;
use Symfony\Component\Serializer\Serializer;

/**
 * Class RevalidationService created on 2019-10-19.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Service
 */
class RevalidationService {

    const REVALIDATION_INFO_OPTION = 'revalidation_info';
    const MAX_URLS_PER_FILTER = 500;

    /**
     * @var Baqend
     */
    private $baqend;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Options
     */
    private $options;

    /**
     * @var AssetFilter[]
     */
    private $filters;

    /**
     * RevalidationService constructor.
     *
     * @param Baqend $baqend
     * @param LoggerInterface $logger
     * @param Serializer $serializer
     * @param Options $options
     */
    public function __construct( Baqend $baqend, LoggerInterface $logger, Serializer $serializer, Options $options ) {
        $this->baqend = $baqend;
        $this->logger = $logger;
        $this->serializer = $serializer;
        $this->options = $options;
        $this->filters = [];
    }

    /**
     * @param AssetFilter $filter
     */
    public function add_asset_filter( AssetFilter $filter ) {
        $this->prepare_asset_filter($filter);

        // Do not add if already exits. Use of '==' is intended to compare both objects.
        if ( $filter == $this->filters ) {
            return;
        }

        $this->collapse_asset_filters( $filter );
    }

    public function process_filters() {
        // Finish if there are no filters to be processed.
        if ( sizeof( $this->filters ) < 1 ) {
            return;
        }

        $timestamp = current_time( 'timestamp' );
        $handled_filters = [];
        $revalidation_info = $this->load_revalidation_info();
        foreach ( $this->filters as $filter ) {
            // Ensure that the same filter was not executed moments ago.
            if ( ! $revalidation_info->outdated( $filter, $timestamp ) ) {
                continue;
            }

            $this->logger->info( $this->serializer->serialize( $filter, 'json' ) );
            $this->send_revalidation( $filter );

            $handled_filters[] = $filter;
        }

        if ( sizeof( $handled_filters ) < 1) {
            return;
        }

        $this->update_revalidation_info( $revalidation_info, $handled_filters, $timestamp );
    }

    /**
     * @param AssetFilter $filter
     */
    public function send_revalidation( AssetFilter $filter ) {
        // Do not revalidate if not logged in with Baqend.
        if ( ! $this->baqend->isConnected() ) {
            return;
        }

        // Change refresh to refresh all if too many URLs are in the filter
        if (sizeof($filter->getUrls()) > self::MAX_URLS_PER_FILTER) {
            $filter = new AssetFilter();
            $filter->addUrl('*');
        }

        try {
            $this->baqend->asset()->revalidate( $filter, 'wordpress-hook' );
            $this->handle_revalidation_success();
            $this->logger->info( 'Revalidation successful' );
        } catch ( ResponseException $e ) {
            $this->handle_revalidation_error( $e );
            $this->logger->error( 'Revalidation failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e->getTraceAsString() ] );
        }
    }

    /**
     * @return bool
     */
    public function has_critical_revalidation_attempts() {
        return $this->options->get( OptionEnums::REVALIDATION_ATTEMPTS ) >= 5;
    }

    private function handle_revalidation_success() {
        $this->options->set( OptionEnums::REVALIDATION_ATTEMPTS, 0 );
        if ( $this->options->get( OptionEnums::SPEED_KIT_ENABLED ) !== true ) {
            $disable_reason = $this->options->get( OptionEnums::SPEED_KIT_DISABLE_REASON );
            if ( $disable_reason === DisableReasonEnums::REVALIDATION_ERROR ) {
                $this->options->set( OptionEnums::SPEED_KIT_ENABLED, true );
            }
        }
        $this->options->save();
    }

    /**
     * @param AssetFilter $filter
     */
    private function prepare_asset_filter( AssetFilter $filter ) {
        $urls = [];
        // Prepare URLs before adding to filter.
        foreach ( $filter->getUrls() as $url ) {
            $param_position = strpos( $url, '?' );
            // Replace all query params with param wildcard.
            if ( $param_position !== false ) {
                $url_without_params = substr( $url, 0, $param_position );
                array_push($urls, $url_without_params.'?*' );
                continue;
            }

            // Add original URL and URL with query param wildcard.
            array_push($urls, $url, $url.'?*' );
        }

        // Override URLs of filter with prepared URLs.
        $filter->setUrls( $urls );
    }

    /**
     * @param ResponseException $error
     */
    private function handle_revalidation_error( ResponseException $error ) {
        // Do not count revalidation attempt if Baqend server had an error.
        if ( $error->getResponse() !== null && $error->getResponse()->getStatusCode() === 500 ) {
            return;
        }

        // Do not increase revalidation attempts if Speed Kit is not enabled.
        if ( $this->options->get( OptionEnums::SPEED_KIT_ENABLED ) !== true ) {
            return;
        }

        $revalidation_attempts = $this->options->get( OptionEnums::REVALIDATION_ATTEMPTS, 0 );
        $this->options->set( OptionEnums::REVALIDATION_ATTEMPTS, $revalidation_attempts + 1 );
        // Disable Speed Kit if the revalidation status is critical.
        if ( $this->has_critical_revalidation_attempts() ) {
            $this->options->set( OptionEnums::SPEED_KIT_DISABLE_REASON, DisableReasonEnums::REVALIDATION_ERROR );
            $this->options->set( OptionEnums::SPEED_KIT_ENABLED, false );
        }

        $this->options->save();
    }

    /**
     * @param AssetFilter $filter
     */
    private function collapse_asset_filters( AssetFilter $filter ) {
        // First, try to simplify the filter by merging 'prefixes' and 'urls'.
        $this->simplify_filter($filter);

        // Add filter if there are no other filters yet.
        if ( sizeof( $this->filters ) === 0 ) {
            $this->filters[] = $filter;
            return;
        }

        $filter_keys = array_keys( $filter->jsonSerialize() );
        // Ignore if the complexity of the new filter is to high (more than one attribute).
        if ( sizeof( $filter_keys ) > 1 ) {
            $this->filters[] = $filter;
            return;
        }

        $was_collapsed = false;
        foreach ( $this->filters as $existing_filter ) {
            $existing_keys = array_keys( $existing_filter->jsonSerialize() );
            // Ignore if the complexity of the existing filter is to high (more than one attribute).
            if ( sizeof( $existing_keys ) > 1 ) {
                continue;
            }

            // Ensure that the existing attributes are the same.
            if ( $existing_keys[ 0 ] !== $filter_keys[ 0 ] ) {
                continue;
            }

            $existing_filter_values = $existing_filter->jsonSerialize()[ $existing_keys[ 0 ] ];
            $new_filter_values = $filter->jsonSerialize()[ $filter_keys[ 0 ] ];

            // Merge the values of both existing attributes and set the attribute of the existing filter.
            $merged_filter_values = array_unique( array_merge( $existing_filter_values, $new_filter_values ) );
            $existing_filter->{ 'set'.ucfirst( $existing_keys[ 0 ] ) }( array_values( $merged_filter_values ) );

            // Do not continue if the filter was already collapsed with one of the existing filters.
            $was_collapsed = true;
            break;
        }

        // Add filter if it could not be collapsed with one of the existing filters.
        if ( ! $was_collapsed ) {
            $this->filters[] = $filter;
        }
    }

    /**
     * @param RevalidationInfo $revalidation_info
     * @param AssetFilter[] $handled_filters
     * @param int $timestamp
     */
    private function update_revalidation_info( RevalidationInfo $revalidation_info, $handled_filters, $timestamp) {
        $revalidation_info->setFilters( $handled_filters );
        $revalidation_info->setTimestamp( $timestamp );
        $serialized = $this->serializer->serialize( $revalidation_info, 'json' );
        $this->options->set( self::REVALIDATION_INFO_OPTION, $serialized )->save();
    }

    /**
     * @return RevalidationInfo
     */
    private function load_revalidation_info() {
        $revalidation_info = $this->options->get( self::REVALIDATION_INFO_OPTION );
        if ( is_null( $revalidation_info ) || $revalidation_info === 'null' ) {
           return new RevalidationInfo();
        }

        return $this->serializer->deserialize( $revalidation_info, RevalidationInfo::class, 'json' );
    }

    /**
     * @param AssetFilter $filter
     */
    private function simplify_filter( AssetFilter $filter ) {
        $keys = array_keys( $filter->jsonSerialize() );

        // Filter can not be simplified if it is empty.
        if ( count($keys) === 0 ) {
            return;
        }

        $hasQuery = $filter->getQuery() !== null;
        $hasMediaTypes = count( $filter->getMediaTypes() ) > 0;
        $hasContentTypes = count( $filter->getContentTypes() ) > 0;

        // Filter can not be simplified if other attributes than 'urls' or 'prefixes' are set.
        if ( $hasQuery || $hasMediaTypes || $hasContentTypes ) {
            return;
        }

        // Simplify filter by adding the prefixes to the list of URLs.
        foreach ( $filter->getPrefixes() as $prefix ) {
            $filter->addUrl($prefix.'*' );
        }

        $filter->setPrefixes([]);
    }
}
