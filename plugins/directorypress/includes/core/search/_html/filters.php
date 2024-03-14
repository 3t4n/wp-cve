<?php if ($search_fields): ?>

		<script>
			(function($) {
				"use strict";
				
				$(function() {
					var fields_in_categories = new Array();
			<?php
			foreach ($search_fields_all AS $search_field): 
				if (!$search_field->field->is_categories() || $search_field->field->categories === array()): ?>
					fields_in_categories[<?php echo esc_attr($search_field->field->id); ?>] = [];
			<?php else: ?>
					fields_in_categories[<?php echo esc_attr($search_field->field->id); ?>] = [<?php echo implode(',', $search_field->field->categories); ?>];
			<?php endif; ?>
			<?php endforeach; ?>
			
					$(document).on("change", ".selected_tax_<?php echo esc_attr(DIRECTORYPRESS_CATEGORIES_TAX); ?>", function() {
						hideShowFields($(this).val());
					});
			
					if ($(".selected_tax_<?php echo esc_attr(DIRECTORYPRESS_CATEGORIES_TAX); ?>").length > 0) {
						hideShowFields($(".selected_tax_<?php echo esc_attr(DIRECTORYPRESS_CATEGORIES_TAX); ?>").val());
					} else {
						hideShowFields(0);
					}
			
					function hideShowFields(id) {
						var selected_categories_ids = [id];
			
						$(".field-form-id-<?php echo esc_attr($search_form->form_id); ?>").hide();
						$.each(fields_in_categories, function(index, value) {
							var show_field = false;
							if (value != undefined) {
								if (value.length > 0) {
									var key;
									for (key in value) {
										var key2;
										for (key2 in selected_categories_ids)
											if (value[key] == selected_categories_ids[key2])
												show_field = true;
									}
								}
								if ((value.length == 0 || show_field) && $(".unique-form-field-id-"+index+"_<?php echo esc_attr($search_form->form_id); ?>").length)
									$(".unique-form-field-id-"+index+"_<?php echo esc_attr($search_form->form_id); ?>").show();
							}
						});
					}
						// hack to remove visibility off hidden fields on initial load
				setTimeout(function(){
			        $('[class*="field-id-"]').unwrap();
				},500);
				});
			   
				
			})(jQuery);
		</script>

        <div id="temp-field-wrapper" style="display:none;">
		<?php
			foreach ($search_fields AS $search_field):
				$search_field->display_search($search_form, $defaults); 
			endforeach; 
		?>
	    </div>
<?php endif; ?>