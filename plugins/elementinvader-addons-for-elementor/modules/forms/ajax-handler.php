<?php
namespace ElementinvaderAddonsForElementor\Modules\Forms;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * @package      Thz Framework
 * @author       Themezly
 * @license      http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 * @websites     http://www.themezly.com | http://www.youjoomla.com | http://www.yjsimplegrid.com
 */
 
class ThzelGetElementSettings {
	
	
	public function __construct( $postid, $widget_id, $widget_type ) {
		
		
		$this->postid 		= $postid;
		$this->widget_id 	= $widget_id;
		$this->widget_type 	= $widget_type;
		$this->widget 		= null;

		$this->parse();
		
	}
	
	public function elementor(){
		
		return 	\Elementor\Plugin::$instance;
		
	}
	
	public function get_settings () {
        if(!$this->widget) return false;
        return $this->widget;

        /* uncomment if need return field only 
		$widget = $this->elementor()->elements_manager->create_element_instance( $this->widget );
		return $widget->get_settings_for_display();*/

	}
	
	private function parse() {
		
		$data = $this->read_data();
		
		$this->parse_options($data);
		
	}
	
	private function read_data () {

		return $this->elementor()->documents->get( $this->postid )->get_elements_data();
		
	}
	
	private function parse_options($data) {
		
		if(!is_array($data) || empty($data)){
			return;
		}		
		
		foreach ( $data as $item ) {
			
			if(empty($item)){
				continue;
			}
			
			if ( 'section' === $item['elType'] || 'column' === $item['elType'] || 'container' === $item['elType']) {
				
				$this->parse_options($item['elements']);
				
			} else {
				
				$this->parse_options_simple($item);
			}
		}
	}
	
	private function parse_options_simple($item) {

		if (
			
			$item['id'] === $this->widget_id && 
			$item['widgetType'] === $this->widget_type
			
		) {
			$this->widget = $item;
		}
	}
}

/*
* Class Ajax Handler
*
* output {
    code:	       null (string|keys) error codes (see bellow error codes)
    message:       ''  (string|html) validation messages
    success:	   'true'  (bool) status ajax query
    no_clear_from: '' (bool) if true not clear fields after sent form
    redirect:      '' (string) if defined link, redirect on this link
*
*
* Error Codes:
	'success'
	'error'
	'required_field'
	'invalid_form'
	'server_error'
	'subscriber_already_exists'
*
*
* Actions ajax handler:
    1)  Before start work with ajax data
        init Code do_action('eli/ajax-handler/before', $form_data); // where $form_data['settings'] settings of widget

    Example : 
    add_action( 'eli/ajax-handler/before', function($form_data = array()){
        ....
    });

    2)  After start work with ajax data
        init Code do_action('eli/ajax-handler/after', $form_data); // where $form_data['settings'] settings of widget

    Example : 
    add_action( 'eli/ajax-handler/after', function($form_data = array()){
        ....
    });

    3) Filter data before code query 
        init Code  $form_data = apply_filters('eli/ajax-handler/filter_from_data', $form_data); // where $form_data['settings'] settings of widget

    Example code with disable mail send: 
    add_filter( 'eli/ajax-handler/filter_from_data', function($form_data = array()){
        //disable double sent to admin mail
        $form_data['settings']['disable_mail_send'] = 1;
        return $form_data;
    });

    4) Filter data after output generated
        init Code   $ajax_output = apply_filters('eli/ajax-handler/filter_output', $ajax_output);; // where $ajax_output is ajax output data

    Example code: 
    add_filter( 'eli/ajax-handler/filter_output', function($filter_output){
        // custom message
        $filter_output['message'] = '<div class="elementinvader_addons_for_elementor_alert elementinvader_addons_for_elementor_alert-danger" role="alert">'.esc_html__('Date not available', 'elementinvader-addons-for-elementor').'</div>';
        
        // disable reset form
        $filter_output['no_clear_from'] = true;

        // add redirect after success form
        $filter_output['redirect'] = 'url_website';
        
        return $filter_output;
    } );

*/

class Ajax_Handler {

	public $is_success = true;
	public $messages = [
		'success' => [],
		'error' => [],
		'admin_error' => [],
	];
	public $data = [];
	public $errors = [];
	public $custom_message;

