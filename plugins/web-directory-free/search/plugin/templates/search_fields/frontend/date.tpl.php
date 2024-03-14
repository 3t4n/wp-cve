<div class="wcsearch-search-input wcsearch-search-date-fields <?php echo $search_model->openedClosedClass(); ?>" <?php echo $search_model->getOptionsString(); ?>>
	<label class="wcsearch-search-input-label"><?php echo esc_html($title); ?></label>
	
	<div class="wcsearch-search-inputs-wrapper">
		<?php
		wcsearch_print_datepickers_code(
			array(
				'values' => $values,
				'slug' => $slug,
				'view' => $view,
				'placeholder_start' => $placeholder_start,
				'placeholder_end' => $placeholder_end,
				'reset_label_text' => $reset_label_text,
			)
		);
		?>
	</div>
</div>