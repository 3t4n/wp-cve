<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link	   http://activetrail.com
 * @since	  1.0.0
 *
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, 
 *
 * @package	Activetrail_Cf7
 * @subpackage Activetrail_Cf7/admin
 * @author	 ActiveTrail <contact@activetrail.com>
 */
class Activetrail_Cf7_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since	1.0.0
	 * @access   private
	 * @var	  string	$plugin_name	The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since	1.0.0
	 * @access   private
	 * @var	  string	$version	The current version of this plugin.
	 */
	private $version;
	
	private $meta_prefix = '_at_cf7_key_';
	
	private $at_cf7_metafields = array(
									'activetrail' => array(
										'token_id'			=> 'token_id',
										'group_id'			=> 'group_id',
										'mailing_list_id'	=> 'mailing_list_id'
									),
									'form' => array(
										'required' => array(
											'form_id'			=> 'form_id',
											'email'				=> 'email'
										),
										'optional' => array(
											'phone_number'		=> 'sms',
											'approve_checkbox'	=> 'approve_checkbox'
										),
										'api' => array()
									)
								);
		
	
	private $at_api_error_stack = array();
		
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since	1.0.0
	 * @param	  string	$plugin_name	   The name of this plugin.
	 * @param	  string	$version	The version of this plugin.
	 */
	public function __construct($plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	private function get_metakey($field) {
		return $this->meta_prefix . $field;
	}
	
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since	1.0.0
	 */
	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/activetrail-cf7-admin.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since	1.0.0
	 */
	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/activetrail-cf7-admin.js', array( 'jquery' ), $this->version, false );
	}

	public function at_cf7_editor_panels($panels) {
		if (!(current_user_can('administrator') || current_user_can('editor'))) {
			return $panels;
		}

		$panels['activetrail-cf7-panel'] = array( 'title' => 'ActiveTrail', 'callback' => array($this, 'at_cf7_callback_panel_init') );
		return $panels;
	}

	public function at_cf7_save_form($cf7) {
		if (!(current_user_can('administrator') || current_user_can('editor'))) {
			wp_die('Invalid access!');
		}
		
		if (!isset($_POST['wpcf7-activetrail']) || !isset($_POST['wpcf7-activetrail-meta'])) {
			wp_die('Invalid POST');
		}

		$cf7_id = (int) $cf7->id();
		
		//Both Required and Covered by above condition
		//$post_fields = $_POST['wpcf7-activetrail'];
		// $meta_post_fields = $_POST['wpcf7-activetrail-meta'];

		// Assign to varable: Check and Sanitization done below on a per element basis 
		// (providing below conditions are met $post_fields_optional: is not null, is array and has elements in it
		//$post_fields_optional = isset($_POST['wpcf7-activetrail-optional']) ? $_POST['wpcf7-activetrail-optional'] : null; 

		/*new code*/
		$post_fields = array();
		$meta_post_fields = array();

		foreach ($this->at_cf7_metafields['activetrail'] as $key => $field) {
			if (isset($_POST['wpcf7-activetrail'][$field])) {
				$post_fields[$field] = sanitize_text_field($_POST['wpcf7-activetrail'][$field]);
			}

			// wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');

			if (isset($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce'])) {
				$meta_post_fields[$field . '_metabox_nonce'] = sanitize_text_field($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce']);
			}
		}

		foreach ($this->at_cf7_metafields['form']['required'] as $key => $field) {
			if (isset($_POST['wpcf7-activetrail'][$field])) {
				$post_fields[$field] = sanitize_text_field($_POST['wpcf7-activetrail'][$field]);
			}

			// wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');

			if (isset($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce'])) {
				$meta_post_fields[$field . '_metabox_nonce'] = sanitize_text_field($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce']);
			}
		}

		foreach ($this->at_cf7_metafields['form']['optional'] as $key => $field) {
			if (isset($_POST['wpcf7-activetrail'][$field])) {
				$post_fields[$field] = sanitize_text_field($_POST['wpcf7-activetrail'][$field]);
			}

			// wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');

			if (isset($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce'])) {
				$meta_post_fields[$field . '_metabox_nonce'] = sanitize_text_field($_POST['wpcf7-activetrail-meta'][$field . '_metabox_nonce']);
			}
		}
		
		/* new code ends */
		
		//Ensures that all required fields are received from the POST and set
		foreach ($this->at_cf7_metafields['activetrail'] as $key => $field) {
			if (!isset($post_fields[$field]) || !isset($meta_post_fields[$field.'_metabox_nonce'])) {
				wp_die('POST data violation');
			}
		}
		
		//Ensures that all required meta fields are received from the POST and set
		foreach ($this->at_cf7_metafields['form']['required'] as $key => $field) {
			if (!isset($post_fields[$field]) || !isset($meta_post_fields[$field.'_metabox_nonce'])) {
				wp_die('POST data violation');
			}
		}
		
		$db_post_meta = get_post_meta($cf7_id);
		$app_token_id = null;
		
		if (array_key_exists($this->get_metakey('token_id'), $db_post_meta)) {
			$app_token_id = $db_post_meta[$this->get_metakey('token_id')][0];
		}
		
		//Ensure that we have a API token in the database and is set to some string: 
		//(e.g. token: 0XE4EFE865BA48798BBB7595DT394310554DE2AC34FE9F61431F3A78C0FFB20FCFC71149DD23020FF7FD7C43A7BF212901)
		if (!isset($app_token_id) || preg_replace('/\s+/', '', $app_token_id) == '') {
			$this->at_api_error_stack[] = 'Invalid API Token';
		}
		
		//Pull list of ActiveTrail fields from the API
		$this->at_request_fields($app_token_id);
		
		$existing_optional_settings = array();
		
		foreach ($this->at_cf7_metafields['form']['api'] as $key => $field_data) {
			$field_name = strtolower($field_data['field_name']);
			$metakey = $this->get_metakey($field_name);
			
			if (array_key_exists($metakey, $db_post_meta)) {
				$existing_optional_settings[] = $field_name;
			}
		}

		if (isset($_POST['wpcf7-activetrail-optional']) && is_array($_POST['wpcf7-activetrail-optional']) && count($_POST['wpcf7-activetrail-optional']) > 0) {
			foreach ($_POST['wpcf7-activetrail-optional'] as $rfc4id => $data) {
				if (isset($data['src']) && isset($data['dst']) && (preg_replace('/\s+/', '', $data['src']) != '') && preg_replace('/\s+/', '', $data['dst']) != '') {
					//Sanitize Value
					$value = sanitize_text_field($data['src']);
					
					$key = $this->get_metakey(sanitize_text_field($data['dst']));
					
					$action = 'replace';
					//Make sure received action via POST is a VALID and one of 2 options
					if (array_key_exists('action', $data) && in_array(sanitize_text_field($data['action']), array('replace','merge'))) {
						$action = sanitize_text_field($data['action']);
					}
					
					//Update field with sanitized value + action: BOTH ARE CLEANED UP AND VALIDATED AT THIS POINT
					update_post_meta($cf7_id, $key, $value.'|'.$action);
					
					if (($key = array_search(sanitize_text_field($data['dst']), $existing_optional_settings)) !== false) {
						unset($existing_optional_settings[$key]);
					}
				}
			}
		}

		$existing_optional_settings = array_values($existing_optional_settings);

		
		//Delete the options that were in the database, but got removed with the interface prior to saving this form.
		if (count($existing_optional_settings) > 0) {
			foreach ($existing_optional_settings as $setting_key) {
				$meta_key = $this->get_metakey($setting_key);
				delete_post_meta($cf7_id, $meta_key);
			}
		}
		
		foreach ($post_fields as $field => $value) {
			$clean_value = null;
			
			// if (in_array($field, array('form_id', 'group_id'))) // form_id removed because CF7 now use alpha + number for Form ID
			if (in_array($field, array('group_id'))) {
				if (is_numeric((int) sanitize_text_field($value))) {
					$clean_value = (int) sanitize_text_field($value);
				}
			} else {
				$clean_value = sanitize_text_field($value);
			}

			if (in_array($field, $this->at_cf7_metafields['form']['optional']) || $clean_value || $field == 'mailing_list_id') {
				update_post_meta($cf7_id, $this->get_metakey(sanitize_text_field($field)), $clean_value);
			}
		}		
	}

	public function at_cf7_callback_panel_init($post) {
		if (!(current_user_can('administrator') || current_user_can('editor'))) {
			wp_die('');
		}

		$settings_values = array();
		$optional_settings_values = array();
		
		$db_post_meta = get_post_meta($post->id());
		
		if (!is_array($db_post_meta)) {
			$db_post_meta = array();
		}
		
		$app_token_id = null;
		if (array_key_exists($this->get_metakey('token_id'), $db_post_meta)) {
			$app_token_id = $db_post_meta[$this->get_metakey('token_id')][0];
		}
				
		if (preg_replace('/\s+/', '', $app_token_id) != '') {
			$this->at_request_fields($app_token_id);
		} else {
			$this->at_api_error_stack[] = 'Invalid API Token';
		}
		
		foreach ($this->at_cf7_metafields['activetrail'] as $key => $field) {
			wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');
			
			$metakey = $this->get_metakey($field);
						
			if (array_key_exists($metakey, $db_post_meta)) {
				$settings_values[$field] = $db_post_meta[$metakey][0];
			}
		}
		
		foreach ($this->at_cf7_metafields['form']['required'] as $key => $field) {
			wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');
			
			$metakey = $this->get_metakey($field);
						
			if (array_key_exists($metakey, $db_post_meta)) {
				$settings_values[$field] = $db_post_meta[$metakey][0];
			}
		}
		
		foreach ($this->at_cf7_metafields['form']['optional'] as $key => $field) {
			wp_nonce_field($field.'_metabox', 'wpcf7-activetrail-meta['. $field .'_metabox_nonce]');
			
			$metakey = $this->get_metakey($field);
						
			if (array_key_exists($metakey, $db_post_meta)) {
				$settings_values[$field] = $db_post_meta[$metakey][0];
			}
		}
		
		foreach ($this->at_cf7_metafields['form']['api'] as $key => $field_data) {
			$field_name = strtolower($field_data['field_name']);
			$metakey = $this->get_metakey($field_name);
			
			if (array_key_exists($metakey, $db_post_meta)) {
				$optional_settings_values[$field_name] = $db_post_meta[$metakey][0];
			}
		}

		$plugin_url = plugins_url('', __FILE__);
		
		$error_stack = $this->at_api_error_stack;
		
		include_once('partials/activetrail-cfs-admin-display.php');
	}

	public function at_cf7_post_form($cf7_form) {
		$cf7_id = (int) $cf7_form->id();

		$db_post_meta = get_post_meta($cf7_id);
		
		$app_token_id = null;
		if (array_key_exists($this->get_metakey('token_id'), $db_post_meta)) {
			$app_token_id = $db_post_meta[$this->get_metakey('token_id')][0];
		}

		$mailing_list_id = null;
		if (array_key_exists($this->get_metakey('token_id'), $db_post_meta)) {
			$mailing_list_id = $db_post_meta[$this->get_metakey('mailing_list_id')][0];
		}
				
		if (preg_replace('/\s+/', '', $app_token_id) == '') {
			$this->at_api_error_stack[] = 'Invalid API Token';
		}
		
		$this->at_request_fields($app_token_id);
		
		$cf7_meta = null;
		
		if (array_key_exists($this->get_metakey($this->at_cf7_metafields['form']['optional']['approve_checkbox']), $db_post_meta)) {
			$cf7_meta = $db_post_meta[$this->get_metakey($this->at_cf7_metafields['form']['optional']['approve_checkbox'])];
		}
		
		$email_metakey = $this->get_metakey($this->at_cf7_metafields['form']['required']['email']);
		
		$email_tag = null;
		if (array_key_exists($email_metakey, $db_post_meta)) {
			$email_tag = $db_post_meta[$email_metakey][0];
		}
		
		$lead_email = null;
		
		if (array_key_exists($email_tag, $_POST) && isset($_POST[$email_tag]) && !empty($_POST[$email_tag])) {
			$lead_email = trim($_POST[$email_tag]);
			$lead_email = sanitize_email($lead_email);	
		}
		
		if (!is_email($lead_email)) {
			$this->at_api_error_stack[] = 'Invalid Email Address';
		}
		
		$api_optional = array();
		foreach ($this->at_cf7_metafields['form']['api'] as $key => $field_data) {
			$field_name = strtolower($field_data['field_name']);
			$metakey = $this->get_metakey($field_name);
			
			if (array_key_exists($metakey, $db_post_meta)) {
				$parts = explode('|', $db_post_meta[$metakey][0]);
				
				if (array_key_exists($parts[0], $_POST)) {
					$action = sanitize_text_field($parts[1]);
					$existing_value = '';
					
					//Fix API inconsistency
					if ($field_name == 'firstname') {
						$field_name = 'first_name';
					}
					if ($field_name == 'lastname') {
						$field_name = 'last_name';
					}
					if ($field_name == 'zipcode') {
						$field_name = 'zip_code';
					}
					
					if ($action == 'merge') {
						$at_contact = $this->at_request_contact($app_token_id, $lead_email);
						
						if (is_array($at_contact) && array_key_exists($field_name, $at_contact)) {
							$existing_value = sanitize_text_field($at_contact[$field_name]);
						}
					}

					$value = '';
					if (array_key_exists($parts[0], $_POST) && isset($_POST[$parts[0]])) {
						$clean_value = sanitize_text_field($_POST[$parts[0]]);

						$value = $existing_value != '' ? trim($existing_value . ',' . $clean_value) : $clean_value;
					}
					
					$api_optional[$field_name] = sanitize_text_field($value);
				}
			}
		}
		
		if ($cf7_meta) {
			$chckbox_id = $cf7_meta[0];
			if (!empty($chckbox_id) && (!array_key_exists($chckbox_id, $_POST) || $cf7_id != $_POST['_wpcf7'])) {
				return;
			}	
		}
		
		$optional = array();
		$sms_metakey = $this->get_metakey($this->at_cf7_metafields['form']['optional']['phone_number']);
		$sms_key = null;
		if (array_key_exists($sms_metakey, $db_post_meta)) {
			$sms_key = $db_post_meta[$sms_metakey][0];
		}
		
		if (array_key_exists($sms_key, $_POST) && isset($_POST[$sms_key])) {
			$sms = trim($_POST[$db_post_meta[$this->get_metakey($this->at_cf7_metafields['form']['optional']['phone_number'])][0]]);
			if ($sms && !empty($sms)) {
				$optional['sms'] = sanitize_text_field($sms);
				
				// Use regular expressions to extract numbers
                $optional['sms'] = preg_replace('/[^0-9]/', '', $optional['sms']);
			}

			if (!is_numeric($optional['sms'])) {
				$this->at_api_error_stack[] = 'Invalid phone number';
			}
		}
		
		$group_id_metakey = $this->get_metakey($this->at_cf7_metafields['activetrail']['group_id']);
		$group_id = null;
		
		if (array_key_exists($group_id_metakey, $db_post_meta)) {
			$group_id = $db_post_meta[$group_id_metakey][0];
		}

		$mailing_list_id_metakey = $this->get_metakey($this->at_cf7_metafields['activetrail']['mailing_list_id']);
		$mailing_list_id = null;
		
		if (array_key_exists($mailing_list_id_metakey, $db_post_meta)) {
			$mailing_list_id = $db_post_meta[$mailing_list_id_metakey][0];
		}
		
		$basic_fields = array(
							'email' => sanitize_email($lead_email)
						);

		$combined = array_merge($basic_fields, $optional, $api_optional);
		$conbined['is_do_not_mail'] = false;
		$combined['is_deleted'] = false;

		$data = array(
					//'mailing_list' => $list_id,
					'group' => $group_id,
					'contacts' => array($combined)
				);
		
		if (isset($mailing_list_id) && $mailing_list_id) {
			$data['mailing_list'] = $mailing_list_id;
		}
		
		$activetrail_api = new Activetrail_Api(array('app_token_id' => $app_token_id));

		$res_json = $activetrail_api->import_contacts($data);
		
		return $res_json;
	}
	
	private function at_request_fields($app_token_id) {
		$activetrail_api = new Activetrail_Api(array('app_token_id' => $app_token_id));
		
		$res_json = $activetrail_api->get_contact_fields();
		
		$unfiltered = array();
		
		if (!empty($res_json)) {
			$unfiltered = json_decode($res_json, true);
		}
		
		$filtered = array();
		
		if (!array_key_exists('Message', $unfiltered)) {
			foreach ($unfiltered as $key => $field_data) {
				if (!in_array(strtolower($field_data['field_source_column']), array('sms'))) {
					$filtered[] = $field_data;
				}
			}
		} else {
			$this->at_api_error_stack[] = $unfiltered['Message'];	
		}
		
		$this->at_cf7_metafields['form']['api'] = $filtered;
	}
	
	private function at_request_contact($app_token_id, $email) {
		$activetrail_api = new Activetrail_Api(array('app_token_id' => $app_token_id));
		
		$res_json = $activetrail_api->get_contact(array('SearchTerm' => $email));
		
		if (!empty($res_json)) {
			$response = json_decode($res_json, true);
		}
		
		if (array_key_exists('Message', $response)) {
			return false;
		} else if (isset($response[0])) {
			return $response[0];
		}

		//return false or return response??
	}

}