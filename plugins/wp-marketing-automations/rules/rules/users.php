<?php

class BWFAN_Rule_Users_Role extends BWFAN_Rule_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'users_role' );
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

		$user_id = isset( $automation_data['global']['user_id'] ) ? $automation_data['global']['user_id'] : 0;
		$email   = isset( $automation_data['global']['email'] ) ? trim( $automation_data['global']['email'] ) : 0;;

		if ( 0 === absint( $user_id ) || ! is_email( $email ) ) {
			return false;
		}

		$user = ! empty( $user_id ) ? get_user_by( 'id', $user_id ) : ( is_email( $email ) ? get_user_by( 'email', $email ) : '' );

		if ( ! $user instanceof WP_User ) {
			return false;
		}

		$operator = $rule_data['rule'];
		$data     = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return false;
		}

		$saved_roles = array_map( function ( $role ) {
			return isset( $role['key'] ) ? $role['key'] : '';
		}, $data );

		$result = false;
		$role   = array_intersect( (array) $user->roles, $saved_roles );

		if ( ! empty( $role ) ) {
			$result = true;
		}

		return ( 'in' === $operator ) ? $result : ! $result;
	}

	/** v2 Methods: END */

	public function get_possible_rule_operators() {
		$operators = array(
			'in'    => __( 'is', 'wp-marketing-automations' ),
			'notin' => __( 'is not', 'wp-marketing-automations' ),
		);

		return $operators;
	}

	public function get_possible_rule_values() {
		if ( ! function_exists( 'get_editable_roles' ) ) {
			require_once ABSPATH . 'wp-admin/includes/user.php';
		}
		$result         = array();
		$editable_roles = get_editable_roles();

		if ( $editable_roles ) {
			foreach ( $editable_roles as $role => $details ) {
				$name            = translate_user_role( $details['name'] );
				$result[ $role ] = $name;
			}
		}

		return $result;
	}

	public function get_condition_input_type() {
		return 'Chosen_Select';
	}

	public function is_match( $rule_data ) {
		$user_id = BWFAN_Core()->rules->getRulesData( 'user_id' );
		$email   = BWFAN_Core()->rules->getRulesData( 'email' );

		$user = ! empty( $user_id ) ? get_user_by( 'id', $user_id ) : ( is_email( $email ) ? get_user_by( 'email', $email ) : '' );
		$user = ! $user instanceof WP_User ? BWFAN_Core()->rules->getRulesData( 'wp_user' ) : $user;

		if ( ! $user instanceof WP_User ) {
			return $this->return_is_match( false, $rule_data );
		}

		$result = false;
		$role   = [];
		if ( $rule_data['condition'] && is_array( $rule_data['condition'] ) ) {
			$role = array_intersect( (array) $user->roles, $rule_data['condition'] );
		}
		if ( ! empty( $role ) ) {
			$result = true;
		}

		$result = ( 'in' === $rule_data['operator'] ) ? $result : ! $result;

		return $this->return_is_match( $result, $rule_data );
	}

	public function sort_attribute_taxonomies( $taxa, $taxb ) {
		return strcmp( $taxa->attribute_name, $taxb->attribute_name );
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

}

class BWFAN_Rule_Users_User extends BWFAN_Dynamic_Option_Base {

	public function __construct() {
		$this->v2 = true;
		parent::__construct( 'users_user' );
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

		$user_id = isset( $automation_data['global']['user_id'] ) ? $automation_data['global']['user_id'] : 0;

		if ( empty( $user_id ) ) {
			$email = isset( $automation_data['global']['email'] ) ? $automation_data['global']['email'] : 0;
			$user  = get_user_by( 'email', $email );

			if ( ! $user instanceof WP_User ) {
				return false;
			}

			$user_id = $user->ID;
		}

		$operator = $rule_data['rule'];
		$data     = $rule_data['data'];
		if ( ! is_array( $data ) && empty( $data ) ) {
			return false;
		}

		$saved_users = array_map( function ( $user ) {
			return isset( $user['key'] ) ? absint( $user['key'] ) : 0;
		}, $data );

		$result = false;
		$result = in_array( $user_id, $saved_users, true );

		return ( 'in' === $operator ) ? $result : ! $result;
	}

	public function get_default_rule_value() {
		return 'yes';
	}

	/** v2 Methods: END */

	public function get_search_type_name() {
		return 'wp_users';
	}

	public function get_condition_values_nice_names( $values ) {
		$return = [];
		if ( is_array( $values ) && count( $values ) > 0 ) {
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

	public function get_possible_rule_operators() {
		$operators = array(
			'in'    => __( 'is', 'wp-marketing-automations' ),
			'notin' => __( 'is not', 'wp-marketing-automations' ),
		);

		return $operators;
	}

	public function is_match( $rule_data ) {
		$user_id = BWFAN_Core()->rules->getRulesData( 'user_id' );

		if ( empty( $user_id ) ) {
			$email = BWFAN_Core()->rules->getRulesData( 'email' );
			$user  = ! is_email( $email ) ? get_user_by( 'email', $email ) : BWFAN_Core()->rules->getRulesData( 'wp_user' );

			if ( ! $user instanceof WP_User ) {
				return $this->return_is_match( false, $rule_data );
			}

			$user_id = $user->ID;
		}

		$rule_data['condition'] = array_map( 'intval', $rule_data['condition'] );
		$result                 = in_array( $user_id, $rule_data['condition'], true );
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

}
