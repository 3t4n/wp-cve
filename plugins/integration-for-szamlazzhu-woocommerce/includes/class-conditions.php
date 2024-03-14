<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'WC_Szamlazz_Conditions', false ) ) :

	class WC_Szamlazz_Conditions {

		//Get possible conditional values
		public static function get_conditions($group = 'notes') {

			//Get country list
			$countries_obj = new WC_Countries();
			$countries = $countries_obj->__get('countries');

			//Setup conditions
			$conditions = array(
				'payment_method' => array(
					"label" => __('Payment method', 'wc-szamlazz'),
					'options' => WC_Szamlazz_Helpers::get_payment_methods()
				),
				'shipping_method' => array(
					"label" => __('Shipping method', 'wc-szamlazz'),
					'options' => WC_Szamlazz_Helpers::get_shipping_methods()
				),
				'type' => array(
					"label" => __('Order type', 'wc-szamlazz'),
					'options' => array(
						'individual' => __('Individual', 'wc-szamlazz'),
						'company' => __('Company', 'wc-szamlazz'),
					)
				),
				'product_category' => array(
					'label' => __('Product category', 'wc-szamlazz'),
					'options' => array()
				),
				'product_attribute' => array(
					'label' => __('Product attributes', 'wc-szamlazz'),
					'options' => array()
				),
				'language' => array(
					'label' => __('Invoice language', 'wc-szamlazz'),
					'options' => WC_Szamlazz_Helpers::get_supported_languages()
				),
				'document' => array(
					'label' => __('Document type', 'wc-szamlazz'),
					'options' => array(
						'invoice' => __('Invoice', 'wc-szamlazz'),
						'proform' => __('Proforma invoice', 'wc-szamlazz'),
						'deposit' => __('Deposit invoice', 'wc-szamlazz'),
						'delivery' => __('Delivery note', 'wc-szamlazz'),
						'receipt' => __('Receipt', 'wc-szamlazz'),
					)
				),
				'account' => array(
					'label' => __('Számlázz.hu account', 'wc-szamlazz'),
					'options' => array()
				),
				'billing_address' => array(
					"label" => __('Billing address', 'wc-szamlazz'),
					'options' => array(
						'eu' => __('Inside the EU', 'wc-szamlazz'),
						'world' => __('Outside of the EU', 'wc-szamlazz'),
					)
				),
				'billing_country' => array(
					"label" => __('Billing country', 'wc-szamlazz'),
					'options' => $countries
				),
				'currency' => array(
					"label" => __('Order currency', 'wc-szamlazz'),
					'options' => array()
				),
				'shipping_class' => array(
					'label' => __('Shipping class', 'vp-woo-pont'),
					'options' => WC_Szamlazz_Helpers::get_shipping_classes()
				)
			);

			//Add category options
			foreach (get_terms(array('taxonomy' => 'product_cat')) as $category) {
				$conditions['product_category']['options'][$category->term_id] = $category->name;
			}

			//Add attribute options
			$attribute_taxonomies = wc_get_attribute_taxonomies();
			if ( $attribute_taxonomies ) {
				foreach ( $attribute_taxonomies as $tax ) {
					$taxonomy = wc_attribute_taxonomy_name( $tax->attribute_name );
					if ( taxonomy_exists( $taxonomy ) ) {
						$terms        = get_terms( $taxonomy, 'hide_empty=0' );
						foreach ($terms as $term) {
							$conditions['product_attribute']['options'][$term->term_id] = $tax->attribute_label . " - " . $term->name;
						}
					}
				}
			}

			//Add account options
			foreach (WC_Szamlazz()->get_szamlazz_accounts() as $account_key => $account_name) {
				$conditions['account']['options'][$account_key] = $account_name.' - '.substr(esc_html($account_key), 0, 10).'...';
			}

			//Add currency options
			$currency_code_options = get_woocommerce_currencies();
			foreach ( $currency_code_options as $code => $name ) {
				$conditions['currency']['options'][ $code ] = $name . ' (' . get_woocommerce_currency_symbol( $code ) . ')';
			}

			//Apply filters
			$conditions = apply_filters('wc_szamlazz_'.$group.'_conditions', $conditions);

			//Sort ascending by label
			uasort($conditions, function($a, $b){
				return strcmp($a['label'], $b['label']);
			});

			return $conditions;
		}

		public static function get_sample_row($group = 'notes') {
			$conditions = self::get_conditions($group);
			ob_start();
			?>
			<script type="text/html" id="wc_szamlazz_<?php echo $group; ?>_condition_sample_row">
				<li>
					<select class="condition" data-name="wc_szamlazz_<?php echo $group; ?>[X][conditions][Y][category]">
						<?php foreach ($conditions as $condition_id => $condition): ?>
							<option value="<?php echo esc_attr($condition_id); ?>"><?php echo esc_html($condition['label']); ?></option>
						<?php endforeach; ?>
					</select>
					<select class="comparison" data-name="wc_szamlazz_<?php echo $group; ?>[X][conditions][Y][comparison]">
						<option value="equal"><?php _e('Equal', 'wc-szamlazz'); ?></option>
						<option value="not_equal"><?php _e('Not equal', 'wc-szamlazz'); ?></option>
					</select>
					<?php foreach ($conditions as $condition_id => $condition): ?>
						<select class="value <?php if($condition_id == 'payment_method'): ?>selected<?php endif; ?>" data-condition="<?php echo esc_attr($condition_id); ?>" data-name="wc_szamlazz_<?php echo $group; ?>[X][conditions][Y][<?php echo esc_attr($condition_id); ?>]" <?php if($condition_id != 'payment_method'): ?>disabled="disabled"<?php endif; ?>>
							<?php foreach ($condition['options'] as $option_id => $option_name): ?>
								<option value="<?php echo esc_attr($option_id); ?>"><?php echo esc_html($option_name); ?></option>
							<?php endforeach; ?>
						</select>
					<?php endforeach; ?>
					<a href="#" class="add-row"><span class="dashicons dashicons-plus-alt"></span></a>
					<a href="#" class="delete-row"><span class="dashicons dashicons-dismiss"></span></a>
				</li>
			</script>
			<?php
			return ob_get_clean();
		}

		public static function get_order_details($order, $group) {

			//Get order type
			$order_type = ($order->get_billing_company()) ? 'company' : 'individual';

			//Get billing address location
			$eu_countries = WC()->countries->get_european_union_countries('eu_vat');
			$billing_address = 'world';
			if(in_array($order->get_billing_country(), $eu_countries)) {
				$billing_address = 'eu';
			}

			//Get payment method id
			$payment_method = $order->get_payment_method();

			//Get shipping method id
			$shipping_method = '';
			$shipping_methods = $order->get_shipping_methods();
			if($shipping_methods) {
				foreach( $shipping_methods as $shipping_method_obj ){
					$shipping_method = $shipping_method_obj->get_method_id().':'.$shipping_method_obj->get_instance_id();
				}
			}

			//Get product category ids and shipping classes
			$product_categories = array();
			$shipping_classes = array();
			$order_items = $order->get_items();
			foreach ($order_items as $order_item) {
				if($order_item->get_product() && $order_item->get_product()->get_category_ids()) {
					$product_categories = $product_categories+$order_item->get_product()->get_category_ids();
				}
		
				if($order_item->get_product() && $order_item->get_product()->get_shipping_class()) {
					$shipping_classes[] = $order_item->get_product()->get_shipping_class();
				}

				//Fix for variations
				$product_id = $order_item->get_product_id();
				$variation_id = $order_item->get_variation_id();
				if($product_id && $variation_id) {
					$product = wc_get_product($product_id);
					$categories = $product->get_category_ids();
					$shipping_class = $product->get_shipping_class();
					if($categories) {
						$product_categories = $product_categories+$categories;
					}
					if($shipping_class) {
						$shipping_classes[] = $shipping_class;
					}
				} 
			}

			//Get attribute ids
			$product_attributes = array();
			$order_items = $order->get_items();
			foreach ($order_items as $order_item) {
				if($order_item->get_product_id()) {
					$product = wc_get_product($order_item->get_product_id());
					if($product && $product->get_attributes()) {
						foreach ($product->get_attributes() as $attribute) {
							if(is_object($attribute)) {
								$terms = $attribute->get_terms();
								if($terms) {
									foreach ($terms as $term) {
										$product_attributes[] = $term->term_id;
									}
								}
							}
						}
					}
				}
			}

			//Account
			$api_key = WC_Szamlazz()->get_szamlazz_agent_key($order);
			$account = $api_key;

			//Setup parameters for conditional check
			$order_details = array(
				'payment_method' => $payment_method,
				'shipping_method' => $shipping_method,
				'type' => $order_type,
				'billing_address' => $billing_address,
				'billing_country' => $order->get_billing_country(),
				'product_categories' => $product_categories,
				'product_attribute' => $product_attributes,
				'account' => $account,
				'currency' => $order->get_currency(),
				'shipping_classes' => $shipping_classes
			);

			//Custom conditions
			return apply_filters('wc_szamlazz_'.$group.'_conditions_values', $order_details, $order);

		}

		public static function match_conditions($items, $item_id, $order_details) {
			$item = $items[$item_id];

			//Check if the conditions match
			foreach ($item['conditions'] as $condition_id => $condition) {
				$comparison = ($condition['comparison'] == 'equal');

				switch ($condition['category']) {
					case 'product_category':
						if(in_array($condition['value'], $order_details['product_categories'])) {
							$items[$item_id]['conditions'][$condition_id]['match'] = $comparison;
						} else {
							$items[$item_id]['conditions'][$condition_id]['match'] = !$comparison;
						}
						break;
					case 'product_attribute':
						if(in_array($condition['value'], $order_details['product_attribute'])) {
							$items[$item_id]['conditions'][$condition_id]['match'] = $comparison;
						} else {
							$items[$item_id]['conditions'][$condition_id]['match'] = !$comparison;
						}
						break;
					case 'shipping_class':
						if(in_array($condition['value'], $order_details['shipping_classes'])) {
							$items[$item_id]['conditions'][$condition_id]['match'] = $comparison;
						} else {
							$items[$item_id]['conditions'][$condition_id]['match'] = !$comparison;
						}
						break;
					default:
						if($condition['value'] == $order_details[$condition['category']]) {
							$items[$item_id]['conditions'][$condition_id]['match'] = $comparison;
						} else {
							$items[$item_id]['conditions'][$condition_id]['match'] = !$comparison;
						}
						break;
				}
			}

			//Count how many matches we have
			$matched = 0;
			foreach ($items[$item_id]['conditions'] as $condition) {
				if($condition['match']) $matched++;
			}

			//Check if we need to match all or just one
			$condition_is_a_match = false;
			if(!isset($item['logic'])) $item['logic'] = 'and';
			if($item['logic'] == 'and' && $matched == count($item['conditions'])) $condition_is_a_match = true;
			if($item['logic'] == 'or' && $matched > 0) $condition_is_a_match = true;

			return $condition_is_a_match;
		}

		public static function check_advanced_options($szamla, $order, $document_type) {
			$order_details = self::get_order_details($order, 'advanced_options');
			$order_details['document'] = $document_type;

			//For manual invoicing
			$order_details['account'] = $szamla->beallitasok->szamlaagentkulcs;

			//Check for options
			$advanced_options = get_option('wc_szamlazz_advanced_options', array());

			//Skip if theres none
			if(empty($advanced_options)) {
				return $szamla;
			}

			//Check one by one
			foreach ($advanced_options as $option_id => $option) {

				//Check for entitlements
				if(false) {
					//Only for billingo
				} else {
					//Compare conditions with order details and see if we have a match
					$is_a_match = self::match_conditions($advanced_options, $option_id, $order_details);

					//If its not a match, continue to next one
					if(!$is_a_match) continue;

					//It is a match, so try to change parameters
					if($option['property'] == 'bank_name') {
						$szamla->elado->bank = $option['value'];
					}

					if($option['property'] == 'bank_number') {
						$szamla->elado->bankszamlaszam = $option['value'];
					}

					if($option['property'] == 'prefix') {
						$szamla->fejlec->szamlaszamElotag = $option['value'];
					}

					if($option['property'] == 'language') {
						$szamla->fejlec->szamlaNyelve = $option['value'];
					}
				}

			}

			return $szamla;
		}

	}

endif;
