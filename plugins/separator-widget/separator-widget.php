<?php
/**
 * Plugin Name: Separator Widget
 * Description: A separator widget
 * Version: 1.0.2
 * Author: Daniel Östman
 * Author URI: http://www.danielostman.se
 * Text Domain: separator-widget
 * License: GPL2
 */

 /*  Copyright 2016  Daniel Ostman  (http://www.danielostman.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

add_action( 'widgets_init',
	create_function( '', 'return register_widget( "Halvfem_Separator_Widget" );' )
);

/**
 * Internationalization, load language files from plugin-folder /languages/
 */
add_action('plugins_loaded', 'halvfem_separator_widget_i18n');
function halvfem_separator_widget_i18n() {
	load_plugin_textdomain( 'separator-widget', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
}


class Halvfem_Separator_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'halvfem_separator_widget', // Base ID
			__( 'Separator', 'separator-widget' ), // Name
			array( 'description' => __( 'A separator', 'separator-widget' ), ) // Args
		);
	}

	/**
	 * Front-end display of widget.
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
		echo $args['before_widget'];
		if ( isset($instance['separator_type']) && $instance['separator_type'] == "div" ) {
			$separator_markup = '<div class="my-separator"></div>';
		} else {
			$separator_markup = '<hr />';
		}
		echo apply_filters( 'separator_markup', $separator_markup );
		echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance['separator_type'] ) ) {
			$separator_type = $instance['separator_type'];
		}
		else {
			$separator_type = 'hr';
		}
		?>
		<p>
			<?php _e( 'Separator type', 'separator-widget' ) ?>:<br />
			<input type="radio" id="<?php echo $this->get_field_id( 'separator_type' ); ?>-1" name="<?php echo $this->get_field_name( 'separator_type' ); ?>" value="hr" <?php checked( 'hr', $separator_type ); ?> />
			<label for="<?php echo $this->get_field_id( 'separator_type' ); ?>-1"><?php _e( 'Horizontal rule', 'separator-widget' ); ?></label> 
			<input type="radio" id="<?php echo $this->get_field_id( 'separator_type' ); ?>-2" name="<?php echo $this->get_field_name( 'separator_type' ); ?>" value="div" <?php checked( 'div', $separator_type ); ?> />
			<label for="<?php echo $this->get_field_id( 'separator_type' ); ?>-2"><?php _e( 'Div', 'separator-widget' ); ?></label> 
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		if ( $new_instance['separator_type'] == 'div') {
			$instance['separator_type'] = 'div';
		} else {
			$instance['separator_type'] = 'hr';
		}

		return $instance;
	}

}