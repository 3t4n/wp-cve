<?php
use CTXFeed\V5\Common\DropDownOptions;
$options         = DropDownOptions::get_options('',true);
?>
<div class="wrap">
	<h2><?php esc_html_e( 'Add Option', 'woo-feed' ); ?></h2>

	<form action="" name="feed" method="post" autocomplete="off">
		<?php wp_nonce_field( 'woo-feed-add-option' ); ?>
		<table class="widefat" style="max-width: 440px;margin: 20px auto;padding: 20px;">
			<tbody>
			<tr><td colspan="2"></td></tr>
			<tr>
				<td style="width: 130px;max-width: 130px;"><label for="wpfp_option"><b><?php esc_html_e( 'Option Name', 'woo-feed' ); ?> <span class="requiredIn">*</span></b></label></td>
				<td>
					<select name="wpfp_option" id="wpfp_option" class="selectize generalInput" style="width: 100%;" placeholder="<?php esc_attr_e( 'Search Option Name', 'woo-feed' ); ?>" required><?php echo $options; ?></select>
				</td>
			</tr>
			<tr>
				<td colspan="2" style="text-align: center">
					<button type="submit" class="button button-primary woo-feed-btn-bg-gradient-blue"><?php esc_html_e( 'Add Option', 'woo-feed' ); ?></button>
				</td>
			</tr>
			<tr><td colspan="2"></td></tr>
			</tbody>
		</table>
	</form>
</div>
