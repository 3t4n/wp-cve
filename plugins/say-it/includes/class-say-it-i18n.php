<?php
class Say_It_i18n {
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			'say-it',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
