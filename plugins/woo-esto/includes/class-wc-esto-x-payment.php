<?php
class WC_Esto_X_Payment extends WC_Esto_Payment {

	public $show_calculator;

	function __construct() {

		$this->id            = 'esto_x';
		$this->method_title  = __( 'Esto X', 'woo-esto' );
		$this->method_description  = __( 'ESTO X is an alternative ESTO payment method. For more information and activation please contact ESTO Partner Support.', 'woo-esto' );
		$this->schedule_type = 'ESTO_X';
		$this->show_calculator = $this->get_option( 'show_calculator', 'no' );

		parent::__construct();

		$this->admin_page_title = __( 'ESTO X payment gateway', 'woo-esto' );
		$this->min_amount       = $this->get_option( 'min_amount', 0.1 );
		$this->max_amount       = $this->get_option( 'max_amount', 10000 );

		add_action( 'wp_enqueue_scripts', [$this, 'enqueue'] );
	}

	function init_form_fields() {

		parent::init_form_fields();

		$this->form_fields = [
			'enabled' => [
				'title'     => __( 'Enable/Disable', 'woo-esto' ),
				'type'      => 'checkbox',
				'label'     => __( 'ESTO X is a campaign of ESTO. Contact ESTO support for additional information.', 'woo-esto' ),
				'default'   => 'no',
			],
			'title' => [
				'title'         => __( 'Title', 'woo-esto' ),
				'type'          => 'text',
				'description'   => __( 'This controls the title which the user sees during checkout.', 'woo-esto' ),
				'default'       => __( 'Pay in 3 equal parts. At no extra charge.', 'woo-esto' ),
			],
			'description' => [
				'title'         => __( 'Description', 'woo-esto' ),
				'type'          => 'textarea',
				'description'   => __( 'This controls the description which the user sees during checkout.', 'woo-esto' ),
				'default'       => __( 'ESTO 3 - pay without additional cost within 3 months! Just pay later.', 'woo-esto' ),
			],
			'show_calculator' => [
				'title'   => __( 'Show calculator', 'woo-esto' ),
				'type'    => 'checkbox',
				'label'   => __( 'Show calculator in payment method\'s description area in checkout.', 'woo-esto' ),
				'default' => 'no',
			],
		]
		+ $this->description_logos
		+ [
			'show_logo'                          => $this->form_fields['show_logo'],
			'logo'                               => $this->form_fields['logo'],
		]
		+ $this->language_specific_logos
		+ [
			'min_amount'                         => $this->form_fields['min_amount'],
			'max_amount'                         => $this->form_fields['max_amount'],
			'disabled_countries_for_this_method' => $this->form_fields['disabled_countries_for_this_method'],
			'set_on_hold_status'                 => $this->form_fields['set_on_hold_status'],
			'order_prefix'                       => $this->form_fields['order_prefix'],
		];

		$this->form_fields['min_amount']['default'] = 0.1;
		$this->form_fields['max_amount']['default'] = 10000;
	}

	public function payment_fields() {
		if ( $this->show_calculator === 'yes' ) {
			$this->display_calculator();
		}

		parent::payment_fields();
	}

	public function display_calculator() {
		// 3 for now, could be increased
		$segments = 3;
		$period = 3;
		$frequency = $period / $segments;

		$multiplier = 100;

		$total = WC()->cart->get_total( 'raw' );
		if ( ! is_numeric( $total ) ) {
			// before WC 3.2, get_total gave a string with currency symbol included
			$total = WC()->cart->cart_contents_total;
		}

		$total *= $multiplier;

		$segment_amount = $total / $segments;
		$modulo = $total % $segments;
		$segment_amount = ( $total - $modulo ) / $segments;
		$cents = $modulo;
		?>
		<div class="esto-x-calc">
			<div class="esto-x-calc__title">
				<?= sprintf( __( '%d interest-free payments over %d months', 'woo-esto' ), $segments, $period ) ?>
			</div>
			<div class="esto-x-calc__segments">
				<?php for ( $i = 1; $i <= $segments; $i++ ) : ?>
					<div class="esto-x-calc__segment" data-segment="<?= $i ?>/<?= $segments ?>">
						<div class="esto-x-calc__img-wrap">
							<img src="<?= self::$plugin_url . 'assets/images/' . $i . '_3-slice-big.svg' ?>" width="56" height="56">
						</div>
						<div class="esto-x-calc__amount">
							<?php
							$amount = $segment_amount;
							if ( $cents > 0 ) {
								$amount++;
								$cents--;
							}
							$amount = $amount / $multiplier;
							echo function_exists( 'wc_price' ) ? wc_price( $amount ) : ( $amount . '€' );
							?>
						</div>
						<div class="esto-x-calc__date">
							<?= date_i18n( 'M', strtotime( 'first day of +' . $i * $frequency . ' months' ) ) ?>
						</div>
					</div>
				<?php endfor; ?>
			</div>
		</div>
		<?php
	}

	public function enqueue() {
		if ( is_checkout() && $this->enabled === 'yes' && $this->show_calculator === 'yes' ) {
			wp_enqueue_style( 'woo-esto-checkout-esto-x-css', plugins_url( 'assets/css/checkout-esto-x.css', dirname( __FILE__ ) ), false, filemtime( dirname( __FILE__, 2 ) . '/assets/css/checkout-esto-x.css' ) );
		}
	}
}
