<?php
/**
 * Shipping method
 *
 * @package dropp-for-woocommerce
 */

namespace Dropp\Shipping_Method;

use Dropp\Cost_Tier;
use Dropp\Data\Price_Data;
use Dropp\Data\Price_Info_Data;
use Dropp\Options;
use Dropp\Shipping_Settings;
use Dropp\API;
use Exception;
use WC_Shipping_Flat_Rate;
use WC_Tax;


/**
 * Shipping method
 */
abstract class Shipping_Method extends WC_Shipping_Flat_Rate
{
	use Shipping_Settings;

	/**
	 * Code (used for price information)
	 *
	 * @var ?string
	 */
	protected ?string $code;

	/**
	 * Rate title (default name for the shipping method)
	 *
	 * @var string
	 */
	protected string $default_title;

	/**
	 * Cost tiers
	 */
	protected array $costTiers = [];

	/**
	 * Weight Limit in KG
	 *
	 * @var int
	 */
	public int $weight_limit = 30;

	/**
	 * Daytime delivery true or false
	 *
	 * @var boolean
	 */
	public bool $day_delivery = false;

	/**
	 * Capital Area
	 *
	 * @var string One of 'inside', 'outside', '!inside' or 'both'
	 */
	protected static string $capital_area = 'both';

	/**
	 * No address available
	 *
	 * @var boolean Available when no address is provided
	 */
	protected static bool $no_address_available = false;

	/**
	 * Constructor.
	 *
	 * @param int $instance_id Shipping method instance.
	 */
	public function __construct(int $instance_id = 0)
	{
		$this->id                 = 'dropp_is';
		$this->instance_id        = absint($instance_id);
		$this->method_title       = __('Dropp', 'dropp-for-woocommerce');
		$this->default_title      = __( 'Dropp - Pick-up at location', 'dropp-for-woocommerce' );
		$this->method_description = __('Deliver parcels at delivery locations in Iceland', 'dropp-for-woocommerce');
		$this->supports           = array(
			'shipping-zones',
			'settings',
			'instance-settings',
			'instance-settings-modal',
		);

		$this->init();
	}

	/**
	 * Initialize free shipping.
	 */
	public function init(): void
	{
		Options::init($this);

		$wc_dir = defined('WC_PLUGIN_FILE') ? plugin_dir_path(WC_PLUGIN_FILE) : null;
		if (! $wc_dir) {
			return;
		}
		$this->instance_form_fields = include $wc_dir . 'includes/shipping/flat-rate/includes/settings-flat-rate.php';
		$this->instance_form_fields['title']['default'] = $this->default_title;

		// Modify default cost for shipping methods with cost tiers
		$this->instance_form_fields['cost']['default'] = '';

		// Load the settings.
		$this->init_properties();
		$this->init_cost_tiers();
		$this->init_instance_form_fields();
		$this->init_form_fields();
		$this->init_settings();


		// Actions.
		add_action('woocommerce_update_options_shipping_'.$this->id, [$this, 'process_admin_options']);
	}

	/**
	 * Is this method available?
	 *
	 * @param array $package Package.
	 *
	 * @return bool
	 */
	public function is_available($package): bool
	{
		if (static::$no_address_available && empty($package['destination']['postcode'])) {
			return true;
		}
		$is_available = true;
		$total_weight = 0;
		foreach ($package['contents'] as $item) {
			if (empty($item['data'])) {
				continue;
			}
			$total_weight += $item['quantity'] * wc_get_weight($item['data']->get_weight(), 'kg');
		}
		if ($total_weight > $this->weight_limit && 0 !== $this->weight_limit) {
			$is_available = false;
		} elseif (empty($package['destination']['country']) || empty($package['destination']['postcode'])) {
			$is_available = false;
		} elseif ('IS' !== $package['destination']['country']) {
			$is_available = false;
		} elseif (!$this->validate_postcode($package['destination']['postcode'], static::$capital_area)) {
			$is_available = false;
		}

		return apply_filters('woocommerce_shipping_'.$this->id.'_is_available', $is_available, $package, $this);
	}

	/**
	 * Validate postcode
	 *
	 * @param string $postcode     Postcode.
	 * @param string $capital_area (optional) One of 'inside', 'outside', '!inside' or 'both'.
	 *
	 * @return boolean Valid post code.
	 */
	public function validate_postcode(string $postcode, string $capital_area = 'inside'): bool
	{
		if ('both' === static::$capital_area) {
			return true;
		}
		$api       = new API($this);
		$postcodes = get_transient('dropp_delivery_postcodes');
		if (empty($postcodes) || !is_array($postcodes[0])) {
			$response  = $api->noauth()->get('dropp/location/deliveryzips');
			$postcodes = $response['codes'];
			set_transient('dropp_delivery_postcodes', $postcodes, 600);
		}

		foreach ($postcodes as $area) {
			if ("{$area['code']}" !== "{$postcode}") {
				continue;
			}
			if ('both' === $capital_area) {
				return true;
			}

			// Check if area matches inside or outside capital area.
			return ('inside' === $capital_area) === $area['capital'];
		}

		if ('!inside' === $capital_area) {
			return true;
		}

		return false;
	}

