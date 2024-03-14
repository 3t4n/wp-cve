<?php defined( 'ABSPATH' ) || exit;
/**
 * @since 1.0.0
 *
 * @param string $optName (require)
 * @param string $value (require)
 * @param string $n (not require)
 *
 * @return true/false
 * Возвращает то, что может быть результатом add_blog_option, add_option
 */
function gupfw_optionADD( $optName, $value = '', $n = '' ) {
	if ( $optName == '' ) {
		return false;
	}
	if ( $n === '1' ) {
		$n = '';
	}
	$optName = $optName . $n;
	if ( is_multisite() ) {
		return add_blog_option( get_current_blog_id(), $optName, $value );
	} else {
		return add_option( $optName, $value );
	}
}

/**
 * @since 1.0.0
 *
 * @param string $optName (require)
 * @param string $value (require)
 * @param string $n (not require)
 *
 * @return bool
 * Возвращает то, что может быть результатом update_blog_option, update_option
 */
function gupfw_optionUPD( $optName, $value = '', $n = '' ) {
	if ( $optName == '' ) {
		return false;
	}
	if ( $n === '1' ) {
		$n = '';
	}
	$optName = $optName . $n;
	if ( is_multisite() ) {
		return update_blog_option( get_current_blog_id(), $optName, $value );
	} else {
		return update_option( $optName, $value );
	}
}

/**
 * @since 1.0.0
 * @updated in v2.0.0
 *
 * @param string $optName (require)
 * @param string $n (not require)
 *
 * @return true/false
 * Возвращает то, что может быть результатом get_blog_option, get_option
 */
function gupfw_optionGET( $optName, $n = '' ) {
	if ( $optName == '' ) {
		return false;
	}
	if ( $n === '1' ) {
		$n = '';
	}
	$optName = $optName . $n;
	if ( is_multisite() ) {
		return get_blog_option( get_current_blog_id(), $optName );
	} else {
		return get_option( $optName );
	}
}

/**
 * @since 1.0.0
 *
 * @param string $optName (require)
 * @param string $n (not require)
 *
 * @return true/false
 * Возвращает то, что может быть результатом delete_blog_option, delete_option
 */
function gupfw_optionDEL( $optName, $n = '' ) {
	if ( $optName == '' ) {
		return false;
	}
	if ( $n === '1' ) {
		$n = '';
	}
	$optName = $optName . $n;
	if ( is_multisite() ) {
		return delete_blog_option( get_current_blog_id(), $optName );
	} else {
		return delete_option( $optName );
	}
}

/**
 * @since 1.0.4
 *
 * @param array $gupfw_gift_for_any_product_arr (require)
 * @dependence lib select2, select2.js, https://github.com/woocommerce/selectWoo 
 *
 * @return string of multiselect products with ajax
 */
function gupfw_select2( $gupfw_gift_for_any_product_arr ) {
	// https://rudrastyh.com/wordpress/select2-for-metaboxes-with-ajax.html
	$html = '';
	// always array because we have added [] to our <select> name attribute
	// $gupfw_gift_for_any_product_arr = gupfw_optionGET('gupfw_gift_for_any_product_arr');
	/**
	 * Select Posts with AJAX search
	 */
	$html .= '<select id="gupfw_gift_for_any_product_arr" name="gupfw_gift_for_any_product_arr[]" multiple="multiple" style="width:99%;max-width:25em;">';
	if ( $gupfw_gift_for_any_product_arr ) {
		foreach ( $gupfw_gift_for_any_product_arr as $post_id ) {
			$title = get_the_title( $post_id );
			// if the post title is too long, truncate it and add "..." at the end
			$title = ( mb_strlen( $title ) > 50 ) ? mb_substr( $title, 0, 49 ) . '...' : $title;
			$html .= '<option value="' . $post_id . '" selected="selected">' . $title . '</option>';
		}
	}
	$html .= '</select>';
	return $html;
}

/**
 * @since 1.0.4
 *
 * @return Возвращает дерево таксономий, обернутое в <option></option>
 */
