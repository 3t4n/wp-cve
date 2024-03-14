<?php
/*
	Plugin Name: Yet Another Social Media Icon Plugin (YASIP)
	Plugin URI: https://mitchbartlett.com/yet-another-social-icons-plugin
	Description: This plugin/widget allows you to insert different types of social media profile icons into your sidebar via a widget.
	Author: Mitch Bartlett
	Author URI: https://mitchbartlett.com/

	Version: 1.2

	License: GNU General Public License v3.0
	License URI: http://www.opensource.org/licenses/gpl-license.php

	NOTE: This plugin is released under the GPLv2 license. The images packaged with this plugin are the property
	of their respective owners, and do not, necessarily, inherit the GPLv2 license.
*/

/**
 * Register the Widget
 */
add_action('widgets_init', 'yasip_widget_register');
function yasip_widget_register() {
	register_widget('yasip_Widget');
}

/**
 * The Widget Class
 */
if ( !class_exists('yasip_Widget') ) {
class yasip_Widget extends WP_Widget {

	function yasip_Widget() {
		$widget_ops = array( 'classname' => 'yasip', 'description' => __('Displays Social Profile links as icons', 'spw') );
		$this->WP_Widget( 'yasip', __('YASIP Social Icons', 'spw'), $widget_ops );
	}

	var $plugin_imgs_url;

	function spw_fields_array( $instance = array() ) {

		$this->plugins_imgs_url = plugin_dir_url(__FILE__) . 'images/';

		return array(
			'feedburner' => array(
				'title' => __('RSS/Feedburner URL', 'spw'),
				'img' => sprintf( '%s/rss_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/rss_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('RSS', 'spw')
			),
			'twitter' => array(
				'title' => __('Twitter URL', 'spw'),
				'img' => sprintf( '%s/twitter_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/twitter_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('Twitter', 'spw')
			),
			'facebook' => array(
				'title' => __('Facebook URL', 'spw'),
				'img' => sprintf( '%s/facebook_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/facebook_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('Facebook', 'spw')
			),
			'linkedin' => array(
				'title' => __('Linkedin URL', 'spw'),
				'img' => sprintf( '%s/linkedin_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/linkedin_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('Linkedin', 'spw')
			),
			'youtube' => array(
				'title' => __('YouTube URL', 'spw'),
				'img' => sprintf( '%s/youtube_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/youtube_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('Youtube', 'spw')
			),
			'flickr' => array(
				'title' => __('Flickr URL', 'spw'),
				'img' => sprintf( '%s/flickr_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/flickr_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('Flickr', 'spw')
			),
			'pinterest' => array(
				'title' => __('Pinterest URL', 'spw'),
				'img' => sprintf( '%s/pinterest_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/pinterest_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('pinterest', 'spw')
			),
			'stumbleupon' => array(
				'title' => __('StumbleUpon URL', 'spw'),
				'img' => sprintf( '%s/stumbleupon_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/stumbleupon_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('StumbleUpon', 'spw')
			),
			'googleplus' => array(
				'title' => __('Google Plus URL', 'spw'),
				'img' => sprintf( '%s/googleplus_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/googleplus_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('googleplus', 'spw')
			),
			'instagram' => array(
				'title' => __('Instagram URL', 'spw'),
				'img' => sprintf( '%s/instagram_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/instagram_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('instagram', 'spw')
			),
	'tumblr' => array(
				'title' => __('Tumblr URL', 'spw'),
				'img' => sprintf( '%s/tumblr_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/tumblr_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('tumblr', 'spw')
			),
	'vine' => array(
				'title' => __('Vine URL', 'spw'),
				'img' => sprintf( '%s/vine_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/vine_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('vine', 'spw')
			),
	'wordpress' => array(
				'title' => __('Wordpress URL', 'spw'),
				'img' => sprintf( '%s/wordpress_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), esc_attr( $instance['size'] ) ),
				'img_widget' => sprintf( '%s/wordpress_%s.png', $this->plugins_imgs_url . esc_attr( $instance['icon_set'] ), '48x48' ),
				'img_title' => __('wordpress', 'spw')
			),


		);
	}

	function widget($args, $instance) {

		extract($args);

		$instance = wp_parse_args($instance, array(
			'title' => '',
			'new_window' => 0,
			'icon_set' => 'default',
			'size' => '24x24'
		) );

		echo $before_widget;

			if ( ! empty( $instance['title'] ) )
				echo $before_title . $instance['title'] . $after_title;
				
			$new_window = $instance['new_window'] ? 'target="_blank"' : '';

			foreach ( $this->spw_fields_array( $instance ) as $key => $data ) {
				if ( ! empty ( $instance[$key] ) ) {
					printf( '<a href="%s" %s><img src="%s" alt="%s" /></a>', esc_url( $instance[$key] ), $new_window, esc_url( $data['img'] ), esc_attr( $data['img_title'] ) );
				}
			}

		echo $after_widget;

	}

	function update($new_instance, $old_instance) {
		return $new_instance;
	}

	function form($instance) {

		$instance = wp_parse_args($instance, array(
			'title' => '',
			'new_window' => 0,
			'icon_set' => 'default',
			'size' => '24x24'
		) );
?>

		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title', 'spw'); ?>:</label><br />
			<input id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" style="width:95%;" />
		</p>
		
		<p><label><input id="<?php echo $this->get_field_id( 'new_window' ); ?>" type="checkbox" name="<?php echo $this->get_field_name( 'new_window' ); ?>" value="1" <?php checked( 1, $instance['new_window'] ); ?>/> <?php esc_html_e( 'Open links in new window?', 'spw' ); ?></label></p>

		<p>
			<label for="<?php echo $this->get_field_id('icon_set'); ?>"><?php _e('Icon Set', 'spw'); ?>:</label>
			<select id="<?php echo $this->get_field_id('icon_set'); ?>" name="<?php echo $this->get_field_name('icon_set'); ?>">
				<option style="padding-right:10px;" value="default" <?php selected('default', $instance['icon_set']); ?>><?php _e('Default', 'spw'); ?></option>
				<option style="padding-right:10px;" value="orbs" <?php selected('orbs', $instance['icon_set']); ?>><?php _e('Orbs', 'spw'); ?></option>
				<option style="padding-right:10px;" value="flat" <?php selected('flat', $instance['icon_set']); ?>><?php _e('Flat', 'spw'); ?></option>
				<option style="padding-right:10px;" value="silver" <?php selected('silver', $instance['icon_set']); ?>><?php _e('Silver', 'spw'); ?></option>
							</select>
		</p>

		<p>
			<label for="<?php echo $this->get_field_id('size'); ?>"><?php _e('Icon Size', 'spw'); ?>:</label>
			<select id="<?php echo $this->get_field_id('size'); ?>" name="<?php echo $this->get_field_name('size'); ?>">
				<option style="padding-right:10px;" value="24x24" <?php selected('24x24', $instance['size']); ?>><?php _e('Mini', 'spw'); ?> (24px)</option>
				<option style="padding-right:10px;" value="32x32" <?php selected('32x32', $instance['size']); ?>><?php _e('Small', 'spw'); ?> (32px)</option>
				<option style="padding-right:10px;" value="48x48" <?php selected('48x48', $instance['size']); ?>><?php _e('Large', 'spw'); ?> (48px)</option>
			</select>
		</p>

		<p><?php _e('Enter the URL(s) for your various social profiles below. If you leave a profile URL field blank, it will not be used.', 'spw'); ?></p>

<?php

		foreach ( $this->spw_fields_array( $instance ) as $key => $data ) {
			echo '<p>';
			printf( '<img style="float: left; margin-right: 3px;" src="%s" title="%s" />', $data['img_widget'], $data['img_title'] );
			printf( '<label for="%s"> %s:</label>', esc_attr( $this->get_field_id($key) ), esc_attr( $data['title'] ) );
			printf( '<input id="%s" name="%s" value="%s" style="%s" />', esc_attr( $this->get_field_id($key) ), esc_attr( $this->get_field_name($key) ), esc_url( $instance[$key] ), 'width:80%; height:40px;' );
			echo '</p>' . "\n";
		}

	}
}}