	public function generate_dropp_warning_html($key, $data) {
		$field_key = $this->get_field_key( $key );
		$defaults  = array(
			'title'             => '',
			'disabled'          => false,
			'class'             => '',
			'css'               => '',
			'placeholder'       => '',
			'type'              => 'text',
			'desc_tip'          => false,
			'description'       => '',
			'custom_attributes' => array(),
		);
		$data = wp_parse_args( $data, $defaults );
		ob_start();
		?>
		<tr valign="top" class="dropp-warning">
			<th scope="row" class="titledesc warning"><?php echo wp_kses_post($data['title']) ?></th>
			<td class="forminp">
				<?php echo $this->get_description_html( $data ); // WPCS: XSS ok. ?>
			</td>
		</tr>
		<?php
		return ob_get_clean();
	}

	/**
	 * Get additional form fields
	 *
	 * @param array $form_fields Form fields.
	 *
	 * @return array              Additional form fields.
	 */
	public function get_additional_form_fields(array $form_fields): array
	{
		return [
			'free_shipping'           => [
				'title'       => __('Free shipping', 'dropp-for-woocommerce'),
				'label'       => __('Enable', 'dropp-for-woocommerce'),
				'type'        => 'checkbox',
				'placeholder' => '',
				'description' => '',
				'default'     => '0',
				'desc_tip'    => false,
			],
			'free_shipping_threshold' => [
				'title'             => __('Free shipping for orders above', 'dropp-for-woocommerce'),
				'type'              => 'text',
				'placeholder'       => '0',
				'description'       => __('Only enable free shipping if the cart total exceeds this value.',
					'dropp-for-woocommerce'),
				'default'           => '0',
				'desc_tip'          => true,
				'sanitize_callback' => 'floatval',
			],
		];
	}

	public function set_instance_option(string $key, mixed $value)
	{
		$this->instance_settings[$key] = $value;
	}

	public function save_instance_settings(): bool
	{
		// Update method copied from WC_Shipping_Method::process_admin_options
		return update_option( $this->get_instance_option_key(), apply_filters( 'woocommerce_shipping_' . $this->id . '_instance_settings_values', $this->instance_settings, $this ), 'yes' );
	}

	public function get_code(): ?string
	{
		return $this->code;
	}

	/**
	 * Evaluate a cost from a sum/string.
	 *
	 * @param string $sum  Sum of shipping.
	 * @param array  $args Args, must contain `cost` and `qty` keys. Having `array()` as default is for back compat
	 *                     reasons.
	 */
	protected function evaluate_cost($sum, $args = array()): int|string
	{
		$cost          = parent::evaluate_cost($sum, $args);
		$free_shipping = $this->get_instance_option('free_shipping');
		$threshold     = $this->get_instance_option('free_shipping_threshold');

		if (apply_filters('dropp_free_shipping_enabled', 'yes' !== $free_shipping, $this, $sum, $args)) {
			return $cost;
		}
		if (empty($threshold) || empty($cost)) {
			// No threshold or no cost specified. Shipping is free.
			return 0;
		}
		$total      = WC()->cart->get_cart_contents_total();
		$calc_taxes = filter_var(
			get_option('woocommerce_calc_taxes'),
			FILTER_VALIDATE_BOOLEAN
		);
		if ($calc_taxes) {
			$prices_including_tax  = filter_var(
				get_option('woocommerce_prices_include_tax'),
				FILTER_VALIDATE_BOOLEAN
			);
			$display_including_tax = 'incl' === get_option('woocommerce_tax_display_cart');
			if ($prices_including_tax && $display_including_tax) {
				$total += WC()->cart->get_cart_contents_tax();
			}
			if ($prices_including_tax && !$display_including_tax) {
				$taxes     = WC_Tax::calc_inclusive_tax($threshold, WC_Tax::get_shipping_tax_rates());
				$threshold -= array_sum($taxes);
			}
		}

		if ($total < $threshold) {
			// Cart is less than threshold. Shipping is not free.
			return $cost;
		}

		// Free shipping aquired.
		return 0;
	}

