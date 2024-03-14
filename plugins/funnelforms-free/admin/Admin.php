<?php

defined('ABSPATH') or die('NO!');

class Admin {

    private $menu_object_dashboard = null;
    private $menu_object_fragen = null;
    private $menu_object_leads = null;
    private $menu_object_leads_details = null;
    private $menu_object_fragenbuilder = null;
    private $menu_object_kontaktformulare = null;
    private $menu_object_kontaktformularbuilder = null;
    private $menu_object_kontaktformularbuilder_settings = null;
    private $menu_object_formulare = null;
    private $menu_object_formularbuilder = null;
    private $menu_object_formularbuilder_settings = null;
    private $menu_object_formularbuilder_preview = null;
    private $menu_object_termine = null;
    private $menu_object_terminevents = null;
    private $menu_object_calculations = null;
    private $menu_object_import = null;
    private $menu_object_demoimport = null;
    private $menu_object_integrationen = null;
    private $menu_object_checklist = null;
    private $menu_object_openai = null;

    private $AdminHelper = null;
    private $af2_menu_ajax = null;

    public function __construct() {
        require_once FNSF_AF2_ADMIN_HELPER_PATH;
        $this->AdminHelper = new Fnsf_Af2AdminHelper();

        add_action('init', array($this, 'fnsf_af2_register_menu_files'));

        add_action('init', array($this, 'fnsf_af2_add_custom_post_types'));

        add_action('admin_init', array($this,'fnsf_af2_external_redirect'));
        add_action('admin_menu', array($this,'fnsf_af2_add_menus'));

        add_action('admin_head', array($this, 'fnsf_af2_external_links_target'));

        add_action('admin_head', array($this,'fnsf_menu_highlight'));
        
        add_filter('admin_body_class', array($this, 'fnsf_af2_fold_admin_menu'));
    }

    public function fnsf_af2_register_menu_files() {

        require_once FNSF_AF2_MENU_DASHBOARD_PATH;
        $this->menu_object_dashboard = new Fnsf_Af2Dashboard($this->AdminHelper);

        require_once FNSF_AF2_MENU_LEADS_PATH;
        $this->menu_object_leads = new Fnsf_Af2Leads($this->AdminHelper);
        require_once FNSF_AF2_MENU_LEADS_DETAILS_PATH;
        $this->menu_object_leads_details = new Fnsf_Af2LeadsDetails($this->AdminHelper);

        require_once FNSF_AF2_MENU_FRAGEN_PATH;
        $this->menu_object_fragen = new Fnsf_Af2Fragen($this->AdminHelper);
        require_once FNSF_AF2_MENU_FRAGENBUILDER_PATH;
        $this->menu_object_fragenbuilder = new Fnsf_Af2Fragenbuilder($this->AdminHelper);

        require_once FNSF_AF2_MENU_KONTAKTFORMULARE_PATH;
        $this->menu_object_kontaktformulare = new Fnsf_Af2Kontaktformulare($this->AdminHelper);
        require_once FNSF_AF2_MENU_KONTAKTFORMULARBUILDER_PATH;
        $this->menu_object_kontaktformularbuilder = new Fnsf_Af2Kontaktformularbuilder($this->AdminHelper);
        require_once FNSF_AF2_MENU_KONTAKTFORMULARBUILDER_SETTINGS_PATH;
        $this->menu_object_kontaktformularbuilder_settings = new Fnsf_Af2KontaktformularbuilderSettings($this->AdminHelper);

        require_once FNSF_AF2_MENU_FORMULARE_PATH;
        $this->menu_object_formulare = new Fnsf_Af2Formulare($this->AdminHelper);
        require_once FNSF_AF2_MENU_FORMULARBUILDER_PATH;
        $this->menu_object_formularbuilder = new Fnsf_Af2Formularbuilder($this->AdminHelper);
        require_once FNSF_AF2_MENU_FORMULARBUILDER_SETTINGS_PATH;
        $this->menu_object_formularbuilder_settings = new Fnsf_Af2FormularbuilderSettings($this->AdminHelper);
        require_once FNSF_AF2_MENU_FORMULARBUILDER_PREVIEW_PATH;
        $this->menu_object_formularbuilder_preview = new Fnsf_Af2FormularbuilderPreview($this->AdminHelper);
        
        require_once FNSF_AF2_MENU_TERMINE_PATH;
        $this->menu_object_termine = new Fnsf_Af2Termine($this->AdminHelper);
        
        require_once FNSF_AF2_MENU_TERMINEVENTS_PATH;
        $this->menu_object_terminevents = new Fnsf_Af2Terminevent($this->AdminHelper);

        require_once FNSF_AF2_MENU_CALCULATIONS_PATH;
        $this->menu_object_calculations = new Fnsf_Af2Kalkulation($this->AdminHelper);

        require_once FNSF_AF2_MENU_IMPORT_PATH;
        $this->menu_object_import = new Fnsf_Af2ImportExport($this->AdminHelper);

        require_once FNSF_AF2_MENU_DEMOIMPORT_PATH;
        $this->menu_object_demoimport = new Fnsf_Af2DemoImport($this->AdminHelper);

        require_once FNSF_AF2_MENU_INTEGRATIONEN_PATH;
        $this->menu_object_integrationen = new Fnsf_Af2Integrationen($this->AdminHelper);

        require_once FNSF_AF2_MENU_CHECKLIST_PATH;
        $this->menu_object_checklist = new Fnsf_Af2Checklist($this->AdminHelper);


        require_once FNSF_AF2_MENU_AJAX_PATH;
        $this->af2_menu_ajax = new Fnsf_Af2MenuAjax($this->AdminHelper);

        
        require_once FNSF_AF2_MENU_OPENAI_PATH;
        $this->menu_object_openai = new FNSF_Af2OpenAI($this->AdminHelper);
    }

