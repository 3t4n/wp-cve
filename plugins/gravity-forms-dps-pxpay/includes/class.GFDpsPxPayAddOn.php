<?php
namespace webaware\gf_dpspxpay;

use GFAPI;
use GFCommon;
use GFFormDisplay;
use GFFormsModel;
use GFPaymentAddOn;
use GFSalesforce;
use GFZapier;

if (!defined('ABSPATH')) {
	exit;
}

/**
* implement a Gravity Forms Payment Add-on instance
*/
class AddOn extends GFPaymentAddOn {

	protected $dpsReturnArgs;							// data returned in Windcave callback
	protected $validationMessages;						// any validation messages picked up for the form as a whole
	protected $urlPaymentForm;							// URL for payment form where purchaser will enter credit card details
	protected $feed = null;								// current feed mapping form fields to payment fields
	protected $feedDefaultFieldMap;						// map of default fields for feed
	protected $error_msg;								// error message stored between steps

	/**
	* static method for getting the instance of this singleton object
	* @return self
	*/
	public static function get_instance() {
		static $instance = null;

		if ($instance === null) {
			$instance = new self();
		}

		return $instance;
	}

	/**
	* declare detail to GF Add-On framework
	*/
	public function __construct() {
		$this->_version						= GFDPSPXPAY_PLUGIN_VERSION;
		$this->_min_gravityforms_version	= MIN_VERSION_GF;
		$this->_slug						= 'gravity-forms-dps-pxpay';
		$this->_path						= GFDPSPXPAY_PLUGIN_NAME;
		$this->_full_path					= GFDPSPXPAY_PLUGIN_FILE;
		$this->_title						= 'Windcave Free';				// NB: no localisation yet
		$this->_short_title					= 'Windcave Free';				// NB: no localisation yet
		$this->_supports_callbacks			= true;

		// define capabilities in case role/permissions have been customised (e.g. Members plugin)
		$this->_capabilities_settings_page	= 'gravityforms_edit_settings';
		$this->_capabilities_form_settings	= 'gravityforms_edit_forms';
		$this->_capabilities_uninstall		= 'gravityforms_uninstall';

		parent::__construct();

		add_action('init', [$this, 'lateLocalise'], 9);
		add_filter('gform_validation_message', [$this, 'gformValidationMessage'], 10, 2);
		add_filter('gform_custom_merge_tags', [$this, 'gformCustomMergeTags'], 10, 4);
		add_filter('gform_replace_merge_tags', [$this, 'gformReplaceMergeTags'], 10, 7);
		add_action('wp', [$this, 'processFormConfirmation'], 5);		// process redirect to GF confirmation
		add_action('gform_payment_details', [$this, 'gformPaymentDetails'], 9, 2);

		// handle deferrals
		add_filter("gform_{$this->_slug}_feed_settings_fields", [$this, 'gformAddSettingsDelayed']);
		add_filter('gform_is_delayed_pre_process_feed', [$this, 'gformIsDelayed'], 10, 4);
		add_filter('gform_disable_post_creation', [$this, 'gformDelayPost'], 10, 3);
		add_action('gform_after_submission', [$this, 'gformDelayOther'], 5, 2);
	}

	/**
	* late localisation of strings, after load_plugin_textdomain() has been called
	*/
	public function lateLocalise() {
		$this->_title			= esc_html_x('Windcave Free', 'add-on full title', 'gravity-forms-dps-pxpay');
		$this->_short_title		= esc_html_x('Windcave Free', 'add-on short title', 'gravity-forms-dps-pxpay');
	}

	/**
	* add our admin initialisation
	*/
	public function init_admin() {
		parent::init_admin();

		add_action('gform_payment_status', [$this, 'gformPaymentStatus'], 10, 3);
		add_action('gform_after_update_entry', [$this, 'gformAfterUpdateEntry'], 10, 2);
	}

	/**
	* run add-on framework setup routines, then check for upgrade requirements
	*/
	public function setup() {
		parent::setup();

		$old_settings = get_option('gfdpspxpay_plugin');

		if ($old_settings !== false) {
			// might be v1.x add-on needing upgrades
			new GFDpsPxPayUpdateV1($this->_slug);
		}
	}

	/**
	* null the add-on framework load of text domain, because we already did it, thanks.
	*/
	public function load_text_domain() {
	}

	/**
	* enqueue required styles
	*/
	public function styles() {
		$ver = SCRIPT_DEBUG ? time() : GFDPSPXPAY_PLUGIN_VERSION;

		$styles = [

			[
				'handle'		=> 'gfdpspxpay_admin',
				'src'			=> plugins_url('static/css/admin.css', GFDPSPXPAY_PLUGIN_FILE),
				'version'		=> $ver,
				'enqueue'		=> [
										[
											'admin_page'	=> ['plugin_settings', 'form_settings'],
											'tab'			=> [$this->_slug],
										],
									],
			],

		];

		return array_merge(parent::styles(), $styles);
	}

	/**
	* set full title of add-on as settings page title
	* @return string
	*/
	public function plugin_settings_title() {
		return esc_html__('Windcave Free settings', 'gravity-forms-dps-pxpay');
	}

	/**
	* set icon for settings page in GF < 2.5
	* @return string
	*/
	public function plugin_settings_icon() {
		return $this->get_svg_icon();
	}

	/**
	* set the icon for the settings page menu in GF >= 2.5
	* @return string
	*/
	public function get_menu_icon() {
		return $this->get_svg_icon();
	}

	/**
	* get SVG icon used for plugin
	* @return string
	*/
	protected function get_svg_icon() {
		return file_get_contents(GFDPSPXPAY_PLUGIN_ROOT . '/static/images/menu-icon.svg');
	}

