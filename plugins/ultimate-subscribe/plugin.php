<?php
/*
Plugin Name: Ultimate Subscribe
Description: Ultimate Subscribe provide awesome forms and popups to collect lead insistently. Connect with MailChimp, GetResponse and  Many are coming.
Version:     1.3
Author:      ThemeFarmer
Author URI:  https://www.themefarmer.com
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: /languages
Text Domain: ultimate-subscribe

Ultimate Subscribe is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Ultimate Subscribe is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Ultimate Subscribe. If not, see https://www.gnu.org/licenses/gpl-2.0.html.
*/


// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    exit;
}


define( 'ULTIMATE_SUBSCRIBE_DIR', plugin_dir_path( __FILE__ ) );
define( 'ULTIMATE_SUBSCRIBE_URI', plugin_dir_url( __FILE__ ) );
require_once ULTIMATE_SUBSCRIBE_DIR.'/admin/admin-init.php';
require_once ULTIMATE_SUBSCRIBE_DIR.'/inc/register-subscriber.php';


/**
 * 
 */
class Ultimate_Subscribe{
	
	function __construct(){
		
	}
}



function ultimate_subscribe_get_socials(){
	$options            = ultimate_subscribe_default_options();;
	$socials            = isset($options['socials'])?$options['socials']:array();
	ob_start();
	if($socials): ?>
	<div class="ultimate-subscribe-socials">
		<?php foreach ($socials as $key => $social): if(!empty($social['url'])): ?>
		<a href="<?php echo esc_url($social['url']); ?>" class="us-socials-link"><i class="<?php echo esc_attr($social['icon']); ?>"></i></a>
		<?php endif; endforeach;?>
	</div>
	<?php endif; 
	$html = ob_get_clean();
	return $html;
}
function ultimate_subscribe_add_frontend_code(){
	$options            = get_option('ultimate_subscribe_options');
	$overlay_hide       = isset($options['overlay_hide'])?$options['overlay_hide']:0;
	$overlay_color      = isset($options['overlay_color'])?$options['overlay_color']:'rgba(25, 23, 23, 0.86)';
	$leave_form_id      = isset($options['leave_form_id'])?$options['leave_form_id']:'';
	$show_loged_in      = isset($options['show_loged_in'])?$options['show_loged_in']:'';
	$cookie_hide 		= (isset($_COOKIE['ultimate_subscribe_confirmed']) && absint($_COOKIE['ultimate_subscribe_confirmed']) ==1 )?true:false;

	if($cookie_hide){
		$show = false;
	}elseif(is_user_logged_in()){
		if($show_loged_in){
			$show = true;
		}else{
			$show = false;
		}
	}else{
		$show = true;
	}

	if($show):
		$socials = ultimate_subscribe_get_socials();
		$args = array( 'post_type' => 'u_subscribe_forms', 'posts_per_page' => -1, 'meta_key' => 'ultimate_subscribe_form_popup_enable', 'meta_value' => 1 );
		$wp_query = new WP_Query( $args );
		while($wp_query->have_posts()){
			$wp_query->the_post();
			$details 			= get_post_meta(get_the_ID(), 'ultimate_subscribe_form_details', true);
			$design_id 			= (isset($details['design_id']) && !empty($details['design_id']))?$details['design_id']:1;
			$settings			= get_post_meta(get_the_ID(), 'ultimate_subscribe_form_popup_settings', true);
			$popup_delay 		= isset($settings['popup_delay'])?$settings['popup_delay']:5;
			$popup_width      	= isset($settings['popup_width'])?$settings['popup_width']:'';
			$popup_width_unit   = isset($settings['popup_width_unit'])?$settings['popup_width_unit']:'px';
			$popup_bg_image     = isset($settings['popup_bg_image'])?$settings['popup_bg_image']:'';
			$popup_bg_color     = isset($settings['popup_bg_color'])?$settings['popup_bg_color']:'';
			$popup_animation    = isset($settings['popup_animation'])?$settings['popup_animation']:'';

			$form_text   			= get_post_meta(get_the_ID(), 'ultimate_subscribe_form_text', true);
			$heading   				= isset($form_text['heading'])?$form_text['heading']:'';
			$sub_heading   			= isset($form_text['sub_heading'])?$form_text['sub_heading']:'';
			$descricption   		= isset($form_text['descricption'])?$form_text['descricption']:'';
			$after_subcribe_text  	= isset($form_text['after_subcribe_text'])?$form_text['after_subcribe_text']:'';
			$button_label       	= !empty($form_text['button_label'])?$form_text['button_label']:__('Subscribe', 'ultimate-subscribe');
			
			?>
			<div class="ultimate-subscribe-overlay" id="<?php the_ID(); ?>" data-delay="<?php echo absint($popup_delay) ?>" data-overlayhide="<?php echo absint($overlay_hide); ?>" style="display:none; background-color: <?php echo esc_attr($overlay_color); ?>">
				<div class="ultimate-subscribe-container form<?php echo absint($design_id); ?>">
					<div class="tfus-inner">
						<div class="ultimate-subscribe-close" title="close" role="button"><i class="fa fa-times"></i></div>
						<div class="ultimate-subscribe-info">
							<h2 class="ultimate-subscribe-title"><?php echo esc_html($heading); ?></h2>
							<p class="ultimate-subscribe-subtitle"><?php echo esc_html($sub_heading); ?></p>
							<p class="ultimate-subscribe-desc"><?php echo esc_html($descricption); ?></p>
						</div>
						<div class="ultimate-subscribe-form-con">
							<form method="post"  class="ultimate-subscribe-form">
								<p class="tfus-field-row">
									<input type="text" class="field-input" id="ultimate-subscribe-fname" name="fname" placeholder="First Name" data-validation=""/>
								</p>
								<p class="tfus-field-row">
									<input type="text" class="field-input" id="ultimate-subscribe-lname" name="lname" placeholder="Last Name" data-validation=""/>
								</p>
								<p class="tfus-field-row">
									<input type="email" class="field-input" id="ultimate-subscribe-email" name="email" placeholder="Email Address" data-validation="email, required"/>
								</p>
								<p class="tfus-field-row">
									<input type="hidden" id="ultimate-subscribe-form-id" name="form_id" value="<?php the_ID(); ?>">
									<button type="submit" name="submit" class="ultimate-subscribe-submit" > <?php echo esc_html($button_label); ?> <i class="ultimate-subscribe-submit-icon fa fa-paper-plane-o" aria-hidden="true"></i> </button>
									<p class="ultimate-subscribe-btntext"><?php echo esc_html($after_subcribe_text); ?></p>
								</p>
							</form>
						</div>
						<?php echo $socials; ?>
						<div class="ultimate-subscribe-res"></div>
					</div>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	endif;
	?>
<?php
}
add_action('wp_footer', 'ultimate_subscribe_add_frontend_code');

function ultimate_subscribe_add_shortcode( $atts ) {
    $form_id = absint($atts['id']);
    $details 				= get_post_meta($form_id, 'ultimate_subscribe_form_details', true);
	$design_id 				= (isset($details['design_id']) && !empty($details['design_id']))?$details['design_id']:1;
	$settings				= get_post_meta($form_id, 'ultimate_subscribe_form_popup_settings', true);
	$popup_delay 			= isset($settings['popup_delay'])?$settings['popup_delay']:5;
	$popup_width      		= isset($settings['popup_width'])?$settings['popup_width']:'';
	$popup_width_unit   	= isset($settings['popup_width_unit'])?$settings['popup_width_unit']:'px';
	$popup_bg_image     	= isset($settings['popup_bg_image'])?$settings['popup_bg_image']:'';
	$popup_bg_color     	= isset($settings['popup_bg_color'])?$settings['popup_bg_color']:'';
	$socials 				= ultimate_subscribe_get_socials();
	$form_text   			= get_post_meta($form_id, 'ultimate_subscribe_form_text', true);
	$heading   				= isset($form_text['heading'])?$form_text['heading']:'';
	$sub_heading   			= isset($form_text['sub_heading'])?$form_text['sub_heading']:'';
	$descricption   		= isset($form_text['descricption'])?$form_text['descricption']:'';
	$after_subcribe_text  	= isset($form_text['after_subcribe_text'])?$form_text['after_subcribe_text']:'';
	$button_label       	= !empty($form_text['button_label'])?$form_text['button_label']:__('Subscribe', 'ultimate-subscribe');
	ob_start();
	?>
		<div class="shortcode ultimate-subscribe-container form<?php echo absint($design_id); ?>">
			<div class="tfus-inner">
				<div class="ultimate-subscribe-info">
					<h2 class="ultimate-subscribe-title"><?php echo esc_html($heading); ?></h2>
					<p class="ultimate-subscribe-subtitle"><?php echo esc_html($sub_heading); ?></p>
					<p class="ultimate-subscribe-desc"><?php echo esc_html($descricption); ?></p>
				</div>
				<div class="ultimate-subscribe-form-con">
					<form method="post"  class="ultimate-subscribe-form">
						<p class="tfus-field-row">
							<input type="text" class="field-input" id="ultimate-subscribe-fname" name="fname" placeholder="First Name" data-validation=""/>
						</p>
						<p class="tfus-field-row">
							<input type="text" class="field-input" id="ultimate-subscribe-lname" name="lname" placeholder="Last Name" data-validation=""/>
						</p>
						<p class="tfus-field-row">
							<input type="email" class="field-input" id="ultimate-subscribe-email" name="email" placeholder="Email Address" data-validation="email, required"/>
						</p>
						<p class="tfus-field-row">
							<input type="hidden" id="ultimate-subscribe-form-id" name="form_id" value="<?php echo absint($form_id); ?>">
							<button type="submit" name="submit" class="ultimate-subscribe-submit" > <?php echo esc_html($button_label); ?> <i class="ultimate-subscribe-submit-icon fa fa-paper-plane-o" aria-hidden="true"></i> </button>
							<p class="ultimate-subscribe-btntext"><?php echo esc_html($after_subcribe_text); ?></p>
						</p>
					</form>
				</div>
				<?php echo $socials; ?>
				<div class="ultimate-subscribe-res"></div>
			</div>
		</div>
	<?php
	$form_html = ob_get_clean();
	return $form_html;
}
add_shortcode( 'ultimate_subscribe_from', 'ultimate_subscribe_add_shortcode' );


function ultimate_subscribe_scripts(){
	$options            = get_option('ultimate_subscribe_options');
	$leave_form_id      = isset($options['leave_form_id'])?$options['leave_form_id']:'';
	wp_enqueue_style( 'animate', plugin_dir_url( __FILE__ ) . 'css/animate.min.css');
	wp_enqueue_style('font-awesome', ULTIMATE_SUBSCRIBE_URI . "/css/font-awesome.min.css");
	wp_enqueue_style( 'jquery-form-validator', plugin_dir_url( __FILE__ ) . 'css/theme-default.min.css'); 
	wp_enqueue_style( 'ultimate-subscribe-style', plugin_dir_url( __FILE__ ) . 'css/subscribe.css'); 
	wp_enqueue_script( 'jquery-cookie',  plugin_dir_url( __FILE__ ) . 'js/jquery.cookie.js', array('jquery'), null, true);
	wp_enqueue_script( 'jquery-form-validator',  plugin_dir_url( __FILE__ ) . 'js/jquery.form-validator.min.js', array('jquery'), null, true);
	wp_enqueue_script( 'ultimate-subscribe-script',  plugin_dir_url( __FILE__ ) . 'js/subscribe.js', array('jquery'), null, true );
	wp_localize_script( 'ultimate-subscribe-script', 'ultimate_subscribe_object',
            array( 'ajax_url' => admin_url( 'admin-ajax.php' ), 'leave_form_id' => absint($leave_form_id)) );
}
add_action( 'wp_enqueue_scripts', 'ultimate_subscribe_scripts' );


function ultimate_subscribe_get_subscribe_form(){
	$form_id = 0;
	$all_post_ids = get_posts(array(
	    'fields'          => 'ids',
	    'posts_per_page'  => 1,
	    'post_type' => 'u_subscribe_forms'

	));
	if(count($all_post_ids)){
		$form_id = intval($all_post_ids[0]);
	}
	?>
	<div class="ultimate-subscribe-form-con">
		<form method="post"  class="ultimate-subscribe-form">
			<p class="tfus-field-row">
				<input type="email" class="field-input" id="ultimate-subscribe-email" name="email" placeholder="Email Address" data-validation="email, required"/>
			</p>
			<p class="tfus-field-row">
				<input type="hidden" id="ultimate-subscribe-form-id" name="form_id" value="<?php echo absint($form_id); ?>">
				<button type="submit" name="submit" class="ultimate-subscribe-submit" > <?php echo esc_html(get_theme_mod('themefarmer_subcribe_button_text' , _e('Subscribe'))); ; ?> <i class="ultimate-subscribe-submit-icon fa fa-paper-plane-o" aria-hidden="true"></i> </button>
				<p class="ultimate-subscribe-btntext"></p>
			</p>
		</form>
	</div>
	<div class="ultimate-subscribe-res"></div>
	<?php
}


function ultimate_subscribe__install() {
	$options = ultimate_subscribe_default_options();
	add_option( 'ultimate_subscribe_options', $options);
	flush_rewrite_rules();

	global $wpdb;
	$table_name = $wpdb->prefix . 'ultimate_subscribe';
	$charset_collate = $wpdb->get_charset_collate();
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`list_id` varchar(50) NOT NULL,
	`form_id` int(11) NOT NULL,
	`first_name` varchar(255) NOT NULL,
	`last_name` varchar(255) NOT NULL,
	`email` varchar(255) NOT NULL,
	`ip_address` varchar(50) NOT NULL,
	`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`updated` timestamp NOT NULL,
	`active_code` varchar(255) NOT NULL,
	`storege` varchar(200) NOT NULL,
	`active` tinyint(1) DEFAULT '0',
	PRIMARY KEY (`id`)
	) $charset_collate;";

	$table_name1 = $wpdb->prefix . 'ultimate_subscribe_lists';
	$sql1 = "CREATE TABLE IF NOT EXISTS $table_name1 (
	`id` int(11) NOT NULL AUTO_INCREMENT,
	`name` varchar(255) NOT NULL,
	`created` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
	PRIMARY KEY (`id`)
	) $charset_collate;";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
	dbDelta( $sql1 );
	
	global $wpdb;
	$wpdb->replace($table_name1, array('id' => 1, 'name'  => 'list 1'), array('%d', '%s'));
	$wpdb->replace($table_name1, array('id' => 2, 'name'  => 'list 2'), array('%d', '%s'));
	$wpdb->replace($table_name1, array('id' => 3, 'name'  => 'list 3'), array('%d', '%s'));
	
	// Create a Form
	$post = get_page_by_title('Form 1', OBJECT, 'u_subscribe_forms');
	if(!$post){
		$form1 = array(
		  'post_title'    => 'Form 1',
		  'post_content'  => '',
		  'post_status'   => 'publish',
		  'post_type' 	  => 'u_subscribe_forms'
		);
		$form_id = wp_insert_post($form1);
		$settings['list_storege']			= sanitize_text_field('database');
		$settings['list_id']				= absint(1);
		$form_text['heading']				= sanitize_text_field(__('Subscribe To Our Newsletter', 'ultimate-subscribe'));
		$form_text['sub_heading']			= sanitize_text_field(__('subscribe to our newsletter to get latest offers & updates into your email inbox', 'ultimate-subscribe'));
		$form_text['descricption']			= sanitize_text_field(__('MONTHLY NEWSLETTER', 'ultimate-subscribe'));
		$form_text['after_subcribe_text']	= sanitize_text_field(__("don\'t worry we hate spam as much as you do!", 'ultimate-subscribe'));
		$form_text['button_label']			= sanitize_text_field(__('Subscribe', 'ultimate-subscribe'));
		
		update_post_meta($form_id, 'ultimate_subscribe_form_settings', $settings);
		update_post_meta($form_id, 'ultimate_subscribe_form_text', $form_text);
	}
	setcookie('ultimate_subscribe_active_tab', 'form-tabc');

}
register_activation_hook(__FILE__, 'ultimate_subscribe__install');

function ultimate_subscribe__uninstall() {
	delete_option( 'ultimate_subscribe_options');
}
register_deactivation_hook(__FILE__, 'ultimate_subscribe__uninstall');