    public function fnsf_af2_add_custom_post_types() {

        // Fragen
        $cpt_labels = array('name' => __('Questions', 'Post type general name', 'textdomain'),
        'singular_name' => __('Question', 'Post type singular name', 'textdomain'));

        register_post_type(FNSF_FRAGE_POST_TYPE, array(
            'labels' => $cpt_labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => FNSF_FRAGE_SLUG,
            'has_archive' => false,
            'publicly_queryable' => false,
            'query_var' => false,
        ));


        // Kontaktformulare
        $cpt_labels = array('name' => __('Contact forms', 'Post type general name', 'textdomain'),
        'singular_name' => __('Contact form', 'Post type singular name', 'textdomain'));

        register_post_type(FNSF_KONTAKTFNSF_FORMULAR_POST_TYPE, array(
            'labels' => $cpt_labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => FNSF_KONTAKTFNSF_FORMULAR_SLUG,
            'has_archive' => false,
            'publicly_queryable' => false,
            'query_var' => false,
        ));

        // Formulare
        $cpt_labels = array('name' => __('Forms', 'Post type general name', 'textdomain'),
        'singular_name' => __('Form', 'Post type singular name', 'textdomain'));

        register_post_type(FNSF_FORMULAR_POST_TYPE, array(
            'labels' => $cpt_labels,
            'public' => false,
            'show_ui' => true,
            'show_in_menu' => FNSF_FORMULAR_SLUG,
            'has_archive' => false,
            'publicly_queryable' => false,
            'query_var' => false,
        ));


        // Requests
        $cpt_labels = array('name' => __('Requests', 'Post type general name', 'textdomain'),
        'singular_name' => __('Requests', 'Post type singular name', 'textdomain'));

        register_post_type(FNSF_REQUEST_POST_TYPE, array(
            'labels' => $cpt_labels,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'query_var' => false,
        ));
        register_post_type(FNSF_REQUEST_POST_TYPE_, array(
            'labels' => $cpt_labels,
            'public' => false,
            'show_ui' => false,
            'show_in_menu' => false,
            'has_archive' => false,
            'publicly_queryable' => false,
            'query_var' => false,
        ));

    }

