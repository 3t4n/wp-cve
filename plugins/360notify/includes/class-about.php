<?php

defined( 'ABSPATH' ) || exit;

class WooNotify_360Messenger_About {

	public function __construct() {
		add_action( 'admin_init', [ $this, 'adminInit' ] );
		add_filter( 'WooNotify_settings_sections', [ $this, 'addSection' ], 9999, 1 );
		add_action( 'WooNotify_settings_form_bottom_360Messenger_about', [ $this, 'aboutPage' ] );
		add_action( 'wp_ajax_WooNotify_hide_about_page', [ $this, 'ajaxCallback' ] );


	}

	public static function aboutPage() {

if(get_locale() == 'fa_IR')
        {
           $plugin_name='افزونه پیام واتساپ 360Notify' ;
           $about_text='درباره ما';
           $text='اطلاعات بیشتر';
           $imageurl='/assets/images/360messenger-logo-bg.png';
           $des='ما یک استارتاپ هستیم که با توجه به شرایط تحریمی WhatsApp Inc برای شماره‌های ایران و نیاز برخی از کاربران به خدمات WhatsApp، با ابتکار و نوآوری و با اتکا به دانش فنی بومی و بین الملل، امکان دسترسی به این خدمات را در ایران با مقیاس بالا فراهم نموده ایم.';
           $urldes="https://360messenger.com/";
        }
        
        
        else
        {
            $plugin_name='360Notify' ;
            $about_text='About us';
            $text='Plugin features';
            $imageurl='/assets/images/360messenger-logo-bg.png';
            $des='We are a 360Messenger brand startup that provides WhatsApp API services for high-scale with best quality on the Hetzner DataCenter in Germany.';
            $urldes="https://360messenger.com/";
            
        }


		?>

<div class="wrap about-wrap">
            <img src="<?php echo esc_url(WooNotify_URL . $imageurl); ?>">
            <h1><?php echo esc_html(sanitize_text_field($plugin_name));?></h1>

            <div class="about-text">

                <h4><?php echo esc_html(sanitize_text_field($des));?></h4>
                   
                </p>
			
                
            </div>
            
            <h2 class="nav-tab-wrapper">
                <a href="<?php echo esc_url($urldes); ?>" target="_blank" class="nav-tab nav-tab-active"><?php echo esc_html(sanitize_text_field($text));?></a>
                
            </h2>
        <style type="text/css">
            a {
                text-decoration: none !important;
            }

            p {
                line-height: 28px !important;
                text-align: justify;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).on('change', '#WooNotify_hide_about_page', function () {
                jQuery.ajax({
                    url: ajaxurl,
                    data: {
                        action: 'WooNotify_hide_about_page'
                    }
                }).done(function () {
                    window.location = "<?php echo esc_url( admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro' ) ); ?>";
                });
            })
        </script>
	<?php }

	public function addSection( $sections ) {

		if ( ! get_option( 'WooNotify_hide_about_page' ) ) {
			if ( get_locale() == 'fa_IR' ) {
				$sections[] = [
					'id'       => '360Messenger_about',
					'title'    => esc_html( 'درباره ما' ),
					'form_tag' => false,
				];
			} else {
			    $sections[] = [
					'id'       => '360Messenger_about',
					'title'    => esc_html( 'About us' ),
					'form_tag' => false,
				];

			}
		}

		return $sections;
	}

	public function adminInit() {
		if ( ! get_option( 'WooNotify_redirect_about_page' ) ) {

			delete_option( 'WooNotify_hide_about_page' );
			update_option( 'WooNotify_redirect_about_page', esc_html('10') );

			if ( ! headers_sent() ) {
				wp_redirect( admin_url( 'admin.php?page=wooNotify-woocommerece-360Messenger-pro&tab=main' ) );
				exit();
			}
		}
	}

	public function ajaxCallback() {
		update_option( 'WooNotify_hide_about_page', esc_html('0') );
		die();
	}

}

new WooNotify_360Messenger_About();