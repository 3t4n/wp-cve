<?php

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {

	exit;
}

/**
 * Add admin notify notice
 */
if ( ! function_exists( 'everest_toolkit_notify_notice' ) ) {

	function everest_toolkit_notify_notice() {

		$theme = everest_toolkit_theme();

		$theme_textDomain = $theme->get( 'TextDomain' );

		$theme_proLink = everest_toolkit_pro_link_generator( $theme_textDomain );

		if ( ! empty( $theme_proLink ) ) {

			everest_toolkit_notify_notice_dismiss();

			$activation_time = get_site_option( 'everest_toolkit_activate_time' );

			if ( ! $activation_time ) {

				add_site_option( 'everest_toolkit_activate_time', time() );
			}

			$daysinseconds = 30; // 5 Days in seconds (432000).

			if ( time() - $activation_time > $daysinseconds ) {

				add_action( 'admin_notices', 'everest_toolkit_notify_notice_message' );
			}

			add_action( 'admin_enqueue_scripts', 'everest_toolkit_notify_scripts' );

		}
	}
}
add_action( 'admin_init', 'everest_toolkit_notify_notice' );

/**
 * Static link as per theme text domain
 */
if ( ! function_exists( 'everest_toolkit_static_link' ) ) {

	function everest_toolkit_static_link() {

		$static_links = array(
			'everest-news'      => esc_url( 'https://everestthemes.com/themes/everest-news-pro/#et-item-pricing' ),

			'everest-news-lite' => esc_url( 'https://everestthemes.com/themes/everest-news-pro/#et-item-pricing' ),

			'viable-blog'       => esc_url( 'https://everestthemes.com/themes/viable-blog-pro/#et-item-pricing' ),

			'ultra-lite-blog'   => esc_url( 'https://everestthemes.com/themes/viable-blog-pro/#et-item-pricing' ),

			'mocho-blog'        => esc_url( 'https://everestthemes.com/themes/mocho-blog-pro/#et-item-pricing' ),

			'arya-multipurpose' => esc_url( 'https://everestthemes.com/themes/arya-multipurpose-pro-themes/#et-item-pricing' ),

			'gucherry-blog'     => esc_url( 'https://everestthemes.com/themes/gucherry-blog-pro/#pricing' ),

			'gucherry-lite'     => esc_url( 'https://everestthemes.com/themes/gucherry-blog-pro/#pricing' ),

			'grace-mag'         => esc_url( 'https://everestthemes.com/themes/grace-mag-pro/#pricing' ),

			'sports-highlight'  => esc_url( 'https://everestthemes.com/themes/sports-highlight/#pricing' ),
		);

		return $static_links;
	}
}

/**
 * For theme texdomain slug and link
 */
if ( ! function_exists( 'everest_toolkit_pro_link_generator' ) ) {

	function everest_toolkit_pro_link_generator( $theme_textDomain ) {

		if ( empty( $theme_textDomain ) ) {

			return;
		}

		$theme_proLink = '';

		$static_links = everest_toolkit_static_link();

		foreach ( $static_links as $value => $static_link ) {

			if ( $theme_textDomain == $value ) {

				$theme_proLink = $static_link;
			}
		}
		return $theme_proLink;
	}
}

/**
 * For theme texdomain slug and link
 */
if ( ! function_exists( 'everest_toolkit_theme_screenshot' ) ) {

	function everest_toolkit_theme_screenshot() {

		$main_dir = get_template_directory_uri();

		$theme_screenshot = $main_dir . '/screenshot.png';

		return $theme_screenshot;
	}
}

/**
 * For notice
 */
if ( ! function_exists( 'everest_toolkit_notify_notice_message' ) ) {

	function everest_toolkit_notify_notice_message() {

		$scheme = ( parse_url( $_SERVER['REQUEST_URI'], PHP_URL_QUERY ) ) ? '&' : '?';

		$url = $_SERVER['REQUEST_URI'] . $scheme . 'everest_toolkit_notify_notice_dismiss=yes';

		$dismiss_url = wp_nonce_url( $url, 'everest-toolkit-nonce' );

		$theme = everest_toolkit_theme();

		$theme_textDomain = $theme->get( 'TextDomain' );

		$theme_name = $theme->get( 'Name' );

		$theme_screenshot = everest_toolkit_theme_screenshot();

		$theme_proLink = everest_toolkit_pro_link_generator( $theme_textDomain );

		$offer_heading = esc_html__( 'BIG XMAS OFFER', 'everest-toolkit' );

		if ( ! empty( $theme_proLink ) ) {
			?>
			<div class="everest-toolkit-review-notice">
				<div class="everest-toolkit-review-thumbnail">
					<img src="<?php echo esc_url( $theme_screenshot ); ?>" alt="">
				</div>
				<div class="everest-toolkit-review-text">
					<h3 class="et-notice-pro-heading">
						<span id="et-offer-percent" class="et-offer-percent"></span>
						<span id="et-offer-heading" class="et-offer-heading"></span>
					</h3>
					<p class="et-notice-pro-grab">
						<?php echo sprintf( esc_html__( 'Grab %1$s of %2$s for Limited Time Only.', 'everest-toolkit' ), '<a href="' . esc_url( $theme_proLink ) . '" target="_blank">PREMIUM version</a>', esc_html( $theme_name ) ); ?>
					</p>
					<p id="et-discount-code" class="et-notice-discount"></p>
					<ul class="everest-toolkit-review-ul">
						<li class="show-mor-message">
							<a href="<?php echo esc_url( $theme_proLink ); ?>" target="_blank">
								<span class="dashicons dashicons-cart"></span>
								<?php esc_html_e( 'Grab Now', 'everest-toolkit' ); ?>
							</a>
						</li>
						<li class="hide-message">
							<a href="<?php echo $dismiss_url; ?>">
								<span class="dashicons dashicons-megaphone"></span>
								<?php esc_html_e( 'Remind Me Later', 'everest-toolkit' ); ?>
							</a>
						</li>
					</ul>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * For PRO Dismiss!
 */
if ( ! function_exists( 'everest_toolkit_notify_notice_dismiss' ) ) {

	function everest_toolkit_notify_notice_dismiss() {

		if ( ! is_admin() ||
			! current_user_can( 'manage_options' ) ||
			! isset( $_GET['_wpnonce'] ) ||
			! wp_verify_nonce( sanitize_key( wp_unslash( $_GET['_wpnonce'] ) ), 'everest-toolkit-nonce' ) ||
			! isset( $_GET['everest_toolkit_notify_notice_dismiss'] ) ) {

			return;
		}
		$daysinseconds = 10800; // 14 Days in seconds (1209600).
		$newtime       = time() + $daysinseconds;
		update_site_option( 'everest_toolkit_activate_time', $newtime );
	}
}

/**
 * For notify notice enqueues
 */
if ( ! function_exists( 'everest_toolkit_notify_scripts' ) ) {

	function everest_toolkit_notify_scripts() {

		wp_enqueue_style( 'everest-toolkit-notice-style', plugin_dir_url( __FILE__ ) . 'css/notice.css' );

		wp_enqueue_script( 'everest-toolkit-notice-script', plugin_dir_url( __FILE__ ) . 'js/notice.js', array(), EVERESTTOOLKIT_VERSION, true );

		wp_localize_script(
			'everest-toolkit-notice-script',
			'noticeObj',
			array(
				'siteURL' => esc_url( 'https://offer.wptemplatesell.com/' ),
			)
		);
	}
}
