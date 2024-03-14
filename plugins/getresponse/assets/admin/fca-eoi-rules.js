/* jshint asi: true */

jQuery( document ).ready( function( $ ) {
	
	$('#fca_eoi_add_rule').click(function(e){
		e.preventDefault()
		jQuery('#fca_eoi_primary_rules_table').append( fcaEoiRuleTableHtml.rowNew )
		
		add_condition_select_handlers()
		set_available_conditions()
		add_delete_handlers()
		$(this).blur()
		
		if ( $('.fca_eoi_condition_select').length === 1 && fcaEoiDistro === 'free' || $('.fca_eoi_condition_select').length === 6 ) {
			$(this).hide()
		}
	})
		
	function load_rules() {
		var optional_rules = []
		//unset the conditions that always show
		if (  $.isArray( fcaEoiRules ) ) {
			optional_rules = fcaEoiRules.filter(function( el ){
				return el.parameter !== 'devices' && el.parameter !== 'show_every' && el.parameter !== 'live'
			})			
		} else {
			//backward compatibility from 1.7.x & earlier to 1.8
			for ( el in fcaEoiRules ) {
				var obj = fcaEoiRules[el]
				if ( obj.parameter !==  'devices' && obj.parameter !== 'show_every' && obj.parameter !== 'live' ) {
					optional_rules.push( {
							'parameter': obj.parameter,
							'value': obj.value						
						} )
				}
			}
			//set devices
			if ( fcaEoiDistro === 'premium' ) {
				$('[name="fca_eoi[publish_lightbox][devices]"]').val( 'all' )
			} else {
				$('[name="fca_eoi[publish_lightbox][devices]"]').val( 'desktop' )
			}
			
			if ( $('[name="fca_eoi[publish_lightbox][show_every]"]').val() === 'never' ) {
				$('[name="fca_eoi[publish_lightbox][live]"]').prop( 'checked', false )
			} else {
				$('[name="fca_eoi[publish_lightbox][live]"]').prop( 'checked', true )
			}
		}
		$.each( optional_rules, function ( key, obj ) {
			$('#fca_eoi_add_rule').click()
			$('.fca_eoi_condition_select').last().val( obj.parameter ).change()
			if ( obj.parameter === 'exit_intervention' ) {
				$('.fca_eoi_condition_select').last().closest('th').next().find('input, select').prop( 'checked', 'checked' ).change()
			} else {
				$('.fca_eoi_condition_select').last().closest('th').next().find('input, select').val( obj.value ).change()
			}
		})
		
	}
	//console.log ( fcaEoiRules )
	load_rules()

})	
	
function add_delete_handlers() {
	var $ = jQuery
	
	$('.fca_eoi_delete_condition').unbind('click')
	
	$('.fca_eoi_delete_condition').click(function(){
		$(this).closest('tr').remove()
		$('#fca_eoi_add_rule').show()
		set_available_conditions()
	})
	
}

function set_available_conditions() {
	var $ = jQuery
	
	var activeConditions = []
		$('.fca_eoi_condition_select').each(function(){
		activeConditions.push( $(this).val() )
	})	
	$('.fca_eoi_condition_select').children().show()
		
	$.each( activeConditions, function ( index, conditionStr ) {
		$('.fca_eoi_condition_select').children('[value="' + conditionStr + '"]').hide()
	})
}

function add_condition_select_handlers() {
	
	var $ = jQuery
	
	$('.fca_eoi_condition_select').unbind('change')
	
	$('.fca_eoi_condition_select').change(function(){
		
		$targetTd = $(this).closest('th').next()
		var newHtml = false
		
		set_available_conditions()
		
		$(this).siblings('.fca_eoi_at_least').hide()
		
		switch ( $(this).val() ) {
			
			case "scrolled_percent":
				newHtml = fcaEoiRuleTableHtml.dataScroll
				$(this).siblings('.fca_eoi_at_least').show()
				break
			
			case "pageviews":
				newHtml = fcaEoiRuleTableHtml.dataPageviews
				 $(this).siblings('.fca_eoi_at_least').show()
				break
				
			case "time_on_page":
				newHtml = fcaEoiRuleTableHtml.dataTime
				 $(this).siblings('.fca_eoi_at_least').show()
				break
				
			case "include":
				newHtml = fcaEoiRuleTableHtml.dataInclude
				break
			
			case "exclude":
				newHtml = fcaEoiRuleTableHtml.dataExclude
				break
			
			case "exit_intervention":
				newHtml = fcaEoiRuleTableHtml.dataExit
				break
				
			default:
				
		}
		
		if ( newHtml ) {
			$targetTd.html( newHtml )
			$targetTd.find('.select2').select2()
		}
	})
	
}