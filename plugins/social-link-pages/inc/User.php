<?php

namespace SocialLinkPages;

class User extends Singleton {

	static function get_meta_key_app_settings() {
		return Social_Link_Pages()->plugin_name_friendly . '_app_settings';
	}

	public function get_current_user_settings() {
		if ( ! is_user_logged_in() ) {
			return [];
		}

		$current_user_app_settings = get_user_meta( get_current_user_id(), User::get_meta_key_app_settings(), true );

		if ( empty( $current_user_app_settings ) ) {
			$current_user_app_settings = [];
		}

		$current_user_app_settings = maybe_unserialize( $current_user_app_settings );

		return (array) apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_current_user_settings',
			$current_user_app_settings
		);
	}

	public function update_current_user_settings( array $new_values = [] ) {
		$current_user_app_settings = User::instance()->get_current_user_settings();

		$current_user_app_settings = array_merge( (array) $current_user_app_settings, (array) $new_values );

		update_user_meta( get_current_user_id(), User::get_meta_key_app_settings(), serialize( $current_user_app_settings ) );

		return $current_user_app_settings;
	}

	protected function setup() {

	}
}