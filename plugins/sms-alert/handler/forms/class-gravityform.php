<?php
/**
 * This file handles gravity form smsalert notification
 * This file handles gravity form smsalert notification
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

if (! is_plugin_active('gravityforms-master/gravityforms.php') 
    && ! is_plugin_active('gravityforms/gravityforms.php') 
) {
    return; 
}

GFForms::include_feed_addon_framework();

/**
 * This file handles gravity form smsalert notification
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 * GF_SMS_Alert class.
 */
class GF_SMS_Alert extends GFFeedAddOn
{

    /**
     * Add on version
     *
     * @var stirng
     */
    protected $_version = '2.0.0';

    /**
     * Add on min_gravityforms_version
     *
     * @var stirng
     */
    protected $_min_gravityforms_version = '1.8.20';

    /**
     * Add on gravity and smsalert slug
     *
     * @var stirng
     */
    protected $_slug = 'gravity-forms-sms-alert';

    /**
     * Add full path
     *
     * @var stirng
     */
    protected $_full_path = __FILE__;

    /**
     * Addon title
     *
     * @var stirng
     */
    protected $_title = 'SMS Alert';

    /**
     * Addon short title for addon.
     *
     * @var stirng
     */
    protected $_short_title = 'SMS Alert';

    /**
     * Check mutliple feed allowed or not.
     *
     * @var bool
     */
    protected $_multiple_feeds = false;

    /**
     * Instance for smsalert addon.
     *
     * @var object
     */
    private static $_instance = null;
    
    /**
     * ErrorMsg for smsalert form setting.
     *
     * @var string
     */
    private $_errorMsg = null;

