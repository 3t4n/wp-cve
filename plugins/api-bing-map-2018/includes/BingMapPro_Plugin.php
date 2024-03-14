<?php

namespace BingMapPro_Plugin;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once( 'BingMapPro_LifeCycle.php');
include_once( 'BingMapPro_MasterMaps.php');

use BingMapPro_LifeCycle;

class BingMapPro_Plugin extends BingMapPro_LifeCycle\BingMapPro_LifeCycle{
    
    public function getPluginDisplayName(){
        return 'WP Bing Map Pro';
    }

    protected function getMainPluginFileName(){
        return 'wp-bing-map-pro.php';
    }

    protected function installDatabaseTables(){
        global $wpdb;
       
        $table_maps             = $this->prefixTableName('maps');
        $table_pins             = $this->prefixTableName('pins');
        $table_map_pins         = $this->prefixTableName('map_pins');
        $table_shapes           = $this->prefixTableName('shapes');
        $table_map_shapes       = $this->prefixTableName('map_shapes');
        $table_map_shortcodes   = $this->prefixTableName('map_shortcodes'); 

        $charset_collate    = $wpdb->get_charset_collate();
 
        $sql_bmp_maps =  "CREATE TABLE IF NOT EXISTS $table_maps (
                id mediumint(4) NOT NULL auto_increment,
                map_title varchar(100) NOT NULL default '',
                map_width varchar(5) NOT NULL default '100',
                map_height varchar(5) NOT NULL default '400',
                map_width_type varchar(5) NOT NULL default '%',
                map_height_type varchar(5) NOT NULL default 'px',
                map_start_lat varchar(100) NOT NULL default '53.350140',
                map_start_long varchar(100) NOT NULL default '-6.266155',
                map_zoom tinyint(3) NOT NULL default '2',
                map_type tinyint(1) NOT NULL default '0', 
                map_active boolean NOT NULL default '1',
                styling_json longtext default '',
                styling_enabled boolean NOT NULL default '0',
                alignment tinyint(5) default '0',
                kml longtext NOT NULL default '',
                html_class varchar(50) default '',
                map_disabled boolean NOT NULL default '0',
                map_shortcode varchar(100) default '',
                map_refresh boolean NOT NULL default '0',
                compact_nav boolean NOT NULL default '0',
                context_menu boolean NOT NULL default '0',
                show_pan_btns boolean NOT NULL default '0',
                look_at_location boolean NOT NULL default '0',
                bicycle boolean NOT NULL default '0',
                traffic boolean NOT NULL default '0',
                custom_map_style boolean NOT NULL default '0',                
                toggle_fullscreen boolean NOT NULL default '0',
                disable_mousewheel boolean NOT NULL default '0',
                disable_zoom boolean NOT NULL default '0',
                show_user_location boolean NOT NULL default '0',
                street_view boolean NOT NULL default '0',
                custom_map_style_script longtext NOT NULL default '',                
                other_settings longtext NOT NULL default '',
                cluster smallint(3) NOT NULL default '-1',
                created_at datetime default CURRENT_TIMESTAMP,             
                PRIMARY KEY  (id),
                KEY map_active (map_active) 
                ) $charset_collate;
                ";
                             

