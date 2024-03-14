<?php

namespace BingMapPro_LifeCycle;

if( ! defined('ABSPATH') ) die('No Access to this page');

include_once('BingMapPro_InstallIndicator.php');
include_once( 'BingMapPro_MasterMaps.php');
include_once( 'BingMapPro_Maps.php' );
include_once( 'BingMapPro_GeneralSettings.php' );
include_once( 'BingMapPro_Pins.php');
include_once( 'BingMapPro_MasterPins.php');
include_once( 'BingMapPro_Permissions.php');
include_once( 'BingMapPro_Shapes.php');
include_once( 'BingMapPro_MasterShapes.php');
include_once( 'BingMapPro_Includes.php');

use BingMapPro_InstallIndicator;
use BingMapPro_MasterMaps;
use BingMapPro_Permissions;
use BingMapPro_OptionsManager;
use BingMapPro_Pins;
use BingMapPro_Shapes;
use BingMapPro_GeneralSettings;
use BingMapPro_Includes;
use BingMapPro_MasterPins\BingMapPro_Pin as BingMapPro_Pin;
use BingMapPro_MasterShapes\BingMapPro_Shape as BingMapPro_Shape;

class BingMapPro_LifeCycle extends BingMapPro_InstallIndicator\BingMapPro_InstallIndicator{
    private $bmp_api_key = '';  
    private $bmp_shortcode = 'bing-map-pro'; 

    public function install(){
        
        // Initialize Plugin Options
        $this->initOptions();

        // Initialize DB Tables used by the plugin
        $this->installDatabaseTables();

        // Other Plugin initialization - for the plugin writer to override as needed
        $this->otherInstall();

        // Record the installed version
        $this->saveInstalledVersion();

        // To avoid running install() more then once
        $this->markAsInstalled();
    }

    public function uninstall(){
        $this->otherUninstall();
        $this->unInstallDatabaseTables();
        $this->deleteSavedOptions();
        $this->markAsUnInstalled();
    }


    public function upgrade() {
    }


    public function activate() {
    }


    public function deactivate() {
        if( isset( $_GET['action'] ) && ( $_GET['action'] == 'deactivate') && isset( $_GET['feedback'] ) ){            

            try{
                
                $plugin_name = $_GET['plugin'];
                
                if( strpos( $plugin_name , 'wp-bing-map-pro.php') ){
                    $bmp_option = sanitize_text_field( $_GET['option'] );
                    $bmp_other  = sanitize_text_field( $_GET['other'] );
                    $bmp_email  = sanitize_email( $_GET['email'] );       
                                       

                    $bmp_to = 'developer@tuskcode.com';                

                    $optionsToText = [
                        "Lack of functionality",
                        "Too difficult to use",
                        "The plugin isn't working",
                        "The plugin isn't useful",
                        "Temporarily disabling or troubleshooting",
                        "Other"
                    ];
                    
                    if( strlen( $bmp_option ) > 0 ){
                        $bmp_option = intval( $bmp_option );
                        $bmp_option = $optionsToText[ $bmp_option ];
                    }
                
                    $bmp_subject = 'Feedback';
                    $bmp_message = "<h1>From: " . $bmp_email . "</h1>";
                    $bmp_message .= "<p> Option: ". $bmp_option."</p>";
                    $bmp_message .= "<p> Other: ". $bmp_other . "</p>";

                    $bmp_header = "From:feedback@wordpress.com \r\n";          
                    $bmp_header .= "MIME-Version: 1.0\r\n";
                    $bmp_header .= "Content-type: text/html\r\n";
                    
                    $is_sent =  mail( $bmp_to, $bmp_subject, $bmp_message, $bmp_header );
                   
               }

            }catch( Exception $e ){

            }
        
        }else{
    
        }
       
    }


    protected function initOptions() {
    }

    public function addActionsAndFilters() {
    }


    protected function installDatabaseTables() {
    }


    protected function unInstallDatabaseTables() {
    }


    protected function otherInstall() {
    }

  
    protected function otherUninstall() {
    }

    public function addSettingsSubMenuPage() {

        $this->addSettingMenuPageToSettings();
        $this->addSettingsSubMenuPins();
        $this->addSettingsSubMenuShapes();
        $this->addSettingsSubMenuSettings();
        $this->addSettingsSubMenuPermissions();
               
    }
    
    protected function requireExtraPluginFiles() {        
        require_once( ABSPATH . 'wp-admin/includes/plugin.php');
    }

 
    protected function getPluginMapsSlug() {
        return 'BingMapPro_Plugin_Maps';
    }

    protected function getPluginPermissionSlug(){
        return 'BingMapPro_Plugin_Permissions';
    }

    protected function getPluginPinsSlug(){
        return 'BingMapPro_Plugin_Pins';    
    }

    protected function getPluginShapesSlug(){
        return 'BingMapPro_Plugin_Shapes';    
    }
    

    protected function getSettingsSlug(){
        return 'BingMapPro_Plugin_Settings';    
    }

    protected function addSettingsSubMenuSettings(){
        global $pw_settings_page;
        $parent_slug = $this->getPluginMapsSlug();   
        $bmp_settings = esc_html__('Settings', 'bing-map-pro');        
       
        $pw_settings_page = add_submenu_page(   $parent_slug, 
                                                $bmp_settings, 
                                                $bmp_settings, 
                                                'edit_bing_map_pro', 
                                                $this->getSettingsSlug(), 
                                                array( &$this, 'generalSettings'),                                                
                                                120 
                                            );    
    }

    protected function addSettingsSubMenuPins(){
        global $pw_settings_pins;
        $bmp_pins = esc_html__('Pins', 'bing-map-pro');
        $pw_settings_pins = add_submenu_page( $this->getPluginMapsSlug(),
                                            $bmp_pins,
                                            $bmp_pins, 
                                            'edit_bing_map_pro',
                                            $this->getPluginPinsSlug(), 
                                            array( &$this, 'bmp_settingPins'),                                             
                                            110);    
    }

    
    protected function addSettingsSubMenuShapes(){
        global $pw_settings_shapes;
        $bmp_shapes = esc_html__('Shapes', 'bing-map-pro');
        $pw_settings_shapes = add_submenu_page( $this->getPluginMapsSlug(),
                                            $bmp_shapes,
                                            $bmp_shapes, 
                                            'edit_bing_map_pro',
                                            $this->getPluginShapesSlug(), 
                                            array( &$this, 'bmp_shapesPage'),                                             
                                            109);    
    }

    protected function addSettingsSubMenuPermissions(){
        global $pw_permissions_page; 
        $displayName = esc_html__('Permissions', 'bing-map-pro');

        $pw_permissions_page = add_submenu_page( 
                                                $this->getPluginMapsSlug(),
                                                $displayName,
                                                $displayName,
                                                'manage_options',
                                                $this->getPluginPermissionSlug(),
                                                array( &$this, 'bmp_show_permissions'),                                         
                                                122
                                                );

    }


