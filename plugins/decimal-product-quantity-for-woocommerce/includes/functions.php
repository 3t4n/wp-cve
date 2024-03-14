<?php
/*
* Decimal Product Quantity for WooCommerce
* functions.php
*/

	/* Check PRO Plugin Installed
	----------------------------------------------------------------- */		
	function WooDecimalProduct_Check_Plugin_Installed ($Plugin_Slug = null) {
		$Result = false;
		
		if ($Plugin_Slug) {
			if (! function_exists ('get_plugins')) {
				require_once ABSPATH .'wp-admin/includes/plugin.php';
			}
			
			$Plugins = get_plugins();
			
			foreach ($Plugins as $Plugin) {
				$Plugin_TextDomain = $Plugin['TextDomain'];
				if ($Plugin_TextDomain == $Plugin_Slug) {
					$Result = true;
				}
			}			
		}	
		
		return $Result;
	}
	
    /* Проверка успешного Обновления Пред.Версий.
    ----------------------------------------------------------------- */ 	
	function WooDecimalProduct_Check_Updated () {
		// Проверка успешной конвертации Названий Мета-Полей для Товаров.
			// woodecimalproduct_min_qnt_default  -> woodecimalproduct_min_qnt
			// woodecimalproduct_step_qnt_product -> woodecimalproduct_step_qnt
			// woodecimalproduct_item_qnt_default -> woodecimalproduct_item_qnt
		$WooDecimalProduct_Updated_PoductMeta = get_option ('woodecimalproduct_updated_poductmeta', false); 
		
		if (!$WooDecimalProduct_Updated_PoductMeta) {
			global $wpdb;
			$PostMeta_Table = $wpdb->prefix .'postmeta';
			
			$Query = "SELECT * FROM $PostMeta_Table WHERE meta_key LIKE 'woodecimalproduct_%'";			
			$Result = $wpdb->get_results ($wpdb->prepare($Query, true));
			
			if ($Result) {
				foreach ($Result as $Meta) {
					$post_id 	= $Meta->post_id;
					$meta_key 	= $Meta->meta_key;
					$meta_value = $Meta->meta_value;
					
					if ($meta_value) {
						if ($meta_key == 'woodecimalproduct_min_qnt_default' || $meta_key == 'woodecimalproduct_step_qnt_product' || $meta_key == 'woodecimalproduct_item_qnt_default') {
							if ($meta_key == 'woodecimalproduct_min_qnt_default') {
								$meta_key_new = 'woodecimalproduct_min_qnt';
							}
							
							if ($meta_key == 'woodecimalproduct_step_qnt_product') {
								$meta_key_new = 'woodecimalproduct_step_qnt';
							}

							if ($meta_key == 'woodecimalproduct_item_qnt_default') {
								$meta_key_new = 'woodecimalproduct_item_qnt';
							}

							// Уже может быть сохранено Новое Значение обновленной пред. версии
							$Meta_Key_New_Exist = get_post_meta ($post_id, $meta_key_new, true);
							
							if (!$Meta_Key_New_Exist) {
								update_post_meta($post_id, $meta_key_new, $meta_value);	
							}
							
							delete_post_meta($post_id, $meta_key);							
						}
					} else {
						delete_post_meta($post_id, $meta_key);
					}
				}
			}
			
			update_option('woodecimalproduct_updated_poductmeta', true);
		}
	}	
	
	/* Минимальное / Максимально кол-во выбора Товара, Шаг, Значение по-Умолчанию, Максимально-Необходимая Точность
	----------------------------------------------------------------- */	
	function WooDecimalProduct_Get_QuantityData_by_ProductID ($Product_ID, $No_MaxEmpty = '') {
		$WooDecimalProduct_Min_Quantity_Default    	= get_option ('woodecimalproduct_min_qnt_default', 1);  
		$WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
		$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);
		$WooDecimalProduct_Max_Quantity_Default    	= get_option ('woodecimalproduct_max_qnt_default', '');  
		$WooDecimalProduct_Auto_Correction_Quantity	= get_option ('woodecimalproduct_auto_correction_qnt', 1);			
		
		$WooDecimalProduct_QuantityData['product_id'] = 0;
		$WooDecimalProduct_QuantityData['min_qnt'] = 1;
		$WooDecimalProduct_QuantityData['max_qnt'] = '';
		$WooDecimalProduct_QuantityData['def_qnt'] = 1;
		$WooDecimalProduct_QuantityData['stp_qnt'] = 1;
		
		if ($Product_ID) {
			$WooDecimalProduct_QuantityData['product_id'] = $Product_ID;
			
			$Term_QuantityData = WooDecimalProduct_Get_Term_QuantityData_by_ProductID ($Product_ID);
			
			$Min_Qnt = get_post_meta ($Product_ID, 'woodecimalproduct_min_qnt', true);	// Минимальное Количество для данного Товара	
			$Max_Qnt = get_post_meta ($Product_ID, 'woodecimalproduct_max_qnt', true);  // Максимальное Количество для данного Товара	
			$Def_Qnt = get_post_meta ($Product_ID, 'woodecimalproduct_item_qnt', true);	// Default_Qnt для данного Товара
			$Stp_Qnt = get_post_meta ($Product_ID, 'woodecimalproduct_step_qnt', true);	// Шаг изменения для данного Товара		

			if (!$Min_Qnt) {
				$Min_Qnt = isset($Term_QuantityData['min_qnt']) ? $Term_QuantityData['min_qnt'] : $WooDecimalProduct_Min_Quantity_Default;			
			}
			
			if (!$Max_Qnt) {
				$Max_Qnt = isset($Term_QuantityData['max_qnt']) ? $Term_QuantityData['max_qnt'] : $WooDecimalProduct_Max_Quantity_Default;							
			}
			if ($Max_Qnt == '') {
				$Max_Qnt = $No_MaxEmpty; // '-1' for Unlimited
			}			

			if (!$Def_Qnt) {
				$Def_Qnt = isset($Term_QuantityData['def_qnt']) ? $Term_QuantityData['def_qnt'] : $WooDecimalProduct_Item_Quantity_Default;							
			}
			
			if (!$Stp_Qnt) {
				$Stp_Qnt = isset($Term_QuantityData['stp_qnt']) ? $Term_QuantityData['stp_qnt'] : $WooDecimalProduct_Step_Quantity_Default;							
			}		
			
			if ($Min_Qnt && $Def_Qnt) {
				if ($Def_Qnt < $Min_Qnt) {
					$Def_Qnt = $Min_Qnt;
				}
			}			
			
			$WooDecimalProduct_QuantityData['min_qnt'] = $Min_Qnt;
			$WooDecimalProduct_QuantityData['max_qnt'] = $Max_Qnt;
			$WooDecimalProduct_QuantityData['def_qnt'] = $Def_Qnt;
			$WooDecimalProduct_QuantityData['stp_qnt'] = $Stp_Qnt;
			
			// Precision
			$Locale_Info = localeconv();
			$Locale_Delimiter = $Locale_Info['decimal_point'];
			
			$Min_QNT_Precision = strlen (substr (strrchr ($Min_Qnt, $Locale_Delimiter), 1));
			$Def_QNT_Precision = strlen (substr (strrchr ($Def_Qnt, $Locale_Delimiter), 1));
			$Stp_QNT_Precision = strlen (substr (strrchr ($Stp_Qnt, $Locale_Delimiter), 1));
			
			$QNT_Precision = max (array ($Min_QNT_Precision, $Def_QNT_Precision, $Stp_QNT_Precision));

			$WooDecimalProduct_QuantityData['precision'] = $QNT_Precision;				
			
			return $WooDecimalProduct_QuantityData;
		}
		
		return $WooDecimalProduct_QuantityData;
	}	
	
	/* Нормализуем дробное число с учетом настроек разделителя
	----------------------------------------------------------------- */	
	function WooDecimalProduct_Normalize_Number ($Number) {
		$Locale_Info = localeconv();
		$Locale_Delimiter = $Locale_Info['decimal_point'];
		
		$Number = str_replace ('.', $Locale_Delimiter, $Number);
		$Number = str_replace (',', $Locale_Delimiter, $Number);
		
		return $Number;
	}	
	
	/* Добавляем новое описание Ошибки.
	----------------------------------------------------------------- */	
	function WooDecimalProduct_Add_Errors_Msg ($Error_Msg, $Errors_Msg) {
		$Errors_Msg .= "<li>$Error_Msg</li>";
		
		return $Errors_Msg;
	}
	
	/* Получаем Pice_Unit_Label Товара.
	----------------------------------------------------------------- */
	function WooDecimalProduct_Get_PiceUnitLabel_by_ProductID ($Product_ID) {
		$Pice_Unit_Label = '<div class="woodecimalproduct_pice_unit_label" style="min-height: 12px;"></div>';
		
		$Product_Pice_Unit_Disable = get_post_meta ($Product_ID, 'woodecimalproduct_pice_unit_disable', true);
		
		if (! $Product_Pice_Unit_Disable) {					
			// Берем Значение из Товара
			$Product_Pice_Unit_Label = get_post_meta ($Product_ID, 'woodecimalproduct_pice_unit_label', true);				
			
			if ($Product_Pice_Unit_Label) {
				$Pice_Unit_Label = '<div class="woodecimalproduct_pice_unit_label" style="min-height: 12px;">' .$Product_Pice_Unit_Label .'</div>';
			} else {
				// Берем Значение из Категории Товара				
				$Term_QuantityData = WooDecimalProduct_Get_Term_QuantityData_by_ProductID ($Product_ID);

				if ($Term_QuantityData) {
					$Pice_Unit_Label = $Term_QuantityData['price_unit'];
					
					if ($Pice_Unit_Label) {
						$Pice_Unit_Label = '<div class="woodecimalproduct_pice_unit_label" style="min-height: 12px;">' .$Pice_Unit_Label .'</div>';
					}	
				}		
			}			
		}
		
		return $Pice_Unit_Label;
	}

	/* Получаем QuantityData Категории Товаров по Term_ID.
	----------------------------------------------------------------- */
	function WooDecimalProduct_Get_Term_QuantityData_by_TermID ($Term_ID, $No_MaxEmpty = '') {
		$WooDecimalProduct_Min_Quantity_Default 	= get_option ('woodecimalproduct_min_qnt_default', 1);  
		$WooDecimalProduct_Max_Quantity_Default 	= get_option ('woodecimalproduct_max_qnt_default', '');  
		$WooDecimalProduct_Step_Quantity_Default   	= get_option ('woodecimalproduct_step_qnt_default', 1); 
		$WooDecimalProduct_Item_Quantity_Default   	= get_option ('woodecimalproduct_item_qnt_default', 1);	
		
		$Term_Min_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_min_qnt', $single = true);			
		$Term_Max_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_max_qnt', $single = true);
		$Term_Step_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_step_qnt', $single = true);
		$Term_Set_Qnt 		= get_term_meta ($Term_ID, 'woodecimalproduct_term_item_qnt', $single = true);
		$Term_Price_Unit 	= get_term_meta ($Term_ID, 'woodecimalproduct_term_price_unit', $single = true);
		
		if (! $Term_Min_Qnt) {
			$Term_Min_Qnt = $WooDecimalProduct_Min_Quantity_Default;
		}
		
		if (! $Term_Max_Qnt) {
			$Term_Max_Qnt = $WooDecimalProduct_Max_Quantity_Default;
		}
		if ($Term_Max_Qnt == '') {
			$Term_Max_Qnt = $No_MaxEmpty; // '-1' for Unlimited
		}		

		if (! $Term_Step_Qnt) {
			$Term_Step_Qnt = $WooDecimalProduct_Step_Quantity_Default;
		}

		if (! $Term_Set_Qnt) {
			$Term_Set_Qnt = $WooDecimalProduct_Item_Quantity_Default;
		}

		$WooDecimalProduct_QuantityData['min_qnt'] 		= $Term_Min_Qnt;
		$WooDecimalProduct_QuantityData['max_qnt'] 		= $Term_Max_Qnt;
		$WooDecimalProduct_QuantityData['def_qnt'] 		= $Term_Set_Qnt;
		$WooDecimalProduct_QuantityData['stp_qnt'] 		= $Term_Step_Qnt;
		$WooDecimalProduct_QuantityData['price_unit'] 	= $Term_Price_Unit;	

		return $WooDecimalProduct_QuantityData;
	}
	
	/* Получаем QuantityData Категории Товаров по Product_ID.
	 * Категорий может быть несколько. Выбираем ту, в которой имеется Pice_Unit_Label
	----------------------------------------------------------------- */
	function WooDecimalProduct_Get_Term_QuantityData_by_ProductID ($Product_ID) {
		$Term_QuantityData = array();
		
		$Pice_Unit_Label = '';
		
		$Product_Category_IDs = wc_get_product_term_ids ($Product_ID, 'product_cat');
		
		// Берем первую из Категорий если их несколько - в которой имеется Pice_Unit_Label.
		foreach ($Product_Category_IDs as $Term_ID) {
			if ($Pice_Unit_Label == '') {
				$Term_Price_Unit = get_term_meta ($Term_ID, 'woodecimalproduct_term_price_unit', $single = true);

				if ($Term_Price_Unit) {
					$Pice_Unit_Label = $Term_Price_Unit;
					
					$Term_QuantityData = WooDecimalProduct_Get_Term_QuantityData_by_TermID ($Term_ID);
				}		
			}
		}

		return $Term_QuantityData;		
	}
	