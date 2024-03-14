<?php
/*
Plugin Name: WP Custom Login Page Logo
Plugin URI: http://wp.larsactionhero.com/development/plugins/wp-custom-login-page-logo/
Description: Customize the admin logo on /wp-admin login page.
Version: 1.4.8.4
Author: Lars Ortlepp
Author URI: http://larsactionhero.com
License: GPL2
*/


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/*
* 
* ...update options if POST data is send AND user has admin role
****************************************************************
*/

$wpclpl_save = (isset($_POST['wpclpl_save'])) ? filter_var($_POST['wpclpl_save'],FILTER_SANITIZE_STRING) : 0;

if(!function_exists('wp_get_current_user')) {
    include(ABSPATH . "wp-includes/pluggable.php"); 
}
$current_user = wp_get_current_user();
$current_user_role = array('administrator');
if( array_intersect($current_user_role, $current_user->roles ) && is_admin() && $wpclpl_save=='1') { 
	wpclpl_update_options();
}

/*
* function wpclpl_init()
*
* loads textdomain & plugin settings
************************************
*/
function wpclpl_init(){
	
	load_plugin_textdomain( 'wpclpl', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
	add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'wpclpl_plugin_links', 10, 2 );
	
}

/*
* function wpclpl_load_jquery()
*
* @param boolean $migrate: load jquery migrate if needed
*
* loads jquery into head section via add_filter
*******************************************************
*/
function wpclpl_load_jquery( $migrate = false ){

	$prefix = ( wpclpl_is_https() ) ? 'https://' : 'http://';

	?>
	<script src="<?php echo $prefix.$_SERVER['SERVER_NAME']; ?>/wp-includes/js/jquery/jquery.js"></script>
	<?php
	if($migrate){
	?>
	<script src="<?php echo $prefix.$_SERVER['SERVER_NAME']; ?>/js/jquery-migrate-1.2.1.min.js"></script>
	<?php
	}

} 
add_filter('login_head', 'wpclpl_load_jquery');





