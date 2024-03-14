<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;
class Dicode_Icons_Settings_API_Fields {

    private $settings_api;

    function __construct() {
        $this->settings_api = new Dicode_Icons_Settings_API;

        add_action( 'admin_init', array($this, 'admin_init') );
        add_action( 'admin_menu', array($this, 'admin_menu') );

        // add action genarel tab
        add_action( 'dicode_icons_form_bottom_dicode_icons_changelog', [ $this, 'dicode_icons_changelog_html' ] );
    }

    function admin_init() {

        //set the settings
        $this->settings_api->set_sections( $this->get_settings_sections() );
        $this->settings_api->set_fields( $this->get_settings_fields() );

        //initialize settings
        $this->settings_api->admin_init();
    }

    function admin_menu() {

        // admin menu page
        add_menu_page(
            'dicode_admin_page',
            esc_html__( 'Dicode Icons', 'dicode-icons-pack' ),
            esc_html__( 'Dicode Icons', 'dicode-icons-pack' ),
            'dicode_icons',
            NULL,
            'dashicons-admin-generic',
            50
        );
        
        // admin sub menu page
        add_submenu_page(
            'dicode_icons', 
            esc_html__( 'Settings', 'dicode-icons-pack' ),
            esc_html__( 'Settings', 'dicode-icons-pack' ), 
            'manage_options', 
            'dicodeicons', 
            array ( $this, 'plugin_page' ) 
        );
		add_submenu_page(
            'dicode_icons', 
            esc_html__( 'Icons Search', 'dicode-icons-pack' ),
            esc_html__( 'Icons Search', 'dicode-icons-pack' ), 
            'manage_options', 
            'dicode_icons_lib', 
            array ( $this, 'screen_icon_library' ) 
        );


    }

    // Tab Nav Menu
    function get_settings_sections() {
        $sections = array(
            array(
                'id'    => 'dicode_icons_activation',
                'title' => __( 'Libraries', 'dicode-icons-pack' )
            ),
            array(
                'id'    => 'dicode_icons_changelog',
                'title' => __( 'Changelog', 'dicode-icons-pack' )
            )
        );
        return $sections;
    }

    /**
     * Returns all the settings fields
     *
     * @return array settings fields
     */
    function get_settings_fields() {
        $settings_fields = array(

            // general tab
            'dicode_general' => array(),

            'dicode_icons_activation' => array(

                array(
                    'name'  => 'dicode_elegant_icons',
                    'label'  => __( 'Elegant', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
					'url' => 'https://www.elegantthemes.com/blog/resources/elegant-icon-font',
                    'count' => '360+ Icons',
					'default' => 'on',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_linearicons',
                    'label'  => __( 'Linearicons', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
					'url' => 'https://linearicons.com/',
                    'count' => '170+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_themify_icons',
                    'label'  => __( 'Themify', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
					'url' => 'https://themify.me/themify-icons',
                    'count' => '350+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_simple_lineicons',
                    'label'  => __( 'Simple Lineicons', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://simplelineicons.github.io/',
                    'count' => '200+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_lineicons',
                    'label'  => __( 'Lineicons', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://lineicons.com/',
                    'count' => '500+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),

                array(
                    'name'  => 'dicode_ionicons',
                    'label'  => __( 'Ionicons', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://ionicons.com/',
                    'count' => '700+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_icofont_icons',
                    'label'  => __( 'Icofont', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://icofont.com/',
                    'count' => '2000+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_lineawesome',
                    'label'  => __( 'Line Awesome', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://icons8.com/line-awesome',
                    'count' => '2000+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'material_icon',
                    'label'  => __( 'Material Design', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'on',
					'url' => 'http://materialdesignicons.com/',
                    'count' => '5300+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_devicons',
                    'label'  => __( 'Devicons', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'http://vorillaz.github.io/devicons/#/dafont',
                    'count' => '190+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_icomoon_icons',
                    'label'  => __( 'Icomoon', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://icomoon.io/#preview-free',
                    'count' => '500+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_elusive_icons',
                    'label'  => __( 'Elusive', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'http://elusiveicons.com/',
                    'count' => '300+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_iconic_icons',
                    'label'  => __( 'Iconic', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://github.com/somerandomdude/Iconic',
                    'count' => '170+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_icomb_icons',
                    'label'  => __( 'Icomoon Brands', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://simpleicons.org/',
                    'count' => '900+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),
                array(
                    'name'  => 'dicode_open_iconic',
                    'label'  => __( 'Open Iconic', 'dicode-icons-pack' ),
                    'type'  => 'checkbox',
                    'default' => 'off',
					'url' => 'https://useiconic.com/open',
                    'count' => '200+ Icons',
                    'class'=>'dicode_icons_setting_field_wrapper',
                ),



            )
        );

        return $settings_fields;
    }

    // admin page tab wrapper
    function plugin_page() {
        echo '<div class="wrap">';
        echo "<h2>" . __( 'Dicode Icons Pack', 'dicode-icons-pack' ) . "</h2>";
        $this->save_message();
        $this->settings_api->show_navigation();
        $this->settings_api->show_forms();
        echo '</div>';
    }
	
	public function screen_icon_library() {
		echo '<div class="wrap" style="height:0;overflow:hidden;"><h2></h2></div>';
		include_once( 'icon-library.php' );
	}

    // Saved Successfully Notification
    function save_message() {
        if( isset($_GET['settings-updated']) ) { ?>
            <div class="updated notice dicode-icons-notice is-dismissible"> 
                <p><strong><?php echo __('Successfully Settings Saved.', 'dicode-icons-pack') ?></strong></p>
            </div>
            <?php
        }
    }    

    /**
     * Get all the pages
     *
     * @return array page names with key value pairs
     */
    function get_pages() {
        $pages = get_pages();
        $pages_options = array();
        if ( $pages ) {
            foreach ($pages as $page) {
                $pages_options[$page->ID] = $page->post_title;
            }
        }

        return $pages_options;
    }  

    /**
     * Update info tab output
     *
     * @return  Update info html markup output
     */
    function dicode_icons_changelog_html(){
        ob_start();
        ?>
            <div class="dicode_icons_changelog_html-wrapper">

                <div class="dicode_icons_-change-log-wrapper">
                    <div class="dicode_icons_-change-log-inner">
                        <div class="dicode_icons_-change-log">
                            <div class="dicode_icons_-single-change-log">
                                <h3><?php echo esc_html('Change Log', 'dicode-icons-pack'); ?></h3>
                            </div>
                        </div>
                        <div class="dicode_icons_-change-log-table-wrapper">
                            <table class="dicode_icons_-change-log-table">
                                                      
                                <tr>
                                    <td>
                                        <label><?php echo esc_html('1.0.0 (Date: 30-11-2022)', 'dicode-icons-pack'); ?></label>
                                        <ul>
                                            <li><i class="dashicons dashicons-yes"></i><?php echo esc_html('Initial release', 'dicode-icons-pack'); ?></li>
                                        </ul>
                                    </td>
                                </tr>
                            </table>   
                        </div>                         
                    </div>


                </div>

            </div>
        <?php
        echo ob_get_clean();
    }    

}
new Dicode_Icons_Settings_API_Fields();

