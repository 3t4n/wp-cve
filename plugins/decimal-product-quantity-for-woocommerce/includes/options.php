<?php
/*
* WPGear. Decimal Product Quantity for WooCommerce
* options.php
*/
	
    if (!current_user_can('edit_dashboard')) {
        return;
    }	
	
	$WooDecimalProduct_Min_Quantity_Default    	= get_option ('woodecimalproduct_min_qnt_default', 1);  
	$WooDecimalProduct_Max_Quantity_Default    	= get_option ('woodecimalproduct_max_qnt_default', '');  
    $WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
	$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);
	
	$WooDecimalProduct_Auto_Correction_Quantity	= get_option ('woodecimalproduct_auto_correction_qnt', 1);
	$WooDecimalProduct_AJAX_Cart_Update			= get_option ('woodecimalproduct_ajax_cart_update', 0);	
	$WooDecimalProduct_Price_Unit_Label			= get_option ('woodecimalproduct_price_unit_label', 0);	
	
	$WooDecimalProduct_ConsoleLog_Debuging		= get_option ('woodecimalproduct_debug_log', 0);
	$WooDecimalProduct_Uninstall_Del_MetaData 	= get_option ('woodecimalproduct_uninstall_del_metadata', 0);
	
	$Action = isset($_REQUEST['action']) ? sanitize_text_field ($_REQUEST['action']) : null;
	
	$Errors_Msg = '';
	
	if ($Action == 'Update') {
		$New_WDPQ_Min	= isset($_REQUEST['wdpq_min_qnt_default']) ? sanitize_text_field ($_REQUEST['wdpq_min_qnt_default']) : 1;
		$New_WDPQ_Max	= isset($_REQUEST['wdpq_max_qnt_default']) ? sanitize_text_field ($_REQUEST['wdpq_max_qnt_default']) : '';
		$New_WDPQ_Step	= isset($_REQUEST['wdpq_step_qnt_default']) ? sanitize_text_field ($_REQUEST['wdpq_step_qnt_default']) : 1;
		$New_WDPQ_Set	= isset($_REQUEST['wdpq_set_qnt_default']) ? sanitize_text_field ($_REQUEST['wdpq_set_qnt_default']) : 1;
		
		$New_WDPQ_Auto_Correction 			= isset($_REQUEST['wdpq_auto_correction']) ? 1 : 0;
		$New_WDPQ_AJAX_Cart_Update 			= isset($_REQUEST['wdpq_ajax_cart_update']) ? 1 : 0;
		$New_WDPQ_Price_Unit_Label 			= isset($_REQUEST['wdpq_pice_unit_label']) ? 1 : 0;
		
		$New_WDPQ_ConsoleLog_Debuging 		= isset($_REQUEST['wdpq_debug_log']) ? 1 : 0;
		$New_WDPQ_Uninstall_Del_MetaData	= isset($_REQUEST['wdpq_uninstall_del']) ? 1 : 0;

		$New_WDPQ_Min 	= WooDecimalProduct_Normalize_Number ($New_WDPQ_Min);
		$New_WDPQ_Max 	= WooDecimalProduct_Normalize_Number ($New_WDPQ_Max);
		$New_WDPQ_Step 	= WooDecimalProduct_Normalize_Number ($New_WDPQ_Step);
		$New_WDPQ_Set 	= WooDecimalProduct_Normalize_Number ($New_WDPQ_Set);	
		
		// Проверка Значений
		if (! is_numeric ($New_WDPQ_Min)) {						
			$Error_Msg 	= 'Min Quantity (' .$New_WDPQ_Min .') - Not a Valid Number. Set = 1';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Min = 1;
		}
		if (! is_numeric ($New_WDPQ_Max)) {
			if ($New_WDPQ_Max != '') {
				$Error_Msg 	= 'Max Quantity (' .$New_WDPQ_Max .') - Not a Valid Number. Set = empty';
				$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
				
				$New_WDPQ_Max = '';
			}
		}
		if (! is_numeric ($New_WDPQ_Step)) {
			$Error_Msg 	= 'Step Quantity (' .$New_WDPQ_Step .') - Not a Valid Number. Set = 1';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Step = 1;
		}
		if (! is_numeric ($New_WDPQ_Set)) {
			$Error_Msg 	= 'Default Set Quantity (' .$New_WDPQ_Set .') - Not a Valid Number. Set = 1';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Set = 1;
		}	

		// Проверка взаимосвязей.
		if ($New_WDPQ_Set < $New_WDPQ_Min) {
			$Error_Msg 	= 'Default Set Quantity (' .$New_WDPQ_Set .') < Min Quantity (' .$New_WDPQ_Min .'). Set = Min';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Set = $New_WDPQ_Min;
		}
		
		if ($New_WDPQ_Max && $New_WDPQ_Set > $New_WDPQ_Max) {
			$Error_Msg 	= 'Default Set Quantity (' .$New_WDPQ_Set .')  > Max Quantity (' .$New_WDPQ_Max .'). Set = Max';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Set = $New_WDPQ_Max;
		}	
		
		if ($New_WDPQ_Max && $New_WDPQ_Step > $New_WDPQ_Max) {
			$Error_Msg 	= 'Step Quantity (' .$New_WDPQ_Step .')  > Max Quantity (' .$New_WDPQ_Max .'). Set = Default';
			$Errors_Msg = WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg);
			
			$New_WDPQ_Step = $New_WDPQ_Set;
		}		
		
		// Обновление Настроек.
		if ($New_WDPQ_Min != $WooDecimalProduct_Min_Quantity_Default) {
			$WooDecimalProduct_Min_Quantity_Default = $New_WDPQ_Min;
			update_option('woodecimalproduct_min_qnt_default', $WooDecimalProduct_Min_Quantity_Default);
		}		
		
		if ($New_WDPQ_Max != $WooDecimalProduct_Max_Quantity_Default) {
			$WooDecimalProduct_Max_Quantity_Default = $New_WDPQ_Max;
			update_option('woodecimalproduct_max_qnt_default', $WooDecimalProduct_Max_Quantity_Default);
		}	
			
		if ($New_WDPQ_Step != $WooDecimalProduct_Step_Quantity_Default) {
			$WooDecimalProduct_Step_Quantity_Default = $New_WDPQ_Step;
			update_option('woodecimalproduct_step_qnt_default', $WooDecimalProduct_Step_Quantity_Default);
		}
		
		if ($New_WDPQ_Set != $WooDecimalProduct_Item_Quantity_Default) {
			$WooDecimalProduct_Item_Quantity_Default = $New_WDPQ_Set;
			update_option('woodecimalproduct_item_qnt_default', $WooDecimalProduct_Item_Quantity_Default);
		}		

		if ($New_WDPQ_Auto_Correction != $WooDecimalProduct_Auto_Correction_Quantity) {
			$WooDecimalProduct_Auto_Correction_Quantity = $New_WDPQ_Auto_Correction;
			update_option('woodecimalproduct_auto_correction_qnt', $WooDecimalProduct_Auto_Correction_Quantity);
		}
		
		if ($New_WDPQ_AJAX_Cart_Update != $WooDecimalProduct_AJAX_Cart_Update) {
			$WooDecimalProduct_AJAX_Cart_Update = $New_WDPQ_AJAX_Cart_Update;
			update_option('woodecimalproduct_ajax_cart_update', $WooDecimalProduct_AJAX_Cart_Update);
		}

		if ($New_WDPQ_Price_Unit_Label != $WooDecimalProduct_Price_Unit_Label) {
			$WooDecimalProduct_Price_Unit_Label = $New_WDPQ_Price_Unit_Label;
			update_option('woodecimalproduct_price_unit_label', $WooDecimalProduct_Price_Unit_Label);
		}		

		if ($New_WDPQ_ConsoleLog_Debuging != $WooDecimalProduct_ConsoleLog_Debuging) {
			$WooDecimalProduct_ConsoleLog_Debuging = $New_WDPQ_ConsoleLog_Debuging;
			update_option('woodecimalproduct_debug_log', $WooDecimalProduct_ConsoleLog_Debuging);
		}

		if ($New_WDPQ_Uninstall_Del_MetaData != $WooDecimalProduct_Uninstall_Del_MetaData) {
			$WooDecimalProduct_Uninstall_Del_MetaData = $New_WDPQ_Uninstall_Del_MetaData;
			update_option('woodecimalproduct_uninstall_del_metadata', $WooDecimalProduct_Uninstall_Del_MetaData);
		}		
	}	
