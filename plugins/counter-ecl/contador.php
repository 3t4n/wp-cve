<?php
/*
 Plugin Name: Counter ECL
 Plugin URI: https://www.infocerda.com/en/plugins-for-wordpress/
 Description: Web counter widget and cookie Law.
 Author: Enrique Cerda
 Version: 1.5
 Author URI: http://www.infocerda.com/
 Text Domain: counter-ecl
 */

/*
 * Copyright 2015 Enrique Cerda
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 */

$ecl_cont_visited = false;
$ecl_cont_message = false;
$ecl_cont_old = ecl_cont_get_counter(false);
$ecl_domain = parse_url( get_option( 'home' ), PHP_URL_HOST);
/*
 * 
 * Initializtion Cookie
 * 
 * 
 */
if ( isset( $_COOKIE[ 'ecl_cont_visited_cookie_' . md5($ecl_domain) ] ) ) {
	
	$ecl_cookie = $_COOKIE[ 'ecl_cont_visited_cookie_' . md5($ecl_domain) ] ;
	
	if ( isset( $ecl_cookie ) && 
		$ecl_cookie == 'visited') {
			$ecl_cont_visited = true;
	}
}

if ( isset( $_COOKIE[ 'ecl_cont_message_cookie_' . md5($ecl_domain) ] ) ) {

	$ecl_cookie = $_COOKIE[ 'ecl_cont_message_cookie_' . md5($ecl_domain) ] ;

	if ( isset( $ecl_cookie ) &&
			$ecl_cookie == 'accept') {
				$ecl_cont_message = true;
			}
}

/*
 * 
 * Cookie Counter
 * 
 */
function ecl_cont_get_time_cookie_expire($display = true) {
 	$valor = ecl_cont_initializacion_time_cookie();
   
 	if ($display)
 		echo $valor;
 	else
 		return $valor;
}

function ecl_cont_initializacion_time_cookie() {
	$time_cookie = get_option('counter_ecl_time');
	if (($time_cookie == false) || ($time_cookie == null) ) {
		$time_cookie = 365;
		add_option( 'counter_ecl_time', $time_cookie, '', 'yes' );
	} elseif (!is_numeric($time_cookie)) {
		$time_cookie = 365;
		update_option('counter_ecl_time', $time_cookie, 'yes');
	}
	return floatval($time_cookie);
}
/*
 *
 * Cookie Message
 *
 */
function ecl_cont_get_time_cookie_expire_message($display = true) {
	$valor = ecl_cont_initializacion_time_cookie_message();
	 
	if ($display)
		echo $valor;
	else
		return $valor;
}

function ecl_cont_initializacion_time_cookie_message() {
	$time_cookie = get_option('counter_ecl_time_message');
	if (($time_cookie == false) || ($time_cookie == null) ) {
		$time_cookie = 365;
		add_option( 'counter_ecl_time_message', $time_cookie, '', 'yes' );
	} elseif (!is_numeric($time_cookie)) {
		$time_cookie = 365;
		update_option('counter_ecl_time_message', $time_cookie, 'yes');
	}
	return floatval($time_cookie);
}
/*
 * 
 * CSS
 * 
 */

function ecl_cont_scripts() {
	// Load Bootstrap
	wp_enqueue_style( 'ecl-cont-bootstrap' );
	
	// Load Bootstrap theme
	wp_enqueue_style('ecl-cont-bootstrap-theme' );
	
	// Load counter-ecl css
	wp_enqueue_style('ecl-cont-css');
	
	wp_enqueue_script('jquery');
	
	// Load counter-ecl js
	wp_enqueue_script('ecl-cont-js');

}
add_action( 'wp_enqueue_scripts', 'ecl_cont_scripts' );

/*
 * 
 * Settings Plugin
 * 
 */

function ecl_cont_settings_link( $links, $file ) {
	
	$plugin_file = plugin_basename( __FILE__ );
	
	if ( $file == $plugin_file ) {
		$settings_link = '<a href="' . admin_url('admin.php?page=' . 'counter-ecl') . '">' . __('Settings', 'counter-ecl') . '</a>';
		array_unshift( $links, $settings_link );
	}
	
	return $links;
}
add_filter( 'plugin_action_links',  'ecl_cont_settings_link', 10, 2 );

/*
 * 
 *  Languages
 * 
 */

