<?php
function directorypress_get_term_parents($id, $tax, $link = false, $return_array = false, $separator = '/', &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		if ($return_array) {
			return array();
		} else { 
			return '';
		}
	}

	$name = $parent->name;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		directorypress_get_term_parents($parent->parent, $tax, $link, $return_array, $separator, $chain);
	}

	$url = get_term_link($parent->slug, $tax);
	if ($link && !is_wp_error($url)) {
		$chain[] = '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="' . esc_url($url) . '" title="' . esc_attr(sprintf(__('View all listings in %s', 'DIRECTORYPRESS'), $name)) . '"><span itemprop="name">' . esc_html($name) . '</span></a></li>';
	} else {
		$chain[] = $name;
	}
	
	if ($return_array) {
		return $chain;
	} else {
		return implode($separator, $chain);
	}
}

function directorypress_get_term_parents_slugs($id, $tax, &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		return '';
	}

	$slug = $parent->slug;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		directorypress_get_term_parents_slugs($parent->parent, $tax, $chain);
	}

	$chain[] = $slug;

	return $chain;
}

function directorypress_get_term_parents_ids($id, $tax, &$chain = array()) {
	$parent = get_term($id, $tax);
	if (is_wp_error($parent) || !$parent) {
		return '';
	}

	$id = $parent->term_id;
	
	if ($parent->parent && ($parent->parent != $parent->term_id)) {
		directorypress_get_term_parents_ids($parent->parent, $tax, $chain);
	}

	$chain[] = $id;

	return $chain;
}

function directorypress_is_anyone_in_taxonomy($tax) {
	return count(get_categories(array('taxonomy' => $tax, 'hide_empty' => false, 'parent' => 0, 'number' => 1)));
}

function directorypress_get_term_by_path($term_path, $full_match = true, $output = OBJECT) {
	$term_path = rawurlencode( urldecode( $term_path ) );
	$term_path = str_replace( '%2F', '/', $term_path );
	$term_path = str_replace( '%20', ' ', $term_path );

	global $wp_rewrite;
	if ($wp_rewrite->using_permalinks()) {
		$term_paths = '/' . trim( $term_path, '/' );
		$leaf_path  = sanitize_title( basename( $term_paths ) );
		$term_paths = explode( '/', $term_paths );
		$full_path = '';
		foreach ( (array) $term_paths as $pathdir )
			$full_path .= ( $pathdir != '' ? '/' : '' ) . sanitize_title( $pathdir );
	
		//$terms = get_terms( array(DIRECTORYPRESS_CATEGORIES_TAX, DIRECTORYPRESS_LOCATIONS_TAX, DIRECTORYPRESS_TAGS_TAX), array('get' => 'all', 'slug' => $leaf_path) );
		$terms = array();
		if ($term = get_term_by('slug', $leaf_path, DIRECTORYPRESS_CATEGORIES_TAX))
			$terms[] = $term;
		if ($term = get_term_by('slug', $leaf_path, DIRECTORYPRESS_TYPE_TAX))
			$terms[] = $term;
		if ($term = get_term_by('slug', $leaf_path, DIRECTORYPRESS_LOCATIONS_TAX))
			$terms[] = $term;
		if ($term = get_term_by('slug', $leaf_path, DIRECTORYPRESS_TAGS_TAX))
			$terms[] = $term;
	
		if ( empty( $terms ) )
			return null;
	
		foreach ( $terms as $term ) {
			$path = '/' . $leaf_path;
			$curterm = $term;
			while ( ( $curterm->parent != 0 ) && ( $curterm->parent != $curterm->term_id ) ) {
				$curterm = get_term( $curterm->parent, $term->taxonomy );
				if ( is_wp_error( $curterm ) )
					return $curterm;
				$path = '/' . $curterm->slug . $path;
			}

			if ( $path == $full_path ) {
				$term = get_term( $term->term_id, $term->taxonomy, $output );
				_make_cat_compat( $term );
				return $term;
			}
		}
	
		// If full matching is not required, return the first cat that matches the leaf.
		if ( ! $full_match ) {
			$term = reset( $terms );
			$term = get_term( $term->term_id, $term->taxonomy, $output );
			_make_cat_compat( $term );
			return $term;
		}
	} else {
		if ($term = get_term_by('slug', $term_path, DIRECTORYPRESS_CATEGORIES_TAX))
			return $term;
		if ($term = get_term_by('slug', $term_path, DIRECTORYPRESS_TYPE_TAX))
			return $term;
		if ($term = get_term_by('slug', $term_path, DIRECTORYPRESS_LOCATIONS_TAX))
			return $term;
		if ($term = get_term_by('slug', $term_path, DIRECTORYPRESS_TAGS_TAX))
			return $term;
	}

	return null;
}

