<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TSWC_SMSWoo_Admin {
	
	/**
	 * Instance of this class.
	 *
	 * @var object Class Instance
	*/
	private static $instance;
	
	/**
	 * Initialize the main plugin function
	*/
	public function __construct() {
		$this->init();
	}

	/**
	 * Get the class instance
	 *
	 * @return smswoo_admin
	*/
	public static function get_instance() {

		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}
	
	/*
	 * init function
	*/
	public function init() {
		
		//register admin menu
		add_action( 'after_trackship_settings', array( $this, 'smswoo_settings' ) );
		
		//ajax save admin api settings
		add_action( 'wp_ajax_smswoo_settings_tab_save', array( $this, 'smswoo_settings_tab_save_callback' ) );

		if ( ! function_exists( 'SMSWOO' ) && !is_plugin_active( 'zorem-sms-for-woocommerce/zorem-sms-for-woocommerce.php' ) ) {
			//hook into AST for shipment SMS notification
			add_action( 'shipment_status_sms_section', array( $this, 'shipment_status_notification_tab'), 10, 1 );
			
			//Ajax save delivered email
			add_action( 'wp_ajax_update_all_shipment_status_sms_delivered', array( $this, 'update_all_shipment_status_sms_delivered') );
		}
	}
	
	public function smswoo_settings() {
		if ( in_array( get_option( 'user_plan' ), array( 'Free Trial', 'Free 50', 'No active plan' ) ) ) {
			return;
		}
		include( dirname(__FILE__) . '/admin-html/settings_tab.php' );
	}
	
	/*
	* get html of fields
	*/
	public function get_html( $arrays ) {
		$checked = '';
		?>
		<ul class="settings_ul">
			<?php foreach ( (array) $arrays as $id => $array ) { ?>
				
				<?php if ( 'title' == $array['type'] ) { ?>
					<li class="<?php echo esc_html($array['type']); ?>_row <?php echo isset( $array['class'] ) ? esc_html($array['class']) : ''; ?>">
						<?php if ( ( 'true' == $button ) ) { ?>
							<div style="float:right;">
								<div class="spinner workflow_spinner"></div>
								<button name="save" class="button-primary button-trackship btn_large button-primary woocommerce-save-button button-smswoo" type="submit" ><?php esc_html_e( 'Save Changes', 'trackship-for-woocommerce' ); ?></button>
							</div>
						<?php } ?>
						<h3><?php echo esc_html($array['title']); ?></h3>
					</li>
					<?php continue; ?>
				<?php } ?>
				
				<?php if ( 'dropdown_button' == $array['type'] ) { ?>
					<li class="<?php echo esc_html($array['type']); ?>_row <?php echo esc_html($array['class']); ?> dis_block">
						<label><?php esc_html_e( $array['title'] ); ?>
							<?php if ( isset($array['tooltip']) ) { ?>
								<span class="woocommerce-help-tip tipTip" title="<?php esc_html_e( $array['tooltip'] ); ?>"></span>
							<?php } ?>
						</label>
						<?php $value = get_option($id); ?>
						<select id="<?php echo esc_html($id); ?>" name="<?php echo esc_html($id); ?>" >
							<?php foreach ( (array) $array['options'] as $key => $val ) { ?>
								<?php $imgpath = isset( $array[ 'img_path_24x24' ][ $key ] ) ? $array[ 'img_path_24x24' ][ $key ] : ''; ?>
								<option value="<?php echo esc_html($key); ?>" image_path="<?php echo esc_url($imgpath); ?>" <?php echo ( $value == ( string ) $key ) ? 'selected' : ''; ?> ><?php echo esc_html($val); ?></option>
							<?php } ?>
						</select>
						<br>
						<?php foreach ( $array['link'] as $key1 => $links ) { ?>
							<p valign="top" class="link_row smswoo_sms_provider <?php echo esc_html($key1); ?>_sms_provider" style="margin:0;">
								<a href="<?php echo esc_url($links['link']); ?>" target="_blank"><?php echo esc_html($links['title']); ?></a>
							</p>
						<?php } ?>
					</li>
					<?php continue; ?>
				<?php } ?>
				
				<?php if ( 'link' == $array['type'] ) { ?>
					<li class="<?php echo esc_html($array['type']); ?>_row <?php echo esc_html($array['class']); ?>">
						<a href="<?php echo esc_url($array['link']); ?>" target="_blank"><?php echo esc_html($array['title']); ?></a>
					</li>
					<?php continue; ?>
				<?php } ?>
				
				<?php if ( 'button' == $array['type'] ) { ?>
					<li class="<?php echo esc_html($array['type']); ?>_row <?php echo esc_html($array['class']); ?>">
						<fieldset>
							<button class="button-primary btn_green2 button-smswoo <?php echo esc_html($array['button_class']); ?>" id="<?php echo esc_html($id); ?>" type="button"><?php echo esc_html($array['title']); ?></button>
							<div class="spinner test_sms_spinner"></div>
						</fieldset>
					</li>
					<?php continue; ?>
				<?php } ?>

				<li class="<?php echo esc_html($array['type']); ?>_row <?php echo esc_html($array['class']); ?> <?php echo 'checkbox' != $array['type'] ? 'dis_block' : ''; ?>">
					<?php
					if ( 'checkbox' == $array['type'] ) {	
						$default = isset( $array['default'] ) ? 1 : 0;
						if ( get_option( $id, $default ) ) {
							$checked = 'checked';
						} else {
							$checked = '';
						} 
						
						if ( isset( $array['disabled'] ) && true == $array['disabled'] ) {
							$disabled = 'disabled';
							$checked = '';
						} else {
							$disabled = '';
						}
						?>
						<input type="hidden" name="<?php echo esc_attr($id); ?>" value="0"/>
						<input class="tgl tgl-flat" type="checkbox" id="<?php echo esc_attr($id); ?>" name="<?php echo esc_attr($id); ?>" <?php echo esc_attr($checked); ?> value="1" <?php echo esc_attr($disabled); ?>/>
						<label class="tgl-btn" for="<?php echo esc_html($id); ?>">
						</label>
					<?php } ?>
					<?php if ( 'desc' != $array['type'] ) { ?>										
						<label for="" class=""><?php echo esc_html($array['title']); ?><?php echo isset( $array['title_link'] ) ? esc_html( $array['title_link'] ) : ''; ?>
							<?php if ( isset( $array['tooltip'] ) ) { ?>
								<span class="woocommerce-help-tip tipTip" title="<?php echo esc_html($array['tooltip']); ?>"></span>
							<?php } ?>
						</label>
					<?php } ?>
					<?php if ( 'textarea' == $array['type'] ) { ?>
						<fieldset>
							<textarea rows="3" cols="20" class="input-text regular-input" type="textarea" name="<?php echo esc_html($id); ?>" id="<?php echo esc_html($id); ?>"	placeholder="<?php echo isset( $array['placeholder'] ) ? esc_html( $array['placeholder'] ) : ''; ?>"><?php echo esc_html(get_option( $id, isset( $array['default'] ) ? $array['default'] : false )); ?></textarea>
						</fieldset>
					<?php } elseif ( isset( $array['type'] ) && 'dropdown' == $array['type'] ) { ?>
						<?php
						if ( isset( $array['multiple'] ) ) {
							$multiple = 'multiple';
							$field_id = $array['multiple'];
						} else {
							$multiple = '';
							$field_id = $id;
						}
						?>
						<fieldset>
							<select class="select select2" id="<?php echo esc_html($field_id); ?>" name="<?php echo esc_html($id); ?>" <?php echo esc_html($multiple); ?>>
								<?php foreach ( (array) $array['options'] as $key => $val ) { ?>
									<?php
									if ( isset( $array['multiple'] ) ) {
										$selected = in_array( $key, ( array ) $this->data->$field_id ) ? 'selected' : '';
									} else {
										$selected = get_option($id) == ( string ) $key ? 'selected' : '';
									}
									?>
									<option value="<?php echo esc_html($key); ?>" <?php echo esc_html($selected); ?> ><?php echo esc_html($val); ?></option>
								<?php } ?>
								<p class="description"><?php echo isset( $array['desc'] ) ? esc_html($array['desc']) : ''; ?></p>
							</select> 
							<br>
							<?php if ( isset( $array['desc'] ) && !empty( $array['desc'] ) ) { ?>
								<p class="description"><?php echo esc_html($array['desc']); ?></p>
							<?php } ?>
							<?php if ( isset( $array['link'] ) ) { ?>
								<?php foreach ( $array['link'] as $key1 => $links) { ?>
								<p valign="top" class="link_row <?php echo esc_html($links['class']); ?>">
									<a href= "<?php echo esc_url($links['link']); ?>" target="_blank"><?php echo esc_html($links['title']); ?></a>
								</p>
								<?php } ?>
							<?php } ?>
						</fieldset>
					<?php } elseif ( 'title' == $array['type'] ) { ?>
					<?php } elseif ( 'checkbox' == $array['type'] ) { ?>
					<?php } elseif ( 'label' == $array['type'] ) { ?>
						<fieldset>
						<label><?php echo esc_html($array['value']); ?></label>
						</fieldset>
					<?php } elseif ( 'radio' == $array['type'] ) { ?>
						<fieldset>
							<ul>
								<?php foreach ( (array) $array['options'] as $key => $val ) { ?>
									<li><label class="label_product_visibility"><input name="product_visibility" value="<?php echo esc_html($key); ?>" type="radio" style="" class="product_visibility" <?php echo $product_visibility == $key ? 'checked' : ''; ?><?php echo esc_html($val); ?><br></label></li>
								<?php } ?>
							</ul>
						</fieldset>
					<?php } elseif ( 'dummyfield' == $array['type'] ) { ?>
					<?php } elseif ( 'time' == $array['type'] ) { ?>
						<fieldset>
							<input id="time_schedule_from" name="time_schedule_from" type="text" class="time" value="<?php echo esc_html(get_option('time_schedule_from')); ?>" /> - 
							<input id="time_schedule_to" name="time_schedule_to" type="text" class="time" value="<?php echo esc_html(get_option('time_schedule_to')); ?>" />
						</fieldset>
					<?php } else { ?>
						<fieldset>
							<input class="input-text regular-input " type="text" name="<?php echo esc_html( $id ); ?>" id="<?php echo esc_html( $id ); ?>" style="" value="<?php echo esc_html(get_option( $id, isset($array['default']) ? $array['default'] : false )); ?>" placeholder="<?php echo isset( $array['placeholder'] ) ? esc_html( $array['placeholder'] ) : ''; ?>">
							<?php if ( isset( $array['desc'] ) && !empty( $array['desc'] ) ) { ?>
								<p class="description" style="margin:0;"><?php echo isset( $array['desc'] ) ? esc_html( $array['desc'] ) : ''; ?></p>
							<?php } ?>
						</fieldset>
					<?php } ?>
				</li>
			<?php } ?>
		</ul>
		<?php 
	}
	
	/**
	 * Get the settings for sms_provider.
	 *
	 * @return array Array of settings sms_provider.
	*/
	public function get_sms_provider_data() {
		$settings = array(
			/*'title1' => array(
				'title'			=> __( 'SMS gateways', 'trackship-for-woocommerce' ),
				'type'			=> 'title',
				'id'			=> 'title1',
			),*/
			'smswoo_sms_provider' => array(
				'title'		=> __( 'SMS gateways', 'trackship-for-woocommerce' ),
				'desc'		=> __( 'Please choose SMS gateway from Dropown.', 'trackship-for-woocommerce' ),
				'type'		=> 'dropdown_button',
				'show'		=> true,
				'id'		=> 'smswoo_sms_provider',
				'class'		=> '',
				'default'	=> '',
				'options'	=> array(
					''					=> __( 'SMS gateways', 'trackship-for-woocommerce' ),
					'smswoo_nexmo'		=> 'Nexmo',
					'smswoo_twilio'		=> 'Twilio',
					'smswoo_clicksend'	=> 'ClickSend',
					'smswoo_fast2sms'	=> 'Fast2sms',
					'smswoo_msg91'		=> 'Msg91',
					'smswoo_smsalert'	=> 'SMS Alert',
				),
				'link' => array(
					'smswoo_nexmo' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'Nexmo' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/vonage/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),
					'smswoo_twilio' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'Twilio' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/twilio/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),
					'smswoo_clicksend' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'ClickSend' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/clicksend/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),
					'smswoo_fast2sms' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'Fast2sms' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/fast2sms/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),	
					'smswoo_msg91' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'MSG91' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/msg91/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),
					'smswoo_smsalert' => array(
						/* translators: %s: search for a tag */
						'title' => sprintf( __( 'How to find your %s credential', 'trackship-for-woocommerce' ), 'SMS Alert' ),
						'link' => 'https://docs.trackship.com/docs/trackship-for-woocommerce/setup/sms-notifications/sms-alert/?utm_source=ts4wc&utm_medium=SMS&utm_campaign=settings',
					),
				),
			),
			'smswoo_nexmo_key' => array(
				'title'		=> __( 'Key', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_nexmo_key',
				'class'		=> 'smswoo_sms_provider smswoo_nexmo_sms_provider',
			),
			'smswoo_nexmo_secret' => array(
				'title'		=> __( 'Secret', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_nexmo_secret',
				'class'		=> 'smswoo_sms_provider smswoo_nexmo_sms_provider',
			),
			'smswoo_twilio_account_sid' => array(
				'title'		=> __( 'Account SID', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_twilio_account_sid',
				'class'		=> 'smswoo_sms_provider smswoo_twilio_sms_provider',
			),
			'smswoo_twilio_auth_token' => array(
				'title'		=> __( 'Auth Token', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_twilio_auth_token',
				'class'		=> 'smswoo_sms_provider smswoo_twilio_sms_provider',
			),
			'enable_twilio_whatsapp' => array(
				'title'		=> __( 'Notifications Type (SMS/WhatsApp)', 'trackship-for-woocommerce' ),
				'type'		=> 'dropdown_button',
				'options'	=> array(
					'enable_sms'		=> 'SMS',
					'enable_whatsapp'		=> 'WhatsApp',
				),
				'link'		=> [],
				'show'		=> true,
				'id'		=> 'enable_twilio_whatsapp',
				'class'		=> 'smswoo_sms_provider smswoo_twilio_sms_provider',
			),
			'smswoo_clicksend_username' => array(
				'title'		=> __( 'API Username', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_clicksend_username',
				'class'		=> 'smswoo_sms_provider smswoo_clicksend_sms_provider',
			),
			'smswoo_clicksend_key' => array(
				'title'		=> __( 'API key', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_clicksend_key',
				'class'		=> 'smswoo_sms_provider smswoo_clicksend_sms_provider',
			),
			'smswoo_fast2sms_key' => array(
				'title'		=> __( 'API Authorization Key', 'trackship-for-woocommerce' ),
				//'desc'	=> __( "Fast2sms API Authorization Key", 'trackship-for-woocommerce'),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_fast2sms_key',
				'class'		=> 'smswoo_sms_provider smswoo_fast2sms_sms_provider',
			),
			'smswoo_msg91_authkey' => array(
				'title'		=> __( 'Authentication Key', 'smswoo' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_msg91_authkey',
				'class'		=> 'smswoo_sms_provider smswoo_msg91_sms_provider',
			),
			'smswoo_msg91_dlt' => array(
				'title'		=> __( 'Use DLT Template id', 'smswoo' ),
				'type'		=> 'checkbox',
				'show'		=> true,
				'id'		=> 'smswoo_msg91_dlt',
				'class'		=> 'smswoo_sms_provider smswoo_msg91_sms_provider',
			),
			'smswoo_smsalert_key' => array(
				'title'		=> __( 'API Authorization Key', 'trackship-for-woocommerce' ),
				//'desc'	=> __( 'Fast2sms API Authorization Key', 'trackship-for-woocommerce'),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_smsalert_key',
				'class'		=> 'smswoo_sms_provider smswoo_smsalert_sms_provider',
			),
			'smswoo_sender_phone_number' => array(
				'title'		=> __( 'Sender phone number / Sender ID', 'trackship-for-woocommerce' ),
				'desc'		=> __( 'This field appears as a from or Sender ID', 'trackship-for-woocommerce'),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_sender_phone_number',
				'class'		=> 'smswoo_sms_provider smswoo_nexmo_sms_provider smswoo_twilio_sms_provider smswoo_clicksend_sms_provider smswoo_smsalert_sms_provider smswoo_msg91_sms_provider', //add provider class if need this field in another provider
			),
			'smswoo_admin_phone_number' => array(
				'title'		=> __( 'Admin Phone Number', 'trackship-for-woocommerce' ),
				'tooltip'	=> __( 'Enter admin phone number with country code.', 'trackship-for-woocommerce'),
				'desc_tip'	=> __( 'Enter admin phone number with country code.', 'trackship-for-woocommerce' ),
				'type'		=> 'text',
				'show'		=> true,
				'id'		=> 'smswoo_admin_phone_number',
				'class'		=> 'halfwidth',
			),
		);
		$settings = apply_filters( 'smswoo_sms_provider_array', $settings );
		return $settings;
	}
	
	/*
	* settings form save
	* save settings of all tab
	*
	* @since   1.0
	*/
	public function smswoo_settings_tab_save_callback() {
		
		check_ajax_referer( 'smswoo_settings_tab', 'smswoo_settings_tab_nonce' );
		
		$data = $this->get_customer_tracking_status_settings();
		foreach ( $data as $key => $val ) {
			if ( isset( $_POST[ $val['id'] ] ) ) {
				
				update_option( $val['id'], wp_unslash( sanitize_textarea_field( $_POST[ $val['id'] ] ) ) );
				
				$enabled_customer = $val['id'] . '_enabled_customer';
				$templete_id = $val['id'] . '_templete_id';
				$template_var = $val['id'] . '_template_var';
				
				update_option( $enabled_customer, isset($_POST[ $enabled_customer ]) ? wc_clean($_POST[ $enabled_customer ]) : '' );
				update_option( $templete_id, isset($_POST[ $templete_id ]) ? wc_clean($_POST[ $templete_id ]) : '' );
				update_option( $template_var, isset($_POST[ $template_var ]) ? wc_clean($_POST[ $template_var ]) : '' );
			}
		}
		
		if ( isset($_POST[ 'smswoo_sms_provider' ]) && 'smswoo_clicksend' == $_POST[ 'smswoo_sms_provider' ] ) {
			$clicksend_username = isset($_POST[ 'smswoo_clicksend_username' ]) ? wc_clean($_POST[ 'smswoo_clicksend_username' ]) : '';
			$clicksend_key = isset($_POST[ 'smswoo_clicksend_key' ]) ? wc_clean($_POST[ 'smswoo_clicksend_key' ]) : '';
			$args = array(
				'headers' => array(
					'Authorization' => 'Basic ' . base64_encode( $clicksend_username . ':' . $clicksend_key ),
				),
			);
			$url = 'https://rest.clicksend.com/v3/account';
			$response = wp_safe_remote_get( $url, $args);

			if ( 200 != wp_remote_retrieve_response_code( $response ) ) {
				wp_send_json( array('success' => 'false', 'message' => 'ClickSend credentials error') );
			}
		}

		$data = $this->get_sms_provider_data();
		foreach ( $data as $key => $val ) {
			if ( isset( $_POST[ $key ] ) ) {
				update_option( $key, wc_clean($_POST[ $key ]) );
			}
		}
		
		wp_send_json( array('success' => 'true', 'message' => __( 'Your settings have been successfully saved.', 'trackship-for-woocommerce' )) );
	}
	
	/*
	* Save delivered email setting
	*/
	public function update_all_shipment_status_sms_delivered () {
		check_ajax_referer( 'all_shipment_delivered', 'security' );
		$all_status = isset( $_POST['sms_delivered'] ) ? wc_clean( $_POST['sms_delivered'] ) : '';
		update_option( 'all-shipment-status-sms-delivered', $all_status );
		exit;
	}
	
	/*
	*
	*/
	public function shipment_status_notification_tab () {
		include( dirname(__FILE__) . '/admin-html/shipment_status_sms_tab.php' );
	}
	
	/*
	* get html of fields
	*/
	public function get_shipment_template_html ( $arrays ) {
		$checked = '';
		?>
		<div class="smswoo-container">
			<?php
			foreach ( (array) $arrays as $id => $array ) {
				$enabled_customer = $array['id'] . '_enabled_customer';
				$template_id = $array['id'] . '_templete_id';
				$template_var = $array['id'] . '_template_var';
				
				$checked_customer = get_option( $enabled_customer );
				?>
				<div class="smswoo-row smswoo-shipment-row <?php echo ( $checked_customer ) ? 'enable_customer' : ''; ?>">
					<div class="smswoo-top">
						<div class="smswoo-top-click"></div>
						<div>
							<?php $image_name = 'in_transit' == $array['slug'] ? 'in-transit' : $array['slug']; ?>
							<?php $image_name = 'available_for_pickup' == $image_name ? 'available-for-pickup' : $image_name; ?>
							<?php $image_name = 'out_for_delivery' == $image_name ? 'out-for-delivery' : $image_name; ?>
							<?php $image_name = in_array( $image_name, array( 'failure', 'exception' ) ) ? 'failure' : $image_name; ?> 
							<?php $image_name = 'on_hold' == $image_name ? 'on-hold' : $image_name; ?>
							<?php $image_name = 'return_to_sender' == $image_name ? 'return-to-sender' : $image_name; ?>
							<?php $image_name = 'delivered' == $image_name ? 'delivered' : $image_name; ?>
							<img src="<?php echo esc_url( trackship_for_woocommerce()->plugin_dir_url() ); ?>assets/css/icons/<?php echo esc_html( $image_name ); ?>.png">
							<span class="smswoo-label <?php echo esc_html($array['id']); ?>"><?php echo esc_html($array['label']); ?></span>
							<?php if ( 'delivered' == $array['slug'] ) { ?>
								<label style="position:relative;">
									<input type="hidden" name="all-shipment-status-sms-delivered" value="no">
									<input name="all-shipment-status-sms-delivered" type="checkbox" id="all-shipment-status-sms-delivered" value="yes" <?php echo get_option( 'all-shipment-status-sms-delivered' ) == 1 ? 'checked' : ''; ?> >
									<?php esc_html_e( 'Send only when all shipments for the order are delivered', 'trackship-for-woocommerce' ); ?>
									<?php $nonce = wp_create_nonce( 'all_shipment_delivered'); ?>
									<input type="hidden" id="delivered_sms" name="delivered_sms" value="<?php echo esc_attr( $nonce ); ?>" />
								</label>
							<?php } ?>
						</div>
						<span class="smswoo-right smswoo-mr20 smswoo-shipment-sendto">
							<button name="save" class="button-primary woocommerce-save-button button-smswoo hide button-trackship" type="submit" value="Save changes"><?php esc_html_e( 'Save & close', 'trackship-for-woocommerce' ); ?></button>
							<span class="smswoo-inlineblock">
								<input type="hidden" name="<?php echo esc_attr($enabled_customer); ?>" value="0"/>
								<input type="checkbox" id="<?php echo esc_attr($enabled_customer); ?>" name="<?php echo esc_attr($enabled_customer); ?>" class="tgl tgl-flat smswoo-shipment-checkbox" value="1" <?php echo $checked_customer ? 'checked' : ''; ?> data-row_class="enable_customer" />
								<label class="tgl-btn" for="<?php echo esc_attr($enabled_customer); ?>"></label>
							</span>
							<span class="smswoo-shipment-sendto-customer dashicons dashicons-admin-generic"></span>

						</span>
					</div>
					<div class="smswoo-bottom">
						<div class="smswoo-ast-textarea">
							<div class="smawoo-textarea-placeholder">
								<textarea class="smswoo-textarea" name="<?php echo esc_attr($array['id']); ?>" id="<?php echo esc_attr($array['id']); ?>" cols="30" rows="5"><?php echo esc_html(get_option( $array['id'], $array['default'] )); ?></textarea>
								
								<input title="<?php esc_html_e('Add template id for this SMS', 'trackship-for-woocommerce'); ?>" class="smswoo-text smswoo-msg91-field tipTip" placeholder="<?php esc_html_e('Template ID', 'trackship-for-woocommerce'); ?>" type="text" name="<?php echo esc_html($template_id); ?>" id="<?php echo esc_html($template_id); ?>" value="<?php echo esc_html(get_option( $template_id )); ?>">
								<input title="<?php esc_html_e('Add template variables that used for this SMS, add variables like this:- {shipment_status}, {tracking_number}', 'trackship-for-woocommerce'); ?>" class="smswoo-text smswoo-msg91-field tipTip" placeholder="<?php esc_html_e('Template variables', 'trackship-for-woocommerce'); ?>" type="text" name="<?php echo esc_attr($template_var); ?>" id="<?php echo esc_attr($template_var); ?>" value="<?php echo esc_html(get_option( $template_var )); ?>">

							</div>
							<div class="zorem_plugin_sidebar smswoo_sidebar">
								<?php include( dirname(__FILE__) . '/admin-html/plugin_sidebar_placeholders.php' ); ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
		</div>
	<?php 
	}
	
	/*
	* get customer tracking status settings
	*
	* @since   1.0
	*/
	public function get_customer_tracking_status_settings() {
		
		$tracking_status = array(
			'in_transit'			=> __( 'In Transit', 'trackship-for-woocommerce' ),
			'available_for_pickup'	=> __( 'Available For Pickup', 'trackship-for-woocommerce' ),
			'out_for_delivery'		=> __( 'Out For Delivery', 'trackship-for-woocommerce' ),
			'failure'				=> __( 'Failed Attempt', 'trackship-for-woocommerce' ),
			'on_hold'				=> __( 'On Hold', 'trackship-for-woocommerce' ),
			'exception'				=> __( 'Exception', 'trackship-for-woocommerce' ),
			'return_to_sender'		=> __( 'Return To Sender', 'trackship-for-woocommerce' ),
			'delivered'				=> __( 'Delivered', 'trackship-for-woocommerce' ),
		);
				
		// Display a textarea setting for each available order status
		foreach ( $tracking_status as $slug => $label ) {

			$slug = 'wc-' === substr( $slug, 0, 3 ) ? substr( $slug, 3 ) : $slug;

			$settings[] = [
				'slug'		=> $slug,
				'id'		=> 'smswoo_trackship_status_' . $slug . '_sms_template',
				'label'		=> sprintf( '%s', $label ),
				'css'		=> 'min-width:500px;',
				'type'		=> 'textarea',
				'default'	=> "Hi, Your order no %order_id% on %shop_name% is now {$label}.",
			];
		}
		return $settings;
	}
	
}
