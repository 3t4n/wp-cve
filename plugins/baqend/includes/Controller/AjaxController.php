<?php

namespace Baqend\WordPress\Controller;

use Baqend\SDK\Client\RestClient;
use Baqend\SDK\Exception\ResponseException;
use Baqend\SDK\Model\AssetFilter;
use Baqend\SDK\Value\Version;
use Baqend\WordPress\Admin\View;
use Baqend\WordPress\DisableReasonEnums;
use Baqend\WordPress\Loader;
use Baqend\WordPress\OptionEnums;

/**
 * Class AjaxController created on 17.07.17.
 *
 * @author Konstantin Simon Maria Möllers
 * @package Baqend\WordPress\Controller
 */
class AjaxController extends Controller {

    public function register( Loader $loader ) {
        // Register AJAX calls
        $loader->add_ajax( 'update_speed_kit', [ $this, 'update_speed_kit' ] );
        $loader->add_ajax( 'trigger_speed_kit', [ $this, 'trigger_speed_kit' ] );
        $loader->add_ajax( 'enable_speed_kit', [ $this, 'enable_speed_kit' ] );
        $loader->add_ajax( 'check_plugin_update', [ $this, 'check_plugin_update' ] );
        $loader->add_ajax( 'clear_cron_error', [ $this, 'clear_cron_error' ] );
        $loader->add_ajax( 'set_identity', [ $this, 'set_identity' ] );
        $loader->add_ajax( 'generate_username', [ $this, 'generate_username' ] );
        $loader->add_ajax( 'update_speedkit_metadata', [ $this, 'update_speedkit_metadata' ] );
        $loader->add_ajax( 'get_speed_kit_status', [ $this, 'get_speed_kit_status' ] );
    }

    /**
     * Updates to the latest version of Speed Kit.
     */
    public function update_speed_kit() {
        $update_attempts = $this->plugin->options->get( OptionEnums::UPDATE_ATTEMPTS, 0 );

        try {
            $was_latest = ! $this->plugin->speed_kit_service->check_for_updates();

            // Remove attempts because update was successful
            $this->plugin->options
                ->remove( OptionEnums::UPDATE_ATTEMPTS )
                ->save();

            $this->send_json_response( [
                'update_speed_kit' => true,
                'was_latest'       => $was_latest,
            ] );
        } catch ( \Exception $e ) {
            $update_attempts += 1;

            if ( $update_attempts >= 3 ) {
                $this->logger->error( 'update_speed_kit failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e ] );

                $this->plugin->options
                    ->remove( OptionEnums::UPDATE_ATTEMPTS )
                    ->save();

                $this->send_json_response( [
                    'update_speed_kit' => false,
                    'error'            => $e->getMessage(),
                ], 500 );
            } else {
                $this->plugin->options
                    ->set( OptionEnums::UPDATE_ATTEMPTS, $update_attempts )
                    ->save();

                // Do not trigger an error if update was successful
                $this->send_json_response( [
                    'update_speed_kit' => false,
                    'error'            => $e->getMessage(),
                ] );
            }
        }

    }

    /**
     * Triggers a revalidation of Speed Kit.
     */
    public function trigger_speed_kit() {
        $filter = new AssetFilter();
        $filter->addPrefix( ensure_ending_slash( site_url() ) );
        $filter->addContentType( AssetFilter::DOCUMENT );
        $error = null;
        try {
            $this->plugin->baqend->asset()->revalidate( $filter, 'wordpress-dashboard' );
            $success = true;
            $this->logger->debug( 'Manual revalidation succeeded', [ 'filter' => $filter->jsonSerialize() ] );
        } catch ( ResponseException $e ) {
            $response = $e->getResponse()->getBody()->getContents();
            $this->logger->error( 'Manual revalidation failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [
                'exception' => $e,
                'response'  => $response,
            ] );
            $error   = json_decode( $response, true );
            $success = false;
        }
        $this->send_json_response( [ 'trigger_speed_kit' => $success, 'error' => $error ] );
    }

