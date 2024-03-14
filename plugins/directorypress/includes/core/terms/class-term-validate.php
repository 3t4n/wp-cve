<?php
class directorypress_terms_validator {
	
	public function __construct() {
		//add_action('add_meta_boxes', array($this, 'removeCategoriesMetabox'));
		//add_action('add_meta_boxes', array($this, 'addCategoriesMetabox'));
		
		add_filter('manage_directorypress-category_custom_column', array($this, 'taxonomy_rows'), 15, 3);
		add_filter('manage_edit-directorypress-category_columns',  array($this, 'taxonomy_columns'));
		
	}
	
	public function taxonomy_columns($original_columns) {
		$new_columns = $original_columns;
		array_splice($new_columns, 4);
		$new_columns['directorypress_category_configuration'] = __('Configuration', 'W2DC');
		return array_merge($new_columns, $original_columns);
	}
	
	public function taxonomy_rows($row, $column_name, $term_id) {
		
		if ($column_name == 'directorypress_category_configuration') {
			//$url = $this->get_featured_image_url($term_id);
			return $row . '<a class="directorypress-terms-admin-configuration" href="#" term_id="'.$term_id.'" data-toggle="modal" data-target="#directorypress_terms_configure">'. __("Configure", "DIRECTORYPRESS") . '</a>';
		}
		
		return $row;
	}
	// remove native locations taxonomy metabox from sidebar
	public function removeCategoriesMetabox() {
		remove_meta_box(DIRECTORYPRESS_CATEGORIES_TAX . 'div', DIRECTORYPRESS_POST_TYPE, 'side');
	}

