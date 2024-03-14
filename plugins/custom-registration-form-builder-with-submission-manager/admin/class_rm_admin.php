<?php



/**

 * The admin-specific functionality of the plugin.

 *

 * @link       http://registration_magic.com

 * @since      1.0.0

 *

 * @package    Registraion_Magic

 * @subpackage Registraion_Magic/admin

 */



/**

 * The admin-specific functionality of the plugin.

 *

 * Defines the plugin name, version, and two examples hooks for how to

 * enqueue the admin-specific stylesheet and JavaScript.

 *

 * @package    Registraion_Magic

 * @subpackage Registraion_Magic/admin

 * @author     CMSHelplive

 */



class RM_Admin {



    /**

     * The ID of this plugin.

     *

     * @since    1.0.0

     * @access   public

     * @var      string    $registraion_magic    The ID of this plugin.

     */

    public $plugin_name;



    /**

     * The version of this plugin.

     *

     * @since    1.0.0

     * @access   public

     * @var      string    $version    The current version of this plugin.

     */

    public $version;



    /**

     * The controller of this plugin.

     *

     * @since    1.0.0

     * @access   public

     * @var      string    $controller    The main controller of this plugin.

     */

    public $controller;

    

    /**

     * The icon of plugin dashboard menu.

     *

     * @since    4.6.0.6

     * @access   public

     * @var      string    $icon    The icon of plugin dashboard menu.

     */

    public $icon;

    public static $editor_counter = 1;



    /**

     * Initialize the class and set its properties.

     *

     * @since    1.0.0

     * @param      string    $plugin_name   The name of this plugin.

     * @param      string    $version       The version of this plugin.

     */

    public function __construct($plugin_name, $version, $controller) {

        $this->plugin_name = $plugin_name;

        $this->version = $version;

        $this->controller = $controller;

        $this->icon = base64_encode('<svg

   xmlns:dc="http://purl.org/dc/elements/1.1/"

   xmlns:cc="http://creativecommons.org/ns#"

   xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"

   xmlns:svg="http://www.w3.org/2000/svg"

   xmlns="http://www.w3.org/2000/svg"

   viewBox="0 0 8.0933332 8.2133331"

   height="8.2133331"

   width="8.0933332"

   xml:space="preserve"

   id="svg2"

   version="1.1"><metadata

     id="metadata8"><rdf:RDF><cc:Work

         rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type

           rdf:resource="http://purl.org/dc/dcmitype/StillImage" /></cc:Work></rdf:RDF></metadata><defs

     id="defs6"><clipPath

       id="clipPath20"

       clipPathUnits="userSpaceOnUse"><path

         style="clip-rule:evenodd"

         id="path18"

         d="M 54.0703,57 H 10.9102 V 3.87891 h 28.6211 l 14.539,13.90239 z" /></clipPath></defs><g

     transform="matrix(1.3333333,0,0,-1.3333333,0,8.2133333)"

     id="g10"><g

       transform="scale(0.1)"

       id="g12"><g

         id="g14"><g

           clip-path="url(#clipPath20)"

           id="g16"><path

             id="path22"

             style="fill:#fff;fill-opacity:1;fill-rule:evenodd;stroke:none"

             d="m 37.4688,3.87891 h 16.6016 v 16.6016 H 37.4688 Z" /><path

             id="path24"

             style="fill:#fff;fill-opacity:1;fill-rule:evenodd;stroke:none"

             d="M 60.7188,47.0391 H -5.69141 V 63.6406 H 60.7188 Z M -2.37109,60.3203 V 50.3594 H 57.3984 v 9.9609 H -2.37109" /></g></g><path

         id="path26"

         style="fill:#fff;fill-opacity:1;fill-rule:evenodd;stroke:none"

         d="M 54.0703,17.7813 39.5313,3.87891 H 10.9102 V 57 H 54.0703 Z M 14.2305,53.6797 V 7.19922 H 38.1992 L 50.7617,19.1992 V 53.6797 H 14.2305" /><path

         id="path28"

         style="fill:#fff;fill-opacity:1;fill-rule:nonzero;stroke:none"

         d="M 39.7617,34.1602 16.5508,15.7109 10.3984,23.4492 33.6094,41.8906 Z M 8.80859,9.55078 C 7.75,8.71094 6.19141,8.89063 5.33984,9.96094 L 2.26953,13.8203 c -0.84765,1.0703 -0.66797,2.6289 0.40235,3.4688 l 7.72652,6.1601 6.1524,-7.7383 z m 36.26951,35.14062 -3.3672,-9 -6.1523,7.7383 9.5195,1.2617" /><path

         id="path30"

         style="fill:#fff;fill-opacity:1;fill-rule:evenodd;stroke:none"

         d="M 35.9297,13.7617 H 16.4102 V 8.76953 h 19.5195 v 4.99217" /></g></g></svg>');

    }



    public function get_plugin_name() {

        return $this->plugin_name;

    }



    public function get_version() {

        return $this->version;

    }



    public function get_controller() {

        return $this->controller;

    }

    public function enqueue_styles_global($hook) {
        wp_enqueue_style($this->plugin_name, plugin_dir_url(__FILE__) . 'css/style_rm_admin.css', array(), $this->version, 'all');
        wp_enqueue_style('rm_admin_common_utility', plugin_dir_url(__FILE__) . 'css/rm-admin-common-utility.css', array(), $this->version, 'all');
    }

    /**

     * Register the stylesheets for the admin area.

     *

     * @since    1.0.0

     */

    public function enqueue_styles($hook) {

        if(defined('REGMAGIC_ADDON'))

        wp_enqueue_style($this->plugin_name . '_addon', RM_ADDON_BASE_URL . 'admin/css/style_rm_admin.css', array(), $this->version, 'all');

        wp_register_style('style_rm_formcard_menu', RM_BASE_URL . 'admin/css/style_rm_formcard_menu.css', array($this->plugin_name), $this->version, 'all');

       // wp_enqueue_style('rm_google_font', RM_BASE_URL . 'admin/css/titillium-web.css', array(), $this->version, 'all');

        if(defined('REGMAGIC_ADDON'))

            wp_enqueue_style('rm_rating_style', RM_ADDON_BASE_URL . 'public/js/rating3/rateit.css', array(), $this->version, 'all');

        wp_enqueue_style('rm_font_awesome', RM_BASE_URL . 'admin/css/font-awesome.min.css', array(), $this->version, 'all');

        wp_register_style('rm_jquery_ui_timepicker_addon_css', RM_BASE_URL . 'admin/css/jquery-ui-timepicker-addon.css', array(), $this->version, 'all');

        //wp_enqueue_style('rm-jquery-ui','http://ajax.googleapis.com/ajax/libs/jqueryui/1/themes/smoothness/jquery-ui.css',false,$this->version,'all');

        //Allowing style attribute for PFBC HTML elements

        wp_register_style('style_rm_daterangepicker', RM_BASE_URL . 'admin/css/daterangepicker.css', array($this->plugin_name), $this->version, 'all');

        //if($hook == 'toplevel_page_rm_dashboard_widget_dashboard' || $hook =='admin_page_rm_dashboard_widget_dashboard' || $hook == 'registrationmagic_page_rm_dashboard_widget_dashboard' || $hook == 'registration_page_rm_dashboard_widget_dashboard') {

            //wp_enqueue_style('style_rm_dashboard', RM_BASE_URL . 'admin/css/style_rm_dashboard.css', array($this->plugin_name), $this->version, 'all');

            wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );

        //}

        //if($hook =='admin_page_rm_form_setup' || $hook =='admin_page_rm_form_setup_finished'){

            //wp_enqueue_style('style_rm_setup_wizard', plugin_dir_url(__FILE__) . 'css/style_rm_setup_wizard.css', array(), $this->version, 'all');

            //wp_enqueue_style( 'rm_material_icons', RM_BASE_URL . 'admin/css/material-icons.css' );

        //}

        //wp_enqueue_style( 'rm-form-manage', plugin_dir_url(__FILE__) . 'css/style_rm_form_manage.css' );
        
        add_filter( 'safe_style_css', function($styles) {

            $styles[] = 'display';

            return $styles;

        });

    }

    

    public function rm_editor_style(){

        add_editor_style( plugin_dir_url(__FILE__) . 'css/rm-mce-editor.css');

    }



    /**

     * Register the JavaScript for the admin area.

     *

     * @since    1.0.0

     */

