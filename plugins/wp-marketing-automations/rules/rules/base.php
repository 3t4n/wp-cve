<?php

/**
 * Base class for a Conditional_Content rule.
 */
class BWFAN_Rule_Base {

	public $supports = array();
	public $name = '';
	public $description = '';
	protected $v2 = false;
	protected $v1 = true;

	/** v2 Properties: START */
	public $event_automation_meta = [];

	public $title = '';

	/** v2 Properties: END */

	public function is_v2() {
		return $this->v2;
	}


	public function is_v1() {
		return $this->v1;
	}

	public function __construct( $name ) {
		$this->name = $name;
	}

	public function get_name() {
		return $this->name;
	}

	/**
	 * Checks if the conditions defined for this rule object have been met.
	 *
	 * @return boolean
	 */
	public function is_match( $rule_data ) {
		return false;
	}

	/**
	 * Helper function to wrap the return value from is_match and apply filters or other modifications in sub classes.
	 *
	 * @param boolean $result The result that should be returned.
	 * @param array $rule_data The array config object for the current rule.
	 *
	 * @return boolean
	 */
	public function return_is_match( $result, $rule_data ) {
		return apply_filters( 'bwfan_rules_is_match', $result, $rule_data );
	}

	/*
	 * Gets the input object type slug for this rule object.
	 */
	public function supports( $env ) {
		return in_array( $env, $this->supports, true );
	}

	public function operators_view() {
		$operators = $this->get_possible_rule_operators();
		if ( empty( $operators ) ) {
			return;
		}

		$operator_args = array(
			'input'   => 'select',
			'name'    => 'bwfan_rule[<%= groupId %>][<%= ruleId %>][operator]',
			'choices' => $operators,
		);
		bwfan_Input_Builder::create_input_field( $operator_args );
	}

	/**
	 * Gets the list of possible rule operators available for this rule object.
	 *
	 * Override to return your own list of operators.
	 *
	 * @return array
	 */
	public function get_possible_rule_operators() {
		return array(
			'==' => __( 'is equal to', 'wp-marketing-automations' ),
			'!=' => __( 'is not equal to', 'wp-marketing-automations' ),
		);
	}

	public function conditions_view() {
		$condition_input_type = $this->get_condition_input_type();
		$values               = $this->get_possible_rule_values();
		$value_args           = array(
			'input'   => $condition_input_type,
			'name'    => 'bwfan_rule[<%= groupId %>][<%= ruleId %>][condition]',
			'choices' => $values,
		);

		bwfan_Input_Builder::create_input_field( $value_args );
		echo $this->add_description(); //phpcs:ignore WordPress.Security.EscapeOutput
	}

	public function get_condition_input_type() {
		return 'Select';
	}

	/**
	 * Get's the list of possible values for the rule.
	 *
	 * Override to return the correct list of possible values for your rule object.
	 * @return array
	 */
	public function get_possible_rule_values() {
		return array();
	}

	public function ui_view() {
		esc_html_e( 'Rule Preview here..', 'wp-marketing-automations' );
	}

	public function get_ui_preview_data() {
		return $this->get_possible_rule_values();
	}

	public function add_description() {
		ob_start();

		if ( empty( $this->description ) ) {
			return ob_get_clean();
		}

		echo '<div class="clearfix bwfan_field_desc">' . esc_html__( $this->description ) . '</div>';

		return ob_get_clean();
	}

	public function validate_matches_set( $array1, $array2, $operator ) {
		switch ( $operator ) {
			case 'any':
				$result = count( array_intersect( $array1, $array2 ) ) > 0;
				break;
			case 'all':
				$result = count( array_intersect( $array1, $array2 ) ) === count( $array1 );
				break;
			case 'none':
				$result = count( array_intersect( $array1, $array2 ) ) === 0;
				break;
			default:
				$result = false;
				break;
		}

		return $result;
	}

	public function validate_matches_duration_set( $data, $rule_data, $type ) {
		$current_time = current_time( 'timestamp' );
		if ( 'between' === $type && is_array( $rule_data['data'] ) ) {
			$from_data = $rule_data['data']['from'];
			$to_data   = $rule_data['data']['to'];
			$from      = strtotime( date( 'Y-m-d', $current_time - ( DAY_IN_SECONDS * absint( $from_data ) ) ) );// excluding time
			$to        = strtotime( date( 'Y-m-d', $current_time - ( DAY_IN_SECONDS * absint( $to_data ) ) ) );// excluding time
			$result    = ( ( $data >= $to ) && ( $data <= $from ) );

			return $result;
		}

		$filter_value = strtotime( date( 'Y-m-d', $current_time - ( DAY_IN_SECONDS * absint( $rule_data['data'] ) ) ) );// excluding time

		switch ( $type ) {
			case 'over':
				$result = ( $data < $filter_value );
				break;
			case 'past':
				$result = ( $data >= $filter_value );
				break;
			default:
				$result = false;
				break;
		}

		return $result;
	}

	public function validate_matches( $operator, $condition_data, $data ) {
		$result = false;
		switch ( $operator ) {
			case 'is':
				$result = ( $condition_data === $data );
				break;
			case 'isnot':
			case 'is_not':
				$result = ( $condition_data !== $data );
				break;
			case 'contains':
				$result = strpos( $data, $condition_data ) !== false;
				break;
			case 'not_contains':
				$result = strpos( $data, $condition_data ) === false;
				break;
			case 'starts_with':
				$length = strlen( $condition_data );
				$result = substr( $data, 0, $length ) === $condition_data;
				break;
			case 'ends_with':
				$length = strlen( $condition_data );

				if ( 0 === $length ) {
					$result = true;
				} else {
					$result = substr( $data, - $length ) === $condition_data;
				}
				break;
			case 'is_blank':
				$result = empty( $data );
				break;
			case 'is_not_blank':
				$result = ! empty( $data );
				break;
			default:
				break;
		}

		return $result;
	}

	public function operator_matches() {
		return array(
			'is'           => __( 'is', 'wp-marketing-automations' ),
			'is_not'       => __( 'is not', 'wp-marketing-automations' ),
			'contains'     => __( 'contains', 'wp-marketing-automations' ),
			'not_contains' => __( 'does not contain', 'wp-marketing-automations' ),
			'starts_with'  => __( 'starts with', 'wp-marketing-automations' ),
			'ends_with'    => __( 'ends with', 'wp-marketing-automations' ),
		);
	}

	/** v2 Methods: START */

	public function get_options( $search = '' ) {
		return [];
	}

	public function get_rule_type() {
		return 'Text';
	}

	public function get_value_label() {
		return '';
	}

	public function get_readable_text_schema() {
		return '{{key /}} {{rule /}} {{value /}}';
	}

	public function is_match_v2( $automation_data, $rule_data ) {
		return true;
	}

	public function get_default_rule_value() {
		return '';
	}

	public function get_multiple_select_support() {
		return true;
	}

	/** v2 Methods: END */

	public function make_value_as_array( $value ) {
		if ( is_array( $value ) ) {
			return $value;
		}

		/** Checking if value contains comma */
		if ( strpos( $value, ',' ) !== false ) {
			$value = explode( ',', $value );
			$value = array_map( 'trim', $value );
		} else {
			$value = array( $value );
		}

		return $value;
	}

	/**
	 * Return extra properties need in js end
	 *
	 * @return array
	 */
	public function get_extra_props() {
		return [];
	}
}