function ecl_cont_textdomain() {
	
	load_plugin_textdomain( 'counter-ecl', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	
}
add_action( 'plugins_loaded', 'ecl_cont_textdomain' );

/*
 * 
 *  Counter
 * 
 * 
 */

function ecl_cont_add_visitor() {	
 	global $ecl_cont_visited;
 	if ( !$ecl_cont_visited && function_exists( 'is_admin' ) && !is_admin() ) {
 		$contador_ecl = ecl_cont_initializacion();
 		$contador_ecl++;
 		ecl_cont_update_counter( $contador_ecl );
 	}

}
add_action('wp_loaded', 'ecl_cont_add_visitor' ); 

function ecl_cont_initializacion() {
 	$contador_ecl = get_option( 'counter_ecl' );
 	if (($contador_ecl == false) || ($contador_ecl == null) ) {
 		$contador_ecl = 0;
 		add_option( 'counter_ecl', $contador_ecl, '', 'yes' );	
 	} elseif (!is_numeric($contador_ecl)) {
 		$contador_ecl = 0;
 		update_option('counter_ecl', $contador_ecl, 'yes');	
 	}
 	return floatval($contador_ecl);
}
 
function ecl_cont_get_counter($display = true) {
 	$valor = ecl_cont_initializacion();
    
 	if ($display)
 		echo $valor;
 	else
 		return $valor;
 
}
 
function ecl_cont_update_counter($valor) {
 	$valor_old = ecl_cont_initializacion();
 	$valor_new = floatval($valor);
 	if ($valor_old != $valor_new) {
 		update_option('counter_ecl', $valor_new, 'yes');	
 	}
}
 
 /*
 *
 * Menu Admin
 *
 */
 
 function ecl_cont_designer_menu() {
 	add_menu_page('Counter ECL', 'Counter ECL', 'manage_options', 'counter-ecl', 'ecl_cont_designer_page', plugins_url( 'images/menu-icon.png', __FILE__ ) );
 	add_action( 'admin_init', 'ecl_cont_designer_settings' );
 }
 add_action('admin_menu', 'ecl_cont_designer_menu');
 
 function ecl_cont_get_message() {
 	$ecl_message_default = __('<h6 class="text-warning">Using cookies</h6><small>This site uses cookies for you to have the best user experience. If you continue to browse you are consenting to the acceptance of the aforementioned cookies and acceptance of our cookie policy</small>', 'counter-ecl');
 	
 	$ecl_message = get_option( 'counter_ecl_message' );
 	if ($ecl_message == false) {
 		$ecl_message = $ecl_message_default;
 		add_option( 'counter_ecl_message', $ecl_message, '', 'yes' );
  	} elseif (empty( $ecl_message )) {
  		$ecl_message = $ecl_message_default;
  		update_option( 'counter_ecl_message', $ecl_message, 'yes' );
  	}
  	
  	
  	return __($ecl_message, 'counter-ecl');
 }
 
 function ecl_cont_designer_settings() {
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_time');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_message_active');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_message');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_color_text');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_color_background');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_effects');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_position');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_hide');
 	register_setting( 'counter-ecl-settings-group', 'counter_ecl_time_message');
 }
 
 function ecl_cont_designer_page() {
 	?>
 	<div class='wrap'> 
 		<h1>Counter ECL</h1>
 		<p class="description"><?php _e('Counter ECL is a hit counter for your web.', 'counter-ecl'); ?></p>
 		<form method="post" action="options.php">
 			<?php settings_errors(); ?>
 			<?php settings_fields('counter-ecl-settings-group'); ?>
			<?php do_settings_sections('counter-ecl-settings-group'); ?>
 			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Counter ECL Settings', 'counter-ecl'); ?></h3>
					<div class="inside">
					<p><?php _e('Optimization Counter ECL', 'counter-ecl'); ?></p>
					<table class="form-table">
						<tr>
							<th scope="row">
								<label for="counter_ecl"><?php _e('Counter', 'counter-ecl');?></label>
							</th>
							<td>
								<input type="number" name="counter_ecl" class="normal-text"  id="counter_ecl" value="<?php ecl_cont_get_counter(); ?>" /> <?php _e('visitor(s)', 'counter-ecl')?> 
								<p class="description"><?php _e('This is the counter of your website.', 'counter-ecl'); ?></p>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="counter_ecl_time"><?php _e('Time Cookie Expired', 'counter-ecl'); ?></label>
							</th>
							<td>
								<input type="number" name="counter_ecl_time" class="normal-text"  id="counter_ecl_time" value="<?php ecl_cont_get_time_cookie_expire(); ?>" /> <?php _e('day(s)', 'counter-ecl')?>
								<p class="description"><?php _e('This is the expiry time of the cookie, once expired is recounted the visit.', 'counter-ecl'); ?></p>
							</td>
						</tr>
						<tr valign="top" align="left">
							<td class="frm_wp_heading">
								<?php submit_button(); ?>
							</td>
						</tr>											
					</table>
					</div>
				</div>
			</div>
			<div id="poststuff" class="ui-sortable meta-box-sortables">
				<div class="postbox">
					<h3><?php _e('Message New Visitor Settings', 'counter-ecl'); ?></h3>
					<div class="inside">
					<p><?php _e('Optimization Message for new visitor', 'counter-ecl'); ?></p>
					<table class="form-table">
					    <tr>
							<th scope="row">
								<label for="counter_ecl_message_active"><?php _e('Active Message for New Visitor', 'counter-ecl'); ?></label>
							</th>
							<td>
							    <p class="description"><?php _e('Active message for show when is a new visitor.', 'counter-ecl'); ?></p>
								<input name="counter_ecl_message_active" type="checkbox" id="counter_ecl_message_active" value="1" <?php checked('1', get_option('counter_ecl_message_active')); ?> />
							</td>
						</tr>
					    <tr>
							<th scope="row">
								<label for="counter_ecl_message"><?php _e('Message for New Visitor', 'counter-ecl'); ?></label>
							</th>
							<td>
							    <p class="description"><?php _e('This message show when is new visitor, include TAG if you want.', 'counter-ecl'); ?></p>
								<textarea name="counter_ecl_message" id="counter_ecl_message" class="large-text code" rows="3"><?php echo ecl_cont_get_message(); ?></textarea>
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="counter_ecl_color_background"><?php _e('Background Color', 'counter-ecl'); ?></label>
							</th>
							<td>
								<p class="description"><?php _e('Background color for your Message', 'counter-ecl'); ?></p>
 								<select name="counter_ecl_color_background" id="counter_ecl_color_background" class="widefat">
 									<option value=""<?php selected( get_option('counter_ecl_color_background'), '' ); ?>><?php _e( 'Default' , 'counter-ecl'); ?></option>
 									<option value="bg-success"<?php selected( get_option('counter_ecl_color_background'), 'bg-success' ); ?>><?php _e( 'Green', 'counter-ecl'); ?></option>
 									<option value="bg-info"<?php selected( get_option('counter_ecl_color_background'), 'bg-info' ); ?>><?php _e( 'Blue', 'counter-ecl' ); ?></option>
 									<option value="bg-warning"<?php selected( get_option('counter_ecl_color_background'), 'bg-warning' ); ?>><?php _e( 'Yellow', 'counter-ecl' ); ?></option>
 									<option value="bg-danger"<?php selected( get_option('counter_ecl_color_background'), 'bg-danger' ); ?>><?php _e( 'Red', 'counter-ecl'); ?></option>	
 			    					<option value="bg-dark"<?php selected( get_option('counter_ecl_color_background'), 'bg-dark' ); ?>><?php _e( 'Dark', 'counter-ecl'); ?></option>
 			    					<option value="bg-white"<?php selected( get_option('counter_ecl_color_background'), 'bg-white' ); ?>><?php _e( 'White', 'counter-ecl'); ?></option>
 								</select>
 							</td>
 						</tr>
						<tr>
						 	<th scope="row">
								<label for="counter_ecl_color_text"><?php _e('Text Color', 'counter-ecl'); ?></label>
							</th>
							<td>
							    <p class="description"><?php _e('Text color for your Message', 'counter-ecl'); ?></p>
								<select name="counter_ecl_color_text" id="counter_ecl_color_text" class="widefat">
 									<option value=""<?php selected( get_option('counter_ecl_color_text'), '' ); ?>><?php _e( 'Default', 'counter-ecl' ); ?></option>
 									<option value="text-success"<?php selected( get_option('counter_ecl_color_text'), 'text-success' ); ?>><?php _e( 'Green', 'counter-ecl' ); ?></option>
 									<option value="text-info"<?php selected( get_option('counter_ecl_color_text'), 'text-info' ); ?>><?php _e( 'Blue', 'counter-ecl' ); ?></option>
 									<option value="text-warning"<?php selected( get_option('counter_ecl_color_text'), 'text-warning' ); ?>><?php _e( 'Yellow', 'counter-ecl' ); ?></option>
 									<option value="text-danger"<?php selected( get_option('counter_ecl_color_text'), 'text-danger' ); ?>><?php _e( 'Red', 'counter-ecl'); ?></option>	
 									<option value="text-dark"<?php selected( get_option('counter_ecl_color_text'), 'text-dark' ); ?>><?php _e( 'Dark', 'counter-ecl'); ?></option>
 									<option value="text-white"<?php selected( get_option('counter_ecl_color_text'), 'text-white' ); ?>><?php _e( 'White', 'counter-ecl'); ?></option>		
 								</select>
							</td>
						</tr>	
						<tr>
 							<th scope="row">
								<label for="counter_ecl_effects"><?php _e('Effects windows', 'counter-ecl'); ?></label>
							</th>
							<td>
								<p class="description"><?php _e('Effects when show/hide window', 'counter-ecl'); ?></p>
 								<select name="counter_ecl_effects" id="counter_ecl_effects" class="widefat">
 									<option value=""<?php selected( get_option('counter_ecl_effects'), '' ); ?>><?php _e( 'None', 'counter-ecl' ); ?></option>
 									<option value="hide"<?php selected( get_option('counter_ecl_effects'), 'hide' ); ?>><?php _e( 'Hide', 'counter-ecl' ); ?></option>
 									<option value="fade"<?php selected( get_option('counter_ecl_effects'), 'fade' ); ?>><?php _e( 'Fade', 'counter-ecl'); ?></option>
 									<option value="slide"<?php selected( get_option('counter_ecl_effects'), 'slide' ); ?>><?php _e( 'Slide', 'counter-ecl'); ?></option>
 								</select>
 							</td>
						</tr>
						<tr>
 							<th scope="row">
								<label for="counter_ecl_position"><?php _e('Position window', 'counter-ecl'); ?></label>
							</th>
							<td>
								<p class="description"><?php _e('Position window in document', 'counter-ecl'); ?></p>
 								<select name="counter_ecl_position" id="counter_ecl_position" class="widefat">
 									<option value=""<?php selected( get_option('counter_ecl_position'), '' ); ?>><?php _e( 'Bottom', 'counter-ecl' ); ?></option>
 									<option value="top"<?php selected( get_option('counter_ecl_position'), 'top' ); ?>><?php _e( 'Top', 'counter-ecl' ); ?></option>
 								</select>
 							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="counter_ecl_hide"><?php _e('Hide Message when click document', 'counter-ecl'); ?></label>
							</th>
							<td>
							    <p class="description"><?php _e('Hide message when click document, does not imply accept', 'counter-ecl'); ?></p>
								<input name="counter_ecl_hide" type="checkbox" id="counter_ecl_hide" value="1" <?php checked('1', get_option('counter_ecl_hide')); ?> />
							</td>
						</tr>
						<tr>
							<th scope="row">
								<label for="counter_ecl_time_message"><?php _e('Time Cookie Expired', 'counter-ecl'); ?></label>
							</th>
							<td>
								<input type="number" name="counter_ecl_time_message" class="normal-text"  id="counter_ecl_time_message" value="<?php ecl_cont_get_time_cookie_expire_message(); ?>" /> <?php _e('day(s)', 'counter-ecl')?>
								<p class="description"><?php _e('This is the expiry time of the cookie, once expired show message.', 'counter-ecl'); ?></p>
							</td>
						</tr>
						<tr valign="top" align="left">
							<td class="frm_wp_heading">
								<?php submit_button(); ?>
							</td>
						</tr>
					</table>
					</div>
				</div>
			</div>
 		</form>
 	</div>
 	<?php 
 }
 
 /*
  * 
  * Widget CounterECL
  * 
  */
 
 class ecl_Widget_CounterECL extends WP_Widget {
 
 	public function __construct() {
 		$widget_ops = array('classname' => 'widget_counterecl', 'description' => __( "Web counter") );
 		parent::__construct('counterecl', __('Counter ECL'), $widget_ops);
 	}
 
 	/**
 	 * @param array $args
 	 * @param array $instance
 	 */
 	public function widget( $args, $instance ) {
 		/** This filter is documented in wp-includes/default-widgets.php */
 		global $ecl_cont_visited, $ecl_cont_old;
 		
 		$title = apply_filters( 'widget_title', empty($instance['title']) ? __( '', 'counter-ecl' ) : $instance['title'], $instance, $this->id_base );
 		$backgroundcolor = apply_filters('widget_backgroundcolor', empty($instance['backgroundcolor']) ? '': $instance['backgroundcolor']);
 		$textcolor = apply_filters('widget_textcolor', empty($instance['textcolor']) ? '': $instance['textcolor']);
 		$size = apply_filters('widget_size', empty($instance['size']) ? 'h3': $instance['size']);
 		$align = apply_filters('widget_align', empty($instance['align']) ? 'text-right': $instance['align']);
 		$type = apply_filters('widget_type', empty($instance['type']) ? 'text': $instance['type']);
 		$container = apply_filters('widget_container', empty($instance['container']) ? 'list': $instance['container']);
 		$formatnumber = apply_filters('widget_formatnumber', empty($instance['formatnumber']) ? '0': '1');
 		$display3d = apply_filters('widget_display3d', empty($instance['display3d']) ? '0': '1');
 		$separatenumbers = apply_filters('widget_separatenumbers', empty($instance['separatenumbers']) ? '0': '1');
 		$effects = apply_filters('widget_effects', empty($instance['effects']) ? 'none': $instance['effects']);
 		$tempo = apply_filters('widget_tempo', empty($instance['tempo']) ? 0: intval($instance['tempo']));
 		$topfix = apply_filters('widget_topfix', empty($instance['topfix']) ? 0: intval($instance['topfix']));
 		
 		$cont = ecl_cont_get_counter(false);
 		$cont_old = $ecl_cont_old;
 		
		if ($formatnumber) {
 			$cont = number_format($cont, 0);
 			$cont_old = number_format($ecl_cont_old, 0);	
		}
 		
 		$textcolortitle = $textcolor;
 		
 		$textSpan = '';
 		
 		switch ($type) { 
 			case 'text': 
 			case 'analog':
 			case 'badge': 
 				break;
 			case 'label': 
 				switch ($textcolor) {
 					case 'text-success': 
 						$textSpan = 'label-success';
 						break;
 					case 'text-info':
 						$textSpan = 'label-info';
 						break;
 					case 'text-warning':
 						$textSpan = 'label-warning';
 						break;
 					case 'text-danger':
 						$textSpan = 'label-danger';
 						break;
 					case 'text-dark':
 						$textSpan = 'label-dark';
 						break;
 					case 'text-white':
 						$textSpan = 'label-white';
 						break;
 					default: $textSpan = 'label-default';
 						
 				}
 				
 				$textcolor = '';
 				
 				break;
 			
 		}
 		
 		echo $args['before_widget']; 
 		
 		if ( $title ) { ?>
 			<div class="text-center <?php echo $textcolortitle; ?>">
 			<?php echo  $args['before_title'] . $title .  $args['after_title']; ?>
 			</div>
 		<?php 
 		}
 		
 		
 		switch ($container) {
 			case 'list': ?>
 			
 				<ul class="list-group" id="<?php echo $args['widget_id'] . '-c'; ?>">
 					<li class="list-group-item <?php echo $backgroundcolor; ?>">
 				        <?php echo ecl_cont_js_counter( $type, $size, $textSpan, $args['widget_id'], $textcolor, $align, $cont, $cont_old, $display3d, $separatenumbers, $effects, $tempo, $topfix ); ?>
 					</li>
 				</ul>
 				
 			<?php break;
 			
 			case 'panel': 
 				switch ($backgroundcolor) {
 					case 'list-group-item-success':
 						$backgroundcolor = 'panel-success';
 						break;
 					case 'list-group-item-info':
 						$backgroundcolor = 'panel-info';
 						break;
 					case 'list-group-item-warning':	
 						$backgroundcolor = 'panel-warning';
 						break;
 					case 'list-group-item-danger':
 						$backgroundcolor = 'panel-danger';	
 						break;
 					case 'list-group-item-dark':
 						$backgroundcolor = 'panel-dark';
 						break;
 					case 'list-group-item-white':
 						$backgroundcolor = 'panel-white';
 						
 				}
 				?>
 				
 				<div class="panel <?php echo $backgroundcolor; ?>" id="<?php echo $args['widget_id'] . '-c'; ?>">
 					<div class="panel-heading">
 					     <?php echo ecl_cont_js_counter( $type, $size, $textSpan, $args['widget_id'], $textcolor, $align, $cont, $cont_old, $display3d, $separatenumbers, $effects, $tempo, $topfix ); ?>
 				 	</div>
 				 </div>
 			<?php break;
 			
			case 'none':
				switch ($backgroundcolor) {
					case 'list-group-item-success':
						$backgroundcolor = 'class="bg-success"';
						break;
					case 'list-group-item-info':
						$backgroundcolor = 'class="bg-info"';
						break;
					case 'list-group-item-warning':
						$backgroundcolor = 'class="bg-warning"';
						break;
					case 'list-group-item-danger':
						$backgroundcolor = 'class="bg-danger"';
					    break;
					case 'list-group-item-dark':
					    $backgroundcolor = 'class="bg-dark"';
					    break;
					case 'list-group-item-white':
					    $backgroundcolor = 'class="bg-white"';
					
				}	
			?>
			
			<div <?php echo $backgroundcolor?> id="<?php echo $args['widget_id'] . '-c'; ?>">
			    <?php echo ecl_cont_js_counter( $type, $size, $textSpan, $args['widget_id'], $textcolor, $align, $cont, $cont_old, $display3d, $separatenumbers, $effects, $tempo, $topfix ); ?>
 			</div>
			 
			<?php break;
	
 		} 
 		
 		echo $args['after_widget'];

 	}
 
 	/**
 	 * @param array $new_instance
 	 * @param array $old_instance
 	 * @return array
 	 */
 	public function update( $new_instance, $old_instance ) {
 		$instance = $old_instance;
 		$instance['title'] = strip_tags($new_instance['title']);
        $instance['backgroundcolor'] = strip_tags($new_instance['backgroundcolor']);
        $instance['textcolor'] = strip_tags($new_instance['textcolor']);
        $instance['size'] = strip_tags($new_instance['size']);
        $instance['align'] = strip_tags($new_instance['align']);
        $instance['type'] = strip_tags($new_instance['type']);
        $instance['container'] = strip_tags($new_instance['container']);
        $instance['formatnumber'] = empty($new_instance['formatnumber']) ? 0 : 1;
        $instance['display3d'] = empty($new_instance['display3d']) ? 0 : 1;
        $instance['separatenumbers'] = empty($new_instance['separatenumbers']) ? 0 : 1;
        $instance['effects'] = strip_tags($new_instance['effects']);
        $instance['tempo'] = intval($new_instance['tempo']);
        $instance['topfix'] = intval($new_instance['topfix']);
 		return $instance;
 	}
 
 	/**
 	 * @param array $instance
 	 */
 	public function form( $instance ) {
 		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'backgroundcolor' => '', 'align' => 'text-right', 			
 		 							'textcolor' => '', 'size' => 'h3', 'type' => 'text', 'container' => 'list',
 									'formatnumber' => 1, 'display3d' => 0, 'separatenumbers' => 0, 'effects' => 'none', 
 				                    'tempo' => 250, 'topfix' => 0 ) );
 		$title = strip_tags($instance['title']);
 		$formatnumber = isset( $instance['formatnumber'] ) ? (bool) $instance['formatnumber'] : false;
 		$display3d = isset( $instance['display3d'] ) ? (bool) $instance['display3d'] : false;
 		$separatenumbers = isset( $instance['separatenumbers'] ) ? (bool) $instance['separatenumbers'] : false;
 		$tempo = isset( $instance['tempo'] ) ? intval($instance['tempo']) : 0;
 		$topfix = isset( $instance['topfix'] ) ? intval($instance['topfix']) : 0;
 ?>
 			<p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'counter-ecl'); ?></label> 
 			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
 			</p>
 			<p><label for="<?php echo $this->get_field_id('backgroundcolor'); ?>"><?php _e('Background Color:', 'counter-ecl'); ?></label> 
 			<select name="<?php echo $this->get_field_name('backgroundcolor'); ?>" id="<?php echo $this->get_field_id('backgroundcolor'); ?>" class="widefat">
 				<option value=""<?php selected( $instance['backgroundcolor'], '' ); ?>><?php _e( 'Default' , 'counter-ecl'); ?></option>
 				<option value="list-group-item-success"<?php selected( $instance['backgroundcolor'], 'list-group-item-success' ); ?>><?php _e( 'Green', 'counter-ecl'); ?></option>
 				<option value="list-group-item-info"<?php selected( $instance['backgroundcolor'], 'list-group-item-info' ); ?>><?php _e( 'Blue', 'counter-ecl' ); ?></option>
 				<option value="list-group-item-warning"<?php selected( $instance['backgroundcolor'], 'list-group-item-warning' ); ?>><?php _e( 'Yellow', 'counter-ecl' ); ?></option>
 				<option value="list-group-item-danger"<?php selected( $instance['backgroundcolor'], 'list-group-item-danger' ); ?>><?php _e( 'Red', 'counter-ecl'); ?></option>	
 			    <option value="list-group-item-dark"<?php selected( $instance['backgroundcolor'], 'list-group-item-dark' ); ?>><?php _e( 'Dark', 'counter-ecl'); ?></option>
 			    <option value="list-group-item-white"<?php selected( $instance['backgroundcolor'], 'list-group-item-white' ); ?>><?php _e( 'White', 'counter-ecl'); ?></option>
 			</select>
 			</p>
 			<p><label for="<?php echo $this->get_field_id('textcolor'); ?>"><?php _e('Text/Label Color:', 'counter-ecl'); ?></label> 
 			<select name="<?php echo $this->get_field_name('textcolor'); ?>" id="<?php echo $this->get_field_id('textcolor'); ?>" class="widefat">
 				<option value=""<?php selected( $instance['textcolor'], '' ); ?>><?php _e( 'Default', 'counter-ecl' ); ?></option>
 				<option value="text-success"<?php selected( $instance['textcolor'], 'text-success' ); ?>><?php _e( 'Green', 'counter-ecl' ); ?></option>
 				<option value="text-info"<?php selected( $instance['textcolor'], 'text-info' ); ?>><?php _e( 'Blue', 'counter-ecl' ); ?></option>
 				<option value="text-warning"<?php selected( $instance['textcolor'], 'text-warning' ); ?>><?php _e( 'Yellow', 'counter-ecl' ); ?></option>
 				<option value="text-danger"<?php selected( $instance['textcolor'], 'text-danger' ); ?>><?php _e( 'Red', 'counter-ecl'); ?></option>	
 				<option value="text-dark"<?php selected( $instance['textcolor'], 'text-dark' ); ?>><?php _e( 'Dark', 'counter-ecl'); ?></option>
 				<option value="text-white"<?php selected( $instance['textcolor'], 'text-white' ); ?>><?php _e( 'White', 'counter-ecl'); ?></option>		
 			</select>
 			</p>
 			<p><label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Size:', 'counter-ecl'); ?></label> 
 			<select name="<?php echo $this->get_field_name('size'); ?>" id="<?php echo $this->get_field_id('size'); ?>" class="widefat">
 				<option value="h1"<?php selected( $instance['size'], 'h1' ); ?>><?php _e( 'Size 1', 'counter-ecl' ); ?></option>
 				<option value="h2"<?php selected( $instance['size'], 'h2' ); ?>><?php _e( 'Size 2', 'counter-ecl' ); ?></option>
 				<option value="h3"<?php selected( $instance['size'], 'h3' ); ?>><?php _e( 'Size 3', 'counter-ecl' ); ?></option>
 				<option value="h4"<?php selected( $instance['size'], 'h4' ); ?>><?php _e( 'Size 4', 'counter-ecl' ); ?></option>
 				<option value="h5"<?php selected( $instance['size'], 'h5' ); ?>><?php _e( 'Size 5', 'counter-ecl'); ?></option>
 				<option value="h6"<?php selected( $instance['size'], 'h6' ); ?>><?php _e( 'Size 6', 'counter-ecl'); ?></option>
 				<option value="p"<?php selected( $instance['size'], 'p' ); ?>><?php _e( 'Paragraph', 'counter-ecl'); ?></option>
 			</select>
 			</p>
 			<p><label for="<?php echo $this->get_field_id('align'); ?>"><?php _e('Align:', 'counter-ecl'); ?></label> 
 			<select name="<?php echo $this->get_field_name('align'); ?>" id="<?php echo $this->get_field_id('align'); ?>" class="widefat">
 				<option value="text-right"<?php selected( $instance['align'], 'text-right' ); ?>><?php _e( 'Right', 'counter-ecl' ); ?></option>
 				<option value="text-left"<?php selected( $instance['align'], 'text-left' ); ?>><?php _e( 'Left', 'counter-ecl' ); ?></option>
 				<option value="text-center"<?php selected( $instance['align'], 'text-center' ); ?>><?php _e( 'Center', 'counter-ecl' ); ?></option>
 				<option value="text-justify"<?php selected( $instance['align'], 'text-justify' ); ?>><?php _e( 'Justify', 'counter-ecl'); ?></option>
 			</select>
 			</p>
 			<p><label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Type:', 'counter-ecl' ); ?></label> 
 			<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>" class="widefat">
 				<option value="text"<?php selected( $instance['type'], 'text' ); ?>><?php _e( 'Text', 'counter-ecl' ); ?></option>
 				<option value="badge"<?php selected( $instance['type'], 'badge' ); ?>><?php _e( 'Badge', 'counter-ecl' ); ?></option>
 				<option value="label"<?php selected( $instance['type'], 'label' ); ?>><?php _e( 'Label', 'counter-ecl' ); ?></option>
 				<option value="analog"<?php selected( $instance['type'], 'analog' ); ?>><?php _e( 'Analog', 'counter-ecl' ); ?></option>
 			</select>
 			</p>
 			<p><label for="<?php echo $this->get_field_id( 'container' ); ?>"><?php _e( 'Container:', 'counter-ecl' ); ?></label> 
 			<select name="<?php echo $this->get_field_name( 'container' ); ?>" id="<?php echo $this->get_field_id( 'container' ); ?>" class="widefat">
 				<option value="list"<?php selected( $instance['container'], 'list' ); ?>><?php _e( 'List', 'counter-ecl' ); ?></option>
 				<option value="panel"<?php selected( $instance['container'], 'panel' ); ?>><?php _e( 'Panel', 'counter-ecl' ); ?></option>
 				<option value="none"<?php selected( $instance['container'], 'none' ); ?>><?php _e( 'None', 'counter-ecl' ); ?></option>
 			</select>
 			</p>
 			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('formatnumber'); ?>" name="<?php echo $this->get_field_name('formatnumber'); ?>"<?php checked( $formatnumber ); ?> />
			<label for="<?php echo $this->get_field_id('formatnumber'); ?>"><?php _e( 'Display counter with numeric format', 'counter-ecl' ); ?></label><br />
			</p>
 			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('display3d'); ?>" name="<?php echo $this->get_field_name('display3d'); ?>"<?php checked( $display3d ); ?> />
			<label for="<?php echo $this->get_field_id('display3d'); ?>"><?php _e( 'Display counter with 3D effects', 'counter-ecl' ); ?></label><br />
			</p>
			<p><input type="checkbox" class="checkbox" id="<?php echo $this->get_field_id('separatenumbers'); ?>" name="<?php echo $this->get_field_name('separatenumbers'); ?>"<?php checked( $separatenumbers ); ?> />
			<label for="<?php echo $this->get_field_id('separatenumbers'); ?>"><?php _e( 'Separate Numbers', 'counter-ecl' ); ?></label><br />
			</p>
			<p><label for="<?php echo $this->get_field_id('effects'); ?>"><?php _e('Animation for new visitor:', 'counter-ecl'); ?></label> 
 			<select name="<?php echo $this->get_field_name('effects'); ?>" id="<?php echo $this->get_field_id('effects'); ?>" class="widefat">
 				<option value="none"<?php selected( $instance['effects'], 'none' ); ?>><?php _e( 'None', 'counter-ecl' ); ?></option>
 				<option value="mov"<?php selected( $instance['effects'], 'mov' ); ?>><?php _e( 'Move Numbers', 'counter-ecl' ); ?></option>
 				<option value="hide"<?php selected( $instance['effects'], 'hide' ); ?>><?php _e( 'Hide', 'counter-ecl' ); ?></option>
 				<option value="fade"<?php selected( $instance['effects'], 'fade' ); ?>><?php _e( 'Fade', 'counter-ecl'); ?></option>
 				<option value="slide"<?php selected( $instance['effects'], 'slide' ); ?>><?php _e( 'Slide', 'counter-ecl'); ?></option>
 			</select>
 			</p>
 			<p>
		    <label for="<?php echo $this->get_field_id('tempo'); ?>"><?php _e( 'Duration Effects new visitor:', 'counter-ecl' ); ?></label>
		    <input id="<?php echo $this->get_field_id('tempo'); ?>" name="<?php echo $this->get_field_name('tempo'); ?>" type="text" value="<?php echo $tempo; ?>"/> ms
		    </p>
		    <p>
		    <label for="<?php echo $this->get_field_id('topfix'); ?>"><?php _e( 'Top. Adjust position in px (- / + number):', 'counter-ecl' ); ?></label>
		    <input id="<?php echo $this->get_field_id('topfix'); ?>" name="<?php echo $this->get_field_name('topfix'); ?>" type="text" value="<?php echo $topfix; ?>"/> px
		    </p>
 <?php
 	}
 }
 
 function ecl_cont_js_counter( $type, $size, $textspan, $id, $classcolor, $classAlign, $cont, $contold, $display3d, $separatenumbers, $effects, $tempo, $topfix ) {
 	$ecl_data = '<div class="counter-ecl-js" data-type="' . $type .'" data-size="' . $size . '" data-textspan="' . $textspan .
 	'" data-id="' . $id . '" data-classcolor="' . $classcolor . '" data-classalign="' . $classAlign .
 	'" data-cont="' . $cont . '" data-contold="' . $contold . '" data-display3d="'. $display3d .
 	'" data-separatenumbers="' . $separatenumbers . '" data-effects="' . $effects .
 	'" data-tempo="' . $tempo . '" data-topfix="' . $topfix . '"></div>';
 	
 	return $ecl_data;
 	
 }
 
 function ecl_register_new_widget() { 		
 	register_widget('ecl_Widget_CounterECL');	
 }
 add_action('widgets_init', 'ecl_register_new_widget');
 
 /*
  * 
  * init
  * 
  */
 
 function ecl_cont_register_init() {
 	// Register Bootstrap
 	wp_register_style( 'ecl-cont-bootstrap', plugins_url( 'css/bootstrap.min.css', __FILE__ ) );
 	
 	// Register Bootstrap theme
 	wp_register_style( 'ecl-cont-bootstrap-theme', plugins_url( 'css/bootstrap-theme.min.css', __FILE__ ) );
 	
 	// Register couter-ecl css
 	wp_register_style( 'ecl-cont-css', plugins_url( 'css/counter-ecl.css', __FILE__ ) );
 	
 	// Rgister counter-ecl js
 	wp_register_script('ecl-cont-js', plugins_url( 'js/counter-ecl.js', __FILE__ ) , array('jquery'), '2', true);
 	
 	
 	
 	add_shortcode( 'counter_ecl', 'ecl_cont_shortcode_handler' );
 	
 }
 add_action( 'init', 'ecl_cont_register_init' );
 
