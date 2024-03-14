<?php
/*
	Plugin Name: Cab Grid
	Plugin URI:  https://cabgrid.com
	Description: Simple A to B taxi price calculator. Use shortcode [cabGrid] to display on your pages/posts. (<a href="http://cabgrid.com">Upgrade to Cab Grid Pro</a>)
	Version:     1.6.9
	Author:      M Williams
	Author URI: http://nimbus.agency
	License:     GPL2
*/
		if ( ! defined( 'WPINC' ) ) {
			die;
		}
	
	// global $cabGridVersion;
	// 	$cabGridVersion="cgb-1.4.9";
	define("_cabGridVersion","cgb-1.6.9");
	global $cabGridInstance;
		$cabGridInstance=0;
	
	function cabGrid_install(){
			//setup database
	
		}
	register_activation_hook( __FILE__, 'cabGrid_install' );
	
	
	function cabGrid_scripts(){
			//global 
			$cabGridVersion=_cabGridVersion;
			
			wp_register_script( 'cabGridJS', plugins_url( 'cab-grid.js', __FILE__ ), array( 'jquery') ,$cabGridVersion, true );
			
			$translation_array = array(
				'please_wait' => esc_attr(__( 'Please Wait', 'cab-grid' )),
				'get_price' => esc_attr(__( 'Get Price', 'cab-grid' ))
			);
			wp_localize_script( 'cabGridJS', 'cabGridObj', $translation_array );
			wp_enqueue_script( 'cabGridJS' );
		}
	add_action('wp_enqueue_scripts','cabGrid_scripts');
	
	function cabGrid_CustomCSS() {
		//global 
		$cabGridVersion=_cabGridVersion;
		$output='<link rel="preload" onload="this.rel=\'stylesheet\'" as="style" id="cabGridCSS" href="'.plugins_url( 'cab-grid.css', __FILE__  ).'?ver='.$cabGridVersion.'" type="text/css" media="all" />';
		$cabGridOptions = get_option( 'cabGrid_Options' );
		$cabGridCSS=$cabGridOptions['css'];
		
		if($cabGridCSS!=''){
			$output.='<style id="cabGridCustomCSS">';
			$output.=stripslashes($cabGridCSS);
			$output.='</style>';
		}
		
		echo $output;

	}
	add_action('wp_footer','cabGrid_CustomCSS');
	
	function cabGrid_headerCSS(){
		$output='<!-- Cab Grid Wordpress Taxi Plugin - https://cabgrid.com -->';
		$output.='<style id="cabGridHeaderCSS">';
		$output.='.cabGrid {visibility: hidden;}';
		$output.='</style>';
		echo $output;
	}
	add_action('wp_head','cabGrid_headerCSS');
	
	function cabGrid(){
		//ob_start();
		return cabGrid_render();
	}
	function cabGrid_render(){
		global $cabGridInstance;
		$cabGridInstance=$cabGridInstance+1;
		$cabGridAMP=0;
		ob_start();
		if(function_exists('is_amp_endpoint')){
			if(is_amp_endpoint()){
				$cabGridAMP=1;
				require( dirname(__file__).'/cab-grid-form-amp.php' );
			}
		}
		if($cabGridAMP==0){
			require( dirname(__file__).'/cab-grid-form.php' );
		}
		ob_get_clean();
		return $cabGridForm;
	}
	add_shortcode('cabGrid', 'cabGrid');
	
	if ( is_admin() ) {
		require_once( dirname(__file__).'/cab-grid-admin.php' );
	}

	//process options
	function cabGrid_sanitize_options( $options ) { // called from register_settings in admin
		foreach ( $options as $key => &$value ) {
			$value = sanitize_text_field( $value ); //too extreme for CSS and Message
		}
		return $options;
	}
	function cabGrid_sanitize_options_loose( $options ) { // called from register_settings in admin
		foreach ( $options as $key => &$value ) {
			$value = strip_tags( $value );
		}
		return $options;
	}

	
	
	function cabGrid_plugin_add_settings_link( $links ) {
		$cabGridInstallDirName=basename(dirname(__FILE__));
		$cabGridAdmin=admin_url ( '/admin.php?page='.$cabGridInstallDirName.'/cab-grid-admin.php');
		$settings_link = '<a href="'.$cabGridAdmin.'">' . __( 'Settings' ) . '</a>';
			array_push( $links, $settings_link );
		$cabGridUpgradeUrl='https://cabgrid.com/buy-download-cab-grid-pro/?f=cgb-wpa';
		$cabGridUpgradeLink='<a target="_blank" title="'.__( 'Upgrade to Cab Grid Pro' ).'" href="'.$cabGridUpgradeUrl.'">' . __( 'Upgrade' ) . '</a>';
			array_push( $links, $cabGridUpgradeLink );
	  	return $links;
	}
	$cgPluginDir = plugin_basename( __FILE__ );
	add_filter( "plugin_action_links_$cgPluginDir", 'cabGrid_plugin_add_settings_link' );
	
	add_action('wp_head','cabGrid_cabGridAJAX');
	function cabGrid_cabGridAJAX() {
		$CGnonce = wp_create_nonce( admin_url('admin-ajax.php') );
	?>
	<script type="text/javascript">
	var cabGridAJAX = {"ajaxurl":"<?php echo admin_url('admin-ajax.php'); ?>","nonce":"<?php echo $CGnonce; ?>"};
	</script>
	<?php
	}
	add_action( 'wp_ajax_cab_grid_price', 'cab_grid_price' );
	add_action( 'wp_ajax_nopriv_cab_grid_price', 'cab_grid_price' ); // need this to serve non logged in users
	
	function cab_grid_price(){
	 $cabGridfrom = $_POST['from'];
	 $cabGridto = $_POST['to'];
	 
	 $cabGridPlaces = get_option( 'cabGrid_Places' );
	 $cabGridPrices = get_option( 'cabGrid_Prices' );
	 $cabGridOptions = get_option( 'cabGrid_Options' );
		$cabGridCurrency=$cabGridOptions['currency'];
	 	if($cabGridCurrency==''){
	 		$cabGridCurrency="$";
	 	}
		$cabGridCurrencyPlacement=(isset($cabGridOptions['currencyPlacement'])) ? $cabGridOptions['currencyPlacement'] : "before";
	 $cabGridPrice=$cabGridPrices[$cabGridPlaces['place'.$cabGridfrom]."-".$cabGridPlaces['place'.$cabGridto]];
	 if($cabGridPrice==""){
	 	$cabGridPrice= __('No price available for this journey','cab-grid');	//"No price available for this journey";
	 } else {
	 	$cabGridPrice=number_format((float)$cabGridPrice, 2, '.', '');
		$cabGridPrice=($cabGridCurrencyPlacement=="after") ? "<i class='cabGridPriceValue'>".number_format_i18n( $cabGridPrice, 2 )."</i><i class='cabGridCurrencySymbol'>".$cabGridCurrency."</i>" : "<i class='cabGridCurrencySymbol'>".$cabGridCurrency."</i><i class='cabGridPriceValue'>".number_format_i18n( $cabGridPrice, 2 )."</i>";
	 }
	 echo $cabGridPrice;
	 wp_die();
	 }
	
	

	 class CabGridWidget extends WP_Widget {

		function __construct() {
			parent::__construct(
 				'cabGridWidget',
 				__( 'Cab Grid', 'cab-grid' ),
 				array( 'description' => __('Displays CabGrid fare price calculator in your sidebar (or widget area)','cab-grid'),) // Args
 			);
 		}
 
	  public function form($instance)
	   {
	     $instance = wp_parse_args( (array) $instance, array( 'title' => '', 'height' => '320' ) );
	     $title = $instance['title'];
	 	$height = $instance['height'];
	 ?>
	   <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title','cab-grid');?>: <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
	   <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Height (pixels)','cab-grid');?>: <input class="widefat" id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>" type="text" value="<?php echo esc_attr($height); ?>" /></label></p>
	 <?php
	   }
 
	  public function update($new_instance, $old_instance)
	   {
	     $instance = $old_instance;
	     $instance['title'] = $new_instance['title'];
	 	$instance['height'] = $new_instance['height'];
	     return $instance;
	   }
 
	   public function widget($args, $instance)
	   {
	     extract($args, EXTR_SKIP);
 
	     echo $before_widget;
	     $title = empty($instance['title']) ? ' ' : apply_filters('widget_title', $instance['title']);
	 	$height = $instance['height'];
 
	     if (!empty($title))
	       echo $before_title . $title . $after_title;
 
			$cabGridForm=cabGrid_render();
	  		echo '<div class="cabGrid_widget" style="min-height:'.$height.'px;">'.$cabGridForm.'</div>';
 
	     echo $after_widget;
	   }
 
	 }
	function register_cabGrid_widget() {register_widget( 'cabGridWidget' );}
	add_action( 'widgets_init', 'register_cabGrid_widget' );
	 
	 add_action('plugins_loaded', 'wan_load_textdomain');
	 function wan_load_textdomain() {
	 	load_plugin_textdomain( 'cab-grid', false, dirname( plugin_basename(__FILE__) ) . '/languages/' );
	 }
	 
	 function cabGridArr($a,$b,$c){
		 $d=""; $e1=1337.34*1000; $e2=89.156*1000; $e=$e1/$e2;
		 if(intval($c)<=intval($e)){ if(isset($a[$b.$c])){ $d=$a[$b.$c];} } 
		 return $d;
	 }
	 
 	function cabGridBot() {
		$ua=false;
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			if(preg_match('/bot|crawl|slurp|spider|mediapartners/i', $_SERVER['HTTP_USER_AGENT']) && !preg_match('/cache|caches|caching/i', $_SERVER['HTTP_USER_AGENT'])){
				$ua=true;
			}
		}
		return $ua;
 	}
	
	function cabGrid_register_block() {

		if ( ! function_exists( 'register_block_type' ) ) {
			return;
		}

		wp_register_script(
			'cabGridBlock',
			plugins_url( 'cab-grid-block.js', __FILE__ ),
			array( 'wp-blocks', 'wp-i18n', 'wp-element','wp-editor' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'cab-grid-block.js' )
		);
		
		wp_register_style(
			'cabGridBlockEditor',
			plugins_url( 'cab-grid-block-editor.css', __FILE__ ),
			array( 'wp-edit-blocks' ),
			filemtime( plugin_dir_path( __FILE__ ) . 'cab-grid-block-editor.css' )
		);

		register_block_type( 'cab-grid/basic-calculator', array(
			'editor_script' => 'cabGridBlock',
			'editor_style' => 'cabGridBlockEditor',
		) );

		if ( function_exists( 'wp_set_script_translations' ) ) {wp_set_script_translations( 'cabGridProBlock', 'cab-grid-pro' );}

	}
	add_action( 'init', 'cabGrid_register_block' );

	
	
?>