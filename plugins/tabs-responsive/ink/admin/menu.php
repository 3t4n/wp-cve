<?php
class wpsm_tabs_r {
	private static $instance;
    public static function forge() {
        if (!isset(self::$instance)) {
            $className = __CLASS__;
            self::$instance = new $className;
        }
        return self::$instance;
    }
	
	private function __construct() {
		add_action('admin_enqueue_scripts', array(&$this, 'wpsm_tabs_r_admin_scripts'));
		include_once ABSPATH . 'wp-admin/includes/plugin.php';
		if ( !(is_plugin_active( 'tabs-pro/tabs-pro.php' )) ) {
			$this->Extension();	
		}
        if (is_admin()) {
			add_action('init', array(&$this, 'tabs_r_register_cpt'), 1);
			add_action('add_meta_boxes', array(&$this, 'wpsm_tabs_r_meta_boxes_group'));
			add_action('admin_init', array(&$this, 'wpsm_tabs_r_meta_boxes_group'), 1);
			add_action('save_post', array(&$this, 'add_tabs_r_meta_box_save'), 9, 1);
			add_action('save_post', array(&$this, 'tabs_r_settings_meta_box_save'), 9, 1);
		}
    }
	
	// admin scripts
	public function wpsm_tabs_r_admin_scripts(){
		if(get_post_type()=="tabs_responsive"){
			
			wp_enqueue_media();
			wp_enqueue_script('jquery-ui-datepicker');
			//color-picker css n js
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'wpsm_tabs_r-color-pic', wpshopmart_tabs_r_directory_url.'assets/js/color-picker.js', array( 'wp-color-picker' ), false, true );
			wp_enqueue_style('wpsm_tabs_r-panel-style', wpshopmart_tabs_r_directory_url.'assets/css/panel-style.css');
			  
			//font awesome css
			wp_enqueue_style('wpsm_tabs_r-font-awesome', wpshopmart_tabs_r_directory_url.'assets/css/font-awesome/css/font-awesome.min.css');
			wp_enqueue_style('wpsm_tabs_r_bootstrap', wpshopmart_tabs_r_directory_url.'assets/css/bootstrap.css');
			wp_enqueue_style('wpsm_tabs_r_font-awesome-picker', wpshopmart_tabs_r_directory_url.'assets/css/fontawesome-iconpicker.css');
			wp_enqueue_style('wpsm_tabs_r_jquery-css', wpshopmart_tabs_r_directory_url .'assets/css/ac_jquery-ui.css');
			
			//css line editor
			wp_enqueue_style('wpsm_tabs_r_line-edtor', wpshopmart_tabs_r_directory_url.'assets/css/jquery-linedtextarea.css');
			wp_enqueue_script( 'wpsm_tabs_r-line-edit-js', wpshopmart_tabs_r_directory_url.'assets/js/jquery-linedtextarea.js');
			
			wp_enqueue_script( 'wpsm_tabs_custom-js', wpshopmart_tabs_r_directory_url.'assets/js/tabs-custom.js');
			//wp_enqueue_script( 'wpsm_tabs_bootstrap-js', wpshopmart_tabs_r_directory_url.'assets/js/bootstrap.js');
			
			//tooltip
			wp_enqueue_style('wpsm_tabs_r_tooltip', wpshopmart_tabs_r_directory_url.'assets/tooltip/darktooltip.css');
			wp_enqueue_script( 'wpsm_tabs_r-tooltip-js', wpshopmart_tabs_r_directory_url.'assets/tooltip/jquery.darktooltip.js');
			
			// tab settings
			wp_enqueue_style('wpsm_tabs_r_settings-css', wpshopmart_tabs_r_directory_url.'assets/css/settings.css');
			
			//icon picker	
			wp_enqueue_script('wpsm_tabs_r_font-icon-picker-js',wpshopmart_tabs_r_directory_url.'assets/js/fontawesome-iconpicker.js',array('jquery'));
			wp_enqueue_script('wpsm_tabs_r_call-icon-picker-js',wpshopmart_tabs_r_directory_url.'assets/js/call-icon-picker.js',array('jquery'), false, true);
			wp_enqueue_style('wpsm_tabs_r_remodal-css', wpshopmart_tabs_r_directory_url .'assets/modal/remodal.css');
			wp_enqueue_style('wpsm_tabs_r_remodal-default-theme-css', wpshopmart_tabs_r_directory_url .'assets/modal/remodal-default-theme.css');
			wp_enqueue_script('wpsm_tabs_r_min-js',wpshopmart_tabs_r_directory_url.'assets/modal/remodal.min.js',array('jquery'), false, true);
			wp_enqueue_style('wpsm_tabs_ac_help_css', wpshopmart_tabs_r_directory_url.'assets/css/help.css');
	
		}
	}
	
	public function tabs_r_register_cpt(){
		require_once('cpt-reg.php');
		add_filter( 'manage_edit-tabs_responsive_columns', array(&$this, 'tabs_responsive_columns' )) ;
		add_action( 'manage_tabs_responsive_posts_custom_column', array(&$this, 'tabs_responsive_manage_columns' ), 10, 2 );
	}
	
	function tabs_responsive_columns( $columns ){
        $columns = array(
            'cb' => '<input type="checkbox" />',
            'title' => __( 'Tabs' ),
            'shortcode' => __( 'Tabs Shortcode' ),
            'date' => __( 'Date' )
        );
        return $columns;
    }

    function tabs_responsive_manage_columns( $column, $post_id ){
        global $post;
        switch( $column ) {
          case 'shortcode' :
            echo '<input style="width:225px" type="text" onclick="this.select()" value="[TABS_R id='.esc_html($post_id).']" readonly="readonly" />';
            break;
          default :
            break;
        }
    }

    public function Extension() {
	    if (is_plugin_active('woocommerce/woocommerce.php')) :
	       new \TABS_RES_PLUGINS\Extension\WooCommerce\WooCommerce();
	    endif;
	}
	
	// metaboxes groups function for call all metabox unit
	public function wpsm_tabs_r_meta_boxes_group(){
		add_meta_box('tabs_r_add', __('Add Tabs Panel', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_add_tabs_r_meta_box_function'), 'tabs_responsive', 'normal', 'low' );
		add_meta_box ('tabs_r_shortcode', __('Tabs Shortcode', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_pic_tabs_r_shortcode'), 'tabs_responsive', 'normal', 'low');
		//add_meta_box ('tabs_r_more_free_themes', __('More Free Themes From Wpshopmart', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_tabs_r_pic_more_free_themes'), 'tabs_responsive', 'normal', 'low');
		add_meta_box ('tabs_r_help', __('Help From Wpshopmart', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_tabs_r_help'), 'tabs_responsive', 'normal', 'low');
		
		add_meta_box ('tabs_r_more_pro', __('More Pro Plugin From Wpshopmart', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_tabs_r_pic_more_pro'), 'tabs_responsive', 'normal', 'low');
		
		add_meta_box('tabs_r_donate', __('Donate Us', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_tabs_r_donate_meta_box_function'), 'tabs_responsive', 'side', 'low');
		add_meta_box('tabs_r_rateus', __('Rate Us If You Like This Plugin', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_tabs_r_rateus_meta_box_function'), 'tabs_responsive', 'side', 'low');
		add_meta_box('tabs_r_setting', __('Tabs Settings', wpshopmart_tabs_r_text_domain), array(&$this, 'wpsm_add_tabs_r_setting_meta_box_function'), 'tabs_responsive', 'side', 'low');
	}
	
	public function wpsm_add_tabs_r_meta_box_function($post){
		require_once('add-tabs.php');
	}
	
	public function wpsm_pic_tabs_r_shortcode(){
		?>
		<style>
			#tabs_r_shortcode{
			background:#fff!important;
			box-shadow: 0 0 20px rgba(0,0,0,.2);
			}
			#tabs_r_shortcode .hndle , #tabs_r_shortcode .handlediv{
			display:none;
			}
			#tabs_r_shortcode p{
			color:#000;
			font-size:15px;
			}
			#tabs_r_shortcode input {
			font-size: 16px;
			padding: 8px 10px;
			width:100%;
			}
			.handle-order-higher, .handle-order-lower{
				display:none;
			}
			
		</style>
		<h3><?php esc_html_e('Tabs Shortcode',wpshopmart_tabs_r_text_domain); ?></h3>
		<p><?php _e("Use below shortcode in any Page/Post to publish your Tabs", wpshopmart_tabs_r_text_domain);?></p>
		<input readonly="readonly" onclick="this.select()" type="text" value="<?php echo "[TABS_R id=".get_the_ID()."]"; ?>">
		<?php
		 $PostId = get_the_ID();
		$Settings = unserialize(get_post_meta( $PostId, 'Tabs_R_Settings', true));
		if(isset($Settings['custom_css'])){  
		     $custom_css   = $Settings['custom_css'];
		}
		else{
		$custom_css="";
		}		
		?>
		
		<br><br>
		<div>
			<h3><?php esc_html_e('To activate widget into any widget area',wpshopmart_tabs_r_text_domain); ?></H3>
			<p><a href="<?php get_site_url();?>./widgets.php" ><?php esc_html_e('Click Here',wpshopmart_tabs_r_text_domain); ?></a>. </p>
			<p><?php esc_html_e('Find ',wpshopmart_tabs_r_text_domain); ?><b><?php esc_html_e('Tabs Widget ',wpshopmart_tabs_r_text_domain); ?></b><?php esc_html_e(' and place it to your widget area. Select any Tabs from the list and then save changes.',wpshopmart_tabs_r_text_domain); ?></p>
		</div>	
		<h3><?php esc_html_e('Custom Css',wpshopmart_tabs_r_text_domain); ?></h3>
		<textarea name="custom_css" id="custom_css" style="width:100% !important ;height:300px;background:#ECECEC;"><?php echo esc_html($custom_css) ; ?></textarea>
		<p><?php esc_html_e('Enter Css without ',wpshopmart_tabs_r_text_domain); ?><strong>&lt;style&gt; &lt;/style&gt; </strong><?php esc_html_e(' tag',wpshopmart_tabs_r_text_domain); ?></p>
		<br>
		
		<?php if(isset($Settings['custom_css'])){ ?> 
		<h3><?php esc_html_e('Add This Tab settings as default setting for new tabs',wpshopmart_tabs_r_text_domain); ?></h3>
		<div class="">
			<a  class="button button-primary button-hero" name="updte_wpsm_tabs_r_default_settings" id="updte_wpsm_tabs_r_default_settings" onclick="wpsm_update_default()"><?php esc_html_e('Update Default Settings',wpshopmart_tabs_r_text_domain); ?></a>
		</div>	
		<?php } ?>
		<script>
		jQuery(function() {
		// Custom Css design editor 
		  jQuery("#custom_css").linedtextarea();

		});
		</script>
		<?php 
	}
	
	public function wpsm_tabs_r_donate_meta_box_function(){ 
	?>
	<style>
			#tabs_r_donate{
			background:transparent;
			text-align:center;
			box-shadow:none;
			}
			#tabs_r_donate .hndle , #tabs_r_donate .handlediv{
			display:none;
			}
			
			a, a:focus{
				box-shadow:none;
				text-decoration:none;
			}
			#tabs_r_donate h3 {
			margin-bottom:10px;
			margin-top:5px;
			padding:10px 0;
			}
			#tabs_r_donate a{
				background:#e0bf1b;
				border-color:#e0bf1b;
			}
			
			</style>
			
			
			<h3> Need Help?</h3>
			
			<a href="https://wordpress.org/support/plugin/tabs-responsive" target="_blank" class="button button-primary button-hero "><?php esc_html_e('Create Support Ticket Here',wpshopmart_tabs_r_text_domain); ?></a>
			<?php 
	}
	public function wpsm_tabs_r_rateus_meta_box_function(){
		?>
		<style>
		#tabs_r_rateus{
			background:#31a3dd;
			text-align:center;
			}
			#tabs_r_rateus .hndle , #tabs_r_rateus .handlediv{
			display:none;
			}
			#tabs_r_rateus h1{
			color:#fff;
			margin-bottom:10px;
			}
			 #tabs_r_rateus h3 {
			color:#fff;
			font-size:15px;
			}
			#tabs_r_rateus .button-hero{
			display:block;
			text-align:center;
			margin-bottom:15px;
			}
			.wpsm-rate-us{
			text-align:center;
			}
			.wpsm-rate-us span.dashicons {
				width: 40px;
				height: 40px;
				font-size:20px;
				color : #fff !important;
			}
			.wpsm-rate-us span.dashicons-star-filled:before {
				content: "\f155";
				font-size: 40px;
			}
		</style>
		   <h1><?php esc_html_e('Rate This plugin',wpshopmart_tabs_r_text_domain); ?></h1>
			<a href="https://wordpress.org/support/plugin/tabs-responsive/reviews/?filter=5" target="_blank" class="button button-primary button-hero "><?php esc_html_e('RATE HERE',wpshopmart_tabs_r_text_domain); ?></a>
			<a class="wpsm-rate-us" style=" text-decoration: none; height: 40px; width: 40px;" href="https://wordpress.org/support/plugin/tabs-responsive/reviews/?filter=5" target="_blank">
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
				<span class="dashicons dashicons-star-filled"></span>
			</a>
		<?php 
	}
	
	public function wpsm_add_tabs_r_setting_meta_box_function($post){
		require_once('settings.php');
	}
	
	public function add_tabs_r_meta_box_save($PostID) {
		require('data-post/tabs-save-data.php');
    }
	
	public function tabs_r_settings_meta_box_save($PostID){
		require('data-post/tabs-settings-save-data.php');
	}
	
	
	public function wpsm_tabs_r_pic_more_free_plugins(){
		require_once('more-free-plugins.php');
	}
	public function wpsm_tabs_r_pic_more_pro(){
		require_once('more-pro.php');
	}
	
	public function wpsm_tabs_r_help(){
		require_once('help.php');
	}
	
	
}
global $wpsm_tabs_r;
$wpsm_tabs_r = wpsm_tabs_r::forge();

 ?>