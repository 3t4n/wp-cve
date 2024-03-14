<?php 
	$wpsm_nonce = wp_create_nonce( 'wpsm_tabs_nonce_save_settings_values' );
  $De_Settings = unserialize(get_option('Tabs_R_default_Settings'));
  $PostId = $post->ID;
  $Settings = unserialize(get_post_meta( $PostId, 'Tabs_R_Settings', true));

	$option_names = array(
		"tabs_sec_title" 	 => $De_Settings['tabs_sec_title'],
		"show_tabs_title_icon" => $De_Settings['show_tabs_title_icon'],
		"show_tabs_icon_align" => $De_Settings['show_tabs_icon_align'],
    "enable_tabs_border"   => $De_Settings['enable_tabs_border'],
    "tabs_title_bg_clr"   => $De_Settings['tabs_title_bg_clr'],
		"tabs_title_icon_clr" => $De_Settings['tabs_title_icon_clr'],
		"select_tabs_title_bg_clr"   => $De_Settings['select_tabs_title_bg_clr'],
		"select_tabs_title_icon_clr" => $De_Settings['select_tabs_title_icon_clr'],
		"tabs_desc_bg_clr"    => $De_Settings['tabs_desc_bg_clr'],
    "tabs_desc_font_clr"  => $De_Settings['tabs_desc_font_clr'],
    "title_size"         => $De_Settings['title_size'],
    "des_size"     		 => $De_Settings['des_size'],
    "font_family"     	 => $De_Settings['font_family'],
    "tabs_styles"      =>$De_Settings['tabs_styles'],
		"custom_css"      =>$De_Settings['custom_css'],
		"tabs_animation"      =>$De_Settings['tabs_animation'],
		"tabs_alignment"      =>$De_Settings['tabs_alignment'],
		"tabs_position"      =>$De_Settings['tabs_position'],
		"tabs_margin"      =>$De_Settings['tabs_margin'],
		"tabs_content_margin"   =>$De_Settings['tabs_content_margin'],
		"tabs_display_on_mob"      =>"1",
		"tabs_display_mode_mob"      =>"2",
		
		);
		
		foreach($option_names as $option_name => $default_value) {
			if(isset($Settings[$option_name])) 
				${"" . $option_name}  = $Settings[$option_name];
			else
				${"" . $option_name}  = $default_value;
		}
	
		
?>

<Script>

 //font slider size script
  jQuery(function() {
    jQuery( "#title_size_id" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 22,
		min:8,
		slide: function( event, ui ) {
		jQuery( "#title_size" ).val( ui.value );
      }
		});
		
		jQuery( "#title_size_id" ).slider("value",<?php echo esc_html($title_size); ?> );
		jQuery( "#title_size" ).val( jQuery( "#title_size_id" ).slider( "value") );
    
  });
</script>
<Script>

 //font slider size script
  jQuery(function() {
    jQuery( "#des_size_id" ).slider({
		orientation: "horizontal",
		range: "min",
		max: 30,
		min:5,
		slide: function( event, ui ) {
		jQuery( "#des_size" ).val( ui.value );
      }
		});
		
		jQuery( "#des_size_id" ).slider("value",<?php echo esc_html($des_size); ?>);
		jQuery( "#des_size" ).val( jQuery( "#des_size_id" ).slider( "value") );
    
  });
</script>  
<Script>
function wpsm_update_default(){
	 jQuery.ajax({
		url: location.href,
		type: "POST",
		data : {
			    'action123':'default_settins_action',
			     },
                success : function(data){
									alert("Default Settings Updated");
									location.reload(true);
                                   }	
	});
	
}
</script>
<?php

