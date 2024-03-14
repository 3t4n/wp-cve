<?php

// If this file is called directly, abort.
if ( !defined( 'WPINC' ) ) {
    die;
}
/**
 * Get the size chart setting.
 *
 * @return mixed|void Size chart setting array.
 */
function scfw_size_chart_get_settings()
{
    $size_chart_setting = get_option( 'size_chart_settings' );
    // If old plugin in serialize data.
    if ( is_serialized( $size_chart_setting, true ) ) {
        return maybe_unserialize( $size_chart_setting );
    }
    // If only array.
    if ( is_array( $size_chart_setting ) ) {
        return $size_chart_setting;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $size_chart_setting ) ) {
        return json_decode( $size_chart_setting, true );
    }
    return $size_chart_setting;
}

/**
 * Get the size chart setting by field.
 *
 * @param string $field_name size chart setting field name.
 *
 * @return mixed|string the field value base on field name.
 */
function scfw_size_chart_get_setting( $field_name )
{
    $size_chart_setting = scfw_size_chart_get_settings();
    if ( isset( $size_chart_setting[$field_name] ) ) {
        return $size_chart_setting[$field_name];
    }
    return '';
}

/***
 * Get the tab label from default setting.
 *
 * @return mixed|string string value.
 */
function scfw_size_chart_get_tab_label()
{
    $size_chart_field_value = scfw_size_chart_get_setting( 'size-chart-tab-label' );
    if ( isset( $size_chart_field_value ) && !empty($size_chart_field_value) ) {
        return $size_chart_field_value;
    }
    return esc_html__( 'Size Chart', 'size-chart-for-woocommerce' );
}

/**
 * Get the popup label from default setting.
 *
 * @return mixed|string a string value.
 */
function scfw_size_chart_get_popup_label()
{
    $size_chart_field_value = scfw_size_chart_get_setting( 'size-chart-popup-label' );
    if ( isset( $size_chart_field_value ) && !empty($size_chart_field_value) ) {
        return $size_chart_field_value;
    }
    return esc_html__( 'Size Chart', 'size-chart-for-woocommerce' );
}

/**
 * Get the popup type from default setting.
 *
 * @return mixed|string a string value.
 */
function scfw_size_chart_get_popup_type()
{
    $size_chart_field_value = scfw_size_chart_get_setting( 'size-chart-popup-type' );
    if ( isset( $size_chart_field_value ) && !empty($size_chart_field_value) ) {
        return $size_chart_field_value;
    }
}

/**
 * Get the sub title text from default setting.
 *
 * @return mixed|string a string value.
 */
function scfw_size_chart_get_sub_title_text()
{
    $size_chart_field_value = scfw_size_chart_get_setting( 'size-chart-sub-title-text' );
    if ( isset( $size_chart_field_value ) && !empty($size_chart_field_value) ) {
        return $size_chart_field_value;
    }
    return '';
}

/**
 * Get the inline styles for size chart by post id.
 *
 * @param int $post_id size chart id.
 *
 * @return mixed|string css string value.
 */
function scfw_size_chart_get_inline_styles_by_post_id( $post_id = 0, $table_style = '', $chart_table_font_size = '' )
{
    if ( empty($table_style) ) {
        $table_style = scfw_size_chart_get_chart_table_style_by_chart_id( $post_id );
    }
    $size_chart_inline_style = '';
    $size_chart_title_color = '#007acc';
    
    if ( 'minimalistic' === $table_style ) {
        $size_chart_inline_style .= "table#size-chart.minimalistic tr th {background: #fff;color: #000;}#size-chart.minimalistic tr:nth-child(2n+1){ background:none;}\n\t\t\t\t.button-wrapper #chart-button, .button-wrapper .md-size-chart-btn {color: {$size_chart_title_color}}";
    } elseif ( 'classic' === $table_style ) {
        $size_chart_inline_style .= "#size-chart.classic tr:nth-child(2n+1) {background: #fff;}table#size-chart.classic tr th {background: #000;color: #fff;}.button-wrapper #chart-button, .button-wrapper .md-size-chart-btn {color: {$size_chart_title_color}}";
    } elseif ( 'modern' === $table_style ) {
        $size_chart_inline_style .= "table#size-chart.modern tr th {background: none;;color: #000;} table#size-chart.modern, table#size-chart.modern tr th, table#size-chart.modern tr td {border: none;background: none;} #size-chart.modern tr:nth-child(2n+1) {background: #ebe9eb;} .button-wrapper #chart-button, .button-wrapper .md-size-chart-btn {color: {$size_chart_title_color}}";
    } else {
        $size_chart_inline_style .= "table#size-chart tr th {background: #000;color: #fff;}#size-chart tr:nth-child(2n+1) {background: #ebe9eb;}.button-wrapper #chart-button, .button-wrapper .md-size-chart-btn {color: {$size_chart_title_color}}";
    }
    
    return apply_filters( 'size_chart_inline_style', $size_chart_inline_style, $post_id );
}

