<?php
global $product, $post;
if( isset($product) ) {
    $product_backup = $product;
}
if( isset($post) ) {
    $post_backup = $post;
}
if( empty( $is_full_screen ) ) {
    $is_full_screen = FALSE;
}
$BeRocket_Compare_Products = BeRocket_Compare_Products::getInstance();
$options_global = $BeRocket_Compare_Products->get_option();
$options = $options_global['general_settings'];
$style = $options_global['style_settings'];
$text = $options_global['text_settings'];
if( isset($_GET['compare']) && $_GET['compare'] ) {
   $products = explode(',', $_GET['compare']);
} else {
    $products = $BeRocket_Compare_Products->get_all_compare_products();
}
if( ! is_array($products) ) {
    $products = array();
}
foreach( $products as &$product_id ) {
    $product_id = intval($product_id);
}
if( $br_compare_apply_filters ) {
    $query = new WP_Query(apply_filters('woocommerce_shortcode_products_query', array('post_type' => 'product', 'fields' => 'ids', 'post__in' => $products), array(), 'products'));
    $products = $query->get_posts();
}
if ( isset( $products ) && is_array( $products ) && count( $products ) > 0 ) {
    foreach ( $products as $i => $product ) {
        $current_language= apply_filters( 'wpml_current_language', NULL );
        $product = apply_filters( 'wpml_object_id', $product, 'product', true, $current_language );
        $term = array();
        $post_get = wc_get_product($product);
        if( $post_get === false ) {
            unset($products[$i]);
        }
    }
}
$terms = array();
$name = array();
$name['attributes'] = array();
$name['custom'] = array();
$name['acf'] = array();
$same = array();
$same['attributes'] = array();
$same['custom'] = array();
$same['acf'] = array();
$attr_count = array();
$attr_count['attributes'] = array();
$attr_count['custom'] = array();
$attr_count['acf'] = array();
$acf_empty = array();
if ( isset( $products ) && is_array( $products ) && count( $products ) > 0 ) {
    $acf_field_list = array();
    if( isset($options['attributes']['acf_br_fields']) && function_exists('acf_get_field_groups') ) {
        if( is_array($options['attributes']['acf_br_fields']) ) {
            $acf_fields = $options['attributes']['acf_br_fields'];
            $groups = acf_get_field_groups();
            if ( is_array( $groups ) ) {
                foreach ( $groups as $group ) {
                    $fields = acf_get_fields($group);
                    if( is_array($fields) ) {
                        foreach($fields as $field) {
                            if( in_array($field['name'], $acf_fields) ) {
                                $acf_field_list[$field['name']] = $field;
                            }
                        }
                    }
                }
            }
        }
        unset($options['attributes']['acf_br_fields']);
    }
    $description_length = 0;
    foreach ( $products as $product ) {
        $current_language= apply_filters( 'wpml_current_language', NULL );
        $product = apply_filters( 'wpml_object_id', $product, 'product', true, $current_language );
        $post = get_post($product);
        $term = array();
        $post_get = wc_get_product($product);
        if( $post_get === false ) {
            continue;
        }
        $attributes = $post_get->get_attributes();
        $taxonomies = get_post_taxonomies( $product );
        $term['id'] = $product;
        $term['title'] = $post_get->get_title();
        $term['image'] = $post_get->get_image();
        $term['price'] = $post_get->get_price_html();
        $term['link'] = $post_get->get_permalink();
        $term['short_description'] = get_the_excerpt($product);
        $description_length = max(strlen($term['short_description']), $description_length);
        //$term['availability'] = $post_get->stock_status;
        $term['availability'] = $post_get->get_availability();
        $term['is_in_stock'] = $post_get->is_in_stock();
        $attributes_value = array();
        $attributes_name = array();
        foreach ( $attributes as $key => $attribute ) {
            if ( ! is_array( $options['attributes'] ) || in_array( $key, $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                $attributes_value[$key] = wc_get_product_terms( $product, $key );
                $same_check = wc_get_product_terms( $product, $key, array('fields' => 'slugs') );
                if ( is_object( br_get_value_from_array($attributes_value, array($key, 0)) ) ) {
                    $attr_value_temp = $attributes_value[$key];
                    $attributes_value[$key] = array();
                    $same_check = array();
                    foreach( @ $attr_value_temp as $term_i => $term_data ) {
                        $attributes_value[$key][] = $term_data->name;
                        $same_check[] = $term_data->name;
                    }
                }
                if( isset($same['attributes'][$key]) ) {
                    $same['attributes'][$key] = array_intersect($same['attributes'][$key], $same_check);
                } else {
                    $same['attributes'][$key] = $same_check;
                }
                if( empty($attr_count['attributes'][$key]) ) {
                    $attr_count['attributes'][$key] = count($attributes_value[$key]);
                } else {
                    $attr_count['attributes'][$key] = max($attr_count['attributes'][$key], count($attributes_value[$key]));
                }
                $attributes_value[$key] = '<p>'.implode( '</p><p>', $attributes_value[$key] ).'</p>';
                if ( ! isset( $name['attributes'][$key] ) ) {
                    $attributes_name[$key] = get_taxonomy( $key );
                    $taxonomy_label = $attributes_name[$key]->labels->singular_name;
                    $taxonomy_label = apply_filters('wpml_translate_single_string', $taxonomy_label, 'WordPress', sprintf( 'taxonomy singular name: %s', $taxonomy_label ) );
                    $attributes_name[$key] = $taxonomy_label;
                }
                if(($key_delete = array_search($key, $taxonomies)) !== false) {
                    unset($taxonomies[$key_delete]);
                }
            }
        }
        foreach($same['attributes'] as $key => $values) {
            if( ! isset($attributes[$key]) ) {
                $same['attributes'][$key] = array();
            }
        }
        $taxonomies = array_diff($taxonomies, array('product_type', 'product_shipping_class'));
        $term['attributes'] = $attributes_value;
        $name['attributes'] = $name['attributes'] + $attributes_name;
        $attributes_value = array();
        $attributes_name = array();
        foreach ( $taxonomies as $key ) {
            if ( ! is_array( $options['attributes'] ) || in_array( $key, $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
                $attributes_value[$key] = wp_get_post_terms( $product, $key );
                $custom_result = '';
                $same_check = array();
                foreach( $attributes_value[$key] as $cutom_term ) {
                    $custom_result .= '<p>'.$cutom_term->name.'</p>';
                    $same_check[] = $cutom_term->slug;
                }
                if( isset($same['custom'][$key]) ) {
                    $same['custom'][$key] = array_intersect($same['custom'][$key], $same_check);
                } else {
                    $same['custom'][$key] = $same_check;
                }
                if( ! empty($attributes_value[$key]) ) {
                    if( empty($attr_count['custom'][$key]) ) {
                        $attr_count['custom'][$key] = count($attributes_value[$key]);
                    } else {
                        $attr_count['custom'][$key] = max($attr_count['custom'][$key], count($attributes_value[$key]));
                    }
                }
                if ( $custom_result ) {
                    $attributes_value[$key] = $custom_result;
                    if ( ! isset( $name['custom'][$key] ) ) {
                        $attributes_name[$key] = get_taxonomy( $key );
                        $taxonomy_label = $attributes_name[$key]->labels->name;
                        $taxonomy_label = apply_filters('wpml_translate_single_string', $taxonomy_label, 'WordPress', sprintf( 'taxonomy singular name: %s', $taxonomy_label ) );
                        $attributes_name[$key] = $taxonomy_label;
                    }
                } else {
                    unset( $attributes_value[$key] );
                }
            }
        }
        foreach($same['custom'] as $key => $values) {
            if( ! in_array($key, $taxonomies) ) {
                $same['custom'][$key] = array();
            }
        }
        $term['custom'] = $attributes_value;
        $name['custom'] = $name['custom'] + $attributes_name;
        //================================================================
        $attributes_value = array();
        $attributes_name = array();
        foreach ( $acf_field_list as $key => $field ) {
            ob_start();
            the_field($field['name'], $product, true);
            $attributes_value[$key] = array(ob_get_clean());
            if( empty($attributes_value[$key][0]) ) {
                $attributes_value[$key] = array('');
            }
            $custom_result = '';
            $same_check = array();
            foreach( $attributes_value[$key] as $cutom_term ) {
                if( is_string($cutom_term) && strlen($cutom_term) > 0 ) {
                    $custom_result .= '<p>'.$cutom_term.'</p>';
                    $same_check[] = $cutom_term;
                }
            }
            if( isset($same['acf'][$key]) ) {
                $same['acf'][$key] = array_intersect($same['acf'][$key], $same_check);
            } else {
                $same['acf'][$key] = $same_check;
            }
            if( ! empty($attributes_value[$key]) ) {
                if( empty($attr_count['acf'][$key]) ) {
                    $attr_count['acf'][$key] = count($attributes_value[$key]);
                } else {
                    $attr_count['acf'][$key] = max($attr_count['acf'][$key], count($attributes_value[$key]));
                }
            }
            if ( $custom_result ) {
                $attributes_value[$key] = $custom_result;
                if ( ! isset( $name['acf'][$key] ) ) {
                    $attributes_name[$key] = $field['label'];
                }
            } else {
                unset( $attributes_value[$key] );
            }
        }
        foreach($same['acf'] as $key => $values) {
            if( ! array_key_exists($key, $acf_field_list) ) {
                $same['acf'][$key] = array();
            }
        }
        $term['acf'] = $attributes_value;
        $name['acf'] = $name['acf'] + $attributes_name;
        //================================================================
        $terms[] = $term;
    }
    $colwidth = $style['table']['colwidth'];
    if( empty($colwidth) ) {
        $colwidth = 200;
    }
    $colwidth = $colwidth - 10;
    if( $colwidth < 0 ) {
        $colwidth = 25;
    }
    $description_width = $description_length * 16;
    $description_lines = $description_width / $colwidth;
    $description_lines = (int)$description_lines;
    $description_lines += 2;
    $description_height = (20 + 20 * (empty($description_lines) ? 1 : $description_lines));
    $have_dif = false;
    foreach ( $same['attributes'] as $attr ) {
        if( count($attr) > 0 ) {
            $have_dif = true;
            break;
        }
    }
    if( ! $have_dif ) {
        foreach ( $same['custom'] as $attr ) {
            if( count($attr) > 0 ) {
                $have_dif = true;
                break;
            }
        }
    }
    if( ! $have_dif ) {
        foreach ( $same['acf'] as $attr ) {
            if( count($attr) > 0 ) {
                $have_dif = true;
                break;
            }
        }
    }
?>
<div class="br_new_compare_block_wrap">
<?php
if ( $options['use_full_screen'] || ! empty($is_full_screen) ) {
    echo '<div class="br_new_compare_full_size br_full_size_open"><a href="#full-screen"><i class="fa fa-arrows-alt"></i></a></div>';
    echo '<div class="br_new_compare_full_size br_full_size_close" style="display:none;"><a href="#full-screen-close"><i class="fa fa-times"></i></a></div>';
}
?>
<div class="br_compare_popup_block"<?php if(! empty($berocket_element_i)) echo ' id="br_popup_'.$berocket_element_i.'"'; ?>>
<div class="br_new_compare_block" data-table_scroll="0" data-table_hidden_scroll="0" data-is_full_screen="<?php echo (empty($is_full_screen) ? "0" : "1"); ?>">
<div style="clear:both;"></div>
<div class="br_top_table">
<?php
    if( $have_dif && ! empty($options['hide_same_button']) ) {
        echo '<a class="br_show_compare_dif'.($options['hide_same_default'] ? ' br_hidden_same' : '').'" href="#difference">'.($options['hide_same_default'] 
            ? (empty($text['show_same_button_text']) ? __( 'Show attributes with same values', 'products-compare-for-woocommerce' ) : $text['show_same_button_text']) 
            : (empty($text['hide_same_button_text']) ? __( 'Hide attributes with same values', 'products-compare-for-woocommerce' ) : $text['hide_same_button_text']) 
        ).'</a>';
    }
    if( ! empty($options['remove_all_compare']) ) {
        echo '<a class="br_remove_all_compare" href="#remove_all">'.(empty($text['remove_all_compare_text']) ? __( 'Clear compare list', 'products-compare-for-woocommerce' ) : $text['remove_all_compare_text']).'</a>';
    }
    $top_table_html = '
        <table>';
    $top_table_html2 = '<td></td>';
    $top_table_html .= '
            <tr><td></td>';
    foreach ( $terms as $term ) {
        $top_table_html .= '<th><div>
            <h3><a href="'.$term['link'].'">'.$term['title'].'</a></h3>';
        $top_table_term2 = '';
        if ( ! is_array( $options['attributes'] ) || in_array( 'cp_price', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
            $top_table_term2 .= '<p class="br_compare_price price">'.$term['price'].'</p>';
        }
        if ( is_array( $options['attributes'] ) && in_array( 'cp_add_to_cart', $options['attributes'] ) ) {
            $product = wc_get_product($term['id']);
            $post = get_post($term['id']);
            ob_start();
            echo '<div>';
            woocommerce_template_loop_add_to_cart();
            echo '</div>';
            $top_table_term2 .= ob_get_clean();
        }
        if( ! empty($top_table_term2) ) {
            $top_table_html2 .= '<th>'.$top_table_term2.'</th>';
        }
        $default_language= apply_filters( 'wpml_default_language', NULL );
        $default_product = apply_filters( 'wpml_object_id', $term['id'], 'product', true, $default_language );
        $top_table_html .= '<a href="#remove" class="br_remove_compare_product_reload" data-id="'.$default_product.'"><i class="fa fa-times"></i></a>';
        $top_table_html .= '</div></th>';
    }
    $top_table_html .= '
            </tr>';
    if( ! empty($top_table_html2) ) {
        $top_table_html .= '<tr>'.$top_table_html2.'</tr>';
    }
    $top_table_html .= '
        </table>';
    ?>
    <div class="br_main_top">
        <?php echo $top_table_html; ?>
    </div>
    <div class="br_opacity_top">
        <?php echo $top_table_html; ?>
    </div>
</div>
<div class="br_new_compare">
<table class="br_left_table">
    <?php if ( ! is_array( $options['attributes'] ) || in_array( 'cp_image', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) { ?>
    <tr class="br_header_row"><td></td></tr>
    <?php 
    }
    if ( ! is_array( $options['attributes'] ) || in_array( 'cp_available', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
        echo '<tr class="br_absolute2_cp_availability"><th>'.(empty($text['availability']) ? __( 'Availability', 'products-compare-for-woocommerce' ) : $text['availability']).'</th></tr>';
    }
    if ( ! is_array( $options['attributes'] ) || in_array( 'cp_short_description', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) { ?>
    <tr class="br_description_row"><th><?php echo (empty($text['description']) ? __( 'Description', 'products-compare-for-woocommerce' ) : $text['description']); ?></th></tr>
    <?php 
    }
    if ( is_array( $name['attributes'] ) && count( $name['attributes'] ) > 0 ) {
        echo '<tr><th class="br_lined_attribute_left">'.(empty($text['attribute']) ? __( 'Attributes', 'products-compare-for-woocommerce' ) : $text['attribute']).'</th></tr>';
        foreach ( $name['attributes'] as $attr => $name_attr ) {
            echo '<tr class="'.(is_array($same['attributes'][$attr]) && count($same['attributes'][$attr]) ? ' br_same_attr' : '').'" style="height:'.(15 + 20 * (empty($attr_count['attributes'][$attr]) ? '1' : $attr_count['attributes'][$attr])).'px!important;"><th>'.$name_attr.'</th></tr>';
        }
    }
    if ( is_array( $name['custom'] ) && count( $name['custom'] ) > 0 ) {
        if ( is_array( $name['custom'] ) && count( $name['custom'] ) > 0 ) {
            echo '<tr><th class="br_lined_attribute_left">'.(empty($text['custom']) ? __( 'Other attributes', 'products-compare-for-woocommerce' ) : $text['custom']).'</th></tr>';
            foreach ( $name['custom'] as $attr => $name_attr ) {
                echo '<tr class="'.(is_array($same['custom'][$attr]) && count($same['custom'][$attr]) ? ' br_same_attr' : '').'" style="height:'.(15 + 20 * (empty($attr_count['custom'][$attr]) ? '1' : $attr_count['custom'][$attr])).'px!important;"><th>'.$name_attr.'</th></tr>';
            }
        }
    }
    if ( is_array( $name['acf'] ) && count( $name['acf'] ) > 0 ) {
        foreach ( $name['acf'] as $attr => $name_attr ) {
            echo '<tr class="'.(is_array($same['acf'][$attr]) && count($same['acf'][$attr]) ? ' br_same_attr' : '').'" style="'.apply_filters('berocket_compare_acf_product_field_height', 'height:'.(15 + 20 * (empty($attr_count['acf'][$attr]) ? '1' : $attr_count['acf'][$attr])).'px!important;', $acf_field_list[$attr]) . '"><th>'.$name_attr.'</th>';
        }
    }
    ?>
</table>
<div class="br_right_table">
<table>
    <?php if ( ! is_array( $options['attributes'] ) || in_array( 'cp_image', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) { ?>
    <thead>
        <tr>
            <td></td>
        <?php 
        foreach ( $terms as $term ) {
            echo '<th>';
                echo '<a href="'.$term['link'].'">'.$term['image'].'</a>';
            echo '</th>';
        }
        ?>
        </tr>
    </thead>
    <?php } ?>
    <tbody>
    <?php
    if ( ! is_array( $options['attributes'] ) || in_array( 'cp_available', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
        echo '<tr>';
        echo '<th>'.(empty($text['availability']) ? __( 'Availability', 'products-compare-for-woocommerce' ) : $text['availability']).'</th>';
        foreach ( $terms as $term ) {
            $class_stock = '';
            $text_stock = '';
            if ( $term['availability'] && ! empty( $term['availability']['availability'] ) ) {
                $product = wc_get_product($term['id']);
                $post = get_post($term['id']);
                $availability_html = empty( $term['availability']['availability'] ) ? '' : '<p class="stock ' . esc_attr( $term['availability']['class'] ) . '">' . esc_html( $term['availability']['availability'] ) . '</p>';
                $text_stock = apply_filters( 'woocommerce_stock_html', $availability_html, $term['availability']['availability'], $product );
                $class_stock = esc_attr( $term['availability']['class'] );
            } else {
                if ( $term['is_in_stock'] ) {
                    $text_stock = __( 'In stock', 'woocommerce' );
                    $class_stock = 'in-stock';
                } else {
                    $text_stock = __( 'Out of stock', 'woocommerce' );
                    $class_stock = 'out-of-stock';
                }
                $text_stock = '<p class="stock '.$class_stock.'">'.$text_stock.'</p>';
            }
            echo '<td>';
            echo $text_stock;
            echo '</td>';
        }
        echo '</tr>';
    }
    if ( ! is_array( $options['attributes'] ) || in_array( 'cp_short_description', $options['attributes'] ) || count( $options['attributes'] ) == 0 ) {
        echo '<tr class="br_description_row">';
        echo '<th>' . (empty($text['description']) ? __( 'Description', 'products-compare-for-woocommerce' ) : $text['description']) . '</th>';
        foreach ( $terms as $term ) {
            echo '<td><div>';
            echo $term['short_description'];
            echo '</div></td>';
        }
        echo '</tr>';
    }
    if ( is_array( $name['attributes'] ) && count( $name['attributes'] ) > 0 ) {
        echo '<tr><th class="br_lined_attribute_left">'.(empty($text['attribute']) ? __( 'Attributes', 'products-compare-for-woocommerce' ) : $text['attribute']).'</th><td class="br_lined_attribute_right" colspan="'.(count($terms)).'"></td></tr>';
        foreach ( $name['attributes'] as $attr => $name_attr ) {
            echo '<tr class="'.(is_array($same['attributes'][$attr]) && count($same['attributes'][$attr]) ? ' br_same_attr' : '').'" style="height:'.(15 + 20 * (empty($attr_count['attributes'][$attr]) ? '1' : $attr_count['attributes'][$attr])).'px!important;"><th>'.$name_attr.'</th>';
            foreach ( $terms as $term ) {
                echo '<td>'.br_get_value_from_array($term, array('attributes', $attr)).'</td>';
            }
            echo '</tr>';
        } 
    }
    if ( is_array( $name['custom'] ) && count( $name['custom'] ) > 0 ) {
        echo '<tr><th class="br_lined_attribute_left">'.(empty($text['custom']) ? __( 'Other attributes', 'products-compare-for-woocommerce' ) : $text['custom']).'</th><td class="br_lined_attribute_right" colspan="'.(count($terms)).'"></td></tr>';
        foreach ( $name['custom'] as $attr => $name_attr ) {
            echo '<tr class="'.(is_array($same['custom'][$attr]) && count($same['custom'][$attr]) ? ' br_same_attr' : '').'" style="height:'.(15 + 20 * (empty($attr_count['custom'][$attr]) ? '1' : $attr_count['custom'][$attr])).'px!important;"><th>'.$name_attr.'</th>';
            foreach ( $terms as $term ) {
                echo '<td>'.br_get_value_from_array($term, array('custom', $attr)).'</td>';
            }
            echo '</tr>';
        } 
    }
    if ( is_array( $name['acf'] ) && count( $name['acf'] ) > 0 ) {
        foreach ( $name['acf'] as $attr => $name_attr ) {
            echo '<tr class="'.(is_array($same['acf'][$attr]) && count($same['acf'][$attr]) ? ' br_same_attr' : '').'" style="'.apply_filters('berocket_compare_acf_product_field_height', 'height:'.(15 + 20 * (empty($attr_count['acf'][$attr]) ? '1' : $attr_count['acf'][$attr])).'px!important;', $acf_field_list[$attr]) . '"><th>'.$name_attr.'</th>';
            foreach ( $terms as $term ) {
                echo '<td>'.apply_filters('berocket_compare_acf_product_field', br_get_value_from_array($term, array('acf', $attr)), $acf_field_list[$attr], $term).'</td>';
            }
            echo '</tr>';
        }
    }
    ?>
    </tbody>
</table>
</div>
<?php
$width_set = (empty($style['table']['colwidth']) ? 200 : $style['table']['colwidth']);
$width_set_full = $width_set * (count($terms) + 1);
$width_set = $width_set * count($terms);
?>
<style>
.br_new_compare_block .br_top_table table,
.br_new_compare_block .br_right_table table {
    min-width:<?php echo $width_set ?>px!important;
}
@media (max-width: 767px) {
    .br_new_compare_block .br_top_table table,
    .br_new_compare_block .br_right_table table {
        min-width:<?php echo $width_set_full ?>px!important;
    }
}
.br_new_compare .br_left_table .br_description_row,
.br_new_compare .br_right_table .br_description_row {
    height:<?php echo $description_height; ?>px!important;
}
.br_new_compare .br_right_table .br_description_row td div {
    height:<?php echo $description_height-1; ?>px!important;
}
</style>
<script>
    if( typeof(berocket_popup_compare_scroll_fix) == 'function' ) {
        berocket_popup_compare_scroll_fix();
    }
</script>
</div>
</div>
</div>
</div>
<?php
} 
if( isset($product_backup) ) {
    $product = $product_backup;
}
if( isset($post_backup) ) {
    $post = $post_backup;
}
?>
