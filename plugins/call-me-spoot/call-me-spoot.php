<?php
/*
Plugin Name: Callback request form
Text Domain: cms30
Domain Path: /languages
Description: Callback request form plugin.
Version: 1.4
Author: Alex Kuimov
Author URI: https://cms3.ru/#cr
Plugin URI: https://cms3.ru/knopka-obratnogo-zvonka-wordpress/
*/

//languages
add_action('plugins_loaded','cms30_languages');
function cms30_languages() {
	load_plugin_textdomain('cms30', false, dirname( plugin_basename( __FILE__ ) ).'/languages/');
}

/*enqueue script and style*/
function cms30_script() {
	wp_register_script('cms30-phone-mask', plugins_url('js/phone_mask.js', __FILE__));
	wp_enqueue_script('cms30-phone-mask' );
	wp_register_script('cms30-script', plugins_url('js/script.js', __FILE__));
	wp_enqueue_script('cms30-script');
	wp_localize_script('cms30-script', 'ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
}

function cms30_style() {
	wp_register_style('cms30-style', plugins_url('css/style.css', __FILE__), false, false, 'all');
	wp_enqueue_style('cms30-style');
	wp_register_style('font-awesome', plugins_url('css/font-awesome.css', __FILE__), false, false, 'all');
	wp_enqueue_style('font-awesome');
}
add_action('wp_head', 'cms30_script');
add_action('wp_enqueue_scripts', 'cms30_style');

/*core section*/
function cms30_button(){
	?><a class="cms30_link cms30_button" href="#cms30_call_me"><i class="fa fa-phone-square" aria-hidden="true"></i> <?php echo __('Callback request','cms30'); ?></a><?php
}	

function cms30_form(){
	?><div class="cms30_modal_wrapper" id="cms30_call_me">
	    <a href="#close" class="cms30_close_modal"></a>
	    <div class="cms30_modal_dialog">
	        <div class="cms30_container">  
	            <form class="cms30_callback_form" action="#" method="post">
	                <a href="#close" class="cms30_close_modal_min"></a>
	                <div class="title_h3"><?php echo __('Callback form','cms30'); ?></div>              
	                <input name="cms30_phone" class="cms30_phone" placeholder="<?php echo __('Phone','cms30'); ?>" type="tel" tabindex="1">
	                <input type="hidden" name="cms30_msg" class="cms30_msg" value="<?php echo __('Thanks!','cms30'); ?>">
	                <button name="submit" type="submit"><?php echo __('Send','cms30'); ?></button>
	                <a class="copyright" title="<?php echo __('Форма обратного звонка WordPress','cms30'); ?>" href="https://cms3.ru/"><?php echo __('Форма обратного звонка WordPress','cms30'); ?></a>
	            </form>
	        </div>        
	    </div>
	</div><?php
}

add_action('wp_footer', 'cms30_button');
add_action('wp_footer', 'cms30_form');

/*ajax*/
function cms30_send(){

	$cms30_phone = sanitize_text_field($_GET['phone']);
	$cms30_email_send = get_option('admin_email');
	$cms30_title = __('Callback request form','cms30');	
	$cms30_message = __('Call me','cms30').': '.$cms30_phone;
	
	add_filter('wp_mail_charset', create_function('', 'return "utf-8";'));
	add_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

	wp_mail($cms30_email_send, $cms30_title, $cms30_message, $headers, $attachments);
	
	remove_filter('wp_mail_charset', create_function('', 'return "utf-8";'));
	remove_filter('wp_mail_content_type', create_function('', 'return "text/html";'));

	wp_die();

}

add_action('wp_ajax_cms30_send', 'cms30_send');
add_action('wp_ajax_nopriv_cms30_send', 'cms30_send');
