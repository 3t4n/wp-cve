
var jq = jQuery;


jq(document).ready(function() {

	jQuery( ".fi_side_options" ).sortable({ handle: '.fi-order-options' });
	jQuery( ".fi_side_options .fi-order-options" ).disableSelection();

	lcwp_colpick();

	jq('#fi_add_rule').on('click', function() {
		jq(this).hide();
		fi_custom_class();				
	});
	
	jq('.fi_side_options').on('click','li >span.fi_panel_name', function(e){
		e.preventDefault();
		
		t = jq(this).attr('data-tab');
		
		
		jq('.fi_side_options li').removeClass('active');
		jq(this).parent().addClass('active');
		jq('.rows-manager .fi-row-box').removeClass('opened');
		jq('.rows-manager .fi-row-box[data-tab-url="'+t+'"]').addClass('opened');
		
	});
	
	jq('.custom-rows').on('click', '.fi-remove-option',function(e) {
		e.preventDefault();
		var t = jq(this).parent('li');
		
		var g = t.find('.fi_panel_name').attr('data-tab');
		
		t.remove();
		jq('.row-manager-wrapper .box-content').find('[data-tab-url="'+g+'"]').remove();
		
		fix_custom_class();
	});

});


function fi_custom_class() {
	
	
	var fnew, side,panel;
	var today = new Date();
	var Hour = today.getHours() > 12 ? today.getHours() - 12 : (today.getHours() < 10 ? "0" + today.getHours() : today.getHours());
	var Minute = today.getMinutes() < 10 ? "0" + today.getMinutes() : today.getMinutes();
	var Seconds = today.getSeconds() < 10 ? "0" + today.getSeconds() : today.getSeconds();

	fnew = Hour+''+Minute+''+Seconds;
		
	side = '<li class="custom_class"><input name="fi_ops['+fnew+'][od]" value="1" type="hidden"><div class="fi-active-row"><input name="fi_ops['+fnew+'][stat]" class="choose_element" type="checkbox"></div><span class="fi_panel_name" data-tab="'+fnew+'">کلاس سفارشی</span><span class="fcp-bar fi-order-options"></span><span class="fcp-close fi-order-options fi-remove-option" style="left:40px;cursor: pointer !important;"></span></li>';
	
	jq('#fi_loading').html('<span class="lcwp_loading"></span>');
		
	var data = {action: 'fi_add_rule', data: fnew};

	jq.post(ajaxurl, data, function(response) {
			
			if(response !== '0') {
				jq('.fi_side_options').append(side);
				panel = '<div class="fi-row-box clearfix " data-tab-url="'+fnew+'">'+response+'</div>';
				jq('.row-manager-wrapper .box-content').append(panel);
				lcwp_colpick();
				jq('#fi_add_rule').show();
			} else {
				alert('خطایی رخ داده است!');
			}
			
	});
	
}

/***** Colour picker *****/
function lcwp_colpick() {
	
	jQuery('.lcwp_colpick input').each(function() {
		jQuery(this).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color', this.value);
		var curr_col = jQuery(this).val().replace('#', '');
		jQuery(this).colpick({
			layout:'rgbhex',
			submit:0,
			color: curr_col,
			onChange:function(hsb,hex,rgb, el, fromSetColor) {
				if(!fromSetColor){
					jQuery(el).val('#' + hex);
					jQuery(el).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color','#'+hex);
				}
			}
		}).keyup(function(){
			jQuery(this).colpickSetColor(this.value);
			jQuery(this).parents('.lcwp_colpick').find('.lcwp_colblock').css('background-color', this.value);
		});
	});
}


