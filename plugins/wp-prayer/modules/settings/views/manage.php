<?php
/**
 * This class used to manage settings page in backend.
 * @author Flipper Code <hello@flippercode.com>
 * @version 1.0.0
 * @package Forms
 */
if ( isset( $_REQUEST['_wpnonce'] ) ) {
	$nonce = sanitize_text_field( $_REQUEST['_wpnonce']  );
	if ( ! wp_verify_nonce( $nonce, 'wpgmp-nonce' ) ) {
		die( 'Cheating...' );
	} else {
		$data = $_POST;
	}
}

$data = unserialize(get_option( '_wpe_prayer_engine_settings' ));

$form  = new FlipperCode_WPE_HTML_Markup();

$form->set_header( __( 'Settings', WPE_TEXT_DOMAIN ), $response );
$form->add_element( 'text', 'wpe_num_prayer_per_page', array(
	'label' => __( 'Number of prayers/praises per page', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_num_prayer_per_page'] ) and ! empty( $data['wpe_num_prayer_per_page'] )) ? $data['wpe_num_prayer_per_page'] : '',

	'placeholder' => __( 'Number of prayers/praises per page', WPE_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'wpe_prayer_btn_color', array(
	'label' => __( 'Submit Button Color', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_prayer_btn_color'] ) and ! empty( $data['wpe_prayer_btn_color'] )) ? $data['wpe_prayer_btn_color'] : '',
	'desc' => __( 'Color code in Hex or Text #CCCCCC or RED', WPE_TEXT_DOMAIN ),
	'placeholder' => '#CCCCCC',
));

$form->add_element( 'text', 'wpe_prayer_btn_text_color', array(
	'label' => __( 'Submit Button Text Color', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_prayer_btn_text_color'] ) and ! empty( $data['wpe_prayer_btn_text_color'] )) ? $data['wpe_prayer_btn_text_color'] : '',
	'desc' => __( 'Color code in Hex or Text #CCCCCC or RED', WPE_TEXT_DOMAIN ),
	'placeholder' => __( '#CCCCCC', WPE_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'wpe_pray_btn_color', array(
	'label' => __( 'Pray Button Color', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_pray_btn_color'] ) and ! empty( $data['wpe_pray_btn_color'] )) ? $data['wpe_pray_btn_color'] : '',
	'desc' => __( 'Color code in Hex or Text #CCCCCC or RED', WPE_TEXT_DOMAIN ),
	'placeholder' => __( '#CCCCCC', WPE_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'wpe_pray_text_color', array(
	'label' => __( 'Pray Button Text Color', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_pray_text_color'] ) and ! empty( $data['wpe_pray_text_color'] )) ? $data['wpe_pray_text_color'] : '',
	'desc' => __( 'Color code in Hex or Text #CCCCCC or RED', WPE_TEXT_DOMAIN ),
	'placeholder' => __( '#CCCCCC', WPE_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'wpe_pray_text', array(
	'label' => __( 'Pray Button Text', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_pray_text'] ) and ! empty( $data['wpe_pray_text'] )) ? $data['wpe_pray_text'] : '',

	'placeholder' => __( 'Pray', WPE_TEXT_DOMAIN ),
));

// $form->add_element( 'file', 'wpe_pray_btn_image', array(
// 	'label' => __( 'Pray Button Image', WPE_TEXT_DOMAIN ),
// 	'value' => (isset( $data['wpe_pray_btn_image'] ) and ! empty( $data['wpe_pray_btn_image'] )) ? $data['wpe_pray_btn_image'] : '',
// 	'desc' => __( 'Button Image', WPE_TEXT_DOMAIN ),
// ));

//
$form->add_element( 'textarea', 'wpe_terms_and_condition', array(
	'label' => __( 'Terms and Condition', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_terms_and_condition'] ) and ! empty( $data['wpe_terms_and_condition'] )) ? $data['wpe_terms_and_condition'] : '',

	'placeholder' => __( 'Terms and Condition', WPE_TEXT_DOMAIN ),
));

$form->add_element( 'text', 'wpe_num_of_characters_in_message', array(
	'label' => __( 'Maximum characters allowed in message box', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_num_of_characters_in_message'] ) and ! empty( $data['wpe_num_of_characters_in_message'] )) ? $data['wpe_num_of_characters_in_message'] : '',

	'placeholder' => __( 'Number of Characters(Numbers Only)', WPE_TEXT_DOMAIN ),
));
//
$form->add_element( 'checkbox', 'wpe_login_required', array(

	'current' => (isset( $data['wpe_login_required'] ) and ! empty( $data['wpe_login_required'] )) ? $data['wpe_login_required'] : '',
	'desc' => __( 'Check if login is not required to submit the prayer request', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'false',
));
$form->add_element( 'checkbox', 'wpe_send_email', array(

	'current' => (isset( $data['wpe_send_email'] ) and ! empty( $data['wpe_send_email'] )) ? $data['wpe_send_email'] : '',
	'desc' => __( 'User email is notified after submitting prayer request', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));
$form->add_element( 'checkbox', 'wpe_send_admin_email', array(

	'current' => (isset( $data['wpe_send_admin_email'] ) and ! empty( $data['wpe_send_admin_email'] )) ? $data['wpe_send_admin_email'] : '',
	'desc' => __( 'Admin email is notified when new prayer request is submitted', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));
$form->add_element( 'checkbox', 'wpe_hide_email', array(

	'current' => (isset( $data['wpe_hide_email'] ) and ! empty( $data['wpe_hide_email'] )) ? $data['wpe_hide_email'] : '',
	'desc' => __( 'Do not show email on prayer form', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

/* Edit By LogixTree (Karan) */

$form->add_element( 'checkbox', 'wpe_disapprove_prayer_default', array(

	'current' => (isset( $data['wpe_disapprove_prayer_default'] ) and ! empty( $data['wpe_disapprove_prayer_default'] )) ? $data['wpe_disapprove_prayer_default'] : '',
	'desc' => __( 'Default prayer requests to status pending', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_hide_prayer', array(

	'current' => (isset( $data['wpe_hide_prayer'] ) and ! empty( $data['wpe_hide_prayer'] )) ? $data['wpe_hide_prayer'] : '',
	'desc' => __( 'Do not show prayer button', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_hide_prayer_count', array(

	'current' => (isset( $data['wpe_hide_prayer_count'] ) and ! empty( $data['wpe_hide_prayer_count'] )) ? $data['wpe_hide_prayer_count'] : '',
	'desc' => __( 'Do not show prayer count', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));
$form->add_element( 'checkbox', 'wpe_display_author', array(

	'current' => (isset( $data['wpe_display_author'] ) and ! empty( $data['wpe_display_author'] )) ? $data['wpe_display_author'] : '',
	'desc' => __( 'Display name on prayer listing', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_captcha', array(

	'current' => (isset( $data['wpe_captcha'] ) and ! empty( $data['wpe_captcha'] )) ? $data['wpe_captcha'] : '',
	'desc' => __( 'Enable captcha', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-8">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'text', 'wpe_prayer_Site_Key', array(
    'label' => __( 'Google reCaptcha v3 Site Key', WPE_TEXT_DOMAIN ),
    'value' => (isset( $data['wpe_prayer_site_key'] ) and ! empty( $data['wpe_prayer_site_key'] )) ? $data['wpe_prayer_site_key'] : '0',
   
    'placeholder' => '6LeG_2QUAAAAAIw5Qj9eyTlt_sATdOmTHesbwert',
));

$form->add_element( 'text', 'wpe_prayer_secret_key', array(
    'label' => __( 'Google reCaptcha v3 secret key', WPE_TEXT_DOMAIN ),
    'value' => (isset( $data['wpe_prayer_secret_key'] ) and ! empty( $data['wpe_prayer_secret_key'] )) ? $data['wpe_prayer_secret_key'] : '0',
    
    'placeholder' => '6LeG_2QUAAAAAIw5Qj9eyTlt_sATdOmTHesbwert',
));

$form->add_element( 'checkbox', 'wpe_country', array(

	'current' => (isset( $data['wpe_country'] ) and ! empty( $data['wpe_country'] )) ? $data['wpe_country'] : '',
	'desc' => __( 'Show country', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_share', array(
	'label' => __( 'Share', WPE_TEXT_DOMAIN ),
	'current' => (isset( $data['wpe_share'] ) and ! empty( $data['wpe_share'] )) ? $data['wpe_share'] : '',
	'desc' => __( 'Do not share this request', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_autoemail', array(
	'current' => (isset( $data['wpe_autoemail'] ) and ! empty( $data['wpe_autoemail'] )) ? $data['wpe_autoemail'] : '',
	'desc' => __( 'email user when someone pray for the request', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'text', 'wpe_prayer_time_interval', array(
	'label' => __( 'Time interval between Prayed/Pray button in seconds', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_prayer_time_interval'] ) and ! empty( $data['wpe_prayer_time_interval'] )) ? $data['wpe_prayer_time_interval'] : '0',


));


$form->add_element( 'checkbox', 'wpe_prayer_comment', array(

	'current' => (isset( $data['wpe_prayer_comment'] ) and ! empty( $data['wpe_prayer_comment'] )) ? $data['wpe_prayer_comment'] : '',
	'desc' => __( 'Allow to enter comments on prayers', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_prayer_comment_status', array(

	'current' => (isset( $data['wpe_prayer_comment_status'] ) and ! empty( $data['wpe_prayer_comment_status'] )) ? $data['wpe_prayer_comment_status'] : '',
	'desc' => __( 'Default prayer comments to Approved status', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$timeago4 = date_i18n(get_option('date_format'),time());
$form->add_element( 'checkbox', 'wpe_date', array(

	'current' => (isset( $data['wpe_date'] ) and ! empty( $data['wpe_date'] )) ? $data['wpe_date'] : '',
	'desc' => __( 'Show date on prayer listing instead of time ago', WPE_TEXT_DOMAIN ).', '.$timeago4,
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$prayer_date3=', '.__('ago',WPE_TEXT_DOMAIN).' '.human_time_diff( current_time('U')-259200, current_time('U') );

$form->add_element( 'checkbox', 'wpe_ago', array(

	'current' => (isset( $data['wpe_ago'] ) and ! empty( $data['wpe_ago'] )) ? $data['wpe_ago'] : '',
	'desc' => __( 'For translation, the word ago is place before the time', WPE_TEXT_DOMAIN ).$prayer_date3,
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'checkbox', 'wpe_category', array(

	'current' => (isset( $data['wpe_category'] ) and ! empty( $data['wpe_category'] )) ? $data['wpe_category'] : '',
	'desc' => __( 'Display prayer category', WPE_TEXT_DOMAIN ),
	'class' => 'form-control ',
	'before' => '<div class="col-md-6">',
	'after' => '</div>',
	'value' => 'true',
));

$form->add_element( 'text', 'wpe_categorylist', array(
	'label' => __( 'Prayer category on prayer request, separate each with a comma', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_categorylist'] ) and ! empty( $data['wpe_categorylist'] )) ? $data['wpe_categorylist'] : 'Deliverance,Generational Healing,Inner Healing,Physical Healing,Protection,Relationships,Salvation,Spiritual Healing',

	'placeholder' => __( 'Enter Prayer categories', WPE_TEXT_DOMAIN ),
));

/**/

$select_options = array(
'all' => __( 'all of them',WPE_TEXT_DOMAIN ),
'14' => __( 'only the last 14 days',WPE_TEXT_DOMAIN ),
'30' => __( 'only the last 30 days',WPE_TEXT_DOMAIN ),
'60' => __( 'only the last 60 days',WPE_TEXT_DOMAIN ),
'90' => __( 'only the last 90 days',WPE_TEXT_DOMAIN ),
'120' => __( 'only the last 120 days',WPE_TEXT_DOMAIN ),
);
$form->add_element( 'select', 'wpe_fetch_req_from', array(
	'label' => __( 'How many prayers/praises to display', WPE_TEXT_DOMAIN ),
	'current' => (isset( $data['wpe_fetch_req_from'] ) and ! empty( $data['wpe_fetch_req_from'] )) ? $data['wpe_fetch_req_from'] : '',

	'options' => $select_options,
	'class' => 'form-control',
	'before' => '<div class="col-md-4">',
	'after' => '</div>',
));

$form->add_element( 'textarea', 'wpe_thankyou', array(
	'label' => __( 'Thank you message', WPE_TEXT_DOMAIN ),
	'value' => (isset( $data['wpe_thankyou'] ) and ! empty( $data['wpe_thankyou'] )) ? $data['wpe_thankyou'] : '',
    'class' => 'form-control email-msg editor',
	'placeholder' => __( 'Thank you. Your form has been received.', WPE_TEXT_DOMAIN ),
    'textarea_name' => 'wpe_thankyou',
    'textarea_rows' => 15,
));

$form->add_element('submit','wpe_save_settings',array(
	'value' => __( 'Save Settings',WPE_TEXT_DOMAIN ),
	));
$form->add_element('hidden','operation',array(
	'value' => 'save',
	));
$form->add_element('hidden','page_options',array(
	'value' => 'wpe_api_key,wpe_scripts_place',
	));
$form->render();
//delete_user_meta(get_current_user_id(),'_word_count');
