<?php

/**
 * Copyright (c) 2021 - 2023 CodeLeaf
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace EmailProtect;

/**
 * Class Loader
 * @package EMailProtect
 */
final class Loader
{

    /**
     * @var string
     */
    private $file;
    /**
     * @var string | false
     */
    private $version;
    /**
     * @var array
     */
    private $dependencies;

    /**
     * @param string $file
     */
    public function __construct($file) {
        $file = (string) $file;
        $this->file = $file;
        $this->version = defined('EMAIL_PROTECT_VERSION') ? EMAIL_PROTECT_VERSION : false;
        $path = plugin_dir_path($this->file) . 'dist/js/email-protect.asset.php';
        $dependencies = file_exists($path) ? require($path) : [];
        if(array_key_exists('dependencies', $dependencies)) {
            $this->dependencies = $dependencies['dependencies'];
        } else {
            $this->dependencies = [];
        }
    }

    /**
     * @return void
     */
    public function run() {

        add_shortcode('mail_crypt', [$this, 'handle_shortcode']);
        add_shortcode('mail_encrypt', [$this, 'handle_shortcode']);
        add_shortcode('email_protect', [$this, 'handle_shortcode']);

        add_filter('the_content', [$this, 'email_filter']);

        add_action('wp_enqueue_scripts', [$this, 'load_script']);

    }

    /**
     * @param array $attributes
     * @return string
     */
    public function handle_shortcode(array $attributes) {
        $attributes = shortcode_atts(['mail' => '', 'text' => '', 'js' => ''], $attributes);

        if($attributes['mail'] === '') return __("<strong>Error</strong> [email_protect] shortcode arguments are invalid. 'mail' is missing.", 'email-protect');

        return CodeFactory::mail_to_code($attributes['mail'], $attributes['text']);

    }

    /**
     * @return void
     */
    public function load_script() {
        wp_enqueue_script(
            'email-protect',
            plugin_dir_url($this->file) . 'dist/js/email-protect.js',
            $this->dependencies,
            $this->version,
            true
        );
    }

    /**
     * @param string $content
     * @return string | null
     */
    public function email_filter($content) {
        $content = (string) $content;
        $links = preg_replace_callback('/MAILTO:(.*?)([\'\"])/i', function(array $input) {
            return sprintf('#%2$s data-email-protect-click="%1$s"', CodeFactory::encrypt_by_caesar($input[1]), $input[2]);
        }, $content);
        return preg_replace_callback('/[^\s\<\>]+\@\S+\.[^\s\<\>]+/i', function(array $input) {
            if(strpos($input[0], "\"") !== false) return $input[0];
            return sprintf('<span data-email-protect="%1$s"></span>', CodeFactory::encrypt_by_caesar($input[0]));
        }, isset($links) ? $links : $content);
   }

}