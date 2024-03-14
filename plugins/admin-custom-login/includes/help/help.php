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
								<?php esc_html_e('Help And Support', WEBLIZAR_ACL)?>
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
						<a href="<?php echo esc_url('https://wordpress.org/support/plugin/admin-custom-login'); ?>" target="_new" type="button" class="btn btn-info btn-lg" style="color:#fff"><?php esc_html_e('View Support Docs or Open a Ticket','')?></a>
					</td>
				</tr>
			</table>
		</div>
	</div>

   <div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Share Us Your Suggestion', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>						
						<h4><?php esc_html_e('If you have any suggestions or features in mind please share your thoughts with us. We will try our best to add them to this plugin.', WEBLIZAR_ACL)?>  </h4>

					</td>
				</tr>
			</table>
		</div>
	</div>

	<div class="panel panel-primary panel-default content-panel">
		<div class="panel-body">
			<table class="form-table">
				<tr>
					<th scope="row" ><?php esc_html_e('Language Contribution', WEBLIZAR_ACL)?></th>
					<td></td>
				</tr>
				<tr class="radio-span">
					<td>
						<h4><?php esc_html_e('Translate this plugin into your language', WEBLIZAR_ACL)?> </h4>
						<h4><?php esc_html_e('Question : How to convert Plugin into My Language?', WEBLIZAR_ACL)?> </h4>
						<a href="https://translate.wordpress.org/projects/wp-plugins/admin-custom-login/" target="_new" type="button" class="btn btn-info btn-lg" style="color:#fff"><?php esc_html_e('Here is solution','')?></a>
					</td>
				</tr>
			</table>
		</div>
	</div>		
</div>

<!-- /row -->