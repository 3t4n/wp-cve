<div class="wcsearch-search-model-input wcsearch-search-model-input-single-slider" <?php echo $search_model->getOptionsString(); ?>>
	<label class="wcsearch-search-model-input-label"><?php echo esc_html($title); ?></label>
	
	<?php
	wcsearch_print_single_slider_code(
		array(
			'is_price' => true,
			'options' => $min_max_options,
			'values' => $values,
			'index' => $index,
			'slug' => $slug,
			'field_name' => $slug,
			'show_scale' => $show_scale,
			'string_label' => "", //esc_html__("Price:", "WCSEARCH"),
			'used_by' => $used_by,
		)
	); ?>
</div>