    protected function addSettingMenuPageToSettings(){
        global $pw_main_page;

        $displayName = esc_html__('WP Bing Map Pro', 'bing-map-pro');      
        $pw_main_page = add_menu_page(  $displayName,
                                        $displayName,
                                        'edit_bing_map_pro',
                                        $this->getPluginMapsSlug(),
                                        array( &$this, 'bmp_showMaps'),
                                        BMP_PLUGIN_URL.'/images/icons/plugin-icon.png',
                                        200
                        );
    }

    
    
    private function bmp_has_user_cap(){
        //if user is admin -> grant access
        $bmp_result = false;
        $bmp_curr_user = wp_get_current_user();
        $bmp_user_caps = $bmp_curr_user->allcaps;
      
        if( ( ! is_user_logged_in() ) || ( ! is_admin() ) )
            return false;

        if( isset($bmp_user_caps['administrator']) && ($bmp_user_caps['administrator'] == true) ){ //administrator always has access to all the pages            
            $bmp_result = true;           
        }else if( isset( $bmp_user_caps['editor'] ) && ( $bmp_user_caps['editor'] == true ) && ( $this->get_editor_cap() ) ){           
            $bmp_result = true;                       
        }else if( isset( $bmp_user_caps['author'] ) && ($bmp_user_caps['author'] == true ) && ( $this->get_author_cap() ) ){          
            $bmp_result = true;
        }else if( isset( $bmp_user_caps['contributor'] ) && ($bmp_user_caps['contributor'] == true ) && ( $this->get_contributor_cap() ) ){          
            $bmp_result = true;
        }else{
            return false;
        }

        return $bmp_result;
    }

    public function bmp_set_user_cap(){
        $bmp_curr_user = wp_get_current_user();
        $bmp_user_caps = $bmp_curr_user->allcaps;
        $bmp_editor_cap = $this->get_editor_cap(); // $this->getOption('editor_cap', false );
        $bmp_author_cap = $this->get_author_cap(); // $this->getOption('author_cap', false );
        $bmp_contributor_cap = $this->get_contributor_cap(); // $this->getOption('contributor_cap', false ); 
    
        if( isset($bmp_user_caps['administrator']) && ($bmp_user_caps['administrator'] == true) ){ //administrator always has access to all the pages            
            $bmp_curr_user->add_cap('edit_bing_map_pro');       
        }else if( isset( $bmp_user_caps['editor'] ) && ($bmp_user_caps['editor'] == true ) ){
            if( $bmp_editor_cap )
                $bmp_curr_user->add_cap('edit_bing_map_pro');
            else
                $bmp_curr_user->remove_cap('edit_bing_map_pro');
        }else if( isset( $bmp_user_caps['author'] ) && ($bmp_user_caps['author'] == true ) ){
            if( $bmp_author_cap )
                $bmp_curr_user->add_cap('edit_bing_map_pro');
            else
                $bmp_curr_user->remove_cap('edit_bing_map_pro');
        }else if( isset( $bmp_user_caps['contributor'] ) && ($bmp_user_caps['contributor'] == true ) ){
            if( $bmp_contributor_cap )
                $bmp_curr_user->add_cap('edit_bing_map_pro');
            else 
                $bmp_curr_user->remove_cap('edit_bing_map_pro');

        }

    }

    protected function bmp_is_admin(){
        $bmp_result = false;
        $bmp_curr_user = wp_get_current_user();
        $bmp_user_caps = $bmp_curr_user->allcaps;

        if( isset($bmp_user_caps['administrator']) && ($bmp_user_caps['administrator'] == true) ) //administrator always has access to all the pages
            $bmp_result = true;

        return $bmp_result;
    }

    public function bmp_shortcodes_init_refac(){    
        
        add_shortcode( $this->bmp_shortcode, array( &$this, 'bmp_create_shortcode_byid') ); 
        
        //old code for shortcodes
        $this->bmp_shortcodes_init();
    }

    public function bmp_create_shortcode_byid( $atts, $content ){

        $map_id = isset( $atts['id'] ) ? $atts['id'] : '';

        if( $map_id == '' ) return;

        $full_map = new BingMapPro_MasterMaps\BingMapPro_Map_Full();

        $map = $full_map->getFullMap( $map_id );

        if( ( $map == null ) || ( sizeof( $map ) == 0 ) ) return;
        else $map = $map[0];

        if( ! $map->map_active )
            return;

        $map_view_id = '';
        $bmm                = new BingMapPro_OptionsManager\BingMapPro_OptionsManager();

        $bmp_api_key_local = $bmm->getOption('bmp_api_key', '' );

        if( $bmp_api_key_local == '')
            return;

        if( isset( $atts['viewid'] ) ){

            $map_view_id = $atts['viewid'];
            $viewid = intval( $atts['viewid'] );
          
            $view_obj = new BingMapPro_MasterMaps\BingMapPro_MapView();

            $view_data = $view_obj->getView( $viewid );

            if( $view_data !== null ){
                $map->is_view = true;
                $map->lock_lat = $map->map_start_lat;
                $map->lock_long = $map->map_start_long;
                $map->map_zoom = $view_data->s_zoom;
                $map->map_start_lat = $view_data->s_lat;
                $map->map_start_long = $view_data->s_long;
            }else{
                return;
            }

        }else{
            $map->is_view = false;
        }

        $bmp_map_pins       = $full_map->getMapAllActivePins( $map->id );
        $bmp_map_shapes     = $full_map->bmp_getAllActiveShapesForMap( $map->id );
        $bmp_icon_src       = BMP_PLUGIN_URL.'/';
        $bmp_encoded_map    = json_encode( $map, true );                        
        $bmp_map_pins       = json_encode( $bmp_map_pins, true );
        $bmp_map_shapes     = json_encode( $bmp_map_shapes, true );
        $bmpPinSizes        = json_encode( $this->bmp_get_infobox_sizes() );
        global $BMP_PLUGIN_VERSION;    
        
        $bmp_map_fullscreen_icon_src    = BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen.png';
        $bmp_map_fullscreen_icon_src2 = BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen2.png';
       
        $bmp_show_fullscreen = $map->toggle_fullscreen == 1;

        $bmp_show_fullscreen_text = $bmp_show_fullscreen ? 'block' : 'none';
        
        $map_view = [];
        $json_map_view = json_encode( $map_view );   

        $bmp_map_id = '_'.$map->id . '_' . $map_view_id;

        $bmp_map_fullscreen_icon_id = 'bmp_map_fullscreen_icon_' . $bmp_map_id;

        $front_url_script = BMP_PLUGIN_URL.'/js/front/bmp_map_script_front.js?v='.$BMP_PLUGIN_VERSION ;

        $script_front = "<script src='{$front_url_script}'></script>";     

        $bmp_map_script = " {$script_front}
                            <div class='{$map->html_class}' id='bmp_map_{$bmp_map_id}' style='position: relative; 
                                                            width:{$map->map_width}{$map->map_width_type};
                                                            height:{$map->map_height}{$map->map_height_type}'>
                                <img id='{$bmp_map_fullscreen_icon_id}' onclick='bmp_toggleFullScreen(\"bmp_map_{$bmp_map_id}\")' 
                                class='bmp_map_fullscreen_icon_class' style='display: {$bmp_show_fullscreen_text}' src='{$bmp_map_fullscreen_icon_src}' />
                            </div>
                           
                            <script type='text/javascript'>
                                var bmp_icon_src            = '{$bmp_icon_src}'; 
                                var bmpPinSizes             = JSON.parse('{$bmpPinSizes}');  
                                var bmpFullscreenIconSrc2   = '{$bmp_map_fullscreen_icon_src2}';  
                                var bmpFullscreenIconSrc    = '{$bmp_map_fullscreen_icon_src}'; 

                                if( typeof bmp_map_data_ === 'undefined' ) var bmp_map_data_ = [];

                                function bmp_load_map_{$bmp_map_id}(){      

                                    let bmp_map_id_{$bmp_map_id}        = document.getElementById('bmp_map_{$bmp_map_id}'); 
                                    let bmp_map_{$bmp_map_id}           = JSON.parse( JSON.stringify( {$bmp_encoded_map} ) );   
                                    let bmp_map_all_pins_{$bmp_map_id}  = JSON.parse( JSON.stringify( {$bmp_map_pins} ) );  
                                    let bmp_infobox_{$bmp_map_id}       = null;   
                                    let bmp_map_shapes_{$bmp_map_id}    = JSON.parse( JSON.stringify( {$bmp_map_shapes} ));  
                                    let bmp_extra_params_{$bmp_map_id}  = JSON.parse('{$json_map_view}');  

                                    let obj_{$bmp_map_id} = {
                                        map_obj      : bmp_map_id_{$bmp_map_id},
                                        map_data     : bmp_map_{$bmp_map_id},
                                        map_pins     : bmp_map_all_pins_{$bmp_map_id},
                                        map_infobox  : bmp_infobox_{$bmp_map_id}, 
                                        map_shapes   : bmp_map_shapes_{$bmp_map_id},   
                                        map_extras   : bmp_extra_params_{$bmp_map_id} 
                                    }

                                    bmp_map_data_.push( obj_{$bmp_map_id} );
                                }

                                bmp_load_map_{$bmp_map_id}();                            
                                     
                            </script>                                                      
                            ";

        wp_enqueue_style('bmp_map_style_front', BMP_PLUGIN_URL.'/css/bmp-frontend-style.css?v='.$BMP_PLUGIN_VERSION );

        if( ! wp_script_is( 'bmp_bing_map_loaded' ) ){
            wp_enqueue_script( 'bmp_bing_map_loaded', "https://www.bing.com/api/maps/mapcontrol?callback=bmp_bingLoadData&key={$bmp_api_key_local}" );
        }                        

        return $bmp_map_script;

    }

