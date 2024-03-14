var thwepof_base = (function($, window, document) {
	'use strict';

	function escapeHTML(html) {
	   var fn = function(tag) {
		   var charsToReplace = {
			   '&': '&amp;',
			   '<': '&lt;',
			   '>': '&gt;',
			   '"': '&#34;'
		   };
		   return charsToReplace[tag] || tag;
	   }
	   return html.replace(/[&<>"]/g, fn);
	}

	function decodeHtml(str) {
		if(str && typeof(str) === 'string'){
		   	var map = {
	        	'&amp;': '&',
	        	'&lt;': '<',
	        	'&gt;': '>',
	        	'&quot;': '"',
	        	'&#039;': "'"
	    	};
	    	return str.replace(/&amp;|&lt;|&gt;|&quot;|&#039;/g, function(m) {return map[m];});
	    }
	    return str;
	}

	function isHtmlIdValid(id) {
		//var re = /^[a-z]+[a-z0-9\_]*$/;
		var re = /^[a-z\_]+[a-z0-9\_]*$/;
		return re.test(id.trim());
	}

	function isValidHexColor(value) {      
		if ( preg_match( '/^#[a-f0-9]{6}$/i', value ) ) { // if user insert a HEX color with #     
			return true;
		}     
		return false;
	}

	function setup_tiptip_tooltips(){
		var tiptip_args = {
			'attribute': 'data-tip',
			'fadeIn': 50,
			'fadeOut': 50,
			'delay': 200
		};

		$('.tips').tipTip( tiptip_args );
	}

	function setup_color_picker(form){
		form.find('.thpladmin-colorpick').iris({
			change: function( event, ui ) {
				$( this ).parent().find( '.thpladmin-colorpickpreview' ).css({ backgroundColor: ui.color.toString() });
			},
			hide: true,
			border: true
		}).click( function() {
			$('.iris-picker').hide();
			$(this ).closest('td').find('.iris-picker').show();
		});
	
		$('body').click( function() {
			$('.iris-picker').hide();
		});
	
		$('.thpladmin-colorpick').click( function( event ) {
			event.stopPropagation();
		});
	}

	function setup_color_pick_preview(form){
		form.find('.thpladmin-colorpick').each(function(){
			$(this).parent().find('.thpladmin-colorpickpreview').css({ backgroundColor: this.value });
		});
	}
	
	function setup_enhanced_multi_select(parent){
		parent.find('select.thwepof-enhanced-multi-select').each(function(){
			if(!$(this).hasClass('enhanced')){
				$(this).selectWoo({
					//minimumResultsForSearch: 10,
					allowClear : true,
					placeholder: $(this).data('placeholder')
				}).addClass('enhanced');
			}
		});
	}

	function setup_product_dropdown(parent, set_dv){
		parent.find('select.thwepof-product-select').each(function(){
			if(!$(this).hasClass('enhanced')){
				if(set_dv){
					prepare_selected_options($(this));
				}

				var elm = $(this).selectWoo({
					//minimumResultsForSearch: 10,
					allowClear : true,
					placeholder: $(this).data('placeholder'),
					ajax: {
						type: 'POST',
				        url: ajaxurl,
				        dataType: 'json',
				        data: function(params) {
				            return {
				            	action: 'thwepof_load_products',
				            	security: thwepof_admin_var.load_product_nonce,
				                term: params.term || '',
				                page: params.page || 1,
				            }
				        },
				        processResults: function (result, params) {
		                    return result.data;
						},
				        cache: true
				    },
				}).addClass('enhanced');
			}
		});
	}

	function prepare_selected_options(elm){
		var value = elm.siblings("input[name=i_rule_value_hidden]").val();
				
		if(value){
			var data = {
	            action: 'thwepof_load_products',
	            value: value,
	            security: thwepof_admin_var.load_product_nonce,
	        };

			$.ajax({
	            type: 'POST',
	            url : ajaxurl,
	            data: data,
	            success: function(result){
	            	$.each(result.data.results, function( key, value ) {
						var newOption = new Option(value.text, value.id, true, true);
						elm.append(newOption);
					});
	            }
	        });
	        elm.trigger('change');
		}		
	}

	function prepare_field_order_indexes(elm) {
		$(elm+" tbody tr").each(function(index, el){
			$('input.f_order', el).val( parseInt( $(el).index(elm+" tbody tr") ) );
		});
	}

	function setup_sortable_table(parent, elm, left){
		parent.find(elm+" tbody").sortable({
			items:'tr',
			cursor:'move',
			axis:'y',
			handle: 'td.sort',
			scrollSensitivity:40,
			helper:function(e,ui){
				ui.children().each(function(){
					$(this).width($(this).width());
				});
				ui.css('left', left);
				return ui;
			}		
		});	
		
		$(elm+" tbody").on("sortstart", function( event, ui ){
			ui.item.css('background-color','#f6f6f6');										
		});
		$(elm+" tbody").on("sortstop", function( event, ui ){
			ui.item.removeAttr('style');
			prepare_field_order_indexes(elm);
		});
	}

	function get_property_field_value(form, type, name){
		var value = '';
		
		switch(type) {
			case 'select':
				value = form.find("select[name=i_"+name+"]").val();
				value = value == null ? '' : value;
				break;
				
			case 'checkbox':
				value = form.find("input[name=i_"+name+"]").prop('checked');
				value = value ? 1 : 0;
				break;

			case 'textarea':
				value = form.find("textarea[name=i_"+name+"]").val();
				value = value == null ? '' : value;
				break;
				
			default:
				value = form.find("input[name=i_"+name+"]").val();
				value = value == null ? '' : value;
		}	
		
		return value;
	}

	function set_property_field_value(form, type, name, value, multiple){
		switch(type) {
			case 'select':
				if(multiple == 1 && typeof(value) === 'string'){
					value = value.split(",");
					name = name+"[]";
				}
				form.find('select[name="i_'+name+'"]').val(value);
				break;
				
			case 'checkbox':
				value = value == 1 ? true : false;
				form.find("input[name=i_"+name+"]").prop('checked', value);
				break;

			case 'textarea':
				value = value ? decodeHtml(value) : value;
				form.find("textarea[name=i_"+name+"]").val(value);
				break;

			case 'colorpicker':
				var bg_color = value ? { backgroundColor: value } : { backgroundColor: '' }; 
				form.find("input[name=i_"+name+"]").val(value);
				form.find("."+name+"_preview").css(bg_color);
				break;
				
			default:
				value = value ? decodeHtml(value) : value;
				form.find("input[name=i_"+name+"]").val(value);
		}	
	}

   /*-------------------------------------------
	*---- POPUP WIZARD FUNCTIONS - SATRT -------
	*------------------------------------------*/
	var active_tab = 0;

	function setup_form_wizard(){
		$('.pp_nav_links > li').click(function(){
			var index = $(this).data('index');
			var popup = $(this).closest('.thpladmin-modal-mask');
			
			open_tab(popup, $(this), index);
		});
	}

	function get_popup(elm){
		return $(elm).closest('.thpladmin-modal-mask');
	}

	function get_active_tab(popup){
		return popup.find('ul.pp_nav_links').find('li.active')
	}

	function get_next_tab_index(elm){
		var popup  = get_popup(elm);
		var active = get_active_tab(popup);

		var link = active.nextAll("li").not(".disabled").first();
		var index = link.length ? link.data('index') : active_tab;
		return index;
	}

	function get_prev_tab_index(elm){
		var popup  = get_popup(elm);
		var active = get_active_tab(popup);

		var link = active.prevAll("li").not(".disabled").first();
		var index = link.length ? link.data('index') : active_tab;
		return index;
	}

	function form_wizard_open(popup){
		active_tab = 0;
		popup.find('ul.pp_nav_links li').first().click();
		popup.css("display", "block");
	}

	function form_wizard_close(elm) {
		var popup = get_popup(elm);
		popup.css("display", "none");
		active_tab = 0;
	}

	function form_wizard_next(elm){
		//active_tab++;
		active_tab = get_next_tab_index(elm);
		move_to(elm, active_tab);
	}

	function form_wizard_previous(elm){
		//active_tab--;
		active_tab = get_prev_tab_index(elm);
		move_to(elm, active_tab);
	}

	function form_wizard_start(elm){
		active_tab = 0;
		move_to(elm, active_tab);
	}

	function move_to(elm, index){
		var popup = get_popup(elm);
		var link = popup.find('*[data-index="'+index+'"]');
		open_tab(popup, link, index);
	}

	function open_tab(popup, link, index){
		var panel = popup.find('.data_panel_'+index);

		close_all_data_panel(popup);
		link.addClass('active');
		panel.css("display", "block");

		enable_disable_btns(popup, link);
	}

	function close_all_data_panel(popup){
		popup.find('.pp_nav_links > li').removeClass('active');
		popup.find('.data-panel').css("display", "none");
	}

	function enable_disable_tab(popup, index, disable){
		var link = popup.find('*[data-index="'+index+'"]');
		var panel = popup.find('.data_panel_'+index);

		if(disable){
			link.addClass('disabled');
			panel.find(":input").attr("disabled", true);
			//panel.css("display", "none");
		}else{
			link.removeClass('disabled');
			panel.find(":input").attr("disabled", false);
			//panel.css("display", "block");
		}
	}

	function form_wizard_enable_tab(popup, index){
		enable_disable_tab(popup, index, 0);
	}
	function form_wizard_disable_tab(popup, index){
		enable_disable_tab(popup, index, 1);
	}
	function form_wizard_enable_all_tabs(popup){
		popup.find('.pp_nav_links > li').removeClass('disabled');
	}

	function enable_disable_btns(popup, link){
		var nextBtn = popup.find('.next-btn');
		var prevBtn = popup.find('.prev-btn');
		var nextBtnTxt = 'Save & Next';

		if(link.hasClass('first')){
			nextBtn.prop( "disabled", false );
			prevBtn.prop( "disabled", true );
		}else if(link.hasClass('last')){
			nextBtn.prop( "disabled", true );
			prevBtn.prop( "disabled", false );
			nextBtnTxt = 'Save & Close';
		}else{
			nextBtn.prop( "disabled", false );
			prevBtn.prop( "disabled", false );
		}

		//nextBtn.find('span').text(nextBtnTxt);
	}

	function block_enter_key_submission(popup){
		popup.find('input').off('keypress').on('keypress', function(e) {
		    return e.which !== 13;
		});
	}
	
   /*-------------------------------------------
	*---- POPUP WIZARD FUNCTIONS - END ---------
	*------------------------------------------*/
		
	return {
		escapeHTML : escapeHTML,
		decodeHtml : decodeHtml,
		isHtmlIdValid : isHtmlIdValid,
		isValidHexColor : isValidHexColor,
		block_enter_key_submission : block_enter_key_submission,
		setup_tiptip_tooltips : setup_tiptip_tooltips,
		setup_color_picker : setup_color_picker,
		setup_color_pick_preview : setup_color_pick_preview,
		setup_enhanced_multi_select : setup_enhanced_multi_select,
		setup_product_dropdown : setup_product_dropdown,
		setup_sortable_table : setup_sortable_table,
		get_property_field_value : get_property_field_value,
		set_property_field_value : set_property_field_value,
		setup_form_wizard : setup_form_wizard,
		form_wizard_open : form_wizard_open,
		form_wizard_close : form_wizard_close,
		form_wizard_next : form_wizard_next,
		form_wizard_previous : form_wizard_previous,
		form_wizard_start : form_wizard_start,
		form_wizard_enable_tab : form_wizard_enable_tab,
		form_wizard_disable_tab : form_wizard_disable_tab,
		form_wizard_enable_all_tabs : form_wizard_enable_all_tabs,

   	};
}(window.jQuery, window, document));

function thwepofCloseModal(elm){
	thwepof_base.form_wizard_close(elm);
}
function thwepofWizardNext(elm){
	thwepof_base.form_wizard_next(elm);
}
function thwepofWizardPrevious(elm){
	thwepof_base.form_wizard_previous(elm);
}

var thwepof_conditions = (function($, window, document) {
	'use strict';

	var OP_AND_HTML  = '<label class="thwepof_logic_label">AND</label>';
		OP_AND_HTML += '<a href="javascript:void(0)" onclick="thwepofRemoveRuleRow(this)" class="thwepof_delete_icon dashicons dashicons-no" title="Remove"></a>';
	var OP_OR_HTML   = '<tr class="thwepo_rule_or"><td colspan="4" align="center">OR</td></tr>';
	
	var OP_HTML  = '<a href="javascript:void(0)" class="thwepof_logic_link" onclick="thwepofAddNewConditionRow(this, 1)" title="">AND</a>';
		OP_HTML += '<a href="javascript:void(0)" class="thwepof_logic_link" onclick="thwepofAddNewConditionRow(this, 2)" title="">OR</a>';
		OP_HTML += '<a href="javascript:void(0)" onclick="thwepofRemoveRuleRow(this)" class="thwepof_delete_icon dashicons dashicons-no" title="Remove"></a>';
				
	var CONDITION_HTML  = '<tr class="thwepo_condition">';
		CONDITION_HTML += '<td class="operand-type"><select name="i_rule_subject" onchange="thwepofRuleOperandTypeChangeListner(this)">';
		CONDITION_HTML += '<option value=""></option><option value="product">'+ thwepof_admin_var.product +'</option>';
		CONDITION_HTML += '<option value="category">'+ thwepof_admin_var.category +'</option>';
		CONDITION_HTML += '<option value="tag">'+ thwepof_admin_var.tag +'</option>';
		CONDITION_HTML += '</select></td>';		
		CONDITION_HTML += '<td class="operator"><select name="i_rule_comparison">';
		CONDITION_HTML += '<option value=""></option> <option value="equals">'+ thwepof_admin_var.equal +'</option><option value="not_equals">'+ thwepof_admin_var.notequal +'</option>';
		CONDITION_HTML += '</select></td>';
		CONDITION_HTML += '<td class="operand thwepo_condition_value"><input type="text" name="i_rule_value" ></td>';
		CONDITION_HTML += '<td class="actions">'+ OP_HTML +'</td></tr>';
		
	var CONDITION_SET_HTML  = '<tr class="thwepo_condition_set_row"><td>';
		CONDITION_SET_HTML += '<table class="thwepo_condition_set" width="100%" style=""><tbody>'+CONDITION_HTML+'</tbody></table>';
		CONDITION_SET_HTML += '</td></tr>';
		
	var CONDITION_SET_HTML_WITH_OR = '<tr class="thwepo_condition_set_row"><td>';
		CONDITION_SET_HTML_WITH_OR += '<table class="thwepo_condition_set" width="100%" style=""><thead>'+OP_OR_HTML+'</thead><tbody>'+CONDITION_HTML+'</tbody></table>';
		CONDITION_SET_HTML_WITH_OR += '</td></tr>';
	
	var RULE_HTML  = '<tr class="thwepo_rule_row"><td>';
		RULE_HTML += '<table class="thwepo_rule" width="100%" style=""><tbody>'+CONDITION_SET_HTML+'</tbody></table>';
		RULE_HTML += '</td></tr>';	
		
	var RULE_SET_HTML  = '<tr class="thwepo_rule_set_row"><td>';
		RULE_SET_HTML += '<table class="thwepo_rule_set" width="100%"><tbody>'+RULE_HTML+'</tbody></table>';
		RULE_SET_HTML += '</td></tr>';

	function rule_operand_type_change_listner(elm){
		$(elm).closest("tr.thwepo_condition").find("td.thwepo_condition_value").html();
		
		var subject = $(elm).val();
		var condition_row = $(elm).closest("tr.thwepo_condition");
		var target  = condition_row.find("td.thwepo_condition_value");
		
		if(subject === 'category'){
			target.html( $("#thwepo_product_cat_select").html() );
		}else if(subject === 'tag'){
			target.html( $("#thwepo_product_tag_select").html() );
		}else{
			target.html( $("#thwepo_product_select").html() );
		}

		thwepof_base.setup_enhanced_multi_select(condition_row);
		thwepof_base.setup_product_dropdown(condition_row, false);	
	}
	
	function add_new_rule_row(elm, op){
		var condition_row = $(elm).closest('tr');
		var condition = {};
		condition["subject"] = condition_row.find("select[name=i_rule_subject]").val();
		condition["comparison"] = condition_row.find("select[name=i_rule_comparison]").val();
		condition["cvalue"] = condition_row.find("select[name=i_rule_value]").val();
		if(!is_valid_condition(condition)){
			alert('Please provide a valid condition.');
			return;
		}
		
		if(op == 1){
			var conditionSetTable = $(elm).closest('.thwepo_condition_set');
			var conditionSetSize  = conditionSetTable.find('tbody tr.thwepo_condition').size();
			
			if(conditionSetSize > 0){
				$(elm).closest('td').html(OP_AND_HTML);
				conditionSetTable.find('tbody tr.thwepo_condition:last').after(CONDITION_HTML);
			}else{
				conditionSetTable.find('tbody').append(CONDITION_HTML);
			}
		}else if(op == 2){
			var ruleTable = $(elm).closest('.thwepo_rule');
			var ruleSize  = ruleTable.find('tbody tr.thwepo_condition_set_row').size();
			
			if(ruleSize > 0){
				ruleTable.find('tbody tr.thwepo_condition_set_row:last').after(CONDITION_SET_HTML_WITH_OR);
			}else{
				ruleTable.find('tbody').append(CONDITION_SET_HTML);
			}
		}	
	}
	
	function remove_rule_row(elm){
		var ctable = $(elm).closest('table.thwepo_condition_set');
		var rtable = $(elm).closest('table.thwepo_rule');
		
		$(elm).closest('tr.thwepo_condition').remove();
		
		var cSize = ctable.find('tbody tr.thwepo_condition').size();
		if(cSize == 0){
			ctable.closest('tr.thwepo_condition_set_row').remove();
		}
		
		var rSize = rtable.find('tbody tr.thwepo_condition_set_row').size();
		if(cSize == 0 && rSize == 0){
			rtable.find('tbody').append(CONDITION_SET_HTML);
		}
	}
		
	function is_valid_condition(condition){
		if(condition["subject"] && condition["comparison"]){
			return true;
		}
		return false;
	}
	
	function get_conditional_rules(elm){
		var conditionalRules = [];
		$(elm).find("#thwepo_conditional_rules tbody tr.thwepo_rule_set_row").each(function() {
			var ruleSet = [];
			$(this).find("table.thwepo_rule_set tbody tr.thwepo_rule_row").each(function() {
				var rule = [];															 
				$(this).find("table.thwepo_rule tbody tr.thwepo_condition_set_row").each(function() {
					var conditions = [];
					$(this).find("table.thwepo_condition_set tbody tr.thwepo_condition").each(function() {
						var condition = {};
						condition["subject"] = $(this).find("select[name=i_rule_subject]").val();
						condition["comparison"] = $(this).find("select[name=i_rule_comparison]").val();
						condition["cvalue"] = $(this).find("select[name=i_rule_value]").val();
						//rule["op"] = $(this).find("input[name=i_rule_op]").val();
						if(is_valid_condition(condition)){
							conditions.push(condition);
						}
					});
					if(conditions.length > 0){
						rule.push(conditions);
					}
				});
				if(rule.length > 0){
					ruleSet.push(rule);
				}
			});
			if(ruleSet.length > 0){
				conditionalRules.push(ruleSet);
			}
		});
		
		var conditionalRulesJson = conditionalRules.length > 0 ? JSON.stringify(conditionalRules) : '';
		conditionalRulesJson = encodeURIComponent(conditionalRulesJson);
		return conditionalRulesJson;
	}
		
	function populate_conditional_rules(form, conditionalRulesJson){
		var conditionalRulesHtml = "";
		if(conditionalRulesJson){
			try{
				conditionalRulesJson = decodeURIComponent(conditionalRulesJson);
				var conditionalRules = $.parseJSON(conditionalRulesJson);

				if(conditionalRules){
					jQuery.each(conditionalRules, function() {
						var ruleSet = this;	
						var rulesHtml = '';
						
						jQuery.each(ruleSet, function() {
							var rule = this;
							var conditionSetsHtml = '';
							
							var y=0;
							var ruleSize = rule.length;
							jQuery.each(rule, function() {
								var conditions = this;								   	
								var conditionsHtml = '';
								
								var x=1;
								var size = conditions.length;
								jQuery.each(conditions, function() {
									var lastRow = (x==size) ? true : false;
									var conditionHtml = populate_condition_html(this, lastRow);
									if(conditionHtml){
										conditionsHtml += conditionHtml;
									}
									x++;
								});
								
								var firstRule = (y==0) ? true : false;
								var conditionSetHtml = populate_condition_set_html(conditionsHtml, firstRule);
								if(conditionSetHtml){
									conditionSetsHtml += conditionSetHtml;
								}
								y++;
							});
							
							var ruleHtml = populate_rule_html(conditionSetsHtml);
							if(ruleHtml){
								rulesHtml += ruleHtml;
							}
						});
						
						var ruleSetHtml = populate_rule_set_html(rulesHtml);
						if(ruleSetHtml){
							conditionalRulesHtml += ruleSetHtml;
						}
					});
				}
			}catch(err) {
				alert(err);
			}
		}
		
		if(conditionalRulesHtml){
			var conditionalRulesTable = form.find("#thwepo_conditional_rules tbody");
			conditionalRulesTable.html(conditionalRulesHtml);
			
			thwepof_base.setup_enhanced_multi_select(conditionalRulesTable);
			thwepof_base.setup_product_dropdown(conditionalRulesTable, true);
			
			conditionalRulesTable.find('tr.thwepo_condition').each(function(){
				var ruleVal = $(this).find("input[name=i_rule_value_hidden]").val();	
				ruleVal = ruleVal.split(",");													
				$(this).find("select[name=i_rule_value]").val(ruleVal).trigger("change");
			});
		}else{
			var conditionalRulesTable = form.find("#thwepo_conditional_rules tbody");
			conditionalRulesTable.html(RULE_SET_HTML);
			
			thwepof_base.setup_enhanced_multi_select(conditionalRulesTable);
			thwepof_base.setup_product_dropdown(conditionalRulesTable, false);
		}
	}
	
	function populate_rule_set_html(ruleHtml){
		var html = '';
		if(ruleHtml){
			html += '<tr class="thwepo_rule_set_row"><td><table class="thwepo_rule_set" width="100%"><tbody>';
			html += ruleHtml;
			html += '</tbody></table></td></tr>';
		}
		return html;
	}
	
	function populate_rule_html(conditionSetHtml){
		var html = '';
		if(conditionSetHtml){
			html += '<tr class="thwepo_rule_row"><td><table class="thwepo_rule" width="100%" style=""><tbody>';
			html += conditionSetHtml;
			html += '</tbody></table></td></tr>';
		}
		return html;
	}
	
	function populate_condition_set_html(conditionsHtml, firstRule){
		var html = '';
		if(conditionsHtml){
			if(firstRule){
				html += '<tr class="thwepo_condition_set_row"><td><table class="thwepo_condition_set" width="100%" style=""><tbody>';
				html += conditionsHtml;
				html += '</tbody></table></td></tr>';
			}else{
				html += '<tr class="thwepo_condition_set_row"><td><table class="thwepo_condition_set" width="100%" style=""><thead>'+OP_OR_HTML+'</thead><tbody>';
				html += conditionsHtml;
				html += '</tbody></table></td></tr>';
			}
		}
		return html;
	}
	
	function populate_condition_html(condition, lastRow){
		var html = '';
		if(condition){
			var selectedSubjProd = condition.subject === "product" ? "selected" : "";
			var selectedSubjCat = condition.subject === "category" ? "selected" : "";
			var selectedSubjTag = condition.subject === "tag" ? "selected" : "";
			
			var selectedCompjE = condition.comparison === "equals" ? "selected" : "";
			var selectedCompjNE = condition.comparison === "not_equals" ? "selected" : "";
			
			var valueHtml = '<input type="hidden" name="i_rule_value_hidden" value="'+condition.cvalue+'"/>';
			if(condition.subject === "product"){
				valueHtml += $("#thwepo_product_select").html();
			}else if(condition.subject === "category"){
				valueHtml += $("#thwepo_product_cat_select").html();
			}else if(condition.subject === "tag"){
				valueHtml += $("#thwepo_product_tag_select").html();
			}else{
				valueHtml += '<input type="text" name="i_rule_value" value="'+condition.cvalue+'"/>';
			}
			
			var actionsHtml = lastRow ? OP_HTML : OP_AND_HTML;
			
			html += '<tr class="thwepo_condition">';
			html += '<td class="operand-type"><select name="i_rule_subject" onchange="thwepofRuleOperandTypeChangeListner(this)" value="'+condition.subject+'">';
			html += '<option value=""></option><option value="product" '+selectedSubjProd+'>'+ thwepof_admin_var.product +'</option>';
			html += '<option value="category" '+selectedSubjCat+'>'+ thwepof_admin_var.category +'</option>';
			html += '<option value="tag" '+selectedSubjTag+'>'+ thwepof_admin_var.tag +'</option>';
			html += '</select></td>';		
			html += '<td class="operator"><select name="i_rule_comparison" value="'+condition.comparison+'">';
			html += '<option value=""></option><option value="equals" '+selectedCompjE+'>'+ thwepof_admin_var.equal +'</option>';
			html += '<option value="not_equals" '+selectedCompjNE+'>'+ thwepof_admin_var.notequal +'</option>';
			html += '</select></td>';
			html += '<td class="operand thwepo_condition_value">'+ valueHtml +'</td>';
			html += '<td class="actions">'+ actionsHtml+'</td></tr>';							
		}
		return html;
	}

	function setup_product_dropdown(parent, set_dv){
		parent.find('select.thwepof-product-select').each(function(){
			if(!$(this).hasClass('enhanced')){
				if(set_dv){
					prepare_selected_options($(this));
				}

				var elm = $(this).selectWoo({
					//minimumResultsForSearch: 10,
					allowClear : true,
					placeholder: $(this).data('placeholder'),
					ajax: {
						type: 'POST',
				        url: ajaxurl,
				        dataType: 'json',
				        data: function(params) {
				            return {
				            	action: 'thwepof_load_products',
				                term: params.term || '',
				                page: params.page || 1,
				            }
				        },
				        processResults: function (result, params) {
		                    return result.data;
						},
				        cache: true
				    },
				}).addClass('enhanced');
			}
		});
	}

	function prepare_selected_options(elm){
		var value = elm.siblings("input[name=i_rule_value_hidden]").val();
				
		if(value){
			var data = {
	            action: 'thwepof_load_products',
	            value: value,
	        };

			$.ajax({
	            type: 'POST',
	            url : ajaxurl,
	            data: data,
	            success: function(result){
	            	$.each(result.data.results, function( key, value ) {
						var newOption = new Option(value.text, value.id, true, true);
						elm.append(newOption);
					});
	            }
	        });
	        elm.trigger('change');
		}		
	}
	
	return {
		populate_conditional_rules : populate_conditional_rules,
		rule_operand_type_change_listner : rule_operand_type_change_listner,
		add_new_rule_row : add_new_rule_row,
		remove_rule_row : remove_rule_row,
		get_conditional_rules : get_conditional_rules,
   	};
	
}(window.jQuery, window, document));

function thwepofRuleOperandTypeChangeListner(elm){
	thwepof_conditions.rule_operand_type_change_listner(elm);
}

function thwepofAddNewConditionRow(elm, op){
	thwepof_conditions.add_new_rule_row(elm, op);
}

function thwepofRemoveRuleRow(elm){
	thwepof_conditions.remove_rule_row(elm);
}

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

var thwepof_settings = (function($, window, document) {
	'use strict';

	$(function() {
		var settings_form = $('#thwepof_product_fields_form');

		thwepof_base.setup_sortable_table(settings_form, '#thwepof_product_fields', '0');
		thwepof_base.setup_tiptip_tooltips();
		thwepof_base.setup_form_wizard();
	});
   
	function select_all_fields(elm){
		var checkAll = $(elm).prop('checked');
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]').prop('checked', checkAll);
	}
   	
	function remove_selected_fields(){
		$('#thwepof_product_fields tbody tr').removeClass('strikeout');
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]:checked').each(function () {
			var row = $(this).closest('tr');
			if(!row.hasClass("strikeout")){
				row.addClass("strikeout");
			}
			row.find(".f_deleted").val(1);
			row.find(".f_edit_btn").prop('disabled', true);
	  	});	
	}

	function enable_disable_selected_fields(enabled){
		$('#thwepof_product_fields tbody input:checkbox[name=select_field]:checked').each(function(){
			var row = $(this).closest('tr');

			if(enabled == 0){
				if(!row.hasClass("thwepof-disabled")){
					row.addClass("thwepof-disabled");
				}
			}else{
				row.removeClass("thwepof-disabled");				
			}
			
			row.find(".f_edit_btn").prop('disabled', enabled == 1 ? false : true);
			row.find(".td_enabled").html(enabled == 1 ? '<span class="dashicons dashicons-yes tips" data-tip="Yes"></span>' : '-');
			row.find(".f_enabled").val(enabled);
	  	});
	}

	function widgetPopUp() {
		var x = document.getElementById("myDIV");
    	var y = document.getElementById("myWidget");
    	var th_animation=document.getElementById("th_quick_border_animation")
    	var th_arrow = document.getElementById("th_arrow_head");

    	if (x.style.display === "none" || !x.style.display) {
        	x.style.display = "block";
//         	y.style.background = "#D34156";
        	th_arrow.style="transform:rotate(-12.5deg);";
        	th_animation.style="box-shadow: 0 0 0 0 rgba(0, 0, 0, 0);";
        	th_animation.style.animation='none';
    	} else {
        	x.style.display = "none";
//         	y.style.background = "#000000";
        	th_arrow.style="transform:rotate(45deg);"
        	th_animation.style.animation='pulse 1.5s infinite';
    	}
	}
	function widgetClose() {
    	var z = document.getElementById("myDIV");
	    var za = document.getElementById("myWidget");
		var th_animation=document.getElementById("th_quick_border_animation")
	    var th_arrow = document.getElementById("th_arrow_head");
	    z.style.display = "none";
		th_arrow.style="transform:rotate(45deg);"
	    th_animation.style.animation='pulse 1.5s infinite';
	//     za.style.background = "black";
	}

	$(document).ready(function(){
	   setTimeout(function(){
	      $("#thwepof_review_request_notice").fadeIn(500);
	   }, 2000);
	});
	   				
	return {
		thwepofwidgetPopUp : widgetPopUp,
		thwepofwidgetClose : widgetClose,
		select_all_fields : select_all_fields,
		remove_selected_fields : remove_selected_fields,
		enable_disable_selected_fields : enable_disable_selected_fields,
   	};
}(window.jQuery, window, document));	

function thwepofSelectAllProductFields(elm){
	thwepof_settings.select_all_fields(elm);
}

function thwepofRemoveSelectedFields(){
	thwepof_settings.remove_selected_fields();
}

function thwepofEnableSelectedFields(){
	thwepof_settings.enable_disable_selected_fields(1);
}

function thwepofDisableSelectedFields(){
	thwepof_settings.enable_disable_selected_fields(0);
}

function thwepofwidgetPopUp(){
	thwepof_settings.thwepofwidgetPopUp();
}

function thwepofwidgetClose() {
	thwepof_settings.thwepofwidgetClose();
}