/**
 * Create size chart table html.
 *
 * @param array|object|mixed $chart_table pass the chart table.
 *
 * @return false|string size chart table html.
 */
function scfw_size_chart_get_chart_table( $chart_table, $chart_id, $table_style = '' )
{
    if ( empty($table_style) ) {
        $table_style = scfw_size_chart_get_chart_table_style_by_chart_id( $chart_id );
    }
    ob_start();
    
    if ( !empty($chart_table) && array_filter( $chart_table ) ) {
        $i = 0;
        $tableOpen = false;
        // Flag to track if a table is open
        foreach ( $chart_table as $chart ) {
            $hasNewTableValue = false;
            // Flag to check if a new table value exists in the current chart
            $hasNewTitleValue = false;
            // Flag to check if a title value exists in the current chart
            $titleValue = '';
            foreach ( $chart as $value ) {
                
                if ( substr( $value, 0, 3 ) === '***' && substr( $value, -3 ) === '***' ) {
                    $hasNewTitleValue = true;
                    $titleValue = trim( $value, '*' );
                    // Remove ** signs from the starting and ending of the value
                    break;
                }
                
                
                if ( substr( $value, 0, 2 ) === '**' && substr( $value, -2 ) === '**' ) {
                    $hasNewTableValue = true;
                    break;
                }
            
            }
            
            if ( $hasNewTableValue || $hasNewTitleValue ) {
                
                if ( $tableOpen ) {
                    echo  "</table>" ;
                    // Close the previous table if any
                }
                
                if ( $hasNewTitleValue ) {
                    echo  '<p class="scfw-chart-table-title">' . esc_html( $titleValue ) . '</p>' ;
                }
                
                if ( $hasNewTableValue ) {
                    echo  "<table id='size-chart' class='scfw-chart-table " . esc_attr( $table_style ) . "'>" ;
                    $tableOpen = true;
                    $i = 0;
                }
            
            }
            
            
            if ( array_filter( $chart ) ) {
                $skipArray = false;
                // Flag to skip the array
                foreach ( $chart as $value ) {
                    
                    if ( substr( $value, 0, 3 ) === '***' && substr( $value, -3 ) === '***' ) {
                        $skipArray = true;
                        break;
                    }
                
                }
                
                if ( $skipArray ) {
                    continue;
                    // Skip the current array and move to the next iteration
                }
                
                
                if ( !$tableOpen ) {
                    echo  "<table id='size-chart' class='scfw-chart-table " . esc_attr( $table_style ) . "'>" ;
                    $tableOpen = true;
                }
                
                echo  "<tr>" ;
                foreach ( $chart as $value ) {
                    // If data available.
                    
                    if ( !empty($value) ) {
                        $value = trim( $value, '*' );
                        // Remove ** signs from the starting and ending of the value
                        echo  ( $i === 0 ? "<th>" . esc_html( $value ) . "</th>" : "<td>" . esc_html( $value ) . "</td>" ) ;
                    } else {
                        echo  ( $i === 0 ? "<th>" . esc_html( 'N/A' ) . "</th>" : "<td>" . esc_html( 'N/A' ) . "</td>" ) ;
                    }
                
                }
                echo  "</tr>" ;
                $i++;
            }
        
        }
        
        if ( $tableOpen ) {
            echo  "</table>" ;
            // Close the last table if any
        }
    
    }
    
    return apply_filters( 'size_chart_table_html', ob_get_clean(), $chart_table );
}

/**
 * Get the default size chart post ids.
 *
 * @return mixed|void a post ids array.
 */