function gupfw_cat_tree( $TermName = '', $termID, $value_arr, $separator = '', $parent_shown = true ) {
	/** 
	 * $value_arr - массив id отмеченных ранее select-ов
	 */
	$result = '';
	$args = 'hierarchical=1&taxonomy=' . $TermName . '&hide_empty=0&orderby=id&parent=';
	if ( $parent_shown ) {
		$term = get_term( $termID, $TermName );
		$selected = '';
		if ( ! empty( $value_arr ) ) {
			foreach ( $value_arr as $value ) {
				if ( $value == $term->term_id ) {
					$selected = 'selected';
					break;
				}
			}
		}
		// $result = $separator.$term->name.'('.$term->term_id.')<br/>';
		$result = '<option title="' . $term->name . '; ID: ' . $term->term_id . '" class="hover" value="' . $term->term_id . '" ' . $selected . '>' . $separator . $term->name . '</option>';
		$parent_shown = false;
	}
	$separator .= '-';
	$terms = get_terms( $TermName, $args . $termID );
	if ( count( $terms ) > 0 ) {
		foreach ( $terms as $term ) {
			$selected = '';
			if ( ! empty( $value_arr ) ) {
				foreach ( $value_arr as $value ) {
					if ( $value == $term->term_id ) {
						$selected = 'selected';
						break;
					}
				}
			}
			$result .= '<option title="' . $term->name . '; ID: ' . $term->term_id . '" class="hover" value="' . $term->term_id . '" ' . $selected . '>' . $separator . $term->name . '</option>';
			// $result .=  $separator.$term->name.'('.$term->term_id.')<br/>';
			$result .= gupfw_cat_tree( $TermName, $term->term_id, $value_arr, $separator, $parent_shown );
		}
	}
	return $result;
}

/**
 * @since 1.0.4
 *
 * @param array $products_in_cart_arr (require)
 * @param float $cart_total (require)
 * @param array $gift_ids_arr (not require)
 *
 * @return bool
 */
