<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Builder {

    private static $_instance = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'admin_init',                  array( $this, 'duplicate_calendar' ) );
        add_action( 'admin_head',                  array( $this, 'load_important_date_color_style' ) );
        add_action( 'admin_menu',                  array( $this, 'admin_menu' ) );
        add_action( 'admin_menu',                  array( $this, 'remove_submenu' ), 9999 );
        add_action( 'wp_ajax_wpsc_save_calendar',  array( $this, 'ajax_save_calendar' ) );
        add_action( 'wp_ajax_wpsc_reload_preview', array( $this, 'ajax_reload_preview' ) );
    }

    /**
     * retrieve singleton class instance
     * @return instance reference to plugin
     */
    public static function instance() {
        if ( NULL === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Add Categories menu
     * 
     * @since 1.0
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'School Calendar Builder', 'wp-school-calendar' ), __( 'Calendar Builder', 'wp-school-calendar' ), 'manage_options', 'wpsc-builder', array( $this, 'admin_page' ) );
    }
    
    public function remove_submenu() {
        remove_submenu_page( 'edit.php?post_type=school_calendar', 'wpsc-builder' );
    }
    
    public function duplicate_calendar() {
        if ( isset( $_REQUEST['page'] ) && 'wpsc-builder' === $_REQUEST['page'] ) {
            $sendback = remove_query_arg( array( '_wp_http_referer', '_wpnonce', 'duplicate', 'page' ), wp_get_referer() );
            
            if ( ! empty( $_GET['duplicate'] ) ) {
                $calendar_id = intval( $_GET['duplicate'] );
                check_admin_referer( 'calendar_duplicate-' . $calendar_id );
                
                wpsc_duplicate_calendar( $calendar_id );
                
                wp_redirect( $sendback );
                exit;
            }
        }
    }
    
    /**
     * Add Calendar page
     * 
     * @since 1.0
     */
    public function admin_page() {
        $calendar = array_merge( array(
            'calendar_id' => '',
            'name'        => __( 'School Calendar', 'wp-school-calendar' ),
        ), wpsc_get_default_calendar_options() );

        if ( ! empty( $_GET['edit'] ) ) {
            $calendar = wp_parse_args( wpsc_get_calendar( intval( $_REQUEST['edit'] ) ), $calendar );
        }

        $theme_options                      = wpsc_get_calendar_theme_options();
        $weekday_options                    = wpsc_get_weekday_options();
        $available_groups                   = wpsc_get_groups();
        $available_categories               = wpsc_get_categories();
        $date_format_options                = wpsc_get_date_format_options();
        $day_format_options                 = wpsc_get_day_format_options();
        $month_options                      = wpsc_get_month_options();
        $year_options                       = wpsc_get_year_options();
        $num_month_options                  = wpsc_get_num_month_options();
        $default_month_range_options        = wpsc_get_default_month_range_options();
        $num_column_options                 = wpsc_get_num_column_options();
        $custom_default_year_options        = wpsc_get_custom_default_year_options( $calendar['start_year'] );
        $custom_default_month_range_options = wpsc_get_custom_default_month_range_options( $calendar['start_year'], $calendar['num_months'] );
        ?>
        <div class="wrap">
            <div id="wpsc-shortcode-panel" class="wpsc-shortcode-panel mfp-hide">
                <div class="wpsc-shortcode-panel-inner">
                    <h3><?php echo __( 'Embed in a Page', 'wp-school-calendar' ) ?></h3>
                    <h4><?php echo __( 'Using Gutenberg Block', 'wp-school-calendar' ) ?></h4>
                    <p><?php echo __( 'To begin, you will need to create a new WordPress page or edit an existing one. Once, you have opened the editor, you can add a new block by clicking the + (plus) icon in the upper left corner.', 'wp-school-calendar' ) ?></p>
                    <p><?php echo __( 'Once you have clicked this icon, a menu of block options will display. To locate the WP School Calendar block, you can search WP School Calendar or open the Widgets category. Then click the block named WP School Calendar.', 'wp-school-calendar' ) ?></p>
                    <p><?php echo __( 'This will add the WP School Calendar block to the editor screen.', 'wp-school-calendar' ) ?></p>
                    <h4><?php echo __( 'Using Shortcode', 'wp-school-calendar' ) ?></h4>
                    <p><?php echo __( 'You can also use the shortcode below to display school calendar on your WordPress site.', 'wp-school-calendar' ) ?></p>
                    <p><input id="wpsc-builder-shortcode-field" type="text" value='<?php printf( '[wp_school_calendar id="%d"]', $calendar['calendar_id'] ) ?>' class="wpsc-builder-shortcode-field" readonly="readonly"></p>
                </div>
            </div>
            <div class="wpsc-buider-navigation">
                <div class="wpsc-buider-navigation-inner">
                    <div class="wpsc-builder-navigation-left">
                        <span><a class="button" href="<?php echo admin_url( 'edit.php?post_type=school_calendar' ) ?>"><?php echo esc_html__( 'Back to Calendars', 'wp-school-calendar' ); ?></a></span>
                    </div>
                    <div class="wpsc-builder-navigation-middle">
                        <div id="wpsc-builder-navigation-name" class="wpsc-builder-name"><?php echo $calendar['name'] ?></div>
                    </div>
                    <div class="wpsc-builder-navigation-right">
                        <span><input id="wpsc-view-shortcode-button" type="button" value="<?php echo __( 'Embed', 'wp-school-calendar' ) ?>" class="button"<?php if ( '' === $calendar['calendar_id'] ) echo ' disabled="disabled"' ?>></span>
                        <span><input id="wpsc-save-calendar-button" type="button" value="<?php echo __( 'Save Calendar', 'wp-school-calendar' ) ?>" class="button button-primary"></span>
                    </div>
                </div>
            </div>
            <div class="wpsc-builder-preview">
                <div id="wpsc-builder-preview-content" class="wpsc-builder-preview-content">
                    <div id="wpsc-block-calendar" class="wpsc-block-calendar"><?php wpsc_render_calendar( $calendar, '', '' ) ?></div>
                </div>
            </div>
            <div class="wpsc-builder-sidebar">
                <div class="wpsc-builder-sidebar-inner">
                    <form method="post" id="wpsc-builder-form">
                    <input type="hidden" name="page" value="wpsc-calendar">
                    <input type="hidden" id="wpsc-reload" value="N">
                    <input id="wpsc-builder-field-calendar-id" type="hidden" name="calendar_id" value="<?php echo $calendar['calendar_id'] ?>">
                    <div class="wpsc-builder-option-group">
                        <div class="wpsc-builder-option-heading"><button type="button" class="wpsc-builder-option-items-open" data-target="wpsc-builder-general-option-items"><?php echo __( 'General', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
                        <div id="wpsc-builder-general-option-items" class="wpsc-builder-option-items">
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Name', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <input id="wpsc-builder-field-name" type="text" name="name" value="<?php echo esc_attr( $calendar['name'] ) ?>" class="large-text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpsc-builder-option-group">
                        <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-important-dates-option-items"><?php echo __( 'Important Dates', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
                        <div id="wpsc-builder-important-dates-option-items" class="wpsc-builder-option-items" style="display:none">
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Groups', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <input id="wpsc-builder-field-groups" type="hidden" name="groups" value="<?php echo esc_attr( implode( ',', $calendar['groups'] ) ) ?>">
                                    <select multiple="multiple" class="wpsc-select wpsc-select-multiple" data-field-target="wpsc-builder-field-groups">
                                        <?php if ( $available_groups ) : foreach ( $available_groups as $group ): ?>
                                        <option value="<?php echo esc_attr( $group['group_id'] ) ?>"<?php selected( in_array( $group['group_id'], $calendar['groups'] ) ) ?>><?php echo esc_html( $group['name'] ) ?></option>
                                        <?php endforeach; endif; ?>
                                    </select>
                                </div>
                                <div class="wpsc-builder-option-field">
                                    <input type="hidden" name="include_no_groups" value="N">
                                    <label for="include-no-groups"><input id="include-no-groups" type="checkbox" name="include_no_groups" value="Y" <?php checked( 'Y', $calendar['include_no_groups'] ) ?> class="wpsc-checkbox"> 
                                        <?php echo esc_html__( 'Include No Group Important Dates', 'wp-school-calendar' ) ?></label>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Categories', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <input id="wpsc-builder-field-categories" type="hidden" name="categories" value="<?php echo esc_attr( implode( ',', $calendar['categories'] ) ) ?>">
                                    <select multiple="multiple" class="wpsc-select wpsc-select-multiple" data-field-target="wpsc-builder-field-categories">
                                        <?php foreach ( $available_categories as $category ): ?>
                                        <option value="<?php echo esc_attr( $category['category_id'] ) ?>"<?php selected( in_array( $category['category_id'], $calendar['categories'] ) ) ?>><?php echo esc_html( $category['name'] ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-field">
                                    <input type="hidden" name="show_important_date_cats" value="N">
                                    <p><label for="show-important-date-cats"><input id="show-important-date-cats" type="checkbox" name="show_important_date_cats" value="Y" <?php checked( 'Y', $calendar['show_important_date_cats'] ) ?> class="wpsc-checkbox"> 
                                        <?php echo esc_html__( 'Display Important Date Categories', 'wp-school-calendar' ) ?></label></p>
                                    <input type="hidden" name="show_important_date_listing" value="N">
                                    <p><label for="show-important-date-listing"><input id="show-important-date-listing" type="checkbox" name="show_important_date_listing" value="Y" <?php checked( 'Y', $calendar['show_important_date_listing'] ) ?> class="wpsc-checkbox"> 
                                        <?php echo esc_html__( 'Display Important Date Listing', 'wp-school-calendar' ) ?></label></p>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Important Date Heading', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <input type="text" name="important_date_heading" value="<?php echo esc_attr( $calendar['important_date_heading'] ) ?>" class="wpsc-text large-text">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpsc-builder-option-group">
                        <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-calendar-structure-option-items"><?php echo __( 'Calendar Structure', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
                        <div id="wpsc-builder-calendar-structure-option-items" class="wpsc-builder-option-items" style="display:none">
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Start of The Year', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select id="wpsc-builder-field-start-year" name="start_year" class="wpsc-select">
                                        <?php foreach ( $month_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['start_year'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Number of Months', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select id="wpsc-builder-field-num-months" name="num_months" class="wpsc-select">
                                        <?php foreach ( $num_month_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['num_months'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Default Range of The Month', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select id="wpsc-builder-field-default-month-range" name="default_month_range" class="wpsc-select">
                                        <?php foreach ( $default_month_range_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['default_month_range'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div id="wpsc-custom-default-year-option" class="wpsc-builder-option" style="<?php if ( 'current' === $calendar['default_month_range'] ) echo 'display:none' ?>">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Custom Default Year', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select id="wpsc-builder-field-custom-default-year" name="custom_default_year" class="wpsc-select">
                                        <?php foreach ( $custom_default_year_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['custom_default_year'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div id="wpsc-custom-default-month-range-option" class="wpsc-builder-option" style="<?php if ( 'current' === $calendar['default_month_range'] ) echo 'display:none' ?>">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Custom Default Range of The Month', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select id="wpsc-builder-field-custom-default-range" name="custom_default_month_range" class="wpsc-select">
                                        <?php foreach ( $custom_default_month_range_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['custom_default_month_range'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="wpsc-builder-option-group">
                        <div class="wpsc-builder-option-heading"><button type="button" data-target="wpsc-builder-calendar-appearance-option-items"><?php echo __( 'Appearance', 'wp-school-calendar' ) ?><span class="wpsc-builder-option-icon"></span></button></div>
                        <div id="wpsc-builder-calendar-appearance-option-items" class="wpsc-builder-option-items" style="display:none">
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Theme', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select name="theme" class="wpsc-select">
                                        <?php foreach ( $theme_options as $option_key => $option ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['theme'] ) ?><?php if ( 'N' === $option['enable'] ) echo ' disabled="disabled"' ?>><?php echo esc_html( $option['name'] ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Number of Columns', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select name="num_columns" class="wpsc-select">
                                        <?php foreach ( $num_column_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['num_columns'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Week Starts On', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select name="week_start" class="wpsc-select">
                                        <?php foreach ( $weekday_options as $key => $weekday ): ?>
                                        <option value="<?php echo esc_attr( $key ) ?>"<?php selected( $calendar['week_start'], $key ) ?>><?php echo esc_html( $weekday ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Weekday', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <input id="wpsc-builder-field-weekday" type="hidden" name="weekday" value="<?php echo esc_attr( implode( ',', $calendar['weekday'] ) ) ?>">
                                    <select multiple="multiple" class="wpsc-select wpsc-select-multiple" data-field-target="wpsc-builder-field-weekday">
                                        <?php foreach ( $weekday_options as $key => $weekday ): ?>
                                        <option value="<?php echo esc_attr( $key ) ?>"<?php selected( in_array( $key, $calendar['weekday'] ) ) ?>><?php echo esc_html( $weekday ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Day Format', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select name="day_format" class="wpsc-select">
                                        <?php foreach ( $day_format_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['day_format'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="wpsc-builder-option">
                                <div class="wpsc-builder-option-label"><?php echo __( 'Date Format', 'wp-school-calendar' ) ?></div>
                                <div class="wpsc-builder-option-field">
                                    <select name="date_format" class="wpsc-select">
                                        <?php foreach ( $date_format_options as $option_key => $option_name ): ?>
                                        <option value="<?php echo esc_attr( $option_key ) ?>"<?php selected( $option_key, $calendar['date_format'] ) ?>><?php echo esc_html( $option_name ) ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                                <div class="wpsc-builder-option-field">
                                    <input type="hidden" name="show_year" value="N">
                                    <label for="show-year"><input id="show-year" type="checkbox" name="show_year" value="Y" <?php checked( 'Y', $calendar['show_year'] ) ?> class="wpsc-checkbox"> 
                                        <?php echo esc_html__( 'Display Year on Date Format', 'wp-school-calendar' ) ?></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php do_action( 'wpsc_builder_options', $calendar ) ?>
                </div>
                
                </form>
            </div>
        </div>
        <?php
    }
    
    public function ajax_save_calendar() {
        check_ajax_referer( 'wpsc_admin', 'nonce' );
        
        $args = array(
            'calendar_id' => intval( $_POST['calendar_id'] ),
            'name'        => sanitize_text_field( $_POST['name'] ),
            'calendar_options' => apply_filters( 'wpsc_valid_calendar_options', array(
                'theme'                       => wpsc_sanitize_calendar_theme( $_POST['theme'] ),
                'groups'                      => wpsc_sanitize_multiple_groups( $_POST['groups'] ),
                'include_no_groups'           => wpsc_sanitize_checkbox( $_POST['include_no_groups'] ),
                'categories'                  => wpsc_sanitize_multiple_categories( $_POST['categories'] ),
                'num_months'                  => wpsc_sanitize_num_months( $_POST['num_months'] ),
                'start_year'                  => wpsc_sanitize_month( $_POST['start_year'] ),
                'default_month_range'         => wpsc_sanitize_default_month_range( $_POST['default_month_range'] ),
                'custom_default_year'         => wpsc_sanitize_custom_default_year( $_POST['custom_default_year'], $_POST['start_year'] ),
                'custom_default_month_range'  => wpsc_sanitize_custom_default_month_range( $_POST['custom_default_month_range'], $_POST['start_year'], $_POST['num_months'] ),
                'num_columns'                 => wpsc_sanitize_num_columns( $_POST['num_columns'] ),
                'week_start'                  => wpsc_sanitize_week_start( $_POST['week_start'] ),
                'weekday'                     => wpsc_sanitize_multiple_weekday( $_POST['weekday'] ),
                'day_format'                  => wpsc_sanitize_day_format( $_POST['day_format'] ),
                'date_format'                 => wpsc_sanitize_date_format( $_POST['date_format'] ),
                'show_year'                   => wpsc_sanitize_checkbox( $_POST['show_year'] ),
                'show_important_date_cats'    => wpsc_sanitize_checkbox( $_POST['show_important_date_cats'] ),
                'show_important_date_listing' => wpsc_sanitize_checkbox( $_POST['show_important_date_listing'] ),
                'important_date_heading'      => sanitize_text_field( $_POST['important_date_heading'] ),
            ) )
        );

        $result = array();
        $calendar_id = $args['calendar_id'];
        
        if ( empty( $calendar_id ) ) {
            $calendar_id = wpsc_add_new_calendar( $args );
            
            $result['replace'] = array( 
                'calendar_id' => $calendar_id,
                'title'       => __( 'WP School Calendar Builder', 'wp-school-calendar' ), 
                'url'         => add_query_arg( 'edit', $calendar_id, admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' ) ) 
            );
        } else {
            wpsc_update_calendar( $args );
        }

        wp_send_json_success( $result );
    }
    
    public function ajax_reload_preview() {
        check_ajax_referer( 'wpsc_admin', 'nonce' );
        
        $calendar_options = apply_filters( 'wpsc_valid_calendar_options', array(
            'theme'                       => wpsc_sanitize_calendar_theme( $_POST['theme'] ),
            'groups'                      => wpsc_sanitize_multiple_groups( $_POST['groups'] ),
            'include_no_groups'           => wpsc_sanitize_checkbox( $_POST['include_no_groups'] ),
            'categories'                  => wpsc_sanitize_multiple_categories( $_POST['categories'] ),
            'num_months'                  => wpsc_sanitize_num_months( $_POST['num_months'] ),
            'start_year'                  => wpsc_sanitize_month( $_POST['start_year'] ),
            'default_month_range'         => wpsc_sanitize_default_month_range( $_POST['default_month_range'] ),
            'custom_default_year'         => wpsc_sanitize_custom_default_year( $_POST['custom_default_year'], $_POST['start_year'] ),
            'custom_default_month_range'  => wpsc_sanitize_custom_default_month_range( $_POST['custom_default_month_range'], $_POST['start_year'], $_POST['num_months'] ),
            'num_columns'                 => wpsc_sanitize_num_columns( $_POST['num_columns'] ),
            'week_start'                  => wpsc_sanitize_week_start( $_POST['week_start'] ),
            'weekday'                     => wpsc_sanitize_multiple_weekday( $_POST['weekday'] ),
            'day_format'                  => wpsc_sanitize_day_format( $_POST['day_format'] ),
            'date_format'                 => wpsc_sanitize_date_format( $_POST['date_format'] ),
            'show_year'                   => wpsc_sanitize_checkbox( $_POST['show_year'] ),
            'show_important_date_cats'    => wpsc_sanitize_checkbox( $_POST['show_important_date_cats'] ),
            'show_important_date_listing' => wpsc_sanitize_checkbox( $_POST['show_important_date_listing'] ),
            'important_date_heading'      => sanitize_text_field( $_POST['important_date_heading'] ),
        ) );
        
        $calendar = array_merge( array(
            'calendar_id' => intval( $_POST['calendar_id'] ),
            'name'        => sanitize_text_field( $_POST['name'] ),
        ), $calendar_options );

        $result = array();
        
        if ( empty( intval( $_POST['calendar_id'] ) ) ) {
            $args = array(
                'calendar_id'      => intval( $_POST['calendar_id'] ),
                'name'             => sanitize_text_field( $_POST['name'] ),
                'calendar_options' => $calendar_options
            );
            
            $calendar_id = wpsc_add_new_calendar( $args );
            
            $calendar = wpsc_get_calendar( $calendar_id );
            
            $result['replace'] = array( 
                'calendar_id' => $calendar_id,
                'title'       => __( 'WP School Calendar Builder', 'wp-school-calendar' ), 
                'url'         => add_query_arg( 'edit', $calendar_id, admin_url( 'edit.php?post_type=school_calendar&page=wpsc-builder' ) ) 
            );
        }
        
        $result['content'] = wpsc_render_calendar( $calendar, '', '', true );

        wp_send_json_success( $result );
    }
    
    public function load_important_date_color_style() {
        $screen = get_current_screen();

        if ( isset( $screen->post_type ) && 'school_calendar' === $screen->post_type && isset( $screen->base ) && 'school_calendar_page_wpsc-builder' === $screen->base ) {
            echo '<style>' . "\n";
            echo wpsc_get_important_date_single_color();
            echo '</style>' . "\n";
        }
    }
    
}

WP_School_Calendar_Builder::instance();