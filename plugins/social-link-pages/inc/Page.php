<?php

namespace SocialLinkPages;

use SocialLinkPages\Db;

class Page extends Singleton {

	private $page_data = [];
	private $default_page_data = [];

	protected function setup() {
		$this->default_page_data = [
			'FacebookPixel'              => '',
			'GoogleAnalytics'            => '',
			'avatar'                     => '',
			'avatarAsBackground'         => 0,
			'avatarBorder'               => 1,
			'avatarLink'                 => '',
			'avatarShape'                => 'circle',
			'backgroundColor'            => '256, 256, 256',
			'backgroundCover'            => '',
			'backgroundImage'            => '',
			'backgroundMediaStyle'       => '',
			'backgroundParallax'         => 0,
			'backgroundStyle'            => 'color',
			'buttonBackgroundColor'      => '',
			'buttonBorderColor'          => '',
			'buttonBorderRadius'         => .382,
			'buttonHoverBackgroundColor' => '',
			'buttonHoverTextColor'       => '',
			'buttonTextColor'            => '',
			'buttons'                    => [],
			'cookieConsentMessage'       => __( 'This website uses cookies to collect and analyse information on site performance and usage.' ),
			'customCss'                  => '',
			'description'                => '',
			'displayName'                => 'Page',
			'displayNameFontSize'        => 2.5,
			'font'                       => '',
			'iconStyle'                  => 'outline',
			'label'                      => '',
			'sectionSpacing'             => 1,
			'showCookieConsent'          => 0,
			'skipEmailNonce'             => '',
			'slug'                       => '',
			'socialsBorder'              => '',
			'socialsPosition'            => 'bottom',
			'textColor'                  => '0, 0, 0',
		];

		add_action( 'init', array( $this, 'catch_slug' ), 0 );

		add_action( 'wp_ajax_' . Social_Link_Pages()->plugin_name_friendly
		            . '_send_email', array(
			$this,
			'ajax_send_email'
		) );
		add_action( 'wp_ajax_nopriv_'
		            . Social_Link_Pages()->plugin_name_friendly
		            . '_send_email',
			array(
				$this,
				'ajax_send_email'
			) );
		add_action( 'wp_ajax_nopriv_'
		            . Social_Link_Pages()->plugin_name_friendly
		            . '_button_click', array(
			$this,
			'ajax_button_click'
		) );

		add_action( 'wp_ajax_nopriv_'
		            . Social_Link_Pages()->plugin_name_friendly
		            . '_MailChimp_subscribe', array(
			$this,
			'ajax_MailChimp_subscribe'
		) );

		add_action( 'wp_ajax_nopriv_'
		            . Social_Link_Pages()->plugin_name_friendly
		            . '_Convertkit_subscribe', array(
			$this,
			'ajax_Convertkit_subscribe'
		) );
	}

	public function catch_slug() {
		if ( is_admin() ) {
			return;
		}

		$request_uri = strtok( $_SERVER["REQUEST_URI"], '?' );

		$post = get_page_by_path(
			$request_uri,
			OBJECT,
			[ Social_Link_Pages()->plugin_name_friendly ]
		);

		if ( ! $post instanceof \WP_Post ) {
			$post = get_page_by_path(
				basename( $request_uri ),
				OBJECT,
				[ Social_Link_Pages()->plugin_name_friendly ]
			);
		}

		if ( ! $post instanceof \WP_Post ) {
			return;
		}

		$this->render( $post );
		exit;
	}

