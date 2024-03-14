<?php
/**
 * Check if field visible.
 *
 * @package Canvas
 */

if ( ! class_exists( 'CNVS_Gutenberg_Utils_Is_Field_Visible' ) ) {
	/**
	 * Class Gutenberg Utils Is Field Visible.
	 */
	class CNVS_Gutenberg_Utils_Is_Field_Visible {
		/**
		 * Check active_callback in custom controls.
		 *
		 * @param array $fieldData field data.
		 * @param array $attributes block attributes.
		 * @param array $allFields all fields.
		 * @param array $prepareAttrs prepare attributes for server render.
		 *
		 * @return boolean
		 */
		public static function check( $fieldData, $attributes, $allFields, $prepareAttrs = true ) {
			$result = true;

			// if ( $prepareAttrs ) {
			// 	$attributes = apply_filters( 'canvas_block_prepare_server_render_attributes', $attributes, array( 'fields' => $allFields ) );
			// }

			if ( isset( $fieldData['active_callback'] ) ) {
				$result = self::checkCondition( $fieldData['active_callback'], $attributes, 'AND' );
			}

			return $result;
		}

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
		 * @param string $relation - Can be one of 'AND' or 'OR'.
		 *
		 * @return boolean
		 */
		public static function checkCondition( $conditions, $attributes, $relation ) {
			$childRelation = ( 'AND' === $relation ) ? 'OR' : 'AND';

			// By default result will be TRUE for relation AND and FALSE for relation OR.
			$result = $relation === 'AND';

			foreach ( $conditions as $data ) {
				if ( is_array( $data ) && ! isset( $data['field'] ) ) {
					$result = self::compare( $result, $relation, self::checkCondition( $data, $attributes, $childRelation ) );
				} elseif ( isset( $data['field'] ) ) {
					$splitValName = explode( '.', $data['field'] );
					$fieldVal     = null;

					// Check for array values like: toggleListName['option1'].
					if ( 2 === count( $splitValName ) && isset( $attributes[ $splitValName[0] ] ) && isset( $attributes[ $splitValName[0] ][ $splitValName[1] ] ) ) {
						$fieldVal = $attributes[ $splitValName[0] ][ $splitValName[1] ];
					}

					// Check for normal values.
					if ( null === $fieldVal && isset( $attributes[ $data['field'] ] ) ) {
						$fieldVal = $attributes[ $data['field'] ];
					}

					// Check count.
					if ( isset( $data['count'] ) ) {
						$count    = explode( $data['count'], $fieldVal );
						$fieldVal = count( $count ) - 1;
					}

					if ( null !== $fieldVal ) {
						$result = self::compare( $result, $relation, self::compare( $fieldVal, isset( $data['operator'] ) ? $data['operator'] : '===', isset( $data['value'] ) ? $data['value'] : true ) );
					}
				}
			}

			return $result;
		}
	}
}