function ecl_cont_visual_message() {
 	global $ecl_cont_message, $ecl_domain;
 	$ecl_message_active = get_option('counter_ecl_message_active');
 	
 	if (!empty($ecl_message_active) && $ecl_message_active == '1' 
 		&& $ecl_cont_message == false){
 		
 		$ecl_message = ecl_cont_get_message();
 		
 		$ecl_position = get_option( 'counter_ecl_position' ); 
 		$ecl_position = empty($ecl_position) ? 'position-bottom ' : 'position-top ';
 	    
 		$ecl_effects = get_option( 'counter_ecl_effects' );
 		$ecl_effects = empty($ecl_effects) ? 'none' : $ecl_effects;
 		
 		$ecl_hide = get_option( 'counter_ecl_hide' );
 		$ecl_hide = empty($ecl_hide) ? '0' : $ecl_hide;
 		 		
 	    // Load Bootstrap
 	    wp_enqueue_style( 'ecl-cont-bootstrap' );
 	    
 	    // Load Bootstrap theme
 	    wp_enqueue_style('ecl-cont-bootstrap-theme' );
 	    
 	    wp_enqueue_style('ecl-cont-css');
 	    
 	    wp_enqueue_script('jquery');
 	    
 	    wp_enqueue_script('ecl-cont-js');
 	     	  
 		?>
 	 	<div id="counter-ecl-dg-js" <?php echo 'class="' . $ecl_position . get_option('counter_ecl_color_text') . ' ' . 
 	 	  get_option('counter_ecl_color_background') . '" data-effects="' . $ecl_effects . '" data-hide="' . $ecl_hide . '"'; ?>>
 		 	<?php echo $ecl_message; ?>
 		 	<br/>
 		 	<button class="btn btn-warning btn-xs" id="counter-ecl-dg-btn-js"><?php _e('Accept', 'counter-ecl'); ?></button>   
 		</div>
 		<?php 
 		
 		echo '<div id="counter-ecl-cookie-message-js" data-expire="' . ecl_cont_get_time_cookie_expire_message(false) .
 		'" data-domain="' . $ecl_domain . '" data-secure="' . (is_ssl() ? '1' : '0') .
 		'" data-path="/" data-name="ecl_cont_message_cookie_' . md5($ecl_domain) . '" data-value="accept"' .
 		'></div>';
 	}
}
add_action('wp_footer', 'ecl_cont_visual_message');
 
