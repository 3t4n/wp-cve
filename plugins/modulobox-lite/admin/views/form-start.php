<?php
/**
 * @package   ModuloBox
 * @author    Themeone <themeone.master@gmail.com>
 * @copyright 2017 Themeone
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

echo '<form action="options.php" id="modulobox" method="post" enctype="multipart/form-data">';

	echo '<div id="mobx-settings">';

		settings_fields( MOBX_NAME );