function directorypress_isCategory() {
	global $directorypress_object;

	if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE))) {
		if ($directorypress_directory_handler->is_category) {
			return $directorypress_directory_handler->category;
		}
	}
}

function directorypress_isLocation() {
	global $directorypress_object;

	if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE))) {
		if ($directorypress_directory_handler->is_location) {
			return $directorypress_directory_handler->location;
		}
	}
}

function directorypress_isTag() {
	global $directorypress_object;

	if (($directorypress_directory_handler = $directorypress_object->directorypress_get_property_of_shortcode(DIRECTORYPRESS_MAIN_SHORTCODE))) {
		if ($directorypress_directory_handler->is_tag) {
			return $directorypress_directory_handler->tag;
		}
	}
}

function directorypress_is_locationsEditPageInAdmin() {
	global $pagenow;

	if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_LOCATIONS_TAX)))) {
		return true;
	}
}

function directorypress_is_categoriesEditPageInAdmin() {
	global $pagenow;

	if (($pagenow == 'edit-tags.php' || $pagenow == 'term.php') && ($taxonomy = directorypress_get_input_value($_GET, 'taxonomy')) &&
				(in_array($taxonomy, array(DIRECTORYPRESS_CATEGORIES_TAX)))) {
		return true;
	}
}

function directorypress_tax_dropdowns_menu_init($params) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	$attrs = array_merge(array(
			'uID' => 0,
			'field_name' => '',
			'count' => true,
			'tax' => 'category',
			'hide_empty' => false,
			'exact_terms' => array(),
			'autocomplete_field' => '',
			'autocomplete_field_value' => '',
			'autocomplete_ajax' => false,
			'field_icon' => '',
			'placeholder' => '',
			'depth' => 1,
			'term_id' => 0,
	), $params);
	extract($attrs);
	
	// unique ID need when we place some dropdowns groups on one page
	if (!$uID) {
		$uID = rand(1, 10000);
	}
	
	if (!$field_name) {
		$field_name = 'selected_tax[' . $uID . ']';
	}
	
	// we use array_merge with empty array because we need to flush keys in terms array
	if ($count) {
		$terms = array_merge(
				// there is a wp bug with pad_counts in get_terms function - so we use this construction
				wp_list_filter(
						get_categories(array(
								'taxonomy' => $tax,
								'pad_counts' => true,
								'hide_empty' => $hide_empty,
						)),
						array('parent' => 0)
				), array());
	} else {
		$terms = array_merge(
				get_categories(array(
						'taxonomy' => $tax,
						'pad_counts' => true,
						'hide_empty' => $hide_empty,
						'parent' => 0,
				)), array());
	}
	
	if ($terms) {
		foreach ($terms AS $id=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$id]);
			}
		}
		
		// when selected exact sub-categories of non-root category
		if (empty($terms) && !empty($exact_terms)) {
			if ($count) {
				// there is a wp bug with pad_counts in get_terms function - so we use this construction
				$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty)));
			} else {
				$terms = get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty));
			}
		}
		
		$selected_tax_text = '';
		if ($term_id) {
			$term = get_term($term_id);
			$selected_tax_text = $term->name;
			$parents = directorypress_get_term_parents($term_id, $tax, false, false, ', ');
			if ($parents) {
				$selected_tax_text .= ', ' . $parents;
			}
		}
		
		echo '<div id="directorypress-tax-dropdowns-wrap-' . esc_attr($uID) . '" class="directorypress-tax-dropdowns-wrap">';
		echo '<input type="hidden" name="' . esc_attr($field_name) . '" id="selected_tax[' . esc_attr($uID) . ']" class="selected_tax_' . esc_attr($tax) . '" value="' . esc_attr($term_id) . '" />';
		echo '<input type="hidden" name="' . esc_attr($field_name) . '_text" id="selected_tax_text[' . esc_attr($uID) . ']" class="selected_tax_text_' . esc_attr($tax) . '" value="' . esc_attr($selected_tax_text) . '" />';
		if ($exact_terms) {
			echo '<input type="hidden" id="exact_terms[' . esc_attr($uID) . ']" value="' . addslashes(implode(',', $exact_terms)) . '" />';
		}
		if ($autocomplete_field) {
			$autocomplete_data = 'data-autocomplete-name="' . esc_attr($autocomplete_field) . '" data-autocomplete-value="' . esc_attr($autocomplete_field_value) . '"';
			if ($autocomplete_ajax) {
				$autocomplete_data .= ' data-ajax-search=1';
			}
		} else {
			$autocomplete_data = '';
		}
		echo '<select class="directorypress-form-control directorypress-selectmenu-' . esc_attr($tax) . '" data-icon="'. esc_attr($attrs['field_icon']) .'" data-id="' . esc_attr($uID) . '" data-placeholder="' . esc_attr($placeholder) . '" ' . wp_kses_post($autocomplete_data) . '>';
		foreach ($terms AS $term) {
			if ($count) {
				$term_count = 'data-count="' . $term->count . '"';
			} else {
				$term_count = '';
			}
			if ($term->term_id == $term_id) {
				$selected = 'data-selected="selected"';
			} else {
				$selected = '';
			}
			if($tax == 'directorypress-category'){
				if($DIRECTORYPRESS_ADIMN_SETTINGS['search_cat_icon_type'] == 'font'){
					$search_icon_type = 'font';
				}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['search_cat_icon_type'] == 'img'){
					$search_icon_type = 'img';
				}else{
					$search_icon_type = 'img';
				}
			}else{
				$search_icon_type = 'img';
			}
			if($search_icon_type == 'img'){
				$icon_type = 'img';
				$icon_color = '';
				if($tax == 'directorypress-category'){
					$icon = 'data-icon="' . get_listing_category_icon_url($term->term_id) . '"';
				}else{
					$icon = 'data-icon="' . get_listing_location_icon_url($term->term_id) . '"';
				}
				
			}else{
				$icon_type = 'font';
				if($tax == 'directorypress-category'){	
					$icon_color = get_listing_category_color($term->term_id);
					$icon = 'data-icon="'.get_listing_category_font_icon($term->term_id).'"';
				}else{
					$icon_color = '';
					$icon = 'dicode-material-icons dicode-material-icons-map-marker-outline';
				}
			}
			
			$option_id_inc = uniqid();
			echo '<option id="' . esc_attr($term->slug) . esc_attr($option_id_inc) . '" value="' . esc_attr($term->term_id) . '" data-level="1" data-fonticolor="'. esc_attr($icon_color) .'" data-name="' . esc_attr($term->name)  . '" data-sublabel="" ' . wp_kses_post($selected) . ' ' . wp_kses_post($icon) . ' data-icontype="'. esc_attr($icon_type) .'" ' . wp_kses_post($term_count) . '>' . esc_html($term->name) . '</option>';
			if ($depth > 1) {
				//echo '<optgroup label="Scripts">';
				echo _directorypress_tax_dropdowns_menu($tax, $term->term_id, $depth, 1, $term_id, $count, $exact_terms, $hide_empty); // phpcs:ignore WordPress.Security.EscapeOutput
			//echo '</optgroup>';
			}
		}
		echo '</select>';
		echo '</div>';
	}
}