    public function enqueue_scripts() {

        wp_register_script('rm-utilities', plugin_dir_url(__FILE__) . 'js/script_rm_utilities.js', array(), $this->version, false);

        $utilities_vars= array(

                        'price_fixed'=>sprintf(__("For creating fixed price single product. <a target='_blank' class='rm-more' href='%s'>More</a><br/><br/>",'custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),

                        'price_multisel'=>sprintf(__("Allow user to pick multiple products with individual prices. Price will calculated as cumulative for the selection for products. <a target='_blank' class='rm-more' href='%s'>More</a>",'custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),

                        'dropdown'=>sprintf(__("Allows user to pick a single product from multiple products with individual prices. <a target='_blank' class='rm-more' href='%s'>More</a>",'custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),

                        'userdef'=>sprintf(__("Allows user to enter his/ her own price for product with the form. Useful for accepting donations etc. <a target='_blank' class='rm-more' href='%s'>More</a>",'custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/knowledgebase/add-product/#htprodpricetype'),

                        'price_default'=>__("Define how the product will be priced.",'custom-registration-form-builder-with-submission-manager'),

                        'admin_url'=>admin_url()

        );

        if(!defined('REGMAGIC_ADDON'))

            $utilities_vars['price_fixed'] .= RM_UI_Strings::get('MSG_BUY_PRO_PRICE_FIELDS');

        wp_localize_script('rm-utilities','utilities_vars',$utilities_vars);

        

        wp_register_script('rm-formflow', plugin_dir_url(__FILE__) . 'js/script_rm_formflow.js', array(), $this->version, false);

        $formflow_vars= array(

                         'copied'=>__("Copied",'custom-registration-form-builder-with-submission-manager'),

                         'copy'=>__("Copy",'custom-registration-form-builder-with-submission-manager'),

                         'ajaxnonce' => wp_create_nonce('rm_formflow')

        );

        wp_localize_script('rm-formflow','formflow_vars',$formflow_vars);

        if(defined('REGMAGIC_ADDON'))

            wp_register_script($this->plugin_name, RM_ADDON_BASE_URL . 'admin/js/script_rm_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker','jquery-ui-droppable','jquery-ui-draggable'), $this->version, false);

        else

            wp_register_script($this->plugin_name, plugin_dir_url(__FILE__) . 'js/script_rm_admin.js', array('jquery', 'jquery-ui-core', 'jquery-ui-sortable', 'jquery-ui-tabs', 'jquery-ui-datepicker','jquery-ui-droppable','jquery-ui-draggable'), $this->version, false);

        $rm_admin_vars= array(

                        'user_deletion_warning'=>__("Are you sure, you want to delete the selected Users?",'custom-registration-form-builder-with-submission-manager'),

                        'custom_tab_icon_msg' => __("Please select an icon for this tab.",'custom-registration-form-builder-with-submission-manager'),

                        'nonce'=>wp_create_nonce('rm_ajax_secure'),

        );

        wp_localize_script($this->plugin_name,'rm_admin_vars',$rm_admin_vars);

        wp_localize_script('rm-utilities','rm_admin_vars',$rm_admin_vars);

        wp_register_script('script_rm_moment', RM_BASE_URL . 'admin/js/moment.min.js', array($this->plugin_name), $this->version, false);

        wp_register_script('script_rm_daterangepicker', RM_BASE_URL . 'admin/js/daterangepicker.min.js', array($this->plugin_name), $this->version, false);

        if(defined('REGMAGIC_ADDON'))

            wp_register_script('rm-rating', RM_ADDON_BASE_URL . 'public/js/rating3/jquery.rateit.js', array(), $this->version, false);

        wp_register_script('google_charts', 'https://www.gstatic.com/charts/loader.js');

        wp_register_script('script_rm_formcard_menu', RM_BASE_URL . 'admin/js/script_rm_formcard_menu.js', array($this->plugin_name), $this->version, false);

        wp_localize_script('script_rm_formcard_menu','rm_admin_vars',$rm_admin_vars);

        wp_register_script('script_rm_angular', RM_BASE_URL . 'admin/js/angular.min.js', array($this->plugin_name), $this->version, false);

        wp_register_script('rm_jquery_ui_timepicker_addon_js', RM_BASE_URL . 'admin/js/jquery-ui-timepicker-addon.js', array('jquery-ui-datepicker'), $this->version, false);

        wp_register_script('chart_js',RM_BASE_URL . 'admin/js/chart.min.js',array('jquery','google_charts'));

        wp_register_script('rm_select2',RM_BASE_URL.'public/js/script_rm_select2.js', array('jquery'));

        wp_register_style('rm_select2',RM_BASE_URL.'public/css/style_rm_select2.css');

        if(isset($_GET['page']) && RM_Utilities::is_valid_rm_page($_GET['page'])) {

            wp_enqueue_script('rm-color', plugin_dir_url(__FILE__) . 'js/jscolor.min.js', array(), $this->version, false);

            wp_enqueue_script('rm-utilities');

            wp_enqueue_script($this->plugin_name);

            wp_enqueue_script('rm-form-presentation', RM_BASE_URL . 'admin/js/script_rm_form_presentation.js', array('script_rm_angular'), $this->version, false);

            wp_localize_script('rm-form-presentation','pr_data',array('upload_btn_title'=>__('Choose Image','custom-registration-form-builder-with-submission-manager'),'older_ie'=>__('You are using older version of IE. Please update IE to latest version','custom-registration-form-builder-with-submission-manager'),'ajaxnonce' => wp_create_nonce('rm_form_settings_controller')));

        }

    }



    /**

     * Registers menu pages and submenu pages at the admin area.

     *

     * @since    1.0.0

     */

    public function add_menu() {

        if (!class_exists("AAM")){
            global  $rm_env_requirements;

            $gopts = new RM_Options();

            $service = new RM_Setting_Service();

            if(!RM_Utilities::fatal_errors()) {

                global $submenu;

                $roles = wp_roles()->roles;

                $admin_order = $gopts->get_value_of('enable_admin_order') == 'yes' ? $gopts->get_value_of('admin_order') : $gopts->default['admin_order'];

                $role_top_admin = array("administrator");

                foreach ($admin_order as $value) {

                    foreach ( $roles as $role_slug => $role ) {

                        $rm_role = get_role( $role_slug );

                        if (in_array( $role_slug, $value[2] )){

                            if ( ! $rm_role->has_cap( $value[0]."manage_options" ) ) {

                                $rm_role->add_cap( $value[0]."manage_options" );

                            }

                        }else{

                            if ( $rm_role->has_cap( $value[0]."manage_options" ) ) {

                                $rm_role->remove_cap( $value[0]."manage_options" );

                            }

                        }

                    }

                }

                foreach ($admin_order as $value) {

                    foreach ($value[2] as $role){
    
                        if ($value[3] == "visible"){
    
                            if (! in_array( $role, $role_top_admin )){
    
                                array_push($role_top_admin, $role);
    
                            }
    
                        }
    
                    }
    
                }

                foreach ( $roles as $role_slug => $role ) {

                    $rm_role = get_role( $role_slug );

                    if (in_array( $role_slug, $role_top_admin )){

                        if ( ! $rm_role->has_cap( 'top_admin_show' ) ) {

                            $rm_role->add_cap( 'top_admin_show' );

                        }

                    }else{

                        if ( $rm_role->has_cap( 'top_admin_show' ) ) {

                            $rm_role->remove_cap( 'top_admin_show' );

                        }

                    }

                }

                if (current_user_can("top_admin_show")){

                    // top menu
                
                    add_menu_page(

                        RM_UI_Strings::get('ADMIN_MENU_REG'),
    
                        RM_UI_Strings::get('ADMIN_MENU_REG'),
    
                        "top_admin_show",
    
                        "rm_form_manage",
    
                        array($this->get_controller(), 'run'),
    
                        'data:image/svg+xml;base64,' . $this->icon,
    
                        26
    
                    );

                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_SUBMENU_REG'), RM_UI_Strings::get('ADMIN_SUBMENU_REG'), "rm_form_managemanage_options", "rm_form_manage", array($this->get_controller(), 'run'));



            

                    // sub menu

                    // add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_SUBMENU_REG'), RM_UI_Strings::get('ADMIN_SUBMENU_REG'), "rm_form_managemanage_options", "rm_form_manage", array($this->get_controller(), 'run'));

                    // dunamic index menu

                    $unread_count_show = " ";

                    if(defined('REGMAGIC_ADDON')) {

                        if ($gopts->get_value_of('inbox_badge') == 0){

                            $unread_count_show = "";

                        } else {
                            $unread_count = RM_DBManager::get_all_submission_read_count($gopts->get_value_of('inbox_badge'));
                            if($unread_count > 999){
                                $unread_count_show = "<span class='update-plugins'> 999+ </span>";
                            } elseif($unread_count <= 0) {
                                $unread_count_show = "";
                            } else {
                                $unread_count_show = "<span class='update-plugins'> $unread_count </span>";
                            }
                        }
                    }

                

                    // dynamic user menu

                    //$users = new RM_User_Services();

                    //$suspended_count = count($users->get_users("","","","pending"));



                    // add menus

                    $i = 0;
                    
                    foreach ($admin_order as $value) {

                        if ($value[3] == "visible"){
                            $i++;
                            if ($value[1] == "Inbox"){
                                $menu_title = $value[1]. " $unread_count_show";
                            }else if($value[1] == "Users"){
                                //$menu_title = $value[1]. "<br><span class='rm-inactive-count'>Inactive ".$suspended_count."</span>";
                                $menu_title = $value[1];
                            }else{
                                $menu_title = $value[1];
                            }

                            if ($value[0] == 'rm_form_manage') {
                                // Forms
                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Forms options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), $value[0]."manage_options", "rm_form_setup", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), $value[0]."manage_options", "rm_form_setup_finished", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), $value[0]."manage_options", "rm_form_import", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), $value[0]."manage_options", "rm_field_manage", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), $value[0]."manage_options", "rm_form_sett_view", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), $value[0]."manage_options", "rm_form_sett_general", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), $value[0]."manage_options", "rm_form_sett_accounts", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), $value[0]."manage_options", "rm_form_sett_post_sub", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), $value[0]."manage_options", "rm_form_sett_autoresponder", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), $value[0]."manage_options", "rm_form_sett_limits", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), $value[0]."manage_options", "rm_form_sett_email_templates", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), $value[0]."manage_options", "rm_form_sett_mailchimp", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), $value[0]."manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), $value[0]."manage_options", "rm_field_add", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PT'), $value[0]."manage_options", "rm_form_sett_manage", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), $value[0]."manage_options", "rm_form_sett_aweber", array($this->get_controller(), 'run'));
                                
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), $value[0]."manage_options", "rm_form_sett_access_control", array($this->get_controller(), 'run'));
                                
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), $value[0]."manage_options", "rm_form_sett_override", array($this->get_controller(), 'run'));
                                
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), $value[0]."manage_options", "rm_form_sett_ccontact", array($this->get_controller(), 'run'));
                                
                                add_submenu_page("rm_dummy_string", __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager'), __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager') , $value[0]."manage_options", "rm_login_sett_manage", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_field_manage", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_field_add", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_field_view_sett", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_view", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_sett_redirections", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_val_sec", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_recovery", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_two_factor_auth", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_email_temp", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'), __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_integrations", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_analytics", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_retention", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_login_advanced", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_dashboard_widget_dashboard') {

                                // Overview

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Overview options

                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup", array($this->get_controller(), 'run'));

                            } elseif($value[0] == 'rm_form_manage_cstatus') {
                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));
                                add_submenu_page("rm_dummy_string", __('Add Custom Status', 'custom-registration-form-builder-with-submission-manager'), __('Add Custom Status', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_form_add_cstatus", array($this->get_controller(), 'run'));
                            } elseif ($value[0] == 'rm_submission_manage') {

                                // Inbox

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Inbox options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), $value[0]."manage_options", "rm_submission_view", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_attachment_manage') {

                                // Attachments
                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // For Premium Only
                                /*
                                if(defined('REGMAGIC_ADDON'))
                                    add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));
                                else
                                    $i--;
                                */
                                // Attachments options
                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), $value[0]."manage_options", "rm_attachment_download", array($this->get_controller(), 'run'));
                            
                            } elseif ($value[0] == 'rm_invitations_manage') {

                                // Bullk Email

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Bullk Email options

                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_user_manage') {

                                // Users

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Users options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REG_PT'), RM_UI_Strings::get('ADMIN_MENU_REG_PT'), $value[0]."manage_options", "rm_user_view", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_user_role_manage') {

                                // Roles

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Roles options

                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_paypal_field_manage') {

                                // Products

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Products options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), $value[0]."manage_options", "rm_paypal_field_add", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_payments_manage') {

                                // Payments

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Payments options

                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), $value[0]."manage_options", "rm_payments_view", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), $value[0]."manage_options", "rm_note_add", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_ex_chronos_manage_tasks') {

                                // attachments menu

                                do_action("rm_admin_menu_after_field_stats",$value[0]);

                            } elseif ($value[0] == 'rm_analytics_show_form') {

                                // Analytics > FORMS

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Analytics > FORMS options

                                // add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_analytics_show_field') {

                                // Analytics > FIELDS
                                if(defined('REGMAGIC_ADDON'))
                                    add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));
                                else
                                    $i--;
                                
                                // Analytics > FIELDS options

                                // add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                            } elseif ($value[0] == 'rm_reports_dashboard') {

                                // Reports
                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                                // Reports options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), $value[0]."manage_options", "rm_reports_submissions", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), $value[0]."manage_options", "rm_reports_login", array($this->get_controller(), 'run'));

                                if (class_exists('Registration_Magic_Addon')){

                                    add_submenu_page("rm_dummy_string", __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_attachments", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_payments", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_form_compare", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_notifications", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Add Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Add Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_notification_add", array($this->get_controller(), 'run'));

                                }

                            } elseif ($value[0] == 'rm_options_manage') {

                                // setting

                                add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), $value[0]."manage_options", "rm_options_manage", array($this->get_controller(), 'run'));

                                // setting options

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), $value[0]."manage_options", "rm_options_fab", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AS_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_security", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), $value[0]."manage_options", "rm_options_user", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), $value[0]."manage_options", "rm_options_autoresponder", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), $value[0]."manage_options", "rm_options_thirdparty", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), $value[0]."manage_options", "rm_options_payment", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), $value[0]."manage_options", "rm_options_default_pages", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_options_user_privacy", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_options_advance", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'),  __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_form_sett_profilegrid", array($this->get_controller(), 'run'));

                                if (current_user_can( "manage_options" )){

                                    add_submenu_page("rm_dummy_string", __('Arrange Admin Menu', 'custom-registration-form-builder-with-submission-manager'), __('Arrange Admin Menu', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_options_admin_menu", array($this->get_controller(), 'run'));

                                }

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), $value[0]."manage_options", "rm_options_tabs", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), $value[0]."manage_options", "rm_options_manage_invoice", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_CTABS'), RM_UI_Strings::get('ADMIN_MENU_CTABS'), $value[0]."manage_options", "rm_options_manage_ctabs", array($this->get_controller(), 'run'));

                                if (class_exists('Registration_Magic_Addon')){
                                    add_submenu_page("rm_dummy_string", __('Add Custom Tabs', 'custom-registration-form-builder-with-submission-manager'), __('Add Custom Tabs', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_options_add_ctabs", array($this->get_controller(), 'run')); 
                                }
                                } elseif ($value[0] == 'rm_sent_emails_manage') {
                                    add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), $value[0]."manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));
                                } else {

                                add_submenu_page("rm_form_manage", $menu_title, $menu_title, $value[0]."manage_options", $value[0], array($this->get_controller(), 'run'));

                            }

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_PP_PROC_PT'), "", $value[0]."manage_options", "rm_paypal_proc", array($this->get_controller(), 'run'));

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), $value[0]."manage_options", "rm_submission_related", array($this->get_controller(), 'run'));

                            //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), $value[0]."manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), $value[0]."manage_options", "rm_options_save", array($this->get_controller(), 'run'));

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), $value[0]."manage_options", "rm_support_forum", array($this->get_controller(), 'run'));

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), $value[0]."manage_options", "rm_user_role_delete", array($this->get_controller(), 'run'));

                            add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), $value[0]."manage_options", "rm_field_add_widget", array($this->get_controller(), 'run'));

                            if (class_exists('Registration_Magic_Addon')){
                                add_submenu_page("rm_dummy_string", __('Add Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), $value[0]."manage_options", "rm_reports_notification_add", array($this->get_controller(), 'run'));
                            }
                            if (isset($value[4]) && $value[4] == "true") {
                                
                                if (isset($submenu['rm_form_manage'])){

                                    if (array_key_exists($i, $submenu['rm_form_manage'])) {
                                        
                                        $submenu['rm_form_manage'][$i][4] = "rm-show-divider";

                                    }

                                }

                            }

                        } else {
                            $menu_title = $value[1];

                            if (current_user_can( "manage_options" )){

                                if ($value[0] == 'rm_form_manage') {

                                    // Forms

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Forms options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup_finished", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), "manage_options", "rm_form_import", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), "manage_options", "rm_field_manage", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), "manage_options", "rm_form_sett_view", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_form_sett_general", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), "manage_options", "rm_form_sett_accounts", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), "manage_options", "rm_form_sett_post_sub", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), "manage_options", "rm_form_sett_autoresponder", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), "manage_options", "rm_form_sett_limits", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), "manage_options", "rm_form_sett_email_templates", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), "manage_options", "rm_form_sett_mailchimp", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PT'), "manage_options", "rm_form_sett_manage", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), "manage_options", "rm_form_sett_aweber", array($this->get_controller(), 'run'));
                                    
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), "manage_options", "rm_form_sett_access_control", array($this->get_controller(), 'run'));
                                    
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), "manage_options", "rm_form_sett_override", array($this->get_controller(), 'run'));
                                    
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), "manage_options", "rm_form_sett_ccontact", array($this->get_controller(), 'run'));
                                    
                                    add_submenu_page("rm_dummy_string", __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager'), __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_sett_manage", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_manage", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_add", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_view_sett", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_view", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string",  __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_sett_redirections", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_val_sec", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_recovery", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_two_factor_auth", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_email_temp", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string",  __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'), __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_integrations", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_analytics", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_retention", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_advanced", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_dashboard_widget_dashboard') {

                                    // Overview

                                    //add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Overview options

                                    //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup", array($this->get_controller(), 'run'));

                                } elseif($value[0] == 'rm_form_manage_cstatus') {

                                    if (class_exists('Registration_Magic_Addon')){

                                        //add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    }else{

                                        $i--;

                                        continue;

                                    }

                                } elseif ($value[0] == 'rm_submission_manage') {

                                    // Inbox

                                    //add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Inbox options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), "manage_options", "rm_submission_view", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_attachment_manage') {

                                    // Attachments

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Attachments options

                                    //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), "manage_options", "rm_attachment_download", array($this->get_controller(), 'run'));
                                
                                } elseif ($value[0] == 'rm_invitations_manage') {

                                    // Bullk Email

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Bullk Email options

                                    //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_user_manage') {

                                    // Users

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Users options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REG_PT'), RM_UI_Strings::get('ADMIN_MENU_REG_PT'), "manage_options", "rm_user_view", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_user_role_manage') {

                                    // Roles

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Roles options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_paypal_field_manage') {

                                    // Products

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Products options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), "manage_options", "rm_paypal_field_add", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_payments_manage') {

                                    // Payments

                                    // add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Payments options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), "manage_options", "rm_payments_view", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), "manage_options", "rm_note_add", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_ex_chronos_manage_tasks') {

                                    // attachments menu

                                    // do_action("rm_admin_menu_after_field_stats","");

                                } elseif ($value[0] == 'rm_analytics_show_form') {

                                    // Analytics > FORMS

                                    add_submenu_page("rm_dummy_string", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Analytics > FORMS options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_analytics_show_field') {

                                    // Analytics > FIELDS

                                    add_submenu_page("rm_dummy_string", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Analytics > FIELDS options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_reports_dashboard') {

                                    // Reports
                                    add_submenu_page("rm_dummy_string", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));

                                    // Reports options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), "manage_options", "rm_reports_submissions", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), "manage_options", "rm_reports_login", array($this->get_controller(), 'run'));

                                    if (class_exists('Registration_Magic_Addon')){

                                        add_submenu_page("rm_dummy_string", __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_attachments", array($this->get_controller(), 'run'));

                                        add_submenu_page("rm_dummy_string", __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_payments", array($this->get_controller(), 'run'));

                                        add_submenu_page("rm_dummy_string", __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_form_compare", array($this->get_controller(), 'run'));

                                        add_submenu_page("rm_dummy_string", __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_notifications", array($this->get_controller(), 'run'));

                                        add_submenu_page("rm_dummy_string", __('Add Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_notification_add", array($this->get_controller(), 'run'));

                                    }

                                } elseif ($value[0] == 'rm_options_manage') {

                                    // setting

                                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), "manage_options", "rm_options_manage", array($this->get_controller(), 'run'));

                                    // setting options

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), "manage_options", "rm_options_fab", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AS_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_security", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), "manage_options", "rm_options_user", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_autoresponder", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_thirdparty", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_payment", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_default_pages", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_options_user_privacy", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_options_advance", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'), __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_form_sett_profilegrid", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_CTABS'), RM_UI_Strings::get('ADMIN_MENU_CTABS'), "manage_options", "rm_options_manage_ctabs", array($this->get_controller(), 'run'));

                                    if (current_user_can( "manage_options" )){

                                        add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ARRANGE_ADMIN_MENU'), RM_UI_Strings::get('ARRANGE_ADMIN_MENU'), "manage_options", "rm_options_admin_menu", array($this->get_controller(), 'run'));

                                    }

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_tabs", array($this->get_controller(), 'run'));

                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), "manage_options", "rm_options_manage_invoice", array($this->get_controller(), 'run'));

                                } elseif ($value[0] == 'rm_sent_emails_manage') {
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), $value[0]."manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));
                                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), $value[0]."manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));
                                } else {
                                    add_submenu_page("rm_form_manage", $menu_title, $menu_title, "manage_options", $value[0], array($this->get_controller(), 'run'));
                                }

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_PP_PROC_PT'), "", "manage_options", "rm_paypal_proc", array($this->get_controller(), 'run'));

                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), "manage_options", "rm_submission_related", array($this->get_controller(), 'run'));
        
                                //add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));
        
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), "manage_options", "rm_options_save", array($this->get_controller(), 'run'));
        
                                add_submenu_page("rm_dummy_string", __('Licensing', 'custom-registration-form-builder-with-submission-manager'), __('Licensing', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_licensing", array($this, 'rm_licensing'));
        
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), "manage_options", "rm_support_forum", array($this->get_controller(), 'run'));
        
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), "manage_options", "rm_user_role_delete", array($this->get_controller(), 'run'));
        
                                add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add_widget", array($this->get_controller(), 'run'));

                            }

                        }
                    }

                    // licensing
                    
                    add_submenu_page("rm_dummy_string", __('Licensing', 'custom-registration-form-builder-with-submission-manager'), __('Licensing', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_licensing", array($this, 'rm_licensing'));

                    // support

                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), "top_admin_show", "rm_support_forum", array($this->get_controller(), 'run'));

                    // end of support

                    if(!defined('REGMAGIC_ADDON')) {

                        add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PREMIUM'), "<div style='color:#ff6c6c; display: inline'>".RM_UI_Strings::get('ADMIN_MENU_PREMIUM')."</div>", "manage_options", "rm_support_premium_page", array($this->get_controller(), 'run'));

                    } else {

                        $addon_admin = new RM_Admin_Addon();

                        $addon_admin->add_menu($this);

                    }

                    // making the first submenu be the default submenu of top menu 

                    $submenu['rm_form_manage'][0][2] = $submenu[ 'rm_form_manage' ][1][2];


                    // hiding forms from main admin menu

                    //wp_enqueue_style( 'rm-form-manage', plugin_dir_url(__FILE__) . 'css/style_rm_form_manage.css' );
                    
                }
            }
        }else{
            if (current_user_can('manage_options')) {
                global  $rm_env_requirements;
                $gopts = new RM_Options();
                $service = new RM_Setting_Service();
                if(!RM_Utilities::fatal_errors()) {
                    global $submenu;
                    // dunamic index menu
                    $unread_count_show = " ";
                    if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION, '5.1.9.9', '>=')) {
                        $unread_count = RM_DBManager::get_all_submission_read_count(1);
                        if($unread_count > 999) {
                            $unread_count_show = "<span class='update-plugins'> 999+ </span>";
                        } elseif($unread_count <= 0) {
                            $unread_count_show = "";
                        } else {
                            $unread_count_show = "<span class='update-plugins'> $unread_count </span>";
                        }
                    }
                    // dynamic user
                    //$users = new RM_User_Services();
                    //$suspended_count = count($users->get_users("","","","pending"));
                    
                    add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this->get_controller(), 'run'),  'data:image/svg+xml;base64,' . $this->icon, 26);
                    //add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_dashboard_widget_dashboard", array($this->get_controller(), 'run'),  'data:image/svg+xml;base64,' . $this->icon, 26);
                    //add_submenu_page("rm_dashboard_widget_dashboard", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM'), "manage_options", "rm_form_add", array($this->get_controller(), 'run'));
                    //add_submenu_page("rm_dashboard_widget_dashboard", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_dashboard_widget_dashboard&create_new_form", "__return_null");
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_SUBMENU_REG'), RM_UI_Strings::get('ADMIN_SUBMENU_REG'), "manage_options", "rm_form_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_DASH'), RM_UI_Strings::get('ADMIN_MENU_DASH'), "manage_options", "rm_dashboard_widget_dashboard", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), RM_UI_Strings::get('ADMIN_MENU_NEWFORM_PT'), "manage_options", "rm_form_sett_general", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SUBS'), RM_UI_Strings::get('ADMIN_MENU_SUBS')."$unread_count_show", "manage_options", "rm_submission_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ATTS'), RM_UI_Strings::get('ADMIN_MENU_ATTS'), "manage_options", "rm_attachment_manage", array($this->get_controller(), 'run'));
                    //add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_USERS'), RM_UI_Strings::get('ADMIN_MENU_USERS')."<br> <span style='font-size:0.55rem'>".__('INACTIVE','custom-registration-form-builder-with-submission-manager')." ".$suspended_count."</span>", "manage_options", "rm_user_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_USERS'), RM_UI_Strings::get('ADMIN_MENU_USERS'), "manage_options", "rm_user_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_ROLES'), RM_UI_Strings::get('ADMIN_MENU_ROLES'), "manage_options", "rm_user_role_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PRICE'), RM_UI_Strings::get('ADMIN_MENU_PRICE'), "manage_options", "rm_paypal_field_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('PAYMENTS_MENU'), RM_UI_Strings::get('PAYMENTS_MENU'), "manage_options", "rm_payments_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), RM_UI_Strings::get('ADMIN_MENU_MNG_FIELDS_PT'), "manage_options", "rm_field_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add", array($this->get_controller(), 'run'));
                    do_action("rm_admin_menu_after_field_stats");
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), RM_UI_Strings::get('ADMIN_MENU_FORM_STATS'), "manage_options", "rm_analytics_show_form", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), RM_UI_Strings::get('ADMIN_MENU_FIELD_STATS'), "manage_options", "rm_analytics_show_field", array($this->get_controller(), 'run'));
                    
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_INV'), RM_UI_Strings::get('ADMIN_MENU_INV'), "manage_options", "rm_invitations_manage", array($this->get_controller(), 'run'));
                    
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_PP_FIELD_PT'), "manage_options", "rm_paypal_field_add", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_PP_PROC_PT'), "", "manage_options", "rm_paypal_proc", array($this->get_controller(), 'run'));                
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), RM_UI_Strings::get('ADMIN_MENU_ATT_DL_PT'), "manage_options", "rm_attachment_download", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_PT'), "manage_options", "rm_submission_view", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), RM_UI_Strings::get('ADMIN_MENU_VIEW_SUB_RELATED'), "manage_options", "rm_submission_related", array($this->get_controller(), 'run'));
                    
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), RM_UI_Strings::get('ADMIN_MENU_SENT_MAILS'), "manage_options", "rm_sent_emails_view", array($this->get_controller(), 'run'));

                    //Sub menu for User role section 8th March 2016
                    
                    
                    //Payments Menu
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), RM_UI_Strings::get('PAYMENTS_VIEW_MENU'), "manage_options", "rm_payments_view", array($this->get_controller(), 'run'));
                    
                    
                    /* Reports Menus */ 
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS'), RM_UI_Strings::get('ADMIN_MENU_REPORTS'), "manage_options", "rm_reports_dashboard", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_SUB'), "manage_options", "rm_reports_submissions", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), RM_UI_Strings::get('ADMIN_MENU_REPORTS_LOGIN'), "manage_options", "rm_reports_login", array($this->get_controller(), 'run'));
                    
                    if (class_exists('Registration_Magic_Addon')){
                        add_submenu_page("rm_dummy_string", __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Attachments Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_attachments", array($this->get_controller(), 'run'));
                        add_submenu_page("rm_dummy_string", __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), __('Payments Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_payments", array($this->get_controller(), 'run'));
                        add_submenu_page("rm_dummy_string", __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), __('Compare Form Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_form_compare", array($this->get_controller(), 'run'));
                        add_submenu_page("rm_dummy_string", __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_notifications", array($this->get_controller(), 'run'));
                        add_submenu_page("rm_dummy_string", __('Add Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), __('Notifications Reports', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_reports_notification_add", array($this->get_controller(), 'run'));
                    }

                    /* Option menues */
                    add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), RM_UI_Strings::get('ADMIN_MENU_SETTINGS'), "manage_options", "rm_options_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_general", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_tabs", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_FAB_PT'), "manage_options", "rm_options_fab", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AS_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_GEN_PT'), "manage_options", "rm_options_security", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_UA_PT'), "manage_options", "rm_options_user", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_autoresponder", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_TP_PT'), "manage_options", "rm_options_thirdparty", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_payment", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_PP_PT'), "manage_options", "rm_options_default_pages", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), __('User Privacy', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_options_user_privacy", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), RM_UI_Strings::get('ADMIN_MENU_SETTING_SAVE_PT'), "manage_options", "rm_options_save", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_NOTE_PT'), "manage_options", "rm_note_add", array($this->get_controller(), 'run'));
                    
                    
                    /* End of settings */
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), RM_UI_Strings::get('ADMIN_MENU_FS_IMPORT'), "manage_options", "rm_form_import", array($this->get_controller(), 'run'));
                    
                    //add_submenu_page("rm_dashboard_widget_dashboard", RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), RM_UI_Strings::get('ADMIN_MENU_FRONTEND'), "manage_options", "rm_support_frontend", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Licensing', 'custom-registration-form-builder-with-submission-manager'), __('Licensing', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_licensing", array($this, 'rm_licensing'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), RM_UI_Strings::get('ADMIN_MENU_SUPPORT'), "manage_options", "rm_support_forum", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), RM_UI_Strings::get('ADMIN_MENU_USER_ROLE_DEL_PT'), "manage_options", "rm_user_role_delete", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_REG_PT'), RM_UI_Strings::get('ADMIN_MENU_REG_PT'), "manage_options", "rm_user_view", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_CC_PT'), "manage_options", "rm_form_sett_ccontact", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AW_PT'), "manage_options", "rm_form_sett_aweber", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_OV_PT'), "manage_options", "rm_form_sett_override", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_AR_PT'), "manage_options", "rm_form_sett_autoresponder", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ET_PT'), "manage_options", "rm_form_sett_email_templates", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_LIM_PT'), "manage_options", "rm_form_sett_limits", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PST_SUB_PT'), "manage_options", "rm_form_sett_post_sub", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACC_PT'), "manage_options", "rm_form_sett_accounts", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_VIEW_PT'), "manage_options", "rm_form_sett_view", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_MC_PT'), "manage_options", "rm_form_sett_mailchimp", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_PT'), "manage_options", "rm_form_sett_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), RM_UI_Strings::get('ADMIN_MENU_FS_ACTRL_PT'), "manage_options", "rm_form_sett_access_control", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_SETUP'), RM_UI_Strings::get('ADMIN_MENU_SETUP'), "manage_options", "rm_form_setup_finished", array($this->get_controller(), 'run'));
                    if(!defined('REGMAGIC_ADDON')) {
                        add_submenu_page("rm_form_manage", RM_UI_Strings::get('ADMIN_MENU_PREMIUM'), "<div style='color:#ff6c6c;'>".RM_UI_Strings::get('ADMIN_MENU_PREMIUM')."</div>", "manage_options", "rm_support_premium_page", array($this->get_controller(), 'run'));
                    } else {
                        $addon_admin = new RM_Admin_Addon();
                        $addon_admin->add_menu($this);
                    }
                
                    // add_submenu_page("rm_dummy_string","What's New!","What's New", "manage_options", "rm_whats_new", array($this, 'show_whatsnew'));
                    $submenu['rm_form_manage'][0][0] = RM_UI_Strings::get('ADMIN_SUBMENU_REG');
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), RM_UI_Strings::get('ADMIN_MENU_ADD_FIELD_PT'), "manage_options", "rm_field_add_widget", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), __('Advance Options', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_options_advance", array($this->get_controller(), 'run'));
                    
                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_add", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), __('Login Fields', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_field_view_sett", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), __('Logged in View', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_view", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager'), __('Login Dashboard', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_sett_manage", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), __('Login Redirections', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_sett_redirections", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), __('Login Validation & Security', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_val_sec", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), __('Password Recovery', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_recovery", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), __('Email Templates', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_email_temp", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), __('Two Factor Authentication', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_two_factor_auth", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'),  __('Third Part Integrations', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_integrations", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), __('Login Analytics', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_analytics", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), __('Log Retention', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_retention", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), __('Advanced Log', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_login_advanced", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'), __('ProfileGrid', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_form_sett_profilegrid", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", __('EventPrime', 'custom-registration-form-builder-with-submission-manager'), __('EventPrime', 'custom-registration-form-builder-with-submission-manager'), "manage_options", "rm_options_eventprime", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ADMIN_MENU_CTABS'), RM_UI_Strings::get('ADMIN_MENU_CTABS'), "manage_options", "rm_options_manage_ctabs", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), RM_UI_Strings::get('PAYMENTS_INVOICE_SETTING_MENU'), "manage_options", "rm_options_manage_invoice", array($this->get_controller(), 'run'));
                    add_submenu_page("rm_dummy_string", RM_UI_Strings::get('ARRANGE_ADMIN_MENU'), RM_UI_Strings::get('ARRANGE_ADMIN_MENU'), "manage_options", "rm_options_admin_menu", array($this->get_controller(), 'run'));
                } else {
                    add_menu_page(RM_UI_Strings::get('ADMIN_MENU_REG'), RM_UI_Strings::get('ADMIN_MENU_REG'), "manage_options", "rm_form_manage", array($this, 'fatal_error_message_display'), plugins_url('../images/profile-icon2.png', __FILE__), 26);
                }
                wp_enqueue_style( 'rm-form-manage-aam', plugin_dir_url(__FILE__) . 'css/style_rm_form_manage_aam.css' );
            }
        }
    }

    

    //To disaply errors on menu page. Such as SimplXML extension not available or PHP version.

    public function fatal_error_message_display()

    {        

        include_once RM_ADMIN_DIR.'views/template_rm_cant_continue.php';

    }



    public function add_dashboard_widget()

    {

        //Dashboard widget is for admin users only.

        if (current_user_can("manage_options"))

        {

            wp_add_dashboard_widget(

                    'rm_dashboard_widget_display', // Widget slug.

                    RM_UI_Strings::get('TITLE_DASHBOARD_WIDGET'), // Title.

                    array($this, 'dashboard_widget_display_function')

            );

        }

    }



    public function dashboard_widget_display_function() {

        $xml_loader = defined('REGMAGIC_ADDON') ? RM_XML_Loader::getInstance(RM_ADDON_INCLUDES_DIR . 'rm_config.xml'): RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');



        $request = new RM_Request($xml_loader);

        $request->setReqSlug('rm_dashboard_widget_display', true);



        $params = array('request' => $request, 'xml_loader' => $xml_loader);

        $this->controller = new RM_Main_Controller($params);

        $this->controller->run();

    }



    public function user_edit_page_widget($user) {

        $xml_loader = defined('REGMAGIC_ADDON') ? RM_XML_Loader::getInstance(RM_ADDON_INCLUDES_DIR . 'rm_config.xml'): RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');



        $request = new RM_Request($xml_loader);

        $request->setReqSlug('rm_user_widget', true);



        $params = array('request' => $request, 'xml_loader' => $xml_loader, 'user' => $user);

        $this->controller = new RM_Main_Controller($params);

        $this->controller->run();

    }



    function add_new_form_editor_button() {

        if (is_admin()) {

            $screen = get_current_screen();

            if (!empty($screen) && $screen->base == 'post') {

                $xml_loader = defined('REGMAGIC_ADDON') ? RM_XML_Loader::getInstance(RM_ADDON_INCLUDES_DIR . 'rm_config.xml'): RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');



                $request = new RM_Request($xml_loader);

                $request->setReqSlug('rm_editor_actions_add_form', true);



                $params = array('request' => $request, 'xml_loader' => $xml_loader);

                $this->controller = new RM_Main_Controller($params);

                $this->controller->run();

            }

        }

    }



    function add_field_autoresponder() {

        if (is_admin()) {

            $screen = get_current_screen();

            if (!empty($screen) && $screen->base == 'admin_page_rm_form_sett_autoresponder') {

                if (self::$editor_counter == 1) {

                    $xml_loader = defined('REGMAGIC_ADDON') ? RM_XML_Loader::getInstance(RM_ADDON_INCLUDES_DIR . 'rm_config.xml'): RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');



                    $request = new RM_Request($xml_loader);

                    $request->setReqSlug('rm_editor_actions_add_email', true);



                    $params = array('request' => $request, 'xml_loader' => $xml_loader);

                    $this->controller = new RM_Main_Controller($params);

                    $this->controller->run();

                }



                self::$editor_counter = self::$editor_counter + 1;

            } elseif (!empty($screen) && $screen->base == 'registrationmagic_page_rm_invitations_manage') {

                $xml_loader = defined('REGMAGIC_ADDON') ? RM_XML_Loader::getInstance(RM_ADDON_INCLUDES_DIR . 'rm_config.xml'): RM_XML_Loader::getInstance(RM_INCLUDES_DIR . 'rm_config.xml');



                $request = new RM_Request($xml_loader);

                $request->setReqSlug('rm_editor_actions_add_fields_dropdown_invites', true);



                $params = array('request' => $request, 'xml_loader' => $xml_loader);

                $this->controller = new RM_Main_Controller($params);

                $this->controller->run();

            }

        }

    }

    

    

    

    public function remove_queue()

    {

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {

            $inv_service = new RM_Invitations_Service;

            $form_id= sanitize_text_field($_POST['form_id']);



            $inv_service->remove_queue($form_id);

        }

        wp_die();

    }

    

    public function update_submit_field_config()

    {

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {

            $service = new RM_Services;

            $form_id= sanitize_text_field($_POST['form_id']);

            $config = is_array($_POST['data']) ? array_map('sanitize_text_field', $_POST['data']) : sanitize_text_field($_POST['data']);

            $service->update_submit_field_config($form_id, $config);

        }

        wp_die();

    }

    

    public function update_login_button_config()

    {  

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {        

            $service = new RM_Login_Service();

            $config = is_array($_POST['data']) ? array_map('sanitize_text_field', $_POST['data']) : sanitize_text_field($_POST['data']);

            $data= array();

            $data['register_btn']= sanitize_text_field($config['register_btn_label']);

            $data['login_btn']= sanitize_text_field($config['login_btn_label']);

            $data['align']= sanitize_text_field($config['btn_align']);

            $data['display_register']= absint($config['display_register']);

            $service->update_button_config($data);

        }

        wp_die();

    }

    

    public function add_version_header() { ?>
        <style>
            .rmagic::before {content:"v<?php echo defined('REGMAGIC_ADDON') ? esc_html(RM_ADDON_PLUGIN_VERSION . " Premium") : esc_html(RM_PLUGIN_VERSION . " Standard"); ?>"}
            .rmagic.rm-hide-version-number::before { display:none}
        </style>
        <?php
    }

    

    public function feedback_dialog()
    {
        if(!is_admin())
            return;
    
        $screen = get_current_screen();
        
        if(!isset($screen->id))
            return;

        if (!in_array($screen->id, array('plugins', 'plugins-network' )))
            return;
        
        wp_enqueue_style('rm_deactivate_message', RM_BASE_URL . 'admin/css/rm_deactivate_message.css', array(), RM_PLUGIN_VERSION, 'all');
        include_once RM_ADMIN_DIR.'views/template_rm_plugin_feedback_dialog.php';
    }

    public function deactivate_message()
    {
        if(!is_admin())
            return;

        $screen = get_current_screen();

        if(!isset($screen->id))
            return;
        

        if (!in_array($screen->id, array('plugins', 'plugins-network')))
            return;
        
        include_once RM_ADMIN_DIR.'views/template_rm_deactivate_message.php';
    }

    

    public function show_whatsnew(){

        include RM_ADMIN_DIR.'views/template_rm_whats_new.php';

    }  

   

    

    public function post_feedback() {
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can('manage_options')) {
            $msg = isset($_POST['msg']) ? wp_kses_post((string)$_POST['msg']) : '';
            $feedback = sanitize_text_field($_POST['feedback']);
            $add_option = absint($_POST['add_option']);
            $email = sanitize_email($_POST['email']);
            $body = '';
            $ticket = false;
            if(trim((string)$feedback) != '') {
                switch($feedback) {
                    case 'feature_not_available':
                        $body = 'Feature not available: ';
                        if($add_option == 1) $ticket = true;
                        break;
                    case 'feature_not_working':
                        $body = 'Feature not working: ';
                        if($add_option == 1) $ticket = true;
                        break;
                    case 'plugin_difficult_to_use':
                        $body = 'Plugin difficult to use: ';
                        break;
                    case 'plugin_broke_site':
                        $body = 'Plugin broke my site: ';
                        if($add_option == 1) $ticket = true;
                        break;
                    case 'temporary_deactivation':
                        $body = 'It\'s a temporary deactivation: ';
                        break;
                    case 'plugin_has_design_issue':
                        $body = 'Plugin has design issues: ';
                        if($add_option == 1) $ticket = true;
                        break;
                    case 'plugin_missing_documentation':
                        $body = 'Missing documentation: ';
                        break;
                    case 'other_reasons':
                        $body = 'Other reasons: ';
                        break;
                    //case 'other': $body = 'Other: '; break;
                    default: return;
                }

                $body .= '<p>'.$msg.'</p>';
                $body .= '<p>RegistrationMagic Standard - version '.RM_PLUGIN_VERSION.'</p>';
                if($ticket)
                    RM_Utilities::quick_email('support@registrationmagic.com', 'RegistrationMagic Deactivation Feedback', $body, RM_EMAIL_GENERIC, array('do_not_save'=>true, 'from'=>$email));
                RM_Utilities::quick_email('feedback@registrationmagic.com', 'RegistrationMagic Deactivation Feedback', $body, RM_EMAIL_GENERIC, array('do_not_save'=>true));
            }
            
            wp_die();
        }
    }



    public function disable_notice(){

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {

            if(defined('REGMAGIC_ADDON')) {

                $addon_admin = new RM_Admin_Addon();

                return $addon_admin->disable_notice();

            }

        }

    }



    public function custom_status_update(){
        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {
            if(defined('REGMAGIC_ADDON')) {
                $addon_admin = new RM_Admin_Addon();
                return $addon_admin->custom_status_update();
            }
            $sub_id= absint($_REQUEST['submission_id']);
            $submission= new RM_Submissions();
            $submission->load_from_db($sub_id);
            
            if(isset($_REQUEST['action_type'])){
                $user_model= new RM_User;
                $service = new RM_Services();
                if($_REQUEST['action_type']=='append'){
                    $form= new RM_Forms();
                    $form->load_from_db($_REQUEST['form_id']);
                    $form_options= $form->get_form_options();
                    $status_data = $form_options->custom_status[$_REQUEST['status_index']];
                    if(isset($status_data['cs_action_status_en']) && $status_data['cs_action_status_en']==1){
                        if($status_data['cs_action_status']=='clear_all'){
                            $service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],'clear_all');
                        }else if($status_data['cs_action_status']=='clear_specific'){
                            $service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],'clear_specific',$status_data['cs_act_status_specific']);
                        }
                    }
                    //echo '<pre>';print_r($status_data);echo '</pre>';die;
                }
                //echo '<pre>';print_r($_REQUEST);echo '</pre>';die;
                echo esc_html($service->update_custom_statuses($_REQUEST['status_index'],$_REQUEST['submission_id'],$_REQUEST['form_id'],$_REQUEST['action_type']));
            }
            wp_die();
        }
    }

    

    public function upload_template(){

       if($_FILES && current_user_can("manage_options") && check_ajax_referer( 'rm_admin_upload_template', 'rm_ajaxnonce' )){

               $name=get_temp_dir().'RMagic.xml';

               if(is_array($_FILES['file']['tmp_name']))

               $status= move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']['0']), $name);

               else

               $status= move_uploaded_file(sanitize_text_field($_FILES['file']['tmp_name']), $name);    

               echo wp_kses_post((string)json_encode(array('success'=>$status)));

        }

        else

        {

            echo wp_kses_post((string)json_encode(array('success'=>false)));

        }

        wp_die();

    }

    

    public function fcm_update_form()

    {

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {

            $service = new RM_Services;

            $form_id= sanitize_text_field($_POST['form_id']);

            $data = is_array($_POST['data']) ? array_map('sanitize_text_field', $_POST['data']) : sanitize_text_field($_POST['data']);

            $service->fcm_update_form($form_id, $data);

        }

        wp_die();

    }
    
    public function generate_plugin_installation_url($plugin_slug) {
        $nonce = wp_create_nonce('install-plugin_' . $plugin_slug);
        $url = admin_url('update.php?action=install-plugin&plugin=' . $plugin_slug . '&_wpnonce=' . $nonce);
        return esc_url($url);
    }

    public function admin_upsell_notices()
    {
        $g_opts= new RM_Options();
        if(!empty($_GET['rm_disable_ep_notice'])){
            $g_opts->set_value_of('ep_notice', 0);
        }
        
        if(!empty($_GET['rm_disable_upgrade_notice']))
        {
            $g_opts->set_value_of('rm_upgrade_notice', 0);
        }
        
        if(!empty($_GET['rm_disable_premium_notice']))
        {
            $g_opts->set_value_of('rm_premium_notice', 0);
        }
        
        
        
        if(!empty($_GET['rm_disable_pg_notice'])){
            $g_opts->set_value_of('pg_notice', 0);
        }
        
        $query_string= $_SERVER['QUERY_STRING'];

        if(empty($query_string)){
            $query_string= '?';
        }
        else
        {
            $query_string= '?'.$query_string.'&';
        }
        
        $ep_notice= !is_null($g_opts->get_value_of('ep_notice'))?$g_opts->get_value_of('ep_notice'):'1';
        $pg_notice= !is_null($g_opts->get_value_of('pg_notice'))?$g_opts->get_value_of('pg_notice'):'1';
        $rm_upgrade_notice = !is_null($g_opts->get_value_of('rm_upgrade_notice'))?$g_opts->get_value_of('rm_upgrade_notice'):'1';
        $rm_premium_notice = !is_null($g_opts->get_value_of('rm_premium_notice'))?$g_opts->get_value_of('rm_premium_notice'):'1';
        if( ! class_exists( 'EventPrime', false ) && $ep_notice!=0 ) {
            if(class_exists('WP_Event_Manager') || defined('EM_VERSION') || class_exists('Ai1ec_Front_Controller') || function_exists('vsel_add_rss_feed') || class_exists('Wpeventin') || function_exists('TotalSoft_Cal_Admin_Style') || defined('EVENT_ORGANISER_URL') || defined('TRIBE_EVENTS_FILE'))
            {
                $ep_url = $this->generate_plugin_installation_url('eventprime-event-calendar-management');
                ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-warning is-dismissible rm-py-2">
                <p><?php printf(__( 'Hey, there 👋 ! It looks like you are publishing events on your website. EventPrime offers you robust event management features, a powerful calendar system and works great with RegistrationMagic. <a href="%s">Click here</a> to try now!','custom-registration-form-builder-with-submission-manager'),$ep_url); ?> </p>
                <a type="button" class="notice-dismiss rm-text-decoration-none" href="<?php echo esc_url($query_string).'rm_disable_ep_notice=1' ?>"><span class="screen-reader-text">Dismiss this notice.</span></a>
            </div>

        <?php
            }
        }
        
        if( ! class_exists( 'Profile_Magic', false ) && $pg_notice!=0 ) {
            if( defined('um_plugin') || defined('UM_PLUGIN') || defined('PMPRO_DIR') || class_exists('BuddyPress') || defined('WPPB_PLUGIN_DIR') || defined('PROFILEPRESS_SYSTEM_FILE_PATH') || class_exists('PeepSo'))
            {
                $pg_url = $this->generate_plugin_installation_url('profilegrid-user-profiles-groups-and-communities');
                
                ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-warning is-dismissible rm-py-2">

                <p><?php printf(__( 'Hey, there 👋 ! It looks like you are building a user community or membership website with user profiles. ProfileGrid, is a powerful and versatile user profile and community plugin and works great with RegistrationMagic. <a href="%s">Click here</a> to try now!','custom-registration-form-builder-with-submission-manager'),$pg_url); ?> </p>
                <a type="button" class="notice-dismiss rm-text-decoration-none" href="<?php echo esc_url($query_string).'rm_disable_pg_notice=1' ?>"><span class="screen-reader-text">Dismiss this notice.</span></a>
            </div>

        <?php
            }
        }
        
        
        if(!defined('REGMAGIC_ADDON') && RM_Utilities::site_has_submissions() && $rm_premium_notice!=0) { 
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-warning is-dismissible rm-py-2 rm-my-2">

                <p><?php printf(__( 'Unlock all RegistrationMagic features by upgrading to Premium. It takes less than 5 minutes! <a href="%s" target="_blank">Click here</a> to start.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/comparison/'); ?> </p>
                <a type="button" class="notice-dismiss rm-text-decoration-none" href="<?php echo esc_url($query_string).'rm_disable_premium_notice=1' ?>"><span class="screen-reader-text">Dismiss this notice.</span></a>
            </div>

        <?php
            
        }
        $premium_id = get_option('rm_premium_license_id','');
        if(defined('REGMAGIC_ADDON') && $this->get_license_status()=='valid' && $premium_id=="55382" && $rm_upgrade_notice!=0) { 
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-warning is-dismissible rm-py-2 rm-my-2">

                <p><?php printf(__( 'Upgrade to RegistrationMagic Premium+ by paying the difference! <a href="%s">Click here </a>to upgrade.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/renew-registrationmagic-premium-license-key/'); ?></p>
                    <a type="button" class="notice-dismiss rm-text-decoration-none" href="<?php echo esc_url($query_string).'rm_disable_upgrade_notice=1' ?>"><span class="screen-reader-text">Dismiss this notice.</span></a>
            </div>

        <?php
            
        }
        
        if(defined('REGMAGIC_ADDON') && $this->get_license_status()=='inactive' && isset($_GET['page']) && $_GET['page'] != 'rm_licensing') { 
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-error inline rm-py-2 rm-my-2">

                <p><?php printf(__( 'RegistrationMagic Premium license key is missing. <a href="%s" class="button action">Activate License </a>','custom-registration-form-builder-with-submission-manager'),esc_url( admin_url('admin.php?page=rm_licensing') )); ?> </p>

            </div>

        <?php
            
        }
        
        if(defined('REGMAGIC_ADDON') && $this->get_license_status()=='inactive' && isset($_GET['page']) && $_GET['page'] == 'rm_licensing') { 
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-error inline rm-py-2 rm-my-2">

                <p><?php printf(__( 'Unlicensed version of RegistrationMagic Premium. Auto-updates are disabled. Please <a href="%s" target= "_blank" class="action">visit this page</a> to get your license key.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/get-registrationmagic-premium-license-key/'); ?> </p>

            </div>

        <?php
            
        }
       
        if(defined('REGMAGIC_ADDON') && $this->get_license_status()!='inactive' && $this->get_license_status()!='valid' && isset($_GET['page']) && RM_Utilities::is_valid_rm_page($_GET['page']) && $_GET['page'] !== 'rm_licensing') { 
            $license_key = get_option('rm_premium_license_key');
            $item_id =  get_option('rm_premium_license_id','');
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-error inline rm-py-2 rm-my-2">

                <p><?php printf(__( 'Your RegistrationMagic Premium license key has expired. Renew now or upgrade to lifetime license at discount price! <a href="%s" target= "_blank">Click here</a> to proceed.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key='.$license_key.'&download_id='.$item_id); ?> </p>

            </div>

        <?php
            
        }
        
        if(defined('REGMAGIC_ADDON') && $this->get_license_status()!='inactive' && $this->get_license_status()!='valid' && isset($_GET['page']) && RM_Utilities::is_valid_rm_page($_GET['page']) && $_GET['page'] == 'rm_licensing') { 
            $license_key = get_option('rm_premium_license_key');
            $item_id =  get_option('rm_premium_license_id','');
            ?>

            <div class="rm_admin_notice_banner rm-notice-banner notice notice-error inline rm-py-2 rm-my-2">

                <p><?php printf(__( 'Your RegistrationMagic Premium license key has expired. Auto-updates are disabled. Renew now or upgrade to lifetime license at discounted price! <a href="%s" target="_blank">Click here</a> to proceed.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/renew-registrationmagic-premium-license-key/?key='.$license_key.'&download_id='.$item_id); ?> </p>

            </div>

        <?php
            
        }

        
    }

    public function admin_notices(){

        if(defined('REGMAGIC_ADDON')) {

            $addon_admin = new RM_Admin_Addon();

            return $addon_admin->admin_notices();

        }

        /* Showing noticed for WooCommerce and EDD integration */

        $g_opts= new RM_Options();

        if(!empty($_GET['rm_disable_edd_notice'])){

            $g_opts->set_value_of('edd_notice', 0);

        }

        if(!empty($_GET['rm_disable_wc_notice'])){

            $g_opts->set_value_of('wc_notice', 0);

        }

        if(!empty($_GET['rm_disable_php_notice'])){

            $g_opts->set_value_of('php_notice', 0);

        }

        if(!empty($_GET['rm_disable_php_8_notice'])){

            $g_opts->set_value_of('php_8_notice', 0);

        }

        if(!empty($_GET['rm_disable_ep_notice'])){

            $g_opts->set_value_of('ep_notice', 0);

        }

        $edd_notice= $g_opts->get_value_of('edd_notice');

        $wc_notice= $g_opts->get_value_of('wc_notice');

        $php_notice= $g_opts->get_value_of('php_notice');

        $php_8_notice= $g_opts->get_value_of('php_8_notice');

        $query_string= $_SERVER['QUERY_STRING'];

        if(empty($query_string)){

            $query_string= '?';

        }

        else

        {

            $query_string= '?'.$query_string.'&';

        }



        ?>

        <?php if($php_notice!=0): ?>

            <?php if(version_compare(PHP_VERSION, '5.6.0', '<')): ?>

            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">

                <p><?php printf(__( 'It seems you are using now obsolete version of PHP. Please note that RegistrationMagic works best with PHP 5.6 or later versions. You may want to upgrade to avoid any potential issues. This is one time warning check and message may not display again once dismissed.','custom-registration-form-builder-with-submission-manager')); ?> <a class="rm_dismiss" href="<?php echo esc_url($query_string).'rm_disable_php_notice=1' ?>"><img src="<?php echo esc_url(RM_IMG_URL. '/close-rm.png'); ?>"></a></p>

            </div>

            <?php endif; ?>

        <?php endif; ?>

        <?php /* if($php_8_notice != 0 && isset($_GET['page']) && $_GET['page'] == 'rm_form_manage'):

            if(version_compare(PHP_VERSION, '8.0.0', '>=')): ?>

            <div id="rm-php-notice-warning" class="rm_admin_notice rm-notice-banner notice notice-warning is-dismissible">

                <p><?php _e( 'You are using PHP 8. RegistrationMagic currently does not supports PHP 8 and you might see some unwanted errors or warnings. We are working on PHP 8 compatibility update and it will be available very soon.','custom-registration-form-builder-with-submission-manager'); ?> <a class="rm_dismiss" href="<?php echo esc_url($query_string).'rm_disable_php_8_notice=1' ?>"><?php _e('Dismiss','custom-registration-form-builder-with-submission-manager'); ?></a></p>

            </div>

            <?php endif;

        endif; */ ?>

        <?php if($edd_notice!=0 &&  class_exists( 'Easy_Digital_Downloads')): ?>

            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">

                <p><?php printf(__( 'Using Easy Digital Downloads? <a target="__blank" href="%s">Learn how to</a> create intelligent support forms for your products using RegistrationMagic which display customer order history and details with the form submission.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/create-super-intelligent-forms-wordpress/'); ?><a class="rm_dismiss" href="<?php echo esc_url($query_string).'rm_disable_edd_notice=1' ?>"><?php _e('Dismiss','custom-registration-form-builder-with-submission-manager'); ?></a></p>

            </div>

        <?php endif; ?>



        <?php if($wc_notice!=0 && class_exists( 'WooCommerce' )): ?>

            <div class="rm_admin_notice rm-notice-banner notice notice-success is-dismissible">

                <p><?php printf(__( 'Using WooCommerce? <a target="__blank" href="%s">Learn how to</a> create intelligent contact forms for your products using RegistrationMagic which display customer order history with the form submission.','custom-registration-form-builder-with-submission-manager'),'https://registrationmagic.com/create-super-intelligent-forms-wordpress/'); ?> <a class="rm_dismiss" href="<?php echo esc_url($query_string).'rm_disable_wc_notice=1' ?>"><?php _e('Dismiss','custom-registration-form-builder-with-submission-manager'); ?></a></p>

            </div>

        <?php endif;

        if (function_exists('is_multisite') && is_multisite()) {
            $nl_subscribed = get_site_option('rm_option_newsletter_subbed', false);
        } else {
            $nl_subscribed = get_site_option('rm_option_newsletter_subbed', false);
        }

        if(!$nl_subscribed) {
            $newsletter_sub_link = RM_UI_Strings::get('NEWSLETTER_SUB_MSG');
        } else {
            $newsletter_sub_link = null;
        }

        global $rm_env_requirements;
        if (($rm_env_requirements & RM_REQ_EXT_CURL) && $newsletter_sub_link && isset($_GET['page']) && $_GET['page'] == 'rm_form_manage') { ?>
            <div class="rm-newsletter-banner rm-newsletter-notice" id="rm_newsletter_sub"><?php echo wp_kses_post((string)$newsletter_sub_link);?><img src="<?php echo esc_url(RM_IMG_URL . 'close-rm.png'); ?>" onclick="jQuery('#rm_newsletter_sub').hide()"></div>
        <?php }

    }

    public function rm_tabs_setting_page($html)

    {

        $opt = '';

        ob_start();

        ?>

        <a href="admin.php?page=rm_options_tabs">

            <div class="rm-settings-box">

                <img class="rm-settings-icon" src="<?php echo RM_IMG_URL;?>rm-tab-reorder-icon.png">

                <div class="rm-settings-description"></div>

                <div class="rm-settings-subtitle"><?php _e('User Area Layout','custom-registration-form-builder-with-submission-manager');?></div>

                <span><?php _e('Set user area tabs order and visibility.','custom-registration-form-builder-with-submission-manager');?></span>

            </div>

        </a>

        <a href="admin.php?page=rm_options_manage_ctabs">

            <div class="rm-settings-box">

                <img class="rm-settings-icon" src="<?php echo RM_IMG_URL;?>rm-custom-profile-tab.png">

                <div class="rm-settings-description"></div>

                <div class="rm-settings-subtitle"><?php _e('Customize User Area Tab','custom-registration-form-builder-with-submission-manager');?></div>

                <span><?php _e('Add customized tabs to the user area','custom-registration-form-builder-with-submission-manager');?></span>

            </div>

        </a>

        <a href="admin.php?page=rm_options_manage_invoice">

            <div class="rm-settings-box">

                <img class="rm-settings-icon" src="<?php echo RM_IMG_URL;?>rm-invoice-icon.png">

                <div class="rm-settings-description"></div>

                <div class="rm-settings-subtitle"><?php _e('Invoice Setting','custom-registration-form-builder-with-submission-manager');?></div>

                <span><?php _e('Setup Invoice options','custom-registration-form-builder-with-submission-manager');?></span>

            </div>

        </a>

        <?php

        $opt = ob_get_clean();

        return $html.$opt;

    }

    

    public function update_welcome_modal_option(){

        if(check_ajax_referer('rm_ajax_secure','rm_sec_nonce') && current_user_can("manage_options")) {

            update_site_option('rm_hide_welcome_modal', 1);

        }

    }

    

    public function rm_profile_tabs_add($tabs){

        $tabs['rm_my_details_tab'] = array('label'=>RM_UI_Strings::get('LABEL_MY_DETAILS'),'icon'=>'account_box','id'=>'rm_my_details_tab','class'=>'rmtab-my-details', 'status'=> 1);

        $tabs['rm_my_sub_tab'] = array('label'=>RM_UI_Strings::get('LABEL_MY_SUBS'),'icon'=>'assignment_turned_in','id'=>'rm_my_sub_tab','class'=>'rmtab-registration','status'=> 1);

        $tabs['rm_my_pay_tab'] = array('label'=>RM_UI_Strings::get('LABEL_PAY_HISTORY'),'icon'=>'credit_card','id'=>'rm_my_pay_tab','class'=>'rmtab-payment-details','status'=> 1);

        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','<')) {

            $tabs['rm_inbox_tab'] = array('label'=>'Inbox','icon'=>'mail','id'=>'rm_inbox_tab','class'=>'rmtab-inbox','status'=> 1);

        }

        return $tabs;

    }



    public function rm_profile_tabs_content_add($data, $uid){

        $this->rm_profile_tabs_content_account($data);

        $this->rm_profile_tabs_content_registration($data);

        $this->rm_profile_tabs_content_payment($data);

        if(defined('REGMAGIC_ADDON') && version_compare(RM_ADDON_PLUGIN_VERSION,'5.1.2.0','<')) {

            $this->rm_profile_tabs_content_add_inbox($data, $uid);

        }

    }

    

    public function rm_profile_tabs_content_account($data){

        include RM_PUBLIC_DIR.'views/template_rm_front_accounts.php';

    }



    public function rm_profile_tabs_content_registration($data){

        include RM_PUBLIC_DIR.'views/template_rm_front_registration.php';

    }



    public function rm_profile_tabs_content_payment($data){

        include RM_PUBLIC_DIR.'views/template_rm_front_payments.php';

    }

    

    public function rm_profile_tabs_content_add_inbox($data, $uid){

        if(defined('REGMAGIC_ADDON')){

            include RM_ADDON_PUBLIC_DIR.'views/template_rm_front_inbox.php';

        }

    }

    

    public function rm_reports_email_setup($notification_id){

        $reports_service = new RM_Reports_Service;

        $reports_service->rm_reports_email_setup($notification_id);

        $reports_service->reports_email_schedule_callback();

    }

    public function rm_licensing() {
        wp_enqueue_script( 'rm-license', RM_BASE_URL . 'admin/js/rm-license.js', array('jquery'), $this->version, true );
        wp_localize_script(
            'rm-license',
            'rm_admin_license_settings',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'rm-license-nonce' ),
                )
            );
        wp_enqueue_style('rm-license', RM_BASE_URL . 'admin/css/rm-license.css', array(), $this->version, 'all');
        include RM_ADMIN_DIR.'views/template_rm_license.php';
    }
    
    public function activate_license()
    {
        $retrieved_nonce = filter_input( INPUT_POST, 'nonce' );
        if ( !wp_verify_nonce( $retrieved_nonce, 'rm-license-nonce' ) ) {
                die( esc_html__( 'Failed security check', 'custom-registration-form-builder-with-submission-manager' ) );
        }
        
        $rm_license_activate = sanitize_text_field(filter_input( INPUT_POST, 'rm_license_activate' ));
        $license_key = sanitize_text_field(filter_input( INPUT_POST, 'rm_license' ));
        $item_id = sanitize_text_field(filter_input( INPUT_POST, 'rm_item_id' ));
        $item_key = sanitize_text_field(filter_input( INPUT_POST, 'rm_item_key' ));
        update_option( $item_key.'_license_key', $license_key );
        update_option( $item_key.'_license_id', $item_id );
        $response = array();
        if( isset( $rm_license_activate ) && ! empty( $rm_license_activate ) ){
            $license = new RM_Licensing();
            $response = $license->rm_activate_license($license_key,$item_id,$item_key);
            wp_send_json_success( $response );
        }
        else
        {
            wp_send_json_error( array( 'message' => esc_html__( 'Security check failed. Please refresh the page and try again later.', 'custom-registration-form-builder-with-submission-manager' ) ) );
        }
        
}

public function deactivate_license(){
    
    $retrieved_nonce = filter_input( INPUT_POST, 'nonce' );
        if ( !wp_verify_nonce( $retrieved_nonce, 'rm-license-nonce' ) ) {
                die( esc_html__( 'Failed security check', 'custom-registration-form-builder-with-submission-manager' ) );
        }
        $item_id = get_option('rm_premium_item_id','');
        $license_key = get_option('rm_premium_license_key','');
        $item_key = 'rm_premium';
        $response = array();
        if( isset( $license_key ) && ! empty( $license_key ) ){
            $license = new RM_Licensing();
            $response = $license->rm_deactivate_license($license_key,$item_id,$item_key);
            
        }
        delete_option('rm_premium_item_id');
        delete_option('rm_premium_license_key');
        delete_option('rm_premium_license_id');
        delete_option('rm_premium_license_status');
        delete_option('rm_premium_license_response');           
        $response = array('message'=>'success');
        wp_send_json_success( $response );
    
}
    
public function get_license_status()
{
    $status = 'inactive';
    $license_key = get_option('rm_premium_license_key');
    $license_status = get_option('rm_premium_license_status','');
    if(!empty($license_key) && !empty($license_status))
    {
        $status = $license_status;
    }
    return $status;
}

public function rm_check_licenses()
{
    $license_data = maybe_unserialize(get_option('rm_premium_license_response',''));

    if(!empty($license_data) && isset($license_data->expires) && $license_data->expires!== 'lifetime' )
    {
        $givenDate = $license_data->expires;
        // Convert the given date to a timestamp
        $givenTimestamp = strtotime($givenDate);
        // Get the current timestamp
        $currentTimestamp = time();
        // Compare the timestamps
        if ($givenTimestamp < $currentTimestamp) {
            $last_check_time = get_transient('rm_license_last_check_time');
            // If last check time doesn't exist or it's more than 24 hours ago
            if (!$last_check_time || (time() - $last_check_time) > 24 * 60 * 60) {
                    $this->rm_check_license_status();
                    set_transient('rm_license_last_check_time', time());
            }
        }
    }

}

public function rm_check_license_status() 
{
    
    $store_url = 'https://registrationmagic.com';
$item_id =  get_option('rm_premium_license_id','');
$license = get_option('rm_premium_license_key','');
$api_params = array(
    'edd_action' => 'check_license',
    'license' => $license,
    'item_id' => $item_id,
    'url' => home_url(),
            'environment' => function_exists( 'wp_get_environment_type' ) ? wp_get_environment_type() : 'production',

);
$response = wp_remote_post( $store_url, array( 'body' => $api_params, 'timeout' => 15, 'sslverify' => false ) );
  if ( is_wp_error( $response ) ) {
    return false;
  }

$license_data = json_decode( wp_remote_retrieve_body( $response ) );

if( isset( $license_data->license ) && ! empty( $license_data->license ) &&  $license_data->license == 'valid' ) {
    
    // this license is still valid
} else {
    $license_status  = ( isset( $license_data->license ) && ! empty( $license_data->license )) ? $license_data->license : '';
            $license_response  = ( isset( $license_data ) && ! empty( $license_data ) ) ? $license_data : '';
            update_option( 'rm_premium_license_status', $license_status );
            update_option( 'rm_premium_license_response', $license_response );
    // this license is no longer valid
}

}
    
}

