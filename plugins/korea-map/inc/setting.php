<?php
	// Kimsmap admin_setting.php - 200514
	//////////////////////////////////////////////////
	
	function kims_admin_settings () {
		
		$strSaveResult = '';
		$arMarkerID = array(1, 2, 3, 4, 5, 6);
		$nMaxZoomLevel = 14;
		
		if (!current_user_can('manage_options')) {
			wp_die( __('You do not have sufficient permissions to access this page.') );
			exit;
		}
				
		if( isset($_POST['api_key']) ) {
			$option = array();
			$option['api_key'] = sanitize_text_field($_POST['api_key']);
			
			if( strlen($option['api_key']) == 32 ){
				update_option(KIMS_OPTION_NAME, $option);
				$strSaveTagClass = 'notice notice-success';
				$strSaveResultMsg = __('Settings saved.');
			}
			else{
				$strSaveTagClass = 'notice notice-error';
				$strSaveResultMsg = __('App Key is 32 character.', KIMS_TEXT_DOMAIN);				
			}
			
			$strSaveResult = '<div class="'.$strSaveTagClass.'" style="clear:both;"><p><strong>'.$strSaveResultMsg.'</strong></p></div>';
		}
		
		$option = KimsGetOptions();
		?>
			<?php _e($strSaveResult); ?>
					
			<div class="wrap">
				<h1>
					Korea Map
					<p style="float:right;">
						<a href="http://icansoft.com/product/korea-map" target="_blank"><?php _e('Homepage'); ?></a> <b>|</b>
						<a href="http://facebook.com/groups/koreasns" target="_blank"><?php _e('Support Forum', KIMS_TEXT_DOMAIN); ?></a>
					</p>
				</h1>
				
				<div class="postbox">
					<div class="inside">
						<h3>
							<span><?php _e('Settings'); ?></span>
						</h3>
					
						<form id="frmSetting" method="post" action="">
							<p>
								<?php _e('Your Kakao App Key', KIMS_TEXT_DOMAIN); ?> (Javascript Key)
							</p>
							<p>
								<input type="text" name="api_key" size="40" value="<?php _e($option['api_key']); ?>">
							</p>		
							<p>
								<a href="http://icansoft.com/blog/getting-api-key-for-kakaotalk-web-share" target="_blank">
									[?] <?php _e('Getting apps key from Kakao Developers', KIMS_TEXT_DOMAIN); ?>
								</a>
							</p>
										
							<input type="button" id="btSubmmit" name="Submit" class="button-primary kims-submit" value="<?php _e('Save Changes'); ?>" />
						</form>
					</div>
				</div>
				
				<div class="postbox">
					<form id="frmShort">
						<div class="inside">
							<h3>
								<span><?php _e('Get Short Code', KIMS_TEXT_DOMAIN); ?></span>
							</h3>
							
							<table class="form-table">	
								<tr>
									<td><strong><?php _e('Title'); ?></strong></td>
									<td>
										<input type="text" name="title" size="30" value="<?php _e('Title'); ?>">
									</td>
								</tr>
								
								<tr>
									<td><strong><?php _e('Address', KIMS_TEXT_DOMAIN); ?></strong></td>
									<td>
										<input type="button" class="button" id="btShowPostcode" value="<?php _e('Search'); ?>" />
										<input type="text" name="address" size="50" value="서울 중구 세종대로 110">
									</td>
								</tr>
								
								<tr>
									<td><strong><?php _e('Size'); ?></strong></td>
									<td>
										<?php _e('Width'); ?> <input type="text" name="width" size="5" value="100%">
										&nbsp;
										<?php _e('Height'); ?> <input type="text" name="height" size="5" value="480px">
									</td>
								</tr>
								
								<tr>
									<td><strong><?php _e('Default Zoom Level', KIMS_TEXT_DOMAIN); ?></strong></td>
									<td>
										<select name="level">
											<?php
												for($i=1 ; $i<=$nMaxZoomLevel ; $i++){													
													?>
														<option value="<?php _e($i); ?>" <?php selected($i==3); ?>><?php _e($i); ?></option>
													<?php												
												}
											?>
										</select>										
									</td>
								</tr>
								
								<tr>
									<td><strong><?php _e('Marker', KIMS_TEXT_DOMAIN); ?></strong></td>
									<td>
										<?php
											foreach($arMarkerID as $strMarkerID){
												$strImageUrl = plugins_url(sprintf("../images/m%02d.png", $strMarkerID), __FILE__);
												?>
													<div style="float:left;margin:0 10px;">
														<img src="<?php _e($strImageUrl); ?>">
														<br>
														<center>
															<input type="radio" name="marker" value="<?php _e($strMarkerID); ?>" <?php checked($strMarkerID == $arMarkerID[0]); ?>>
														</center>
													</div>
												<?php
											}	
										?>
									</td>
								</tr>
										
								<tr>
									<td><strong><?php _e('Option', KIMS_TEXT_DOMAIN); ?></strong></td>
									<td>
										<input type="checkbox" name="zoom" checked /> <?php _e('Zoom Control', KIMS_TEXT_DOMAIN); ?>
									</td>
								</tr>
							</table>
							
							<p style="text-align:center;">
								<input type="button" id="btCreateShort" class="button-primary kims-submit" value="<?php _e('Create'); ?>" />
							</p>
							
							<p>
								<?php _e('Copy and paste this code to post or page.', KIMS_TEXT_DOMAIN); ?>
							</p>
							<textarea id="short_res" style="width:100%;" rows="7"></textarea>							
						</div>
					</form>
				</div>
			</div>
		
			<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>

			<script type="text/javascript">
								
				jQuery(document).ready(function($) {					
					$.fn.shortcodeObject = function() {
					  var result = "";
					  var extend = function(i, element) {
					    if( result != '' ) result = result + ' ';
				      result = result + element.name + '="' + element.value + '"';
					    }					
					  $.each(this.serializeArray(), extend);
					  return result;
					}
					
					$('#btSubmmit').click(function(){
						var strKey = $('#frmSetting input[name=api_key]').val();						
						if( strKey.length == 32 ){
							$('#frmSetting').submit();
						}
						else{
							alert('<?php _e('App Key is 32 character.', KIMS_TEXT_DOMAIN); ?>');
							$('#frmSetting input[name=api_key]').focus();
						}
				  });
										
					$('#btCreateShort').click(function(){
						var strKey = $('#frmSetting input[name=api_key]').val();
						if( strKey.length == 32 ){
							var strShortParamString = $('#frmShort').shortcodeObject();
							var strResult = '[korea_map ' + strShortParamString + ']';
							$('#short_res').html(strResult);
						}
						else{						
							alert('<?php _e('App Key is 32 character.', KIMS_TEXT_DOMAIN); ?>');
							$('#frmSetting input[name=api_key]').focus();
							return;
						}
				  });
				  	
				  $('#btShowPostcode').click(function(){
					  
					  new daum.Postcode({
					    oncomplete: function(data) {
				        var fullAddr = '';
				        var extraAddr = '';
				
				        if (data.userSelectedType === 'R') {
				          fullAddr = data.roadAddress;
				
				        } else {
				          fullAddr = data.jibunAddress;
				        }
				
				        $('#frmShort input[name=address]').val(fullAddr);					
					    }
						}).open();        
					});
				});
				
			</script>
		<?php
	}
	
?>