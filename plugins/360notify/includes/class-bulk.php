<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Bulk {

	public function __construct() {

		add_action( 'WooNotify_settings_form_bottom_360Messenger_send', [ $this, 'bulkForm' ] );
		add_action( 'WooNotify_settings_form_admin_notices', [ $this, 'bulkNotice' ], 10 );

		if ( WooNotify()->Options( 'enable_buyer' ) ) {
			add_action( 'admin_footer', [ $this, 'bulkScript' ], 10 );
			add_action( 'load-edit.php', [ $this, 'bulkAction' ] );
		}
	}

	public function bulkForm() { ?>
		<div class="notice notice-info below-h2">
		<?php if (get_locale() == 'fa_IR') {
			echo ' <p>'.esc_html('با استفاده از قسمت ارسال پیام واتساپ ، میتوانید آزمایش کنید که آیا سرویس شما به خوبی به افزونه متصل شده است یا خیر.').'</p>';
		  } else {
				echo '<p>'.esc_html('Using the WhatsApp messaging section, you can test whether your service is well connected to the plugin or not.').'</p>';
			}
			?>
        </div>
        <form class="initial-form" id="WooNotify-send-360Messenger-bulk-form" method="post"
              action="<?php echo esc_url(admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=send' )) ?>">

			<?php wp_nonce_field( 'WooNotify_send_360Messenger_nonce', '_wpnonce' ); ?>

            <p>
			<?php if (get_locale() == 'fa_IR') { 
			echo '
                <label for="WooNotify_mobile">'.esc_html('شماره دریافت کننده').'</label><br>';
			}	
			else
			{
				echo '<label for="WooNotify_mobile">'.esc_html('Recipient number').'</label><br>';
			}
			?>

                <input type="text" name="WooNotify_mobile" id="WooNotify_mobile"
					value="<?php echo esc_html( sanitize_text_field( $_POST['WooNotify_mobile'] ?? '' ) ); ?>"
					   
                       style="direction:ltr; text-align:left; width:100%; !important"/><br>
					   <?php if (get_locale() == 'fa_IR') { 
						echo '
						<span>'.esc_html('شماره واتساپ دریافت کننده پیام را وارد نمایید. شماره ها را با کاما (,) جدا کنید.').'</span>';
					   }
					   else
					   {
						echo '<span>'.esc_html('Enter the WhatsApp number of the recipient of the message. Separate numbers with commas (,).').'</span>';
					   }
					   ?>
            </p>

            <p>
			<?php if (get_locale() == 'fa_IR') { 
				echo '
                <label for="WooNotify_message">'.esc_html('متن پیام واتساپ').'</label><br>';
			}
			else
			{
				echo '
                 <label for="WooNotify_message">'.esc_html('WhatsApp message text').'</label><br>';
			}
			?>
                <textarea name="WooNotify_message" id="WooNotify_message" rows="10" style="width:100% !important;"><?php
    echo !empty( $_POST['WooNotify_message'] ) ? esc_textarea( sanitize_text_field($_POST['WooNotify_message']) ) : '';
?></textarea><br>


						  <?php if (get_locale() == 'fa_IR') { 
							echo '
						  <span>'.esc_html('متن دلخواهی که میخواهید به دریافت کننده ارسال کنید را وارد کنید.').'</span>';
						  }
						  else
						  {
							echo '<span>'.esc_html('Enter the desired text you want to send to the recipient.').'</span>';
						  }
						  
						  ?>
            </p>
			<?php if (get_locale() == 'fa_IR') { 
				echo '
            <p>
                <input type="submit" class="button button-primary" name="WooNotify_send_360Messenger"
                      
				value="'.esc_html('ارسال پیام واتساپ').'">
            </p>';
			}
			else
			{
				echo '
             <p>
                 <input type="submit" class="button button-primary" name="WooNotify_send_360Messenger"          
			value="'.esc_html('send WhatsApp message').'">
             </p>';
			}
			?>
        </form>
		<?php
	}

	public function bulkNotice() {

		if ( isset( $_POST['WooNotify_send_360Messenger'] ) ) {

			if ( ! wp_verify_nonce( $_POST['_wpnonce'] ?? null, 'WooNotify_send_360Messenger_nonce' ) ) {
				if (get_locale() == 'fa_IR')
				wp_die( esc_html('خطایی رخ داده است.') );
				else
					wp_die( esc_html('An error occurred.') );
			}

			$data            = [];
			$data['type']    = 1;
			$data['mobile']  = $mobiles = ! empty( $_POST['WooNotify_mobile'] ) ? explode( ',', esc_attr(sanitize_text_field( $_POST['WooNotify_mobile']) ) ) : [];
			$data['message'] = ! empty(($_POST['WooNotify_message'] )) ? esc_html(sanitize_textarea_field( $_POST['WooNotify_message'] )) : '';

			$response = WooNotify()->Send360Messenger( $data );

			if ( $response === true ) { ?>
                <div class="notice notice-success below-h2">
				<?php
			if (get_locale() == 'fa_IR') { 
				$Number=esc_html(' شماره ');
				echo '
				<p>'.esc_html('پیام با موفقیت ارسال شد.').'<br><strong>'.esc_html('تعداد مخاطبین با حذف شماره های').'
                '.esc_html('تکراری').' </strong>=>';
			}
			
			else{
				$Number=' Number ';
				echo '
				<p>'.esc_html('The message was sent successfully.').'<br><strong>'.esc_html('The number of contacts by removing the numbers').'
				'.esc_html('duplicate').' </strong>=>';
			}
				?>
							
				<?php echo intval(count($mobiles))  . esc_html($Number); ?></p>
                </div>
				<?php
				return true;
			} ?>

            <div class="notice notice-error below-h2">
			<?php if (get_locale() == 'fa_IR') { 
				echo '
                <p><strong>'.esc_html('خطا: ').'</strong>'.esc_html('پیام ارسال نشد. پاسخ وبسرویس:');
			}
			else 
			{
				echo '
                 <p><strong>'.esc_html('Error: ').'</strong>'.esc_html('Message could not be sent. Web service response:');
			}
			?>
					<?php echo esc_html(sanitize_text_field( $response )); ?>
                </p>
            </div>
			<?php
		}

		return false;
	}

	public function bulkScript() {

		global $post_type;
		if ( 'shop_order' == $post_type ) : ?>
            <script type="text/javascript">
                jQuery(function () {
					
                    jQuery('<option>').val('send_360Messenger').text('Send a group WhatsApp message').appendTo("select[name='action']");
                    jQuery('<option>').val('send_360Messenger').text('Send a group WhatsApp message').appendTo("select[name='action2']");
                });
            </script>
		<?php
		endif;
	}

	public function bulkAction() {

		$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
		$action        = $wp_list_table->current_action();
		if ( $action != 'send_360Messenger' ) {
			return;
		}

		$post_ids = array_map( 'absint', (array) ['post'] );
		$mobiles  = [];
		foreach ( $post_ids as $order_id ) {
			$mobiles[] = WooNotify()->buyerMobile( $order_id );
		}

		$mobiles = implode( ',', array_unique( array_filter( $mobiles ) ) );

		echo '<form method="POST" name="WooNotify_posted_form" action="' . esc_url(admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=send' )) . '">
		<input type="hidden" value="' . esc_attr ( $mobiles ) . '" name="WooNotify_mobile" />
		</form>
		<script language="javascript" type="text/javascript">document.WooNotify_posted_form.submit(); </script>';
		exit();
	}
}

new WooNotify_360Messenger_Bulk();