function _directorypress_tax_dropdowns_menu($tax, $parent = 0, $depth = 2, $current_level = 1, $term_id = null, $count = false, $exact_terms = array(), $hide_empty = false) {
	global $DIRECTORYPRESS_ADIMN_SETTINGS;
	if ($count) {
		// there is a wp bug with pad_counts in get_terms function - so we use this construction
		$terms = wp_list_filter(
				get_categories(array(
						'taxonomy' => $tax,
						'pad_counts' => true,
						'hide_empty' => $hide_empty,
				)),
				array('parent' => $parent)
		);
	} else {
		$terms = get_categories(array(
				'taxonomy' => $tax,
				'pad_counts' => true,
				'hide_empty' => $hide_empty,
				'parent' => $parent,
		));
	}
	
	$html = '';
	if ($terms && ($depth == 0 || !is_numeric($depth) || $depth > $current_level)) {
		foreach ($terms AS $key=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$key]);
			}
		}
	
		if ($terms) {
			$current_level++;
			
			$sublabel = directorypress_get_term_parents($term->parent, $tax, false, false, ', ');

			foreach ($terms AS $term) {
				if ($count) {
					$term_count = 'data-count="' . $term->count . '"';
				} else {
					$term_count = '';
				}
				if ($term->term_id == $term_id) {
					$selected = 'data-selected="selected"';
				} else {
					$selected = '';
				}
				
			if($tax == 'directorypress-category'){
				if($DIRECTORYPRESS_ADIMN_SETTINGS['search_cat_icon_type'] == 'font'){
					$search_icon_type = 'font';
				}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['search_cat_icon_type'] == 'img'){
					$search_icon_type = 'img';
				}elseif($DIRECTORYPRESS_ADIMN_SETTINGS['search_cat_icon_type'] == 'svg'){
					$search_icon_type = 'svg';
				}else{
					$search_icon_type = 'img';
				}
			}else{
				$search_icon_type = 'img';
			}
			if($search_icon_type == 'img'){
				$icon_type = 'img';
				$icon_color = '';
				
				$icon = 'data-icon="' . get_listing_category_icon_url($term->term_id) . '"';
				
			}elseif($search_icon_type == 'font' && $tax == 'directorypress-category'){
				$icon_type = 'font';
				if(!empty(get_listing_category_color($term->term_id))){
					$icon_color = $cat_color_set;
				}else{
					global $DIRECTORYPRESS_ADIMN_SETTINGS;
					$icon_color = $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_primary_color'];
				}
				
				$icon = 'data-icon="'.get_listing_category_font_icon($term->term_id).'"';
				
			}else{
				$icon_color = '';
				$icon = 'directorypress-theme-icon-search';
			}
			$option_id_inc = uniqid();
				echo '<option id="' . esc_attr($term->slug) . esc_attr($option_id_inc) . '" value="' . esc_attr($term->term_id) . '" data-level="'. $current_level .'" data-parent="'. $parent .'" data-fonticolor="'. esc_attr($icon_color) .'" data-name="' . esc_attr($term->name)  . '" data-sublabel="' . esc_attr($sublabel) . '" ' . wp_kses_post($selected) . ' ' . wp_kses_post($icon) . ' data-icontype="' . esc_attr($icon_type) . '" ' . wp_kses_post($term_count) . '>' . esc_html($term->name) . '</option>';
				if ($depth > $current_level) {
					echo _directorypress_tax_dropdowns_menu($tax, $term->term_id, $depth, $current_level, $term_id, $count, $exact_terms, $hide_empty); // phpcs:ignore WordPress.Security.EscapeOutput
				}
			}
		}
	}
	return $html;
}

