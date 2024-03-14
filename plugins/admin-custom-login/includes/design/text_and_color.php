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
								<?php esc_html_e('Text And Color Settings', WEBLIZAR_ACL)?>
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
					<th scope="row" ><?php esc_html_e('Headline Font Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td id="td-headline-font-color">
						<input id="headline-font-color" name="headline-font-color" type="text" value="<?php echo esc_attr($heading_font_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Input Font Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td id="td-input-font-color">
						<input id="input-font-color" name="input-font-color" type="text" value="<?php echo esc_attr($input_font_color); ?>" class="my-color-field" data-default-color="#ffffff"/>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Link Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td id="td-link-font-color">
						<input id="link-color" name="link-color" type="text" value="<?php echo esc_attr($link_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Button Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td id="td-button-font-color">
						<input id="button-color" name="button-color" type="text" value="<?php echo esc_attr($button_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>
    <!-- login button font color -->
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Login Button font Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td id="td-login-button-font-color">
						<input id="login-button-text-color" name="login-button-font-color" type="text" value="<?php echo esc_attr($login_button_font_color); ?>" class="my-color-field" data-default-color="#ffffff" />
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Headline Font size', WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<div id="button-size-slider" class="size-slider" style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="headline-size-text-box" name="headline-size-text-box"  readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Input Font Size', WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<div id="button-size-slider2" class="size-slider" style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="input-size-text-box" name="input-size-text-box"  readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Link Font Size', WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<div id="button-size-slider3" class="size-slider" style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="link-size-text-box" name="link-size-text-box"  readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Button Font Size', WEBLIZAR_ACL)?><p class="font-italic"> (Use your left and right arrow keys to select the exact number)</p></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<div id="button-size-slider7" class="size-slider" style="width: 25%;display:inline-block"></div>
						<input type="text" class="slider-text" id="button-size-text-box" name="button-size-text-box"  readonly="readonly">
						<span class="slider-text-span"><?php esc_html_e('Px', WEBLIZAR_ACL)?></span>
					</td>
				</tr>
			</table>
		</div>
	</div>

