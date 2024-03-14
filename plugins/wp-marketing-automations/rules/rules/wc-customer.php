<?php

class BWFAN_Rule_Is_First_Order extends BWFAN_Rule_Base {

	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'is_first_order' );
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
			return false;
		}

		$email = isset( $automation_data['global']['email'] ) ? $automation_data['global']['email'] : 0;

		$is_first = false;
		if ( empty( $email ) || ! is_email( $email ) ) {
			$order_id = isset( $automation_data['global']['order_id'] ) ? $automation_data['global']['order_id'] : 0;
			$order    = wc_get_order( $order_id );
			if ( ! $order instanceof WC_Order ) {
				return false;
			}

			$email = BWFAN_WooCommerce_Compatibility::get_order_data( $order, '_billing_email' );
		}

		$orders = wc_get_orders( array(
			'customer' => $email,
			'limit'    => 2,
			'return'   => 'ids',
		) );

		if ( count( $orders ) === 1 ) {
			$is_first = true;
		}
		$operator = $rule_data['data'];

		return ( 'yes' === $operator ) ? $is_first : ! $is_first;
	}

	/** v2 Methods: END */

	public function get_possible_rule_operators() {
		return null;
	}

	public function get_possible_rule_values() {
		return array(
			'yes' => __( 'Yes', 'wp-marketing-automations' ),
			'no'  => __( 'No', 'wp-marketing-automations' ),
		);
	}

	public function get_condition_input_type() {
		return 'Select';
	}

	public function is_match( $rule_data ) {
		$is_first = false;

		$data = BWFAN_Core()->rules->getRulesData();
		if ( empty( $data ) ) {
			return $is_first;
		}

		$billing_email = ( isset( $data['email'] ) ) ? $data['email'] : '';
		if ( empty( $billing_email ) && isset( $data['wc_order'] ) ) {
			$order = $data['wc_order'];
			/** check for order instance */
			if ( $order instanceof WC_Order ) {
				$billing_email = BWFAN_WooCommerce_Compatibility::get_order_data( $order, '_billing_email' );
			}
		}

		if ( empty( $billing_email ) ) {
			return $is_first;
		}

		$orders = wc_get_orders( array(
			'customer' => $billing_email,
			'limit'    => 2,
			'return'   => 'ids',
		) );

		if ( count( $orders ) === 1 ) {
			$is_first = true;
		}

		return ( 'yes' === $rule_data['condition'] ) ? $is_first : ! $is_first;
	}

	public function ui_view() {
		esc_html_e( 'Order', 'wp-marketing-automations' );
		?>
        <% if (condition == "yes") { %> is <% } %>
        <% if (condition == "no") { %> is not <% } %>
		<?php
		esc_html_e( 'a First Order', 'wp-marketing-automations' );
	}
}

class BWFAN_Rule_Is_Guest extends BWFAN_Rule_Base {
	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'is_guest' );
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
			return false;
		}

		$order_id = isset( $automation_data['global']['order_id'] ) ? $automation_data['global']['order_id'] : 0;
		$order    = wc_get_order( $order_id );
		if ( ! $order instanceof WC_Order ) {
			return false;
		}

		if ( ! empty( $order ) ) {
			$result = ( $order->get_user_id() === 0 );

			return ( 'yes' === $rule_data['data'] ) ? $result : ! $result;
		}


		$email = isset( $automation_data['global']['email'] ) ? $automation_data['global']['email'] : '';
		if ( ! empty( $email ) ) {
			$result = true;
			$user   = get_user_by( 'user_email', $email );
			if ( $user instanceof WP_User ) {
				$result = false;
			}

			return ( 'yes' === $rule_data['data'] ) ? $result : ! $result;
		}

		/** Checking user logged in value only if order or email value not passed */
		return ! is_user_logged_in();
	}

	/** v2 Methods: END */

	public function get_possible_rule_operators() {
		return null;
	}

	public function get_possible_rule_values() {
		return array(
			'yes' => __( 'Yes', 'wp-marketing-automations' ),
			'no'  => __( 'No', 'wp-marketing-automations' ),
		);
	}

	public function is_match( $rule_data ) {
		$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );

		if ( ! empty( $order ) ) {
			$result = ( $order->get_user_id() === 0 );

			return ( 'yes' === $rule_data['condition'] ) ? $result : ! $result;
		}

		$email = BWFAN_Core()->rules->get_environment_var( 'email' );
		if ( ! empty( $email ) ) {
			$result = true;
			$user   = get_user_by( 'user_email', $email );
			if ( $user instanceof WP_User ) {
				$result = false;
			}

			return ( 'yes' === $rule_data['condition'] ) ? $result : ! $result;
		}

		/** Checking user logged in value only if order or email value not passed */
		return ! is_user_logged_in();
	}

	public function ui_view() {
		esc_html_e( 'Order', 'wp-marketing-automations' );
		?>
        <% if (condition == "yes") { %> is <% } %>
        <% if (condition == "no") { %> is not <% } %>
		<?php
		esc_html_e( 'a Guest Order', 'wp-marketing-automations' );
	}
}

