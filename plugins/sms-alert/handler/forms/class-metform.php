<?php
/**
 * This file handles Metform via sms notification
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! defined('ABSPATH') ) {
    exit;
}

if (! is_plugin_active('metform/metform.php')) {
    
    return; 
}
    
/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * SA_Metform class.
 */
class SA_Metform extends FormInterface
{

    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_action(
            'metform_after_store_form_data', array( $this,
            'metformSubmissionComplete' ), 10, 4
        );
        add_action(
            'mf_form_settings_tab', array( $this, 
            'addSmsalertSetting' ), 10
        );
        add_action(
            'mf_form_settings_tab_content', array( $this, 
            'smsalertSettingContent' ), 10
        );        
    }
        
    /**
     * Add SMS Alert setting tab 
     *
     * @return void
     */
    public function addSmsalertSetting()
    {
        echo'<li role="presentation">
		        <a href="#mf-smsalert" aria-controls="smsalert"
				role="tab" data-toggle="tab" 
				class="top">SMS Alert</a>
            </li>'; 
    }
    
    /**
     * Add SMS Alert content tab
     *    
     * @return void
     */
    public function smsalertSettingContent()
    {
        echo '<a href="https://kb.smsalert.co.in/knowledgebase/metform-sms-integration/" target="_blank" class="btn-outline"><span class="dashicons dashicons-format-aside"></span> Documentation</a><br><br>';        
        echo'<div role="tabpanel" class="attr-tab-pane" id="mf-smsalert">
				<div class="attr-modal-body" id="metform_form_modal_body">
                    <div class="mf-input-group">
							<label class="attr-input-label"  >
								<input type="checkbox" value="1"
								id="mf_sms_status" name="mf_sms_status"
								class="mf-admin-control-input
								mf-form-modalinput-sms mf_sms_status">
								<span>SMSAlert Notification:</span>
							</label> 
                    </div>
                    <div class="mf-input-group mf-sms" style="">						
						<div>						
							<div class="mf-input-group">
								<label class="attr-input-label">
									<input type="checkbox" value="1"
									id="mf_sms_status" name="mf_sms_user_status" 
									class="mf-admin-control-input
									mf-form-modalinput-sms-user">
									<span>Customer Notification:</span>
								</label> 
							</div>
							<div class="mf-input-group mf-sms-user" style="display: none;">
								<textarea name="mf_sms_user_body"
								id="mf_sms_user_body" 
								class ="attr-active  mf-sms-user-body mf_sms_user_body" 
								style="width: 100% !important">
								</textarea>
								 <div class="mf-input-group">
									<span class="mf-input-help">Enter 
									Token as same as field name with
									square brackets eg [billing_phone].</span>
								</div>
							</div>
						</div>
						<div>					
							<div class="mf-input-group">
								<label class="attr-input-label">
									<input type="checkbox" value="1"
									name="mf_sms_admin_status" 
									id="mf_sms_admin_status"
									class="mf-admin-control-input
									mf-form-modalinput-sms-admin">
									<span>Admin Notification:</span>
								</label> 
							</div>
							<div class="mf-input-group mf-sms-admin" style="display: none;">
								<textarea name="mf_sms_admin_body" id="mf_sms_admin_body"
								class ="attr-active mf-form-modalinput-sms-admin 
								mf-sms-admin-body mf_sms_admin_body" 
								style="width: 100% !important">
								</textarea> 
								<div class="mf-input-group">
									<span class="mf-input-help">
									Enter Token as same as field name
									with square brackets eg [billing_phone].</span>
							   </div>
						    </div>						
						</div>			
				    </div>
				</div>
			</div>';            
    }

    /**
     * Process metform submission and send sms
     *
     * @param array $form_id       form id.
     * @param array $form_data     form data.
     * @param int   $form_settings form settings.
     * @param int   $attributes    attributes.
     *
     * @return void
     */
    public function metformSubmissionComplete($form_id,$form_data,$form_settings,$attributes)
    {
        $smsalert_notification   = isset($form_settings['mf_sms_status']);
        if (! empty($smsalert_notification)) {            
            $phone_field         = $form_data['billing_phone'] ;        
            $buyer_sms_content   = $form_settings['mf_sms_user_body'];
            $buyer_sms_notify    = isset($form_settings['mf_sms_user_status']);
            $admin_phone_number  = smsalert_get_option(
                'sms_admin_phone',
                'smsalert_message', ''
            );
            $admin_phone_number  = str_replace(
                'post_author', '',
                $admin_phone_number
            );
            $admin_sms_content   = $form_settings['mf_sms_admin_body'];            
            $admin_sms_notify    = isset($form_settings['mf_sms_admin_status']);
            
            if (! empty($buyer_sms_content) && ! empty($buyer_sms_notify)) {
                do_action(
                    'sa_send_sms', $phone_field, 
                    self::parseSmsContent($buyer_sms_content, $form_data)
                );
            }            
            if (! empty($admin_phone_number) && !empty($admin_sms_notify) ) {
                do_action(
                    'sa_send_sms', $admin_phone_number,
                    self::parseSmsContent($admin_sms_content, $form_data)
                );
            }
        }        
    }

    /**
     * Check your otp setting is enabled or not.
     *
     * @return bool
     */
    public static function isFormEnabled()
    {
        $user_authorize = new smsalert_Setting_Options();
        $islogged       = $user_authorize->is_user_authorised();
        return (is_plugin_active('metform/metform.php')
        && $islogged ) ? true : false;
    }

    /**
     * Handle after failed verification
     *
     * @param object $user_login   users object.
     * @param string $user_email   user email.
     * @param string $phone_number phone number.
     *
     * @return void
     */
    public function handle_failed_verification($user_login,$user_email,$phone_number)
    {
        
    }

    /**
     * Handle after post verification
     *
     * @param string $redirect_to  redirect url.
     * @param object $user_login   user object.
     * @param string $user_email   user email.
     * @param string $password     user password.
     * @param string $phone_number phone number.
     * @param string $extra_data   extra hidden fields.
     *
     * @return void
     */
    public function handle_post_verification( $redirect_to, $user_login, $user_email, $password, $phone_number, $extra_data )
    {        
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables() 
    {
    }

    /**
     * Check current form submission is ajax or not
     *
     * @param bool $is_ajax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play( $is_ajax )
    {
        return $is_ajax;
    }

    /**
     * Replace variables for sms contennt
     *
     * @param string $content   sms content to be sent.
     * @param array  $formdatas values of varibles.
     *
     * @return string
     */
    public static function parseSmsContent($content = null, $formdatas = array())
    {
        $datas = array();
        foreach ( $formdatas as $key => $data ) {
            if (is_array($data) ) {
                foreach ( $data as $k => $v ) {
                    $datas[ '[' . $k . ']' ] = $v;
                }
            } else {
                $datas[ '[' . $key . ']' ] = $data;
            }
        }
        
        $find    = array_keys($datas);
        $replace = array_values($datas);
        $content = str_replace($find, $replace, $content); 
        return $content;
    }

    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
        if (is_plugin_active('metform/metform.php') ) {        
        }
    }

}
new SA_Metform();