	private $current_form;

	const SUCCESS = 'success';
	const ERROR = 'error';
	const FIELD_REQUIRED = 'required_field';
	const INVALID_FORM = 'invalid_form';
	const RECAPTCHA_ERROR = 'recaptcha_error';
	const SERVER_ERROR = 'server_error';
	const SUBSCRIBER_ALREADY_EXISTS = 'subscriber_already_exists';

	public static function is_form_submitted() {
		return wp_doing_ajax() && sanitize_text_field( $_POST['action'] ) && 'elementinvader_addons_for_elementor_forms_send_form' === sanitize_text_field($_POST['action']);
	}

	public static function get_default_messages() {
		return [
			self::SUCCESS => esc_html__( 'The form was sent successfully.', 'elementinvader-addons-for-elementor' ),
			self::ERROR => esc_html__( 'An error occured.', 'elementinvader-addons-for-elementor' ),
			self::FIELD_REQUIRED => esc_html__( 'This field is required.', 'elementinvader-addons-for-elementor' ),
			self::INVALID_FORM => esc_html__( 'There\'s something wrong. The form is invalid.', 'elementinvader-addons-for-elementor' ),
			self::RECAPTCHA_ERROR => esc_html__( 'Recaptcha is wrong, try reload page.', 'elementinvader-addons-for-elementor' ),
			self::SERVER_ERROR => esc_html__( 'Server can\'t send emails, please use SMTP mail configuration.', 'elementinvader-addons-for-elementor' ),
			self::SUBSCRIBER_ALREADY_EXISTS => esc_html__( 'Subscriber already exists.', 'elementinvader-addons-for-elementor' ),
		];
	}

	public static function get_default_message( $id, $settings ) {
                $settings_id = '';
                switch ($id) {
                    case 'success': $settings_id = 'success_message';
                                break;
                    case 'error': $settings_id = 'error_message';
                                break;
                    case 'required_field': $settings_id = 'required_field_message';
                                break;
                    case 'recaptcha_error': $settings_id = 'recaptcha_error';
                                break;
                    case 'invalid_form': $settings_id = 'invalid_message';
                                break;
                }
            
                if ( isset( $settings[ $settings_id ] ) ) {
                        return $settings[ $settings_id ];
                }
                
		return self::get_default_messages()[$id];
	}

