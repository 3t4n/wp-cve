<?php
/**
 * The form saving from backend functionality of the plugin.
 *
 * @package    Wishlist-and-compare
 * @subpackage Wishlist-and-compare/inc/admin
 *
 * @link  https://themehigh.com
 * @since 1.0.0.0
 */

namespace THWWC\admin;

use \THWWC\base\THWWC_Utils;

if (!class_exists('THWWC_Admin_Settings')) :
    /**
     * Admin settings class.
     *
     * @package Wishlist-and-compare
     * @link    https://themehigh.com
     */
    class THWWC_Admin_Settings
    {
         /**
         * Function to run hooks and filters.
         *
         * @return void
         */
        public function register()
        {
            $ajax_functions = array( 'save_general_settings', 'save_shop_page_settings', 'save_product_page_settings', 'save_wishlist_page_settings',
                'save_wishlist_counter_settings', 'save_socialmedia_settings',
                'save_compare_settings', 'save_compare_table_settings', 'reset_to_default' );

            foreach ($ajax_functions as $ajax_key => $ajax_function) {
                add_action('wp_ajax_'.$ajax_function, array($this, $ajax_function));
                // add_action('wp_ajax_nopriv_'.$ajax_function, array($this,$ajax_function));
            }

            add_action('admin_head', array($this,'review_banner_custom_css'));
            add_action('admin_footer', array($this,'review_banner_custom_js'));
            add_action( 'admin_init', array( $this, 'wwc_notice_actions' ), 20 );
            add_action( 'admin_notices', array($this, 'output_review_request_link'));

        }
        
        /**
         * Function to reset to default by deleting get_option value.
         *
         * @return void
         */
        public function reset_to_default()
        {
            if (!isset($_POST['thwwac_r_security']) || !wp_verify_nonce($_POST['thwwac_r_security'], 'thwwac_reset_security')) {
                die('Sorry, your nonce did not verify');
            }
            
            if (current_user_can('manage_options')) {
                if (isset($_POST['reset'])) {
                    $reset = sanitize_text_field($_POST['reset']);
                    if ($reset) {
                        delete_option('thwwac_'.$reset);
                    }
                }
            }
            wp_die();
        }

        /**
         * Function to remove update notifications(ajax-response).
         *
         * @return array
         */
        public function remove_update_notifications()
        {
            global $wp_version;
            return(object) array('last_checked'=> time(), 'version_checked'=> $wp_version,);
        }

        /**
         * Function to reset to default by deleting get_option value(ajax-response).
         *
         * @return void
         */
        public function save_general_settings()
        {
            if (!isset($_POST['thwwac_security']) || !wp_verify_nonce($_POST['thwwac_security'], 'thwwac_ajax_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $fields = array('wishlist_page', 'ajax_loading', 'remove_on_second_click', 'require_login', 'redirect_login', 'wishlnk_myaccont', 'remove_pdct', 'chckut_redrct', 'success_notice', 'wishlst_added_text', 'redirect_wishlist', 'view_button_text','custom_css_wishlist');
            foreach ($fields as $key => $value) {
                if (isset($_POST[$value])) {
                    $data[$value] = sanitize_text_field($_POST[$value]);
                }
            }
            $updated = update_option('thwwac_general_settings', $data);

            wp_send_json($updated);
        }

        /**
         * Function to save shop page settings(ajax-response).
         *
         * @return void
         */
        public function save_shop_page_settings()
        {
            if (!isset($_POST['thwwac_s_security']) || !wp_verify_nonce($_POST['thwwac_s_security'], 'thwwac_shop_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $url = '';
            $error = '';
            if (isset($_FILES['icon_upload']['name'])) {
                $upload_data = $this->thwwc_upload_data($_FILES['icon_upload']);
                $url = $upload_data['url'];
                $error = $upload_data['error'];
            }

            $fields = array('show_loop', 'shop_btn_type', 'wishlist_position', 'thumb_position', 'wish_icon', 'icon_upload', 'wish_icon_color', 'preloader', 'button_text', 'wishlist_btn_style_shop','shop_btn_background', 'shop_btn_font', 'shop_btn_border','shop_link_font','wishlist_font_size', 'add_wishlist_text', 'already_text_show', 'already_wishlist_text','wishlist_link_style','shop_link_');
            foreach ($fields as $key => $value) {
                if ($value == 'icon_upload') {
                    $data[$value] = isset($url) ? $url : '';
                } else {
                    if ($value == 'custom' && $error != '') {
                        $shop_preview = isset($_POST['shop_preview']) ? sanitize_text_field($_POST['shop_preview']) : '';
                        $data['icon_upload'] = $shop_preview;
                    }
                    if (isset($_POST[$value])) {
                        $data[$value] = sanitize_text_field($_POST[$value]);
                    }
                }
            }
            $updated = update_option('thwwac_shop_page_settings', $data);
            wp_send_json($updated);
        }

        public function sanitize_upload_data( $files ){
            $uploads = [];
            if( is_array($files) && $files){
                foreach ($files as $fkey => $fvalue) {
                    $uploads[sanitize_key($fkey)] = $this->sanitize_uploads( $fkey, $fvalue);
                }
            }
            return $uploads;
        }

        public function sanitize_uploads( $type, $value){
            $cleaned = '';
            $value = $type != 'tmp_name' ? stripslashes( $value ) : $value;
            if( $type ){
                switch ($type) {
                    case 'name':
                        $cleaned = sanitize_file_name( $value );
                    break;
                    case 'type':
                        $cleaned = sanitize_mime_type( $value );
                    break;
                    case 'error':
                    case 'size':
                        $cleaned = is_numeric( trim( $value ) );
                        $cleaned = $cleaned ? absint( trim( $value ) ) : "";
                    break;

                    default:
                        $cleaned = $value;
                    break;
                }
            }
            return $cleaned;
        }

        /**
         * Function to save product page settings(ajax-response).
         *
         * @return void
         */
        public function save_product_page_settings()
        {
            if (!isset($_POST['thwwac_p_security']) || !wp_verify_nonce($_POST['thwwac_p_security'], 'thwwac_product_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $error = '';
            $url = '';
            if (isset($_FILES['iconp_upload']['name'])) {
                $upload_data = $this->thwwc_upload_data($_FILES['iconp_upload']);
                $url = $upload_data['url'];
                $error = $upload_data['error'];
            }
            $fields = array('show_in_pdctpage', 'pdct_btn_type', 'button_pstn_pdct_page', 'pdct_thumb_position', 'icon_pdct_page', 'iconp_upload', 'wish_icon_color_pdctpage', 'preloader_pdctpage', 'button_text_pdctpage', 'add_wishlist_text_pdctpage','wishlist_btn_style_pdct','pdct_btn_background','pdct_btn_font','pdct_btn_border','pdct_link_font','pdct_wishlist_font_size','already_text_show_pdctpage', 'already_wishlist_text_pdctpage');
            foreach ($fields as $key => $value) {
                if ($value == 'iconp_upload') {
                    $data[$value] = isset($url) ? $url : '';
                } else {
                    if ($value == 'custom' && $error != '') {
                        $pdct_preview = isset($_POST['pdct_preview']) ? sanitize_text_field($_POST['pdct_preview']) : '';
                        $data['iconp_upload'] = $pdct_preview;
                    }
                    if (isset($_POST[$value])) {
                        $data[$value] = sanitize_text_field($_POST[$value]);
                    }
                }
            }
            $updated = update_option('thwwac_product_page_settings', $data);
            
            wp_send_json($updated);
        }

        /**
         * Function to save wishlist page settings(ajax-response).
         *
         * @return void
         */
        public function save_wishlist_page_settings()
        {
            if (!isset($_POST['thwwac_w_security']) || !wp_verify_nonce($_POST['thwwac_w_security'], 'thwwac_wishpage_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $fields = array('add_cart_text_show', 'add_cart_text_wshlstpage', 'show_unit_price', 'show_stock_status', 'show_date_addition', 'remove_icon_pstn', 'show_checkboxes', 'show_actions_button', 'show_selectedto_cart', 'add_slct_to_cart_text', 'show_addallto_cart', 'add_all_to_cart_text', 'grid_list_view','show_wishlist_filter');
            foreach ($fields as $key => $value) {
                if (isset($_POST[$value])) {
                    $data[$value] = sanitize_text_field($_POST[$value]);
                }
            }
            $updated = update_option('thwwac_wishlist_page_settings', $data);
            
            wp_send_json($updated);
        }

        /**
         * Function to save wishlist counter settings(ajax-response).
         *
         * @return void
         */
        public function save_wishlist_counter_settings()
        {
            if (!isset($_POST['thwwac_c_security']) || !wp_verify_nonce($_POST['thwwac_c_security'], 'thwwac_counter_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $error = '';
            $url = '';
            if (isset($_FILES['iconc_upload']['name'])) {
                $upload_data = $this->thwwc_upload_data($_FILES['iconc_upload']);
                $url = $upload_data['url'];
                $error = $upload_data['error'];
            }
            $fields = array('counter_icon', 'iconc_upload', 'counter_icon_color', 'show_counter_text', 'whlst_counter_text', 'add_countr_to_menu', 'counter_position', 'num_pdcts_counter', 'hide_zero_value');
            foreach ($fields as $key => $value) {
                if ($value == 'iconc_upload') {
                    $data[$value] = isset($url) ? $url : '';
                } else {
                    if ($value == 'custom' && $error != '') {
                        $count_preview = isset($_POST['count_preview']) ? sanitize_text_field($_POST['count_preview']) : '';
                        $data['iconc_upload'] = $count_preview;
                    }
                    if (isset($_POST[$value])) {
                        $data[$value] = sanitize_text_field($_POST[$value]);
                    }
                }
            }
            $updated = update_option('thwwac_wishlist_counter_settings', $data);
            wp_send_json($updated);
        }

        /**
         * Function to save social media settings(ajax-response).
         *
         * @return void
         */
        public function save_socialmedia_settings()
        {
            if (!isset($_POST['thwwac_sm_security']) || !wp_verify_nonce($_POST['thwwac_sm_security'], 'thwwac_social_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $fields = array('share_wishlist', 'fb_button', 'twitter_button', 'pi_button', 'whtsp_button', 'email_button', 'clipboard_button', 'share_on_text', 'social_icon_color');
            foreach ($fields as $key => $value) {
                if (isset($_POST[$value])) {
                    $data[$value] = sanitize_text_field($_POST[$value]);
                }
            }
            $updated = update_option('thwwac_socialmedia_settings', $data);
            wp_send_json($updated);
        }

        /**
         * Function to save compare general settings(ajax-response).
         *
         * @return void
         */
        public function save_compare_settings()
        {
            if (!isset($_POST['thwwac_cp_security']) || !wp_verify_nonce($_POST['thwwac_cp_security'], 'thwwac_compare_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }
            $error = '';
            $url = '';
            $url_pdct = '';
            $error_pdct = '';
            if (isset($_FILES['icon_upload']['name'])) {
                $upload_data = $this->thwwc_upload_data($_FILES['icon_upload']);
                $url = $upload_data['url'];
                $error = $upload_data['error'];
            }
            $fields = array('show_in_shop', 'shoppage_position', 'thumb_position', 'cmp_icon', 'icon_upload', 'cmp_icon_color', 'compare_type', 'compare_text', 'added_text', 'show_in_product', 'productpage_position', 'open_popup', 'button_action');
            foreach ($fields as $key => $value) {
                if ($value == 'icon_upload') {
                    $data[$value] = isset($url) ? $url : '';
                } else {
                    if ($value == 'custom' && $error != '') {
                        $shop_preview = isset($_POST['shop_preview']) ? sanitize_text_field($_POST['shop_preview']) : '';
                        $data['icon_upload'] = $shop_preview;
                    }
                    if (isset($_POST[$value])) {
                        $data[$value] = sanitize_text_field($_POST[$value]);
                    }
                }
            }
            $updated = update_option('thwwac_compare_settings', $data);
            
            wp_send_json($updated);
        }

        /**
         * Function to save compare table settings(ajax-response).
         *
         * @return void
         */
        public function save_compare_table_settings()
        {
            if (!isset($_POST['thwwac_ct_security']) || !wp_verify_nonce($_POST['thwwac_ct_security'], 'thwwac_table_security')) {
                die('Sorry, your nonce did not verify');
            }
            if (!current_user_can('manage_options')) {
                die();
            }

            $attribute_taxonomies = wc_get_attribute_taxonomies(); 
            $attribute_terms = array();
            if ($attribute_taxonomies) {
                foreach ($attribute_taxonomies as $tax) {
                    if (taxonomy_exists(wc_attribute_taxonomy_name($tax->attribute_name))) {
                        $attribute_name = isset($tax->attribute_name) ? $tax->attribute_name : '';
                        array_push($attribute_terms, $attribute_name);
                    }
                }
            }

            $values = array('compare_page', 'lightbox_title', 'hide_attribute', 'hide_show', 'remove_button');
            $drag_fields = array('show_image', 'show_title', 'show_price', 'show_description', 'show_addtocart', 'show_sku', 'show_available', 'show_weight', 'show_dimension');
            $drag_fields = array_merge($drag_fields, $attribute_terms);
            $order_fields = array();
            for ($i=0; $i<count($drag_fields); $i++) {
                foreach ($drag_fields as $value) {
                    if (isset($_POST[$value.'_order'])) {
                        $order_number = sanitize_text_field($_POST[$value.'_order']);
                        if ($order_number == $i) {
                            array_push($order_fields, $value);
                        }
                    }
                }
            }
            $insert = array();
            foreach ($values as $key => $value) {
                if (isset($_POST[$value])) {
                    $insert[$value] = sanitize_text_field($_POST[$value]);
                }
            }
           // $values = $_POST;
            $insert['fields'] = array();
            foreach ($order_fields as $key => $value) {
                if (isset($_POST[$value])) {
                    $insert['fields'][$value] = sanitize_text_field($_POST[$value]);
                }
            }

            foreach ($drag_fields as $field) {
                if (!array_key_exists($field, $insert['fields'])) {
                    $insert['fields'][$field] = false;
                }
            }

            $updated = update_option('thwwac_compare_table_settings', $insert);
            wp_send_json($updated);
        }

        private function thwwc_upload_data($file){
            if (!current_user_can('upload_files')) {
                die('You do not have permission to upload files.');
            }
            if (!function_exists('wp_handle_upload')) {
                include_once ABSPATH . 'wp-admin/includes/file.php';
            }
            $upload_data = array();
            $upload_data['url'] = '';
            $upload_data['error'] = '';
            $uploadedfile = $this->sanitize_upload_data($file);
            $allowedMimes = array(
                'jpg|jpeg|jpe' => 'image/jpeg',
                'png'          => 'image/png',
                'webp'         => 'image/webp',
            );
            $fileInfo = wp_check_filetype(basename($uploadedfile['name']), $allowedMimes);
            
            if (!empty($fileInfo['type'])) {
                $upload_overrides = array('test_form' => false, 'mimes' => $allowedMimes);
                $movefile = wp_handle_upload($uploadedfile, $upload_overrides);
                if ($movefile && !isset($movefile['error'])) {
                    $upload_data['url'] = isset($movefile['url']) ? esc_url($movefile['url']) : '';
                } else {
                    $upload_data['error'] = isset($movefile['error']) ? esc_html($movefile['error']) : '';
                }
            }
            return $upload_data;
        }

        public function wwc_notice_actions(){

            if( !(isset($_GET['thwwc_remind']) || isset($_GET['thwwc_dissmis']) || isset($_GET['thwwc_reviewed'])) ) {
                return;
            }

            $nonse = isset($_GET['thwwc_review_nonce']) ? $_GET['thwwc_review_nonce'] : false;
            $capability = THWWC_Utils::wwc_capability();

            if(!wp_verify_nonce($nonse, 'thwwc_notice_security') || !current_user_can($capability)){
                die();
            }

            $now = time();

            $thwwc_remind = isset($_GET['thwwc_remind']) ? sanitize_text_field( wp_unslash($_GET['thwwc_remind'])) : false;
            if($thwwc_remind){
                update_user_meta( get_current_user_id(), 'thwwc_review_skipped', true );
                update_user_meta( get_current_user_id(), 'thwwc_review_skipped_time', $now );
            }

            $thwwc_dissmis = isset($_GET['thwwc_dissmis']) ? sanitize_text_field( wp_unslash($_GET['thwwc_dissmis'])) : false;
            if($thwwc_dissmis){
                update_user_meta( get_current_user_id(), 'thwwc_review_dismissed', true );
                update_user_meta( get_current_user_id(), 'thwwc_review_dismissed_time', $now );
            }

            $thwwc_reviewed = isset($_GET['thwwc_reviewed']) ? sanitize_text_field( wp_unslash($_GET['thwwc_reviewed'])) : false;
            if($thwwc_reviewed){
                update_user_meta( get_current_user_id(), 'thwwc_reviewed', true );
                update_user_meta( get_current_user_id(), 'thwwc_reviewed_time', $now );
            }
        }

        public function output_review_request_link(){

            if(!apply_filters('thwwc_show_dismissable_admin_notice', true)){
                return;
            }

            if ( !current_user_can( 'manage_options' ) ) {
                return;
            }

            $current_screen = get_current_screen();
            // if($current_screen->id !== 'woocommerce_page_checkout_form_designer'){
            //  return;
            // }

            $thwwc_reviewed = get_user_meta( get_current_user_id(), 'thwwc_reviewed', true );
            if($thwwc_reviewed){
                return;
            }

            $now = time();
            $dismiss_life  = apply_filters('thwwc_dismissed_review_request_notice_lifespan', 6 * MONTH_IN_SECONDS);
            $reminder_life = apply_filters('thwwc_skip_review_request_notice_lifespan', 7 * DAY_IN_SECONDS);
            
            $is_dismissed   = get_user_meta( get_current_user_id(), 'thwwc_review_dismissed', true );
            $dismisal_time  = get_user_meta( get_current_user_id(), 'thwwc_review_dismissed_time', true );
            $dismisal_time  = $dismisal_time ? $dismisal_time : 0;
            $dismissed_time = $now - $dismisal_time;
            
            if( $is_dismissed && ($dismissed_time < $dismiss_life) ){
                return;
            }

            $is_skipped = get_user_meta( get_current_user_id(), 'thwwc_review_skipped', true );
            $skipping_time = get_user_meta( get_current_user_id(), 'thwwc_review_skipped_time', true );
            $skipping_time = $skipping_time ? $skipping_time : 0;
            $remind_time = $now - $skipping_time;
            
            if($is_skipped && ($remind_time < $reminder_life) ){
                return;
            }

            $thwwc_since = get_option('thwwc_since');
            if(!$thwwc_since){
                $now = time();
                update_option('thwwc_since', $now, 'no' );
            }
            $thwwc_since = $thwwc_since ? $thwwc_since : $now;
            $render_time = apply_filters('thwwc_show_review_banner_render_time' , 7 * DAY_IN_SECONDS);
            $render_time = $thwwc_since + $render_time;
            if($now > $render_time ){
                $this->render_review_request_notice();
            }
            
        }

        public function review_banner_custom_css(){

            ?>
            <style>

                #thwwc_review_request_notice{
                    margin-bottom: 20px;
                }
                .thwwc-review-wrapper {
                    padding: 15px 28px 26px 10px !important;
                    margin-top: 5px;
                }
                .thwwc-review-image {
                    float: left;
                }
                .thwwc-review-content {
                    padding-right: 180px;
                }
                .thwwc-review-content p {
                    padding-bottom: 14px;
                    line-height: 1.4;
                }
                .thwwc-notice-action{ 
                    padding: 8px 18px 8px 18px;
                    background: #fff;
                    color: #007cba;
                    border-radius: 5px;
                    border: 1px solid #007cba;
                }
                .thwwc-notice-action.thwwc-yes {
                    background-color: #2271b1;
                    color: #fff;
                }
                .thwwc-notice-action:hover:not(.thwwc-yes) {
                    background-color: #f2f5f6;
                }
                .thwwc-notice-action.thwwc-yes:hover {
                    opacity: .9;
                }
                .thwwc-notice-action .dashicons{
                    display: none;
                }
                .thwwc-themehigh-logo {
                    position: absolute;
                    right: 20px;
                    top: calc(50% - 13px);
                }
                .thwwc-notice-action {
                    background-repeat: no-repeat;
                    padding-left: 40px;
                    background-position: 18px 8px;
                    cursor: pointer;
                }
                .thwwc-yes{
                    background-image: url(<?php echo THWWC_URL; ?>assets/libs/icons/tick.svg);
                }
                .thwwc-remind{
                    background-image: url(<?php echo THWWC_URL; ?>assets/libs/icons/reminder.svg);
                }
                .thwwc-dismiss{
                    background-image: url(<?php echo THWWC_URL; ?>assets/libs/icons/close.svg);
                }
                .thwwc-done{
                    background-image: url(<?php echo THWWC_URL; ?>assets/libs/icons/done.svg);
                }
            </style>
        <?php    
        }

        public function review_banner_custom_js(){
            ?>
            <script type="text/javascript">
                (function($, window, document) { 
                    $( document ).on( 'click', '.thpladmin-notice .notice-dismiss', function() {
                        var wrapper = $(this).closest('div.thpladmin-notice');
                        var nonce = wrapper.data("nonce");
                        var data = {
                            thwwc_review_nonce: nonce,
                            action: 'hide_thwwc_admin_notice',
                        };
                        $.post( ajaxurl, data, function() {

                        });
                    });
                }(window.jQuery, window, document));
            </script>
            <?php
        }

        private function render_review_request_notice(){
            $current_tab = isset( $_GET['tab'] ) ? sanitize_key( $_GET['tab'] ) : 'general_settings';
            $current_section = isset( $_GET['section'] ) ? sanitize_key( $_GET['section'] ) : '';

            
            $remind_url = add_query_arg(array('thwwc_remind' => true, 'thwwc_review_nonce' => wp_create_nonce( 'thwwc_notice_security')));
            $dismiss_url = add_query_arg(array('thwwc_dissmis' => true, 'thwwc_review_nonce' => wp_create_nonce( 'thwwc_notice_security')));
            $reviewed_url= add_query_arg(array('thwwc_reviewed' => true, 'thwwc_review_nonce' => wp_create_nonce( 'thwwc_notice_security')));
            ?>

            <div class="notice notice-info thpladmin-notice is-dismissible thwwc-review-wrapper" data-nonce="<?php echo wp_create_nonce( 'thwwc_notice_security'); ?>">
                <div class="thwwc-review-image">
                    <img src="<?php echo esc_url(THWWC_URL .'assets/libs/icons/review-left.png'); ?>" alt="themehigh">
                </div>
                <div class="thwwc-review-content">
                    <h3><?php _e('We would love to hear about your recent experience.', 'wishlist-and-compare'); ?></h3>
                    <p><?php _e('We are eager to know how did Wishlist and Compare for WooCommerce plugin helped you work better. Share with us your valuable feedback and reviews and help us bring the best for you.', 'wishlist-and-compare'); ?></p>
                    <div class="action-row">
                        <a class="thwwc-notice-action thwwc-yes" onclick="window.open('https://wordpress.org/support/plugin/wishlist-and-compare/reviews/?rate=5#new-post', '_blank')" style="margin-right:16px; text-decoration: none">
                            <?php _e("Yes, today", 'wishlist-and-compare'); ?>
                        </a>

                        <a class="thwwc-notice-action thwwc-done" href="<?php echo esc_url($reviewed_url); ?>" style="margin-right:16px; text-decoration: none">
                            <?php _e('Already, Did', 'wishlist-and-compare'); ?>
                        </a>

                        <a class="thwwc-notice-action thwwc-remind" href="<?php echo esc_url($remind_url); ?>" style="margin-right:16px; text-decoration: none">
                            <?php _e('Maybe later', 'wishlist-and-compare'); ?>
                        </a>

                        <a class="thwwc-notice-action thwwc-dismiss" href="<?php echo esc_url($dismiss_url); ?>" style="margin-right:16px; text-decoration: none">
                            <?php _e("Nah, Never", 'wishlist-and-compare'); ?>
                        </a>
                    </div>
                </div>
                <div class="thwwc-themehigh-logo">
                    <span class="logo" style="float: right">
                        <a target="_blank" href="https://www.themehigh.com">
                            <img src="<?php echo esc_url(THWWC_URL .'assets/libs/icons/logo.svg'); ?>" style="height:19px;margin-top:4px;" alt="themehigh"/>
                        </a>
                    </span>
                </div>
            </div>

            <?php
        }
    }
endif;