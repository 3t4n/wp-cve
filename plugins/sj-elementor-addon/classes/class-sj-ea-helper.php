<?php 

if ( ! class_exists( 'SJEaHelper' ) ) {
	
	/**
	* Responsible for setting up constants, classes and includes.
	*
	* @since 0.1
	*/
	final class SJEaHelper {
		
		function __construct() {
			add_action( 'elementor/init', array( $this, 'add_elementor_category' ) );
			add_action( 'admin_head', array( $this, 'localize_scripts' ) );
			add_action( 'wp_head', array( $this, 'localize_scripts' ) );
			add_action( 'elementor/frontend/after_enqueue_styles', array( $this, 'sjea_frontend_styles' ) );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'sjea_frontend_styles' ) );
			add_action( 'elementor/frontend/before_enqueue_scripts', array( $this, 'sjea_frontend_scripts' ) );
		}

		function add_elementor_category(){
		    Elementor\Plugin::instance()->elements_manager->add_category(
		        'sjea-elements',
		        [
		            'title'  => 'SJEA Elements',
		            'icon' => 'font'
		        ],
		        1
		    );
		}

		function localize_scripts(){ ?>
			<script type="text/javascript">
    			var ajaxurl = <?php echo json_encode( admin_url( "admin-ajax.php" ) ); ?>;      
    			var ajaxnonce = <?php echo json_encode( wp_create_nonce( "itr_ajax_nonce" ) ); ?>;
    			var sjea = <?php echo json_encode( array( 
         			'ajaxurl' => admin_url( "admin-ajax.php" )
       			) ); ?>
  			</script><?php
		}

		function sjea_frontend_scripts() {
			wp_enqueue_script(
		   		'sjea-frontend',
		   		SJ_EA_URL . 'assets/sjea-frontend.js',
		   		[],
				SJ_EA_VERSION,
				true // in_footer
		   );
		}

		function sjea_frontend_styles() {
			wp_enqueue_style(
				'sjea-fronten',
				SJ_EA_URL . 'assets/sjea-frontend.css',
				[],
				SJ_EA_VERSION
			);
		}
		
	}

	new SJEaHelper();
}