    public function bmp_shortcodes_init(){
        global $active_maps;
        global $full_map;
        global $bmpPinSizes;
        $maps = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
        $active_maps = $maps->getActiveMaps();
        $full_map = $active_maps;        
        $bmpPinSizes = json_encode( $this->bmp_get_infobox_sizes() );


        if( count( $active_maps ) > 0 ){  // if active maps exist, create shortcode

            function wporg_shortcode($atts = [], $content = null ){
          
                global $full_map;
                global $bmp_api_key;
                global $bmpPinSizes;
                // do something to $content
                $bmp_full_map = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                $bmm = new BingMapPro_OptionsManager\BingMapPro_OptionsManager();
                $bmp_views = new BingMapPro_MasterMaps\BingMapPro_MapView();

              
                // normalize attribute keys, lowercase
                $atts = array_change_key_case((array)$atts, CASE_LOWER);

                $wporg_atts = shortcode_atts([
                    'id' => '',
                    'name' => ''        
                ], $atts );
                
            
                foreach( $full_map as $map ){

                    if( $map->map_shortcode != '' ){                       
                        
                        global $BMP_PLUGIN_VERSION;                        
                        $bmp_map_pins = $bmp_full_map->getMapAllActivePins( $map->id );
                        $bmp_map_shapes = $bmp_full_map->bmp_getAllActiveShapesForMap( $map->id );
                        $bmp_icon_src = BMP_PLUGIN_URL.'/';
                        $bmp_encoded_map  = json_encode( $map, true );                        
                        $bmp_map_pins = json_encode( $bmp_map_pins, true );
                        $bmp_map_shapes = json_encode( $bmp_map_shapes, true );
                        
                        $map_view_id = $wporg_atts['id'];
                        $bmp_map_fullscreen_icon_src = BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen.png';
                        $bmp_map_fullscreen_icon_src2 = BMP_PLUGIN_URL.'/images/icons/bmp-map-full-screen2.png';
                       
                        $bmp_show_fullscreen = $map->toggle_fullscreen == 1;
                        $bmp_show_fullscreen_text = 'none';
                        if( $bmp_show_fullscreen ){
                            $bmp_show_fullscreen_text  = 'block';
                        }

                        $bmp_map_shortcode = $map->map_shortcode . '_' . $map_view_id;
                        if( $map_view_id == ''){
                            $map_view = [];
                        }else{
                            $map_view = $bmp_views->getSimpleView( $map_view_id );
                        }
                        $json_map_view = json_encode( $map_view );                      
                        $bmp_map_id = '_'.$map->id . '_' . $map_view_id;
                        $bmp_map_fullscreen_icon_id = 'bmp_map_fullscreen_icon_' . $bmp_map_id;


                        //BMP_show_maps
                        $bmp_api_key_local = $bmm->getOption('bmp_api_key', $bmp_api_key );
                        $bmp_map_script = "<div class='{$map->html_class}' id='bmp_{$bmp_map_shortcode}' style='position: relative; width:{$map->map_width}{$map->map_width_type}; height:{$map->map_height}{$map->map_height_type}'>
                                            <img id='{$bmp_map_fullscreen_icon_id}' onclick='bmp_toggleFullScreen(\"bmp_{$bmp_map_shortcode}\")' class='bmp_map_fullscreen_icon_class' style='display: {$bmp_show_fullscreen_text}' src='{$bmp_map_fullscreen_icon_src}'>
                                          </div>
                                        <script type='text/javascript' src='https://www.bing.com/api/maps/mapcontrol?key=".$bmp_api_key_local."' async defer ></script>
                                        <script type='text/javascript'> 
                                            var bmp_icon_src    = '{$bmp_icon_src}'; 
                                            var bmpPinSizes     = JSON.parse('{$bmpPinSizes}');  
                                            var bmpFullscreenIconSrc2 = '{$bmp_map_fullscreen_icon_src2}';  
                                            var bmpFullscreenIconSrc = '{$bmp_map_fullscreen_icon_src}';                                        
                                    
                                            window.addEventListener('load', function(){    
                                                let bmp_map_id_{$bmp_map_id}        = document.getElementById('bmp_{$bmp_map_shortcode}'); 
                                                let bmp_map_{$bmp_map_id}           = JSON.parse( JSON.stringify( {$bmp_encoded_map} ) );   
                                                let bmp_map_all_pins_{$bmp_map_id}  = JSON.parse( JSON.stringify( {$bmp_map_pins} ) );  
                                                let bmp_infobox_{$bmp_map_id}       = null;   
                                                let bmp_map_shapes_{$bmp_map_id}    = JSON.parse( JSON.stringify( {$bmp_map_shapes} ));  
                                                let bmp_extra_params_{$map_view_id} = JSON.parse('{$json_map_view}');                                                
                                                 
                                                bmp_loadMapScenario( bmp_map_id_{$bmp_map_id}, 
                                                                    bmp_map_all_pins_{$bmp_map_id}, 
                                                                    bmp_map_{$bmp_map_id}, 
                                                                    bmp_infobox_{$bmp_map_id}, 
                                                                    bmp_map_shapes_{$bmp_map_id},
                                                                    bmp_extra_params_{$map_view_id} );                                                                                                                                           
                                            });

                                           // function bmp_toggle_map_fullscreen_{$map_view_id }( {$bmp_map_fullscreen_icon_id}, bmp_{$bmp_map_shortcode} ){
                                                var bmp_map_fullscreen_{$bmp_map_id} = document.getElementById('{$bmp_map_fullscreen_icon_id}');
                                                
                                                bmp_map_fullscreen_{$bmp_map_id}.addEventListener('click', function(){
                                                    var bmp_map_object  = document.querySelector('#bmp_{$bmp_map_shortcode} .MicrosoftMap');
                                                    var bmp_map_object_class = bmp_map_object.className;
                                                    if( bmp_map_object_class.includes('bmp-admin-map-full-sceen') ){
                                                        bmp_map_object_class = bmp_map_object_class.replace(/bmp-admin-map-full-sceen/g,''); 
                                                        bmp_map_object.className = bmp_map_object_class;
                                                        this.className = this.className.replace(/bmp_map_fullsreen_top/g, '');
                                                    }else{
                                                        bmp_map_object.className = bmp_map_object_class + ' ' +  'bmp-admin-map-full-sceen';
                                                        this.className = this.className + ' ' + 'bmp_map_fullsreen_top';
                                                    }
                                                });
                                        //    }
                                                                                    
                                        </script>
                                        ";
                       
                        wp_enqueue_script('bmp_map_script_front',BMP_PLUGIN_URL.'/js/front/bmp_map_script_front.js'. '?v='.$BMP_PLUGIN_VERSION );
                        wp_enqueue_style('bmp_map_style_front', BMP_PLUGIN_URL.'/css/bmp-frontend-style.css?v='.$BMP_PLUGIN_VERSION );
                        return $bmp_map_script;
                    }
                }

              

            }  

            foreach( $active_maps as $map ){                             
               
                if( $map->map_shortcode !== '' ){
                    add_shortcode( $map->map_shortcode , 'wporg_shortcode');
                }
            }



        }
       
    }