class BWFAN_Rule_Customer_User extends BWFAN_Dynamic_Option_Base {
	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'customer_user' );
	}

	/** v2 Methods: START */

	public function get_options( $term = '' ) {
		return $this->get_possible_values( $term, true );
	}

	public function get_rule_type() {
		return 'Search';
	}

	public function is_match_v2( $automation_data, $rule_data ) {

		if ( ! isset( $automation_data['global'] ) || ! is_array( $automation_data['global'] ) ) {
			return false;
		}

		$id = isset( $automation_data['global']['user_id'] ) ? $automation_data['global']['user_id'] : 0;
		if ( empty( $id ) ) {
			$order_id = isset( $automation_data['global']['order_id'] ) ? $automation_data['global']['order_id'] : 0;
			$order    = wc_get_order( $order_id );
			$id       = $order->get_user_id();
		}

		$data = $rule_data['data'];

		$users = array_map( function ( $user ) {
			return absint( $user['key'] );
		}, $data );

		$result = in_array( $id, $users, true );
		$result = ( 'in' === $rule_data['rule'] ) ? $result : ! $result;

		return $this->return_is_match( $result, $rule_data );
	}

	public function get_search_type_name() {
		return 'wp_users';
	}

	public function get_condition_values_nice_names( $values ) {
		$return = [];
		if ( count( $values ) > 0 ) {
			foreach ( $values as $user ) {
				$userdata        = get_userdata( $user );
				$return[ $user ] = $userdata->display_name;
			}
		}

		return $return;
	}

	public function get_possible_values( $term, $v2 = false ) {
		$array       = array();
		$users       = new WP_User_Query( array(
			'search'         => '*' . esc_attr( $term ) . '*',
			'search_columns' => array(
				'user_login',
				'user_nicename',
				'user_email',
				'user_url',
			),
		) );
		$users_found = $users->get_results();
		$return      = array();
		foreach ( $users_found as $user ) {
			array_push( $array, array(
				'id'   => $user->ID,
				'text' => $user->data->display_name,
			) );
			if ( $v2 ) {
				$return[ $user->ID ] = $user->data->display_name;
			}
		}

		if ( $v2 ) {
			return $return;
		}

		wp_send_json( array(
			'results' => $array,
		) );
	}

	public function is_match( $rule_data ) {
		$id = BWFAN_Core()->rules->getRulesData( 'user_id' );
		if ( empty( $id ) ) {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$id    = $order->get_user_id();
		}

		$rule_data['condition'] = array_map( 'intval', $rule_data['condition'] );
		$result                 = in_array( $id, $rule_data['condition'], true );
		$result                 = ( 'in' === $rule_data['operator'] ) ? $result : ! $result;

		return $this->return_is_match( $result, $rule_data );
	}

	public function ui_view() {
		esc_html_e( 'User', 'wp-marketing-automations' );
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
			'in'    => __( 'is', 'wp-marketing-automations' ),
			'notin' => __( 'is not', 'wp-marketing-automations' ),
		);
	}
}

class BWFAN_Rule_Customer_Role extends BWFAN_Rule_Base {

