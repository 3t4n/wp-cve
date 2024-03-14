<?php
namespace GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\Fields\CountDownTimerCPT;

use GPLSCore\GPLS_PLUGIN_WPSCTR\Settings\CountDownTimerCPTSettings;

defined( 'ABSPATH' ) || exit;
/**
 * Setup Settings Fields.
 *
 * @return array
 */
function setup_settings_fields( $core, $plugin_info ) {
	return array(
		'general' => array(
			'interval'  => array(
				'settings_list' => array(
					// Timer Interval.
					'timer_interval'     => array(
						'input_label' => esc_html__( 'Timer Target Time', 'simple-countdown' ),
						'type'        => 'datetime-local',
						'value'       => date( 'Y-m-d\TH:i', strtotime( '+1 week' ) ),
						'classes'     => $plugin_info['classes_prefix'] . '-timer-interval',
						'attrs'       => array(
							'min' => date( 'Y-m-d\TH:i', strtotime( '-2 day' ) ),
						),
					),
					// Timezone.
					'timer_timezone'     => array(
						'wrapper_classes' => ' bg-light',
						'input_label'  => esc_html__( 'Timer Timezone', 'simple-countdown' ) . $core->pro_btn( '', 'Pro', '', '', true ),
						'type'         => 'select',
						'custom_input' => true,
						'value'        => CountDownTimerCPTSettings::get_site_timezone(),
						'classes'      => $plugin_info['classes_prefix'] . '-timer-timezone',
						'input_footer' => esc_html__( 'Custom Timezone. Default is the site timezone', 'simple-countdown' ),
						'attrs'        => array(
							'disabled'     => 'disabled',
						),
					),
					// Redirct URl.
					'timer_redirect_url' => array(
						'wrapper_classes' => ' bg-light',
						'input_label'  => esc_html__( 'Redirect URL', 'simple-countdown' ) . $core->pro_btn( '', 'Pro', '', '', true ),
						'input_footer' => esc_html__( 'Redirect after the timer is completed', 'simple-countdown' ),
						'type'         => 'url',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-redirect-url regular-text w-100',
						'attrs'        => array(
							'disabled'     => 'disabled',
						),
					),
				),
			),
			'colors'    => array(
				'settings_list' => array(
					// Days Colors.
					'colors_days_title_color'            => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'days',
						),
					),
					'colors_days_counter_front_color'    => array(
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'days',
						),
					),
					'colors_days_counter_back_color'     => array(
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'days',
						),
					),
					'colors_days_divider_color'          => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'days',
						),
					),
					// Hours Colors.
					'colors_hours_title_color'           => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'hours',
						),
					),
					'colors_hours_counter_front_color'   => array(
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'hours',
						),
					),
					'colors_hours_counter_back_color'    => array(
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'hours',
						),
					),
					'colors_hours_divider_color'         => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'hours',
						),
					),
					// Minutes Colors.
					'colors_minutes_title_color'         => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'minutes',
						),
					),
					'colors_minutes_counter_front_color' => array(
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'minutes',
						),
					),
					'colors_minutes_counter_back_color'  => array(
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'minutes',
						),
					),
					'colors_minutes_divider_color'       => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'minutes',
						),
					),
					// Seconds Colors.
					'colors_seconds_title_color'         => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor-group-heading',
							'data-target' => 'seconds',
						),
					),
					'colors_seconds_counter_front_color' => array(
						'input_label' => esc_html__( 'Counter Front Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#FFF',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'color',
							'data-handle' => 'rotor',
							'data-target' => 'seconds',
						),
					),
					'colors_seconds_counter_back_color'  => array(
						'input_label' => esc_html__( 'Counter Back Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'background',
							'data-handle' => 'rotor',
							'data-target' => 'seconds',
						),
					),
					'colors_seconds_divider_color'       => array(
						'input_label' => esc_html__( 'Title Color', 'simple-countdown' ),
						'type'        => 'text',
						'value'       => '#000',
						'classes'     => $plugin_info['classes_prefix'] . '-color-input wp-color-picker',
						'attrs'       => array(
							'data-css'    => 'border-top-color',
							'data-handle' => 'rotor-divider',
							'data-target' => 'seconds',
						),
					),
				),
			),
			'related'   => array(
				'settings_list' => array(
					'related_hide_division' => array(
						'input_label'  => esc_html__( 'Hide Divisions', 'simple-countdown' ),
						'type'         => 'checkbox',
						'value'        => 'off',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-hide-division',
						'input_footer' => esc_html__( 'Hide days, hours and minutes divisions once they are completed.', 'simple-countdown' ),
					),
					'related_title'         => array(
						'input_label'  => esc_html__( 'Timer Title', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-title regular-text w-100',
						'input_footer' => esc_html__( 'Timer Title is placed above the timer. leave it empty to disable', 'simple-countdown' ),
					),
					'related_title_type'    => array(
						'input_label'  => esc_html__( 'Timer Title Tag', 'simple-countdown' ),
						'type'         => 'select',
						'value'        => 'h3',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-title-tag',
						'input_footer' => esc_html__( 'Timer Title Tag | h1 - h6', 'simple-countdown' ),
						'options'      => array(
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
						),
					),
					'related_complete_text' => array(
						'input_label'  => esc_html__( 'Timer Complete Text', 'simple-countdown' ),
						'type'         => 'textarea',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-complete-content text-editor',
						'id'           => $plugin_info['classes_prefix'] . '-timer-complete-content',
						'input_footer' => esc_html__( 'This text will appear after the timer interval is completed.', 'simple-countdown' ),
						'html_allowed' => true,
					),
				),
			),
			'subscribe' => array(
				'section_classes' => 'opacity-50',
				'settings_list' => array(
					'subscribe_form_status'      => array(
						'input_label'  => esc_html__( 'Timer form status', 'simple-countdown' ),
						'type'         => 'checkbox',
						'value'        => 'off',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-status',
						'input_footer' => esc_html__( 'Enable - Disable the timer subscribe form.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_title'       => array(
						'input_label'  => esc_html__( 'Timer form title', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-title regular-text',
						'input_footer' => esc_html__( 'Form title.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_title_tag'   => array(
						'input_label'  => esc_html__( 'Form title tag', 'simple-countdown' ),
						'type'         => 'select',
						'value'        => 'h3',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-tile tag',
						'input_footer' => esc_html__( 'Form Title Tag | h1 - h6.', 'simple-countdown' ),
						'options'      => array(
							'h1' => 'h1',
							'h2' => 'h2',
							'h3' => 'h3',
							'h4' => 'h4',
							'h5' => 'h5',
							'h6' => 'h6',
						),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_submit_text' => array(
						'input_label'  => esc_html__( 'Timer form submit text', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => 'Subscribe',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-submit-text regular-text',
						'input_footer' => esc_html__( 'Form Submit Button Text.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
                    'subscribe_form_submit_color'   => array(
						'input_label'  => esc_html__( 'Timer form submit color', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => '#FFF',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-submit-bg wp-color-picker',
						'input_footer' => esc_html__( 'Form Submit Button Color.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_submit_bg'   => array(
						'input_label'  => esc_html__( 'Timer form submit background', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => '#000',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-submit-bg wp-color-picker',
						'input_footer' => esc_html__( 'Form Submit Button Background Color.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_consent'     => array(
						'input_label'  => esc_html__( 'Form consent text', 'simple-countdown' ),
						'type'         => 'textarea',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-consent-text text-editor',
						'input_footer' => esc_html__( 'Subscribe form consent text', 'simple-countdown' ),
						'id'           => $plugin_info['classes_prefix'] . '-timer-consent-text',
						'html_allowed' => true,
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_past_subscribe'     => array(
						'input_label'  => esc_html__( 'After Subscription text', 'simple-countdown' ),
						'type'         => 'textarea',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-past-subscribe text-editor',
						'input_footer' => esc_html__( 'This text wil appear after form subscription.', 'simple-countdown' ),
						'id'           => $plugin_info['classes_prefix'] . '-timer-form-past-subscribe',
						'html_allowed' => true,
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
					'subscribe_form_shortcode'   => array(
						'input_label'  => esc_html__( 'Custom form Shortcode', 'simple-countdown' ),
						'type'         => 'text',
						'value'        => '',
						'classes'      => $plugin_info['classes_prefix'] . '-timer-form-shortcode regular-text w-100',
						'input_footer' => esc_html__( 'You can place custom subscribe form shortcode here to use instead of the default form.', 'simple-countdown' ),
						'attrs'        => array(
							'disabled' => 'disabled',
						),
					),
				),
			),
		),
	);

}
