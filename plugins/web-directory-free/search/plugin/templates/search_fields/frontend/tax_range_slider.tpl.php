<div class="wcsearch-search-input wcsearch-search-input-tax-range <?php echo $search_model->openedClosedClass(); ?>" <?php echo $search_model->getOptionsString(); ?>>
	<label class="wcsearch-search-input-label"><?php echo esc_html($title); ?></label>
	
	<?php
	wcsearch_print_range_slider_code(
		array(
			'min_max_options' => $min_max_options,
			'values' => $values,
			'slug' => $tax_name,
			'tax' => $tax_name,
			'field_name' => $slug,
			'show_scale' => "string",
			'string_label' => "",
		)
	);
	?>
</div>