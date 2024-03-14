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
