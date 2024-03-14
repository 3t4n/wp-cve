<?php
	//load classes init method
	add_action('init', array('PMProGateway_peach', 'init'));

	/**
	 * PMProGateway_gatewayname Class
	 */
	class PMProGateway_peach extends PMProGateway
	{
		function PMProGateway($gateway = NULL)
		{
			$this->gateway = $gateway;
			return $this->gateway;
		}										

		/**
		 * Run on WP init
		 *
		 * @since 1.8
		 */
		static function init()
		{
			//make sure Peach is a gateway option
			add_filter('pmpro_gateways', array('PMProGateway_peach', 'pmpro_gateways'));

			//add fields to payment settings
			add_filter('pmpro_payment_options', array('PMProGateway_peach', 'pmpro_payment_options'));
			add_filter('pmpro_payment_option_fields', array('PMProGateway_peach', 'pmpro_payment_option_fields'), 10, 2);

			//updates cron
			add_action('pmpro_activation', array('PMProGateway_peach', 'pmpro_activation'));
			add_action('pmpro_deactivation', array('PMProGateway_peach', 'pmpro_deactivation'));
			add_action('pmpro_cron_peach_subscription_updates', array('PMProGateway_peach', 'pmpro_cron_peach_subscription_updates'));

			//code to add at checkout if Peach is the current gateway
			$gateway = pmpro_getOption("gateway");
			if($gateway == "peach")
			{
				/*add_action('pmpro_checkout_preheader', array('PMProGateway_peach', 'pmpro_checkout_preheader'));*/
				add_filter('pmpro_checkout_order', array('PMProGateway_peach', 'pmpro_checkout_order'));
				add_filter('pmpro_include_billing_address_fields', array('PMProGateway_peach', 'pmpro_include_billing_address_fields'));
				add_filter('pmpro_include_cardtype_field', array('PMProGateway_peach', 'pmpro_include_billing_address_fields'));
				
				add_filter('pmpro_checkout_confirmed', array('PMProGateway_peach', 'pmpro_checkout_confirmed'), 10, 2);
				add_filter('pmpro_required_billing_fields', array( 'PMProGateway_peach', 'pmpro_required_billing_fields' ) );
				add_filter('pmpro_billing_show_payment_method', '__return_false' );
				add_filter('pmpro_include_payment_information_fields', '__return_false');
				
				add_filter( 'pmpro_pages_custom_template_path', 'peach_pmpro_pages_custom_template_path', 10, 2 );
				
				add_filter( 'pmpro_registered_crons', array( 'PMProGateway_peach', 'register_cron' ) );
				
				//add_action("pmpro_subscription_payment_failed", array( 'PMProGateway_peach', 'peach_subscription_payment_failed' ));
			}
			
			$default_gateway = pmpro_getOption( 'gateway' );
			$current_gateway = pmpro_getGateway();
			if ( ( $default_gateway == "peach" || $current_gateway == "peach" ) && empty( $_REQUEST['review'] ) ) {
				//add_filter('pmpro_checkout_before_change_membership_level', array('PMProGateway_peach', 'pmpro_checkout_before_change_membership_level'), 10, 2);
			}
		}

		/**
		 * Make sure Peach is in the gateways list
		 *
		 * @since 1.8
		 */
		static function pmpro_gateways($gateways)
		{
			if(empty($gateways['peach']))
				$gateways['peach'] = __('Peach Payments', 'pmpro');

			return $gateways;
		}

		/**
		 * Get a list of payment options that the Peach gateway needs/supports.
		 *
		 * @since 1.8
		 */
		static function getGatewayOptions()
		{
			$options = array(
				'sslseal',
				'nuclear_HTTPS',
				'gateway_environment',
				'currency',
				'use_ssl',
				'tax_state',
				'tax_rate',
				'accepted_credit_cards',
				'peach_accesstoken',
				'peach_secrettoken',
				'peach_3dsecure',
				'peach_recurringid',
				'peach_webhookkey',
				'peach_billingaddress'
			);

			return $options;
		}

		/**
		 * Set payment options for payment settings page.
		 *
		 * @since 1.8
		 */
		static function pmpro_payment_options($options)
		{
			//get example options
			$peach_options = PMProGateway_peach::getGatewayOptions();

			//merge with others.
			$options = array_merge($peach_options, $options);

			return $options;
		}

		/**
		 * Display fields for example options.
		 *
		 * @since 1.8
		 */
		static function pmpro_payment_option_fields($values, $gateway)
		{
			?>
            <tr class="pmpro_settings_divider gateway gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <hr />
                    <h2 class="pmpro_peach_legacy_keys"><?php esc_html_e( 'Peach API Settings', 'paid-memberships-pro' ); ?></h2>
                </td>
            </tr>
            <tr class="gateway pmpro_peach_legacy_keys gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_accesstoken"><?php esc_html_e( 'Access Token', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <input type="text" id="peach_accesstoken" name="peach_accesstoken" value="<?php echo esc_attr( $values['peach_accesstoken'] ) ?>" class="regular-text code" />
                    <p class="description">This is the key generated within the Peach Payments Console under Development > Access Token.</p>
                </td>
            </tr>
            <tr class="gateway pmpro_peach_legacy_keys gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_secrettoken"><?php esc_html_e( 'Secret Token', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <input type="text" id="peach_secrettoken" name="peach_secrettoken" value="<?php echo esc_attr( $values['peach_secrettoken'] ) ?>" autocomplete="off" class="regular-text code pmpro-admin-secure-key" />
                    <p class="description">This is the key generated within the Peach Payments Dashboard (Only if non-card payment method types have been enabled).</p>
                </td>
            </tr>
            <tr class="gateway pmpro_peach_legacy_keys gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_3dsecure"><?php esc_html_e( '3DSecure Channel ID', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <input type="text" id="peach_3dsecure" name="peach_3dsecure" value="<?php echo esc_attr( $values['peach_3dsecure'] ) ?>" autocomplete="off" class="regular-text code pmpro-admin-secure-key" />
                    <p class="description">The Entity ID that you received from Peach Payments.</p>
                </td>
            </tr>
            <tr class="gateway pmpro_peach_legacy_keys gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_recurringid"><?php esc_html_e( 'Recurring Channel ID', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <input type="text" id="peach_recurringid" name="peach_recurringid" value="<?php echo esc_attr( $values['peach_recurringid'] ) ?>" class="regular-text code" />
                    <p class="description">This field is only required if you want to receive recurring payments. You will receive this from Peach Payments.</p>
                </td>
            </tr>
            <tr class="gateway pmpro_peach_legacy_keys gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_webhookkey"><?php esc_html_e( 'Card Webhook Decryption Key', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <input type="text" id="peach_webhookkey" name="peach_webhookkey" value="<?php echo esc_attr( $values['peach_webhookkey'] ) ?>" class="regular-text code" />
                    <p class="description">You’ll receive this key from Peach Payments after your webhook is enabled.<br>To enable the webhook, please email <a href="mailto:support@peachpayments.com">support@peachpayments.com</a> to set up <a href="https://peach8.semantica.co.za/" target="_blank" rel="nofollow">https://peach8.semantica.co.za/</a> on your account.</p>
                </td>
            </tr>
            <tr class="pmpro_settings_divider gateway gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <td colspan="2">
                    <hr />
                    <h2><?php esc_html_e( 'Other Peach Settings', 'paid-memberships-pro' ); ?></h2>
                </td>
            </tr>
            <tr class="gateway gateway_peach" <?php if ( $gateway != "peach" ) { ?>style="display: none;"<?php } ?>>
                <th scope="row" valign="top">
                    <label for="peach_billingaddress"><?php esc_html_e( 'Show Billing Address Fields', 'paid-memberships-pro' ); ?>:</label>
                </th>
                <td>
                    <select id="peach_billingaddress" name="peach_billingaddress">
                        <option value="0"
                                <?php if ( empty( $values['peach_billingaddress'] ) ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'No', 'paid-memberships-pro' ); ?></option>
                        <option value="1"
                                <?php if ( ! empty( $values['peach_billingaddress'] ) ) { ?>selected="selected"<?php } ?>><?php esc_html_e( 'Yes', 'paid-memberships-pro' ); ?></option>
                    </select>
                    <p class="description"><?php echo wp_kses_post( __( "Peach Payments require billing address fields.", 'paid-memberships-pro' ) ); ?></p>
                </td>
            </tr>
            <?php
		}
		
		public static function pmpro_include_billing_address_fields( $include ) {
			//check settings RE showing billing address
			if ( ! pmpro_getOption( "peach_billingaddress" ) ) {
				$include = false;
			}
	
			return $include;
		}

		/**
		 * Filtering orders at checkout.
		 *
		 * @since 1.8
		 */
		static function pmpro_checkout_order($morder)
		{
			return $morder;
		}

		/**
		 * Code to run after checkout
		 *
		 * @since 1.8
		 */
		static function pmpro_after_checkout($user_id, $morder)
		{
			global $gateway;
	
			if ( $gateway == "peach" ) {
				if ( self::$is_loaded && ! empty( $morder ) && ! empty( $morder->Gateway ) && ! empty( $morder->Gateway->customer ) && ! empty( $morder->Gateway->customer->id ) ) {
					update_user_meta( $user_id, "pmpro_peach_customerid", $morder->Gateway->customer->id );
				}
			}
		}
		
		/**
		 * Review and Confirmation code.
		 */
		static function pmpro_checkout_confirmed($pmpro_confirmed, $morder){
			global $pmpro_msg, $pmpro_msgt, $pmpro_level, $current_user, $pmpro_review, $pmpro_peach_token, $discount_code, $bemail;
				
			$checkout_page_id = pmpro_getOption('checkout_page_id');
			$checkout_page = get_permalink($checkout_page_id);
			$ssl_verifypeer = true;
			$url = "https://eu-prod.oppwa.com";
			$success_code = '000.000.000';
			$paymentType = 'DB';
			if(pmpro_getOption( 'gateway_environment' ) === 'sandbox'){
				$ssl_verifypeer = false;
				$url = "https://eu-test.oppwa.com";
				$success_code = '000.100.110';
			}
			
			if(pmpro_round_price( $pmpro_level->initial_payment ) == 0 && pmpro_round_price( $pmpro_level->billing_amount ) == 0 && pmpro_round_price( $pmpro_level->trial_amount ) == 0 && $_REQUEST['submit-checkout'] == 1){
				return array("pmpro_confirmed"=>true);
			}
			
			if(isset($_GET['id']) && isset($_GET['resourcePath']) && empty($_REQUEST['submit-checkout'])){
				$id = urldecode($_GET['id']);
				$resourcePath = urldecode($_GET['resourcePath']);
				
				$url .= $resourcePath;
				$url .= "?entityId=".pmpro_getOption( 'peach_3dsecure' );
				
				$auth_bearer = pmpro_getOption( 'peach_accesstoken' );
				
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							   'Authorization:Bearer '. $auth_bearer));
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);

				$responseData = curl_exec($ch);
				$response = json_decode($responseData);
			
				if($response->result->code == $success_code){
					if(empty($morder)){
						$pmpro_peach_token = $response->merchantTransactionId;
						$morder = new MemberOrder();
						$morder->Token = $response->id;
						$morder->membership_id = $pmpro_level->id;
						$morder->membership_name = $pmpro_level->name;
						$morder->discount_code = $discount_code;
						$morder->InitialPayment = pmpro_round_price( $pmpro_level->initial_payment );
						$morder->PaymentAmount = pmpro_round_price( $pmpro_level->billing_amount );
						$morder->ProfileStartDate = date_i18n("Y-m-d\TH:i:s");
						$morder->BillingPeriod = $pmpro_level->cycle_period;
						$morder->BillingFrequency = $pmpro_level->cycle_number;
						$morder->Email = $bemail;
						
						//setup level var
						$morder->getMembershipLevelAtCheckout();
						
						//tax
						$morder->subtotal = $morder->InitialPayment;
						$morder->getTax();
						if($pmpro_level->billing_limit)
							$morder->TotalBillingCycles = $pmpro_level->billing_limit;
	
						if(pmpro_isLevelTrial($pmpro_level))
						{
							$morder->TrialBillingPeriod = $pmpro_level->cycle_period;
							$morder->TrialBillingFrequency = $pmpro_level->cycle_number;
							$morder->TrialBillingCycles = $pmpro_level->trial_limit;
							$morder->TrialAmount = pmpro_round_price( $pmpro_level->trial_amount );
						}
						
						//my vals
						$morder->payment_type = "Credit Card";
						$morder->cardtype = $response->paymentBrand;
						$morder->accountnumber = $response->card->bin;
						$morder->expirationmonth = $response->card->expiryMonth;
						$morder->expirationyear = $response->card->expiryYear;
						$morder->gateway = pmpro_getOption("gateway");
						$morder->gateway_environment = pmpro_getOption("gateway_environment");
						$morder->payment_transaction_id = $pmpro_peach_token;
						$morder->subscription_transaction_id = $pmpro_peach_token;
						$morder->notes = $response->registrationId;
						$morder->checkout_id = $response->id;
			
						$morder->billing = new stdClass();
						$morder->billing->name = $response->billing->street1;
						$morder->billing->street = $response->billing->street2;
						$morder->billing->city = $response->billing->city;
						$morder->billing->state = $response->billing->state;
						$morder->billing->zip = $response->billing->postcode;
						$morder->billing->country = $response->billing->country;
						$morder->billing->phone = "";
						
						$morder->status = "success";
						$morder->saveOrder();
						
						$pmpro_msg = __($response->merchantTransactionId, 'paid-memberships-pro' );
						
						switch ($pmpro_level->cycle_period) {
						  case 'Day':
							$interval = 1;
							break;
						  case 'Week':
							$interval = 7;
							break;
						  case 'Month':
							$interval = 31;
							break;
						  case 'Year':
							$interval = 365;
							break;
						  default:
							$interval = 0;
						}
						
						return array("pmpro_confirmed"=>true, "morder"=>$morder);
					}
					
				}else{
					$pmpro_msg = __('Payment Failed: ['.$response->result->code.'] '.$response->result->description, 'paid-memberships-pro' );
					$pmpro_msgt = "pmpro_error";
					return false;
				}
				
			}
			
			if(!empty($_REQUEST['submit-checkout']) && !empty($morder)){
				add_action('pmpro_checkout_after_form', function () use ( $morder ) {
					
					$checkout_page_id = pmpro_getOption('checkout_page_id');
					$checkout_page = get_permalink($checkout_page_id);
					$morder->status  = 'pending';
					$ssl_verifypeer = true;
					$url = "https://eu-prod.oppwa.com";
					$success_code = '000.000.000';
					$paymentType = 'DB';
					$shopperResultUrl = WC_PEACH_SITE_URL.'?wc-api=WC_Peach_Payments';
					if(pmpro_getOption( 'gateway_environment' ) === 'sandbox'){
						$ssl_verifypeer = false;
						$url = "https://eu-test.oppwa.com";
						$success_code = '000.100.110';
					}
					
					$checkoutUrl = $url.'/v1/checkouts';
					
					$data = "entityId=". pmpro_getOption( 'peach_3dsecure' ) .
					"&amount=" .$morder->InitialPayment.
					"&currency=" .pmpro_getOption( 'currency' ).
					"&customParameters[SHOPPER_pluginVersion]=PMPRO-".PMPRO_VERSION.
					"&paymentType=" .$paymentType.
					"&createRegistration=true" .
					"&merchantTransactionId=" .$morder->payment_transaction_id .
					"&standingInstruction.source=CIT" .
					"&standingInstruction.mode=INITIAL" .
					"&standingInstruction.type=RECURRING" .
					"&threeDSecure.challengeIndicator=04";
					"&billing.street1=" .$_REQUEST['baddress1'].
					"&billing.street2=" .$_REQUEST['baddress2'].
					"&billing.postcode=" .$_REQUEST['bzipcode'].
					"&billing.city=" .$_REQUEST['bcity'].
					"&billing.country=" .$_REQUEST['bcountry'];
				
					$ch = curl_init();
					curl_setopt($ch, CURLOPT_URL, $checkoutUrl);
					curl_setopt($ch, CURLOPT_HTTPHEADER, array(
								   'Authorization:Bearer '. pmpro_getOption( 'peach_accesstoken' )));
					curl_setopt($ch, CURLOPT_POST, 1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
					curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
					curl_setopt($ch, CURLOPT_FAILONERROR, true);
					$responseData = curl_exec($ch);
					$response = json_decode($responseData);
					$responseID = $response->id;
					
					echo '<style>#pmpro_form, .wpwl-group-billing {display:none;} .pmpro_checkout-fields div, #loginform p {
	margin: 0 0 1em 0;}</style>';
					
					echo '<script src="'.$url.'/v1/paymentWidgets.js?checkoutId='.$responseID.'"></script>';
					echo '<h3><span class="pmpro_checkout-h3-name">Membership Level</span></h3>';
					echo '
						<div class="pmpro_checkout-fields">
							<p class="pmpro_level_name_text">You have selected the <strong>'.$morder->membership_level->name.'</strong> membership level.</p>
							<div class="pmpro_level_description_text">'.$morder->membership_level->description.'</div>
							<div id="pmpro_level_cost">
								<div class="pmpro_level_cost_text"><p>The price for membership is <strong>'.pmpro_getOption( 'currency' ).' '.$morder->membership_level->initial_payment.'</strong> now and then <strong>'.pmpro_getOption( 'currency' ).' '.$morder->membership_level->billing_amount.' per '.$morder->membership_level->cycle_period.'</strong>.</p>
								</div>
							</div> <!-- end #pmpro_level_cost -->
						</div>
					';

					echo '
					<script>
					var wpwlOptions = {
						billingAddress: {
							country: "'.$_REQUEST['bcountry'].'",
							state: "'.$_REQUEST['bstate'].'",
							city: "'.$_REQUEST['bcity'].'",
							postcode: "'.$_REQUEST['bzipcode'].'",
							street1: "'.$_REQUEST['baddress1'].'",
							street2: "'.$_REQUEST['baddress2'].'"
						},
						mandatoryBillingFields:{
							country: true,
							state: false,
							city: true,
							postcode: true,
							street1: true,
							street2: false
						}
					}
					</script>
					';

					echo '<form action="'.$checkout_page.'?level='.$morder->membership_level->id.'" class="paymentWidgets" data-brands="VISA MASTER AMEX DINERS"></form>';
					
					return array("pmpro_confirmed"=>false, "morder"=>$morder);
				}, 10, 1);
			}else{
				
				return false;
			}
			
		}
		
		/**
		 * Don't require address fields if they are set to hide.
		 */
		public static function pmpro_required_billing_fields( $fields ) {
			$remove = array( 'CardType', 'AccountNumber', 'ExpirationMonth', 'ExpirationYear', 'CVV' );
			foreach ( $remove as $field ) {
				unset( $fields[ $field ] );
			}
			return $fields;
		}

		/**
		 * Cron activation for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_activation()
		{
			wp_schedule_event(time(), 'daily', 'pmpro_cron_peach_subscription_updates');
		}

		/**
		 * Cron deactivation for subscription updates.
		 *
		 * @since 1.8
		 */
		static function pmpro_deactivation()
		{
			wp_clear_scheduled_hook('pmpro_cron_peach_subscription_updates');
		}
		
		/**
		 * Register the cron we need for Stripe subscription updates.
		 *
		 * @since 2.8
		 *
		 * @param array $crons The list of registered crons for Paid Memberships Pro.
		 *
		 * @return array The list of registered crons for Paid Memberships Pro.
		 */
		public static function register_cron( $crons ) {
			$crons['pmpro_cron_peach_subscription_updates'] = [
				'interval' => 'daily',
			];
	
			return $crons;
		}

		/**
		 * Cron job for subscription updates.
		 *
		 * The subscription updates menu is no longer accessible as of v2.6.
		 * This function is staying to process subscription updates that were already queued.
		 *
		 * @since 1.8
		 */
		public static function pmpro_cron_peach_subscription_updates() {
			global $wpdb;
			
			$date = date_i18n( "Y-m-d", strtotime( "+1 day", current_time( 'timestamp' ) ) );
			$sqlQuery = "SELECT *
						 FROM $wpdb->pmpro_memberships_users
						 WHERE status = 'active'
							AND enddate > '".$date."'
							OR enddate = '0000-00-00 00:00:00'";
			$updates  = $wpdb->get_results( $sqlQuery ); 
	
			if ( ! empty( $updates ) ) {
				$users = array();
				//loop through
				foreach ( $updates as $update ) {
					//pull values from update
					$user_id = $update->user_id;
					
					$next = pmpro_next_payment($user_id, array('success', 'cancelled', ''), 'date_format');
	
					$user = get_userdata( $user_id );
					$userEntry = array();
					
					$userEntry['id'] = $user_id;
					$userEntry['next'] = $next;
					$userEntry['data'] = $user;
	
					//if user is missing, delete the update info and continue
					if ( empty( $user ) || empty( $user->ID ) ) {
						$userEntry['error'] = 'empty';
					}else if($next == date('Y-m-d')){
						self::updateSubscription( $update, $user_id );
					}
					
					$users[] = $userEntry;

				}
			}
		}
		
		public static function peach_subscription_payment_failed($order)
		{
			pmpro_changeMembershipLevel(0, $order->user_id);		
		}

		
		function process(&$order)
		{
			//check for initial payment
			if(floatval($order->InitialPayment) == 0)
			{
				//auth first, then process
				if($this->authorize($order))
				{						
					$this->void($order);										
					if(!pmpro_isLevelTrial($order->membership_level))
					{
						//subscription will start today with a 1 period trial (initial payment charged separately)
						$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";
						$order->TrialBillingPeriod = $order->BillingPeriod;
						$order->TrialBillingFrequency = $order->BillingFrequency;													
						$order->TrialBillingCycles = 1;
						$order->TrialAmount = 0;
						
						//add a billing cycle to make up for the trial, if applicable
						if(!empty($order->TotalBillingCycles))
							$order->TotalBillingCycles++;
					}
					elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
					{
						//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
						$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";														
						$order->TrialBillingCycles++;
						
						//add a billing cycle to make up for the trial, if applicable
						if($order->TotalBillingCycles)
							$order->TotalBillingCycles++;
					}
					else
					{
						//add a period to the start date to account for the initial payment
						$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $order->BillingFrequency . " " . $order->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
					}
					
					$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
					return $this->subscribe($order);
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Authorization failed.", "pmpro");
					return false;
				}
			}
			else
			{
				//charge first payment
				if($this->charge($order))
				{							
					//set up recurring billing					
					if(pmpro_isLevelRecurring($order->membership_level))
					{						
						if(!pmpro_isLevelTrial($order->membership_level))
						{
							//subscription will start today with a 1 period trial
							$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";
							$order->TrialBillingPeriod = $order->BillingPeriod;
							$order->TrialBillingFrequency = $order->BillingFrequency;													
							$order->TrialBillingCycles = 1;
							$order->TrialAmount = 0;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						elseif($order->InitialPayment == 0 && $order->TrialAmount == 0)
						{
							//it has a trial, but the amount is the same as the initial payment, so we can squeeze it in there
							$order->ProfileStartDate = date("Y-m-d") . "T0:0:0";														
							$order->TrialBillingCycles++;
							
							//add a billing cycle to make up for the trial, if applicable
							if(!empty($order->TotalBillingCycles))
								$order->TotalBillingCycles++;
						}
						else
						{
							//add a period to the start date to account for the initial payment
							$order->ProfileStartDate = date("Y-m-d", strtotime("+ " . $this->BillingFrequency . " " . $this->BillingPeriod, current_time("timestamp"))) . "T0:0:0";
						}
						
						$order->ProfileStartDate = apply_filters("pmpro_profile_start_date", $order->ProfileStartDate, $order);
						if($this->subscribe($order))
						{
							return true;
						}
						else
						{
							if($this->void($order))
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", "pmpro");
							}
							else
							{
								if(!$order->error)
									$order->error = __("Unknown error: Payment failed.", "pmpro");
								
								$order->error .= " " . __("A partial payment was made that we could not void. Please contact the site owner immediately to correct this.", "pmpro");
							}
							
							return false;								
						}
					}
					else
					{
						//only a one time charge
						$order->status = "success";	//saved on checkout page											
						return true;
					}
				}
				else
				{
					if(empty($order->error))
						$order->error = __("Unknown error: Payment failed.", "pmpro");
					
					return false;
				}	
			}	
		}
		
		/*
			Run an authorization at the gateway.

			Required if supporting recurring subscriptions
			since we'll authorize $1 for subscriptions
			with a $0 initial payment.
		*/
		function authorize(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//code to authorize with gateway and test results would go here

			//simulate a successful authorization
			$order->payment_transaction_id = "TEST" . $order->code;
			$order->updateStatus("authorized");													
			return true;					
		}
		
		/*
			Void a transaction at the gateway.

			Required if supporting recurring transactions
			as we void the authorization test on subs
			with a $0 initial payment and void the initial
			payment if subscription setup fails.
		*/
		function void(&$order)
		{
			//need a transaction id
			if(empty($order->payment_transaction_id))
				return false;
			
			//code to void an order at the gateway and test results would go here

			//simulate a successful void
			$order->payment_transaction_id = "TEST" . $order->code;
			$order->updateStatus("voided");					
			return true;
		}	
		
		/*
			Make a charge at the gateway.

			Required to charge initial payments.
		*/
		function charge(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//code to charge with gateway and test results would go here

			//simulate a successful charge
			$order->payment_transaction_id = "TEST" . $order->code;
			$order->updateStatus("success");					
			return true;						
		}
		
		/*
			Setup a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
		function subscribe(&$order)
		{
			//create a code for the order
			if(empty($order->code))
				$order->code = $order->getRandomCode();
			
			//filter order before subscription. use with care.
			$order = apply_filters("pmpro_subscribe_order", $order, $this);
			
			//code to setup a recurring subscription with the gateway and test results would go here

			//simulate a successful subscription processing
			$order->status = "success";		
			$order->subscription_transaction_id = "TEST" . $order->code;				
			return true;
		}	
		
		/*
			Update billing at the gateway.

			Required if supporting recurring subscriptions and
			processing credit cards on site.
		*/
		function update(&$order)
		{
			//code to update billing info on a recurring subscription at the gateway and test results would go here

			//simulate a successful billing update
			return true;
		}
		
		/*
			Cancel a subscription at the gateway.

			Required if supporting recurring subscriptions.
		*/
		function cancel(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to cancel a subscription at the gateway and test results would go here

			//simulate a successful cancel			
			$order->updateStatus("cancelled");					
			return true;
		}	
		
		/*
			Get subscription status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getSubscriptionStatus(&$order)
		{
			//require a subscription id
			if(empty($order->subscription_transaction_id))
				return false;
			
			//code to get subscription status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			return array();
		}

		/*
			Get transaction status at the gateway.

			Optional if you have code that needs this or
			want to support addons that use this.
		*/
		function getTransactionStatus(&$order)
		{			
			//code to get transaction status at the gateway and test results would go here

			//this looks different for each gateway, but generally an array of some sort
			return array();
		}
		
		/**
		 * @deprecated 2.7.0. Only deprecated for public use, will be changed to private non-static in a future version.
		 */
		static function updateSubscription( $update, $user_id ) {
			pmpro_method_should_be_private( '2.7.0' );
			global $wpdb;
			
			$update = (array)$update;
			$user_info = get_userdata($user_id);
			$gateway = pmpro_getOption("gateway");
			
			$sqlQuery = "SELECT *
						 FROM $wpdb->pmpro_membership_orders
						 WHERE user_id = '".$user_id."'
						 AND membership_id = '".$update["membership_id"]."'
						 AND status = 'success'
						 AND gateway = 'peach'
						 ORDER BY id DESC LIMIT 1";
						 
			$payInfo  = $wpdb->get_results( $sqlQuery );

			$user_level = pmpro_getMembershipLevelForUser( $user_id );

			$last_order = new MemberOrder();
			$last_order->getLastMemberOrder( $user_id );
			$last_order->setGateway( 'peach' );

			$end_timestamp = strtotime( "+" . $update['cycle_number'] . " " . $update['cycle_period'], current_time( 'timestamp' ) );
			
			$payment = self::updateSubscriptionPayment((array)$payInfo[0], $update, $user_id);
			
			$morderPayInfo = (array)$payInfo[0];
			
			if($payment['status'] == 'success'){
				$response = $payment['result'];
				//build order object
				$update_order = new MemberOrder();
				$update_order->setGateway( 'peach' );
				$update_order->code             = $update_order->getRandomCode();
				$update_order->user_id          = $user_id;
				$update_order->membership_id    = $user_level->id;
				$update_order->membership_name  = $user_level->name;
				$update_order->InitialPayment   = 0;
				$update_order->PaymentAmount    = $update['billing_amount'];
				$update_order->ProfileStartDate = date_i18n( "Y-m-d", $end_timestamp );
				$update_order->BillingPeriod    = $update['cycle_period'];
				$update_order->BillingFrequency = $update['cycle_number'];
				$update_order->getMembershipLevel();
				
				//$morder->Token = $response->id;;
				$update_order->Email = $user_info->user_email;
				$update_order->subtotal = $update['billing_amount'];
				$update_order->getTax();
				$update_order->payment_type = "Credit Card";
				$update_order->cardtype = $morderPayInfo["cardtype"];
				$update_order->accountnumber = $morderPayInfo["accountnumber"];
				$update_order->expirationmonth = $morderPayInfo["expirationmonth"];
				$update_order->expirationyear = $morderPayInfo["expirationyear"];
				$update_order->gateway = pmpro_getOption("gateway");
				$update_order->gateway_environment = pmpro_getOption("gateway_environment");
				$update_order->payment_transaction_id = $morderPayInfo["subscription_transaction_id"];
				$update_order->subscription_transaction_id = $morderPayInfo["subscription_transaction_id"];
				$update_order->notes = $response->id;
				$update_order->checkout_id = $response->id;
	
				$update_order->billing = new stdClass();
				$update_order->billing->name = $user_info->first_name;
				$update_order->billing->street = $morderPayInfo["billing_name"];
				$update_order->billing->city = $morderPayInfo["billing_city"];
				$update_order->billing->state = $morderPayInfo["billing_state"];
				$update_order->billing->zip = $morderPayInfo["billing_zip"];
				$update_order->billing->country = $morderPayInfo["billing_country"];
				$update_order->billing->phone = "";
		
				//need filter to reset ProfileStartDate
				$profile_start_date = $update_order->ProfileStartDate;
				add_filter( 'pmpro_profile_start_date', function ( $startdate, $order ) use ( $profile_start_date ) {
					return "{$profile_start_date}T0:0:0";
				}, 10, 2 );
		
				//update membership
				$sqlQuery = "UPDATE $wpdb->pmpro_memberships_users
								SET billing_amount = '" . esc_sql( $update['billing_amount'] ) . "',
									cycle_number = '" . esc_sql( $update['cycle_number'] ) . "',
									cycle_period = '" . esc_sql( $update['cycle_period'] ) . "',
									trial_amount = '',
									trial_limit = ''
								WHERE user_id = '" . esc_sql( $user_id ) . "'
									AND membership_id = '" . esc_sql( $last_order->membership_id ) . "'
									AND status = 'active'
								LIMIT 1";
		
				$wpdb->query( $sqlQuery );
		
				//save order so we know which plan to look for at stripe (order code = plan id)
				$update_order->status = "success";
				$update_order->saveOrder();
			}else{
				self::peach_subscription_payment_failed($last_order);
			}
			
		}
		
		static function updateSubscriptionPayment($payInfo, $update, $user_id) {
			$id = $payInfo['notes'];
			$auth_bearer = pmpro_getOption( 'peach_accesstoken' );
			$recurringid = pmpro_getOption( 'peach_recurringid' );
			
			if((isset($recurringid) && $recurringid != '') && (isset($id) && $id != '')){
				
				$ssl_verifypeer = true;
				$url = "https://eu-prod.oppwa.com";
				$success_code = '000.000.000';
				if(pmpro_getOption( 'gateway_environment' ) === 'sandbox'){
					$ssl_verifypeer = false;
					$url = "https://eu-test.oppwa.com";
					$success_code = '000.100.110';
				}
				
				$amount = number_format((float)$update['billing_amount'], 2, '.', '');
				
				$url .= "/v1/registrations/".$id."/payments";
				$data = "entityId=" .$recurringid.
							"&amount=" .$amount.
							"&currency=" .pmpro_getOption( 'currency' ).
							"&paymentType=DB" .
							"&merchantTransactionId=" .$payInfo['payment_transaction_id'] .
							"&standingInstruction.mode=REPEATED" .
							"&standingInstruction.type=RECURRING" .
							"&standingInstruction.source=MIT";
			
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, $url);
				curl_setopt($ch, CURLOPT_HTTPHEADER, array(
							   'Authorization:Bearer '. $auth_bearer));
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $ssl_verifypeer);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_FAILONERROR, true);
				
				$responseData = curl_exec($ch);
				
				if(curl_errno($ch)) {
					return array(
						'url' => $url,
						'status' => 'failed',
						'result' => curl_error($ch)
					);
				}else{
					$response = json_decode($responseData);
					$resultCode = $response->result->code;
				}
				
				curl_close($ch);
			
				if ( $resultCode == $success_code) {
					return array(
						'url' => $url,
						'status' => 'success',
						'result' => $response
					);
				}else if($resultCode == '000.200.000' || $resultCode == '000.200.100'){
					return array(
						'url' => $url,
						'status' => 'success',
						'result' => $response
					);
				}else {					
					return array(
						'url' => $url,
						'status' => 'failed',
						'result' => $response
					);
				}
			}else{
				return array(
					'url' => $url,
					'status' => 'failed',
					'result' => 'no recurring id'
				);
			}
		}
	}
	
	function peach_pmpro_pages_custom_template_path( $templates, $page_name ) {
		$url = str_replace('classes/','',plugin_dir_path( __FILE__ ));		
		$templates[] = $url . 'templates/' . $page_name . '.php';	
		
		return $templates;
	}