<?php
namespace CTXFeed\V5\Filter;
use CTXFeed\V5\Utility\Config;
use CTXFeed\V5\Helper\ProductHelper;
use WC_Product;

class AdvanceFilter {

	/**
	 * Filter Products by Conditions
	 *
	 * @param WC_Product $product
	 * @param Config     $config
	 *
	 * @return bool
	 * @since 3.2.0
	 */
	public static function filter_product( $product, $config ) {

		if ( isset( $config->fattribute ) && count( $config->fattribute ) ) {

			// Filtering Variable
			$fAttributes   = $config->fattribute;
			$conditions    = $config->condition;
			$filterCompare = $config->filterCompare;
			$concatType    = isset( $config->concatType ) ? $config->concatType : [];

			// Backward compatibility for <= v5.2.25
			$filterType = isset( $config->filterType ) && ! empty( $config->filterType )
				? $config->filterType
				: 2;

			$filterType = $filterType === 1
				? 'OR'
				: 'AND';

			// Tracking Variables
			$totalOr          = 0;
			$effectiveOrCount = 0;

			foreach ( $fAttributes as $key => $check ) {

				$flag = false;

				// Backward compatibility for <= v5.2.25
				$concatOperator = isset( $concatType[ $key ] ) && ! empty( $concatType[ $key ] )
					? $concatType[ $key ]
					: $filterType;

				if ( $concatOperator === 'OR' ) {
					$totalOr ++;
				}

				$conditionName    = ProductHelper::get_attribute_value_by_type( $check, $product, $config );
				$condition        = $conditions[ $key ];
				$conditionCompare = stripslashes( $filterCompare[ $key ] );
				// DEBUG HERE
				// echo "Product Name: ".$product->get_name() .''.$product->get_id();   echo "<br>";
				// echo "Name: ".$conditionName;   echo "<br>";
				// echo "Condition: ".$condition;   echo "<br>";
				// echo "Compare: ".$conditionCompare;  echo "<br>";   echo "<br>";

				switch ( $condition ) {

					case '==':
						if ( strtolower( $conditionName ) === strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case '!=':
						if ( strtolower( $conditionName ) !== strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case '>=':
						if ( strtolower( $conditionName ) >= strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case '<=':
						if ( strtolower( $conditionName ) <= strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case '>':
						if ( strtolower( $conditionName ) > strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case '<':
						if ( strtolower( $conditionName ) < strtolower( $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case 'contains':
						if ( false !== stripos( $conditionName, $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case 'nContains':
						if ( false === stripos( $conditionName, $conditionCompare ) ) {
							$flag = true;
						}
						break;
					case 'between':
						$compare_items = explode( '-', $conditionCompare );
						if ( $conditionName >= $compare_items[0] && $conditionName <= $compare_items[1] ) {
							$flag = true;
						}
						break;
					default:
						break;
				}


				if ( $concatOperator === 'OR' && $flag ) {
					$effectiveOrCount ++;
				}

				if ( $concatOperator === 'AND' && ! $flag ) {
					return false;
				}
			}

			if ( $totalOr > 0 && $effectiveOrCount === 0 ) {
				return false;
			}
		}

		return true;
	}


}
