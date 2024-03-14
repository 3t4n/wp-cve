<?php
/**
 * Apocalypse Meow CLI.
 *
 * Yay WP-CLI!
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow;

use blobfolio\wp\meow\vendor\common;
use WP_CLI;

// Add the main command.
if (! \class_exists('WP_CLI') || ! \class_exists('WP_CLI_Command')) {
	return;
}

if (WP_CLI::add_command(
	'meow',
	\MEOW_BASE_CLASS . 'cli',
	array(
		'before_invoke'=>function() {
			if (\is_multisite()) {
				WP_CLI::error(\__('This plugin cannot be used on Multi-Site.', 'apocalypse-meow'));
			}

			if (! \defined('FS_CHMOD_DIR')) {
				\define('FS_CHMOD_DIR', (@\fileperms(\ABSPATH) & 0777 | 0755));
			}
			if (! \defined('FS_CHMOD_FILE')) {
				\define('FS_CHMOD_FILE', (@\fileperms(\ABSPATH . 'index.php') & 0777 | 0644));
			}
		},
	)
)) {
	// Bootstrap the subcommands.
	foreach (array('activity', 'jail', 'security', 'settings') as $class) {
		require_once __DIR__ . "/cli/$class.php";
	}
}

/**
 * Apocalypse Meow
 *
 * This plugin is a simple, light-weight collection of tools to help
 * protect wp-admin, including passworth strength requirements and
 * brute-force login protection.
 *
 * ## EXAMPLES
 *
 *     wp meow --help
 */
class cli extends \WP_CLI_Command {

	/**
	 * Apocalypse Meow Status
	 *
	 * This plugin is a simple, light-weight collection of tools to help
	 * protect wp-admin, including passworth strength requirements and
	 * brute-force login protection.
	 *
	 * This command displays information about the locally-installed
	 * version.
	 *
	 * @return bool True.
	 */
	public function version() {
		// Ain't localization a bitch? Haha.
		$translated = array(
			'Author'=>\__('Author', 'apocalypse-meow'),
			'Download'=>\__('Download', 'apocalypse-meow'),
			'Email'=>\__('Email', 'apocalypse-meow'),
			'External'=>\__('External', 'apocalypse-meow'),
			'Installed'=>\__('Installed', 'apocalypse-meow'),
			'Latest'=>\__('Latest', 'apocalypse-meow'),
			'Must-Use'=>\__('Must-Use', 'apocalypse-meow'),
			'N/A'=>\__('N/A', 'apocalypse-meow'),
			'Name'=>\__('Name', 'apocalypse-meow'),
			'No'=>\__('No', 'apocalypse-meow'),
			'Plugin'=>\__('Plugin', 'apocalypse-meow'),
			'Timezone'=>\__('Timezone', 'apocalypse-meow'),
			'Upgrade'=>\__('Upgrade', 'apocalypse-meow'),
			'WordPress'=>\__('WordPress', 'apocalypse-meow'),
			'Yes'=>\__('Yes', 'apocalypse-meow'),
		);

		// Start gathering data.
		$out = array(
			$translated['Plugin']=>array(
				$translated['Name']=>about::get_local('Name'),
				$translated['Author']=>about::get_local('Author'),
				$translated['Must-Use']=>\MEOW_MUST_USE ? $translated['Yes'] : $translated['No'],
				$translated['Timezone']=>about::get_timezone(),
				$translated['Installed']=>about::get_local('Version'),
				$translated['Latest']=>about::get_remote('version'),
				$translated['Upgrade']=>(\version_compare(
						about::get_local('Version'),
						about::get_remote('version')
					) < 0) ? $translated['Yes'] : $translated['No'],
			),
		);

		// Put together some external links.
		$out[$translated['External']] = array(
			'Blobfolio'=>\MEOW_URL,
			$translated['WordPress']=>about::get_local('Plugin URI'),
			$translated['Download']=>about::get_remote('download_link'),
			$translated['Email']=>\MEOW_EMAIL,
		);

		// We have to build our own damn table because WP-CLI's
		// formtter isn't really meant for in-row headers.
		$key_width = 0;
		foreach ($translated as $v) {
			$length = common\mb::strlen($v);
			if ($length > $key_width) {
				$key_width = $length;
			}
		}
		$key_width += 4;

		$value_width = 0;
		foreach ($out as $v) {
			foreach ($v as $v2) {
				$length = common\mb::strlen($v2);
				if ($length > $value_width) {
					$value_width = $length;
				}
			}
		}
		$value_width += 2;

		$row_width = $key_width + $value_width;

		$num = 0;
		foreach ($out as $k=>$v) {
			++$num;

			if (1 === $num) {
				WP_CLI::log(
					'+' . \str_repeat('-', $row_width) . '+'
				);
			}
			WP_CLI::log(
				'|' .
				common\mb::str_pad($k, $row_width, ' ', \STR_PAD_BOTH) .
				'|'
			);
			WP_CLI::log(
				'+' . \str_repeat('-', $row_width) . '+'
			);

			foreach ($v as $k2=>$v2) {
				WP_CLI::log(
					'|' .
					WP_CLI::colorize('%B' . common\mb::str_pad("$k2:", ($key_width - 1), ' ', \STR_PAD_LEFT) . '%n') . ' ' .
					common\mb::str_pad("$v2", $value_width, ' ', \STR_PAD_RIGHT) .
					'|'
				);
			}

			WP_CLI::log(
				'+' . \str_repeat('-', $row_width) . '+'
			);
		}

		return true;
	}

}