function scfw_size_chart_get_default_post_ids()
{
    $default_size_chart_posts_ids = get_option( 'default_size_chart_posts_ids' );
    // If old plugin in serialize data.
    if ( is_serialized( $default_size_chart_posts_ids, true ) ) {
        return maybe_unserialize( $default_size_chart_posts_ids );
    }
    // If only array.
    if ( is_array( $default_size_chart_posts_ids ) ) {
        return $default_size_chart_posts_ids;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $default_size_chart_posts_ids ) ) {
        return json_decode( $default_size_chart_posts_ids, true );
    }
    return $default_size_chart_posts_ids;
}

/**
 * Update the default size chart post ids.
 *
 * @param array $default_size_chart_ids default size chart array.
 */
function scfw_size_chart_update_default_post_ids( $default_size_chart_ids )
{
    $default_size_chart_ids = wp_json_encode( $default_size_chart_ids );
    update_option( 'default_size_chart_posts_ids', $default_size_chart_ids );
}

/**
 * This function check weather data is in json format or not.
 *
 * @param string $string json string.
 *
 * @return bool true if the data is json.
 */
function scfw_size_chart_is_json( $string )
{
    if ( !empty($string) && is_array( $string ) ) {
        return false;
    }
    json_decode( $string, true );
    if ( json_last_error() === JSON_ERROR_NONE ) {
        return true;
    }
    return false;
}

/**
 * Get the size chart categories.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed a category which is selected on size chart.
 */
function scfw_size_chart_get_categories( $size_chart_id )
{
    $size_cart_cat_id = get_post_meta( $size_chart_id, 'chart-categories', true );
    // If old plugin in serialize data.
    if ( is_serialized( $size_cart_cat_id, true ) ) {
        return maybe_unserialize( $size_cart_cat_id );
    }
    // If only array.
    if ( is_array( $size_cart_cat_id ) ) {
        return $size_cart_cat_id;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $size_cart_cat_id ) ) {
        return json_decode( $size_cart_cat_id, true );
    }
    // If is array no array.
    if ( !is_array( $size_cart_cat_id ) ) {
        return array();
    }
    return $size_cart_cat_id;
}

/**
 * Get the size chart tags.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed a category which is selected on size chart.
 */
function scfw_size_chart_get_tags( $size_chart_id )
{
    $size_cart_tag_id = get_post_meta( $size_chart_id, 'chart-tags', true );
    // If old plugin in serialize data.
    if ( is_serialized( $size_cart_tag_id, true ) ) {
        return maybe_unserialize( $size_cart_tag_id );
    }
    // If only array.
    if ( is_array( $size_cart_tag_id ) ) {
        return $size_cart_tag_id;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $size_cart_tag_id ) ) {
        return json_decode( $size_cart_tag_id, true );
    }
    // If is array no array.
    if ( !is_array( $size_cart_tag_id ) ) {
        return array();
    }
    return $size_cart_tag_id;
}

/**
 * Get the size chart attributes.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed a category which is selected on size chart.
 */
function scfw_size_chart_get_attributes( $size_chart_id )
{
    $size_cart_tag_id = get_post_meta( $size_chart_id, 'chart-attributes', true );
    // If old plugin in serialize data.
    if ( is_serialized( $size_cart_tag_id, true ) ) {
        return maybe_unserialize( $size_cart_tag_id );
    }
    // If only array.
    if ( is_array( $size_cart_tag_id ) ) {
        return $size_cart_tag_id;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $size_cart_tag_id ) ) {
        return json_decode( $size_cart_tag_id, true );
    }
    // If is array no array.
    if ( !is_array( $size_cart_tag_id ) ) {
        return array();
    }
    return $size_cart_tag_id;
}

/**
 * Get the label value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a label name.
 */
function scfw_size_chart_get_label_by_chart_id( $size_chart_id )
{
    $chart_label = get_post_meta( $size_chart_id, 'label', true );
    if ( isset( $chart_label ) && !empty($chart_label) ) {
        return $chart_label;
    }
    return get_the_title( $size_chart_id );
}

/**
 * Get the chart sub title value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a sub title.
 */
function scfw_size_chart_get_sub_title_by_chart_id( $size_chart_id )
{
    $chart_sub_title = get_post_meta( $size_chart_id, 'size-chart-sub-title', true );
    if ( isset( $chart_sub_title ) && !empty($chart_sub_title) ) {
        return $chart_sub_title;
    }
}

/**
 * Get the chart popup note value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a popup note.
 */
