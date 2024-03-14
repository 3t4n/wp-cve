<?php

class admin_menu_bogo_by_sp{

    public $plugin_name;
    public $menu;
    
    function __construct($plugin_name , $version){
        $this->plugin_name = $plugin_name;
        $this->version = $version;
        add_action( 'admin_menu', array($this,'plugin_menu') );
        add_action( 'wp_ajax_Sp_Bogo_Free_Product', array( $this, 'search_product' ) ); 
		add_action( 'wp_ajax_Sp_Bogo_Buy_Product', array( $this, 'search_product' ) ); 
    }

    function plugin_menu(){
        
        $this->menu = add_menu_page(
            __( 'Buy X Get Y'),
            __( 'Buy X Get Y'),
            'manage_options',
            'BuyXGetY-by-storepro',
            array($this, 'menu_option_page'),
            'dashicons-store',
            6
        );

        add_action("load-".$this->menu, array($this,"bootstrap_style"));
 
    }

    public function bootstrap_style() {

        
        
        wp_enqueue_style( $this->plugin_name."_bootstrap", plugin_dir_url( __FILE__ ) . 'css/bootstrap.css', array(), $this->version, 'all' );	
        wp_register_script( 'selectWoo', WC()->plugin_url() . '/assets/js/selectWoo/selectWoo.full.min.js', array( 'jquery' ) );
        wp_enqueue_script( 'selectWoo' );
        wp_enqueue_style( 'select2', WC()->plugin_url() . '/assets/css/select2.css');
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bogo_by_sp_class_admin.js', array( 'jquery', 'selectWoo' ), $this->version, false );
		
	}

    function menu_option_page(){
        ?>
                <div class="col-sm-6 sp6">
                <div class="">
                    <div class="row">
                        <div class="col-sm-12 spmain">
                        <?php do_action($this->plugin_name.'_tab_content'); ?>
                        </div> 
                    </div>
				</div>
				</div>
        <?php
    }


    public function search_product( $x = '', $post_types = array( 'product' ) ) {

		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

        ob_start();
        
        if(!isset($_GET['keyword'])) die;

		$keyword = isset($_GET['keyword']) ? sanitize_text_field($_GET['keyword']) : "";

		if ( empty( $keyword ) ) {
			die();
		}
		$arg            = array(
			'post_status'    => 'publish',
			'post_type'      => $post_types,
			'posts_per_page' => 50,
			's'              => $keyword

		);
		$the_query      = new WP_Query( $arg );
		$found_products = array();
		if ( $the_query->have_posts() ) {
			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				$prd = wc_get_product( get_the_ID() );
				$cat_ids  = wp_get_post_terms( get_the_ID(), 'product_cat', array( 'fields' => 'ids' ) );

				/* remove grouped product or external product */
				if($prd->is_type('grouped') || $prd->is_type('external')){
					continue;
				}
				

				if ( $prd->has_child() && $prd->is_type( 'variable' ) ) {
					
				} else {
					$product_id    = get_the_ID();
					$product_title = get_the_title();
					$the_product   = new WC_Product( $product_id );
					if ( ! $the_product->is_in_stock() ) {
						$product_title .= ' (Out of stock)';
					}
					$product          = array( 'id' => $product_id, 'text' => $product_title );
					$found_products[] = $product;
				}
			}
        }
		wp_send_json( $found_products );
		die;
    }

}