<?php

namespace Dev4Press\Plugin\GDPOL\Basic;

use Dev4Press\v43\Core\Plugins\Settings as BaseSettings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Settings extends BaseSettings {
	public $base = 'gdpol';

	public $settings = array(
		'core'     => array(
			'activated' => 0,
		),
		'settings' => array(
			'global_enabled'                  => true,
			'global_cap_check'                => 'cap',
			'global_user_roles'               => array(
				'bbp_keymaster',
				'bbp_moderator',
				'bbp_participant',
			),
			'global_disable_forums'           => array(),
			'global_auto_embed_poll'          => true,
			'global_auto_embed_icon'          => true,
			'global_auto_embed_form'          => true,
			'global_auto_embed_form_priority' => 10,

			'sort_results_by_votes'  => false,
			'calculate_multi_method' => 'voters',

			'poll_field_description'          => true,
			'poll_field_responses_allow_html' => false,
			'poll_field_show_default'         => 'always',
			'poll_field_show_included'        => true,
		),
		'objects'  => array(
			'label_poll_singular' => 'Topic Poll',
			'label_poll_plural'   => 'Topic Polls',
		),
	);

	protected function constructor() {
		$this->info = new Information();

		add_action( 'gdpol_load_settings', array( $this, 'init' ), 2 );
	}

	protected function _db() {
		InstallDB::instance()->install();
	}
}
