<?php
/**
 * Utils Is Field Visible.
 *
 * @package Sight
 */

/**
 * Class Utils Is Field Visible.
 */
class Sight_Utils_Is_Field_Visible {
	/**
	 * Compare 2 values
	 *
	 * @param mixed  $lval Left value.
	 * @param string $operator Operator.
	 * @param mixed  $rval Right value.
	 *
	 * @return boolean
	 */
	public static function compare( $lval, $operator, $rval ) {
		$check_result = true;

		switch ( $operator ) {
			case '==':
				$check_result = $lval == $rval;
				break;
			case '===':
				$check_result = $lval === $rval;
				break;
			case '!=':
				$check_result = $lval != $rval;
				break;
			case '!==':
				$check_result = $lval !== $rval;
				break;
			case '>=':
				$check_result = $lval >= $rval;
				break;
			case '<=':
				$check_result = $lval <= $rval;
				break;
			case '>':
				$check_result = $lval > $rval;
				break;
			case '<':
				$check_result = $lval < $rval;
				break;
			case 'AND':
				$check_result = $lval && $rval;
				break;
			case 'OR':
				$check_result = $lval || $rval;
				break;
			default:
				$check_result = $lval;
				break;
		}

		return $check_result;
	}

	/**
	 * Check condition
	 *
	 * @param array  $conditions - Conditions array.
	 * @param array  $attributes - Available block attributes.
	 * @param string $relation   - Can be one of 'AND' or 'OR'.
	 *
	 * @return boolean
	 */
	public static function check_condition( $conditions, $attributes, $relation ) {
		$child_relation = ( 'AND' === $relation ) ? 'OR' : 'AND';

		// By default result will be TRUE for relation AND and FALSE for relation OR.
		$result = 'AND' === $relation;

		foreach ( $conditions as $data ) {
			if ( is_array( $data ) && ! isset( $data['field'] ) ) {
				$result = self::compare( $result, $relation, self::check_condition( $data, $attributes, $child_relation ) );
			} elseif ( isset( $data['field'] ) ) {
				$split_val_name = explode( '.', $data['field'] );
				$field_val      = null;

				// Check for array values like: toggleListName['option1'].
				if ( 2 === count( $split_val_name ) && isset( $attributes[ $split_val_name[0] ] ) && isset( $attributes[ $split_val_name[0] ][ $split_val_name[1] ] ) ) {
					$field_val = $attributes[ $split_val_name[0] ][ $split_val_name[1] ];
				}

				// Check for normal values.
				if ( null === $field_val && isset( $attributes[ $data['field'] ] ) ) {
					$field_val = $attributes[ $data['field'] ];
				}

				// Check count.
				if ( isset( $data['count'] ) ) {
					$count     = explode( $data['count'], $field_val );
					$field_val = count( $count ) - 1;
				}

				if ( null !== $field_val ) {
					$result = self::compare( $result, $relation, self::compare( $field_val, isset( $data['operator'] ) ? $data['operator'] : '===', isset( $data['value'] ) ? $data['value'] : true ) );
				}
			}
		}

		return $result;
	}
}
