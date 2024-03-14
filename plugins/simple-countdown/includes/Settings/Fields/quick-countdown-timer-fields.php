<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\Fields\QuickCountDownTimer;

defined( 'ABSPATH' ) || exit;
/**
 * Setup Settings Fields.
 *
 * @return array
 */
function setup_settings_fields( $core, $plugin_info ) {
	return array(
		'general' => array(
			'interval' => array(
				'settings_list' => array(
					'countdown_target' => array(
						'input_label'  => esc_html__( 'CountTimer Interval', 'simple-countdown' ),
						'input_suffix' => esc_html__( 'Set the countdown interval time. The timer uses the site timezone in ', 'simple-countdown' ) . '<a target="_blank=" href="' . esc_url_raw( admin_url( 'options-general.php' ) ) . '" >' . esc_html__( 'Settings' ) . '</a>',
						'input_footer' => esc_html__( 'The timer won\'t appear if the time is past', 'simple-countdown' ),
						'type'         => 'datetime-local',
						'value'        => date( 'Y-m-d\TH:i', strtotime( '+1 week' ) ),
						'classes'      => $plugin_info['classes_prefix'] . '-arrival-time',
						'attrs'        => array(
							'min' => date( 'Y-m-d\TH:i', strtotime( 'now' ) ),
						),
					),

				),
			),
			'colors'   => array(
				'hide'          => true,
				'settings_list' => array(
					// Days Colors.
					'colors_days_title_color'            => array(
						'id'          => 'days-title-color',
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-days-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'days',
							'data-default' => '#000',
						),
					),
					'colors_days_counter_front_color'    => array(
						'id'          => 'days-front-color',
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-days-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'days',
							'data-default' => '#FFF',
						),
					),
					'colors_days_counter_back_color'     => array(
						'id'          => 'days-back-color',
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-days-colors',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'days',
							'data-default' => '#000',
						),
					),
					'colors_days_divider_color'          => array(
						'id'          => 'days-divider-color',
						'input_label' => esc_html__( 'Divider Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-days-colors',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'days',
							'data-default' => '#000',
						),
					),
					// Hours Colors.
					'colors_hours_title_color'           => array(
						'id'          => 'hours-title-color',
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-hours-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'hours',
							'data-default' => '#000',
						),
					),
					'colors_hours_counter_front_color'   => array(
						'id'          => 'hours-front-color',
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-hours-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'hours',
							'data-default' => '#FFF',
						),
					),
					'colors_hours_counter_back_color'    => array(
						'id'          => 'hours-back-color',
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-hours-colors',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'hours',
							'data-default' => '#000',
						),
					),
					'colors_hours_divider_color'         => array(
						'id'          => 'hours-divider-color',
						'input_label' => esc_html__( 'Divider Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-hours-colors',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'hours',
							'data-default' => '#000',
						),
					),
					// Minutes Colors.
					'colors_minutes_title_color'         => array(
						'id'          => 'minutes-title-color',
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-minutes-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'minutes',
							'data-default' => '#000',
						),
					),
					'colors_minutes_counter_front_color' => array(
						'id'          => 'minutes-front-color',
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-minutes-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'minutes',
							'data-default' => '#FFF',
						),
					),
					'colors_minutes_counter_back_color'  => array(
						'id'          => 'minutes-back-color',
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-minutes-colors',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'minutes',
							'data-default' => '#000',
						),
					),
					'colors_minutes_divider_color'       => array(
						'id'          => 'minutes-divider-color',
						'input_label' => esc_html__( 'Divider Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-minutes-colors',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'minutes',
							'data-default' => '#000',
						),
					),
					// Seconds Colors.
					'colors_seconds_title_color'         => array(
						'id'          => 'seconds-title-color',
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-seconds-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'seconds',
							'data-default' => '#000',
						),
					),
					'colors_seconds_counter_front_color' => array(
						'id'          => 'seconds-front-color',
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-seconds-colors',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'seconds',
							'data-default' => '#FFF',
						),
					),
					'colors_seconds_counter_back_color'  => array(
						'id'          => 'seconds-back-color',
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-seconds-colors',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'seconds',
							'data-default' => '#000',
						),
					),
					'colors_seconds_divider_color'       => array(
						'id'          => 'seconds-divider-color',
						'input_label' => esc_html__( 'Divider Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker ' . $plugin_info['classes_prefix'] . '-seconds-colors',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'seconds',
							'data-default' => '#000',
						),
					),
				),
			),
		),
	);

}
