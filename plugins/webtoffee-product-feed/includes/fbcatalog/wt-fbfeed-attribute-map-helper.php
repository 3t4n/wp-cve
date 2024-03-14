<?php

/**
 * Attribute Mapping helper
 *
 * @link       https://webtoffee.com/
 * @since      1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	die();
}


// Category mapping.
if ( ! function_exists( 'wt_fbfeed_render_attributes' ) ) {
    /**
     * Get Product Categories
     *
     * @param int    $parent Parent ID.
     * @param string $par separator.
     * @param string $value mapped values.
     */
    function wt_fbfeed_render_attributes( $parent = 0, $par = '', $value = '' ) {
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
        //$categories   = get_categories( $category_args );
		
		$all_attributes = WT_Fb_Catalog_Manager_Settings::get_all_wc_attributes();
		

        if ( ! empty( $all_attributes ) ) {
            if ( ! empty( $par ) ) {
                $par = $par . ' > ';
            }
			
			
            foreach ( $all_attributes as $cat ) {
                $class = $parent ? "treegrid-parent-{$parent} category-mapping" : 'treegrid-parent category-mapping';
                ?>
                <tr class="treegrid-1 ">
                    <th>
                        <label for="cat_mapping_<?php echo esc_attr( $cat->term_id ); ?>"><?php echo esc_html( $par . $cat->name ); ?></label>
                    </th>
                    <td><!--suppress HtmlUnknownAttribute -->
						
                        <select name="map_to[<?php echo esc_attr( $cat->term_id ); ?>]">
                                <?php echo wt_fb_category_dropdown(); ?>
                            </select>
                    </td>
                </tr>
                <?php
                // call for child category if any.
                wt_fbfeed_render_categories( $cat->term_id, $par . $cat->name, $value );
            }
        }
    }
}



	