if(isset($_POST['action123']) == "default_settins_action")
	{
	
		$Settings_Array2 = serialize( array(
				"tabs_sec_title" 	 => $tabs_sec_title,
				"show_tabs_title_icon" => $show_tabs_title_icon,
				"show_tabs_icon_align" => $show_tabs_icon_align,
				"enable_tabs_border"   => $enable_tabs_border,
				"tabs_title_bg_clr"   => $tabs_title_bg_clr,
				"tabs_title_icon_clr" => $tabs_title_icon_clr,
				"select_tabs_title_bg_clr"   => $select_tabs_title_bg_clr,
				"select_tabs_title_icon_clr" => $select_tabs_title_icon_clr,
				"tabs_desc_bg_clr"    => $tabs_desc_bg_clr,
				"tabs_desc_font_clr"  => $tabs_desc_font_clr,
				"title_size"         => $title_size,
				"des_size"     		 => $des_size,
				"font_family"     	 => $font_family,
				"tabs_styles"      =>$tabs_styles,
				"custom_css"      =>$custom_css,
				"tabs_animation"      =>$tabs_animation,
				"tabs_alignment"      =>$tabs_alignment,
				"tabs_position"      =>$tabs_position,
				"tabs_margin"      =>$tabs_margin,
				"tabs_content_margin"      =>$tabs_content_margin,
				) );

			update_option('Tabs_R_default_Settings', $Settings_Array2);
}

 ?>
<input type="hidden" name="wpsm_tabs_security" value="<?php echo esc_attr( $wpsm_nonce ); ?>">  
<input type="hidden" id="tabs_setting_save_action" name="tabs_setting_save_action" value="tabs_setting_save_action">
	