	public function ajax_send_form() {
  
            ob_clean();
            ob_start();

            $ajax_output = [
                'results'=>'',
                'code'=>'',
                'message'=>'',
                'success'=>false,
            ];
            
            $post = sanitize_post($_POST);
            if(!isset($post['element_id']) || empty($post['element_id'])){
                $ajax_output['code'] = self::INVALID_FORM;
                $ajax_output['message'] = $this->generate_alert(esc_html__( 'Element id not found.', 'elementinvader-addons-for-elementor' ),'elementinvader_addons_for_elementor_alert-danger');
                $this->output($ajax_output);
            }
            $element_id = $post['element_id'];
            /* deprecated */
            //$form_data = get_option('elementinvader_addons_for_elementor_form_'.$element_id);

            $form_data = array(); 
            if(isset($post['shortcode']) && !empty($post['shortcode'])){
                $form_data = array('settings' => $_POST);
            } else {
                $get_settings	= new ThzelGetElementSettings($post['eli_page_id'],$post['eli_id'],$post['eli_type']); 
                $form_data = $get_settings->get_settings();
            }

            do_action('eli/ajax-handler/before', $form_data);
            
            if(has_filter('eli/ajax-handler/filter_from_data'))
                $form_data = apply_filters('eli/ajax-handler/filter_from_data', $form_data);

            if(!$form_data){
                $ajax_output['code'] = self::INVALID_FORM;
                $ajax_output['message'] = $this->generate_alert($this->get_default_message( self::INVALID_FORM, $form_data ),'elementinvader_addons_for_elementor_alert-danger');
                $this->output($ajax_output);
            }
            $form_data = $form_data['settings'];

            if(!empty($form_data['redirect_url'])) {
                $ajax_output['redirect'] = $form_data['redirect_url'];
            }

            /* start recaptcha */
            if(isset($form_data['recaptcha_secret_key']) && !empty($form_data['recaptcha_secret_key']))
                if(isset($post['g-recaptcha-response']) && $this->valid_recaptcha($post['g-recaptcha-response'], $form_data['recaptcha_secret_key']) === TRUE)
                {
                    /* success */
                }
                else
                {
                    $ajax_output['code'] = self::RECAPTCHA_ERROR;
                    $ajax_output['message'] = $this->generate_alert($this->get_default_message( self::RECAPTCHA_ERROR, $form_data ),'elementinvader_addons_for_elementor_alert-danger');
                    $this->output($ajax_output);
                }
            /* end recaptcha */
                
            if(!isset($form_data['disable_mail_send']) || empty($form_data['disable_mail_send'])) {
                $subject = $this->_ch($form_data['mail_data_subject']);
                $subject = $this->replace_smart_data($subject, $post);
                // message
                $message = '
                    <html xmlns="http://www.w3.org/1999/xhtml">
                    <head>
                        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                        <title>'.esc_html($subject).'</title>
                        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                    </head>
                    <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding: 48px 0;width: 100%; background-color: #f7f7f7; -webkit-text-size-adjust: none;">
                        <div id="wrapper" dir="ltr" style="width: 600px; background-color: #fff; margin: 0 auto;border: 1px solid #dedede;box-shadow: 0 1px 4px rgb(0 0 0 / 10%);">
                    
                            <!-- header -->
                            <div class="header" style="background-color: #2671cb;padding: 48px 48px;color: #FFF;">
                                <h2 style="margin:0;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff; background-color: inherit;"">
                                    '.esc_html($subject).'
                                </h2>
                            </div>
                    
                            <!-- Body -->
                            <div class=" body" style="padding: 48px 48px;color: #636363; font-size: 14px;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;">
                                {dynamic_values}       
                            </div>
                    
                            <!-- Footer -->
                            <div class="footer" style="padding: 25px 48px;color: #4e5254; font-weight: 500;font-size: 14px;line-height: 1.6;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;border-top: 1px solid #eee;">
                                '.esc_html__('Thanks', 'elementinvader-addons-for-elementor').', </br>
                                '.esc_html__('Best regards', 'elementinvader-addons-for-elementor').', </br>
                                '.esc_html(get_bloginfo('name')).' </br>
                            </div>
                        </div>                        
                    </body>
                    </html>
                ';

                $data_mess= array();
                $custom_message = '';
                $email_client = false;
                foreach($post as $key => $value){
                    if($key=='element_id') continue;
                    if(in_array($key, array('eli_id', 'eli_type','ID','filter','action','send_action_type'))) continue;

                    if($key  == 'eli_page_id'){
                        $value = get_permalink($value);
                        $key = 'page_link';
                    }
                    
                    if(filter_var($value, FILTER_VALIDATE_URL ) || strpos( $value, 'http' ) !== FALSE) {
                        $data_mess []= '<p><strong>'.str_replace('_',' ', ucfirst($key)).':</strong> <a href="'.esc_url($value).'">'.$value.'</a></p>';
                    } else {
                        $data_mess []= '<p><strong>'.str_replace('_',' ', ucfirst($key)).':</strong> '.$value.'</p>';
                    }

                    if(stripos($key,'custom_subject') !== FALSE)
                        $custom_message = $value;

                    if(stripos($key,'mail') !== FALSE || filter_var($value, FILTER_VALIDATE_EMAIL))
                        $email_client = $value;
                }

                $data_mess = implode('', $data_mess);
                $message = str_replace('{dynamic_values}', $data_mess, $message);

                $email_address = $this->_ch($form_data['mail_data_to_email']);

                if(empty($email_address)) {
                    $form_data['mail_data_from_email'] = $email_address = get_bloginfo('admin_email');
                }
                
                $headers = array('Content-Type: text/html; charset=UTF-8');
                $headers[] = 'From: '. $this->_ch($form_data['mail_data_from_name']).' <'. $this->_ch($form_data['mail_data_from_email']).'>';

                if($email_client)
                    $headers[] = 'Reply-To: '.$email_client;

                $subject = $subject.' '.esc_html($custom_message);
                $ret = false;
                if(strpos($email_address, ';')) {
                    $email_address = explode(';', $email_address);
                    foreach ($email_address as $email) {
                        if(!empty($email)){
                            $ret = wp_mail( $email, $subject, $message, $headers );
                        }
                    }
                } else{
                    $ret = wp_mail( $email_address, $subject, $message, $headers );
                }

            } else {
                $ret = true;
            }

            /* send inform message for client */
            $email = false;
            foreach ($post as $key => $value) {
                if(stripos($key,'mail') !== FALSE || filter_var($value, FILTER_VALIDATE_EMAIL))
                    $email = $value;
            }

            if(!isset($form_data['disable_mail_send']) || empty($form_data['disable_mail_send'])){
                if($email) {
                    // message
                    $message = '
                    <html xmlns="http://www.w3.org/1999/xhtml">
                        <head>
                            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
                            <title>'.esc_html__( 'The message was sent successfully.', 'elementinvader-addons-for-elementor' ).'</title>
                            <meta name="viewport" content="width=device-width, initial-scale=1.0" />
                        </head>
                        <body leftmargin="0" marginwidth="0" topmargin="0" marginheight="0" offset="0" style="padding: 48px 0;width: 100%; background-color: #f7f7f7; -webkit-text-size-adjust: none;">
                            <div id="wrapper" dir="ltr" style="width: 600px; background-color: #fff; margin: 0 auto;border: 1px solid #dedede;box-shadow: 0 1px 4px rgb(0 0 0 / 10%);">
                        
                                <!-- header -->
                                <div class="header" style="background-color: #2671cb;padding: 48px 48px;color: #FFF;">
                                    <h2 style="margin:0;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif; font-size: 30px; font-weight: 300; line-height: 150%; margin: 0; text-align: left; text-shadow: 0 1px 0 #ab79a1; color: #ffffff; background-color: inherit;"">
                                        '.esc_html__( 'The message was sent successfully.', 'elementinvader-addons-for-elementor' ).'
                                    </h2>
                                </div>
                        
                                <!-- Body -->
                                <div class=" body" style="padding: 48px 48px;color: #636363; font-size: 14px;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;">
                                    <h2 style="margin-top:0">'.esc_html__( 'Details from your message.', 'elementinvader-addons-for-elementor' ).'</h2>
                                    {dynamic_values}       
                                </div>
                        
                                <!-- Footer -->
                                <div class="footer" style="padding: 25px 48px;color: #4e5254; font-weight: 500;font-size: 14px;line-height: 1.6;font-family: \'Helvetica Neue\', Helvetica, Roboto, Arial, sans-serif;border-top: 1px solid #eee;">
                                    '.esc_html__('Thanks', 'elementinvader-addons-for-elementor').', </br>
                                    '.esc_html__('Best regards', 'elementinvader-addons-for-elementor').', </br>
                                    '.esc_html(get_bloginfo('name')).' </br>
                                </div>
                            </div>
                        </body>
                    </html>
                    ';

                    $data_mess= array();
                    foreach($post as $key => $value){
                        if($key=='element_id') continue;
                        if(in_array($key, array('eli_id', 'eli_type','ID','filter','action', 'send_action_type', 'g-recaptcha-response'))) continue;

                        if($key  == 'eli_page_id'){
                            $value = get_permalink($value);
                            $key = 'page_link';
                        }
                        
                        if(filter_var($value, FILTER_VALIDATE_URL ) || strpos( $value, 'http' ) !== FALSE) {
                            $data_mess []= '<p><strong>'.str_replace('_',' ', ucfirst($key)).':</strong> <a href="'.esc_url($value).'">'.$value.'</a></p>';
                        } else {
                            $data_mess []= '<p><strong>'.str_replace('_',' ', ucfirst($key)).':</strong> '.$value.'</p>';
                        }
                    }

                    $data_mess = implode('', $data_mess);
                    $message = str_replace('{dynamic_values}', $data_mess, $message);

                    $headers = array('Content-Type: text/html; charset=UTF-8');
                    $headers[] = 'From: '. esc_html(get_bloginfo('name')).' <'. esc_html(get_bloginfo('admin_email')).'>';

                    $subject = esc_html__( 'The message was sent successfully.', 'elementinvader-addons-for-elementor' );
                    
                    wp_mail( $email, $subject, $message, $headers );
                }
            }
            
            /* action after send */
            
            if(isset($post['send_action_type'])) {
                if(stripos($post['send_action_type'], 'mail_base') !== FALSE) {
                    $this->action_mail_base($post);
                }
                if(stripos($post['send_action_type'], 'mailchimp') !== FALSE) {
                  
                    if(!$this->action_mailchimp($post)) {
                        $ret = false;
                    }
                }            
            }
            /* end action after send */
            
            do_action('eli/ajax-handler/after', $form_data);

            if($ret){
                $ajax_output['code'] = self::SUCCESS;
                $ajax_output['success'] = 'true';
                if($this->custom_message) {
                    $ajax_output['message'] = $this->custom_message;
                } else {
                    $ajax_output['message'] = $this->generate_alert($this->get_default_message( self::SUCCESS, $form_data ),'elementinvader_addons_for_elementor_alert-primary');
                }
            
            } else {
                $ajax_output['code'] = self::SERVER_ERROR;
                $ajax_output['message'] = $this->generate_alert($this->get_default_message( self::SERVER_ERROR, $form_data ),'elementinvader_addons_for_elementor_alert-danger');
            }
    
            if(has_filter('eli/ajax-handler/filter_output'))
                $ajax_output = apply_filters('eli/ajax-handler/filter_output', $ajax_output);

            $this->output($ajax_output);
	}
        