function scfw_size_chart_popup_note( $size_chart_id )
{
    $chart_popup_note = get_post_meta( $size_chart_id, 'chart-popup-note', true );
    if ( !empty($chart_popup_note) ) {
        return $chart_popup_note;
    }
}

/**
 * Get the chart tab label value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a tab label.
 */
function scfw_size_chart_get_tab_label_by_chart_id( $size_chart_id )
{
    $chart_tab_label = get_post_meta( $size_chart_id, 'chart-tab-label', true );
    if ( isset( $chart_tab_label ) && !empty($chart_tab_label) ) {
        return $chart_tab_label;
    }
}

/**
 * Get the chart popup label value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a popup label.
 */
function scfw_size_chart_get_popup_label_by_chart_id( $size_chart_id )
{
    $chart_popup_label = get_post_meta( $size_chart_id, 'chart-popup-label', true );
    if ( isset( $chart_popup_label ) && !empty($chart_popup_label) ) {
        return $chart_popup_label;
    }
}

/**
 * Get the chart popup icon.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a icon name.
 */
function scfw_size_chart_get_popup_icon_by_chart_id( $size_chart_id )
{
    $chart_popup_icon = get_post_meta( $size_chart_id, 'chart-popup-icon', true );
    if ( isset( $chart_popup_icon ) && !empty($chart_popup_icon) ) {
        return $chart_popup_icon;
    }
}

/**
 * Get the chart popup type value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a type name.
 */
function scfw_size_chart_get_popup_type_by_chart_id( $size_chart_id )
{
    $chart_popup_type = get_post_meta( $size_chart_id, 'chart-popup-type', true );
    if ( isset( $chart_popup_type ) && !empty($chart_popup_type) ) {
        return $chart_popup_type;
    }
}

/**
 * Get the size chart style value.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string a size chart style.
 */
function scfw_size_chart_style_value_by_chart_id( $size_chart_id )
{
    $size_chart_style = get_post_meta( $size_chart_id, 'size-chart-style', true );
    if ( isset( $size_chart_style ) && !empty($size_chart_style) ) {
        return $size_chart_style;
    }
}

/**
 * Get the primary chart image data.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return array a image array.
 */
function scfw_size_chart_get_primary_chart_image_data_by_chart_id( $size_chart_id )
{
    $chart_img_id = scfw_size_chart_get_primary_chart_image_id( $size_chart_id );
    $size_chart_image_arr = array();
    $img_url = scfw_size_chart_default_chart_image();
    $img_width = '';
    $img_height = '';
    $close_icon_enable = false;
    
    if ( 0 !== $chart_img_id ) {
        // Display the form, using the current value.
        $size_chart_img_arr = wp_get_attachment_image_src( $chart_img_id, 'thumbnail' );
        
        if ( isset( $size_chart_img_arr[0] ) ) {
            $img_url = $size_chart_img_arr[0];
            $img_width = $size_chart_img_arr[1];
            $img_height = $size_chart_img_arr[2];
            $close_icon_enable = true;
        }
    
    }
    
    $size_chart_image_arr['attachment_id'] = $chart_img_id;
    $size_chart_image_arr['url'] = $img_url;
    $size_chart_image_arr['width'] = $img_width;
    $size_chart_image_arr['height'] = $img_height;
    $size_chart_image_arr['close_icon_status'] = $close_icon_enable;
    return $size_chart_image_arr;
}

/**
 * Get the default chart image.
 *
 * @return string default image path.
 */
function scfw_size_chart_default_chart_image()
{
    return plugins_url( 'admin/images/chart-img-placeholder.jpg', dirname( __FILE__ ) );
}

/**
 * Get the primary chart image id.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return int|mixed attachment id.
 */
function scfw_size_chart_get_primary_chart_image_id( $size_chart_id )
{
    $chart_img_id = get_post_meta( $size_chart_id, 'primary-chart-image', true );
    if ( isset( $chart_img_id ) && !empty($chart_img_id) ) {
        return $chart_img_id;
    }
    return 0;
}

/**
 * Get the position of the chart.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string string of position.
 */
function scfw_size_chart_get_position_by_chart_id( $size_chart_id )
{
    $chart_position = get_post_meta( $size_chart_id, 'position', true );
    if ( isset( $chart_position ) && !empty($chart_position) ) {
        return $chart_position;
    }
    return '';
}

