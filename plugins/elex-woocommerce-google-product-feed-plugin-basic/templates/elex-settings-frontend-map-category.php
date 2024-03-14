<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
	$cat_args = array(
		'hide_empty' => 0,
		'taxonomy' => 'product_cat',
		'hierarchical' => 1,
		'orderby' => 'name',
		'order' => 'ASC',
		'child_of' => 0,
		'pad_counts' => true,

	);
	$cat_hierarchy = elex_gpf_get_cat_hierarchy( 0, $cat_args );
	$category_products_count = get_count_and_category_name();

	$cat_rows = elex_gpf_category_rows( $cat_hierarchy, 0, 'elex_cat_filter', $category_products_count );
	?>
<div id="settings_map_category" >
	<div style="margin: 7px 1px;">
		<div class="elex-gpf-steps-navigator">
			<a href="javascript:void(0);" id ="elex_gpf_sub_map_category" class="elex-select-category elex-select-category-active">
				<?php esc_html_e( 'Map Category', 'elex-product-feed' ); ?>
			</a> |
			<a href="javascript:void(0);" id ="elex_gpf_sub_map_product" class="elex-select-category">
				<?php esc_html_e( 'Map Product', 'elex-product-feed' ); ?>
			</a>    
			<p id="elex_gpf_map_category_edit_text" style="display: none;"><i>If you change the existing Google Category, the mapped Google attributes in the feed may be modified or removed based on Google Category.</i></p>        
		</div>
	</div>
	<div id="settings_map_category_1" class="postbox elex-gpf-table-box elex-gpf-table-box-main ">
		<table>
			<tr>
				<td>
					<h1><?php esc_html_e( 'Map Category', 'elex-product-feed' ); ?></h1>
				</td>
				<td>
					<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Map your WooCommerce product categories with corresponding Google categories. Start typing in the Google category field to get options to map. Please make sure to enable mapping by clicking the corresponding checkbox.', 'elex-product-feed' ); ?>'></span>
				</td>
			</tr>
		</table>
		<table id="elex_cat_table" class="widefat">
		
			<thead>
				<tr>
					<th class="elex-gpf-catmap-checkbox check-column" style="padding-left: inherit;"><input type="checkbox" /></th>
					<th class="elex-gpf-settings-table-cat-map-left"> <b> <?php esc_html_e( 'Product Category', 'elex-product-feed' ); ?> </b> </th>
					<th class="elex-gpf-settings-table-cat-map-middle"> <b> <?php esc_html_e( 'Google Category', 'elex-product-feed' ); ?> </b> </th>
				</tr>
			</thead>
		<?php
		$supported_html = array(
			'a' => array(
				'href' => array(),
				'target' => array(),
			),
		);

		foreach ( $cat_rows as $cat_ID => $cat_name ) {
			if ( '%' == $cat_ID ) {
				$cat_ID = urldecode( $cat_ID );
			}
			?>
				<tr >
					<td  class="elex-gpf-catmap-checkbox check-column"><input name="elex-cat-map-checkbox" value="<?php echo esc_html_e( $cat_ID ); ?>" type="checkbox" /></td>
					<td name="elex-cat-map-name" class="elex-gpf-settings-table-cat-map-left"  > <?php echo wp_kses( $cat_name, $supported_html ); ?></td>
					<td name="elex-cat-map-google_cat" class="elex-gpf-settings-table-cat-map-middle"><div class="elex_google_cats_auto"><input class="typeahead" id="elex_google_cats_<?php echo esc_html_e( $cat_ID ); ?>"  style="width: 100%;" type="text" placeholder="Google Categories"></div> </td>
				</tr>
			<?php
		}


		?>
		</table>
		<div style="margin-top: 1%;">
			<button id="category_back_button" class="botton button-large button-primary" ><?php esc_html_e( 'Back', 'elex-product-feed' ); ?></button>
			<button id="save_settings_cat_map" class="botton button-large button-primary" style="float: right;"><?php esc_html_e( 'Continue', 'elex-product-feed' ); ?></button>
			<button id="reset_settings_cat_map" class="botton button-large button-primary"><?php esc_html_e( 'Reset google categories', 'elex-product-feed' ); ?></button>			
		</div>
	</div>
	<div id="settings_map_product" class="postbox elex-gpf-table-box elex-gpf-table-box-main ">
		
		<table>
			<tr>
				<td>
					<h1><?php esc_html_e( 'Map Product', 'elex-product-feed' ); ?></h1>
				</td>
				<td>
					<span class='woocommerce-help-tip tooltip' data-tooltip='<?php esc_html_e( 'Map your WooCommerce products with corresponding Google categories. Start typing in the Product Field and Google category field to get options to map.', 'elex-product-feed' ); ?>'></span>
				</td>
			</tr>
		</table>
		<input type="hidden" name="" id="elex_map_product_count_id" value="<?php echo 1; ?>">
		<table id="elex_cat_table_product" class=" elex_gfd_table_lines elex_gpf_product_table widefat" style="table-layout: fixed;">
			<thead>
				<tr>                   
					<th class="elex-gpf-settings-table-cat-map-left" style="width: 55%;"> <b> <?php esc_html_e( 'Product ', 'elex-product-feed' ); ?> </b> </th>
					<th class="elex-gpf-settings-table-cat-map-middle" style="width: 40%;"> <b> <?php esc_html_e( 'Google Category', 'elex-product-feed' ); ?> </b> </th>
					<th class="elex-gpf-settings-table-cat-map-right" style="width: 5%; text-align:center !important;"> </th>
				</tr>
			</thead>
			<?php
				$row = '                    
                    <td class="elex-gpf-settings-table-cat-map-left" style="width: 55%;">
                        <select class="wc-product-search elex_gpf_include_products" multiple="multiple" style="width: 100%;height:30px" name="elex_gpf_include_products[]" data-placeholder="' . esc_attr__( 'Search for a product&hellip;', 'elex-product-feed' ) . '" data-action="woocommerce_json_search_products_and_variations"></select>
                    </td>
                    
                    <td class="elex-gpf-settings-table-cat-map-middle elex_google_cats_auto " style="width: 40%;">                        
                        <div class="elex_google_cats_auto" >    
                            <input class="typeahead  elex_gpf_product_google_cat" name="elex_gpf_product_google_cats[]"  style="width: 153%;" type="text" placeholder="Google Categories">                        
                        </div>
                    </td>
                    <td class="elex-gpf-settings-table-cat-map-right" style="width: 5%; text-align:center !important ;"> 
                        <a href="javascript:void(0);"> 
                            <span class="elex-gpf-icon elex-gpf-minus-icon elex-gpf-map-product-remove" title="Remove" style="display: inline-block;" >
                            </span>
                        </a> 
                    </td>
                ';
			?>
			<tbody id="elex_map_product_tbody_id" data-row="<?php echo esc_attr( $row ); ?>">
			   
			</tbody>