function directorypress_tax_dropdowns_init($tax = 'category', $field_name = null, $term_id = null, $count = true, $labels = array(), $titles = array(), $uID = null, $exact_terms = array(), $hide_empty = false) {
	// unique ID need when we place some dropdowns groups on one page
	if (!$uID) {
		$uID = rand(1, 10000);
	}

	$localized_data[$uID] = array(
			'labels' => $labels,
			'titles' => $titles
	);
	echo "<script>directorypress_js_instance['tax_dropdowns_" . esc_attr($uID) . "'] = " . json_encode($localized_data) . "</script>";

	if (!is_null($term_id) && $term_id != 0) {
		$chain = array();
		$parent_id = $term_id;
		while ($parent_id != 0) {
			if ($term = get_term($parent_id, $tax)) {
				$chain[] = $term->term_id;
				$parent_id = $term->parent;
			} else {
				break;
			}
		}
	}
	$chain[] = 0;
	$chain = array_reverse($chain);

	if (!$field_name) {
		$field_name = 'selected_tax[' . $uID . ']';
	}

	echo '<div id="directorypress-tax-dropdowns-wrap-' . esc_attr($uID) . '" class="' . esc_attr($tax) . ' cs_count_' . (int)$count . ' cs_hide_empty_' . (int)$hide_empty . ' directorypress-tax-dropdowns-wrap">';
	echo '<input type="hidden" name="' . esc_attr($field_name) . '" id="selected_tax[' . esc_attr($uID) . ']" class="selected_tax_' . esc_attr($tax) . '" value="' . esc_attr($term_id) . '" />';
	if ($exact_terms) {
		echo '<input type="hidden" id="exact_terms[' . esc_attr($uID) . ']" value="' . addslashes(implode(',', $exact_terms)) . '" />';
	}
	foreach ($chain AS $key=>$term_id) {
		if ($count) {
			// there is a wp bug with pad_counts in get_terms function - so we use this construction
			$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty)), array('parent' => $term_id));
		} else {
			$terms = get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty, 'parent' => $term_id));
		}

		if (!empty($terms)) {
			foreach ($terms AS $id=>$term) {
				if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
					unset($terms[$id]);
				}
			}

			// when selected exact sub-categories of non-root category
			if (empty($terms) && !empty($exact_terms)) {
				if ($count) {
					// there is a wp bug with pad_counts in get_terms function - so we use this construction
					$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty)));
				} else {
					$terms = get_categories(array('taxonomy' => $tax, 'include' => $exact_terms, 'pad_counts' => true, 'hide_empty' => $hide_empty));
				}
			}

			if (!empty($terms)) {
				$level_num = $key + 1;
				$select2_class = (!is_admin())? 'directorypress-select2': '';
				echo '<div id="wrap_chainlist_' . esc_attr($level_num) . '_' . esc_attr($uID) . '" class="'. esc_attr($tax) .'-input clearfix">';
					echo '<div class="row">';
						if (isset($labels[$key])) {
							echo '<div class="col-md-12">';
								echo '<label class="directorypress-submit-field-title" for="chainlist_' . esc_attr($level_num) . '_' . esc_attr($uID) . '">' . esc_html($labels[$key]) . '</label>';
							echo '</div>';
						}
						echo '<div class="col-md-12">';
							echo '<select id="chainlist_' . esc_attr($level_num) . '_' . esc_attr($uID) . '" name="' . esc_attr($tax) . '[]" class="directorypress-form-control '. esc_attr($select2_class) .'">';
								echo '<option value="">' . ((isset($titles[$key])) ? esc_html($titles[$key]) : esc_html__('Select term', 'DIRECTORYPRESS')) . '</option>';
								foreach ($terms AS $term) {
									if ($count)
										$term_count = " ($term->count)";
									else
										 $term_count = '';
									if (isset($chain[$key+1]) && $term->term_id == $chain[$key+1]) {
										$selected = 'selected';
									} else
										$selected = '';
											
									if ($icon_file = get_listing_category_icon_url($term->term_id))
										$icon = 'data-class="term-icon" data-icon="' . $icon_file . '"';
									else
										$icon = '';
			
									echo '<option id="' . esc_attr($term->slug) . '" value="' . esc_attr($term->term_id) . '" ' . wp_kses_post($selected) . ' ' . wp_kses_post($icon) . '>' . esc_html($term->name) . esc_html($term_count) . '</option>';
								}
							echo '</select>';
						echo '</div>';
					echo '</div>';
				echo '</div>';
			}
		}
	}
	echo '</div>';
}

