<?php

namespace Tussendoor\OpenRDW\Admin;

use Tussendoor\OpenRDW\Config;
use Tussendoor\OpenRDW\Includes\VehiclePlateInformationFields;
use Tussendoor\OpenRDW\Includes\VehiclePlateInformationWidget;

/**
 * The admin-specific functionality of the plugin.
 * @see       http://www.tussendoor.nl
 * @since      2.0.0
 */
class VehiclePlateInformationAdmin
{

    public $dot_config;

    /**
     * The ID of this plugin.
     * @since    2.0.0
     * @var string $open_rdw_kenteken_voertuiginformatie    The ID of this plugin.
     */
    private $open_rdw_kenteken_voertuiginformatie;

    /**
     * The version of this plugin.
     * @since    2.0.0
     * @var string $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     * @since      2.0.0
     * @param string $open_rdw_kenteken_voertuiginformatie The name of this plugin.
     * @param string $version                              The version of this plugin.
     */
    public function __construct($open_rdw_kenteken_voertuiginformatie, $version)
    {
        global $dot_config;
        $this->dot_config = $dot_config;

        $this->open_rdw_kenteken_voertuiginformatie = $open_rdw_kenteken_voertuiginformatie;
        $this->version = $version;

        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('widgets_init', [$this, 'register_widget']);

        add_action('wp_ajax_open-rdw-notice-dismiss', [$this, 'admin_notice_dismiss']);
        $nodis = get_option('open-rdw-notice-dismissed'); //Workaround, because wordpress still supports PHP 5.4..
        if (empty($nodis) && isset($_GET['page']) && $_GET['page'] == 'open_data_rdw') {
            add_action('admin_notices', [$this, 'admin_notice_donate_upgrade']);
        }

        // if($this->version === '3.0.0') {
        //     add_action('admin_notices', [$this, 'admin_notice_upgrade_available']);
        // }

        // When editting post or page add our hidden fields
        add_action('admin_footer', [$this, 'add_tinymce_form']);
        add_filter('mce_buttons', [$this, 'add_tinymce_button']);
        add_filter('mce_external_plugins', [$this, 'register_tinymce_button']);
    }

    /**
     * Display menu page.
     *
     * @since    2.0.0
     */
    public function admin_menu()
    {
        add_menu_page(
            'OpenRDW',
            'OpenRDW',
            'manage_options',
            'open_data_rdw',
            [&$this, 'settings'],
            $this->dot_config['plugin.asset_url'] . 'images/admin/open-rdw_white.png'
        );
    }

    /**
     * Display settings/admin page.
     *
     * @since    2.0.0
     */
    public function settings()
    {
        if (isset($_GET['tab']) && $_GET['tab'] == 'getting-started') {
            require_once($this->dot_config['plugin.view'] . 'admin/vehicle-plate-information-getting-started.php');
        } else {
            require_once $this->dot_config['plugin.view'] . 'admin/vehicle-plate-information-admin.php';
        }
    }

    /**
     * Function that adds a dismiss option so we don't nag our users with admin notices.
     *
     * @since    2.0.0
     */
    public function admin_notice_dismiss()
    {
        add_option('open-rdw-notice-dismissed', 1);
    }

    /**
     * Set update notification
     *
     * @return void
     */
    public function admin_notice_upgrade_available()
    {
        ?>
            <div class="notice notice-success is-dismissible open-rdw-notice">
                <p>
                    <?php echo esc_html__('Er staat een nieuwe update klaar.', 'open-rdw-kenteken-voertuiginformatie'); ?>
                    <br>
                    <?php echo esc_html__('Update nu om toegang te hebben tot de meest recente beveiligings patch.', 'open-rdw-kenteken-voertuiginformatie'); ?>
                </p>
            </div>
        <?php
    }

    /**
     * Function that displays our admin notice and might make our user donate or go premium.
     *
     * @since    2.0.0
     */
    public function admin_notice_donate_upgrade()
    {
    ?>
        <div class="notice notice-success is-dismissible open-rdw-notice">
            <p>
                <?php echo esc_html__('Je maakt gebruik van de gratis versie van onze Open RDW plugin.', 'open-rdw-kenteken-voertuiginformatie'); ?>
                <br>
                <?php echo esc_html__('Als deze plugin je bevalt, kun je ons doneren via PayPal of iDeal of de premium versie van deze plugin kopen.', 'open-rdw-kenteken-voertuiginformatie'); ?>
            </p>
            <p><a href="https://tussendoor.nl/wordpress-plugins/open-rdw">
                    <?php echo esc_html__('Kijk op deze pagina voor meer informatie.', 'open-rdw-kenteken-voertuiginformatie'); ?>
                </a></p>
        </div>
<?php
    }

    /**
     * Register our open rdw widget.
     *
     * @since    2.0.0
     */
    public function register_widget()
    {
        register_widget(VehiclePlateInformationWidget::class);
    }

    /**
     * Add our tinymce button to the post/page text area.
     *
     * @since    2.0.0
     */
    public function add_tinymce_button($buttons)
    {
        $buttons[] = 'open_rdw_kenteken_button';
        return $buttons;
    }

    /**
     * Register our tinymce button for the post/page text area.
     *
     * @since    2.0.0
     */
    public function register_tinymce_button($plugin_array)
    {
        $plugin_array['open_rdw_kenteken_button'] = $this->dot_config['plugin.asset_url'] . 'js/admin/open-rdw-kenteken-voertuiginformatie-tinymce.js';
        return $plugin_array;
    }

    /**
     * Our TB_overlay() that displays the shortcode menu when you hit the tinymce button.
     *
     * @since    2.0.0
     */
    public function add_tinymce_form()
    {
        $screen = get_current_screen();

        // Abort if there's no post type
        if ($screen->post_type === '') return;

        $fields = VehiclePlateInformationFields::fields();
        require_once $this->dot_config['plugin.view'] . 'admin/vehicle-plate-information-tinymce.php';
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->open_rdw_kenteken_voertuiginformatie, $this->dot_config['plugin.asset_url'] . 'css/admin/open-rdw-kenteken-voertuiginformatie-admin.css', [], $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    2.0.0
     */
    public function enqueue_scripts()
    {
        wp_enqueue_script($this->open_rdw_kenteken_voertuiginformatie, $this->dot_config['plugin.asset_url'] . 'js/admin/open-rdw-kenteken-voertuiginformatie-admin.js', ['jquery'], $this->version, false);

        // Gutenberg does not use thickbox, so we have to load it manually
        add_thickbox();
    }
}
