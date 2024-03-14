<?php

namespace Watchful\Helpers;

use Watchful\Helpers\BackupPlugins\Ai1wmBackupPlugin;
use Watchful\Helpers\BackupPlugins\AkeebaBackupPlugin;
use Watchful\Helpers\BackupPlugins\XClonerBackupPlugin;

class BackupPluginHelper
{
    /** @var AkeebaBackupPlugin|null */
    private $akeebaBackupPluginHelper;
    /** @var Ai1wmBackupPlugin|null */
    private $ai1wmBackupPluginHelper;
    /** @var XClonerBackupPlugin|null */
    private $xclonerBackupPluginHelper;

    public function __construct()
    {
        $this->akeebaBackupPluginHelper = self::has_active_backup_plugin('akeeba') ? new AkeebaBackupPlugin() : null;
        $this->ai1wmBackupPluginHelper = self::has_active_backup_plugin('ai1wm') ? new Ai1wmBackupPlugin() : null;
        $this->xclonerBackupPluginHelper = self::has_active_backup_plugin('xcloner') ? new XClonerBackupPlugin() : null;
    }

    /**
     * @param $site_backups_data array
     * @return false|mixed
     */
    public function get_last_backup_date($site_backups_data)
    {
        $last_backup_dates = array();

        if (empty($site_backups_data)) {
            return false;
        }

        $akeeba_profiles = array_filter($site_backups_data, function ($profile) {
            return $profile->plugin === 'akeebav2';
        });

        $ai1wm_profiles = array_filter($site_backups_data, function ($profile) {
            return $profile->plugin === 'ai1wm';
        });

        $xcloner_profiles = array_filter($site_backups_data, function ($profile) {
            return $profile->plugin === 'xcloner';
        });

        if ($this->akeebaBackupPluginHelper !== null && !empty($akeeba_profiles)) {
            foreach ($akeeba_profiles as $akeeba_profile) {
                $last_backup_dates[] = $this->akeebaBackupPluginHelper->get_last_backup_date($akeeba_profile->akeebaProfile);
            }
        }

        if ($this->ai1wmBackupPluginHelper !== null && !empty($ai1wm_profiles)) {
            $last_backup_dates[] = $this->ai1wmBackupPluginHelper->get_last_backup_date();
        }

        if ($this->xclonerBackupPluginHelper !== null && !empty($xcloner_profiles)) {
            $last_backup_dates[] = $this->xclonerBackupPluginHelper->get_last_backup_date();
        }

        if (empty($last_backup_dates)) {
            return false;
        }

        return max($last_backup_dates);
    }

    public function get_backup_list($plugin_name) {
        if (
            (
                $plugin_name === 'akeeba' ||
                $plugin_name === 'akeebav2'
            ) &&
            $this->akeebaBackupPluginHelper !== null
        ) {
            return self::has_akeeba_backup();
        }

        if ($plugin_name === 'ai1wm' && $this->ai1wmBackupPluginHelper !== null) {
            return $this->ai1wmBackupPluginHelper->get_backup_list();
        }

        if ($plugin_name === 'xcloner' && $this->xclonerBackupPluginHelper !== null) {
            return $this->xclonerBackupPluginHelper->get_backup_list();
        }

        return array();
    }


    private static function has_akeeba_backup()
    {
        // For current Akeeba backup plugins.
        if (InstalledPlugins::has('akeebabackupwp/akeebabackupwp.php')) {
            return InstalledPlugins::has_active('akeebabackupwp/akeebabackupwp.php');
        }

        // For Akeeba backup before version 1.9.0.
        if (InstalledPlugins::has('akeebabackupwp-core/akeebabackupwp.php')) {
            return InstalledPlugins::has_active('akeebabackupwp-core/akeebabackupwp.php');
        }

        return false;
    }

    public static function has_active_backup_plugin($plugin_name)
    {
        if ($plugin_name === 'xcloner') {
            return InstalledPlugins::has_active('xcloner-backup-and-restore/xcloner.php');
        }

        if (
            $plugin_name === 'akeeba' ||
            $plugin_name === 'akeebav2'
        ) {
            return self::has_akeeba_backup();
        }

        if ($plugin_name === 'ai1wm') {
            return InstalledPlugins::has_active('all-in-one-wp-migration/all-in-one-wp-migration.php');
        }

        return false;
    }
}