function directorypress_tax_dropdowns_updateterms() {
	$parentid = directorypress_get_input_value($_POST, 'parentid');
	$next_level = directorypress_get_input_value($_POST, 'next_level');
	$tax = directorypress_get_input_value($_POST, 'tax');
	$count = directorypress_get_input_value($_POST, 'count');
	$hide_empty = directorypress_get_input_value($_POST, 'hide_empty');
	$exact_terms = array_filter(explode(',', directorypress_get_input_value($_POST, 'exact_terms')));
	$select2_class = (!is_admin())? 'directorypress-select2': '';
	if (!$label = directorypress_get_input_value($_POST, 'label'))
		$label = '';
	if (!$title = directorypress_get_input_value($_POST, 'title'))
		$title = __('Select term', 'DIRECTORYPRESS');
	$uID = directorypress_get_input_value($_POST, 'uID');
	
	if ($hide_empty == 'cs_hide_empty_1') {
		$hide_empty = true;
	} else {
		$hide_empty = false;
	}

	if ($count == 'cs_count_1') {
		// there is a wp bug with pad_counts in get_terms function - so we use this construction
		$terms = wp_list_filter(get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty)), array('parent' => $parentid));
	} else {
		$terms = get_categories(array('taxonomy' => $tax, 'pad_counts' => true, 'hide_empty' => $hide_empty, 'parent' => $parentid));
	}
	if (!empty($terms)) {
		foreach ($terms AS $id=>$term) {
			if ($exact_terms && (!in_array($term->term_id, $exact_terms) && !in_array($term->slug, $exact_terms))) {
				unset($terms[$id]);
			}
		}

		if (!empty($terms)) {
			echo '<div id="wrap_chainlist_' . esc_attr($next_level) . '_' . esc_attr($uID) . '" class="'. esc_attr($tax) .'-input">';
				echo '<div class="row">';
					if ($label) {
						echo '<div class="col-md-12">';
						echo '<label class="directorypress-submit-field-title" for="chainlist_' . esc_attr($next_level) . '_' . esc_attr($uID) . '">' . esc_html($label) . '</label>';
						echo '</div>';
					}
					echo '<div class="col-md-12">';
						echo '<select id="chainlist_' . esc_attr($next_level) . '_' . esc_attr($uID) . '" name="' . esc_attr($tax) . '[]" class="'. esc_attr($select2_class) .' tax-level-'. esc_attr($next_level) .'">';
							echo '<option value="">' . esc_html($title) . '</option>';
							foreach ($terms as $term) {
								if (!$exact_terms || (in_array($term->term_id, $exact_terms) || in_array($term->slug, $exact_terms))) {
									if ($count == 'cs_count_1') {
										$term_count = " ($term->count)";
									} else {
										$term_count = '';
									}
									
									
									$icon = '';
									
									echo '<option id="' . esc_attr($term->slug) . '" value="' . esc_attr($term->term_id) . '" ' . esc_attr($icon) . '>' . esc_html($term->name) . esc_attr($term_count) . '</option>';
								}
							}
						echo '</select>';
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}
	}
	die();
}

