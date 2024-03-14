<?php
add_action('admin_menu', 'wbvcbaic_menu_page');
function wbvcbaic_menu_page(){
	global $submenu;
	add_menu_page(
		'Before After Slider for WPBakery',
		'Before After Slider for WPBakery',
		'manage_options',
		'wbvc-before-after-slider',
		'wbvc_before_after_slider_callback',
		'dashicons-image-flip-horizontal',
		'59'
	);

	add_submenu_page(
		'wbvc-before-after-slider',
		'Custom CSS',
		'Custom CSS',
		'manage_options',
		'wbvc-before-after-custom-css',
		'wbvc_before_after_slider_css_callback' 
	);

	add_submenu_page(
		'wbvc-before-after-slider',
		'Custom JS',
		'Custom JS',
		'manage_options',
		'wbvc-before-after-custom-js',
		'wbvc_before_after_slider_js_callback' 
	);

	$link_text = '<span class="wpvcbaic-up-pro-link" style="font-weight: bold; color: #FCB214">Upgrade To Pro</span>';
			
	$submenu["wbvc-before-after-slider"][4] = array( $link_text, 'manage_options' , 'https://plugin-devs.com/product/before-after-slider-for-wpbakery/' );

	return $submenu;
}

function wbvc_before_after_slider_callback(){}
function wbvc_before_after_slider_css_callback(){
	 // The default message that will appear
    $custom_css_default = __( '/*
Welcome to the Custom CSS editor!

Please add all your custom CSS here and avoid modifying the core plugin files. Don\'t use <style> tag
*/');
	    $custom_css = get_option( 'wbvcbaic_custom_css', $custom_css_default );
?>
	    <div class="wrap">
	        <div id="icon-themes" class="icon32"></div>
	        <h2><?php _e( 'Custom CSS' ); ?></h2>
	        <?php if ( ! empty( $_GET['settings-updated'] ) ) echo '<div id="message" class="updated"><p><strong>' . __( 'Custom CSS updated.' ) . '</strong></p></div>'; ?>
	 
	        <form id="custom_css_form" method="post" action="options.php" style="margin-top: 15px;">
	 
	            <?php settings_fields( 'wbvcbaic_custom_css' ); ?>
	 
	            <div id="custom_css_container">
	                <div name="wbvcbaic_custom_css" id="wbvcbaic_custom_css" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 400px; position: relative;"></div>
	            </div>
	 
	            <textarea id="custom_css_textarea" name="wbvcbaic_custom_css" style="display: none;"><?php echo $custom_css; ?></textarea>
	            <p><input type="submit" class="button-primary" value="<?php _e( 'Save Changes' ) ?>" /></p>
	        </form>
	    </div>
<?php
}

function wbvc_before_after_slider_js_callback(){
	// The default message that will appear
    $custom_js_default = __( '/*
Welcome to the Custom JS editor!

Please add all your custom JS here and avoid modifying the core plugin files. Don\'t use <script> tag
*/');
	    $custom_css = get_option( 'wbvcbaic_custom_js', $custom_js_default );
?>
	    <div class="wrap">
	        <div id="icon-themes" class="icon32"></div>
	        <h2><?php _e( 'Custom JS' ); ?> <a href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank" class="button" style="background: #FCB214; color: #fff;font-weight: 700">Upgrade to Pro</a></h2>
	        <?php if ( ! empty( $_GET['settings-updated'] ) ) echo '<div id="message" class="updated"><p><strong>' . __( 'Custom JS updated.' ) . '</strong></p></div>'; ?>

	        <h3>This is a Pro Version feature. You have need to <a href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank">upgrade to the pro</a> version to use this feature.</h3>
	 
	        <form id="custom_js_form" method="post" action="#" onsubmit="return false;" style="margin-top: 15px;">
	 
	            <?php settings_fields( 'wbvcbaic_custom_js' ); ?>
	 
	            <div id="custom_css_container">
	                <div name="wbvcbaic_custom_js" id="wbvcbaic_custom_js" style="border: 1px solid #DFDFDF; -moz-border-radius: 3px; -webkit-border-radius: 3px; border-radius: 3px; width: 100%; height: 400px; position: relative;"></div>
	            </div>
	 
	            <textarea id="custom_js_textarea" name="wbvcbaic_custom_js" style="display: none;"><?php echo $custom_css; ?></textarea>
	            <p><input type="submit" class="button-primary disabled" value="<?php _e( 'Save Changes' ) ?>" /><a href="https://plugin-devs.com/product/before-after-slider-for-wpbakery/" target="_blank" class="button" style="background: #FCB214; color: #fff;font-weight: 700; margin-left: 10px">Upgrade to Pro</a></p>
	        </form>
	    </div>
<?php
}

add_action( 'admin_enqueue_scripts', 'wbvcbaic_custom_css_js_scripts' );
function wbvcbaic_custom_css_js_scripts( $hook ) {
	
    if ( ('before-after-slider-for-wpbakery_page_wbvc-before-after-custom-css' == $hook) || ('before-after-slider-for-wpbakery_page_wbvc-before-after-custom-js' == $hook) ) {
        wp_enqueue_script( 'ace_code_highlighter_js', WB_VC_BAIC_URL . 'assets/ace/js/ace.js', '', '1.0.0', true );
        wp_enqueue_script( 'ace_mode_css', WB_VC_BAIC_URL . 'assets/ace/js/mode-css.js', array( 'ace_code_highlighter_js' ), '1.0.0', true );
        wp_enqueue_script( 'ace_mode_js', WB_VC_BAIC_URL . 'assets/ace/js/mode-javascript.js', array( 'ace_code_highlighter_js' ), '1.0.0', true );
        wp_enqueue_script( 'custom_css_js', WB_VC_BAIC_URL . 'assets/ace/ace-include.js', array( 'jquery', 'ace_code_highlighter_js' ), '1.0.0', true );
    }
}

add_action( 'admin_init', 'wbvcbaic_register_custom_css_setting' ); 
function wbvcbaic_register_custom_css_setting() {
    register_setting( 'wbvcbaic_custom_css', 'wbvcbaic_custom_css',  'wbvcbaic_custom_css_validation');
    register_setting( 'wbvcbaic_custom_js', 'wbvcbaic_custom_js');
}

function wbvcbaic_custom_css_validation( $input ) {
    if ( ! empty( $input['wbvcbaic_custom_css'] ) )
        $input['wbvcbaic_custom_css'] = trim( $input['wbvcbaic_custom_css'] );
    return $input;
}


// Admin footer Thank you modification
function wbvcbaic_remove_footer_admin ($text) 
{
	$page = '';
	if( isset($_GET['page']) ){
		$page = $_GET['page'];
	}
	if( $page == 'wbvc-before-after-custom-css' || $page == 'wbvc-before-after-custom-js' || $page == 'wb-page-wbvcbaic-support' ){
		$text = '<span id="footer-thankyou">If you like our <strong>Before After Slider.</strong> please leave us a <a href="https://wordpress.org/support/plugin/before-after-image-comparison-slider-for-visual-composer/reviews/?rate=5#new-post" target="_blank" class="wc-rating-link" aria-label="five star" data-rated="Thanks :)">★★★★★</a> rating. A huge thanks in advance!</span>';
	}
    return $text;
}
 
add_filter('admin_footer_text', 'wbvcbaic_remove_footer_admin');