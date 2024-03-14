<?php
/**
 * Container models.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Model_Container extends Base_Model {

    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|Model_Container
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return array|null|object
     */
    public function getAll() {
        $result = $this->db->get_results( "SELECT * FROM `" . $this->db->prefix . $this->table_containers . "` WHERE `deleted_at` IS NULL" );

        return $result;
    }

    /**
     * @param $uuid
     *
     * @return array|null|object
     */
    public function get( $uuid ) {
        $result = $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM `" . $this->db->prefix . $this->table_containers . "` WHERE `deleted_at` IS NULL AND `uuid` = %s",
                $uuid
            )
        );

        return $result;
    }

    /**
     * @param $container
     */
    public function createOrUpdate( $container ) {
        $container = $this->clearTrash( $container );

        $this->db->replace(
            $this->db->prefix . $this->table_containers,
            [
                'uuid'       => $container->uuid,
                'properties' => json_encode( $container )
            ]
        );
    }
}