/**
 * Get the chart table and check is it not empty.
 *
 * @param int $size_chart_id size chart id.
 * @param bool $return_json_decode json decode true or false.
 *
 * @return array|mixed|object return json decode data.
 */
function scfw_size_chart_get_chart_table_by_chart_id( $size_chart_id, $return_json_decode = true )
{
    $chart_table = get_post_meta( $size_chart_id, 'chart-table', true );
    if ( false === $return_json_decode ) {
        return $chart_table;
    }
    if ( isset( $chart_table ) && !empty($chart_table) ) {
        return json_decode( $chart_table );
    }
    return array();
}

/**
 * Get the chart table style class.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string string of table style class.
 */
function scfw_size_chart_get_chart_table_style_by_chart_id( $size_chart_id )
{
    $table_style = get_post_meta( $size_chart_id, 'table-style', true );
    if ( isset( $table_style ) && !empty($table_style) ) {
        return $table_style;
    }
    return '';
}

/**
 * Get the size chart content.
 *
 * @param int $size_chart_id size chart id.
 *
 * @return mixed|string|void return content.
 */
function scfw_size_chart_get_the_content( $size_chart_id )
{
    $size_chart_post = get_post( $size_chart_id );
    $size_chart_post_content = $size_chart_post->post_content;
    $size_chart_post_content = apply_filters( 'the_content', $size_chart_post_content );
    $size_chart_post_content = str_replace( ']]>', ']]&gt;', $size_chart_post_content );
    return apply_filters( 'size_chart_the_content', $size_chart_post_content, $size_chart_id );
}

/**
 * Get the size chart in product meta.
 *
 * @param int $product_id product id.
 *
 * @return int|mixed return product chart id.
 */
function scfw_size_chart_get_product_chart_id( $product_id )
{
    $product_chart_id = get_post_meta( $product_id, 'prod-chart', true );
    if ( isset( $product_chart_id ) && !empty($product_chart_id) ) {
        return $product_chart_id;
    }
    return 0;
}

/**
 * Get the size chart in product.
 *
 * @param int $product_id product id.
 *
 * @return int|mixed return product chart id.
 */
function scfw_size_chart_get_product( $product_id )
{
    $size_cart_tag_id = get_post_meta( $product_id, 'prod-chart', true );
    // If old plugin in serialize data.
    if ( is_serialized( $size_cart_tag_id, true ) ) {
        return maybe_unserialize( $size_cart_tag_id );
    }
    // If only array.
    if ( is_array( $size_cart_tag_id ) ) {
        return $size_cart_tag_id;
    }
    // If the data is json format.
    if ( scfw_size_chart_is_json( $size_cart_tag_id ) ) {
        return json_decode( $size_cart_tag_id, true );
    }
    // If is array no array.
    if ( !is_array( $size_cart_tag_id ) ) {
        return array();
    }
    return 0;
}

/**
 * Create pagination html.
 *
 * @param WP_Query $size_chart_query size chart wp_query.
 * @param int $current_post_id current size chart post id.
 * @param int $posts_per_page page per post.
 * @param bool $html if return html pass $html value true or if return array pass $html value false.
 *
 * @return array|int|string a array of all the pagination or print the pagination.
 */
