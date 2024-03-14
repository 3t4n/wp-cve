<?php
defined( 'ABSPATH' ) or die( 'Plugin file cannot be accessed directly.' );
/**
* PLUGIN SETTINGS PAGE
*/
class OutofStockSettings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_outofstock_menu_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_outofstock_menu_page()
    {
        // This page will be under "Settings"add_submenu_page( 'tools.php', 'SEO Image Tags', 'SEO Image Tags', 'manage_options', 'seo_image_tags', 'seo_image_tags_options_page' );

        add_submenu_page(
            'edit.php?post_type=product',
            'Out of Stock Report',
            'Out of Stock Report',
            'manage_options',
            'outofstock-stats',
            array( $this, 'create_outofstock_menu_page' )
        );

    }

    /**
     * Options page callback
     */
    public function create_outofstock_menu_page()
    {
        // Set class property
        $this->options = get_option( 'outofstock_settings_option' );
        ?>
        <div class="wrap">
            <h2>Woo Out of Stock</h2>
            <form method="post" action="options.php">

            <?php
                // This prints out all hidden setting fields
                settings_fields( 'outofstock_settings_option_group' );
                do_settings_sections( 'outofstock-setting-admin' );
                submit_button('Save Settings');
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'outofstock_settings_option_group', // Option group
            'outofstock_settings_option', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'outofstock_settings_section', // ID
            'Out of Stock Product Statistics', // Title
            array( $this, 'print_section_info' ), // Callback
            'outofstock-setting-admin' // Page
        );

        add_settings_field(
            'enable_outofstock_reports', // ID
            'Enable Reports', // Title
            array( $this, 'enable_outofstock_reports_callback' ), // Callback
            'outofstock-setting-admin', // Page
            'outofstock_settings_section' // Section
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();

        if( isset( $input['enable_outofstock_reports'] ) )
            $new_input['enable_outofstock_reports'] = absint( $input['enable_outofstock_reports'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print '<br/><p style="font-size:14px; margin:0 25% 0 0;"><strong>Options coming soon!</strong>';
    }
    /**
     * Get the settings option array and print one of its values
     */
    public function enable_outofstock_reports_callback()
    {
        //Get plugin options
        $options = get_option( 'outofstock_settings_option' );

        if (isset($options['enable_outofstock_reports'])) {
            $html .= '<input type="checkbox" id="enable_outofstock_reports"
             name="outofstock_settings_option[enable_outofstock_reports]" value="1"' . checked( 1, $options['enable_outofstock_reports'], false ) . '/>';
        } else {
            $html .= '<input type="checkbox" id="enable_outofstock_reports"
             name="outofstock_settings_option[enable_outofstock_reports]" value="1"' . checked( 1, $options['enable_outofstock_reports'], false ) . '/>';
        }

        echo $html;
    }
}

if( is_admin() )
    $outofstock = new OutofStockSettings();
