<?php

namespace WPRuby_CAA\Core\Migrations;


use WPRuby_CAA\Core\Constants;

class Migrator {

	const OPTION_KEY_LATEST_VERSION_MIGRATED = '_wpruby_caa_lite_latest_migrated_version';

	public static function boot()
	{
		$migrator = new self();
		if ($migrator->should_process()) {
			$migrator->process();
		}
	}

	public function process()
	{
		$migrated_versions = [];
		foreach ($this->available_migrations() as $migration) {
			if (!$migration instanceof Interface_Migration) continue;
			if ($this->already_migrated($migration)) continue;

			if ($migration->migrate()) {
				$migrated_versions[] = $this->version_to_int($migration->version());
				$this->flag_as_migrated($migration);
			}
		}

		if ( count($migrated_versions) > 0 ) {
			update_option(self::OPTION_KEY_LATEST_VERSION_MIGRATED, max($migrated_versions));
		}

	}

	/**
	 * @return bool
	 */
	public function should_process() {
		$plugin_version = $this->version_to_int(Constants::UTIL_CURRENT_VERSION);
		$last_migrated_version = (int) get_option(self::OPTION_KEY_LATEST_VERSION_MIGRATED);

		return $plugin_version > $last_migrated_version;
	}

	/**
	 * @return Interface_Migration[]
	 */
	public function available_migrations()
	{
		return [
			new Migration_200(),
		];
	}


	/**
	 * @param string $version
	 * @return int
	 */
	private function version_to_int($version)
	{
		return (int) str_replace('.', '', $version);
	}

	/**
	 * @param Interface_Migration $migration
	 * @return bool
	 */
	private function already_migrated( $migration )
	{
		return get_option($this->option_key($migration)) == 1;
	}

	/**
	 * @param Interface_Migration $migration
	 */
	private function flag_as_migrated( $migration )
	{
		add_option($this->option_key($migration), true);
	}

	/**
	 * @param Interface_Migration $migration
	 * @return string
	 */
	private function option_key ($migration)
	{
		return '_wpruby_caa_lite_migration_' . $this->version_to_int($migration->version());

	}
}