function directorypress_renderOptionsTerms($tax, $parent, $selected_terms, $level = 0) {
	$terms = get_terms($tax, array('parent' => $parent, 'hide_empty' => false));

	foreach ($terms AS $term) {
		echo '<option value="' . esc_attr($term->term_id) . '" ' . (($selected_terms && (in_array($term->term_id, $selected_terms) || in_array($term->slug, $selected_terms))) ? 'selected' : '') . '>' . (str_repeat('&nbsp;&nbsp;&nbsp;', $level)) . esc_html($term->name) . '</option>';
		directorypress_renderOptionsTerms($tax, $term->term_id, $selected_terms, $level+1);
	}
	return $terms;
}

function directorypress_termsSelectList($name, $tax = 'category', $selected_terms = array()) {
	echo '<select multiple="multiple" name="' . esc_attr($name) . '[]" class="selected_terms_list directorypress-form-control directorypress-form-group directorypress-select2" style="height: 300px">';
	echo '<option value="" ' . ((!$selected_terms) ? 'selected' : '') . '>' . __('- Select All -', 'DIRECTORYPRESS') . '</option>';

	directorypress_renderOptionsTerms($tax, 0, $selected_terms);

	echo '</select>';
}


function directorypress_renderSubCategories($parent_category_slug = '', $columns = 2, $count = false) {
	if ($parent_category = directorypress_get_term_by_path($parent_category_slug))
		$parent_category_id = $parent_category->term_id;
	else
		$parent_category_id = 0;
	
	directorypress_renderAllCategories($parent_category_id, 1, $columns, $count);
}

