<?php

class CompanyHelper {

	public static function getCompanyConfigs() {
		global $wpdb;

		$rows = $wpdb->get_results( 'SELECT id, title, alias FROM ' . $wpdb->prefix . 'tblight_configs' );

		$result = array();
		if ( ! empty( $rows ) ) {
			foreach ( $rows as $row ) {
				$result[ $row->alias ] = $row->id;
			}
		}
		return $result;
	}
	public static function getConfigURL( $config_id, $debug_row_title ) {
		$admin_config_url = 'javascript:void(0);';

		$html = '<a href="' . $admin_config_url . '" target="_blank">' . $debug_row_title . '</a>';

		return $html;
	}
	public static function getCompanyPaymentMethods() {
		 global $wpdb;

		$rows = $wpdb->get_results( 'SELECT id, title, alias, text FROM ' . $wpdb->prefix . 'tblight_paymentmethods WHERE state = 1' );

		return $rows;
	}
	public static function getSelectPaymentMethodsHtml( $payment_methods = array() ) {
		global $wpdb;

		$html = '';
		if ( ! empty( $payment_methods ) ) {
			foreach ( $payment_methods as $payment_method ) {
				$checked = '';

				$html .= '<div style="width: 100%;float: left; margin-bottom: 5px;"><input type="radio" name="tb_paymentmethod_id" class="tb_paymentmethods" id="payment_id_' . $payment_method->id . '"   value="' . $payment_method->id . '" ' . $checked . ">\n"
				. '<label for="payment_id_' . $payment_method->id . '">' . '<span class="payment">' . $payment_method->title . '</span></label></div>';
			}
		}

		return $html;
	}
	public static function getCountryById( $id ) {
		global $wpdb;

		$sql  = 'SELECT * FROM ' . $wpdb->prefix . 'tblight_countries';
		$sql .= ' WHERE country_id = ' . (int) $id;
		$row  = $wpdb->get_row( $sql );

		return $row;
	}
}
