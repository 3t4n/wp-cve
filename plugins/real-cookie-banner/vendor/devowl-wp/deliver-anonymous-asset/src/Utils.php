<?php

namespace DevOwl\RealCookieBanner\Vendor\DevOwl\DeliverAnonymousAsset;

/**
 * Util functionalities.
 * @internal
 */
class Utils
{
    /**
     * Read a JavaScript file and update the sourceMappingUrl parameter in the
     * file content to the correct one - it allows you to serve any file
     * via any URL with the correct source map URL.
     *
     * @param string $path The path to the original file (not the anonymous file!)
     */
    public static function readFileAndCorrectSourceMap($path)
    {
        $output = \explode("\n", \file_get_contents($path));
        // Check if last line is sourceMappingUrl
        $lastLine = \array_pop($output);
        $startWith = '//# sourceMappingURL=';
        if (\substr($lastLine, 0, \strlen($startWith)) === $startWith) {
            $mapFile = $path . '.map';
            $usedFolder = \basename(\dirname($mapFile));
            $usedFile = \basename($mapFile);
            if (\file_exists($mapFile)) {
                $output[] = $startWith . \wp_make_link_relative(\plugins_url('public/' . $usedFolder . '/' . $usedFile, RCB_FILE));
            }
        } else {
            $output[] = $lastLine;
        }
        return \join("\n", $output);
    }
    /**
     * Get the content directory URL.
     */
    public static function getContentUrl()
    {
        return \trailingslashit(\set_url_scheme(\constant('WP_CONTENT_URL')));
    }
    /**
     * Get the content directory within `wp-content` and also ensure it is created.
     *
     * @return string[]|false
     */
    public static function getContentDir()
    {
        $contentDir = \wp_normalize_path(\constant('WP_CONTENT_DIR') . '/');
        /**
         * Get the content directory where anonymous assets should be placed.
         *
         * If you change the directory, the old assets are not deleted automatically as this could break
         * the cache of caching plugins like WP Rocket.
         *
         * Attention: This filter needs to return an absolute path pointing to a directory within your
         * `WP_CONTENT_DIR` (`wp-content/`) folder so we can safely convert it to an URL, if not, it falls
         * back to `wp-content/`.
         *
         * @hook DevOwl/DeliverAnonymousAsset/ContentDir
         * @param {string} $folder
         * @return {string}
         * @see https://devowl.io/knowledge-base/real-cookie-banner-javascript-files-in-wp-content/
         * @example <caption>Put the files to `wp-content/uploads`</caption>
         * <?php
         * add_filter( 'DevOwl/DeliverAnonymousAsset/ContentDir', function ( $content_dir )  {
         *     $folder = trailingslashit(wp_upload_dir()['basedir']);
         *     return $folder;
         * });
         */
        $folder = \wp_normalize_path(\trailingslashit(\apply_filters('DevOwl/DeliverAnonymousAsset/ContentDir', $contentDir)));
        // Force to use `wp-content` folder
        if (\strpos($folder, $contentDir) !== 0) {
            $folder = $contentDir;
        }
        if (!\wp_is_writable($folder) && !\wp_mkdir_p($folder)) {
            return \false;
        }
        return $folder;
    }
    /**
     * This hash function is used to generate a simple hash from a given string. This is very simple
     * so it can be used in frontend (e.g. Webpack chunk loading).
     *
     * @param string $s
     */
    public static function simpleHash($s)
    {
        $a = 0;
        foreach (\str_split($s) as $char) {
            $charCode = \ord($char);
            // Force PHP to perform integer arithmetic by using bitwise operations.
            // Use & to ensure the result stays within PHP's integer size.
            $a = ($a << 5 & \PHP_INT_MAX) - $a + $charCode;
            // Use a bitwise AND with a large prime number to ensure the result stays within 64-bit bounds
            // and to avoid negative numbers on systems where PHP ints are 64 bits.
            $a = $a & 0x7fffffff;
            // This is the largest 31-bit positive integer
        }
        return $a;
    }
}