/*
* function wpclpl_load_textdomain()
*
* load translation files
***********************************	
*/
function wpclpl_load_textdomain(){
	load_plugin_textdomain( 'wpclpl', FALSE, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}


/*
* function wpclpl_plugin_links()
*
* add settings, docs & plugins url to admin panel in plugins overview. 
**********************************************************************
*/
function wpclpl_plugin_links( $links ){

	// settings urlsm, etc
	$wpclpl_settings_url = admin_url('options-general.php?page=wp-custom-login-page-logo.php');
	$wpclpl_docs_url = 'http://wp.larsactionhero.com/development/plugins/wp-custom-login-page-logo/';

	$wpclpl_plugin_links = array(
		'<a href="'.$wpclpl_settings_url.'">' . _e( 'Settings', 'wpclpl' ) . '</a>',
	    /*'<a href="'.$wpclpl_docs_url.'" target="_blank">' . _e( 'Documentation', 'wpclpl' ) . '</a>'*/
	);
        
	return array_merge( $wpclpl_plugin_links, $links );    

}


add_action( 'admin_init', 'wpclpl_init' );




/*
* function register_wpclpl_plugin_option_page()
*
* add options page to menu.
***********************************************
*/
function register_wpclpl_plugin_option_page(){
   add_options_page('Custom Login Page Logo', 'Custom Login Page Logo', 'manage_options', basename(__FILE__), 'wpclpl_admin_options_page');
}
add_action('admin_menu','register_wpclpl_plugin_option_page');



/*
* function wpclpl_filter_vars()
*
* @param mixed $input
* filters input and return it. 
*******************************
*/
function wpclpl_filter_vars( $input ){
	
	if(!empty($input)) {
		
		// input is an array? filter each item and return filtered array
		if(is_array($input)){
			
			$tmparr = array();
			for($i=0; $i<count($input);$i++){
				$tmparr[] = filter_var( $input[$i], FILTER_SANITIZE_STRING );
			}
			
			return $tmparr;
			
		} else {
			return filter_var( $input, FILTER_SANITIZE_STRING );
		}
		
	} else {
		return '';
	}
}


// get options at load...
$wpclpl_plugin_options = get_option('wpclpl_plugin_options');
if( $wpclpl_plugin_options == false) {
	// defaults...
	$wpclpl_plugin_options = array(
		'wpclpl_logo_url'=>'',
		'wpclpl_additional_text'=>'',
		'wpclpl_custom_css'=>'',
		);
}




/*
* function wpclpl_settings_header_text()
*
* shows admin panel header text.
****************************************
*/   
function wpclpl_settings_header_text() {
	?>
	<p class="wpclpl-plugin-information">
	<?php _e('This Plugin allows you to change the default (wordPress-) logo at the admin login page.','wpclpl'); ?>
	<br />
	<?php _e('Helpful if you want to customize the login page for your clients or a company.','wpclpl'); ?>
	</p>
	<?php
}


/*
* function wpclpl_is_https()
*
* checks if we have a secure connection via https.
* returns true or false. currently not in use but maybe useful for later.
*************************************************************************
*/

function wpclpl_is_https(){
	return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') || $_SERVER['SERVER_PORT'] == 443;
}


    
/*
* function wpclpl_settings_logo()
*
* builds settings area for our logo
****************************************
*/   
function wpclpl_settings_logo() {  

	global $wpclpl_plugin_options;

	$wpclpl_plugin_logo_url =  ( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ) ? esc_url($wpclpl_plugin_options['wpclpl_logo_url']) : '';
	
	
	if( stristr($wpclpl_plugin_logo_url, 'http://') && $_SERVER)
	?>
     <p>
     	<input class="wpclpl-logo-upload-btn button" type="button" value="<?php esc_attr_e('Select an image file','wpclpl'); ?>" /> 
     	<span class="wpclpl-description">
     		<?php _e('Select an existing image from the media library or upload a new one.','wpclpl');?><br />
	 		<?php _e('You also can insert an image url manually:','wpclpl'); ?><br />
        <input type="text" class="wpclpl-logo-url" name="wpclpl_logo_url" value="<?php echo $wpclpl_plugin_logo_url; ?>" placeholder="<?php _e('Insert URL here or select image with button below.','wpclpl'); ?>" />     
        <code>(<?php _e('e.g.','wpclpl'); ?> http://www.mywebsite.com/wp-content/themes/mytheme/images/mylogo.jpg)</code></span>
     </p>
     <?php
}  
    
    
    
/*
* function wpclpl_settings_logo_plain_url()
*
* returns the plain url of custom logo file
*******************************************
*/
function wpclpl_settings_logo_plain_url() {  
	global $wpclpl_plugin_options;
	echo ( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ) ? 'background-image: url("'.esc_url($wpclpl_plugin_options['wpclpl_logo_url']).'");'."\n" : '';
}



/*
* function wpclpl_settings_logo_preview()
*
* displays html preview of logo in admin panel
**********************************************
*/
function wpclpl_settings_logo_preview() {  
	
	global $wpclpl_plugin_options;

	if( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ){
	
	?>
		<div class="wpclpl-logo-preview-wrap">  
			<a href="<?php echo esc_url( $wpclpl_plugin_options['wpclpl_logo_url'] );?>?TB_inline=true&height=400&width=400&inlineId=wpclpl-logo-preview"  class="thickbox">
				<img class="wpclpl-logo-preview" src="<?php echo esc_url( $wpclpl_plugin_options['wpclpl_logo_url'] ); ?>" id="wpclpl-logo-preview" /></a>
		</div>
		<p>
			<input class="wpclpl-logo-remove-img-btn button" type="button" value="<?php esc_attr_e('Remove Image','wpclpl'); ?>" />
			<span class="wpclpl-description">(<?php _e('File in Media Library will not be deleted','wpclpl'); ?>)</span>
		</p>
		<?php
		
	} else {
		
		?>
		<div class="wpclpl-currentlogo" style="background-image: url('<?php echo admin_url(); ?>images/wordpress-logo.svg?ver=20131107')"></div>
		<br clear="left" />
		<p class="wpclpl-default-logo" style="">(<?php _e('Default WP Logo','wpclpl'); ?>)</p>
		<?php
	}
	
	?>
	<div class="wpclpl-logo-dimensions-wrap">
		<p>
		 <span class="wpclpl-logo-size wpclpl-description"><?php _e('Original size','wpclpl'); ?>: <span id="wpclpl-logo-width" data-size-width="<?php echo $wpclpl_logo_width; ?>"></span> x <span id="wpclpl-logo-height" data-size-height=""></span>px</span>
		</p>
	</div>
	
	<?php

}  



/*
* function wpclpl_settings_add_text()
*
* sets the additional (optional) text below logo
************************************************
*/
function wpclpl_settings_add_text(){

	global $wpclpl_plugin_options;
	
	$wpclpl_additional_text = ( !empty( $wpclpl_plugin_options['wpclpl_additional_text']) ) ? $wpclpl_plugin_options['wpclpl_additional_text'] : '';
	
	?>
	<input name="wpclpl_additional_text" type="text" class="wpclpl-additional-text" placeholder="<?php esc_attr_e('This text will appear below the custom logo','wpclpl'); ?>." value="<?php echo  $wpclpl_plugin_options['wpclpl_additional_text']; ?>" />
	<br />
	<span class="wpclpl-description"><?php _e('Add some optional user information. This text will appear below the custom logo.','wpclpl'); ?></span>
	<?php

}



/*
* function wpclpl_build_css_output()
*
* build css output.
*
* values are added as follows:
*
* 1. the background-image url
* 2. the detected size (width/height)
* 3. the user's custom styles
*************************************
*/
	  
function wpclpl_build_css_output(){
	
	global $wpclpl_plugin_options;
	
	$output = '';
	
	// add bg image
	$image_url = (!empty($wpclpl_plugin_options['wpclpl_logo_url'])) ? esc_url($wpclpl_plugin_options['wpclpl_logo_url']) : '';
	$output .= 'background-image: url('.$image_url.');'."\n";
	
	return $output;
	
}




/*
* function wpclpl_settings_custom_css()
*
* @param boolean $return (default: false)
*
* builds the custom css style.
*****************************************
*/
function wpclpl_settings_custom_css($return = false){

	global $wpclpl_plugin_options;
	$wpclpl_logo_url = ( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ) ? esc_url($wpclpl_plugin_options['wpclpl_logo_url']) : '';

	  if( !$return ){
		  ?>
		<script type="text/javascript">
		jQuery(function($){
			
			$('.wpclpl-logo-example-css-btn').click(function(){
			
				exampleCss = 'padding: 0;'+"\n";
				exampleCss += 'background-size: cover;'+"\n";
				exampleCss += 'background-position: center center;'+"\n";
				exampleCss += 'background-repeat: no-repeat;'+"\n";
				exampleCss += 'background-color: #fff;'+"\n";
				
				exampleCss += 'width: '+$('#wpclpl-logo-width').attr('data-size-width')+'px;'+"\n";
				exampleCss += 'height: '+$('#wpclpl-logo-height').attr('data-size-height')+'px;'+"\n";
				
				$('.wpclpl-custom-css').val(exampleCss);
				
				var backgroundImage = 'background-image:url('+$('.wpclpl-logo-url').val()+');';
				
			});
			
		});
		</script>
		<?php
		$custom_css =  ( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ) ? wpclpl_build_css_output() : '';		
		?>
		<textarea class="wpclpl-custom-css" name="wpclpl_custom_css" ><?php echo $wpclpl_plugin_options['wpclpl_custom_css']; ?></textarea>
		<br />
		<span class="wpclpl-description"><?php _e('Enter your custom css style for your logo.','wpclpl'); ?><br />
		<?php _e('There\'s nothing to see at the beginning because the login page logo is styled by default.','wpclpl') ?>
		<br /><?php _e('You also may load an example css to start and customize it.','wpclpl'); ?></strong>
		
		<p><input class="wpclpl-logo-example-css-btn button" type="button" value="<?php esc_attr_e('Load example CSS','wpclpl'); ?>" /> </p>

		<p class="wpclpl-notice">
			<strong><?php _e('Note:  There\'s no need to insert an','wpclpl'); ?> </strong><code><?php _e('background-image','wpclpl'); ?></code> <strong><?php _e('value here, it will be added by default to the final output.','wpclpl'); ?></strong>
			</span>
		</p>
		
		
		<?php
	  } else {
	  
	  	// output the plain css 
		  return $wpclpl_plugin_options['wpclpl_custom_css'];
	  }

}



/*
* function wpclpl_admin_options_page()
* 
* builds the admin options page
****************************************
*/
function wpclpl_admin_options_page(){ ?>

		<?php 
		global $wpclpl_save;	
			
		// update options, if successful, show message
		if($wpclpl_save==1){ 		
		?>
			<script> jQuery(function($){ $('.wpclpl-settings-save-ok').fadeIn(500).delay(3000).slideUp(500); }); </script>
		<?php
		} // eof if($wpclpl_save==1) 
		?>

		<?php // modal windows: reset image only ?>
		<div class="wpclpl-modal-box wpclpl-modal-box-reset-image">
			<div>
				<h4><?php _e('Confirm reset','wpclpl'); ?></h4>
				<p><?php _e('This will remove the custom image.<br />(File will be kept in the library).','wpclpl'); ?>
				<br />
				<?php _e('Are you sure you want to continue?','wpclpl'); ?></p>
				<p><input type="button" class="wpclpl-reset-cancel button-secondary" value="<?php esc_attr_e('No, keep settings', 'wpclpl'); ?>" /> <input type="button" class="wpclpl-reset-confirmed button-primary" value="<?php esc_attr_e('Reset all settings', 'wpclpl'); ?>" /></p>
			</div>			
		</div>
			
			
		<?php // modal windows: reset all settings ?>
		<div class="wpclpl-modal-box wpclpl-modal-box-reset-all">
			<div>
				<h4><?php _e('Confirm reset','wpclpl'); ?></h4>
				<p><?php _e('This will reset all your settings, including the custom image, additonal text and any entered styles.', 'wpclpl'); ?>
				<br />
				<?php _e('Are you sure you want to continue?','wpclpl'); ?></p>
				<p><input type="button" class="wpclpl-reset-cancel button-secondary" value="<?php esc_attr_e('No, keep settings', 'wpclpl'); ?>" /> <input type="button" class="wpclpl-reset-confirmed button-primary" value="<?php esc_attr_e('Reset all settings', 'wpclpl'); ?>" /></p>
			</div>			
		</div>	
			
			
    	<div class="wrap">
			<div id="icon-themes" class="icon32"><br /></div>
			<h2><?php _e( 'Custom Login Page Logo', 'wpclpl' ); ?></h2>
			<p class="wpclpl-settings-save-ok"><?php _e('Settings saved.', 'wpclpl'); ?></p>
			<p class="wpclpl-settings-save-error"><?php _e('Error: Could not save settings.', 'wpclpl'); ?><br /><?php _e('Please try again.', 'wpclpl'); ?></p>
			
			<!-- form -->
			<form class="wpclpl-options-form" action="" enctype="multipart/form-data" method="post" enctype="multipart/form-data">
			<?php $wpclpl_plugin_options = (get_option( 'wpclpl_plugin_options' ) != false) ? get_option('wpclpl_plugin_options') : '';
				settings_fields('plugin_wpclpl_options');
				do_settings_sections('wpclpl');
			?>
			  <p class="submit">
			    	<input id="submit_options_form" type="submit" class="button-primary" value="<?php esc_attr_e('Save Settings', 'wpclpl'); ?>" />
					<input type="hidden" name="wpclpl_save" value="1" />	
			    </p>     
			</form>
			<!-- // form -->
	</div>
<?php
}
   


/*
* function wpclpl_update_options()
*
* saves the settings.
****************************************
*/    
function wpclpl_update_options(){

	// collect values in array...
	$wpclpl_plugin_options_arr = array( 
		'wpclpl_logo_url' => wpclpl_filter_vars( $_POST['wpclpl_logo_url'] ),
		'wpclpl_additional_text' => wpclpl_filter_vars( $_POST['wpclpl_additional_text'] ),
		'wpclpl_custom_css' => wpclpl_filter_vars( $_POST['wpclpl_custom_css'] ) 
	);
	
	// ...and store' em	
	// return ( (update_option('wpclpl_plugin_options', $wpclpl_plugin_options_arr )===TRUE) ) ? 1 : 0;
	update_option('wpclpl_plugin_options', $wpclpl_plugin_options_arr );
}
      

/*
* function wpclpl_enqueue_styles_scripts()
*
* enqueues required styles & scripts
*****************************************
*/    
function wpclpl_enqueue_styles_scripts() {

	wp_enqueue_style( 'wpclpl_plugin_styles', plugins_url( '/css/wp-custom-login-page-logo.css', __FILE__ ) );
	wp_enqueue_script( 'wpclpl_plugin_scripts', plugins_url( '/js/wp-custom-login-page-logo.js', __FILE__ ), array( 'jquery', 'media-upload', 'thickbox') );
	wp_enqueue_style('thickbox');
	wp_enqueue_script('thickbox');
	wp_enqueue_script('media-upload');
	wp_enqueue_script('wpclpl-upload'); 
	
}

// load our stuff if we sre on the plugin's settigns page.
$wpclpl_page = (isset($_GET['page'])) ? $_GET['page'] : '';
if($wpclpl_page == "wp-custom-login-page-logo.php"){
	add_action( 'admin_enqueue_scripts', 'wpclpl_enqueue_styles_scripts' );
}



/*
* function wpclpl_custom_login_logo()
*
* builds final html output on admin login page
**********************************************
*/

function wpclpl_custom_login_logo() {
	
	global $wpclpl_plugin_options;

	// do we have an image url?
	if( !empty( $wpclpl_plugin_options['wpclpl_logo_url'] ) ){
		$wpclpl_plugin_logo_url =  esc_url($wpclpl_plugin_options['wpclpl_logo_url']);
	?>
	
	<style type="text/css">                                                                                   
    body.login div#login h1 a {
    	<?php echo wpclpl_settings_logo_plain_url(); ?>
	    <?php echo wpclpl_settings_custom_css(true); ?>
    }
    </style>

    <?php
	} else {
		$wpclpl_plugin_logo_url = '';
	} // eof if( !empty(...) )

}
add_action('login_head', 'wpclpl_custom_login_logo');



/*
* function wpclpl_footer_js()
*
* adds the required footer javascript
*
*************************************
*/ 
function wpclpl_footer_js(){
	global $wpclpl_plugin_options;

	// js doesn't like line breaks in strings...
	$wpclpl_additional_text = str_ireplace(array("\r","\n",'\r','\n'),'', $wpclpl_plugin_options['wpclpl_additional_text']);

	$wpclpl_loggedout = (isset($_GET['loggedout']) && $_GET['loggedout']!="true") ? htmlentities(strip_tags($_GET['loggedout'])) : '';
?>

    <script>
	    jQuery(function(){
		    jQuery('#login h1 a').attr('href','<?php echo esc_url( home_url( '/' ) ); ?>').attr('title','<?php $bloginfo=get_bloginfo('description'); echo $bloginfo; ?>');  
	    });
   
<?php 
	if($wpclpl_loggedout != "true"){ 
?>
	
    jQuery(function($){
    	var wpclpl_additional_text = '<?php echo $wpclpl_additional_text; ?>';
    	$('<p style="text-align:center">'+wpclpl_additional_text+'</p>').insertAfter("#login h1");
    });	    
   
<?php
    }
?>
 </script>
 <?php
}
add_action('login_footer', 'wpclpl_footer_js');



/*
* function wpclpl_options_settings_init()
*
* loads required settings
*****************************************
*/   
function wpclpl_options_settings_init() {  
    
    //register_setting( 'plugin_wpclpl_options', 'plugin_wpclpl_options');  
  
    // Form 
    add_settings_section('wpclpl_settings_header', __( 'Settings', 'wpclpl' ), 'wpclpl_settings_header_text', 'wpclpl');  
  
    // Logo uploader  
    add_settings_field('wpclpl_settings_logo',  __( 'Logo', 'wpclpl' ), 'wpclpl_settings_logo', 'wpclpl', 'wpclpl_settings_header');
    
    // Current Image Preview  
	add_settings_field('wpclpl_settings_logo_preview',  __( 'Logo Preview', 'wpclpl' ), 'wpclpl_settings_logo_preview', 'wpclpl', 'wpclpl_settings_header');  
	
	// additional text to appear
	add_settings_field('wpclpl_settings_add_text',  __( 'Additional Text', 'wpclpl' ), 'wpclpl_settings_add_text', 'wpclpl', 'wpclpl_settings_header');  
	
	// custom css
	add_settings_field('wpclpl_settings_custom_css',  __( 'Custom CSS Styles', 'wpclpl' ), 'wpclpl_settings_custom_css', 'wpclpl', 'wpclpl_settings_header');  
	
}
add_action( 'admin_init', 'wpclpl_options_settings_init' );


?>