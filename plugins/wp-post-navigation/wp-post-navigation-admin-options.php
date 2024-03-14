<style type="text/css">
a.company{
	text-decoration: none;
	font: 600 16px sens-sarif, arial, verdana;
	color: #ff2f2f;
}
select{
	padding: 3px;
	min-width: 70px;
}
input[type="text"]{
	width: 220px;
}
input,
textarea{
	outline: none;
}
.wp-social-box{
	float: left;
	width: 550px;
	background-color: whiteSmoke;
	background-image: -ms-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -moz-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -o-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: -webkit-gradient(linear,left top,left bottom,from(#F9F9F9),to(whiteSmoke));
	background-image: -webkit-linear-gradient(top,#F9F9F9,whiteSmoke);
	background-image: linear-gradient(top,#F9F9F9,whiteSmoke);
	border-color: #DFDFDF;
	-moz-box-shadow: inset 0 1px 0 #fff;
	-webkit-box-shadow: inset 0 1px 0 white;
	box-shadow: inset 0 1px 0 white;
	-webkit-border-radius: 3px;
	webkit-border-radius: 3px;
	border-radius: 3px;
	border-width: 1px;
	border-style: solid;
	position: relative;
	margin-bottom: 20px;
	padding: 0;
	border-width: 1px;
	border-style: solid;
	line-height: 1;
	margin-left: 10px;
}
.wp-social-box h3 {
	font-size: 15px;
	font-weight: normal;
	padding: 7px 10px;
	margin: 0;
	line-height: 1;
	font-family: Georgia,"Times New Roman","Bitstream Charter",Times,serif;
	cursor: move;
	-webkit-border-top-left-radius: 3px;
	-webkit-border-top-right-radius: 3px;
	border-top-left-radius: 3px;
	border-top-right-radius: 3px;
	color: #464646;
	border-bottom-color: #DFDFDF;
	text-shadow: white 0 1px 0;
	-moz-box-shadow: 0 1px 0 #fff;
	-webkit-box-shadow: 0 1px 0 white;
	box-shadow: 0 1px 0 white;
	background-color: #F1F1F1;
	background-image: -ms-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -moz-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -o-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: -webkit-gradient(linear,left top,left bottom,from(#F9F9F9),to(#ECECEC));
	background-image: -webkit-linear-gradient(top,#F9F9F9,#ECECEC);
	background-image: linear-gradient(top,#F9F9F9,#ECECEC);
	margin-top: 1px;
	border-bottom-width: 1px;
	border-bottom-style: solid;
	cursor: move;
	-webkit-user-select: none;
	-moz-user-select: none;
	user-select: none;
}
input[type="submit"]{
	cursor: pointer;
}
</style>
<script>
 jQuery(function($){
	
 });
</script>
<div class="wrap" style="margin-top: 30px;margin-left: 10px;max-width:800px !important;">
<div style="width: 570px;float:left;">
<form action="<?php echo $action_url ?>" method="post">
		<input type="hidden" name="submitted" value="1" />
		<?php wp_nonce_field('wp-post-navigation-by-sharp-coders'); ?>
	
<div class="wp-social-box">
	<h3>Configuration</h3>
		<table class="form-table">
			<tbody>
			<tr valign="top">
					<th scope="row" style="width: 100px;">Active</th>
					<td>
						<fieldset>
							<legend class="hidden">Active</legend>
							<label for="is_active"><input type="checkbox" name="is_active" value="1" <?php echo $settings['is_active']=="1"? 'checked="checked"': '' ; ?>  /> (auto place) </label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Position</th>
					<td>
						<fieldset>
							<legend class="hidden">Position</legend>
							<label for="position">
								<select name="position" id="position">
									<option value="bottom" <?php echo $settings['position']=="bottom"? 'selected="selected"': '' ; ?>>Bottom</option>
									<option value="top" <?php echo $settings['position']=="top"? 'selected="selected"': '' ; ?>>Top</option>
									<option value="both" <?php echo $settings['position']=="both"? 'selected="selected"': '' ; ?>>Top &amp; Bottom</option>
								</select>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Navigate Within Category</th>
					<td>
						<fieldset>
							<legend class="hidden">Navigate Within Category</legend>
							<label for="nav_within_cat"><input type="checkbox" name="nav_within_cat" value="1" <?php echo $settings['nav_within_cat']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Reverse Navigation</th>
					<td>
						<fieldset>
							<legend class="hidden">Reverse Navigation</legend>
							<label for="is_reversed"><input type="checkbox" name="is_reversed" value="1" <?php echo $settings['is_reversed']=="1"? 'checked="checked"': '' ; ?>  /> (Reverse Next/Previous Position) </label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">CSS Code for Links</th>
					<td>
						<fieldset>
							<legend class="hidden">CSS Code</legend>
							<label for="style_css"><textarea id="style_css" name="style_css" placeholder="text-decoration: none;" style="width: 400px;height: 140px;"><?php echo $settings['style']!=""? stripslashes($settings['style']): ''; ?></textarea></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Custom Text</th>
					<td>
						<fieldset>
							<legend class="hidden">Custom Text</legend>
							<label for="is_custom"><input type="checkbox" name="is_custom" value="1" <?php echo $settings['is_custom']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Previous Post Text</th>
					<td>
						<fieldset>
							<label for="custom_pre"><input type="text" name="custom_pre"  value="<?php echo $settings['custom_pre']!=""? stripslashes($settings['custom_pre']): ''; ?>"  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Next Post Text</th>
					<td>
						<fieldset>
							<label for="custom_next"><input type="text" name="custom_next"  value="<?php echo $settings['custom_next']!=""? stripslashes($settings['custom_next']): ''; ?>"  /></label>
						</fieldset>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row" style="width: 100px;">Use Images</th>
					<td>
						<fieldset>
							<legend class="hidden">Use Images</legend>
							<label for="navi_img"><input type="checkbox" name="navi_img" value="1" <?php echo $settings['navi_img']=="1"? 'checked="checked"': '' ; ?>  /></label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Previous Post Image</th>
					<td>
						<fieldset>
							<legend class="hidden">Previous Post Image</legend>
							<label for="pre_img_link"><input type="text" name="pre_img_link"  value="<?php echo $settings['pre_img_link']!=""? stripslashes($settings['pre_img_link']): ''; ?>"  /></label>
							<br /><span style="color: #999;">i.e. http://www.ApnaGoogle.com/previous.png</span>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row" style="width: 100px;">Next Post Image</th>
					<td>
						<fieldset>
							<legend class="hidden">Next Post Image</legend>
							<label for="next_img_link"><input type="text" name="next_img_link"  value="<?php echo $settings['next_img_link']!=""? stripslashes($settings['next_img_link']): ''; ?>"  /></label>
							<br /><span style="color: #999;">i.e. http://www.ApnaGoogle.com/next.png</span>
						</fieldset>
					</td>
				</tr>
			</tbody>
		</table>
	</div><!-- End Block -->

	
	
	<div class="submit" style="float: left; display: block; width: 100%;"><input type="submit" name="Submit" value=" Update " style="min-width: 100px;min-height: 30px;font-size: 14px;" /></div>
		</form>
	<div class="submit" style="float: left; display: block; width: 100%;">
		
		
	</div>
</div>
<div style="float:right;">
<!-- Start Plugin Information -->
	<div class="wp-social-box" style="width: 200px;">
	<h3>Plugin Information</h3>
		<table class="form-table">
			<tbody>
				<tr valign="top">
					<td>
						This Plugin is Developed By <a href="http://sharp-coders.com" target="_blank" class="company">Sharp Coders</a>.<br />
						Visit <a href="http://sharp-coders.com/category/plugins/wp-plugins/" target="_blank">sharp-coders.com</a> for more plugins.<br />
						<strong>Author: </strong> <a href="https://plus.google.com/104763153154719100069" target="_blank">Anas Mir</a>
						<br />
						
<br /><br />
						<strong>Support: </strong> <a href="http://sharp-coders.com/wp-post-navigation/" target="_blank">Open Support Page</a>
						<br /><br />
						<iframe src="//www.facebook.com/plugins/like.php?href=http%3A%2F%2Fwww.facebook.com%2Fsharpcoders&amp;send=false&amp;layout=standard&amp;width=180&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=35" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:180px; height:35px;" allowTransparency="true"></iframe>
						<br />
						<strong>Twitter: </strong>&nbsp;&nbsp;<a href="http://twitter.com/sharpcoderz" target="_blank">@SharpCoderz</a>
						
						<hr style="max-width: 180px;" />
						<strong>Other Plugins</strong><br />
						<a href="http://sharp-coders.com/wp-social-share/" target="_blank">WP Social Share</a>
						<br />
						<a href="http://sharp-coders.com/wp-subscriber-form/" target="_blank">WP Subscriber Form</a>
						<br />
						<a href="http://sharp-coders.com/wp-ads-within-contents-wordpress-plugin/" target="_blank">WP Ads Within Contents</a>
						<hr style="max-width: 180px;" />
						<strong>Make Extra Money</strong><br />
						<br />
						<a href="https://www.seoclerks.com/PHP/184793/SEOClerks-Affiliate-Store-Responsive" style="font-size: 18px;font-weight:bold;" target="_blank">Auto Money Making Script</a>
						<br /><br />
					</td>
				</tr>
				
			</tbody>
		</table>
	</div>
	<!-- End Information -->
</div>
</div>