    /**
     * Get instance for gravity form.
     *
     * @return object
     */
    public static function get_instance()
    {

        if (null === self::$_instance ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Set feed setting title.
     *
     * @return object
     */
    public function feed_settings_title()
    {
        return __('SMS ALERT', 'smsalert-gravity-forms');
    }

    /**
     * Set feed setting fields.
     *
     * @return array
     */
    public function feed_settings_fields()
    {   
        $options = array(
        array(
        'title'  => 'Customer SMS Settings',
        'fields' => array(
        array(
                    'label'   => 'Enable Mobile Verification',
                    'type'    => 'checkbox',
                    'name'    => 'smsalert_gForm_otp',
                    'class'   => 'mt-position-right',
                    'tooltip' => 'Enable otp',
                    'choices' => array(
                        array(
                           'label' => '',
                            'name'  => 'smsalert_gForm_otp'
                        )
                     )
                ),
        array(
        'label'             => 'Customer Numbers',
        'type'              => 'text',
        'name'              => 'smsalert_gForm_cstmer_nos',
        'tooltip'           => 'Enter Customer Numbers',
        'class'             => 'medium merge-tag-support mt-position-right',
        'feedback_callback' => array( $this, 'is_valid_setting'),
        ),
            array(
        'label'   => 'Customer Templates',
        'type'    => 'textarea',
        'name'    => 'smsalert_gForm_cstmer_text',
        'tooltip' => 'Enter your Customer SMS Content',
        'default_value' => SmsAlertMessages::showMessage('DEFAULT_CONTACT_FORM_CUSTOMER_MESSAGE'),
        'class'   => 'medium merge-tag-support mt-position-right',
        ),                    
        ),
        ),
        array(
        'title'  => 'Admin SMS Settings',
        'fields' => array(
        array(
        'label'             => 'Admin Numbers',
        'type'              => 'text',
        'name'              => 'smsalert_gForm_admin_nos',
        'tooltip'           => 'Enter admin Numbers',
        'class'             => 'medium merge-tag-support mt-position-right',
        'feedback_callback' => array( $this, 'is_valid_setting' ),
        ),
        array(
         'label'         => 'Admin Templates',
         'type'          => 'textarea',
         'name'          => 'smsalert_gForm_admin_text',
         'tooltip'       => 'Enter your admin SMS Content',
        'default_value' => SmsAlertMessages::showMessage(
            'DEFAULT_CONTACT_FORM_ADMIN_MESSAGE'
        ),
         'class'         => 'medium merge-tag-support mt-position-right',
                        
        ),
        ),
        ));
        if (is_plugin_active('gravityview/gravityview.php')) {
            $gf_form = new GF_Smsalert_Form();
            $statuses = $gf_form->getEnum();
            $cst_fields = $admin_fields = array();
            foreach ($statuses as $ks => $vs) {
                $cst_fields[] =  array(
                'label'   => $vs,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_cstmer_status_ '. strtolower($vs),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_cstmer_status_'. strtolower($vs),
                'choices' => array(
                array(
                 'label' => '',
                'name'  => 'smsalert_gform_cstmer_status_'. strtolower($vs)
                )
                )
                );        
                $cst_fields[] =    array(            
                'type'    => 'textarea',
                'name'    => 'smsalert_gform_cstmer_'. strtolower($vs) .'_text',
                'tooltip' => 'Enter your Customer SMS Content',
                'default_value' => SmsAlertMessages::showMessage(
                    'DEFAULT_GRAVITY_NEW_USER'
                ),
                'class'   => 'medium merge-tag-support mt-position-right',
                );
                $admin_fields[] =  array(
                'label'   => $vs,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_admin_status_'. strtolower($vs),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_admin_status_'. strtolower($vs),
                'choices' => array(
                array(
                'label' => '',
                'name'  => 'smsalert_gform_admin_status_'. strtolower($vs)
                )
                )
                );        
                $admin_fields[] =    array(            
                'type'    => 'textarea',
                'name'    => 'smsalert_gform_admin_'. strtolower($vs) .'_text',
                'tooltip' => 'Enter your Admin SMS Content',
                'default_value' => SmsAlertMessages::showMessage(
                    'DEFAULT_GRAVITY_NEW_ADMIN'
                ),
                'class'   => 'medium merge-tag-support mt-position-right',
                );                 
            }    
            
            
            $options[] =  array(
            'title'  => 'Customer notification when entry status change to',
            'fields' => $cst_fields, 
            );
            $options[] =  array(
            'title'  => 'Admin notification when entry status change to',
            'fields' => $admin_fields, 
            );
        }    
        
        $gf_form = new GF_Smsalert_Form();
        $gf_form_status = new GF_Smsalert_Form();
        $status =$gf_form_status->getStatuses();
        $cst_payment_fields = $admin_payment_fields = array();
        foreach ($status as $ks => $vs) {
            $cst_payment_fields[] =  array(
                'label'   => $vs,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_cstmer_status_ '. strtolower($vs),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_cstmer_status_'. strtolower($vs),
                'choices' => array(
                array(
                 'label' => '',
                'name'  => 'smsalert_gform_cstmer_status_'. strtolower($vs)
                )
                )
                );        
                $cst_payment_fields[] =    array(            
               'type'    => 'textarea',
                'name'    => 'smsalert_gform_cstmer_'. strtolower($vs) .'_text',
                'tooltip' => 'Enter your Customer SMS Content',
                'default_value' => SmsAlertMessages::showMessage('DEFAULT_GRAVITY_CST_SMS_STATUS_CHANGED')
                ,
                'class'   => 'medium merge-tag-support mt-position-right',
                );
                
                $admin_payment_fields[] =  array(
                'label'   => $vs,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_admin_status_'. strtolower($vs),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_status_'. strtolower($vs),
                'choices' => array(
                array(
                'label' => '',
                'name'  => 'smsalert_gform_admin_status_'. strtolower($vs)
                )
                )
                );        
                $admin_payment_fields[] =    array(            
                'type'    => 'textarea',
                'name'    => 'smsalert_gform_admin_'. strtolower($vs) .'_text',
                'tooltip' => 'Enter your Admin SMS Content',
                'default_value' => SmsAlertMessages::showMessage(
                    'DEFAULT_GRAVITY_ADMIN_SMS_STATUS_CHANGED'
                ),
                'class'   => 'medium merge-tag-support mt-position-right',
                );
                
        }
        $options[] =  array(
            'title'  => 'Customer Payment Notification',
            'fields' => $cst_payment_fields, 
            );
        $options[] =  array(
            'title'  => 'Admin Payment Notification',
            'fields' => $admin_payment_fields, 
            );
        if (is_plugin_active('gAppointments/index.php')) {    
            $gf_form = new GF_Smsalert_Form();
            $gf_booking_status = new GF_Smsalert_Form();
            $statuss =$gf_booking_status->getBookingStatuses();
            $cst_bookig_fields = $admin_booking_fields = array();
            foreach ($statuss as $kss => $vss) {             
                $cst_bookig_fields[] =  array(
                'label'   => $kss,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_cstmer_booking_status_ '. strtolower($vss),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_cstmer_booking_status_'. strtolower($vss),
                'choices' => array(
                array(
                 'label' => '',
                'name'  => 'smsalert_gform_cstmer_booking_status_'. strtolower($vss)
                )
                )
                );        
                $cst_bookig_fields[] =    array(            
                'type'    => 'textarea',
                'name'    => 'smsalert_gform_cstmer_booking_'. strtolower($vss) .'_text',
                'tooltip' => 'Enter your Customer SMS Content',
                'default_value' => SmsAlertMessages::showMessage('DEFAULT_GRAVITY_CUSTOMER_MESSAGE')
                ,
                'class'   => 'medium merge-tag-support mt-position-right',
                );
                
                $admin_booking_fields[] =  array(
                'label'   => $kss,
                'type'    => 'checkbox',
                'name'    => 'smsalert_gform_admin_booking_status_'. strtolower($vss),
                'class'   => 'mt-position-right',
                'tooltip' => 'smsalert_gform_admin_booking_status_'. strtolower($vss),
                'choices' => array(
                array(
                'label' => '',
                'name'  => 'smsalert_gform_admin_booking_status_'. strtolower($vss)
                )
                )
                );        
                $admin_booking_fields[] =    array(            
                'type'    => 'textarea',
                'name'    => 'smsalert_gform_admin_booking_'. strtolower($vss) .'_text',
                'tooltip' => 'Enter your Admin SMS Content',
                'default_value' => SmsAlertMessages::showMessage(
                    'DEFAULT_GRAVITY_ADMIN_MESSAGE'
                ),
                'class'   => 'medium merge-tag-support mt-position-right',
                );
                
            }
            $options[] =  array(
            'title'  => 'Customer booking Notification',
            'fields' => $cst_bookig_fields, 
            );
            $options[] =  array(
            'title'  => 'Admin booking Notification',
            'fields' => $admin_booking_fields, 
            );
        }
        
        return $options;
    }
    
    
    /**
     * Handle form submission at gravity smsalert setting.
     *
     * @param array $feed_id  form feed_id. 
     * @param array $form_id  form form_id.
     * @param array $settings form settings.
     *
     * @return void
     */
    public function save_feed_settings( $feed_id, $form_id, $settings ) 
    {
        if (empty($settings['smsalert_gForm_cstmer_nos']) 
            && !empty($settings['smsalert_gForm_cstmer_text'])
        ) {
            $this->_errorMsg = true;
            GFCommon::add_error_message(
                __(
                    "Please enter
			your customer number.", 'sms-alert'
                )
            );
            $result = false;
        } else if (!empty($settings['smsalert_gForm_otp']) 
            && empty($settings['smsalert_gForm_cstmer_nos'])
        ) {
            $this->_errorMsg = true;
            GFCommon::add_error_message(
                __(
                    "Please enter
			your customer number.", 'sms-alert'
                )
            );
            $result = false;
        } else if (empty($settings['smsalert_gForm_admin_nos']) 
            && !empty($settings['smsalert_gForm_admin_text'])
        ) {
            $this->_errorMsg = true;        
            GFCommon::add_error_message(
                __(
                    "Please enter
			your admin number.", 'sms-alert'
                )
            );
            $result = false;
        } else {
            parent::save_feed_settings($feed_id, $form_id, $settings);
            $result = true;
        }
        return $result;
    }
    
    /**
     * Handle form submission at gravity smsalert setting save error message.
     *
     * @param array $sections form sections. 
     *
     * @return void
     */
    public function get_save_error_message( $sections ) 
    {
        return !empty($this->_errorMsg) ? '' : esc_html__(
            'There
		was an error while saving your settings.', 'sms-alert'
        );
    }
    
    /**
     * Handle form submission and send message to customer and admin.
     *
     * @param array $entry form entry. 
     * @param array $form  form form.
     *
     * @return void
     */
    public static function do_gForm_processing( $entry, $form )
    {    
        $entry_id = $entry['id'];
        $message    = '';
        $cstmer_nos_pattern = '';
        $admin_nos  = '';
        $admin_msg  = '';
        $meta       = RGFormsModel::get_form_meta($entry['form_id']);       
        $feeds      = GFAPI::get_feeds(
            null, $entry['form_id'],
            'gravity-forms-sms-alert'
        );
        foreach ( $feeds as $feed ) {
            if (count($feed) > 0 && array_key_exists('meta', $feed) ) {
                $admin_msg          = $feed['meta']
                ['smsalert_gForm_admin_text'];
                $admin_nos          = $feed['meta']
                ['smsalert_gForm_admin_nos'];
                $cstmer_nos_pattern = $feed['meta']
                ['smsalert_gForm_cstmer_nos'];
                $message            = $feed['meta']
                ['smsalert_gForm_cstmer_text'];
            }
        }
        $cstmer_nos ='';
        foreach ( $meta['fields'] as $meta_field ) {            
            if (is_object($meta_field) ) {
                $field_id = $meta_field->id; 
                
                if (isset($entry[ $field_id ]) ) {
                    $label     = $meta_field->label;
                    $search    = '{' . $label . ':' . $field_id . '}';
                    $replace   = $entry[ $field_id ];
                    if ($cstmer_nos_pattern === $search ) {
                        $cstmer_nos = $replace;                        
                    }                    
                }            
            }            
        }
        
        
        
        
        if (! empty($cstmer_nos) && ! empty($message) ) {
            $gf_sms = new GF_Smsalert_Form();          
            $message = $gf_sms->parseSmsBody(
                $entry_id,
                $message
            );
            do_action('sa_send_sms', $cstmer_nos, $message);
        }
        if (! empty($admin_nos) && ! empty($admin_msg) ) {
            
            $gf_admin_sms = new GF_Smsalert_Form();          
            $admin_msg = $gf_admin_sms->parseSmsBody(
                $entry_id,
                $admin_msg
            );
            do_action('sa_send_sms', $admin_nos, $admin_msg);
        }
    }    
        
    
}
new GF_SMS_Alert();

add_action(
    'gform_after_submission', array( 'GF_SMS_Alert',
    'do_gForm_processing' ), 10, 2
); 




 /**
  * This file handles gravity form smsalert notification
  *
  * PHP version 5
  *
  * @category Handler
  * @package  SMSAlert
  * @author   SMS Alert <support@cozyvision.com>
  * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
  * @link     https://www.smsalert.co.in/
  * GF_Smsalert_Form class.
  */
class GF_Smsalert_Form extends FormInterface
{
    
    /**
     * Form Session Variable.
     *
     * @var stirng
     */
    private $form_session_var = FormSessionVars::GRAVITY_FORM;
    
    /**
     * Handle OTP form
     *
     * @return void
     */
     
    public $enum = array(
    '1' => 'approved',
    '2' => 'disapproved',
    '3' => 'unapproved',
    );
    
    public $payment_statuses = array(   
    'Paid'       => 'Paid',    
    'Failed'     => 'Failed',
    'Cancelled'  => 'Cancelled',
    'Pending'    => 'Pending',
    'Expired'    => 'Expired',
    );
    
    public $booking_statuses = array(   
    'Completed'         => 'Completed',    
    'Confirmed '        => 'Publish',
    'Pending Payment'  => 'Payment',
    'Pending'          => 'Pending',
    'Cancelled'        => 'Cancelled',
    );     
    
    
    /**
     * GetEnum
     *
     * @return void
     */
    public function getEnum()
    {
        return $this->enum;
    }
   
    /**
     * Get Statuses
     *
     * @return void
     */
    public function getStatuses()
    {
        return $this->payment_statuses;
    }
    
    /**
     * Get BookingStatuses
     *
     * @return void
     */
    public function getBookingStatuses()
    {
        return $this->booking_statuses;
    }
    /**
     * Handle OTP form
     *
     * @return void
     */
    public function handleForm()
    {
        add_filter(
            'gform_submit_button', array( $this, 'add_otp_btn' ), 10, 2
        );        
        add_action(
            'gform_preview_footer', array( $this, 'load_smsalert_modal_html' ), 10, 1
        );    
        add_filter(
            'gform_payment_statuses', array( $this, 'add_new_status' ), 10, 1
        ); 
        
            
        add_action(
            'gform_post_payment_action', array( $this, 'paymentStatus' ), 10, 2
        );
        
        
        if (is_plugin_active('gravityview/gravityview.php')) {
            foreach ($this->enum as $status) {
                add_action(
                    'gravityview/approve_entries/'.$status, array( $this,
                    'trigger_notifications' ), 10
                );
            } 
        }
        if (is_plugin_active('gAppointments/index.php')) {
            add_action('ga_new_appointment', array($this, 'sendNewAppoinmentNotification'), 10, 2);
			add_action( 'transition_post_status', array($this, 'sendAppoinmentNotification'), 10, 3 );
        }

        SAVerify::enqueue_otp_js_script();
    }
    
    /**
     * Add new status
     *
     * @param $payment_statuses payment_statuses.
     *
     * @return void
     */
    function add_new_status( $payment_statuses )
    {

        return $payment_statuses;
    }    
    
    
    /**
     * Process payment Status and send sms
     *
     * @param array $entry  entry.
     * @param array $action action.
     *
     * @return void
     */
    function paymentStatus($entry, $action)
    {
        $entry_id = $action['entry_id'];    
        $cst_message             = '';
        $cst_notification    = '';
        $admin_message      = '';
        $cstmer_nos_pattern  = '';             
        $admin_nos  = '';             
        $admin_notification = '';
        $meta       = RGFormsModel::get_form_meta($entry['form_id']);      
        $feeds      = GFAPI::get_feeds(
            null, $entry['form_id'],
            'gravity-forms-sms-alert'
        );
                 
        foreach ( $feeds as $feed ) {        
            if (count($feed) > 0 && array_key_exists('meta', $feed) ) {
                $status = strtolower($action['payment_status']);
                $cst_message              = $feed['meta']
                ['smsalert_gform_cstmer_'. $status .'_text'];
                $cst_notification         = $feed['meta']
                ['smsalert_gform_cstmer_status_'. $status];
                $admin_message          = $feed['meta']                
                ['smsalert_gform_admin_'. $status .'_text'];                
                $admin_notification     = $feed['meta']
                ['smsalert_gform_admin_status_'.$status];                
                $cstmer_nos_pattern             = $feed['meta']
                ['smsalert_gForm_cstmer_nos'];
                
                $admin_nos                      = $feed['meta']
                ['smsalert_gForm_admin_nos'];                
            }
        }        
        $cstmer_nos = "";
        foreach ( $meta['fields'] as $meta_field ) {
        
            if (is_object($meta_field) ) {
                $field_id = $meta_field->id; 
               
                if (isset($entry[ $field_id ]) ) {
                    $label     = $meta_field->label;
                    $search    = '{' . $label . ':' . $field_id . '}';
                    $replace   = $entry[ $field_id ];
                   
            
                    if ($cstmer_nos_pattern === $search ) {
                        $cstmer_nos = $replace;                        
                    }                    
                }            
            }            
        }        
        if (! empty($cstmer_nos) 
            && ! empty($cst_message) 
            && !empty($cst_notification)
        ) {
            $message = $this->parseSmsBody(
                $entry_id,
                $cst_message
            );
               do_action('sa_send_sms', $cstmer_nos, $message);
        }
        /* Admin  SMS Notification */        
        if (! empty($admin_nos) 
            && ! empty($admin_message) 
            && ! empty($admin_notification) 
        ) {
            
            $admin_msg = $this->parseSmsBody(
                $entry_id,
                $admin_message                
            );
            do_action('sa_send_sms', $admin_nos, $admin_msg);
        }
    }
    
    /**
     * Send NewAppoinment Notification sms
     *
     * @param array $postID      postID.
     * @param array $provider_id provider_id.
     *
     * @return void
     */
    public function sendNewAppoinmentNotification($postID, $provider_id)
    {
        $post = get_post($postID);
		if($post->post_type == 'ga_appointments') {			
			$entry_id    = get_post_meta($postID, "ga_appointment_gf_entry_id", true);		
			$this->sendSms($entry_id,$post);
		}
    } 

    /**
     * Send Appoinment Notification sms
     *
     * @param array $new_status new_status.
     * @param array $provider_id provider_id.
     * @param array $post post.
     *
     * @return void
     */
	public function sendAppoinmentNotification($new_status, $old_status, $post){
		if ( !is_admin() ) {
			return;
		}
		if ($post->post_type == 'ga_appointments') {
			$entry_id    = get_post_meta($post->ID, "ga_appointment_gf_entry_id", true);			
			$this->sendSms($entry_id,$post);				
		}
	}	
	
	 /**
     * Send  sms
     *
     * @param array $entry_id entry_id.
     * @param array $post post.
     *
     * @return void
     */
	public function sendSms($entry_id, $post) {
		
		$entry = GFAPI::get_entry($entry_id);		
		$cst_message         = '';
		$cst_notification    = '';
		$admin_message       = '';
		$cstmer_nos_pattern  = '';             
		$admin_nos           = '';             
		$admin_notification  = '';	
		$meta       = RGFormsModel::get_form_meta($entry['form_id']);
		$feeds      = GFAPI::get_feeds(null, $entry['form_id'],	'gravity-forms-sms-alert');			
		 foreach ( $feeds as $feed ) {        
			if (count($feed) > 0 && array_key_exists('meta', $feed) ) {
				$statuss = strtolower($post->post_status);
				$status = str_replace(' ', '_', $statuss);
				$cst_message              = $feed['meta']
				['smsalert_gform_cstmer_booking_'. $status .'_text'];
				$cst_notification         = $feed['meta']
				['smsalert_gform_cstmer_booking_status_'. $status];
				$admin_message          = $feed['meta']                
				['smsalert_gform_admin_booking_'. $status .'_text'];                
				$admin_notification     = $feed['meta']
				['smsalert_gform_admin_booking_status_'.$status];                
				$cstmer_nos_pattern             = $feed['meta']
				['smsalert_gForm_cstmer_nos'];					
				$admin_nos                      = $feed['meta']
				['smsalert_gForm_admin_nos'];                
			}
		}		
		$cstmer_nos = "";
		foreach ( $meta['fields'] as $meta_field ) {       
			if (is_object($meta_field) ) {
				$field_id = $meta_field->id;               
				if (isset($entry[ $field_id ]) ) {
					$label     = $meta_field->label;
					$search    = '{' . $label . ':' . $field_id . '}';
					$replace   = $entry[ $field_id ];                   
			
					if ($cstmer_nos_pattern === $search ) {
						$cstmer_nos = $replace;						
					}                    
				}            
			}            
		}		
		if (! empty($cstmer_nos) 
			&& ! empty($cst_message) 
			&& !empty($cst_notification)
		) {
			$message = $this->parseSmsBody(
				$entry_id,
				$cst_message,
				$post
			);				
			do_action('sa_send_sms', $cstmer_nos, $message);
		}
		/* Admin  SMS Notification */        
		if (! empty($admin_nos) 
			&& ! empty($admin_message) 
			&& ! empty($admin_notification) 
		) {
			
			$admin_msg = $this->parseSmsBody(
				$entry_id,
				$admin_message,
				$post                
			);
			do_action('sa_send_sms', $admin_nos, $admin_msg);
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
        return ( $islogged &&
        (is_plugin_active('gravityforms-master/gravityforms.php') ||
        is_plugin_active('gravityforms/gravityforms.php') )) ? true : false;
    }
    
    /**
     * Handle smsalert gravity shortcode.
     *
     * @param int $form_id form id.
     *
     * @return string
     */
    function load_smsalert_modal_html($form_id)
    {
        SAVerify::add_shortcode_popup_html();
    }
    
    /**
     * Process gravity form submission and send sms
     *
     * @param array $entry_id entry id.
     *
     * @return void
     */
    public function trigger_notifications( $entry_id = '')
    {
        $entry = GFAPI::get_entry($entry_id);
        $form_id = $entry['form_id'];    
        $cst_message             = '';
        $cst_notification    = '';
        $admin_message      = '';
        $cstmer_nos_pattern  = '';             
        $admin_nos  = '';             
        $admin_notification = '';        
        $meta       = RGFormsModel::get_form_meta($entry['form_id']);      
        $feeds      = GFAPI::get_feeds(
            null, $entry['form_id'],
            'gravity-forms-sms-alert'
        );        
        foreach ( $feeds as $feed ) {        
            if (count($feed) > 0 && array_key_exists('meta', $feed) ) {
                $status = $this->enum[$entry['is_approved']];
                
                $cst_message              = $feed['meta']
                ['smsalert_gform_cstmer_'. $status .'_text'];
                
                $cst_notification         = $feed['meta']
                ['smsalert_gform_status_'. $status];
                $admin_message          = $feed['meta']                
                ['smsalert_gform_admin_'. $status .'_text'];                
                $admin_notification     = $feed['meta']
                ['smsalert_gform_status_'.$status];                
                $cstmer_nos_pattern             = $feed['meta']
                ['smsalert_gForm_cstmer_nos']; 
                $admin_nos                      = $feed['meta']
                ['smsalert_gForm_admin_nos'];                
            }
        }         
        $cstmer_nos ='';
        
        foreach ( $meta['fields'] as $meta_field ) {            
            if (is_object($meta_field) ) {
                $field_id = $meta_field->id; 
                
                if (isset($entry[ $field_id ]) ) {
                    $label     = $meta_field->label;
                    $search    = '{' . $label . ':' . $field_id . '}';
                    $replace   = $entry[ $field_id ];
                    $cst_message   = str_replace(
                        $search,
                        $replace, $cst_message
                    );                     
                    $admin_message   = str_replace(
                        $search,
                        $replace, $admin_message
                    );
            
                    if ($cstmer_nos_pattern === $search ) {
                        $cstmer_nos = $replace;                        
                    }                    
                }            
            }            
        }     
        if (! empty($cstmer_nos) 
            && ! empty($cst_message) 
            && !empty($cst_notification)
        ) {
            $message = $this->parseSmsBody(
                $entry_id,
                $cst_message
            );
            do_action('sa_send_sms', $cstmer_nos, $message);
        }
        /* Admin  SMS Notification */        
        if (! empty($admin_nos) 
            && ! empty($admin_message) 
            && ! empty($admin_notification) 
        ) {
            
            $admin_msg = $this->parseSmsBody(
                $entry_id,
                $admin_message                
            );
            do_action('sa_send_sms', $admin_nos, $admin_msg);
        }      
    }    
    
    /**
     * Handle smsalert gravity shortcode.
     *
     * @param object $button get button.
     * @param object $form   get form array.
     *
     * @return string
     */     
    function add_otp_btn( $button, $form )
    {
        $form_id          = $form["fields"][0]->formId;
        $feeds            = GFAPI::get_feeds(
            null, $form_id,
            'gravity-forms-sms-alert'
        );
        if (!empty($feeds->errors)) {
            return $button;
        } 
        $phone_field     = !empty(
            $feeds[0]['meta']
            ['smsalert_gForm_cstmer_nos']
        )? $feeds[0]
        ['meta']['smsalert_gForm_cstmer_nos']:'';
        if (empty($phone_field) 
            || empty($feeds[0]['meta']['smsalert_gForm_otp'])
        ) {
            return $button; 
        }
        $phone_field_id  = preg_replace('/[^0-9]/', '', $phone_field);
        return $button .= do_shortcode(
            '[sa_verify id=""
		phone_selector="input_'.$phone_field_id.'"
		submit_selector= "#gform_submit_button_'.$form_id.'" ]'
        );
    }

    /**
     * Replace variables for sms contennt
     *
     * @param int    $entry_id entry_id.
     * @param string $content  sms content to be sent.
     * @param string $post     sms post to be sent.
     *
     * @return string
     */
    public function parseSmsBody( $entry_id = '', $content = null, $post = '')
    { 
        $search        = array();
        $replace       = array();
        $bookingstatuss = '';
        $entry = GFAPI::get_entry($entry_id);
        foreach ($entry as $key=>$value) {
            
            $search[]    = '{'. $key .'}';                
            $replace[]   = $value;
        }
        $bookingstatus = !empty($post) ? str_replace(' ', '_', strtolower($post->post_status)) : "";
        $status = !empty($entry['is_approved'])? $this->enum[$entry['is_approved']]:''; 
        $paymentstatus = strtolower($entry['payment_status']);        
        $form_id = $entry['form_id'];        
        $meta       = RGFormsModel::get_form_meta($entry['form_id']);
    
        foreach ($meta['fields'] as $field) {             
            if (is_object($field) ) {                
                $name = $field->label;                
                if (is_array($field['inputs'])) {
                    foreach ($field['inputs'] as $vss) {
                        $id          = $vss['id'];                        
                        $label       = $vss['label'];
                        if (!empty($entry[$id])) {
                            $search[]    = '{' . $name .' '.
                            '('. $label .')' . ':' . $id . '}';
                            $replace[]   = $entry[$id];                        
                        }                    
                    }
                } else {
                    $id =$field->id;
                    $label = $field->label;                    
                    $search[]    = '{' . $label . ':' . $id . '}';                
                    $replace[]   = $entry[$id];
                } 
            }
        }  
       
        $replace[]   = $status;
        $replace[]   = $paymentstatus;        
        $replace[]   = $bookingstatus;        
        $replace[]   = $entry_id;        
        $search[]    = '[user_status]';
        $search[]    = '{payment_status}';
        $search[]    = '{booking_status}';
        $search[]    = '{entry_id}';
        $content     = str_replace($search, $replace, $content);        
        return $content;  
    
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
    public function handle_failed_verification($user_login,$user_email,$phone_number )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (! empty($_REQUEST['option'])
            && sanitize_text_field(
                wp_unslash($_REQUEST['option'])
            ) ===        'smsalert-validate-otp-form' 
        ) {
            wp_send_json(
                SmsAlertUtility::_create_json_response(
                    SmsAlertMessages::showMessage(
                        'INVALID_OTP'
                    ), 'error'
                )
            );
            exit();
        } else {
            $_SESSION[ $this->form_session_var ] = 'verification_failed';
        }
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
    public function handle_post_verification( $redirect_to,$user_login,$user_email,$password,$phone_number,$extra_data )
    {
        SmsAlertUtility::checkSession();
        if (! isset($_SESSION[ $this->form_session_var ]) ) {
            return;
        }
        if (! empty($_REQUEST['option'])  
            && sanitize_text_field(wp_unslash($_REQUEST['option'])) === 'smsalert-validate-otp-form' 
        ) {
            wp_send_json(
                SmsAlertUtility::_create_json_response(
                    'OTP
				Validated Successfully.', 'success'
                )
            );
            exit();
        } else {
            $_SESSION[ $this->form_session_var ] = 'validated';
        }
    }
    
    /**
     * Check current form submission is ajax or not
     *
     * @param bool $is_ajax bool value for form type.
     *
     * @return bool
     */
    public function is_ajax_form_in_play($is_ajax )
    {
        SmsAlertUtility::checkSession();
        return isset($_SESSION[ $this->form_session_var ]) ? true : $is_ajax;
    }

    /**
     * Clear otp session variable
     *
     * @return void
     */
    public function unsetOTPSessionVariables()
    {
        unset($_SESSION[ $this->form_session_var ]);
    }
    
    /**
     * Handle form for WordPress backend
     *
     * @return void
     */
    public function handleFormOptions()
    {
    }
}
 new GF_Smsalert_Form();