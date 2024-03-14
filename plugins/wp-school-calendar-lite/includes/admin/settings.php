<?php
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
    exit;
}

class WP_School_Calendar_Settings {

    private static $_instance = NULL;
    public $action = NULL;

    /**
     * Initialize all variables, filters and actions
     */
    public function __construct() {
        add_action( 'admin_init', array( $this, 'settings_init' ) );
        add_action( 'admin_menu', array( $this, 'admin_menu' ), 90 );
        
        add_filter( 'wpsc_admin_script_args', array( $this, 'admin_script_args' ) );
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
     * Add Settings menu page
     * 
     * @since 1.0
     */
    public function admin_menu() {
        add_submenu_page( 'edit.php?post_type=school_calendar', __( 'Calendar Settings', 'wp-school-calendar' ), __( 'Settings', 'wp-school-calendar' ), 'manage_options', 'wpsc-settings', array( $this, 'admin_page' ) );
    }
    
    /**
     * Add Settings page
     * 
     * @since 1.0
     */
    public function admin_page() {
        $categories = wpsc_get_categories();
        
        $css_location_types = array( 
            'site'   => __( 'Entire Site', 'wp-school-calendar' ), 
            'single' => __( 'Single Posts (select below)', 'wp-school-calendar' ) 
        );
        ?>
        <div class="wrap">
            <h2><?php _e( 'Calendar Settings', 'wp-school-calendar' );?></h2>
            
            <form method="post" action="options.php">
                <?php settings_fields( 'wpsc_options' ); ?>
                <div id="wpsc-settings-general" class="wpsc-settings-page">
                    <table class="form-table">
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__( 'Default Category', 'wp-school-calendar' );?></th>
                            <td class="forminp">
                                <select name="wpsc_options[default_category]" class="wpsc-select">
                                    <?php foreach ( $categories as $category ): ?>
                                    <option value="<?php echo esc_attr( $category['category_id'] ) ?>"<?php selected( $category['category_id'], wpsc_settings_value( 'default_category' ) ) ?>><?php echo esc_html( $category['name'] ) ?></option>
                                    <?php endforeach ?>
                                </select>
                                <p class="description"><?php echo __( 'This option determines default category of important date.', 'wp-school-calendar' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__( 'CSS Location', 'wp-school-calendar' );?></th>
                            <td class="forminp">
                                <div class="wpsc-option-location-type">
                                <?php foreach ( $css_location_types as $id => $name ): ?>
                                <p><input type="radio" name="wpsc_options[css_location_type]" id="css_location_type-<?php echo esc_attr( $id ) ?>" value="<?php echo esc_attr( $id ) ?>"<?php checked( $id, wpsc_settings_value( 'css_location_type' ) ) ?>> <label for="css_location_type-<?php echo esc_attr( $id ) ?>"><?php echo esc_html( $name ) ?></label></p>
                                <?php endforeach ?>
                                </div>
                                <div class="wpsc-option-location-posts" style="margin-top:10px;">
                                <select multiple="multiple" name="wpsc_options[css_location_posts][]" class="wpsc-select-location-posts"></select>
                                </div>
                                <p class="description"><?php echo __( 'This option only applies to calendar styles, not widgets.', 'wp-school-calendar' ) ?></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__( 'External Color Style', 'wp-school-calendar' );?></th>
                            <td class="forminp">
                                <p><label for="external-color-style"><input id="external-color-style" type="checkbox" name="wpsc_options[external_color_style]" value="Y" <?php checked( 'Y', wpsc_settings_value( 'external_color_style' ) ) ?>> 
                                    <?php echo esc_html__( 'Check this if you would like to use external color style for important date.', 'wp-school-calendar' ) ?></label></p>
                            </td>
                        </tr>
                        <tr valign="top">
                            <th scope="row"><?php echo esc_html__( 'School Calendar Credit', 'wp-school-calendar' );?></th>
                            <td class="forminp">
                                <p><label for="credit"><input id="credit" type="checkbox" name="wpsc_options[credit]" value="Y" <?php checked( 'Y', wpsc_settings_value( 'credit' ) ) ?>> 
                                    <?php echo esc_html__( 'Check this if you would like to display "Powered by WP School Calendar" in your calendar.', 'wp-school-calendar' ) ?></label></p>
                            </td>
                        </tr>
                    </table>
                </div>
                
                <?php do_action( 'wpsc_settings_page' ) ?>
                
                <p class="submit">
                    <input type="submit" class="button-primary" value="<?php echo esc_attr__( 'Save Changes', 'wp-school-calendar' ); ?>" />
                </p>
            </form>
        </div>
        
        <?php
    }
    
    /**
     * Register settings
     * 
     * @since 1.0
     */
    public function settings_init() {
        if ( delete_transient( 'wpsc_flush_rules' ) ) {
            flush_rewrite_rules();
        }
        
        register_setting( 'wpsc_options', 'wpsc_options', array( $this, 'settings_sanitize' ) );
    }
    
    /**
     * Sanitize the setting input
     * 
     * @since 1.0
     * 
     * @param array $input Settings input
     * @return array Sanitized input
     */
    public function settings_sanitize( $input ) {
        $options  = get_option( 'wpsc_options', wpsc_get_default_settings() );
        $settings = require WPSC_PLUGIN_DIR . 'config/plugin-settings.php';
        
        $post_ids = array();
        
        $post_type_posts = $this->get_post_type_posts();
        
        foreach ( $post_type_posts as $post_type => $post_type_obj ) {
            $posts = $post_type_obj['posts'];
            
            foreach ( $posts as $post ) {
                $post_ids[] = $post['post_id'];
            }
        }
        
        $settings['css_location_posts']['options'] = $post_ids;
        
        foreach ( $settings as $key => $setting ) {
            if ( 'text' === $setting['type'] ) {
                $options[$key] = sanitize_text_field( $input[$key] );
            } elseif ( 'select' === $setting['type'] || 'radio' === $setting['type'] ) {
                if ( in_array( $input[$key], $setting['options'] ) ) {
                    $options[$key] = $input[$key];
                } else {
                    $options[$key] = $setting['default_value'];
                }
            } elseif ( 'multiple' === $setting['type'] ) {
                $valid_values = array();
                
                foreach ( $input[$key] as $val ) {
                    if ( in_array( $val, $setting['options'] ) ) {
                        $valid_values[] = $val;
                    }
                }
                
                $options[$key] = $valid_values;
            } elseif ( 'checkbox' === $setting['type'] ) {
                if ( isset( $input[$key] ) && 'Y' === $input[$key] ) {
                    $options[$key] = 'Y';
                } else {
                    $options[$key] = 'N';
                }
            } else {
                $options[$key] = $input[$key];
            }
        }
        
        set_transient( 'wpsc_flush_rules', 'Y' );
        
        return $options;
    }
    
    private function get_post_types() {
        $post_types = array();
        
        $objs = get_post_types(
            array(
                'public' => true,
            ), 'objects'
        );
        
        foreach ( $objs as $post_type_slug => $post_type ) {
            if ( 'attachment' === $post_type_slug ) {
                continue;
            }
            
            $post_types[] = $post_type_slug;
        }
        
        return $post_types;
    }
    
    private function get_post_type_posts() {
        $post_type_posts = array();
        
        $post_types = $this->get_post_types();
        
        if ( ! is_array( $post_types ) ) {
            $post_types = array( $post_types );
        }

        foreach ( $post_types as $post_type ) {
            global $wpdb;

            $post_status = array( 'publish', 'future', 'draft', 'pending', 'private' );

            $object = get_post_type_object( $post_type );

            $post_type_posts[ $post_type ] = array(
                'slug'  => $post_type,
                'label' => $object->label,
                'posts' => array(),
            );

            $format = implode( ', ', array_fill( 0, count( $post_status ), '%s' ) );
            $query = sprintf( "SELECT ID, post_title, post_status from $wpdb->posts where post_type = '%s' AND post_status IN(%s) ORDER BY post_title", $post_type, $format );
            $objs = $wpdb->get_results( $wpdb->prepare( $query, $post_status ) );

            foreach ( $objs as $obj ) {
                $_post_status = ( 'publish' === $obj->post_status ) ? '' : sprintf( ' &mdash; %s', ucfirst( $obj->post_status ) );
                $title = ( '' !== $obj->post_title ) ? esc_attr( $obj->post_title ) : $post_type . '-' . $obj->ID;
                $post_type_posts[ $post_type ]['posts'][] = array(
                    'post_id'    => $obj->ID,
                    'post_title' => $title . $_post_status
                );
            }
        }

        return $post_type_posts;
    }
    
    public function admin_script_args( $args ) {
        if ( function_exists( 'get_current_screen' ) ) {
            $screen = get_current_screen();
            
            if ( isset( $screen->post_type ) && 'school_calendar' === $screen->post_type && isset( $screen->base ) && 'school_calendar_page_wpsc-settings' === $screen->base ) {
                $post_type_posts = $this->get_post_type_posts();
                $css_location_posts = wpsc_settings_value( 'css_location_posts' );
                
                $data = array();
                
                foreach ( $post_type_posts as $post_type => $post_type_obj ) {
                    $children = array();
                    
                    $posts = $post_type_obj['posts'];
                    
                    foreach ( $posts as $post ) {
                        if ( in_array( $post['post_id'], $css_location_posts ) ) {
                            $children[] = array(
                                'id'       => $post['post_id'],
                                'text'     => $post['post_title'],
                                'selected' => true
                            );
                        } else {
                            $children[] = array(
                                'id'   => $post['post_id'],
                                'text' => $post['post_title']
                            );
                        }
                    }
                    
                    $data[] = array(
                        'text'     => $post_type_obj['label'],
                        'children' => $children
                    );
                }
                
                $args['post_type_posts'] = $data;
                $args['placeholder'] = __( 'Click here to choose single posts', 'wp-school-calendar' );
            }
        }
        
        return $args;
    }
}

WP_School_Calendar_Settings::instance();