    /**
     * Enables Speed Kit via AJAX.
     */
    public function enable_speed_kit() {
        $params  = $_POST['parameter'];
        $enabled = $params['enabled'] === 'true';
        $this->plugin->options->set( OptionEnums::SPEED_KIT_ENABLED, $enabled );
        if ( $enabled ) {
            $this->plugin->options->set( OptionEnums::REVALIDATION_ATTEMPTS, 0 );
        }

        $disable_reason = $enabled ? DisableReasonEnums::NONE : DisableReasonEnums::MANUAL;
        $this->plugin->options->set( OptionEnums::SPEED_KIT_DISABLE_REASON, $disable_reason );
        $this->plugin->options->save();

        $stats    = $this->plugin->stats_service->load_stats();
        $exceeded = $stats->is_exceeded();

        // Render updated HTML
        $comparison = $this->plugin->analyzer_service->load_latest_comparison( $enabled, $exceeded );
        if ( $comparison === null ) {
            $this->plugin->analyzer_service->start_comparison( $enabled );
        }
        $view = new View();
        $view->set_template( 'overview/performance.php' )
             ->assign( 'speed_kit', $enabled )
             ->assign( 'exceeded', $exceeded )
             ->assign( 'comparison', $comparison )
             ->render();
        exit;
    }

    /**
     * Checks for updates of the WordPress plugin.
     */
    public function check_plugin_update() {
        try {
            $ourVersion    = Version::parse( $this->plugin->version );
            $latestVersion = $this->plugin->baqend->getWordPressPlugin()->getLatestVersion();

            $this->send_json_response(
                [
                    'our'     => $ourVersion->__toString(),
                    'latest'  => $latestVersion->__toString(),
                    'compare' => $ourVersion->compare( $latestVersion ),
                ]
            );
        } catch ( \Exception $e ) {
            $this->logger->error( 'check_plugin_update failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e ] );
            $this->send_json_response( [
                'check_plugin_update' => false,
                'error'               => $e->getMessage(),
            ], 500 );
        }
    }

    /**
     * Clears the cron error message.
     */
    public function clear_cron_error() {
        $this->plugin->options->remove( 'cron_error' )->save();
        $this->send_json_response( [ 'clear_cron_error' => true ] );
    }

    /**
     * Sets the logged in identity.
     */
    public function set_identity() {
        $params       = $_POST['parameter'];
        $app_name     = $params['appName'];
        $bbq_username = isset( $params['bbqUsername'] ) ? $params['bbqUsername'] : '';
        $bbq_password = isset( $params['bbqPassword'] ) ? $params['bbqPassword'] : '';
        $username     = $params['username'];
        $token        = $params['token'];

        // Login using post data
        $this->plugin->login_user( $app_name, $username, $token, $bbq_username, $bbq_password );

        $this->send_json_response( [
            'set_identity' => true,
            'username'     => $username,
            'app_name'     => $app_name,
        ] );
    }

    /**
     * Generates a username to use for this WordPress instance.
     */
    public function generate_username() {
        $username = $this->plugin->generate_username();

        $this->send_json_response( [ 'username' => $username ] );
    }

    /**
     * Updates the environmental metadata for Speed Kit
     */
    public function update_speedkit_metadata() {
        try {
            $this->plugin->speed_kit_service->save_speedkit_metadata();

            $this->send_json_response( [
                'success' => true,
            ] );
        } catch ( \Exception $e ) {
            $this->send_json_response( [
                'success' => false,
            ] );
        }
    }

    public function get_speed_kit_status() {
        try {
            $stats             = $this->plugin->stats_service->load_stats();
            $exceeded          = $stats ? $stats->is_exceeded() : false;
            $speed_kit_enabled = $this->plugin->options->get( OptionEnums::SPEED_KIT_ENABLED );

            $this->send_json_response( [
                'exceeded' => $exceeded,
                'speedkit' => $speed_kit_enabled,
            ] );
        } catch ( \Exception $e ) {
            $this->logger->error( 'get_speed_kit_status failed with ' . get_class( $e ) . ': ' . $e->getMessage(), [ 'exception' => $e ] );
            $this->send_json_response( [
                'get_speed_kit_status' => false,
                'error'                => $e->getMessage(),
            ], 500 );
        }
    }

    /**
     * Sends a JSON response to the User.
     *
     * @param array $data
     * @param int $status_code
     */
    private function send_json_response( array $data, $status_code = 200 ) {
        status_header( $status_code );
        header( 'Content-Type: application/json' );
        die( json_encode( $data, 15, 512 ) );
    }

    /**
     * Returns the archive directory location.
     *
     * @return string
     */
    private function get_archive_dir() {
        return trailingslashit( $this->plugin->options->get( OptionEnums::TEMP_FILES_DIR ) . $this->plugin->options->get( OptionEnums::ARCHIVE_NAME ) );
    }
}
