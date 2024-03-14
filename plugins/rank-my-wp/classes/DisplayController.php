<?php

/**
 * The class handles the theme part in WP
 */
class RKMW_Classes_DisplayController {

    private static $cache;

    /**
     * echo the css link from theme css directory
     *
     * @param string $uri The name of the css file or the entire uri path of the css file
     * @param string $params : trigger, media
     *
     * @return void
     */
    public static function loadMedia($uri = '', $params = array()) {
        if (RKMW_Classes_Helpers_Tools::isAjax()) {
            return;
        }

        if (empty($params)) {
            $params = array(
                'trigger' => false,
                'media' => 'all'
            );
        }

        $css_uri = '';
        $js_uri = '';

        if (isset(self::$cache[$uri]))
            return;

        self::$cache[$uri] = true;

        /* if is a custom css file */
        if (strpos($uri, '//') === false) {
            $name = substr(md5($uri), 0, 10);

            if (strpos($uri, '.css') !== false && file_exists(RKMW_ASSETS_DIR . 'css/' . strtolower($uri))) {
                $css_uri = RKMW_ASSETS_URL . 'css/' . strtolower($uri);
            }
            if (file_exists(RKMW_ASSETS_DIR . 'css/' . strtolower($uri) . (RKMW_DEBUG ? '' : '.min') . '.css')) {
                $css_uri = RKMW_ASSETS_URL . 'css/' . strtolower($uri) . (RKMW_DEBUG ? '' : '.min') . '.css';
            }

            if (strpos($uri, '.js') !== false && file_exists(RKMW_ASSETS_DIR . 'js/' . strtolower($uri))) {
                $js_uri = RKMW_ASSETS_URL . 'js/' . strtolower($uri);
            }
            if (file_exists(RKMW_ASSETS_DIR . 'js/' . strtolower($uri) . (RKMW_DEBUG ? '' : '.min') . '.js')) {
                $js_uri = RKMW_ASSETS_URL . 'js/' . strtolower($uri) . (RKMW_DEBUG ? '' : '.min') . '.js';
            }

        } else {

            $name = substr(md5($uri), 0, 10);

            if (strpos($uri, '.css') !== FALSE) {
                $css_uri = $uri;
            } elseif (strpos($uri, '.js') !== FALSE) {
                $js_uri = $uri;
            }

        }


        if ($css_uri <> '') {
            if (!wp_style_is($name)) {
                wp_enqueue_style($name, $css_uri, null, RKMW_VERSION, $params['media']);

                if (is_admin() || (isset($params['trigger']) && $params['trigger'] === true)) { //load CSS for admin or on triggered
                    wp_print_styles(array($name));
                }
            }

        }

        if ($js_uri <> '') {
            if (!wp_script_is($name)) {

                if (!wp_script_is('jquery')) {
                    wp_enqueue_script('jquery');
                    wp_print_scripts(array('jquery'));
                }

                wp_enqueue_script($name, $js_uri, null, RKMW_VERSION);

                if (is_admin() || isset($params['trigger']) && $params['trigger'] === true) {
                    wp_print_scripts(array($name));
                }
            }

        }
    }

    /**
     * return the block content from theme directory
     *
     * @param $block
     * @param $view
     * @return bool|string
     */
    public function getView($block, $view) {

        try {
            $file = apply_filters('rkmw_view', RKMW_THEME_DIR . $block . '.php', $block);

            if (file_exists($file)) {
                ob_start();
                include($file);
                return ob_get_clean();
            }

        } catch (Exception $e) {
        }

        return false;
    }

}
