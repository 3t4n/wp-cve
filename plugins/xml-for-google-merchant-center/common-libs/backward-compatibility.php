<?php if (!defined('ABSPATH')) {exit;}
/*
Version: 1.0.0
Date: 09-01-2022
Author: Maxim Glazunov
Author URI: https://icopydoc.ru 
License: GPLv2
Description: This code helps ensure backward compatibility with older versions of the plugin.
*/

/**
* @since 1.0.0
*
* @return string/NULL
*
* Возвращает версию Woocommerce
*/ 
define('xfgmc_VER', '2.7.0'); // для совместимости со старыми прошками
$xfgmc_keeplogs = xfgmc_optionGET('xfgmc_keeplogs');
define('xfgmc_KEEPLOGS', $xfgmc_keeplogs);
/**
* @since 1.0.0
*
* @param string $text (require)
* @param string $i (require)
* 
* @return void
* Записывает файл логов /wp-content/uploads/xfgmc/xfgmc.log
*/
function xfgmc_error_log($text, $i) {	
	if (xfgmc_KEEPLOGS !== 'on') {return;}
	$upload_dir = (object)wp_get_upload_dir();
	$name_dir = $upload_dir->basedir."/xfgmc";
	// подготовим массив для записи в файл логов
	if (is_array($text)) {$r = xfgmc_array_to_log($text); unset($text); $text = $r;}
	if (is_dir($name_dir)) {
		$filename = $name_dir.'/xfgmc.log';
		file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);
	} else {
		if (!mkdir($name_dir)) {
			error_log('Нет папки xfgmc! И создать не вышло! $name_dir ='.$name_dir.'; Файл: functions.php; Строка: '.__LINE__, 0);
		} else {
			error_log('Создали папку xfgmc!; Файл: functions.php; Строка: '.__LINE__, 0);
			$filename = $name_dir.'/xfgmc.log';
			file_put_contents($filename, '['.date('Y-m-d H:i:s').'] '.$text.PHP_EOL, FILE_APPEND);
		}
	} 
	return;
}
/**
* @since 1.0.0
* 
* @param string $text (require)
* @param int $i (not require)
* @param string $res (not require)
*
* @return string
* Позволяте писать в логи массив /wp-content/uploads/xfgmc/xfgmc.log
*/
function xfgmc_array_to_log($text, $i=0, $res = '') {
	$tab = ''; for ($x = 0; $x<$i; $x++) {$tab = '---'.$tab;}
	if (is_array($text)) { 
		$i++;
		foreach ($text as $key => $value) {
			if (is_array($value)) {	// массив
				$res .= PHP_EOL .$tab."[$key] => (".gettype($value).")";
				$res .= $tab.xfgmc_array_to_log($value, $i);
			} else { // не массив
				$res .= PHP_EOL .$tab."[$key] => (".gettype($value).")". $value;
			}
		}
	} else {
		$res .= PHP_EOL .$tab.$text;
	}
	return $res;
}
function xfgmc_add_settings_arr($allNumFeed) {
	$numFeed = '1';
	for ($i = 1; $i<$allNumFeed+1; $i++) {	 
	   wp_clear_scheduled_hook('xfgmc_cron_period', array($numFeed));
	   wp_clear_scheduled_hook('xfgmc_cron_sborki', array($numFeed));
	   $numFeed++;
	}
 
	$xfgmc_settings_arr = array();
	$numFeed = '1';  
	for ($i = 1; $i<$allNumFeed+1; $i++) { 
//		$xfgmc_settings_arr[$numFeed]['xfgmc_status_sborki'] = xfgmc_optionGET('xfgmc_status_sborki', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_date_sborki'] = xfgmc_optionGET('xfgmc_date_sborki', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_date_sborki_end'] = xfgmc_optionGET('xfgmc_date_sborki_end', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_file_url'] = xfgmc_optionGET('xfgmc_file_url', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_file_file'] = xfgmc_optionGET('xfgmc_file_file', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_errors'] = xfgmc_optionGET('xfgmc_errors', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_status_cron'] = xfgmc_optionGET('xfgmc_status_cron', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_date_save_set'] = xfgmc_optionGET('xfgmc_date_save_set', $numFeed, 'for_update_option');

		$xfgmc_settings_arr[$numFeed]['xfgmc_run_cron'] = xfgmc_optionGET('xfgmc_run_cron', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_ufup'] = xfgmc_optionGET('xfgmc_ufup', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_feed_assignment'] = xfgmc_optionGET('xfgmc_feed_assignment', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_adapt_facebook'] = xfgmc_optionGET('xfgmc_adapt_facebook', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_whot_export'] = xfgmc_optionGET('xfgmc_whot_export', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_desc'] = xfgmc_optionGET('xfgmc_desc', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_the_content'] = xfgmc_optionGET('xfgmc_the_content', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_var_desc_priority'] = xfgmc_optionGET('xfgmc_var_desc_priority', $numFeed, 'for_update_option');

		$xfgmc_settings_arr[$numFeed]['xfgmc_shop_name'] = xfgmc_optionGET('xfgmc_shop_name', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_shop_description'] = xfgmc_optionGET('xfgmc_shop_description', $numFeed, 'for_update_option');	
		$xfgmc_settings_arr[$numFeed]['xfgmc_target_country'] = xfgmc_optionGET('xfgmc_target_country', $numFeed, 'for_update_option');

		$xfgmc_settings_arr[$numFeed]['xfgmc_default_currency'] = xfgmc_optionGET('xfgmc_default_currency', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_wooc_currencies'] = xfgmc_optionGET('xfgmc_wooc_currencies', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_main_product'] = xfgmc_optionGET('xfgmc_main_product', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_step_export'] = xfgmc_optionGET('xfgmc_step_export', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_cache'] = 'disabled';
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_store_code'] = xfgmc_optionGET('xfgmc_def_store_code', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_behavior_onbackorder'] = xfgmc_optionGET('xfgmc_behavior_onbackorder', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_g_stock'] = 'disabled';
		$xfgmc_settings_arr[$numFeed]['xfgmc_default_condition'] = xfgmc_optionGET('xfgmc_default_condition', $numFeed, 'for_update_option');	
		$xfgmc_settings_arr[$numFeed]['xfgmc_skip_missing_products'] = xfgmc_optionGET('xfgmc_skip_missing_products', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_skip_backorders_products'] = xfgmc_optionGET('xfgmc_skip_backorders_products', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_no_default_png_products'] = xfgmc_optionGET('xfgmc_no_default_png_products', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_one_variable'] = xfgmc_optionGET('xfgmc_one_variable', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_shipping_weight_unit'] = xfgmc_optionGET('xfgmc_def_shipping_weight_unit', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_shipping_country'] = xfgmc_optionGET('xfgmc_def_shipping_country', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_delivery_area_type'] = xfgmc_optionGET('xfgmc_def_delivery_area_type', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_delivery_area_value'] = xfgmc_optionGET('xfgmc_def_delivery_area_value', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_shipping_service'] = xfgmc_optionGET('xfgmc_def_shipping_service', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_shipping_price'] = xfgmc_optionGET('xfgmc_def_shipping_price', $numFeed, 'for_update_option');

		$xfgmc_settings_arr[$numFeed]['xfgmc_tax_info'] = xfgmc_optionGET('xfgmc_tax_info', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_shipping_label'] = xfgmc_optionGET('xfgmc_def_shipping_label', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_s_return_rule_label'] = 'disabled';
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_return_rule_label'] = xfgmc_optionGET('xfgmc_def_return_rule_label', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_min_handling_time'] = xfgmc_optionGET('xfgmc_def_min_handling_time', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_def_max_handling_time'] = xfgmc_optionGET('xfgmc_def_max_handling_time', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_instead_of_id'] = xfgmc_optionGET('xfgmc_instead_of_id', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_product_type'] = xfgmc_optionGET('xfgmc_product_type', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_product_type_home'] = xfgmc_optionGET('xfgmc_product_type_home', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_sale_price'] = xfgmc_optionGET('xfgmc_sale_price', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_gtin'] = xfgmc_optionGET('xfgmc_gtin', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_gtin_post_meta'] = xfgmc_optionGET('xfgmc_gtin_post_meta', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_mpn'] = xfgmc_optionGET('xfgmc_mpn', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_mpn_post_meta'] = xfgmc_optionGET('xfgmc_mpn_post_meta', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_age'] = xfgmc_optionGET('xfgmc_age', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_age_group_post_meta'] = xfgmc_optionGET('xfgmc_age_group_post_meta', $numFeed, 'for_update_option');	
		$xfgmc_settings_arr[$numFeed]['xfgmc_brand'] = xfgmc_optionGET('xfgmc_brand', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_brand_post_meta'] = xfgmc_optionGET('xfgmc_brand_post_meta', $numFeed, 'for_update_option'); 
		$xfgmc_settings_arr[$numFeed]['xfgmc_color'] = xfgmc_optionGET('xfgmc_color', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_material'] = xfgmc_optionGET('xfgmc_material', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_pattern'] = xfgmc_optionGET('xfgmc_pattern', $numFeed, 'for_update_option');

		$xfgmc_settings_arr[$numFeed]['xfgmc_gender'] = xfgmc_optionGET('xfgmc_gender', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_gender_alt'] = xfgmc_optionGET('xfgmc_gender_alt', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_size'] = xfgmc_optionGET('xfgmc_size', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_size_type'] = xfgmc_optionGET('xfgmc_size_type', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_size_type_alt'] = xfgmc_optionGET('xfgmc_size_type_alt', $numFeed, 'for_update_option');
		$xfgmc_settings_arr[$numFeed]['xfgmc_size_system'] = xfgmc_optionGET('xfgmc_size_system', $numFeed, 'for_update_option');	
		$xfgmc_settings_arr[$numFeed]['xfgmc_size_system_alt'] = xfgmc_optionGET('xfgmc_size_system_alt', $numFeed, 'for_update_option');
		$numFeed++;  
		$xfgmc_registered_feeds_arr = array(
			0 => array('last_id' => $i),
			1 => array('id' => $i)
		);
	}

	if (is_multisite()) {
		update_blog_option(get_current_blog_id(), 'xfgmc_settings_arr', $xfgmc_settings_arr);
		update_blog_option(get_current_blog_id(), 'xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr);
	} else {
		update_option('xfgmc_settings_arr', $xfgmc_settings_arr);
		update_option('xfgmc_registered_feeds_arr', $xfgmc_registered_feeds_arr);
	}
	$numFeed = '1';  
	for ($i = 1; $i<$allNumFeed+1; $i++) {		
		xfgmc_optionDEL('xfgmc_status_sborki', $numFeed); // статус сборки файла
		xfgmc_optionDEL('xfgmc_date_sborki', $numFeed); // дата последней сборки
		xfgmc_optionDEL('xfgmc_date_sborki_end', $numFeed); 
		xfgmc_optionDEL('xfgmc_file_url', $numFeed); // урл до файла
		xfgmc_optionDEL('xfgmc_file_file', $numFeed); // путь до файла
		xfgmc_optionDEL('xfgmc_errors', $numFeed);
		xfgmc_optionDEL('xfgmc_status_cron', $numFeed);
		xfgmc_optionDEL('xfgmc_date_save_set', $numFeed);

		xfgmc_optionDEL('xfgmc_run_cron', $numFeed);
		xfgmc_optionDEL('xfgmc_ufup', $numFeed); // нужно ли запускать обновление фида при перезаписи файла
		xfgmc_optionDEL('xfgmc_feed_assignment', $numFeed);
		xfgmc_optionDEL('xfgmc_adapt_facebook', $numFeed);
		xfgmc_optionDEL('xfgmc_whot_export', $numFeed); // что выгружать (все или там где галка)
		xfgmc_optionDEL('xfgmc_desc', $numFeed);
		xfgmc_optionDEL('xfgmc_the_content', $numFeed);
		xfgmc_optionDEL('xfgmc_var_desc_priority', $numFeed);
		xfgmc_optionDEL('xfgmc_shop_name', $numFeed);
		xfgmc_optionDEL('xfgmc_shop_description', $numFeed);	
		xfgmc_optionDEL('xfgmc_target_country', $numFeed);
		xfgmc_optionDEL('xfgmc_default_currency', $numFeed);
		xfgmc_optionDEL('xfgmc_wooc_currencies', $numFeed);
		xfgmc_optionDEL('xfgmc_main_product', $numFeed);
		xfgmc_optionDEL('xfgmc_step_export', $numFeed);	
		xfgmc_optionDEL('xfgmc_cache', $numFeed);
		xfgmc_optionDEL('xfgmc_def_store_code', $numFeed);
		xfgmc_optionDEL('xfgmc_behavior_onbackorder', $numFeed);
		xfgmc_optionDEL('xfgmc_default_condition', $numFeed);
		xfgmc_optionDEL('xfgmc_skip_missing_products', $numFeed);	
		xfgmc_optionDEL('xfgmc_skip_backorders_products', $numFeed);
		xfgmc_optionDEL('xfgmc_no_default_png_products', $numFeed);
		xfgmc_optionDEL('xfgmc_one_variable', $numFeed);

		xfgmc_optionDEL('xfgmc_def_shipping_weight_unit', $numFeed);
		xfgmc_optionDEL('xfgmc_def_shipping_country', $numFeed);
		xfgmc_optionDEL('xfgmc_def_delivery_area_type', $numFeed);
		xfgmc_optionDEL('xfgmc_def_delivery_area_value', $numFeed);
		xfgmc_optionDEL('xfgmc_def_shipping_service', $numFeed);
		xfgmc_optionDEL('xfgmc_def_shipping_price', $numFeed);		

		xfgmc_optionDEL('xfgmc_tax_info', $numFeed);
		xfgmc_optionDEL('xfgmc_def_shipping_label', $numFeed);
		xfgmc_optionDEL('xfgmc_def_return_rule_label', $numFeed);
		xfgmc_optionDEL('xfgmc_def_min_handling_time', $numFeed);
		xfgmc_optionDEL('xfgmc_def_max_handling_time', $numFeed);
		xfgmc_optionDEL('xfgmc_instead_of_id', $numFeed);
		xfgmc_optionDEL('xfgmc_product_type', $numFeed);
		xfgmc_optionDEL('xfgmc_product_type_home', $numFeed);		
		xfgmc_optionDEL('xfgmc_sale_price', $numFeed);
		xfgmc_optionDEL('xfgmc_gtin', $numFeed);
		xfgmc_optionDEL('xfgmc_gtin_post_meta', $numFeed);
		xfgmc_optionDEL('xfgmc_mpn', $numFeed);
		xfgmc_optionDEL('xfgmc_mpn_post_meta', $numFeed);
		xfgmc_optionDEL('xfgmc_age', $numFeed);
		xfgmc_optionDEL('xfgmc_age_group_post_meta', $numFeed);	
		xfgmc_optionDEL('xfgmc_brand', $numFeed); 
		xfgmc_optionDEL('xfgmc_brand_post_meta', $numFeed); 
		xfgmc_optionDEL('xfgmc_color', $numFeed);
		xfgmc_optionDEL('xfgmc_material', $numFeed);
		xfgmc_optionDEL('xfgmc_pattern', $numFeed);	

		xfgmc_optionDEL('xfgmc_gender', $numFeed);
		xfgmc_optionDEL('xfgmc_gender_alt', $numFeed);	
		xfgmc_optionDEL('xfgmc_size', $numFeed);
		xfgmc_optionDEL('xfgmc_size_type', $numFeed);
		xfgmc_optionDEL('xfgmc_size_type_alt', $numFeed);
		xfgmc_optionDEL('xfgmc_size_system', $numFeed);	
		xfgmc_optionDEL('xfgmc_size_system_alt', $numFeed);
		$numFeed++;
	}

	// перезапустим крон-задачи
	for ($i = 1; $i < xfgmc_number_all_feeds(); $i++) {
		$numFeed = (string)$i;
		$status_sborki = (int)xfgmc_optionGET('xfgmc_status_sborki', $numFeed);
		$xfgmc_status_cron = xfgmc_optionGET('xfgmc_status_cron', $numFeed, 'set_arr');
		if ($xfgmc_status_cron === 'off') {continue;}
		$recurrence = $xfgmc_status_cron;
		wp_clear_scheduled_hook('xfgmc_cron_period', array($numFeed));
		wp_schedule_event(time(), $recurrence, 'xfgmc_cron_period', array($numFeed));
		xfgmc_error_log('FEED № '.$numFeed.'; xfgmc_cron_period внесен в список заданий; Файл: function.php; Строка: '.__LINE__, 0);
	}
}
/**
* @since 1.0.0
*
* @return string/NULL
*
* Возвращает версию Woocommerce
*/ 
function xfgmc_get_woo_version_number() {
	// If get_plugins() isn't available, require it
	if (!function_exists('get_plugins')) {
		require_once( ABSPATH . 'wp-admin/includes/plugin.php');
	}
	// Create the plugins folder and file variables
	$plugin_folder = get_plugins('/' . 'woocommerce');
	$plugin_file = 'woocommerce.php';
	   
	// If the plugin version number is set, return it 
	if (isset($plugin_folder[$plugin_file]['Version'])) {
		return $plugin_folder[$plugin_file]['Version'];
	} else {	
		return NULL;
	}
}
/**
* @since 1.0.0
*
* @return array
*
* Получает все атрибуты вукомерца 
*/
function xfgmc_get_attributes() {
	$result = array();
	$attribute_taxonomies = wc_get_attribute_taxonomies();
	if (count($attribute_taxonomies) > 0) {
		$i = 0;
		foreach($attribute_taxonomies as $one_tax ) {
			/**
			* $one_tax->attribute_id => 6
			* $one_tax->attribute_name] => слаг (на инглише или русском)
			* $one_tax->attribute_label] => Еще один атрибут (это как раз название)
			* $one_tax->attribute_type] => select 
			* $one_tax->attribute_orderby] => menu_order
			* $one_tax->attribute_public] => 0			
			*/
			$result[$i]['id'] = $one_tax->attribute_id;
			$result[$i]['name'] = $one_tax->attribute_label;
			$i++;
		}
	}
	return $result;
}
/**
* @since 1.0.0
*
* @param string $TermName (not require)
* @param int $termID (not require)
* @param array $value_arr (not require) - id выбранных ранее глобальных атрибутов
* @param string $separator (not require)
* @param bool $parent_shown (not require)
* 
* Возвращает дерево таксономий, обернутое в <option></option>
*/
function xfgmc_cat_tree($TermName = '', $termID = -1, $value_arr = array(), $separator = '', $parent_shown = true) {
	/* 
	* $value_arr - массив id отмеченных ранее select-ов
	*/
	$result = '';
	$args = 'hierarchical=1&taxonomy='.$TermName.'&hide_empty=0&orderby=id&parent=';
	if ($parent_shown) {
		$term = get_term($termID , $TermName); 
		$selected = '';
		if (!empty($value_arr)) {
			foreach ($value_arr as $value) {		
				if ($value == $term->term_id) {
					$selected = 'selected'; break;
				}
			}
		}
		$result = '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'xml-for-google-merchant-center'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';		
		$parent_shown = false;
	}
	$separator .= '-';  
	$terms = get_terms($TermName, $args . $termID);
	if (count($terms) > 0) {
		foreach ($terms as $term) {
		$selected = '';
		if (!empty($value_arr)) {
			foreach ($value_arr as $value) {
				if ($value == $term->term_id) {
					$selected = 'selected'; break;
				}
			}
		}
		$result .= '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'xml-for-google-merchant-center'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';
		$result .= xfgmc_cat_tree($TermName, $term->term_id, $value_arr, $separator, $parent_shown);
		}
	}
	return $result; 
}

/**
 * @since 0.1.0
 * 
 * @deprecated 2.0.0 (06-03-2023)
 * 
 * Функция обеспечивает правильность данных, чтобы не валились ошибки и не зависало
 */
function validation_variabl($args, $p = 'xfgmc') {
	$is_string = common_option_get('woo_'.'hook_isc'.$p);
	if ($is_string == '202' && $is_string !== $args) {
		return true;
	} else {
		return false;
	}
}