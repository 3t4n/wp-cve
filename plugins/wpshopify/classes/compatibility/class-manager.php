<?php

namespace ShopWP\Compatibility;

class Manager
{
    private $Filesystem;
    private $wp_filesystem;

    public $mu_plugin_dir;
    public $mu_plugin_source;
    public $mu_plugin_dest;
    public $plugin_dir_path;

    public function __construct($Filesystem, $DB_Settings_General)
    {
        $this->Filesystem = $Filesystem;
        $this->DB_Settings_General = $DB_Settings_General;

        $this->wp_filesystem = $this->Filesystem->bootstrap_filesystem();

        $this->mu_plugin_dir =
            defined('WPMU_PLUGIN_DIR') && defined('WPMU_PLUGIN_URL')
                ? WPMU_PLUGIN_DIR
                : trailingslashit(WP_CONTENT_DIR) . 'mu-plugins';

        $this->mu_plugin_source =
            trailingslashit(SHOPWP_PLUGIN_DIR_PATH) .
            'classes/compatibility/class-compatibility.php';

        $this->mu_plugin_dest =
            trailingslashit($this->mu_plugin_dir) .
            'shopwp-compatibility.php';
    }

    public function is_muplugin_update_required()
    {
        if (!defined('SHOPWP_COMPATIBILITY_PLUGIN_VERSION')) {
            return false;
        }

        $new_version = SHOPWP_COMPATIBILITY_PLUGIN_VERSION;

        $current_version = $this->DB_Settings_General->get_col_val(
            'compatibility_plugin_version',
            'string'
        );

        if (is_object($current_version)) {
            return false;
        }

        if (version_compare($new_version, $current_version, '>')) {
            return true;
        }

        return false;
    }

    public function make_compatibility_mu()
    {
        if (apply_filters('shopwp_skip_compatibility', false)) {
            return false;
        }

        $filesystem = $this->wp_filesystem;

        if (!$filesystem) {
            $filesystem = $this->Filesystem->bootstrap_filesystem();
        }

        $make_dir = $filesystem->mkdir($this->mu_plugin_dir);

        $copy_file = $filesystem->copy_file(
            $this->mu_plugin_source,
            $this->mu_plugin_dest
        );

        return $copy_file;
    }

    public function delete_compatibility_mu()
    {
        if (apply_filters('shopwp_skip_compatibility', false)) {
            return false;
        }

        $filesystem = $this->wp_filesystem;

        if (!$filesystem) {
            $filesystem = $this->Filesystem->bootstrap_filesystem();
        }

        $removed = $filesystem->delete_file($this->mu_plugin_dest);

        if ($filesystem->file_exists($this->mu_plugin_dest) && !$removed) {
            error_log(
                'ShopWP Error: The compatibility plugin could not be deactivated because your mu-plugin directory is currently not writable.  Please update the permissions of the mu-plugins folder.'
            );
            return false;
        }

        return $removed;
    }

    public function muplugin_version_check()
    {
        if (apply_filters('shopwp_skip_compatibility', false)) {
            return false;
        }

        $page_names = [
            'wps-connect',
            'wps-settings',
            'wps-tools',
            'wps-license',
            'wps-extensions',
            'wps-help',
        ];

        if (
            isset($_GET['page']) &&
            in_array($_GET['page'], $page_names) &&
            $this->is_muplugin_update_required()
        ) {
            $del_result = $this->delete_compatibility_mu();
            $mk_result = $this->make_compatibility_mu();

            $ver_update_result = $this->DB_Settings_General->update_col('compatibility_plugin_version', SHOPWP_COMPATIBILITY_PLUGIN_VERSION);
        }
    }
}
