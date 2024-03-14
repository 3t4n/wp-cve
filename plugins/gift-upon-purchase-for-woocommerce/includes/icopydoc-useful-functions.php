<?php if (!defined('ABSPATH')) {exit;}
// 1.5.2 (25-02-2023)
// Maxim Glazunov (https://icopydoc.ru)
// This code adds several useful functions to the WordPress.

/**
 * @since 1.0.0
 *
 * @param array/string/obj	$text (require)
 * @param string 			$new_line (not require)
 * @param int 				$i (not require)
 * @param string 			$res (not require)
 *
 * @return string
 *
 * Converts an array to an easy-to-read format
 */
if (!function_exists('get_array_as_string')) {
	function get_array_as_string($text, $new_line = PHP_EOL, $i = 0, $res = '') {
		$tab = ''; for ($x = 0; $x < $i; $x++) {$tab = '---'.$tab;}
		if (is_object($text)) {$text = (array)$text;}
		if (is_array($text)) { 
			$i++;
			foreach ($text as $key => $value) {
				if (is_array($value)) {	// массив
					$res .= $new_line .$tab."[$key] => (".gettype($value).")";
					$res .= $tab.get_array_as_string($value, $new_line, $i);
				} else if (is_object($value)) { // не массив
					$res .= $new_line .$tab."[$key] => (".gettype($value).")";
					$value = (array)$value;
					$res .= $tab.get_array_as_string($value, $new_line, $i);
				} else {
					$res .= $new_line .$tab."[$key] => (".gettype($value).")". $value;
				}
			}
		} else {
		   $res .= $new_line .$tab.$text;
		}
		return $res;
	}	
}

/**
 * @since 1.0.0
 *
 * @param string 			$url (require)
 * @param string 			$whot (not require)
 *
 * @return string/false
 *
 * Return URL without GET parameters or just GET parameters without URL
 */
if (!function_exists('get_from_url')) {
	function get_from_url($url, $whot = 'url') {
		$url = str_replace("&amp;", "&", $url); // Заменяем сущности на амперсанд, если требуется
		list($url_part, $get_part) = array_pad(explode("?", $url), 2, ""); // Разбиваем URL на 2 части: до знака ? и после
		switch($whot) {
			case "url":
				$url_part = str_replace(" ", "%20", $url_part); // заменим пробел на сущность
				return $url_part; // Возвращаем URL без get-параметров (до знака вопроса)
			break;
			case "get_params":
				return $get_part; // Возвращаем get-параметры (без знака вопроса)
			break;
			default:
				return false;
		}
	}
}

/**
 * @since 1.1.0
 *
 * @param string 			$option_name (require)
 * @param any 				$value (require)
 * @param string/bool 		$autoload (not require) (yes/no or true/false)
 *
 * @return true/false
 * 
 * Returns what might be the result of a add_blog_option or add_option
 */
if (!function_exists('univ_option_add')) {
	function univ_option_add($option_name, $value, $autoload = 'no') {
		if (is_multisite()) { 
			return add_blog_option(get_current_blog_id(), $option_name, $value);
		} else {
			return add_option($option_name, $value, '', $autoload);
		}
	}
}

/**
 * @since 1.1.0
 *
 * @param string 			$option_name (require)
 * @param any 				$newvalue (require)
 * @param string/bool 		$autoload (not require) (yes/no or true/false)
 *
 * @return true/false
 * 
 * Returns what might be the result of a update_blog_option or update_option
 */
if (!function_exists('univ_option_upd')) {
	function univ_option_upd($option_name, $newvalue, $autoload = 'no') {
		if (is_multisite()) { 
			return update_blog_option(get_current_blog_id(), $option_name, $newvalue);
		} else {
			return update_option($option_name, $newvalue, $autoload);
		}
	}
}

/**
 * @since 1.1.0
 *
 * @param string 			$option_name (require)
 * @param any 				$default (not require) - value to return if the option does not exist
 *
 * @return true/false
 * Returns what might be the result of a get_blog_option or get_option
 */
if (!function_exists('univ_option_get')) {
	function univ_option_get($option_name, $default = false) {
		if (is_multisite()) { 
			return get_blog_option(get_current_blog_id(), $option_name, $default);
		} else {
			return get_option($option_name, $default);
		}
	}
}

/**
 * @since 1.1.0
 *
 * @param string 			$option_name (require)
 *
 * @return true/false
 * Returns what might be the result of a delete_blog_option or delete_option
 */
