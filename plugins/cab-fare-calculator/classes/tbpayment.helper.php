<?php

class tbPaymentPlugin {

	// the name of the table to store plugin internal data, like payment logs
	protected $_tablename = 0;
	protected $_tableId   = 'id';
	// the name of the table which holds the configuration like paymentmethods, shipmentmethods, customs
	protected $_configTable          = 0;
	protected $_configTableFileName  = 0;
	protected $_configTableClassName = 0;
	// id field of the config table
	protected $_idName = 0;
	// Name of the field in the configtable, which holds the parameters of the pluginmethod
	protected $_configTableFieldName = 0;
	protected $_loggable             = false;

	function __construct() {

		global $wpdb;

		$this->_tablepkey            = 'id'; // order_id';
		$this->_idName               = 'id';
		$this->_configTable          = $wpdb->prefix . 'tblight_paymentmethods';
		$this->_configTableFieldName = 'payment_params';
		$this->_configTableFileName  = 'paymentmethods';
		$this->_configTableClassName = 'TablePaymentmethods'; // TablePaymentmethods
		$this->_tablename            = $wpdb->prefix . 'tblight_payment_plg_' . $this->_name;
	}

	/**
	 * displayListFE
	 * This event is fired to display the pluginmethods
	 *
	 * @param integer $selected ID of the method selected
	 * @return boolean True on success, false on failures, null when this plugin was not selected.
	 * On errors, JError::raiseWarning (or JError::raiseError) must be used to set a message.
	 */
	public function displayListFE( $selected = 0 ) {

		if ( $this->getPluginMethods() === 0 ) {
			return false;
		}

		session_start();

		$html        = array();
		$method_name = 'payment_name';

		foreach ( $this->methods as $method ) {
			if ( $this->checkConditions( $method, $_SESSION['price'] ) ) {
				$methodSalesPrice     = $this->calculateSalesPrice( $method, $_SESSION['price'] );
				$method->$method_name = $this->renderPluginName( $method );
				// $html[$method->ordering] = $this->getPluginHtml($method, $selected, $methodSalesPrice);
				$html[ $method->id ] = $this->getPluginHtml( $method, $selected, $methodSalesPrice );
			}
		}

		if ( ! empty( $html ) ) {
			return $html;
		}

		return false;
	}

	/**
	 * Fill the array with all plugins found with this plugin for the current vendor
	 *
	 * @return True when plugins(s) was (were) found for this vendor, false otherwise
	 */
	protected function getPluginMethods() {

		global $wpdb;

		$sql           = 'SELECT * 
			FROM ' . $this->_configTable . '  
			WHERE state = 1';
		$this->methods = $wpdb->get_results( $sql );

		// process params
		if ( ! empty( $this->methods ) ) {
			foreach ( $this->methods as $method ) {
				$params = json_decode( $method->payment_params );
				foreach ( $params as $k => $v ) {
					$method->$k = $v;
				}
			}
		}

		return count( $this->methods );
	}

	/**
	 * calculateSalesPrice
	 *
	 * @param $value
	 * @return $salesPrice
	 */
	protected function calculateSalesPrice( $method, $cart_price ) {

		$value = $this->getCosts( $method, $cart_price );

		return $value;
	}

	/**
	 * @param $plugin plugin
	 */
	protected function renderPluginName( $plugin ) {

		$return      = '';
		$plugin_name = 'title';
		$plugin_desc = 'text';
		$description = '';

		if ( ! empty( $plugin->$plugin_desc ) ) {
			$description = '<br/><span class="payment_description">' . $plugin->$plugin_desc . '</span>';
		}
		$pluginName = $return . '<span class="payment_name">' . $plugin->$plugin_name . '</span>' . $description;
		return $pluginName;
	}

	protected function getPluginHtml( $plugin, $selectedPlugin, $pluginSalesPrice ) {

		$pluginmethod_id = $this->_idName;
		$pluginName      = 'title';
		if ( $selectedPlugin == $plugin->$pluginmethod_id ) {
			$checked = 'checked="checked"';
		} else {
			$checked = '';
		}

		$costDisplay = '';
		if ( $pluginSalesPrice ) {
			$costDisplay = '<span class="payment_cost"> (' . BookingHelper::price_display( $pluginSalesPrice ) . ')</span>';
		}

		// show grand total
		$grandTotalDisplay = ' - <span class="grand_total">' . $this->getGrandTotalLabel( $plugin, $selectedPlugin, $pluginSalesPrice ) . '</span>';

		if ( $plugin->text != '' ) {
			$plugin_title = '<a href="javascript:void(0);" class="payment_desc" title="' . strip_tags( $plugin->text ) . '">' . $plugin->title . '</a>';
		} else {
			$plugin_title = $plugin->title;
		}

		$html = '<div style="width: 100%;float: left; margin-bottom: 5px;"><input type="radio" name="tb_paymentmethod_id" class="tb_paymentmethods" id="payment_id_' . $plugin->$pluginmethod_id . '"   value="' . $plugin->$pluginmethod_id . '" ' . $checked . ">\n"
			. '<label for="payment_id_' . $plugin->$pluginmethod_id . '">' . '<span class="payment">' . $plugin_title . $costDisplay . $grandTotalDisplay . '</span></label></div>';

			return $html;
	}

