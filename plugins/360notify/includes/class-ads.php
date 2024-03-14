<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_Ads {

	public function __construct() {

		if ( ! is_admin() || get_option( 'WooNotify_ads_noticemelli' ) ) {
			return;
		}

		add_action( 'WooNotify_settings_form_admin_notices', [ $this, 'adminNotice' ] );
		add_action( 'wp_ajax_WooNotify_notice_dismiss', [ $this, 'ajaxCallback' ] );
	}

	public function adminNotice() { 
        if(get_locale() == 'fa_IR')
        {
           $txt1= esc_html('در کمتر از 3 دقیقه وب سرویس واتساپ خود را فعال کنید') ;
           $txt2=esc_html('سرویس ما در لایه نرم افزار و سخت افزار با امنیت بالا نگهداری می شود');
           $txt3=esc_html('در هر دقیقه به بیش از 10000 درخواست در سرویس ما پاسخ داده می شود');
           $txt4=esc_html('می توانید براحتی با اتصال پلاگین سیستم های مدیریت محتوا به "WaMessenger" و فعال کردن وب سرویس واتساپ خود، به شماره واتساپ مشتریان پیام ارسال کنید');
           $txt5=esc_html('راه اندازی سرویس');
           $txt6=esc_html('ابتدا در سایت با شماره همراه خود ثبت نام و شروع کنید. سپس از منوی “خرید سرویس” اشتراک ماهیانه یا سالیانه خود را از طریق پرداخت در درگاه اینترنتی فعال نمایید. از منوی “مدیریت سرویس ها” باز زدن دکمه “اتصال به واتساپ”، شماره واتساپ خود را به سرویس ما متصل کنید. کلید وب سرویس را از “اطلاعات وب سرویس” دریافت کنید و در افزونه یا کد برنامه ی خود وارد کنید و از خدمات ما لذت ببرید!');
           $txt7=esc_html('کلید دارم');
        }
        
        
        else
        {
            $txt1=esc_html('Activate your 360Messenger API in less than 3 minutes.') ;
           $txt2=esc_html('Our service is maintained in the software and hardware layer with high security.');
           $txt3=esc_html('Every minute more than 10000 requests are answered on our service.');
           $txt4=esc_html('
           Send messages to WhatsApp customers with Plugins
           You can easily send messages to customers WhatsApp numbers by connecting the content management systems plugin to "WooNotify" and activating your 360Messenger API.
           ');
           $txt5=esc_html('start service');
           $txt6=esc_html(' ');
           $txt7=esc_html('no thanks');
        
        }
        ?>
        <div class="notice notice-info below-h2" id="360Messenger-notic-block"><p>

                 <strong>
            <p><?php echo esc_html( $txt1 ); ?></p>
            <p><?php echo esc_html( $txt2 ); ?></p>
            <p><?php echo esc_html( $txt3 ); ?></p>
            <p><?php echo esc_html( $txt6 ); ?></p>
    <br><br>
</strong>

                <a href='https://360messenger.net' class='button button-primary button-large' target='_blank'>
                    <?php echo esc_html( $txt5 ); ?>
</a>
                <a href='#' onclick='return false;' class='button button-secondary button-large'>
                    <?php echo esc_html( $txt5 ); ?>
</a>
            </p>
        </div>
        <script type="text/javascript">
            jQuery(document).on('click', '#360Messenger-notic-block .button-secondary', function () {
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'WooNotify_notice_dismiss'
                    }
                }).done(function () {
                    jQuery("#360Messenger-notic-block").slideUp(1000);
                });
            })
        </script>
		<?php
	}

	public function ajaxCallback() {
		update_option( 'WooNotify_ads_noticemelli', esc_html(1) );
		die();
	}

}

new WooNotify_360Messenger_Ads();