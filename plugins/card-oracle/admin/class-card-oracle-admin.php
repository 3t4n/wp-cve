<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://chillichalli.com
 * @since      1.1.3
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin
 */
use  MailChimp\MailChimp as mailchimp ;
/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin
 * @author     Christopher Graham <support@chillichalli.com>
 */
class CardOracleAdmin
{
    /**
     * The ID of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private  $plugin_name ;
    /**
     * The version of this plugin.
     *
     * @since    0.5.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private  $version ;
    /**
     * Initialize the class and set its properties.
     *
     * @since 0.26.0
     * @param string $plugin_name The name of this plugin.
     * @param string $version The version of this plugin.
     */
    public function __construct( $plugin_name, $version )
    {
        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }
    
    /**
     * Add an options page under the Card Oracle menu
     *
     * @since 0.5.0
     * @return void
     */
    public function add_card_oracle_options_page()
    {
        $this->plugin_screen_hook_suffix = add_options_page(
            esc_html__( 'Card Oracle Settings', 'card-oracle' ),
            esc_html__( 'Card Oracle', 'card-oracle' ),
            'manage_options',
            $this->plugin_name,
            array( $this, 'display_card_oracle_options_page' )
        );
    }
    
    /**
     * Convert html characters in to plain text characters.
     *
     * @since  0.16.0
     * @param string $text Text to convert.
     * @return string Converted text.
     */
    public function card_oracle_html2text( $text )
    {
        $rules = array(
            '@<script[^>]*?>.*?</script>@si',
            '@<[\\/\\!]*?[^<>]*?>@si',
            '@([\\r\\n])[\\s]+@',
            '@&(quot|#34);@i',
            '@&(amp|#38);@i',
            '@&(lt|#60);@i',
            '@&(gt|#62);@i',
            '@&(nbsp|#160);@i',
            '@&(iexcl|#161);@i',
            '@&(cent|#162);@i',
            '@&(pound|#163);@i',
            '@&(copy|#169);@i',
            '@&(reg|#174);@i',
            '@&#(d+);@e'
        );
        $replace = array(
            '',
            '',
            '',
            '',
            '&',
            '<',
            '>',
            ' ',
            chr( 161 ),
            chr( 162 ),
            chr( 163 ),
            chr( 169 ),
            chr( 174 ),
            'chr()'
        );
        return preg_replace( $rules, $replace, $text );
    }
    
    /**
     * Get the total counts of a cpt
     *
     * @since 1.1.3
     * @param string $card_oracle_cpt The name of the custom post type.
     * @return int The count of custom post types.
     */
    private function get_card_oracle_cpt_count( $card_oracle_cpt )
    {
        return wp_count_posts( $card_oracle_cpt )->publish;
    }
    
    /**
     * Render the options page for plugin
     *
     * @since 1.1.1
     * @return void
     */
    public function display_card_oracle_options_page()
    {
        global  $wpdb ;
        $reading_array = array();
        $screen = get_current_screen();
        $active_tab = ( !empty($_REQUEST['tab']) ? sanitize_title( wp_unslash( $_REQUEST['tab'] ) ) : 'dashboard' );
        // phpcs:ignore WordPress.Security.NonceVerification
        $tabs = array(
            array(
            'uid'      => 'dashboard',
            'name'     => esc_html__( 'Dashboard', 'card-oracle' ),
            'htmlfile' => 'partials/card-oracle-tab-dashboard.php',
            'order'    => 10,
        ),
            array(
            'uid'      => 'general',
            'name'     => esc_html__( 'General', 'card-oracle' ),
            'htmlfile' => 'partials/card-oracle-tab-general.php',
            'order'    => 20,
        ),
            array(
            'uid'      => 'wizard',
            'name'     => esc_html__( 'Wizard', 'card-oracle' ),
            'htmlfile' => 'partials/card-oracle-tab-wizard.php',
            'order'    => 60,
        ),
            array(
            'uid'      => 'status',
            'name'     => esc_html__( 'Status', 'card-oracle' ),
            'htmlfile' => 'partials/card-oracle-tab-status.php',
            'order'    => 70,
        )
        );
        $keys = array_column( $tabs, 'order' );
        array_multisort( $keys, SORT_ASC, $tabs );
        $readings_count_i18n = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_readings' ) );
        /* translators: %d is a number */
        $readings_text = sprintf( _n(
            '%d Total',
            '%d Total',
            $readings_count_i18n,
            'card-oracle'
        ), $readings_count_i18n );
        $cards_count_i18n = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_cards' ) );
        /* translators: %d is a number */
        $cards_text = sprintf( _n(
            '%d Total',
            '%d Total',
            $cards_count_i18n,
            'card-oracle'
        ), $cards_count_i18n );
        $positions_count_i18n = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_positions' ) );
        /* translators: %d is a number */
        $positions_text = sprintf( _n(
            '%d Total',
            '%d Total',
            $positions_count_i18n,
            'card-oracle'
        ), $positions_count_i18n );
        $descriptions_count_i18n = number_format_i18n( $this->get_card_oracle_cpt_count( 'co_descriptions' ) );
        /* translators: %d is a number */
        $descriptions_text = sprintf( _n(
            '%d Total',
            '%d Total',
            $descriptions_count_i18n,
            'card-oracle'
        ), $descriptions_count_i18n );
        $readings = get_card_oracle_posts_by_cpt( 'co_readings', 'post_title' );
        $readings_count = count( $readings );
        for ( $i = 0 ;  $i < $readings_count ;  $i++ ) {
            $meta_data = array(
                'key'     => CO_READING_ID,
                'value'   => $readings[$i]->ID,
                'compare' => 'LIKE',
            );
            $reading_array[$i] = new stdClass();
            $reading_array[$i]->positions = count( get_card_oracle_posts_by_cpt(
                'co_positions',
                null,
                null,
                $meta_data
            ) );
            $reading_array[$i]->cards = count( get_card_oracle_posts_by_cpt(
                'co_cards',
                'title',
                'ASC',
                $meta_data
            ) );
            $reading_array[$i]->descriptions = $this->count_card_oracle_descriptions_by_reading( $readings[$i]->ID );
        }
        // Include the Header file.
        include_once 'partials/card-oracle-admin-header.php';
        // Display the tabs.
        echo  '<h2 class="nav-tab-wrapper">' ;
        foreach ( $tabs as $tab ) {
            $class = ( $tab['uid'] === $active_tab ? ' nav-tab-active' : '' );
            echo  '<a class="nav-tab' . esc_attr( $class ) . '" href="?page=card-oracle-admin-menu&tab=' . esc_attr( $tab['uid'] ) . '">' . esc_attr( $tab['name'] ) . '</a>' ;
        }
        echo  '</h2>' ;
        // End tab display.
        // include the html files for each of the tabs.
        foreach ( $tabs as $tab ) {
            include_once plugin_dir_path( __FILE__ ) . $tab['htmlfile'];
        }
        include_once 'partials/card-oracle-admin-footer.php';
    }
    
    /**
     * Callbacks for the admin display sections
     *
     * @since 1.1.3
     * @param array $arguments Section headings.
     * @return void
     */
    public function card_oracle_section_callback( $arguments )
    {
        switch ( $arguments['id'] ) {
            case 'general_section':
                break;
            case 'email_section':
                break;
            case 'status':
                ( new CardOracleAdminStatus() )->status_report();
                break;
            default:
                break;
        }
    }
    
    /**
     * Setup the General tab fields
     *
     * @since 1.1.3
     */
    public function card_oracle_setup_general_options()
    {
        $sections = array( array(
            'uid'   => 'general_section',
            'label' => esc_html__( 'General Settings', 'card-oracle' ),
            'page'  => 'card_oracle_option_general',
        ), array(
            'uid'   => 'email_section',
            'label' => esc_html__( 'Email Options', 'card-oracle' ),
            'page'  => 'card_oracle_option_general',
        ) );
        $fields = array(
            array(
            'uid'          => CARD_ORACLE_RANDOM_DAYS,
            'label'        => esc_html__( 'Days to display random card', 'card-oracle' ),
            'min'          => 0,
            'max'          => 365,
            'default'      => 0,
            'section'      => 'general_section',
            'type'         => 'numberbox',
            'supplemental' => esc_html__( 'Number of days to display the random card. If 0, resets every 10 minutes.', 'card-oracle' ),
        ),
            array(
            'uid'          => 'card_oracle_powered_by',
            'label'        => esc_html__( 'Allow "Powered by"', 'card-oracle' ),
            'section'      => 'general_section',
            'type'         => 'toggle_switch',
            'value'        => 'yes',
            'supplemental' => esc_html__( 'When ON "Create your own reading using Tarot Card Oracle! Go to ChilliChalli.com" is displayed in footer.', 'card-oracle' ),
        ),
            array(
            'uid'     => 'card_oracle_allow_email',
            'label'   => esc_html__( 'Allow users to send reading to an email address', 'card-oracle' ),
            'section' => 'email_section',
            'type'    => 'toggle_switch',
            'value'   => 'yes',
        ),
            array(
            'uid'          => 'card_oracle_from_email',
            'label'        => esc_html__( 'From email address', 'card-oracle' ),
            'section'      => 'email_section',
            'type'         => 'text',
            'placeholder'  => 'hello@example.com',
            'helper'       => esc_html__( 'The From email address used when the user sends the reading.', 'card-oracle' ),
            'supplemental' => esc_html__( 'If blank this defaults to the Admin email address.', 'card-oracle' ),
        ),
            array(
            'uid'          => 'card_oracle_from_email_name',
            'label'        => esc_html__( 'From email name', 'card-oracle' ),
            'section'      => 'email_section',
            'type'         => 'text',
            'placeholder'  => esc_html__( 'Tarot Card Oracle', 'card-oracle' ),
            'helper'       => esc_html__( 'The Name displayed as the From email address.', 'card-oracle' ),
            'supplemental' => esc_html__( 'If blank this defaults to the site title.', 'card-oracle' ),
        ),
            array(
            'uid'          => 'card_oracle_email_text',
            'label'        => esc_html__( 'Text to display', 'card-oracle' ),
            'section'      => 'email_section',
            'type'         => 'text',
            'placeholder'  => esc_html__( 'Email this Reading to:', 'card-oracle' ),
            'helper'       => esc_html__( 'Text to display on the email form.', 'card-oracle' ),
            'supplemental' => esc_html__( 'If blank this defaults "Email this Reading to:".', 'card-oracle' ),
        ),
            array(
            'uid'          => 'card_oracle_email_success',
            'label'        => esc_html__( 'Text to display', 'card-oracle' ),
            'section'      => 'email_section',
            'type'         => 'text',
            'placeholder'  => esc_html__( 'Text to display on successful email:', 'card-oracle' ),
            'helper'       => esc_html__( 'Text to display after the user submits the email form.', 'card-oracle' ),
            'supplemental' => esc_html__( 'If blank this defaults "Your email has been sent. Please make sure to check your spam folder."', 'card-oracle' ),
        )
        );
        $this->card_oracle_register_sections_fields( $sections, $fields, 'card_oracle_option_general' );
    }
    