    public function fnsf_af2_add_menus() {
        
        // Adding the Main Admin Menu Page
        add_menu_page(__('Funnelforms', 'funnelforms-free'), __('Funnelforms', 'funnelforms-free'), 'manage_options', FNSF_MAIN_MENU_SLUG, '', FNSF_AF2_MENU_ICON_URL, 26);

            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Dashboard', 'funnelforms-free'), __('Dashboard', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_MAIN_MENU_SLUG, array($this->menu_object_dashboard, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('First steps', 'funnelforms-free'), __('First steps', 'funnelforms-free'), 'manage_options', FNSF_CHECKLIST_SLUG, array($this->menu_object_checklist, 'fnsf_get_content') );
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Leads', 'funnelforms-free'), __('Leads', 'funnelforms-free'), 'manage_options', FNSF_LEADS_SLUG, array($this->menu_object_leads, 'fnsf_get_content'));
            add_submenu_page(FNSF_LEADS_SLUG, __('Lead Details', 'funnelforms-free'), __('Lead Details', 'funnelforms-free'), 'manage_options', FNSF_LEADS_DETAILS_SLUG, array($this->menu_object_leads_details, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Questions', 'funnelforms-free'), __('Questions', 'funnelforms-free'), 'manage_options', FNSF_FRAGE_SLUG, array($this->menu_object_fragen, 'fnsf_get_content'));
            add_submenu_page(FNSF_FRAGE_SLUG, __('Question editor', 'funnelforms-free'), __('Question editor', 'funnelforms-free'), 'manage_options', FNSF_FRAGENBUILDER_SLUG, array($this->menu_object_fragenbuilder, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Contact forms', 'funnelforms-free'), __('Contact forms', 'funnelforms-free'), 'manage_options', FNSF_KONTAKTFNSF_FORMULAR_SLUG, array($this->menu_object_kontaktformulare, 'fnsf_get_content'));
            add_submenu_page(FNSF_KONTAKTFNSF_FORMULAR_SLUG, __('Contact form editor', 'funnelforms-free'), __('Contact form editor', 'funnelforms-free'), 'manage_options', FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG, array($this->menu_object_kontaktformularbuilder, 'fnsf_get_content'));
            add_submenu_page(FNSF_KONTAKTFNSF_FORMULAR_SLUG, __('Contact form editor', 'funnelforms-free'), __('Contact form editor', 'funnelforms-free'), 'manage_options', FNSF_KONTAKTFNSF_FORMULARBUILDER_SETTINGS_SLUG, array($this->menu_object_kontaktformularbuilder_settings, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Forms', 'funnelforms-free'), __('Forms', 'funnelforms-free'), 'manage_options', FNSF_FORMULAR_SLUG, array($this->menu_object_formulare, 'fnsf_get_content'));
            add_submenu_page(FNSF_FORMULAR_SLUG, __('Form editor', 'funnelforms-free'), __('Form editor', 'funnelforms-free'), 'manage_options', FNSF_FORMULARBUILDER_SLUG, array($this->menu_object_formularbuilder, 'fnsf_get_content'));
            add_submenu_page(FNSF_FORMULAR_SLUG, __('Form editor', 'funnelforms-free'), __('Form editor', 'funnelforms-free'), 'manage_options', FNSF_FORMULARBUILDER_SETTINGS_SLUG, array($this->menu_object_formularbuilder_settings, 'fnsf_get_content'));
            add_submenu_page(FNSF_FORMULAR_SLUG, __('Form preview', 'funnelforms-free'), __('Form preview', 'funnelforms-free'), 'manage_options', FNSF_FORMULARBUILDER_PREVIEW_SLUG, array($this->menu_object_formularbuilder_preview, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Appointments', 'funnelforms-free'), __('Appointments', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_TERMIN_SLUG, array($this->menu_object_termine, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Appointment events', 'funnelforms-free'), __('Appointment events', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_TERMINEVENT_SLUG, array($this->menu_object_terminevents, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Calculations', 'funnelforms-free'), __('Calculations', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_CALCULATIONS_SLUG, array($this->menu_object_calculations, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Import & Export', 'funnelforms-free'), __('Import & Export', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_IMPORT_SLUG, array($this->menu_object_import, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Funnelforms AI', 'funnelforms-free'), __('Funnelforms AI', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_OPENAI_SLUG, array($this->menu_object_openai, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Demo import', 'funnelforms-free'), __('Demo import', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_DEMOFNSF_IMPORT_SLUG, array($this->menu_object_demoimport, 'fnsf_get_content'));
            add_submenu_page(FNSF_MAIN_MENU_SLUG, __('Integrations', 'funnelforms-free'), __('Integrations', 'funnelforms-free').'<i class="fas fa-lock af2_locked_menu"></i>', 'manage_options', FNSF_INTEGRATIONEN_SLUG, array($this->menu_object_integrationen, 'fnsf_get_content'));
        

        // External submenu pages
        add_submenu_page(FNSF_MAIN_MENU_SLUG, 'Partnerprogramm', __('Affiliate program', 'funnelforms-free'), 'manage_options', FNSF_PARTNERPROGRAMM_SLUG, array($this, 'fnsf_af2_external_redirect'));
        add_submenu_page(FNSF_MAIN_MENU_SLUG, 'Help Center', __('Help center', 'funnelforms-free'), 'manage_options', FNSF_HELPCENTER_SLUG, array($this, 'fnsf_af2_external_redirect'));
        add_submenu_page(FNSF_MAIN_MENU_SLUG, 'Fehler melden', __('Report error', 'funnelforms-free'), 'manage_options', FNSF_SUPPORT_SLUG, array($this, 'fnsf_af2_external_redirect'));
        add_submenu_page(FNSF_MAIN_MENU_SLUG,  __('Choose Pro', 'funnelforms-free'), '<span style="color: red;">'.__('Choose Pro', 'funnelforms-free').'</span>', 'manage_options', FNSF_PRO_SLUG, array($this, 'fnsf_af2_external_redirect'));
    }

    public function fnsf_af2_external_redirect() {
        $locale = get_locale();

        $partner = null;
        $help = null;
        $support = null;
        $pro = null;

        switch($locale) {
            case 'de_DE': {
                $partner = 'https://funnelforms.io/partnerprogramm/';
                $help = 'https://help.funnelforms.io/';
                $support = 'https://funnelforms.io/support/';

                $pro = 'https://www.funnelforms.io/preise';

                break;
            }
            default: {
                $partner = 'https://en.funnelforms.io/affiliate-program';
                $help = 'https://help.funnelforms.io/en';
                $support = 'https://en.funnelforms.io/create-support-ticket';
                $pro = 'https://en.funnelforms.io/pricing';
                break;
            }
        }

        if (empty($_GET['page'])) {
            return;
        }

        if (FNSF_PARTNERPROGRAMM_SLUG === sanitize_key($_GET['page'])) {
            wp_safe_redirect($partner);
            exit;
        }

        if (FNSF_HELPCENTER_SLUG === sanitize_key($_GET['page'])) {
            wp_safe_redirect($help);
            exit;
        }
        
        if (FNSF_SUPPORT_SLUG === sanitize_key($_GET['page'])) {
            wp_safe_redirect($support);
            exit;
        }

        if (FNSF_PRO_SLUG === sanitize_key($_GET['page'])) {
            wp_safe_redirect($pro);
            exit;
        }
    }

    public function fnsf_af2_external_links_target(){
       ; ?>
            <script>
                jQuery(function(){
                    jQuery("a[href='admin.php?page=af2_partnerprogramm']").attr("target","__blank");
                    jQuery("a[href='admin.php?page=af2_helpcenter']").attr("target","__blank");
                    jQuery("a[href='admin.php?page=af2_support']").attr("target","__blank");
                    jQuery("a[href='admin.php?page=af2_pro']").attr("target","__blank");
                })
            </script>
        <?php
    }

    public function fnsf_menu_highlight(){

        global $parent_file, $submenu_file,  $plugin_page, $typenow;

        switch ($plugin_page){

            case 'af2_lead_details':
                $parent_file = 'af2_dashboard';
                $submenu_file = 'af2_leads';
                $plugin_page = 'af2_leads';
                break;
            case FNSF_FRAGENBUILDER_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_FRAGE_SLUG;
                $plugin_page = FNSF_FRAGE_SLUG;
                break;
            case FNSF_LEADS_DETAILS_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_LEADS_SLUG;
                $plugin_page = FNSF_LEADS_SLUG;
                break;
            case FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_KONTAKTFNSF_FORMULAR_SLUG;
                $plugin_page = FNSF_KONTAKTFNSF_FORMULAR_SLUG;
                break;
            case FNSF_KONTAKTFNSF_FORMULARBUILDER_SETTINGS_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_KONTAKTFNSF_FORMULAR_SLUG;
                $plugin_page = FNSF_KONTAKTFNSF_FORMULAR_SLUG;
                break;
            case FNSF_FORMULARBUILDER_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_FORMULAR_SLUG;
                $plugin_page = FNSF_FORMULAR_SLUG;
                break;
            case FNSF_FORMULARBUILDER_SETTINGS_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_FORMULAR_SLUG;
                $plugin_page = FNSF_FORMULAR_SLUG;
                break;
            case FNSF_FORMULARBUILDER_PREVIEW_SLUG:
                $parent_file = FNSF_MAIN_MENU_SLUG;
                $submenu_file = FNSF_FORMULAR_SLUG;
                $plugin_page = FNSF_FORMULAR_SLUG;
                break;

        }
    }

    public function fnsf_af2_fold_admin_menu($classes){
        $current_screen = get_current_screen();
        if( $current_screen->base == 'admin_page_' . FNSF_FRAGENBUILDER_SLUG
            || $current_screen->base == 'admin_page_' . FNSF_KONTAKTFNSF_FORMULARBUILDER_SLUG
            || $current_screen->base == 'admin_page_' . FNSF_KONTAKTFNSF_FORMULARBUILDER_SETTINGS_SLUG
            || $current_screen->base == 'admin_page_' . FNSF_FORMULARBUILDER_SLUG
            || $current_screen->base == 'admin_page_' . FNSF_FORMULARBUILDER_SETTINGS_SLUG
            || $current_screen->base == 'admin_page_' . FNSF_FORMULARBUILDER_PREVIEW_SLUG
        ){
            $classes .= ' folded ';
        }
        return $classes;
    }
}

new Admin();
