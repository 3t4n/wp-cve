<?php
/**
 * Controller for managing Backups.
 *
 * @version   2016-12-20 11:41 UTC+01
 * @package   Watchful WP Client
 * @author    Watchful
 * @authorUrl https://watchful.net
 * @copyright Copyright (c) 2020 watchful.net
 * @license   GNU/GPL
 */

namespace Watchful\Controller;

use Ai1wm_Backups;
use Ai1wm_Export_Controller;
use Ai1wm_Status_Controller;
use Watchful\Helpers\Authentification;
use Watchful\Helpers\BackupPluginHelper;
use Watchful\Helpers\BackupPlugins\XClonerBackupPlugin;
use WP_REST_Request;
use WP_REST_Server;
use Watchful\Exception;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Class Backups
 *
 * @package Watchful
 */
class Backups implements BaseControllerInterface {

    private $backupPluginHelper;

    public function __construct()
    {
        $this->backupPluginHelper = new BackupPluginHelper();
    }


    /**
     * Register WP REST API routes.
     */
    public function register_routes() {
        register_rest_route(
            'watchful/v1',
            '/backup/ai1wm',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'execute_all_in_one_migration_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => Authentification::get_arguments(),
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/ai1wm/step',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'step_all_in_one_migration_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'archive'        =>  array(
                                'default' => null,
                            ),
                            'priority'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/ai1wm/list',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'list_all_in_one_migration_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'limit'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/ai1wm/status',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'status_all_in_one_migration_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'limit'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/xcloner',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'execute_xcloner_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => Authentification::get_arguments(),
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/xcloner/step',
            array(
                array(
                    'methods'             => WP_REST_Server::CREATABLE,
                    'callback'            => array( $this, 'step_xcloner_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'archive'        =>  array(
                                'default' => null,
                            ),
                            'priority'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/xcloner/list',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'list_xcloner_backup' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'limit'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
        register_rest_route(
            'watchful/v1',
            '/backup/xcloner/data',
            array(
                array(
                    'methods'             => WP_REST_Server::READABLE,
                    'callback'            => array( $this, 'data_xcloner' ),
                    'permission_callback' => array( 'Watchful\Routes', 'authentification' ),
                    'args'                => array_merge(
                        Authentification::get_arguments(),
                        array(
                            'limit'        =>  array(
                                'default' => null,
                            ),
                        )
                    )
                ),
            )
        );
    }

    /**
     * Execute "All-in-One WP Migration" backup
     *
     * @throws Exception If AI1WM secret key is not defined .
     */
    public function execute_all_in_one_migration_backup () {
        if ( ! defined( 'AI1WM_SECRET_KEY' ) ) {
            throw new Exception( 'AI1WM_SECRET_KEY is not defined', 400 );
        }
        if ( ! defined( 'AI1WM_STORAGE_PATH' ) ) {
            throw new Exception( 'AI1WM_STORAGE_PATH is not defined', 400 );
        }
        if (class_exists('Ai1wm_Directory')) {
            \Ai1wm_Directory::create(AI1WM_STORAGE_PATH);
        }
        Ai1wm_Export_Controller::export(
            array(
                'ai1wm_manual_export' => 1,
                'storage' => 'Watchful',
                'secret_key' => get_option( AI1WM_SECRET_KEY ),
            )
        );
    }

    /**
     * Continues previously started backup process
     *
     * @param WP_REST_Request $request
     * @throws Exception
     */
    public function step_all_in_one_migration_backup (WP_REST_Request $request) {
        if ( ! defined( 'AI1WM_SECRET_KEY' ) ) {
            throw new Exception( 'AI1WM_SECRET_KEY is not defined', 400 );
        }
        Ai1wm_Export_Controller::export(
            array_merge(
                array(
                    'ai1wm_manual_export' => 1,
                    'storage' => 'Watchful',
                    'secret_key' => get_option( AI1WM_SECRET_KEY ),
                ),
                $request->get_params()
            )
        );
    }

    /**
     * Get "All-in-One WP Migration" backup list
     *
     * @param WP_REST_Request $request
     * @return array
     */
    public function list_all_in_one_migration_backup(WP_REST_Request $request) {
        $backups = $this->backupPluginHelper->get_backup_list('ai1wm');
        if (!empty($request->get_param('limit'))) {
            return array_slice($backups, 0 , $request->get_param('limit') );
        }
        return $backups;
    }


    /**
     * get current status of previously started backup process
     *
     * @param WP_REST_Request $request
     * @throws Exception
     */
    public function status_all_in_one_migration_backup (WP_REST_Request $request) {
        if ( ! defined( 'AI1WM_SECRET_KEY' ) ) {
            throw new Exception( 'AI1WM_SECRET_KEY is not defined', 400 );
        }
        Ai1wm_Status_Controller::status(
            array_merge(
                array(
                    'ai1wm_manual_export' => 1,
                    'storage' => 'Watchful',
                    'secret_key' => get_option( AI1WM_SECRET_KEY ),
                ),
                $request->get_params()
            )
        );
    }

    /**
     * Execute "XCloner" backup
     *
     * @throws Exception If AI1WM secret key is not defined .
     */
    public function execute_xcloner_backup (WP_REST_Request $request) {
        return (new XClonerBackupPlugin())->start_backup();
    }

    /**
     * Continues previously started backup process
     *
     * @param WP_REST_Request $request
     * @return array
     * @throws Exception
     */
    public function step_xcloner_backup (WP_REST_Request $request) {
        $body = json_decode($request->get_body(), true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception('Cannot decode request body');
        }
        return (new XClonerBackupPlugin($body['hash']))->step_backup(
            $body['params']
        );
    }

    /**
     * Get "XCloner" backup list
     *
     * @param WP_REST_Request $request
     * @return array
     * @throws Exception
     */
    public function list_xcloner_backup(WP_REST_Request $request) {
        return $this->backupPluginHelper->get_backup_list('xcloner');
    }


    /**
     * Get "XCloner" available remote storage
     *
     * @param WP_REST_Request $request
     * @return array
     * @throws Exception
     */
    public function data_xcloner(WP_REST_Request $request) {
        return [
            'remote_storage' => (new XClonerBackupPlugin)->get_available_remote_storage()
        ];
    }
}
