<?php
/**
 * Plugin Name:       Video Player for WPBakery
 * Plugin URI:        https://wordpress.org/plugins/video-player-for-wpbakery/
 * Description:       Video Player add-on for WPBakery Page Builder allow add YouTube, Vimeo and Self-Hosted videos (HTML5) to your WordPress website.
 * Version:           1.0.1
 * Requires at least: 4.7
 * Requires PHP:      7.0
 * Author:            NuttTaro
 * Author URI:        https://nutttaro.com
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       video-player-for-wpbakery
 * Domain Path:       /languages
 */

// don't load directly
if (!defined('ABSPATH')) {
    die('-1');
}

// Define constants.
define('WBVP_PATH', plugin_dir_path(__FILE__));
define('WBVP_BASENAME', plugin_basename(__FILE__));
define('WBVP_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WBVP_VERSION', '1.0.1');

/**
 * Class Video_Player_For_WPBakery
 */
class Video_Player_For_WPBakery
{

    /** @var null $instance */
    public static $instance = null;

    /**
     * Singleton method responsible for the instantiation of the object
     */
    static public function instance()
    {
        if (null === self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Buzzwoo_Wpbakery_Video_Field constructor.
     */
    public function __construct()
    {
        add_filter('plugin_action_links_' . WBVP_BASENAME, [$this, 'add_plugin_donate_link']);

        /**
         * Check WPBakery Page Builder already installed and activated
         */
        if (in_array('js_composer/js_composer.php', apply_filters('active_plugins', get_option('active_plugins')))) {

            add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts'], 999);
            add_action('admin_enqueue_scripts', [$this, 'admin_enqueue_scripts'], 9999);
            add_action('vc_load_default_params', [$this, 'vc_load_params'], 999);
            add_action('vc_before_init', [$this, 'vc_map'], 999);

            add_shortcode('video_player_for_wpbakery', [$this, 'video_player_for_wpbakery']);
        } else {
            add_action('admin_notices', [$this, 'missing_plugins_warning']);
        }
    }

    /**
     * Add donate link
     *
     * @param $links
     * @return mixed
     */
    public function add_plugin_donate_link($links)
    {
        $links[] = '<a href="https://www.buymeacoffee.com/nutttaro" target="_blank">' . __('Donate') . '</a>';
        return $links;
    }

    /**
     * Admin Enqueue Scripts
     */
    public function admin_enqueue_scripts()
    {
        wp_enqueue_script('wbvp-video-field', WBVP_PLUGIN_URL . 'assets/js/admin-script.min.js', ['jquery'], WBVP_VERSION, true);
    }

    /**
     * Enqueue Scripts
     */
    public function enqueue_scripts()
    {
        wp_register_style('wbvp-video-style', WBVP_PLUGIN_URL . 'assets/css/style.css', [], WBVP_VERSION);
    }

    /**
     * VC load params
     */
    public function vc_load_params()
    {
        vc_add_shortcode_param('attach_video', [$this, 'vc_attach_video_form_field']);
    }

    /**
     * VC Map
     *
     * @throws Exception
     */
    public function vc_map()
    {
        if (function_exists('vc_map')) {
            vc_map(
                [
                    "name"     => esc_html__("Video Player"),
                    "base"     => "video_player_for_wpbakery",
                    "class"    => 'video-player-for-wpbakery',
                    "icon"     => WBVP_PLUGIN_URL . "assets/images/video-player.svg",
                    "category" => esc_html__('Content', 'video-player-for-wpbakery'),
                    "params"   => [
                        [
                            'type'             => 'dropdown',
                            'heading'          => esc_html__('Video Type', 'video-player-for-wpbakery'),
                            'param_name'       => 'type',
                            'admin_label'      => true,
                            'value'            => [
                                __('Self-Hosted videos (HTML5)', 'video-player-for-wpbakery') => 'html5',
                                __('Youtube or Vimeo', 'video-player-for-wpbakery')           => 'embed',
                            ],
                            'std'              => 'html5',
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-type',
                        ],
                        [
                            'type'             => 'attach_video',
                            'heading'          => esc_html__('Video', 'video-player-for-wpbakery'),
                            'param_name'       => 'video',
                            'value'            => '',
                            'description'      => esc_html__('Select video from media library.', 'video-player-for-wpbakery'),
                            'admin_label'      => true,
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video',
                        ],
                        [
                            'type'             => 'textfield',
                            'heading'          => esc_html__('Video URL', 'video-player-for-wpbakery'),
                            'param_name'       => 'video_url',
                            'admin_label'      => true,
                            'description'      => esc_html__('Youtube or Vimeo URL that should be embedded the video.', 'video-player-for-wpbakery'),
                            'value'            => '',
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video_url',
                        ],
                        [
                            'type'             => 'textfield',
                            'heading'          => esc_html__('Width', 'video-player-for-wpbakery'),
                            'param_name'       => 'width',
                            'value'            => '560',
                            'edit_field_class' => 'vc_col-xs-6',
                        ],
                        [
                            'type'             => 'textfield',
                            'heading'          => esc_html__('Height', 'video-player-for-wpbakery'),
                            'param_name'       => 'height',
                            'value'            => '315',
                            'edit_field_class' => 'vc_col-xs-6',
                        ],
                        [
                            'type'             => 'checkbox',
                            'heading'          => esc_html__('Controls', 'video-player-for-wpbakery'),
                            'param_name'       => 'controls',
                            'value'            => [esc_html__('Yes', 'video-player-for-wpbakery') => 'controls'],
                            'std'              => 'controls',
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video',
                        ],
                        [
                            'type'             => 'checkbox',
                            'heading'          => esc_html__('Autoplay', 'video-player-for-wpbakery'),
                            'param_name'       => 'autoplay',
                            'description'      => esc_html__('Video will muted sound because some browser block autoplay the video.', 'video-player-for-wpbakery'),
                            'value'            => [esc_html__('Yes', 'video-player-for-wpbakery') => 'autoplay'],
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video video-player-for-wpbakery-autoplay',
                        ],
                        [
                            'type'             => 'checkbox',
                            'heading'          => esc_html__('Muted', 'video-player-for-wpbakery'),
                            'param_name'       => 'muted',
                            'value'            => [esc_html__('Yes', 'video-player-for-wpbakery') => 'muted'],
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video video-player-for-wpbakery-muted',
                        ],
                        [
                            'type'             => 'checkbox',
                            'heading'          => esc_html__('Loop', 'video-player-for-wpbakery'),
                            'param_name'       => 'loop',
                            'value'            => [esc_html__('Yes', 'video-player-for-wpbakery') => 'loop'],
                            'edit_field_class' => 'vc_col-xs-12 video-player-for-wpbakery-video',
                        ],
                        [
                            'type'        => 'el_id',
                            'heading'     => esc_html__('Element ID', 'video-player-for-wpbakery'),
                            'param_name'  => 'el_id',
                            'description' => sprintf(esc_html__('Enter element ID (Note: make sure it is unique and valid according to %sw3c specification%s).', 'video-player-for-wpbakery'), '<a href="https://www.w3schools.com/tags/att_global_id.asp" target="_blank">', '</a>'),
                        ],
                        [
                            'type'        => 'textfield',
                            'heading'     => esc_html__('Extra class name', 'video-player-for-wpbakery'),
                            'param_name'  => 'el_class',
                            'description' => esc_html__('Style particular content element differently - add a class name and refer to it in custom CSS.', 'video-player-for-wpbakery'),
                        ],
                    ],
                ]
            );
        }
    }

    /**
     * Attach video shortcode attribute type generator.
     *
     * @param $settings
     * @param $value
     * @param $tag
     * @param false $single
     * @return string
     */
    public function vc_attach_video_form_field($settings, $value, $tag, $single = false)
    {
        $filename = '';
        $add_hide = '';
        $remove_hide = 'style="display: none;"';

        $output = '';
        $param_value = wpb_removeNotExistingImgIDs($value);
        $output .= '<input type="hidden" class="wpb_vc_param_value widget_attached_video_id ' . esc_attr($settings['param_name']) . ' ' . esc_attr($settings['type']) . '" name="' . esc_attr($settings['param_name']) . '" value="' . $param_value . '"/>';

        if ($value && is_numeric($value)) {
            $filename = basename(get_attached_file($value));
            $add_hide = $remove_hide;
            $remove_hide = '';
        }

        $output .= '<p class="widget_attached_video_name">' . $filename . '</p>';
        $output .= '<a class="gallery_widget_add_video" ' . $add_hide . ' href="javascript:;" title="' . esc_attr__('Add video', 'video-player-for-wpbakery') . '">' . esc_html__('Add video', 'video-player-for-wpbakery') . '</a>';
        $output .= '<a class="gallery_widget_remove_video" ' . $remove_hide . ' href="javascript:;" title="' . esc_attr__('Remove video', 'video-player-for-wpbakery') . '">' . esc_html__('Remove video', 'video-player-for-wpbakery') . '</a>';


        return $output;
    }


    /**
     * Short code Video HTML5
     *
     * @param $atts
     * @return string|void
     */
    public function video_player_for_wpbakery($atts)
    {
        wp_enqueue_style('wbvp-video-style');

        $atts = shortcode_atts([
            'type'      => 'html5',
            'video'     => '',
            'video_url' => '',
            'width'     => '560',
            'height'    => '315',
            'controls'  => 'controls',
            'preload'   => 'auto',
            'autoplay'  => '',
            'loop'      => '',
            'muted'     => '',
            'poster'    => '',
            'el_id'     => '',
            'el_class'  => '',
        ], $atts);
        extract($atts);

        ob_start();

        if ($type === 'html5' && $video) {
            $url = wp_get_attachment_url($video);
            $mime_type = get_post_mime_type($video);
            include WBVP_PATH . '/templates/video-html5.php';
        }

        if ($type === 'embed' && $this->check_video_link($video_url)) {
            $embed_code = wp_oembed_get($video_url, ['width' => $width, 'height' => $height]);
            if ($embed_code) {
                include WBVP_PATH . '/templates/embed.php';
            }
        }

        $output = ob_get_contents();
        ob_clean();

        return $output;

    }

    /**
     * Check video link
     *
     * @param $url
     * @return string
     */
    public function check_video_link($url)
    {
        if (strpos($url, 'youtube') > 0) {
            return true;
        } elseif (strpos($url, 'youtu.be') > 0) {
            return true;
        } elseif (strpos($url, 'vimeo') > 0) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Missing plugins warning.
     */
    public function missing_plugins_warning()
    {
        $class = 'notice notice-error';
        $message = __('<strong>Video Player for WPBakery</strong> is enabled but not effective. It requires <strong>WPBakery Page Builder</strong> in order to work.', 'video-player-for-wpbakery');

        printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);
    }

}

Video_Player_For_WPBakery::instance();
