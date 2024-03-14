<?php

/**
 * Tatvic Category Wrapper Class.
 */
if ( ! class_exists( 'Tatvic_Category_Wrapper' ) ) :

	class Tatvic_Category_Wrapper {

		/**
		 * Returns the code for the category mapping, containing all shop categories as rows.
		 *
		 * @param  string   $mode   displays a normal category selector or a category mapping selector when 'mapping' is given. Default = 'normal'.
		 * @return string
		 */
		public function category_table_content_old( $mode = 'normal' ) {
			$shop_categories = TVC_Taxonomies::get_shop_categories_list();
			return $this->category_rows( $shop_categories, 0, $mode );
		}

		/**
		 * Returns the code for the product filter.
		 *
		 * @return string
		 */
		/*public function product_filter() {
			return Tatvic_Category_Selector_Element::product_filter_selector();
		}*/

		public function category_rows( $shop_categories, $category_depth_level, $mode ) {
			if (!class_exists('Tatvic_Category_Selector_Element')) {
            	require_once(__DIR__ . '/class-tatvic-category-selector-element.php');
        	}
      $ee_prod_mapped_cats = unserialize(get_option('ee_prod_mapped_cats'));
			//$ee_prod_mapped_attrs = unserialize(get_option('ee_prod_mapped_attrs'));

			$TCSE_Obj = new Tatvic_Category_Selector_Element();
			$html_code       = '';
			$level_indicator = '';

			for ( $i = 0; $i < $category_depth_level; $i ++ ) {
				$level_indicator .= '— ';
			}
			
			if (!empty((array)$shop_categories ) ) {
				foreach ( $shop_categories as $category ) {

					$category_children = $this->get_sub_categories( $category );

					$html_code .= $TCSE_Obj->category_mapping_row( $category, $category_children, $level_indicator, $mode, $ee_prod_mapped_cats );

					if ( $category->children && count( (array) $category->children ) > 0 ) {
						$html_code .= self::category_rows( $category->children, $category_depth_level + 1, $mode );
					}
				}
			} else {
				$html_code .= esc_html__( 'No shop categories found.', 'enhanced-e-commerce-for-woocommerce-store' );
			}
			return $html_code;
		}

		public function get_sub_categories( $category ) {
			$array_string = '';

			if ( $category->children && count( (array) $category->children ) ) {
				$array_string .= '[';

				foreach ( $category->children as $child ) {
					$array_string .= $child->term_id . ', ';
				}

				$array_string  = substr( $array_string, 0, - 2 );
				$array_string .= ']';
			}

			return $array_string;
		}

		public function category_table_content( $category, $category_depth_level, $mode ) {
			if (!class_exists('Tatvic_Category_Selector_Element')) {
            	require_once(__DIR__ . '/class-tatvic-category-selector-element.php');
        	}
      		$ee_prod_mapped_cats = unserialize(get_option('ee_prod_mapped_cats'));

			$TCSE_Obj = new Tatvic_Category_Selector_Element();
			$html_code       = '';
			$level_indicator = '';

			for ( $i = 0; $i < $category_depth_level; $i ++ ) {
				$level_indicator .= '— ';
			}
			
			$args = array(
				'parent' 	=> $category,
				'hide_empty'    => false,
				'no_found_rows' => true,
			);
			// $path = ENHANCAD_PLUGIN_DIR . 'includes/setup/json/category.json';
    		// $str = file_get_contents($path);
    		// $category_json = $str ? json_decode($str, true) : [];
			
			$option = '<option value="0"> Select category </option>';
			// foreach($category_json as $key => $value){
			// 	$option .= '<option value="'.esc_attr($value['id']).'"> '.esc_attr($value['name']).' </option>';
			// }
	
			$shop_categories = get_terms('product_cat', $args);
			if (!empty((array)$shop_categories ) ) {
				foreach ( $shop_categories as $category ) {
					$html_code .= $TCSE_Obj->category_mapping_row( $category, $level_indicator, $mode, $ee_prod_mapped_cats, $option );
					if ( $category->term_id !== 0) {
						$html_code .= self::category_table_content( $category->term_id, $category_depth_level + 1, $mode );
					}
				}
			} else if ($category == 0 ) {
				$html_code .= esc_html__( 'No shop categories found.', 'enhanced-e-commerce-for-woocommerce-store' );
			}
			return $html_code;
		}
	}
endif;