`    
		</table>
		<div style="margin-top: 1%;">
			<button id="category_back_button_product" class="botton button-large button-primary" ><?php esc_html_e( 'Back', 'elex-product-feed' ); ?></button>
			<button  class=" elex-gpf-map-product-add botton button-large button-primary" ><?php esc_html_e( 'Add More Rows', 'elex-product-feed' ); ?></button>
			<button id="save_settings_cat_map_product" class="botton button-large button-primary" style="float: right;"><?php esc_html_e( 'Save & Continue', 'elex-product-feed' ); ?></button>
		</div>
		
	</div>
</div>
<?php
include_once ELEX_PRODUCT_FEED_TEMPLATE_PATH . '/elex-settings-frontend-map-attributes.php';

function elex_gpf_get_cat_hierarchy( $parent, $args ) {
	$cats = get_categories( $args );
	$ret = new stdClass();
	foreach ( $cats as $cat ) {
		if ( $cat->parent == $parent ) {
			$id = $cat->cat_ID;
			$ret->$id = $cat;
			$ret->$id->children = elex_gpf_get_cat_hierarchy( $id, $args );
		}
	}
	return $ret;
}

function elex_gpf_category_rows( $categories, $level, $name, $category_products_count ) {
	$html_code = array();
	$html_code_new = array();
   $level_indicator = '';
	for ( $i = 0; $i < $level; $i++ ) {
		$level_indicator .= '- ';
	}
	if ( $categories ) {
		foreach ( $categories as $category ) {
			$category->count = isset( $category_products_count[ $category->term_id ] ) ? $category_products_count[ $category->term_id ] : 0;
			$html_code[ $category->cat_ID ] = $level_indicator . $category->name . ' <a target = "_blank" href= ' . home_url() . '/wp-admin/edit.php?product_cat=' . $category->slug . '&post_type=product>(' . $category->count . ')</a>' ;

			if ( $category->children && count( (array) $category->children ) > 0 ) {
				$html_code_new = elex_gpf_category_rows( $category->children, $level + 1, $name, $category_products_count );
				$key = array_keys( $html_code_new );
				$val = array_values( $html_code_new );
				for ( $index = 0;$index < count( $key );$index++ ) {
					$html_code[ $key[ $index ] ] = $val[ $index ];
				   
				}           
			}
		}
	} else {
		$html_code = esc_html__( 'No categories found.', 'elex-product-feed' );
	}

	   return $html_code;

}
//Simple product count.

function get_count_and_category_name() {
	global $wpdb;
	
	$results = $wpdb->get_results( 
		"
    SELECT COUNT(p.ID) AS count, t.term_id
    FROM {$wpdb->prefix}posts AS p
    INNER JOIN {$wpdb->prefix}term_relationships AS tr ON ( p.ID = tr.object_id OR p.post_parent = tr.object_id )
    INNER JOIN {$wpdb->prefix}term_taxonomy AS tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
    INNER JOIN {$wpdb->prefix}terms AS t ON tt.term_id = t.term_id
    LEFT JOIN {$wpdb->prefix}postmeta AS pm ON p.ID = pm.post_id
    WHERE p.post_type = 'product'
      AND p.post_status = 'publish'
      AND pm.meta_key = '_manage_stock'
      AND pm.meta_value = 'no'
      AND p.ID NOT IN (
        SELECT DISTINCT post_parent
        FROM {$wpdb->prefix}posts
        WHERE post_type = 'product_variation'
      )
      AND p.ID NOT IN (
        SELECT DISTINCT tr.object_id
        FROM {$wpdb->prefix}term_relationships AS tr
        INNER JOIN {$wpdb->prefix}term_taxonomy AS tx ON tr.term_taxonomy_id = tx.term_taxonomy_id
        WHERE tx.taxonomy = 'product_type'
          AND tx.term_id = (SELECT t.term_id FROM {$wpdb->prefix}terms AS t WHERE t.slug = 'variable')
      )
      AND tt.taxonomy = 'product_cat'
    GROUP BY t.term_id"
	);
	
	$count_and_name = wp_list_pluck( $results, 'count', 'term_id' );
	return $count_and_name;
}
