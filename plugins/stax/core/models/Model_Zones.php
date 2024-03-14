<?php
/**
 * Zones models.
 *
 * @package Stax
 * @author SeventhQueen <themesupport@seventhqueen.com>
 * @since 1.0.2
 */

namespace Stax;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

class Model_Zones extends Base_Model {

    /**
     * @var null
     */
    public static $instance = null;

    /**
     * @return null|Model_Zones
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
        $zones = $this->db->get_results( "SELECT * FROM `" . $this->db->prefix . $this->table_zones . "` WHERE `deleted_at` IS NULL" );

        return $this->prepare_db_result( $zones );
    }

    /**
     * @return array|null|object
     */
    public function getAllEnabled() {
        $zones = $this->db->get_results( "SELECT * FROM `" . $this->db->prefix . $this->table_zones . "` WHERE `enabled` = 1 AND `deleted_at` IS NULL" );

        return $this->prepare_db_result( $zones );
    }

    /**
     * @param $slug
     *
     * @return array|null|object
     */
    public function get_by_slug( $slug ) {
        $zones = $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM `" . $this->db->prefix . $this->table_zones . "` WHERE `slug` = %s AND `enabled` = 1 AND `deleted_at` IS NULL",
                $slug
            )
        );
        if ( $zones ) {
            return $this->prepare_single_db_result( $zones );
        }

        return false;

    }

    /**
     * @param $type
     *
     * @return array|null|object
     */
    public function getByType( $type ) {
        return $this->db->get_results(
            $this->db->prepare(
                "SELECT * FROM `" . $this->db->prefix . $this->table_zones . "` WHERE `type` = %s AND `deleted_at` IS NULL",
                $type
            )
        );
    }

    /**
     * @param $id
     *
     * @return array|null|object
     */
    public function getById( $id ) {
        return $this->db->get_row(
            $this->db->prepare(
                "SELECT * FROM `" . $this->db->prefix . $this->table_zones . "` WHERE `id` = %s AND `deleted_at` IS NULL",
                $id
            )
        );
    }

    /**
     * @param $zone
     * @param $containers
     * @param $fonts
     *
     * @return false|int
     */
    public function createOrUpdate( $zone, $containers, $fonts ) {
        return $this->db->replace(
            $this->db->prefix . $this->table_zones,
            [
                'name'      => $zone->name,
                'uuid'      => $zone->uuid,
                'type'      => 0,
                'slug'      => $zone->slug,
                'selector'  => json_encode( $zone->selector ),
                'pack'      => json_encode( $containers ),
                'condition' => json_encode( $zone->condition ),
                'fonts'     => json_encode( $fonts ),
                'enabled'   => ( $zone->enabled ) ? 1 : 0
            ]
        );
    }

    /**
     * Add new zone with provided data.
     *
     * @param array $data
     *
     * @return mixed|string|bool
     */
    public function make( $data ) {

        if ( ! is_array( $data )
             || ! isset( $data['zone'] )
             || ! isset( $data['containers'] )
             || ! isset( $data['columns'] )
             || ! isset( $data['elements'] )
             || ! isset( $data['group'] )
             || ! isset( $data['fonts'] )

        ) {
            return false;
        }

        if ( is_object( $data['zone'] ) || is_array( $data['zone'] ) ) {
            $zone = $data['zone'];
        } else {
            $zone = json_decode( $data['zone'] );
        }

        if ( is_object( $data['containers'] ) || is_array( $data['containers'] ) ) {
            $containers = $data['containers'];
            if ( is_array( $containers ) ) {
                $containers = (object) $containers;
            }
        } else {
            $containers = json_decode( $data['containers'] );
        }

        if ( is_object( $data['columns'] ) || is_array( $data['columns'] ) ) {
            $columns = $data['columns'];
            if ( is_array( $columns ) ) {
                $columns = (object) $columns;
            }
        } else {
            $columns = json_decode( $data['columns'] );
        }

        if ( is_object( $data['elements'] ) || is_array( $data['elements'] ) ) {
            $elements = $data['elements'];

            if ( is_array( $elements ) ) {
                $elements = (object) $elements;
            }

        } else {
            $elements = json_decode( $data['elements'] );
        }

        if ( is_object( $data['group'] ) || is_array( $data['group'] ) ) {
            $group = $data['group'];
        } else {
            $group = json_decode( $data['group'] );
        }

        if ( is_object( $data['fonts'] ) || is_array( $data['fonts'] ) ) {
            $fonts = $data['fonts'];
        } else {
            $fonts = json_decode( $data['fonts'] );
        }

        $saveFonts = [];

        if ( $group ) {
            $columnsStack    = [];
            $elementsStack   = [];
            $containersStack = [];

            foreach ( $group->containers as $containerUuid => $container ) {
                $containersStack[] = $containerUuid;
                Model_ContainerItems::instance()->deleteByContainerUuid( $containerUuid );
                foreach ( $container as $items ) {
                    foreach ( $items as $resolution => $item ) {
                        Model_ContainerViewport::instance()->create( $containerUuid, $resolution, $item, $item->belongsTo );
                        foreach ( $item->columns as $columnUuid => $column ) {
                            Model_ContainerItems::instance()->create( $containerUuid, $columnUuid, $column, $resolution );

                            if ( ! in_array( $columnUuid, $columnsStack ) ) {
                                $columnsStack[] = $columnUuid;
                            }

                            foreach ( $column->elements as $elementUuid => $element ) {
                                if ( ! in_array( $elementUuid, $elementsStack ) ) {
                                    $elementsStack[] = $elementUuid;
                                }
                            }
                        }
                    }
                }

                $saveFonts = $this->getItemFonts( $saveFonts, $fonts, $containers->{$containerUuid} );
                Model_Container::instance()->createOrUpdate( $containers->{$containerUuid} );
            }

            foreach ( $columnsStack as $column ) {
                $saveFonts = $this->getItemFonts( $saveFonts, $fonts, $columns->{$column} );
                Model_Columns::instance()->createOrUpdate( $columns->{$column} );
            }

            foreach ( $elementsStack as $element ) {
                $saveFonts = $this->getItemFonts( $saveFonts, $fonts, $elements->{$element} );
                Model_Elements::instance()->createOrUpdate( $elements->{$element} );
            }

            return $this->createOrUpdate( $zone, $containersStack, $saveFonts );
        }

        return false;
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed|\WP_REST_Response
     */
    public function save( \WP_REST_Request $request ) {
        $data = $request->get_params();

        $response = $this->make( $data );
        if ( ! $response ) {
            return $this->response( self::STATUS_FAILED );
        } else {
            return $this->response( self::STATUS_OK );
        }
    }

    /**
     * @param \WP_REST_Request $request
     *
     * @return mixed|\WP_REST_Response
     */
    public function delete( \WP_REST_Request $request ) {
        $data = $request->get_params();

        if ( ! isset( $data['uuid'] ) ) {
            return $this->response( self::STATUS_FAILED );
        }

        $this->db->update(
            $this->db->prefix . $this->table_zones,
            [
                'deleted_at' => ( new \DateTime() )->format( 'Y-m-d H:i:s' )
            ],
            [
                'uuid' => $data['uuid']
            ]
        );

        return $this->response( self::STATUS_OK );
    }

    /**
     * Prepare database result
     *
     * @param null $zones
     *
     * @return array|null
     */
    private function prepare_db_result( $zones = null ) {

        $result = [];

        if ( null === $zones ) {
            return $result;
        }

        foreach ( $zones as $zone ) {
            $result[ $zone->uuid ] = [
                'name'      => $zone->name,
                'uuid'      => $zone->uuid,
                'slug'      => $zone->slug,
                'pack'      => $zone->pack,
                'condition' => @json_decode( $zone->condition ),
                'selector'  => @json_decode( $zone->selector ),
                'fonts'     => @json_encode( $zone->fonts ),
                'enabled'   => ( $zone->enabled ) ? 1 : 0
            ];
        }

        return $result;
    }


    /**
     * Prepare database result
     *
     * @param null $zones
     *
     * @return array|null
     */
    private function prepare_single_db_result( $zones = null ) {

        $result = [];

        if ( null === $zones ) {
            return $result;
        }

        foreach ( $zones as $zone ) {
            return [
                'name'      => $zone->name,
                'uuid'      => $zone->uuid,
                'slug'      => $zone->slug,
                'pack'      => $zone->pack,
                'condition' => @json_decode( $zone->condition ),
                'selector'  => @json_decode( $zone->selector ),
                'fonts'     => @json_encode( $zone->fonts ),
                'enabled'   => ( $zone->enabled ) ? 1 : 0
            ];
        }

    }
}

