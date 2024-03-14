<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Orders {
	public $SafeWooNotifyCSS = ['style' => [] ];
	private $enabled_buyers = false;
	private $enable_super_admin_360Messenger = false;
	private $enable_product_admin_360Messenger = false;

	public function __construct() {

		$this->enabled_buyers           = WooNotify()->Options( 'enable_buyer' );
		$this->enable_super_admin_360Messenger   = WooNotify()->Options( 'enable_super_admin_360Messenger' );
		$this->enable_product_admin_360Messenger = WooNotify()->Options( 'enable_product_admin_360Messenger' );

		if ( $this->enabled_buyers || $this->enable_super_admin_360Messenger || $this->enable_product_admin_360Messenger ) {

			add_filter( 'woocommerce_checkout_fields', [ $this, 'mobileLabel' ], 0 );
			add_filter( 'woocommerce_billing_fields', [ $this, 'mobileLabel' ] );

			add_action( 'wp_enqueue_scripts', [ $this, 'checkoutScript' ] );
			add_action( 'woocommerce_after_order_notes', [ $this, 'checkoutFields' ] );
			add_action( 'woocommerce_checkout_process', [ $this, 'checkoutFieldsValidation' ] );
			add_action( 'woocommerce_checkout_update_order_meta', [ $this, 'save360MessengerOrderMeta' ] );

			/*بعد از تغییر وضعیت سفارش*/
			add_action( 'woocommerce_order_status_changed', [ $this, 'sendOrder360Messenger' ], 99, 3 );

			/*بعد از ثبت سفارش*/
			add_action( 'woocommerce_checkout_order_processed', [ $this, 'sendOrder360Messenger' ], 99, 1 );
			add_action( 'woocommerce_process_shop_order_meta', [ $this, 'sendOrder360Messenger' ], 999, 1 );

			/*جلوگیری از ارسال بعد از ثبت مجدد سفارش از صفحه تسویه حساب*/
			add_action( 'woocommerce_resume_order', function () {
				remove_action( 'woocommerce_checkout_order_processed', [ $this, 'sendOrder360Messenger' ], 99 );
			} );

			add_filter( 'woocommerce_form_field_WooNotify_multiselect', [
				'WooNotify_360Messenger_Helper',
				'multiSelectAndCheckbox',
			], 11, 4 );
			add_filter( 'woocommerce_form_field_WooNotify_multicheckbox', [
				'WooNotify_360Messenger_Helper',
				'multiSelectAndCheckbox',
			], 11, 4 );

			if ( is_admin() ) {
				add_action( 'woocommerce_admin_order_data_after_billing_address', [
					$this,
					'buyer360MessengerDetails',
				], 10, 1 );
				add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'change360MessengerTextJS' ] );
				add_action( 'wp_ajax_change_360Messenger_text', [ $this, 'change360MessengerTextCallback' ] );
				//add_action( 'wp_ajax_nopriv_change_360Messenger_text', array( $this, 'change360MessengerTextCallback' ) );
			}
		}
	}

	public function mobileLabel( $fields ) {

		$mobile_meta = WooNotify()->buyerMobileMeta();

		if ( ! empty( $fields[ $mobile_meta ]['label'] ) ) {
			$fields[ $mobile_meta ]['label'] = WooNotify()->Options( 'buyer_phone_label', $fields[ $mobile_meta ]['label'] );
		}

		if ( ! empty( $fields['billing'][ $mobile_meta ]['label'] ) ) {
			$fields['billing'][ $mobile_meta ]['label'] = WooNotify()->Options( 'buyer_phone_label', $fields['billing'][ $mobile_meta ]['label'] );
		}

		return $fields;
	}

	public function checkoutScript() {

		if ( ! function_exists( 'is_checkout' ) || ! function_exists( 'wc_enqueue_js' ) ) {
			return;
		}

		if ( WooNotify()->Options( 'allow_buyer_select_status' ) && is_checkout() ) {

			wp_register_script( 'WooNotify-frontend-js', WooNotify_URL . '/assets/js/multi-select.js', [ 'jquery' ], WooNotify_VERSION, true );
			if (get_locale() == 'fa_IR')
			{
				wp_localize_script( 'WooNotify-frontend-js', 'WooNotify',
					[
						'ajax_url'                  => admin_url( 'admin-ajax.php' ),
						'chosen_placeholder_single' => esc_html('گزینه مورد نظر را انتخاب نمایید.'),
						'chosen_placeholder_multi'  => esc_html('گزینه های مورد نظر را انتخاب نمایید.'),
						'chosen_no_results_text'    => esc_html('هیچ گزینه ای وجود ندارد.'),
					]
				);
			}
			else
			{
				wp_localize_script( 'WooNotify-frontend-js', 'WooNotify',
					[
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'chosen_placeholder_single' => esc_html('Choose the desired option.'),
					'chosen_placeholder_multi' => esc_html('Choose the desired options.'),
					'chosen_no_results_text' => esc_html('There is no choice.'),
					]
				);
			}


			wp_enqueue_script( 'WooNotify-frontend-js' );

			if ( ! WooNotify()->Options( 'force_enable_buyer' ) ) {
				wc_enqueue_js( "
					jQuery( '#buyer_360Messenger_status_field' ).hide();
					jQuery( 'input[name=buyer_360Messenger_notify]' ).change( function () {
						if ( jQuery( this ).is( ':checked' ) )
							jQuery( '#buyer_360Messenger_status_field' ).show();
						else
							jQuery( '#buyer_360Messenger_status_field' ).hide();
					} ).change();
				" );
			}
		}
	}

	public function checkoutFields( $checkout ) {

		if ( ! $this->enabled_buyers || count( WooNotify()->GetBuyerAllowedStatuses() ) < 0 ) {
			return;
		}

		echo '<div id="checkoutFields">';
		if (get_locale() == 'fa_IR')
			$checkbox_text = WooNotify()->Options( 'buyer_checkbox_text', esc_html('میخواهم از وضعیت سفارش از طریق پیام واتساپ آگاه شوم.') );
		else
			$checkbox_text = WooNotify()->Options( 'buyer_checkbox_text', esc_html('I want to be informed about the status of the order via WhatsApp message.') );
		$required      = WooNotify()->Options( 'force_enable_buyer' );
		if ( ! $required ) {
			woocommerce_form_field( 'buyer_360Messenger_notify',
				[
					'type'        => 'checkbox',
					'class'       => [ 'buyer-360Messenger-notify form-row-wide' ],
					'label'       => $checkbox_text,
					'label_class' => '',
					'required'    => false,
				], $checkout->get_value( 'buyer_360Messenger_notify' )
			);
		}

		if ( WooNotify()->Options( 'allow_buyer_select_status' ) ) {
			$multiselect_text        = WooNotify()->Options( 'buyer_select_status_text_top' );
			$multiselect_text_bellow = WooNotify()->Options( 'buyer_select_status_text_bellow' );
			$required                = WooNotify()->Options( 'force_buyer_select_status' );
			$mode                    = WooNotify()->Options( 'buyer_status_mode', 'selector' ) == 'selector' ? 'WooNotify_multiselect' : 'WooNotify_multicheckbox';
			woocommerce_form_field( 'buyer_360Messenger_status', [
				'type'        => $mode ? esc_html(sanitize_text_field($mode)) : '',
				'class'       => [ 'buyer-360Messenger-status form-row-wide wc-enhanced-select' ],
				'label'       => esc_html(sanitize_text_field($multiselect_text)),
				'options'     => WooNotify()->GetBuyerAllowedStatuses( true ),
				'required'    => esc_html(sanitize_text_field($required)),
				'description' => esc_html(sanitize_textarea_field($multiselect_text_bellow)),
			], $checkout->get_value( 'buyer_360Messenger_status' ) );
		}

		echo '</div>';
	}

	public function checkoutFieldsValidation() {

		$mobile_meta = WooNotify()->buyerMobileMeta();

		$_POST[ $mobile_meta ] = WooNotify()->modifyMobile(esc_attr (sanitize_text_field( $_POST[ $mobile_meta ] ?? null ) ));

		if ( ! $this->enabled_buyers || count( WooNotify()->GetBuyerAllowedStatuses() ) < 0 ) {
			return;
		}

		$force_buyer = WooNotify()->Options( 'force_enable_buyer' );

		if ( ! $force_buyer && ! empty( $_POST['buyer_360Messenger_notify'] ) && empty( $_POST[ $mobile_meta ] ) ) {
			if (get_locale() == 'fa_IR')
				wc_add_notice( esc_html('برای دریافت پیام می بایست شماره واتساپ را وارد نمایید.'), 'error' );
			else
				wc_add_notice( esc_html('You must enter the WhatsApp number to receive the message.'), 'error');
		}

		$buyer_selected = $force_buyer || ( ! $force_buyer && ! empty( $_POST['buyer_360Messenger_notify'] ) );

		//if ( $buyer_selected && ! WooNotify()->validateMobile( $_POST[ $mobile_meta ] ?? null ) ) {
			if ( $buyer_selected && ! WooNotify()->validateMobile(esc_attr(sanitize_text_field( $_POST[ $mobile_meta ] ?? '' )) ) ) {
			if (get_locale() == 'fa_IR')
				wc_add_notice( esc_html('شماره واتساپ معتبر نیست.'), 'error' );
			else
				wc_add_notice( esc_html('The WhatsApp number is not valid.'), 'error' );
		}

		if ( $buyer_selected && empty( $_POST['buyer_360Messenger_status'] ) && WooNotify()->Options( 'allow_buyer_select_status' ) && WooNotify()->Options( 'force_buyer_select_status' ) ) {
			if (get_locale() == 'fa_IR')
				wc_add_notice( esc_html('انتخاب حداقل یکی از وضعیت های سفارش دریافت پیام واتساپ الزامی است.'), 'error' );
			else
			wc_add_notice( esc_html('Choosing at least one of the order status to receive WhatsApp messages is required.'), 'error' );
		}
	}

	public function save360MessengerOrderMeta( $order_id ) {

		if ( ! $this->enabled_buyers || count( WooNotify()->GetBuyerAllowedStatuses() ) <= 0 ) {
			return;
		}

		
		$order = wc_get_order( $order_id );
 		
 		        $order->update_meta_data( '_force_enable_buyer', WooNotify()->Options( 'force_enable_buyer', '__' ) );
 		        $order->update_meta_data( '_allow_buyer_select_status', WooNotify()->Options( 'allow_buyer_select_status', '__' ) );
		if ( ! empty( $_POST['buyer_360Messenger_notify'] ) || WooNotify()->Options( 'force_enable_buyer' ) ) {
			$order->update_meta_data( '_buyer_360Messenger_notify', 'yes' );
		} else {
			$order->delete_meta_data( '_buyer_360Messenger_notify' );
		}

		if ( ! empty( $_POST['buyer_360Messenger_status'] ) ) {
			$statuses = is_array( $_POST['buyer_360Messenger_status'] ) ? array_map( 'sanitize_text_field', $_POST['buyer_360Messenger_status'] ) : esc_attr(sanitize_text_field( $_POST['buyer_360Messenger_status'] ));
			$order->update_meta_data( '_buyer_360Messenger_status', $statuses );
		} else {
			$order->delete_meta_data( '_buyer_360Messenger_status' );
		}
		$order->save_meta_data();
	}

	public function buyer360MessengerDetails( WC_Order $order ) {

		if ( ! $this->enabled_buyers || count( WooNotify()->GetBuyerAllowedStatuses() ) < 0 ) {
			return;
		}

		$mobile = WooNotify()->buyerMobile( $order->get_id() );

		if ( empty( $mobile ) ) {
			return;
		}

		if ( ! WooNotify()->validateMobile( $mobile ) ) {
			if (get_locale() == 'fa_IR')
				echo '<p>'.esc_html('شماره واتساپ مشتری معتبر نیست.').'</p>';
			else
				echo '<p>'.esc_html('The customers WhatsApp number is not valid.').'</p>';

			return;
		}

		if ( WooNotify()->maybeBool( $order->get_meta( '_force_enable_buyer' ) ) ) {
			if (get_locale() == 'fa_IR')
				echo '<p>'.esc_html('مشتری حق انتخاب دریافت یا عدم دریافت پیام واتساپ را ندارد.').'</p>';
			else
				echo '<p>'.esc_html('The customer does not have the right to choose whether or not to receive WhatsApp messages.').'</p>';
		} else {
			$want_360Messenger = $order->get_meta( '_buyer_360Messenger_notify' );

			if (get_locale() == 'fa_IR')
				echo '<p>' .esc_html('آیا مشتری مایل به دریافت پیام واتساپ هست : '). ( WooNotify()->maybeBool( $want_360Messenger ) ? esc_html('بله') : esc_html('خیر') ) . '</p>';
			else
				echo '<p>'.esc_html('Does the customer want to receive WhatsApp messages: '). ( WooNotify()->maybeBool( $want_360Messenger ) ? esc_html('Yes') : esc_html('No') ) . '</p>';
		}

		echo '<p>';
		if ( WooNotify()->maybeBool( $order->get_meta( '_allow_buyer_select_status' ) ) ) {

			$buyer_360Messenger_status = (array) $order->get_meta( '_buyer_360Messenger_status' );

			$buyer_360Messenger_status = array_filter( $buyer_360Messenger_status );
			if (get_locale() == 'fa_IR')
				echo esc_html('وضعیت های انتخابی توسط مشتری برای دریافت پیام واتساپ : ');
			else
			echo esc_html('situations selected by the customer to receive WhatsApp messages: ');

			if ( ! empty( $buyer_360Messenger_status ) ) {
				$statuses = [];
				foreach ( $buyer_360Messenger_status as $status ) {
					$statuses[] = WooNotify()->statusName( $status );
				}

				echo esc_html( implode( ' - ', $statuses ) );
			} else {
				if (get_locale() == 'fa_IR'){
					echo esc_html('وضعیتی انتخاب نشده است.');
				}else{
					 echo esc_html('A status is not selected.');
				    
				}
					 
			}

		} else {
			if (get_locale() == 'fa_IR')
				 esc_html('مشتری حق انتخاب وضعیت های دریافت پیام واتساپ را ندارد و از تنظیمات افزونه پیروی میکند.');
			else
				echo esc_html('The customer does not have the right to choose the status of receiving WhatsApp messages and follows the settings of the plugin.');
			/*
			
			$allowed_status = WooNotify()->GetBuyerAllowedStatuses();
			if ( ! empty( $allowed_status ) ) {
				echo ' وضعیت مجاز برای دریافت پیامک با توجه به تنظیمات: ' . '<br>';
				echo esc_html( implode( ' - ', array_values( $allowed_status ) ) );
			}
			*/
		}
		echo '</p>';
	}

	public function sendOrder360Messenger( int $order_id, $old_status = '', $new_status = 'created' ) {

		if ( current_action() == 'woocommerce_process_shop_order_meta' ) {
			if ( ! is_admin() ) {
				return;
			}
		} else {
			remove_action( 'woocommerce_process_shop_order_meta', [ $this, 'sendOrder360Messenger' ], 999 );
		}

		$new_status = WooNotify()->modifyStatus( $new_status );

		if ( ! $order_id ) {
			return;
		}

		$order = wc_get_order( $order_id );

		// Customer
		$order_page = ( $_POST['is_shop_order'] ?? null ) == 'true';

		if ( ( $order_page && ! empty( $_POST['360Messenger_order_send'] ) ) || ( ! $order_page && $this->buyerCanGet360Messenger( $order_id, $new_status ) ) ) {

			$mobile  = WooNotify()->buyerMobile( $order_id );
			$country = WooNotify()->buyerCountry( $order_id );
			$message = isset( $_POST['360Messenger_order_text'] ) ? sanitize_textarea_field( $_POST['360Messenger_order_text'] ) : WooNotify()->Options( '360Messenger_body_' . $new_status );

			$data = [
				'post_id' => $order_id,
				'type'    => 2,
				'mobile'  => $mobile,
				'country'  => $country,
				'message' => WooNotify()->ReplaceShortCodes( $message, $new_status, $order ),
			];

			if ( ( $result = WooNotify()->Send360Messenger( $data ) ) === true ) {
				if (get_locale() == 'fa_IR')
					$order->add_order_note( esc_html(sprintf( 'پیام با موفقیت به مشتری با شماره واتساپ  %s ارسال گردید.', $mobile )) );
				else
					$order->add_order_note( esc_html(sprintf( 'The message was successfully sent to the customer with the WhatsApp number %s.', $mobile )) );

			} else {
				if (get_locale() == 'fa_IR')
					$order->add_order_note( esc_html(sprintf( 'پیام بخاطر خطا به مشتری با شماره واتساپ  %s ارسال نشد.<br>پاسخ وبسرویس: %s', $mobile, $result )) );
				else
					$order->add_order_note( esc_html(sprintf( 'The message could not be sent to the customer with WhatsApp number %s due to an error.<br> Web service response: %s', $mobile, $result )) );
			}
		}


		//superAdmin
		if ( $this->enable_super_admin_360Messenger && in_array( $new_status, (array) WooNotify()->Options( 'super_admin_order_status' ) ) ) {

			$mobile  = WooNotify()->Options( 'super_admin_phone' );
			$message = WooNotify()->Options( 'super_admin_360Messenger_body_' . $new_status );

			$data = [
				'post_id' => absint(sanitize_text_field($order_id)),
				'type'    => 4,
				'mobile'  => esc_html(sanitize_text_field($mobile)),
				'message' => WooNotify()->ReplaceShortCodes( $message, $new_status, $order ),
			];

			if ( ( $result = WooNotify()->Send360Messenger( $data ) ) === true ) {
				if (get_locale() == 'fa_IR')
				$order->add_order_note( esc_html(sprintf( 'پیام با موفقیت به مدیر کل با شماره واتساپ  %s ارسال گردید.', $mobile )) );
				else
					$order->add_order_note( esc_html(sprintf( 'The message was successfully sent to the general manager with the WhatsApp number %s.', $mobile )) );

			} else {
               				$order->add_order_note( esc_html(sprintf( 'پیام واتساپ بخاطر خطا به مدیر کل با شماره %s ارسال نشد.<br>پاسخ وبسرویس: %s', $mobile, $result )) );
			}
		}

		//productAdmin
		if ( $this->enable_product_admin_360Messenger ) {

			$order_products = WooNotify()->GetProdcutLists( $order, 'product_id' );
			$mobiles        = WooNotify()->ProductAdminMobiles( $order_products['product_id'], $new_status );

			foreach ( (array) $mobiles as $mobile => $product_ids ) {

				$vendor_items = WooNotify()->ProductAdminItems( $order_products, $product_ids );
				$message      = WooNotify()->Options( 'product_admin_360Messenger_body_' . $new_status );

				$data = [
					'post_id' => absint(sanitize_text_field($order_id)),
					'type'    => 5,
					'mobile'  => esc_html(sanitize_text_field($mobile)),
					'message' => WooNotify()->ReplaceShortCodes( $message, $new_status, $order, $vendor_items ),
				];

				if ( ( $result = WooNotify()->Send360Messenger( $data ) ) === true ) {
					if (get_locale() == 'fa_IR')
						$order->add_order_note(esc_html( sprintf( 'پیام با موفقیت به مدیر محصول با شماره واتساپ  %s ارسال گردید.', $mobile )) );
					else
					$order->add_order_note( esc_html(sprintf( 'The message was successfully sent to the product manager with the WhatsApp number %s.', $mobile )) );
				} else {
					if (get_locale() == 'fa_IR')
						$order->add_order_note(esc_html( sprintf( 'پیام بخاطر خطا به مدیر محصول با شماره واتساپ %s ارسال نشد.<br>پاسخ وبسرویس: %s', $mobile, $result )) );
					else
						$order->add_order_note( esc_html(sprintf( 'The message could not be sent to the product manager with the WhatsApp number %s due to an error. <br>Response from the web service: %s', $mobile, $result )) );
				}
			}
		}
	}

	public function buyerCanGet360Messenger( int $order_id, string $new_status ): bool {

		if ( ! $this->enabled_buyers ) {
			return false;
		}

		if ( ! $order_id ) {
			return false;
		}

		$order = new WC_Order( $order_id );

		$allowed_status = array_keys( WooNotify()->GetBuyerAllowedStatuses() );

		if ( is_admin() ) {
			$status      = WooNotify()->OrderProp( $order, 'status' );
			$created_via = WooNotify()->OrderProp( $order, 'created_via' );
			if ( $created_via == 'admin' || ! in_array( $status, array_keys( WooNotify()->GetAllStatuses() ) ) ) {
				$order->update_meta_data( '_force_enable_buyer', WooNotify()->Options( 'force_enable_buyer', '__' ) );
			 	$order->update_meta_data( '_allow_buyer_select_status', WooNotify()->Options( 'allow_buyer_select_status', '__' ) );
			 	$order->update_meta_data( '_buyer_360Messenger_notify', 'yes' );
			 	$order->update_meta_data( '_buyer_360Messenger_status', $allowed_status );
			 	$order->save_meta_data();
			}
		}


		if ( ! WooNotify()->validateMobile( WooNotify()->buyerMobile( $order_id ) ) ) {
			return false;
		}

		$buyer_can_get_whatsapp = false;

		if ( in_array( $new_status, $allowed_status ) && WooNotify()->maybeBool( 
			$order->get_meta( '_buyer_360Messenger_notify' ) ) ) {

			$buyer_360Messenger_status    = (array) $order->get_meta( '_buyer_360Messenger_status' );
			$allow_select_status = WooNotify()->maybeBool( $order->get_meta( '_allow_buyer_select_status' ) );

            if ( ! $allow_select_status || in_array( $new_status, $buyer_360Messenger_status ) ) {
 		                $buyer_can_get_whatsapp = true;
 		            }
 		        }
 		
 		        return apply_filters( 'WooNotify_buyer_can_get_order_whatsapp', $buyer_can_get_whatsapp, $order, $new_status );
	}

	public function change360MessengerTextJS( WC_Order $order ) {

		if ( $this->enabled_buyers && WooNotify()->validateMobile( WooNotify()->buyerMobile( $order->get_id() ) ) ) { ?>
            <script type="text/javascript">
                jQuery(document).ready(function ($) {
                    $("#order_status").change(function () {
                        $("#WooNotify_textbox").html("<img src=\"<?php echo esc_url(WooNotify_URL) ?>/assets/images/ajax-loader.gif\" />");

                        $.ajax({
                            //old
							
							//new
							url: "<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>",
							//
                            type: "post",
                            data: {
                                action: "change_360Messenger_text",
                                security: "<?php echo esc_attr( wp_create_nonce( "change-360Messenger-text" ) ); ?>",

                                order_id: "<?php echo absint( $order->get_id() ); ?>",
                                order_status: $("#order_status").val()
                            },
                            success: function (response) {
                                $("#WooNotify_textbox").html(response);
                            }
                        });
                    });
                });
            </script>
            <p class="form-field form-field-wide" id="WooNotify_textbox_p">
                <span id="WooNotify_textbox" class="WooNotify_textbox"></span>
            </p>
			<?php
		}
	}

	public function change360MessengerTextCallback() {

		check_ajax_referer( 'change-360Messenger-text', 'security' );

		$order_id = absint(sanitize_text_field( $_POST['order_id'] ?? 0 ));

		if ( empty( $order_id ) ) {
			if (get_locale() == 'fa_IR')
				die( esc_html('خطای آیجکس رخ داده است.') );
			else
				die( esc_html('An Ajax error has occurred.') );
				
		}

		$new_status = '';

		if ( isset( $_POST['order_status'] ) ) {
			$_order_status = is_array( $_POST['order_status'] ) ? array_map( 'sanitize_text_field', $_POST['order_status'] ) : esc_attr(sanitize_text_field( $_POST['order_status'] ));
			$new_status    = WooNotify()->modifyStatus( $_order_status );
		}

		$order   = new WC_Order( $order_id );
		$message = WooNotify()->Options( '360Messenger_body_' . $new_status );
		$message = WooNotify()->ReplaceShortCodes( $message, $new_status, $order );

		echo '<textarea id="360Messenger_order_text" name="360Messenger_order_text" style="width:100%;height:120px;"> ' . esc_textarea( $message ) . ' </textarea>';
		$truer = true;
		echo '<input type="hidden" name="is_shop_order" value='.esc_attr($truer).' />';

		if ( $this->buyerCanGet360Messenger( $order_id, $new_status ) ) {
			$woonotify_360messenger_checked = 'checked="checked"';
			if (get_locale() == 'fa_IR'){
				$description =esc_html('با توجه به تنظیمات و انتخاب ها، مشتری باید این پیام را دریافت کند. ولی میتوانید ارسال پیام واتساپ به او را از طریق این چک باکس غیرفعال نمایید.');
				$whtsdes =esc_html('ارسال پیام واتساپ به مشتری');
			}else{
				$description = esc_html('According to the settings and choices, the Customer should receive this message. But you can disable sending WhatsApp messages to him through this check box.');
				$whtsdes =esc_html('Sending a WhatsApp message to a customer');
			}

		} else {
			$woonotify_360messenger_checked = '';
			if (get_locale() == 'fa_IR'){
				$description =esc_html('با توجه به تنظیمات و انتخاب ها، مشتری نباید این پیام را دریافت کند. ولی میتوانید ارسال پیام واتساپ به او را از طریق این چک باکس فعال نمایید.');
				$whtsdes =esc_html('ارسال پیام واتساپ به مشتری');
			}else{
				$description = esc_html('Due to settings and choices, the Customer should not receive this message. But you can send WhatsApp messages to him through this check box.');
				$whtsdes =esc_html('Sending a WhatsApp message to a customer');
			}

		}

		echo '<input type="checkbox" id="360Messenger_order_send" class="360Messenger_order_send" name="360Messenger_order_send" value='.esc_attr($truer).' style="margin-top:2px;width:20px; float:right" ' . wp_kses( $woonotify_360messenger_checked, $this->SafeWooNotifyCSS ) . '/>
					<label class="360Messenger_order_send_label" for="360Messenger_order_send" >'.esc_html($whtsdes).'</label>
					<span class="description">' . esc_html( $description ) . '</span>';

		die();
	}
}

new WooNotify_360Messenger_Orders();