function ecl_cont_set_cookie() {
  global $ecl_cont_visited, $ecl_domain;
  
  if ($ecl_cont_visited == false) {
  	wp_enqueue_script('jquery');
  	
  	wp_enqueue_script('ecl-cont-js');
  	
  	echo '<div id="counter-ecl-cookie-js" data-expire="' . ecl_cont_get_time_cookie_expire(false) . 
  	     '" data-domain="' . $ecl_domain . '" data-secure="' . (is_ssl() ? '1' : '0') . 
  	     '" data-path="/" data-name="ecl_cont_visited_cookie_' . md5($ecl_domain) . '" data-value="visited"' .
 		 '></div>';
  }
 	
 }
add_action('wp_footer', 'ecl_cont_set_cookie');
 
 function ecl_cont_shortcode_handler ( $atts, $content = null ) {
 	global $ecl_cont_old;
 	
 	static $ecl_numid = 0;
 	
 	
 	$ecl_numid++;
 	
 	$ecl_id = 'counterecl-shortcode-' . $ecl_numid;
 	
 	$ecl_attr = shortcode_atts(
 			array(
 					'format' => 'false',
 					'background' => '',
 					'color' => '',
 					'size' => '',
 					'align' => '',
 					'type' => '',
 					'container' => '',
 					'float' => 'left',
 					'display3d' => 'false',
 					'separate' => 'false',
 					'animate' => 'none',
 					'duration' => '250',
 					'topfix' => '0'
 			), $atts );
 	
 	$ecl_contador = ecl_cont_get_counter(false);
 	$ecl_contador_old = $ecl_cont_old;
 	
 	if ($ecl_attr['format'] == 'true') {
 		$ecl_contador = number_format($ecl_contador, 0);
 		$ecl_contador_old = number_format($ecl_contador_old, 0);
 	}
 	
 	
 	// Load Bootstrap
 	wp_enqueue_style( 'ecl-cont-bootstrap' );
 		
 	// Load Bootstrap theme
 	wp_enqueue_style('ecl-cont-bootstrap-theme' );

 	wp_enqueue_style('ecl-cont-css');
 	
 	wp_enqueue_script('jquery');
 	
 	wp_enqueue_script('ecl-cont-js');
	
 	$ecl_size = '';
	 	
 	switch ($ecl_attr['size']) {
 		case '1':
 			$ecl_size = 'h1';	
 			break;
 		case '2':
 			$ecl_size = 'h2';
 			break;
 		case '3':
 			$ecl_size = 'h3';
 			break;
 		case '4':
 			$ecl_size = 'h4';
 			break;
 		case '5':
 			$ecl_size = 'h5';
 			break;
 		case '6':
 			$ecl_size = 'h6';
 			break;
 		case 'p':
 			$ecl_size = 'p';
 			break;	
 		default:
 			$ecl_size = 'h3';
 			
 	}
 	
 	$ecl_type = '';
 	
 	switch ($ecl_attr['type']) {
 		case 'text':
 		case 'label':
 		case 'analog':
 		case 'badge':
 			$ecl_type = $ecl_attr['type'];
 			break;
 		default: $ecl_type = 'text';
 	}
 	
 	
 	$ecl_color_class = '';
 	$ecl_color_label = '';
 	
 	switch ($ecl_attr['color']) {
 		case 'green':
 			$ecl_color_class = 'text-success';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-success' : '';
 			break;
 		case 'blue':
 			$ecl_color_class = 'text-info';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-info' : '';
 			break;
 		case 'yellow':
 			$ecl_color_class = 'text-warning';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-warning' : '';
 			break;
 		case 'red':
 			$ecl_color_class = 'text-danger';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-danger' : '';
 			break;
 		case 'dark':
 			$ecl_color_class = 'text-dark';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-dark' : '';
 			break;
 		case 'white':
 			$ecl_color_class = 'text-white';
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-white' : '';
 			break;
 		default:
 			$ecl_color_label = $ecl_attr['type'] == 'label' ? 'label-default' : '';
 		
 	}
 	
 	if ($ecl_type == 'label') {
 		$ecl_color_class = '';
 	}
 	
 	$ecl_align_class = '';
 	
 	switch ($ecl_attr['align']) {
 		case 'left':
 			$ecl_align_class = 'text-left';
 			break;
 		case 'center':
 			$ecl_align_class = 'text-center';
 			break;
 		case 'justify':
 			$ecl_align_class = 'text-justify';
 			break;
 		default:
 			$ecl_align_class = 'text-right';
 	}
 		

 	
 	
 	
 	$ecl_background_start = '';
 	$ecl_background_end = '';
 	
 	$ecl_float = '';
 	switch ($ecl_attr['float']) {
 		case 'left':
 			$ecl_float = 'float-left';
 			break;
 		case 'right':
 			$ecl_float = 'float-right';
 			break;
 		case 'none':
 			$ecl_float = 'float-none';
 			break;
 		default:
 			$ecl_float = 'float-left';
 	}
 	
 	switch ($ecl_attr['container']) {
 		case 'list':
 			$ecl_background_start = '<ul class="list-group">';
 			$ecl_background_start .= '<li class="list-group-item ';
 			
 			switch ($ecl_attr['background']) {
 				case 'green':
 					$ecl_background_start .= 'list-group-item-success';	
 					break;
 				case 'blue':
 					$ecl_background_start .= 'list-group-item-info';
 					break;
 				case 'yellow':
 					$ecl_background_start .= 'list-group-item-warning';
 					break;
 				case 'red':
 					$ecl_background_start .= 'list-group-item-danger';
 					break;
 				case 'dark':
 					$ecl_background_start .= 'list-group-item-dark';
 					break;
 				case 'white':
 					$ecl_background_start .= 'list-group-item-white';
 					break;
 			}

 			$ecl_background_start .= '" id="' . $ecl_id . '-c"';
 			$ecl_background_start .= '>';
 			$ecl_background_end = '</li></ul>';
 					
 			break;
 			
 		case 'panel':
 			$ecl_background_start = '<div class="panel ';
 			 			
 			switch ($ecl_attr['background']) {
 				case 'green':
 					$ecl_background_start .= 'panel-success';
 					break;
 				case 'blue':
 					$ecl_background_start .= 'panel-info';
 					break;
 				case 'yellow':
 					$ecl_background_start .= 'panel-warning';
 					break;
 				case 'red':
 					$ecl_background_start .= 'panel-danger';
 					break;
 				case 'dark':
 					$ecl_background_start .= 'panel-dark';
 					break;
 				case 'white':
 					$ecl_background_start .= 'panel-white';
 					break;
 			}
 			
 			$ecl_background_start .= '" id="' . $ecl_id . '-c"';
 			$ecl_background_start .= '><div class="panel-heading">';
 			$ecl_background_end = '</div></div>';
 			break;
 			
 		default:
 			$ecl_background_start = '<div ';
 			switch ($ecl_attr['background']) {
 				case 'green':
 					$ecl_background_start .= 'class="bg-success"';
 					break;
 				case 'blue':
 					$ecl_background_start .= 'class="bg-info"';
 					break;
 				case 'yellow':
 					$ecl_background_start .= 'class="bg-warning"';
 					break;
 				case 'red':
 					$ecl_background_start .= 'class="bg-danger"';
 					break;
 				case 'dark':
 					$ecl_background_start .= 'class="bg-dark"';
 					break;
 				case 'white':
 					$ecl_background_start .= 'class="bg-white"';
 					break;
 			}
 			
 			$ecl_background_start .= ' id="' . $ecl_id . '-c"'; 
 			$ecl_background_start .= '>';
 			$ecl_background_end = '</div>';
 			break;
 		
 	}
 	
 	 	
 	$ecl_display3d = '0';
 	
 	if ($ecl_attr['display3d'] == 'true') {
 		$ecl_display3d = '1';
 	} 
 	
 	$ecl_separate = '0';
 	
 	if ($ecl_attr['separate'] == 'true') {
 		$ecl_separate = '1';
 	}
 	
 	$ecl_animate = 'none';
 	
 	switch ($ecl_attr['animate']) {
 		case 'mov':
 		case 'hide':
 		case 'fade':
 		case 'slide':
 			$ecl_animate = $ecl_attr['animate'];
 			
 	}
 	
 	$ecl_duration = isset($ecl_attr['duration']) ? intval($ecl_attr['duration']): 250;
 	
 	$ecl_topfix = isset($ecl_attr['topfix']) ? intval($ecl_attr['topfix']): 0;
 	
 	$ecl_html = '<div class="' . $ecl_float . '">' .
 	 $ecl_background_start . 
 	 ecl_cont_js_counter( $ecl_type, $ecl_size, $ecl_color_label, 
 	 		$ecl_id, $ecl_color_class, $ecl_align_class, $ecl_contador, $ecl_contador_old, 
 	 		$ecl_display3d, $ecl_separate, $ecl_animate, $ecl_duration, $ecl_topfix ) .
 	 $ecl_background_end . 
 	'</div>';
 	 	 	
 	return  $ecl_html;
 	
 }
 ?>