if (!function_exists('univ_option_del')) {
	function univ_option_del($option_name) {
		if (is_multisite()) { 
			return delete_blog_option(get_current_blog_id(), $option_name);
		} else {
			return delete_option($option_name);
		}
	}
}

/**
 * @since 1.1.1
 *
 * @param string 			$str (require)
 *
 * @return string
 * 
 * Returns a formatted string
 */
if (!function_exists('translit_cyr_en')) {
	function translit_cyr_en($str) {
		$converter_arr = [
			'а' => 'a',		'б' => 'b',		'в' => 'v',		'г' => 'g',		'д' => 'd',
			'е' => 'e',		'ё' => 'e',		'ж' => 'zh',	'з' => 'z',		'и' => 'i',
			'й' => 'y',		'к' => 'k',		'л' => 'l',		'м' => 'm',		'н' => 'n',
			'о' => 'o',		'п' => 'p',		'р' => 'r',		'с' => 's',		'т' => 't',
			'у' => 'u',		'ф' => 'f',		'х' => 'h',		'ц' => 'c',		'ч' => 'ch',
			'ш' => 'sh',	'щ' => 'sch',	'ь' => '',		'ы' => 'y',		'ъ' => '',
			'э' => 'e',		'ю' => 'yu',	'я' => 'ya',
		];
	
		$str = mb_strtolower($str);
		$str = strtr($str, $converter_arr);
		$str = mb_ereg_replace('[^-0-9a-z]', '-', $str);
		$str = mb_ereg_replace('[-]+', '-', $str);
		$str = trim($str, '-');	
		return $str;
	}
}

/**
 * @since 1.1.2
 * 
 * @param int 				$id (require)			- the product ID
 * @param bool 				$force (not require)	- true to permanently delete product, false to move to trash
 *
 * @return \WP_Error|boolean
 * 
 * @see 					https://stackoverflow.com/questions/46874020/delete-a-product-by-id-using-php-in-woocommerce
 * @usage:
 * 		wooс_delete_product(170); // to trash a product
 * 		wooс_delete_product(170, true); // to permanently delete a product
 *
 * Method to delete Woo Product
 */
if (!function_exists('wooс_delete_product')) {
	function wooс_delete_product($id, $force = false) {
		$product = wc_get_product($id);

		if (empty($product)) {
			return new WP_Error(999, sprintf(__('No %s is associated with #%d', 'woocommerce'), 'product', $id));
		}
		// If we're forcing, then delete permanently.
		if ($force) {
			if ($product->is_type('variable')) {
				foreach ($product->get_children() as $child_id) {
					$child = wc_get_product($child_id);
					$child->delete(true);
				}
			} elseif ($product->is_type('grouped')) {
				foreach ($product->get_children() as $child_id) {
					$child = wc_get_product($child_id);
					$child->set_parent_id(0);
					$child->save();
				}
			}

			$product->delete(true);
			$result = $product->get_id() > 0 ? false : true;
		} else {
			$product->delete();
			$result = 'trash' === $product->get_status();
		}

		if (!$result) {
			return new WP_Error(999, sprintf(__('This %s cannot be deleted', 'woocommerce'), 'product'));
		}

		// Delete parent product transients.
		if ($parent_id = wp_get_post_parent_id($id)) {
			wc_delete_product_transients($parent_id);
		}
		return true;
	}
}

/**
 * @since 1.1.3
 *
 * @param string 			$str (require)
 *
 * @return string/null
 *
 * Returns a file extension or null
 */
if (!function_exists('get_file_extension')) {
	function get_file_extension($fileurl) {
		$path_info = pathinfo($fileurl);
		if (isset($path_info['extension'])) {
			return $path_info['extension'];
		} else {
			return null;
		}
	}
}

/**
 * @since 1.2.0
 *
 * @param string			$opt_val (require)
 * @param array 			$opt_data_arr (not require)
 * @param bool 				$is_echo (not require)
 * @param string			$result (not require)
 *
 * @return string/nothing
 *
 * Get or prints html option tags
 */
if (!function_exists('print_html_tags_option')) {
	function print_html_tags_option($opt_val, $opt_data_arr = [], $is_echo = true, $result = '') {
		if (!empty($opt_data_arr)) {
			for ($i = 0; $i < count($opt_data_arr); $i++) {
				$result .= sprintf( '<option value="%1$s" %2$s>%3$s</option>',
					$opt_data_arr[$i][1],
					selected($opt_val, $opt_data_arr[$i][1]),
					$opt_data_arr[$i][0]
				);
			}
		}
		if ($is_echo == true) {
			echo $result;
		} else {
			return $result;
		}
	}
}

