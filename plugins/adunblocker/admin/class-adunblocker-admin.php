<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://digitalapps.com
 * @since      1.0.0
 *
 * @package    AdUnblocker
 * @subpackage AdUnblocker/admin
 */

class AdUnblocker_Admin {

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
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $error_log;

    /**
     * Initialize the class and set its properties.
     *
     * @since           1.0.0
     * @param           string      $plugin_name        The name of this plugin.
     * @param           string      $version            The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) {

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        $this->set_options();

    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since   1.0.0
     */
    public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in AdUnblocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AdUnblocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( 'wp-color-picker' );
        wp_enqueue_style(
            'alpha-color-picker',
            plugin_dir_url( __FILE__ ) . 'css/alpha-color-picker.css', // Update to where you put the file.
            array( 'wp-color-picker' ) // You must include these here.
        );

        wp_enqueue_style( 
            $this->plugin_name . '-style', 
            plugin_dir_url( __FILE__ ) . 'css/adunblocker-admin.css', 
            array(), 
            $this->version, 
            'all' 
        );

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since   1.0.0
     */
    public function enqueue_scripts( $hook_suffix ) {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in AdUnblocker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The AdUnblocker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        
        if( $hook_suffix == 'toplevel_page_adunblocker-general-settings-page' ) {

            wp_enqueue_script( 'wp-color-picker' );
            wp_enqueue_script(
                'alpha-color-picker',
                plugin_dir_url( __FILE__ ) . 'js/alpha-color-picker.js', // Update to where you put the file.
                array( 'jquery', 'wp-color-picker' ), // You must include these here.
                null,
                true
            );

            wp_enqueue_script( 
                $this->plugin_name . '-admin', 
                plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-admin.js', 
                array( 
                    'jquery', 
                    'wp-color-picker', 
                    'alpha-color-picker' 
                ), 
                $this->version, 
                false 
            );

        }

        if( $hook_suffix == 'adunblocker_page_adunblocker-help' ) {

            wp_enqueue_script( $this->plugin_name . '-help', plugin_dir_url( __FILE__ ) . 'js/' . $this->plugin_name . '-admin-help.js', array( 'jquery' ), $this->version, false );

        }

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since   1.0.0
     */
    public function localize_script() {

        $nonces = apply_filters( 'daau_nonces', array(
            'clear_log'             => wp_create_nonce( 'clear-log' ),
            'get_log'               => wp_create_nonce( 'get-log' ),
            'check_licence'         => wp_create_nonce( 'check-licence' )
        ) );

        $data = apply_filters( 'daau_data', array(
            'this_url'               => esc_html( addslashes( home_url() ) ),
            'nonces'                 => $nonces
        ) );

        // wp_localize_script( $handle, $name, $data );
        wp_localize_script(
            $this->plugin_name,
            'daau_app',
            $data
        );

    }

    /**
     * Adds a settings page link to a menu
     *
     * @link            https://codex.wordpress.org/Administration_Menus
     * @since           1.0.0
     * @return          void
     */
    public function add_menu() {

        // Top-level page
        // add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
        // Submenu Page
        // add_submenu_page( $parent_slug, $page_title, $menu_title, $capability, $menu_slug, $function);
        // Add the menu item and page

        $page_title = 'AdUnblocker Settings';
        $menu_title = 'AdUnblocker';
        $capability = 'manage_options';
        $slug = $this->plugin_name . '-general-settings-page';
        $callback = array( $this, 'page_options' );
        $icon = 'dashicons-welcome-widgets-menus';
        $position = 100;

        add_menu_page( $page_title, $menu_title, $capability, $slug, $callback, $icon, $position );

        add_submenu_page(
            $slug,
            apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'AdUnblocker Settings', 'adunblocker' ) ),
            apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Settings', 'adunblocker' ) ),
            'manage_options',
            $slug,
            array( $this, 'page_options' )
        );

        add_submenu_page(
            $slug,
            apply_filters( $this->plugin_name . '-settings-page-title', esc_html__( 'AdUnblocker Help', 'adunblocker' ) ),
            apply_filters( $this->plugin_name . '-settings-menu-title', esc_html__( 'Help', 'adunblocker' ) ),
            'manage_options',
            $this->plugin_name . '-help',
            array( $this, 'page_help' )
        );