// Функция учёта общих подарков
function gupfw_gift_check( $products_in_cart_arr, $cart_total, $gift_ids_arr = [] ) {
	new GUPFW_Error_Log( 'Стартовала gupfw_gift_check; Файл: functions.php; Строка: ' . __LINE__ );
	new GUPFW_Error_Log( '$products_in_cart_arr => (см ниже); Файл: functions.php; Строка: ' . __LINE__ );
	new GUPFW_Error_Log( $products_in_cart_arr );
	new GUPFW_Error_Log( '$cart_total = ' . $cart_total . '; Файл: functions.php; Строка: ' . __LINE__ );
	new GUPFW_Error_Log( '$gift_ids_arr = ; Файл: functions.php; Строка: ' . __LINE__ );
	new GUPFW_Error_Log( $gift_ids_arr );

	$causes = 0;

	$gupfw_gift_for_any_product_arr = gupfw_optionGET( 'gupfw_gift_for_any_product_arr' );
	if ( empty( $gupfw_gift_for_any_product_arr ) ) {
		new GUPFW_Error_Log( 'WARNING: нет общих подароков; Возвращаем false; Файл: functions.php; Строка: ' . __LINE__ );
		$causes++;
		return false;
	} // нет общих подароков

	// проверка общего подарка по дате и времени
	$gupfw_days_of_the_week = gupfw_optionGET( 'gupfw_days_of_the_week' );
	$gupfw_days_of_the_hours = gupfw_optionGET( 'gupfw_days_of_the_hours' );
	if ( is_array( $gupfw_days_of_the_week ) && ! empty( $gupfw_days_of_the_week ) ) {
		if ( ! in_array( current_time( 'l' ), $gupfw_days_of_the_week ) ) {
			new GUPFW_Error_Log( 'Функция gupfw_gift_check возвращает false т.к. у нас не подарочный день недели; Файл: functions.php; Строка: ' . __LINE__ );
			return false;
		}
	}
	if ( is_array( $gupfw_days_of_the_hours ) && ! empty( $gupfw_days_of_the_hours ) ) {
		if ( ! in_array( current_time( 'H' ), $gupfw_days_of_the_hours ) ) {
			new GUPFW_Error_Log( 'Функция gupfw_gift_check возвращает false т.к. у нас не подраочный час; Файл: functions.php; Строка: ' . __LINE__ );
			return false;
		}
	}
	// end проверка общего подарка по дате и времени

	if ( $cart_total === 0 ) {
		$cart_total = 0;
		foreach ( $products_in_cart_arr as $hash => $cart_item ) {
			if ( in_array( $cart_item["product_id"], $gift_ids_arr )
				|| in_array( $cart_item["variation_id"], $gift_ids_arr )
				|| in_array( $cart_item["product_id"], $gupfw_gift_for_any_product_arr )
				|| in_array( $cart_item["variation_id"], $gupfw_gift_for_any_product_arr ) ) { // Если в корзине есть товары с ID = подарка
				if ( $cart_item['quantity'] < 2 ) {
					new GUPFW_Error_Log( 'Не учитываем цену товара с id = ' . $cart_item["product_id"] . ', т.к. он идёт в подарок и в корзине меньше 2х штук этого подарка; Файл: functions.php; Строка: ' . __LINE__ );
				} else {
					if ( isset( $cart_item["line_total"] ) ) {
						$line_total = (float) $cart_item["line_total"];
						$cart_total = $cart_total + $line_total;
					}
				}
			} else {
				if ( isset( $cart_item["line_total"] ) ) {
					$line_total = (float) $cart_item["line_total"];
					$cart_total = $cart_total + $line_total;
				}
			}
		}
		new GUPFW_Error_Log( 'Сумма НЕ подарочных товаров равна = ' . $cart_total . '; Файл: functions.php; Строка: ' . __LINE__ );
	}

	$gupfw_cart_total_price = gupfw_optionGET( 'gupfw_cart_total_price' );
	if ( $cart_total < $gupfw_cart_total_price ) {
		new GUPFW_Error_Log( 'сумма корзины меньше чем надо ' . $cart_total . ' < ' . $gupfw_cart_total_price . ' ($cart_total < $gupfw_cart_total_price); Возвращаем false; Файл: functions.php; Строка: ' . __LINE__ );
		$causes++;
	} // сумма корзины меньше чем надо

	// корзина содержит по крайней мере один товар, дороже
	$gupfw_whose_price_exceeds = gupfw_optionGET( 'gupfw_whose_price_exceeds' );
	if ( $gupfw_whose_price_exceeds > 0 ) {
		$flag = 0;
		foreach ( $products_in_cart_arr as $cur_cart_item ) {
			// если цена товара выше указанной суммы
			$line_total = (float) $cur_cart_item['line_total']; // цена товара
			if ( $line_total > $gupfw_whose_price_exceeds ) {
				new GUPFW_Error_Log( 'В корзине есть товар дороже ' . $gupfw_whose_price_exceeds . '. ' . $line_total . ' > ' . $gupfw_whose_price_exceeds . ' ($line_total > $gupfw_whose_price_exceeds); Файл: functions.php; Строка: ' . __LINE__ );
				$flag = 1;
				break;
			}
		}
		if ( $flag === 0 ) {
			new GUPFW_Error_Log( 'В корзине нет товаров дороже ' . $gupfw_whose_price_exceeds . '; Файл: functions.php; Строка: ' . __LINE__ );
			$causes++;
		}
	}

	$causes = apply_filters( 'gupfw_causes_filter', $causes );

	if ( $causes > 0 ) {
		new GUPFW_Error_Log( 'Функция gupfw_gift_check возвращает false по ' . $causes . ' причинам; Файл: functions.php; Строка: ' . __LINE__ );
		return false;
	} else {
		new GUPFW_Error_Log( 'Функция gupfw_gift_check возвращает true; Файл: functions.php; Строка: ' . __LINE__ );
		return true;
	}
}

/**
 * @since 1.0.6
 *
 * @param array $products_in_cart_arr (require)
 * @param array $gupfw_gift_for_any_product_arr (not require)
 * @param array $gift_ids_arr (not require)
 *
 * @return $cart_total (float)
 */
function gupfw_cart_total_without_gifts( $products_in_cart_arr = array(), $gupfw_gift_for_any_product_arr = array(), $gift_ids_arr = array() ) {
	// Функция учёта суммы корзины без товаров подарков
	new GUPFW_Error_Log( 'Стартовала gupfw_cart_total_without_gifts; Файл: functions.php; Строка: ' . __LINE__ );
	$cart_total = 0;
	foreach ( $products_in_cart_arr as $hash => $cart_item ) {
		if ( in_array( $cart_item["product_id"], $gift_ids_arr ) || in_array( $cart_item["variation_id"], $gift_ids_arr ) || in_array( $cart_item["product_id"], $gupfw_gift_for_any_product_arr ) || in_array( $cart_item["variation_id"], $gupfw_gift_for_any_product_arr ) ) { // Если в корзине есть товары с ID = подарка
			if ( $cart_item['quantity'] < 2 ) {
				new GUPFW_Error_Log( 'Не учитываем цену товара с id = ' . $cart_item["product_id"] . ', т.к. он идёт в подарок и в корзине меньше 2х штук этого подарка; Файл: functions.php; Строка: ' . __LINE__ );
			} else {
				if ( isset( $cart_item["line_total"] ) ) {
					$line_total = (float) $cart_item["line_total"];
					$cart_total = $cart_total + $line_total;
				}
			}
		} else {
			if ( isset( $cart_item["line_total"] ) ) {
				$line_total = (float) $cart_item["line_total"];
				$cart_total = $cart_total + $line_total;
			}
		}
	}
	return $cart_total;
}
/**
 * @since 1.1.0
 *
 * @param array $field (require)
 *
 * Function based woocommerce_wp_select
 * https://stackoverflow.com/questions/23287358/woocommerce-multi-select-for-single-product-field
 */
