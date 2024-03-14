<?php
if (!defined('ABSPATH')) {
	exit;
}

$taxonomy = 'product_cat';

if (
	empty($display_type) ||
	(!empty($position) && $position === 'left_sidebar')
) {
	$display_type = 'dropdown';
}

if (empty($single)) {
	$single = false;
}

if ($display_type == 'dropdown') {
	$container_html_class = 'wcpt-dropdown wcpt-filter ' . $html_class;
	$heading_html_class = 'wcpt-dropdown-label';
	$options_container_html_class = 'wcpt-dropdown-menu';
	$single_option_container_html_class = 'wcpt-dropdown-option';

	if (empty($heading)) {
		$heading = __('Category', 'wc-product-table');
	}

} else {
	$container_html_class = 'wcpt-options-row wcpt-filter ' . $html_class;
	$heading_html_class = 'wcpt-options-heading';
	$options_container_html_class = 'wcpt-options';
	$single_option_container_html_class = 'wcpt-option';

}

// heading row
if (!$heading) {
	$container_html_class .= ' wcpt-no-heading ';
}

// redirect enabled
if (!empty($redirect_enabled)) {
	$container_html_class .= ' wcpt-redirect-enabled ';
	$_GET['wcpt_category_redirect'] = true;
}

// applied filter
$table_id = $GLOBALS['wcpt_table_data']['id'];
$input_field_name = $table_id . '_product_cat';
$input_field_name__radio = $table_id . '_product_cat--' . rand(1, 100000);

// filter open in sidebar
if (
	!empty($position) &&
	$position === 'left_sidebar'
) {
	if (
		!empty($_REQUEST[$input_field_name]) ||
		!empty($accordion_always_open)
	) {
		$container_html_class .= ' wcpt-filter-open wcpt-open';
	}
}

$dropdown_options = array();

$table_data = wcpt_get_table_data();
$sc_attrs = $table_data['query']['sc_attrs'];

if (!empty($table_data['query']['category'])) {
	$term_ids = explode(',', $table_data['query']['category']);
} else {
	$term_ids = array();
}

// pre-selected
if ($pre_selected = wcpt_get_nav_filter('category')) {

	// visitor hasn't filtered yet. Apply site owner's filter
	if (empty($_GET[$table_id . '_filtered'])) {
		$_GET[$input_field_name] = $_REQUEST[$input_field_name] = $pre_selected['values'];

	} else if (
		// spare for only one exception - cateogry archive page with no sub-cat selected yet
		!(
			// category archive page
			isset($sc_attrs['_archive']) &&
			isset($sc_attrs['_taxonomy']) &&
			$sc_attrs['_taxonomy'] === 'product_cat' &&
			// no sub-category selected
			empty($_GET[$input_field_name])
		)
	) {
		wcpt_clear_nav_filter('category');
	}
}

if (empty($hide_empty)) {
	$hide_empty = false;
}

// display all category options
if (empty($redirect_enabled)) {
	$display_all = false;
}

$terms = array();
if (
	$display_all ||
	( // archive
		isset($sc_attrs['_archive']) &&
		(
			// - shop
			$sc_attrs['_archive'] == 'shop' ||
			// - search
			$sc_attrs['_archive'] == 'search' ||
				// - attribute / tag / custom product taxonomy
			(
				isset($sc_attrs['_taxonomy']) &&
				$sc_attrs['_taxonomy'] !== 'product_cat'
			)
		)
	) ||
	( // shortcode with no categories
		empty($sc_attrs['_archive']) &&
		empty($table_data['query']['category'])
	)
) {
	$terms = wcpt_get_terms($taxonomy, false, $hide_empty);	// get all cat terms

} else if ( // displaying a category archive
	!empty($sc_attrs['_archive']) &&
	!empty($sc_attrs['_taxonomy']) &&
	$sc_attrs['_taxonomy'] === 'product_cat'
) {
	$cat_term = get_term_by('slug', $sc_attrs['category'], 'product_cat');
	if ($child_tt_ids = get_term_children($cat_term->term_taxonomy_id, 'product_cat')) {
		$terms = wcpt_get_terms('product_cat', $child_tt_ids, $hide_empty);
	}

} else if (count($term_ids)) {
	$terms = wcpt_get_terms($taxonomy, $term_ids, $hide_empty);

}

