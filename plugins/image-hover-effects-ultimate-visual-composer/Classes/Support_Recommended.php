<?php

namespace OXI_FLIP_BOX_PLUGINS\Classes;

/**
 * Description of Support_Recommended
 *
 * @author biplo
 */
class Support_Recommended
{

    /**
     * Revoke this function when the object is created.
     *
     */
    const GET_LOCAL_PLUGINS = 'get_all_oxilab_plugins';
    const PLUGINS = 'https://www.oxilab.org/wp-json/oxilabplugins/v2/all_plugins';

    public $get_plugins = [];
    public $current_plugins = 'image-hover-effects-ultimate-visual-composer/index.php';

  

    /**
     * Admin Notice JS file loader
     * @return void
     */
    public function dismiss_button_scripts()
    {
        wp_enqueue_script('oxi_flip-admin-recommended', OXI_FLIP_BOX_URL . 'asset/backend/js/admin-recommended.js', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        wp_localize_script('oxi_flip-admin-recommended', 'oxi_flip_admin_recommended', array('ajaxurl' => admin_url('admin-ajax.php'), 'nonce' => wp_create_nonce('oxi_flip_admin_recommended')));
    }

    /**
     * Admin Notice Ajax  loader
     * @return void
     */
    public function notice_dissmiss()
    {
        if (isset($_POST['_wpnonce']) && wp_verify_nonce(sanitize_key(wp_unslash($_POST['_wpnonce'])), 'oxi_flip_admin_recommended')) :
            $data = 'done';
            update_option('oxilab_flip_box_recommended', $data);
            echo 'done';
        else :
            return;
        endif;

        die();
    }

    /**
     * Revoke this function when the object is created.
     *
     */
    public function __construct()
    {
        if (!current_user_can('install_plugins')) :
            return;
        endif;

        require_once(ABSPATH . 'wp-admin/includes/screen.php');
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }
        $this->extension();
        add_action('admin_notices', array($this, 'first_install'));
        add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'));
        add_action('admin_notices', array($this, 'dismiss_button_scripts'));
        add_action('wp_ajax_oxi_flip_admin_recommended', array($this, 'notice_dissmiss'));
    }

    public function extension()
    {
        $response = get_transient(self::GET_LOCAL_PLUGINS);
        if (!$response || !is_array($response)) {
            $URL = self::PLUGINS;
            $request = wp_remote_request($URL);
            if (!is_wp_error($request)) {
                $response = json_decode(wp_remote_retrieve_body($request), true);
                set_transient(self::GET_LOCAL_PLUGINS, $response, 10 * DAY_IN_SECONDS);
            } else {
                $response = $request->get_error_message();
            }
        }
        $this->get_plugins = $response;
    }

    /**
     * Admin Notice CSS file loader
     * @return void
     */
    public function admin_enqueue_scripts()
    {
        wp_enqueue_script("jquery");
        wp_enqueue_style('oxilab_flip-admin-notice-css', OXI_FLIP_BOX_URL . 'asset/backend/css/notice.css', false, OXI_FLIP_BOX_PLUGIN_VERSION);
        $this->dismiss_button_scripts();
    }
    /**
     * First Installation Track
     * @return void
     */
    public function first_install()
    {
        $installed_plugins = get_plugins();

        $plugin = [];
        $i = 1;

        foreach ($this->get_plugins as $key => $value) {
            if (!isset($installed_plugins[$value['modules-path']])) :
                $plugin[$i] = $value;
                $i++;
            endif;
        }


        $recommend = [];

        for ($p = 1; $p < 100; $p++) :
            if (isset($plugin[$p]) && count($recommend) < 3) :
                if (isset($plugin[$p]['dependency']) && $plugin[$p]['dependency'] != '') :
                    if (isset($installed_plugins[$plugin[$p]['dependency']])) :
                        $recommend = $plugin[$p];
                        $p = 100;
                    endif;
                elseif ($plugin[$p]['modules-path'] != $this->current_plugins) :

                    $recommend = $plugin[$p];
                    $p = 100;
                endif;

            else :
                $p = 100;
            endif;
        endfor;

        if (count($recommend) > 2 && $recommend['modules-path'] != '') :
            $plugin = explode('/', $recommend['modules-path'])[0];

            $install_url = wp_nonce_url(add_query_arg(array('action' => 'install-plugin', 'plugin' => $plugin), admin_url('update.php')), 'install-plugin' . '_' . $plugin);
?>
            <div class="oxi-addons-admin-notifications" style=" width: auto;">
                <h3>
                    <span class="dashicons dashicons-flag"></span>
                    Notifications
                </h3>
                <p></p>
                <div class="oxi-addons-admin-notifications-holder">
                    <div class="oxi-addons-admin-notifications-alert">
                        <p>Thank you for using my Flipbox - Awesomes Flip Boxes Image Overlay. <?php echo esc_html($recommend['modules-massage']); ?></p>
                        <p><a href="<?php echo esc_url($install_url); ?>" class="button button-large button-primary">Install Now</a> <a href="#" class="button button-large button-secondary oxi-flip-admin-recommended-dismiss">No, Thanks</a></p>
                    </div>
                </div>
                <p></p>
            </div>
<?php
        endif;
    }
}