function gupfw_woocommerce_wp_select_multiple( $field, $blog_option = false ) {
	if ( $blog_option === false ) {
		global $thepostid, $post, $woocommerce;
		$thepostid = empty( $thepostid ) ? $post->ID : $thepostid;
		$field['value'] = isset( $field['value'] ) ? $field['value'] : ( get_post_meta( $thepostid, $field['id'], true ) ? get_post_meta( $thepostid, $field['id'], true ) : array() );
	} else { // если у нас глобальные настройки, а не метаполя, то данные тащим через gupfw_optionGET
		global $woocommerce;
		$field['value'] = isset( $field['value'] ) ? $field['value'] : ( gupfw_optionGET( $field['id'] ) ? gupfw_optionGET( $field['id'] ) : array() );
	}

	$field['class'] = isset( $field['class'] ) ? $field['class'] : 'select short';
	$field['wrapper_class'] = isset( $field['wrapper_class'] ) ? $field['wrapper_class'] : '';
	$field['name'] = isset( $field['name'] ) ? $field['name'] : $field['id'];
	$field['label'] = isset( $field['label'] ) ? $field['label'] : '';

	echo '<p class="form-field ' . esc_attr( $field['id'] ) . '_field ' . esc_attr( $field['wrapper_class'] ) . '"><label for="' . esc_attr( $field['id'] ) . '">' . wp_kses_post( $field['label'] ) . '</label><select id="' . esc_attr( $field['id'] ) . '" name="' . esc_attr( $field['name'] ) . '[]" class="' . esc_attr( $field['class'] ) . '" multiple="multiple">';

	foreach ( $field['options'] as $key => $value ) {
		echo '<option value="' . esc_attr( $key ) . '" ' . ( in_array( $key, $field['value'] ) ? 'selected="selected"' : '' ) . '>' . esc_html( $value ) . '</option>';
	}

	echo '</select> ';

	if ( ! empty( $field['description'] ) ) {
		if ( isset( $field['desc_tip'] ) && false !== $field['desc_tip'] ) {
			echo '<img class="help_tip" data-tip="' . esc_attr( $field['description'] ) . '" src="' . esc_url( WC()->plugin_url() ) . '/assets/images/help.png" height="16" width="16" />';
		} else {
			echo '<span class="description">' . wp_kses_post( $field['description'] ) . '</span>';
		}
	}

	echo '</p>';
}
/**
 * @since 1.2.0
 *
 * @return array
 */
function gupfw_possible_problems_list() {
	$possibleProblems = '';
	$possibleProblemsCount = 0;
	$conflictWithPlugins = 0;
	$conflictWithPluginsList = '';

	if ( is_plugin_active( 'snow-storm/snow-storm.php' ) ) {
		$possibleProblemsCount++;
		$conflictWithPlugins++;
		$conflictWithPluginsList .= 'Snow Storm<br/>';
	}
	if ( is_plugin_active( 'email-subscribers/email-subscribers.php' ) ) {
		$possibleProblemsCount++;
		$conflictWithPlugins++;
		$conflictWithPluginsList .= 'Email Subscribers & Newsletters<br/>';
	}
	if ( $conflictWithPlugins > 0 ) {
		$possibleProblemsCount++;
		$possibleProblems .= '<li><p>' . __( 'Most likely, these plugins negatively affect the operation of', 'gift-upon-purchase-for-woocommerce' ) . ' Gift upon purchase for WooCommerce:</p>' . $conflictWithPluginsList . '<p>' . __( 'If you are a developer of one of the plugins from the list above, please contact me', 'gift-upon-purchase-for-woocommerce' ) . ': <a href="mailto:support@icopydoc.ru">support@icopydoc.ru</a>.</p></li>';
	}
	return array( $possibleProblems, $possibleProblemsCount, $conflictWithPlugins, $conflictWithPluginsList );
}