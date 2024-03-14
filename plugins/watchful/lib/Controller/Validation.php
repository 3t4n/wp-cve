<?php

namespace Watchful\Controller;

use stdClass;
use Watchful\Helpers\AppAlerts;
use Watchful\Helpers\Authentification;
use Watchful\Helpers\Users;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

class Validation implements BaseControllerInterface
{
    public function register_routes()
    {
        register_rest_route(
            'watchful/v1',
            '/validate',
            array(
                'methods'             => array( WP_REST_Server::CREATABLE, WP_REST_Server::READABLE ),
                'callback'            => array('Watchful\Controller\Validation', 'validate' ),
                'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                'args'                => Authentification::get_arguments(),
            )
        );
    }

    /**
     * Validation API endpoint
     *
     * @param WP_REST_Request $request The request with the plugin info.
     *
     * @return WP_REST_Response
     */
    public static function validate( WP_REST_Request $request ) {
        $extensions  = $request->get_param( 'extensions' );
        $site_backups = $request->get_param( 'siteBackups' );

        $plugin_data = array();
        if ( ! empty( $extensions ) ) {
            $plugin_data = json_decode( $extensions );
        }

        $site_backups_data = array();
        if ( ! empty( $site_backups ) ) {
            $site_backups_data = json_decode( $site_backups );
        }

        $data    = new stdClass();
        $plugins = new Plugins();
        $themes  = new Themes();
        $core    = new Core();
        $users   = new Users();
        $appAlerts = new AppAlerts();

        $data->status                 = $core->get_status();
        $data->versions               = $core->get_versions();
        $data->extensions             = new stdClass();
        $data->extensions->extensions = $plugins->get_all_plugins( $plugin_data );
        $data->extensions->themes     = $themes->get_themes();
        $data->filesproperties        = $core->get_files_properties();
        $data->latestBackup           = $core->get_latest_backup_info($site_backups_data); // phpcs:ignore WordPress.NamingConventions.ValidVariableName
        $data->adminUsersList         = $users->get_administrators_user();
        $data->watchfulliApps         = array(
            array(
                'alerts' => $appAlerts->getAppAlerts()
            )
        );

        return new WP_REST_Response( $data );
    }
}
