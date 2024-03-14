<?php 

class Fnsf_Af2MenuAjax {

    private $testmail_ajax = null;
    private $category_ajax = null;
    private $Fnsf_Af2AjaxFormularbuilderFonts = null;

    private $Admin = null;

    function __construct($Admin) {

        $this->Admin = $Admin;

        require_once FNSF_AF2_MENU_AJAX_TESTMAIL;
        $this->testmail_ajax = new Af2AjaxTestmail();

        require_once FNSF_AF2_MENU_AJAX_CATEGORY;
        $this->category_ajax = new Fnsf_Af2AjaxCategory();

        require_once FNSF_AF2_MENU_AJAX_FORMULARBUILDER_FONTS;
        $this->Fnsf_Af2AjaxFormularbuilderFonts = new Fnsf_Af2AjaxFormularbuilderFonts();

        $this->add_actions();
    }

    function add_actions() {
        add_action( 'wp_ajax_fnsf_af2_trigger_dark_mode', array($this, 'fnsf_af2_trigger_dark_mode') );
        add_action( 'wp_ajax_fnsf_af2_save_post', array($this->Admin, 'fnsf_af2_save_post') );
        add_action( 'wp_ajax_af2_fnsf_copy_posts', array($this->Admin, 'fnsf_copy_posts') );
        add_action( 'wp_ajax_af2_fnsf_delete_posts', array($this->Admin, 'fnsf_delete_posts') );
        add_action( 'wp_ajax_fnsf_af2_test_mail', array($this->testmail_ajax, 'fnsf_af2_test_mail') );
        add_action( 'wp_ajax_af2_fnsf_add_category', array( $this->category_ajax, 'fnsf_add_category' ) );
        add_action( 'wp_ajax_af2_fnsf_delete_category', array( $this->category_ajax, 'fnsf_delete_category' ) );
        add_action( 'wp_ajax_af2_fnsf_set_element_category', array( $this->category_ajax, 'fnsf_update_category' ) );
        add_action( 'wp_ajax_af2_fnsf_delete_af2_font', array($this->Fnsf_Af2AjaxFormularbuilderFonts, 'af2_delete_font') );
        add_action( 'wp_ajax_af2_fnsf_add_af2_font', array($this->Fnsf_Af2AjaxFormularbuilderFonts, 'af2_add_font') );
    }

    // Functions
    function fnsf_af2_trigger_dark_mode() {

        if ( !current_user_can( 'edit_others_posts' ) ) {
            die( 'Permission denied' );
        }

        if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( $_POST['nonce'], 'af2_FE_nonce' ) ) {
            die( 'Permission denied' );
        }

        $state = sanitize_text_field($_POST['state']);
        if($state == 'true'){
            $state_number = 1;
        }else{
            $state_number = 0;
        }
        update_option('af2_dark_mode', $state_number);
        die();
    }
}
    

; ?>
