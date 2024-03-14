<?php
/*
* Decimal Product Quantity for WooCommerce
* Admin Product Setup Page.
* admin_setup_product.php
*/

	/* DashBoard. WooCommerce. List Products. 
	 * Добавляем новые Колонки в Списке Товаров.
	----------------------------------------------------------------- */	
	add_filter ('manage_edit-product_columns', 'WooDecimalProduct_filter_manage_edit_product_columns');
	function WooDecimalProduct_filter_manage_edit_product_columns ($Columns) {		
		$New_Columns = array();
		
		foreach ($Columns as $column_name => $column_info) {
			$New_Columns [$column_name] = $column_info;
			
			if ($column_name == 'price' ) {
				$New_Columns['quantity'] = 'Quantity';
			}			
		}		
		
		return $New_Columns;
	}	
	
	/* DashBoard. WooCommerce. List Products.
	 * Заполняем новые Колонки в Списке Товаров.
	----------------------------------------------------------------- */
	add_action ('manage_product_posts_custom_column', 'WooDecimalProduct_action_manage_product_posts_custom_column', 10, 2);
	function WooDecimalProduct_action_manage_product_posts_custom_column ($Column, $Product_ID) {
		if ($Column == 'quantity') {
			$No_MaxEmpty = '---';
			$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID, $No_MaxEmpty);
			
			$Min_Qnt 		= $WooDecimalProduct_QuantityData['min_qnt'];
			$Max_Qnt 		= $WooDecimalProduct_QuantityData['max_qnt'];
			$Def_Qnt 		= $WooDecimalProduct_QuantityData['def_qnt'];
			$Stp_Qnt 		= $WooDecimalProduct_QuantityData['stp_qnt'];				
			$QNT_Precision 	= $WooDecimalProduct_QuantityData['precision'];		
				
			echo "Min: $Min_Qnt<br>";
			echo "Max: $Max_Qnt <br>";
			echo "Step: $Stp_Qnt <br>";
			echo "Set: $Def_Qnt";
		}
	}
	
	/* DashBoard. WooCommerce. List Products.
	 * Добавляем в Колонку "Price" -> "Pice_Unit_Label" в Списке Товаров.
	----------------------------------------------------------------- */	
	add_filter ('woocommerce_get_price_html', 'WooDecimalProduct_filter_get_price_html', 10, 2);	
	function WooDecimalProduct_filter_get_price_html ($price, $product) {		
		$WooDecimalProduct_Price_Unit_Label	= get_option ('woodecimalproduct_price_unit_label', 0);			
		
		if ($WooDecimalProduct_Price_Unit_Label) {
			global $pagenow;

			if ($pagenow == 'edit.php') {
				$Product_ID = $product -> get_id();
				
				$Pice_Unit_Label = WooDecimalProduct_Get_PiceUnitLabel_by_ProductID ($Product_ID);
				
				if ($Pice_Unit_Label) {
					$price .= "<br>$Pice_Unit_Label";
					
					return $price;			
				}				
			}			
		}

		return $price;
	}

	/* WooCommerce Product Setup Page. | Вкладка "General"
	 * Добавляем опции: "Mинимальное / Максимальное кол-во Товара, Шаг изменения кол-ва и Количество по-умалчанию".
	 * Если не указано, то будет как в Глобальных Настройках.
	----------------------------------------------------------------- */		
	add_action ('woocommerce_product_options_general_product_data', 'WooDecimalProduct_Tab_General_add_Options');
	function WooDecimalProduct_Tab_General_add_Options() {	
		$WooDecimalProduct_Min_Quantity_Default    	= get_option ('woodecimalproduct_min_qnt_default', 1);  
		$WooDecimalProduct_Max_Quantity_Default    	= get_option ('woodecimalproduct_max_qnt_default', '');  
		$WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
		$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);		
		$WooDecimalProduct_Price_Unit_Label			= get_option ('woodecimalproduct_price_unit_label', 0);		

		$Product_ID = get_the_ID();
			
		$No_MaxEmpty = '---';
		$WooDecimalProduct_QuantityData = WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID, $No_MaxEmpty);

		$Min_Qnt 		= $WooDecimalProduct_QuantityData['min_qnt'];
		$Max_Qnt 		= $WooDecimalProduct_QuantityData['max_qnt'];
		$Def_Qnt 		= $WooDecimalProduct_QuantityData['def_qnt'];
		$Stp_Qnt 		= $WooDecimalProduct_QuantityData['stp_qnt'];				
		$QNT_Precision 	= $WooDecimalProduct_QuantityData['precision'];	

		$Product_Category_IDs = wc_get_product_term_ids ($Product_ID, 'product_cat');

		$Product_Price_Unit_Label = '';

		// Берем первую из Категорий если их несколько.
		foreach ($Product_Category_IDs as $Term_ID) {
			if ($Product_Price_Unit_Label == '') {
				$Term_Price_Unit = get_term_meta ($Term_ID, 'woodecimalproduct_term_price_unit', $single = true);

				if ($Term_Price_Unit) {
					$Product_Price_Unit_Label = $Term_Price_Unit;
				}		
			}
		}

		$Label_Price_Unit_Label = 'Price Unit-Label';

		if (! $WooDecimalProduct_Price_Unit_Label) {
			$Label_Price_Unit_Label = '<span style="color:red;" title="Disabled in Global Option.">Price Unit-Label</span>';
		}		
	
		echo '<div class="options_group">';
 		echo '<div style="margin-top: 10px; margin-left: 10px;"><span style="font-weight: bold;">Quantity Options</span><span style="margin-left: 10px;">(* If not specified, it will be as in <a target="_blank" href="/wp-admin/edit-tags.php?taxonomy=product_cat&post_type=product">Categories</a> / <a target="_blank" href="/wp-admin/edit.php?post_type=product&page=decimal-product-quantity-for-woocommerce/includes/options.php">Global Settings</a>)</span></div>';
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => 'woodecimalproduct_min_qnt', 
				'label'       => 'Minimum', 
				'placeholder' => $Min_Qnt,
				'desc_tip'    => 'true',
				'description' => "Set the Min of changing the quantity: 0.1 0.5 100 e.t.c (Default = $Min_Qnt)"
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          => 'woodecimalproduct_item_qnt', 
				'label'       => 'Default Set', 
				'placeholder' => $Def_Qnt,
				'desc_tip'    => 'true',
				'description' => "Set the Default quantity: 0.1 0.5 100 e.t.c (Default = $Def_Qnt)"
            )
		);		

		woocommerce_wp_text_input( 
			array( 
				'id'          => 'woodecimalproduct_step_qnt', 
				'label'       => 'Step change +/-', 
				'placeholder' => $Stp_Qnt,
				'desc_tip'    => 'true',
				'description' => "Set the Step of changing the quantity: 0.1 0.5 100 e.t.c (Default = $Stp_Qnt)"
            )
		);

		woocommerce_wp_text_input( 
			array( 
				'id'          => 'woodecimalproduct_max_qnt', 
				'label'       => 'Maximum', 
				'placeholder' => $Max_Qnt,
				'desc_tip'    => 'true',
				'description' => "Set the Max of changing the quantity: 0.1 0.5 100 e.t.c (or leave blank)"
			)
		);
		
		woocommerce_wp_text_input( 
			array( 
				'id'          	=> 'woodecimalproduct_pice_unit_label', 
				'label'       	=> $Label_Price_Unit_Label, 
				'placeholder' 	=> $Product_Price_Unit_Label,
				'desc_tip'    	=> 'true',
				'description' 	=> 'View Price Unit-Label. Sample: "Price per Kg." / "Price per Meter". Or leave blank to use Category value.'
			)
		);	

		woocommerce_wp_checkbox( 
			array( 
				'id'          	=> 'woodecimalproduct_pice_unit_disable', 
				'label'       	=> 'Disable Price Unit-Label', 
				'desc_tip'    	=> 'true',
				'description' 	=> 'Disable Price Unit-Label for this Product.'
			)
		);		
				
		echo '</div>';      	
	} 
	
	/* Сохраняем "Опции кол-ва Товара" для данного Товара.
	----------------------------------------------------------------- */	
	add_action ('woocommerce_process_product_meta', 'WooDecimalProduct_save_product_field_step_Qnt');	
	function WooDecimalProduct_save_product_field_step_Qnt ($post_id) {	
        $new_min_Qnt    		= isset($_POST['woodecimalproduct_min_qnt']) ? sanitize_text_field ($_POST['woodecimalproduct_min_qnt']): 1;
        $new_step_Qnt   		= isset($_POST['woodecimalproduct_step_qnt']) ? sanitize_text_field ($_POST['woodecimalproduct_step_qnt']): 1;  
		$new_dft_Qnt    		= isset($_POST['woodecimalproduct_item_qnt']) ? sanitize_text_field ($_POST['woodecimalproduct_item_qnt']): 1;
		$new_max_Qnt			= isset($_POST['woodecimalproduct_max_qnt']) ? sanitize_text_field ($_POST['woodecimalproduct_max_qnt']): '';
		$new_Pice_Unit_Label	= isset($_POST['woodecimalproduct_pice_unit_label']) ? sanitize_text_field ($_POST['woodecimalproduct_pice_unit_label']): '';
		$new_Pice_Unit_Disable	= isset($_POST['woodecimalproduct_pice_unit_disable']) ? 'yes': '';			
		
		update_post_meta ($post_id, 'woodecimalproduct_min_qnt', $new_min_Qnt);
		update_post_meta ($post_id, 'woodecimalproduct_step_qnt', $new_step_Qnt);
		update_post_meta ($post_id, 'woodecimalproduct_item_qnt', $new_dft_Qnt);	
		update_post_meta ($post_id, 'woodecimalproduct_max_qnt', $new_max_Qnt);		
		update_post_meta ($post_id, 'woodecimalproduct_pice_unit_label', $new_Pice_Unit_Label);	
		update_post_meta ($post_id, 'woodecimalproduct_pice_unit_disable', $new_Pice_Unit_Disable);	
	}	