<?php
if ( ! defined( 'ABSPATH' ) ) { exit;}
if( !class_exists( 'Ni_WooCommerce_Cost_Of_Goods_Init' ) ) { 
	class Ni_WooCommerce_Cost_Of_Goods_Init {
		var $ni_constant = array();  
		 public function __construct($ni_constant = array()){
			$this->ni_constant = $ni_constant; 
			$this->add_cost_of_goods();
			
			add_action('admin_menu', 		array($this,'admin_menu'));	
			add_action( 'admin_enqueue_scripts',  array(&$this,'admin_enqueue_scripts' ));
			add_action( 'wp_ajax_ni_cog_action',  array(&$this,'ajax_ni_cog_action' )); /*used in form field name="action" value="my_action"*/
			add_filter( 'plugin_row_meta',  array(&$this,'plugin_row_meta' ), 10, 2 );
			
			add_filter( 'admin_footer_text',  array(&$this,'admin_footer_text' ),101);
			
			add_action('admin_init', array( &$this, 'admin_init' ) ); 
			
			//add_filter( 'gettext', array($this, 'get_text'),20,3);
			
		 }
		 function get_text($translated_text, $text, $domain){
			if($domain == 'wooreportcog'){
				return '['.$translated_text.']';
			}		
			return $translated_text;
		}
		 function admin_init(){
			 if(isset($_REQUEST['btn_nicog_print'])){
				include_once('ni-cog-sales-report.php');
				$obj = new Ni_COG_Sales_Report();
				$obj->get_print_content();
				die;
			}
		 }
		 function admin_footer_text($text){
		
			$page = isset($_REQUEST["page"]) ? $_REQUEST["page"] : '';
			$admin_pages = $this->get_admin_pages();
			if (in_array($page,$admin_pages)){
					
					$text = sprintf( __( 'Thank you for using our plugins <a href="%s" target="_blank">naziinfotech</a>' ,'wooreportcog'), 
					__( 'http://naziinfotech.com/'  ,'wooreportcog') );
					$text = "<span id=\"footer-thankyou\">". $text ."</span>"	 ;
				
			 }
			return $text ; 
		 }
		 function get_admin_pages(){
			
			$admin_pages = array();
			$admin_pages[] = 'ni-cost-of-goods';
			$admin_pages[] = 'ni-cog-report';
			$admin_pages[] = 'ni-cog-top-profit-product';
			$admin_pages[] = 'niwoocog-add-cost-price';
			$admin_pages[] = 'ni-cog-setting';
			$admin_pages[] = 'ni-cog-other-plugin';
			$admin_pages[] = 'ni-cog-analytical-report';
			return $admin_pages;
		 
		 }
		 function admin_menu(){
			add_menu_page( __(  'Ni Cost Of Goods'  ,'wooreportcog') , __(  'Ni Cost Of Goods'  ,'wooreportcog'), $this->ni_constant['manage_options'], $this->ni_constant['menu'], array( $this, 'add_page'), 'dashicons-performance', "57.6361" );
			add_submenu_page($this->ni_constant["menu"], __(  'Dashboard'  ,'wooreportcog') ,__(  'Dashboard'  ,'wooreportcog'), $this->ni_constant['manage_options'],$this->ni_constant["menu"], array( $this, 'add_page'));
			add_submenu_page($this->ni_constant["menu"], __(  'Profit Report'  ,'wooreportcog') ,__(  'Profit Report'  ,'wooreportcog'),  $this->ni_constant['manage_options'],'ni-cog-report', array( $this, 'add_page'));
			
			
			
		add_submenu_page($this->ni_constant["menu"]
		,__( 'Top Profit Product', 'wooreportcog' )
		,__( 'Top Profit Product', 'wooreportcog' )
		, $this->ni_constant['manage_options'], 'ni-cog-top-profit-product' 
		, array(&$this,'add_page'));
	
		add_submenu_page($this->ni_constant["menu"]
		,__( 'Analytical Report', 'wooreportcog' )
		,__( 'Profit Analytical', 'wooreportcog' )
		, $this->ni_constant['manage_options'], 'ni-cog-analytical-report' 
		, array(&$this,'add_page'));
		
		
		add_submenu_page($this->ni_constant["menu"]
		,__( 'Category Stock Value', 'wooreportcog' )
		,__( 'Category Stock Value', 'wooreportcog' )
		, $this->ni_constant['manage_options'], 'ni-category-stock-value' 
		, array(&$this,'add_page'));
		
		
		add_submenu_page($this->ni_constant["menu"], __(  'Add Cost Price'  ,'wooreportcog') ,__(  'Add Cost Price'  ,'wooreportcog'),  $this->ni_constant['manage_options'],'niwoocog-add-cost-price', array( $this, 'add_page'));
		
		
	
		
		
		add_submenu_page($this->ni_constant["menu"]
		,__( 'Setting', 'wooreportcog' )
		,__( 'Setting', 'wooreportcog' )
		, $this->ni_constant['manage_options'], 'ni-cog-setting' 
		, array(&$this,'add_page'));	
		
		
		
		
		
		
		
		add_submenu_page($this->ni_constant["menu"], __(  'Other plugin'  ,'wooreportcog'), __(  'Other plugin'  ,'wooreportcog'),  $this->ni_constant['manage_options'],'ni-cog-other-plugin', array( $this, 'add_page'));
		do_action("nicog_add_menu_end",$this->ni_constant["menu"]);
			
			
	
		 }
		 function admin_enqueue_scripts(){
			$page = sanitize_text_field( isset($_REQUEST["page"]) ? $_REQUEST["page"] : '');
		 
			$menu_name = $this->get_menu_name();
			
			if (!in_array($page ,	$menu_name )){
				
				return false;
			}
			
			
			if ($page =="ni-cog-report" || $page =="ni-cog-analytical-report"|| $page =="ni-category-stock-value"){
				wp_enqueue_script( 'ni-ajax-script-cog-report', plugins_url( '../assets/js/ni-sales-order-cost-of-goods.js', __FILE__ ) );
			}
			if ($page =="ni-cog-setting"){
				wp_enqueue_script( 'ni-ajax-script-cog-setting', plugins_url( '../assets/js/ni-cog-setting.js', __FILE__ ) );
			}
			if ($page =="niwoocog-add-cost-price"){
				wp_enqueue_script( 'niwoocog-add-cost-price-script', plugins_url( '../assets/js/niwoocog-add-cost-price.js', __FILE__ ) );
			}
			
			wp_register_style('niwoocog-style', plugins_url('../assets/css/niwoocog-style.css', __FILE__));
			wp_enqueue_style('niwoocog-style' );
					
			wp_register_style('niwoocog-bootstrap-style', plugins_url('../assets/css/bootstrap.min.css', __FILE__));
			wp_enqueue_style('niwoocog-bootstrap-style' );
			
			wp_enqueue_script( 'ni-ajax-script-cog', plugins_url( '../assets/js/script.js', __FILE__ ), array('jquery') );
			wp_localize_script( 'ni-ajax-script-cog','ni_cog_ajax_object',array('ni_cog_ajax_object_ajaxurl'=>admin_url('admin-ajax.php') ) );
			
			
			
			
		 }
		 function get_menu_name(){
		 	$admin_pages = array();
			$admin_pages[] = 'ni-cost-of-goods';
			$admin_pages[] = 'ni-cog-report';
			$admin_pages[] = 'ni-cog-top-profit-product';
			$admin_pages[] = 'niwoocog-add-cost-price';
			$admin_pages[] = 'ni-cog-setting';
			$admin_pages[] = 'ni-cog-other-plugin';
			$admin_pages[] = 'ni-cog-analytical-report';
			$admin_pages[] = 'ni-category-stock-value';
			
			return apply_filters("nicog_menu_name", $admin_pages);
		 }
		 function add_page(){
			
			 if(isset($_REQUEST["page"])){
			 	$page =  $_REQUEST["page"];
				if ($page=="ni-cog-report"){
					include_once("ni-cog-sales-report.php");
					$obj =  new Ni_COG_Sales_Report();
					$obj->page_init();
				
				}
				if ($page=="ni-cost-of-goods"){
					include_once("ni-cog-dashboard.php");
					$obj =  new Ni_COG_Dashboard();
					$obj->page_init();
				}
				if ($page=="ni-cog-other-plugin"){
					include_once("ni-sales-report-addons.php");
					$obj =new ni_sales_report_addons();
					$obj->page_init();
				}
				if ($page=="ni-cog-top-profit-product"){
					include_once('ni-cog-top-profit-product.php');
					$obj = new Ni_COG_Top_Profit_Product(); 
					$obj->page_init(); 
				}
				if ($page=="ni-cog-setting"){
					include_once('ni-cog-setting.php');
					$obj = new Ni_COG_Setting(); 
					$obj->page_init(); 
				}
				if ($page =="niwoocog-add-cost-price"){
					include_once('niwoocog-add-cost-price.php');
					$obj = new NiWooCOG_Add_Cost_Price(); 
					$obj->page_init(); 
				}
				if ($page=="ni-cog-analytical-report"){
					include_once('ni-cog-analytical-report.php');
					$obj = new Ni_COG_Analytical_Report(); 
					$obj->page_init(); 
				}
				if ($page=="ni-category-stock-value"){
					include_once('ni-category-stock-value.php');
					$obj = new Ni_Category_Stock_Value(); 
					$obj->page_init(); 
				}
			 }
		 }
		 function add_cost_of_goods(){
			 include_once("ni-woocommerce-cost-of-goods-function.php");
			 $obj_cogf =  new Ni_WooCommerce_Cost_Of_Goods_Function();
			 
			 include_once("ni-woocommerce-cost-of-goods-quick-edit.php");
			 $obj_cogqe =  new Ni_WooCommerce_Cost_Of_Goods_Quick_Edit($this->ni_constant);
			 
		 }
		 function plugin_row_meta($links, $file){
			if ( $file == "ni-woocommerce-cost-of-goods/ni-woocommerce-cost-of-goods.php" ) {
				$row_meta = array(
				
				'ni_cogpro_viewdemo'=> '<a target="_blank" href="http://demo.naziinfotech.com?demo_login=woo_cost_of_goods">View Pro Demo</a>',
				'ni_cogpro_buypro'=> '<a target="_blank" href="http://naziinfotech.com/product/ni-woocommerce-cost-of-good-pro/">Buy Pro Version</a>',	
				'ni_cogpro_review'=> '<a target="_blank" href="https://wordpress.org/plugins/ni-woocommerce-cost-of-goods/">Write a Review</a>'	);
					
	
				return array_merge( $links, $row_meta );
			}
			return (array) $links;
		}
		
		function ajax_ni_cog_action(){
		 
			if (isset($_REQUEST["sub_action"])){
				
				do_action("nicog_ajax_action",$_REQUEST["sub_action"]);
				
				$sub_action = $_REQUEST["sub_action"];
				if ($sub_action=="ni_cog_sales_report"){
					include_once("ni-cog-sales-report.php");
					$obj =  new Ni_COG_Sales_Report();
					$obj->page_ajax();
				
				}
				if ($sub_action=="ni_cog_setting"){
					include_once('ni-cog-setting.php');
					$obj = new Ni_COG_Setting(); 
					$obj->page_ajax();
				
				}
				if ($sub_action=="add_product_cost"){
				include_once('niwoocog-add-cost-price.php');
					$obj = new NiWooCOG_Add_Cost_Price(); 
					$obj->page_ajax();
				
				}
				if ($sub_action=="ni_cog_analytical_report"){
					include_once('ni-cog-analytical-report.php');
					$obj = new Ni_COG_Analytical_Report(); 
					$obj->page_ajax();
				}
				if ($sub_action=="ni_category_stock_value"){
					include_once('ni-category-stock-value.php');
					$obj = new Ni_Category_Stock_Value(); 
					$obj->page_ajax(); 
				}
				
			}
			die;
		 }
		
	}
}
?>