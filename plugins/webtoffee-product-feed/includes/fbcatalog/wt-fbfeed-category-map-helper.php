<?php

/**
 * Category Mapping helper
 *
 * @link       https://webtoffee.com/
 * @since      1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


// Category mapping.
if ( ! function_exists( 'wt_fbfeed_render_categories' ) ) {
    /**
     * Get Product Categories
     *
     * @param int    $parent Parent ID.
     * @param string $par separator.
     * @param string $value mapped values.
     */
    function wt_fbfeed_render_categories( $parent = 0, $par = '', $value = '' ) {
        $category_args = [
			'taxonomy'		 => 'product_cat',
			'parent'		 => $parent,
			'orderby'		 => 'term_group',
			'show_count'	 => 1,
			'pad_counts'	 => 1,
			'hierarchical'	 => 1,
			'title_li'		 => '',
			'hide_empty'	 => 0,
			'meta_query'	 => [
				[
					'key'		 => 'wt_fb_category',
					'compare'	 => 'NOT EXISTS',
				]
			]
		];
        $categories   = get_categories( $category_args );
        if ( ! empty( $categories ) ) {
            if ( ! empty( $par ) ) {
                $par = $par . ' > ';
            }
			
			
            foreach ( $categories as $cat ) {
                $class = $parent ? "treegrid-parent-{$parent} category-mapping" : 'treegrid-parent category-mapping';
                ?>
                <tr class="treegrid-1 ">
                    <th>
                        <label for="cat_mapping_<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $par . $cat->name ); ?></label>
                    </th>
                    <td><!--suppress HtmlUnknownAttribute -->
						

                        <select style="width:100%" id= "cat_mapping_<?php echo esc_attr( $cat->term_id ); ?>" name="map_to[<?php echo esc_attr( $cat->term_id ); ?>]" class="wc-enhanced-select">
                                <?php echo wt_fb_category_dropdown(); ?>
                            </select>
                    </td>
                </tr>
                <?php
                // call for child category if any.
				if(!empty($par))
                wt_fbfeed_render_categories( $cat->term_id, $par . $cat->name, $value );
            }
        }else{
			?>
				<tr class="treegrid-1">
					<td><!--suppress HtmlUnknownAttribute -->
						<?php esc_html_e( 'All categories have already been mapped', 'webtoffee-product-feed' ); ?>
					</td>
				</tr>
			<?php
			}
    }
}

// FB Category dropdown caching
if ( ! function_exists( 'wt_fb_category_dropdown' ) ) {
	 function wt_fb_category_dropdown( $selected = '' ) {
		
		$category_dropdown = wp_cache_get( 'wt_fbfeed_dropdown_product_categories' );

		if ( false === $category_dropdown ) {
			$categories = Webtoffee_Product_Feed_Sync_Facebook::get_category_array();
			
			# Primary Attributes
			$category_dropdown = '';
			
				foreach ( $categories as $key => $value ) {
					$category_dropdown .= sprintf( '<option value="%s">%s</option>', $key, $value );
				}

			wp_cache_set( 'wt_fbfeed_dropdown_product_categories', $category_dropdown, '', WEEK_IN_SECONDS );
		}
		
		
		if ( $selected && strpos( $category_dropdown, 'value="' . $selected . '"' ) !== false ) {
			$category_dropdown = str_replace( 'value="' . $selected . '"', 'value="' . $selected . '"' . ' selected', $category_dropdown );
		}
		 

		
		return $category_dropdown;
	}
}


	