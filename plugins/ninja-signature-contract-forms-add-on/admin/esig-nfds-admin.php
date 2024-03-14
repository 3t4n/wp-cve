<?php

/**
 *
 * @package ESIG_NFDS_Admin
 * @author  Abu Shoaib <abushoaib73@gmail.com>
 */
if (!class_exists('ESIG_NFDS_Admin')) :

    class ESIG_NFDS_Admin extends ESIG_NF_SETTING {

        /**
         * Instance of this class.
         * @since    1.0.1
         * @var      object
         */
        protected static $instance = null;
        public $name;
        private $plugin_slug, $current_tab, $document_view;

        /**
         * Slug of the plugin screen.
         * @since    1.0.1
         * @var      string
         */
        protected $plugin_screen_hook_suffix = null;

        /**
         * Initialize the plugin by loading admin scripts & styles and adding a
         * settings page and menu.
         * @since     0.1
         */
        public function __construct() {

            /*
             * Call $plugin_slug from public plugin class.
             */
            $plugin = ESIG_NFDS::get_instance();
            $this->plugin_slug = $plugin->get_plugin_slug();

            $this->name = __('Esignature', 'esig-nfds');
            $this->current_tab = empty($tab) ? 1 : $tab;
            $this->document_view = new esig_ninjaform_document_view();
            // Add an action link pointing to the options page.

            add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));

            add_filter('nf_notification_types', array($this, 'register_action_type'));
            add_filter('esig_sif_buttons_filter', array($this, 'add_sif_ninja_buttons'), 11, 1);
            add_filter('esig_text_editor_sif_menu', array($this, 'add_sif_nf_text_menu'), 11, 1);

            add_filter('esig_admin_more_document_contents', array($this, 'document_add_data'), 10, 1);
            // add_filter('esig_admin_more_document_contents', array($this, 'show_ninja_actions'), 10, 1);
            add_action('wp_ajax_esig_ninja_form_fields', array($this, 'esig_ninja_form_fields'));
            add_action('wp_ajax_nopriv_esig_ninja_form_fields', array($this, 'esig_ninja_form_fields'));

            // Ninja core checking fallback. 
           // add_action('admin_notices', array($this, 'esig_ninja_contract_requirement'));

            //add_filter('esig_notices_display', array($this, 'esig_ninja_contract_requirement_modal'), 10, 1);

            add_action('admin_init', array($this, 'esig_almost_done_ninja_settings'));
            // add_action('esig_send_daily_reminders', array($this, 'esig_send_reminder_email'));
             add_action('admin_menu', array($this, 'adminmenu'));
            // rgistering shortcode 
            add_shortcode('esigninja', array($this, 'render_shortcode_esigninja'));
            // adding action
            
            add_filter('show_sad_invite_link', array($this, 'show_sad_invite_link'), 10, 3);
            add_filter('esig_invite_not_sent', array($this, 'show_invite_error'), 10, 2);
            if (get_option('ninja_forms_load_deprecated', FALSE)) {
                add_filter('ninja_forms_display_before_form', array($this, 'esig_ninja_forms_display_response_deprecated'), 10, 1);
            } else {
                add_filter('ninja_forms_display_before_form', array($this, 'esig_ninja_forms_display_response'), 10, 2);
            }
            
             add_action('esig_signature_loaded', array($this, 'after_sign_check_next_agreement'), 99, 1);
            add_action('esig_agreement_after_display', array($this, 'esig_agreement_before_display'), 10, 1);
        }
        
        public function adminmenu() {
            $esigAbout = new esig_Addon_About("Ninjaforms");
            add_submenu_page('ninja-forms', __('E-signature', 'esig'), __('E-signature', 'esig'), 'read', 'esign-ninja-about', array($esigAbout, 'about_page'));
           
        }
        
         final function esig_agreement_before_display($args) {
           
            if (!self::is_nf_esign_required()) {
                return;
            }
           
            $all_done = true;
            $temp_data = self::get_temp_settings();
            foreach ($temp_data as $invite => $data) {
                if (esig_nfds_get("signed", $data) == "no") {
                    $all_done = false;
                }
            }
            if ($all_done) {
                self::delete_temp_settings();
            }
              
        }
        
        
        final function after_sign_check_next_agreement($args) {

            $document_id = $args['document_id'];

            if (!self::is_ninja_requested_agreement($document_id)) {
                return false;
            }
            if (!self::is_nf_esign_required()) {
                return false;
            }

            $invite_hash = WP_E_Sig()->invite->getInviteHash_By_documentID($document_id);
            self::save_esig_nf_meta($invite_hash, "signed", "yes");

            $temp_data = self::get_temp_settings();

            //$t_data = krsort($temp_data);

            foreach ($temp_data as $invite => $data) {
                if (esig_nfds_get("signed",$data) == "no") {
                    $invite_url = self::nf_get_invite_url($invite);
                    wp_redirect($invite_url);
                    exit;
                }
            }
        }
        

        final function show_invite_error($ret, $docId) {

            $doc = WP_E_Sig()->document->getDocument($docId);
            if (!isset($doc->document_content)) {
                return $ret;
            }
            $document_content = $doc->document_content;
            $document_raw = WP_E_Sig()->signature->decrypt(ENCRYPTION_KEY, $document_content);

            if (has_shortcode($document_raw, 'esigninja')) {

                $ret = true;
                return $ret;
            }
            return $ret;
        }

        final function esig_ninja_forms_display_response_deprecated($form_id) {
            global $ninja_forms_processing;

            //if(is_object( $ninja_forms_processing ) AND $ninja_forms_processing->get_all_success_msgs()){
            if (is_object($ninja_forms_processing)) {

                $invite_url = self::get_invite_url();
                if ($invite_url) {
                    self::remove_invite_url();
                    wp_redirect($invite_url);
                    exit;
                } else {
                    return $form_id;
                }
            }
            return $form_id;
        }

        final function esig_ninja_forms_display_response($content, $form_id) {
            global $ninja_forms_processing;

            //if(is_object( $ninja_forms_processing ) AND $ninja_forms_processing->get_all_success_msgs()){
            if (is_object($ninja_forms_processing)) {

                $invite_url = self::get_invite_url();
                if ($invite_url) {
                    self::remove_invite_url();
                    wp_redirect($invite_url);
                    exit;
                } else {
                    return $content;
                }
            }
            return $content;
        }

        final function show_sad_invite_link($show, $doc, $page_id) {
            if (!isset($doc->document_content)) {
                return $show;
            }
            $document_content = $doc->document_content;
            $document_raw = WP_E_Sig()->signature->decrypt(ENCRYPTION_KEY, $document_content);

            if (has_shortcode($document_raw, 'esigninja')) {
                $show = false;
                return $show;
            }
            return $show;
        }

        public function esig_send_reminder_email() {

            if (!function_exists('WP_E_Sig'))
                return;

            $api = new WP_E_Api();
            // get document list by status awaiting 
            $docs = $api->document->fetchAllOnStatus('awaiting');
            // loops starts 
            foreach ($docs as $doc) {
                
            }
        }

       
        
        /**
         *  Showing fallback modal for rquirement to run this plugins. 
         * 
         */
        final function esig_ninja_contract_requirement_modal($msg) {

            if (class_exists('Ninja_Forms') && function_exists("WP_E_Sig") && class_exists('ESIG_SAD_Admin') && class_exists('ESIG_SIF_Admin'))
                            return;

            ob_start();
            include_once "views/alert-modal.php";
            $msg .= ob_get_contents();
            ob_end_clean();
            return $msg;
        }

        final function esig_ninja_contract_requirement() {
            if (class_exists('Ninja_Forms') && function_exists("WP_E_Sig") && class_exists('ESIG_SAD_Admin') && class_exists('ESIG_SIF_Admin'))
                return;


            include_once "views/alert-modal.php";
        }

        final function render_shortcode_esigninja($atts) {

            extract(shortcode_atts(array(
                'formid' => '',
                'field_id' => '', //foo is a default value
                'display' => 'value',
                'option' => 'default'
                            ), $atts, 'esigninja'));
                         

            $document_id = self::docId();
            //echo $field_id;
            $notification_id = self::ninjaNotificationId($document_id);
            if (!$notification_id) {
                return;
            }
            $newFormId = self::ninjaFormId($document_id);

            // if field not exists  return false 
            if(!self::fieldExists($formid,$field_id)) return false ;

            $allowOtherFormData = apply_filters("esig_ninja_allow_otherform_data",false);
            if(!wp_validate_boolean($allowOtherFormData))
            {
                if ($newFormId != $formid) return false;
            }

            $nf_value = self::get_value($document_id,$formid, $field_id, $display, $option);
           

            if (!$nf_value) {
                return;
            }

            $finalValue = self::calculate($document_id, $nf_value);

            //$form_id = WP_E_Sig()->meta->get($document_id, 'esig_ninja_form_id');
            return self::display_value($notification_id, $formid, $finalValue);
        }

        final function esig_almost_done_ninja_settings() {

            if (!function_exists('WP_E_Sig'))
                return;

            // getting sad document id 
            $sad_document_id = ESIG_GET('doc_preview_id');


            if (!$sad_document_id) {
                return;
            }
            // creating esignature api here 
            $api = new WP_E_Api();

            $documents = $api->document->getDocument($sad_document_id);


            $document_content = $documents->document_content;

            $document_raw = $api->signature->decrypt(ENCRYPTION_KEY, $document_content);



            if (has_shortcode($document_raw, 'esigninja')) {


                preg_match_all('/' . get_shortcode_regex() . '/s', $document_raw, $matches, PREG_SET_ORDER);

                //$ninja_shortcode = $matches[0][0];

                $ninja_shortcode = '';
                $ninjaFormid = '';
                foreach ($matches as $match) {
                    if (in_array('esigninja', $match)) {
                        
                        $atts = shortcode_parse_atts($match[0]);
                        extract(shortcode_atts(array(
                    'formid' => '',
                    'field_id' => '', //foo is a default value
                                ), $atts, 'esigninja'));
                        if(is_numeric($formid)){
                            $ninjaFormid =$formid ; 
                            break;
                        }
                         //$ninja_shortcode = $match[0];
                       
                    }
                }
               
                WP_E_Sig()->document->saveFormIntegration($sad_document_id, 'ninja');
                

                



                $data = array("form_id" => $ninjaFormid);


                $display_notice = dirname(__FILE__) . '/views/alert-almost-done.php';
                $api->view->renderPartial('', $data, true, '', $display_notice);
            }
        }

        public function show_ninja_actions($more_option_page) {

            $more_option_page .= $this->document_view->add_document_view_modal();
            return $more_option_page;
        }

        public function esig_ninja_form_fields() {

            if (!function_exists('WP_E_Sig'))
                return;

            $html = '';

            $html .= '<select id="esig_nf_field_id" name="esig_nf_field_id" class="chosen-select" style="width:250px;">';
            $form_id = ESIG_POST('form_id');

            $html .= '<option value="all">Insert all fields</option>';
            //$ninja_forms = Ninja_Forms()->form( $form_id );


            $html .= ESIG_NF_SETTING::ninja_form_fields($form_id);

            

            $html .= '</select><input type="hidden" name="esig_nf_form_id" value="' . $form_id . '">';

            echo $html;

            die();
        }

        public function document_add_data($more_contents) {

            if (!function_exists('Ninja_Forms')) {
                return $more_contents;
            }

            $more_contents .= $this->document_view->add_document_view();
            return $more_contents;
        }

        public function add_sif_ninja_buttons($sif_menu) {

            if (!function_exists('Ninja_Forms')) {
                return $sif_menu;
            }

            $esig_type = ESIG_GET('esig_type');
            $document_id = ESIG_GET('document_id');

            if (empty($esig_type) && !empty($document_id)) {

                $api = new WP_E_Api();

                $document_type = $api->document->getDocumenttype($document_id);
                if ($document_type == "stand_alone") {
                    $esig_type = "sad";
                }
            }

            if ($esig_type != 'sad') {
                return $sif_menu;
            }

            $sif_menu .= ' {text: "Ninja Form Data",value: "ninja", onclick: function () { tb_show( "+ Ninja form option", "#TB_inline?width=450&height=300&inlineId=esig-ninja-option");esign.tbSize(450);}},';
            //$plugins['esig_sif'] = plugin_dir_url(__FILE__) . 'assets/js/esig-ninja-sif-buttons.js';
            return $sif_menu;
        }

        public function add_sif_nf_text_menu($sif_menu) {

            $esig_type = ESIG_GET('esig_type');
            $document_id = ESIG_GET('document_id');

            if (empty($esig_type) && !empty($document_id)) {
                $document_type = WP_E_Sig()->document->getDocumenttype($document_id);
                if ($document_type == "stand_alone") {
                    $esig_type = "sad";
                }
            }

            if ($esig_type != 'sad') {
                return $sif_menu;
            }
            $sif_menu['Ninja'] = array('label' => "Ninja Form Data");
            return $sif_menu;
        }

        public function register_action_type($types) {
            $types[$this->name] = $this;
            return (array) $types;
        }

        public function enqueue_admin_scripts() {

            if(!function_exists('esig_nfds_get')){
                return false;
            }
            
            $screen = get_current_screen();
            $admin_screens = array(
                'admin_page_esign-add-document',
                'admin_page_esign-edit-document',
                'e-signature_page_esign-view-document',
            );

            // Add/Edit Document scripts
            if (in_array(esig_nfds_get("id",$screen), $admin_screens)) {

                // wp_enqueue_style( $this->plugin_slug . '-admin-style', plugins_url( 'assets/css/esig_template.css', __FILE__ ));
                wp_enqueue_script('jquery');
                wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/esig-add-ninja.js', __FILE__), array('jquery', 'jquery-ui-dialog'), ESIG_NFDS::VERSION, true);
            }
            if (esig_nfds_get("id",$screen) != "plugins") {
                wp_enqueue_script($this->plugin_slug . '-admin-script', plugins_url('assets/js/esig-ninja-control.js', __FILE__), array('jquery', 'jquery-ui-dialog'), ESIG_NFDS::VERSION, true);
            }


            if (esig_nfds_get("id",$screen) == "toplevel_page_ninja-forms") {

                wp_enqueue_script($this->plugin_slug . '-ninja-validation', plugins_url('assets/js/esig-ninja-form-validation.js', __FILE__), array('jquery', 'jquery-ui-dialog'), ESIG_NFDS::VERSION, true);

                wp_localize_script($this->plugin_slug . '-ninja-validation', 'esig_ninja_L10n', array(
                    'valid_msg' => __("This field is required", "esig-nfds"),
                ));
            }
            
            if (esig_nfds_get("id",$screen) == "ninja-forms_page_esign-ninja-about" || esig_nfds_get("id",$screen) == "admin_page_esign-ninja-about"  || esig_nfds_get("id",$screen) == "toplevel_page_esign-ninjaforms-about") {

                wp_enqueue_script('esign-iframe-script', plugins_url('assets/js/esign-iframe.js', __FILE__), array('jquery', 'jquery-ui-dialog'), '0.0.1', true);          
                wp_register_style( 'esig_ninja_enqueue_style', plugins_url('about/assets/css/esig-about.css', __FILE__), false, '1.0.0' );
                wp_enqueue_style( 'esig_ninja_enqueue_style' );
                wp_enqueue_style( 'esig-google-fonts', 'https://fonts.googleapis.com/css2?family=Source+Sans+Pro:wght@200;300;400;600;700;900&display=swap', false );
                wp_enqueue_style( 'esig-snip-styles', plugins_url('about/assets/css/snip-styles.css', __FILE__), false, '0.0.1' );
            
            }
        }

        /**
         * Edit Screen
         *
         * @param $id
         * @return void
         */
        public function edit_screen($id = '') {
            //$settings['example'] = Ninja_Forms()->notification( $id )->get_setting( 'example' );
            $signer_name = Ninja_Forms()->notification($id)->get_setting('signer_name');
            $signer_email_address = Ninja_Forms()->notification($id)->get_setting('signer_email_address');

            $signing_logic = Ninja_Forms()->notification($id)->get_setting('signing_logic');
            $select_sad = Ninja_Forms()->notification($id)->get_setting('select_sad');
            $underline_data = Ninja_Forms()->notification($id)->get_setting('underline_data');

            $signing_reminder_email = Ninja_Forms()->notification($id)->get_setting('signing_reminder_email');

            $reminder_email = Ninja_Forms()->notification($id)->get_setting('reminder_email');
            $first_reminder_send = Ninja_Forms()->notification($id)->get_setting('first_reminder_send');
            $expire_reminder = Ninja_Forms()->notification($id)->get_setting('expire_reminder');

            include plugin_dir_path(__FILE__) . '/views/esig-ninja-action-view.php';
        }

        public function save_admin() {
            
        }

        /**
         * This function was very important in the past.
         *
         * @since 1.3.1
         * @deprecated 1.7.7
         *
         * @return string
         */
        public function process($id = '') {

            global $ninja_forms_processing;

            $api = new WP_E_Api();

            if(!class_exists('esig_sad_document')){
                return false;
            }
            
            $sad = new esig_sad_document();


            $form_id = $ninja_forms_processing->get_form_ID();

            $post_id = $ninja_forms_processing->get_form_setting('sub_id');

            $signing_logic = Ninja_Forms()->notification($id)->get_setting('signing_logic');

            $sad_page_id = Ninja_Forms()->notification($id)->get_setting('select_sad');

            $document_id = $sad->get_sad_id($sad_page_id);
            
             $docStatus  = WP_E_Sig()->document->getStatus($document_id);
            
            
            if($docStatus !="stand_alone"){
                return false;
            }

            
            

            $signer_name_field = $this->get_ninja_field_id(Ninja_Forms()->notification($id)->get_setting('signer_name'));

            $signer_email_address_field = $this->get_ninja_field_id(Ninja_Forms()->notification($id)->get_setting('signer_email_address'));


            $signer_email = Ninja_Forms()->sub($post_id)->get_field($signer_email_address_field);
            $signer_name = Ninja_Forms()->sub($post_id)->get_field($signer_name_field);

            
            $signing_reminder_email = Ninja_Forms()->notification($id)->get_setting('signing_reminder_email');

            if ($signing_reminder_email == '0') {
                // saving remidner meta here 
                $reminder_email = Ninja_Forms()->notification($id)->get_setting('reminder_email');
                $first_reminder_send = Ninja_Forms()->notification($id)->get_setting('first_reminder_send');
                $expire_reminder = Ninja_Forms()->notification($id)->get_setting('expire_reminder');

                $esig_ninja_reminders_settings = array(
                    "esig_reminder_for" => 10 , //$reminder_email,
                    "esig_reminder_repeat" => 20, //$first_reminder_send,
                    "esig_reminder_expire" =>30, // $expire_reminder,
                );

            }
            else 
            {
                $esig_ninja_reminders_settings = array();
            }
             
            // if not email address 
            if (!is_email($signer_email)) 
            {
                return;
            }
           
            // sending email invitation / redirecting .
            $result = $this->esig_invite_document($document_id, $signer_email, $signer_name, $form_id, $post_id, $signing_logic, $id);
        }

        /*         * *
         *  Return a numeric field id 
         *   If field written like this field_1 
         */

        public function get_ninja_field_id($field_id) {

            $fields = explode("_", $field_id);
            if (!isset($fields[1])) {
                $fields[1] = null;
            }
            return $fields[1];
        }

        public function enableReminders($docId,$actionSettings)
        {
            $signing_reminder_email = esig_nfds_get("signing_reminder_email",$actionSettings); //$action_settings['signing_reminder_email'];

            if ($signing_reminder_email == '1') {
                // saving remidner meta here 
                $reminder_email = esig_nfds_sanitize_init(esig_nfds_get('reminder_email', $actionSettings)); //(int) $action_settings['reminder_email'];
                $first_reminder_send = esig_nfds_sanitize_init(esig_nfds_get('first_reminder_send', $actionSettings)); //(int)$action_settings['first_reminder_send'];
                $expire_reminder = esig_nfds_sanitize_init(esig_nfds_get('expire_reminder', $actionSettings)); //(int) $action_settings['expire_reminder'];
                $esig_ninja_reminders_settings = array(
                    "esig_reminder_for" => ($reminder_email=="yes" || empty($reminder_email))? 1 : $reminder_email,
                    "esig_reminder_repeat" => ($first_reminder_send == "yes" || empty($first_reminder_send)) ? 1  : $first_reminder_send,
                    "esig_reminder_expire" => ($expire_reminder== "yes" || empty($expire_reminder)) ? 1 : $expire_reminder ,
                );

                WP_E_Sig()->meta->add($docId, "esig_reminder_settings_", json_encode($esig_ninja_reminders_settings));
                WP_E_Sig()->meta->add($docId, "esig_reminder_send_", "1");
            }
        }

        public function esig_invite_document($old_doc_id, $siner_email, $signer_name, $form_id, $post_id, $signing_logic, $notification_id,$action_settings=array()) {

            if (!function_exists('WP_E_Sig'))
                return;


            $api = new WP_E_Api();
            global $wpdb;

            global $esig_ninja_entry_id,$esig_ninja_notification_id,$esigNinjaSubmissions,$esig_ninja_form_id ;

            $esigNinjaSubmissions = get_post_meta($post_id);
            // assign global value  
            $esig_ninja_entry_id = $post_id;
            $esig_ninja_notification_id = $notification_id; 
            $esig_ninja_form_id = $form_id;

            /* make it a basic document and then send to sign */
            $old_doc = $api->document->getDocument($old_doc_id);

            $args = array(
                "entryId" => $post_id,
                "formId" => $form_id,
                "integrationType" => "esig-ninja",
            );


            //$doc_table = $wpdb->prefix . 'esign_documents';
            // Copy the document
            $doc_id = $api->document->copy($old_doc_id,$args);

            // settings meta key for ninja form field 
            $api->meta->add($doc_id, 'esig_ninja_notification_id', $notification_id);

            $api->meta->add($doc_id, 'esig_ninja_form_id', $form_id);

            $api->meta->add($doc_id, 'esig_ninja_entry_id', $post_id);
            
            
            $api->meta->add($doc_id, 'esig_ninja_submission_value', serialize($esigNinjaSubmissions));

            $api->document->saveFormIntegration($doc_id, 'ninja');

            // set document timezone
            $esig_common = new WP_E_Common();
            $esig_common->set_document_timezone($doc_id);
            // Create the user
            $recipient = array(
                "user_email" => $siner_email,
                "first_name" => $signer_name,
                "document_id" => $doc_id,
                "wp_user_id" => '',
                "user_title" => '',
                "last_name" => ''
            );

            $recipient['id'] = $api->user->insert($recipient);

            $document_type = 'normal';
            $document_status = 'awaiting';
            $doc_title = $old_doc->document_title . ' - ' . $signer_name;
            // Update the doc title
            WP_E_Sig()->document->updateTitle($doc_id, $doc_title);
            WP_E_Sig()->document->updateType($doc_id, 'normal');
            WP_E_Sig()->document->updateStatus($doc_id, 'awaiting');

            $doc = $api->document->getDocument($doc_id);

            // trigger an action after document save .
            do_action('esig_sad_document_invite_send', array(
                'document' => $doc,
                'old_doc_id' => $old_doc_id,
            ));

            // reminder settings needs here 
            $this->enableReminders($doc_id,$action_settings);

            // Get Owner
            $owner = $api->user->getUserByID($doc->user_id);


            // Create the invitation?
            $invitation = array(
                "recipient_id" => $recipient['id'],
                "recipient_email" => $recipient['user_email'],
                "recipient_name" => $recipient['first_name'],
                "document_id" => $doc_id,
                "document_title" => $doc->document_title,
                "sender_name" => $owner->first_name . ' ' . $owner->last_name,
                "sender_email" => $owner->user_email,
                "sender_id" => 'stand alone',
                "document_checksum" => $doc->document_checksum,
                "sad_doc_id" => $old_doc_id,
            );

            $invite_controller = new WP_E_invitationsController();
            if ($signing_logic == "email") {

                if ($invite_controller->saveThenSend($invitation, $doc)) {

                    return true;
                }
            } elseif ($signing_logic == "redirect") {
                // if used redirect then other plugin can not work properly. 

                $invitation_id = $invite_controller->save($invitation);
                $invite_hash = $api->invite->getInviteHash($invitation_id);
                if (self::is_ninja_three()) {
                    
                     //self::save_invite_url($invite_hash, $doc->document_checksum);
                     self::save_esig_nf_meta($invite_hash, "signed", "no",$post_id);
                     return self::nf_next_agreement_link();
                    //return WP_E_Invite::get_invite_url($invite_hash, $doc->document_checksum);
                } else {
                    self::save_invite_url($invite_hash, $doc->document_checksum);
                    return true;
                }
            }
        }

        /**
         * Return an instance of this class.
         * @since     0.1
         * @return    object    A single instance of this class.
         */
        public static function get_instance() {

            // If the single instance hasn't been set, set it now.
            if (null == self::$instance) {
                self::$instance = new self;
            }

            return self::$instance;
        }

    }

    

    

    

    
    
endif;