        $sql_bmp_pins = "CREATE TABLE IF NOT EXISTS $table_pins ( 
                        id mediumint(4) NOT NULL auto_increment,                       
                        active boolean NOT NULL default '1',
                        pin_name varchar(255) NOT NULL default '',
                        pin_lat varchar(100) NOT NULL default '',
                        pin_long varchar(100) NOT NULL default '',
                        pin_address varchar(255) NOT NULL default '',                    
                        pin_title varchar(255) NOT NULL default '',
                        pin_desc text NOT NULL default '',
                        pin_custom_url varchar(255) NOT NULL default '',
                        pin_iframe_url varchar(255) NOT NULL default '',
                        pin_image_one varchar(255) NOT NULL default '',
                        pin_image_two varchar(255) NOT NULL default '',
                        pin_image_three varchar(255) NOT NULL default '',
                        pin_image_four varchar(255) NOT NULL default '',
                        pin_link_one varchar(255) NOT NULL default '',
                        pin_link_two varchar(255) NOT NULL default '',
                        pin_link_three varchar(255) NOT NULL default '',    
                        pin_link_four varchar(255) NOT NULL default '',
                        pin_link_one_txt varchar(50) NOT NULL default '',
                        pin_link_one_open varchar(10) NOT NULL default '',
                        pin_link_two_txt varchar(50) NOT NULL default '',
                        pin_link_two_open varchar(10) NOT NULL default '',
                        pin_link_three_txt varchar(50) NOT NULL default '',
                        pin_link_three_open varchar(10) NOT NULL default '',
                        pin_link_four_txt varchar(50) NOT NULL default '',
                        pin_link_four_open varchar(10) NOT NULL default '',
                        img_desc varchar(200) NOT NULL default '',
                        icon_link varchar(200) NOT NULL default '',
                        icon varchar(100) NOT NULL default '',
                        animation tinyint(1) NOT NULL default '0',
                        category tinyint(1) NOT NULL default '0',
                        pin_type tinyint(2) NOT NULL default '0',
                        direction_to_here boolean NOT NULL default '0',
                        data_json longtext NOT NULL default '',
                        other_settings text NOT NULL default '',
                        created_at datetime NOT NULL default CURRENT_TIMESTAMP,
                        approved boolean NOT NULL default '1',
                        approved_by varchar(50) NOT NULL default '',
                        text_pin varchar(50) NOT NULL default '',
                        use_text_pin boolean NOT NULL default '0',
                        groupid smallint(3) NOT NULL default '0',
                        tag varchar(255) NOT NULL default '',
                        PRIMARY KEY  (id),
                        KEY active (active),
                        KEY approved (approved)
                    ) $charset_collate;
					";
    
        $sql_bmp_map_pins = "CREATE TABLE IF NOT EXISTS $table_map_pins( 
                        id mediumint(4) NOT NULL auto_increment,
                        map_id mediumint(4) NOT NULL default '0',
                        pin_id mediumint(4) NOT NULL default '0',
                        pin_active boolean NOT NULL default '1',
                        created_at datetime NOT NULL default CURRENT_TIMESTAMP,
                        PRIMARY KEY  (id),
                        KEY map_id (map_id),
                        KEY pin_id (pin_id),
                        KEY pin_active (pin_active)
                    ) $charset_collate;
                    "; 
                    
        $sql_bmp_shapes = "CREATE TABLE IF NOT EXISTS $table_shapes(
                        id mediumint(4) NOT NULL auto_increment,
                        s_name varchar(50) NOT NULL default '',
                        s_type varchar(50) NOT NULL default '',                         
                        bodycolor varchar(50) NOT NULL default '',
                        strokecolor varchar(50) NOT NULL default '',
                        stroketickness mediumint(2) NOT NULL default '1',
                        bodyopacity float NOT NULL default '0.4',                        
                        shapedata text NOT NULL default '',
                        s_style text NOT NULL default '',
                        infotype varchar(15) NOT NULL default '',
                        infosimpletitle varchar(250) NOT NULL default '',
                        infosimpledesc text NOT NULL default '',
                        infoadvanced text NOT NULL default '',
                        caption varchar(250) NOT NULL default '',
                        maplat varchar(50) NOT NULL default '',
                        maplong varchar(50) NOT NULL default '',
                        mapzoom tinyint(1) NOT NULL default '12',
                        maptype varchar(20) NOT NULL default 'r',
                        tickboxone boolean NOT NULL DEFAULT '0',
                        tickboxtwo boolean NOT NULL DEFAULT '0',
                        tickboxthree boolean NOT NULL DEFAULT '1',
                        textfieldone varchar(30) NOT NULL DEFAULT '',
                        textfiledtwo varchar(30) NOT NULL DEFAULT '',
                        textone text NOT NULL DEFAULT '',
                        texttwo text NOT NULL DEFAULT '',
                        textthree text NOT NULL DEFAULT '',
                        textfour text NOT NULL DEFAULT '',
                        s_import varchar(20) NOT NULL DEFAULT '',
                        s_tags varchar(200) NOT NULL DEFAULT '',
                        s_caption varchar(100) NOT NULL DEFAULT '',
                        created_at datetime NOT NULL default CURRENT_TIMESTAMP,
                        created_by varchar(40) NOT NULL DEFAULT '',
                        PRIMARY KEY  (id)
                        ) $charset_collate;
                    ";
        $sql_bmp_map_shapes = "CREATE TABLE IF NOT EXISTS $table_map_shapes(
                            id mediumint(4) NOT NULL auto_increment,
                            map_id mediumint(4) NOT NULL default '0',
                            shape_id mediumint(4) NOT NULL default '0',
                            shape_active boolean NOT NULL default '1',
                            created_at datetime NOT NULL default CURRENT_TIMESTAMP,
                            PRIMARY KEY  (id),
                            KEY map_id (map_id),
                            KEY shape_id (shape_id),
                            KEY shape_active (shape_active)
                            ) $charset_collate;            
                    ";
        $sql_bmp_map_shortcodes = "CREATE TABLE IF NOT EXISTS $table_map_shortcodes(
                                    id mediumint(4) NOT NULL auto_increment,
                                    map_id mediumint(4) NOT NULL DEFAULT '0',
                                    shortcode varchar(200) NOT NULL DEFAULT '',
                                    s_lat varchar(50) NOT NULL DEFAULT '',
                                    s_long varchar(50) NOT NULL DEFAULT '',
                                    s_zoom mediumint(2) NOT NULL DEFAULT '0',
                                    s_checkone boolean NOT NULL DEFAULT '0',
                                    s_string varchar(200) NOT NULL DEFAULT '',
                                    s_text text NOT NULL DEFAULT '',
                                    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                    PRIMARY KEY  (id),
                                    KEY map_id (map_id)
                                    ) $charset_collate; ";


        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta( $sql_bmp_maps ); 
        dbDelta( $sql_bmp_pins ); 
        dbDelta( $sql_bmp_map_pins );  
        dbDelta( $sql_bmp_shapes );
        dbDelta( $sql_bmp_map_shapes );
        dbDelta( $sql_bmp_map_shortcodes );

       
        $bmp_maps_qry = $wpdb->get_results(" SHOW COLUMNS FROM {$table_maps} " );
        $bmp_pins_qry = $wpdb->get_results(" SHOW COLUMNS FROM {$table_pins} ");     
     
                
        if( ! $this->bmp_hasColumn( $bmp_maps_qry, 'cluster') ){          
            $wpdb->query("ALTER TABLE {$table_maps   } ADD cluster smallint(3) NOT NULL DEFAULT '-1' ");
        }
        
        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'approved_by') ){      
            $wpdb->query("ALTER TABLE {$table_pins} ADD approved_by varchar(50) NOT NULL DEFAULT '' ");      
        }
            
        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'text_pin') ){         
            $wpdb->query("ALTER TABLE {$table_pins} ADD text_pin varchar(50) NOT NULL DEFAULT '' ");     
        }
        
        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'use_text_pin') ){
            $wpdb->query("ALTER TABLE {$table_pins} ADD use_text_pin boolean NOT NULL DEFAULT '0' ");   
        }

        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'groupid') ){
            $wpdb->query("ALTER TABLE {$table_pins} ADD groupid smallint(3) NOT NULL DEFAULT '0' ");   
        }

        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'tag') ){
            $wpdb->query("ALTER TABLE {$table_pins} ADD tag varchar(255) NOT NULL DEFAULT '' ");
        }
            
        if( ! $this->bmp_hasColumn( $bmp_pins_qry, 'approved')  ){
            $wpdb->query("ALTER TABLE {$table_pins} ADD approved boolean NOT NULL DEFAULT '1' ");
            $wpdb->query("ALTER TABLE {$table_pins} ADD INDEX approved (approved) ");
        }
        
      
    }

    private function bmp_hasColumn( $arrColumns, $colName ){
        if( is_array( $arrColumns ) ){
            foreach( $arrColumns as $column ){
                if( $column->Field == $colName ){
                    return true;                
                }
            }
        }
        return false;
    }
  

    protected function uninstallDatabaseTables(){
        //        global $wpdb;
        //        $tableName = $this->prefixTableName('mytable');
        //        $wpdb->query("DROP TABLE IF EXISTS `$tableName`");
    }

    public function upgrade(){

    }

    public function bmp_check_capabilities(){
        add_action('plugins_loaded', array( &$this, 'bmp_set_user_cap') );        
    }

    public function addActionsAndFilters(){

        add_action('admin_menu', array(&$this, 'addSettingsSubMenuPage'));      

  
        add_action( 'wp_ajax_bmp_general_settings', array( &$this,  'bmp_general_settings' ) );

        add_action( 'wp_ajax_bmp_map_actions', array( &$this, 'bmp_map_actions') );

    //  save map async
        add_action( 'wp_ajax_bmp_save_map', array( &$this, 'bmp_save_map') );

        add_action('admin_enqueue_scripts', array( &$this, 'pw_load_scripts')); 
        
        add_action('wp_ajax_bmp_new_pin', array( &$this, 'bmp_new_pin') );

        add_action('wp_ajax_bmp_pin_actions', array( &$this, 'bmp_pin_actions') );

        add_action('wp_ajax_bmp_ajax_permissions', array( &$this, 'bmp_ajax_permissions') );

        add_action('wp_ajax_bmp_shape_actions', array ( &$this, 'bmp_shape_actions') );        

   //   add_action('init', array(&$this, 'bmp_shortcodes_init'));  
        add_action('init', array(&$this, 'bmp_shortcodes_init_refac'));   
   

        add_action( 'admin_footer', array( &$this, 'bmp_feedback_uninstall') );
        
        //add woo address suggest 
        add_action( 'wp_footer', array( &$this, 'bmp_woo_address_suggestion') );

    }


    public function pw_load_scripts( $hook ){
        global $pw_settings_page;
        global $pw_main_page;
        global $pw_settings_pins;
        global $pw_settings_shapes;
        global $pw_permissions_page;
        

        if( ( $hook != $pw_settings_page ) && 
            ( $hook != $pw_main_page) && 
            ( $hook != $pw_settings_pins) && 
            ( $hook != $pw_permissions_page ) && 
            ( $hook != $pw_settings_shapes ) )
            return;

        wp_enqueue_style('my-style-bootstrap-bmp', BMP_PLUGIN_URL.'/css/bootstrap.css' . '?v='.$this->getVersion() );
        wp_enqueue_style('my-style-fontawsome-bmp', BMP_PLUGIN_URL.'/css/fa/css/all.css'.'?v='.$this->getVersion() );
        wp_enqueue_style('my-style-bootstrap-toggle', BMP_PLUGIN_URL.'/css/bootstrap-toggle.min.css'. '?v='.$this->getVersion() );
        wp_enqueue_style('bmp-style', BMP_PLUGIN_URL.'/css/bmp-style.css' . '?v='.$this->getVersion() );
        wp_enqueue_style('my-style-bootstrap-table', BMP_PLUGIN_URL.'/css/bootstrap-table.css' . '?v='.$this->getVersion() );
                
        wp_enqueue_script('my_script_bootstrap-toggle', BMP_PLUGIN_URL.'/js/bootstrap-toggle.min.js' . '?v='.$this->getVersion() );
        wp_enqueue_script('my-script-bootstrap', BMP_PLUGIN_URL.'/js/bootstrap.min.js' . '?v='.$this->getVersion() );        
        wp_enqueue_script('my-script-bmp', BMP_PLUGIN_URL.'/js/bmp-script.js'.'?v=' . $this->getVersion() );  
        wp_enqueue_script('bmp-script-ajax',  BMP_PLUGIN_URL.'/js/bmp-script-ajax.js'.'?v=' . $this->getVersion() );
        wp_enqueue_script('bmp-script-message',  BMP_PLUGIN_URL.'/js/bmp_script_message.js'.'?v=' . $this->getVersion() );
        wp_enqueue_script('my-script-bmp-popper', BMP_PLUGIN_URL.'/js/popper.js'.'?v=' . $this->getVersion() );   
        wp_enqueue_script('my-script-bmp-bootstrap-table', BMP_PLUGIN_URL.'/js/bootstrap-table.js'.'?v=' . $this->getVersion() );  
             
        wp_enqueue_editor();

        if( $hook == $pw_settings_pins ){   
            add_filter( 'user_can_richedit' , '__return_true', 50 );
            wp_enqueue_script('my-script-bmp-pins', BMP_PLUGIN_URL.'/js/bmp-script-pins.js'.'?v=' . $this->getVersion() );
            wp_enqueue_script( 'jquery-ui-core', false, array('jquery'));
            return;
        }
        
        if( $hook == $pw_permissions_page ){
            wp_enqueue_script('my-script-bmp-permissions', BMP_PLUGIN_URL.'/js/bmp-script-permissions.js'.'?v=' . $this->getVersion() ); 
            return;
        }

        if( $hook == $pw_settings_shapes ){  
            add_filter( 'user_can_richedit' , '__return_true', 51 );                 
            wp_enqueue_script('my-script-bmp-shapes', BMP_PLUGIN_URL.'/js/bmp-script-shapes.js'.'?v=' . $this->getVersion() ); 
            return;
        }

        if( $hook == $pw_settings_page ){
            wp_enqueue_script('my-script-bmp-settings', BMP_PLUGIN_URL.'/js/bmp-script-settings.js'.'?v=' . $this->getVersion() ); 
            return; 
        }

        
        
    }
}