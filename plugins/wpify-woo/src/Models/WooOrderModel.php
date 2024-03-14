<?php

namespace WpifyWoo\Models;

use WpifyWooDeps\Wpify\Core\Abstracts\AbstractWooOrderModel;

class WooOrderModel extends AbstractWooOrderModel {
	private $ic;
	private $dic;

	/**
	 * @return mixed
	 */
	public function get_ic() {
		if ( $this->ic ) {
			return $this->ic;
		}
		$this->ic = $this->get_wc_order()->get_meta( '_billing_ic' );

		return $this->ic;
	}

	/**
	 * @return mixed
	 */
	public function get_dic() {
		if ( $this->dic ) {
			return $this->dic;
		}

		$this->dic = $this->get_wc_order()->get_meta( '_billing_dic_dph' );

		if (!$this->dic) {
			$this->dic = $this->get_wc_order()->get_meta( '_billing_dic' );
		}


		return $this->dic;
	}

}
