<?php 

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Marker {

    /**
     * Marker Type
     *
     * @var string
     */
    protected $marker_type;

    /**
     * Simple Markers
     *
     * @var array
     */
    protected $simple_markers;

    /**
     * Advanced Markers
     *
     * @var array
     */
    protected $advanced_markers;

    /**
     * Post Id
     *
     * @var int
     */
    protected $post_id;

    /**
     * Simple Marker Key
     * 
     * @var string
     */
    const SIMPLE_MARKER_KEY     = '_rgm_simple_markers';

    /**
     * Advanced Marker Key
     * 
     * @var string
     */
    const ADVANCED_MARKER_KEY   = '_rgm_advanced_markers';

    public function __construct( $marker_type, $post_id ) {

        $this->marker_type = $marker_type;
        $this->post_id = $post_id;

        if ( $this->marker_type == 'simple' ) {
            $this->simple_markers = get_post_meta( 
                $this->post_id, 
                self::SIMPLE_MARKER_KEY, 
                true 
            );
        }
    
        if ( $this->marker_type == 'advanced' ) {
            $this->advanced_markers = get_post_meta( 
                $this->post_id, 
                self::ADVANCED_MARKER_KEY, 
                true 
            );
        }
    }

    /**
     * Get Markers Entries
     *
     * @return mixed array|blank
     */
    public function get_markers() {
        return $this->marker_type == 'simple' ? 
            $this->simple_markers : 
            $this->advanced_markers;
    }

    /**
     * Get Single Marker Entry
     *
     * @param string $uuid
     * @return mixed return array data based on unique key otherwise return null
     */
    public function get_marker( $uuid ) {

        $marker = null;
        $markers = $this->get_markers();

        foreach( $markers as $m ) {
            if ( $m['uuid'] == $uuid ) {
                $marker = $m;
                break;
            }
        }

        return $marker;
    }

    /**
     * Check Marker exists based on unique id
     *
     * @param string $uuid
     * @return mixed return key index if data exists otherwise return null
     */
    protected function has_marker( $uuid ) {
        
        $index = null;
        $markers = $this->marker_type == 'simple' ? $this->simple_markers : $this->advanced_markers;

        foreach ( $markers as $k => $v ) {
            if ( $v['uuid'] == $uuid ) {
                $index = $k;
                break;
            }
        }

        return $index;
    }

    /**
     * Update / Merge Marker data
     *
     * @param string $key
     * @param array $markers
     * @param array $post_data
     * @return void
     */
    private function update_marker_data( $key, $markers, $post_data ) {

        $marker_index = $this->has_marker( $post_data['uuid'] );

        if ( ! is_null( $marker_index ) ) {
            $markers[ $marker_index ] = $post_data;
        }
        else {
            array_push( $markers, $post_data );
        }

        update_post_meta( $this->post_id, $key, $markers );
    }

    /**
     * Add Marker Data
     *
     * @param array $marker_data 
     * @return void
     */
    public function add( $marker_data ) {

        $markers    = $this->get_markers();
        $key        = $this->marker_type == 'simple' ? self::SIMPLE_MARKER_KEY : self::ADVANCED_MARKER_KEY;

        if ( empty( $markers ) ) {
            update_post_meta( $this->post_id, $key, array($marker_data) );
        }
        else {
            $this->update_marker_data( $key, $markers, $marker_data );
        }
    }

    /**
     * Delete Marker Entry based on unique id
     *
     * @param string $uuid
     * @return boolean
     */
    public function delete( $uuid ) {

        $marker_index   = $this->has_marker( $uuid );
        $marker_key     = $this->marker_type == 'simple' ? self::SIMPLE_MARKER_KEY : self::ADVANCED_MARKER_KEY;
        
        if ( ! is_null( $marker_index ) ) {
            $markers = $this->get_markers();
            unset( $markers[$marker_index] );

            // reset array keys
            $markers = array_values( $markers );

            update_post_meta( $this->post_id, $marker_key, $markers );
            return true;
        }

        return false;
    }
}