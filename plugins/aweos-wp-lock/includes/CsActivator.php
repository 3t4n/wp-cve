<?php
class CsActivator {
	public static function activate() {
		add_option('wpLockMode', '0');
		add_option('wpLockUntil', '0000-00-00 00:00');
		add_option('wpLockFor', '0');
		add_option('wpLockForI', 'Minutes');
		add_option('wpLockUpdated', '0000-00-00 00:00');
		add_option('wpLockFrom', '0000-00-00 00:00');
		add_option('wpLockTo', '0000-00-00 00:00');
		add_option('wpLockMessage', "This site is currently not available. Please return later!");
	}
}
