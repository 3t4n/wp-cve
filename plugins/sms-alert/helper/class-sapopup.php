<?php
/**
 * Emementer Widget helper.
 *
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 */

if (! is_plugin_active('elementor/elementor.php')) {
    return; 
}


use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography as Scheme_Typography;
use Elementor\Group_Control_Background;
use Elementor\Core\Schemes\Color as Scheme_Color;

use Elementor\Plugin as Elementor;

/**
 * PHP version 5
 *
 * @category Handler
 * @package  SMSAlert
 * @author   SMS Alert <support@cozyvision.com>
 * @license  URI: http://www.gnu.org/licenses/gpl-2.0.html
 * @link     https://www.smsalert.co.in/
 *
 * SAPopup class
 */
class SAPopup
{
    private $app = null;
    
    /**
     * Construct function
     *
     * @param $app app.
     *
     * @return array
     */
    public function __construct($app)
    {        
        $this->app = $app;
        add_action('elementor/widgets/register', [$this, 'initWidget']);        
        add_action('admin_init', array( $this, 'addThemeCaps'));
        add_action('elementor/document/before_save', array( $this, 'checkSmsalertWidget' ), 100, 2);
        add_action('elementor/document/before_save', array( $this, 'checkSmsalertExitIntentWidget' ), 100, 2);
        add_action('elementor/document/before_save', array( $this, 'checkSmsalertShareCartWidget' ), 100, 2);
        add_action('elementor/document/before_save', array( $this, 'checkSmsalertNotifyMeWidget' ), 100, 2);
        $this->routeData();        
        $name = 'sms-alert';
        $args = array(
        'public'    => true,
        'show_in_menu'     => false,
        'label' => esc_html__('SMSAlert', 'sms-alert'),
        'supports' => array('title', 'editor', 'elementor', 'permalink'),
        'capability_type' => array('sms-alert','sms-alert'),
        'capabilities' => array(
        'edit_post'          => 'edit_sms_alert', 
        'edit_posts' => 'edit_sms_alert',
        'read_post' => 'read_sms_alert',
        ),
        );
        add_action(
            'init', function () use ($name,$args) {
                register_post_type($name, $args);
                flush_rewrite_rules();
            }
        );        
    }
    
    /**
     * Add theme caps.
     *
     * @return void
     */
    function addThemeCaps()
    {
        // gets the administrator role
        $admins = get_role('administrator');
        $admins->add_cap('edit_sms_alert'); 
        $admins->add_cap('edit_sms_alerts'); 
        $admins->add_cap('read_sms_alert'); 
    }
    
    /**
     * Init widgets function
     *
     * @return array
     */
    public function initWidget()
    {
        $widgets_manager = Elementor::instance()->widgets_manager;
        if (file_exists(plugin_dir_path(__DIR__) . 'helper/class-sapopupwidget.php')) {            
            include_once plugin_dir_path(__DIR__) . 'helper/class-sapopupwidget.php';
            $widgets_manager->register(new SAPopupWidget());
        }
        if (file_exists(plugin_dir_path(__DIR__) . 'helper/class-saexitintentwidget.php')) {      
            include_once plugin_dir_path(__DIR__) . 'helper/class-saexitintentwidget.php';
            $widgets_manager->register(new SAExitIntentWidget());
        } 
        if (file_exists(plugin_dir_path(__DIR__) . 'helper/class-sasharecartwidget.php')) {      
            include_once plugin_dir_path(__DIR__) . 'helper/class-sasharecartwidget.php';
            $widgets_manager->register(new SAShareCartWidget());
        }
        if (file_exists(plugin_dir_path(__DIR__) . 'helper/class-notifymewidget.php')) {      
            include_once plugin_dir_path(__DIR__) . 'helper/class-notifymewidget.php';
            $widgets_manager->register(new SANotifyMeWidget());
        }         
    }