<table class="form-table acc_table">
	<tbody>
		
		<tr>
			<th scope="row"><label><?php _e('Display Tabs Section Title ',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<div class="switch">
					<input type="radio" class="switch-input" name="tabs_sec_title" value="yes" id="enable_tabs_sec_title" <?php if($tabs_sec_title == 'yes' ) { echo "checked"; } ?>   >
					<label for="enable_tabs_sec_title" class="switch-label switch-label-off"><?php _e('Yes',wpshopmart_tabs_r_text_domain); ?></label>
					<input type="radio" class="switch-input" name="tabs_sec_title" value="no" id="disable_tabs_sec_title"  <?php if($tabs_sec_title == 'no' ) { echo "checked"; } ?> >
					<label for="disable_tabs_sec_title" class="switch-label switch-label-on"><?php _e('No',wpshopmart_tabs_r_text_domain); ?></label>
					<span class="switch-selection"></span>
				</div>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tab_r_sec_title_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tab_r_sec_title_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Display Tabs Section Title ',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/sec-title.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Display Option For Title and icon ',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="show_tabs_title_icon" id="show_tabs_title_icon" value="1" <?php if($show_tabs_title_icon == '1' ) { echo "checked"; } ?> /> <?php esc_html_e('Show Tabs Title + Icon (both)',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="show_tabs_title_icon" id="show_tabs_title_icon" value="2" <?php if($show_tabs_title_icon == '2' ) { echo "checked"; } ?> /> <?php esc_html_e('Show Only Tabs Title',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="show_tabs_title_icon" id="show_tabs_title_icon" value="3" <?php if($show_tabs_title_icon == '3' ) { echo "checked"; } ?>  /> <?php esc_html_e('Show Only Icon',wpshopmart_tabs_r_text_domain); ?> </span>
				
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_title_icon_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_title_icon_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Display Tabs Title And Icon ',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tab-title.png'); ?>">
						<br>
						
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tab-icon.png'); ?>">
						
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Tabs Icon Position Alignment',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="show_tabs_icon_align" id="show_tabs_icon_align" value="left" <?php if($show_tabs_icon_align == 'left' ) { echo "checked"; } ?> /> <?php esc_html_e('Before Tab Title',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="show_tabs_icon_align" id="show_tabs_icon_align" value="right" <?php if($show_tabs_icon_align == 'right' ) { echo "checked"; } ?> /> <?php esc_html_e('After Tab Title',wpshopmart_tabs_r_text_domain); ?> </span>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_title_icon_align_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_title_icon_align_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Align your Tab Icon Position before title or after title',wpshopmart_tabs_r_text_domain); ?></h2>
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Display Tabs Border',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<div class="switch">
					<input type="radio" class="switch-input" name="enable_tabs_border" value="yes" id="enable_tabs_border" <?php if($enable_tabs_border == 'yes' ) { echo "checked"; } ?>   >
					<label for="enable_tabs_border" class="switch-label switch-label-off"><?php _e('Yes',wpshopmart_tabs_r_text_domain); ?></label>
					<input type="radio" class="switch-input" name="enable_tabs_border" value="no" id="disable_tabs_border"  <?php if($enable_tabs_border == 'no' ) { echo "checked"; } ?> >
					<label for="disable_tabs_border" class="switch-label switch-label-on"><?php _e('No',wpshopmart_tabs_r_text_domain); ?></label>
					<span class="switch-selection"></span>
				</div>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#enable_ac_border_tp" data-tooltip="#enable_tabs_r_border_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="enable_tabs_r_border_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Display Or Hide Tabs Border Here',wpshopmart_tabs_r_text_domain); ?></h2>
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Tabs Styles',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_styles" id="tabs_styles" value="1" <?php if($tabs_styles == '1' ) { echo "checked"; } ?> /> <?php esc_html_e('Default',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_styles" id="tabs_styles" value="2" <?php if($tabs_styles == '2' ) { echo "checked"; } ?>  /> <?php esc_html_e('Soft',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block"><input type="radio" name="tabs_styles" id="tabs_styles" value="3"  <?php if($tabs_styles == '3' ) { echo "checked"; } ?> /> <?php esc_html_e('Noise',wpshopmart_tabs_r_text_domain); ?> </span>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_styles_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_styles_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Tab Styles',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tab-title.png'); ?>">
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/soft.png'); ?>">
						<br>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/noise.png'); ?>">
					</div>
		    	</div>
				<div style="margin-top:10px;display:block;overflow:hidden;width:100%;"> <a style="margin-top:10px" href="http://wpshopmart.com/plugins/tabs-pro-plugin/" target="_balnk"><?php esc_html_e('Unlock 2 More Overlays Styles In Premium Version',wpshopmart_tabs_r_text_domain); ?></a> </div>
			
			</td>
		</tr>
		
		<tr >
			<th scope="row"><label><?php _e('Tabs Title Background Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="tabs_title_bg_clr" name="tabs_title_bg_clr" type="text" value="<?php echo esc_attr($tabs_title_bg_clr); ?>" class="my-color-field" data-default-color="#e8e8e8" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_title_bg_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_title_bg_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Tabs Title Background Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tabs-bg.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr >
			<th scope="row"><label><?php _e('Tabs Title/Icon Font Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="tabs_title_icon_clr" name="tabs_title_icon_clr" type="text" value="<?php echo esc_attr($tabs_title_icon_clr); ?>" class="my-color-field" data-default-color="#ffffff" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_title_icon_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_title_icon_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Tabs Title/Icon Font Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tabs-ft-color.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		
		<tr >
			<th scope="row"><label><?php _e('Selected Tabs Title Background Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="select_tabs_title_bg_clr" name="select_tabs_title_bg_clr" type="text" value="<?php echo esc_attr($select_tabs_title_bg_clr); ?>" class="my-color-field" data-default-color="#e8e8e8" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_sel_bg_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_sel_bg_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Selected/Open Tabs Title Background Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/sel-tab-color.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr >
			<th scope="row"><label><?php _e('Selected Tabs Title/Icon Font Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="select_tabs_title_icon_clr" name="select_tabs_title_icon_clr" type="text" value="<?php echo esc_attr($select_tabs_title_icon_clr); ?>" class="my-color-field" data-default-color="#ffffff" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_sel_icon_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_sel_icon_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Selected/Open Tabs Title/Icon Font Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/tabs-ft-color.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr >
			<th scope="row"><label><?php _e('Tabs Description Background Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="tabs_desc_bg_clr" name="tabs_desc_bg_clr" type="text" value="<?php echo esc_attr($tabs_desc_bg_clr); ?>" class="my-color-field" data-default-color="#ffffff" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_desc_bg_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_desc_bg_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Tabs Description Background Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/desc-bg-color.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr >
			<th scope="row"><label><?php _e('Tabs Description Font Colour',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<input id="tabs_desc_font_clr" name="tabs_desc_font_clr" type="text" value="<?php echo esc_attr($tabs_desc_font_clr); ?>" class="my-color-field" data-default-color="#000000" />
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_desc_font_clr_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_desc_font_clr_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Tabs Description Font Colour',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/noise.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr class="setting_color">
			<th><?php _e('Tabs Title/Icon Font Size',wpshopmart_tabs_r_text_domain); ?> </th>
			<td>
				<div id="title_size_id" class="size-slider" ></div>
				<input type="text" class="slider-text" id="title_size" name="title_size"  readonly="readonly">
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#title_size_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="title_size_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;max-width: 300px;">
						<h2 style="color:#fff !important;"><?php esc_html_e('You can update Tabs Title and Icon Font Size from here. Just Scroll it to change size.',wpshopmart_tabs_r_text_domain); ?></h2>
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr class="setting_color">
			<th><?php _e('Tabs Description Font Size',wpshopmart_tabs_r_text_domain); ?> </th>
			<td>
				<div id="des_size_id" class="size-slider" ></div>
				<input type="text" class="slider-text" id="des_size" name="des_size"  readonly="readonly">
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#des_size_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="des_size_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;max-width: 300px;">
						<h2 style="color:#fff !important;"><?php esc_html_e('You can update Tabs Description/content Font Size from here. Just Scroll it to change size.',wpshopmart_tabs_r_text_domain); ?></h2>
						
					</div>
		    	</div>
			</td>
		</tr>
		<tr >
			<th><?php _e('Font Style/Family',wpshopmart_tabs_r_text_domain); ?> </th>
			<td>
				<select name="font_family" id="font_family" class="standard-dropdown" style="width:100%" >
					<optgroup label="Default Fonts">
						<option value="0"        <?php if($font_family == '0' ) { echo "selected"; } ?>><?php esc_html_e('Theme Default Style',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Arial"           <?php if($font_family == 'Arial' ) { echo "selected"; } ?>><?php esc_html_e('Arial',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Arial Black"    <?php if($font_family == 'Arial Black' ) { echo "selected"; } ?>><?php esc_html_e('Arial Black',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Courier New"     <?php if($font_family == 'Courier New' ) { echo "selected"; } ?>><?php esc_html_e('Courier New',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Georgia"         <?php if($font_family == 'Georgia' ) { echo "selected"; } ?>><?php esc_html_e('Georgia',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Grande"          <?php if($font_family == 'Grande' ) { echo "selected"; } ?>><?php esc_html_e('Grande',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Helvetica" 	<?php if($font_family == 'Helvetica' ) { echo "selected"; } ?>><?php esc_html_e('Helvetica Neue',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Impact"         <?php if($font_family == 'Impact' ) { echo "selected"; } ?>><?php esc_html_e('Impact',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Lucida"         <?php if($font_family == 'Lucida' ) { echo "selected"; } ?>><?php esc_html_e('Lucida',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Lucida Grande"         <?php if($font_family == 'Lucida Grande' ) { echo "selected"; } ?>><?php esc_html_e('Lucida Grande',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Open Sans"   <?php if($font_family == 'Open Sans' ) { echo "selected"; } ?>><?php esc_html_e('Open Sans',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="OpenSansBold"   <?php if($font_family == 'OpenSansBold' ) { echo "selected"; } ?>><?php esc_html_e('OpenSansBold',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Palatino Linotype"       <?php if($font_family == 'Palatino Linotype' ) { echo "selected"; } ?>><?php esc_html_e('Palatino',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Sans"           <?php if($font_family == 'Sans' ) { echo "selected"; } ?>><?php esc_html_e('Sans',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="sans-serif"           <?php if($font_family == 'sans-serif' ) { echo "selected"; } ?>><?php esc_html_e('Sans-Serif',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Tahoma"         <?php if($font_family == 'Tahoma' ) { echo "selected"; } ?>><?php esc_html_e('Tahoma',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Times New Roman"          <?php if($font_family == 'Times New Roman' ) { echo "selected"; } ?>><?php esc_html_e('Times New Roman',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Trebuchet"      <?php if($font_family == 'Trebuchet' ) { echo "selected"; } ?>><?php esc_html_e('Trebuchet',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="Verdana"        <?php if($font_family == 'Verdana' ) { echo "selected"; } ?>><?php esc_html_e('Verdana',wpshopmart_tabs_r_text_domain); ?></option>
					</optgroup>
				</select>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#font_family_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="font_family_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;max-width: 300px;">
						<h2 style="color:#fff !important;"><?php esc_html_e('You can update Tabs Title and Description Font Family/Style from here. Select any one form these options.',wpshopmart_tabs_r_text_domain); ?></h2>
					
					</div>
		    	</div>
				<div style="margin-top:10px;display:block;overflow:hidden;width:100%;"> <a style="margin-top:10px" href="http://wpshopmart.com/plugins/tabs-pro-plugin/" target="_balnk"><?php esc_html_e('Get 500+ Google Fonts In Premium Version',wpshopmart_tabs_r_text_domain); ?></a> </div>
			
			</td>
		</tr>
		
		
		<tr >
			<th><?php _e('Tabs Description Animation',wpshopmart_tabs_r_text_domain); ?> </th>
			<td>
				<select name="tabs_animation" id="tabs_animation" class="standard-dropdown" style="width:100%" >
						<option value="fadeIn"           <?php if($tabs_animation == 'fadeIn' ) { echo "selected"; } ?>><?php esc_html_e('Fade Animation',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="fadeInUp"     <?php if($tabs_animation == 'fadeInUp' ) { echo "selected"; } ?>><?php esc_html_e('Fade In Up Animation',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="fadeInDown"         <?php if($tabs_animation == 'fadeInDown' ) { echo "selected"; } ?>><?php esc_html_e('Fade In Down Animation',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="fadeInLeft"          <?php if($tabs_animation == 'fadeInLeft' ) { echo "selected"; } ?>><?php esc_html_e('Fade In Left Animation',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="fadeInRight" 	<?php if($tabs_animation == 'fadeInRight' ) { echo "selected"; } ?>><?php esc_html_e('Fade In Right Animation',wpshopmart_tabs_r_text_domain); ?></option>
						<option value="None"         <?php if($tabs_animation == 'None' ) { echo "selected"; } ?>><?php esc_html_e('No Animation',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flip"  disabled   		<?php if($tabs_animation == 'flip' ) { echo "selected"; } ?> ><?php esc_html_e('flip (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flipInX"  disabled  		<?php if($tabs_animation == 'flipInX' ) { echo "selected"; } ?> ><?php esc_html_e('flipInX (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flipInY"   disabled 		<?php if($tabs_animation == 'flipInY' ) { echo "selected"; } ?> ><?php esc_html_e('flipInY (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flipOutX"  disabled  	<?php if($tabs_animation == 'flipOutX' ) { echo "selected"; } ?> ><?php esc_html_e('flipOutX (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flipOutY"   disabled 	<?php if($tabs_animation == 'flipOutY' ) { echo "selected"; } ?> ><?php esc_html_e('flipOutY (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="zoomIn"    disabled		<?php if($tabs_animation == 'zoomIn' ) { echo "selected"; } ?> ><?php esc_html_e('ZoomIn (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="zoomInLeft"  disabled  	<?php if($tabs_animation == 'zoomInLeft' ) { echo "selected"; } ?> ><?php esc_html_e('ZoomInLeft (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="zoomInRight" disabled   	<?php if($tabs_animation == 'zoomInRight' ) { echo "selected"; } ?> ><?php esc_html_e('ZoomInRight (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="zoomInUp"   disabled 	<?php if($tabs_animation == 'zoomInUp' ) { echo "selected"; } ?> ><?php esc_html_e('ZoomInUp (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="zoomInDown" disabled   	<?php if($tabs_animation == 'zoomInDown' ) { echo "selected"; } ?> ><?php esc_html_e('ZoomInDown (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounce"   disabled 		<?php if($tabs_animation == 'bounce' ) { echo "selected"; } ?> ><?php esc_html_e('bounce (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounceIn"   disabled 	<?php if($tabs_animation == 'bounceIn' ) { echo "selected"; } ?> ><?php esc_html_e('bounceIn (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounceInLeft" disabled   <?php if($tabs_animation == 'bounceInLeft' ) { echo "selected"; } ?> ><?php esc_html_e('bounceInLeft (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounceInRight" disabled   <?php if($tabs_animation == 'bounceInRight' ) { echo "selected"; } ?> ><?php esc_html_e('bounceInRight (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounceInUp"   disabled 	<?php if($tabs_animation == 'bounceInUp' ) { echo "selected"; } ?> ><?php esc_html_e('bounceInUp (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="bounceInDown"  disabled   <?php if($tabs_animation == 'bounceInDown' ) { echo "selected"; } ?> ><?php esc_html_e('bounceInDown (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="flash"    disabled		<?php if($tabs_animation == 'flash' ) { echo "selected"; } ?> ><?php esc_html_e('flash (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="pulse"  disabled  		<?php if($tabs_animation == 'pulse' ) { echo "selected"; } ?> ><?php esc_html_e('pulse (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="shake"    disabled		<?php if($tabs_animation == 'shake' ) { echo "selected"; } ?> ><?php esc_html_e('shake (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="swing"   disabled 		<?php if($tabs_animation == 'swing' ) { echo "selected"; } ?> ><?php esc_html_e('swing (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="tada"    disabled		<?php if($tabs_animation == 'tada' ) { echo "selected"; } ?> ><?php esc_html_e('tada (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="wobble"   disabled 		<?php if($tabs_animation == 'wobble' ) { echo "selected"; } ?> ><?php esc_html_e('wobble (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="lightSpeedIn" disabled    <?php if($tabs_animation == 'lightSpeedIn' ) { echo "selected"; } ?> ><?php esc_html_e('lightSpeedIn (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rollIn"    	disabled	<?php if($tabs_animation == 'rollIn' ) { echo "selected"; } ?> ><?php esc_html_e('rollIn (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="slideInDown"  disabled  		<?php if($tabs_animation == 'slideInDown' ) { echo "selected"; } ?> ><?php esc_html_e('slideInDown (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="slideInLeft"  disabled  		<?php if($tabs_animation == 'slideInLeft' ) { echo "selected"; } ?> ><?php esc_html_e('slideInLeft (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="slideInRight" disabled   		<?php if($tabs_animation == 'slideInRight' ) { echo "selected"; } ?> ><?php esc_html_e('slideInRight (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="slideInUp"   disabled 		<?php if($tabs_animation == 'slideInUp' ) { echo "selected"; } ?> ><?php esc_html_e('slideInUp (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rotateIn"    disabled		<?php if($tabs_animation == 'rotateIn' ) { echo "selected"; } ?> ><?php esc_html_e('rotateIn (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rotateInDownLeft" disabled   		<?php if($tabs_animation == 'rotateInDownLeft' ) { echo "selected"; } ?> ><?php esc_html_e('rotateInDownLeft (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rotateInDownRight"  disabled  		<?php if($tabs_animation == 'rotateInDownRight' ) { echo "selected"; } ?> ><?php esc_html_e('rotateInDownRight (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rotateInUpLeft"    disabled		<?php if($tabs_animation == 'rotateInUpLeft' ) { echo "selected"; } ?> ><?php esc_html_e('rotateInUpLeft (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
					<option value="rotateInUpRight"   disabled 		<?php if($tabs_animation == 'rotateInUpRight' ) { echo "selected"; } ?> ><?php esc_html_e('rotateInUpRight (Available in Pro)',wpshopmart_tabs_r_text_domain); ?></option>
				
				</select>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_animation">help</a>
				<div id="tabs_r_animation" style="display:none;">
					<div style="color:#fff !important;padding:10px;max-width: 300px;">
						<h2 style="color:#fff !important;"><?php esc_html_e('Animation your tabs content on click , select your animation form here',wpshopmart_tabs_r_text_domain); ?></h2>
					</div>
		    	</div>
				<div style="margin-top:10px;display:block;overflow:hidden;width:100%;"> <a style="margin-top:10px" href="http://wpshopmart.com/plugins/tabs-pro-plugin/" target="_balnk"><?php esc_html_e('Unlock 25+ More Animation Effect In Premium Version',wpshopmart_tabs_r_text_domain); ?></a> </div>
			
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Tabs Alignment ',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
					<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_alignment" id="tabs_alignment" value="horizontal" <?php if($tabs_alignment == 'horizontal' ) { echo "checked"; } ?> /><?php esc_html_e('Horizontal',wpshopmart_tabs_r_text_domain); ?> </span>
				    <span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_alignment" id="tabs_alignment" value="vertical" <?php if($tabs_alignment == 'vertical') { echo "checked"; } ?> /><?php esc_html_e('Vertical',wpshopmart_tabs_r_text_domain); ?> </span>
				
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_align"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_align" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Align Your Tabs from here',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/margin-con-tab.png'); ?>">
					
						<br>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/vertical-left.png'); ?>">
					
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Tabs Position ',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<div class="switch">
					<input type="radio" class="switch-input" name="tabs_position" value="left" id="enable_tabs_position" <?php if($tabs_position == 'left' ) { echo "checked"; } ?>  >
					<label for="enable_tabs_position" class="switch-label switch-label-off"><?php _e('left',wpshopmart_tabs_r_text_domain); ?></label>
					<input type="radio" class="switch-input" name="tabs_position" value="right" id="disable_tabs_position" <?php if($tabs_position == 'right' ) { echo "checked"; } ?> >
					<label for="disable_tabs_position" class="switch-label switch-label-on"><?php _e('right',wpshopmart_tabs_r_text_domain); ?></label>
					<span class="switch-selection"></span>
				</div>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_pos"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_pos" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Align Your Tabs position here ',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/vertical-left.png'); ?>">
						<br>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/vertical-right.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Margin Between Two Tabs',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<div class="switch">
					<input type="radio" class="switch-input" name="tabs_margin" value="yes" id="enable_tabs_margin" <?php if($tabs_margin == 'yes' ) { echo "checked"; } ?>  >
					<label for="enable_tabs_margin" class="switch-label switch-label-off"><?php _e('Yes',wpshopmart_tabs_r_text_domain); ?></label>
					<input type="radio" class="switch-input" name="tabs_margin" value="no" id="disable_tabs_margin" <?php if($tabs_margin == 'no' ) { echo "checked"; } ?> >
					<label for="disable_tabs_margin" class="switch-label switch-label-on"><?php _e('No',wpshopmart_tabs_r_text_domain); ?></label>
					<span class="switch-selection"></span>
				</div>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_2_margin"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_2_margin" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Margin Between Two Tabs ',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/margin-2-tab.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Margin Between Tabs And Content',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<div class="switch">
					<input type="radio" class="switch-input" name="tabs_content_margin" value="yes" id="enable_tabs_content_margin" <?php if($tabs_content_margin == 'yes' ) { echo "checked"; } ?>  >
					<label for="enable_tabs_content_margin" class="switch-label switch-label-off"><?php _e('Yes',wpshopmart_tabs_r_text_domain); ?></label>
					<input type="radio" class="switch-input" name="tabs_content_margin" value="no" id="disable_tabs_content_margin" <?php if($tabs_content_margin == 'no' ) { echo "checked"; } ?> >
					<label for="disable_tabs_content_margin" class="switch-label switch-label-on"><?php _e('No',wpshopmart_tabs_r_text_domain); ?></label>
					<span class="switch-selection"></span>
				</div>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_r_con_marg"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_r_con_marg" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Margin Between Tabs And Content',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/margin-con-tab.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php _e('Tabs Mobile display Settings',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_display_on_mob" id="tabs_display_on_mob" value="1" <?php if($tabs_display_on_mob == '1' ) { echo "checked"; } ?> /> <?php esc_html_e('Display Both Title + Icon',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_display_on_mob" id="tabs_display_on_mob" value="2" <?php if($tabs_display_on_mob == '2' ) { echo "checked"; } ?>  /> <?php esc_html_e('Display only Icon',wpshopmart_tabs_r_text_domain); ?> </span>
				<span style="display:block"><input type="radio" name="tabs_display_on_mob" id="tabs_display_on_mob" value="3"  <?php if($tabs_display_on_mob == '3' ) { echo "checked"; } ?> /> <?php esc_html_e('Display Only Title',wpshopmart_tabs_r_text_domain); ?> </span>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_display_on_mob_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_display_on_mob_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Hide/display your icon and title on mobile and tablets',wpshopmart_tabs_r_text_domain); ?></h2>
					
					</div>
		    	</div>
			</td>
		</tr>
		
		<tr>
			<th scope="row"><label><?php _e('Title Display Mode In Mobile',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_display_mode_mob" id="tabs_display_mode_mob" value="1" <?php if($tabs_display_mode_mob == '1' ) { echo "checked"; } ?> /> <?php esc_html_e('Display As a tabs',wpshopmart_tabs_r_text_domain); ?>  </span>
				<span style="display:block;margin-bottom:10px"><input type="radio" name="tabs_display_mode_mob" id="tabs_display_mode_mob" value="2" <?php if($tabs_display_mode_mob == '2' ) { echo "checked"; } ?>  /> <?php esc_html_e('Display  As A vertical Button',wpshopmart_tabs_r_text_domain); ?> </span>
				<!-- Tooltip -->
				<a  class="ac_tooltip" href="#help" data-tooltip="#tabs_display_mode_mob_tp"><?php esc_html_e('help',wpshopmart_tabs_r_text_domain); ?></a>
				<div id="tabs_display_mode_mob_tp" style="display:none;">
					<div style="color:#fff !important;padding:10px;">
						<h2 style="color:#fff !important;"><?php _e('Display Your Title as Vrtical Button or as tabs in Mobile',wpshopmart_tabs_r_text_domain); ?></h2>
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/noise.png'); ?>">
						
						<img src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/tooltip/img/as-a-button.png'); ?>">
					</div>
		    	</div>
			</td>
		</tr>
		<tr>
			<th scope="row"><label><?php _e('Tabs On Hover',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<img style="width:100px; "class="wpsm_img_responsive"  src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/images/snap.png'); ?>" />
				<br />
				<a style="margin-top:10px" href="http://wpshopmart.com/plugins/tabs-pro-plugin/" target="_balnk"><?php esc_html_e('Available In Premium Version',wpshopmart_tabs_r_text_domain); ?></a>
			</td>
		</tr>
	
		<tr>
			<th scope="row"><label><?php _e('',wpshopmart_tabs_r_text_domain); ?></label></th>
			<td>
				<img class="wpsm_img_responsive"  src="<?php echo esc_url(wpshopmart_tabs_r_directory_url.'assets/images/more-setting.jpg'); ?>" />
				<br />
				<a style="margin-top:10px" href="http://wpshopmart.com/plugins/tabs-pro-plugin/" target="_balnk"><?php esc_html_e('Available In Premium Version',wpshopmart_tabs_r_text_domain); ?></a>
			</td>
		</tr>
		<script>
		
		jQuery('.ac_tooltip').darkTooltip({
				opacity:1,
				gravity:'east',
				size:'small'
			});
			

		</script>
	</tbody>
</table>