	protected function getGrandTotalLabel( $method, $selectedPlugin, $pluginSalesPrice ) {

		$pluginmethod_id = $this->_idName;
		$sub_total       = (float) $_SESSION['price'];

		if ( preg_match( '/%$/', $method->cost_percent_total ) ) {
			$cost_percent_total = substr( $method->cost_percent_total, 0, -1 );
		} else {
			$cost_percent_total = $method->cost_percent_total;
		}
		$flat_fee       = $method->cost_per_transaction;
		$percentage_fee = $sub_total * $cost_percent_total * 0.01;

		$grand_total = $sub_total + $flat_fee + $percentage_fee;

		$html = sprintf( 'Grand Total %s', BookingHelper::price_display( $grand_total ) );

		return $html;
	}

	/*
	 * onSelectedCalculatePrice
	*/
	public function onSelectedCalculatePrice( $method_id, $cart_prices ) {

		$id = $this->_idName;
		if ( ! ( $method = $this->selectedThisByMethodId( $method_id ) ) ) {
			return null; // Another method was selected, do nothing
		}
		if ( ! $this->checkConditions( $method, $cart_prices ) ) {
			return false;
		}
		$paramsName = 'payment_params';

		$this->setCartPrices( $cart_prices, $method );

		return true;
	}

	/**
	 * update the plugin cart_prices
	 */
	function setCartPrices( &$cart_price, $method ) {

		if ( preg_match( '/%$/', $method->cost_percent_total ) ) {
			$cost_percent_total = substr( $method->cost_percent_total, 0, -1 );
		} else {
			$cost_percent_total = $method->cost_percent_total;
		}
		$flat_fee       = $method->cost_per_transaction;
		$percentage_fee = $cart_price * $cost_percent_total * 0.01;

		$_SESSION['flat_cost']       = $flat_fee;
		$_SESSION['percentage_cost'] = $percentage_fee;
	}

	/**
	 * Get Plugin Data for a go given plugin ID
	 *
	 * @param int $pluginmethod_id The method ID
	 * @return  method data
	 */
	protected function getPluginMethod( $method_id ) {

		$method = $this->selectedThisByMethodId( $method_id );
		if ( ! $method ) {
			return null; // Another method was selected, do nothing
		} else {
			return $method;
		}
	}
	/**
	 * Checks if this plugin should be active by the trigger
	 *
	 * @param int/array $id the registered plugin id(s) of the joomla table
	 */
	protected function selectedThisByMethodId( $id = 'type' ) {

		if ( $id === 'type' ) {
			return true;
		} else {
			global $wpdb;

			$sql = 'SELECT * 
				FROM ' . $this->_configTable . '  
				WHERE id = ' . (int) $id;
			$res = $wpdb->get_row( $sql );

			if ( ! $res ) {
				return false;
			} else {
				// process params
				$params = json_decode( $res->payment_params );
				foreach ( $params as $k => $v ) {
					$res->$k = $v;
				}
				return $res;
			}
		}
	}

	/**
	 * check if it is the correct element
	 *
	 * @param string $element either standard or paypal
	 * @return boolean
	 */
	public function selectedThisElement( $element ) {

		if ( $this->_name <> $element ) {
			return false;
		} else {
			return true;
		}
	}

	/**
	 * Extends the standard function in vmplugin. Extendst the input data by virtuemart_order_id
	 * Calls the parent to execute the write operation
	 *
	 * @param array  $_values
	 * @param string $_table
	 */
	protected function storePluginInternalData( $values, $primaryKey = 0, $preload = false ) {

		global $wpdb;

		$wpdb->insert( $this->_tablename, $values );

		return $values;
	}

	/**
	 * Get Method Data for a given Payment ID
	 *
	 * @param int $order_id The order ID
	 * @return  $methodData
	 */
	protected function getDataByOrderId( $order_id ) {

		global $wpdb;

		$sql = 'SELECT * 
			FROM ' . $this->_tablename . '  
			WHERE order_id = ' . (int) $order_id;

		$results = $wpdb->get_results( $sql );

		return $results;
	}
}
