var thwepof_settings_field = (function($, window, document) {
	'use strict';

	var MSG_INVALID_NAME = 'NAME/ID must begin with a lowercase letter ([a-z]) and may be followed by any number of lowercase letters, digits ([0-9]) and underscores ("_")';

	var FIELD_FORM_PROPS = {
		name  : {name : 'name', type : 'text'},
		type  : {name : 'type', type : 'select'},
		
		value : {name : 'value', type : 'text'},
		options     : {name : 'options', type : 'text'},
		placeholder : {name : 'placeholder', type : 'text'},
		validator   : {name : 'validator', type : 'select'},
		input_class : {name : 'input_class', type : 'text'},
		cssclass    : {name : 'cssclass', type : 'text'},
		maxlength   : {name : 'maxlength', type : 'text'},
		minlength   : {name : 'minlength', type : 'text'},
		step : {name : 'step', type : 'text'},
		
		title          : {name : 'title', type : 'text'},
		title_type     : {name : 'title_type', type : 'select'},
		title_position : {name : 'title_position', type : 'select', value : 'left'},
		title_class    : {name : 'title_class', type : 'text'},
		
		checked  : {name : 'checked', type : 'checkbox'},

		required : {name : 'required', type : 'checkbox'},
		enabled  : {name : 'enabled', type : 'checkbox', value : 1},
		readonly : {name : 'readonly', type : 'checkbox'},
		view_password : {name : 'view_password', type : 'checkbox'},
		
		maxsize : {name : 'maxsize', type : 'text'},
		accept  : {name : 'accept', type : 'text'},

		cols : {name : 'cols', type : 'text'},
		rows : {name : 'rows', type : 'text'},

		input_mask : {name : 'input_mask', type : 'text'},
	};

	function open_new_field_form(sname){
		open_field_form('new', false);
	}
	
	function open_edit_field_form(elm, rowId){
		open_field_form('edit', elm);
	}
	
	function open_copy_field_form(elm, rowId){
		open_field_form('copy', elm);
	}

	function open_field_form(action, elm){
		var popup = $("#thwepof_field_form_pp");
		var form  = $("#thwepof_field_form");
		
		populate_field_form(popup, form, action, elm);
		
		thwepof_base.form_wizard_open(popup);
		thwepof_base.setup_color_pick_preview(form);
	}

	function populate_field_form(popup, form, action, elm){
		var title = action === 'edit' ? thwepof_admin_var.edit_field : thwepof_admin_var.new_field;
		popup.find('.wizard-title').text(title);

		form.find('.err_msgs').html('');
		form.find("input[name=i_action]").val(action);
		form.find("input[name=i_name_old]").val('');
		form.find("input[name=i_rules]").val('');

		var props = false;
		var row = false;

		if(action === 'edit' || action === 'copy'){
			row = $(elm).closest('tr');

			var props_json = row.find(".f_props").val();
			props = JSON.parse(props_json);
		}

		populate_field_form_general(form, action, props);
		form.find("select[name=i_type]").change();

		populate_field_form_props(form, props);
		populate_display_rules(form, row);
	}

	function populate_field_form_general(form, action, props){
		var name = '';
		var type = 'inputtext';

		if(props){
			if(action === 'edit'){
				name = props.name;
				type = props.type;
			}else if(action === 'copy'){
				type = props.type;
			}
		}

		thwepof_base.set_property_field_value(form, 'text', 'name', name, 0);
		thwepof_base.set_property_field_value(form, 'hidden', 'name_old', name, 0);
		thwepof_base.set_property_field_value(form, 'select', 'type', type, 0);
		//form.find("select[name=i_type]").prop('selectedIndex',0);
	}

	/*function clear_field_form(form){
		form.find("input[name=i_value]").val('');
		form.find("textarea[name=i_value]").val('');
		form.find("input[name=i_placeholder]").val('');
		form.find("input[name=i_options]").val('');

		form.find("select[name=i_validator] option:selected").removeProp('selected');
		form.find("input[name=i_cssclass]").val('');

		form.find("input[name=i_title]").val('');
		form.find("input[name=i_title_class]").val('');
		form.find("select[name=i_title_position]").prop('selectedIndex',0);

		form.find("input[name=i_cols]").val('');
		form.find("input[name=i_rows]").val('');

		form.find("input[name=i_checked]").prop('checked', false);
		form.find("input[name=i_required]").prop('checked', false);
		form.find("input[name=i_enabled]").prop('checked', true);
		form.find("input[name=i_readonly]").prop('checked', false);
		
		var conditionalRulesTable = form.find("#thwepo_conditional_rules tbody");
		conditionalRulesTable.html(RULE_SET_HTML);
		setup_enhanced_multi_select(conditionalRulesTable);
	}*/
	
	function populate_field_form_props(form, props){
		var ftype = props.type;
		
		$.each( FIELD_FORM_PROPS, function( name, field ) {
			if(name == 'name' || name == 'type') {
				return true;
			}
	
			var type  = field['type'];
			var value = field['value'] ? field['value'] : '';
			var multiple = field['multiple'] ? field['multiple'] : 0;

			if(props){
				value = props[name] ? props[name] : '';
			}

			if(ftype == 'paragraph' && name == 'value'){
				type = "textarea";
			}
			
			thwepof_base.set_property_field_value(form, type, name, value, multiple);
			
			if(type == 'select'){
				name = multiple == 1 ? name+"[]" : name;
				
				if(multiple == 1 || field['change'] == 1){
					form.find('select[name="i_'+name+'"]').trigger("change");
				}
			}
		});
	}

	function populate_display_rules(form, row){
		var rules = row.length ? row.find(".f_rules").val() : '';
		thwepof_conditions.populate_conditional_rules(form, rules, false);
		
		//var rulesAction = props && props['rules_action'] ? props['rules_action'] : 'show';
		//thwepof_base.set_property_field_value(form, 'select', 'rules_action', rulesAction, false);
	}
	
	function field_type_change_listner(elm){
		var popup = $("#thwepof_field_form_pp");
		var form = $(elm).closest('form');
		var type = $(elm).val();
		type = type == null ? 'inputtext' : type;
		
		form.find('.thwepof_field_form_tab_general_placeholder').html($('#thwepof_field_form_id_'+type).html());

		thwepof_base.form_wizard_enable_all_tabs(popup);
		form.find(':input').attr("disabled", false);
		form.find('tr').removeClass('disabled');

		if(type === 'hidden' || type === 'heading' || type === 'paragraph' || type === 'separator'){
			thwepof_base.form_wizard_disable_tab(popup, 1);
		}if(type === 'checkbox'){
			disable_field(form, 'select', 'title_position');
		}

		thwepof_base.setup_enhanced_multi_select(form);
		thwepof_base.setup_color_picker(form);

		thwepof_base.block_enter_key_submission(popup);

		//populate_field_form_props(form, row, props);
		//thwepof_base.setup_enhanced_multi_select(form);	
		
		//thwepof_base.setupSortableTable(form, '.thwepo-option-list', '100');
	}

	function disable_field(form, type, name){
		var elm = null;

		switch(type) {
			case 'select':
				elm = form.find('select[name="i_'+name+'"]');
				break;

			case 'textarea':
				elm = form.find("textarea[name=i_"+name+"]");
				break;
				
			default:
				elm = form.find("input[name=i_"+name+"]");
		}
		
		if(elm && elm.length){
			elm.attr("disabled", true);
			elm.closest('tr.form_field_'+name).addClass('disabled');
		}
	}

	/*
	function colorpicker_style_change_listner( elm ){
		var style = $(elm).val();
		var tr = $(elm).closest('tr').siblings('.thweop-colorpicker-style2');
		if( style == 'style2' && tr.length ){
			tr.hide();
		}else{
			tr.show();
		}
	}
	*/

	function save_field(elm){
		var popup = $("#thwepof_field_form_pp");
		var form  = $("#thwepof_field_form");
		var result = validate_field_form(form, popup);

		if(result){
			prepare_field_form(form);
			form.submit();
		}
	}
	
	function validate_field_form(form, popup){
		var err_msgs = '';
		
		var fname  = thwepof_base.get_property_field_value(form, 'text', 'name');
		var ftype  = thwepof_base.get_property_field_value(form, 'select', 'type');
		//var foriginalType  = thwepof_base.get_property_field_value(form, 'hidden', 'original_type');

		if(ftype == '' ){
			err_msgs = 'Type is required';

		}else if(fname == ''){
			err_msgs = 'Name is required';

		}else if(!thwepof_base.isHtmlIdValid(fname)){
			err_msgs = MSG_INVALID_NAME;

		}else{
			if(ftype == 'html'){
				if(ftitle == ''){
					err_msgs = 'Title is required';
				}
			}else if(ftype == 'paragraph'){
				var content = thwepof_base.get_property_field_value(form, 'textarea', 'value');

				if(content == ''){
					err_msgs = 'Content is required';
				}
			}else if(ftype == 'html'){
				var content = thwepof_base.get_property_field_value(form, 'text', 'title');

				if(content == ''){
					err_msgs = 'Title is required';
				}	
			}
		}

		if(err_msgs != ''){
			form.find('.err_msgs').html(err_msgs);
			thwepof_base.form_wizard_start(popup);
			return false;
		}

		return true;
	}
	
	function prepare_field_form(form){
		var rules_json = thwepof_conditions.get_conditional_rules(form);
		rules_json = rules_json.replace(/"/g, "'");
		
		thwepof_base.set_property_field_value(form, 'hidden', 'rules', rules_json, 0);
	}
   /*------------------------------------
	*---- PRODUCT FIELDS - END ----------
	*------------------------------------*/
	
   /*------------------------------------
	*---- OPTIONS FUNCTIONS - SATRT -----
	*------------------------------------*/
	/*
	function get_options(form){
		var optionsKey  = form.find("input[name='i_options_key[]']").map(function(){ return $(this).val(); }).get();
		var optionsText = form.find("input[name='i_options_text[]']").map(function(){ return $(this).val(); }).get();
		var optionsPrice = form.find("input[name='i_options_price[]']").map(function(){ return $(this).val(); }).get();
		var optionsPriceType = form.find("select[name='i_options_price_type[]']").map(function(){ return $(this).val(); }).get();
		
		var optionsSize = optionsText.length;
		var optionsArr = [];
		
		for(var i=0; i<optionsSize; i++){
			var optionDetails = {};
			optionDetails["key"] = optionsKey[i];
			optionDetails["text"] = optionsText[i];
			optionDetails["price"] = optionsPrice[i];
			optionDetails["price_type"] = optionsPriceType[i];
			
			optionsArr.push(optionDetails);
		}
		
		var optionsJson = optionsArr.length > 0 ? JSON.stringify(optionsArr) : '';
		optionsJson = encodeURIComponent(optionsJson);
		return optionsJson;
	}
	
	function populate_options(form, row){
		//var optionsJson = row.find(".f_options").val();
		//populate_options_list(form, optionsJson);
	}

	function populate_options_list(form, optionsJson){
		var optionsHtml = "";
		
		if(optionsJson){
			try{
				optionsJson = decodeURIComponent(optionsJson);
				var optionsList = $.parseJSON(optionsJson);
				if(optionsList){
					jQuery.each(optionsList, function() {
						var op1Selected = this.price_type === 'percentage' ? 'selected' : '';
						var price = this.price ? this.price : '';
						
						var html  = '<tr>';
						html += '<td style="width:190px;"><input type="text" name="i_options_key[]" value="'+this.key+'" placeholder="Option Value" style="width:180px;"/></td>';
						html += '<td style="width:190px;"><input type="text" name="i_options_text[]" value="'+this.text+'" placeholder="Option Text" style="width:180px;"/></td>';
						html += '<td style="width:80px;"><input type="text" name="i_options_price[]" value="'+price+'" placeholder="Price" style="width:70px;"/></td>';
						html += '<td style="width:130px;"><select name="i_options_price_type[]" value="'+this.price_type+'" style="width:120px;">';
						html += '<option value="">Fixed</option><option value="percentage" '+op1Selected+'>Percentage</option></select></td>';
						html += '<td class="action-cell"><a href="javascript:void(0)" onclick="thwepoAddNewOptionRow(this)" class="btn btn-blue" title="Add new option">+</a></td>';
						html += '<td class="action-cell"><a href="javascript:void(0)" onclick="thwepoRemoveOptionRow(this)" class="btn btn-red"  title="Remove option">x</a></td>';
						html += '<td class="action-cell sort ui-sortable-handle"></td>';
						html += '</tr>';
						
						optionsHtml += html;
					});
				}
			}catch(err) {
				alert(err);
			}
		}
		
		var optionsTable = form.find(".thwepo-option-list tbody");
		if(optionsHtml){
			optionsTable.html(optionsHtml);
		}else{
			optionsTable.html(OPTION_ROW_HTML);
		}
	}
	
	function add_new_option_row(elm){
		var ptable = $(elm).closest('table');
		var optionsSize = ptable.find('tbody tr').size();
			
		if(optionsSize > 0){
			ptable.find('tbody tr:last').after(OPTION_ROW_HTML);
		}else{
			ptable.find('tbody').append(OPTION_ROW_HTML);
		}
	}
	
	function remove_option_row(elm){
		var ptable = $(elm).closest('table');
		$(elm).closest('tr').remove();
		var optionsSize = ptable.find('tbody tr').size();
			
		if(optionsSize == 0){
			ptable.find('tbody').append(OPTION_ROW_HTML);
		}
	}
	*/
   /*------------------------------------
	*---- OPTIONS FUNCTIONS - END -------
	*------------------------------------*/
	   				
	return {
		open_new_field_form : open_new_field_form,
		open_edit_field_form : open_edit_field_form,
		open_copy_field_form : open_copy_field_form,
		field_type_change_listner : field_type_change_listner,
		
		//addNewOptionRow : add_new_option_row,
		//removeOptionRow : remove_option_row,
		//colorpickerStyleChangeListner : colorpicker_style_change_listner,
		save_field : save_field,
   	};
}(window.jQuery, window, document));

function thwepofOpenNewFieldForm(sectionName){
	thwepof_settings_field.open_new_field_form(sectionName);		
}

function thwepofOpenEditFieldForm(elm, rowId){
	thwepof_settings_field.open_edit_field_form(elm, rowId);		
}

function thwepofOpenCopyFieldForm(elm, rowId){
	thwepof_settings_field.open_copy_field_form(elm, rowId);		
}

function thwepofFieldTypeChangeListner(elm){	
	thwepof_settings_field.field_type_change_listner(elm);
}

/*
function thwepoAddNewOptionRow(elm){
	thwepof_settings_field.addNewOptionRow(elm);
}
function thwepoRemoveOptionRow(elm){
	thwepof_settings_field.removeOptionRow(elm);
}

function thwepoColorpickerStyleChangeListner( elm ){
	thwepof_settings_field.colorpickerStyleChangeListner( elm );
}
*/

function thwepofSaveField(elm){
	thwepof_settings_field.save_field(elm);	
}
