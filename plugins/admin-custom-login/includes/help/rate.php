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
								<?php esc_html_e('Rate & Donate  Us', WEBLIZAR_ACL)?>
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
				
				<tr class="radio-span">
					<td>
					<h3><?php esc_html_e('We need your feedback for improve our plugin functionality on WordPress. So, if you like our plugin then please rate us', WEBLIZAR_ACL)?></h3>	
					</td>
				</tr>
			</table>
		</div>
	</div>
	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Rate Us', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td class="colcent">
						
						<a class="acl-rate-us" href="http://wordpress.org/support/view/plugin-reviews/admin-custom-login" target="_blank">
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
							<span class="dashicons dashicons-star-filled"></span>
						</a>
					</td>
				</tr>
			</table>
		</div>
	</div>	

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Donate To Us', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td class="colcent">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
							<input type="hidden" name="cmd" value="_s-xclick">
							<input type="hidden" name="hosted_button_id" value="9MXDU3NKPCR5Y">
							<input type="image" src="https://www.paypalobjects.com/en_GB/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal – The safer, easier way to pay online!">
							<img alt="paypal" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1">
						</form>
					</td>
					
				</tr>
			</table>
		</div>
	</div>
</div>
<!-- /row -->

