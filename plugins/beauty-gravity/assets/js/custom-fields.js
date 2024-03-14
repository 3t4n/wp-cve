jQuery(document).ready(function () {

    if (typeof fieldSettings === 'undefined') {
        return;
    }
    var withoutTooltip = [
        "section",
        "html",
        "hidden",
        "page",
		"beauty_page"
    ]

    jQuery(document).bind('gform_load_field_settings', function (event, field, form) {

        if (jQuery.inArray(field['type'], withoutTooltip)>0){
            jQuery(this).find(".gravity_tooltip").css("display","none")
        }
        jQuery('#is_tooltip').val(field['is_tooltip'] === undefined ? '' : field['is_tooltip']);
        jQuery('#view_mode').val(field['view_mode'] === undefined ? 'default' : field['view_mode']);
    });
    jQuery('.choices_setting').on('input propertychange', '.field-choice-tooltip', function () {
        var $this = jQuery(this);
        var i = $this.closest('li.field-choice-row').data('index');

        field = GetSelectedField();
        field.choices[i].is_tooltip = $this.val();
    });

    gform.addFilter('gform_append_field_choice_option', function (str, field, i) {

        if ( field['type'] === "radio" || field['type'] === "checkbox" || 
			 ( field["type"] === "product" && field["inputType"] === "radio" ) || 
			 ( field["type"] === "post_tags" && (field["inputType"] === "checkbox" || field["inputType"] === "radio")) || 
			 ( field["type"] === "post_custom_field" && (field["inputType"] === "checkbox" || field["inputType"] === "radio"))) {
            var inputType = GetInputType(field);
            var is_tooltip = field.choices[i].is_tooltip ? field.choices[i].is_tooltip : '';
            var tooltipBody = "<input type='text' id='" + inputType + "_choice_tooltip_" + i + "' value='" + is_tooltip + "' class='field-choice-input field-choice-tooltip' placeholder='Place tooltip here' />";
            str += '<div class="choice-costume-tooltip">' + tooltipBody +'</div>';
            return str;
        }
        return "";
    });
	
	//Show radio/checkbox view mode in new gravityforms version
	jQuery(document).on( 'mouseup', '.gfield', function(){
		setTimeout(function(){
			var field_id = jQuery(document).find('#sidebar_field_label').attr('data-fieldid');
			if(form){
				jQuery(form['fields']).each(function(key,val){
					if( val['id'] == field_id ) {
						if( val['type'] == 'checkbox' || val['inputType'] == 'checkbox' || val['type'] == 'radio' || val['inputType'] == 'radio') {
							jQuery('.Beauty_choose').css("display","")
						}
					}
				})
			}
		},10)
	})

});


