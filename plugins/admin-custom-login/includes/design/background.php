<!-- Dashboard Settings panel content --->
<?php
// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;
?>

<div class="row">
	<div class="post-social-wrapper clearfix">
		<div class="col-md-12 post-social-item">
			<div class="panel panel-default">
				<div class="panel-heading padding-none">
					<div class="post-social post-social-xs" id="post-social-5">
						<div class="text-center padding-all text-center">
							<div class="textbox text-white   margin-bottom settings-title">
								<?php esc_html_e('Background Settings', WEBLIZAR_ACL)?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Select Background', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<select id="select-background" class="standard-dropdown" name="select-background" onchange='getComboid()'>
							<option value="no-background" ><?php esc_html_e('No Background Selected', WEBLIZAR_ACL)?></option>
							<optgroup label="<?php esc_html_e('Select Background', WEBLIZAR_ACL)?>">
							<option value="static-background-color" <?php if ( $top_bg_type == 'static-background-color' ) { echo "selected"; } ?>><?php esc_html_e('Static Background Color', WEBLIZAR_ACL)?></option>
							<option value="static-background-image" <?php if ( $top_bg_type == 'static-background-image' ) { echo "selected"; } ?>><?php esc_html_e('Static Background Image', WEBLIZAR_ACL)?></option>
							</optgroup>
						</select>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div id="div-bakground-color" class="no-background">
		<div class="bg-color">
			<img src="<?php echo esc_url( WEBLIZAR_NALF_PLUGIN_URL .'/images/background-color1.png'); ?>" class="img-responsive" alt="" >
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('Background Color', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>
					<tr  class="radio-span">
						<td id="td-top-background-color">
							<input id="top-background-color" name="top-background-color" type="text" value="<?php echo esc_attr($top_color); ?>" class="my-color-field" data-default-color="#000000" />
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<div id="div-bakground-image" class="no-background">
		<div class="bg-color">
			<img src="<?php echo esc_url(WEBLIZAR_NALF_PLUGIN_URL.'/images/background-image.png'); ?>" class="img-responsive">
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('Background Image', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>
					<tr class="radio-span">
						<td>
							<input type="text" class="pro_text" id="top_image" placeholder="<?php esc_attr_e('No media selected!', WEBLIZAR_ACL)?>" name="upload_image" value="<?php echo esc_attr($top_image); ?>"/>

							<input type="button" value="<?php esc_attr_e('Upload', WEBLIZAR_ACL)?>" id="upload-logo" class="button-primary rcsp_media_upload" />

							<input type="button"  value="<?php esc_attr_e('Preview', WEBLIZAR_ACL)?>" data-toggle="modal" data-target="#top_about_us_image_builder" id="top-image-previewer" title="Font Awesome Icons"  class="button  " onclick="Acl_show_Image()" />

							<input type="button" id="display-logo" value="<?php esc_attr_e('Remove', WEBLIZAR_ACL)?>" class="button" onclick="Acl_top_img_clear();" />

							<!-- Modal -->
							<div class="modal " id="top_about_us_image_builder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">

											<h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Background Image', WEBLIZAR_ACL)?></h4>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
										</div>

										<div class="modal-body">
											<img class="show_prev_img" src="" style="width:100%; height:50%" id="top_img_prev"/>
										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close', WEBLIZAR_ACL)?></button>
										</div>
									</div>
								</div>
							</div>
							<!--End Modal -->
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('Cover or Strech', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>
					<tr  class="radio-span">
						<td>
							<input type="checkbox" value="yes" id="bg-strech" name="strech-bg"  onclick="OnChangeCheckbox(this)" style="visibility: visible;" <?php if($top_cover=="yes"){echo esc_attr("checked");}?>/>&nbsp;<label for="bg-strech"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div id="div-on-strech">
			<div class="panel panel-primary panel-default content-panel">
				<div class="panel-body">
					<table class="form-table">
						<tr>
							<th scope="row" ><?php esc_html_e('Background Repeat', WEBLIZAR_ACL)?></th>
							<td></td>
						</tr>
						<tr class="radio-span">
							<td>
								<select id="top_bg_repeat" class="standard-dropdown" name="top_bg_repeat" >
										<option value="no-repeat" ><?php esc_html_e('No Repeat', WEBLIZAR_ACL)?></option>
										<option value="repeat" ><?php esc_html_e('Repeat', WEBLIZAR_ACL)?></option>
										<option value="repeat-x" ><?php esc_html_e('Repeat Horizontally', WEBLIZAR_ACL)?></option>
										<option value="repeat-y" ><?php esc_html_e('Repeat Vertically', WEBLIZAR_ACL)?></option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="panel panel-primary panel-default content-panel">
				<div class="panel-body">
					<table class="form-table">
						<tr>
							<th scope="row" ><?php esc_html_e('Background Position', WEBLIZAR_ACL)?></th>
							<td></td>
						</tr>
						<tr class="radio-span">
							<td>
								<select id="top_bg_position" class="standard-dropdown" name="top_bg_position"  >
									<option value="left top" ><?php esc_html_e('Left Top', WEBLIZAR_ACL)?> </option>
									<option value="left center" ><?php esc_html_e('Left Center', WEBLIZAR_ACL)?> </option>
									<option value="left bottom" ><?php esc_html_e('Left Bottom', WEBLIZAR_ACL)?> </option>
									<option value="right top" ><?php esc_html_e('Right Top', WEBLIZAR_ACL)?> </option>
									<option value="right center" ><?php esc_html_e('Right Center', WEBLIZAR_ACL)?> </option>
									<option value="right bottom" ><?php esc_html_e('Right Bottom', WEBLIZAR_ACL)?></option>
									<option value="center top" ><?php esc_html_e('Center Top', WEBLIZAR_ACL)?> </option>
									<option value="center" ><?php esc_html_e('Center Center', WEBLIZAR_ACL)?> </option>
									<option value="center bottom" ><?php esc_html_e('Center Bottom', WEBLIZAR_ACL)?> </option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>

			<div class="panel panel-primary panel-default content-panel">
				<div class="panel-body">
					<table class="form-table">
						<tr>
							<th scope="row" ><?php esc_html_e('Background Attachment', WEBLIZAR_ACL)?></th>
							<td></td>
						</tr>
						<tr class="radio-span">
							<td>
								<select id="top_bg_attachment" class="standard-dropdown" name="top_bg_attachment">
										<option value="fixed" ><?php esc_html_e('Fixed', WEBLIZAR_ACL)?> </option>
										<option value="scroll" ><?php esc_html_e('Scroll', WEBLIZAR_ACL)?> </option>
										<option value="inherit" ><?php esc_html_e('Inherit', WEBLIZAR_ACL)?> </option>
								</select>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div><!-- End of div-on-strech -->
	</div><!-- End of div-bakground-image -->

	<div id="div-bakground-Slideshow" class="no-background">
		<div class="bg-color">
			<img src="<?php echo esc_url(WEBLIZAR_NALF_PLUGIN_URL.'/images/background-slideshow.png'); ?>" class="img-responsive">
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('No. Of Background Slideshow', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>
					<tr class="radio-span">
						<td>
							<select id="top_slideshow_no" class="standard-dropdown" name="top_slideshow_no" onchange="set_slideshow()">
									<option value="0" ><?php esc_html_e('Select Number of Slide Show', WEBLIZAR_ACL)?> </option>
									<option value="2" ><?php esc_html_e('2', WEBLIZAR_ACL)?> </option>
									<option value="3" ><?php esc_html_e('3', WEBLIZAR_ACL)?> </option>
									<option value="4" ><?php esc_html_e('4', WEBLIZAR_ACL)?> </option>
									<option value="5" ><?php esc_html_e('5', WEBLIZAR_ACL)?> </option>
									<option value="6" ><?php esc_html_e('6', WEBLIZAR_ACL)?> </option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('No. Of Background Slideshow', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>

					<tr  class="radio-span">
						<td style="width:100% !important" id="tdslider">
							<div class="row">
						<?php
						$Slidshow_image = unserialize(get_option('Admin_custome_login_Slidshow'));
								for($i = 1; $i <= 6; $i++) {
								?>
								<div class="col-md-4 slideshow_settings" id="slideshow_settings_<?php echo esc_attr($i);?>" style="<?php if($i<=$top_slideshow_no) { echo esc_attr(" "); } else{ ?> display:none <?php } ?>" >
								<div class="rpg-image-entry" id="rpg_img">
										<img src="<?php if($Slidshow_image['Slidshow_image_'.$i] != "") {echo esc_attr($Slidshow_image['Slidshow_image_'.$i]);} else {echo  esc_url(WEBLIZAR_NALF_PLUGIN_URL.'images/rpg-default.jpg');} ?>"  class="rpg-meta-image" alt=""  style="" id="simages-<?php echo esc_attr($i);?>">
										<input type="button" id="upload-background-<?php echo esc_attr($i);?>" name="upload-background" value="Upload Image" class="button-primary" onClick="weblizar_image('<?php echo esc_attr($i);?>')" />
										<input type="text" id="rpg_img_url-<?php echo esc_attr($i);?>" name="rpg_img_url-<?php echo esc_attr($i);?>" class="rpg_label_text-<?php echo esc_attr($i);?>"  value=""  readonly="readonly" style="display:none;" />
								</div>
								</div>
								<?php
								}// end of foreach
							?>
							</div>
						</td>
					</tr>
				</table>
			</div>
		</div>

		<div class="panel panel-primary panel-default content-panel">
			<div class="panel-body">
				<table class="form-table">
					<tr>
						<th scope="row" ><?php esc_html_e('Slider Animation', WEBLIZAR_ACL)?></th>
						<td></td>
					</tr>
					<tr class="radio-span">
						<td>
							<select id="top_bg_slider_animation" class="standard-dropdown" name="top_bg_slider_animation"  >

									<option value="slider-style1" ><?php esc_html_e('Slider Animation 1', WEBLIZAR_ACL)?> </option>
									<option value="slider-style2" ><?php esc_html_e('Slider Animation 2', WEBLIZAR_ACL)?> </option>
									<option value="slider-style3" ><?php esc_html_e('Slider Animation 3', WEBLIZAR_ACL)?> </option>
									<option value="slider-style4" ><?php esc_html_e('Slider Animation 4', WEBLIZAR_ACL)?> </option>
							</select>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</div>

	<button data-dialog1="somedialog1" class="dialog-button1"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog1" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content" >
			<div class="morph-shape" id="morph-shape1" data-morph-open1="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close1="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Top Background', WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Save Successfully', WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" id="dialog-close-button1" data-dialog-close ><?php esc_html_e('Close', WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>

	<button data-dialog11="somedialog11" class="dialog-close-button11"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog11" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" id="morph-shape1" data-morph-open1="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close1="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Top Background', WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Reset Successfully', WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button11"><?php esc_html_e('Close', WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>

	<div class="panel panel-primary save-button-block">
		<div class="panel-body">
			<div class="pull-left">
				<button type="button" onclick="return Custom_login_top('topbgSave', '');" class="btn btn-info btn-lg"><?php esc_html_e('Save Changes', WEBLIZAR_ACL)?></button>
				<button type="button" onclick="return Custom_login_top('topbgReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e('Reset Default', WEBLIZAR_ACL)?></button>
			</div>
		
		</div>
	</div>

</div>
<!-- /row -->
<?php

 ?>


<?php

	if(isset($_POST['Action'])) {

		$Action = sanitize_text_field($_POST['Action']);
		if( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
			die('Not authorized');
		}
		else {
			//Save
			if($Action == "topbgSave") {
				$select_bg_value 	     = sanitize_text_field($_POST['select_bg_value']);
				$top_background_color    = isset( $_POST['top_background_color'] ) ? sanitize_hex_color( $_POST['top_background_color'] ) : NULL;
				$top_bg_image      	     = isset( $_POST['top_bg_image'] ) ? sanitize_text_field( $_POST['top_bg_image'] ) : NULL;
				$top_cover 		   	     = isset( $_POST['top_cover'] ) ? sanitize_text_field( $_POST['top_cover'] ) : NULL;
				$top_bg_repeat     	     = isset( $_POST['top_bg_repeat'] ) ? sanitize_text_field( $_POST['top_bg_repeat'] ) : NULL;
				$top_bg_position   	     = isset( $_POST['top_bg_position'] ) ? sanitize_text_field( $_POST['top_bg_position'] ) : NULL;
				$top_bg_attachment 	     = isset( $_POST['top_bg_attachment'] ) ? sanitize_text_field( $_POST['top_bg_attachment'] ) : NULL;
				$top_slideshow_no  	     = isset( $_POST['top_slideshow_no'] ) ? sanitize_text_field( $_POST['top_slideshow_no'] ) : NULL;
				$top_bg_slider_animation = isset( $_POST['top_bg_slider_animation'] ) ? sanitize_text_field( $_POST['top_bg_slider_animation'] ) : NULL;

				$Slidshow_image_1 		 = isset( $_POST['Slidshow_image_1'] ) ? sanitize_text_field( $_POST['Slidshow_image_1'] ) : NULL;

				$Slidshow_image_2 		 = isset( $_POST['Slidshow_image_2'] ) ? sanitize_text_field( $_POST['Slidshow_image_2'] ) : NULL;

				$Slidshow_image_3 		 = isset( $_POST['Slidshow_image_3'] ) ? sanitize_text_field( $_POST['Slidshow_image_3'] ) : NULL;

				$Slidshow_image_4 		 = isset( $_POST['Slidshow_image_4'] ) ? sanitize_text_field( $_POST['Slidshow_image_4'] ) : NULL;

				$Slidshow_image_5 		 = isset( $_POST['Slidshow_image_5'] ) ? sanitize_text_field( $_POST['Slidshow_image_5'] ) : NULL;

				$Slidshow_image_6 		 = isset( $_POST['Slidshow_image_6'] ) ? sanitize_text_field( $_POST['Slidshow_image_6'] ) : NULL;

				$image_label_1 = isset( $_POST['image_label_1'] ) ? sanitize_text_field( $_POST['image_label_1'] ) : NULL;
				$image_label_2 = isset( $_POST['image_label_2'] ) ? sanitize_text_field( $_POST['image_label_2'] ) : NULL;
				$image_label_3 = isset( $_POST['image_label_3'] ) ? sanitize_text_field( $_POST['image_label_3'] ) : NULL;
				$image_label_4 = isset( $_POST['image_label_4'] ) ? sanitize_text_field( $_POST['image_label_4'] ) : NULL;
				$image_label_5 = isset( $_POST['image_label_5'] ) ? sanitize_text_field( $_POST['image_label_5'] ) : NULL;
				$image_label_6 = isset( $_POST['image_label_6'] ) ? sanitize_text_field( $_POST['image_label_6'] ) : NULL;

				// Save Values in Option Table
				$top_page= serialize(array(
					'top_bg_type'=> $select_bg_value,
					'top_color' => $top_background_color,
					'top_image' => $top_bg_image,
					'top_cover' => $top_cover,
					'top_repeat' => $top_bg_repeat,
					'top_position' => $top_bg_position,
					'top_attachment' => $top_bg_attachment,
					'top_slideshow_no' => $top_slideshow_no,
					'top_bg_slider_animation' => $top_bg_slider_animation
				));
				update_option('Admin_custome_login_top', $top_page);

				$Slidshow_image= serialize(array(
					'Slidshow_image_1'=> $Slidshow_image_1 ,
					'Slidshow_image_2'=> $Slidshow_image_2 ,
					'Slidshow_image_3'=> $Slidshow_image_3 ,
					'Slidshow_image_4'=> $Slidshow_image_4 ,
					'Slidshow_image_5'=> $Slidshow_image_5 ,
					'Slidshow_image_6'=> $Slidshow_image_6 ,
					'Slidshow_image_label_1'=> $image_label_1 ,
					'Slidshow_image_label_2'=> $image_label_2 ,
					'Slidshow_image_label_3'=> $image_label_3 ,
					'Slidshow_image_label_4'=> $image_label_4 ,
					'Slidshow_image_label_5'=> $image_label_5 ,
					'Slidshow_image_label_6'=> $image_label_6
				));
				update_option('Admin_custome_login_Slidshow', $Slidshow_image);
			}
		}
		

		if($Action == "topbgReset") {
			if( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
				die('Not authorized');
			}
			else {
				$top_page= serialize(array(
					'top_bg_type'=>'static-background-image',
					'top_color' => '#f9fad2',
					'top_image' =>   WEBLIZAR_NALF_PLUGIN_URL.'/images/3d-background.jpg',
					'top_cover' => 'yes',
					'top_repeat' => 'repeat',
					'top_position' => 'left top',
					'top_attachment' => 'fixed',
					'top_slideshow_no' => '6',
					'top_bg_slider_animation' => 'slider-style1'
				));
				update_option('Admin_custome_login_top', $top_page);
				$Slidshow_image= serialize(array(
				'Slidshow_image_1'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_2'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_3'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_4'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_5'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_6'=> WEBLIZAR_NALF_PLUGIN_URL.'/images/rpg-default.jpg',
				'Slidshow_image_label_1'=> '' ,
				'Slidshow_image_label_2'=> '' ,
				'Slidshow_image_label_3'=> '' ,
				'Slidshow_image_label_4'=> '' ,
				'Slidshow_image_label_5'=> '' ,
				'Slidshow_image_label_6'=> ''
				));
				update_option('Admin_custome_login_Slidshow', $Slidshow_image);	
			}			
		}
	}