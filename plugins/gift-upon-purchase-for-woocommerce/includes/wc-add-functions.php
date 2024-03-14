<?php if (!defined('ABSPATH')) {exit;}
/*
Version: 1.0.0
Date: 21-10-2021
Author: Maxim Glazunov
Author URI: https://icopydoc.ru 
License: GPLv2
Description: This code adds several useful functions to the WooCommerce.
*/

/*
* @since 1.0.0
*
* @return string/NULL
*
* Возвращает версию Woocommerce
*/ 
if (!function_exists('get_woo_version_number')) {
	function get_woo_version_number() {
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
}
/*
* @since 1.0.0
*
* @return array
*
* Получает все атрибуты вукомерца 
*/
if (!function_exists('get_woo_attributes')) {
	function get_woo_attributes() {
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
}
/*
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
if (!function_exists('the_cat_tree')) {
	function the_cat_tree($TermName = '', $termID = -1, $value_arr = array(), $separator = '', $parent_shown = true) {
		// $value_arr - массив id отмеченных ранее select-ов
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
			$result = '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'yfym'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';		
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
			$result .= '<option title="'.$term->name.'; ID: '.$term->term_id.'; '. __('products', 'yfym'). ': '.$term->count.'" class="hover" value="'.$term->term_id.'" '.$selected .'>'.$separator.$term->name.'</option>';
			$result .= the_cat_tree($TermName, $term->term_id, $value_arr, $separator, $parent_shown);
			}
		}
		return $result; 
	}
}
?>