        public function output($ajax_output = []){
            $json_output = json_encode($ajax_output);
            header('Pragma: no-cache');
            header('Cache-Control: no-store, no-cache');
            header('Content-Type: application/json; charset=utf8');
            //header('Content-Length: '.$length); // special characters causing troubles

            echo $json_output;
            exit();
        }
        
	public function generate_alert($message = '', $class='elementinvader_addons_for_elementor_alert-primary' ) {
		return '<div class="elementinvader_addons_for_elementor_alert '.$class.'" role="alert">'.$message.'</div>';
	}

	public function __construct() {
            add_action( 'wp_ajax_elementinvader_addons_for_elementor_forms_send_form', [ $this, 'ajax_send_form' ] );
            add_action( 'wp_ajax_nopriv_elementinvader_addons_for_elementor_forms_send_form', [ $this, 'ajax_send_form' ] );
	}
        
        public function _ch(&$var, $empty = '')
        {
            if(empty($var))
                return $empty;
            return $var;
        }
        
        public function valid_recaptcha($response='',$recaptcha_secret_key='')
        {
            if(isset($response) && !empty($response)){
                //your site secret key
                if($this->valid_recaptcha_curl($response,$recaptcha_secret_key))
                {
                    return TRUE;
                }
            }
            return FALSE;
        }

