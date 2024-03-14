<?php
/*
* Decimal Product Quantity for WooCommerce
* Admin WooCommerce Order Page.
* admin_order.php
*/

    /* WooCommerce Order Page.
	 * Шаг изменения кол-ва Товара на странице Администрирования Заказа.
	 * \woocommerce\includes\admin\meta-boxes\views\html-order-item.php
    ----------------------------------------------------------------- */ 
	add_filter ('woocommerce_quantity_input_step_admin', 'WooDecimalProduct_quantity_Input_Step', 10, 3);
	function WooDecimalProduct_quantity_Input_Step($Step_Qnt, $product, $mode) {
		// $mode = 'edit' or 'refund'

		if (is_admin()) {
			if ($product) {
				$Parent_ID = 0;
				
				if (method_exists($product, 'get_parent_id')) {
					$Parent_ID = $product->get_parent_id();
				}
				
				if ($Parent_ID > 0) {
					// Вариативный Товар.
					$Product_ID = $Parent_ID;
				} else {
					// Простой Товар.				
					if (method_exists($product, 'get_id')) {
						$Product_ID = $product->get_id();
					}
				}

				$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID);
				
				$Step_Qnt = $WooDecimalProduct_QuantityData['stp_qnt'];	

				return $Step_Qnt;				
			}
		}
		
		return $Step_Qnt;
	}
	
    /* WooCommerce Order Page.
	 * Минимальное кол-во Товара на странице Администрирования Заказа.
	 * \woocommerce\includes\admin\meta-boxes\views\html-order-item.php
    ----------------------------------------------------------------- */ 
	add_filter ('woocommerce_quantity_input_min_admin', 'WooDecimalProduct_quantity_Input_Min', 10, 3);
	function WooDecimalProduct_quantity_Input_Min($Min_Qnt, $product, $mode) {
		// $mode = 'edit' or 'refund'
		
		if (is_admin()) {
			if ($product) {
				$Parent_ID = 0;
				
				if (method_exists($product, 'get_parent_id')) {
					$Parent_ID = $product->get_parent_id();
				}
				
				if ($Parent_ID > 0) {
					// Вариативный Товар.
					$Product_ID = $Parent_ID;
				} else {
					// Простой Товар.
					if (method_exists($product, 'get_id')) {
						$Product_ID = $product->get_id();
					}
				}
				
				$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID);
				
				$Min_Qnt = $WooDecimalProduct_QuantityData['min_qnt'];
				
				return $Min_Qnt;				
			}
		}
		
		return $Min_Qnt;
	}