// excludes array
$exclude_term_slugs = array();

// -- from category filter element's settings
if (!empty($exclude_terms)) {
	$exclude_term_slugs = preg_split('/\r\n|\r|\n/', $exclude_terms);
}

// -- from shortcode attribute
if (function_exists('wcpt_get_excluded_taxonomy_terms')) {
	$exclude_term_slugs = array_merge($exclude_term_slugs, wcpt_get_excluded_taxonomy_terms('product_cat'));
}

$excludes_arr = wcpt_include_descendant_slugs($exclude_term_slugs);

// build dropdown array
foreach ($terms as $term) {

	// exclude
	if (
		in_array($term->name, $excludes_arr) ||
		in_array($term->slug, $excludes_arr)
	) {
		continue;
	}

	// relabel
	if (isset($relabels)) {

		// look for a matching rule
		$match = false;
		foreach ($relabels as $rule) {
			// skip default
			if (wcpt_is_default_relabel($rule)) {
				continue;
			}

			if (
				wp_specialchars_decode($term->name) == $rule['term'] ||
				(
					function_exists('icl_object_id') &&
					!empty($rule['ttid']) &&
					$term->term_taxonomy_id == icl_object_id($rule['ttid'], $taxonomy, false)
				)
			) {
				$term->label = str_replace('[term]', $term->name, wcpt_parse_2($rule['label']));
				if (!empty($rule['clear_label'])) {
					$term->clear_label = $rule['clear_label'];
				}
				$match = true;
			}
		}
	}

	if (!isset($term->label)) {
		$term->label = $term->name;
	}

	if (!$match) {
		$term_name = apply_filters('wcpt_term_name_in_navigation_filter', $term->name, $term);
		$term->label = '<div class="wcpt-item-row"><span class="wcpt-text">' . $term_name . '</span></div>';

	}

	// option must have value field
	$term->value = $term->term_taxonomy_id;

	// add term in dropdown options
	$dropdown_options[] = $term;

}

// dynamic filter lazy load
$dynamic_filter_lazy_load = false;
if (
	!empty($table_data['query']['sc_attrs']['dynamic_filters_lazy_load']) &&
	(
		!empty($table_data['query']['sc_attrs']['dynamic_recount']) ||
		!empty($table_data['query']['sc_attrs']['dynamic_hide_filters'])
	)
) {
	$dynamic_filter_lazy_load = true;
}

if ($dynamic_filter_lazy_load) {
	$container_html_class .= ' wcpt--dynamic-filters--loading-filter';
}

// heading format when option is selected 
if (empty($heading_format__op_selected)) {
	$heading_format__op_selected = 'only_heading';
}

// search filter options
$search_placeholder_attr = '';
if (
	$display_type == 'dropdown' &&
	!empty($search_enabled)
) {
	if (empty($search_placeholder)) {
		$search_placeholder = '';
	}
	$search_placeholder_attr = ' data-wcpt-search-filter-options-placeholder="' . esc_attr($search_placeholder) . '" ';
}

