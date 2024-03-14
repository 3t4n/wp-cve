<?php

namespace Baqend\WordPress\Controller;

use Baqend\SDK\Model\AssetFilter;
use Baqend\WordPress\Loader;
use Baqend\WordPress\OptionEnums;

/**
 * Cron Controller created on 10.08.2017.
 *
 * This controller contains all actions which are triggered by cron
 * jobs from WordPress.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Controller
 */
class CronController extends Controller {

    public function register( Loader $loader ) {
        $loader->add_action( 'cron_revalidate_html', [ $this, 'revalidate_html' ] );
        $loader->add_action( 'cron_update_speed_kit', [ $this, 'update_speed_kit' ] );
    }

    /**
     * This action is triggered by the plugin to revalidate HTML.
     */
    public function revalidate_html() {
        // Revalidate HTML
        $filter = new AssetFilter();
        $filter->addPrefix( ensure_ending_slash( site_url() ) );
        $filter->addContentType( AssetFilter::DOCUMENT );

        $this->logger->info( 'Revalidating HTML as scheduled', [ 'filter' => $filter ] );
        $this->plugin->revalidation_service->send_revalidation( $filter );
        $this->logger->info( 'Next revalidation: ' . date( 'Y-m-d H:i:s', wp_next_scheduled( 'cron_revalidate_html' ) ) );
    }

    /**
     * This action is triggered by the plugin to check the Speed Kit
     * version and update it automatically.
     */
    public function update_speed_kit() {
        $this->logger->info( 'Checking for Speed Kit updates' );

        try {
            $is_updated = $this->plugin->speed_kit_service->check_for_updates();
            $this->logger->info( 'Speed Kit ' . ( $is_updated ? ' has been updated ' : ' is already the latest version' ) );

            // revalidate HTML and CSS of the theme
            if ( $is_updated ) {
                $filter = new AssetFilter();
                $filter->addPrefix( ensure_ending_slash( site_url() ) );
                $filter->addContentTypes( [ AssetFilter::DOCUMENT, AssetFilter::STYLE ] );
                $this->logger->info( 'Revalidating HTML and CSS as scheduled', [ 'filter' => $filter ] );
                $this->plugin->revalidation_service->send_revalidation( $filter );
            }

            // Ensure no cron errors are stored when there was no error
            $this->plugin->options->set( OptionEnums::CRON_ERROR, null )->save();
        } catch ( \Exception $e ) {
            /* translators: %s: Original error message */
            $message = __( 'Could not update Speed Kit: %s', 'baqend' );

            $this->plugin->options->set( OptionEnums::CRON_ERROR, sprintf( $message, $e->getMessage() ) )->save();
            $this->logger->error( 'Update failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e ] );
        }
    }
}