    protected function prefixTableName($name) {
        global $wpdb;
        return $wpdb->prefix .  strtolower($this->prefix($name));
    }

    public function getAjaxUrl($actionName) {
        return admin_url('admin-ajax.php') . '?action=' . $actionName;
    }

    public function bmp_general_settings() {

        if( ( ! isset( $_POST['data'] ) ) && ( ! $this->bmp_has_user_cap() ) ) {
            echo json_encode('0');
            wp_die();
            return;
        }

        if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
            || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
        ) {
            echo json_encode( 
                array(
                    'error' => true,
                    'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
            ));
            wp_die();
        } 



        $bmm = new BingMapPro_OptionsManager\BingMapPro_OptionsManager();
        global $bmp_api_key;
       
        $data =  $_POST['data'];
       
        $data_api_key =  trim( sanitize_text_field( $data['bmp_api_key'] ) );
    //    $data_bmp_dfs = $data['bmp_dfs'];
        $data_bmp_dsom = sanitize_text_field( $data['bmp_dsom'] );
        $data_bmp_cnb = sanitize_text_field( $data['bmp_cnb'] );        
    //    $data_bmp_dsv = $data['bmp_dsv'];
        $data_bmp_dz  = sanitize_text_field( $data['bmp_dz'] );
        $data_bmp_mr  = sanitize_text_field( 'false' );

        $bmp_pin_desktop_width = sanitize_text_field( $data['bmp_settings_pin_desktop_width']);
        $bmp_pin_desktop_height = sanitize_text_field( $data['bmp_settings_pin_desktop_height']);

        $bmp_pin_tablet_width = sanitize_text_field( $data['bmp_settings_pin_tablet_width']);
        $bmp_pin_tablet_height = sanitize_text_field( $data['bmp_settings_pin_tablet_height']);

        $bmp_pin_mobile_width = sanitize_text_field( $data['bmp_settings_pin_mobile_width']);
        $bmp_pin_mobile_height = sanitize_text_field( $data['bmp_settings_pin_mobile_height']);
        
        $bmp_woo_autosuggest_enabled = intval( sanitize_text_field( $data['bmp_woo_autosuggest_enabled'] ) );
        $bmp_restrict_suggest = sanitize_text_field( $data['restrict_suggest'] );


        if( $data_api_key !== 'undefined' ){
            if(! $bmm->addOption('bmp_api_key', $data_api_key ) )
                $bmm->updateOption( 'bmp_api_key', $data_api_key  );
        }

        if(! $bmm->addOption( 'bmp_dsom', $data_bmp_dsom) )
            $bmm->updateOption( 'bmp_dsom', $data_bmp_dsom );
        
        if(! $bmm->addOption( 'bmp_cnb', $data_bmp_cnb ))
            $bmm->updateOption( 'bmp_cnb', $data_bmp_cnb );


        if(! $bmm->addOption('bmp_dz', $data_bmp_dz))
            $bmm->updateOption( 'bmp_dz', $data_bmp_dz );

        if(! $bmm->addOption('bmp_mr', $data_bmp_mr))
            $bmm->updateOption( 'bmp_mr', $data_bmp_mr );

        if(! $bmm->addOption('bmp_pin_desktop_width', $bmp_pin_desktop_width))
            $bmm->updateOption( 'bmp_pin_desktop_width', $bmp_pin_desktop_width );
        
        if(! $bmm->addOption('bmp_pin_desktop_height', $bmp_pin_desktop_height))
            $bmm->updateOption( 'bmp_pin_desktop_height', $bmp_pin_desktop_height );

        if(! $bmm->addOption('bmp_pin_tablet_width', $bmp_pin_tablet_width))
            $bmm->updateOption( 'bmp_pin_tablet_width', $bmp_pin_tablet_width );
        
        if(! $bmm->addOption('bmp_pin_tablet_height', $bmp_pin_tablet_height))
            $bmm->updateOption( 'bmp_pin_tablet_height', $bmp_pin_tablet_height );

        if(! $bmm->addOption('bmp_pin_mobile_width', $bmp_pin_mobile_width))
            $bmm->updateOption( 'bmp_pin_mobile_width', $bmp_pin_mobile_width );
        
        if(! $bmm->addOption('bmp_pin_mobile_height', $bmp_pin_mobile_height))
            $bmm->updateOption( 'bmp_pin_mobile_height', $bmp_pin_mobile_height );

        if(! $bmm->addOption('bmp_woo_autosuggest_enabled', $bmp_woo_autosuggest_enabled))
            $bmm->updateOption( 'bmp_woo_autosuggest_enabled', $bmp_woo_autosuggest_enabled );