	public function render( $post ) {
		if ( ! $post instanceof \WP_Post ) {
			$post = get_post( $post, OBJECT );
		}

		if ( ! $post instanceof \WP_Post ) {
			return false;
		}

		$page_data = Db::instance()->page_data_from_post( $post );

		if ( ! $page_data ) {
			$this->render_404();
		}

		http_response_code( 200 );

		if ( ! is_user_logged_in() ) {
			add_filter(
				Social_Link_Pages()->plugin_name_friendly
				. '_update_page_data_permission_check',
				'__return_true',
				PHP_INT_MAX
			);

			// Update count.
			$pageLoads = empty( $page_data->pageLoads ) ? 1
				: $page_data->pageLoads + 1;
			$page_data = Db::instance()->update_page_data( $page_data->id,
				[ 'pageLoads' => $pageLoads ] );

			remove_filter(
				Social_Link_Pages()->plugin_name_friendly
				. '_update_page_data_permission_check',
				'__return_true',
				PHP_INT_MAX
			);
		}

		try {
			if ( ! empty( $page_data->buttons ) ) {
				foreach ( $page_data->buttons as $index => &$button ) {
					$button = (object) $button;

					if ( ! empty( $button->type ) && in_array( $button->type, [ 'Mailchimp', 'Convertkit' ] ) ) {
						if ( ! empty( $button->APIKey )
						     && ! empty( $button->listId )
						     && ! empty( $button->label )
						) {
							$button->valueIsSet = true;
						}

						unset( $button->APIKey );
						unset( $button->listId );
					}

					if ( ! empty( $button->type ) && 'Email' === $button->type ) {
						if ( ! empty( $button->value ) ) {
							$button->valueIsSet = true;
						}
						unset( $button->value );
					}
				}
			}

			unset( $page_data->pageLoads );
		} catch ( Exception $e ) {
		}

		$this->page_data = $page_data;

		$allowed_callback_actions = [
			'_wp_render_title_tag',
			'_wp_footer_scripts',
			'wp_default_scripts',
			'wp_default_packages',
			'wp_enqueue_scripts',
			'wp_footer',
			'wp_head',
			'wp_print_footer_scripts',
			'wp_print_styles',
		];

		foreach ( $GLOBALS['wp_filter'] as $action => &$action_data ) {

			$slp_action = strpos( $action,
				Social_Link_Pages()->plugin_name_friendly );

			if ( in_array( $action, $allowed_callback_actions )
			     || $slp_action !== false
			) {
				foreach (
					$action_data->callbacks as $priority =>
					$callback_actions
				) {
					foreach (
						$callback_actions as $callback_action =>
						$callback_action_data
					) {
						if ( ! in_array( $callback_action,
								$allowed_callback_actions )
						     && $slp_action === false
						) {
							remove_action( $action, $callback_action,
								$priority );
						}
					}
				}
				continue;
			}

			unset( $GLOBALS['wp_filter'][ $action ] );
		}

		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ],
			PHP_INT_MAX );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_styles' ],
			PHP_INT_MAX );

		// Update title.
		add_filter( 'pre_get_document_title',
			[ $this, 'filter_page_title' ],
			1000000, 1 );
		add_filter( 'wp_title', [ $this, 'filter_page_title' ], 1000000,
			1 );
		add_filter( 'the_title', [ $this, 'filter_page_title' ], 1000000,
			1 );
		add_filter( 'document_title_parts',
			[ $this, 'filter_page_title_parts' ], 1000000, 1 );

		add_action( 'wp_head', [ $this, 'render_meta_tags' ] );
		add_action( 'wp_footer', [ $this, 'render_google_tag' ] );
		add_action( 'wp_footer', [ $this, 'render_facebook_tag' ] );

		do_action( Social_Link_Pages()->plugin_name_friendly
		           . '_page_render',
			$this->get_page_data() );

		include Social_Link_Pages()->plugin_dir_path
		        . '/link-page/link-page-template.php';
		exit;
	}

	public function render_google_tag() {
		if ( empty( $this->get_page_data()->GoogleAnalytics ) ) {
			return;
		}

		?>
		<!-- Global site tag (gtag.js) - Google Analytics -->
		<script async
			src="https://www.googletagmanager.com/gtag/js?id=<?php echo $this->get_page_data()->GoogleAnalytics ?>"></script>
		<script>
			window.dataLayer = window.dataLayer || [];

			function gtag() {
				dataLayer.push( arguments );
			}

			<?php if ( $this->get_page_data()->showCookieConsent ) : ?>
			gtag( 'consent', 'default', {
				ad_storage: 'denied',
				analytics_storage: 'denied',
				wait_for_update: 500, // milliseconds
			} );
			<?php endif ?>

			gtag( 'js', new Date() );
			gtag( 'config', '<?php echo $this->get_page_data()->GoogleAnalytics ?>', { 'anonymize_ip': true } );
		</script>
		<?php
	}

	public function render_facebook_tag() {
		if ( empty( $this->get_page_data()->FacebookPixel ) ) {
			return;
		}

		?>
		<!-- Facebook Pixel Code -->
		<script>
			!function( f, b, e, v, n, t, s ) {
				if ( f.fbq ) {
					return;
				}
				n = f.fbq = function() {
					n.callMethod ?
						n.callMethod.apply( n, arguments ) : n.queue.push( arguments );
				};
				if ( !f._fbq ) {
					f._fbq = n;
				}
				n.push = n;
				n.loaded = !0;
				n.version = '2.0';
				n.queue = [];
				t = b.createElement( e );
				t.async = !0;
				t.src = v;
				s = b.getElementsByTagName( e )[0];
				s.parentNode.insertBefore( t, s );
			}( window, document, 'script',
				'https://connect.facebook.net/en_US/fbevents.js' );
			<?php if ( $this->get_page_data()->showCookieConsent ) : ?>
			fbq( 'consent', 'revoke' );
			<?php endif ?>
			fbq( 'init', '<?php echo $this->get_page_data()->FacebookPixel ?>' );
			fbq( 'track', 'PageView' );
		</script>
		<noscript>
			<img height="1" width="1" style="display:none; position: fixed;"
				src="https://www.facebook.com/tr?id=<?php echo $page_data->FacebookPixel ?>&ev=PageView&noscript=1" />
		</noscript>
		<!-- End Facebook Pixel Code -->
		<?php
	}

	public function render_meta_tags() {
		$page = $this->get_page_data();

		$title       = ! empty( $page->ogTitle ) ? $page->ogTitle
			: $page->displayName;
		$description = ! empty( $page->ogDescription )
			? $page->ogDescription : $title;

		$avatar = ! empty( $page->ogImage ) ? $page->ogImage
			: ( ! empty( $page->avatar ) ? $page->avatar : '' );
		$type   = ! empty( $page->ogType ) ? $page->ogType : 'website';
		$url    = ! empty( $page->ogUrl ) ? $page->ogUrl
			: site_url() . '/' . ( ! empty( $page->slug ) ? $page->slug : '' );

		?>
		<?php if ( ! empty( $title ) ) : ?>
			<meta property="og:title"
				content="<?php echo esc_attr( $title ) ?>" />
		<?php endif ?>

		<?php if ( ! empty ( $description ) ) : ?>
			<meta name="description"
				content="<?php echo $description ?>" />
			<meta property="og:description"
				content="<?php echo $description ?>" />
		<?php endif ?>

		<?php if ( ! empty( $avatar ) ) : ?>
			<meta property="og:image"
				content="<?php echo esc_attr( $avatar ) ?>" />
		<?php endif ?>

		<meta property="og:type"
			content="<?php echo esc_attr( $type ) ?>" />
		<meta property="og:url" content="<?php echo esc_attr( $url ) ?>" />

		<?php
	}

	public function filter_page_title( $title ) {
		return empty( $this->get_page_data()->displayName ) ? $title
			: $this->get_page_data()->displayName;
	}

	public function filter_page_title_parts( $title_parts_array ) {
		$title_parts_array['title']
			= empty( $this->get_page_data()->displayName )
			? $title_parts_array['title']
			: $this->get_page_data()->displayName;

		return $title_parts_array;
	}

	public function enqueue_scripts() {

		if ( ! Social_Link_Pages()->use_local() ) {
			$scripts = Social_Link_Pages()->get_asset_urls( 'link-page',
				'js' );
		} else {
			$scripts = [
				[
					'name'    => 'bundle.js',
					'url'     => 'https://localhost:3001/static/js/bundle.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],
				],
				[
					'name'    => 'vendors~main.chunk.js',
					'url'     => 'https://localhost:3001/static/js/vendors~main.chunk.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],
				],
				[
					'name'    => 'main.chunk.js',
					'url'     => 'https://localhost:3001/static/js/main.chunk.js',
					'version' => Social_Link_Pages()->plugin_data()['Version'],

				],
			];
		}

		$scripts = apply_filters(
			Social_Link_Pages()->plugin_name_friendly
			. '_enqueue_scripts_scripts',
			$scripts
		);

		foreach ( $scripts as $script ) {
			wp_enqueue_script(
				$script['name'],
				$script['url'],
				array( 'wp-i18n' ),
				$script['version'],
				true
			);

			wp_set_script_translations( $script['name'],
				'social-link-pages' );
		}

		// Localize data to first SLP script.
		$first_script = reset( $scripts );
		$this->localize_footer_vars( $first_script['name'] );
	}

	public function enqueue_styles() {
		if ( ! Social_Link_Pages()->use_local() ) {
			$styles = Social_Link_Pages()->get_asset_urls( 'link-page',
				'css' );

			foreach ( $styles as $style ) {
				wp_enqueue_style(
					$style['name'],
					$style['url'],
					array(),
					$style['version']
				);
			}
		}
	}

	/**
	 * @link https://richjenks.com/wordpress-throw-404/
	 */
	public function render_404() {
		global $wp_query;
		$wp_query->set_404();

		add_action( 'wp_title', function () {
			return '404: Not Found';
		}, 9999 );

		status_header( 404 );
		nocache_headers();

		require get_404_template();

		exit;
	}

	public function ajax_button_click() {
		if ( is_user_logged_in() ) {
			wp_send_json_error();
		}

		if ( empty ( $_POST['page_id'] )
		     || empty ( $_POST['button_id'] )
		) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$page_id = sanitize_title( $_POST['page_id'] );

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'], $page_id )
		) {
			wp_send_json_error( [
				'error' => __( 'Invalid nonce.', 'social-link-pages' )
			] );
		}

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			wp_send_json_error( [
				'error' => __( 'Page not found.', 'social-link-pages' )
			] );
		}

		// find button
		$button = null;
		foreach ( $page_data->buttons as &$button ) {
			if ( $_POST['button_id'] === $button->id ) {
				add_filter(
					Social_Link_Pages()->plugin_name_friendly
					. '_update_page_data_permission_check',
					'__return_true',
					PHP_INT_MAX
				);

				// Update count.
				$button->buttonClicks = empty( $button->buttonClicks ) ? 1
					: $button->buttonClicks + 1;
				Db::instance()->update_page_data( $page_id, $page_data );

				remove_filter(
					Social_Link_Pages()->plugin_name_friendly
					. '_update_page_data_permission_check',
					'__return_true',
					PHP_INT_MAX
				);
				wp_send_json_success();
				break;
			}
		}

		wp_send_json_error( [
			'error' => __( 'Unknown error.', 'social-link-pages' )
		] );
	}

	public function ajax_send_email() {
		if ( empty( $_POST['email'] ) || empty ( $_POST['page_id'] )
		     || empty ( $_POST['message'] )
		) {
			wp_send_json_error( [
				'line'  => __LINE__,
				'error' => __( 'Missing param: ', 'social-link-pages' )
				           . json_encode( $_POST )
			] );
		}

		$page_id = intval( $_POST['page_id'] );

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			wp_send_json_error( [
				'line'  => __LINE__,
				'error' => __( 'Page not found.', 'social-link-pages' )
			] );
		}

		if ( ! $page_data->skipEmailNonce ) {
			if ( empty( $_POST['_wpnonce'] )
			     || ! wp_verify_nonce( $_POST['_wpnonce'], $page_id )
			) {
				wp_send_json_error( [
					'line'  => __LINE__,
					'error' => __( 'Invalid nonce.', 'social-link-pages' )
				] );
			}
		}

		$button = null;
		foreach ( $page_data->buttons as $b ) {
			if ( $_POST['button_id'] === $b->id ) {
				$button = $b;
				break;
			}
		}

		if ( ! $button ) {
			wp_send_json_error( [
				'line'  => __LINE__,
				'error' => __( 'Button not found.', 'social-link-pages' )
			] );
		}

		wp_mail(
			$button->value,
			/* translators: %s: Email address */
			sprintf( __( 'Email from %s', 'social-link-pages' ), site_url( $page_data->slug ) ),
			sanitize_textarea_field( $_POST['message'] ),
			array(
				sprintf( 'From:   <%s>', sanitize_email( $_POST['email'] ) )
			)
		);

		wp_send_json_success();
	}

	public function ajax_MailChimp_subscribe() {

		if ( empty( $_POST['email'] ) || empty ( $_POST['page_id'] )
		     || empty ( $_POST['button_id'] )
		) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$page_id = sanitize_title( $_POST['page_id'] );

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'], $page_id )
		) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			wp_send_json_error( [
				'error' => __( 'Page not found.', 'social-link-pages' )
			] );
		}

		// find button
		$button = null;
		foreach ( $page_data->buttons as $b ) {
			if ( $_POST['button_id'] === $b->id ) {
				$button = $b;
				break;
			}
		}

		// check api key
		if ( ! $button || empty( $button->APIKey ) ) {
			wp_send_json_error( [
				'error' => __( 'Button not found.', 'social-link-pages' )
			] );
		}

		// get domain from api key
		$APIKeyArr = explode( '-', $button->APIKey );
		$domain    = end( $APIKeyArr );

		if ( empty( $domain ) ) {
			wp_send_json_error( [
				'error' => __( 'Invalid API key.', 'social-link-pages' )
			] );
		}

		$body = array(
			'email_address' => sanitize_email( $_POST['email'] ),
			'status'        => 'subscribed'
		);

		$args = array(
			'method'  => 'POST',
			'headers' => array(
				'Authorization' => 'Basic ' . base64_encode( 'a:'
				                                             . $button->APIKey )

			),
			'body'    => json_encode( $body ),
		);

		$response = wp_remote_post(
			sprintf(
				'https://%s.api.mailchimp.com/3.0/lists/%s/members',
				$domain,
				$button->listId
			),
			$args
		);

		if ( is_wp_error( $response ) ) {
			$error_message = $response->get_error_message();
			wp_send_json_error( [
				'error' => "Something went wrong: $error_message"
			] );
		}

		if ( 200 === intval( wp_remote_retrieve_response_code( $response ) ) ) {
			wp_send_json_success();
		}

		$response_body = json_decode( wp_remote_retrieve_body( $response ), true );

		wp_send_json_error( [
			'error' => ! empty( $response_body['detail'] ) ? $response_body['detail'] : __( 'Whoops! You could not be subscribed.', 'social-link-pages' )
		] );
	}

	public function ajax_Convertkit_subscribe() {

		if ( empty( $_POST['email'] ) || empty ( $_POST['page_id'] )
		     || empty ( $_POST['button_id'] )
		) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		$page_id = sanitize_title( $_POST['page_id'] );

		if ( empty( $_POST['_wpnonce'] )
		     || ! wp_verify_nonce( $_POST['_wpnonce'], $page_id )
		) {
			wp_send_json_error( [
				'error' => __( 'Parameters missing.', 'social-link-pages' )
			] );
		}

		// Get page data
		$page_data = Db::instance()->page_data_from_post( $page_id );

		if ( ! $page_data ) {
			wp_send_json_error( [
				'error' => __( 'Page not found.', 'social-link-pages' )
			] );
		}

		// find button
		$button = null;
		foreach ( $page_data->buttons as $b ) {
			if ( $_POST['button_id'] === $b->id ) {
				$button = $b;
				break;
			}
		}

		// check api key
		if ( ! $button || empty( $button->APIKey ) ) {
			wp_send_json_error( [
				'error' => __( 'Button not found.', 'social-link-pages' )
			] );
		}

		$args = array(
			'method' => 'POST',
			'body'   => array(
				'email'   => sanitize_email( $_POST['email'] ),
				'api_key' => $button->APIKey
			)
		);

		$response = wp_remote_post(
			sprintf(
				'https://api.convertkit.com/v3/forms/%s/subscribe',
				$button->listId
			),
			$args
		);

		wp_send_json_success();
	}

	public function localize_footer_vars( $handle ) {

		$app_vars = apply_filters(
			Social_Link_Pages()->plugin_name_friendly . '_page_footer_vars',
			array(
				'admin_ajax' => admin_url( 'admin-ajax.php' ),
				'page'       => apply_filters(
					Social_Link_Pages()->plugin_name_friendly
					. '_page_footer_vars',
					$this->get_page_data()
				),
				'wpApiSettings' => [
					'root'  => esc_url_raw( rest_url() ),
					'nonce' => wp_create_nonce( 'wp_rest' )
				]
			)
		);

		wp_localize_script(
			$handle,
			Social_Link_Pages()->plugin_name_friendly,
			$app_vars
		);

	}

	public function get_page_data() {
		return $this->page_data;
	}

	public function get_default_page_data() {
		return apply_filters(
			'slp_page_default_data',
			$this->default_page_data
		);
	}
}