?>
	<div class="wrap">
		<h2>Decimal Product Quantity for WooCommerce.</h2>
		<hr>	
		
		<?php
		if ($Errors_Msg != '') {
			echo "<div style='margin-bottom: 20px; border-style: solid; border-width: 1px; border-radius: 5px; padding: 10px; background: white; border-color: grey;'>
					Warning!
					<ul style='color: red; list-style-type: circle; margin-left: 20px;'>						
						$Errors_Msg
					</ul>
					Check this Settings.
				</div>";
		}
		?>
		
		<div class="wdpq_options_box">
			<div>
				* Each Product can set a custom value.
			</div>	
			
			<form name="form_wdpq_Options" method="post" style="margin-top: 20px;">
				<div style="margin-top: 10px; margin-left: 38px;">
					<label for="wdpq_min_qnt_default" title="How min-much quantity of product to cart">Min Quantity: </label>
					<input id="wdpq_min_qnt_default" name="wdpq_min_qnt_default" type="text" style="margin-left: 51px; width: 64px; text-align: center;" value="<?php echo $WooDecimalProduct_Min_Quantity_Default; ?>">
					1 or 0.1 or 0.25 or 1.5 etc.
				</div>	

				<div style="margin-top: 10px; margin-left: 38px;">
					<label for="wdpq_max_qnt_default" title="How max-much quantity of product to cart">Max Quantity: </label>
					<input id="wdpq_max_qnt_default" name="wdpq_max_qnt_default" type="text" style="margin-left: 48px; width: 64px; text-align: center;" value="<?php echo $WooDecimalProduct_Max_Quantity_Default; ?>">
					1 or 0.1 or 0.25 or 1.5 etc. Or leave blank
				</div>
				
				<div style="margin-top: 10px; margin-left: 38px;">
					<label for="wdpq_step_qnt_default" title="How much to increase or decrease the quantity of product to cart">Step change Quantity: </label>
					<input id="wdpq_step_qnt_default" name="wdpq_step_qnt_default" type="text" style="width: 64px; text-align: center;" value="<?php echo $WooDecimalProduct_Step_Quantity_Default; ?>">
					1 or 0.1 or 0.25 or 1.5 etc.
				</div>

				<div style="margin-top: 10px; margin-left: 38px;">
					<label for="wdpq_set_qnt_default" title="How much default quantity of product to cart">Default set Quantity: </label>
					<input id="wdpq_set_qnt_default" name="wdpq_set_qnt_default" type="text" style="margin-left: 11px; width: 64px; text-align: center;" value="<?php echo $WooDecimalProduct_Item_Quantity_Default; ?>">
					1 or 0.1 or 0.25 or 1.5 etc.
				</div>				

				<div style="margin-top: 40px; margin-left: 38px;">
					<input id="wdpq_auto_correction" name="wdpq_auto_correction" type="checkbox" <?php if($WooDecimalProduct_Auto_Correction_Quantity) {echo 'checked';} ?>>
					Auto correction "No valid Value" customer enters to nearest valid value Quantity.
				</div>
				
				<div style="margin-top: 10px; margin-left: 38px;">
					<input id="wdpq_ajax_cart_update" name="wdpq_ajax_cart_update" type="checkbox" <?php if($WooDecimalProduct_AJAX_Cart_Update) {echo 'checked';} ?>>
					Auto update Cart if change Quantity (AJAX Cart Update).
				</div>	

				<div style="margin-top: 10px; margin-left: 38px;">
					<input id="wdpq_pice_unit_label" name="wdpq_pice_unit_label" type="checkbox" <?php if($WooDecimalProduct_Price_Unit_Label) {echo 'checked';} ?>>
					Enable "Price Unit-Label".
				</div>				
				
				<div style="margin-top: 20px; margin-left: 38px;">
					<input id="wdpq_debug_log" name="wdpq_debug_log" type="checkbox" <?php if($WooDecimalProduct_ConsoleLog_Debuging) {echo 'checked';} ?>>
					View Debug info in Browser Console.
				</div>
				
				<div style="margin-top: 10px; margin-left: 38px;">
					<input id="wdpq_uninstall_del" name="wdpq_uninstall_del" type="checkbox" <?php if($WooDecimalProduct_Uninstall_Del_MetaData) {echo 'checked';} ?>>
					Delete Quantity-MetaData with Uninstall Plugin.
				</div>				

				<hr>				

				<div style="margin-top: 10px; margin-bottom: 5px; text-align: right;">
					<span id="save_options_processing" style="display: none; margin-right: 5px;">
						<i class="fa fa-refresh fa-spin fa-fw fa-2x" aria-hidden="true" style="vertical-align: baseline;"></i>
					</span>
					<input id="btn_options_save" type="submit" class="button button-primary" style="margin-right: 5px;" value="Save">
				</div>
				<input id="action" name="action" type="hidden" value="Update">
			</form>
		</div>		
	</DIV>