	/**
	* specify the settings fields to be rendered on the plugin settings page
	* @return array
	*/
	public function plugin_settings_fields() {
		$settings = [
			[
				'title'					=> esc_html__('Live gateway settings', 'gravity-forms-dps-pxpay'),
				'fields'				=> [

					[
						'name'			=> 'userID',
						'label'			=> esc_html_x('User ID', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'text',
						'class'			=> 'medium',
						'autocorrect'	=> 'off',
						'autocapitalize' => 'off',
						'spellcheck'	=> 'false',
					],

					[
						'name'			=> 'userKey',
						'label'			=> esc_html_x('User Key', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'text',
						'class'			=> 'large',
						'autocorrect'	=> 'off',
						'autocapitalize' => 'off',
						'spellcheck'	=> 'false',
					],

				],
			],

			[
				'title'					=> esc_html__('Sandbox gateway settings', 'gravity-forms-dps-pxpay'),
				'description'			=> esc_html__('When your feed is configured for Test (Sandbox), it will send transactions to this gateway instead of the live gateway.', 'gravity-forms-dps-pxpay'),
				'fields'				=> [

					[
						'name'			=> 'testEnv',
						'label'			=> esc_html_x('Sandbox Environment', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'radio',
						'tooltip'		=> esc_html__('When Windcave sent you your user ID and password, they will have told you to use either SEC or UAT for your sandbox.', 'gravity-forms-dps-pxpay'),
						'choices'		=> [
							['value' => 'SEC', 'label' => 'SEC'],
							['value' => 'UAT', 'label' => 'UAT'],
						],
						'default_value'	=> 'UAT',
					],

					[
						'name'			=> 'testID',
						'label'			=> esc_html_x('Sandbox User ID', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'text',
						'class'			=> 'medium',
						'autocorrect'	=> 'off',
						'autocapitalize' => 'off',
						'spellcheck'	=> 'false',
					],

					[
						'name'			=> 'testKey',
						'label'			=> esc_html_x('Sandbox User Key', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'text',
						'class'			=> 'large',
						'autocorrect'	=> 'off',
						'autocapitalize' => 'off',
						'spellcheck'	=> 'false',
					],

					[
						'type'			=> 'save',
						'messages'		=> ['success' => esc_html__('Settings updated', 'gravity-forms-dps-pxpay')],
					],

				],
			],
		];

		return $settings;
	}

	/**
	* title of feed settings
	* @return string
	*/
	public function feed_settings_title() {
		return esc_html__('Windcave Free transaction settings', 'gravity-forms-dps-pxpay');
	}

	/**
	* columns to display in list of feeds
	* @return array
	*/
	public function feed_list_columns() {
		$columns = [
			'feedName'					=> esc_html_x('Feed name', 'feed field name', 'gravity-forms-dps-pxpay'),
			'feedItem_useTest'			=> esc_html_x('Mode', 'feed field name', 'gravity-forms-dps-pxpay'),
			'feedItem_paymentMethod'	=> esc_html_x('Payment method', 'feed field name', 'gravity-forms-dps-pxpay'),
		];

		return $columns;
	}

	/**
	* feed list value for payment mode
	* @param array $item
	* @return string
	*/
	protected function get_column_value_feedItem_paymentMethod($item) {
		switch (rgars($item, 'meta/paymentMethod')) {

			case 'authorize':
				$value = esc_html_x('Authorize', 'payment method', 'gravity-forms-dps-pxpay');
				break;

			default:
				$value = esc_html_x('Capture', 'payment method', 'gravity-forms-dps-pxpay');
				break;

		}

		return $value;
	}

	/**
	* feed list value for payment mode
	* @param array $item
	* @return string
	*/
	protected function get_column_value_feedItem_useTest($item) {
		switch (rgars($item, 'meta/useTest')) {

			case '0':
				$value = esc_html_x('Live', 'payment transaction mode', 'gravity-forms-dps-pxpay');
				break;

			case '1':
				$value = esc_html_x('Test', 'payment transaction mode', 'gravity-forms-dps-pxpay');
				break;

			default:
				$value = '';
				break;

		}

		return $value;
	}

	/**
	* configure the fields in a feed
	* @return array
	*/
	public function feed_settings_fields() {
		$this->setFeedDefaultFieldMap();

		// set default transaction type to prevent User Rego add-on breaking new feeds with unmet prerequisite
		$current = $this->get_current_settings();
		if ($current === null) {
			$this->set_settings(['transactionType' => 'product']);
		}

		$fields = [

			#region "core settings"

			[
				'fields' => [

					[
						'name'   		=> 'feedName',
						'label'  		=> esc_html_x('Feed name', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'   		=> 'text',
						'class'			=> 'medium',
						'tooltip'		=> esc_html__('Give this feed a name, to differentiate it from other feeds.', 'gravity-forms-dps-pxpay'),
						'required'		=> '1',
					],

					[
						'name'   		=> 'useTest',
						'label'  		=> esc_html_x('Mode', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'   		=> 'radio',
						'tooltip'		=> esc_html__('Credit cards will not be processed in Test mode. Special card numbers must be used.', 'gravity-forms-dps-pxpay'),
						'choices'		=> [
							['value' => '0', 'label' => esc_html_x('Live', 'payment transaction mode', 'gravity-forms-dps-pxpay')],
							['value' => '1', 'label' => esc_html_x('Test', 'payment transaction mode', 'gravity-forms-dps-pxpay')],
						],
						'default_value'	=> '1',
					],

					[
						'name'   		=> 'paymentMethod',
						'label'  		=> esc_html_x('Payment Method', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'   		=> 'radio',
						'tooltip'		=> esc_html__("Capture processes the payment immediately. Authorize holds the amount on the customer's card for processing later.", 'gravity-forms-dps-pxpay')
										.  '<br/><br/>'
										.  esc_html__('Authorize transactions can be completed manually in Payline. Perform a transaction search, and look for its Complete button.', 'gravity-forms-dps-pxpay'),
						'choices'		=> [
							['value' => 'capture',   'label' => esc_html_x('Capture', 'payment method', 'gravity-forms-dps-pxpay')],
							['value' => 'authorize', 'label' => esc_html_x('Authorize', 'payment method', 'gravity-forms-dps-pxpay')],
						],
						'default_value'	=> 'capture',
					],

					[
						'name'   		=> 'transactionType',
						'type'   		=> 'hidden',
						'default_value'	=> 'product',
					],

				],
			],

			#endregion "core settings"

			#region "mapped fields"

			[
				'title'					=> esc_html__('Mapped Field Settings', 'gravity-forms-dps-pxpay'),
				'fields'				=> [

					[
						'name'			=> 'billingInformation',
						'type'			=> 'field_map',
						'field_map'		=> $this->billing_info_fields(),
					],

				],
			],

			#endregion "mapped fields"

			#region "hosted page settings"

			[
				'title'					=> esc_html__('Hosted Page Settings', 'gravity-forms-dps-pxpay'),
				'id'					=> 'gfdpspxpay-settings-shared',
				'fields'				=> [

					[
						'name'			=> 'cancelURL',
						'label'			=> esc_html_x('Cancel URL', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'text',
						'class'  		=> 'large merge-tag-support mt-position-right mt-hide_all_fields mt-option-url',
						'placeholder'	=> esc_html_x('Leave empty to use default Gravity Forms confirmation handler', 'field placeholder', 'gravity-forms-dps-pxpay'),
						'tooltip'		=> esc_html__('Redirect to this URL if the transaction is canceled.', 'gravity-forms-dps-pxpay')
										.  '<br/><br/>'
										.  esc_html__('Please note: standard Gravity Forms submission logic applies if the transaction is successful.', 'gravity-forms-dps-pxpay'),
					],

					[
						'name'			=> 'allow_retry',
						'label'			=> esc_html_x('Retry Transaction', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'checkbox',
						'choices'		=> [
							[
								'name'			=> 'allowRetry',
								'label'			=> esc_html__('Allow the customer to retry a failed or canceled transaction', 'gravity-forms-dps-pxpay'),
								'default_value'	=> '1',
							],
						],
					],

					[
						'name'			=> 'post_payment_actions',
						'label'			=> esc_html_x('Post Payment Actions', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'checkbox',
						'choices'		=> [
							['name' => 'delayPost', 'label' => esc_html__('Create post only when payment is received.', 'gravity-forms-dps-pxpay')],
						],
						'tooltip'		=> esc_html__('Select which actions should only occur after transaction has been completed.', 'gravity-forms-dps-pxpay')
										.  '<br/><br/>'
										.  esc_html__('By default, the transaction must be successful to trigger these actions, or there must be no transaction. You can change that with the Delayed Execute setting.', 'gravity-forms-dps-pxpay'),
					],

					[
						'name'			=> 'execDelayed',
						'label'			=> esc_html_x('Delayed Execute', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'   		=> 'radio',
						'tooltip'		=> __('The delayed actions above will be processed according to these options.', 'gravity-forms-dps-pxpay'),
						'choices'		=> [
							['value' => 'success',      'label' => esc_html_x('Execute if transaction was successful, or if there was no transaction', 'delayed execute mode', 'gravity-forms-dps-pxpay')],
							['value' => 'always',       'label' => esc_html_x('Delay until transaction completes, and then always execute', 'delayed execute mode', 'gravity-forms-dps-pxpay')],
							['value' => 'success_only', 'label' => esc_html_x('Only execute if there was a successful transaction (overrides other feeds)', 'delayed execute mode', 'gravity-forms-dps-pxpay')],
						],
						'default_value'	=> 'success',
					],

				],
			],

			#endregion "hosted page settings"

			#region "conditional processing settings"

			[
				'title'					=> esc_html__('Feed Conditions', 'gravity-forms-dps-pxpay'),
				'fields'				=> [

					[
						'name'			=> 'condition',
						'label'			=> esc_html_x('Windcave condition', 'feed field name', 'gravity-forms-dps-pxpay'),
						'type'			=> 'feed_condition',
						'checkbox_label' => esc_html_x('Enable', 'checkbox label', 'gravity-forms-dps-pxpay'),
						'instructions'	=> esc_html_x('Send to Windcave if', 'feed conditions', 'gravity-forms-dps-pxpay'),
						'tooltip'		=> esc_html__('When the Windcave condition is enabled, form submissions will only be sent to Windcave when the condition is met. When disabled, all form submissions will be sent to Windcave.', 'gravity-forms-dps-pxpay'),
					],

				],
			],

			#endregion "conditional processing settings"

		];

		return $fields;
	}

	/**
	* title of fields column for mapped fields
	* @return string
	*/
	public function field_map_title() {
		return esc_html_x('PxPay field', 'mapped fields title', 'gravity-forms-dps-pxpay');
	}

	/**
	* build map of field types to fields, for default field mappings
	*/
	protected function setFeedDefaultFieldMap() {
		$this->feedDefaultFieldMap = [];

		$form_id = rgget( 'id' );
		$form = GFFormsModel::get_form_meta( $form_id );

		if (!isset($this->feedDefaultFieldMap['billingInformation_description'])) {
			$this->feedDefaultFieldMap['billingInformation_description']			= 'form_title';
		}

		if (is_array($form['fields'])) {
			foreach ($form['fields'] as $field) {

				switch ($field->type) {

					case 'email':
						if (!isset($this->feedDefaultFieldMap['billingInformation_email'])) {
							$this->feedDefaultFieldMap['billingInformation_email']			= $field->id;
						}
						break;

				}
			}
		}
	}

	/**
	 * Prepend the name fields to the default billing_info_fields added by the framework.
	 *
	 * @return array
	 */
	public function billing_info_fields() {
		$fields = [
			[
				'name' => 'description',
				'label' => esc_html_x('Invoice Description', 'mapped field name', 'gravity-forms-dps-pxpay'),
				'required' => false,
			],
			[
				'name' => 'txn_data1',
				'label' => esc_html_x('TxnData1', 'mapped field name', 'gravity-forms-dps-pxpay'),
				'required' => false,
			],
			[
				'name' => 'txn_data2',
				'label' => esc_html_x('TxnData2', 'mapped field name', 'gravity-forms-dps-pxpay'),
				'required' => false,
			],
			[
				'name' => 'txn_data3',
				'label' => esc_html_x('TxnData3', 'mapped field name', 'gravity-forms-dps-pxpay'),
				'required' => false,
			],
			[
				'name' => 'email',
				'label' => esc_html_x('Email Address', 'mapped field name', 'gravity-forms-dps-pxpay'),
				'required' => false,
			],
		];

		return $fields;
	}

	/**
	* override to set default mapped field selections from first occurring field of type
	* @param  array $field
	* @return string|null
	*/
	public function get_default_field_select_field($field) {
		if (!empty($this->feedDefaultFieldMap[$field['name']])) {
			return $this->feedDefaultFieldMap[$field['name']];
		}

		return parent::get_default_field_select_field($field);
	}

	/**
	* specify where the "Post Payment Action" setting should appear on the payment add-on feed
	* @param string $feed_slug
	* @return array
	*/
	public function get_post_payment_actions_config( $feed_slug ) {
		return [
			'position' => 'after',
			'setting'  => 'options',
		];
	}

	/**
	* add fields for delayed actions
	* @param array $fields
	* @return array
	*/
	public function gformAddSettingsDelayed($fields) {

		// detect Gravity Forms < 2.4.14 (which added automatic support for post payment actions to non-PayPal payment add-ons)
		if (!method_exists($this, 'add_post_payment_actions')) {
			// handle add-ons that support delayed payment conventions
			$addons = self::get_registered_addons();
			foreach ($addons as $class_name) {
				if (method_exists($class_name, 'get_instance')) {
					$addon = call_user_func([$class_name, 'get_instance']);
					if (!empty($addon->delayed_payment_integration)) {
						$fields = $addon->add_paypal_post_payment_actions($fields);
					}
				}
			}
		}

		// manually add some supported add-ons that don't comply with delayed payment conventions
		if (method_exists('GFZapier', 'add_paypal_post_payment_actions')) {
			// Zapier add-on versions 1.6 - 3.1
			$fields = GFZapier::add_paypal_post_payment_actions($fields, $this);
		}

		// manually add the old free Salesforce integration if installed, for backwards compatibility
		if (class_exists('KWS_GF_Salesforce', false)) {
			$field = $this->get_field('post_payment_actions', $fields);
			$field['choices'][] = [
				'name'		=> 'delay_gravity-forms-salesforce',
				'label'		=> esc_html__('Send feed to Salesforce only when payment is received', 'gravity-forms-dps-pxpay'),
				'tooltip'	=> esc_html__('Supports the legacy free Gravity Forms Salesforce add-on by Zack Katz.', 'gravity-forms-dps-pxpay'),
			];
			$fields = $this->replace_field('post_payment_actions', $field, $fields);
		}

		return $fields;
	}

	/**
	* process form validation
	* @param array $data an array with elements is_valid (boolean) and form (array of form elements)
	* @return array
	*/
	public function validation($data) {
		try {
			$data = parent::validation($data);

			if ($data['is_valid'] && $this->is_payment_gateway) {
				$form  = $data['form'];

				// make sure form hasn't already been submitted / processed
				if (has_form_been_processed($form['id'])) {
					throw new GFDpsPxPayException(__('Payment already submitted and processed - please close your browser window.', 'gravity-forms-dps-pxpay'));
				}

				// set hook to request redirect URL
				add_filter('gform_entry_post_save', [$this, 'requestRedirectUrl'], 9, 2);
			}
		}
		catch (GFDpsPxPayException $e) {
			$data['is_valid'] = false;
			$this->validationMessages[] = nl2br(esc_html($e->getMessage()));
		}

		return $data;
	}

	/**
	* pre-process feeds to ensure that they have necessary gateway credentials
	* @param array $feeds
	* @param array $entry
	* @param array $form
	* @return array
	* @throws GFDpsPxPayException
	*/
	public function pre_process_feeds($feeds, $entry, $form) {
		if (is_array($feeds)) {
			foreach ($feeds as $feed) {
				// feed must be active and meet feed conditions, if any
				if (!$feed['is_active'] || !$this->is_feed_condition_met($feed, $form, [])) {
					continue;
				}

				// make sure that gateway credentials have been set for feed, or globally
				$creds = new GFDpsPxPayCredentials($this, !empty($feed['meta']['useTest']));
				if ($creds->isIncomplete()) {
					throw new GFDpsPxPayException(__('Incomplete credentials for Windcave PxPay payment; please tell the web master.', 'gravity-forms-dps-pxpay'));
				}
			}
		}

		return $feeds;
	}

	/**
	* attempt to get shared page URL for transaction
	* @param array $entry the form entry
	* @param array $form the form submission data
	* @return array
	*/
	public function requestRedirectUrl($entry, $form) {
		$feed				= $this->current_feed;

		if ( !$this->current_submission_data ) {
			$this->current_submission_data = $this->get_submission_data($feed, $form, $entry);
		}

		$submission_data = $this->current_submission_data;
		$payment_amount  = $this->current_submission_data['payment_amount'] ? $this->current_submission_data['payment_amount'] : $entry['payment_amount'];

		$this->log_debug('========= initiating transaction request');
		$this->log_debug(sprintf('%s: feed #%d - %s', __FUNCTION__, $feed['id'], $feed['meta']['feedName']));

		try {
			$paymentReq = $this->getPaymentRequest($submission_data, $feed, $form, $entry);

			// record some payment meta
			gform_update_meta($entry['id'], META_TRANSACTION_ID, $paymentReq->transactionNumber);
			$entry[META_TRANSACTION_ID] = $paymentReq->transactionNumber;
			gform_update_meta($entry['id'], META_FEED_ID, $feed['id']);
			$entry[META_FEED_ID] = $feed['id'];

			$response = $paymentReq->requestSharedPage();

			if ($response->isValid && !empty($response->URI)) {
				$this->urlPaymentForm = $response->URI;
				GFFormsModel::update_lead_property($entry['id'], 'payment_status', 'Processing');
				$entry['payment_status']	= 'Processing';
			}
			else {
				$entry['payment_status']	= 'Failed';
				$entry['payment_date']		= date('Y-m-d H:i:s');

				$paymentMethod = rgar($feed['meta'], 'paymentMethod', 'capture');

				$this->log_debug(sprintf('%s: failed', __FUNCTION__));

				$error_msg = esc_html__('Transaction request failed', 'gravity-forms-dps-pxpay');

				$note = $this->getFailureNote($paymentMethod, [$error_msg]);
				$this->add_note($entry['id'], $note, 'error');

				// record payment failure, and set hook for displaying error message
				$this->error_msg = $error_msg;
				add_filter('gform_confirmation', [$this, 'displayPaymentFailure'], 1000, 4);
			}
		}
		catch (GFDpsPxPayException $e) {
			$this->log_error(__FUNCTION__ . ': exception = ' . $e->getMessage());

			// record payment failure, and set hook for displaying error message
			GFFormsModel::update_lead_property($entry['id'], 'payment_status', 'Failed');
			$this->error_msg = $e->getMessage();
			add_filter('gform_confirmation', [$this, 'displayPaymentFailure'], 1000, 4);
		}

		return $entry;
	}

	/**
	* display a payment request failure message
	* @param mixed $confirmation text or redirect for form submission
	* @param array $form the form submission data
	* @param array $entry the form entry
	* @param bool $ajax form submission via AJAX
	* @return mixed
	*/
	public function displayPaymentFailure($confirmation, $form, $entry, $ajax) {
		// record entry's unique ID in database, to signify that it has been processed so don't attempt another payment!
		gform_update_meta($entry['id'], META_UNIQUE_ID, GFFormsModel::get_form_unique_id($form['id']));

		// create a "confirmation message" in which to display the error
		$anchor = get_form_confirmation_anchor($form);
		$cssClass = rgar($form, 'cssClass') . ' gfdpspxpay-transaction-message gfdpspxpay-request-failure';
		$error_msg = wpautop($this->error_msg);

		ob_start();
		require GFDPSPXPAY_PLUGIN_ROOT . 'views/error-payment-failure.php';
		return ob_get_clean();
	}

	/**
	* create and populate a Payment Request object
	* @param array $formData
	* @param array $feed
	* @param array $form
	* @param array|false $entry
	* @return GFDpsPxPayAPI
	*/
	protected function getPaymentRequest($formData, $feed, $form, $entry = false) {
		// build a payment request and execute on API
		$useTest	= !empty($feed['meta']['useTest']);
		$creds		= new GFDpsPxPayCredentials($this, !empty($feed['meta']['useTest']));
		$paymentReq	= new GFDpsPxPayAPI($creds);

		// generate a unique transaction ID to avoid collisions, e.g. between different installations using the same gateway account
		// use last three characters of entry ID as prefix, to avoid collisions with entries created at same microsecond
		// uniqid() generates 13-character string, plus 3 characters from entry ID = 16 characters which is max for field
		$transactionID = uniqid(substr($entry['id'], -3));

		// allow plugins/themes to modify transaction ID; NB: must remain unique for gateway account!
		$transactionID = apply_filters('gfdpspxpay_invoice_trans_number', $transactionID, $form);

		$capture = (rgar($feed['meta'], 'paymentMethod', 'capture') !== 'authorize');

		$paymentReq->amount					= $formData['payment_amount'];
		$paymentReq->currency				= GFCommon::get_currency();
		$paymentReq->transactionNumber		= $transactionID;
		$paymentReq->invoiceReference		= $formData['description'];
		$paymentReq->txnType				= $capture ? GFDpsPxPayAPI::TXN_TYPE_CAPTURE : GFDpsPxPayAPI::TXN_TYPE_AUTHORISE;
		$paymentReq->urlSuccess				= home_url($useTest ? ENDPOINT_RETURN_TEST : ENDPOINT_RETURN);
		$paymentReq->urlFail				= $paymentReq->urlSuccess;		// NB: redirection will happen after transaction status is updated

		// billing details
		$paymentReq->txn_data1				= $formData['txn_data1'];
		$paymentReq->txn_data2				= $formData['txn_data2'];
		$paymentReq->txn_data3				= $formData['txn_data3'];
		$paymentReq->emailAddress			= $formData['email'];

		// allow plugins/themes to modify invoice description and reference, and set option fields
		$paymentReq->invoiceReference	= apply_filters('gfdpspxpay_invoice_ref', $paymentReq->invoiceReference, $form);
		$paymentReq->txn_data1			= apply_filters('gfdpspxpay_invoice_txndata1', $paymentReq->txn_data1, $form);
		$paymentReq->txn_data2			= apply_filters('gfdpspxpay_invoice_txndata2', $paymentReq->txn_data2, $form);
		$paymentReq->txn_data3			= apply_filters('gfdpspxpay_invoice_txndata3', $paymentReq->txn_data3, $form);
		$paymentReq->options			= apply_filters('gfdpspxpay_options', null, $form);

		return $paymentReq;
	}

	/**
	* alter the validation message
	* @param string $msg
	* @param array $form
	* @return string
	*/
	public function gformValidationMessage($msg, $form) {
		if (!empty($this->validationMessages)) {
			$msg = sprintf('<div class="validation_error">%s</div>', implode('<br />', $this->validationMessages));
		}

		return $msg;
	}

	/**
	* return redirect URL for the payment gateway
	* @param array $feed
	* @param array $submission_data
	* @param array $form
	* @param array $entry
	* @return string
	*/
	public function redirect_url($feed, $submission_data, $form, $entry) {
		if ($this->urlPaymentForm) {
			// record entry's unique ID in database, to signify that it has been processed so don't attempt another payment!
			gform_update_meta($entry['id'], META_UNIQUE_ID, GFFormsModel::get_form_unique_id($form['id']));
		}

		return $this->urlPaymentForm;
	}

	/**
	* test for valid callback from gateway
	*/
	public function is_callback_valid() {
		$request_uri = parse_url($_SERVER['REQUEST_URI']);

		// path must contain our callback slug
		if (empty($request_uri['path']) || strpos($request_uri['path'], ENDPOINT_RETURN) === false) {
			return false;
		}

		// there must be a query string
		if (empty($request_uri['query'])) {
			return false;
		}

		// query string must have a result element
		parse_str($request_uri['query'], $args);
		if (!isset($args['result'])) {
			return false;
		}

		// set up for processing the callback after everything has loaded properly
		$this->dpsReturnArgs = wp_unslash($args);
		$this->dpsReturnArgs['useTest'] = strpos($request_uri['path'], ENDPOINT_RETURN_TEST) !== false;

		// stop WooCommerce Windcave Gateway from intercepting other integrations' transactions!
		unset($_GET['userid']);
		unset($_REQUEST['userid']);

		return true;
	}

	/**
	* process the gateway callback
	*/
	public function callback() {
		$this->log_debug('========= processing transaction result');

		$lock_id = false;
		$entry_was_locked = false;

		try {
			$creds				= new GFDpsPxPayCredentials($this, rgar($this->dpsReturnArgs, 'useTest', false));
			$paymentReq			= new GFDpsPxPayAPI($creds);
			$paymentReq->result	= rgar($this->dpsReturnArgs, 'result');
			$response			= $paymentReq->processResult();

			if (!$response->isValid) {
				return;
			}

			$transactionNumber = $response->TxnId;

			// attempt to lock entry
			$lock_id = 'gfdpspxpay_elock_' . $transactionNumber;
			$entry_was_locked = get_option($lock_id);
			if (!$entry_was_locked) {
				update_option($lock_id, time());
			}
			else {
				$this->log_debug("transaction $transactionNumber was locked");
			}

			$search = [
				'field_filters' => [
					[
						'key'		=> META_TRANSACTION_ID,
						'value'		=> $transactionNumber,
					],
				],
			];
			$entries = GFAPI::get_entries(0, $search);

			// must have an entry, or nothing to do
			if (empty($entries)) {
				throw new GFDpsPxPayException(sprintf(__('Invalid transaction number: %s', 'gravity-forms-dps-pxpay'), $transactionNumber));
			}
			$entry = $entries[0];
			$lead_id = rgar($entry, 'id');

			$form = GFFormsModel::get_form_meta($entry['form_id']);
			$feed = $this->getFeed($lead_id);

			// capture current state of lead
			$initial_status = $entry['payment_status'];

			$capture = (rgar($feed['meta'], 'paymentMethod', 'capture') !== 'authorize');

			if (rgar($entry, 'payment_status') === 'Processing') {
				// update lead entry, with success/fail details
				if ($response->Success) {
					$action = [
						'type'							=> 'complete_payment',
						'payment_status'				=> $capture ? 'Paid' : 'Pending',
						'payment_date'					=> date('Y-m-d H:i:s'),
						'amount'						=> empty($response->TotalAmount) ? $response->AmountSettlement : $response->TotalAmount,
						'currency'						=> $response->CurrencySettlement,
						'transaction_id'				=> $response->DpsTxnRef,
					];
					$action['note']						=  $this->getPaymentNote($capture, $action, $response->getProcessingMessages());
					$entry[META_AUTHCODE]				=  $response->AuthCode;
					$entry[META_GATEWAY_TXN_ID]			=  $response->DpsTxnRef;
					$entry['currency']					=  $response->CurrencySettlement;
					if (!empty($response->AmountSurcharge)) {
						$entry[META_SURCHARGE]			=  $response->AmountSurcharge;
					}

					if (!$entry_was_locked) {
						$this->complete_payment($entry, $action);
					}

					$this->log_debug(sprintf('%s: success, date = %s, id = %s, status = %s, amount = %s',
						__FUNCTION__, $entry['payment_date'], $entry['transaction_id'], $entry['payment_status'], $entry['payment_amount']));
					$this->log_debug(sprintf('%s: %s', __FUNCTION__, $response->ResponseText));
					$this->log_debug(sprintf('%s: TxnMac = %s', __FUNCTION__, $response->TxnMac));
				}
				else {
					$entry['payment_status']			=  'Failed';
					$entry['payment_date']				=  date('Y-m-d H:i:s');
					$entry['currency']					=  $response->CurrencySettlement;

					// record empty bank authorisation code, so that we can test for it
					$entry[META_AUTHCODE]			=  '';

					if (!$entry_was_locked) {
						// fail_payment() below doesn't update whole entry, so we need to do it here
						GFAPI::update_entry($entry);

						$note = $this->getFailureNote($capture, $response->getProcessingMessages());

						$action = [
							'type'							=> 'fail_payment',
							'payment_status'				=> 'Failed',
							'note'							=> $note,
						];
						$this->fail_payment($entry, $action);
					}

					$this->log_debug(sprintf('%s: failed; %s', __FUNCTION__, $this->getErrorsForLog($response->getProcessingMessages())));
				}

				// if order hasn't been fulfilled, process any deferred actions
				if (!$entry_was_locked && $initial_status === 'Processing') {
					$this->log_debug('processing deferred actions');

					$this->processDelayed($feed, $entry, $form);

					// allow hookers to trigger their own actions
					$hook_status = $response->Success ? 'approved' : 'failed';
					do_action("gfdpspxpay_process_{$hook_status}", $entry, $form, $feed);
				}
			}

			if ($entry['payment_status'] === 'Failed' && $feed['meta']['cancelURL']) {
				// on failure, redirect to failure page if set
				// after first replacing any merge tags in the redirect URL
				$redirect_url = $feed['meta']['cancelURL'];
				$redirect_url = GFCommon::replace_variables( trim( $redirect_url ), $form, $entry, false, true, true, 'text' );
				$redirect_url = esc_url_raw($redirect_url);
			}
			else {
				// otherwise, redirect to Gravity Forms page, passing form and lead IDs, encoded to deter simple attacks
				$query = [
					'form_id'	=> $entry['form_id'],
					'lead_id'	=> $entry['id'],
				];
				if ($entry['payment_status'] === 'Failed') {
					$query['cancelled'] = $response->WasUserCancelled ? 1 : 0;
				}
				$query = encode_confirmation_values($query);
				$redirect_url = esc_url_raw(add_query_arg(ENDPOINT_CONFIRMATION, $query, $entry['source_url']));
			}

			// clear lock if we set one
			if (!$entry_was_locked) {
				delete_option($lock_id);
			}

			wp_safe_redirect($redirect_url);
			exit;
		}
		catch (GFDpsPxPayException $e) {
			// TODO: what now?
			echo nl2br(esc_html($e->getMessage()));
			$this->log_error(__FUNCTION__ . ': ' . $e->getMessage());

			// clear lock if we set one
			if ($lock_id && !$entry_was_locked) {
				delete_option($lock_id);
			}
			exit;
		}
	}

	/**
	* get / cache whether form has any active feeds with execDelayed == success_only and action is to be delayed
	* @param int $form_id
	* @param string $action
	* @return bool
	*/
	protected function formHasSuccessOnly($form_id, $action) {
		static $cacheHasSuccessOnly = [];

		if (!isset($cacheHasSuccessOnly[$form_id])) {

			$cacheHasSuccessOnly[$form_id] = [];
			$feeds = $this->get_active_feeds($form_id);

			foreach ($feeds as $feed) {
				$success_only = rgar($feed['meta'], 'execDelayed', 'success') === 'success_only';

				foreach ($feed['meta'] as $name => $value) {
					if ($name !== 'delayPost' && substr($name, 0, 6) !== 'delay_') {
						continue;
					}

					if (empty($cacheHasSuccessOnly[$form_id][$name])) {
						$cacheHasSuccessOnly[$form_id][$name] = $success_only && !empty($value);
					}
				}
			}

		}

		return !empty($cacheHasSuccessOnly[$form_id][$action]);
	}

	/**
	* filter whether post creation from form is enabled (yet)
	* @param bool $is_delayed
	* @param array $form
	* @param array $entry
	* @return bool
	*/
	public function gformDelayPost($is_delayed, $form, $entry) {
		if (!$is_delayed) {
			if ($this->formHasSuccessOnly($form['id'], 'delayPost')) {
				$is_delayed = true;
			}
			else {
				$feed = $this->get_single_submission_feed($entry);

				if ($feed) {
					switch (rgar($feed['meta'], 'execDelayed', 'success')) {

						case 'success':		// delay if there is something to charge, and execute if transaction was successful
						case 'always':		// delay if there is something to charge, and then always execute
							$is_delayed = $entry['payment_status'] === 'Processing' && !empty($feed['meta']['delayPost']);
							break;

						default:
							// already handled
							break;

					}
				}
			}
			if ($is_delayed) {
				$this->log_debug(sprintf('delay post creation: form id %s, lead id %s', $form['id'], $entry['id']));
			}
		}

		return $is_delayed;
	}

	/**
	* filter whether form delays some actions (e.g. MailChimp)
	* @param bool $is_delayed
	* @param array $form
	* @param array $entry
	* @param string $addon_slug
	* @return bool
	*/
	public function gformIsDelayed($is_delayed, $form, $entry, $addon_slug) {
		// don't bother if it's this addon!
		if ($addon_slug === $this->_slug) {
			return $is_delayed;
		}

		if (!$is_delayed) {
			if ($this->formHasSuccessOnly($form['id'], 'delay_' . $addon_slug)) {
				$is_delayed = true;
			}
			else {
				$feed = $this->get_single_submission_feed($entry);

				if ($feed) {
					switch (rgar($feed['meta'], 'execDelayed', 'success')) {

						case 'success':		// delay if there is something to charge, and execute if transaction was successful
						case 'always':		// delay if there is something to charge, and then always execute
							$is_delayed = $entry['payment_status'] === 'Processing' && !empty($feed['meta']['delay_' . $addon_slug]);
							break;

						default:
							// already handled
							break;

					}
				}
			}
			if ($is_delayed) {
				$this->log_debug(sprintf('delay %s: form id %s, lead id %s', $addon_slug, $form['id'], $entry['id']));
			}
		}

		return $is_delayed;
	}

	/**
	* disable "feeds" that don't subclass the feed add-on, like Salesforce
	* @param array $entry
	* @param array $form
	*/
	public function gformDelayOther($entry, $form) {
		$feed = $this->get_single_submission_feed($entry);

		$is_delayed = false;

		if ($feed) {
			switch (rgar($feed['meta'], 'execDelayed', 'success')) {

				case 'success':		// delay if there is something to charge, and execute if transaction was successful
				case 'always':		// delay if there is something to charge, and then always execute
					$is_delayed = $entry['payment_status'] === 'Processing';
					break;

				default:
					// success_only is handled separately
					break;

			}
		}

		if (($is_delayed && !empty($feed['meta']['delay_gravity-forms-salesforce'])) || $this->formHasSuccessOnly($form['id'], 'delay_gravity-forms-salesforce')) {
			if (has_action('gform_after_submission', ['GFSalesforce', 'export'])) {
				$this->log_debug(sprintf('delay gravity-forms-salesforce feeds: form id %s, lead id %s', $form['id'], $entry['id']));
				remove_action('gform_after_submission', ['GFSalesforce', 'export'], 10, 2);
			}

		}
	}

	/**
	* process any delayed actions
	* @param array $feed
	* @param array $entry
	* @param array $form
	*/
	protected function processDelayed($feed, $entry, $form) {
		// default to only performing delayed actions if payment was successful, unless feed opts to always execute
		switch (rgar($feed['meta'], 'execDelayed', 'success')) {

			case 'always':
				$execute_delayed = true;
				break;

			default:
				$execute_delayed = in_array($entry['payment_status'], ['Paid', 'Pending']);
				break;

		}

		if (!empty($feed['meta']['delayPost'])) {
			if (apply_filters('gfdpspxpay_delayed_post_create', $execute_delayed, $entry, $form, $feed)) {
				$this->log_debug(sprintf('executing delayed post creation; form id %s, lead id %s', $form['id'], $entry['id']));
				GFFormsModel::create_post($form, $entry);
			}
		}

		if ($execute_delayed) {
			if (method_exists($this, 'trigger_payment_delayed_feeds')) {
				// Gravity Forms 2.4.13+
				$this->trigger_payment_delayed_feeds($entry['transaction_id'], $feed, $entry, $form);
			}
			else {
				// Gravity Forms < 2.4.13
				if (has_action('gform_paypal_fulfillment')) {
					$this->log_debug(sprintf('calling gform_paypal_fulfillment action; form id %s, entry id %s', $form['id'], $entry['id']));
					do_action('gform_paypal_fulfillment', $entry, $feed, rgar($entry, 'transaction_id'), rgar($entry, 'payment_amount'));
				}
			}

			$this->maybeExecuteSalesforce($feed, $entry, $form);
		}
	}

	/**
	* maybe execute delayed Salesforce feed, if there is one
	* @param array $feed
	* @param array $entry
	* @param array $form
	*/
	public function maybeExecuteSalesforce($feed, $entry, $form) {
		if (!empty($feed['meta']['delay_gravity-forms-salesforce']) && method_exists('GFSalesforce', 'export')) {
			$this->log_debug(sprintf('executing delayed gravity-forms-salesforce feed: form id %s, entry id %s', $form['id'], $entry['id']));
			GFSalesforce::export($entry, $form);
		}
	}

	/**
	* allow edits to payment status
	* @param string $content
	* @param array $form
	* @param array $entry
	* @return string
	*/
	public function gformPaymentStatus($content, $form, $entry) {
		// make sure that we're editing the entry and are allowed to change it
		if ($this->canEditPaymentDetails($entry, 'edit')) {
			// create drop down for payment status
			ob_start();
			include GFDPSPXPAY_PLUGIN_ROOT . 'views/admin-entry-payment-status.php';
			$content = ob_get_clean();
		}

		return $content;
	}

	/**
	* update payment status if it has changed
	* @param array $form
	* @param int $entry_id
	*/
	public function gformAfterUpdateEntry($form, $entry_id) {
		// make sure we have permission
		check_admin_referer('gforms_save_entry', 'gforms_save_entry');

		$entry = GFFormsModel::get_lead($entry_id);

		// make sure that we're editing the entry and are allowed to change it
		if (!$this->canEditPaymentDetails($entry, 'update')) {
			return;
		}

		// make sure we have new values
		$payment_status = rgpost('payment_status');

		if (empty($payment_status)) {
			return;
		}

		$note = __('Payment information was manually updated.', 'gravity-forms-dps-pxpay');

		if ($entry['payment_status'] !== $payment_status) {
			// translators: 1: old payment status; 2: new payment status
			$note .= "\n" . sprintf(__('Payment status changed from %1$s to %2$s.', 'gravity-forms-dps-pxpay'), $entry['payment_status'], $payment_status);
			$entry['payment_status'] = $payment_status;
		}


		GFAPI::update_entry($entry);

		$user = wp_get_current_user();
		GFFormsModel::add_note($entry['id'], $user->ID, $user->display_name, esc_html($note));
	}

	/**
	* payment processed and recorded, show confirmation message / page
	*/
	public function processFormConfirmation() {
		// check for redirect to Gravity Forms page with our encoded parameters
		if (isset($_GET[ENDPOINT_CONFIRMATION])) {
			do_action('gfdpspxpay_process_confirmation');

			// decode the encoded form and lead parameters passed from the callback
			$query = decode_confirmation_values($_GET[ENDPOINT_CONFIRMATION]);

			// make sure we have a match
			if ($query) {
				// load form and lead data
				$form = GFFormsModel::get_form_meta($query['form_id']);
				$lead = GFFormsModel::get_lead($query['lead_id']);
				$this->current_feed = $this->getFeed($lead['id']);

				do_action('gfdpspxpay_process_confirmation_parsed', $lead, $form);

				// ensure that we can set up the confirmation page
				if (!class_exists('GFFormDisplay', false)) {
					require_once(GFCommon::get_base_path() . '/form_display.php');
				}

				// check for failed payment (error / cancellation) that can be retried
				if ($this->canRetryTransaction($form, $lead)) {
					// if asked to retry the payment, set up the transaction request and redirect to DPS
					$lead = $this->maybeRetryTransaction($form, $lead);

					// if we're still going, then need to build a retry / cancel confirmation message
					$confirmation = $this->getRetryConfirmationMesssage($form, $lead, !empty($query['cancelled']));
				}
				else {
					// regular confirmation as configured for the form
					$confirmation = GFFormDisplay::handle_confirmation($form, $lead, false);
				}

				// preload the GF submission, ready for processing the confirmation message
				GFFormDisplay::$submission[$form['id']] = [
					'is_confirmation'		=> true,
					'confirmation_message'	=> $confirmation,
					'form'					=> $form,
					'lead'					=> $lead,
				];

				// if it's a redirection (page or other URL) then do the redirect now
				if (is_array($confirmation) && isset($confirmation['redirect'])) {
					wp_safe_redirect($confirmation['redirect']);
					exit;
				}
			}
		}
	}

	/**
	* determine what to do on the confirmation page
	* @param array $form
	* @param array $entry
	* @return string
	*/
	protected function canRetryTransaction($form, $entry) {
		if (rgar($entry, 'payment_status') !== 'Failed') {
			// not a failed nor cancelled transaction; carry on
			return false;
		}

		if (rgar($this->current_feed['meta'], 'allowRetry', '') === '') {
			// feed is configured for no retry
			return false;
		}

		if (!empty($_GET['cancel_payment'])) {
			// customer cancelled from confirmation page / retry buttons
			return false;
		}

		return true;
	}

	/**
	* if customer has requested to retry a transaction, send them back to Windcave
	* @param array $form
	* @param array $entry
	* @return array
	*/
	protected function maybeRetryTransaction($form, $entry) {
		if (!empty($_GET['retry_payment'])) {
			$entry = $this->requestRedirectUrl($entry, $form);
			if ($entry['payment_status'] === 'Processing') {
				wp_redirect($this->urlPaymentForm);
				exit;
			}
		}

		return $entry;
	}

	/**
	* build a retry / cancel confirmation message
	* @param array $form
	* @param array $entry
	* @param bool $was_cancelled
	* @return string
	*/
	protected function getRetryConfirmationMesssage($form, $entry, $was_cancelled) {
		$submission_data	= $this->get_submission_data($this->current_feed, $form, $entry);
		$payment_amount		= GFCommon::to_money($submission_data['payment_amount']);

		$retry_link			= add_query_arg(array_merge($_GET, ['retry_payment'  => '1']), $entry['source_url']);
		$cancel_link		= add_query_arg(array_merge($_GET, ['cancel_payment' => '1']), $entry['source_url']);

		if ($was_cancelled) {
			$error_msg		= _x('The transaction was canceled.', 'retry payment message', 'gravity-forms-dps-pxpay');
		}
		else {
			$error_msg		= _x('There was an error with your payment. Please try again.', 'retry payment message', 'gravity-forms-dps-pxpay');
		}

		// create a "confirmation message" in which to display the error
		$anchor = get_form_confirmation_anchor($form);
		$cssClass = rgar($form, 'cssClass') . ' gfdpspxpay-transaction-message gfdpspxpay-transaction-failure';

		ob_start();
		require GFDPSPXPAY_PLUGIN_ROOT . 'views/error-payment-retry.php';
		return ob_get_clean();
	}

	/**
	* supported notification events
	* @param array $form
	* @return array
	*/
	public function supported_notification_events( $form ) {
		if (!$this->has_feed($form['id'])) {
			return false;
		}

		return [
			'complete_payment'		=> esc_html_x('Payment Completed', 'notification event', 'gravity-forms-dps-pxpay'),
			'fail_payment'			=> esc_html_x('Payment Failed', 'notification event', 'gravity-forms-dps-pxpay'),
		];
	}

	/**
	* activate and configure custom entry meta
	* @param array $entry_meta
	* @param int $form_id
	* @return array
	*/
	public function get_entry_meta($entry_meta, $form_id) {
		// not on feed admin screen
		if (is_admin()) {
			global $plugin_page;
			$subview = isset($_GET['subview']) ? $_GET['subview'] : '';

			if ($plugin_page === 'gf_edit_forms' && $subview === $this->_slug) {
				return $entry_meta;
			}
		}

		// duplicate of transaction_id as meta, so that it can be passed to other integrations (like Zapier)
		$entry_meta['gateway_txn_id'] = [
			'label'					=> esc_html_x('Transaction ID', 'entry meta label', 'gravity-forms-dps-pxpay'),
			'is_numeric'			=> false,
			'is_default_column'		=> false,
			'filter'				=> [
										'operators' => ['is', 'isnot'],
									],
		];

		$entry_meta['payment_gateway'] = [
			'label'					=> esc_html_x('Payment Gateway', 'entry meta label', 'gravity-forms-dps-pxpay'),
			'is_numeric'			=> false,
			'is_default_column'		=> false,
			'filter'				=> [
										'operators' => ['is', 'isnot'],
									],
		];

		$entry_meta[META_AUTHCODE] = [
			'label'					=> esc_html_x('AuthCode', 'entry meta label', 'gravity-forms-dps-pxpay'),
			'is_numeric'			=> false,
			'is_default_column'		=> false,
			'filter'				=> [
										'operators' => ['is', 'isnot'],
									],
		];

		$entry_meta[META_SURCHARGE] = [
			'label'					=> esc_html_x('Surcharge', 'entry meta label', 'gravity-forms-dps-pxpay'),
			'is_numeric'			=> true,
			'is_default_column'		=> false,
			'filter'				=> [
										'operators' => ['is', 'isnot', '>', '<'],
									],
		];

		return $entry_meta;
	}

	/**
	* add custom merge tags
	* @param array $merge_tags
	* @param int $form_id
	* @param array $fields
	* @param int $element_id
	* @return array
	*/
	public function gformCustomMergeTags($merge_tags, $form_id, $fields, $element_id) {
		if ($form_id) {
			$feeds = $this->get_feeds($form_id);
			if (!empty($feeds)) {
				// at least one feed for this add-on, so add our merge tags if nobody else has already
				$tags = array_flip(wp_list_pluck($merge_tags, 'tag'));

				$custom_tags = [
					['label' => esc_html_x('Transaction ID', 'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{transaction_id}'],
					['label' => esc_html_x('Auth Code',      'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{authcode}'],
					['label' => esc_html_x('Payment Amount', 'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{payment_amount}'],
					['label' => esc_html_x('Payment Status', 'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{payment_status}'],
					['label' => esc_html_x('Surcharge',      'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{surcharge}'],
					['label' => esc_html_x('Entry Date',     'merge tag label', 'gravity-forms-dps-pxpay'), 'tag' => '{date_created}'],
				];

				foreach ($custom_tags as $custom) {
					if (!isset($tags[$custom['tag']])) {
						$merge_tags[] = $custom;
					}
				}
			}
		}

		return $merge_tags;
	}

	/**
	* replace custom merge tags
	* @param string $text
	* @param array $form
	* @param array $entry
	* @param bool $url_encode
	* @param bool $esc_html
	* @param bool $nl2br
	* @param string $format
	* @return string
	*/
	public function gformReplaceMergeTags($text, $form, $entry, $url_encode, $esc_html, $nl2br, $format) {
		// check for invalid calls, e.g. Gravity Forms User Registration login form widget
		if (empty($form) || empty($entry)) {
			return $text;
		}

		$gateway = gform_get_meta($entry['id'], 'payment_gateway');

		if ($gateway === $this->_slug) {
			$authCode  = gform_get_meta($entry['id'], META_AUTHCODE);
			$surcharge = gform_get_meta($entry['id'], META_SURCHARGE);

			// format payment amount as currency
			if (isset($entry['payment_amount'])) {
				$payment_amount = GFCommon::format_number($entry['payment_amount'], 'currency', rgar($entry, 'currency', ''));
			}
			else {
				$payment_amount = '';
			}

			// format surcharge amount as currency
			$surcharge = empty($surcharge) ? '' : GFCommon::format_number($surcharge, 'currency', rgar($entry, 'currency', ''));

			$tags = [
				'{transaction_id}',
				'{payment_status}',
				'{payment_amount}',
				'{surcharge}',
				'{authcode}',
				'{date_created}',
			];
			$values = [
				rgar($entry, 'transaction_id', ''),
				rgar($entry, 'payment_status', ''),
				$payment_amount,
				$surcharge ?: '',
				$authCode ?: '',
				GFCommon::format_date(rgar($entry, 'date_created'), false, '', false),
			];

			// maybe encode the results
			if ($url_encode) {
				$values = array_map('urlencode', $values);
			}
			elseif ($esc_html) {
				$values = array_map('esc_html', $values);
			}

			$text = str_replace($tags, $values, $text);
		}

		return $text;
	}

	/**
	* get feed for lead/entry
	* @param int $lead_id the submitted entry's ID
	* @return array
	*/
	protected function getFeed($lead_id) {
		if ($this->feed !== false && (empty($this->feed['lead_id']) || intval($this->feed['lead_id']) !== intval($lead_id))) {
			$form = gform_get_meta($lead_id, META_FEED_ID);
			$this->feed = $this->get_feed($form);
			if ($this->feed) {
				$this->feed['lead_id'] = $lead_id;
			}
		}

		return $this->feed;
	}

	/**
	* action hook for building the entry details view
	* @param int $form_id
	* @param array $entry
	*/
	public function gformPaymentDetails($form_id, $entry) {
		$payment_gateway = gform_get_meta($entry['id'], 'payment_gateway');
		if ($payment_gateway === $this->_slug) {
			$authCode	= gform_get_meta($entry['id'], META_AUTHCODE);
			$surcharge	= gform_get_meta($entry['id'], META_SURCHARGE);

			// format surcharge amount as currency
			$surcharge = empty($surcharge) ? '' : GFCommon::format_number($surcharge, 'currency', rgar($entry, 'currency', ''));

			require GFDPSPXPAY_PLUGIN_ROOT . 'views/admin-entry-payment-details.php';
		}
	}

	/**
	* test whether we can edit payment details
	* @param array $entry
	* @param string $action
	* @return bool
	*/
	protected function canEditPaymentDetails($entry, $action) {
		// make sure payment is not Paid already (can't go backwards!)
		// no Paid, and no Active recurring payments
		$payment_status = rgar($entry, 'payment_status');
		if ($payment_status === 'Paid' || $payment_status === 'Active') {
			return false;
		}

		// check that we're editing the lead
		if (strcasecmp(rgpost('save'), $action) !== 0) {
			return false;
		}

		// make sure payment is one of ours
		if (gform_get_meta($entry['id'], 'payment_gateway') !== $this->_slug) {
			return false;
		}

		return true;
	}

	/**
	* get payment note based on payment method, with details, and gateway response messages
	* @param bool $capture
	* @param array $results
	* @param array $messages
	* @return string
	*/
	protected function getPaymentNote($capture, $results, $messages) {
		if ($capture) {
			$message = esc_html__('Payment has been captured successfully. Amount: %1$s. Transaction ID: %2$s.', 'gravity-forms-dps-pxpay');
		}
		else {
			$message = esc_html__('Payment has been authorized successfully. Amount: %1$s. Transaction ID: %2$s.', 'gravity-forms-dps-pxpay');
		}

		$amount = GFCommon::to_money($results['amount'], $results['currency']);

		$note = sprintf($message, $amount, $results['transaction_id']);
		if (!empty($messages)) {
			$note .= "\n" . esc_html(implode("\n", $messages));
		}

		return $note;
	}

	/**
	* get failure note based on payment method, with gateway response messages
	* @param string $paymentMethod
	* @param array $messages
	* @return string
	*/
	protected function getFailureNote($paymentMethod, $messages) {
		switch ($paymentMethod) {

			case 'authorize':
				$note = esc_html__('Payment authorization failed.', 'gravity-forms-dps-pxpay');
				break;

			default:
				$note = esc_html__('Failed to capture payment.', 'gravity-forms-dps-pxpay');
				break;

		}

		if (!empty($messages)) {
			$note .= "\n" . esc_html(implode("\n", $messages));
		}

		return $note;
	}

	/**
	* get formatted error message for front end, with gateway errors appended
	* @param string $error_msg
	* @param array $errors
	* @return string
	*/
	protected function getErrorMessage($error_msg, $errors) {
		if (!empty($errors)) {
			// add detailed error messages
			$error_msg .= "\n" . implode("\n", $errors);
		}

		return $error_msg;
	}

	/**
	* get errors and response messages as a string, for logging
	* @param array $errors
	* @return string
	*/
	protected function getErrorsForLog($errors) {
		return implode('; ', (array) $errors);
	}

}
