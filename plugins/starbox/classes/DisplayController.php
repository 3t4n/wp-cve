<?php defined('ABSPATH') || die('Cheatin\' uh?'); ?>
<?php

/**
 * The class handles the theme part in WP
 */
class ABH_Classes_DisplayController {

    private static $name;
    /** @var array all css js handles */
    private static $handles = array();

    /**
     * echo the css link from theme css directory
     *
     * @param string $filename The name of the css file or the entire uri path of the css file
     * @param string $media
     *
     * @return string
     */
    public static function loadMedia($filename = '', $params = array('trigger' => true), $media = 'all') {

        if (ABH_Classes_Tools::isAjax() || ABH_Classes_Tools::isApi()){
            return;
        }

        $css_uri = '';
        $js_uri = '';

        /* if is a custom css file */
        if (strpos($filename, '//') === false) {
            $filename = strtolower($filename);

            if (file_exists(_ABH_THEME_DIR_ . 'css/' . $filename . (ABH_DEBUG ? '' : '.min') . '.css')) {
                $css_uri = _ABH_THEME_URL_ . 'css/' . $filename . (ABH_DEBUG ? '' : '.min') . '.css?ver=' . ABH_VERSION ;
            }
            if (file_exists(_ABH_THEME_DIR_ . 'js/' . $filename . (ABH_DEBUG ? '' : '.min') . '.js')) {
                $js_uri = _ABH_THEME_URL_ . 'js/' . $filename . (ABH_DEBUG ? '' : '.min') . '.js?ver=' . ABH_VERSION;
            }
        } elseif (strpos($filename, '.css') !== FALSE)
            $css_uri = $filename;
        elseif (strpos($filename, '.js') !== FALSE) {
            $js_uri = $filename;
        }

        $handle = substr(md5($filename), 0, 10);

        //add the current handle in queue
        self::$handles[] = $handle;

        if ($css_uri <> '') {

            if (wp_style_is($handle)) {
                wp_dequeue_style($handle);
            }

            wp_enqueue_style($handle, $css_uri, null, ABH_VERSION, $media);

            if (is_admin() || isset($params['trigger']) && $params['trigger'] === true) {
                wp_print_styles(array($handle));
            }
        }

        if ($js_uri <> '') {

            if (wp_script_is($handle)) {
                wp_dequeue_script($handle);
            }

            wp_enqueue_script($handle, $js_uri, array('jquery'), ABH_VERSION, true);

            if (isset($params['trigger']) && $params['trigger'] === true) {
                wp_print_scripts(array($handle));
            }
        }
    }

    /**
     * Hook the styles and scripts
     * @return void
     */
    public function hookFooter(){
        if (is_admin() && !empty(self::$handles)) {
            wp_print_styles(self::$handles);
            wp_print_scripts(self::$handles);
        }
    }

    /**
     * Called for any class to show the block content
     *
     * @param string $block the name of the block file in theme directory (class name by default)
     *
     * @return string of the current class view
     */
    public function output($block, $obj) {
        self::$name = $block;
        echo $this->echoBlock($obj);
    }

    /**
     * echo the block content from theme directory
     *
     * @return string
     */
    public static function echoBlock($view) {
        global $post_ID;
        if (file_exists(_ABH_THEME_DIR_ . self::$name . '.php')) {
            ob_start();
            /* includes the block from theme directory */
            include(_ABH_THEME_DIR_ . self::$name . '.php');
            $block_content = ob_get_contents();
            ob_end_clean();

            return $block_content;
        }
    }

}