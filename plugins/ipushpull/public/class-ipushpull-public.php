<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.ipushpull.com/wordpress
 * @since      2.0.0
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Ipushpull
 * @subpackage Ipushpull/public
 * @author     ipushpull <support@ipushpull.com>
 */
class Ipushpull_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    2.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    2.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ipushpull_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ipushpull_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/ipushpull-public.css', array(), $this->version, 'all');

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Ipushpull_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Ipushpull_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/ipushpull-public.js', array('jquery'), $this->version, false);

    }

    /**
     * Shortcode input sanitisation
     * @param $input
     */
    public function sanitise_input($input)
    {
        // Add your custom sanitization logic here
        $sanitized_input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
        $sanitized_input = str_replace(array('"', "'"), array('', ''), $sanitized_input);
        $sanitized_input = $input;
        return $sanitized_input;
    }

    /**
     * Shortcode markup
     * @param $atts
     * @param null $content
     * @return string
     */
    public function add_embed_markup($atts, $content = null)
    {
        extract(shortcode_atts(array(
            // esc_attr
            'page' => isset($atts['page']) ? esc_attr($this->sanitise_input($atts['page'])) : null,
            'folder' => isset($atts['folder']) ? esc_attr($this->sanitise_input($atts['folder'])) : null,
            'uuid' => isset($atts['uuid']) ? esc_attr($this->sanitise_input($atts['uuid'])) : null,
            'toolbar' => isset($atts['toolbar']) ? esc_attr($this->sanitise_input($atts['toolbar'])) : true,
            'logo' => isset($atts['logo']) ? esc_attr($this->sanitise_input($atts['logo'])) : true,
            'btn_share' => isset($atts['btn_share']) ? esc_attr($this->sanitise_input($atts['btn_share'])) : true,
            'share' => isset($atts['share']) ? esc_attr($this->sanitise_input($atts['share'])) : true,
            'btn_popup' => isset($atts['btn_popup']) ? esc_attr($this->sanitise_input($atts['btn_popup'])) : true,
            'popup' => isset($atts['popup']) ? esc_attr($this->sanitise_input($atts['popup'])) : true,
            'btn_download' => isset($atts['btn_download']) ? esc_attr($this->sanitise_input($atts['btn_download'])) : true,
            'download' => isset($atts['download']) ? esc_attr($this->sanitise_input($atts['download'])) : true,
            'update_frequency' => isset($atts['update_frequency']) ? esc_attr($this->sanitise_input($atts['update_frequency'])) : true,
            'width' => isset($atts['width']) ? esc_attr($this->sanitise_input($atts['width'])) : '100%',
            'height' => isset($atts['height']) ? esc_attr($this->sanitise_input($atts['height'])) : '400px',
            'interval' => isset($atts['interval']) ? esc_attr($this->sanitise_input($atts['interval'])) : null,
            'background_color' => isset($atts['background_color']) ? esc_attr($this->sanitise_input($atts['background_color'])) : '',
            'beta' => isset($atts['beta']) ? esc_attr($this->sanitise_input($atts['beta'])) : false,
            'fit' => isset($atts['fit']) ? esc_attr($this->sanitise_input($atts['fit'])) : '',
            'select' => isset($atts['select']) ? esc_attr($this->sanitise_input($atts['select'])) : false,
            'edit' => isset($atts['edit']) ? esc_attr($this->sanitise_input($atts['edit'])) : false,
            'sort_filter' => isset($atts['sort_filter']) ? esc_attr($this->sanitise_input($atts['sort_filter'])) : false,
            'sortfilterbtns' => isset($atts['sortfilterbtns']) ? esc_attr($this->sanitise_input($atts['sortfilterbtns'])) : false,
            'responsive' => isset($atts['responsive']) ? esc_attr($this->sanitise_input($atts['responsive'])) : false,
            'find' => isset($atts['find']) ? esc_attr($this->sanitise_input($atts['find'])) : false,
            'highlights' => isset($atts['highlights']) ? esc_attr($this->sanitise_input($atts['highlights'])) : false,
            'copy' => isset($atts['copy']) ? esc_attr($this->sanitise_input($atts['copy'])) : false,
            'contrast' => isset($atts['contrast']) ? esc_attr($this->sanitise_input($atts['contrast'])) : '',
            'underline_links' => isset($atts['underline_links']) ? esc_attr($this->sanitise_input($atts['underline_links'])) : false,
            'view' => isset($atts['view']) ? esc_attr($this->sanitise_input($atts['view'])) : false,
            'headings' => isset($atts['headings']) ? esc_attr($this->sanitise_input($atts['headings'])) : false,
        ), []));

        if ((!$page || !$folder) && !$uuid)
            return '';

        if ($sortfilterbtns) $sort_filter = $sortfilterbtns;

        // Process back and login
        $queryParams = array(
            'toolbar' => (!$toolbar || $toolbar === "no" || $toolbar === "hide" || $toolbar === "false") ? 0 : 1,
            'logo' => (!$logo || $logo === "no" || $logo === "hide" || $logo === "false") ? 0 : 1,
            'highlights' => ($highlights === "yes" || $highlights === "1") ? 1 : "",
            'findbtn' => (!$find || $find === "no" || $find === "hide" || $find === "false") ? 0 : 1,
            'copybtn' => (!$copy || $copy === "no" || $copy === "hide" || $copy === "false") && !$edit ? 0 : 1,
            'editbtns' => (!$edit || $edit === "no" || $edit === "hide" || $edit === "false") ? 0 : 1,
            'sortfilterbtns' => (!$sort_filter || $sort_filter === "no" || $sort_filter === "hide" || $sort_filter === "false") ? 0 : 1,
            'viewbtn' => (!$view || $view === "no" || $view === "hide" || $view === "false") ? 0 : 1,
            'headings' => (!$headings || $headings === "no" || $headings === "hide" || $headings === "false") ? 0 : 1,
            'sharebtn' => (!$btn_share || $btn_share === "no" || $btn_share === "hide" || $btn_share === "false") ? 0 : 1,
            'popupbtn' => (!$btn_popup || $btn_popup === "no" || $btn_popup === "hide" || $btn_popup === "false") ? 0 : 1,
            'downloadbtn' => (!$btn_download || $btn_download === "no" || $btn_download === "hide" || $btn_download === "false") ? 0 : 1,
            'underlineLinks' => (!$underline_links || $underline_links === "no" || $underline_links === "hide" || $underline_links === "false") ? 0 : 1,
            'updatefreq' => (!$update_frequency || $update_frequency === "no" || $update_frequency === "hide" || $update_frequency === "false") ? 0 : 1,
            'background_color' => $background_color,
            'contrast' => $contrast,
            'fit' => $fit,
            'ref' => 'wp',
            'iframe' => 'ipp_page_' . $folder . '_' . $page . '_' . $uuid
        );

        $www = (!$beta || $beta === "no" || $beta === "hide" || $beta === "false") ? 'www' : 'beta';
        $responsiveClass = (!$responsive || $responsive === "no" || $responsive === "hide" || $responsive === "false") ? '' : ' ipushpull-page-responsive';
        if ($responsiveClass) {
            $queryParams['fit'] = 'contain';
            $queryParams['iframe'] .= '_responsive';
            $width = '100%';
        }


        if ($interval)
            $queryParams['pull_interval'] = $interval;

        $src = $folder . '/' . $page;
        if ($uuid) $src = $uuid;

        $attrs = array(
            'id' => $queryParams['iframe'],
            'name' => $queryParams['iframe'],
            'class' => 'ipushpull-page' . $responsiveClass,
            'style' => 'width:' . $width . ';height:' . $height . ';border:none;',
            'width' => $width,
            'height' => $height,
            'border' => '0',
            'src' => str_replace('www', $www, IPUSHPULL_URL) . '/embed/' . $src . '?' . http_build_query($queryParams)
        );

        $markup = '<iframe';

        foreach ($attrs as $key => $val) {
            $markup .= ' ' . $key . '="' . $val . '"';
        }

        $markup .= '></iframe>';
        return $markup;
    }
}
