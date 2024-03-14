<?php

class SelectList {

	public static function getCallingCodeOptions( $name, $class, $selectedValue, $scope = 'frontend' ) {
		global $wpdb;
		$dbtable = $wpdb->prefix . 'tblight_countries';

		$rows = $wpdb->get_results( 'SELECT * FROM ' . $dbtable );

		$html  = '';
		$html .= '<select name="' . $name . '" class="' . $class . '" id="' . $class . '">';
		foreach ( $rows as $country ) {
			$selected           = ( $country->country_id == $selectedValue ) ? 'selected="selected"' : '';
			$calling_code_value = $country->calling_code;
			// display calling code preceeding a plus sign if it is not there already
			$calling_code_label = ( substr( $country->calling_code, 0, 1 ) == '+' ) ? $country->calling_code : '+' . $country->calling_code;

			if ( $scope == 'backend' ) {
				$selected = ( $country->calling_code == $selectedValue ) ? 'selected="selected"' : '';
				$html    .= '<option data-countrycode="' . $country->country_2_code . '" data-callingcode="' . $country->calling_code . '" value="' . $country->country_id . '"' . $selected . '>' . $country->country_name . ' (' . $calling_code_label . ')</option>';
			} else {
				$html .= '<option data-countrycode="' . $country->country_2_code . '" data-countryid="' . $country->country_id . '" value="' . $calling_code_value . '"' . $selected . '>' . $country->country_name . ' (' . $calling_code_label . ')</option>';
			}
		}
		$html .= '</select>';

		return $html;
	}

	public static function getCountryOptions( $name, $class, $selectedValue ) {
		 global $wpdb;
		$dbtable = $wpdb->prefix . 'tblight_countries';

		$rows = $wpdb->get_results( 'SELECT * FROM ' . $dbtable );

		$html  = '';
		$html .= '<select name="' . $name . '" class="' . $class . '" id="' . $class . '">';
		foreach ( $rows as $country ) {
			$selectedClass = ( $country->country_id == $selectedValue ) ? 'selected="selected"' : '';
				$html     .= '<option value=' . $country->country_id . ' ' . $selectedClass . '>' . $country->country_name . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	public static function getCurrencyOptions( $name, $class, $selectedValue ) {
		global $wpdb;
		$dbtable = $wpdb->prefix . 'tblight_currencies';
		$sql     = 'SELECT currency_id, currency_code, currency_name FROM ' . $dbtable . ' ORDER BY currency_name ASC';
		$rows    = $wpdb->get_results( $sql );

		$html  = '';
		$html .= '<select name="' . $name . '" class="' . $class . '" id="' . $class . '">';
		foreach ( $rows as $currency ) {
			$selected = ( $currency->currency_code == $selectedValue ) ? 'selected="selected"' : '';
			$html    .= '<option value=' . $currency->currency_code . ' ' . $selected . '>' . $currency->currency_name . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	public static function getPaymentOptions( $name, $class, $selectedValue ) {
		 $paymentMethods = array(
			 '0'      => 'Select a Method',
			 'cash'   => 'Cash',
			 'paypal' => 'Paypal (Available in PRO)',
			 'stripe' => 'Stripe (Available in PRO)',
		 );

		 $html  = '';
		 $html .= '<select name="' . $name . '" class="' . $class . '" id="' . $class . '">';
		 foreach ( $paymentMethods as $key => $value ) {
			 $selectedClass = ( $key == $selectedValue ) ? ' selected="selected"' : '';
			 $html         .= '<option value="' . $key . '"' . $selectedClass . '>' . $value . '</option>';
		 }
		 $html .= '</select>';

		 return $html;
	}

	public static function getDefaultOrderStatusOptions( $name, $class, $selectedValue ) {
		$orderStatus = array(
			'1'  => 'Accepted',
			'0'  => 'Rejected',
			'-1' => 'Archived',
			'-2' => 'Waiting',
		);

		$html  = '';
		$html .= '<select name="' . $name . '" class="' . $class . '" id="' . $class . '">';
		foreach ( $orderStatus as $key => $value ) {
			$selectedClass = ( $key == $selectedValue ) ? 'selected="selected"' : '';
			$html         .= '<option value=' . $key . ' ' . $selectedClass . '>' . $value . '</option>';
		}
		$html .= '</select>';

		return $html;
	}

	public static function getSelectListHtml( $name, $class = '', $optionsArr = array(), $selectedValue = '' ) {
		$html = '';

		if ( ! empty( $optionsArr ) ) {
			$html .= '<select name="' . $name . '" class="' . $class . '" id="' . $name . '">';
			foreach ( $optionsArr as $key => $value ) {
				$selected = ( $key == $selectedValue ) ? ' selected="selected"' : '';
				$html    .= '<option value="' . $key . '"' . $selected . '>' . $value . '</option>';
			}
			$html .= '</select>';
		}
		return $html;
	}
}
