<?php
class WpSaioShortcodes {

	private static $_instance = null;

	public function __construct() {
		$apps = WpSaio::defaultApps();
		foreach ( $apps as $k => $v ) {
			if ( isset( $v['shortcode'] ) && ! empty( $v['shortcode'] ) ) {
				$func = str_replace( '-', '', $k );
				if(str_contains($k, 'custom-app')) {
					add_shortcode( $v['shortcode'], array( $this,'customAppShortcode' ) );
				} else {
					add_shortcode( $v['shortcode'], array( $this, $func . 'Shortcode' ) );
				}
			}
		}
	}
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function facebookmessengerShortcode( $atts ) {
		ob_start();
		$atts     = shortcode_atts(
			array(
				'url' => '',
			),
			$atts
		);
		$atts     = extract( $atts );
		$urlArray = explode( '/', $url );
		?>
		<div class="nt-aio-popup nt-aio-messenger-popup" id="nt-aio-popup-facebook-messenger">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Messenger', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href='https://m.me/<?php echo $urlArray[3]; ?>'></a>
				<iframe wh-src="fbIframeURL" style="border:none; border-radius: 0 0 16px 16px; overflow:hidden" scrolling="no" allowtransparency="true" src="https://www.facebook.com/plugins/page.php?href=<?php echo esc_url( $url ); ?>&amp;tabs=messages&amp;small_header=true&amp;width=300&amp;height=300&amp;adapt_container_width=true&amp;hide_cover=true&amp;show_facepile=false&amp;appId" width="300" height="300" frameborder="0"></iframe>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<!-- /#nt-aio-popup-facebook-messenger.nt-aio-popup nt-aio-messenger-popup -->
		<?php
		return ob_get_clean();
	}
	public function whatsappShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'phone' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		preg_match_all( '/\d+/', $phone, $matches );
		$phone = isset($matches[0][0]) ? $matches[0][0] : '';
		?>
		<div class="nt-aio-popup nt-aio-whatsapp-popup" id="nt-aio-popup-whatsapp">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'WhatsApp', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="https://api.whatsapp.com/send?phone=<?php echo $phone; ?>" target="_blank">
					<?php echo $phone; ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<!-- /#nt-aio-popup-whatsapp.nt-aio-popup nt-aio-whatsapp-popup -->
		<?php
		return ob_get_clean();
	}
	public function snapchatShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'username' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-snapchat-popup" id="nt-aio-popup-snapchat">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Snapchat', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<!-- <a href="https://www.snapchat.com/add/
			<?php
			// echo $username
			?>
															"></a> -->
				<div class="content-snapchat-qrcode" wh-html="snapchatQRCode"><object data="https://feelinsonice-hrd.appspot.com/web/deeplink/snapcode?username=<?php echo $username; ?>&amp;type=PNG" type="image/png" width="200px" height="200px"></object></div>
				<div style="margin: 5px;" class="content-snapchat-name" wh-html-unsafe="snapchatUser">
					<a href="https://www.snapchat.com/add/<?php echo $username; ?>" target="_blank"><?php echo $username; ?></a>
				</div>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<!-- /#nt-aio-popup-snapchat.nt-aio-popup nt-aio-snapchat-popup -->
			<?php
			return ob_get_clean();
	}
	public function lineShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'url' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-line-popup" id="nt-aio-popup-line">
			<!-- <p class="test">Test</p> -->
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Line', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<iframe wh-src="lineIframeURL" scrolling="no" allowtransparency="true" src="<?php echo $url; ?>" frameborder="0"></iframe>
				<a href="http://line.me/R/ti/p/@<?php echo $url; ?>" target="_blank"></a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}
	public function viberShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'account' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-viber-popup" id="nt-aio-popup-viber">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Viber', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="http://chats.viber.com/<?php echo $account; ?>" target="_blank"><?php echo $account; ?></a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}
	public function phoneShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'phone_number' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-phone-popup" id="nt-aio-popup-phone">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Phone', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="tel:<?php echo $phone_number; ?>" target="_blank"><?php echo $phone_number; ?></a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}
	public function emailShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'email' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-email-popup" id="nt-aio-popup-email">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Email', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="mailto:<?php echo $email; ?>" target="_blank"><?php echo $email; ?></a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function telegramShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'username' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-telegram-popup" id="nt-aio-popup-telegram">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Telegram', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="https://t.me/<?php echo $username; ?>" target="_blank">
				<?php echo $username; ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function skypeShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'username' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-skype-popup" id="nt-aio-popup-skype">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Skype', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="skype:<?php echo $username; ?>?chat" target="_blank">
				<?php echo $username; ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function zaloShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'username' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-zalo-popup" id="nt-aio-popup-zalo">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Zalo', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="zalo:<?php echo $username; ?>?chat" target="_blank">
				<?php echo $username; ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function kakaotalkShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'username' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-kakaotalk-popup" id="nt-aio-popup-kakaotalk">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Kakaotalk', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="kakaotalk:<?php echo $username; ?>?chat" target="_blank">
				<?php echo $username; ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function wechatShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'email' => '',
			),
			$atts
		);
		$atts = extract( $atts );
		?>
		<div class="nt-aio-popup nt-aio-wechat-popup" id="nt-aio-popup-wechat">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( 'Wechat', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="mailto:<?php echo $email; ?>" target="_blank"><?php echo $email; ?></a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<?php
		return ob_get_clean();
	}

	public function customAppShortcode( $atts ) {
		ob_start();
		$atts = shortcode_atts(
			array(
				'url'              => '',
				'custom-app-title' => '',
				'url-icon'         => '',
				'color-icon'       => '',
			),
			$atts
		);
		// $atts = extract($atts);
		?>
		<div class="nt-aio-popup nt-aio-custom-app-popup" id="nt-aio-popup-custom-app">
			<div class="nt-aio-popup-header">
				<div class="nt-aio-popup-title">
					<div class="nt-aio-popup-title-icon"></div>
					<!-- /.nt-aio-popup-title-icon -->
					<div class="nt-aio-popup-title-txt"><?php _e( $atts['custom-app-title'] && $atts['custom-app-title'] !== '' ? $atts['custom-app-title'] : 'Custom App', WP_SAIO_LANG_PREFIX ); ?></div>
					<!-- /.nt-aio-popup-title-txt -->
				</div>
				<!-- /.nt-aio-popup-title -->
				<div class="nt-aio-popup-close js__nt_aio_close_popup"></div>
				<!-- /.nt-aio-popup-close -->
			</div>
			<!-- /.nt-aio-popup-header -->
			<div class="nt-aio-popup-content">
				<a href="<?php echo esc_attr( $atts['url'] ); ?>" target="_blank">
				<?php echo esc_attr( $atts['url'] ); ?>
				</a>
			</div>
			<!-- /.nt-aio-popup-content -->
		</div>
		<style>
			.nt-aio-popup-header {
				--backgroundColorCustomApp: <?php echo $atts['color-icon'] ? esc_attr( $atts['color-icon'] ) : '#007cc4'; ?>;
			}

			.nt-aio-popup-title-icon {
				--backgroundIconCustomApp: <?php echo $atts['url-icon'] ? 'url(' . esc_attr( $atts['url-icon'] ) . ')' : 'url("../images/custom-app.svg")'; ?>;
			}
		</style>
		<?php
		return ob_get_clean();
	}
}
