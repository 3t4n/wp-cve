<?php

class CustomSortStack {

	/**
	 * Get sorted array
	 *
	 * @return  array
	 */
	public function custom_sort( $temp_arr, $sort_by = 'item_count', $sort_type = 'DESC' ) {
		$function = $sort_by . '_';

		switch ( $sort_type ) {
			case 'DESC':
				$function .= 'rcmp';
				break;
			case 'ASC':
				$function .= 'cmp';
				break;
			default:
				$function .= 'rcmp';
				break;
		}

		usort( $temp_arr, array( $this, $function ) );

		return $temp_arr;
	}

	public function itemid_cmp( $a, $b ) {
		if ( $a->id == $b->id ) {
			return 0;
		}
		return ( $a->id < $b->id ) ? -1 : 1;
	}
	public function paymentsordering_cmp( $a, $b ) {
		$tempa = array_keys( $a );
		$tempb = array_keys( $b );

		if ( $tempa[0] == $tempb[0] ) {
			return 0;
		}
		return ( $tempa[0] < $tempb[0] ) ? -1 : 1;
	}
	public function itemid_rcmp( $a, $b ) {
		if ( $a->id == $b->id ) {
			return 0;
		}
		return ( $a->id > $b->id ) ? -1 : 1;
	}
	public function car_price_cmp( $a, $b ) {
		if ( $a->car_price == $b->car_price ) {
			return 0;
		}
		return ( $a->car_price < $b->car_price ) ? -1 : 1;
	}
	public function car_price_rcmp( $a, $b ) {
		if ( $a->car_price == $b->car_price ) {
			return 0;
		}
		return ( $a->car_price > $b->car_price ) ? -1 : 1;
	}
}

// sort an array
function sort_stack( $temp_arr, $sort_by = 'item_count', $sort_type = 'DESC' ) {
	$stack = new CustomSortStack();
	return $stack->custom_sort( $temp_arr, $sort_by, $sort_type );
}
