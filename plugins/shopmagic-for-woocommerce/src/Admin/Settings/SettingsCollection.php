<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Admin\Settings;

/**
 * @implements \IteratorAggregate<SettingTab>
 */
class SettingsCollection implements \IteratorAggregate {

	/** @var SettingTab[] */
	private $setting_tabs;

	/**
	 * @param iterable<SettingTab> $settings
	 */
	public function __construct( iterable $settings ) {
		foreach ( $settings as $setting ) {
			$this->append_setting_tab( $setting );
		}
	}

	public function append_setting_tab( SettingTab $tab ): void {
		$this->setting_tabs[ $tab::get_tab_slug() ] = $tab;
	}

	public function getIterator(): \Traversable {
		$tabs = apply_filters( 'shopmagic/core/settings/tabs', $this->setting_tabs );

		return new \ArrayIterator( $tabs );
	}
}