	public $supports = array( 'order' );

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'customer_role' );
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
			return false;
		}

		$id             = isset( $automation_data['global']['user_id'] ) ? $automation_data['global']['user_id'] : 0;
		$abandoned_data = isset( $automation_data['global']['abandoned_data'] ) ? $automation_data['global']['abandoned_data'] : [];
		$contact_id     = isset( $automation_data['global']['contact_id'] ) ? $automation_data['global']['contact_id'] : 0;

		/** If contact_id available */
		if ( empty( $id ) && ! empty( $contact_id ) ) {
			$contact = new WooFunnels_Contact( '', '', '', $contact_id );
			$id      = $contact->get_wpid();
		}

		/** If WooCommerce active and order object available */
		if ( empty( $id ) && class_exists( 'WooCommerce' ) ) {
			$order_id = isset( $automation_data['global']['order_id'] ) ? $automation_data['global']['order_id'] : 0;
			$order    = wc_get_order( $order_id );
			$id       = $order instanceof WC_Order ? $order->get_user_id() : $id;
		}

		/** If email available */
		if ( empty( $id ) ) {
			$email = isset( $automation_data['global']['email'] ) ? $automation_data['global']['email'] : '';
			$email = is_email( $email ) ? $email : ( isset( $abandoned_data['email'] ) ? $abandoned_data['email'] : false );

			if ( ! is_email( $email ) ) {
				return false;
			}

			$contact_db  = WooFunnels_DB_Operations::get_instance();
			$contact_obj = $contact_db->get_contact_by_email( $email );

			if ( isset( $contact_obj->wpid ) && absint( $contact_obj->wpid ) > 0 ) {
				$id = absint( $contact_obj->wpid );
			}

			/** Get WP user by email */
			if ( empty( $id ) ) {
				$user_data = get_user_by( 'email', $email );
				$id        = $user_data instanceof WP_User ? $user_data->ID : false;
			}
		}

		/** If no user, return false */
		if ( empty( $id ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$data = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return false;
		}

		$saved_roles = array_map( function ( $role ) {
			return $role['key'];
		}, $data );


		$result = false;
		$user   = get_user_by( 'id', $id );
		$role   = array_intersect( (array) $user->roles, $saved_roles );
		$result = ! empty( $role ) ? true : false;

		return $this->return_is_match( ( 'in' === $rule_data['rule'] ) ? $result : ! $result, $rule_data );
	}

	/** v2 Methods: END */

	public function get_possible_rule_values() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$result         = array();
		$editable_roles = get_editable_roles();

		if ( $editable_roles ) {
			foreach ( $editable_roles as $role => $details ) {
				$name = translate_user_role( $details['name'] );

				$result[ $role ] = $name;
			}
		}

		return $result;
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$id             = BWFAN_Core()->rules->getRulesData( 'user_id' );
		$abandoned_data = BWFAN_Core()->rules->getRulesData( 'abandoned_data' );
		$contact_id     = BWFAN_Core()->rules->getRulesData( 'contact_id' );

		/** If contact_id available */
		if ( empty( $id ) && ! empty( $contact_id ) ) {
			$contact = new WooFunnels_Contact( '', '', '', $contact_id );
			$id      = $contact->get_wpid();
		}

		/** If WooCommerce active and order object available */
		if ( empty( $id ) && class_exists( 'WooCommerce' ) ) {
			$order = BWFAN_Core()->rules->getRulesData( 'wc_order' );
			$id    = $order instanceof WC_Order ? $order->get_user_id() : $id;
		}

		/** If email available */
		if ( empty( $id ) ) {
			$email = BWFAN_Core()->rules->getRulesData( 'email' );
			$email = is_email( $email ) ? $email : ( isset( $abandoned_data['email'] ) ? $abandoned_data['email'] : false );

			if ( ! is_email( $email ) ) {
				return false;
			}

			$contact_db  = WooFunnels_DB_Operations::get_instance();
			$contact_obj = $contact_db->get_contact_by_email( $email );

			if ( isset( $contact_obj->wpid ) && absint( $contact_obj->wpid ) > 0 ) {
				$id = absint( $contact_obj->wpid );
			}

			/** Get WP user by email */
			if ( empty( $id ) ) {
				$user_data = get_user_by( 'email', $email );
				$id        = $user_data instanceof WP_User ? $user_data->ID : false;
			}
		}

		/** If no user, return false */
		if ( empty( $id ) ) {
			return $this->return_is_match( false, $rule_data );
		}

		$result = false;

		if ( $rule_data['condition'] && is_array( $rule_data['condition'] ) ) {
			$user = get_user_by( 'id', $id );

			foreach ( $rule_data['condition'] as $role ) {
				if ( in_array( $role, $user->roles ) ) {
					$result = true;
					break;
				}
			}
		}

		if ( 'in' === $rule_data['operator'] ) {
			return $this->return_is_match( $result, $rule_data );
		} else {
			return $this->return_is_match( ! $result, $rule_data );
		}

	}

	public function ui_view() {
		esc_html_e( 'User Role', 'wp-marketing-automations' );
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
			'in'    => __( 'is', 'wp-marketing-automations' ),
			'notin' => __( 'is not', 'wp-marketing-automations' ),
		);
	}
}