     /**
      * RouteData function
      *
      * @return array
      */
    public function routeData()
    {        
        if (!empty($_GET['post_name'])) {        
            switch ($_GET['post_name']) {
            case "modal_style":
                    $otp_template_style = smsalert_get_option('otp_template_style', 'smsalert_general', 'popup-4');                
                $post = get_page_by_path('modal_style', OBJECT, 'sms-alert');
                if (!empty($post) ) {
                    $builder_form_id = $post->ID;
                } else {
                    $template_content = [
                    [
                    "id" => "a819417", 
                    "elType" => "section", 
                    "settings" => [
                    ], 
                    "elements" => [
                                               [
                                                  "id" => "cbffca4", 
                                                  "elType" => "column", 
                                                  "settings" => [
                                                     "_column_size" => 100, 
                                                     "_inline_size" => null 
                                                  ], 
                                                  "elements" => [
                                                        [
                                                           "id" => "df1545a", 
                                                           "elType" => "widget", 
                                                           "settings" => [
                                                              "form_list" => $otp_template_style, 
                                                              "sa_ele_f_mobile_lbl" => SmsAlertMessages::showMessage('OTP_SENT_PHONE'), 
                                                              "sa_ele_f_mobile_botton" => SmsAlertMessages::showMessage('VALIDATE_OTP'),
                                                              "sa_ele_f_otp_resend"=> esc_html__('Didn\'t receive the code?', 'sms-alert'),
                                                              "sa_ele_f_resend_btn"=> esc_html__('Resend', 'sms-alert'),
                                                              "sa_otp_re_send_timer"=>    '15',
                                                              "max_otp_resend_allowed"=>    '4',
                                                              "sa_edit_mobile_number"=>    'Edit Number',
                                                              "auto_validate"=>    'off',
															  "sa_edit_mobile_meaasege"=>    'Please enter mobile number to send OTP'
                                                           ], 
                                                           "elements" => [
                                                              ], 
                                                           "widgetType" => "smsalert-modal-widget" 
                                                        ] 
                                                     ], 
                                                  "isInner" => false 
                                               ] 
                    ], 
                    "isInner" => false 
                    ] 
                    ];  //serialisedata 
                    $builder_form_id = $this->create_form('modal_style', $template_content, $data = []);
                }            
                $this->get_editor($builder_form_id);
                
                break; 
            case "exitintent_style":                    
                $post = get_page_by_path('exitintent_style', OBJECT, 'sms-alert');
                if (!empty($post) ) {                
                    $builder_form_id = $post->ID;
                } else {
                    $template_content = [
                     [
                    "id" => "a819417", 
                    "elType" => "section", 
                    "settings" => [
                                        ], 
                                        "elements" => [
                                              [
                                                 "id" => "cbffca4", 
                                                 "elType" => "column", 
                                                 "settings" => [
                                                    "_column_size" => 100, 
                                                    "_inline_size" => null 
                                                 ], 
                                                 "elements" => [
                                                       [
                                                          "id" => "df1545a", 
                                                          "elType" => "widget", 
                                                          "settings" => [
                                                               "sa_ele_f_mobile_title"=> esc_html__('You were not leaving your cart just like that, right?', 'sms-alert'),
                                                               "sa_ele_f_mobile_description"=>esc_html__('Just enter your mobile number below to save your shopping cart for later. And, who knows, maybe we will even send you a sweet discount code :)', 'sms-alert'),
                                                               "sa_ele_f_mobile_label"=>esc_html__('Your Mobile No:', 'sms-alert'),    
                                                               "sa_submit_button"=> esc_html__('Save cart', 'sms-alert')
                                                           ], 
                                                          "elements" => [
                                                             ], 
                                                          "widgetType" => "smsalert-exitintent-widget" 
                                                       ] 
                                                    ], 
                                                 "isInner" => false 
                                              ] 
                                           ], 
                                        "isInner" => false 
                     ]
                    ];  //serialisedata  
                    $builder_form_id = $this->create_form('exitintent_style', $template_content, $data = []);
                }            
                $this->get_editor($builder_form_id);                
                break;
            case "sharecart_style":                        
                    $post = get_page_by_path('sharecart_style', OBJECT, 'sms-alert');
                if (!empty($post) ) {                
                    $builder_form_id = $post->ID;
                } else {
                    $template_content = [
                    [
                    "id" => "a819417", 
                    "elType" => "section", 
                    "settings" => [
                    ], 
                    "elements" => [
                    [
                    "id" => "cbffca4", 
                    "elType" => "column", 
                    "settings" => [
                    "_column_size" => 100, 
                    "_inline_size" => null 
                    ], 
                    "elements" => [
                    [
                    "id" => "df1545a", 
                    "elType" => "widget", 
                    "settings" => ["sa_ele_f_sharecart_title"=> esc_html__('Share cart', 'sms-alert'),
                    "sa_ele_f_user_placehoder"=>esc_html__('Your Name*', 'sms-alert'),
                    "sa_ele_f_user_phone_placeholder"=>esc_html__('Your Mobile No*', 'sms-alert'),    
                    "sa_ele_f_frnd_placeholder"=> esc_html__('Friend Name*', 'sms-alert'),   
                    "sa_ele_f_frnd_phone_placeholder"=> esc_html__('Friend Mobile No*', 'sms-alert'),   
                    "sa_submit_button"=> esc_html__('Share Cart', 'sms-alert')  ], 
                    "elements" => [
                    ], 
                    "widgetType" => "smsalert-sharecart-widget" 
                    ] 
                    ], 
                    "isInner" => false 
                    ] 
                    ], 
                    "isInner" => false 
                    ]
                    ];  //serialisedata  
                    $builder_form_id = $this->create_form('sharecart_style', $template_content, $data = []);
                }
                $this->get_editor($builder_form_id);
                break;
            case "notifyme_style":                        
                     $post = get_page_by_path('notifyme_style', OBJECT, 'sms-alert');
                if (!empty($post) ) {                
                    $builder_form_id = $post->ID;
                } else {
                    $template_content = [
                    [
                    "id" => "a819417", 
                    "elType" => "section", 
                    "settings" => [
                    ], 
                    "elements" => [
                    [
                    "id" => "cbffca4", 
                    "elType" => "column", 
                    "settings" => [
                    "_column_size" => 100, 
                    "_inline_size" => null 
                    ], 
                    "elements" => [
                    [
                    "id" => "df1545a", 
                    "elType" => "widget", 
                    "settings" => [
                    "sa_ele_f_notifyme_title"=> esc_html__('Notify Me when back in stock', 'sms-alert'),
                    "sa_ele_f_notifyme_placehoder"=>esc_html__('Enter Number Here', 'sms-alert'),
                    "sa_notifyme_button"=>esc_html__('Notify Me', 'sms-alert')
                        ], 
                        "elements" => [
                        ], 
                        "widgetType" => "smsalert-notifyme-widget" 
                    ] 
                    ], 
                    "isInner" => false 
                    ] 
                    ], 
                    "isInner" => false 
                    ]
                    ];  //serialisedata  
                    $builder_form_id = $this->create_form('notifyme_style', $template_content, $data = []);
                }            
                     $this->get_editor($builder_form_id);
                break;
            }
        }            
    }
    
