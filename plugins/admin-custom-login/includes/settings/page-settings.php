<!-- Logo Settings --->
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
								<?php esc_html_e('Logo Settings',WEBLIZAR_ACL)?>
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
					<th scope="row" ><?php esc_html_e('Logo Image',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<input type="text" class="pro_text" id="logo-image" placeholder="<?php esc_attr_e('No media selected!',WEBLIZAR_ACL)?>" name="upload_image" disabled="disabled"  value="<?php echo esc_attr($logo_image); ?>"/>
						<input type="button" value="<?php esc_attr_e('Upload',WEBLIZAR_ACL)?>" id="upload-logo" class="button-primary rcsp_media_upload"/>

						<input type="button" id="display-logo" value="<?php esc_attr_e('Preview',WEBLIZAR_ACL)?>" data-toggle="modal" data-target="#logo_about_us_image_builder" class="button " onclick="Acl_show_Image_3()"/>

						<!-- Modal -->
						<div class="modal " id="logo_about_us_image_builder" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h4 class="modal-title" id="myModalLabel"><?php esc_html_e('Login Background Image',WEBLIZAR_ACL)?></h4>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
									</div>
									<div class="modal-body">
										<img class="show_prev_img" src="" style="width:100%; height:50%" id="logo_img_prev"/>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-default" data-dismiss="modal"><?php esc_html_e('Close',WEBLIZAR_ACL)?></button>
									</div>
								</div>
							</div>
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
					<th scope="row" ><?php esc_html_e('Show Logo',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input <?php checked( $logo_show, 'yes', true ); ?> type="radio" name="show_logo" value="yes" id="logo_show1">&nbsp;<label for="logo_show1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input <?php checked( $logo_show, 'no', true ); ?>  type="radio" name="show_logo" value="no" id="logo_show2">&nbsp;<label for="logo_show2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
						</span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">

			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Logo Image Width',WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr  class="radio-span" style="border-bottom:none;">
					<td>
						<div id="logo-width-slider" class="size-slider" style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="logo-width-text-box" name="headline-size-text-box"  readonly="readonly">
						<span class="slider-text-span" style="width: 25%;display:inline-block"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Logo Image Height',WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<div id="logo-height-slider" class="size-slider"style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="logo-height-text-box" name="input-size-text-box"  readonly="readonly">
						<span class="slider-text-span" style="width: 25%;display:inline-block"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Logo Link URL',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<input type="text" class="pro_text" id="log-url" name="log-url" placeholder="<?php esc_attr_e('Logo Link URL',WEBLIZAR_ACL); ?>" size="56" value="<?php echo esc_attr($logo_url); ?>"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Logo Image Title',WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<input type="text" class="pro_text" id="log-url-title" name="log-url-title" placeholder="<?php esc_attr_e('Logo Image Title', WEBLIZAR_ACL); ?>" size="56" value="<?php echo esc_attr($logo_url_title); ?>"/>
					</td>
				</tr>
			</table>
		</div>
	</div>
	<button data-dialog4="somedialog4" class="dialog-button4"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog4" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Logo',WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Save Successfully',WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button4"><?php esc_html_e('Close',WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>
	<button data-dialog10="somedialog10" class="dialog-button10"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog10" class="dialog">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Logo',WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Reset Successfully',WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button10"><?php esc_html_e('Close',WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary save-button-block">
		<div class="panel-body">
				<button type="button" onclick="return Custom_login_logo('logoSave', '');" class="btn btn-info btn-lg"><?php esc_html_e('Save Changes',WEBLIZAR_ACL)?></button>
				<button type="button" onclick="return Custom_login_logo('logoReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e('Reset Default',WEBLIZAR_ACL)?></button>
		</div>
	</div>
</div>
<?php
if(isset($_POST['Action'])) {
	$Action = sanitize_text_field($_POST['Action']);
	if( ! wp_verify_nonce( $_POST['nonce_ajax'], 'weblizar_admin_nonce' ) ) {
		die('Not authorized');
	}
	else {
		//Save Page Values
		if($Action == "logoSave") {
			$logo_image 	= sanitize_text_field($_POST['logo_image']);
			$logo_show 		= sanitize_text_field($_POST['logo_show']);
			$logo_width 	= sanitize_text_field($_POST['logo_width']);
			$logo_height 	= sanitize_text_field($_POST['logo_height']);
			$logo_url 		= sanitize_url($_POST['logo_url']);
			$logo_url_title = sanitize_title($_POST['logo_url_title']);

			// save values in option table
			$logo_page = serialize(array(
				'logo_image' 	=> $logo_image,
				'logo_show' 	=> $logo_show,
				'logo_width'	=> $logo_width,
				'logo_height'	=> $logo_height,
				'logo_url'		=> $logo_url,
				'logo_url_title'=> $logo_url_title
			));
			update_option('Admin_custome_login_logo', $logo_page);
		}

		//Reset Page Settings
		if($Action == "logoReset") {
			$logo_page = serialize(array(
				'logo_image'	 => WEBLIZAR_NALF_PLUGIN_URL.'/images/default-logo.png',
				'logo_show'		 => 'yes',
				'logo_width'	 => '274',
				'logo_height'	 => '63',
				'logo_url'		 => home_url(),
				'logo_url_title' => 'Your Site Name and Info'
			));
			update_option('Admin_custome_login_logo', $logo_page);
		}	
	}	
}