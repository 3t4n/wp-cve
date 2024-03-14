<?php
/*
Plugin Name: Ultimate floating widgets
Plugin URI: https://www.aakashweb.com/wordpress-plugins/ultimate-floating-widgets/
Version: 2.8
Author: Aakash Chakravarthy
Author URI: https://www.aakashweb.com/
Description: Add WordPress widgets to sticky/floating popup bubble and flyout sidebars.
*/

define( 'UFW_VERSION', '2.8' );
define( 'UFW_PATH', plugin_dir_path( __FILE__ ) ); // All have trailing slash
define( 'UFW_URL', plugin_dir_url( __FILE__ ) );
define( 'UFW_ADMIN_URL', trailingslashit( plugin_dir_url( __FILE__ ) . 'admin' ) );
define( 'UFW_BASE_NAME', plugin_basename( __FILE__ ) );

class Ultimate_Floating_Widgets{
    
    public static function init(){
        
        add_action( 'widgets_init', array( __CLASS__, 'register_widget_box' ), 99 );
        
        self::includes();
        
    }
    
    public static function includes(){
        
        include_once( UFW_PATH . 'includes/helpers.php' );
        include_once( UFW_PATH . 'includes/display.php' );
        
        include_once( UFW_PATH . 'admin/wp-optionizer/index.php' );
        include_once( UFW_PATH . 'admin/admin.php' );
        include_once( UFW_PATH . 'admin/edit-form.php' );
        
    }
    
    public static function list_all(){
        
        $widget_boxes = get_option( 'ufw_data' );
        
        return empty( $widget_boxes ) ? array() : $widget_boxes;
        
    }
    
    public static function defaults(){
        
        return array(
            'name' => '',
            'title' => '',
            'status' => 'enabled',
            'init_state' => 'closed',
            'init_state_m' => 'closed',
            'type' => 'popup',
            'pp_position' => 'bottom_right',
            'fo_position' => 'left',
            'trigger' => 'button',
            'auto_trigger' => '60',
            
            'pp_anim_open' => 'fadeInRight',
            'pp_anim_close' => 'fadeOutRight',
            'fo_anim_open' => 'slideInRight',
            'fo_anim_close' => 'slideOutRight',
            'anim_duration' => '0.5',
            'fo_btn_position' => 'br',

            'save_state' => 'no',
            'save_state_duration' => '0',

            'auto_close' => '',
            'auto_close_time' => '',
            'wb_close_btn' => 'no',
            'wb_close_icon' => 'fas fa-times',

            'wb_width' => '400px',
            'wb_height' => '450px',
            'wb_bg_color' => '#ffffff',
            'wb_bdr_size' => '1',
            'wb_bdr_color' => '#ececec',
            'wb_text_color' => '',
            'wb_bdr_radius' => '0',
            
            'btn_type' => 'icon',
            'btn_icon' => 'fas fa-bars',
            'btn_icon_size' => 'sd',
            'btn_size' => '48',
            'btn_text' => '',
            'btn_bg_color' => '#dd3333',
            'btn_bdr_size' => '0',
            'btn_bdr_color' => 'transparent',
            'btn_text_color' => '#ffffff',
            'btn_radius' => '100',
            'btn_reveal' => '0',
            'btn_close_icon' => 'fas fa-times',
            'btn_close_text' => 'Close',
            
            'loc_rules_config' => 'basic',
            'loc_rules_basic' => array(),
            'loc_rules' => array(
                'type' => 'show_all',
                'rule' => 'W10=',
                'devices' => 'all',
            ),

            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '',
            'after_title' => '',
            'additional_css' => '',
            
            'modified' => ''
            
        );
        
    }
    
    public static function register_widget_box(){
        
        $widget_boxes = self::list_all();
        $sidebar_tmpl = UFW_Helpers::sidebar_template();
        
        foreach( $widget_boxes as $wb_id => $opts ){
            
            $opts = wp_parse_args( $opts, self::defaults() );
            self::register_sidebar( 'ufw_' . $wb_id, $opts[ 'name' ], $opts, $sidebar_tmpl );

        }
        
    }

    public static function register_sidebar( $id, $name, $opts, $sidebar_tmpl ){

        register_sidebar( array(
            'id' => $id,
            'name' => $name,
            'description' => $opts[ 'title' ],
            'class' => $id,
            'before_widget' => empty( $opts[ 'before_widget' ] ) ? $sidebar_tmpl[ 'before_widget' ] : $opts[ 'before_widget' ],
            'after_widget' => empty( $opts[ 'after_widget' ] ) ? $sidebar_tmpl[ 'after_widget' ] : $opts[ 'after_widget' ],
            'before_title' => empty( $opts[ 'before_title' ] ) ? $sidebar_tmpl[ 'before_title' ] : $opts[ 'before_title' ],
            'after_title' => empty( $opts[ 'after_title' ] ) ? $sidebar_tmpl[ 'after_title' ] : $opts[ 'after_title' ],
        ));

    }

}

Ultimate_Floating_Widgets::init();

?>