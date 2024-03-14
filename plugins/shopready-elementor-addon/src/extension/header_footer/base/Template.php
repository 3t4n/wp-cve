<?php

namespace Shop_Ready\extension\header_footer\base;

use Shop_Ready\helpers\classes\Elementor_Helper;

class Template extends Base_Template{

    public function register() {

	    if ( !file_exists( WP_PLUGIN_DIR . '/elementor/elementor.php' ) ) {

           return false;
		}
		
        add_action( 'wp', array( $this, 'hooks' ) );
		add_action( 'wp_head', array( $this, 'wp_head' ) );
		add_action( 'woo_ready_header_builder', array( $this, 'header_template' ), 10 );	
		add_action( 'woo_ready_footer_builder', array( $this, 'footer_template' ), 10 );
  
 	}

    public function hooks() {
	
		if($this->active_header()) { 
			
			add_action( 'get_header', array( $this, 'render_header' ) );
		}

		if($this->active_footer()) { 
			add_action( 'get_footer', array( $this, 'render_footer' ) );
		}
		
	}

	public function wp_head() {
		
		wp_reset_postdata();
	
    }
   
    public function render( $header, $path ) {

		if( $header->have_posts() ) {

			while ( $header->have_posts() ) {
				$header->the_post();
				load_template( $path );
			}

			wp_reset_postdata();
		}

	}
  
	public function render_header() {
		
      	if ( $this->active_header()) {

			require SHOP_READY_HEADER_FOOTER_PATH . 'templates/default/header.php';
			
			$templates   = array();
			$templates[] = 'header.php';
			
			remove_all_actions( 'wp_head' );
			ob_start();
				locate_template( $templates, true );
			ob_get_clean();

		}
	}

	public function render_footer() {
   
		if ( $this->active_footer()) {

            require SHOP_READY_HEADER_FOOTER_PATH . 'templates/default/footer.php';
			$templates   = array();
			$templates[] = 'footer.php';
			
			remove_all_actions( 'wp_footer' );
			ob_start();
				locate_template( $templates, true );
			ob_get_clean();

		}
    }
  

 
	
	

  
    
}