        if(! $bmm->addOption('restrict_suggest', $bmp_restrict_suggest))
            $bmm->updateOption( 'restrict_suggest', $bmp_restrict_suggest );



        if( ! isset( $_POST['data'] ) )
            echo json_encode('0');
        else {
            if( strcmp( $bmp_api_key, $data['bmp_api_key'] ) == 0)
                echo json_encode( '3' );
            else
                echo json_encode( '1');      
        }

        wp_die(); 
    }

    public function bmp_new_map(){
        
        if( ! $this->bmp_has_user_cap() ){
            wp_die( esc_html__('No Access', 'bing-map-pro') );
            return;
        }

        if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
            || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
        ) {
            echo json_encode( 
                array(
                    'error' => true,
                    'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
            ));
            wp_die();
        } 

        if( isset( $_POST['data']) )
            $map_title = sanitize_text_field( $_POST['data']['map_title'] );
        
        $masterMap = new MasterMaps();

        $arr = $masterMap->mapsToArray();
        echo json_encode( $arr );
    
        wp_die();
    }

    public function bmp_save_map(){
        
        if( $this->bmp_has_user_cap()  ){     
         
            if( isset( $_POST['data'] ) ){
                if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
                    || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
                ) {
                    echo json_encode( 
                        array(
                            'error' => true,
                            'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
                    ));
                    wp_die();
                } 

                $fullMap = new BingMapPro_MasterMaps\BingMapPro_Map_Full(); 
                $data_map =  $_POST['data']; 
                $response_data =  $fullMap->updateFullMap( $data_map  );  //sanitized inside this function, before adding into db
                echo  $response_data;
            }else{
                echo 'error';
            }

        }

        wp_die();
    }


    public function bmp_map_actions(){

        if( isset( $_POST['data'] ) && $this->bmp_has_user_cap() ){
            
            if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
                || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
            ) {
                echo json_encode( 
                    array(
                        'error' => true,
                        'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
                ));

               wp_die();
            } 
          
            $bmp_map_action = sanitize_text_field( $_POST['data']['bmp_map_action'] );            
            $bmp_map_id     = intval( sanitize_text_field( $_POST['data']['map_id'] ) );

            $bmp_map_title  = isset( $_POST['data']['bmp_map_title'] ) ? trim( sanitize_text_field( $_POST['data']['bmp_map_title'] ) ) : '';
            $masterMaps     = new BingMapPro_MasterMaps\BingMapPro_MasterMaps();
            $masterMaps->loadMaps();
            $maps           = $masterMaps->getAllMaps(); 

            $arr= [ $bmp_map_action, $bmp_map_id, $bmp_map_title];
            if( $bmp_map_action == 'new'){   
       
                if( $bmp_map_title !== '' ){                   
                    
                    $bmp_dsom   = $this->getOption( 'bmp_dsom', 'false');
                    $bmp_cnb    = $this->getOption( 'bmp_cnb', 'false' );                
                    $bmp_dz     = $this->getOption( 'bmp_dz', 'false');
                    $bmp_mr     = $this->getOption( 'bmp_mr', 'false');

                    $bmp_extras['dsom'] = $bmp_dsom;
                    $bmp_extras['cnb']  = $bmp_cnb;
                    $bmp_extras['dz']   = $bmp_dz;
                    $bmp_extras['mr']   = $bmp_mr;

                    $bmp_new_map =  $masterMaps->saveNewMap( $bmp_map_title, $bmp_extras );

                    if(  ! is_bool( $bmp_new_map ) ){
                        echo json_encode( $bmp_new_map->mapToArray() );
                    }else{
                       echo  'false';
                    }
                }
                
            }else if( $bmp_map_action == 'edit'){
                array_push( $arr, 'edit');
                echo json_encode( $arr );
            }else if( $bmp_map_action == 'delete'){
                array_push( $arr, 'delete');            
                $masterMaps->deleteMap( $bmp_map_id );
                echo json_encode( $bmp_map_id );
            }else if( $bmp_map_action == 'active' ){
                $new_map = $maps[ $bmp_map_id ];
                echo $new_map->disableMap( $bmp_map_id );             
            }else if( $bmp_map_action == 'bmp_save_map_view'){
                $map_id = sanitize_text_field( $_POST['data']['map_id'] );
                $name   = sanitize_text_field( $_POST['data']['name'] );
                $lat    = sanitize_text_field( $_POST['data']['lat'] );
                $long   = sanitize_text_field( $_POST['data']['long'] );
                $zoom   = intval( sanitize_text_field( $_POST['data']['zoom'] ));
                $map_view = new BingMapPro_MasterMaps\BingMapPro_MapView();
                $added = $map_view->addNew( $map_id, $name, $lat, $long, $zoom ); 
                if( $added ){
                    $last_added = $map_view->getLastCreated( $map_id );
                    echo json_encode( $last_added );                    
                   
                }else{
                    echo '0';                    
                }
            }else if( $bmp_map_action == 'bmp_delete_map_view'){
                $view_id = intval( sanitize_text_field( $_POST['data']['id'] ) );
                $map_view = new BingMapPro_MasterMaps\BingMapPro_MapView();
                $delete_view = $map_view->deleteView( $view_id );
                echo $delete_view;
            }

        }
             
        wp_die();

    }

    public function bmp_show_permissions(){

        if( ! $this->bmp_has_user_cap() ){
            wp_die( esc_html__('You don\'t have sufficient permissions to access this page.', 'bing-map-pro') );
            return;
        }
        
        $bmp_saved_permissions = array(
            'editor'        => $this->getOption('editor_cap', false),
            'author'        => $this->getOption('author_cap', false),
            'contributor'   => $this->getOption('contributor_cap', false),
            'hide_api_key'  => $this->getOption('hide_api_key', false )           
        );
        
        BingMapPro_Permissions\BingMapPro_Permissions::bmp_show_permissions_html( $bmp_saved_permissions, $this->bmp_menu_links('', 5) );
    }

    public function bmp_showMaps() {

       if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('You don\'t have sufficient permissions to access this page.', 'bing-map-pro') );   

    ?>
        
        <div class="wrap">
            <?php

                if( isset( $_POST['bmp_page_action']) && ( $_POST['bmp_page_action'] == 'edit-map' ) ){ 
                    $map_id = sanitize_text_field( $_POST['bmp_page_map_id'] );
                    $new_map = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                    $bmp_views = new BingMapPro_MasterMaps\BingMapPro_MapView();
                    $bmp_api_key = $this->getOption('bmp_api_key', $this->getDefaultKey() );
                    $bmp_map_pins = $new_map->getMapPinsFull( $map_id );
                    $bmp_infobox_sizes = $this->bmp_get_infobox_sizes();
                    $bmp_fullMap =  $new_map->getFullMap( $map_id );
                    $bmp_map_views = $bmp_views->getAllViewsForMap( $map_id );
                    $bmp_map_shapes = $new_map->bmp_getAllActiveShapesForMap( $map_id );
                    
                    bmp_editMap( $bmp_fullMap,
                                 $bmp_api_key, 
                                 $bmp_map_pins, 
                                 $bmp_infobox_sizes, 
                                 $this->bmp_menu_links('', 1), 
                                 $bmp_map_shapes,
                                 $bmp_map_views  );

                }else if( isset( $_POST['bmp_page_action']) && ( $_POST['bmp_page_action'] == 'bmp-add-map-pins') ){
                    $map_id = trim( $_POST['bmp_page_map_id'] );
                    $bmp_api_key = $this->getOption('bmp_api_key', $this->getDefaultKey() );
                    $bmp_api_pin = new BingMapPro_Pin();
                    $bmp_api_all_pins = $bmp_api_pin->getAllPins();
                    $bmp_api_map = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                    $bmp_api_map_pins = $bmp_api_map->getMapPins( $map_id );
                    bmp_add_map_pins( $map_id, $bmp_api_key, $bmp_api_all_pins, $bmp_api_map_pins, $this->bmp_menu_links('', 1) );    
                }else if( isset( $_POST['bmp_page_action']) && ( $_POST['bmp_page_action'] == 'bmp-add-map-shapes') ){
                    $map_id = sanitize_text_field( trim( $_POST['bmp_page_map_id'] ) );
                    $bmp_shapes = new BingMapPro_Shape();
                //    $all_shapes = $bmp_shapes->bmp_getAllShapes();
                    $all_shapes = $bmp_shapes->bmp_getAllFilteredShapes( $map_id );
                    $map_shapes = $bmp_shapes->bmp_getAllMapShapes( $map_id );
                    bmp_add_map_shapes( $map_id, $all_shapes , $map_shapes,  $this->bmp_menu_links('', 1) );   
                }else{
                    $allMaps = new BingMapPro_MasterMaps\BingMapPro_MasterMaps();
                    $allMaps->loadMaps( true );
                    $allMaps = $allMaps->mapsToArray();
                    $bmp_api_key = $this->getOption( 'bmp_api_key', $this->getDefaultKey() );
                    bmp_maps( $allMaps, $bmp_api_key, $this->bmp_menu_links('', 1) ); 
                }
            ?>
        
        </div>

    <?php

    }

    public function generalSettings(){

        
       if( ! $this->bmp_has_user_cap() )
          wp_die( esc_html__('You don\'t have sufficient permissions to access this page.', 'bing-map-pro') );
        
        $bmp_api_key = trim( $this->getOption( 'bmp_api_key', $this->getDefaultKey() ) );
 
        $bmp_dsom   = $this->getOption( 'bmp_dsom', 'false');
        $bmp_cnb    = $this->getOption( 'bmp_cnb', 'false' );  
        $bmp_dz     = $this->getOption( 'bmp_dz', 'false');
        $bmp_mr     = $this->getOption( 'bmp_mr', 'false');

        $bmp_pin_desktop_width = (int) $this->getOption( 'bmp_pin_desktop_width', 250 );
        $bmp_pin_desktop_height = (int) $this->getOption( 'bmp_pin_desktop_height', 170);

        $bmp_pin_tablet_width = (int) $this->getOption( 'bmp_pin_tablet_width', 210 );
        $bmp_pin_tablet_height = (int) $this->getOption( 'bmp_pin_tablet_height', 130);

        $bmp_pin_mobile_width = (int) $this->getOption( 'bmp_pin_mobile_width', 140 );
        $bmp_pin_mobile_height = (int) $this->getOption( 'bmp_pin_mobile_height', 100);

        $bmp_woo_autosuggest = (int)$this->getOption( 'bmp_woo_autosuggest_enabled', 0 );

        $settings = [];
        $settings['bmp_api_key']    = $bmp_api_key;
        $settings['bmp_dsom']       = $bmp_dsom;
        $settings['bmp_cnb']        = $bmp_cnb;   
        $settings['bmp_dz']         = $bmp_dz;  
        $settings['bmp_mr']         = $bmp_mr;  //map refresh -> update map when changes 
        $settings['bmp_pin_desktop_width']          = $bmp_pin_desktop_width;
        $settings['bmp_pin_desktop_height']         = $bmp_pin_desktop_height;

        $settings['bmp_pin_tablet_width']           = $bmp_pin_tablet_width;
        $settings['bmp_pin_tablet_height']          = $bmp_pin_tablet_height;

        $settings['bmp_pin_mobile_width']           = $bmp_pin_mobile_width;
        $settings['bmp_pin_mobile_height']          = $bmp_pin_mobile_height;
        $settings['hide_api_key']                   = ( ! $this->bmp_is_admin() ) && ( $this->getOption('hide_api_key', false ) );
        $settings['bmp_woo_autosuggest_enabled']    = $bmp_woo_autosuggest;
        $settings['bmp_woo_activated']              = $this->bmp_is_woo_activated();
        $settings['bmp_woo_valid_api']              = strlen( $bmp_api_key ) > 5;
        $settings['restrict_suggest']               = $this->getOption( 'restrict_suggest', '' );
    
        BingMapPro_GeneralSettings\BingMapPro_GeneralSettings::bmp_generalSettings( $settings, $this->bmp_menu_links('bmp_side_menu_settings', 4) );        
    }
    private function getDefaultKey(){
        return $this->bmp_api_key;
    }

    private function bmp_is_woo_activated(){
          
        if ( class_exists( 'woocommerce' ) ) 
            return  true;
        else 
            return  false;             
         
    }

    public function  bmp_settingPins(){

        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('You don\'t have sufficient permissions to access this page.', 'bing-map-pro') );

        $bmp_api_key = $this->getOption( 'bmp_api_key', $this->getDefaultKey() );
        $new_pin = new BingMapPro_Pin();
        $all_pins = $new_pin->getAllPins();
        $extra_settings = [];

        $extra_settings['pin'] = $this->bmp_get_infobox_sizes();                          

        $bmp_menu_links = $this->bmp_menu_links('', 2);

        BingMapPro_Pins\BingMapPro_Pins::bmp_init_pins( $all_pins , $bmp_api_key, $extra_settings, $bmp_menu_links );
    }

    public function bmp_get_infobox_sizes(){
        $bmp_pin_desktop_width = (int) $this->getOption( 'bmp_pin_desktop_width', 250 );
        $bmp_pin_desktop_height = (int) $this->getOption( 'bmp_pin_desktop_height', 170);

        $bmp_pin_tablet_width = (int) $this->getOption( 'bmp_pin_tablet_width', 210 );
        $bmp_pin_tablet_height = (int) $this->getOption( 'bmp_pin_tablet_height', 130);

        $bmp_pin_mobile_width = (int) $this->getOption( 'bmp_pin_mobile_width', 140 );
        $bmp_pin_mobile_height = (int) $this->getOption( 'bmp_pin_mobile_height', 100);

        $bmp_pin_sizes = [ 'bmp_pin_desktop_width' => $bmp_pin_desktop_width,
                           'bmp_pin_desktop_height' => $bmp_pin_desktop_height,
                           'bmp_pin_tablet_width' => $bmp_pin_tablet_width,
                           'bmp_pin_tablet_height' => $bmp_pin_tablet_height,
                           'bmp_pin_mobile_width' => $bmp_pin_mobile_width,
                           'bmp_pin_mobile_height' => $bmp_pin_mobile_height,
                          ];
        return $bmp_pin_sizes;
    }

    public function bmp_menu_links( $bmp_block_class, $bmp_page ){
        

        $bmp_permission = '';
        if( $this->bmp_is_admin() )
            $bmp_permission = menu_page_url( $this->getPluginPermissionSlug(), false );
        
        $bmp_links = array(
            'maps'          => menu_page_url( $this->getPluginMapsSlug(), false ),
            'pins'          => menu_page_url( $this->getPluginPinsSlug(), false ),
            'shapes'        => menu_page_url( $this->getPluginShapesSlug(), false ),
            'settings'      => menu_page_url( $this->getSettingsSlug(), false ),
            'permissions'   => $bmp_permission
        );
        $bmp_page_maps_active  = '';
        $bmp_page_pins_active = '';
        $bmp_page_settings_active = '';
        $bmp_page_permissions_active = '';
        $bmp_page_shapes_active = '';
    

        $bmp_s_maps         = esc_html__('Maps', 'bing-map-pro');
        $bmp_s_pins         = esc_html__('Pins', 'bing-map-pro');
        $bmp_s_shapes       = esc_html__('Shapes', 'bing-map-pro');
        $bmp_s_settings     = esc_html__('Settings', 'bing-map-pro');
        $bmp_s_permissions  = esc_html__('Permissions', 'bing-map-pro');
        $bmp_s_store        = esc_html__('Store Locator', 'bing-map-pro');
        switch( $bmp_page ){
            case 1 : //maps
                $bmp_page_maps_active = 'active'; break;
            case 2 : //pins
                $bmp_page_pins_active = 'active'; break;
            case 3 : //shapes
                $bmp_page_shapes_active = 'active'; break;
            case 4 : //settings
                $bmp_page_settings_active = 'active'; break;
            case 5 : //permissions
                $bmp_page_permissions_active = 'active'; break;
        }


        $result = '';
        $result .= '<div id="bmp_side_menu" class="'. $bmp_block_class .'">';
            $result .= '<ul>';
                $result .= '<li data-toggle="tooltip" data-placement="left" id="menu_item_maps" title="'.$bmp_s_maps.'" class="'. $bmp_page_maps_active .'"> <a href="'. $bmp_links['maps'].'"> <img src="' . BMP_PLUGIN_URL. '/images/icons/menu-map.png" /> </a>';
                $result .= '<li data-toggle="tooltip" data-placement="left" id="menu_item_pins" title="'.$bmp_s_pins.'" class="'. $bmp_page_pins_active .'" > <a href="'. $bmp_links['pins'].'"> <img src="' . BMP_PLUGIN_URL.'/images/icons/menu-pins.png"/> </a>';
                $result .= '<li data-toggle="tooltip" data-placement="left" id="menu_item_shapes" title="'.$bmp_s_shapes.'" class="'. $bmp_page_shapes_active .'" > <a href="'. $bmp_links['shapes'].'"> <img src="' . BMP_PLUGIN_URL.'/images/icons/menu-shapes.png"/> </a>';
                $result .= '<li data-toggle="tooltip" data-placement="left" id="menu_item_settings" title="'.$bmp_s_settings.'" class="'. $bmp_page_settings_active .'" > <a href="'. $bmp_links['settings'].'"> <img src="' . BMP_PLUGIN_URL.'/images/icons/menu-settings.png"/> </a>';
                if( $bmp_permission !== '' )
                    $result .= '<li data-toggle="tooltip" data-placement="left" id="menu_item_permissions" title="'.$bmp_s_permissions.'" class="'. $bmp_page_permissions_active .'" > <a href="'. $bmp_links['permissions'].'"> <img src="' . BMP_PLUGIN_URL . '/images/icons/menu-permissions.png"/> </a>';

                
            $result .= '</ul>';
        $result .= '</div>';

        return $result;
    }

    public function bmp_new_pin(){
      
        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('No access to this page', 'bing-map-pro'));

        if( ! isset( $_POST['data']) ){
            wp_die();
        }


        if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
            || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
        ) {
            echo json_encode( 
                array(
                    'error' => true,
                    'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
            ));

           wp_die();
        } 

        $bmp_new_pin = new BingMapPro_Pin();       

        if( isset( $_POST['data']) && ($_POST['data']['action'] == 'new-pin') ){            
            $bmp_new_pin->saveNewPin( $_POST['data'] );                     
       

            $bmp_last_created_pin = $bmp_new_pin->getLastAddedPin();
        
            if( $bmp_last_created_pin !== null ){
                $bmp_last_created_pin->data_json = json_decode( $bmp_last_created_pin->data_json, true );
                echo json_encode( $bmp_last_created_pin ); 
            }else{
                echo '0';
            }

        }else{
            echo '0';
        }

        
        wp_die();
    }

    public function bmp_pin_actions(){

        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('No access to this page', 'bing-map-pro'));

        if( isset( $_POST['data'] ) ){
            if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
                || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
            ) {
                echo json_encode( 
                    array(
                        'error' => true,
                        'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
                ));

                wp_die();
            } 

            $data_action = isset( $_POST['data']['action'] ) ? $_POST['data']['action'] : '' ;
            $bmp_pin = new BingMapPro_Pin();
            if( $data_action === 'disable-pin'){
                $pin_id = (int)sanitize_text_field( $_POST['data']['pin_id']);
                $pin_status = sanitize_text_field( $_POST['data']['status'] );                
                $bmp_result =  $bmp_pin->disableEnablePin( $pin_id, $pin_status );
                echo json_encode( $bmp_result );
            }else if( $data_action === 'delete-pin' ){
                $pin_id = sanitize_text_field( (int) $_POST['data']['pin_id']);
                $bmp_result = $bmp_pin->deletePin( $pin_id ); 
                echo json_encode( $bmp_result );
            }else if( $data_action === 'edit-pin' ){
                $data_pin = $_POST['data']['pin']; // it is sanitized inside the updatePin function     
                $data_pinId = ( sanitize_text_field( (int)$_POST['data']['pin']['id'] ));                        
                $update_result =  $bmp_pin->updatePin( $data_pin  );
                                           
                if( $update_result){                    
                    $updated_pin = $bmp_pin->bmpGetPinById( $data_pinId );
                    
                    if( $updated_pin ){
                        $new_updated_pin = $updated_pin[0];
                        //$new_updated_pin->data_json = json_decode( $new_updated_pin->data_json, true );
                        echo json_encode( $new_updated_pin );
                    }                
                    
                }else 
                    echo $update_result;                                  
            
            }else if( $data_action === 'add_pin_to_map'){
                $map_id = sanitize_text_field( $_POST['data']['map_id'] );
                $pin_id = sanitize_text_field( $_POST['data']['pin_id'] );
                $map    = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                $result = $map->addPinToMap( $map_id, $pin_id );
                $data = array(
                    'result' => $result,
                    'action' => 'add_pin_to_map'
                );
                echo json_encode( $data );
            }else if( $data_action === 'remove_pin_from_map'){
                $map_id = sanitize_text_field( $_POST['data']['map_id'] );
                $pin_id = sanitize_text_field( $_POST['data']['pin_id'] );
                $map    = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                $result = $map->removePinFromMap( $map_id, $pin_id );
                $data = array(
                    'result' => $result,
                    'action' => 'remove_pin_from_map'
                );
                echo json_encode( $data );
            }else if( $data_action === 'disable_pin_from_map' ){
                $map_id = sanitize_text_field( $_POST['data']['map_id'] );
                $pin_id = sanitize_text_field( $_POST['data']['pin_id'] );
                $map    = new BingMapPro_MasterMaps\BingMapPro_Map_Full();
                $result = $map->disablePinFromMap( $map_id, $pin_id );
                $data = array(
                    'result' => $result,
                    'action' => 'disable_pin_from_map'
                );
                echo json_encode( $data );   
            }
            
        }
        

        wp_die();
    }

    public function bmp_ajax_permissions(){

        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('No access to this page', 'bing-map-pro'));

        if( isset( $_POST['data'] )){   

            if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
                || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
            ) {
                echo json_encode( 
                    array(
                        'error' => true,
                        'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
                ));

                wp_die();
            } 

            $bmp_editor_field      = sanitize_text_field( $_POST['data']['editor'] ); 
            $bmp_author_field      = sanitize_text_field( $_POST['data']['author'] ); 
            $bmp_contributor_field = sanitize_text_field( $_POST['data']['contributor'] ); 
            $bmp_hide_api_key_field = sanitize_text_field( $_POST['data']['hide_api_key'] );                      

            $bmp_editor_cap = ( $bmp_editor_field  == 'true' ) ? true : false;
            $bmp_author_cap = ( $bmp_author_field == 'true' ) ? true : false;
            $bmp_contributor_cap = ( $bmp_contributor_field == 'true' ) ? true : false;            
            $bmp_hide_api_key = ( $bmp_hide_api_key_field == 'true' ) ? true : false;   

            $this->set_editor_cap( $bmp_editor_cap );
            $this->set_author_cap( $bmp_author_cap );
            $this->set_contributor_cap( $bmp_contributor_cap );

            $this->set_hide_api_key( $bmp_hide_api_key );                        
      
            $bmp_saved_permissions = array(
                'editor'        => $bmp_editor_cap,
                'author'        => $bmp_author_cap,
                'contributor'   => $bmp_contributor_cap,
                'hide_api_key'  => $bmp_hide_api_key       
            );
          
            echo json_encode( $bmp_saved_permissions );
           
        }else{
            echo false;
        }

        wp_die();
    }

    public function bmp_shapesPage(){

        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('You don\'t have sufficient permissions to access this page.', 'bing-map-pro') );
        
        $bmp_menu_links =  $this->bmp_menu_links('', 3);
        $bmp_api_key = $this->getOption( 'bmp_api_key', $this->getDefaultKey() );
        $bmp_infobox_sizes = $this->bmp_get_infobox_sizes();
        BingMapPro_Shapes\BingMapPro_Shapes::bmp_shapesPageHtml( $bmp_menu_links, $bmp_api_key, $bmp_infobox_sizes );
    }

    public function bmp_shape_actions(){
       
        if( ! $this->bmp_has_user_cap() )
            wp_die( esc_html__('No access to this page', 'bing-map-pro'));

        if( isset( $_POST['data']) ){

            if ( ! isset( $_POST['data']['nonce_bing_map_pro'] ) 
                || ! wp_verify_nonce( $_POST['data']['nonce_bing_map_pro'], 'nonce_action_bing_map_pro' ) 
            ) {
                echo json_encode( 
                    array(
                        'error' => true,
                        'message' => __('Error. Sorry, the page did not verify.', 'bing-map-pro')
                ));

                wp_die();
            } 

            $shape = new BingMapPro_Shape();
            $action = $_POST['data']['action'];
            if( $action == 'new' || $action == 'saveandnew'){              
                $shape->bmp_newShape( $_POST['data'] );
                //$lastPin->shapeData = json_encode( $lastPin->shapeData );
                //$lastPin->style = json_encode( $lastPin->style );
                $lastShapeAdded = $shape->bmp_getLastAddedShape();
                echo json_encode( $lastShapeAdded );
                wp_die();
            }else if( $action == 'table' ){
                $allShapes = $shape->bmp_getAllShapes();
                echo json_encode( $allShapes );
                wp_die();
            }else if( $action == 'delete'){
                $bmp_shape_id = $_POST['data']['id'];
                echo $shape->bmp_deleteShape( $bmp_shape_id );                
                wp_die();
            }else if( $action == 'edit'){
                $bmp_shape_data = $_POST['data'];
                $bmp_shape_id = $_POST['data']['id'];
                $shape->bmp_updateShape( $bmp_shape_data );
                $bmp_edited = $shape->bmp_getShapeById( $bmp_shape_id );
                echo json_encode( $bmp_edited );                                
                wp_die();
            }else if( $action == 'add_shape_to_map'){
                $bmp_shape_id   = intval( sanitize_text_field( $_POST['data']['shapeid'] ) );
                $bmp_map_id     = intval( sanitize_text_field( $_POST['data']['map_id'] ) );
                $result =  $shape->bmp_addShapeToMap( $bmp_shape_id, $bmp_map_id );                
                echo $result;  
                wp_die();
            }else if( $action == 'remove_shape_from_map'){
                $bmp_shape_id   = sanitize_text_field( $_POST['data']['shapeid'] );
                $bmp_map_id     = sanitize_text_field( $_POST['data']['map_id'] );
                
                $result = $shape->bmp_removeShapeFromMap( $bmp_shape_id, $bmp_map_id );
                echo $result;
                wp_die();

            }
        }
       
        echo 'error';
        wp_die();
    }


    function bmp_feedback_uninstall(){
        BingMapPro_Includes\BingMapPro_Includes::add_feedback_form();
    }    

    function bmp_woo_address_suggestion(){
        $bmp_woo_activated              = $this->bmp_is_woo_activated();

        if( $bmp_woo_activated  && ( is_checkout() || is_account_page() )){

            $bmp_woo_autosuggest_enabled    = (int)$this->getOption( 'bmp_woo_autosuggest_enabled', 0 );        
            $bmp_api_key                    = trim( $this->getOption( 'bmp_api_key', $this->getDefaultKey() ) );
            $bmp_woo_valid_api              = strlen( $bmp_api_key ) > 5;  
           
            
            if( ( $bmp_woo_autosuggest_enabled  == 1)  && $bmp_woo_valid_api ){
              
                wp_enqueue_script('bmp_bingmap-autocomplete-js', BMP_PLUGIN_URL .'/js/front/bmp-checkout-address-suggestion.js'.'?v=' . $this->getVersion()  );  
                wp_enqueue_script('bmp-bingmap-autocomplete', 'https://www.bing.com/api/maps/mapcontrol?key='.$bmp_api_key.'&callback=loadBmpMapSuggestion' );  
                
                $bmp_restrict_suggest           = $this->getOption( 'restrict_suggest', '' );                     
                ?>
                    <script type='text/javascript'> var bmp_restrict_suggest = '<?php echo $bmp_restrict_suggest; ?>';</script>
                <?php
            }
        }
    }

}