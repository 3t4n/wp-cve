<?php

class BWFAN_Rule_Order_Total extends BWFAN_Rule_Base {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_total' );
	}

	/** v2 Methods: START */

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		if ( ! isset( $automation_data['global']['order_id'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$price = (float) $order->get_total();
		$value = (float) $rule_data['data'];

		switch ( $rule_data['rule'] ) {
			case '==':
				$result = $price === $value;
				break;
			case '!=':
				$result = $price !== $value;
				break;
			case '>':
				$result = $price > $value;
				break;
			case '<':
				$result = $price < $value;
				break;
			case '>=':
				$result = $price >= $value;
				break;
			case '<=':
				$result = $price <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data ) {
		$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$price = (float) $order->get_total();
		$value = (float) $rule_data['condition'];

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $price === $value;
				break;
			case '!=':
				$result = $price !== $value;
				break;
			case '>':
				$result = $price > $value;
				break;
			case '<':
				$result = $price < $value;
				break;
			case '>=':
				$result = $price >= $value;
				break;
			case '<=':
				$result = $price <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		?>
        Order Total
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Product_Stock extends BWFAN_Rule_Base {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_stock' );
	}

	/** v2 Methods: START */

	public function get_rule_type() {
		return 'Number';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$item  = BWFAN_Rules::get_order_item_object( $automation_data );
		$order = BWFAN_Rules::get_order_object( $automation_data );

		$product = BWFAN_Woocommerce_Compatibility::get_product_from_item( $order, $item );
		if ( ! $product instanceof WC_Product ) {
			return $this->return_is_match( false, $rule_data );
		}

		$qty   = $product->get_stock_quantity();
		$value = (int) $rule_data['data'];

		switch ( $rule_data['rule'] ) {
			case '==':
				$result = $qty === $value;
				break;
			case '!=':
				$result = $qty !== $value;
				break;
			case '>':
				$result = $qty > $value;
				break;
			case '<':
				$result = $qty < $value;
				break;
			case '>=':
				$result = $qty >= $value;
				break;
			case '<=':
				$result = $qty <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data ) {
		$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		if ( empty( $cart_item ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$product = BWFAN_Woocommerce_Compatibility::get_product_from_item( $order, $cart_item );
		if ( ! $product instanceof WC_Product ) {
			return $this->return_is_match( false, $rule_data );
		}

		$price = $product->get_stock_quantity();
		$value = (int) $rule_data['condition'];

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $price === $value;
				break;
			case '!=':
				$result = $price !== $value;
				break;
			case '>':
				$result = $price > $value;
				break;
			case '<':
				$result = $price < $value;
				break;
			case '>=':
				$result = $price >= $value;
				break;
			case '<=':
				$result = $price <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Product Stock', 'wp-marketing-automations' );
		?>

        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Product_Item extends BWFAN_Rule_Products {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_item' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_products( $automation_data = [] ) {
		$found_ids = [];
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order     = BWFAN_Rules::get_order_object( $automation_data );
			$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );
		} else {
			$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		}

		if ( ! empty( $cart_item ) && $cart_item instanceof WC_Order_Item_Product ) {
			return $this->get_product_ids( $found_ids, $order, $cart_item );
		}

		if ( $order instanceof WC_Order ) {
			foreach ( $order->get_items() as $item ) {
				$found_ids = $this->get_product_ids( $found_ids, $order, $item );
			}
		}

		return $found_ids;
	}

	public function get_product_ids( $found_ids, $order, $cart_item ) {
		$product = BWFAN_Woocommerce_Compatibility::get_product_from_item( $order, $cart_item );
		if ( ! $product instanceof WC_Product ) {
			return $found_ids;
		}

		$product_id   = $product->get_id();
		$product_id   = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
		$variation_id = $cart_item->get_variation_id();

		if ( ! empty( $variation_id ) ) {
			array_push( $found_ids, $variation_id );
			array_push( $found_ids, $product_id );
		} else {
			array_push( $found_ids, $product_id );
		}

		return $found_ids;
	}

	public function ui_view() {
		esc_html_e( 'Ordered Product ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %> <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <%
        if(_.has(uiData, value)) {
        chosen.push(uiData[value]);
        }
        %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'all'  => __( 'matches exactly', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Order_Taxonomy extends BWFAN_Rule_Term_Taxonomy {

	public $taxonomy_name = '';

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_category' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_term_ids( $automation_data = [] ) {

		$all_terms = array();

		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );
			$order     = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		}

		if ( ! empty( $cart_item ) ) {
			return $this->get_product_terms( $all_terms, $order, $cart_item );
		}

		foreach ( $order->get_items() as $item ) {
			$all_terms = $this->get_product_terms( $all_terms, $order, $item );
		}

		return $all_terms;
	}

	public function ui_view() {
		esc_html_e( 'Ordered Products Taxonomy ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %><% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Product_Category extends BWFAN_Rule_Term_Taxonomy {

	public $taxonomy_name = 'product_cat';

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_category' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_term_ids( $automation_data = [] ) {
		$all_terms = array();

		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order     = BWFAN_Rules::get_order_object( $automation_data );
			$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );
		} else {
			$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		}

		if ( ! empty( $cart_item ) ) {
			return $this->get_product_terms( $all_terms, $order, $cart_item );
		}

		foreach ( $order->get_items() as $item ) {
			$all_terms = $this->get_product_terms( $all_terms, $order, $item );
		}

		return $all_terms;
	}

	public function ui_view() {
		esc_html_e( 'Product Categories ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %><% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Product_Tags extends BWFAN_Rule_Term_Taxonomy {

	public $taxonomy_name = 'product_tag';

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_tags' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_term_ids( $automation_data = [] ) {
		$all_terms = array();
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order     = BWFAN_Rules::get_order_object( $automation_data );
			$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );
		} else {
			$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		}

		if ( ! empty( $cart_item ) ) {
			return $this->get_product_terms( $all_terms, $order, $cart_item );
		}

		foreach ( $order->get_items() as $item ) {
			$all_terms = $this->get_product_terms( $all_terms, $order, $item );
		}

		return $all_terms;
	}

	public function ui_view() {
		esc_html_e( 'Product Tags ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %><% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Product_Item_Type extends BWFAN_Rule_Base {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_item_type' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );
		$item  = BWFAN_Rules::get_order_item_object( $automation_data );

		$rule = $rule_data['rule'];
		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$saved_type = array_map( function ( $type ) {
			return $type['key'];
		}, $data );
		$all_types  = array();

		if ( ! empty( $item ) && $order instanceof WC_Order ) {
			$all_types = $this->get_product_types( $all_types, $order, $item );
		}

		if ( empty( $all_types ) && $order instanceof WC_Order ) {
			foreach ( $order->get_items() as $item ) {
				$all_types = $this->get_product_types( $all_types, $order, $item );
			}
		}

		if ( empty( $all_types ) ) {
			$result = ( 'none' === $rule ) ? true : false;

			return $this->return_is_match( $result, $rule_data );
		}

		$result = false;
		switch ( $rule ) {
			case 'any':
				if ( is_array( $saved_type ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $saved_type, $all_types ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $saved_type ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $saved_type, $all_types ) ) === 0;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$type      = $rule_data['operator'];
		$all_types = array();
		$result    = false;
		$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );

		if ( ! empty( $cart_item ) ) {
			return $this->get_product_types( $all_types, $order, $cart_item );
		}

		foreach ( $order->get_items() as $item ) {
			$all_types = $this->get_product_types( $all_types, $order, $item );
		}

		if ( empty( $all_types ) ) {
			$result = ( 'none' === $type ) ? true : false;

			return $this->return_is_match( $result, $rule_data );
		}

		switch ( $type ) {
			case 'any':
				if ( is_array( $rule_data['condition'] ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $rule_data['condition'], $all_types ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $rule_data['condition'] ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $rule_data['condition'], $all_types ) ) === 0;
				}
				break;

			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function get_product_types( $all_types, $order, $cart_item ) {
		$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
		if ( ! $product instanceof WC_Product ) {
			return $all_types;
		}

		$product_id    = $product->get_id();
		$product_id    = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
		$product_types = wp_get_post_terms( $product_id, 'product_type', array(
			'fields' => 'ids',
		) );
		$all_types     = array_merge( $all_types, $product_types );
		$all_types     = array_filter( $all_types );

		return $all_types;
	}

	public function ui_view() {
		?>
        Product Type
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}

	public function get_ui_preview_data() {
		return $this->get_possible_rule_values();
	}

	public function get_possible_rule_values() {
		$terms = get_terms( 'product_type', array(
			'hide_empty' => false,
		) );

		$result = [];
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( 'grouped' === $term->name ) {
					continue;
				}
				$result[ $term->term_id ] = $term->name;
			}
		}

		return $result;
	}

}

class BWFAN_Rule_Product_Item_Count extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_item_count' );
	}

	/** v2 Methods: START */

	public function get_rule_type() {
		return 'Number';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );

		$quantity = ( isset( $cart_item['quantity'] ) ) ? $cart_item['quantity'] : 0;
		$count    = absint( $quantity );
		$operator = $rule_data['rule'];
		$value    = absint( $rule_data['data'] );
		switch ( $operator ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data ) {
		/**
		 * @var WC_order $order
		 */

		$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		$quantity  = ( isset( $cart_item['quantity'] ) ) ? $cart_item['quantity'] : 0;
		$count     = intval( $quantity );
		$value     = absint( $rule_data['condition'] );

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		?>
        Product Count
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Product_Item_Price extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_item_price' );
	}

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );

		$price = (float) $cart_item->get_total();

		$operator = $rule_data['rule'];
		$value    = (float) $rule_data['data'];
		switch ( $operator ) {
			case '==':
				$result = $price === $value;
				break;
			case '!=':
				$result = $price !== $value;
				break;
			case '>':
				$result = $price > $value;
				break;
			case '<':
				$result = $price < $value;
				break;
			case '>=':
				$result = $price >= $value;
				break;
			case '<=':
				$result = $price <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function is_match( $rule_data ) {
		/**
		 * @var WC_Order_Item
		 */
		$item  = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		$count = (float) $item->get_total();
		$value = (float) $rule_data['condition'];

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		?>
        Product Price
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Order_Item extends BWFAN_Rule_Products {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_item' );

	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_products( $automation_data = [] ) {

		$found_ids = [];
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		if ( ! $order instanceof WC_Order ) {
			return $found_ids;
		}

		if ( $order->get_items() && is_array( $order->get_items() ) && count( $order->get_items() ) ) {
			foreach ( $order->get_items() as $cart_item ) {

				$product = BWFAN_Woocommerce_Compatibility::get_product_from_item( $order, $cart_item );
				if ( ! $product instanceof WC_Product ) {
					continue;
				}

				$product_id   = $product->get_id();
				$product_id   = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
				$variation_id = $cart_item->get_variation_id();

				if ( ! empty( $variation_id ) ) {
					array_push( $found_ids, $variation_id );
					array_push( $found_ids, $product_id );
				} else {
					array_push( $found_ids, $product_id );
				}
			}
		}

		return $found_ids;
	}

	public function ui_view() {
		esc_html_e( 'Ordered Products ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %> <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <%
        if(_.has(uiData, value)) {
        chosen.push(uiData[value]);
        }
        %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

}

class BWFAN_Rule_Order_Category extends BWFAN_Rule_Term_Taxonomy {

	public $taxonomy_name = 'product_cat';

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_category' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_term_ids( $automation_data = [] ) {
		$all_terms = array();
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		if ( ! $order instanceof WC_Order ) {
			return $all_terms;
		}

		if ( ! is_array( $order->get_items() ) || 0 === count( $order->get_items() ) ) {
			return $all_terms;
		}

		foreach ( $order->get_items() as $cart_item ) {
			$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
			if ( ! $product instanceof WC_Product ) {
				continue;
			}

			$product_id = $product->get_id();

			$terms = wp_get_object_terms( $product_id, $this->taxonomy_name, array(
				'fields' => 'ids',
			) );
			if ( $terms instanceof WP_Error || empty( $terms ) ) {
				$terms = [];
			}

			if ( ! empty( $product->get_parent_id() ) ) {
				$parent_terms = wp_get_object_terms( $product->get_parent_id(), $this->taxonomy_name, array(
					'fields' => 'ids',
				) );
				if ( ! $parent_terms instanceof WP_Error && count( $parent_terms ) > 0 ) {
					$terms = array_merge( $terms, $parent_terms );
				}
			}
			$all_terms = array_merge( $all_terms, $terms );
		}

		return array_filter( array_unique( $all_terms ) );
	}

	public function ui_view() {
		esc_html_e( 'Ordered Products Categories ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %><% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

}

class BWFAN_Rule_Order_Tags extends BWFAN_Rule_Term_Taxonomy {

	public $taxonomy_name = 'product_tag';

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_tags' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_term_ids( $automation_data = [] ) {
		$all_terms = array();
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		if ( ! $order instanceof WC_Order ) {
			return $all_terms;
		}

		if ( is_array( $order->get_items() ) && count( $order->get_items() ) > 0 ) {
			foreach ( $order->get_items() as $cart_item ) {
				$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
				if ( ! $product instanceof WC_Product ) {
					continue;
				}

				$product_id = $product->get_id();
				$product_id = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
				$terms      = wp_get_object_terms( $product_id, $this->taxonomy_name, array(
					'fields' => 'ids',
				) );
				$all_terms  = array_merge( $all_terms, $terms );
			}
		}

		return $all_terms;
	}

	public function ui_view() {
		esc_html_e( 'Ordered Products Tags ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %><% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

}

class BWFAN_Rule_Order_Item_Type extends BWFAN_Rule_Base {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_item_type' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );

		$rule = $rule_data['rule'];
		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$saved_type = array_map( function ( $type ) {
			return $type['key'];
		}, $data );


		$all_types = array();

		if ( $order->get_items() && count( $order->get_items() ) ) {
			foreach ( $order->get_items() as $cart_item ) {
				$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
				if ( ! $product instanceof WC_Product ) {
					continue;
				}

				$product_id    = $product->get_id();
				$product_id    = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
				$product_types = wp_get_post_terms( $product_id, 'product_type', array(
					'fields' => 'ids',
				) );
				$all_types     = array_merge( $all_types, $product_types );
			}
		}

		$all_types = array_filter( $all_types );

		if ( empty( $all_types ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$result = false;
		switch ( $rule ) {
			case 'all':
				if ( is_array( $saved_type ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $saved_type, $all_types ) ) === count( $saved_type );
				}
				break;
			case 'any':
				if ( is_array( $saved_type ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $saved_type, $all_types ) ) >= 1;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$type      = $rule_data['operator'];
		$all_types = array();
		$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );

		if ( $order->get_items() && count( $order->get_items() ) ) {
			foreach ( $order->get_items() as $cart_item ) {
				$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
				if ( ! $product instanceof WC_Product ) {
					continue;
				}

				$product_id    = $product->get_id();
				$product_id    = ( $product->get_parent_id() ) ? $product->get_parent_id() : $product_id;
				$product_types = wp_get_post_terms( $product_id, 'product_type', array(
					'fields' => 'ids',
				) );
				$all_types     = array_merge( $all_types, $product_types );
			}
		}

		$all_types = array_filter( $all_types );

		if ( empty( $all_types ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$result = false;
		switch ( $type ) {
			case 'all':
				if ( is_array( $rule_data['condition'] ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $rule_data['condition'], $all_types ) ) === count( $rule_data['condition'] );
				}
				break;
			case 'any':
				if ( is_array( $rule_data['condition'] ) && is_array( $all_types ) ) {
					$result = count( array_intersect( $rule_data['condition'], $all_types ) ) >= 1;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		?>
        Ordered Products Type
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any' => __( 'matches any of', 'wp-marketing-automations' ),
			'all' => __( 'matches all of ', 'wp-marketing-automations' ),
		);
	}

	public function get_ui_preview_data() {
		return $this->get_possible_rule_values();
	}

	public function get_possible_rule_values() {
		$terms = get_terms( 'product_type', array(
			'hide_empty' => false,
		) );

		$result = [];
		if ( $terms && ! is_wp_error( $terms ) ) {
			foreach ( $terms as $term ) {
				if ( 'grouped' === $term->name ) {
					continue;
				}
				$result[ $term->term_id ] = $term->name;
			}
		}

		return $result;
	}

}

class BWFAN_Rule_Order_Item_Count extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_item_count' );
	}

	/** v2 Methods: START */

	public function get_rule_type() {
		return 'Number';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$count = absint( $order->get_item_count() );

		$operator = $rule_data['rule'];
		$value    = absint( $rule_data['data'] );

		switch ( $operator ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data ) {
		/**
		 * @var WC_order $order
		 */
		$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$count = absint( $order->get_item_count() );
		$value = absint( $rule_data['condition'] );

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		?>
        Ordered Products Count
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Product_Item_Custom_Field extends BWFAN_Rule_Custom_Field {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'product_item_custom_field' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return [];
	}

	public function get_rule_type() {
		return 'key-value';
	}

	/** v2 Methods: END */

	public function get_possible_value( $key, $automation_data = [] ) {
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$cart_item = BWFAN_Rules::get_order_item_object( $automation_data );
		} else {
			$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );
		}
		if ( empty( $cart_item ) ) {
			return false;
		}

		if ( ! $cart_item instanceof WC_Order_Item ) {
			return false;
		}

		return get_post_meta( $cart_item->get_product_id(), $key, true );
	}

	public function get_possible_rule_operators() {
		return array(
			'is'     => __( 'is', 'wp-marketing-automations' ),
			'is_not' => __( 'is not', 'wp-marketing-automations' ),
		);
	}

	public function ui_view() {
		?>
        Product Custom Field
        '<%= condition['key'] %>' <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>
        <%= ops[operator] %> '<%= condition['value'] %>'
		<?php
	}
}

class BWFAN_Rule_Product_Item_SKU extends BWFAN_Rule_Base {

	public function __construct() {
		parent::__construct( 'product_item_sku' );
	}

	public function is_match( $rule_data ) {
		$order     = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$cart_item = BWFAN_Core()->rules->getRulesData( 'wc_items' );

		if ( empty( $cart_item ) ) {
			return false;
		}

		if ( ! $cart_item instanceof WC_Order_Item ) {
			return false;
		}

		$product = BWFAN_WooCommerce_Compatibility::get_product_from_item( $order, $cart_item );
		if ( ! $product instanceof WC_Product ) {
			return false;
		}

		$product_sku = $product->get_sku();

		return $this->return_is_match( BWFAN_Common::validate_string( $product_sku, $rule_data['operator'], $rule_data['condition'] ), $rule_data );
	}


	public function get_condition_input_type() {
		return 'Text';
	}

	public function get_possible_rule_operators() {
		return array(
			'is'           => __( 'is', 'wp-marketing-automations' ),
			'is_not'       => __( 'is not', 'wp-marketing-automations' ),
			'contains'     => __( 'contains', 'wp-marketing-automations' ),
			'not_contains' => __( 'not contains', 'wp-marketing-automations' ),
			'starts_with'  => __( 'starts with', 'wp-marketing-automations' ),
			'ends_with'    => __( 'ends with', 'wp-marketing-automations' ),
		);
	}

	public function ui_view() {
		?>
        Product SKU
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        '<%= condition %>'
		<?php
	}
}

class BWFAN_Rule_Order_Coupons extends BWFAN_Dynamic_Option_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_coupons' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_search_results( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type  = $rule_data['rule'];
		$order = BWFAN_Rules::get_order_object( $automation_data );

		$used_coupons = $order->get_coupon_codes();
		if ( empty( $used_coupons ) ) {
			if ( 'all' === $type || 'any' === $type ) {
				$res = false;
			} else {
				$res = true;
			}

			return $this->return_is_match( $res, $rule_data );
		}

		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		global $wpdb;
		$saved_coupons = array_map( function ( $term ) {
			return $term['key'];
		}, $data );

		$used_coupons_ids = [];
		foreach ( $used_coupons as $coupon ) {
			$used_coupons_ids[] = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' LIMIT 1;", $coupon ) );
		}

		$result = false;
		switch ( $type ) {
			case 'all':
				if ( is_array( $saved_coupons ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $saved_coupons, $used_coupons_ids ) ) === count( $saved_coupons );
				}
				break;
			case 'any':
				if ( is_array( $saved_coupons ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $saved_coupons, $used_coupons_ids ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $saved_coupons ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $saved_coupons, $used_coupons_ids ) ) === 0;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_values_nice_names( $values ) {
		$return = [];
		if ( count( $values ) > 0 ) {
			foreach ( $values as $coupon_id ) {
				$return[ $coupon_id ] = get_the_title( $coupon_id );
			}
		}

		return $return;
	}

	public function get_search_type_name() {
		return 'coupon_rule';
	}

	public function get_search_results( $term, $v2 = false ) {
		$array = array();
		if ( isset( $term ) && '' !== $term ) {
			$args = array(
				'post_type'     => 'shop_coupon',
				'post_per_page' => 2,
				'paged'         => 1,
				's'             => $term,
			);

			$posts = get_posts( $args );
			if ( $v2 ) {
				if ( count( $posts ) > 0 ) {
					foreach ( $posts as $post ) {
						$array[ $post->ID ] = $post->post_title;
					}
				}

				return $array;
			}

			if ( $posts && is_array( $posts ) && count( $posts ) > 0 ) {
				foreach ( $posts as $post ) :
					setup_postdata( $post );
					$array[] = array(
						'id'   => (string) $post->ID,
						'text' => $post->post_title,
					);
				endforeach;
			}
		}

		wp_send_json( array(
			'results' => $array,
		) );
	}

	public function is_match( $rule_data ) {
		global $wpdb;
		$type  = $rule_data['operator'];
		$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );

		$used_coupons = $order->get_coupon_codes();
		if ( empty( $used_coupons ) ) {
			if ( 'all' === $type || 'any' === $type ) {
				$res = false;
			} else {
				$res = true;
			}

			return $this->return_is_match( $res, $rule_data );
		}

		$used_coupons_ids = [];
		foreach ( $used_coupons as $coupon ) {
			$used_coupons_ids[] = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM {$wpdb->posts} WHERE post_title = %s AND post_type = 'shop_coupon' AND post_status = 'publish' LIMIT 1;", $coupon ) );
		}

		$result = false;
		switch ( $type ) {
			case 'all':
				if ( is_array( $rule_data['condition'] ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $rule_data['condition'], $used_coupons_ids ) ) === count( $rule_data['condition'] );
				}
				break;
			case 'any':
				if ( is_array( $rule_data['condition'] ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $rule_data['condition'], $used_coupons_ids ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $rule_data['condition'] ) && is_array( $used_coupons_ids ) ) {
					$result = count( array_intersect( $rule_data['condition'], $used_coupons_ids ) ) === 0;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Order Coupon Code ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %> <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'all'  => __( 'matches all of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}


}

class BWFAN_Rule_Order_Payment_Gateway extends BWFAN_Rule_Base {
	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_payment_gateway' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type  = $rule_data['rule'];
		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$payment = BWFAN_WooCommerce_Compatibility::get_payment_gateway_from_order( $order );
		if ( empty( $payment ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$saved_payment = array_map( function ( $term ) {
			return $term['key'];
		}, $data );

		switch ( $type ) {
			case 'is':
				$result = in_array( $payment, $saved_payment, true );
				break;
			case 'is_not':
				$result = ! in_array( $payment, $saved_payment, true );
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_possible_rule_values() {
		$result = array();
		foreach ( WC()->payment_gateways()->payment_gateways() as $gateway ) {
			if ( 'yes' === $gateway->enabled ) {
				$result[ $gateway->id ] = $gateway->get_title();
			}
		}

		return $result;
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$type    = $rule_data['operator'];
		$order   = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$payment = BWFAN_WooCommerce_Compatibility::get_payment_gateway_from_order( $order );

		if ( empty( $payment ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		switch ( $type ) {
			case 'is':
				$result = in_array( $payment, $rule_data['condition'], true );
				break;
			case 'is_not':
				$result = ! in_array( $payment, $rule_data['condition'], true );
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Order Payment Gateway ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'is'     => __( 'is', 'wp-marketing-automations' ),
			'is_not' => __( 'is not', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Order_Shipping_Country extends BWFAN_Rule_Country {

	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_shipping_country' );
	}

	public function get_objects_country( $automation_data = [] ) {
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		$shipping_country = BWFAN_WooCommerce_Compatibility::get_shipping_country_from_order( $order );

		return empty( $shipping_country ) ? false : array( $shipping_country );
	}

	public function ui_view() {
		esc_html_e( 'Order Shipping Country', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}
}

class BWFAN_Rule_Order_Shipping_Method extends BWFAN_Rule_Base {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_shipping_method' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		if ( ! isset( $automation_data['global']['order_id'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type = $rule_data['rule'];
		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$saved_methods = array_map( function ( $term ) {
			return $term['key'];
		}, $data );

		$methods = array();
		foreach ( $order->get_shipping_methods() as $method ) {
			// extract method slug only, discard instance id
			$split = strpos( $method['method_id'], ':' );
			if ( $split ) {
				$methods[] = substr( $method['method_id'], 0, $split );
			} else {
				$methods[] = $method['method_id'];
			}
		}

		$result = false;
		switch ( $type ) {
			case 'any':
				if ( is_array( $saved_methods ) && is_array( $methods ) ) {
					$result = count( array_intersect( $saved_methods, $methods ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $saved_methods ) && is_array( $methods ) ) {
					$result = count( array_intersect( $saved_methods, $methods ) ) === 0;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_possible_rule_values() {
		$result = array();

		foreach ( WC()->shipping()->get_shipping_methods() as $method_id => $method ) {
			// get_method_title() added in WC 2.6
			$result[ $method_id ] = is_callable( array( $method, 'get_method_title' ) ) ? $method->get_method_title() : $method->get_title();
		}

		return $result;
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$type    = $rule_data['operator'];
		$order   = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$methods = array();

		foreach ( $order->get_shipping_methods() as $method ) {
			// extract method slug only, discard instance id
			$split = strpos( $method['method_id'], ':' );
			if ( $split ) {
				$methods[] = substr( $method['method_id'], 0, $split );
			} else {
				$methods[] = $method['method_id'];
			}
		}

		$result = false;
		switch ( $type ) {
			case 'any':
				if ( is_array( $rule_data['condition'] ) && is_array( $methods ) ) {
					$result = count( array_intersect( $rule_data['condition'], $methods ) ) >= 1;
				}
				break;
			case 'none':
				if ( is_array( $rule_data['condition'] ) && is_array( $methods ) ) {
					$result = count( array_intersect( $rule_data['condition'], $methods ) ) === 0;
				}
				break;
			default:
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Order Shipping Method', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'any'  => __( 'matches any of', 'wp-marketing-automations' ),
			'none' => __( 'matches none of', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Order_Billing_Country extends BWFAN_Rule_Country {
	public $supports = array( 'cart', 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_billing_country' );
	}

	public function get_objects_country( $automation_data = [] ) {
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		$billing_country = BWFAN_WooCommerce_Compatibility::get_billing_country_from_order( $order );
		if ( empty( $billing_country ) ) {
			return false;
		}

		$billing_country = array( $billing_country );

		return $billing_country;
	}

	public function ui_view() {
		esc_html_e( 'Order Billing Country', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>
        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>

        <% }); %>
        <%= chosen.join("/ ") %>
		<?php
	}
}

class BWFAN_Rule_Order_Custom_Field extends BWFAN_Rule_Custom_Field {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_custom_field' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return [];
	}

	public function get_rule_type() {
		return 'key-value';
	}

	/** v2 Methods: END */

	public function get_possible_value( $key, $automation_data = [] ) {
		if ( ! empty( $automation_data ) && isset( $automation_data['global'] ) && is_array( $automation_data['global'] ) ) {
			$order = BWFAN_Rules::get_order_object( $automation_data );
		} else {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		}

		if ( ! $order instanceof WC_Order ) {
			return '';
		}

		return $order->get_meta( $key );
	}

	public function get_possible_rule_operators() {
		$operators                 = $this->operator_matches();
		$operators['is_blank']     = __( 'is blank', 'wp-marketing-automations' );
		$operators['is_not_blank'] = __( 'is not blank', 'wp-marketing-automations' );

		return $operators;
	}
}

class BWFAN_Rule_Order_Items_Data extends BWFAN_Rule_Base {
	public function __construct() {
		$this->v2 = true;
		$this->v1 = false;
		parent::__construct( 'order_items_data' );
	}

	public function get_possible_rule_operators() {
		return array(
			'is'           => __( 'is', 'autonami-automations-pro' ),
			'is_not'       => __( 'is not', 'autonami-automations-pro' ),
			'contains'     => __( 'contains', 'wp-marketing-automations' ),
			'not_contains' => __( 'does not contain', 'wp-marketing-automations' ),
			'starts_with'  => __( 'starts with', 'wp-marketing-automations' ),
			'ends_with'    => __( 'ends with', 'wp-marketing-automations' ),
		);
	}

	/** v2 Methods: START */
	public function get_rule_type() {
		return 'key-value';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order_id = isset( $automation_data['global']['order_id'] ) ? $automation_data['global']['order_id'] : 0;
		if ( empty( $order_id ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		// Get the order object
		$order = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		// Extract meta key and value from rule data
		$meta_key   = isset( $rule_data['data'][0] ) ? $rule_data['data'][0] : '';
		$meta_value = isset( $rule_data['data'][1] ) ? $rule_data['data'][1] : '';

		$value = array(); // Initialize an array to store order item meta values

		// Loop through order items
		foreach ( $order->get_items() as $item_id => $item ) {
			$item_meta_value = wc_get_order_item_meta( $item_id, $meta_key );
			if ( $item_meta_value !== '' ) {
				$value[] = $item_meta_value;
			}
		}

		// Convert all values to lowercase for case-insensitive comparison
		$value           = array_map( 'strtolower', $value );
		$condition_value = strtolower( trim( $meta_value ) );

		$type   = $rule_data['rule'];
		$result = false; // Initialize the result

		switch ( $type ) {
			case 'is':
				$result = in_array( $condition_value, $value );
				break;
			case 'is_not':
				$result = ! in_array( $condition_value, $value );
				break;
			case 'contains':
				foreach ( $value as $single_value ) {
					if ( strpos( $single_value, $condition_value ) !== false ) {
						$result = true;
						break;
					}
				}
				break;
			case 'not_contains':
				foreach ( $value as $single_value ) {
					if ( strpos( $single_value, $condition_value ) === false ) {
						$result = true;
						break;
					}
				}
				break;
			case 'starts_with':
				foreach ( $value as $single_value ) {
					$length = strlen( $condition_value );
					if ( substr( $single_value, 0, $length ) === $condition_value ) {
						$result = true;
						break;
					}
				}
				break;
			case 'ends_with':
				foreach ( $value as $single_value ) {
					$length = strlen( $condition_value );
					if ( $length === 0 || substr( $single_value, - $length ) === $condition_value ) {
						$result = true;
						break;
					}
				}
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}
}


class BWFAN_Rule_Order_Coupon_Text_Match extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_coupon_text_match' );
	}

	public function conditions_view() {
		$condition_input_type = $this->get_condition_input_type();
		$values               = $this->get_possible_rule_values();
		$value_args           = array(
			'input'       => $condition_input_type,
			'name'        => 'bwfan_rule[<%= groupId %>][<%= ruleId %>][condition]',
			'choices'     => $values,
			'placeholder' => __( 'Enter Few Characters...', 'wp-marketing-automations' ),
		);

		bwfan_Input_Builder::create_input_field( $value_args );
	}

	public function get_condition_input_type() {
		return 'Text';
	}

	public function get_possible_rule_values() {
		return null;
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type  = $rule_data['rule'];
		$order = BWFAN_Rules::get_order_object( $automation_data );

		$saved_text   = $rule_data['data'];
		$used_coupons = $order->get_coupon_codes();

		return $this->return_is_match( BWFAN_Common::validate_string_multi( $used_coupons, $type, $saved_text ), $rule_data );
	}

	public function is_match( $rule_data ) {
		$order        = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$used_coupons = $order->get_coupon_codes();

		return $this->return_is_match( BWFAN_Common::validate_string_multi( $used_coupons, $rule_data['operator'], $rule_data['condition'] ), $rule_data );
	}

	public function ui_view() {
		?>
        Order Coupon Code
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        '<%= condition %>'
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'contains'    => __( 'contains', 'wp-marketing-automations' ),
			'is'          => __( 'matches exactly', 'wp-marketing-automations' ),
			'starts_with' => __( 'starts with', 'wp-marketing-automations' ),
			'ends_with'   => __( 'ends with', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Order_Note_Text_Match extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_note_text_match' );
	}

	public function conditions_view() {
		$condition_input_type = $this->get_condition_input_type();
		$values               = $this->get_possible_rule_values();
		$value_args           = array(
			'input'       => $condition_input_type,
			'name'        => 'bwfan_rule[<%= groupId %>][<%= ruleId %>][condition]',
			'choices'     => $values,
			'placeholder' => __( 'Enter Few Characters...', 'wp-marketing-automations' ),
		);

		bwfan_Input_Builder::create_input_field( $value_args );
	}

	public function get_condition_input_type() {
		return 'Text';
	}

	public function get_possible_rule_values() {
		return null;
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type       = $rule_data['rule'];
		$order_note = isset( $automation_data['global']['current_order_note'] ) ? trim( $automation_data['global']['current_order_note'] ) : '';

		return $this->return_is_match( BWFAN_Common::validate_string( $order_note, $type, trim( $rule_data['data'] ) ), $rule_data );
	}

	public function is_match( $rule_data ) {
		$order_note = BWFAN_Core()->rules->getRulesData( 'wc_order_note' );

		return $this->return_is_match( BWFAN_Common::validate_string( trim( $order_note ), $rule_data['operator'], trim( $rule_data['condition'] ) ), $rule_data );
	}

	public function ui_view() {
		?>
        Order Note Text
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>
        <%= ops[operator] %>
        '<%= condition %>'
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'contains'    => __( 'contains', 'wp-marketing-automations' ),
			'is'          => __( 'matches exactly', 'wp-marketing-automations' ),
			'starts_with' => __( 'starts with', 'wp-marketing-automations' ),
			'ends_with'   => __( 'ends with', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Order_Status_Change extends BWFAN_Rule_Base {
	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'order_status_change' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_possible_rule_values() {
		return wc_get_order_statuses();
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type        = $rule_data['rule'];
		$from_status = isset( $automation_data['global']['from'] ) ? $automation_data['global']['from'] : '';
		if ( empty( $from_status ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$data = array_map( function ( $status ) {
			return $status['label'];
		}, $rule_data['data'] );

		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		switch ( $type ) {
			case 'is':
				$result = in_array( $from_status, $data, true );
				break;
			case 'is_not':
				$result = ! in_array( $from_status, $data, true );
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function is_match( $rule_data ) {
		$type         = $rule_data['operator'];
		$order        = BWFAN_Core()->rules->getRulesData( 'wc_order' );
		$order_status = 'wc-' . $order->get_status();

		switch ( $type ) {
			case 'is':
				$result = in_array( $order_status, $rule_data['condition'], true );
				break;
			case 'is_not':
				$result = ! in_array( $order_status, $rule_data['condition'], true );
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Older Order Status ', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <% var chosen = []; %>

        <% _.each(condition, function( value, key ){ %>
        <% chosen.push(uiData[value]); %>
        <% }); %>

        <%= chosen.join("/ ") %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'is'     => __( 'is', 'wp-marketing-automations' ),
			'is_not' => __( 'is not', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Comment_Count extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'comment_count' );
	}

	/** v2 Methods: START */

	public function get_rule_type() {
		return 'Number';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$comment_id = isset( $automation_data['global']['comment_id'] ) ? $automation_data['global']['comment_id'] : 0;
		$rating     = $comment_id > 0 ? get_comment_meta( $comment_id, 'rating', true ) : 0;
		$count      = absint( $rating );

		$operator = $rule_data['rule'];
		$value    = absint( $rule_data['data'] );

		switch ( $operator ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_condition_input_type() {
		return 'Text';
	}

	public function is_match( $rule_data ) {
		$comment_details      = BWFAN_Core()->rules->getRulesData( 'wc_comment' );
		$comment_rating_count = $comment_details['rating_number'];
		$count                = absint( $comment_rating_count );
		$value                = absint( $rule_data['condition'] );

		switch ( $rule_data['operator'] ) {
			case '==':
				$result = $count === $value;
				break;
			case '!=':
				$result = $count !== $value;
				break;
			case '>':
				$result = $count > $value;
				break;
			case '<':
				$result = $count < $value;
				break;
			case '>=':
				$result = $count >= $value;
				break;
			case '<=':
				$result = $count <= $value;
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'Review Rating count', 'wp-marketing-automations' );
		?>
        <% var ops = JSON.parse('<?php echo wp_json_encode( $this->get_possible_rule_operators() ); ?>'); %>

        <%= ops[operator] %>
        <%= condition %>
		<?php
	}

	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
			'>'  => __( 'is greater than', 'wp-marketing-automations' ),
			'<'  => __( 'is less than', 'wp-marketing-automations' ),
			'>=' => __( 'is greater or equal to', 'wp-marketing-automations' ),
			'<=' => __( 'is less or equal to', 'wp-marketing-automations' ),
		);
	}

}

class BWFAN_Rule_Order_Status extends BWFAN_Rule_Base {
	public $supports = array( 'order' );

	public function __construct() {
		$this->v1 = false;
		$this->v2 = true;
		parent::__construct( 'order_status' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Search';
	}

	/** v2 Methods: END */

	public function get_possible_rule_values() {
		return wc_get_order_statuses();
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$type  = $rule_data['rule'];
		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order_status = 'wc-' . $order->get_status();

		$data = array_map( function ( $status ) {
			return $status['key'];
		}, $rule_data['data'] );

		if ( ! is_array( $data ) && empty( $data ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		switch ( $type ) {
			case 'is':
				$result = in_array( $order_status, $data, true );
				break;
			case 'is_not':
				$result = ! in_array( $order_status, $data, true );
				break;
			default:
				$result = false;
				break;
		}

		return $this->return_is_match( $result, $rule_data );
	}

	public function get_possible_rule_operators() {
		return array(
			'is'     => __( 'is', 'wp-marketing-automations' ),
			'is_not' => __( 'is not', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Order_Has_Coupon extends BWFAN_Rule_Base {
	public function __construct() {
		$this->v1 = false;
		$this->v2 = true;
		parent::__construct( 'order_has_coupon' );
	}

	public function get_possible_rule_values() {
		return array(
			'yes' => __( 'Yes', 'wp-marketing-automations' ),
			'no'  => __( 'No', 'wp-marketing-automations' ),
		);
	}

	/** v2 Methods: START */
	public function get_options( $term = '' ) {
		return $this->get_possible_rule_values();
	}

	public function get_rule_type() {
		return 'Select';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$order = BWFAN_Rules::get_order_object( $automation_data );
		if ( ! $order instanceof WC_Order ) {
			return $this->return_is_match( false, $rule_data );
		}

		$result = ( count( $order->get_coupon_codes() ) > 0 ) ? true : false;

		return $this->return_is_match( ( 'yes' === $rule_data['data'] ) ? $result : ! $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_possible_rule_operators() {
		return null;
	}
}
