<?php
namespace GSLOGO;

/**
 * Protect direct access
 */
if ( ! defined( 'ABSPATH' ) ) exit;

class Column {

    public function __construct() {
  
        add_filter( 'manage_edit-gs-logo-slider_columns', [ $this, 'gs_logo_screen_columns' ] );
        add_action( 'manage_posts_custom_column', [ $this, 'gs_logo_columns_content' ], 10, 2 );
        add_action( 'manage_posts_custom_column', [ $this, 'populate_columns' ] );
        add_filter( 'manage_edit-gs-logo-slider_sortable_columns', [ $this, 'gs_logo_sort' ] );     
    }

    public function gs_logo_screen_columns( $columns ) {
        unset( $columns['date'] );
        $columns['gsl_featured_image'] = 'Logo';
        $columns['gs_logo_slider_url_field'] = 'URL';
        $columns['date'] = 'Date';
        return $columns;
    }
       
    public function gs_logo_columns_content( $column_name, $post_ID ) {
        if ($column_name == 'gsl_featured_image') {
            $post_featured_image = gs_get_featured_image( $post_ID );
            if ( $post_featured_image ) {
                echo '<img src="' . esc_url_raw( $post_featured_image ) . '" width="80"/>';
            }
        }
    }
    
    public function populate_columns( $column ) {
    
        if ( 'gs_logo_slider_url_field' == $column ) {
            $client_url = get_post_meta( get_the_ID(), 'client_url', true );
            echo esc_url_raw( $client_url );
        }
    }
        
    public function gs_logo_sort( $columns ) {
        $columns['gs_logo_slider_url_field'] = 'gs_logo_slider_url_field';
     
        return $columns;
    }

}