function scfw_size_chart_pagination_html(
    $size_chart_query,
    $current_post_id = 0,
    $posts_per_page = 10,
    $html = true
)
{
    // Current page.
    $current_page = (int) $size_chart_query->query_vars['paged'];
    // The overall amount of pages.
    $max_page = $size_chart_query->max_num_pages;
    // We don't have to display pagination or load more button in this case.
    if ( $max_page <= 1 ) {
        return '';
    }
    // Set the current page to 1 if not exists.
    if ( empty($current_page) || $current_page === 0 ) {
        $current_page = 1;
    }
    // You can play with this parameter - how much links to display in pagination.
    $links_in_the_middle = 3;
    $links_in_the_middle_minus_1 = $links_in_the_middle - 1;
    // The code below is required to display the pagination properly for large amount of pages.
    $first_link_in_the_middle = $current_page - floor( $links_in_the_middle_minus_1 / 2 );
    $last_link_in_the_middle = $current_page + ceil( $links_in_the_middle_minus_1 / 2 );
    // Some calculations with $first_link_in_the_middle and $last_link_in_the_middle.
    if ( $first_link_in_the_middle <= 0 ) {
        $first_link_in_the_middle = 1;
    }
    if ( $last_link_in_the_middle - $first_link_in_the_middle !== $links_in_the_middle_minus_1 ) {
        $last_link_in_the_middle = $first_link_in_the_middle + $links_in_the_middle_minus_1;
    }
    
    if ( $last_link_in_the_middle > $max_page ) {
        $first_link_in_the_middle = $max_page - $links_in_the_middle_minus_1;
        $last_link_in_the_middle = (int) $max_page;
    }
    
    if ( $first_link_in_the_middle <= 0 ) {
        $first_link_in_the_middle = 1;
    }
    
    if ( true === $html ) {
        $pagination_html = '';
        // Begin to generate HTML of the pagination.
        $pagination_html .= '<nav class="pagination-box"><ul class="pagination" data-nonce="' . esc_attr( wp_create_nonce( 'size-chart-pagination' ) ) . '">';
        // Arrow left (previous page).
        
        if ( $current_page !== 1 ) {
            $first_page_number = $current_page - 1;
            $pagination_html .= scfw_size_chart_get_link_html(
                $first_page_number,
                $current_post_id,
                $posts_per_page,
                esc_html__( "<<", "size-chart-for-woocommerce" ),
                true,
                'prev'
            );
        }
        
        // When to display "..." and the first page before it.
        
        if ( $first_link_in_the_middle >= 3 && $links_in_the_middle < $max_page ) {
            $pagination_html .= scfw_size_chart_get_link_html(
                1,
                $current_post_id,
                $posts_per_page,
                esc_html__( "1", "size-chart-for-woocommerce" )
            );
            if ( $first_link_in_the_middle !== 2 ) {
                $pagination_html .= '<li><span class="page-numbers dots">...</span></li>';
            }
        }
        
        // Loop page links in the middle between "..." and "...".
        for ( $lopp_number = $first_link_in_the_middle ;  $lopp_number <= $last_link_in_the_middle ;  $lopp_number++ ) {
            
            if ( $lopp_number === $current_page ) {
                $pagination_html .= '<li><span class="page-numbers current">' . esc_html( $lopp_number ) . '</span></li>';
            } else {
                $pagination_html .= scfw_size_chart_get_link_html(
                    $lopp_number,
                    $current_post_id,
                    $posts_per_page,
                    esc_html( $lopp_number )
                );
            }
        
        }
        // When to display "..." and the last page after it.
        
        if ( $last_link_in_the_middle < $max_page ) {
            if ( $last_link_in_the_middle !== $max_page - 1 ) {
                $pagination_html .= '<li><span class="page-numbers dots">...</span></li>';
            }
            $pagination_html .= scfw_size_chart_get_link_html(
                $max_page,
                $current_post_id,
                $posts_per_page,
                esc_html( $max_page )
            );
        }
        
        // Arrow right (next page).
        
        if ( $current_page !== $last_link_in_the_middle ) {
            $next_page_number = $current_page + 1;
            $pagination_html .= scfw_size_chart_get_link_html(
                $next_page_number,
                $current_post_id,
                $posts_per_page,
                esc_html__( ">>", "size-chart-for-woocommerce" ),
                true,
                'next'
            );
        }
        
        // end HTML
        $pagination_html .= "</ul></nav>";
        $allow_html = array(
            'nav'  => array(
            'class' => array(),
        ),
            'ul'   => array(
            'class'      => array(),
            'data-nonce' => array(),
        ),
            'li'   => array(
            'class' => array(),
        ),
            'a'    => array(
            'class'              => array(),
            'href'               => array(),
            'data-post-id'       => array(),
            'data-page-number'   => array(),
            'data-post-per-page' => array(),
        ),
            'span' => array(
            'class' => array(),
        ),
        );
        echo  wp_kses( $pagination_html, $allow_html ) ;
    } else {
        $pagination_array = array();
        // Begin to generate HTML of the pagination.
        // Arrow left (previous page).
        
        if ( $current_page !== 1 ) {
            $first_page_number = $current_page - 1;
            $pagination_array[] = scfw_size_chart_get_link_html(
                $first_page_number,
                $current_post_id,
                $posts_per_page,
                __( "<<", "size-chart-for-woocommerce" ),
                false,
                'prev'
            );
        }
        
        // When to display "..." and the first page before it.
        
        if ( $first_link_in_the_middle >= 3 && $links_in_the_middle < $max_page ) {
            $pagination_array[] = scfw_size_chart_get_link_html(
                1,
                $current_post_id,
                $posts_per_page,
                esc_html__( "1", "size-chart-for-woocommerce" ),
                false
            );
            if ( $first_link_in_the_middle !== 2 ) {
                $pagination_array[] = array(
                    'pagination_tag'   => 'span',
                    'pagination_mode'  => 'dots',
                    'pagination_class' => 'dots',
                    'page_text'        => '...',
                );
            }
        }
        
        // Loop page links in the middle between "..." and "...".
        for ( $lopp_number = $first_link_in_the_middle ;  $lopp_number <= $last_link_in_the_middle ;  $lopp_number++ ) {
            
            if ( (int) $lopp_number === (int) $current_page ) {
                $pagination_array[] = array(
                    'pagination_tag'   => 'span',
                    'pagination_mode'  => 'number',
                    'pagination_class' => 'current',
                    'page_text'        => $lopp_number,
                );
            } else {
                $pagination_array[] = scfw_size_chart_get_link_html(
                    $lopp_number,
                    $current_post_id,
                    $posts_per_page,
                    esc_html( $lopp_number ),
                    false
                );
            }
        
        }
        // When to display "..." and the last page after it.
        
        if ( $last_link_in_the_middle < $max_page ) {
            if ( $last_link_in_the_middle !== $max_page - 1 ) {
                $pagination_array[] = array(
                    'pagination_tag'   => 'span',
                    'pagination_mode'  => 'dots',
                    'pagination_class' => 'dots',
                    'page_text'        => '...',
                );
            }
            $pagination_array[] = scfw_size_chart_get_link_html(
                $max_page,
                $current_post_id,
                $posts_per_page,
                esc_html( $max_page ),
                false
            );
        }
        
        // Arrow right (next page).
        
        if ( $current_page !== $last_link_in_the_middle ) {
            $next_page_number = $current_page + 1;
            $pagination_array[] = scfw_size_chart_get_link_html(
                $next_page_number,
                $current_post_id,
                $posts_per_page,
                __( ">>", "size-chart-for-woocommerce" ),
                false,
                'next'
            );
        }
        
        return $pagination_array;
    }
    
    return 0;
}

