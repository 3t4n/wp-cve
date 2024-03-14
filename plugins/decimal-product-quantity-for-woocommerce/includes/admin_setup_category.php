<?php
/*
* Decimal Product Quantity for WooCommerce
* Admin Product-Category Setup Page.
* admin_setup_category.php
*/

	/* DashBoard. WooCommerce. List Product-Categories. 
	 * Добавляем новые Колонки в Списке Категорий Товаров.
	----------------------------------------------------------------- */	
	add_filter ('manage_edit-product_cat_columns', 'WooDecimalProduct_filter_manage_edit_product_cat_columns');
	function WooDecimalProduct_filter_manage_edit_product_cat_columns ($Columns) {

			$New_Columns = array();
			
			foreach ($Columns as $column_name => $column_info) {
				$New_Columns [$column_name] = $column_info;
				
				if ($column_name == 'name' ) {
					$New_Columns['quantity'] = 'Quantity';
				}			
			}		
			
			return $New_Columns;	
		
		return $Columns;
	}
	
	/* DashBoard. WooCommerce. List Product-Categories.
	 * Заполняем новые Колонки в Списке Категорий Товаров.
	----------------------------------------------------------------- */
	add_action ('manage_product_cat_custom_column', 'WooDecimalProduct_action_manage_product_cat_custom_column', 10, 3);
	function WooDecimalProduct_action_manage_product_cat_custom_column ($columns, $column, $Term_ID) {
		if ($column == 'quantity') {
			$No_MaxEmpty = '---';

			$Term_QuantityData = WooDecimalProduct_Get_Term_QuantityData_by_TermID ($Term_ID, $No_MaxEmpty);

			if ($Term_QuantityData) {
				echo "Min: " .$Term_QuantityData['min_qnt'] ."<br>";
				echo "Max: " .$Term_QuantityData['max_qnt'] ."<br>";
				echo "Step: " .$Term_QuantityData['stp_qnt'] ."<br>";
				echo "Set: " .$Term_QuantityData['def_qnt'] ."<br>";
			}
		}		
	}	

	/* DashBoard. Products-Categories Setup Page. Read.
	 * Опция "Price Unit-Label"
	----------------------------------------------------------------- */
	add_action ('product_cat_add_form_fields', 'WooDecimalProduct_action_product_cat_edit_form_fields', 9999);
	add_action ('product_cat_edit_form_fields', 'WooDecimalProduct_action_product_cat_edit_form_fields', 9999);	
	function WooDecimalProduct_action_product_cat_edit_form_fields ($term) {
		$WooDecimalProduct_Min_Quantity_Default    	= get_option ('woodecimalproduct_min_qnt_default', 1);  
		$WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
		$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);
		$WooDecimalProduct_Max_Quantity_Default    	= get_option ('woodecimalproduct_max_qnt_default', '');
		$WooDecimalProduct_Price_Unit_Label			= get_option ('woodecimalproduct_price_unit_label', 0);
		
		$Style_and_Title = '';
		
		if (! $WooDecimalProduct_Price_Unit_Label) {
			$Style_and_Title = 'style= "color:red;" title="Disabled in Global Option."';
		}		
		
		if ($term == 'product_cat') {
			// Mode: "Add new category"
			ob_start();
				?>
				<div style='margin-top: 40px; margin-top: 40px; border-style: solid; border-width: thin; border-radius: 5px; padding: 5px; margin-bottom: 20px;'>
					Decimal Product Quantity:
					<p class="description">This Options will be shown for all Products in this Category.</p>
					<p class="description">But each Product can have its own Options.</p>
					
					<div style='margin-left: 20px; margin-bottom: 20px;'>
						<div class='form-field term-price_unit'>
							<label for='wdpq_term_price_unit' <?php echo $Style_and_Title; ?>>Price Unit-Label</label>
							<input type="text" name="wdpq_term_price_unit" id="wdpq_term_price_unit" value="">
							<p class="description">Sample: "Price per Kg." / "Price per Meter". Or leave blank.</p>
						</div>
						
						<div style='text-align: right; margin-right: 16px;'>
							<div class='form-field term-min-qnt' style='margin-bottom: 2px;'>
								<label for='wdpq_term_min_qnt' style='display: inline-block;'>Min Quantity:</label>
								<input type="text" name="wdpq_term_min_qnt" id="wdpq_term_min_qnt" style="margin-left: 5px; width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Min_Quantity_Default; ?>" value="">
							</div>
							
							<div class='form-field term-max-qnt' style='margin-top: 2px; margin-bottom: 2px;'>
								<label for='wdpq_term_max_qnt' style='display: inline-block;'>Max Quantity:</label>
								<input type="text" name="wdpq_term_max_qnt" id="wdpq_term_max_qnt" style="margin-left: 5px; width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Max_Quantity_Default; ?>" value="" >
							</div>

							<div class='form-field term-step-qnt' style='margin-top: 2px; margin-bottom: 2px;'>
								<label for='wdpq_term_step_qnt' style='display: inline-block;'>Step change Quantity:</label>
								<input type="text" name="wdpq_term_step_qnt" id="wdpq_term_step_qnt" style="margin-left: 5px; width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Step_Quantity_Default; ?>" value="" >
							</div>
							
							<div class='form-field term-set-qnt' style='margin-top: 2px;'>
								<label for='wdpq_term_set_qnt' style='display: inline-block;'>Default set Quantity:</label>
								<input type="text" name="wdpq_term_set_qnt" id="wdpq_term_set_qnt" style="margin-left: 5px; width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Item_Quantity_Default; ?>" value="" >						
							</div>									
						</div>
						<div>
							<p class="description">Sample (Min,Max,Step,Set): 1 or 0.1 or 0.25 or 1.5 etc.</p>
							<p class="description">or leave blank for <a target="_blank" href="/wp-admin/edit.php?post_type=product&page=decimal-product-quantity-for-woocommerce%2Fincludes%2Foptions.php">General Settings.</a></p>						
						</div>
					</div>
				</div>			
				<?php

			$contents = ob_get_contents();
			ob_end_clean();
			
			echo $contents;	
			
		} else {
			// Mode: "Edit category"
			$Term_ID = $term -> term_id;

			$Term_Price_Unit 	= get_term_meta ($Term_ID, 'woodecimalproduct_term_price_unit', $single = true);
			
			$Term_Min_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_min_qnt', $single = true);			
			$Term_Max_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_max_qnt', $single = true);
			$Term_Step_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_step_qnt', $single = true);
			$Term_Set_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_item_qnt', $single = true);
			
			ob_start();
				?>
				<tr class='form-field term-wdpq' style='border-style: solid; border-width: thin; border-bottom: none;'>
					<th scope='row'>
						Decimal Product Quantity:
					</th>
					<td>
						<p class="description">This Options will be shown for all Products in this Category.</p>
						<p class="description">But each Product can have its own Options.</p>					
					</td>
				</tr>

				<tr class='form-field term-price_unit' style='border-style: solid; border-width: thin; border-top: none; border-bottom: none;'>
					<th scope='row' style="text-align: right;">
						Price Unit-Label
					</th>
					<td>
						<input type="text" name="wdpq_term_price_unit" id="wdpq_term_price_unit" value="<?php echo $Term_Price_Unit; ?>">
						<p class="description">Sample: "Price per Kg." / "Price per Meter". Or leave blank.</p>						
					</td>
				</tr>	

				<tr class='form-field term-min-qnt' style='border-style: solid; border-width: thin; border-top: none; border-bottom: none;'>
					<th scope='row' style="text-align: right; padding-top: 5px; padding-bottom: 2px;">
						Min Quantity:
					</th>
					<td style="padding-top: 2px; padding-bottom: 2px;">
						<input type="text" name="wdpq_term_min_qnt" id="wdpq_term_min_qnt" style="width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Min_Quantity_Default; ?>" value="<?php echo $Term_Min_Qnt; ?>">
					</td>
				</tr>	

				<tr class='form-field term-max-qnt'style='border-style: solid; border-width: thin; border-top: none; border-bottom: none;'>
					<th scope='row' style="text-align: right; padding-top: 5px; padding-bottom: 2px;">
						Max Quantity:
					</th>
					<td style="padding-top: 2px; padding-bottom: 2px;">
						<input type="text" name="wdpq_term_max_qnt" id="wdpq_term_max_qnt" style="width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Max_Quantity_Default; ?>" value="<?php echo $Term_Max_Qnt; ?>">
					</td>
				</tr>	

				<tr class='form-field term-step-qnt' style='border-style: solid; border-width: thin; border-top: none; border-bottom: none;'>
					<th scope='row' style="text-align: right; padding-top: 5px; padding-bottom: 2px;">
						Step change Quantity:
					</th>
					<td style="padding-top: 2px; padding-bottom: 2px">
						<input type="text" name="wdpq_term_step_qnt" id="wdpq_term_step_qnt" style="width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Step_Quantity_Default; ?>" value="<?php echo $Term_Step_Qnt; ?>">
					</td>
				</tr>	

				<tr class='form-field term-set-qnt' style='border-style: solid; border-width: thin; border-top: none; border-bottom: none;'>
					<th scope='row' style="text-align: right; padding-top: 5px; padding-bottom: 2px;">
						Default set Quantity:
					</th>
					<td style="padding-top: 2px; padding-bottom: 2px;">
						<input type="text" name="wdpq_term_set_qnt" id="wdpq_term_set_qnt" style="width: 64px; text-align: center;" placeholder="<?php echo $WooDecimalProduct_Item_Quantity_Default; ?>" value="<?php echo $Term_Set_Qnt; ?>">
					</td>
				</tr>

				<tr class='form-field term-wdpq' style='border-style: solid; border-width: thin; border-top: none;'>
					<th scope='row' style="text-align: right; padding-top: 5px; padding-bottom: 2px;">
						*
					</th>
					<td style="padding-top: 2px; padding-bottom: 2px;">
						<p class="description">Sample (Min,Max,Step,Set): 1 or 0.1 or 0.25 or 1.5 etc.</p>
						<p class="description">or leave blank for <a target="_blank" href="/wp-admin/edit.php?post_type=product&page=decimal-product-quantity-for-woocommerce%2Fincludes%2Foptions.php">General Settings.</a></p>
					</td>
				</tr>				
				
				<?php

			$contents = ob_get_contents();
			ob_end_clean();
			
			echo $contents;	
		}
	}
	
	/* DashBoard. Products-Categories Setup Page. Save.
	 * Опция "Price Unit-Label"
	----------------------------------------------------------------- */
	add_action ('create_product_cat', 'WooDecimalProduct_action_edited_product_cat');
	add_action ('edited_product_cat', 'WooDecimalProduct_action_edited_product_cat');
	function WooDecimalProduct_action_edited_product_cat ($Term_ID) {
		$Term_Price_Unit 	= isset ($_POST['wdpq_term_price_unit'])? $_POST['wdpq_term_price_unit'] : '';
		$Term_Min_Qnt 		= isset ($_POST['wdpq_term_min_qnt'])? $_POST['wdpq_term_min_qnt'] : '';
		$Term_Max_Qnt 		= isset ($_POST['wdpq_term_max_qnt'])? $_POST['wdpq_term_max_qnt'] : '';
		$Term_Step_Qnt 		= isset ($_POST['wdpq_term_step_qnt'])? $_POST['wdpq_term_step_qnt'] : '';
		$Term_Set_Qnt 		= isset ($_POST['wdpq_term_set_qnt'])? $_POST['wdpq_term_set_qnt'] : '';
		
		update_term_meta ($Term_ID, 'woodecimalproduct_term_price_unit', $Term_Price_Unit);
		update_term_meta ($Term_ID, 'woodecimalproduct_term_min_qnt', $Term_Min_Qnt);
		update_term_meta ($Term_ID, 'woodecimalproduct_term_max_qnt', $Term_Max_Qnt);
		update_term_meta ($Term_ID, 'woodecimalproduct_term_step_qnt', $Term_Step_Qnt);
		update_term_meta ($Term_ID, 'woodecimalproduct_term_item_qnt', $Term_Set_Qnt);
	}