	/**
	 * Get pricetype
	 *
	 * @return int
	 */
	public function get_pricetype(): int
	{
		$location_data = WC()->session->get('dropp_session_location');

		return intval($location_data['pricetype'] ?? 1);
	}

	/**
	 * Calculate the shipping costs.
	 *
	 * @param array $package Package of items from cart.
	 */
	public function calculate_shipping($package = array())
	{
		do_action('dropp_before_calculate_shipping', $package, $this);
		if ($this->get_pricetype() === 0) {
			$location_data = WC()->session->get('dropp_session_location');
			$this->add_rate([
				'id'      => $this->get_rate_id(),
				'label'   => $this->title,
				'cost'    => floatval($location_data['price'] ?? 0),
				'package' => $package,
			]);
		} else {
			parent::calculate_shipping($package);
		}
		do_action('dropp_after_calculate_shipping', $package, $this);
	}

	/**
	 * Sanitize the cost field.
	 *
	 * @param string $value Unsanitized value.
	 *
	 * @return string
	 * @throws Exception Last error triggered.
	 * @since 3.4.0
	 */
	public function sanitize_cost($value): string
	{
		do_action('dropp_before_calculate_shipping', [], $this);
		$value = parent::sanitize_cost($value);
		do_action('dropp_after_calculate_shipping', [], $this);

		return $value;
	}

	public function init_cost_tiers()
	{
		// Recursion protection because during the get request we use Shipping_Method\Dropp, which extends this
		// class, to update a setting that caches the response data.
		$prices          = Price_Info_Data::get_instance()->get($this->code);
		$previous_weight = 0;
		/** @var Price_Data $price */
		foreach ($prices as $price) {
			$this->costTiers[] = new Cost_Tier(
				$price->max_weight,
				sprintf(
					'%d kg &#8211; %d kg',
					$previous_weight,
					$price->max_weight
				),
				$price->price
			);
			$previous_weight   = $price->max_weight;
		}
	}

	public function get_cost_tiers(): array
	{
		return $this->costTiers;
	}

	private function init_instance_form_fields(): void
	{
		$form_fields = $this->instance_form_fields;
		$form_fields['title']['default'] = $this->default_title;
		$additional = $this->get_additional_form_fields($form_fields);
		if (empty($form_fields['cost'])) {
			// If there is no cost field then return early
			$this->instance_form_fields = array_merge($form_fields, $additional);
			return;
		}

		// Place cost fields in a separate array in order to sort settings correctly
		$cost_fields = [
			'cost' => $form_fields['cost']
		];
		if (! empty($this->costTiers)) {
			// Add cost tier settings

			/** @var Cost_Tier $costTier */
			$title = $cost_fields['cost']['title'];
			foreach ($this->costTiers as $i => $costTier) {
				$key = $costTier->getKey($i);
				if (!isset($cost_fields[$key])) {
					$cost_fields[$key] = $cost_fields['cost'];
				}
				$cost_fields[$key]['title']       = $title.' '.$costTier->suffix;
				$cost_fields[$key]['placeholder'] = $costTier->placeholder;
			}
			$cost_fields['load_prices_from_api'] = [
					'title'       => __('Load prices from API', 'dropp-for-woocommerce'),
					'type'        => 'button',
					'description' => __('Automatically fill in the cost fields above with suggested prices from Dropp. Note: Suggested prices are in ISK and clicking this button will overwrite existing settings.', 'dropp-for-woocommerce'),
					'placeholder' => __('Use suggested prices', 'dropp-for-woocommerce'),
					'default'     => __('Use suggested prices', 'dropp-for-woocommerce'),
			];
		}
		$options = Options::get_instance();

		if (! ($options->test_mode ? $options->api_key_test : $options->api_key)) {
			$cost_fields['warning'] = [
					'title'       => __('Missing API key', 'dropp-for-woocommerce'),
					'type'        => 'dropp_warning',
					'description' => __('No API key detected. Please ensure that you have entered a valid API key in order to display the correct cost settings',
							'dropp-for-woocommerce'),
					'default'     => '',
			];
		}
		$keys     = array_keys($form_fields);
		$filtered = preg_grep('/^cost(_\d+)?$/', $keys);
		$key      = end($filtered);
		$pos      = array_search($key, $keys, true);

		// Insert additional fields after costs.
		$this->instance_form_fields = array_merge(
				array_slice($form_fields, 0, $pos),
				$cost_fields,
				$additional,
				array_slice($form_fields, $pos + 1, null)
		);
	}
//	public function get_instance_form_fields()
//	{
//		$form_fields = parent::get_instance_form_fields();
//		$form_fields['title']['default'] = $this->default_title;
//		return $form_fields;
//	}
}