        private function valid_recaptcha_curl($g_recaptcha_response='', $recaptcha_secret_key='') {
            $url = 'https://www.google.com/recaptcha/api/siteverify';
            $args = array(
                    'timeout'     => 200,
                    'blocking'    => true,
                    'headers'     => array(),
                    'body'        => array(
                        'secret' => $recaptcha_secret_key,
                        'response' => $g_recaptcha_response,
                        'remoteip' => $_SERVER['REMOTE_ADDR']
                    ),
                    'cookies'     => array()
            );
            $response = wp_remote_post( $url, $args );
            
            if ( is_wp_error( $response ) ) {
               /*$error_message = $response->get_error_message();*/
               return true;
            } else {
                $response = json_decode($response['body']);
                return $response->success;
            }
            
        }
                
        public function action_mail_base($data)
        {
            $element_id =$data['element_id'];
            if(isset($data['shortcode']) && !empty($data['shortcode'])){
                $form_data = $data;
            } else {
                $get_settings	= new ThzelGetElementSettings($data['eli_page_id'],$data['eli_id'],$data['eli_type']); 
                $form_data = $get_settings->get_settings();
                $form_data = $form_data['settings'];
            }

            $json_object = json_encode($data);
            
            $email = false;
            foreach ($data as $key => $value) {
                if(stripos($key,'mail') !== FALSE || filter_var($value, FILTER_VALIDATE_EMAIL))
                    $email = $value;
            }
            
            if(!$email) return false;
            
            global $wpdb;
            $table_name = $wpdb->prefix . "eli_newsletters";
            $res = false;
            $checkIfExists = $wpdb->get_var("SELECT id FROM $table_name WHERE email = '$email'");
            if ($checkIfExists == NULL) {
                $res = $wpdb->insert($table_name, array(
                    'form_id' => $element_id,
                    'email' => $email,
                    'website' => get_site_url(),
                    'json_object' => $json_object,
                    'date' => date('Y-m-d H:i:s')
                ));
            } else {
                $this->custom_message = $this->generate_alert(esc_html__('Email already added', 'elementinvader-addons-for-elementor'));
            }

            if($res)
                return true;
    }
                
