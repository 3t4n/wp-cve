<?php
/**
 * Raidboxes Class
 *
 * @package nitropack
 */

namespace NitroPack\Integration\Hosting;

use \NitroPack\SDK\Filesystem;

/**
 * Raidboxes Class
 */
class Raidboxes extends Hosting {
    const STAGE = "very_early";

    private $nginx_cache_path = ABSPATH . 'wp-content/nginx_cache';
    private $wordpress_gt_cache_path = ABSPATH . 'wp-content/gt-cache';

    /**
     * Detect if Raidboxes is active
     *
     * @return bool
     */
    public static function detect() {
        return substr(gethostname(), 0, 4) == "box-" && Filesystem::fileExists(nitropack_trailingslashit(ABSPATH) . 'rb-plugins');
    }

    /**
     * Initialize Raidboxes
     *
     * @param $stage
     * @return void
     */
    public function init($stage) {
        if (self::detect()) {
            add_action('nitropack_execute_purge_url', [$this, 'purgeCache']);
            add_action('nitropack_execute_purge_all', [$this, 'purgeCache']);
        }
    }

    private function purgeCacheDirectory($directory) {
        try {
            Filesystem::deleteDir($directory);
        } catch (\Exception $e) {
            // TODO: Log this
            return false;
        }
    }

    public function purgeCache() {
        $this->purgeCacheDirectory($this->nginx_cache_path);
        $this->purgeCacheDirectory($this->wordpress_gt_cache_path);
    }
}