    /**
     * Create form function
     *
     * @param string $title            title.
     * @param string $template_content template_content.
     * @param string $data             data.
     *
     * @return void
     */
    public function create_form($title,$template_content, $data = [])
    {        
        $user_id = get_current_user_id();        
        $defaults = array(
            'post_author'  => $user_id,
            'post_content' => '',
            'post_title'   => $title,
            'post_status'  => 'publish',
            'post_type'    => 'sms-alert',
            'post_name'    => $title,
        );        
        $builder_form_id = wp_insert_post($defaults);
        $default_settings = array();
        $default_settings['form_title'] = $defaults['post_title'];        
        if (isset($data['form_type']) && !empty($data['form_type'])) {
            $default_settings['form_type'] = $data['form_type'];
            // Unset form type from $data array
            unset($data['form_type']);
        }
        update_post_meta($builder_form_id, '_wp_page_template', 'elementor_header_footer');
        update_post_meta($builder_form_id, '_elementor_edit_mode', 'ElementorWidget');
        if ($template_content != null) {
            update_post_meta($builder_form_id, '_elementor_data', json_encode($template_content));
        }
        return $builder_form_id;
    }
    
    
    /**
     * CheckSmsalertWidget
     *
     * @param $obj   obj
     * @param $datas datas
     *
     * @return void
     */
    public function checkSmsalertWidget($obj, $datas)
    { 
        $post_title = !empty($datas['settings']['post_title'])?$datas['settings']['post_title']:'';
        if ($post_title == "modal_style") {
            $smsalert_widget_added = 0;
            $sa_otp_re_send_timer = '';
            $max_otp_resend_allowed = '';
            if (!empty($datas['elements'])) {          
                foreach ( $datas['elements'] as $data ) {
                    if (array_key_exists('elements', $data) ) {
                        foreach ( $data['elements'] as $element ) {
							$widgetType = !empty($element['widgetType'])?$element['widgetType']:'';
                            if (array_key_exists('elements', $element) && $widgetType == '') {
                                foreach ( $element['elements'] as $setting ) {
                                    $widgetType = !empty($setting['widgetType'])?$setting['widgetType']:'';
                                }
                            }
							if (!empty($widgetType) && $widgetType == 'smsalert-modal-widget') {
								$smsalert_widget_added++;
								$sa_otp_re_send_timer = !empty($setting['settings']['sa_otp_re_send_timer'])?$setting['settings']['sa_otp_re_send_timer']:''; 
								$max_otp_resend_allowed = !empty($setting['settings']['max_otp_resend_allowed'])?$setting['settings']['max_otp_resend_allowed']:'';
                            }
                        }
                    }
                }          
            }
            if ($smsalert_widget_added==1) {
                if (empty($sa_otp_re_send_timer)) {
                    wp_send_json_error([ 'statusText' => esc_html__("OTP Re-send Timer field can't be empty.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
                } else if (empty($max_otp_resend_allowed)) {
                    wp_send_json_error([ 'statusText' => esc_html__("Max OTP Re-send Allowed field can't be empty.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
                }
            } else if ($smsalert_widget_added==0) {
                wp_send_json_error([ 'statusText' => esc_html__('Please add smsalert modal widget.', 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            } else if ($smsalert_widget_added > 1) {
                wp_send_json_error([ 'statusText' => esc_html__("You can't add multiple smsalert modal widget.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            }
        }
    }
    
    /**
     * Get editor function
     *
     * @param string $builder_form_id builder_form_id.
     * @param string $post_type       post_type.
     *
     * @return void
     */
    public function get_editor( $builder_form_id )
    {        
        $url = get_admin_url() . 'post.php?post='.$builder_form_id.'&action=elementor';
        wp_safe_redirect($url);
         exit;        
    }   
    
    /**
     * CheckSmsalertExitIntentWidget
     *
     * @param $obj   obj
     * @param $datas datas
     *
     * @return void
     */
    public function checkSmsalertExitIntentWidget($obj, $datas)
    { 
        $post_title = !empty($datas['settings']['post_title'])?$datas['settings']['post_title']:'';
        if ($post_title == "exitintent_style") {
            $smsalert_exitintent_widget_added = 0;
            if (!empty($datas['elements'])) {          
                foreach ( $datas['elements'] as $data ) {
                    if (array_key_exists('elements', $data) ) {
                        foreach ( $data['elements'] as $element ) {
							$widgetType = !empty($element['widgetType'])?$element['widgetType']:'';
                            if (array_key_exists('elements', $element) && $widgetType == '') {
                                foreach ( $element['elements'] as $setting ) {
                                    $widgetType = !empty($setting['widgetType'])?$setting['widgetType']:'';
                                }
                            }
							if (!empty($widgetType) && $widgetType == 'smsalert-exitintent-widget') {
                               $smsalert_exitintent_widget_added++;
                            }
                        }
                    }
                }          
            }
            if ($smsalert_exitintent_widget_added==0) {
                wp_send_json_error([ 'statusText' => esc_html__('Please add smsalert exit intent widget.', 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            } else if ($smsalert_exitintent_widget_added > 1) {
                wp_send_json_error([ 'statusText' => esc_html__("You can't add multiple smsalert exit intent widget.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            }
        }
    }
    
    /**
     * CheckSmsalertShareCartWidget
     *
     * @param $obj   obj
     * @param $datas datas
     *
     * @return void
     */
    public function checkSmsalertShareCartWidget($obj, $datas)
    { 
        $post_title = !empty($datas['settings']['post_title'])?$datas['settings']['post_title']:'';
        if ($post_title == "sharecart_style") {
            $smsalert_sharecart_widget_added = 0;
            if (!empty($datas['elements'])) {          
                foreach ( $datas['elements'] as $data ) {
                    if (array_key_exists('elements', $data) ) {
                        foreach ( $data['elements'] as $element ) {
							$widgetType = !empty($element['widgetType'])?$element['widgetType']:'';
                            if (array_key_exists('elements', $element) && $widgetType == '') {
                                foreach ( $element['elements'] as $setting ) {
                                    $widgetType = !empty($setting['widgetType'])?$setting['widgetType']:'';
                                }
                            }
							if (!empty($widgetType) && $widgetType == 'smsalert-sharecart-widget') {
                                $smsalert_sharecart_widget_added++;
                            }
                        }
                    }
                }          
            }
            if ($smsalert_sharecart_widget_added==0) {
                wp_send_json_error([ 'statusText' => esc_html__('Please add smsalert share cart widget.', 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            } else if ($smsalert_sharecart_widget_added > 1) {
                wp_send_json_error([ 'statusText' => esc_html__("You can't add multiple smsalert share cart widget.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            }
        }
    }
    /**
     * CheckSmsalertNotifyMeWidget
     *
     * @param $obj   obj
     * @param $datas datas
     *
     * @return void
     */
    public function checkSmsalertNotifyMeWidget($obj, $datas)
    { 
        $post_title = !empty($datas['settings']['post_title'])?$datas['settings']['post_title']:'';
        if ($post_title == "notifyme_style") {
            $smsalert_notifyme_widget_added = 0;
            if (!empty($datas['elements'])) {          
                foreach ( $datas['elements'] as $data ) {
                    if (array_key_exists('elements', $data) ) {
                        foreach ( $data['elements'] as $element ) {
							$widgetType = !empty($element['widgetType'])?$element['widgetType']:'';
                            if (array_key_exists('elements', $element) && $widgetType == '') {
                                foreach ( $element['elements'] as $setting ) {
                                    $widgetType = !empty($setting['widgetType'])?$setting['widgetType']:'';
                                }
                            }
							if (!empty($widgetType) && $widgetType == 'smsalert-notifyme-widget') {
                                $smsalert_notifyme_widget_added++;
                            }
                        }
                    }
                }          
            }
            if ($smsalert_notifyme_widget_added==0) {
                wp_send_json_error([ 'statusText' => esc_html__('Please add smsalert Notify me widget.', 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            } else if ($smsalert_notifyme_widget_added > 1) {
                wp_send_json_error([ 'statusText' => esc_html__("You can't add multiple smsalert Notifyme widget.", 'sms-alert'),'readyState'=>4,'status'=>500 ]);
            }
        }
    }
    
    /**
     * Get getModelStyle
     *
     * @param string $callback callback.    
     *
     * @return void
     */
    public static function getModelStyle($callback=null)
    {
		$number_label = "";
		$edit_phone_form ='';
		$text ='';
		$otp_in_popup = smsalert_get_option('otp_in_popup', 'smsalert_general', 'on');
		$close_class = 'close';
		if('on' !== $otp_in_popup)
		{
			$close_class = 'back';
		}
        $otp_length             = esc_attr(SmsAlertUtility::get_otp_length());
        $sa_label        = ( ! empty($callback['sa_label']) ) ? $callback['sa_label'] :SmsAlertMessages::showMessage('OTP_SENT_PHONE');
        $placeholder     = ( ! empty($callback['placeholder']) ) ? $callback['placeholder'] : '';
        $edit_phone_label     = ( ! empty($callback['edit_phone_label']) ) ? $callback['edit_phone_label'] : esc_html__('Edit Number', 'sms-alert');
		$sa_mobile_meaasege     = ( ! empty($callback['sa_mobile_meaasege']) ) ? $callback['sa_mobile_meaasege'] : esc_html__('Please Enter Mobile Number To Send OTP', 'sms-alert');
        $otp_template_style     = ( ! empty($callback['otp_template_style']) ) ? $callback['otp_template_style'] : 'popup-4';
        $digit_class = ($otp_template_style!='popup-1' && $otp_template_style!='popup-4') ?(($otp_template_style=='popup-3')?'digit-group popup-3':'digit-group'):'';
        $hide_class = (($otp_template_style=='popup-1') || ($otp_template_style=='popup-4'))?'hide':'';
        $sa_button       = (! empty($callback['sa_button']) ) ? $callback['sa_button'] :SmsAlertMessages::showMessage('VALIDATE_OTP');
        $sa_resend_otp   = ( ! empty($callback['sa_resend_otp']) ) ? $callback['sa_resend_otp'] :'Didn t receive the code?';
        $sa_resend_btns  = ( ! empty($callback['sa_resend_btns']) ) ? $callback['sa_resend_btns'] :'Resend';
        if($otp_template_style == 'popup-4'){  
			$number_label ='<div class="edit-user-phone"><a class="saeditphone" name="saeditphone"><span class="sa-edit-icon" title="'.$edit_phone_label.'"><svg class="edit-phone bi bi-pencil-fill" fill="currentColor" height="24" viewBox="0 0 16 16" width="16" xmlns="http://www.w3.org/2000/svg"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"></path></svg></span><span>'.$edit_phone_label.'</span><a></div>'; 
			$edit_phone_form = '<div class="sa-edit-phone"><div class="saeditmessage sa-hide">'.$sa_mobile_meaasege.'</div><form></form></div>';			
		}                
        $content = '<div class="modal-content">
		<div class="'.$close_class.'"><span></span></div><div class="modal-body"><div style="margin:1.7em 1.5em;">'.$edit_phone_form.'<div style="position:relative" class="sa-message">'.esc_attr($sa_label).'</span></div>'.$number_label.'</div>		
		<div class="smsalert_validate_field '.esc_attr($digit_class).'" style="margin:1.5em">       
		<input type="number" class="otp-number '.esc_attr($hide_class).'" id="digit-1" name="digit-1" oninput="saGroup(this)" onkeyup="tabChange(1,this)" data-next="digit-2" style="margin-right: 5px!important;" data-max="1"  autocomplete="off"/>';
        
        $j = $otp_length - 1;
        $input = '';
        for ( $i = 1; $i < $otp_length; $i++ ) {
            $input.= '<input type="number" class="otp-number '.esc_attr($hide_class).'" id="digit-'.esc_attr($i + 1).'" name="digit-'.esc_attr($i + 1).'" oninput="saGroup(this)" onkeyup="tabChange('.esc_attr($i + 1).',this)" data-next="digit-'.esc_attr($i + 2).'" data-previous="digit-'.esc_attr($otp_length - $j--).'" data-max="1" autocomplete="off">';
        }
        $content.= $input;
        $content.= '<input type="number" oninput="saGroup(this)" name="smsalert_customer_validation_otp_token" autofocus="true" placeholder="'.esc_attr($placeholder).'" id="smsalert_customer_validation_otp_token" class="input-text otp_input" pattern="[0-9]{'.esc_attr($otp_length).'}" title="Only digits within range 4-8 are allowed." data-max="' . esc_attr($otp_length) . '">';
        
        $content.= '<br><button type="button" name="smsalert_otp_validate_submit" style="color:grey; pointer-events:none;" id="sa_verify_otp" class="button smsalert_otp_validate_submit" value="Validate OTP">'.esc_attr($sa_button).'</button><br><a href="#" style="float:right" class="sa_resend_btn" onclick="saResendOTP(this)">'.esc_attr($sa_resend_btns).'</a><span class="sa_timer" style="min-width:80px; float:right"><span class="satimer">00:00:00</span> sec</span><span class="sa_forgot" style="float:right">'.esc_attr($sa_resend_otp).'</span><br></div></div></div>';    
        return $content;        
    }
    
    /**
     * Get ExitIntentStyle
     *
     * @param string $callback callback.    
     *
     * @return void
     */
    public static function getExitIntentStyle($callback=null)
    {        
         $cvt_title          = !empty($callback['cvt_title']) ? $callback['cvt_title'] : esc_html__('You were not leaving your cart just like that, right?', 'sms-alert');
         
        $cvt_description    = !empty($callback['cvt_description']) ? $callback['cvt_description'] : esc_html__('Just enter your mobile number below to save your shopping cart for later. And, who knows, maybe we will even send you a sweet discount code :)', 'sms-alert');
        $cvt_label          = !empty($callback['cvt_label']) ? $callback['cvt_label'] : esc_html__('Your Mobile No:', 'sms-alert');
        $cvt_placeholder    = !empty($callback['cvt_placeholder']) ? $callback['cvt_placeholder'] : "";
         $cvt_button         = !empty($callback['cvt_button']) ? $callback['cvt_button'] : esc_html__('Save cart', 'sms-alert');
         
        
           $content = '<div id="cart-exit-intent-form-content-r">
               <h2 class ="sa_title">'.esc_attr($cvt_title).'</h2>
			   <p class ="sa_description">'.esc_attr($cvt_description).'</p> 
               <form>
                    <label for="cart-exit-intent-mobile" id="sa_label">'.esc_attr($cvt_label).'</label>                   
                    <input type="text" id="cart-exit-intent-mobile" class="phone-valid" size="20" placeholder="'.esc_attr($cvt_placeholder).'" required="">
                    <button type="submit" name="cart-exit-intent-submit" id="cart-exit-intent-submit" class="button" value="submit">'.esc_attr($cvt_button).'</button>'.wp_nonce_field("smsalert_wp_abcart_nonce", "smsalert_abcart_nonce", true, false).'
			</form>
            </div>';
            
        return $content;
      
    }
    
    /**
     * Get SharCartStyle
     *
     * @param string $callback callback.    
     *
     * @return void
     */
    public static function getShareCartStyle($callback=null)
    {    
        $sa_title         = !empty($callback['sa_title']) ? $callback['sa_title'] : esc_html__('Share cart', 'sms-alert');         
        $sa_user_placeholder    = !empty($callback['sa_user_placeholder']) ? $callback['sa_user_placeholder'] : esc_html__('Your Name*', 'sms-alert');
        $sa_user_phone          = !empty($callback['sa_user_phone']) ? $callback['sa_user_phone'] : esc_html__('Your Mobile No*', 'sms-alert');
        $sa_frnd_placeholder    = !empty($callback['sa_frnd_placeholder']) ? $callback['sa_frnd_placeholder'] : esc_html__('Friend Name*', 'sms-alert');
        $sa_frnd_phone    = !empty($callback['sa_frnd_phone']) ? $callback['sa_frnd_phone'] : esc_html__('Friend Mobile No*', 'sms-alert');
        $sa_sharecart_button         = !empty($callback['sa_sharecart_button']) ? $callback['sa_sharecart_button'] : esc_html__('Share Cart', 'sms-alert');         
        $current_user_id = get_current_user_id();
        $phone = ( get_user_meta($current_user_id, 'billing_phone', true) !== '' ) ? SmsAlertUtility::formatNumberForCountryCode(get_user_meta($current_user_id, 'billing_phone', true)) : '';
        $uname = ( get_user_meta($current_user_id, 'first_name', true) !== '' ) ? ( get_user_meta($current_user_id, 'first_name', true) ) : '';
        
        $content = '<div class="smsalert_scp_close_modal-content modal-content">
                <div class="smsalert_scp_inner_div">
                    <div class="close"><span></span></div>
                    <form class="sc_form">
                        <ul id="smsalert_scp_ul">
                            <h2 class="box-title">'.esc_attr($sa_title).'</h2>
                            <li class="savecart_li">
                                <input type="text" name="sc_uname" id="sc_uname" placeholder="'.esc_attr($sa_user_placeholder).'" value="'.esc_attr($uname).'">
                            </li>
                            <li class="savecart_li">
                                <input type="text" name="sc_umobile" id="sc_umobile" placeholder="'.esc_attr($sa_user_phone).'" class="phone-valid" value="'.esc_attr($phone).'">
                            </li>
                            <li class="savecart_li">
                                <input type="text" name="sc_fname" id="sc_fname" placeholder="'.esc_attr($sa_frnd_placeholder).'">
                            </li>
                            <li class="savecart_li">
                                <input type="text" name="sc_fmobile" id="sc_fmobile" placeholder="'.esc_attr($sa_frnd_phone).'" class="phone-valid">
                            </li>
                            <li class="savecart_li">
                                <button class="button btn" id="sc_btn" name="sc_btn"><span class="button__text">'.esc_attr($sa_sharecart_button).'</span></button>
                            </li>
                        </ul>
						'.wp_nonce_field("smsalert_wp_sharecart_nonce", "smsalert_sharecart_nonce", true, false).'
						</form>
                    <div id="sc_response"></div>
                </div>                
            </div>';
            
        return $content;
      
    }
    /**
     * Get NotifyMeStyle
     *
     * @param string $callback callback.    
     *
     * @return void
     */
    public static function getNotifyMeStyle($callback=null)
    { 
        $notify_title = !empty($callback['notify_title']) ? $callback['notify_title'] : esc_html__('Notify Me when back in stock', 'sms-alert');
        $notify_placeholder = !empty($callback['notify_placeholder']) ? $callback['notify_placeholder'] : esc_html__('Enter Number Here', 'sms-alert');
        $notify_button = !empty($callback['notify_button']) ? $callback['notify_button'] : esc_html__('Notify Me', 'sms-alert');
		$current_user_id = get_current_user_id();
        $phone  = ( get_user_meta($current_user_id, 'billing_phone', true) !== '' ) ? SmsAlertUtility::formatNumberForCountryCode(get_user_meta($current_user_id, 'billing_phone', true)) : '';
        $content = '  <section class="smsalert_instock-subscribe-form">
			<div class="panel panel-primary smsalert_instock-panel-primary">
				<form class="panel-body">
					<div class="row">
						<fieldset class="smsalert_instock_field">
							<div class="col-md-12 hide-success">
								<div class="panel-heading smsalert_instock-panel-heading">
									<h4 class = "notify_title" style=""> '.esc_attr($notify_title).' </h4>
								</div>
								<div class="form-row">
									<input type="text" class="input-text phone-valid" id="sa_bis_phone" name="sa_bis_phone_phone" placeholder="'.esc_attr($notify_placeholder).'" value="'.esc_attr($phone).'">
								</div>
								<div class="form-group center-block" style="text-align:center;margin-top:10px">
									<button type="submit" id="sa_bis_submit" name="smsalert_submit" class="button sa_bis_submit" style="width:100%">'.esc_attr($notify_button).'</button>
								</div>
							</div>						
						</fieldset>
						<div class="col-md-12">
							<div class="sastock_output"></div>
						</div>
					</div>
					<!-- End ROW -->
				</form>
			</div>
		</section>';
            
        return $content;
      
    }    
}
new SAPopup('smsalertotp');