        add_submenu_page(
            $slug,
            '',
            '<span class="dashicons dashicons-star-filled" style="font-size: 17px"></span> ' . esc_html__( 'Go Pro', 'adunblocker' ),
            'manage_options',
            'adunblocker_go_pro',
            array( $this, 'go_pro_redirect' )
        );

    } // add_menu()

    /**
     * Sets the class variable $options
     */
    private function set_options() {
        $this->options = get_option( $this->plugin_name . '-options' );
    } // set_options()

    /**
     * Creates the options page
     *
     * @since           1.0.0
     * @return          void
     */
    public function page_options() {

        include( plugin_dir_path( __FILE__ ) . 'partials/adunblocker-admin-page-settings.php' );

    } // page_options()

    /**
     * Creates the help page
     *
     * @since           1.0.0
     * @return          void
     */
    public function page_help() {

        include( plugin_dir_path( __FILE__ ) . 'partials/adunblocker-admin-page-help.php' );

    } // page_help()

    /**
     * Creates the options page
     *
     * @since           1.0.0
     * @return          void
     */
    public function page_sidebar() {

        include( plugin_dir_path( __FILE__ ) . 'partials/adunblocker-admin-page-sidebar.php' );

    } // page_options()

    /**
     * #1 Registers settings sections with WordPress
     */
    public function register_sections() {

        // add_settings_section( $id, $title, $callback, $menu_slug );

        add_settings_section(
            $this->plugin_name . '-options',
            apply_filters( $this->plugin_name . 'section-title', esc_html__( '', 'adunblocker' ) ),
            array( $this, 'section_options' ),
            $this->plugin_name . '-general-settings-page'
        );

        add_settings_section(
            $this->plugin_name . '-content',
            apply_filters( $this->plugin_name . 'section-title', esc_html__( '', 'adunblocker' ) ),
            array( $this, 'section_content' ),
            $this->plugin_name . '-content-settings-page'
        );

        add_settings_section(
            $this->plugin_name . '-style',
            apply_filters( $this->plugin_name . 'section-title', esc_html__( '', 'adunblocker' ) ),
            array( $this, 'section_style' ),
            $this->plugin_name . '-style-settings-page'
        );

    } // register_sections()

    /**
     * #2 Registers settings fields with WordPress
     */
    public function register_fields() {

        // add_settings_field( $id, $title, $callback, $menu_slug, $section, $args );

        // the hidden field required to pass the 0, when checkbox not checked
        // https://stackoverflow.com/questions/31367098/how-to-submit-0-if-checkbox-is-unchecked-and-submit-1-if-checkbox-is-checked-in
        add_settings_field(
            $this->plugin_name . '-status-hidden',
            apply_filters( $this->plugin_name . 'label-status', esc_html__( '', 'adunblocker' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name . '-general-settings-page',
            $this->plugin_name . '-options',
            array(
                'id'                => $this->plugin_name . '-status-hidden',
                'name'              => 'adunblocker-options[' . $this->plugin_name . '-status]' ,
                'type'              => 'hidden',
                'value'             => 'n'
            )
        );

        add_settings_field(
            $this->plugin_name . '-status',
            apply_filters( $this->plugin_name . 'label-status', esc_html__( 'Status:', 'adunblocker' ) ),
            array( $this, 'field_checkbox' ),
            $this->plugin_name . '-general-settings-page',
            $this->plugin_name . '-options',
            array(
                'id'                => $this->plugin_name . '-status',
                'description'       => 'Check to activate',
                'value'             => 'y'
            )
        );

        add_settings_field(
            $this->plugin_name . '-type',
            apply_filters( $this->plugin_name . 'label-type', esc_html__( 'Type:', 'adunblocker' ) ),
            array( $this, 'field_select' ),
            $this->plugin_name . '-general-settings-page',
            $this->plugin_name . '-options',
            array(
                'description'       => 'Strict: popup cannot be dismissed, Temporary: popup disappears after timer runs out, Dismissible: popup can be dismissed with a click.',
                'id'                => $this->plugin_name . '-type',
                'selections'        => array(
                                            array(
                                                    'value'     => 'strict',
                                                    'label'     => 'Strict'
                                                ),
                                            array(
                                                    'value'     => 'temp',
                                                    'label'     => 'Temporary (Pro Version)',
                                                    'attribute' => 'disabled'
                                                ),
                                            array(
                                                    'value'     => 'dismissible',
                                                    'label'     => 'Dismissible (Pro Version)',
                                                    'attribute' => 'disabled'
                                                )
                                        )
            )
        );

        add_settings_field(
            $this->plugin_name . '-scope',
            apply_filters( $this->plugin_name . 'label-scope', esc_html__( 'Scope:', 'adunblocker' ) ),
            array( $this, 'field_select' ),
            $this->plugin_name . '-general-settings-page',
            $this->plugin_name . '-options',
            array(
                'description'       => 'Page: Show popup on each page load. Session: Show popup once.',
                'id'                => $this->plugin_name . '-scope',
                'selections'        => array(
                                            array(
                                                    'value'     => 'page',
                                                    'label'     => 'Page'
                                                ),
                                            array(
                                                    'value'     => 'session',
                                                    'label'     => 'Session'
                                                )
                                        )
            )
        );

        add_settings_field(
            $this->plugin_name . '-delay',
            apply_filters( $this->plugin_name . 'label-delay', esc_html__( 'Delay', 'adunblocker' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name . '-general-settings-page',
            $this->plugin_name . '-options',
            array(
                'id'                => $this->plugin_name . '-delay',
                'description'       => 'Popup disappears after timer runs out in (ms)'
            )
        );

        add_settings_field(
            $this->plugin_name . '-title',
            apply_filters( $this->plugin_name . 'label-title', esc_html__( 'Title', 'adunblocker' ) ),
            array( $this, 'field_text' ),
            $this->plugin_name . '-content-settings-page',
            $this->plugin_name . '-content',
            array(
                'id'                => $this->plugin_name . '-title',
                'description'       => 'The title for the popup. (Available in Pro Version)',
                'value'             => 'Adblock Detected',
                'attribute'         => 'disabled'
            )
        );

        add_settings_field(
            $this->plugin_name . '-content',
            apply_filters( $this->plugin_name . 'label-content', esc_html__( 'Content', 'adunblocker' ) ),
            array( $this, 'field_editor' ),
            $this->plugin_name . '-content-settings-page',
            $this->plugin_name . '-content',
            array(
                'id'                => $this->plugin_name . '-content',
                'description'       => 'The content for the popup. (Available in Pro Version)',
                'value'             => 'Our website is made possible by displaying online advertisements to our visitors. Please consider supporting us by whitelisting our website.'
            )
        );

        add_settings_field(
            $this->plugin_name . '-overlay-color',
            apply_filters( $this->plugin_name . 'label-overlay-color', esc_html__( 'Overlay', 'adunblocker' ) ),
            array( $this, 'color_picker' ),
            $this->plugin_name . '-style-settings-page',
            $this->plugin_name . '-style',
            array(
                'id'                => $this->plugin_name . '-overlay-color',
                'class'             => $this->plugin_name . '-color-picker',
            )
        );

        add_settings_field(
            $this->plugin_name . '-bg-color',
            apply_filters( $this->plugin_name . 'label-bg-color', esc_html__( 'Background', 'adunblocker' ) ),
            array( $this, 'color_picker' ),
            $this->plugin_name . '-style-settings-page',
            $this->plugin_name . '-style',
            array(
                'id'                => $this->plugin_name . '-bg-color',
                'class'             => $this->plugin_name . '-color-picker',
                'value'             => '#FFFFFF'
            )
        );

        add_settings_field(
            $this->plugin_name . '-title-color',
            apply_filters( $this->plugin_name . 'label-title-color', esc_html__( 'Title', 'adunblocker' ) ),
            array( $this, 'color_picker' ),
            $this->plugin_name . '-style-settings-page',
            $this->plugin_name . '-style',
            array(
                'id'                => $this->plugin_name . '-title-color',
                'class'             => $this->plugin_name . '-color-picker'
            )
        );

        add_settings_field(
            $this->plugin_name . '-text-color',
            apply_filters( $this->plugin_name . 'label-text-color', esc_html__( 'Text', 'adunblocker' ) ),
            array( $this, 'color_picker' ),
            $this->plugin_name . '-style-settings-page',
            $this->plugin_name . '-style',
            array(
                'id'                => $this->plugin_name . '-text-color',
                'class'             => $this->plugin_name . '-color-picker'
            )
        );

        add_settings_field(
            $this->plugin_name . '-file-name',
            '',
            array( $this, 'field_text' ),
            $this->plugin_name . '-settings',
            $this->plugin_name . '-options',
            array(
                'id'                => $this->plugin_name . '-file-name',
                'type'              => 'hidden',
                'class'             => 'hidden'
            )
        );

    } // register_fields()

    /**
     * Registers plugin settings
     *
     * @since           1.0.0
     * @return          void
     */
    public function register_settings() {

        // register_setting( $option_group, $option_name, $sanitize_callback );

        register_setting(
            $this->plugin_name . '-general-settings-group',
            $this->plugin_name . '-options',
            array( $this , 'validate_options')
        );

        register_setting(
            $this->plugin_name . '-content-settings-group',
            $this->plugin_name . '-options',
            array( $this , 'validate_options')
        );

        register_setting(
            $this->plugin_name . '-style-settings-group',
            $this->plugin_name . '-options',
            array( $this , 'validate_options')
        );

    } // register_settings()

    /**
     * Creates a checkbox field
     *
     * @param           array       $args       The arguments for the field
     * @return          string                  The HTML field
     */
    public function field_checkbox( $args ) {

        $defaults['class']          = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['default_value']  = 'y';
        apply_filters( $this->plugin_name . '-field-checkbox-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-checkbox.php' );

    } // field_checkbox()

    /**
     * Creates a text field
     *
     * @param           array       $args       The arguments for the field
     * @return          string                  The HTML field
     */
    public function field_text( $args ) {

        $defaults['class']          = 'text widefat';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder']    = '';
        $defaults['type']           = 'text';
        $defaults['value']          = '';
        $defaults['attribute']      = '';

        apply_filters( $this->plugin_name . '-field-text-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-text.php' );

    } // field_text()

    /**
     * Creates a text field
     *
     * @param           array       $args       The arguments for the field
     * @return          string                  The HTML field
     */
    public function color_picker( $args ) {

        $defaults['class']          = 'text widefat';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['placeholder']    = '';
        $defaults['type']           = 'text';
        $defaults['value']          = '';

        apply_filters( $this->plugin_name . '-color-picker-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-color-picker.php' );

    } // field_text()

    /**
     * Creates a select field
     *
     * Note: label is blank since its created in the Settings API
     *
     * @param           array       $args       The arguments for the field
     * @return          string                  The HTML field
     */
    public function field_select( $args ) {

        $defaults['aria']           = '';
        $defaults['blank']          = '';
        $defaults['class']          = 'widefat';
        $defaults['context']        = '';
        $defaults['description']    = '';
        $defaults['label']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        $defaults['selections']     = array();
        $defaults['value']          = '';

        apply_filters( $this->plugin_name . '-field-select-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        if ( empty( $atts['aria'] ) && ! empty( $atts['description'] ) ) {

            $atts['aria'] = $atts['description'];

        } elseif ( empty( $atts['aria'] ) && ! empty( $atts['label'] ) ) {

            $atts['aria'] = $atts['label'];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-select.php' );

    } // field_select()

    /**
     * Creates an editor field
     *
     * NOTE: ID must only be lowercase letter, no spaces, dashes, or underscores.
     *
     * @param           array       $args       The arguments for the field
     * @return          string                  The HTML field
     */
    public function field_editor( $args ) {

        $defaults['description']    = '';
        $defaults['settings']       = array( 'textarea_name' => $this->plugin_name . '-options[' . $args['id'] . ']' );
        $defaults['value']          = '';
        $defaults['name']           = $this->plugin_name . '-options[' . $args['id'] . ']';
        apply_filters( $this->plugin_name . '-field-editor-options-defaults', $defaults );
        $atts = wp_parse_args( $args, $defaults );

        if ( ! empty( $this->options[$atts['id']] ) ) {

            $atts['value'] = $this->options[$atts['id']];

        }

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-field-editor.php' );

    } // field_editor()

    /**
     * Creates a settings section
     *
     * @since           1.0.0
     * @param           array       $params     Array of parameters for the section
     * @return          mixed                   The settings section
     */
    public function section_options( $params ) {

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-section-options.php' );

    } // section_options()

    /**
     * Creates a settings section
     *
     * @since           1.0.0
     * @param           array       $params     Array of parameters for the section
     * @return          mixed                   The settings section
     */
    public function section_content( $params ) {

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-section-content.php' );

    } // section_content()

    /**
     * Creates a settings section
     *
     * @since           1.0.0
     * @param           array       $params     Array of parameters for the section
     * @return          mixed                   The settings section
     */
    public function section_style( $params ) {

        include( plugin_dir_path( __FILE__ ) . 'partials/' . $this->plugin_name . '-admin-section-style.php' );

    } // section_content()

    private function sanitizer( $type, $data ) {

        if ( empty( $type ) ) { return; }
        // if ( empty( $data ) ) { return; }

        $return = '';

        $sanitizer = new AdUnblocker_Sanitize( $this->plugin_name );
        $sanitizer->set_data( $data );
        $sanitizer->set_type( $type );
        $return = $sanitizer->clean();

        unset( $sanitizer );

        return $return;

    } // sanitizer()

    /**
     * Returns an array of options names, fields types, and default values
     *
     * @return          array             An array of options
     */
    public function get_options_list() {

        $options = array();
        $options[] = array( $this->plugin_name . '-status', 'checkbox' );
        $options[] = array( $this->plugin_name . '-type', 'text' );
        $options[] = array( $this->plugin_name . '-delay', 'text' );
        $options[] = array( $this->plugin_name . '-title', 'text' );
        $options[] = array( $this->plugin_name . '-content', 'editor' );
        $options[] = array( $this->plugin_name . '-overlay-color', 'text' );
        $options[] = array( $this->plugin_name . '-bg-color', 'color' );
        $options[] = array( $this->plugin_name . '-title-color', 'color' );
        $options[] = array( $this->plugin_name . '-text-color', 'color' );
        $options[] = array( $this->plugin_name . '-file-name', 'file' );
        $options[] = array( $this->plugin_name . '-scope', 'text' );

        return $options;

    } // get_options_list()

    /**
     * Validates saved options
     *
     * @since   1.0.0
     * @param   array       $input      array of submitted plugin options
     * @return  array       array of validated plugin options
     */
    public function validate_options( $input ) {

        if ( null == $input ) {
            add_settings_error(
                'requiredTextFieldEmpty',
                'empty',
                'Cannot be empty',
                'error'
            );
        }

        $valid          = array();
        $options        = $this->get_options_list();
        $settings       = $this->options;

        foreach ( $options as $option ) {

            $name = $option[0]; // adunblocker-status
            $type = $option[1]; // text


            $valid[$name] = $this->sanitizer( $type, $input[$name] );

            if( empty( $valid[$name] ) ) {
                $valid[$name] = $settings[$name];
            }

        }

        return $valid;

    } // validate_options()

    /**
     * @param mixed $return Value to be returned as response.
     *
     * @return null
     */
    function end_ajax( $return = false ) {
        $return = apply_filters( 'daau_before_response', $return );

        if ( defined( 'DOING_daau_TESTS' ) || $this->doing_cli_migration ) {
            // This function should signal the end of the PHP process, but for CLI it carries on so we need to reset our own usage
            // of the daau_before_response filter before another respond_to_* function adds it again.
            remove_filter( 'daau_before_response', array( $this, 'scramble' ) );

            return ( false === $return ) ? null : $return;
        }

        echo ( false === $return ) ? '' : $return;
        exit;
    }

    function check_ajax_referer( $action ) {

        $result = check_ajax_referer( $action, 'nonce', false );

        if ( false === $result ) {
            $return = array( 'daau_error' => 1, 'body' => sprintf( __( 'Invalid nonce for: %s', 'adunblocker' ), $action ) );
            $this->end_ajax( json_encode( $return ) );
        }

        $cap = ( is_multisite() ) ? 'manage_network_options' : 'export';
        $cap = apply_filters( 'daau_ajax_cap', $cap );

        if ( ! current_user_can( $cap ) ) {
            $return = array( 'daau_error' => 1, 'body' => sprintf( __( 'Access denied for: %s', 'adunblocker' ), $action ) );
            $this->end_ajax( json_encode( $return ) );
        }
    }

    /**
     * Loads the error log into the error log class property.
     */
    function load_error_log() {
        if ( ! is_null( $this->error_log ) ) {
            return;
        }

        $this->error_log = get_site_option( 'daau_error_log' );

        /*
         * The error log was previously stored and retrieved using get_option and update_option respectively.
         * Here we update the subsite option to a network wide option if applicable.
         */
        if ( false === $this->error_log && is_multisite() && is_network_admin() ) {
            $this->error_log = get_option( $this->plugin_name . '_error_log' );
            if ( false !== $this->error_log ) {
                update_site_option( $this->plugin_name . '_error_log', $this->error_log );
                delete_option( $this->plugin_name . '_error_log' );
            }
        }
    }

    function ajax_get_log() {

        $this->check_ajax_referer( 'get-log' );
        ob_start();
        $this->output_diagnostic_info();
        // $this->output_log_file();
        $return = ob_get_clean();
        $result = $this->end_ajax( $return );

    }

    function output_log_file() {
        $this->load_error_log();
        if ( isset( $this->error_log ) ) {
            echo $this->error_log;
        }
    }

    /**
     * Outputs diagnostic info for debugging.
     *
     * Outputs useful diagnostic info text at the Diagnostic Info & Error Log
     * section under the Help tab so the information can be viewed or
     * downloaded and shared for debugging.
     *
     * If you would like to add additional diagnostic information use the
     * `daau_diagnostic_info` action hook (see {@link https://developer.wordpress.org/reference/functions/add_action/}).
     *
     * <code>
     * add_action( 'daau_diagnostic_info', 'my_diagnostic_info' ) {
     *     echo "Additional Diagnostic Info: \r\n";
     *     echo "...\r\n";
     * }
     * </code>
     *
     * @return void
     */
    function output_diagnostic_info() {
        global $wpdb;
        $table_prefix = $wpdb->base_prefix;

        echo 'site_url(): ';
        echo esc_html( site_url() );
        echo "\r\n";

        echo 'home_url(): ';
        echo esc_html( home_url() );
        echo "\r\n";

        echo 'Database Name: ';
        echo esc_html( $wpdb->dbname );
        echo "\r\n";

        echo 'Table Prefix: ';
        echo esc_html( $table_prefix );
        echo "\r\n";

        echo 'WordPress: ';
        echo bloginfo( 'version' );
        if ( is_multisite() ) {
            $multisite_type = defined( 'SUBDOMAIN_INSTALL' ) && SUBDOMAIN_INSTALL ? 'Sub-domain' : 'Sub-directory';
            echo ' Multisite (' . $multisite_type . ')';
            echo "\r\n";

            if ( defined( 'DOMAIN_CURRENT_SITE' ) ) {
                echo 'Domain Current Site: ';
                echo DOMAIN_CURRENT_SITE;
                echo "\r\n";
            }

            if ( defined( 'PATH_CURRENT_SITE' ) ) {
                echo 'Path Current Site: ';
                echo PATH_CURRENT_SITE;
                echo "\r\n";
            }

            if ( defined( 'SITE_ID_CURRENT_SITE' ) ) {
                echo 'Site ID Current Site: ';
                echo SITE_ID_CURRENT_SITE;
                echo "\r\n";
            }

            if ( defined( 'BLOG_ID_CURRENT_SITE' ) ) {
                echo 'Blog ID Current Site: ';
                echo BLOG_ID_CURRENT_SITE;
            }
        }
        echo "\r\n";

        echo 'Web Server: ';
        echo esc_html( ! empty( $_SERVER['SERVER_SOFTWARE'] ) ? $_SERVER['SERVER_SOFTWARE'] : '' );
        echo "\r\n";

        echo 'PHP: ';
        if ( function_exists( 'phpversion' ) ) {
            echo esc_html( phpversion() );
        }
        echo "\r\n";

        echo 'MySQL: ';
        echo esc_html( empty( $wpdb->use_mysqli ) ? mysql_get_server_info() : mysqli_get_server_info( $wpdb->dbh ) );
        echo "\r\n";

        echo 'ext/mysqli: ';
        echo empty( $wpdb->use_mysqli ) ? 'no' : 'yes';
        echo "\r\n";

        echo 'WP Memory Limit: ';
        echo esc_html( WP_MEMORY_LIMIT );
        echo "\r\n";

        echo 'Blocked External HTTP Requests: ';
        if ( ! defined( 'WP_HTTP_BLOCK_EXTERNAL' ) || ! WP_HTTP_BLOCK_EXTERNAL ) {
            echo 'None';
        } else {
            $accessible_hosts = ( defined( 'WP_ACCESSIBLE_HOSTS' ) ) ? WP_ACCESSIBLE_HOSTS : '';

            if ( empty( $accessible_hosts ) ) {
                echo 'ALL';
            } else {
                echo 'Partially (Accessible Hosts: ' . esc_html( $accessible_hosts ) . ')';
            }
        }
        echo "\r\n";

        echo 'WP Locale: ';
        echo esc_html( get_locale() );
        echo "\r\n";

        echo 'DB Charset: ';
        echo esc_html( DB_CHARSET );
        echo "\r\n";

        if ( function_exists( 'ini_get' ) && $suhosin_limit = ini_get( 'suhosin.post.max_value_length' ) ) {
            echo 'Suhosin Post Max Value Length: ';
            echo esc_html( is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit );
            echo "\r\n";
        }

        if ( function_exists( 'ini_get' ) && $suhosin_limit = ini_get( 'suhosin.request.max_value_length' ) ) {
            echo 'Suhosin Request Max Value Length: ';
            echo esc_html( is_numeric( $suhosin_limit ) ? size_format( $suhosin_limit ) : $suhosin_limit );
            echo "\r\n";
        }

        echo 'Debug Mode: ';
        echo esc_html( ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? 'Yes' : 'No' );
        echo "\r\n";

        echo 'WP Max Upload Size: ';
        echo esc_html( size_format( wp_max_upload_size() ) );
        echo "\r\n";

        echo 'PHP Post Max Size: ';
        echo esc_html( size_format( $this->get_post_max_size() ) );
        echo "\r\n";

        echo 'PHP Time Limit: ';
        if ( function_exists( 'ini_get' ) ) {
            echo esc_html( ini_get( 'max_execution_time' ) );
        }
        echo "\r\n";

        echo 'PHP Error Log: ';
        if ( function_exists( 'ini_get' ) ) {
            echo esc_html( ini_get( 'error_log' ) );
        }
        echo "\r\n";

        echo 'fsockopen: ';
        if ( function_exists( 'fsockopen' ) ) {
            echo 'Enabled';
        } else {
            echo 'Disabled';
        }
        echo "\r\n";

        echo 'OpenSSL: ';
        if ( $this->open_ssl_enabled() ) {
            echo esc_html( OPENSSL_VERSION_TEXT );
        } else {
            echo 'Disabled';
        }
        echo "\r\n";

        echo 'cURL: ';
        if ( function_exists( 'curl_init' ) ) {
            echo 'Enabled';
        } else {
            echo 'Disabled';
        }
        echo "\r\n";


        do_action( 'daau_diagnostic_info' );
        if ( has_action( 'daau_diagnostic_info' ) ) {
            echo "\r\n";
        }

        $theme_info = wp_get_theme();
        echo "Active Theme Name: " . esc_html( $theme_info->Name ) . "\r\n";
        echo "Active Theme Folder: " . esc_html( basename( $theme_info->get_stylesheet_directory() ) ) . "\r\n";
        if ( $theme_info->get( 'Template' ) ) {
            echo "Parent Theme Folder: " . esc_html( $theme_info->get( 'Template' ) ) . "\r\n";
        }
        if ( ! file_exists( $theme_info->get_stylesheet_directory() ) ) {
            echo "WARNING: Active Theme Folder Not Found\r\n";
        }

        echo "\r\n";

        echo "Active Plugins:\r\n";

        $active_plugins = (array) get_option( 'active_plugins', array() );

        if ( is_multisite() ) {
            $network_active_plugins = wp_get_active_network_plugins();
            $active_plugins         = array_map( array( $this, 'remove_wp_plugin_dir' ), $network_active_plugins );
        }

        foreach ( $active_plugins as $plugin ) {
            $suffix = ( isset( $blacklist[ $plugin ] ) ) ? '*' : '';
            $this->print_plugin_details( WP_PLUGIN_DIR . '/' . $plugin, $suffix );
        }

        $mu_plugins = wp_get_mu_plugins();
        if ( $mu_plugins ) {
            echo "\r\n";

            echo "Must-use Plugins:\r\n";

            foreach ( $mu_plugins as $mu_plugin ) {
                $this->print_plugin_details( $mu_plugin );
            }

            echo "\r\n";
        }
    }

    function open_ssl_enabled() {
        if ( defined( 'OPENSSL_VERSION_TEXT' ) ) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Returns the php ini value for post_max_size in bytes
     *
     * @return int
     */
    function get_post_max_size() {
        $bytes = max( wp_convert_hr_to_bytes( trim( ini_get( 'post_max_size' ) ) ), wp_convert_hr_to_bytes( trim( ini_get( 'hhvm.server.max_post_size' ) ) ) );

        return $bytes;
    }

    function print_plugin_details( $plugin_path, $suffix = '' ) {
        $plugin_data = get_plugin_data( $plugin_path );
        if ( empty( $plugin_data['Name'] ) ) {
            return;
        }

        printf( "%s%s (v%s) by %s\r\n", $plugin_data['Name'], $suffix, $plugin_data['Version'], $plugin_data['AuthorName'] );
    }

    /**
     * Check for daabd-download-log and related nonce
     * if found begin diagnostic logging
     *
     * @return void
     */
    function http_prepare_download_log() {
        if ( isset( $_GET['daabd-download-log'] ) && wp_verify_nonce( $_GET['nonce'], 'daabd-download-log' ) ) {
            ob_start();
            $this->output_diagnostic_info();
            $this->output_log_file();
            $log      = ob_get_clean();
            $url      = $this->parse_url( home_url() );
            $host     = sanitize_file_name( $url['host'] );
            $filename = sprintf( '%s-diagnostic-log-%s.txt', $host, date( 'YmdHis' ) );
            header( 'Content-Description: File Transfer' );
            header( 'Content-Type: application/octet-stream' );
            header( 'Content-Length: ' . strlen( $log ) );
            header( 'Content-Disposition: attachment; filename=' . $filename );
            echo $log;
            exit;
        }
    }

    /**
     * Parses a url into its components. Compatible with PHP < 5.4.7.
     *
     * @param $url string The url to parse.
     *
     * @return array|false The parsed components or false on error.
     */
    function parse_url( $url ) {
        $url = trim( $url );
        if ( 0 === strpos( $url, '//' ) ) {
            $url       = 'http:' . $url;
            $no_scheme = true;
        } else {
            $no_scheme = false;
        }

        $parts = parse_url( $url );
        if ( $no_scheme ) {
            unset( $parts['scheme'] );
        }

        return $parts;
    }

    /**
     * Displays admin notices
     *
     * @return  string          Admin notices
     */
    public function display_admin_notices() {
        settings_errors();
    } // display_admin_notices()

    public function get_current_tab() {
        $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general_settings';
        return $active_tab;
    }

    /**
	 * Hides the review notice.
	 *
	 * @since 1.0.6
	 */
	public function hide_review_notice() {
		update_option( $this->plugin_name . '-review-notify', 'yes' );
		echo json_encode( array( "success" ) );
		exit;
	}

    /**
	 * Generating the review notice.
	 *
	 * @since 1.0.6
	 */
	public static function review_notice() {
        
        $install_date = get_option( 'adunblocker-install-date' );
        $display_date = date( 'Y-m-d h:i:s' );
        $datetime1 = new DateTime( $install_date );
        $datetime2 = new DateTime( $display_date );
        $diff_intrval = round( ($datetime2->format( 'U' ) - $datetime1->format( 'U' )) / (60 * 60 * 24) );
		if ( $diff_intrval >= 14 && get_option( 'adunblocker-review-notify' ) == "no" ) {
			?>
            <div class="adunblocker-review-notice notice notice-info">
                <p style="font-size: 14px;">
					<?php _e( 'Hey,<br> I noticed that you have been using <strong>AdUnblocker</strong> for a while now - that’s awesome! Could you please do me a BIG favor and <b>give it a 5-star rating on WordPress</b>? Just to help us spread the word and boost our motivation. <br>~ Andrey from Digital Apps.', 'adunblocker' ); ?>
                </p>
                <ul>
                    <li>
                        <a style="margin-right: 5px; margin-bottom: 5px;" class="button-primary"
                           href="https://wordpress.org/support/plugin/adunblocker/reviews/?filter=5#new-post"
                           target="_blank">Sure, you deserve it.</a>
                        <a style="margin-right: 5px;" class="adunblocker-hide-review-notice button" href="javascript:void(0);">I already did.</a>
                        <a class="adunblocker-hide-review-notice button" href="javascript:void(0);">No, not good enough.</a>
                    </li>
                </ul>
            </div>
            <script>
                jQuery(document).ready(function ($) {
                    jQuery('.adunblocker-hide-review-notice').click(function () {
                        var data = {'action': 'adunblocker_hide_review_notice'};
                        jQuery.ajax({
                            url: "<?php echo admin_url( 'admin-ajax.php' ); ?>",
                            type: "post",
                            data: data,
                            dataType: "json",
                            async: !0,
                            success: function (notice_hide) {
                                if (notice_hide == "success") {
                                    jQuery('.adunblocker-review-notice').slideUp('fast');
                                }
                            }
                        });
                    });
                });
            </script>
			<?php
		}
    }

    /**
     * Go Pro.
     * Redirect to the Pro purchase page.
     * 
     * @since 1.1.2
     */
    public function go_pro_redirect() {
        // phpcs:ignore
        if ( ! isset( $_GET['page'] ) || empty( $_GET['page'] ) ) {
            return;
        }

        // phpcs:ignore
        if ( 'adunblocker_go_pro' === $_GET['page'] ) {
            // phpcs:ignore
            wp_redirect( $this->go_pro_link() );
            exit();
        }
    }
    
    /**
     * Add Go Pro link to plugins page.
     * 
     * @param Array $links - available links.
     *
     * @return array
     * 
     * @since 1.1.2
     */
    public function add_go_pro_link_plugins_page( $links ) {
        return array_merge(
            $links,
            array(
                '<a target="_blank" href="admin.php?page=adunblocker_go_pro">' . esc_html__( 'Go Pro', 'adunblocker' ) . '</a>',
            )
        );
    }

    /**
     * Get Go Pro link
     * 
     * @return string
     * 
     * @since 1.1.2
     */
    public function go_pro_link() {
        return 'https://digitalapps.com/adunblocker-pro/?utm_source=gopro&utm_medium=web&utm_content=link&utm_campaign=freeplugin';
    }

    /**
     * Runs only when the plugin is activated.
     * 
     * @since 1.1.2
     */
    function admin_notice_activation_hook() {
    
        /* Check transient, if available display notice */
        if( get_transient( 'daau-activation-admin-notice' ) ){
            ?>
            <div class="updated notice is-dismissible">
                <p>Thank you for choosing AdUnblocker :)<br> By default the plugin is <strong>NOT active</strong>.<br> Adjust your settings and activate the plugin on the <a href="admin.php?page=adunblocker-general-settings-page">AdUnblocker Settings Page</a>.</p>
            </div>
            <?php
            /* Delete transient, only display this notice once. */
            delete_transient( 'daau-activation-admin-notice' );
        }
    }

}
