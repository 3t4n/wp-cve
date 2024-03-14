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
