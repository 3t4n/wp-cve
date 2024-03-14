<?php
/**
 * CLI: Settings
 *
 * Manage plugin settings.
 *
 * @package apocalypse-meow
 * @author  Blobfolio, LLC <hello@blobfolio.com>
 */

namespace blobfolio\wp\meow\cli;

use blobfolio\wp\meow\options;
use blobfolio\wp\meow\vendor\common;
use WP_CLI;
use WP_CLI\Utils;

// Add the main command.
if (! \class_exists('WP_CLI') || ! \class_exists('WP_CLI_Command')) {
	return;
}

// Add the main command.
WP_CLI::add_command(
	'meow settings',
	\MEOW_BASE_CLASS . 'cli\\settings',
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
);

/**
 * Plugin Settings
 *
 * Apocalypse Meow allows users to configure the security policies on a
 * site-by-site basis. These configurations can be set by site
 * administrators through wp-admin, or hardcoded into the wp-config.php
 * file as PHP constants.
 *
 * These commands allow for viewing, exporting, and importing the
 * current plugin settings.
 */
class settings extends \WP_CLI_Command {

	/**
	 * Export Configuration
	 *
	 * Export the current plugin settings to keep as a backup or to
	 * restore to this or another site.
	 *
	 * Note: settings are always re-validated at load time; changes in
	 * environment, etc., – including hard-coded constants – might
	 * result in some variation.
	 *
	 * ## OPTIONS
	 *
	 * [<path>]
	 * : The export path. Settings are encoded as JSON.
	 * ---
	 * default: domain.com-apocalypse-meow.json
	 * ---
	 *
	 * [--overwrite]
	 * : Overwrite <path> if it exists.
	 *
	 * @param array $args N/A.
	 * @param array $assoc_args Flags.
	 * @return bool True.
	 */
	public function export($args=null, $assoc_args=array()) {
		// User options.
		$overwrite = !! Utils\get_flag_value($assoc_args, 'overwrite');

		$export = common\data::array_pop_top($args);
		if ('domain.com-apocalypse-meow.json' === $export) {
			$export = \str_replace('domain.com', common\sanitize::hostname(\site_url()), $export);
		}
		common\ref\file::path($export, false);
		if (! $export) {
			WP_CLI::error(
				\__('The export file path is not valid.', 'apocalypse-meow')
			);
		}

		// Check for collisions.
		if (! $overwrite && @\file_exists($export)) {
			WP_CLI::error(
				"$export " . \__('already exists. Use --overwrite to replace it.', 'apocalypse-meow')
			);
		}

		// Load and encode.
		$settings = options::get();
		$settings = \json_encode($settings);

		// Try to save it.
		@\file_put_contents($export, $settings);
		@\chmod($export, \FS_CHMOD_FILE);
		if (@\file_exists($export)) {
			WP_CLI::success(
				\__('The settings have been exported to', 'apocalypse-meow') . " $export."
			);
		}
		else {
			WP_CLI::error(
				\__('The data could not be written to', 'apocalypse-meow') . " $export."
			);
		}

		return true;
	}

	/**
	 * Import Configuration
	 *
	 * Import a plugin configuration that was previously exported via
	 * `wp meow settings export`. For best results, make sure both sites
	 * are running the same version of the plugin.
	 *
	 * Note: settings are always re-validated at load time; changes in
	 * environment, etc., – including hard-coded constants – might
	 * result in some variation.
	 *
	 * ## OPTIONS
	 *
	 * [<path>]
	 * : The import path.
	 * ---
	 * default: domain.com-apocalypse-meow.json
	 * ---
	 *
	 * @param array $args N/A.
	 * @return bool True.
	 */
	public function import($args=null) {
		// User options.
		$import = common\data::array_pop_top($args);
		if ('domain.com-apocalypse-meow.json' === $import) {
			$import = \str_replace('domain.com', common\sanitize::hostname(\site_url()), $import);
		}
		common\ref\file::path($import, true);
		if (! $import || ! @\file_exists($import)) {
			WP_CLI::error(
				\__('The import file path is not valid.', 'apocalypse-meow')
			);
		}

		$settings = @\file_get_contents($import);
		$settings = \json_decode($settings, true);
		if (! \is_array($settings) || ! \count($settings)) {
			WP_CLI::error(
				\__('The import file could not be read or is corrupt.', 'apocalypse-meow')
			);
		}

		// Parse, save, and reload (triggers sanitizing, etc.).
		$settings = common\data::parse_args($settings, options::get());
		\update_option(options::OPTION_NAME, $settings);
		options::load(true);

		WP_CLI::success(
			\__('The settings were successfully imported.', 'apocalypse-meow')
		);

		return true;
	}

	/**
	 * Show Configuration
	 *
	 * Most settings can be defined via PHP constants in wp-config.php.
	 * Hard-coded values take priority over whatever might be stored in
	 * the database and cannot be changed through the admin settings
	 * page.
	 *
	 * Those values, if any, will be indicated as "Readonly" here.
	 *
	 * @return bool True.
	 *
	 * @subcommand list
	 */
	public function _list() {
		$settings = options::get();

		$translated = array(
			'Section'=>\__('Section', 'apocalypse-meow'),
			'Setting'=>\__('Setting', 'apocalypse-meow'),
			'Value'=>\__('Value', 'apocalypse-meow'),
			'Constant'=>\__('Constant', 'apocalypse-meow'),
			'Readonly'=>\__('Readonly', 'apocalypse-meow'),
		);
		$headers = \array_values($translated);

		$readonly = options::get_readonly();

		$data = array();
		foreach ($settings as $k=>$v) {
			foreach ($v as $k2=>$v2) {
				$readonly_key = "$k-$k2";
				$tmp = array(
					$translated['Section']=>$k,
					$translated['Setting']=>$k2,
					$translated['Value']=>$v2,
					$translated['Constant']=>\strtoupper("meow_{$k}_{$k2}"),
					$translated['Readonly']=>\in_array($readonly_key, $readonly, true) ? \__('Yes', 'apocalypse-meow') : '',
				);

				// The whitelist cannot be hardcoded.
				if ('MEOW_LOGIN_EXEMPT' === $tmp['Constant']) {
					$tmp['Constant'] = '';
				}

				// Reformat the values by type.
				if (\is_bool($tmp['Value'])) {
					$tmp['Value'] = $tmp['Value'] ? 'TRUE' : 'FALSE';
				}
				elseif (\is_array($tmp['Value'])) {
					$tmp['Value'] = \implode('; ', $tmp['Value']);
				}

				$data[] = $tmp;
			}
		}

		WP_CLI\Utils\format_items('table', $data, $headers);

		return true;
	}
}
