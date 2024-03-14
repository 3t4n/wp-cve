<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Sends feedback about the plugin
 *
 * @link			https://icopydoc.ru/
 * @since		1.6.0
 */

final class XFGMC_Feedback {
	private $pref = 'xfgmc';
	private $radio_name;
	private $input_name;
	private $textarea_name;
	private $submit_name;
	private $nonce_action;
	private $nonce_field;

	public function __construct() {
		$this->radio_name = $this->get_pref() . '_its_ok';
		$this->input_name = $this->get_pref() . '_email';
		$this->textarea_name = $this->get_pref() . '_message';
		$this->submit_name = $this->get_pref() . '_submit_send_stat';
		$this->nonce_action = $this->get_pref() . '_nonce_action_send_stat';
		$this->nonce_field = $this->get_pref() . '_nonce_field_send_stat';

		$this->listen_submits_func();
	}

	public function get_form() { ?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Send data about the work of the plugin', 'xml-for-google-merchant-center' ); ?>
			</h2>
			<div class="inside">
				<p>
					<?php _e( 'Sending statistics you help make the plugin even better', 'xml-for-google-merchant-center' ); ?>!
					<?php _e( 'The following data will be transferred', 'xml-for-google-merchant-center' ); ?>:
				</p>
				<ul class="xfgmc_ul">
					<li>
						<?php _e( 'URL your feeds', 'xml-for-google-merchant-center' ); ?>
					</li>
					<li>
						<?php _e( 'Files generation status', 'xml-for-google-merchant-center' ); ?>
					</li>
					<li>
						<?php _e( 'PHP version information', 'xml-for-google-merchant-center' ); ?>
					</li>
					<li>
						<?php _e( 'Multisite mode status', 'xml-for-google-merchant-center' ); ?>
					</li>
					<li>
						<?php _e( 'Technical information and plugin logs', 'xml-for-google-merchant-center' ); ?> XML for Google
						Merchant Center
					</li>
				</ul>
				<p>
					<?php _e( 'Did my plugin help you upload your products to the', 'xml-for-google-merchant-center' ); ?> XML for
					Google Merchant Center?
				</p>
				<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" enctype="multipart/form-data">
					<p>
						<input type="radio" name="<?php echo $this->get_radio_name(); ?>" value="yes"><?php _e( 'Yes', 'xml-for-google-merchant-center' ); ?><br />
						<input type="radio" name="<?php echo $this->get_radio_name(); ?>" value="no"><?php _e( 'No', 'xml-for-google-merchant-center' ); ?><br />
					</p>
					<p>
						<?php _e( "If you don't mind to be contacted in case of problems, please enter your email address", "xfgmc" ); ?>.
					</p>
					<p><input type="email" name="<?php echo $this->get_input_name(); ?>"></p>
					<p>
						<?php _e( 'Your message', 'xml-for-google-merchant-center' ); ?>:
					</p>
					<p><textarea rows="6" cols="32" name="<?php echo $this->get_textarea_name(); ?>"
							placeholder="<?php _e( 'Enter your text to send me a message (You can write me in Russian or English). I check my email several times a day', 'xml-for-google-merchant-center' ); ?>"></textarea>
					</p>
					<?php wp_nonce_field( $this->get_nonce_action(), $this->get_nonce_field() ); ?>
					<input class="button-primary" type="submit" name="<?php echo $this->get_submit_name(); ?>"
						value="<?php _e( 'Send data', 'xml-for-google-merchant-center' ); ?>" />
				</form>
			</div>
		</div>
		<?php
	}

