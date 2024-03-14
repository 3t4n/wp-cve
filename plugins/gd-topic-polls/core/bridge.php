<?php

use Dev4Press\Plugin\GDPOL\Admin\Plugin as AdminPlugin;
use Dev4Press\Plugin\GDPOL\Basic\DB;
use Dev4Press\Plugin\GDPOL\Basic\Plugin;
use Dev4Press\Plugin\GDPOL\Basic\Settings;

function gdpol() : Plugin {
	return Plugin::instance();
}

function gdpol_settings() : Settings {
	return Settings::instance();
}

function gdpol_db() : DB {
	return DB::instance();
}

function gdpol_admin() : AdminPlugin {
	return AdminPlugin::instance();
}