/**
 * Get the size chart pagination html or array.
 *
 * @param int $page_number pagination page number.
 * @param int $post_id current size chart id.
 * @param int $posts_per_page post per page.
 * @param string $page_text text of button and tab string.
 * @param bool $html create html or not bool value.
 * @param string $class_name pagination class name.
 *
 * @return string|array
 */
function scfw_size_chart_get_link_html(
    $page_number,
    $post_id,
    $posts_per_page,
    $page_text,
    $html = true,
    $class_name = ''
)
{
    
    if ( true === $html ) {
        return sprintf(
            '<li><a href="#" data-page-number="%s" data-post-id="%s" data-post-per-page="%s" class="page-numbers %s">%s</a></li>',
            $page_number,
            $post_id,
            $posts_per_page,
            $class_name,
            $page_text
        );
    } else {
        return array(
            'pagination_mode'  => 'number',
            'page_number'      => $page_number,
            'post_id'          => $post_id,
            'post_per_page'    => $posts_per_page,
            'pagination_class' => $class_name,
            'page_text'        => $page_text,
        );
    }

}

/**
 * Get the size chart popup from default setting.
 *
 */
function scfw_size_chart_get_size()
{
    $size_chart_field_value = 'medium';
    
    if ( scfw_fs()->is__premium_only() && scfw_fs()->can_use_premium_code() ) {
        $size_chart_field_value = scfw_size_chart_get_setting( 'size-chart-size' );
        if ( isset( $size_chart_field_value ) && !empty($size_chart_field_value) ) {
            return $size_chart_field_value;
        }
    }
    
    return $size_chart_field_value;
}

/**
 * Remove empty arrays and elements from an array.
 *
 */
function scfw_size_chart_check_empty_array( $array )
{
    foreach ( $array as &$value ) {
        if ( is_array( $value ) ) {
            $value = scfw_size_chart_check_empty_array( $value );
        }
    }
    return array_filter( $array, function ( $item ) {
        return !empty($item) || $item === 0;
        // Add additional checks if needed
    } );
}
