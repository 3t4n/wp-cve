<?php
/**
* Plugin Name: Woocommerce Catalog
* Plugin URI: https://wordpress.org/plugins/
* Description: Switch to Catalog mode with one click. 
* Version: 1.2.5
* Author: Leonidas Maroulis
* Text Domain: woocommerce-catalog
* Author URI: http://www.maroulis.net.gr
**/
class Woocommerce_Catalog_Mode {
    public $version = '20160604'; // Latest version release date Year-Month-Day
	public $url = ''; // URL of plugin installation
	public $path = ''; // Path of plugin installation
	public $file = ''; // Path of this file
    public $settings; // Settings object
	
	//options
	public $catalog_mode;
	public $remove_add_to_cart_button;
	public $user_groups;
	public $woo_categories;
	public $add_custom_button;
	public $custom_button_type;
	public $remove_price;
	public $load_more_button_text;
	public $custom_button_link;
	
	public $catalog_on=false;
	
	function __construct() {
        $this->file = __file__;
        $this->path = dirname($this->file) . '/';
        $this->url = WP_PLUGIN_URL . '/' . plugin_basename(dirname(__file__)) . '/';
		
		require_once ($this->path . 'include/php/settings.php');
		
		$this->settings = new Woo_Catalog_Settings($this->file);
		
		//assign options
		$this->catalog_mode=get_option('woo_Catalog_catalog_mode');
		$this->remove_add_to_cart_button=get_option('woo_Catalog_remove_add_to_cart_button');
		$this->remove_price=get_option('woo_Catalog_remove_price');
		$this->add_custom_button=get_option('woo_Catalog_add_custom_button');
		$this->custom_button_type =get_option('woo_Catalog_custom_button_type');
		$this->custom_button_link =get_option('woo_Catalog_custom_button_link');
		$this->user_groups =get_option('woo_Catalog_catalog_groups');
		$this->woo_categories =get_option('woo_Catalog_categories');
		
		if ($this->catalog_mode=="on"){
		
		add_action('plugins_loaded', array($this,'configCatalog'));
		
		add_action('wp_head', array($this,'register_frontend_assets'));
		add_action('wp_enqueue_scripts', array($this, 'load_frontend_assets'));
		
		}
		
		add_action('plugins_loaded', array($this,'configLang'));
		
    }
	
	public function woo_catalogue_apply(){
		//check for user groups
		if($this->catalog_on){
				if ($this->remove_add_to_cart_button=="on"){
					add_action('init',array($this,'remove_add_to_cart_button'));
				}
				if ($this->remove_price=="on"){
					add_action('init',array($this,'remove_price'));
				}
				
	
			
		}
	}
	
	public function register_frontend_assets() {
        // Add frontend assets in footer
		//wp_register_style('woocommerce-catalog-frontend-custom-style', $this->url . 'include/css/style.php');
		
		echo "<style>
		#woocommerce-catalog_custom_button {
			background: #". get_option('woo_Catalog_button_background') .";
			color: #". get_option('woo_Catalog_button_color').";
			padding: ". get_option('woo_Catalog_button_padding')."px;
			width: ". get_option('woo_Catalog_button_width')."px;
			height: ". get_option('woo_Catalog_button_height')."px;
			line-height: ". get_option('woo_Catalog_button_height')."px;
			border-radius:". get_option('woo_Catalog_button_border_radius')."px;
			font-size: ". get_option('woo_Catalog_button_font_size')."px;
			border:  ". get_option('woo_Catalog_button_border_width')."px;  solid   #".get_option('woo_catalogue_button_border_color')."
		}
		#woocommerce-catalog_custom_button:hover {
			background: #". get_option('woo_Catalog_button_background_hover').";
			color: #". get_option('woo_Catalog_button_color_hover').";
		}
		</style>
		";
		
		
	
		
    }
	
	
	public function load_frontend_assets() {
		//load all scripts
		wp_enqueue_style( 'woocommerce-catalog-frontend-custom-style' );
    }
	
	public function remove_add_to_cart_button(){
		
			if ($this->add_custom_button=="on"){
				add_filter('woocommerce_loop_add_to_cart_link', array($this,'custom_button'),10);
				add_action( 'woocommerce_after_shop_loop_item', array($this,'set_up_template_add_to_cart'),1 );	
			}else{
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
				add_action( 'woocommerce_after_shop_loop_item', array($this,'set_up_template_add_to_cart'),1 );					
			}
			
			remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			add_action( 'woocommerce_single_product_summary', array($this,'set_up_template_add_to_cart'), 1 );
	}
	
	public function custom_button(){
			$this->load_more_button_text= get_option('woo_Catalog_button_text')==""?__( 'More', 'woocommerce-catalog' ):get_option('woo_Catalog_button_text');
			
			if ($this->custom_button_type=="custom_button_type_read_more"){
				global $product;
				echo ' <a id="woocommerce-catalog_custom_button" href="' . esc_url( $product->get_permalink( $product->id ) ) . '" class="single_add_to_cart_button button alt">'.$this->load_more_button_text.'</a>
					  </a>';
			}else{
				echo ' <a id="woocommerce-catalog_custom_button" href="' . $this->custom_button_link . '" class="single_add_to_cart_button button alt">'.$this->load_more_button_text.'</a>
					  </a>';
			}
	
	}
	
	public function set_up_template_add_to_cart(){
		if($this->shouldExcludeCategory()){//excluded categories bring back add to cart
			remove_filter('woocommerce_loop_add_to_cart_link', array($this,'custom_button'),10);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
			
		}else{
			//in catalog
			if ($this->add_custom_button!="on"){
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
			}else{
				add_filter('woocommerce_loop_add_to_cart_link', array($this,'custom_button'),10);
			}
		}
	}
	
	public function remove_price(){
		
		remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
		add_action( 'woocommerce_single_product_summary', array($this,'set_up_template_price'), 5 );
		remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );	
		add_action( 'woocommerce_after_shop_loop_item_title', array($this,'set_up_template_price'), 1 );
	}
	
	public function set_up_template_price(){
			if($this->shouldExcludeCategory()){
				add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
			}else{
				remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );	
			}
	}
	
	
	public function shouldExcludeCategory() {
		//if all categories selected get out of here
		if(in_array("all",$this->woo_categories)){
			return false;
		}
		//get terms for each product
		global $product;
		$terms = get_the_terms( $product->id, 'product_cat' );
		if($terms){
			foreach($terms as $term){
				$cat_id = $term->term_id;
				if(in_array($cat_id,$this->woo_categories)){
					return false;
				}
			}
		}
		
		return true;
    }
	
	
	public function version() {
        return $this->version;
    }
	
	public function configLang(){
		$lang_dir = basename(dirname(__FILE__)). '/languages';
		load_plugin_textdomain( 'woocommerce-catalog', false, $lang_dir );
	}
	
	public function configCatalog(){
	
		if($this->user_groups=="registered_users"){
			if ( is_user_logged_in() ) {
				$this->catalog_on=true;
			}
		}
		if($this->user_groups=="non_registered_users"){
			if ( !is_user_logged_in() ) {
				$this->catalog_on=true;
			}
		}
		
		if($this->user_groups=="all"){
				$this->catalog_on=true;
		}
	
		add_action('init', array($this,'woo_catalogue_apply'),3);		
	
	}
}
$woocatalog = new Woocommerce_Catalog_Mode();
 ?>