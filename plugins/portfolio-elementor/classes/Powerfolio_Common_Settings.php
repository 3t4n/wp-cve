<?php

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}
/**
 * Portfolio: Customization Options
 *
 */
class Powerfolio_Common_Settings
{
    public function __construct()
    {
    }
    
    public static function get_yes_no_options()
    {
        return [
            'yes' => __( 'Yes', 'powerfolio' ),
            'no'  => __( 'No', 'powerfolio' ),
        ];
    }
    
    public static function get_column_options()
    {
        $column_array = array(
            '2' => __( 'Two Columns', 'powerfolio' ),
            '3' => __( 'Three Columns', 'powerfolio' ),
            '4' => __( 'Four Columns', 'powerfolio' ),
            '5' => __( 'Five Columns', 'powerfolio' ),
            '6' => __( 'Six Columns', 'powerfolio' ),
        );
        return $column_array;
    }
    
    public static function get_column_mobile_options()
    {
        $column_mobile_array = array(
            '1' => __( 'One (default)', 'powerfolio' ),
            '2' => __( 'Two Columns', 'powerfolio' ),
            '3' => __( 'Three Columns', 'powerfolio' ),
        );
        return $column_mobile_array;
    }
    
    public static function get_grid_options()
    {
        $grid_options = array(
            'masonry'       => __( 'Masonry', 'powerfolio' ),
            'box'           => __( 'Boxes', 'powerfolio' ),
            'purchasedgrid' => __( 'Customized Grid Service', 'powerfolio' ),
        );
        return $grid_options;
    }
    
    public static function get_hover_options()
    {
        $grid_options = array();
        $grid_options = array(
            'simple'  => __( 'Style 0: Simple', 'powerfolio' ),
            'hover1'  => __( 'Style 1: From Bottom', 'powerfolio' ),
            'hover2'  => __( 'Style 2: From Top', 'powerfolio' ),
            'hover16' => __( 'Style 16: Content Visible 2', 'powerfolio' ),
            'hover17' => __( 'Style 17: Content Visible 1', 'powerfolio' ),
        );
        return $grid_options;
    }
    
    public static function get_lightbox_options( $source = '' )
    {
        $options = array(
            'image'   => __( 'Image (with the Powerfolio lightbox)', 'powerfolio' ),
            'project' => __( 'Project Details Page', 'powerfolio' ),
        );
        if ( $source == 'elementor' ) {
            $options['image_elementor'] = __( 'Image (with Elementor default lightbox)', 'powerfolio' );
        }
        return $options;
    }
    
    public static function get_post_types( $args = array() )
    {
        if ( empty($args) ) {
            $args = array(
                'public' => true,
            );
        }
        return get_post_types( $args );
    }
    
    public static function get_portfolio_taxonomy_terms()
    {
        $terms = get_terms( array(
            'taxonomy'   => 'elemenfoliocategory',
            'fields'     => 'id=>name',
            'hide_empty' => false,
        ) );
        return $terms;
    }
    
    public static function generate_element_id( $key = 'elpt_powerfolio' )
    {
        return $key . '_' . rand( 0, 99999 );
    }
    
    public static function get_upgrade_message( $source = '' )
    {
        $raw = '';
        $raw .= '<div style="border: 1px solid #eee; padding: 10px; background: #eee; border-radius: 6px;">';
        $raw .= '<h3 style="font-weight: bold; tet-transform: uppercase; font-size: 14px; margin-bottom: 10px; text-trasnform: uppercase;">' . __( 'ENABLE ALL FEATURES', 'powerfolio' ) . '</h3>';
        $raw .= '<p style="margin-bottom: 10px; font-size: 12px; line-heigh: 22px;">' . __( 'Upgrade your plugin to PRO version and unlock all features!', 'powerfolio' ) . '</p>';
        $raw .= '<a href="' . pe_fs()->get_upgrade_url() . '" style="background: #ea0e59; color: #fff; font-weight: bold; padding: 5px 10px; border-radius: 3px; display: inline-block; font-size: 14px; text-transform: uppercase;">' . __( 'Click here to Upgrade', 'powerfolio' ) . '</a>';
        //$raw .='<hr style="margin-top: 20px; margin-bottom: 20px;">';
        
        if ( $source == 'elementor' ) {
            $raw .= '<p style="margin-bottom: 10px; font-size: 12px; font-style: italic; margin-top: 5px;">' . __( 'Get access to the Paginated Grid (NEW), Special Grids, Grid Builder, extra CSS effects, several customization options and much more!', 'powerfolio' ) . '</p>';
        } else {
            $raw .= '<p style="margin-bottom: 10px; font-size: 12px; font-style: italic; margin-top: 5px;">' . __( 'Get access to the Paginated Grid (NEW), Special Grids, extra CSS effects, several customization options and much more!', 'powerfolio' ) . '</p>';
        }
        
        /*$raw .='<ul style="list-style-type: circle; list-style-position: outside; font-style: italic;">';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- Grid Builder', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- All customization options enabled for both widgets (portfolio and image gallery)', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- 15+ hover effects', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- 8 grid styles', 'powerfolio').'</li>';						
        			$raw .='<li style="margin-bottom: 5px;">'.__('- Extra CSS effects', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- Option to display a specific portfolio category', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- Option to display content from any post type to the grid', 'powerfolio').'</li>';
        			$raw .='<li style="margin-bottom: 5px;">'.__('- Extra customization options', 'powerfolio').'</li>';
        		$raw .='</ul>';*/
        $raw .= '</div>';
        return $raw;
    }
    
    /*
     * get_image_url_for_gallery
     */
    public static function get_image_url( $img_identifier, $img_size = "" )
    {
        $image_url = '';
        // Check if $img_identifier is an image ID
        
        if ( is_numeric( $img_identifier ) ) {
            $image_url = wp_get_attachment_url( $img_identifier );
        } else {
            if ( filter_var( $img_identifier, FILTER_VALIDATE_URL ) ) {
                $image_url = $img_identifier;
            }
        }
        
        // Apply filter to the image URL if needed
        return apply_filters(
            'powerfolio_filter_image_url',
            $image_url,
            $img_identifier,
            $img_size
        );
    }

}