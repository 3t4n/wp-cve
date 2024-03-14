<?php

namespace Baqend\WordPress\Service;

use Baqend\SDK\Model\AssetFilter;
use Baqend\WordPress\Model\WidgetUpdateInfo;
use Baqend\WordPress\Options;
use Symfony\Component\Serializer\Serializer;

/**
 * Class WidgetUpdateService created on 2020-03-27.
 *
 * @author Kevin Twesten
 * @package Baqend\WordPress\Service
 */
class WidgetUpdateService {

    const WIDGET_UPDATE_OPTION = 'widget_update_info';

    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var Options
     */
    private $options;

    /**
     * @var RevalidationService
     */
    private $revalidation_service;

    /**
     * @var WidgetUpdateInfo[]
     */
    private $updated_widgets;

    /**
     * WidgetUpdateService constructor.
     *
     * @param Serializer $serializer
     * @param Options $options
     * @param RevalidationService $revalidation_service
     */
    public function __construct( Serializer $serializer, Options $options, RevalidationService $revalidation_service ) {
        $this->serializer = $serializer;
        $this->options = $options;
        $this->revalidation_service = $revalidation_service;
        $this->updated_widgets = $this->load_updated_widgets();
    }

    /**
     * @param string $widget_id_base
     *
     * @return bool
     */
    public function is_revalidation_allowed( $widget_id_base ) {
        $widget_update_info = $this->get_widget_update_info( $widget_id_base );
        return $widget_update_info->revalidation_allowed();
    }

    /**
     * @param string $widget_id_base
     */
    public function update_after_revalidation( $widget_id_base ) {
        $widget_update_info = $this->get_widget_update_info( $widget_id_base );
        $widget_update_info->mark_as_revalidated();
        $this->save_updated_widgets();
    }

    public function set_waiting( $widget_id_base ) {
        $widget_update_info = $this->get_widget_update_info( $widget_id_base );
        if ( $widget_update_info->isWaiting() ) {
            return;
        }

        $widget_update_info->setWaiting( true );
        $this->save_updated_widgets();
    }

    public function process_waiting() {
        $callback = function ( WidgetUpdateInfo $widget_update_info ) {
            return $widget_update_info->isWaiting() && $widget_update_info->revalidation_allowed();
        };
        $executables = array_filter( $this->updated_widgets, $callback );
        if ( sizeof( $executables ) === 0 ) {
            return;
        }

        $this->do_revalidation();
        foreach ( $executables as $executable ) {
            $executable->mark_as_revalidated();
        }
        $this->save_updated_widgets();
    }

    /**
     * @param string $widget_id_base
     *
     * @return WidgetUpdateInfo
     */
    private function create_widget_update_info( $widget_id_base ) {
        $widget_update_info = new WidgetUpdateInfo();
        $widget_update_info->setWidgetIdBase( $widget_id_base );
        array_push( $this->updated_widgets, $widget_update_info );
        return $widget_update_info;
    }

    /**
     * @param string $widget_id_base
     *
     * @return WidgetUpdateInfo
     */
    private function get_widget_update_info( $widget_id_base ) {
        $callback = function ( WidgetUpdateInfo $widget_update_info ) use ( $widget_id_base ) {
            return $widget_update_info->getWidgetIdBase() === $widget_id_base;
        };
        $existing = array_filter( $this->updated_widgets, $callback );
        return sizeof( $existing ) > 0 ? array_shift( $existing ) : $this->create_widget_update_info( $widget_id_base );
    }

    /**
     * @return WidgetUpdateInfo[]
     */
    private function load_updated_widgets() {
        $updated_widgets = $this->options->get( self::WIDGET_UPDATE_OPTION );
        if ( is_null( $updated_widgets ) || $updated_widgets === 'null' ) {
            return [];
        }

        return $this->serializer->deserialize( $updated_widgets, WidgetUpdateInfo::class.'[]', 'json' );
    }

    private function save_updated_widgets() {
        $serialized = $this->serializer->serialize( $this->updated_widgets, 'json' );
        $this->options->set( self::WIDGET_UPDATE_OPTION, $serialized )->save();
    }

    /**
     * Revalidates the whole WordPress blog.
     */
    public function do_revalidation() {
        $site_url = ensure_ending_slash( site_url() );

        $filter = new AssetFilter();
        $filter->addPrefix( $site_url );

        // Add hook name as url for debugging
        $hook_name = 'widget_update_callback';
        $filter->addUrl( 'https://'.$hook_name.'/' );

        $this->revalidation_service->send_revalidation( $filter );
    }
}