    public function action_mailchimp($data)
        {
            $element_id =$data['element_id'];
                                   
            if(isset($data['shortcode']) && !empty($data['shortcode'])){
                $form_data = $data;
            } else {
                $get_settings	= new ThzelGetElementSettings($data['eli_page_id'],$data['eli_id'],$data['eli_type']); 
                $form_data = $get_settings->get_settings();
                $form_data = $form_data['settings'];
            }

            $json_object = json_encode($data);
            
            $email = false;
            foreach ($data as $key => $value) {
                if(stripos($key,'mail') !== FALSE || filter_var($value, FILTER_VALIDATE_EMAIL))
                    $email = $value;
            }

            if(!$email) return false;
            
            $ajax_output = array();
            $this->data['message'] = esc_html__('No message returned!', 'elementinvader-addons-for-elementor');
            $this->data['parameters'] = sanitize_post($_POST);
            $this->data['success'] = false;

            if(empty($form_data['section_send_action_mailchimp_api_key']) || empty($form_data['section_send_action_mailchimp_list_id'])) {
                $this->data['message'] = esc_html__('Subscribe API not configured, please contact with administrator','elementinvader-addons-for-elementor'); 
            }
            else if( filter_var($email, FILTER_VALIDATE_EMAIL)){

            $data = [
                'email'     => $email,
                'status'    => 'subscribed',
            ];

            $apiKey = $form_data['section_send_action_mailchimp_api_key'];
            $listId = $form_data['section_send_action_mailchimp_list_id'];
            
            $memberId = md5(strtolower($data['email']));
            $dataCenter = substr($apiKey,strpos($apiKey,'-')+1);
            $url = 'https://' . $dataCenter . '.api.mailchimp.com/3.0/lists/' . $listId . '/members/' . $memberId;

            $json = json_encode([
                'apikey' => $apiKey,
                'email_address' => $data['email'],
                'status'        => $data['status'], // "subscribed","unsubscribed","cleaned","pending"
            ]);

            $httpCode=0;
            $result = $this->sw_mailchimp_post($url, $apiKey, $httpCode, $json);
            if($httpCode == 200) {
                $this->data['success'] = true;
                $this->data['message'] = esc_html__('Your e-mail','elementinvader-addons-for-elementor').' '. sanitize_text_field($_POST['subscriber_email']) .' '.__(' has been added to our mailing list!','elementinvader-addons-for-elementor'); 
            } else {
                 $this->data['message'] = esc_html__('Please check mailchimp settings','elementinvader-addons-for-elementor').' '.$this->data['parameters']['subscriber_email']; 
            }

            } else {
               $this->data['message'] = esc_html__('There was a problem with your e-mail','elementinvader-addons-for-elementor').' '.$this->data['parameters']['subscriber_email']; 
            }

            $ajax_output['success'] = $this->data['success'];
            $ajax_output['message'] = $this->data['message'];

            $json_output = json_encode($ajax_output);

            return $this->data['success'];
    }
    
    
    function sw_mailchimp_post($url, $apiKey, &$httpCode, $json)
    {
        $args = array(
                'headers' => array(
                        'Authorization' => 'Basic ' . base64_encode( 'user:'. $apiKey )
                )
        );

        $response = wp_remote_get( $url, $args );
        $result = json_decode( $response['body'] );
        $httpCode = $response['response']['code'];
        
        return $result;
    }
    
    function replace_smart_data($string = '', $post = array()) {
        // Match all occurrences of the pattern
        if (preg_match_all('/\{([^}]+)\}/', $string, $matches)) {
            foreach ($matches[1] as $key => $value) {
                if(isset($post[$value])) {
                    $string = str_replace('{'.$value.'}', $post[$value], $string);
                }
            }
        }
        return $string;
    }
}
