<script>
	(function($) {
		"use strict";
	
		$(function() {
			var fields_in_categories = new Array();
	<?php
	foreach ($fields AS $field): 
		//if (!$field->is_core_field )
			if (!$field->is_categories() || $field->categories === array()) { ?>
				fields_in_categories[<?php echo esc_attr($field->id); ?>] = [];
		<?php } else { ?>
				fields_in_categories[<?php echo esc_attr($field->id); ?>] = [<?php echo implode(',', $field->categories); ?>];
		<?php } ?>
	<?php endforeach; ?>
	
			hideShowFields();
	
			$("input[name=tax_input\\[directorypress-category\\]\\[\\]]").change(function() {hideShowFields()});
			$(".category_selction").change(function() {hideShowFields()});
			$("#directorypress-category-pop input[type=checkbox]").change(function() {hideShowFields()});
			
			$(".directorypress-category").on('change', 'select', function() {
				hideShowFields();
			});
			
			
			function hideShowFields() {
				var selected_categories_ids = [];
				$.each($(".category_selction option:selected"), function() {
					selected_categories_ids.push($(this).val());
					
				});
				$.each($(".directorypress-category select option:selected"), function() {
					selected_categories_ids.push($(this).val());
					
				});
				$.each($("input[name=tax_input\\[directorypress-category\\]\\[\\]]:checked"), function() {
					selected_categories_ids.push($(this).val());
					
				});
				//console.log(selected_categories_ids);
				$('.field-input-item').hide();
				$.each(fields_in_categories, function(index, value) {
					var show_field = false;
					if (value != undefined) {
						//alert(value);
						if (value.length > 0) {
							var key;
							//alert(key);
							for (key in value) {
								var key2;
								for (key2 in selected_categories_ids){
									if (value[key] == selected_categories_ids[key2]){
										show_field = true;
										console.log(value[key]+' - '+selected_categories_ids[key2]);
									}
								}
							}
						}
						
						if ((value.length == 0 || show_field) && $(".field-input-item.submit_field_id_"+index).length)
							$(".field-input-item.submit_field_id_"+index).show();
					}
				});
			}
		});
	})(jQuery);
</script>
<div class="fields-dependency-alert alert alert-info"><?php _e('Fields can be depended on select category', 'DIRECTORYPRESS'); ?></div>
<?php
	foreach ($fields AS $field) {
		if (!$field->is_core_field){
			$field->renderInput();
		}
	}
?>
		