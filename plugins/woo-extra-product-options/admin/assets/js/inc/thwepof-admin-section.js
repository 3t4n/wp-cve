var thwepof_settings_section = (function($, window, document) {
	'use strict';

	var MSG_INVALID_NAME = 'NAME/ID must begin with a lowercase letter ([a-z]) and may be followed by any number of lowercase letters, digits ([0-9]) and underscores ("_")';

	var SECTION_FORM_FIELDS = {
		name 	   : {name : 'name', label : 'Name/ID', type : 'text', required : 1},
		position   : {name : 'position', label : 'Display Position', type : 'select', value : 'woo_before_add_to_cart_button', required : 1},
		order      : {name : 'order', label : 'Display Order', type : 'text'},
		cssclass   : {name : 'cssclass', label : 'CSS Class', type : 'text'},
		show_title : {name : 'show_title', label : 'Show section title in product page.', type : 'checkbox', value : 'yes', checked : true},
		
		title_cell_with : {name : 'title_cell_with', label : 'Col-1 Width', type : 'text', value : ''},
		field_cell_with : {name : 'field_cell_with', label : 'Col-2 Width', type : 'text', value : ''},
		
		title 		: {name : 'title', label : 'Title', type : 'text'},
		title_type 	: {name : 'title_type', label : 'Title Type', type : 'select', value : 'h3'},
		title_color : {name : 'title_color', label : 'Title Color', type : 'colorpicker'},
		title_class : {name : 'title_class', label : 'Title Class', type : 'text'}
	};

	function open_new_section_form(){
		open_section_form('new', false);
	}
	
	function open_edit_section_form(valueJson){
		open_section_form('edit', valueJson);
	}
	
	function open_copy_section_form(valueJson){
		open_section_form('copy', valueJson);
	}

	function open_section_form(type, valueJson){
		var popup = $("#thwepof_section_form_pp");
		var form  = $("#thwepof_section_form");

		populate_section_form(popup, form, type, valueJson);
		
		thwepof_base.form_wizard_open(popup);
		thwepof_base.setup_color_picker(form);
		thwepof_base.setup_enhanced_multi_select(form);
		thwepof_base.block_enter_key_submission(popup);
	}

	function populate_section_form(popup, form, type, valueJson){
		var title = type === 'edit' ? thwepof_admin_var.edit_section : thwepof_admin_var.new_section;
		popup.find('.wizard-title').text(title);

		form.find('.err_msgs').html('');
		form.find("input[name=i_name]").prop("readonly", false);

		form.find("input[name=s_action]").val(type);
		form.find("input[name=s_name]").val('');
		form.find("input[name=s_name_copy]").val('');
		form.find("input[name=i_position_old]").val('');
		form.find("input[name=i_rules]").val('');

		if(type === 'new'){
			set_form_field_values(form, SECTION_FORM_FIELDS, false);

		}else{
			set_form_field_values(form, SECTION_FORM_FIELDS, valueJson);

			if(type === 'copy'){
				var sNameCopy = valueJson ? valueJson['name'] : '';
				form.find("input[name=i_name]").val("");
				form.find("input[name=s_name_copy]").val(sNameCopy);
			}else{
				form.find("input[name=i_name]").prop("readonly", true);
			}

			form.find("select[name=i_position_old]").val(valueJson.position);
			//setTimeout(function(){form.find("select[name=i_position]").focus();}, 1);
		}
	}

	function set_form_field_values(form, fields, valuesJson){
		var sname = valuesJson && valuesJson['name'] ? valuesJson['name'] : '';
		
		$.each( fields, function( name, field ) {
			var type = field['type'];								  
			var value = valuesJson && valuesJson[name] ? valuesJson[name] : field['value'];
			var multiple = field['multiple'] ? field['multiple'] : 0;

			if(type === 'checkbox'){
				if(!valuesJson && field['checked']){
					value = field['checked'];
				}
			}

			thwepof_base.set_property_field_value(form, type, name, value, multiple);
		});
		
		var prop_form = $('#section_prop_form_'+sname);
		
		var rulesAction = valuesJson && valuesJson['rules_action'] ? valuesJson['rules_action'] : 'show';
		var conditionalRules = prop_form.find(".f_rules").val();

		thwepof_base.set_property_field_value(form, 'select', 'rules_action', rulesAction, false);
		thwepof_conditions.populate_conditional_rules(form, conditionalRules, false);	
	}
	
	function save_section(elm){
		var popup = $("#thwepof_section_form_pp");
		var form  = $("#thwepof_section_form");
		var result = validate_section(form, popup);

		if(result){
			prepare_section_form(form);
			form.submit();
		}
	}

	function validate_section(form, popup){
		var name  = form.find("input[name=i_name]").val();
		var title = form.find("input[name=i_title]").val();
		var position = form.find("select[name=i_position]").val();
		
		var err_msgs = '';
		if(name.trim() == ''){
			err_msgs = 'Name/ID is required';
		}else if(!thwepof_base.isHtmlIdValid(name)){
			err_msgs = MSG_INVALID_NAME;
		}else if(title.trim() == ''){
			err_msgs = 'Title is required';
		}else if(position == ''){
			err_msgs = 'Please select a position';
		}		
		
		if(err_msgs != ''){
			form.find('.err_msgs').html(err_msgs);
			thwepof_base.form_wizard_start(popup);
			return false;
		}		
		return true;
	}
	
	function prepare_section_form(form){
		var rules_json = thwepof_conditions.get_conditional_rules(form);
		rules_json = rules_json.replace(/"/g, "'");
		
		thwepof_base.set_property_field_value(form, 'hidden', 'rules', rules_json, 0);
	}
	
	function remove_section(elm){
		var _confirm = confirm('Are you sure you want to delete this section?.');
		if(_confirm){
			var form = $(elm).closest('form');
			if(form){ form.submit(); }
		}
	}

	return {
		open_new_section_form : open_new_section_form,
		open_edit_section_form : open_edit_section_form,
		open_copy_section_form : open_copy_section_form,
		save_section : save_section,
		remove_section : remove_section,
   	};
}(window.jQuery, window, document));

function thwepofOpenNewSectionForm(){
	thwepof_settings_section.open_new_section_form();		
}

function thwepofOpenEditSectionForm(section){
	thwepof_settings_section.open_edit_section_form(section);		
}

function thwepofOpenCopySectionForm(section){
	thwepof_settings_section.open_copy_section_form(section);		
}

function thwepofSaveSection(elm){
	thwepof_settings_section.save_section(elm);	
}

function thwepofRemoveSection(elm){
	thwepof_settings_section.remove_section(elm);	
}
