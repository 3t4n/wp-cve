<?php
/**
 * @author    Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @copyright Copyright (c) 2011 - 2015 Jason Lemahieu and Kevin Graeme (Cooperative Extension Technology Services)
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GPLv2
 * @package   CETS\Conditional_Widgets
 */

add_action( 'admin_init', 'conditional_widgets_maybe_update' );	

function conditional_widgets_maybe_update() {

	$v = get_option( 'conditional_widgets_version', '0' );

	if ( version_compare( $v, "2.0", "<" ) ) {

		// move post category data to new format
		$widget_group_options = conditonal_widgets_get_widget_settings_arrays();

		foreach ( $widget_group_options as $widget_group_option ) {
			
			$widget_group_option_name  = $widget_group_option->option_name;
			$widget_group_option_value = unserialize( $widget_group_option->option_value );

			//echo "<hr>WIDGET GROUP: {$widget_group_option_name}<br>";

			//var_dump($widget_group_option_value);
			//echo "<br>";

			foreach ( $widget_group_option_value as $key => $widget_option_value ) {
				
				//see if i'm just the default "_multiwidget" crap.
				if ( $key == '_multiwidget' ) {
					continue;
				}

				if ( ! is_array( $widget_option_value ) ) {
					// somethings wrong with this one. get me outta here!
					continue;
				}

				//echo "<br><br>-- New Widget Instance :{$key} --<br><br>";
				//var_dump($widget_option_value);


				if ( ! isset( $widget_option_value['cw_home_enable_checkbox'] ) ) {
					// if we don't have conditional widgets data, just skip this one
					//echo "<br>Skipping this one because no conditional widgets logic...<br>";
					continue;
				}

				if ( ! isset( $widget_option_value['cw_custom'] ) ) {
					$widget_option_value['cw_custom'] = array();
				}

				//print_r($option_value);
				if ( ! isset( $widget_option_value['cw_custom']['post'] ) ) {
					$widget_option_value['cw_custom']['post'] = array();
				}

				if ( ! isset( $widget_option_value['cw_custom']['post']['category'] ) ) {
					$widget_option_value['cw_custom']['post']['category'] = array();
				}

				$widget_option_value['cw_custom']['post']['category']['enable']       = $widget_option_value['cw_cats_enable_checkbox'];
				$widget_option_value['cw_custom']['post']['category']['all']          = $widget_option_value['cw_cats_all'];
				$widget_option_value['cw_custom']['post']['category']['sub']          = $widget_option_value['cw_cats_sub_checkbox'];
				$widget_option_value['cw_custom']['post']['category']['select']       = $widget_option_value['cw_select_cats'];
				$widget_option_value['cw_custom']['post']['category']['selected_ids'] = $widget_option_value['cw_selected_cats'];

				unset( $widget_option_value['cw_cats_enable_checkbox'] );
				unset( $widget_option_value['cw_cats_all'] );
				unset( $widget_option_value['cw_cats_sub_checkbox'] );
				unset( $widget_option_value['cw_select_cats'] );
				unset( $widget_option_value['cw_selected_cats'] );

				$widget_group_option_value[$key] = $widget_option_value;

				// /each-widget-instance
			}

			update_option( $widget_group_option_name, $widget_group_option_value );

		} // /foreach

	}

	update_option( 'conditional_widgets_version', '2.0' );

} // /function conditional_widgets_maybe_update()

function conditonal_widgets_get_widget_settings_arrays() {

	global $wpdb;

	$sql     = "SELECT option_name, option_value FROM {$wpdb->options} WHERE option_name LIKE 'widget_%'";
	$results = $wpdb->get_results( $sql );

	return $results;

} // /function conditonal_widgets_get_widget_settings_arrays()