?>
<div class="<?php echo $container_html_class; ?>" data-wcpt-filter="category" data-wcpt-taxonomy="product_cat"
	data-wcpt-heading_format__op_selected="<?php echo $heading_format__op_selected; ?>" <?php echo $search_placeholder_attr; ?>>

	<div class="wcpt-filter-heading">
		<!-- label -->
		<span class="<?php echo $heading_html_class; ?>">
			<?php echo wcpt_parse_2($heading); ?>
		</span>

		<!-- active count -->
		<?php
		if (
			!empty($_GET[$input_field_name]) &&
			!empty($_GET[$table_id . '_filtered']) &&
			!$single
		) {
			?>
			<span class="wcpt-active-count">
				<?php echo count($_GET[$input_field_name]); ?>
			</span>
			<?php
		}
		?>

		<!-- loader icon -->
		<?php
		if ($dynamic_filter_lazy_load) {
			wcpt_icon('loader', 'wcpt--dynamic-filters--loading-filter__loading-icon');
		}
		?>

		<!-- toggle icon -->
		<?php wcpt_icon('chevron-down'); ?>
	</div>

	<!-- options menu -->
	<div class="wcpt-hierarchy <?php echo $options_container_html_class; ?>">

		<?php
		// "Show all" option - when only one option is allowed to be selected
		ob_start();
		if (
			$single &&
			!wcpt_is_template_empty($show_all_label) &&
			empty($redirect_enabled) &&
			count($dropdown_options) > 1
		) {

			if (
				isset($sc_attrs['_archive']) &&
				isset($sc_attrs['_taxonomy']) &&
				$sc_attrs['_taxonomy'] === 'product_cat'
			) {
				$is_cat_archive = true;
				$cat_term = get_term_by('slug', $sc_attrs['category'], 'product_cat');

			} else {
				$is_cat_archive = false;

			}

			if (
				empty($_GET[$input_field_name]) ||
				$_GET[$input_field_name] === array('') ||
				(
					$is_cat_archive &&
					$_GET[$input_field_name][0] === $cat_term->term_taxonomy_id
				)
			) {
				$checked = true;
			} else {
				$checked = false;
			}

			?>
			<label
				class="wcpt-show-all-option <?php echo $single_option_container_html_class; ?> <?php echo $checked ? 'wcpt-active' : ''; ?>"
				data-wcpt-value="">
				<input type="radio" value="" class="wcpt-filter-checkbox" <?php echo $checked ? ' checked="checked" ' : ''; ?>
					name="<?php echo $single ? $input_field_name__radio : $input_field_name; ?>[]">
				<?php echo wcpt_parse_2($show_all_label); ?>
			</label>
			<?php
		}
		$show_all_op_markup = ob_get_clean();

		if (
			!empty($sc_attrs['nav_category']) &&
			$sc_attrs['nav_category'] == '_none'
		) {
			$dropdown_options = array();
		}
		?>

		<?php if ($display_type == 'dropdown') {

			foreach ($dropdown_options as &$option) {
				$option = apply_filters('wcpt_nav_filter_option', $option, 'category', array('taxonomy' => $taxonomy));
			}



			// All & Category
			if (
				!empty($sc_attrs['_archive']) &&
				!empty($sc_attrs['_taxonomy']) &&
				$sc_attrs['_taxonomy'] === 'product_cat' &&
				!empty($sc_attrs['_term']) &&
				empty($display_all)
			) {
				// 'All' option during search in category or reaching category from shop
				if (
					!empty($_GET['s']) ||
					!empty($_GET[$table_data['id'] . '_from_shop'])
				) {
					$url = home_url('/') . wcpt_get_archive_query_string('shop');
					$label = wcpt_get_icon('chevron-left') . ' ' . __('All', 'woocommerce');
					$all = true;

					?>
					<div class="wcpt-dropdown-option wcpt-term-ancestor wcpt-term-ancestor-all " data-wcpt-value="">
						<label class="" data-wcpt-value="" data-wcpt-slug="">
							<a href="<?php echo $url; ?>">
								<?php echo $label; ?>
							</a>
						</label>
					</div>
					<?php
				}

				if ($term = get_term_by('slug', $sc_attrs['_term'], 'product_cat')) {

					// hierarchy
					if ($ancestors = get_ancestors($term->term_taxonomy_id, 'product_cat', 'taxonomy')) {
						$ancestors = array_reverse($ancestors);
						foreach ($ancestors as $ancestor_id) {
							$ancestor = get_term_by('id', $ancestor_id, 'product_cat');
							$url = strtok(get_term_link($ancestor), '?') . wcpt_get_archive_query_string('category', $ancestor->term_taxonomy_id);
							$label = wcpt_get_icon('chevron-left') . ' ' . esc_attr($ancestor->name);

							?>
							<div class="wcpt-dropdown-option wcpt-term-ancestor" data-wcpt-value="<?php echo $ancestor->term_taxonomy_id ?>">
								<label class="" data-wcpt-value="<?php echo $ancestor->term_taxonomy_id ?>"
									data-wcpt-slug="<?php echo $ancestor->slug ?>">
									<a href="<?php echo $url; ?>">
										<?php echo $label; ?>
									</a>
								</label>
							</div>
							<?php
						}
					}

					// current category
					if (
						!empty($ancestors) ||
						!empty($all)
					) {
						echo '<div class="wcpt-current-option-wrapper">';
					}
					?>
					<div class="wcpt-dropdown-option wcpt-current-term" data-wcpt-value="<?php echo $term->term_taxonomy_id ?>">
						<label class="" data-wcpt-value="<?php echo $term->term_taxonomy_id ?>"
							data-wcpt-slug="<?php echo $term->slug ?>">
							<?php echo esc_attr($term->name); ?>
						</label>
					</div>
					<?php
					if (
						!empty($ancestors) ||
						!empty($all)
					) {
						echo '</div>';
					}
				}
			}

			echo $show_all_op_markup;

			wcpt_include_taxonomy_walker();

			if (!empty($redirect_enabled)) {
				echo '<div class="wcpt-redirect-options-wrapper">';
			}

			$walker = new WCPT_Taxonomy_Walker(
				array(
					'_field_name' => $input_field_name,
					'field_name' => $single ? $input_field_name__radio : $input_field_name,
					'exclude' => $exclude_term_slugs,
					'single' => $single,
					'hide_empty' => $hide_empty,
					'taxonomy' => $taxonomy,
					'operator' => 'IN',
					'pre_open_depth' => !empty($pre_open_depth) ? (int) $pre_open_depth : 0,
					'option_class' => $single_option_container_html_class,
					'redirect' => !empty($redirect_enabled),
					'category' => !empty($sc_attrs['_archive']) && !empty($sc_attrs['category']) ? $sc_attrs['category'] : '',
				)
			);
			echo $walker->walk($dropdown_options, 0);

			if (!empty($redirect_enabled)) {
				echo '</div>';
			}

			// row
		} else {

			if (!empty($dropdown_options)) {

				echo $show_all_op_markup;

				foreach ($dropdown_options as $option) {
					// option was selected or not?
					$option = apply_filters('wcpt_nav_filter_option', (array) $option, 'category', array('taxonomy' => $taxonomy));

					if (
						!empty($_GET[$input_field_name]) &&
						(
							$_GET[$input_field_name] == $option['value'] ||
							(
								is_array($_GET[$input_field_name]) &&
								in_array($option['value'], $_GET[$input_field_name])
							)
						)
					) {

						$checked = true;

						// use filter in query
						$filter_info = array(
							'filter' => 'category',
							'values' => array($option['value']),
							'taxonomy' => $taxonomy,
							'operator' => !empty($operator) ? $operator : 'IN',
							'clear_label' => __('Category', 'woocommerce'),
						);

						if (!empty($option['clear_label'])) {
							$filter_info['clear_labels_2'] = array(
								$option['value'] => str_replace(array('[option]', '[filter]'), array($option['name'], __('Category', 'woocommerce')), $option['clear_label']),
							);
						} else {
							$filter_info['clear_labels_2'] = array(
								$option['value'] => __('Category', 'woocommerce') . ' : ' . $option['name'],
							);
						}

						wcpt_update_user_filters($filter_info, $single);

					} else {
						$checked = false;
					}

					?>
					<label class="<?php echo $single_option_container_html_class; ?> <?php echo $checked ? 'wcpt-active' : ''; ?>"
						data-wcpt-slug="<?php echo $option['slug']; ?>" data-wcpt-value="<?php echo $option['value']; ?>">
						<input type="<?php echo $single ? 'radio' : 'checkbox'; ?>" value="<?php echo $option['value']; ?>"
							class="wcpt-filter-checkbox" <?php echo $checked ? ' checked="checked" ' : ''; ?>
							name="<?php echo $single ? $input_field_name__radio : $input_field_name; ?>[]"
							data-wcpt-clear-filter-label="<?php echo esc_attr($option['name']); ?>">
						<?php echo $option['label']; ?>
					</label>
					<?php
				}
			}

		} ?>
	</div>

</div>