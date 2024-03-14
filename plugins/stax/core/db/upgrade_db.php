<?php
/**
 * Upgrade DB component.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0.3
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class UpgradeDB extends DB {
    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|UpgradeDB
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function __construct() {
        parent::__construct();
    }

    public function run() {
        $this->doUpgrades();
        update_option( 'stax-version', STAX_VERSION );
    }

    private function __upgrade_103() {
        $tag = get_option( 'stax_options' );

        $charset_collate = $this->db->get_charset_collate();
        global $wpdb;

        $tb_active_headers   = $this->db->prefix . 'stax_active_headers';
        $tb_headers          = $this->db->prefix . 'stax_headers';
        $tb_grp_header       = $this->db->prefix . 'stax_grp_header';
        $tb_grp_header_items = $this->db->prefix . 'stax_grp_header_items';
        $tb_components       = $this->db->prefix . 'stax_components';

        $wpdb->query( "ALTER TABLE `" . $tb_grp_header . "` CHANGE  `header_uuid` `container_uuid` varchar(36) NOT NULL" );
        $wpdb->query( "ALTER TABLE `" . $tb_grp_header_items . "` CHANGE  `header_uuid` `container_uuid` varchar(36) NOT NULL" );

        $wpdb->query( "RENAME TABLE `" . $tb_headers . "` TO `" . $this->tb_containers . "`" );
        $wpdb->query( "RENAME TABLE `" . $tb_grp_header . "` TO `" . $this->tb_container_viewport . "`" );
        $wpdb->query( "RENAME TABLE `" . $tb_grp_header_items . "` TO `" . $this->tb_container_items . "`" );

        $wpdb->query( "CREATE TABLE IF NOT EXISTS `" . $this->tb_zones . "` (
                `id` int(20) unsigned NOT NULL AUTO_INCREMENT,
                `name` varchar(100) NOT NULL,
                `uuid` varchar(36) NOT NULL,
                `slug` varchar(10) DEFAULT NULL,
                `type` tinyint(1) NOT NULL,
                `selector` BLOB NOT NULL,
                `pack` BLOB NOT NULL,
                `condition` BLOB DEFAULT NULL,
                `fonts` BLOB DEFAULT NULL,
                `enabled` tinyint(1) NOT NULL,
                `deleted_at` datetime DEFAULT NULL,
                `created_at` timestamp NOT NULL,
                `updated_at` timestamp NOT NULL,
                PRIMARY KEY (`id`),
                UNIQUE (`uuid`)
              ) $charset_collate;" );

        $selectors = [];
        if ( $tag ) {
            $tags = unserialize( $tag );
            if ( is_array( $tags ) && ! empty( $tags ) ) {
                foreach ( $tags as $theme => $theme_tag ) {
                    $selectors[ $theme ] = [
                        'xpath'      => ( $theme_tag['tagType'] === 'selector' ) ? false : true,
                        'position'   => 1,
                        'visibility' => 1,
                        'path'       => $theme_tag['tag']
                    ];
                }
            }
        }

        $zone = (object) [
            'name'      => 'Header',
            'slug'      => 'header',
            'uuid'      => 'r4fm2lb2',
            'builder'   => '',
            'condition' => [
                [
                    'uuid'        => '9w8dyqx5',
                    'type'        => 'include',
                    'category'    => 'general',
                    'subcategory' => '',
                    'target'      => ''
                ]
            ],
            'selector'  => $selectors,
            'enabled'   => true
        ];

        $containers = [];
        $fonts      = [];

        $active_headers = $this->db->get_results( "SELECT * FROM `" . $tb_active_headers . "` WHERE `deleted_at` IS NULL" );

        foreach ( $active_headers as $a_header ) {
            $a_header = @json_decode( $a_header->pack );
            if ( $a_header ) {
                $containers = array_merge( $containers, $a_header->headers );
                $fonts      = array_merge( $fonts, $a_header->fonts );
            }
        }

        Model_Zones::instance()->createOrUpdate( $zone, array_unique( $containers ), array_unique( $fonts ) );

        $model_container = Model_Container::instance();
        $model_column    = Model_Columns::instance();
        $model_element   = Model_Elements::instance();
        $model_component = Model_Components::instance();

        $saved_containers = $model_container->getAll();
        $saved_columns    = $model_column->getAll();
        $saved_elements   = $model_element->getAll();
        $saved_components = $model_component->get();

        foreach ( $saved_containers as $s_container ) {
            $pack       = @json_decode( $s_container->properties );
            $pack->auth = [
                'logged_in'  => false,
                'logged_out' => false,
                'all'        => true
            ];

            $model_container->createOrUpdate( $pack );
        }

        foreach ( $saved_columns as $s_column ) {
            $pack       = @json_decode( $s_column->properties );
            $pack->auth = [
                'logged_in'  => false,
                'logged_out' => false,
                'all'        => true
            ];
            $model_column->createOrUpdate( $pack );
        }

        foreach ( $saved_elements as $s_element ) {
            $pack       = @json_decode( $s_element->properties );
            $pack->auth = [
                'logged_in'  => false,
                'logged_out' => false,
                'all'        => true
            ];
            $model_element->createOrUpdate( $pack );
        }

        $migrated_components = [];

        foreach ( $saved_components as $s_component ) {
            $pack       = @json_decode( $s_component->properties );
            $pack->auth = [
                'logged_in'  => false,
                'logged_out' => false,
                'all'        => true
            ];

            $migrated_components = [
                'name' => $s_component->name,
                'pack' => $pack
            ];
        }

        $wpdb->query( "TRUNCATE `" . $tb_components . "`" );

        foreach ( $migrated_components as $m_component ) {
            $model_component->create( $m_component['name'], json_encode( $m_component['pack'] ) );
        }

        $this->db->query( "DROP TABLE IF EXISTS $tb_active_headers" );
    }

    private function __upgrade_117() {
        global $wpdb;

        $tb_containers_viewport = $this->db->prefix . 'stax_container_viewport';

        $wpdb->query( "ALTER TABLE `" . $tb_containers_viewport . "` ADD COLUMN  `belongs_to` varchar(36) DEFAULT NULL AFTER `viewport`" );
    }

    private function __upgrade_121() {

        $tb_containers_viewport = $this->db->prefix . 'stax_container_viewport';
        if ( ! $this->table_column_exists( $tb_containers_viewport, 'belongs_to' ) ) {
        	$this->__upgrade_117();
        }
    }

	protected function table_column_exists( $table_name, $column_name ) {
		global $wpdb;
		$column = $wpdb->get_results( $wpdb->prepare(
			"SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = %s AND TABLE_NAME = %s AND COLUMN_NAME = %s ",
			DB_NAME, $table_name, $column_name
		) );
		if ( ! empty( $column ) ) {
			return true;
		}
		return false;
	}

    public function doUpgrades() {
        $old_upgrades = get_option( 'stax-upgrades', [] );

        $upgrades = [
            '1.0.3' => '__upgrade_103',
            '1.1.7' => '__upgrade_117',
            '1.2.1' => '__upgrade_121',
        ];

        $currentVersion = get_option( 'stax-version' );
        foreach ( $upgrades as $version => $function ) {
            if ( version_compare( $currentVersion, $version, '<' ) && ! isset( $old_upgrades[ $version ] ) ) {
                $this->$function();
                $old_upgrades[ $version ] = true;
                update_option( 'stax-upgrades', $old_upgrades );
            }
        }
    }
}
