<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_MetaBox {

	private $enable_metabox = false;
	private $enable_notification = false;
	private $enable_product_admin_360Messenger = false;

	public function __construct() {

		if ( ! is_admin() ) {
			return;
		}

		$this->enable_metabox           = WooNotify()->Options( 'enable_metabox' );
		$this->enable_notification      = WooNotify()->Options( 'enable_notif_360Messenger_main' );
		$this->enable_product_admin_360Messenger = WooNotify()->Options( 'enable_product_admin_360Messenger' );

		if ( $this->enable_metabox || $this->enable_notification || $this->enable_product_admin_360Messenger ) {
			add_action( 'add_meta_boxes', [ $this, 'addMetaBox' ] );
			add_action( 'wp_ajax_WooNotify_metabox', [ $this, 'ajaxCallback' ] );
			}
		}
 		
    public function addMetaBox( ) {
 		
        if ( $this->enable_metabox ) {
            if (get_locale() == 'fa_IR'){
				add_meta_box( 'send_360Messenger_to_buyer', ('ارسال پیام واتساپ به مشتری'),
				[
 		                $this,
 		                'orderMetaBoxHtml',
 		            ], [
 		                'shop_order',
 		                wc_get_page_screen_id( 'shop-order' ),
 		            ], 'side', 'high' );
			}
			else
			{
				add_meta_box('send_360Messenger_to_buyer', ('Send whatsapp message to customer'),
				[
 		                $this,
 		                'orderMetaBoxHtml',
 		            ], [
 		                'shop_order',
 		                wc_get_page_screen_id( 'shop-order' ),
 		            ], 'side', 'high' );
			}
 		           
		}

		if ( $this->enable_notification || $this->enable_product_admin_360Messenger ) {
			if (get_locale() == 'fa_IR'){
			add_meta_box( 'send_360Messenger_to_buyer', ('ارسال پیام واتساپ به مشترکین این محصول'),
				[ $this, 'productMetaboxHtml' ], 'product', 'side', 'high' );
			}
			else
			{
				add_meta_box('send_360Messenger_to_buyer', ('Send whatsapp message to subscribers of this product'),
				[ $this, 'productMetaboxHtml' ], 'product', 'side', 'high' );
			}
		}
	}

	public function ajaxCallback() {

		check_ajax_referer( 'WooNotify_metabox', 'security' );

		if ( empty( $_POST['post_id'] ) || empty( $_POST['post_type'] ) ) {
			if (get_locale() == 'fa_IR')
				wp_send_json_error( [ 'message' => ('خطای ایجکس رخ داده است.') ] );
			else
				wp_send_json_error( [ 'message' => ('An Ajax error has occurred.') ] );
		}

		$message = esc_attr(sanitize_text_field( $_POST['message'] ?? '' ));
//old code
/*
		switch ( $_POST['post_type'] ) {

			case 'shop_order':
				$this->orderMetaboxResult( intval( $_POST['post_id'] ), $message );
				break;

			case 'product':
				$this->productMetaboxResult( intval( $_POST['post_id'] ), $message, sanitize_text_field( $_POST['group'] ?? '' ) );
				break;

			default:
				wp_send_json_error( [ 'message' => 'An Ajax error has occurred.' ] );
		}
		*/
		//new code
	//	$post_type = isset( $_POST['post_type'] ) ? sanitize_key( $_POST['post_type'] ) : '';

switch ( esc_attr(sanitize_text_field($_POST['post_type'] ))) {
    case 'shop_order':
		$this->orderMetaBoxResult( absint( sanitize_text_field($_POST['post_id']) ), $message );
		break;

    case 'product':
		case 'product':
			$this->productMetaBoxResult( absint(sanitize_text_field($_POST['post_id'])  ), $message, sanitize_text_field( $_POST['group'] ?? '' ) );
			break;

    default:
        wp_send_json_error( [ 'message' => ('An Ajax error has occurred.') ] );
}

	}

	public function orderMetaBoxResult( $order_id, $message ) {

		$order  = new WC_Order( $order_id );
		$mobile = WooNotify()->buyerMobile( $order_id );
		$country = WooNotify()->buyerCountry( $order_id );
		$data = [
			'post_id' => $order_id,
			'type'    => 3,
			'mobile'  => $mobile,
			'country'  => $country,
			'message' => $message,
		];

		if ( ( $result = WooNotify()->Send360Messenger( $data ) ) === true ) {
			if (get_locale() == 'fa_IR')
				$order->add_order_note( (
					sprintf( 'پیام  با موفقیت به مشتری با شماره واتساپ %s ارسال شد.<br>متن پیام: %s', $mobile, $message )
                ) );
			else
				$order->add_order_note( (sprintf( 'Message successfully sent to customer with whatsapp number %s.<br>Message text: %s', esc_html(sanitize_text_field($mobile)), esc_html(sanitize_text_field($message)) )) );
				if (get_locale() == 'fa_IR'){
					wp_send_json_success( [
						'message'    => ('پیام واتساپ با موفقیت ارسال شد.'),
						'order_note' => WooNotify()->orderNoteMetaBox( $order ),
					] );
				}
				else
				{
					wp_send_json_success( [
						'message' => ('whatsapp message has been sent successfully.'),
						'order_note' => WooNotify()->orderNoteMetaBox( $order ),
						] );
				}


		} else {

			if (get_locale() == 'fa_IR')
			{
				$order->add_order_note( (sprintf( 'پیام به مشتری با شماره واتساپ %s ارسال نشد.<br>متن پیام: %s<br>پاسخ وبسرویس: %s', esc_html(sanitize_text_field($mobile)), esc_html(sanitize_text_field($message)), esc_html(sanitize_text_field($result)) )) );
			wp_send_json_error( [
				'message'    => (sprintf( 'ارسال پیام واتساپ با خطا مواجه شد. %s', esc_html(sanitize_text_field($result)) )),
				'order_note' => WooNotify()->orderNoteMetaBox( $order ),
			] );
			}
			else
			{
				$order->add_order_note( (sprintf( 'The message was not sent to the customer with the whatsapp number %s.<br>Text of the message: %s<br>Response from the web service: %s', esc_html(sanitize_text_field($mobile)), esc_html(sanitize_text_field($message)), esc_html(sanitize_text_field($result)) )) );
				wp_send_json_error( [
				'message' => (sprintf( 'There was an error sending whatsapp message. %s', esc_html(sanitize_text_field($result)) )),
				'order_note' => WooNotify()->orderNoteMetaBox( $order ),
				] );

			}

		}
	}

	public function productMetaBoxResult( int $product_id, string $message, string $group ) {

		if ( empty( $group ) ) {
			if (get_locale() == 'fa_IR')
				wp_send_json_error( [ 'message' => ('یک گروه برای دریافت پیام واتساپ انتخاب کنید.') ] );
			else
				wp_send_json_error( [ 'message' => ('Select a group to receive whatsapp messages.') ] );
		}

		if ( $group == '_product_admins' ) {
			$type    = 6;
			$mobiles = array_keys( WooNotify()->ProductAdminMobiles( $product_id ) );
		} else {

			switch ( $group ) {

				case '_onsale'://حراج
					$type = 10;
					break;

				case '_in'://موجود شدن
					$type = 12;
					break;

				case '_low'://کم بودن موجودی
					$type = 14;
					break;

				default:
					$type = 15;
			}

			$mobiles = WooNotify_360Messenger_Contacts::getContactsMobiles( $product_id, $group );
		}

		$data = [
			'post_id' => absint(sanitize_text_field($product_id)),
			'type'    => esc_html(sanitize_text_field($type)),
			'mobile'  => esc_html(sanitize_text_field($mobiles)),
			'message' => esc_html(sanitize_textarea_field($message)),
		];

		if ( ( $result = WooNotify()->Send360Messenger( $data ) ) === true ) {
			if (get_locale() == 'fa_IR')
				wp_send_json_success( [ 'message' => (sprintf( 'پیام با موفقیت به %s شماره واتساپ ارسال شد.', count( $mobiles ) )) ] );
			else
				wp_send_json_success( [ 'message' => (sprintf( 'Message successfully sent to %s whatsapp number.', count( $mobiles ) )) ] );
		} else {
			if (get_locale() == 'fa_IR')
				wp_send_json_error( [ 'message' => (sprintf( 'ارسال پیام واتساپ با خطا مواجه شد. %s', $result )) ] );
			else
			wp_send_json_error( [ 'message' => (sprintf( 'Error sending whatsapp message. %s', $result )) ] );
		}
	}

public function orderMetaBoxHtml( $post_or_order_object ) {
		$order_id = $post_or_order_object instanceof WC_Order ? $post_or_order_object->get_id() : $post_or_order_object->ID;
		$mobile   = WooNotify()->buyerMobile( $order_id );

		if ( empty( $mobile ) ) {
			if (get_locale() == 'fa_IR')
				echo '<p>'.esc_html('شماره واتساپی برای ارسال پیام وجود ندارد.').'</p>';
			else
				echo '<p>'.esc_html('There is no whatsapp number to send message to.').'</p>';

			return;
		}

		if ( ! WooNotify()->validateMobile( $mobile ) ) {
			if (get_locale() == 'fa_IR')
				echo '<p>'.esc_html('شماره واتساپ مشتری معتبر نیست.').'</p>';
			else
				echo '<p>'.esc_html('The customers whatsapp number is not valid.').'</p>';

			return;
		}
		if (get_locale() == 'fa_IR')
			$this->metaBoxHtml( $order_id, 'shop_order', (sprintf( '<p>ارسال پیام واتساپ به شماره %s</p>', $mobile )) );
		else
			$this->metaBoxHtml( $order_id, 'shop_order', (sprintf( '<p>Send whatsapp message to %s</p>', $mobile )) );
	}

	/*محصول*/

	private function metaBoxHtml( int $post_id, $post_type, $html_above = '', $html_below = '' ) { ?>

        <div id="WooNotify_metabox_result"></div>

		<?php
        $safemetabox = [
 		            'a'        => [ 'href' => true, 'title' => true, 'target' => true ],
 		            'p'        => [],
 		            'select'   => [ 'id' => true, 'class' => true ],
 		            'option'   => [ 'value' => true ],
 		            'label'    => [ 'for' => true ],
 		            'optgroup' => [
 		                'label' => true,
 		            ],
 		
 		
 		        ];
 		
 		        echo wp_kses( $html_above, $safemetabox );
 		
 		
 		        ?>
 		
 		        <p>
            <textarea rows="5" cols="20" class="input-text" id="WooNotify_message"
                      name="WooNotify_message" style="width: 100%; height: 78px;" title=""></textarea>
        </p>

		<?php echo wp_kses($html_below,$safemetabox) ; ?>

        <div class="wide" id="WooNotify_divider" style="text-align: left">
            <input type="submit" class="button save_order button-primary" name="WooNotify_submit"
                  
			id="WooNotify_submit" value="<?php
			if (get_locale() == 'fa_IR')
				echo esc_html('ارسال پیام واتساپ');
			else
				echo esc_html('send whatsapp message');
			
			
			?>">
        </div>

        <div class="WooNotify_loading">
            <img src="<?php echo esc_url(WooNotify_URL . '/assets/images/ajax-loader.gif'); ?>">
        </div>

        <style type="text/css">
            .WooNotify_loading {
                position: absolute;
                background: rgba(255, 255, 255, 0.5);
                top: 0;
                left: 0;
                z-index: 9999;
                display: none;
                width: 100%;
                height: 100%;
            }

            .WooNotify_loading img {
                position: absolute;
                top: 40%;
                left: 47%;
            }

            #WooNotify_metabox_result {
                padding: 6px;
                width: 93%;
                display: none;
                border-radius: 2px;
                border: 1px solid #fff;
            }

            #WooNotify_metabox_result.success {
                color: #155724;
                background-color: #d4edda;
                border-color: #c3e6cb;
            }

            #WooNotify_metabox_result.fault {
                color: #721c24;
                background-color: #f8d7da;
                border-color: #f5c6cb;
            }

            #WooNotify_divider {
                width: 100%;
                border-top: 1px solid #e9e9e9;
                padding-top: 5px;
            }
        </style>

        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('#WooNotify_submit').on('click', function (e) {
                    e.preventDefault();
                    var notes = $('#woocommerce-order-notes .inside');
                    var result = $('div#WooNotify_metabox_result');
                    var loading = $('.WooNotify_loading');
                    loading.show();
                    loading.clone().prependTo(notes);
                    var self = $(this);
                    var post_type = '<?php echo esc_html(sanitize_text_field( $post_type )); ?>';
                    result.removeClass('fault', 'success');
                    self.attr('disabled', true);
					$.post('<?php echo esc_url( admin_url( "admin-ajax.php" ) ); ?>', {

                        action: 'WooNotify_metabox',
                        security: '<?php echo esc_attr(wp_create_nonce( 'WooNotify_metabox' ));?>',
                        post_id: '<?php echo absint( $post_id );?>',
                        post_type: post_type,
                        message: $('#WooNotify_message').val(),
                        group: $('#select_group').val()
                    }, function (res) {
                        result.addClass(res.success ? 'success' : 'fault').html(res.data.message).show();
                        self.attr('disabled', false);
                        if (typeof res.data.order_note != "undefined" && res.data.order_note.length) {
                            notes.html(res.data.order_note);
                        }
                        loading.hide();
                    });
                });
            });
        </script>
		<?php
	}

	public function productMetaBoxHtml( $post ) {

		$product_id = $post->ID;

		ob_start(); ?>
        <p>
		
            <label for="select_group">
				<?php
			if (get_locale() == 'fa_IR')
				echo esc_html('ارسال پیام به:');
			else
				echo esc_html('Send message to:');
			?>
			</label><br>
            <select name="select_group" class="wc-enhanced-select" id="select_group" style="width: 100%;">
			
				<?php if ( $this->enable_product_admin_360Messenger ) { ?>
                    <option value="_product_admins">
				<?php
					if (get_locale() == 'fa_IR')
						echo esc_html('به مدیران این محصول');
					else
						echo esc_html('To the managers of this product');
			?>

					</option>
				<?php }

				if ( $this->enable_notification ) {

					$groups = WooNotify_360Messenger_Contacts::getGroups( $product_id, false, true );
					
					if ( ! empty( $groups ) ) { ?>
                        <optgroup label="
						
					<?php
						if (get_locale() == 'fa_IR')
							echo esc_html('به مشترکین گروه های زیر:');
						else
							echo esc_html('To subscribers of the following groups:');
					?>
						
						
						">
							<?php foreach ( $groups as $code => $text ) { ?>
                                <option
                                    value="<?php echo esc_attr( $code ); ?>"><?php echo esc_html(sanitize_text_field( $text) ); ?></option>
							<?php } ?>
                        </optgroup>
					<?php }
				}
				?>

            </select>
        </p>
		<?php
		$html_above = ob_get_clean();

		$html_below = '';
		if ( $this->enable_notification ) {
			$contact_url = admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=contacts&product_id=' . $product_id );
			if (get_locale() == 'fa_IR')
				$html_below  = '<p><a style="text-decoration: none" href="' . esc_url($contact_url) . '" target="_blank">'.('مشاهده مشترکین اطلاع رسانی این محصول').'</a></p>';
			else
				$html_below = '<p><a style="text-decoration: none" href="' . esc_url($contact_url) . '" target="_blank">'.('View notification subscribers of this product').'</a></p>';
		}

		$this->metaBoxHtml( $product_id, 'product', $html_above, $html_below );
	}

}

new WooNotify_360Messenger_MetaBox();