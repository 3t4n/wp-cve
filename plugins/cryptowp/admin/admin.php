<?php
/**
 * Build admin panel and actions.
 *
 * @since 1.0
 */

class CryptoWP_Admin {

	/**
	 * List of properties used throughout class (set in constructor).
	 *
	 * @since 1.0
	 */

	public $add_page;
	public $currencies;
	public $strings;

	/**
	 * Start the engine, add admin page and setup names.
	 *
	 * @since 1.0
	 */

	public function init() {
		$this->strings = cryptowp_strings();
		$this->currencies = cryptowp_currencies();
		add_action( 'admin_menu', array( $this, 'add_menu' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
		add_action( 'admin_init', array( $this, 'register_setting' ) );
	}

	/**
	 * Register settings and hook into sanitizer.
	 *
	 * @since 1.0
	 */

	public function register_setting() {
		register_setting( 'cryptowp', 'cryptowp', array( $this, 'sanitize' ) );
	}

	/**
	 * Add admin page to menu.
	 *
	 * @since 1.0
	 */

	public function add_menu() {
		$this->add_page = add_menu_page( $this->strings['cryptocurrency'], $this->strings['crypto'], 'manage_options', 'cryptowp', array( $this, 'admin_page' ), 'dashicons-money', 20 );
	}

	/**
	 * Create admin page HTML and add fields.
	 *
	 * @since 1.0
	 */

	public function admin_page() {
		$strings = $this->strings;
		$option = get_option( 'cryptowp' );
		$currencies = $this->currencies;
		$currency = ! empty( $option['currency'] ) ? $option['currency'] : '';
		$currency_sign = ! empty( $option['currency_sign'] ) ? $option['currency_sign'] : '';
		include( CRYPTOWP_DIR . 'templates/admin/settings-page.php' );
	}

	/**
	 * Load admin scripts to CryptoWP admin page.
	 *
	 * @since 1.0
	 */

	public function admin_scripts() {
		$screen = get_current_screen();
		if ( $screen->base == $this->add_page ) {
			wp_enqueue_style( 'cryptowp-admin', CRYPTOWP_URL . 'assets/css/admin.css', array(), cryptowp_ver( 'assets/css/admin.css' ) );
			wp_enqueue_script( 'cryptowp-sortable', CRYPTOWP_URL . 'assets/js/sortable.js', array(), CRYPTOWP_VERSION, true );
			wp_enqueue_script( 'cryptowp-admin', CRYPTOWP_URL . 'assets/js/admin.js', array( 'jquery' ), cryptowp_ver( 'assets/js/admin.js' ), true );
			wp_enqueue_media();
		}
	}

	/**
	 * Build Coins listing template.
	 *
	 * @since 1.0
	 */

	public function coins() {
		$option = get_option( 'cryptowp' );
	?>
		<div id="cryptowp_coins" class="cryptowp-coins">
			<?php if ( ! empty( $option['coins'] ) ) : ?>
				<?php foreach ( $option['coins'] as $symbol => $fields ) : ?>
					<?php $this->coin( $symbol, $fields ); ?>
				<?php endforeach; ?>
			<?php endif; ?>
		</div>
	<?php }

	/**
	 * Individual Coin template.
	 *
	 * @since 1.0
	 */

	public function coin( $symbol, $fields ) {
		$strings = cryptowp_strings();
		$option = get_option( 'cryptowp' );
		$price = ! empty( $option['coins'][$symbol]['price'] ) ? $option['coins'][$symbol]['price'] : '';
		$id = ! empty( $fields['id'] ) ? $fields['id'] : '';
		include( CRYPTOWP_DIR . 'templates/admin/settings-coin.php' );
	}

	/**
	 * Sanitize option fields to know exactly what is
	 * being saved to the database.
	 *
	 * @since 1.0
	 */

	public function sanitize( $input ) {
		$save = array();

		foreach ( $input as $key => $fields ) {
			if ( $key == 'currency' )
				$save['currency'] = in_array( $input['currency'], $this->currencies ) ? $input['currency'] : '';

			if ( $key == 'currency_sign' )
				$save['currency_sign'] = sanitize_text_field( $input['currency_sign'] );

			if ( $key == 'version' )
				$save['version'] = sanitize_text_field( $input['version'] );

			if ( $key == 'coins' ) {
				foreach ( $fields as $symbol => $field ) {
					$save['coins'][$symbol]['name'] = sanitize_text_field( $input['coins'][$symbol]['name'] );
					$save['coins'][$symbol]['icon'] = esc_url( $input['coins'][$symbol]['icon'] );
					$save['coins'][$symbol]['id'] = esc_attr( $input['coins'][$symbol]['id'] );
					$save['coins'][$symbol]['url'] = esc_url( $input['coins'][$symbol]['url'] );
					$save['coins'][$symbol]['symbol'] = esc_html( $symbol );
					foreach ( array( 'percent', 'percent_hour', 'price', 'value', 'value_hour', 'price_btc', 'supply', 'market_cap' ) as $f )
						if ( ! empty( $input['coins'][$symbol][$f] ) )
							$save['coins'][$symbol][$f] = esc_html( $input['coins'][$symbol][$f] );
					$save['coins'][$symbol]['error'] = $input['coins'][$symbol]['error'] ? true : false;
				}
			}
		}

		return $save;
	}

}
$CryptoWP_Admin = new CryptoWP_Admin;
$CryptoWP_Admin->init();