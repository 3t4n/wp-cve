<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.buymeacoffee.com
 * @since      1.0.0
 *
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Buy_Me_A_Coffee
 * @subpackage Buy_Me_A_Coffee/admin
 * @author     Buymeacoffee <hello@buymeacoffee.com>
 */
class Buy_Me_A_Coffee_Admin
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;



    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version     = $version;
        $this->view = new Buy_Me_A_Coffee_Admin_View();
        add_action('wp_head', array(&$this, 'header_widget'));
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Buy_Me_A_Coffee_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Buy_Me_A_Coffee_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/buy-me-a-coffee-admin.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Buy_Me_A_Coffee_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Buy_Me_A_Coffee_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/buy-me-a-coffee-admin.js', array(
            'jquery'
        ), $this->version, false);
    }

    public function bmc_menu()
    {
        add_menu_page(__('Buy Me a Coffee', 'bmc-menu'), __('Buy Me a Coffee', 'bmc-menu'), 'manage_options', 'buy-me-a-coffee', array(
            $this->view,
            'bmc_show_data'
        ), 'data:image/svg+xml;base64,PHN2ZyBpZD0iTGF5ZXJfMSIgZGF0YS1uYW1lPSJMYXllciAxIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAyMCAyMCI+PHRpdGxlPmRvd25sb2FkPC90aXRsZT48cGF0aCBkPSJNMTUuMTQsMi4zOSwxNCwwSDZMNC44NCwyLjM5SDMuMjh2Mi4xaC40OEw2LjE2LDIwaDcuNjVsMi41LTE1LjUxaC40MVYyLjM5Wk0xMi45MywxOUg3TDQuODMsNC42OEgxNS4yNFoiIGZpbGw9IiM4Mjg3OGMiLz48cG9seWdvbiBwb2ludHM9IjMuNTkgOC4yIDE2LjQxIDguMiAxNS4yIDE0Ljk3IDQuODggMTQuOTcgMy41OSA4LjIiIGZpbGw9IiM4Mjg3OGMiLz48L3N2Zz4=');
    }



    public function bmc_activation_redirect()
    {
        if (get_option('bmc_plugin_activated') == 0) {
            update_option('bmc_plugin_activated', 1);
            update_option('BMC_Widget_disconnect', 1);
            exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee')));
        }
    }

    function bmc_register_plugin()
    {
        $widget = new BMC_Widget();
        if (get_option('BMC_Widget_disconnect') != 1) {
            register_widget($widget);
            update_option('bmc_plugin_activated', 1);
        }
    }

    public function bmc_disconnect()
    {
        if (!$this->check_user_capabilities()) {
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee'))));
        }

        unregister_widget('BMC_Widget');
        delete_option('widget_buymeacoffee_widget');
        update_option('BMC_Widget_disconnect', 1);
        update_option('bmc_plugin_activated', 1);

        global $wpdb;
        $current_user = wp_get_current_user();
        $table = $wpdb->prefix . 'bmc_plugin';
        $tableWidget = $wpdb->prefix . 'bmc_widget_plugin';
        $admin_email = $current_user->data->user_email;

        $table = $wpdb->prefix . 'bmc_plugin';

        $where = array('admin_email' => $admin_email);

        $wpdb->delete($table, $where, $where_format = null);
        $wpdb->delete($tableWidget, $where, $where_format = null);

        die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee'))));
    }

    public function recieve_post()
    {
        if (!$this->check_user_capabilities() || !check_admin_referer('bmc_post_reception')) {
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=false'))));
        }


        status_header(200);

        global $wpdb;
        $current_user = wp_get_current_user();
        $table = $wpdb->prefix . 'bmc_plugin';

        $data = array(
            'background_color' => htmlentities(strip_tags($_POST['background_color'])),
            'text_color' => htmlentities(strip_tags($_POST['text_color'])),
            'widget_text' => htmlentities(strip_tags($_POST['text'])),
            'font_family' => htmlentities(strip_tags($_POST['font_family']))
        );
        // print_r($_POST);die();
        $where = array('name' => $_POST['bmc-user-name']);

        $wpdb->update($table, $data, $where);
        update_option('BMC_Widget_disconnect', 0);
        die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true'))));
        // die("Server received ".$_POST['slug']." from your browser.");
        //request handlers should die() when they complete their task
    }

    public function name_post()
    {
        if (!$this->check_user_capabilities() || !check_admin_referer('bmc_name_post')) {
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&name=false'))));
        }

        status_header(200);
        global $wpdb;


        global $wpdb;
        $table_name      = $wpdb->prefix . 'bmc_plugin';
        $table_plugin_name      = $wpdb->prefix . 'bmc_widget_plugin';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "DROP TABLE IF EXISTS $table_name";
        $wpdb->query($sql);

        $sqlplugin = "DROP TABLE IF EXISTS $table_plugin_name";
        $wpdb->query($sqlplugin);

        $sql = "CREATE TABLE $table_name (
  					id mediumint(9) NOT NULL AUTO_INCREMENT,
  					created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
            slug VARCHAR(70),
            name VARCHAR(30),
            background_color VARCHAR(30),
            text_color VARCHAR(20),
            widget_text VARCHAR(50),
            font_family VARCHAR(100),
            type TINYINT DEFAULT 1,
            admin_email VARCHAR(100),
            button_isactive TINYINT DEFAULT 0,
  					PRIMARY KEY  (id)
					) $charset_collate;";


        $table_name      = $wpdb->prefix . 'bmc_widget_plugin';
        $sqlWidget = "CREATE TABLE $table_name (
              id mediumint(9) NOT NULL AUTO_INCREMENT,
              created_on datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
              name TEXT,
              description TEXT,
              message TEXT,
              widget_color TEXT,
              align TEXT,
              side_spacing TEXT,
              bottom_spacing TEXT,
              admin_email TEXT,
              widget_isactive TEXT,
              PRIMARY KEY  (id)
            ) $charset_collate;";


        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
        dbDelta($sqlWidget);





        $current_user = wp_get_current_user();
        $table = $wpdb->prefix . 'bmc_plugin';
        $tableWidget = $wpdb->prefix . 'bmc_widget_plugin';
        $admin_email = $current_user->data->user_email;
        $name = $_POST['bmc-user-name'];
        $matches = array();
        preg_match('/buymeacoffee\.com\/([^\/&?=]+)\/*/', $name, $matches);
        if ($matches[1]) {
            $name = $matches[1];
        }

        $response = wp_remote_post('https://app.buymeacoffee.com/api/v1/check_availability/?project_slug=' . $name  , array(
            'headers' => array(
                'Accept' => 'application/json/*/*',
                'connection' => 'keep-alive',
            ),
            'cookies'     => array(),
        ));

        $decodejs = json_decode($response['body']);

        if (!is_null($decodejs->data->available) && !$decodejs->data->available) {
            $result = $wpdb->get_row("SELECT *FROM $table	WHERE admin_email ='" . $admin_email . "'");
            // var_dump($result);die();
            if (empty($result)) {
                $current_user = wp_get_current_user();

                $data = array(
                    'created_on' => date('Y-m-d H:i:s'),
                    'name' => $name,
                    'slug' => '',
                    'background_color' => '#ff813f',
                    'text_color' => '#ffffff',
                    'widget_text' => 'Buy Me a Coffee',
                    'font_family' => 'Cookie',
                    'type' => '1',
                    'admin_email' => $current_user->data->user_email,
                    'button_isactive' => '0'

                );
                $wpdb->insert($table, $data);

                $dataWidget = array(
                    'created_on' => date('Y-m-d H:i:s'),
                    'name' => $name,
                    'description' => '',
                    'widget_color' => '#FF813F',
                    'message' => '',
                    'align' => 'right',
                    'side_spacing' => '',
                    'bottom_spacing' => '',
                    'admin_email' => $current_user->data->user_email,
                    'widget_isactive' => '0'
                );

                $wpdb->insert($tableWidget, $dataWidget);
            } else {
                $data = array(
                    'name' => $name
                );
                $where = array(
                    'admin_email' => $current_user->data->user_email
                );
                $wpdb->update($table, $data, $where, $format = null, $where_format = null);

                $data = array(
                    'name' => $name
                );
                $wpdb->update($tableWidget, $data, $where, $format = null, $where_format = null);
            }
            update_option('BMC_Widget_disconnect', 0);

            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&name=true'))));
        } else {
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&name=false'))));
        }
    }


    public function widget_post()
    {
        if (!$this->check_user_capabilities() || !check_admin_referer('bmc_widget_post')) {
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&widget=false'))));
        }

        status_header(200);

        global $wpdb;
        $current_user = wp_get_current_user();
        $table = $wpdb->prefix . 'bmc_widget_plugin';
        // var_dump($_POST['toogle_switch_widget']);
        // die();

        if ($_POST['toogle_switch_widget'] == 1) {
            $data = array(
                'description' => 'Support me on Buy Me a Coffee!',
                'message' => 'Thank you for visiting. You can now buy me a coffee!',
                'align' => 'right',
                'widget_color' => '#FF813F',
                'side_spacing' => '18',
                'bottom_spacing' => '18',
                'widget_isactive' => '1'
            );
        }

        if ($_POST['reset'] == 'Delete' || $_POST['toogle_switch_widget'] == 0) {
            $data = array(
                'description' => " ",
                'message' => " ",
                'align' => " ",
                'widget_color' => "#FF813F",
                'side_spacing' => " ",
                'bottom_spacing' => " ",
                'widget_isactive' => '0'
            );
        }

        if ($_POST['save']  && $_POST['toogle_switch_widget'] == null) {
            $data = array(
                'description' => htmlentities(strip_tags($_POST['description']), ENT_NOQUOTES),
                'message' => htmlentities(strip_tags($_POST['message']), ENT_NOQUOTES),
                'align' => htmlentities(strip_tags($_POST['align'])),
                'widget_color' => htmlentities(strip_tags($_POST['widget_color'])),
                'side_spacing' => htmlentities(strip_tags($_POST['side_spacing'])),
                'bottom_spacing' => htmlentities(strip_tags($_POST['bottom_spacing'])),
                'widget_isactive' => '1'
            );
        }

        $where = array('admin_email' => $current_user->data->user_email);

        $wpdb->update($table, $data, $where);
        // var_dump($wpdb->last_query);
        // die();

        if (!$_POST['save'] && $_POST['toogle_switch_widget'] == null) {
            update_option('BMC_Widget_disconnect', 0);
            die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&widget=false'))));
        }
        update_option('BMC_Widget_disconnect', 0);
        die(exit(wp_redirect(admin_url('admin.php?page=buy-me-a-coffee&status=true&widget=true'))));
    }

    function header_widget()
    {
        global $wpdb;
        $table = $wpdb->prefix . 'bmc_widget_plugin';
        $result = $wpdb->get_row("SELECT *FROM $table ORDER BY id ASC LIMIT 1");
        ?>
        <script data-name="BMC-Widget" src="https://cdnjs.buymeacoffee.com/1.0.0/widget.prod.min.js" data-id="<?php echo $result->name ?>" data-description="<?php echo wp_unslash($result->description) ?>" data-message="<?php echo wp_unslash($result->message) ?>" data-color="<?php echo $result->widget_color ?>" data-position="<?php echo $result->align ?>" data-x_margin="<?php echo $result->side_spacing ?>" data-y_margin="<?php echo $result->bottom_spacing ?>">
        </script>
        <?php
    }

    function check_user_capabilities()
    {
        return current_user_can('activate_plugins') || current_user_can('install_plugins') || current_user_can('update_plugins');
    }
}