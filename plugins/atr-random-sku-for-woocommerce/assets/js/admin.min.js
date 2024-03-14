/* 
 * 
 */
jQuery(document).ready(function (jQuery) {
	function change_bg( tr_num_white, tr_num_transparent ) {
		for ( var i in tr_num_white ){
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(tr_num_white[i]).find('td').removeClass().addClass('tr-color-white-bg');
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(tr_num_white[i]).find('th').removeClass().addClass('tr-color-white-bg');
		}
		for ( var i in tr_num_transparent ){
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(tr_num_transparent[i]).find('td').removeClass().addClass('tr-color-transparent-bg');
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(tr_num_transparent[i]).find('th').removeClass().addClass('tr-color-transparent-bg');
		}		
	}
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(2).find('td').addClass('tr-color-antiquewhite-bg');
			jQuery('.atr-random-sku-settings-wrap .form-table tr').eq(2).find('th').addClass('tr-color-antiquewhite-bg');
			
		tr_num_white_1 = [];
		tr_num_transparent_1 = []; 
    // onload
	
	tr_num_white_1 = [3,4];
    if (jQuery("#select_sku_format_maxminsku").attr("checked")) {
		tr_num_white_1 = [3,4];
		tr_num_transparent_1 = [5,6,7,8];    
    } else if (jQuery("#select_sku_format_charactersforsku").attr("checked")) {
		tr_num_white_1 = [5,6];
		tr_num_transparent_1 = [3,4,7,8];       
    } else  if (jQuery("#select_sku_format_increment").attr("checked")){
		tr_num_white_1 = [7,8];
		tr_num_transparent_1 = [3,4,5,6];  		
	} 	
	change_bg( tr_num_white_1, tr_num_transparent_1 ); 
	
    // On radio change
    jQuery('input[type=radio][name=atr_select_sku_format]').change(function() {
        if (jQuery("#select_sku_format_maxminsku").attr("checked")) {
			tr_num_white_1 = [3,4];
			tr_num_transparent_1 = [5,6,7,8]; 
			change_bg( tr_num_white_1, tr_num_transparent_1 );			
        } else if (jQuery("#select_sku_format_charactersforsku").attr("checked")) {
			tr_num_white_1 = [5,6];
			tr_num_transparent_1 = [3,4,7,8]; 
			change_bg( tr_num_white_1, tr_num_transparent_1 );			
        } else if (jQuery("#select_sku_format_increment").attr("checked")) {
			tr_num_white_1 = [7,8];
			tr_num_transparent_1 = [3,4,5,6];       
			change_bg( tr_num_white_1, tr_num_transparent_1 );			
        }
    })
		
	
});