	public function addCategoriesMetabox($post_type) {
		if ($post_type == DIRECTORYPRESS_POST_TYPE && ($package = directorypress_pull_current_listing_admin()->package) && $package->category_number_allowed > 0) {
			//add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts_styles'));

			add_meta_box(DIRECTORYPRESS_CATEGORIES_TAX,
					__('Listing categories', 'DIRECTORYPRESS'),
					'post_categories_meta_box',
					DIRECTORYPRESS_POST_TYPE,
					'normal',
					'high',
					array('taxonomy' => DIRECTORYPRESS_CATEGORIES_TAX));
		}
	}
	public function validateCategories($package, &$postarr, &$errors) {
		global $directorypress_object;
		if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX][0]) && $postarr[DIRECTORYPRESS_CATEGORIES_TAX] == 0){
			unset($postarr[DIRECTORYPRESS_CATEGORIES_TAX][0]);
		}
		
		if (
			$directorypress_object->fields->get_field_by_slug('categories_list')->is_required &&
			(
			!isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!array_filter($postarr[DIRECTORYPRESS_CATEGORIES_TAX])
			)
		){
			$errors[] = __('Select at least one category!', 'DIRECTORYPRESS');
		}
		if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && count($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) > $package->category_number_allowed && $directorypress_object->fields->get_field_by_slug('categories_list')->is_multiselect){
			$errors[] = __('You can not select categories more than!', 'DIRECTORYPRESS').' '. $package->category_number_allowed;
		}
		if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX])) {
			if($directorypress_object->fields->get_field_by_slug('categories_list')->is_multiselect){
				$postarr[DIRECTORYPRESS_CATEGORIES_TAX] = array_slice($postarr[DIRECTORYPRESS_CATEGORIES_TAX], 0, $package->category_number_allowed, true);
			}else{
				$postarr[DIRECTORYPRESS_CATEGORIES_TAX] = array_slice($postarr[DIRECTORYPRESS_CATEGORIES_TAX], 0, 5, true);
			}
			
			if ($package->selected_categories && array_diff($postarr[DIRECTORYPRESS_CATEGORIES_TAX], $package->selected_categories)){
				$errors[] = __('Sorry, you can not choose some categories for this package!', 'DIRECTORYPRESS');
			}
			$post_categories_ids = $postarr[DIRECTORYPRESS_CATEGORIES_TAX];
		} else{
			$post_categories_ids = array();
		}
		return $post_categories_ids;
	}
	public function validateCategories2($package, &$postarr, &$errors) {
		global $directorypress_object;
		if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX][0]) && $postarr[DIRECTORYPRESS_CATEGORIES_TAX] == 0){
			unset($postarr[DIRECTORYPRESS_CATEGORIES_TAX][0]);
		}
		
		if (
			$directorypress_object->fields->get_field_by_slug('categories_list')->is_required &&
			(
			!isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!array_filter($postarr[DIRECTORYPRESS_CATEGORIES_TAX])
			)
		){
			$errors[] = __('Select at least one category!', 'DIRECTORYPRESS');
		}
		//if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && count($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) > $package->category_number_allowed){
		//	$errors[] = __('You can not select categories more than!', 'DIRECTORYPRESS').' '. $package->category_number_allowed;
		//}
		if (isset($postarr[DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr[DIRECTORYPRESS_CATEGORIES_TAX])) {
			$postarr[DIRECTORYPRESS_CATEGORIES_TAX] = array_slice($postarr[DIRECTORYPRESS_CATEGORIES_TAX], 0, 5, true);
			
			if ($package->selected_categories && array_diff($postarr[DIRECTORYPRESS_CATEGORIES_TAX], $package->selected_categories)){
				$errors[] = __('Sorry, you can not choose some categories for this package!', 'DIRECTORYPRESS');
			}
			$post_categories_ids = $postarr[DIRECTORYPRESS_CATEGORIES_TAX];
		} else{
			$post_categories_ids = array();
		}
		return $post_categories_ids;
	}
	public function validateCategoriesBackend($package, &$postarr, &$errors) {
		global $directorypress_object;
		if (isset($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX][0]) && $postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX][0] == 0){
			unset($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX][0]);
		}
		
		if (
			$directorypress_object->fields->get_field_by_slug('categories_list')->is_required &&
			(
			!isset($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!is_array($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) ||
			!count($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX])
			)
		){
			$errors[] = __('Select at least one category!', 'DIRECTORYPRESS');
		}
		if (isset($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) && count($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) > $package->category_number_allowed){
			$errors[] = sprintf(__('Maximum %s categories allowed, %s provided!', 'DIRECTORYPRESS'), $package->category_number_allowed, count($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]));
		}elseif (isset($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) && is_array($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX])  && count($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX]) <= $package->category_number_allowed) {
			$postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX] = array_slice($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX], 0, $package->category_number_allowed, true);
			
			if ($package->selected_categories && array_diff($postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX], $package->selected_categories)){
				$errors[] = __('Sorry, you can not choose some categories for this package!', 'DIRECTORYPRESS');
			}
			$post_categories_ids = $postarr['tax_input'][DIRECTORYPRESS_CATEGORIES_TAX];
		} else{
			$post_categories_ids = array();
		}
		return $post_categories_ids;
	}
	public function validateTags(&$postarr, &$errors) {
		if (isset($postarr[DIRECTORYPRESS_TAGS_TAX]) && $postarr[DIRECTORYPRESS_TAGS_TAX]) {
			$post_tags_ids = array();
			foreach ($postarr[DIRECTORYPRESS_TAGS_TAX] AS $tag) {
				if ($term = term_exists($tag, DIRECTORYPRESS_TAGS_TAX)) {
					$post_tags_ids[] = intval($term['term_id']);
				} else {
					if ($newterm = wp_insert_term($tag, DIRECTORYPRESS_TAGS_TAX))
						if (!is_wp_error($newterm))
							$post_tags_ids[] = intval($newterm['term_id']);
				}
			}
		} else
			$post_tags_ids = array();

		return $post_tags_ids;
	}
	public function admin_enqueue_scripts_styles() {
		
		wp_enqueue_script('directorypress_categories_scripts');
		

		if ($listing = directorypress_pull_current_listing_admin()) {
			$categories_number = $listing->package->category_number_allowed;

			wp_localize_script(
					'directorypress_categories_scripts',
					'package_categories',
					array(
							'package_categories_array' => $listing->package->selected_categories,
							'package_categories_number' => $categories_number,
							'package_categories_notice_disallowed' => __('Sorry, you can not choose this category for this level!', 'DIRECTORYPRESS'),
							'package_categories_notice_number' => sprintf(__('Sorry, you can not choose more than %d categories!', 'DIRECTORYPRESS'), $categories_number)
					)
			);
		}
	}
}