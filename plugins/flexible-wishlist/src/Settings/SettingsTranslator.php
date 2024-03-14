<?php

namespace WPDesk\FlexibleWishlist\Settings;

use FlexibleWishlistVendor\WPDesk\PluginBuilder\Plugin\Hookable;
use WPDesk\FlexibleWishlist\Repository\SettingsRepository;

/**
 * Generates a plugin settings page.
 */
class SettingsTranslator implements Hookable {

	/**
	 * @var SettingsRepository
	 */
	private $settings_repository;

	public function __construct( SettingsRepository $settings_repository ) {
		$this->settings_repository = $settings_repository;
	}

	/**
	 * {@inheritdoc}
	 */
	public function hooks() {
		add_action( 'init', [ $this, 'init_settings_translation' ] );
	}

	/**
	 * @return void
	 * @internal
	 */
	public function init_settings_translation() {
		if ( ! function_exists( 'pll_register_string' ) && ! function_exists( 'icl_register_string' ) ) {
			return;
		}

		$this->settings_repository->init_options_translations();
	}
}
