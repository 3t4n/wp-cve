<?php

foreach ( glob( QI_ADDONS_FOR_ELEMENTOR_PLUGINS_PATH . '/*/include.php' ) as $module ) {
	include_once $module;
}
