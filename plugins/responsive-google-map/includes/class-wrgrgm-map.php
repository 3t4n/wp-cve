<?php

defined( 'ABSPATH' ) || exit;

if ( ! class_exists('WRGRGM_Map') ) {

    final class WRGRGM_Map {

        /**
         * Self Instance
         *
         * @var instance
         */
        private static $instance;

        public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new self();
			}
			return self::$instance;
        }

        public static function get_maps() {

            $post_data = get_posts(array(
                'post_type'     => 'wrg_rgm',
                'numberposts'   => 100,
                'post_status'   => 'publish',
                'orderby'       => 'post_title',
                'order'         => 'ASC'
            ));
    
            wp_reset_postdata();

            return $post_data;
        }

        public static function render( $mapId ) {

            if ( empty( $mapId ) ) {
                return;
            }

            $post_data = get_posts(array(
                'post_type'     => 'wrg_rgm',
                'page_id'       => $mapId,
                'numberposts'   => 1,
                'post_status'   => 'publish'
            ));
    
            wp_reset_postdata();

            $map_element = '';

            if ( $post_data ) {
                $post = $post_data[0];
    
                $map_settings       = MapSettings::get( $post->ID );
                $simple_marker      = new Marker( 'simple', $post->ID );
                $advanced_marker    = new Marker( 'advanced', $post->ID );
                $map_style          = '';
    
                $markers = array(
                    'simple' => $simple_marker->get_markers(),
                    'advanced' => $advanced_marker->get_markers()
                );
                
                if ( isset($map_settings['map_style']) && ! empty($map_settings['map_style']) ) {
                    $map_style = wrg_rgm()->map_styles->get_style_data( $map_settings['map_style'] );
                    $map_style = ! empty($map_style) ? htmlspecialchars(json_encode(json_decode($map_style)), ENT_QUOTES, 'UTF-8') : $map_style;
                }
    
                $map_width  = filter_var( $map_settings['container_width'], FILTER_SANITIZE_NUMBER_INT) . ( isset($map_settings['cw_suffix']) ? $map_settings['cw_suffix'] : '%' );
                $map_height = filter_var( $map_settings['container_height'], FILTER_SANITIZE_NUMBER_INT) . ( isset($map_settings['ch_suffix']) ? $map_settings['ch_suffix'] : 'px' );
    
                $map_element = sprintf(
                    '<div 
                        id="wrg-rgm-%1$s" 
                        style="width:%2$s;height:%3$s;" 
                        class="wrg-rgm-container" 
                        data-map-style="%4$s" 
                        data-map-settings="%5$s" 
                        data-markers="%6$s"
                    ></div>',
                    $post->ID, 
                    $map_width, 
                    $map_height,
                    $map_style,
                    htmlspecialchars( json_encode($map_settings), ENT_QUOTES, 'UTF-8' ),
                    htmlspecialchars( json_encode($markers), ENT_QUOTES, 'UTF-8' )
                );
            }

            return $map_element;
        }
    }

    WRGRGM_Map::get_instance();
}