function directorypress_renderSubLocations($parent_location_slug = '', $columns = 2, $count = false) {
	if ($parent_location = directorypress_get_term_by_path($parent_location_slug))
		$parent_location_id = $parent_location->term_id;
	else
		$parent_location_id = 0;
	
	directorypress_renderAllLocations($parent_location_id, 1, $columns, $count);
}

function directorypress_terms_checklist($listing, $parent_allowed) {
	$post_id = $listing->post->ID;
	$package = $listing->package;
	$selected_categories = $package->selected_categories;
	
	//print_r($selected_categories);
	
	$terms = get_categories(array('taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX, 'pad_counts' => true, 'hide_empty' => false, 'parent' => 0, 'include' => $selected_categories));
	$checked_categories_ids = array();
	$checked_categories = wp_get_object_terms($post_id, DIRECTORYPRESS_CATEGORIES_TAX);
	//$checked_tags = wp_get_object_terms($post_id, DIRECTORYPRESS_CATEGORIES_TAX);
	foreach ($checked_categories AS $term) {
		$checked_categories_ids[] = $term->term_id;
		//$checked_tags_names[] = $term->name;
	}

	echo '<select name="' . DIRECTORYPRESS_CATEGORIES_TAX . '[]" multiple="multiple" class="directorypress-select2 category_selction">';
	foreach ($terms AS $term) {
		$checked = '';
		if ( count( get_term_children( $term->term_id, DIRECTORYPRESS_CATEGORIES_TAX ) ) > 0 && !$parent_allowed ) {
			$parent_status = 'disabled';
		}else{
			$parent_status = '';
		}
		if (in_array($term->term_id, $checked_categories_ids))
			$checked = 'selected';
		echo '<option value="' . esc_attr($term->term_id) . '" ' . esc_attr($checked) . ' '.esc_attr($parent_status).'>' . esc_html($term->name) . '</option>';
		echo _directorypress_terms_checklist($selected_categories, $term->term_id, $checked_categories_ids);
	}
	echo '</select>';
}
function _directorypress_terms_checklist($selected_categories, $parent = 0, $checked_categories_ids = array()) {
	$html = '';
	$child_items = array_diff($selected_categories, array($parent));
	if ($terms = get_categories(array('taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX, 'pad_counts' => true, 'hide_empty' => false, 'parent' => $parent, 'include' => $child_items))) {
		//$html .= '<ul class="children">';
		$html = '';
		foreach ($terms AS $term) {
			$checked = '';
			if (in_array($term->term_id, $checked_categories_ids)) {
				$checked = 'selected';
			}

			$html .= '<optgroup id="' . DIRECTORYPRESS_CATEGORIES_TAX . '-' . esc_attr($term->term_id) . '">';
			$html .= '<option ' . esc_attr($checked) . ' id="in-' . DIRECTORYPRESS_CATEGORIES_TAX . '-' . esc_attr($term->term_id) . '" value="' . esc_attr($term->term_id) . '"> ' . esc_html($term->name) . '</option>';
			$html .= _directorypress_terms_checklist($selected_categories, $term->term_id, $checked_categories_ids);
			$html .= '</optgroup>';
		}
	}
	return $html;
}
function directorypress_category_selectbox($listing) {
	$uID = $uID = rand(1, 10000);
	$post_id = $listing->post->ID;
	$selected_categories_ids = array();
	$selected_categories = wp_get_object_terms($post_id, DIRECTORYPRESS_CATEGORIES_TAX);
	foreach ($selected_categories AS $term) {
		$selected_categories_ids[] = $term->term_id;
	}
	$selected_term_id = end($selected_categories_ids);
	//var_dump($selected_term_ids);
	directorypress_tax_dropdowns_init(
		DIRECTORYPRESS_CATEGORIES_TAX,
		'directorypress-category[]',
		$selected_term_id,
		false,
		'',
		'',
		$uID,
		$listing->package->selected_categories,
		false
	);
}
function directorypress_tags_selectbox($post_id) {
	$terms = get_categories(array('taxonomy' => DIRECTORYPRESS_TAGS_TAX, 'pad_counts' => true, 'hide_empty' => false));
	$checked_tags_ids = array();
	$checked_tags_names = array();
	$checked_tags = wp_get_object_terms($post_id, DIRECTORYPRESS_TAGS_TAX);
	foreach ($checked_tags AS $term) {
		$checked_tags_ids[] = $term->term_id;
		$checked_tags_names[] = $term->name;
	}

	echo '<select name="' . DIRECTORYPRESS_TAGS_TAX . '[]" multiple="multiple" class="directorypress-token">';
	foreach ($terms AS $term) {
		$checked = '';
		if (in_array($term->term_id, $checked_tags_ids))
			$checked = 'selected';
		echo '<option value="' . esc_attr($term->name) . '" ' . esc_attr($checked) . '>' . esc_html($term->name) . '</option>';
	}
	echo '</select>';
}

function directorypress_categoriesOfLevels($allowed_packages = array()) {
	global $directorypress_object;
	
	$allowed_categories = array();
	foreach ((array) $allowed_packages AS $package_id) {
		if (isset($directorypress_object->packages->packages_array[$package_id])) {
			$package = $directorypress_object->packages->packages_array[$package_id];
			$allowed_categories = array_merge($allowed_categories, $package->selected_categories);
		}
	}
	
	return $allowed_categories;
}

function directorypress_displayCategoriesTable($category_id = 0) {
	global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;

	if ($directorypress_object->current_directorytype->categories) {
		$exact_categories = $directorypress_object->current_directorytype->categories;
	} else {
		$exact_categories = array();
	}
	if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 2){
		$params = array(
				'parent' => $category_id,
				'depth' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_categories_depth'],
				'hide_empty' => 0,
				'columns' => 1,
				'count' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_category_count'],
				'max_subterms' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_subcategories_items'],
				'exact_terms' => $exact_categories,
				'menu' => 0,
				'cat_style' =>  'default',
				'cat_icon_type' => $DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type']
		);
	}else{
		$params = array(
				'parent' => $category_id,
				'depth' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_categories_depth'],
				'hide_empty' => 0,
				'columns' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_categories_columns'],
				'count' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_category_count'],
				'max_subterms' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_subcategories_items'],
				'exact_terms' => $exact_categories,
				'menu' => 0,
				'cat_style' =>  $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_categories_style'],
				'cat_icon_type' => $DIRECTORYPRESS_ADIMN_SETTINGS['cat_icon_type']
		);
	}
	$categories = new DirectoryPress_Category_Terms($params);
	$categories->display();
}

function directorypress_displayLocationsTable($location_id = 0) {
	global $directorypress_object, $DIRECTORYPRESS_ADIMN_SETTINGS;

	if ($directorypress_object->current_directorytype->locations) {
		$exact_locations = $directorypress_object->current_directorytype->locations;
	}else {
		$exact_locations = array();
	}
	if($DIRECTORYPRESS_ADIMN_SETTINGS['archive_page_style'] == 2){
		$params = array(
				'parent' => $location_id,
				'depth' => 2,
				'hide_empty' => 0,
				'columns' => 1,
				'count' => 1,
				'max_subterms' => 0,
				'exact_terms' => $exact_locations,
				'menu' => 0,
				'location_style' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_location_style'],
				'location_padding' => 0,
		);
	}else{
		$params = array(
				'parent' => $location_id,
				'depth' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_locations_depth'],
				'hide_empty' => 0,
				'columns' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_locations_columns'],
				'count' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_show_location_count'],
				'max_subterms' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_sublocations_items'],
				'exact_terms' => $exact_locations,
				'menu' => 0,
				'location_style' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_location_style'],
				'location_padding' => $DIRECTORYPRESS_ADIMN_SETTINGS['directorypress_location_padding'],
		);
	}
	$locations = new DirectoryPress_Location_Terms($params);
	$locations->display();
}