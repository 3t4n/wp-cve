<?php

/**
 * Details for the provider Compaign Monitor
 */

function provider_campaignmonitor() {

	$provider_id = 'campaignmonitor';

    $eoi_settings = get_option('easy_opt_in_settings');

	return  array(
		'info' => array(
			'id' => 'campaignmonitor',
			'name' => 'Campaign Monitor',
		),
		'settings' => array(
			'api_key' => array(
				'title' => 'Campaign Monitor Api Key',
				'html' => K::input( '{{setting_name}}'
					, array(
						'value' => K::get_var( $provider_id . '_api_key', $eoi_settings ),
						'class' => 'regular-text',
					)
					, array(
						'return' => true,
						'format' => ':input<br /><a tabindex="-1" href="http://help.campaignmonitor.com/topic.aspx?t=206" target="_blank">Where can I find my Campaign Monitor Api Key?</a>',
					)
				),
			),
			'client_id' => array(
				'title' => 'Campaign Monitor Client ID',
				'html' => K::input( '{{setting_name}}'
					, array(
						'value' => K::get_var( $provider_id . '_client_id', $eoi_settings ),
						'class' => 'regular-text',
					)
					, array(
						'return' => true,
						'format' => ':input<br /><a tabindex="-1" href="http://www.campaignmonitor.com/api/getting-started/#clientid" target="_blank">Where can I find my Campaign Monitor Client ID?</a>',
					)
				),
			),
		),
	);
}
