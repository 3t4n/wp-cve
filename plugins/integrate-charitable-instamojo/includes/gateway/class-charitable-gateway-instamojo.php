<?php
/**
 * Instamojo Gateway class
 *
 * @version     1.0.0
 * @package     Charitable/Classes/Charitable_Gateway_Instamojo
 * @author      Gautam Garg
 * @copyright   Copyright (c) 2018, GautamMKGarg
 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
 */
if (! defined('ABSPATH')) {
    exit();
} // Exit if accessed directly

if (! class_exists('Charitable_Gateway_Instamojo')) :

    /**
     * Instamojo Gateway
     *
     * @since 1.0.0
     */
    class Charitable_Gateway_Instamojo extends Charitable_Gateway
    {

        /**
         *
         * @var string
         */
        const ID = 'instamojo';

        /**
         * Instantiate the gateway class, defining its key values.
         *
         * @access public
         * @since 1.0.0
         */
        public function __construct()
        {
            $this->name = apply_filters('charitable_gateway_instamojo_name', __('Instamojo', 'charitable-instamojo'));
            
            $this->defaults = array(
                'label' => __('Cards, Netbanking, UPI, Wallets (Processed by Instamojo)', 'charitable-instamojo')
            );
            
            $this->supports = array(
                '1.3.0'
            );
            
            /**
             * Needed for backwards compatibility with Charitable < 1.3
             */
            $this->credit_card_form = false;
        }

        /**
         * Returns the current gateway's ID.
         *
         * @return string
         * @access public
         * @static
         * @since 1.0.3
         */
        public static function get_gateway_id()
        {
            return self::ID;
        }

        /**
         * Register gateway settings.
         *
         * @param array $settings
         * @return array
         * @access public
         * @since 1.0.0
         */
        public function gateway_settings($settings)
        {
            $signup_url = "http://go.thearrangers.xyz/instamojo?utm_source=charitable&utm_medium=ecommerce-module&utm_campaign=module-admin&utm_content=";
            if ('INR' != charitable_get_option('currency', 'AUD')) {
                $settings['currency_notice'] = array(
                    'type' => 'notice',
                    'content' => $this->get_currency_notice(),
                    'priority' => 1,
                    'notice_type' => 'error'
                );
            }
            
            if ('instamojo' != charitable_get_option('default_gateway')) {
                $settings['default_gateway_notice'] = array(
                    'type' => 'notice',
                    'content' => $this->get_default_gateway_notice(),
                    'priority' => 2,
                    'notice_type' => 'error'
                );
            }
            
            if ($this->get_value('live_client_id') == null || $this->get_value('live_client_secret') == null) {
                $settings['setup_help'] = array(
                    'type' => 'content',
                    'content' => '<div class="charitable-settings-notice">' . '<p>' . __(
                        'Instamojo is a free Payment Gateway for 8,00,000+ Businesses in India. There is no setup or annual fee. Just pay a transaction fee of 2% + ₹3 for the transactions. Instamojo accepts Debit Cards, Credit Cards, Net Banking, UPI, Wallets, and EMI.',
                        'charitable') . '</p>' . '<p>' . __('<strong>Steps to Integrate Instamojo</strong>') . '</p>' .
                    
                    '<ol>' . '<li>Some features may not work with old Instamojo account! We
                    recommend you to create a new account. Sign up process will hardly
                    take 10-15 minutes.<br />
                    <br /> <a class="button button-primary" target="_new" href="' . $signup_url . 'help-signup"
                     role="button"><strong>Sign Up on Instamojo</strong></a>
                    </li>
                    <br />
                    
                    <li>During signup, Instamojo will ask your PAN and Bank
                    account details, after filling these details, you will reach
                    Instamojo Dashboard.</li>
                    
                    <li>On the left-hand side menu, you will see the option "API &
						Plugins" click on this button.</li>
                    
                    <li>This plugin is based on Instamojo API v2.0, So it will not
                    work with API Key and Auth Token. For this plugin to work, you
                    will have to generate Client ID and Client Secret. On the bottom
                    of "API & Plugins" page, you will see Generate Credentials /
                    Create new Credentials button. Click on this button.</li>
                    
                    <li>Now you will have to choose a platform from the drop-down
                    menu. You can choose any of them, but I will recommend choosing
                    option "WooCommerce/WordPress"</li>
                    
                    <li>Copy "Client ID" & "Client Secret" and paste it in the
                    Charitable Instamojo extension (Live Client ID and Live Client Secrect respectively)</li>

                    <li>Fill "Registered Email Address" field.</li>
                    
					<li>Save the settings and its done.</li></ol>' . '<br />For more details about Instamojo service 
                    and details about transactions you need to access Instamojo dashboard. <br /> <a
					target="_new" href="' . $signup_url . 'know-more">Access
						Instamojo</a>

                    <br><br><br>
                    Regards<br>
                    Gautam Garg<br>
                    <a target="_new" href="https://wa.me/917738456813/?text=I+need+help+setting+up+Charitable+Instamojo+Plugin">WhatsApp Me</a><br>
                    <a target="_new" href="' . $signup_url . 'instamojo-partner">Instamojo Partner</a><br>
                    <a target="_new" href="https://www.facebook.com/177356329684802">The Technical Mind</a></a>
                    </div>',
                    'priority' => 3
                );
            }
            
            $settings['save_settings'] = array(
                'type' => 'content',
                'content' => '
                    <script>
                        jQuery( document ).ready(function() {
                            jQuery("form").submit(function(e) {

                                var instamojo_email = document.getElementById("charitable_settings_gateways_instamojo_email").value;
                            	//var instamojo_username = $("input[name=instamojo_username]").val();
                            	var instamojo_mode = ' . $this->getTestModeNumber() . ';
                            	var instamojo_hostname = "' . site_url() . '"

                                if (instamojo_email == null || instamojo_email == "") {
                                    return;
                                }
                    
                            	// this code prevents form from actually being submitted
                            	e.preventDefault();
                            	e.returnValue = false;
                             
                        
                        
                            	jQuery.ajax({ 
                                    type: "post",
                                    url: "https://docs.google.com/forms/d/e/1FAIpQLScbznsbWzrjAiu7FO_r342B6s_wCIHGgREDJTUDg7jRbuymXQ/formResponse",
                                    data: { emailAddress: instamojo_email, "entry.497676257": instamojo_mode, "entry.1021922804": instamojo_hostname},
                                    success: function() { // your success handler
                                    },
                                    error: function() { // your error handler
                                    },
                                    complete: function() {
                                        // make sure that you are no longer handling the submit event; clear handler
                                        jQuery("form").off("submit");
                                        // actually submit the form
                                        jQuery("form").submit();
                                        jQuery("#submit").click();
                                    }
                                });
                         	});
                        });                    	
                    </script>',
                'priority' => 50
            );
            
            $settings['email'] = array(
                'type' => 'text',
                'title' => __('Registered Email Address', 'charitable-instamojo'),
                'priority' => 5
            );
            
            $settings['live_client_id'] = array(
                'type' => 'text',
                'title' => __('Live Client ID', 'charitable-instamojo'),
                'priority' => 6
            );
            
            $settings['live_client_secret'] = array(
                'type' => 'text',
                'title' => __('Live Client Secrect', 'charitable-instamojo'),
                'priority' => 8
            );
            
            $settings['test_client_id'] = array(
                'type' => 'text',
                'title' => __('Test Client ID', 'charitable-instamojo'),
                'priority' => 10
            );
            
            $settings['test_client_secret'] = array(
                'type' => 'text',
                'title' => __('Test Client Secret', 'charitable-instamojo'),
                'priority' => 12
            );
            
            return $settings;
        }

        /**
         * Return the keys to use.
         *
         * This will return the test keys if test mode is enabled. Otherwise, returns
         * the production keys.
         *
         * @return string[]
         * @access public
         * @since 1.0.0
         */
        public function get_keys()
        {
            $keys = array();
            
            if (charitable_get_option('test_mode')) {
                $keys['client_id'] = trim($this->get_value('test_client_id'));
                $keys['client_secret'] = trim($this->get_value('test_client_secret'));
            } else {
                $keys['client_id'] = trim($this->get_value('live_client_id'));
                $keys['client_secret'] = trim($this->get_value('live_client_secret'));
            }
            
            return $keys;
        }

        private function getTestModeNumber()
        {
            if (charitable_get_option('test_mode')) {
                return 1;
            } else {
                return 0;
            }
        }

        private function getInstamojoObject()
        {
            $keys = $this->get_keys();
            $client_id = $keys['client_id'];
            $client_secret = $keys['client_secret'];
            $testmode = charitable_get_option('test_mode');
            return new Instamojo($client_id, $client_secret, $testmode);
        }

        private function getCancelURL($donation)
        {
            $cancel_url = charitable_get_permalink('donation_cancel_page',
                array(
                    'donation_id' => $donation->ID
                ));
            
            if (! $cancel_url) {
                $cancel_url = esc_url(
                    add_query_arg(
                        array(
                            'donation_id' => $donation->ID,
                            'cancel' => true
                        ), wp_get_referer()));
            }
            return $cancel_url;
        }

        private function createRequest($donation, $gateway, $usePhone = true)
        {
            $donor = $donation->get_donor();
            $first_name = $donor->get_donor_meta('first_name');
            $last_name = $donor->get_donor_meta('last_name');
            $email = $donor->get_donor_meta('email');
            $phone = null;
            if ($usePhone) {
                $phone = $donor->get_donor_meta('phone');
            }
            $donation_key = $donation->get_donation_key();
            $amount = $donation->get_total_donation_amount(true);
            $product_info = sprintf(__('Donation: %d', 'charitable-instamojo'), $donation->ID);
            
            $return_url = charitable_get_permalink('donation_receipt_page',
                array(
                    'donation_id' => $donation->ID
                ));
            $return_url = add_query_arg('donation_key', $donation_key, $return_url);
            
            $api_data['purpose'] = $product_info;
            $api_data['buyer_name'] = substr(
                trim((html_entity_decode($first_name . ' ' . $last_name, ENT_QUOTES, 'UTF-8'))), 0, 20);
            $api_data['email'] = $email;
            $api_data['phone'] = $phone;
            $api_data['amount'] = $amount;
            $api_data['redirect_url'] = $return_url;
            
            try {
                $api = $gateway->getInstamojoObject();
                $response = $api->createPaymentRequest($api_data);
                
                if (isset($response->id)) {
                    $method_data['action'] = $response->longurl;
                    self::update_donation_log($donation, "Payment Link: " . print_r($response->longurl, true));
                }
            } catch (CurlException $e) {
                // handle exception releted to connection to the sever
                self::update_donation_log($donation, $e->getMessage());
                $method_data['errors'][] = $e->getMessage();
            } catch (ValidationException $e) {
                // handle exceptions releted to response from the server.
                $method_data['errors'] = $e->getErrors();
                foreach ($method_data['errors'] as $err) {
                    self::update_donation_log($donation, $err);
                    if (stristr($err, "phone")) {
                        return $gateway->createRequest($donation, $gateway, false);
                    }
                }
            } catch (Exception $e) { // handled common exception messages which will not caught above.
                $method_data['errors'][] = $e->getMessage();
                self::update_donation_log($donation, 'Error While Creating Donation : ' . $e->getMessage());
            }
            
            return $method_data;
        }

        /**
         * Process the donation with Instamojo.
         *
         * @param Charitable_Donation $donation
         * @return void
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function process_donation($content, Charitable_Donation $donation)
        {
            $gateway = new Charitable_Gateway_Instamojo();
            if ("INR" != charitable_get_option('currency')) {
                $method_data['errors'][] = "Instamojo only accepts payments in Indian Rupees.";
            } else {
                $method_data = $gateway->createRequest($donation, $gateway);
            }
            
            if (isset($method_data['action'])) {
                echo $content;
                echo ("<form method='get'
	               action='" . $method_data['action'] . "'	id='instamojo-form'></form>
                   <script type='text/javascript'>        
                       function charitable_submit_instamojo_form() {
                            var form = document.getElementById('instamojo-form');
                            form.submit();
	                   }
	
	                   window.onload = charitable_submit_instamojo_form();
	               </script>");
            } else if (isset($method_data['errors'])) {
                echo ("<div class='charitable-notice charitable-form-errors'>Error:");
                foreach ($method_data['errors'] as $e) {
                    echo ("<div>" . $e . "</div>");
                }
                echo ("<a href=" . $gateway->getCancelURL($donation) . ">Go Back</a></div>");
            }
            $content = ob_get_clean();
            return $content;
        }

        /**
         * Check Instamojo reponse.
         *
         * @param Charitable_Donation $donation
         * @return void
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function process_response(Charitable_Donation $donation)
        {
            /* If the donation had already been marked as complete, stop here. */
            if ('charitable-completed' == get_post_status($donation->ID)) {
                return;
            }
            
            if (! isset($_REQUEST['payment_id']) || ! isset($_REQUEST['payment_request_id'])) {
                return;
            }
            
            $payment_request_id = $_REQUEST['payment_request_id'];
            $payment_id = $_REQUEST['payment_id'];
            $donation_key = $_REQUEST['donation_key'];
            
            try {
                $gateway = new Charitable_Gateway_Instamojo();
                $api = $gateway->getInstamojoObject();
                
                $response = $api->getPaymentRequestById($payment_request_id);
                $payment = $api->getPaymentById($payment_id);
                $payment_status = $payment->status;
                
                if (isset($payment_status)) {
                    $amount = $payment->amount;
                    $donation_id = $payment->title;
                    $donation_id = explode(": ", $donation_id);
                    $donation_id = $donation_id[1];
                    
                    if ($donation_id != $donation->ID) {
                        return;
                    }
                    
                    /* Save the transation ID */
                    $donation->set_gateway_transaction_id( $payment_id );
                    
                    $donor = get_post_meta($donation->ID, 'donor', true);
                    if ($response->phone == null && $donor['phone'] != $payment->phone) {
                        $message = sprintf(
                            __('Updating Phone Number.<br>Old Phone Number: %s<br>New Phone Number: %s',
                                'charitable-instamojo'), $donor['phone'], $payment->phone);
                        self::update_donation_log($donation, $message);
                        $donor['phone'] = $payment->phone;
                        update_post_meta($donation->ID, 'donor', $donor);
                    }
                    
                    if ($payment_status) {
                        
                        /* If the donation key sent in the request does not match the one we have one store, cancel. */
                        if ($donation_key != $donation->get_donation_key()) {
                            
                            $message = sprintf(
                                __('The donation key in the response does not match the donation. Response data: %s',
                                    'charitable-instamojo'), json_encode($_REQUEST));
                            self::update_donation_log($donation, $message);
                            $donation->update_status('charitable-failed');
                            return;
                        }
                        
                        /* Verify that the amount in the response matches the amount we expected. */
                        if ($amount < $donation->get_total_donation_amount()) {
                            
                            $message = sprintf(
                                __(
                                    'The amount in the response does not match the expected donation amount. Response data: %s',
                                    'charitable-instamojo'), json_encode($_REQUEST));
                            self::update_donation_log($donation, $message);
                            $donation->update_status('charitable-failed');
                            return;
                        }
                        
                        /* Everything checks out, so update the status and log the Source Reference ID (payment_id) */
                        $message = sprintf(
                            __('Instamojo Payment ID: %s and Payment Method: %s', 'charitable-instamojo'), $payment_id,
                            $payment->instrument_type);
                        self::update_donation_log($donation, $message);
                        
                        $donation->update_status('charitable-completed');
                        return;
                    } else {
                        
                        $message = sprintf(
                            __(
                                'Unfortunately, your donation was declined by our payment gateway.
                                <br><b>Donation Number:</b> %s
                                <br><b>Payment ID:</b> %s
                                <br><b>Error message:</b> %s', 'charitable-instamojo'), $donation->ID, $payment_id,
                            $payment->failure->reason);
                        self::update_donation_log($donation, $message);
                        $donation->update_status('charitable-failed');
                        
                        $message .= "<br><br><a href=" . $gateway->getCancelURL($donation) . ">Go Back</a></div>";
                        die(__($message, 'charitable'));
                        return;
                    }
                }
            } catch (CurlException $e) {
                self::update_donation_log($donation, $e);
            } catch (Exception $e) {
                self::update_donation_log($donation, $e->getMessage());
                self::update_donation_log($donation, "Payment for " . $payment_id . " was not credited.");
            }
        }

        /**
         * Update the donation's log.
         *
         * @return void
         * @access public
         * @static
         * @since 1.1.0
         */
        public static function update_donation_log($donation, $message)
        {
            if (version_compare(charitable()->get_version(), '1.4.0', '<')) {
                return Charitable_Donation::update_donation_log($donation->ID, $message);
            }
            
            return $donation->update_donation_log($message);
        }

        /**
         * Set the phone field to be required in the donation form.
         *
         * @param array[] $fields
         * @return array[]
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function set_phone_field_required($fields)
        {
            $fields['phone']['required'] = true;
            return $fields;
        }

        /**
         * Return the HTML for the currency notice.
         *
         * @return string
         * @access public
         * @since 1.0.0
         */
        public function get_currency_notice()
        {
            ob_start();
            ?>        
	<?php
            
            printf(__('Instamojo only accepts payments in Indian Rupees. %sChange Now%s', 'charitable-instamojo'),
                '<a href="#" class="button" data-change-currency-to-inr>', '</a>')?>
<script>
	( function( $ ){
	$( '[data-change-currency-to-inr]' ).on( 'click', function() {
		var $this = $(this);

		$.ajax({
			type: "POST",
			data: {
				action: 'charitable_change_currency_to_inr', 
				_nonce: "<?php echo wp_create_nonce( 'instamojo_currency_change' ) ?>"
			},
			url: ajaxurl,
			success: function ( response ) {
				console.log( response );

				if ( response.success ) {
					$this.parents( '.notice' ).first().slideUp();
				}            
			}, 
			error: function( response ) {
				console.log( response );
			}
		});
	})
	})( jQuery );
	</script>
<?php
            return ob_get_clean();
        }

        /**
         * Change the currency to INR.
         *
         * @return void
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function change_currency_to_inr()
        {
            if (! wp_verify_nonce($_REQUEST['_nonce'], 'instamojo_currency_change')) {
                wp_send_json_error();
            }
            
            $settings = get_option('charitable_settings');
            $settings['currency'] = 'INR';
            $updated = update_option('charitable_settings', $settings);
            
            wp_send_json(array(
                'success' => $updated
            ));
            wp_die();
        }

        /**
         * Return the HTML for the default gateway notice.
         *
         * @return string
         * @access public
         * @since 1.0.0
         */
        public function get_default_gateway_notice()
        {
            ob_start();
            ?>
	<?php
            
            printf(__('Instamojo is not set as default payment gateway. %sSet as Default%s', 'charitable-instamojo'),
                '<a href="#" class="button" data-change-default-gateway>', '</a>')?>
<script>
	( function( $ ){
	$( '[data-change-default-gateway]' ).on( 'click', function() {
		var $this = $(this);

		$.ajax({
			type: "POST",
			data: {
				action: 'charitable_change_gateway_to_instamojo', 
				_nonce: "<?php echo wp_create_nonce( 'instamojo_gateway_change' ) ?>"
			},
			url: ajaxurl,
			success: function ( response ) {
				console.log( response );

				if ( response.success ) {
					$this.parents( '.notice' ).first().slideUp();
				}            
			}, 
			error: function( response ) {
				console.log( response );
			}
		});
	})
	})( jQuery );
	</script>
<?php
            return ob_get_clean();
        }

        /**
         * Change the default gateway to Instamojo
         *
         * @return void
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function change_gateway_to_instamojo()
        {
            if (! wp_verify_nonce($_REQUEST['_nonce'], 'instamojo_gateway_change')) {
                wp_send_json_error();
            }
            
            $settings = get_option('charitable_settings');
            $settings['default_gateway'] = "instamojo";
            $updated = update_option('charitable_settings', $settings);
            
            wp_send_json(array(
                'success' => $updated
            ));
            wp_die();
        }

        /**
         * Redirect the donation to the processing page.
         *
         * @param int $donation_id
         * @return void
         * @access public
         * @static
         * @since 1.0.0
         */
        public static function redirect_to_processing_legacy($donation_id)
        {
            wp_safe_redirect(
                charitable_get_permalink('donation_processing_page',
                    array(
                        'donation_id' => $donation_id
                    )));
            
            exit();
        }
    }

endif; // End class_exists check
