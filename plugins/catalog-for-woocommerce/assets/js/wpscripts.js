jQuery(document).ready(function() {
	
	jQuery('.btn_color_picker').wpColorPicker();
	
	
	jQuery('.reset_button').click(function() {
		
		jQuery('.btn_title').val('Click Here');
		
		jQuery('.btn_url').val('');
		
		jQuery('.btn_new_win').prop('checked',false);
		
		jQuery('.btn_bg_col').wpColorPicker('color', '#a46497');
				
		jQuery('.btn_txt_col').wpColorPicker('color', '#ffffff');
				
		jQuery('.btn_hov_col').wpColorPicker('color', '#935386');
		
		jQuery('.btn_border_style').val('none');
		
		jQuery('.btn_bor_col').wpColorPicker('color', '#ffffff');
								
		jQuery('.btn_num').val('0');
	
		
	});
	
	jQuery('.reset_cat').click(function() {
				
		jQuery('.p_check').prop('checked',false);
	
	});
});