/**
 * @since 1.5.0
 *
 * @param string 			$option_name (require)
 * @param any 				$value (require)
 * @param string/bool 		$autoload (not require) (yes/no or true/false)
 * @param string 			$n (not require) (key in the array of common settings)
 * @param string 			$slug (not require)
 *
 * @return true/false
 * 
 * Returns what might be the result of a add_blog_option or add_option
 */
if (!function_exists('common_option_add')) {
	function common_option_add($option_name, $value, $autoload = 'no', $n = '0', $slug = '') {
		if ($n === '0') {
			$option_name_in_db = $option_name; 
			unset($option_name);
			$value_in_db = $value; 
			unset($value);
		} else {
			$option_name_in_db = $slug.'_settings_arr';
			$settings_arr = univ_option_get($option_name_in_db);
			$settings_arr[$n][$option_name] = $value;
			$value_in_db = $settings_arr;
		}

		if (is_multisite()) { 
			return add_blog_option(get_current_blog_id(), $option_name_in_db, $value_in_db);
		} else {
			return add_option($option_name_in_db, $value_in_db, '', $autoload);
		}
	}
}

/**
 * @since 1.5.0 (25-02-2023)
 *
 * @param string 			$option_name (require)
 * @param any 				$value (require)
 * @param string/bool 		$autoload (not require) (yes/no or true/false)
 * @param string 			$n (not require) (key in the array of common settings)
 * @param string 			$slug (not require)
 *
 * @return true/false
 * 
 * Returns what might be the result of a update_blog_option or update_option
 */
if (!function_exists('common_option_upd')) {
	function common_option_upd($option_name, $value, $autoload = 'no', $n = '0', $slug = '') {
		if ($n === '0') {
			$option_name_in_db = $option_name; 
			unset($option_name);
			$value_in_db = $value; 
			unset($value);
		} else {
			$option_name_in_db = $slug.'_settings_arr';
			$settings_arr = common_option_get($option_name_in_db);
			if (is_array($settings_arr)) {
				$settings_arr[$n][$option_name] = $value;
			} else {
				$settings_arr = [];
				$settings_arr[$n][$option_name] = $value;
			}
			$value_in_db = $settings_arr;
		}

		if (is_multisite()) { 
			return update_blog_option(get_current_blog_id(), $option_name_in_db, $value_in_db);
		} else {
			return update_option($option_name_in_db, $value_in_db, $autoload);
		}
	}
}

/**
 * @since 1.5.0
 *
 * @param string 			$option_name (require)
 * @param any 				$default (not require) - value to return if the option does not exist
 * @param string 			$n (not require) (key in the array of common settings)
 * @param string 			$slug (not require)
 *
 * @return true/false
 * Returns what might be the result of a get_blog_option or get_option
 */
if (!function_exists('common_option_get')) {
	function common_option_get($option_name, $default = false, $n = '0', $slug = '') {
		if ($n === '0') {
			$option_name_in_db = $option_name;
		} else {
			$option_name_in_db = $slug.'_settings_arr';
			$settings_arr = common_option_get($option_name_in_db, [ ]);
			if (isset( $settings_arr[$n][$option_name] )) {
				return $settings_arr[$n][$option_name];
			} else {
				return false;
			}
		}

		if (is_multisite()) { 
			return get_blog_option(get_current_blog_id(), $option_name_in_db, $default);
		} else {
			return get_option($option_name_in_db, $default);
		}
	}
}

/**
 * @since 1.5.0
 *
 * @param string 			$option_name (require)
 * @param string 			$n (not require) (key in the array of common settings)
 * @param string 			$slug (not require)
 *
 * @return true/false
 * Returns what might be the result of a delete_blog_option or delete_option
 */
if (!function_exists('common_option_del')) {
	function common_option_del($option_name, $n = '0', $slug = '') {
		if ($n === '0') {
			$option_name_in_db = $option_name;
		} else {
			$option_name_in_db = $slug.'_settings_arr';
			$settings_arr = common_option_get($option_name_in_db, [ ]);
			if (isset($settings_arr[$n][$option_name])) {
				unset($settings_arr[$n][$option_name]);
				if (is_multisite()) { 
					return update_blog_option(get_current_blog_id(), $option_name_in_db, $settings_arr);
				} else {
					return update_option($option_name_in_db, $settings_arr);
				}
			} else {
				return false;
			}
		}

		if (is_multisite()) { 
			return delete_blog_option(get_current_blog_id(), $option_name_in_db);
		} else {
			return delete_option($option_name_in_db);
		}
	}
}