<div class="panel panel-primary panel-default content-panel">
	<div class="panel-body">
		<table class="form-table">
			<tr>
				<th scope="row" ><?php esc_html_e('Show Remember Me Field', WEBLIZAR_ACL)?></th>
				<td></td>
			</tr>
			<tr class="radio-span" style="border-bottom:none;">
				<td>
					<span>
						<input type="radio" name="show_remember_me_field" value="yes" id="show_remember_me_field1" <?php if($show_remember_me_field=="yes")echo esc_attr("checked"); ?> />&nbsp;<label for="show_remember_me_field1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
					</span>
					<span>
						<input type="radio" name="show_remember_me_field" value="no" id="show_remember_me_field2" <?php if($show_remember_me_field=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="show_remember_me_field2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
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
				<th scope="row" ><?php esc_html_e('Show Back To Site Link ', WEBLIZAR_ACL)?></th>
				<td></td>
			</tr>
			<tr class="radio-span" style="border-bottom:none;">
				<td>
					<span>
						<input type="radio" name="show_back_to_site_link" value="yes" id="show_back_to_site_link1" <?php if($show_back_to_site_link=="yes")echo esc_attr("checked"); ?> />&nbsp;<label for="show_back_to_site_link1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
					</span>
					<span>
						<input type="radio" name="show_back_to_site_link" value="no" id="show_back_to_site_link2" <?php if($show_back_to_site_link=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="show_back_to_site_link2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
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
				<th scope="row" ><?php esc_html_e('Show Copyright link text', WEBLIZAR_ACL)?></th>
				<td></td>
			</tr>
			<tr class="radio-span" style="border-bottom:none;">
				<td>
					<span>
						<input type="radio" name="show_copyright_link_text" value="yes" id="show_copyright_link_text1" <?php if($show_copyright_link_text=="yes")echo esc_attr("checked"); ?> />&nbsp;<label for="show_copyright_link_text1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
					</span>
					<span>
						<input type="radio" name="show_copyright_link_text" value="no" id="show_copyright_link_text2" <?php if($show_copyright_link_text=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="show_copyright_link_text2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
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
					<th scope="row" ><?php esc_html_e('Enable Link shadow?', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="enable_Link_shadow" value="yes" id="enable_Link_shadow1" <?php if($enable_link_shadow=="yes")echo esc_attr("checked"); ?> />&nbsp;<label for="enable_Link_shadow1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
						</span>
						<span>
							<input type="radio" name="enable_Link_shadow" value="no" id="enable_Link_shadow2" <?php if($enable_link_shadow=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="enable_Link_shadow2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
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
					<th scope="row" ><?php esc_html_e('Link Shadow Color', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr  style="border-bottom:none;">
					<td>
						<input id="link-shadow-color" name="link-shadow-color" type="text" value="<?php echo esc_attr($link_shadow_color); ?>" class="my-color-field" data-default-color="#ffffff"/>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Headline Font Style', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<?php $RPP_Font_Style=""; ?>
				<tr class="" style="border-bottom:none;">
					<td>
						<select id="headline_font_style" class="standard-dropdown" name="headline_font_style">
							<optgroup label="Google Fonts">
								<?php
								    // fetch the Google font list
								    $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDVBuDznbRvMf7ckomKRcsbgHuJ1Elf0LI';
								   $response_font_api = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
								   if(!is_wp_error( $response_font_api ) ) {
								        $fonts_list = json_decode($response_font_api,  true);
								        // that's it
								        if(is_array($fonts_list)) {
								        	if(isset($fonts_list['items'])){
								        		$g_fonts = $fonts_list['items'];
								            	//print_r($fonts_list);
								            	foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?>
								                	<option value="<?php echo esc_attr($font_name); ?>" <?php selected($RPP_Font_Style, $font_name ); ?>><?php echo esc_html($font_name); ?></option><?php 
								            	}
								        	}
								            
								        } else {
								            echo esc_html(esc_html("<option disabled>Error to fetch Google fonts.</option>"));
								            echo esc_html("<option disabled>Google font will not available in offline mode.</option>");
								        }
								         
								    } 
								?>
								<option value="ntf-grandregular"><?php esc_html_e('NTF Grand Regular', WEBLIZAR_ACL)?></option>
								<option value="jameel_noori_nastaleeqregular"><?php esc_html_e('Jameel Noori Nastaleeq', WEBLIZAR_ACL)?></option>
							</optgroup>	
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
					<th scope="row" ><?php esc_html_e('Input Font Style', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<?php $RPP_Font_Style=""; ?>
				<tr class="" style="border-bottom:none;">
					<td>
						<select id="input_font_style" class="standard-dropdown" name="input_font_style"  >
							<optgroup label="Google Fonts">
								<?php
		                            // fetch the Google font list
		                            $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDVBuDznbRvMf7ckomKRcsbgHuJ1Elf0LI';
		                           $response_font_api = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
		                           if(!is_wp_error( $response_font_api ) ) {
		                                $fonts_list = json_decode($response_font_api,  true);
		                                // that's it
		                                if(is_array($fonts_list)) {
		                                	if(isset($fonts_list['items'])){
				                                    $g_fonts = $fonts_list['items'];
				                                    foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?>
				                                        <option value="<?php echo esc_attr($font_name); ?>" <?php selected($RPP_Font_Style, $font_name ); ?>><?php echo esc_html($font_name); ?></option><?php 
				                                    }
			                                	} 
			                            	} else {
			                                    echo esc_html("<option disabled>Error to fetch Google fonts.</option>");
			                                    echo esc_html("<option disabled>Google font will not available in offline mode.</option>");
			                                }
		                            } 
		                        ?>
		                        <option value="ntf-grandregular"><?php esc_html_e('NTF Grand Regular', WEBLIZAR_ACL)?></option>
		                        <option value="jameel_noori_nastaleeqregular"><?php esc_html_e('Jameel Noori Nastaleeq', WEBLIZAR_ACL)?></option>
							</optgroup>	
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
					<th scope="row" ><?php esc_html_e('Link Font Style', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<?php $RPP_Font_Style=""; ?>
				<tr class="" style="border-bottom:none;">
					<td>
						<select id="link_font_style" class="standard-dropdown" name="link_font_style">	
							<optgroup label="Google Fonts">
								<?php
		                            // fetch the Google font list
		                            $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDVBuDznbRvMf7ckomKRcsbgHuJ1Elf0LI';
		                           $response_font_api = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
		                           if(!is_wp_error( $response_font_api ) ) {
		                                $fonts_list = json_decode($response_font_api,  true);
		                                // that's it
		                                if(is_array($fonts_list)) {
		                                	if(isset($fonts_list['items'])){
			                                    $g_fonts = $fonts_list['items'];
			                                    foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?>
			                                        <option value="<?php echo esc_attr($font_name); ?>" <?php selected($RPP_Font_Style, $font_name ); ?>><?php echo esc_html($font_name); ?></option><?php 
			                                    }
			                                } 
		                                } else {
		                                    echo esc_html("<option disabled>Error to fetch Google fonts.</option>");
		                                    echo esc_html("<option disabled>Google font will not available in offline mode.</option>");
		                                }
		                                
		                            } 
		                        ?>
		                        <option value="ntf-grandregular"><?php esc_html_e('NTF Grand Regular', WEBLIZAR_ACL)?></option>
		                        <option value="jameel_noori_nastaleeqregular"><?php esc_html_e('Jameel Noori Nastaleeq', WEBLIZAR_ACL)?></option>
							</optgroup>	
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
					<th scope="row" ><?php esc_html_e('Button Font Style', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<?php $RPP_Font_Style=""; ?>
				<tr class="" style="border-bottom:none;">
					<td>
						<select id="button_font_style" class="standard-dropdown" name="button_font_style"  >
							<optgroup label="Google Fonts">
								<?php
		                            // fetch the Google font list
		                            $google_api_url = 'https://www.googleapis.com/webfonts/v1/webfonts?key=AIzaSyDVBuDznbRvMf7ckomKRcsbgHuJ1Elf0LI';
		                           $response_font_api = wp_remote_retrieve_body( wp_remote_get($google_api_url, array('sslverify' => false )));
		                           if(!is_wp_error( $response_font_api ) ) {
		                                $fonts_list = json_decode($response_font_api,  true);
		                                // that's it
		                                if(is_array($fonts_list)) {
		                                	if(isset($fonts_list['items'])){
			                                    $g_fonts = $fonts_list['items'];
			                                    foreach( $g_fonts as $g_font) { $font_name = $g_font['family']; ?>
			                                        <option value="<?php echo esc_attr($font_name); ?>" <?php selected($RPP_Font_Style, $font_name ); ?>><?php echo esc_html($font_name); ?></option><?php 
			                                    }
			                                } 
		                                } else {
		                                    echo esc_html("<option disabled>Error to fetch Google fonts.</option>");
		                                    echo esc_html("<option disabled>Google font will not available in offline mode.</option>");
		                                }

		                            } 
		                        ?>
		                        <option value="ntf-grandregular"><?php esc_html_e('NTF Grand Regular', WEBLIZAR_ACL)?></option>
		                        <option value="jameel_noori_nastaleeqregular"><?php esc_html_e('Jameel Noori Nastaleeq', WEBLIZAR_ACL)?></option>
							</optgroup>	
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
					<th scope="row" ><?php esc_html_e('Enable Input Box Icon?', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span" style="border-bottom:none;">
					<td>
						<span>
							<input type="radio" name="enable_inputbox_icon" value="yes" id="enable_inputbox_icon1" <?php if($enable_inputbox_icon=="yes")echo esc_attr("checked"); ?> />&nbsp;<label for="enable_inputbox_icon1"><?php esc_html_e('Yes', WEBLIZAR_ACL)?></label><br>
						</span>
						<span>	
							<input type="radio" name="enable_inputbox_icon" value="no" id="enable_inputbox_icon2" <?php if($enable_inputbox_icon=="no")echo esc_attr("checked"); ?> />&nbsp;<label for="enable_inputbox_icon2"><?php esc_html_e('No', WEBLIZAR_ACL)?></label><br>
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
					<th scope="row" ><?php esc_html_e('Icon For user Input Box', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="" style="border-bottom:none;">
					<td>
						<!-- Modal -->
						<div class="col-md-9">
						<div class="input-group">
							<input data-placement="bottomRight" class="form-control icp icp-auto" type="text" id="user-input-icon" name="user-input-icon" value="<?php echo esc_attr($user_input_icon); ?>"/>
							<span class="input-group-addon"></span>
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
					<th scope="row" ><?php esc_html_e('Icon For Password Input Box', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="" style="border-bottom:none;">
					<td>
						<!-- Modal -->
						<div class="col-md-9">
						<div class="input-group">
							<input data-placement="bottomRight" class="form-control icp icp-auto" type="text" id="password-input-icon" name="password-input-icon" value="<?php echo esc_attr($password_input_icon); ?>"/>
							<span class="input-group-addon"></span>
						</div>
						</div>
					</td>
				</tr>
			</table>
		</div>
	</div>

	<button data-dialog3="somedialog3" class="dialog-button3" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?>
</button>
	<div id="somedialog3" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Text and Color', WEBLIZAR_ACL); ?></strong> <?php esc_html_e('Setting Save Successfully', WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button3"><?php esc_html_e('Close', WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>

	<button data-dialog9="somedialog9" class="dialog-button9" style="display:none"><?php esc_html_e('Open Dialog', WEBLIZAR_ACL)?></button>
	<div id="somedialog9" class="dialog" style="position: fixed; z-index: 9999;">
		<div class="dialog__overlay"></div>
		<div class="dialog__content">
			<div class="morph-shape" data-morph-open="M33,0h41c0,0,0,9.871,0,29.871C74,49.871,74,60,74,60H32.666h-0.125H6c0,0,0-10,0-30S6,0,6,0H33" data-morph-close="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33">
				<svg xmlns="" width="100%" height="100%" viewBox="0 0 80 60" preserveAspectRatio="none">
					<path d="M33,0h41c0,0-5,9.871-5,29.871C69,49.871,74,60,74,60H32.666h-0.125H6c0,0-5-10-5-30S6,0,6,0H33"></path>
				</svg>
			</div>
			<div class="dialog-inner">
				<h2><strong><?php esc_html_e('Text and Color', WEBLIZAR_ACL)?></strong> <?php esc_html_e('Setting Reset Successfully', WEBLIZAR_ACL)?></h2><div><button class="action dialog-button-close" data-dialog-close id="dialog-close-button9"><?php esc_html_e('Close', WEBLIZAR_ACL)?></button></div>
			</div>
		</div>
	</div>
	
	<div class="panel panel-primary save-button-block" >
		<div class="panel-body">
				<button type="button" onclick="return Custom_login_text('textandcolorSave', '');" class="btn btn-info btn-lg"><?php esc_html_e('Save Changes', WEBLIZAR_ACL)?></button>
				<button type="button" onclick="return Custom_login_text('textandcolorReset', '');" class="btn btn-primary btn-lg"><?php esc_html_e('Reset Default', WEBLIZAR_ACL)?></button>
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
		//Save
		if($Action == "textandcolorSave"){
			$heading_font_color       = sanitize_hex_color($_POST['heading_font_color']);
			$input_font_color         = sanitize_hex_color($_POST['input_font_color']);
			$link_color               = sanitize_hex_color($_POST['link_color']);
			$button_color             = sanitize_hex_color($_POST['button_color']);
			$login_button_font_color  = sanitize_hex_color($_POST['login_button_font_color']);
			$heading_font_size        = sanitize_text_field($_POST['heading_font_size']);
			$input_font_size          = sanitize_text_field($_POST['input_font_size']);
			$link_size                = sanitize_text_field($_POST['link_size']);
			$button_font_size         = sanitize_text_field($_POST['button_font_size']);
			$enable_link_shadow       = sanitize_text_field($_POST['enable_link_shadow']);
			$show_remember_me_field   = sanitize_text_field($_POST['show_remember_me_field']);
			$show_back_to_site_link   = sanitize_text_field($_POST['show_back_to_site_link']);
			$show_copyright_link_text = sanitize_text_field($_POST['show_copyright_link_text']);
			$link_shadow_color        = sanitize_hex_color($_POST['link_shadow_color']);
			$heading_font_style       = sanitize_text_field($_POST['heading_font_style']);
			$input_font_style         = sanitize_text_field($_POST['input_font_style']);
			$link_font_style          = sanitize_text_field($_POST['link_font_style']);
			$button_font_style        = sanitize_text_field($_POST['button_font_style']);
			$enable_inputbox_icon     = sanitize_text_field($_POST['enable_inputbox_icon']);
			$user_input_icon          = sanitize_text_field( $_POST['user_input_icon']);
			$password_input_icon      = sanitize_text_field( $_POST['password_input_icon']);

			
			// Save Values in Option Table
			$text_and_color_page= serialize(array(
				'heading_font_color'       => $heading_font_color,
				'input_font_color'         => $input_font_color,
				'link_color'               => $link_color,
				'button_color'             => $button_color,
				'login_button_font_color'  => $login_button_font_color,
				'heading_font_size'        => $heading_font_size,
				'input_font_size'          => $input_font_size,
				'link_size'                => $link_size,
				'button_font_size'         => $button_font_size,
				'enable_link_shadow'       => $enable_link_shadow,
				'show_remember_me_field'   => $show_remember_me_field,
				'show_back_to_site_link'   => $show_back_to_site_link,
				'show_copyright_link_text' => $show_copyright_link_text,
				'link_shadow_color'        => $link_shadow_color,
				'heading_font_style'       => $heading_font_style,
				'input_font_style'         => $input_font_style,
				'link_font_style'          => $link_font_style,
				'button_font_style'        => $button_font_style,
				'enable_inputbox_icon'     => $enable_inputbox_icon,
				'user_input_icon'          => $user_input_icon,
				'password_input_icon'      => $password_input_icon
			));
			update_option('Admin_custome_login_text', $text_and_color_page);
		}

		/*Reset */
		if($Action == "textandcolorReset") {
			$text_and_color_page= serialize(array(
				'heading_font_color'=>'#ffffff',
				'input_font_color'=>'#000000',
				'link_color'=>'#ffffff',
				'button_color'=>'#dd3333',
				'login_button_font_color'=>'#ffffff',
				'heading_font_size'=>'14',
				'input_font_size'=>'18',
				'link_size'=>'14',
				'button_font_size'=>'14',
				'enable_link_shadow'=>'yes',
				'show_remember_me_field'=>'yes',
				'show_back_to_site_link'=>'yes',
				'show_copyright_link_text'=>'yes',
				'link_shadow_color'=>'#ffffff',
				'heading_font_style'=>'Open Sans',
				'input_font_style'=>'Open Sans',
				'link_font_style'=>'Open Sans',
				'button_font_style'=>'Open Sans',
				'enable_inputbox_icon'=>'yes',
				'user_input_icon'=>'fa-user',
				'password_input_icon'=>'fa-key'
			));
			update_option('Admin_custome_login_text', $text_and_color_page);
		}
	}
}