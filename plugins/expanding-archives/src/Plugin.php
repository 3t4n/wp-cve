<?php
/**
 * Plugin.php
 *
 * @package   expanding-archives
 * @copyright Copyright (c) 2022, Ashley Gibson
 * @license   GPL2+
 */

namespace Ashleyfae\ExpandingArchives;

use Ashleyfae\ExpandingArchives\Api\v1\Posts;

class Plugin
{
    /**
     * Single instance of this plugin.
     *
     * @var Plugin|null
     * @since 2.0
     */
    private static ?Plugin $instance = null;

    private string $assetsUrl;

    public function __construct()
    {
        $this->assetsUrl = trailingslashit(plugins_url('assets/build/', EXPANDING_ARCHIVES_FILE));
    }

    /**
     * Returns the single instance of this class.
     *
     * @since 2.0
     *
     * @return Plugin
     */
    public static function instance(): Plugin
    {
        if (is_null(self::$instance)) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    /**
     * Magic getter to handle backwards compatibility for properties that no longer exist.
     *
     * @since 2.0
     *
     * @param  string  $property
     *
     * @return string|null
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->{$property};
        }

        // Backwards compatibility for old properties from 1.x.
        switch ($property) {
            case '_version' :
                return EXPANDING_ARCHIVES_VERSION;
            case '_token' :
                return 'expanding-archives';
            case 'file' :
                return EXPANDING_ARCHIVES_FILE;
            case 'dir' :
                return dirname(EXPANDING_ARCHIVES_FILE);
            case 'assets_dir' :
                return trailingslashit(dirname(EXPANDING_ARCHIVES_FILE)).'assets';
            case 'assets_url' :
                return $this->assetsUrl;
            case 'script_suffix' :
                return '';
            default :
                return null;
        }
    }

    /**
     * Boots the plugin.
     *
     * @since 2.0
     *
     * @return void
     */
    public function boot(): void
    {
        add_action('init', [$this, 'loadTextDomain']);
        add_action('wp_enqueue_scripts', [$this, 'loadAssets']);
        add_action('widgets_init', function () {
            register_widget(Widget::class);
        });
        add_action('rest_api_init', function () {
            (new Posts())->register();
        });

        // Delete transient when new post is published.
        add_action('transition_post_status', array($this, 'delete_transient'), 10, 3);

        // Backwards compatibility.
        class_alias(Widget::class, 'NG_Expanding_Archives_Widget');
        class_alias(Plugin::class, 'NG_Expanding_Archives');
    }

    /**
     * Load the plugin language files.
     *
     * @since 2.0
     */
    public function loadTextDomain(): void
    {
        $locale = apply_filters('plugin_locale', get_locale(), 'expanding-archives');

        load_textdomain(
            'expanding-archives',
            WP_LANG_DIR.'/'.'expanding-archives'.'/'.'expanding-archives'.'-'.$locale.'.mo'
        );
        load_plugin_textdomain('expanding-archives', false, dirname(plugin_basename(EXPANDING_ARCHIVES_FILE)).'/lang/');
    }

    /**
     * Adds the CSS and JavaScript for the plugin.
     *
     * @since 2.0
     */
    public function loadAssets(): void
    {
        wp_enqueue_style(
            'expanding-archives',
            $this->assetsUrl.'css/expanding-archives.css',
            [],
            EXPANDING_ARCHIVES_VERSION
        );

        wp_enqueue_script(
            'expanding-archives-frontend',
            $this->assetsUrl.'js/expanding-archives.js',
            [],
            EXPANDING_ARCHIVES_VERSION,
            true
        );

        wp_localize_script(
            'expanding-archives-frontend',
            'expandingArchives',
            [
                'ajaxurl'   => admin_url('admin-ajax.php'),
                'nonce'     => wp_create_nonce('expand_archives'),
                'restBase'  => rest_url('expanding-archives/v1/posts'),
                'restNonce' => wp_create_nonce('rest_nonce'),
            ]
        );
    }

    /**
     * Gets a list of all the posts in the current month.
     *
     * @access public
     * @since  1.0.0
     * @deprecated 2.0
     * @return string
     */
    public function get_current_month_posts(): string
    {
        $renderer = new \Ashleyfae\ExpandingArchives\Helpers\ArchiveRenderer();
        $month    = new \Ashleyfae\ExpandingArchives\ValueObjects\Month(
            date('Y'),
            date('m')
        );

        return $renderer->getPostsInMonthHtml($month);
    }

    /**
     * Gets a list of all the posts in a given month/date via ajax.
     *
     * @access public
     * @since  1.0.0
     * @deprecated 2.0
     * @return void
     */
    public function load_monthly_archives(): void
    {
        // Security check.
        check_ajax_referer('expand_archives', 'nonce');

        $renderer = new \Ashleyfae\ExpandingArchives\Helpers\ArchiveRenderer();
        $month    = new \Ashleyfae\ExpandingArchives\ValueObjects\Month(
            absint($_POST['year']) ?? date('Y'),
            absint($_POST['month']) ?? date('m')
        );

        wp_send_json_success(
            $renderer->getPostsInMonthHtml($month)
        );

        exit;
    }

    /**
     * Deletes our transient of posts in the current month when
     * a new post is published.
     *
     * @param  string  $new_status
     * @param  string  $old_status
     * @param  \WP_Post  $post
     *
     * @access public
     * @since  1.0.0
     * @return void
     */
    public function delete_transient($new_status, $old_status, $post): void
    {
        if ($new_status === 'publish' || $old_status === 'publish') {
            delete_transient(sprintf('expanding_archives_posts_%d_%d', date('Y'), date('m')));
            delete_transient('expanding_archives_current_month_posts');
            delete_transient('expanding_archives_months');
        }
    }

}