	public function get_block_support_project() { ?>
		<div class="postbox">
			<h2 class="hndle">
				<?php _e( 'Please support the project', 'xml-for-google-merchant-center' ); ?>!
			</h2>
			<div class="inside">
				<p>
					<?php _e( 'Thank you for using the plugin', 'xml-for-google-merchant-center' ); ?> <strong>XML for Google
						Merchant Center</strong>
				</p>
				<p>
					<?php _e( 'Please help make the plugin better', 'xml-for-google-merchant-center' ); ?> <a
						href="//forms.gle/cCTNqWbUQzQcJpZJ9" target="_blank">
						<?php _e( 'answering 6 questions', 'xml-for-google-merchant-center' ); ?>!
					</a>
				</p>
				<p>
					<?php _e( 'If this plugin useful to you, please support the project one way', 'xml-for-google-merchant-center' ); ?>:
				</p>
				<ul class="xfgmc_ul">
					<li><a href="//wordpress.org/support/plugin/xml-for-google-merchant-center/reviews/" target="_blank">
							<?php _e( 'Leave a comment on the plugin page', 'xml-for-google-merchant-center' ); ?>
						</a>.</li>
					<li>
						<?php _e( 'Support the project financially', 'xml-for-google-merchant-center' ); ?>! <a
							href="//sobe.ru/na/xml_for_google_merchant_center" target="_blank">
							<?php _e( 'Donate now', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</li>
					<li>
						<?php _e( 'Noticed a bug or have an idea how to improve the quality of the plugin', 'xml-for-google-merchant-center' ); ?>?
						<a href="mailto:support@icopydoc.ru">
							<?php _e( 'Let me know', 'xml-for-google-merchant-center' ); ?>
						</a>.
					</li>
				</ul>
				<p>
					<?php _e( 'The author of the plugin Maxim Glazunov', 'xml-for-google-merchant-center' ); ?>.
				</p>
				<p><span style="color: red;">
						<?php _e( 'Accept orders for individual revision of the plugin', 'xml-for-google-merchant-center' ); ?>
					</span>:<br /><a href="mailto:support@icopydoc.ru">
						<?php _e( 'Leave a request', 'xml-for-google-merchant-center' ); ?>
					</a>.</p>
			</div>
		</div>
		<?php
	}

	private function get_pref() {
		return $this->pref;
	}

	private function get_radio_name() {
		return $this->radio_name;
	}

	private function get_input_name() {
		return $this->input_name;
	}

	private function get_textarea_name() {
		return $this->textarea_name;
	}

	private function get_submit_name() {
		return $this->submit_name;
	}

	private function get_nonce_action() {
		return $this->nonce_action;
	}

	private function get_nonce_field() {
		return $this->nonce_field;
	}

	public function listen_submits_func() {
		if ( isset( $_REQUEST[ $this->get_submit_name()] ) ) {
			$this->send_data();
			add_action( 'admin_notices', function () {
				$class = 'notice notice-success is-dismissible';
				$message = __( 'The data has been sent. Thank you', 'xml-for-google-merchant-center' );
				printf( '<div class="%1$s"><p>%2$s</p></div>', $class, $message );
			}, 9999 );
		}
	}

	public function send_data() {
		if ( ! empty( $_POST ) && check_admin_referer( $this->get_nonce_action(), $this->get_nonce_field() ) ) {
			if ( is_multisite() ) {
				$xfgmc_is_multisite = 'включен';
				$xfgmc_keeplogs = get_blog_option( get_current_blog_id(), 'xfgmc_keeplogs' );
			} else {
				$xfgmc_is_multisite = 'отключен';
				$xfgmc_keeplogs = get_option( 'xfgmc_keeplogs' );
			}
			$unixtime = current_time( 'Y-m-d H:i' );
			$mail_content = '<h1>Заявка (#' . $unixtime . ')</h1>';
			$mail_content .= "Версия плагина: " . XFGMC_PLUGIN_VERSION . "<br />";
			$mail_content .= "Версия WP: " . get_bloginfo( 'version' ) . "<br />";
			$woo_version = xfgmc_get_woo_version_number();
			$mail_content .= "Версия WC: " . $woo_version . "<br />";
			$mail_content .= "Версия PHP: " . phpversion() . "<br />";
			$mail_content .= "Режим мультисайта: " . $xfgmc_is_multisite . "<br />";
			$mail_content .= "Вести логи: " . $xfgmc_keeplogs . "<br />";
			$upload_dir = wp_get_upload_dir();
			$mail_content .= 'Расположение логов: <a href="' . $upload_dir['baseurl'] . '/xfgmc/xfgmc.log" target="_blank">' . $upload_dir['basedir'] . '/xfgmc/xfgmc.log</a><br />';
			$possible_problems_arr = xfgmc_possible_problems_list();
			if ( $possible_problems_arr[1] > 0 ) {
				$possible_problems_arr[3] = str_replace( '<br/>', "<br />", $possible_problems_arr[3] );
				$mail_content .= "Самодиагностика: " . "<br />" . $possible_problems_arr[3];
			} else {
				$mail_content .= "Самодиагностика: Функции самодиагностики не выявили потенциальных проблем" . "<br />";
			}
			if ( ! class_exists( 'XmlforGoogleMerchantCenterPro' ) ) {
				$mail_content .= "Pro: не активна" . "<br />";
			} else {
				if ( ! defined( 'xfgmcp_VER' ) ) {
					define( 'xfgmcp_VER', 'н/д' );
				}
				$order_id = xfgmc_optionGET( 'xfgmcp_order_id' );
				$order_email = xfgmc_optionGET( 'xfgmcp_order_email' );
				$mail_content .= "Pro: активна (v " . xfgmcp_VER . " (#" . $order_id . " / " . $order_email . "))" . "<br />";
			}
			$yandex_zeng_rss = xfgmc_optionGET( 'yzen_yandex_zeng_rss' );
			$mail_content .= "RSS for Yandex Zen: " . $yandex_zeng_rss . "<br />";
			if ( isset( $_POST[ $this->get_radio_name()] ) ) {
				$mail_content .= "<br />" . "Помог ли плагин: " . sanitize_text_field( $_POST[ $this->get_radio_name()] );
			}
			if ( isset( $_POST[ $this->get_input_name()] ) ) {
				$mail_content .= '<br />Почта: <a href="mailto:' . sanitize_email( $_POST[ $this->get_input_name()] ) . '?subject=Ответ разработчика XML for Google Merchant Center (#' . $unixtime . ')" target="_blank" rel="nofollow noreferer" title="' . sanitize_email( $_POST['xfgmc_email'] ) . '">' . sanitize_email( $_POST['xfgmc_email'] ) . '</a>';
			}
			if ( isset( $_POST[ $this->get_textarea_name()] ) ) {
				$mail_content .= "<br />" . "Сообщение: " . sanitize_text_field( $_POST[ $this->get_textarea_name()] );
			}
			$argsp = array( 'post_type' => 'product', 'post_status' => 'publish', 'posts_per_page' => -1, 'fields' => 'ids', );
			$products = new WP_Query( $argsp );
			$vsegotovarov = $products->found_posts;
			$mail_content .= "<br />" . "Число товаров на выгрузку: " . $vsegotovarov;
			$xfgmc_settings_arr = xfgmc_optionGET( 'xfgmc_settings_arr' );
			$xfgmc_settings_arr_keys_arr = array_keys( $xfgmc_settings_arr );
			for ( $i = 0; $i < count( $xfgmc_settings_arr_keys_arr ); $i++ ) {
				$feed_id = $xfgmc_settings_arr_keys_arr[ $i ];
				$status_sborki = (int) xfgmc_optionGET( 'xfgmc_status_sborki', $feed_id );
				$xfgmc_file_url = urldecode( xfgmc_optionGET( 'xfgmc_file_url', $feed_id, 'set_arr' ) );
				$xfgmc_file_file = urldecode( xfgmc_optionGET( 'xfgmc_file_file', $feed_id, 'set_arr' ) );
				$xfgmc_desc = xfgmc_optionGET( 'xfgmc_desc', $feed_id, 'set_arr' );
				$xfgmc_whot_export = xfgmc_optionGET( 'xfgmc_whot_export', $feed_id, 'set_arr' );
				$xfgmc_skip_missing_products = xfgmc_optionGET( 'xfgmc_skip_missing_products', $feed_id, 'set_arr' );
				$xfgmc_skip_backorders_products = xfgmc_optionGET( 'xfgmc_skip_backorders_products', $feed_id, 'set_arr' );
				$xfgmc_status_cron = xfgmc_optionGET( 'xfgmc_status_cron', $feed_id, 'set_arr' );
				$xfgmc_ufup = xfgmc_optionGET( 'xfgmc_ufup', $feed_id, 'set_arr' );
				$xfgmc_date_sborki = xfgmc_optionGET( 'xfgmc_date_sborki', $feed_id, 'set_arr' );
				$xfgmc_main_product = xfgmc_optionGET( 'xfgmc_main_product', $feed_id, 'set_arr' );
				$xfgmc_errors = xfgmc_optionGET( 'xfgmc_errors', $feed_id, 'set_arr' );

				$mail_content .= "<h2>ФИД №: " . $feed_id . "</h2>";
				$mail_content .= "status_sborki: " . $status_sborki . "<br />";
				$mail_content .= "УРЛ: " . get_site_url() . "<br />";
				$mail_content .= "УРЛ XML-фида: " . $xfgmc_file_url . "<br />";
				$mail_content .= "Временный файл: " . $xfgmc_file_file . "<br />";
				$mail_content .= "Описание товара: " . $xfgmc_desc . "<br />";
				$mail_content .= "Что экспортировать: " . $xfgmc_whot_export . "<br />";
				$mail_content .= "Исключать товары которых нет в наличии (кроме предзаказа): " . $xfgmc_skip_missing_products . "<br />";
				$mail_content .= "Исключать из фида товары для предзаказа: " . $xfgmc_skip_backorders_products . "<br />";
				$mail_content .= "Автоматическое создание файла: " . $xfgmc_status_cron . "<br />";
				$mail_content .= "Обновить фид при обновлении карточки товара: " . $xfgmc_ufup . "<br />";
				$mail_content .= "Дата последней сборки XML: " . $xfgmc_date_sborki . "<br />";
				$mail_content .= "Что продаёт: " . $xfgmc_main_product . "<br />";
				$mail_content .= "Ошибки: " . $xfgmc_errors . "<br />";
			}

			add_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
			wp_mail( 'support@icopydoc.ru', 'Cтатистика о работе плагина XML for Google Merchant Center', $mail_content );
			// Сбросим content-type, чтобы избежать возможного конфликта
			remove_filter( 'wp_mail_content_type', array( $this, 'set_html_content_type' ) );
		}
	}

	public static function set_html_content_type() {
		return 'text/html';
	}
}