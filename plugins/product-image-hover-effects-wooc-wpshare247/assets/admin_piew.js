jQuery(document).ready(function(e) {
	jQuery( '.colorpicker' ).wpColorPicker();
	
	ws247_piew_js_group_show_intit();
	ws247_piew_js_group_effect_show_intit();
});

function ws247_piew_js_group_show_intit(){
	jQuery(".ws247-piew-js-group-show").click(function(e) {
		var group_id = jQuery(this).data('group');
		if( !jQuery(group_id).hasClass('parent-checked') ){
			jQuery(group_id).addClass('parent-checked');
		}else{
			jQuery(group_id).removeClass('parent-checked');
		}
    });
}

function ws247_piew_js_group_effect_show_intit(){
	jQuery(".ws247-piew-js-group-effect-show").change(function() {
		//
		var sel_id = jQuery(this).attr("id");
		jQuery("."+sel_id).removeClass('parent-checked');
		
		//
		var optionSelected = jQuery(this).find("option:selected");
		var group_id = jQuery(optionSelected).data('group');
		jQuery(group_id).addClass('parent-checked');
	});
}