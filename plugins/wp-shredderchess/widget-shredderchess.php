<?php
/*
Plugin Name: WP Shredderchess
Plugin URI: https://wordpress.org/plugins/wp-shredderchess/
Description: Widget that displays the chess puzzle from shredderchess.com.
Author: Marcel Pol
Version: 1.0.7
Author URI: https://timelord.nl
License: GPLv2 or later


Copyright 2016 - 2023  Marcel Pol  (email: marcel@timelord.nl)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


if ( function_exists('register_sidebar') && class_exists('WP_Widget') ) {
	class Shredderchess_Widget extends WP_Widget {

		/* Constructor */
		public function __construct() {
			$widget_ops = array(
				'classname'   => 'shredderchess_widget',
				'description' => esc_html__( 'Displays the puzzle from Shredderchess.', 'wp-shredderchess' ),
				);
			parent::__construct('shredderchess_widget', 'Shredderchess', $widget_ops);
			$this->alt_option_name = 'shredderchess_widget';
		}

		/** @see WP_Widget::widget */
		public function widget( $args, $instance ) {

			$default_value = array(
					'title' => 'Shredderchess',
					'sizes' => (int) 32,
				);
			$instance = wp_parse_args( (array) $instance, $default_value );

			$widget_title = $instance['title'];
			$sizes        = (int) $instance['sizes'];
			if ( $sizes === 0 ) {
				$sizes = 32;
			}

			$iframe_width  = 310;
			$iframe_height = 341;
			if ( $sizes === 14) {
				$iframe_width  = 148;
				$iframe_height = 197;
			} else if ( $sizes === 18) {
				$iframe_width  = 184;
				$iframe_height = 229;
			} else if ( $sizes === 22) {
				$iframe_width  = 220;
				$iframe_height = 261;
			} else if ( $sizes === 26) {
				$iframe_width  = 256;
				$iframe_height = 293;
			} else if ( $sizes === 32) {
				$iframe_width  = 310;
				$iframe_height = 341;
			}

			$locale = get_locale();
			$locale = substr( $locale, 0, 2 );

			echo $args['before_widget']; ?>
			<div class="shredderchess_widget">

			<?php
			if ($widget_title !== FALSE) {
				echo $args['before_title'] . apply_filters('widget_title', $widget_title) . $args['after_title'];
			} ?>

				<iframe scrolling="no" height="<?php echo (int) $iframe_height; ?>" width="<?php echo (int) $iframe_width; ?>" loading="lazy" frameborder="0"
					src="https://www.shredderchess.com/online/playshredder/gdailytactics.php?mylang=<?php echo esc_attr( $locale ); ?>&mysize=<?php echo (int) $sizes; ?>" referrerpolicy="no-referrer">
				</iframe>

			</div>

			<?php
			echo $args['after_widget'];
		}

		/** @see WP_Widget::update */
		public function update( $new_instance, $old_instance ) {
			$instance = $old_instance;
			$instance['title'] = wp_strip_all_tags( $new_instance['title'] );
			$instance['sizes'] = (int) $new_instance['sizes'];

			return $instance;
		}

		/** @see WP_Widget::form */
		public function form( $instance ) {
			$default_value = array(
					'title' => 'Shredderchess',
					'sizes' => (int) 32,
				);
			$instance = wp_parse_args( (array) $instance, $default_value );

			$title = esc_attr($instance['title']);
			$sizes = (int) $instance['sizes'];
			if ( $sizes === 0 ) {
				$sizes = 32;
			}
			?>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('title') ); ?>" /><?php esc_html_e( 'Title:', 'wp-shredderchess' ); ?></label>
				<br />
				<input type="text" id="<?php echo esc_attr( $this->get_field_id('title') ); ?>" value="<?php echo esc_attr( $title ); ?>" name="<?php echo esc_attr( $this->get_field_name('title') ); ?>" />
			</p>

			<p>
				<label for="<?php echo esc_attr( $this->get_field_id('sizes') ); ?>" /><?php esc_html_e( 'Piecesizes:', 'wp-shredderchess' ); ?></label>
				<br />
				<select name="<?php echo esc_attr( $this->get_field_name('sizes') ); ?>" id="<?php echo esc_attr( $this->get_field_id('sizes') ); ?>">
					<?php
					$presets = array( 14, 18, 22, 26, 32 );
					foreach ( $presets as $preset ) {
						echo '
						<option value="' . (int) $preset . '"';
						if ( (int) $preset === (int) $sizes ) {
							echo ' selected="selected"';
						}
						echo '>' . (int) $preset . '</option>';
					} ?>
				</select>
			</p>
			<?php
		}

	}

	function shredderchess_widget() {
		register_widget('Shredderchess_Widget');
	}
	add_action( 'widgets_init', 'shredderchess_widget' );
}


/*
 * Add example text to the privacy policy.
 *
 * @since 1.0.2
 */
function shredderchess_add_privacy_policy_content() {
	if ( ! function_exists( 'wp_add_privacy_policy_content' ) ) {
		return;
	}

	$content = sprintf(
		'<p>' . esc_html__( 'The chess puzzle from Shredderchess uses Google Analytics for statistics. Shredderchess and Google can see who is using this widget and where they are visiting it. The puzzle is loaded with an iframe into the website and behaves the same as if you would visit the Shredderchess website itself.', 'wp-shredderchess' ) . '</p>'
	);

	wp_add_privacy_policy_content(
		'WP Shredderchess',
		wp_kses_post( wpautop( $content, false ) )
	);
}
add_action( 'admin_init', 'shredderchess_add_privacy_policy_content' );
