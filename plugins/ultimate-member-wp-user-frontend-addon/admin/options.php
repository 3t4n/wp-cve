<?php      
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
class UAF_Settings {

    private $settings_api;

    function __construct() {
        $this->settings_api = new WeDevs_Settings_API();

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {
        add_options_page( 'UFA Settings', 'UFA Settings', 'activate_plugins', 'ufa_settings', array($this, 'plugin_page') );
    }

    function get_settings_sections() {
        $sections = array(
          array(
            'id' => 'settings_basic',
            'title' => __( 'Basic Settings', 'ufa' )
          )
        );
        return $sections;
    }

    function get_settings_fields() {

            global $ultimatemember;
            $fields = array(
                'settings_basic' => array(
                    array(
                        'name' => 'roles',
                        'label' => __( 'Roles', 'ufa' ),
                        'desc' => __( 'In what roles want to show additional boxes.', 'ufa' ),
                        'type' => 'multicheck',
                        'options' => um_get_roles(),
                    ),
                    array(
                        'name' => 'slug_add',
                        'label' => __( 'Slug ADD', 'ufa' ),
                        'desc' => __( 'Slug in URL for Add post', 'ufa' ),
                        'type' => 'text',
                        'default' => 'add-post'
                    ),
                    array(
                        'name' => 'slug_view',
                        'label' => __( 'Slug VIEW', 'ufa' ),
                        'desc' => __( 'Slug in URL for My posts', 'ufa' ),
                        'type' => 'text',
                        'default' => 'my-posts'
                    ),
                    array(
                        'name' => 'id_form',
                        'label' => __( 'Form ID', 'ufa' ),
                        'desc' => __( 'Form ID from WP User Frontend Plugin', 'ufa' ),
                        'type' => 'text',
                        'default' => ''
                    )
                )
            );
            return $fields;
    }

    function plugin_page() {
        ?>
        <div class="wrap">
            <?php
            settings_errors();

            $this->settings_api->show_navigation();
            $this->settings_api->show_forms();
            ?>
        </div>
        <?php
    }
}
$wpuf_settings = new UAF_Settings();