    /**
     * Callback function for Card Oracle Options
     *
     * @since 1.1.3
     * @param array $arguments A list of options to display.
     */
    public function card_oracle_option_callback( $arguments )
    {
        $value = get_option( $arguments['uid'] );
        
        if ( !$value && isset( $arguments['default'] ) ) {
            $value = $arguments['default'];
            // Set to our default.
        }
        
        switch ( $arguments['type'] ) {
            case 'checkbox':
                $checked = ( 'yes' === $value ? 'checked="checked"' : '' );
                printf(
                    '<label class="card-oracle-checkbox"><input name="%1$s" id="%1$s" type="%2$s" value="%3$s" %4$s /><span class="card-oracle-slider round"></span></label>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['type'] ),
                    esc_attr( $arguments['value'] ),
                    esc_attr( $checked )
                );
                break;
            case 'text':
                printf(
                    '<input class="regular-text code" id="%1$s" name="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" />',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['type'] ),
                    esc_attr( $arguments['placeholder'] ),
                    esc_attr( $value )
                );
                break;
            case 'textarea':
                $rows = ( isset( $arguments['rows'] ) ? $arguments['rows'] : 3 );
                printf(
                    '<textarea class="large-text code" id="%1$s" name="%1$s" rows="%2$s" placeholder="%3$s">%4$s</textarea>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $rows ),
                    esc_attr( $arguments['placeholder'] ),
                    esc_html( $value )
                );
                break;
            case 'reading_list':
                echo  $this->get_reading_dropdown_box( $arguments['uid'], esc_html( get_option( 'card_oracle_reading_list' ) ) ) ;
                // phpcs:ignore
                break;
            case 'dropdown':
                // If it is a select dropdown.
                
                if ( !empty($arguments['options']) && is_array( $arguments['options'] ) ) {
                    printf( '<select name="%1$s" id="%1$s">', esc_attr( $arguments['uid'] ) );
                    foreach ( $arguments['options'] as $key => $label ) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( esc_attr( $value ), esc_attr( $key ), false ),
                            esc_textarea( $label )
                        );
                    }
                    printf( '</select>' );
                }
                
                break;
            case 'numberbox':
                printf(
                    '<div class="card-oracle-grid-content"><input class="card-oracle-metabox-number" name="%1$s" id="%1$s" type="number" min="%2$d" max="%3$d" ondrop="return false" onpaste="return false" value="%4$s" /></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['min'] ),
                    esc_attr( $arguments['max'] ),
                    esc_attr( $value )
                );
                break;
            case 'toggle_switch':
                $checked = ( 'yes' === $value ? 'checked="checked"' : '' );
                printf(
                    '<label class="card-oracle-switch">
					<input type="checkbox" name="%1$s" class="card-oracle-checkbox" id="%1$s" style="margin: 10px" value="%2$s" %3$s>
					<span class="card-oracle-toggle-thumb">%4$s %5$s</span></label>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['value'] ),
                    esc_attr( $checked ),
                    '',
                    ''
                );
                break;
            default:
                break;
        }
        // If there is help text.
        
        if ( !empty($arguments['helper']) ) {
            printf( '<span class="helper">%s</span>', esc_html( $arguments['helper'] ) );
            // Show it.
        }
        
        // If there is supplemental text.
        
        if ( !empty($arguments['supplemental']) ) {
            printf( '<p class="description">%s</p>', esc_html( $arguments['supplemental'] ) );
            // Show it.
        }
    
    }
    
    /**
     * Get all the posts of a custom post type, optional orderby and order
     *
     * @since 0.13.1
     * @param string $reading_id ID of the Reading.
     * @return array An array of all custom post_types requested.
     */
    public function count_card_oracle_descriptions_by_reading( $reading_id )
    {
        // Get the Card IDs that relate to the Reading ID.
        $args_cards = array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'co_cards',
            'meta_query'  => array(
            // @codingStandardsIgnoreLine
            array(
                'key'     => CO_READING_ID,
                'value'   => $reading_id,
                'compare' => '=',
            ),
        ),
        );
        // Get the Position IDs that relate to the Reading ID.
        $args_positions = array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'co_positions',
            'meta_query'  => array(
            // @codingStandardsIgnoreLine
            array(
                'key'     => CO_READING_ID,
                'value'   => $reading_id,
                'compare' => '=',
            ),
        ),
        );
        // Run the queries.
        $cards = get_posts( $args_cards );
        $positions = get_posts( $args_positions );
        // Get the Descriptions IDs that relate to the Card IDs.
        $args_descriptions_cards = array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'co_descriptions',
            'meta_query'  => array(
            // @codingStandardsIgnoreLine
            array(
                'key'     => CO_CARD_ID,
                'value'   => $cards,
                'compare' => 'IN',
            ),
        ),
        );
        // Get the Descriptions IDs that relate to the Position IDs.
        $args_descriptions_positions = array(
            'fields'      => 'ids',
            'numberposts' => -1,
            'post_status' => 'publish',
            'post_type'   => 'co_descriptions',
            'meta_query'  => array(
            // @codingStandardsIgnoreLine
            array(
                'key'     => CO_POSITION_ID,
                'value'   => $positions,
                'compare' => 'IN',
            ),
        ),
        );
        // Get the Descriptions IDs for the Cards and Positions.
        $desc_cards = ( empty($cards) ? array() : get_posts( $args_descriptions_cards ) );
        $desc_positions = ( empty($positions) ? array() : get_posts( $args_descriptions_positions ) );
        // Merge the two arrays, get the unique IDs and return the count.
        return count( array_unique( array_merge( $desc_cards, $desc_positions ) ) );
    }
    
    /**
     * Create our custom metabox for cards
     *
     * @since 0.13.0
     * @param string $name Name of reading.
     * @param string $selected_reading (optional) Reading to select in dropdown.
     * @return array A html dropdown element.
     */
    public function get_reading_dropdown_box( $name, $selected_reading = null )
    {
        return wp_dropdown_pages( array(
            'echo'             => 0,
            'name'             => esc_html( $name ),
            'show_option_none' => esc_html__( 'Select a Reading:', 'card-oracle' ),
            'post_type'        => 'co_readings',
            'selected'         => esc_attr( $selected_reading ),
            'sort_column'      => 'post_title',
        ) );
    }
    
    /**
     * Create our custom metabox for readings
     *
     * @since 0.13.0
     * @return void
     */
    public function add_meta_boxes_for_readings_cpt()
    {
        $screens = array( 'co_readings' );
        add_meta_box(
            'card-reading',
            esc_html__( 'Settings', 'card-oracle' ),
            array( $this, 'render_reading_metabox' ),
            $screens,
            'normal',
            'high'
        );
    }
    
    /**
     * Create our custom metabox for positions
     *
     * @since 0.13.0
     * @return void
     */
    public function add_meta_boxes_for_positions_cpt()
    {
        $screens = array( 'co_positions' );
        add_meta_box(
            'card-reading',
            esc_html__( 'Settings', 'card-oracle' ),
            array( $this, 'render_position_metabox' ),
            $screens,
            'normal',
            'high'
        );
    }
    
    /**
     * Create our custom metabox for cards
     *
     * @since 0.13.0
     * @return void
     */
    public function add_meta_boxes_for_cards_cpt()
    {
        $screens = array( 'co_cards' );
        add_meta_box(
            'card-reading',
            esc_html__( 'Settings', 'card-oracle' ),
            array( $this, 'render_card_metabox' ),
            $screens,
            'normal',
            'high'
        );
    }
    
    // add_meta_boxes_for_cards_cpt
    /**
     * Create our custom metabox for descriptions
     *
     * @since 0.13.0
     * @return void
     */
    public function add_meta_boxes_for_descriptions_cpt()
    {
        $screens = array( 'co_descriptions' );
        add_meta_box(
            'reverse',
            esc_html__( 'Reverse Card Description', 'card-oracle' ),
            array( $this, 'render_reverse_description_metabox' ),
            $screens,
            'normal',
            'high'
        );
        add_meta_box(
            'card',
            esc_html__( 'Settings', 'card-oracle' ),
            array( $this, 'render_description_metabox' ),
            $screens,
            'normal',
            'high'
        );
    }
    
    // add_meta_boxes_for_descriptions_cpt
    /**
     * Create our menu and submenus
     *
     * @since 1.1.3
     * @return void
     */
    public function card_oracle_menu_items()
    {
        // Card Oracle icon for admin menu svg.
        $co_admin_icon = 'data:image/svg+xml;base64,' . base64_encode(
            // @codingStandardsIgnoreLine
            '<svg height="100px" width="100px"  fill="black" 
				xmlns:x="http://ns.adobe.com/Extensibility/1.0/" 
				xmlns:i="http://ns.adobe.com/AdobeIllustrator/10.0/" 
				xmlns:graph="http://ns.adobe.com/Graphs/1.0/" 
				xmlns="http://www.w3.org/2000/svg" 
				xmlns:xlink="http://www.w3.org/1999/xlink" 
				version="1.1" x="0px" y="0px" viewBox="0 0 100 100" enable-background="new 0 0 100 100" 
				xml:space="preserve"><g><g i:extraneous="self">
				<circle fill="black" cx="49.926" cy="57.893" r="10.125"></circle>
				<path fill="black" d="M50,78.988c-19.872,0-35.541-16.789-36.198-17.503l-1.95-2.12l1.788-2.259c0.164-0.208,4.097-5.142,
					10.443-10.102 C32.664,40.296,41.626,36.751,50,36.751c8.374,0,17.336,3.546,25.918,10.253c6.346,4.96,10.278,9.894,
					10.443,10.102l1.788,2.259 l-1.95,2.12C85.541,62.2,69.872,78.988,50,78.988z M20.944,59.019C25.56,63.219,36.99,
					72.238,50,72.238 c13.059,0,24.457-9.013,29.061-13.214C74.565,54.226,63.054,43.501,50,43.501C36.951,43.501,25.444,
					54.218,20.944,59.019z"></path>
				<path fill="black" d="M44.305,30.939L50,21.075l5.695,9.864c3.002,0.427,6.045,1.185,9.102,2.265L50,7.575L35.203,33.204 
					C38.26,32.124,41.303,31.366,44.305,30.939z"></path>
				<path fill="black" d="M81.252,74.857L87.309,85H12.691l6.057-10.143c-2.029-1.279-3.894-2.629-5.578-3.887L1,92h98L86.83,
					70.97 C85.146,72.228,83.28,73.578,81.252,74.857z"></path>
				</g></g></svg>'
        );
        // Add the main Card Oracle admin menu.
        add_menu_page(
            esc_html__( 'Card Oracle', 'card-oracle' ),
            esc_html__( 'Card Oracle', 'card-oracle' ),
            'manage_options',
            'card-oracle-admin-menu',
            array( $this, 'display_card_oracle_options_page' ),
            $co_admin_icon,
            40
        );
        // Add the Options submenu as the default menu when you click on Card Oracle menu.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle Options', 'card-oracle' ),
            esc_html__( 'Dashboard', 'card-oracle' ),
            'manage_options',
            'card-oracle-admin-menu',
            array( $this, 'display_card_oracle_options_page' )
        );
        // Add the Readings submenu page.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle Readings Admin', 'card-oracle' ),
            esc_html__( 'Readings', 'card-oracle' ),
            'manage_options',
            'edit.php?post_type=co_readings'
        );
        // Add the Positions submenu page.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle positions Admin', 'card-oracle' ),
            esc_html__( 'Positions', 'card-oracle' ),
            'manage_options',
            'edit.php?post_type=co_positions'
        );
        // Add the Cards submenu page.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle cards Admin', 'card-oracle' ),
            esc_html__( 'Cards', 'card-oracle' ),
            'manage_options',
            'edit.php?post_type=co_cards'
        );
        // Add the Descriptions submenu page.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle Descriptions Admin', 'card-oracle' ),
            esc_html__( 'Descriptions', 'card-oracle' ),
            'manage_options',
            'edit.php?post_type=co_descriptions'
        );
        // Add the Demo Data submenu page.
        add_submenu_page(
            'card-oracle-admin-menu',
            esc_html__( 'Card Oracle Demo Data', 'card-oracle' ),
            esc_html__( 'Demo Data', 'card-oracle' ),
            'manage_options',
            'card-oracle-admin-demodata',
            array( $this, 'display_card_oracle_demodata_page' )
        );
    }
    
    /**
     * Move the featured image box for readings
     *
     * @since 0.13.0
     * @return void
     */
    public function cpt_image_box()
    {
        // Move the image metabox from the sidebar to the normal position.
        $screens = array( 'co_cards' );
        remove_meta_box( 'postimagediv', $screens, 'side' );
        add_meta_box(
            'postimagediv',
            __( 'Front of Card Image', 'card-oracle' ),
            'post_thumbnail_meta_box',
            $screens,
            'side',
            'default'
        );
        // Move the image metabox from the sidebar to the normal position.
        $screens = array( 'co_readings' );
        remove_meta_box( 'postimagediv', $screens, 'side' );
        add_meta_box(
            'postimagediv',
            __( 'Back of Card Image', 'card-oracle' ),
            'post_thumbnail_meta_box',
            $screens,
            'side',
            'default'
        );
        // Remove Astra metaboxes from our cpt.
        $screens = array(
            'co_cards',
            'co_readings',
            'co_positions',
            'co_descriptions'
        );
        // Remove Astra Settings in Posts.
        remove_meta_box( 'astra_settings_meta_box', $screens, 'side' );
        add_meta_box(
            'back-metabox',
            __( 'Previous Page', 'card-oracle' ),
            array( $this, 'render_back_button_metabox' ),
            $screens,
            'side',
            'high'
        );
    }
    
    /**
     * Display the custom admin columns for Cards
     *
     * @since 1.1.1
     * @param string $column Name of column.
     * @return void
     */
    public function custom_card_column( $column )
    {
        global  $post ;
        global  $wpdb ;
        switch ( $column ) {
            case 'card_reading':
                $readings = get_post_meta( $post->ID, CO_READING_ID, false );
                foreach ( $readings as $reading ) {
                    echo  '<p>' ;
                    echo  esc_html( get_the_title( $reading ) ) ;
                    echo  '</p>' ;
                }
                break;
            case 'card_order':
                echo  esc_html( get_post_meta( $post->ID, CO_CARD_ORDER, true ) ) ;
                break;
            case 'co_shortcode':
                $alt_tag = esc_html__( 'Copy to clipboard', 'card-oracle' );
                echo  '<input class="card-oracle-shortcode" id="copy' . esc_attr( $post->ID ) . '" value="[card-oracle id=&quot;' . esc_attr( $post->ID ) . '&quot;]"><button id="copy-action-btn" class="button" value="[card-oracle id=&quot;' . esc_attr( $post->ID ) . '&quot;]"> <img src="' . esc_url( CARD_ORACLE_CLIPPY ) . '" alt="' . esc_attr( $alt_tag ) . '"></button>' ;
                break;
            case 'description_reading':
                $position_id = get_post_meta( $post->ID, CO_POSITION_ID, false );
                $reading_id = get_post_meta( $position_id, CO_READING_ID, false );
                echo  esc_html( get_the_title( $reading_id[0] ) ) ;
                break;
            case 'number_card_descriptions':
                $meta_data = array(
                    'key'     => CO_CARD_ID,
                    'value'   => $post->ID,
                    'compare' => 'IN',
                );
                $count = count( get_card_oracle_posts_by_cpt(
                    'co_descriptions',
                    null,
                    null,
                    $meta_data
                ) );
                echo  '<p>' . esc_html( $count ) . '</p>' ;
                break;
            case 'number_reading_positions':
                $meta_data = array(
                    'key'     => CO_READING_ID,
                    'value'   => $post->ID,
                    'compare' => 'LIKE',
                );
                echo  count( get_card_oracle_posts_by_cpt(
                    'co_positions',
                    null,
                    null,
                    $meta_data
                ) ) ;
                break;
            case 'card_title':
                $card_id = get_post_meta( $post->ID, CO_CARD_ID, true );
                $card_title = get_the_title( $card_id );
                echo  '<strong><a class="row-title" href="' . esc_url( admin_url() ) . 'post.php?post=' . esc_attr( $post->ID ) . '&action=edit">' . esc_html( $card_title ) . '</a></strong>' ;
                break;
            case 'position_title':
                $position_id = get_post_meta( $post->ID, CO_POSITION_ID, false );
                foreach ( $position_id as $id ) {
                    echo  '<p>' . esc_html( get_the_title( $id ) ) . '</p>' ;
                }
                break;
            case 'position_number':
                $position_id = get_post_meta( $post->ID, CO_POSITION_ID, false );
                foreach ( $position_id as $id ) {
                    echo  '<p>' . esc_html( get_post_meta( $id, CO_CARD_ORDER, true ) ) . '</p>' ;
                }
                break;
            case 'order_id':
                break;
            case 'order_email':
                echo  esc_html( get_post_meta( $post->ID, CO_IPN_EMAIL, true ) ) ;
                break;
            case 'order_price':
                echo  esc_html( get_post_meta( $post->ID, CO_AMOUNT, true ) ) ;
                break;
            case 'order_status':
                echo  esc_html( get_post_meta( $post->ID, CO_ORDER_STATUS, true ) ) ;
                break;
            case 'order_content':
                echo  wp_kses_post( card_oracle_modal( $post->ID, esc_html( wp_kses_post( $post->post_content ) ), __( 'Copy Content', 'card-oracle' ) ) ) ;
                break;
            case 'order_txn_id':
                $transaction_id = get_post_meta( $post->ID, CO_TXN_ID_LINK, true );
                if ( $transaction_id ) {
                    echo  wp_kses_post( card_oracle_modal( $transaction_id, get_the_content( null, false, $transaction_id ), get_the_title( $transaction_id ) ) ) ;
                }
                break;
            default:
                break;
        }
    }
    
    /**
     * Register the stylesheets for the admin area.
     *
     * @since 0.27.0
     * @return void
     */
    public function enqueue_styles()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Card_Oracle_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Card_Oracle_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_enqueue_style(
            $this->plugin_name,
            CARD_ORACLE_ADMIN_CSS_URL,
            array(),
            $this->version,
            'all'
        );
    }
    
    /**
     * Register the JavaScript for the admin area.
     *
     * @since 1.1.1
     * @return void
     */
    public function enqueue_scripts()
    {
        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Card_Oracle_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Card_Oracle_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        wp_register_script(
            'card-oracle-sortable',
            plugin_dir_url( __FILE__ ) . 'js/min/card-oracle-sortable.min.js',
            array( 'jquery-ui-sortable' ),
            $this->version,
            true
        );
        wp_enqueue_script( 'jquery-ui-sortable' );
        wp_enqueue_script(
            $this->plugin_name,
            plugin_dir_url( __FILE__ ) . 'js/min/card-oracle-admin.min.js',
            array( 'jquery' ),
            $this->version,
            false
        );
        wp_enqueue_script( 'card-oracle-sortable' );
    }
    
    /**
     * Create our custom post type for readings
     *
     * @since 0.25.0
     * @return void
     */
    public function register_card_oracle_cpt()
    {
        // Register the Cards cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'               => esc_html__( 'Cards', 'card-oracle' ),
            'singular_name'      => esc_html__( 'Card', 'card-oracle' ),
            'add_new'            => esc_html__( 'Add New Card', 'card-oracle' ),
            'add_new_item'       => esc_html__( 'Add New Card', 'card-oracle' ),
            'edit_item'          => esc_html__( 'Edit Card', 'card-oracle' ),
            'new_item'           => esc_html__( 'New Card', 'card-oracle' ),
            'all_items'          => esc_html__( 'All Cards', 'card-oracle' ),
            'view_item'          => esc_html__( 'View Card', 'card-oracle' ),
            'search_items'       => esc_html__( 'Search Cards', 'card-oracle' ),
            'featured_image'     => esc_html__( 'Card Image', 'card-oracle' ),
            'set_featured_image' => esc_html__( 'Add Card Image', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our card information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-media-default',
            'menu_position'     => 42,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'supports'          => array( 'title', 'editor', 'thumbnail' ),
            'query_var'         => true,
        );
        register_post_type( 'co_cards', $args );
        // Register the Descriptions cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'          => esc_html__( 'Descriptions', 'card-oracle' ),
            'singular_name' => esc_html__( 'Description', 'card-oracle' ),
            'add_new'       => esc_html__( 'Add New Description', 'card-oracle' ),
            'add_new_item'  => esc_html__( 'Add New Description', 'card-oracle' ),
            'edit_item'     => esc_html__( 'Edit Description', 'card-oracle' ),
            'new_item'      => esc_html__( 'New Description', 'card-oracle' ),
            'all_items'     => esc_html__( 'All Descriptions', 'card-oracle' ),
            'view_item'     => esc_html__( 'View Description', 'card-oracle' ),
            'search_items'  => esc_html__( 'Search Descriptions', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our description information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-format-gallery',
            'menu_position'     => 43,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'supports'          => array( 'title', 'editor' ),
            'query_var'         => true,
        );
        register_post_type( 'co_descriptions', $args );
        // register the Orders cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'          => esc_html__( 'Orders', 'card-oracle' ),
            'singular_name' => esc_html__( 'Order', 'card-oracle' ),
            'add_new'       => esc_html__( 'Add New Order', 'card-oracle' ),
            'add_new_item'  => esc_html__( 'Add New Order', 'card-oracle' ),
            'edit_item'     => esc_html__( 'Edit Order', 'card-oracle' ),
            'new_item'      => esc_html__( 'New Order', 'card-oracle' ),
            'all_items'     => esc_html__( 'All Orders', 'card-oracle' ),
            'view_item'     => esc_html__( 'View Order', 'card-oracle' ),
            'search_items'  => esc_html__( 'Search Orders', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our orders information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-admin-page',
            'menu_position'     => 44,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'supports'          => array( '' ),
            'query_var'         => true,
            'capabilities'      => array(
            'create_posts' => 'do_not_allow',
        ),
        );
        register_post_type( 'co_order', $args );
        // Register the Positions cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'          => esc_html__( 'Positions', 'card-oracle' ),
            'singular_name' => esc_html__( 'Position', 'card-oracle' ),
            'add_new'       => esc_html__( 'Add New Position', 'card-oracle' ),
            'add_new_item'  => esc_html__( 'Add New Position', 'card-oracle' ),
            'edit_item'     => esc_html__( 'Edit Position', 'card-oracle' ),
            'new_item'      => esc_html__( 'New Position', 'card-oracle' ),
            'all_items'     => esc_html__( 'All Positions', 'card-oracle' ),
            'view_item'     => esc_html__( 'View Position', 'card-oracle' ),
            'search_items'  => esc_html__( 'Search Positions', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our position information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-format-gallery',
            'menu_position'     => 41,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => true,
            'show_in_nav_menus' => false,
            'supports'          => array( 'title' ),
            'query_var'         => true,
        );
        register_post_type( 'co_positions', $args );
        // register the Readings cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'               => esc_html__( 'Readings', 'card-oracle' ),
            'singular_name'      => esc_html__( 'Reading', 'card-oracle' ),
            'add_new'            => esc_html__( 'Add New Reading', 'card-oracle' ),
            'add_new_item'       => esc_html__( 'Add New Reading', 'card-oracle' ),
            'edit_item'          => esc_html__( 'Edit Reading', 'card-oracle' ),
            'new_item'           => esc_html__( 'New Reading', 'card-oracle' ),
            'all_items'          => esc_html__( 'All Readings', 'card-oracle' ),
            'view_item'          => esc_html__( 'View Reading', 'card-oracle' ),
            'search_items'       => esc_html__( 'Search Readings', 'card-oracle' ),
            'featured_image'     => esc_html__( 'Card Back', 'card-oracle' ),
            'set_featured_image' => esc_html__( 'Add Card Back', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our reading information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-admin-page',
            'menu_position'     => 40,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'supports'          => array( 'title', 'thumbnail' ),
            'query_var'         => true,
        );
        register_post_type( 'co_readings', $args );
        // register the Transactions cpt.
        // Set the labels for the custom post type.
        $labels = array(
            'name'          => esc_html__( 'Transactions', 'card-oracle' ),
            'singular_name' => esc_html__( 'Transaction', 'card-oracle' ),
            'add_new'       => esc_html__( 'Add New Transaction', 'card-oracle' ),
            'add_new_item'  => esc_html__( 'Add New Transaction', 'card-oracle' ),
            'edit_item'     => esc_html__( 'Edit Transaction', 'card-oracle' ),
            'new_item'      => esc_html__( 'New Transaction', 'card-oracle' ),
            'all_items'     => esc_html__( 'All Transactions', 'card-oracle' ),
            'view_item'     => esc_html__( 'View Transaction', 'card-oracle' ),
            'search_items'  => esc_html__( 'Search Transactions', 'card-oracle' ),
        );
        // Settings for our post type.
        $args = array(
            'description'       => 'Holds our transactions information',
            'has_archive'       => false,
            'hierarchical'      => true,
            'labels'            => $labels,
            'menu_icon'         => 'dashicons-admin-page',
            'menu_position'     => 44,
            'public'            => true,
            'show_in_menu'      => false,
            'show_in_admin_bar' => false,
            'show_in_nav_menus' => false,
            'supports'          => array( 'title' ),
            'query_var'         => true,
        );
        register_post_type( 'co_transaction', $args );
    }
    
    /**
     * Render the Reading Metabox for Cards CPT
     *
     * @since 0.13.0
     * @return void
     */
    public function render_back_button_metabox()
    {
        global  $post_type ;
        $posttypes = get_post_types( array(
            'name' => $post_type,
        ), 'objects' );
        foreach ( $posttypes as $posttype ) {
            $page = $posttype->labels->name;
        }
        $text = esc_html__( 'Back to ', 'card-oracle' );
        echo  '<a href="edit.php?post_type=' . esc_attr( $post_type ) . '"<button class="button button-primary button-large">' . esc_attr( $text ) . esc_attr( $page ) . '</button></a>' ;
    }
    
    /**
     * Display fields for the Metaboxes.
     *
     * @since 1.1.3
     * @param array $arguments Array of elements to display.
     */
    private function display_metabox_fields( $arguments )
    {
        global  $post ;
        if ( 'checkbox_list' !== $arguments['type'] ) {
            printf( '<div class="card-oracle-grid-label">%1$s</div>', esc_html( $arguments['label'] ) );
        }
        switch ( $arguments['type'] ) {
            case 'checkbox':
                $checked = ( $arguments['value'] === $arguments['checked'] ? 'checked="checked"' : '' );
                printf(
                    '<div class="card-oracle-grid-content"><label class="card-oracle-checkbox"><input name="%1$s" id="%1$s" type="%2$s" value="%3$s" %4$s /><span class="card-oracle-slider round"></span></label></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['type'] ),
                    esc_attr( $arguments['value'] ),
                    esc_attr( $checked )
                );
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                printf( '</td></tr>' );
                break;
            case 'checkbox_list':
                $checked = ( is_array( $arguments['checked'] ) && in_array( (string) $arguments['uid'], $arguments['checked'], true ) ? 'checked="checked"' : '' );
                printf(
                    '<div class="card-oracle-multiitem"><input id="reading%1$d" type="checkbox" name="_co_reading_id[]" value="%1$d" %2$s /><label for="reading%1$d">%3$s</label></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $checked ),
                    esc_html( $arguments['label'] )
                );
                break;
            case 'dropdown':
                // If it is a select dropdown.
                
                if ( !empty($arguments['options']) && is_array( $arguments['options'] ) ) {
                    printf( '<div class="card-oracle-grid-content"><select name="%1$s" id="%1$s">', esc_attr( $arguments['uid'] ) );
                    foreach ( $arguments['options'] as $key => $label ) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( esc_attr( $arguments['value'] ), esc_attr( $key ), false ),
                            esc_textarea( $label )
                        );
                    }
                    printf( '</select></div>' );
                }
                
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                break;
            case 'layout_dropdown':
                // If it is a select dropdown.
                $presentation_layouts = get_presentation_layouts();
                
                if ( !empty($arguments['options']) && is_array( $arguments['options'] ) ) {
                    printf( '<div class="card-oracle-grid-content"><select name="%1$s" id="%1$s">', esc_attr( $arguments['uid'] ) );
                    foreach ( $arguments['options'] as $key => $label ) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( esc_attr( $arguments['value'] ), esc_attr( $key ), false ),
                            esc_textarea( $label )
                        );
                    }
                    printf( '</select>' );
                    $modal_content = '<span class="helper"><div class="card-oracle-presentation-layout">';
                    foreach ( $presentation_layouts as $layout ) {
                        
                        if ( $layout['image'] && file_exists( $layout['file'] ) ) {
                            $modal_content .= '<div><img class="card-oracle-layout-image" src="' . $layout['image'] . '" alt="..." />';
                            $modal_content .= '<p>' . $layout['label'] . '</p></div>';
                        }
                    
                    }
                    $modal_content .= '</div></span>';
                    printf( wp_kses_post( card_oracle_modal(
                        'presentation',
                        $modal_content,
                        __( 'Examples', 'card-oracle' ),
                        'card-oracle-presentation-example'
                    ) ) );
                    printf( '</div>' );
                    if ( array_key_exists( 'supplemental', $arguments ) ) {
                        printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                    }
                }
                
                break;
            case 'question_layout_dropdown':
                // If it is a select dropdown.
                
                if ( !empty($arguments['options']) && is_array( $arguments['options'] ) ) {
                    printf( '<div class="card-oracle-grid-content"><select name="%1$s" id="%1$s">', esc_attr( $arguments['uid'] ) );
                    foreach ( $arguments['options'] as $key => $label ) {
                        printf(
                            '<option value="%s" %s>%s</option>',
                            esc_attr( $key ),
                            selected( esc_attr( $arguments['value'] ), esc_attr( $key ), false ),
                            esc_textarea( $label )
                        );
                    }
                    printf( '</select>' );
                    if ( array_key_exists( 'supplemental', $arguments ) ) {
                        printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                    }
                    printf( '</div>' );
                }
                
                // Add a new row for the layout.
                printf( '<div class="card-oracle-grid-footer"><div class="card-oracle-layout-demo">' );
                printf( '<div id="card-oracle-display-layout" class="%s">', esc_attr( $arguments['value'] ) );
                printf( '<input class="card-oracle-form-textbox" type="text" size="30" value="Question Box" disabled=""/>' );
                printf( '<div id="submitbuttondiv" class="btn-block ">' );
                printf( '<button class="card-oracle-form-button" name="readingsubmit" type="submit" id="readingsubmit" disabled="">Submit</button>' );
                printf( '</div></div></div></div>' );
                break;
            case 'numberbox':
                printf(
                    '<div class="card-oracle-grid-content"><input class="card-oracle-metabox-number" name="%1$s" id="%1$s" type="number" min="%2$d" max="%3$d" ondrop="return false" onpaste="return false" value="%4$s" /></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['min'] ),
                    esc_attr( $arguments['max'] ),
                    esc_html( $arguments['value'] )
                );
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                break;
            case 'reading_list':
                echo  $this->get_reading_dropdown_box( $arguments['uid'], esc_html( get_option( 'card_oracle_reading_list' ) ) ) ;
                // phpcs:ignore
                break;
            case 'radiobox':
                echo  '<div class="card-oracle-grid-content"><div class="card-oracle-radio-toolbar">' ;
                foreach ( $arguments['options'] as $key => $label ) {
                    $checked = ( $arguments['value'] === $key ? 'checked="checked"' : '' );
                    printf(
                        '<input type="radio" id="%1$s" name="%2$s" value="%1$s" %3$s /><label for="%1$s">%4$s</label>',
                        esc_attr( $key ),
                        esc_attr( $arguments['uid'] ),
                        esc_attr( $checked ),
                        esc_attr( $label )
                    );
                }
                echo  '</div></div>' ;
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                break;
            case 'text':
                printf(
                    '<div class="card-oracle-grid-content"><input class="regular-text code" id="%1$s" name="%1$s" type="%2$s" placeholder="%3$s" value="%4$s" /></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['type'] ),
                    esc_attr( $arguments['placeholder'] ),
                    wp_kses( $arguments['value'], array() )
                );
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                break;
            case 'textarea':
                $rows = ( isset( $arguments['rows'] ) ? $arguments['rows'] : 3 );
                printf(
                    '<div class="card-oracle-grid-content"><textarea class="large-text code" id="%1$s" name="%1$s" rows="%2$s" placeholder="%3$s">%4$s</textarea></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $rows ),
                    esc_attr( $arguments['placeholder'] ),
                    esc_html( $arguments['value'] )
                );
                break;
            case 'toggle_switch':
                $checked = ( $arguments['value'] === $arguments['checked'] ? 'checked="checked"' : '' );
                printf(
                    '<div class="card-oracle-grid-content"><label class="card-oracle-switch">
					<input type="checkbox" name="%1$s" class="card-oracle-checkbox" id="%1$s" style="margin: 10px;" value="%2$s" %3$s>
					<span class="card-oracle-toggle-thumb">%4$s %5$s</span></label></div>',
                    esc_attr( $arguments['uid'] ),
                    esc_attr( $arguments['value'] ),
                    esc_attr( $checked ),
                    '',
                    ''
                );
                if ( array_key_exists( 'supplemental', $arguments ) ) {
                    printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $arguments['supplemental'] ) );
                }
                break;
            default:
                break;
        }
    }
    
    /**
     * Render the Reading Metabox for Cards CPT
     *
     * @since 1.1.3
     * @return void
     */
    public function render_card_metabox()
    {
        global  $post ;
        // Generate nonce.
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );
        $readings = get_card_oracle_posts_by_cpt( 'co_readings', 'post_title' );
        $selected_readings = get_post_meta( $post->ID, CO_READING_ID, false );
        echo  '<p class="card-oracle-metabox">' ;
        esc_html_e( 'Reading', 'card-oracle' );
        echo  '</p>' ;
        echo  '<div class="card-oracle-multiflex">' ;
        foreach ( $readings as $id ) {
            $checked = ( is_array( $selected_readings ) && in_array( (string) $id->ID, $selected_readings, true ) ? 'checked="checked"' : '' );
            echo  '<div class="card-oracle-multiitem">
					<input id="reading' . esc_attr( $id->ID ) . '" type="checkbox" name="_co_reading_id[]" value="' . esc_attr( $id->ID ) . '" ' . esc_attr( $checked ) . ' /><label for="reading' . esc_attr( $id->ID ) . '">' . esc_html( $id->post_title ) . '</label></div>' ;
        }
        echo  '</div>' ;
    }
    
    /**
     * Render the Card Metabox for Descriptions CPT
     *
     * @since 1.1.3
     * @return void
     */
    public function render_description_metabox()
    {
        global  $post ;
        // Generate nonce.
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );
        $selected_card = get_post_meta( $post->ID, CO_CARD_ID, true );
        echo  '<p class="card-oracle-metabox">' ;
        esc_html_e( 'Card', 'card-oracle' );
        echo  '</p>' ;
        $dropdown = wp_dropdown_pages( array(
            'post_type'        => 'co_cards',
            'selected'         => esc_attr( $selected_card ),
            'name'             => esc_attr( CO_CARD_ID ),
            'show_option_none' => esc_html__( '(no card)', 'card-oracle' ),
            'sort_column'      => 'post_title',
            'echo'             => 0,
        ) );
        $allowed_html = array(
            'select' => array(
            'name' => array(),
        ),
            'option' => array(
            'value'    => array(),
            'selected' => array(),
        ),
        );
        echo  wp_kses( $dropdown, $allowed_html ) ;
        echo  '</p>' ;
        $selected_position = get_post_meta( $post->ID, CO_POSITION_ID, false );
        echo  '<p class="card-oracle-metabox">' ;
        esc_html_e( 'Description Position', 'card-oracle' );
        echo  '</p>' ;
        $positions = get_card_oracle_posts_by_cpt( 'co_positions', 'title', 'ASC' );
        echo  '<div class="card-oracle-multiflex">' ;
        foreach ( $positions as $id ) {
            $selected = get_post_meta( $post->ID, CO_POSITION_ID, false );
            $checked = ( is_array( $selected_position ) && in_array( (string) $id->ID, $selected_position, true ) ? 'checked="checked"' : '' );
            echo  '<div class="card-oracle-multiitem"><input id="position' . esc_attr( $id->ID ) . '" class="card-oracle-multibox" type="checkbox" name="_co_position_id[]" value="' . esc_attr( $id->ID ) . '" ' . esc_attr( $checked ) . ' /><label for="position' . esc_attr( $id->ID ) . '">' . esc_html( get_the_title( $id->ID ) ) . '</label></div>' ;
        }
        echo  '</div>' ;
    }
    
    // render_description_metabox
    /**
     * Render the Reverse Descriptions Metabox for Descriptions CPT
     *
     * @since 0.13.0
     * @return void
     */
    public function render_reverse_description_metabox()
    {
        global  $post ;
        // Generate nonce.
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );
        $reverse_description = wpautop( get_post_meta( $post->ID, CO_REVERSE_DESCRIPTION, true ), true );
        wp_editor( $reverse_description, 'meta_content_editor', array(
            'wpautop'       => true,
            'media_buttons' => false,
            'textarea_name' => CO_REVERSE_DESCRIPTION,
            'textarea_rows' => 10,
            'teeny'         => true,
        ) );
    }
    
    /**
     * Render the Reading and Order Metabox for Positions CPT
     *
     * @since 1.0.4
     * @return void
     */
    public function render_position_metabox()
    {
        global  $post ;
        // Generate nonce.
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );
        $readings = get_card_oracle_posts_by_cpt( 'co_readings', 'post_title' );
        $selected_readings = get_post_meta( $post->ID, CO_READING_ID, false );
        $fields = array( array(
            'uid'   => CO_CARD_ORDER,
            'label' => esc_html__( 'Order', 'card-oracle' ),
            'min'   => 1,
            'max'   => 999,
            'value' => $post->_co_card_order,
            'type'  => 'numberbox',
        ) );
        // Display the Reading Setting.
        printf( '<div class="card-oracle-grid-wrapper"><div class="card-oracle-grid-label">%s</div><div class="card-oracle-grid-content"><div class="card-oracle-multiflex">', esc_html__( 'Reading', 'card-oracle' ) );
        foreach ( $readings as $id ) {
            $this->display_metabox_fields( array(
                'uid'     => $id->ID,
                'checked' => $selected_readings,
                'label'   => $id->post_title,
                'type'    => 'checkbox_list',
            ) );
        }
        echo  '</div></div></div>' ;
        // Display the all the Settings.
        foreach ( $fields as $field ) {
            echo  '<div class="card-oracle-grid-wrapper">' ;
            $this->display_metabox_fields( $field );
            echo  '</div>' ;
        }
    }
    
    /**
     * Render the Reading Metabox for Cards CPT
     *
     * @since 1.1.1
     * @return void
     */
    public function render_reading_metabox()
    {
        global  $post ;
        // Get the deck layouts to populate the setting field.
        $deck_layouts = get_deck_layouts();
        $presentation_layouts = get_presentation_layouts();
        $number_positions = count( get_positions_for_reading( $post->ID ) );
        // Create the option list for the Presentation layouts.
        foreach ( $presentation_layouts as $presentation_layout ) {
            if ( file_exists( $presentation_layout['file'] ) ) {
                if ( 0 === $presentation_layout['positions'] || $number_positions === $presentation_layout['positions'] ) {
                    $presentation_options[$presentation_layout['uid']] = $presentation_layout['label'];
                }
            }
        }
        $settings = array(
            'wpautop'          => true,
            'media_buttons'    => false,
            'textarea_rows'    => 3,
            'tabindex'         => '',
            'editor_css'       => '',
            'editor_class'     => '',
            'teeny'            => false,
            'dfw'              => false,
            'tinymce'          => true,
            'quicktags'        => true,
            'drag_drop_upload' => false,
        );
        $sections = array( array(
            'uid'   => 'general_section',
            'label' => esc_html__( 'General', 'card-oracle' ),
        ), array(
            'uid'   => 'question_section',
            'label' => esc_html__( 'Display Question Options', 'card-oracle' ),
        ) );
        $fields = array(
            array(
            'uid'          => CO_AUTO_SUBMIT,
            'label'        => esc_html__( 'Auto Submit', 'card-oracle' ),
            'type'         => 'toggle_switch',
            'order'        => 10,
            'section'      => 'general_section',
            'checked'      => $post->_co_auto_submit,
            'value'        => 'yes',
            'supplemental' => esc_html__( 'Automatically submit the reading when the last card is selected by the user. The submit button is hidden from the user.', 'card-oracle' ),
        ),
            array(
            'uid'          => CO_DECK_LAYOUT,
            'label'        => esc_html__( 'Deck Display Layout', 'card-oracle' ),
            'type'         => 'dropdown',
            'order'        => 20,
            'section'      => 'general_section',
            'options'      => $deck_layouts,
            'value'        => $post->_co_deck_layout,
            'supplemental' => esc_html__( 'How the deck of cards will be displayed to the users.', 'card-oracle' ),
        ),
            array(
            'uid'          => CO_REVERSE_PERCENT,
            'label'        => esc_html__( 'Reversed Percentage', 'card-oracle' ),
            'type'         => 'numberbox',
            'order'        => 30,
            'section'      => 'general_section',
            'min'          => 0,
            'max'          => 100,
            'value'        => $post->_co_reverse_percent,
            'supplemental' => esc_html__( 'Percentage chance of a card displaying in reverse.', 'card-oracle' ),
        ),
            array(
            'uid'          => CO_PRESENTATION_LAYOUT,
            'label'        => esc_html__( 'Presentation Layout', 'card-oracle' ),
            'type'         => 'layout_dropdown',
            'order'        => 40,
            'section'      => 'general_section',
            'options'      => $presentation_options,
            'value'        => $post->_co_presentation_layout,
            'supplemental' => esc_html__( 'Only Layouts with the same number of positions as the reading will be available in the dropdown.' ),
        ),
            array(
            'uid'          => CO_LAYOUT_TABLE,
            'label'        => esc_html__( 'Display Layout table', 'card-oracle' ),
            'type'         => 'toggle_switch',
            'order'        => 50,
            'section'      => 'general_section',
            'checked'      => $post->_co_layout_table,
            'value'        => 'yes',
            'supplemental' => esc_html__( 'Displays a table of the positions and the cards picked next to the layout.', 'card-oracle' ),
        ),
            array(
            'uid'          => CO_TARGET_BLANK,
            'label'        => esc_html__( 'New Window', 'card-oracle' ),
            'type'         => 'toggle_switch',
            'order'        => 55,
            'section'      => 'general_section',
            'checked'      => $post->_co_target_blank,
            'value'        => 'yes',
            'supplemental' => esc_html__( 'The result page will open in a new browser window or tab.', 'card-oracle' ),
        ),
            array(
            'uid'          => DISPLAY_QUESTION,
            'label'        => esc_html__( 'Display Question Input Box', 'card-oracle' ),
            'type'         => 'toggle_switch',
            'order'        => 60,
            'section'      => 'question_section',
            'checked'      => $post->display_question,
            'value'        => 'yes',
            'supplemental' => esc_html__( 'Enabling this will display an input field to the users to enter a question.', 'card-oracle' ),
        ),
            array(
            'uid'          => 'question_text',
            'label'        => esc_html__( 'Text for question input box', 'card-oracle' ),
            'type'         => 'text',
            'order'        => 70,
            'section'      => 'question_section',
            'value'        => get_post_meta( $post->ID, 'question_text', true ),
            'placeholder'  => '',
            'supplemental' => esc_html__( 'Avoid using apostrophes in the text if you plan on allowing users to email the readings.', 'card-oracle' ),
        )
        );
        $text_editors = array(
            // Text editor for text to display between Question and Cards.
            array(
                'uid'          => 'before_cards_text',
                'label'        => esc_html__( 'Text to display before Cards.', 'card-oracle' ),
                'order'        => 20,
                'value'        => $post->before_cards_text,
                'supplemental' => '',
            ),
            // Add a text editor for the footer text on daily and random cards.
            array(
                'uid'          => 'footer_text',
                'label'        => esc_html__( 'Footer to be displayed on daily and random cards.', 'card-oracle' ),
                'order'        => 30,
                'value'        => $post->footer_text,
                'supplemental' => '',
            ),
        );
        // Generate nonce.
        wp_nonce_field( 'meta_box_nonce', 'meta_box_nonce' );
        // Get whether or not the Display Input box should be checked.
        $display_question_checked = $post->display_question;
        if ( 'yes' === $display_question_checked ) {
            $display_question_checked = 'checked="checked"';
        }
        foreach ( $sections as $section ) {
            echo  '<div class="card-oracle-grid-wrapper">' ;
            printf( '<div class="card-oracle-grid-section">%s</div>', esc_attr( $section['label'] ) );
            echo  '</div>' ;
            foreach ( $fields as $field ) {
                
                if ( $field['section'] === $section['uid'] ) {
                    echo  '<div class="card-oracle-grid-wrapper">' ;
                    $this->display_metabox_fields( $field );
                    echo  '</div>' ;
                }
            
            }
        }
        $keys = array_column( $text_editors, 'order' );
        array_multisort( $keys, SORT_ASC, $text_editors );
        // Add text editor fields at the bottom.
        foreach ( $text_editors as $text_editor ) {
            echo  '<div class="card-oracle-grid-wrapper">' ;
            printf( '<div class="card-oracle-grid-section">%s</div></div>', wp_kses_post( $text_editor['label'] ) );
            wp_editor( html_entity_decode( $text_editor['value'] ), $text_editor['uid'], $settings );
            if ( $text_editor['supplemental'] ) {
                printf( '<div class="card-oracle-grid-footer">%s</div>', esc_html( $text_editor['supplemental'] ) );
            }
        }
    }
    
    // render_reading_metabox.
    /**
     * Save the card post meta for Card Oracle
     *
     * @since 1.1.3
     * @return void
     */
    public function save_card_oracle_meta_data()
    {
        global  $post ;
        // Check nonce.
        if ( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( sanitize_key( $_POST['meta_box_nonce'] ), 'meta_box_nonce' ) ) {
            return;
        }
        // If this is an autosave, our form has not been submitted, so we don't want to do anything.
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        // Prevent quick edit from clearing custom fields.
        if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
            return;
        }
        // Check the user's permissions.
        if ( !current_user_can( 'edit_post', $post->ID ) ) {
            return;
        }
        // If the Reading ID has been selected then update it.
        
        if ( isset( $_POST[CO_READING_ID] ) ) {
            $current_readings = get_post_meta( $post->ID, CO_READING_ID, false );
            // Multiple Readings ID update.
            
            if ( isset( $_POST[CO_READING_ID] ) ) {
                $post_readings = wp_unslash( $_POST[CO_READING_ID] );
                // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
                // If a current _co_position_id is not in the POST array then remove it.
                if ( !empty($current_readings) ) {
                    foreach ( $current_readings as $current_reading ) {
                        if ( !in_array( $current_reading, $post_readings, true ) ) {
                            delete_post_meta( $post->ID, CO_READING_ID, $current_reading );
                        }
                    }
                }
                // If a POST position is not in the current array then add it.
                if ( !empty($post_readings) ) {
                    foreach ( $post_readings as $reading ) {
                        if ( !in_array( $reading, $current_readings, true ) ) {
                            add_post_meta( $post->ID, CO_READING_ID, sanitize_text_field( $reading ) );
                        }
                    }
                }
            }
        
        } else {
            delete_post_meta( $post->ID, CO_READING_ID );
        }
        
        // If the Auto Submit has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_AUTO_SUBMIT );
        // If the Card has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_CARD_ID );
        // If the Order has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_CARD_ORDER );
        // If the Deck Layout has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_DECK_LAYOUT );
        // If the Deck Layout has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_LAYOUT_TABLE );
        // If the Position Text has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_POSITION_TEXT );
        // If the Deck Layout has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_PRESENTATION_LAYOUT );
        // If the Price is set update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_PRICE );
        // If the Purchase Name is set update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_PURCHASE_NAME );
        // If the Purchase Name is set update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_PURCHASE_SUBJECT );
        // If the Reading Question Layout has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_QUESTION_LAYOUT );
        // If the Reverse Percentage has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_REVERSE_PERCENT );
        // If the New Window has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, CO_TARGET_BLANK );
        // If the Reading Display has been selected update it.
        $this->update_post_meta_using_sanitize( $_POST, $post->ID, DISPLAY_QUESTION );
        // If the Reading Text to display before Cards has been selected update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, BEFORE_CARDS_TEXT );
        // If the Reverse Description is set update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, CO_REVERSE_DESCRIPTION );
        // If the Sales Text is set update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, CO_SALES_TEXT );
        // If the Subscriber List is set update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, CO_SUBSCRIBER_LIST );
        // If the Reading Footer text has been selected update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, FOOTER_TEXT );
        // If the Reading Display has been selected update it.
        $this->update_post_meta_using_wp_kses_post( $_POST, $post->ID, QUESTION_TEXT );
        // If the Position has been selected update it.
        
        if ( isset( $_POST[CO_POSITION_ID] ) ) {
            $current_positions = get_post_meta( $post->ID, CO_POSITION_ID, false );
            // If a current _co_position_id is not in the POST array then remove it.
            foreach ( $current_positions as $current_position ) {
                if ( !in_array( $current_position, $_POST[CO_POSITION_ID], true ) ) {
                    delete_post_meta( $post->ID, CO_POSITION_ID, $current_position );
                }
            }
            // If a current POST position is not in the current array then add it.
            $positions = array_map( 'sanitize_text_field', wp_unslash( $_POST[CO_POSITION_ID] ) );
            foreach ( $positions as $position ) {
                if ( !in_array( $position, $current_positions, true ) ) {
                    add_post_meta( $post->ID, CO_POSITION_ID, sanitize_text_field( $position ) );
                }
            }
        } else {
            delete_post_meta( $post->ID, CO_POSITION_ID );
        }
    
    }
    
    /**
     * Update the meta data for a post using sanitize_text_field.
     *
     * @since 1.1.3
     * @param string $post_data  The _POST.
     * @param int    $post_id  The id of the post to update.
     * @param string $meta_name  The id of the post to update.
     */
    private function update_post_meta_using_sanitize( $post_data, $post_id, $meta_name )
    {
        ( isset( $post_data[$meta_name] ) ? update_post_meta( $post_id, $meta_name, sanitize_text_field( wp_unslash( $post_data[$meta_name] ) ) ) : delete_post_meta( $post_id, $meta_name ) );
    }
    
    /**
     * Update the meta data for a post using wp_kses_post.
     *
     * @since 1.1.3
     * @param string $post_data  The _POST.
     * @param int    $post_id  The id of the post to update.
     * @param string $meta_name  The id of the post to update.
     */
    private function update_post_meta_using_wp_kses_post( $post_data, $post_id, $meta_name )
    {
        ( isset( $post_data[$meta_name] ) ? update_post_meta( $post_id, $meta_name, wp_kses_post( wp_unslash( $post_data[$meta_name] ) ) ) : delete_post_meta( $post_id, $meta_name ) );
    }
    
    /**
     * Set the admin columns for Cards
     *
     * @since 0.13.0
     * @param array $columns Set the columns for Cards.
     * @return array $columns
     */
    public function set_custom_cards_columns( $columns )
    {
        // unset the date so we can move it to the end.
        unset( $columns['date'] );
        $columns['card_reading'] = esc_html__( 'Associated Reading(s)', 'card-oracle' );
        $columns['number_card_descriptions'] = esc_html__( 'Number of Descriptions', 'card-oracle' );
        $columns['date'] = esc_html__( 'Date', 'card-oracle' );
        return $columns;
    }
    
    /**
     * Set the admin columns for Descriptions
     *
     * @since 0.13.0
     * @param array $columns Set the columns for Descriptions.
     * @return $columns
     */
    public function set_custom_descriptions_columns( $columns )
    {
        // unset the date so we can move it to the end.
        unset( $columns['date'] );
        $columns['card_title'] = esc_html__( 'Card', 'card-oracle' );
        $columns['position_title'] = esc_html__( 'Position', 'card-oracle' );
        $columns['position_number'] = esc_html__( 'Position Number', 'card-oracle' );
        $columns['date'] = esc_html__( 'Date', 'card-oracle' );
        return $columns;
    }
    
    /**
     * Set the admin columns for Readings
     *
     * @since 0.13.0
     * @param array $columns Set the columns for Readings.
     * @return array $columns
     */
    public function set_custom_readings_columns( $columns )
    {
        // unset the date so we can move it to the end.
        unset( $columns['date'] );
        $columns['co_shortcode'] = esc_html__( 'Shortcode', 'card-oracle' );
        $columns['number_reading_positions'] = esc_html__( 'Positions', 'card-oracle' );
        $columns['date'] = esc_html__( 'Date', 'card-oracle' );
        return $columns;
    }
    
    /**
     * Set the admin columns for Positions
     *
     * @since 0.3.0
     * @param array $columns Set the columns for Positions.
     * @return array $columns
     */
    public function set_custom_positions_columns( $columns )
    {
        // unset the date so we can move it to the end.
        unset( $columns['date'] );
        $columns['card_reading'] = esc_html__( 'Reading', 'card-oracle' );
        $columns['card_order'] = esc_html__( 'Position', 'card-oracle' );
        $columns['date'] = esc_html__( 'Date', 'card-oracle' );
        return $columns;
    }
    
    /**
     * Set the admin columns for Orders
     *
     * @since 0.25.0
     * @param array $columns Set the columns for Orders.
     * @return array $columns
     */
    public function set_custom_order_columns( $columns )
    {
        // unset the Checkbox and the Title.
        unset( $columns['cb'] );
        unset( $columns['title'] );
        // unset the date so we can move it to the end.
        unset( $columns['date'] );
        $columns['order_email'] = esc_html__( 'Email Address', 'card-oracle' );
        $columns['order_price'] = esc_html__( 'Price', 'card-oracle' );
        $columns['order_status'] = esc_html__( 'Status', 'card-oracle' );
        $columns['order_content'] = esc_html__( 'Content', 'card-oracle' );
        $columns['order_txn_id'] = esc_html__( 'Transaction ID', 'card-oracle' );
        $columns['date'] = esc_html__( 'Date', 'card-oracle' );
        return $columns;
    }
    
    /**
     * Set the sortable columns for Cards.
     *
     * @since 0.13.0
     * @param array $columns Set the sortable columns for Cards.
     * @return array $columns
     */
    public function set_custom_sortable_card_columns( $columns )
    {
        $columns['card_reading'] = 'card_reading';
        $columns['number_card_descriptions'] = 'number_card_descriptions';
        return $columns;
    }
    
    /**
     * Set the sortable columns for Descriptions
     *
     * @since 0.13.0
     * @param array $columns Set the sortable columns for Descriptions.
     * @return array $columns
     */
    public function set_custom_sortable_description_columns( $columns )
    {
        $columns['card_title'] = 'card_title';
        $columns['description_reading'] = 'description_reading';
        $columns['position_title'] = 'position_title';
        $columns['position_number'] = 'position_number';
        return $columns;
    }
    
    /**
     * Set the sortable columns for Positions
     *
     * @since 0.13.0
     * @param array $columns Set the sortable columns for Positions.
     * @return array $columns
     */
    public function set_custom_sortable_position_columns( $columns )
    {
        $columns['card_reading'] = 'card_reading';
        $columns['card_order'] = 'card_order';
        return $columns;
    }
    
    /**
     * Set the sortable columns for Orders.
     *
     * @since 1.0.4
     * @param array $columns Set the sortable columns for Orders.
     * @return array $columns
     */
    public function set_custom_sortable_order_columns( $columns )
    {
        $columns['order_email'] = 'order_email';
        $columns['order_status'] = 'order_status';
        return $columns;
    }
    
    /**
     * Add order by clause to the query.
     *
     * @since 1.1.1
     * @param mixed $query Query to add the order by clause.
     */
    public function card_oracle_column_orderby( $query )
    {
        if ( !is_admin() ) {
            return;
        }
        $orderby = $query->get( 'orderby' );
        
        if ( 'order_status' === $orderby ) {
            $query->set( 'meta_key', CO_ORDER_STATUS );
            $query->set( 'orderby', 'meta_value' );
        }
        
        
        if ( 'order_email' === $orderby ) {
            $query->set( 'orderby', 'meta_value' );
            $query->set( 'meta_query', array(
                'relation' => 'or',
                array(
                'key' => CO_IPN_EMAIL,
            ),
                array(
                'key'     => CO_IPN_EMAIL,
                'compare' => 'NOT EXISTS',
            ),
            ) );
        }
    
    }
    
    /**
     * Remove edit from the bulk actions.
     *
     * @since 1.0.5
     * @param array $actions List of menu actions.
     */
    public function card_oracle_remove_from_bulk_actions( $actions )
    {
        unset( $actions['edit'] );
        return array();
    }
    
    /**
     * Page for adding Demo Data.
     *
     * @since 1.1.3
     */
    public function display_card_oracle_demodata_page()
    {
        global  $co_logs, $co_notices ;
        $demo_attributes = '';
        $image_attributes = '';
        $demodata = ( new CardOracleDemoData() )->import_json_data( 'assets/data/demo-data.json' );
        $image_data = ( new CardOracleDemoData() )->import_json_data( 'assets/data/images.json' );
        // Check whether the button has been pressed AND also check the nonce.
        
        if ( $image_data && isset( $_POST['image_button'] ) && check_admin_referer( 'image_button_clicked' ) ) {
            $co_logs->add(
                DEMO_DATA,
                'Inserting image data.',
                null,
                'event'
            );
            // Just created the data so disable the button.
            $image_attributes .= 'disabled="disabled"';
            ( new CardOracleDemoData() )->insert_images( $image_data );
            $image_attributes = '';
            // Show success admin notice.
            $co_notices->add( 'images_installed', esc_html__( 'Images installed.', 'card-oracle' ), 'success' );
        }
        
        
        if ( $demodata ) {
            $readings = get_card_oracle_posts_by_cpt( 'co_readings', 'post_title' );
            $reading_id = get_page_by_title( $demodata['reading']['name'], OBJECT, 'co_readings' );
            
            if ( isset( $reading_id ) && 'publish' === $readings[0]->post_status ) {
                $co_logs->add(
                    DEMO_DATA,
                    'Demo data already exists. Exiting.',
                    null,
                    'event'
                );
                // The Reading already exists disable the button.
                $demo_attributes .= 'disabled="disabled"';
                // Display admin notice.
                $co_notices->add( 'demo_data_installed', esc_html__( 'Appears you have already installed the demo data. To re-install you must delete the old data.', 'card-oracle' ), 'warning' );
            }
            
            // Check whether the button has been pressed AND also check the nonce.
            
            if ( isset( $_POST['demo_data_button'] ) && check_admin_referer( 'demo_data_button_clicked' ) ) {
                $co_logs->add(
                    DEMO_DATA,
                    'Inserting demo data.',
                    null,
                    'event'
                );
                // Just created the data so disable the button.
                $demo_attributes .= 'disabled="disabled"';
                ( new CardOracleDemoData() )->insert_data( $demodata );
                // Show success admin notice.
                $co_notices->add( 'demo_data_installed', esc_html__( 'Demo data installed.', 'card-oracle' ), 'success' );
            }
        
        }
        
        // Include the Card Oracle admin header file.
        include_once 'partials/card-oracle-admin-header.php';
        $co_notices->display();
        
        if ( $image_data ) {
            echo  '<div class="card-oracle-cards"><div class="card-oracle-card"><div class="card-oracle-form-left">' ;
            echo  '<h2>' ;
            esc_html_e( 'Insert all the images for the Marseille Tarot Card Deck.', 'card-oracle' );
            echo  '</h2>' ;
            esc_html_e( 'If the post title for the image already exists, then the image will not be added.', 'card-oracle' );
            echo  '</br>' ;
            esc_html_e( 'For example, if a post/page/image with the title "Card Oracle The World" exists, then that image will not be saved.', 'card-oracle' );
            echo  '</div><form id="image_form" action="" method="post">' ;
            wp_nonce_field( 'image_button_clicked' );
            echo  '<input type="hidden" value="true" name="image_button" />' ;
            submit_button(
                esc_attr__( 'Insert Images', 'card-oracle' ),
                'primary',
                'image_button',
                '',
                $image_attributes
            );
            echo  '</form></div></div>' ;
        }
        
        echo  '<div class="card-oracle-cards"><div class="card-oracle-card"><h2>' ;
        esc_html_e( 'Insert Demo Data for Tarot Card Oracle.', 'card-oracle' );
        echo  '</h2>' ;
        esc_html_e( 'This will create a set of demo data for you consisting of:', 'card-oracle' );
        echo  '<ul class="ul-disc"><li>' ;
        esc_html_e( 'One reading named "Past, Present, Future (Demo)"', 'card-oracle' );
        echo  '</li><li>' ;
        esc_html_e( 'Three Positions named "Past", "Present", "Future".', 'card-oracle' );
        echo  '</li><li>' ;
        esc_html_e( 'The 22 major arcana tarot cards.', 'card-oracle' );
        echo  '</li><li>' ;
        esc_html_e( '66 Descriptions, one for each of the 22 Card in each of the 3 Positions.', 'card-oracle' );
        echo  '</li></ul>' ;
        echo  '<form id="demo_data_form" action="" method="post">' ;
        // this is a WordPress security feature - see: https://codex.wordpress.org/WordPress_Nonces.
        wp_nonce_field( 'demo_data_button_clicked' );
        echo  '<input type="hidden" value="true" name="demo_data_button" />' ;
        submit_button(
            esc_attr__( 'Insert Data', 'card-oracle' ),
            'primary',
            'demo_data_button',
            '',
            $demo_attributes
        );
        echo  '</form>' ;
        echo  '</div></div>' ;
        // Include the Card Oracle admin footer file.
        include_once 'partials/card-oracle-admin-footer.php';
    }
    
    /**
     * Set the sections and fields for the admin screens
     *
     * @since 0.25.0
     *
     * @param array  $sections Sections to display on screen.
     * @param array  $fields Fields to display on screen.
     * @param string $page Which page are the fields for.
     * @return void
     */
    public function card_oracle_register_sections_fields( $sections, $fields, $page )
    {
        foreach ( $sections as $section ) {
            if ( 'card_oracle_option_integrations' !== $page || '' === $section['provider'] || get_option( 'card_oracle_integration' ) === $section['provider'] ) {
                add_settings_section(
                    $section['uid'],
                    $section['label'],
                    array( $this, 'card_oracle_section_callback' ),
                    $section['page']
                );
            }
        }
        foreach ( $fields as $field ) {
            add_settings_field(
                $field['uid'],
                $field['label'],
                array( $this, 'card_oracle_option_callback' ),
                $page,
                $field['section'],
                $field
            );
            register_setting( $page, $field['uid'] );
        }
    }

}