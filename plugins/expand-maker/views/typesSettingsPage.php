<?php
$type = @esc_attr($_GET['yrm_type']);

if(!isset($type)) {
	$type = 'button';
}
$proClassWrapper = '';
if(YRM_PKG == YRM_FREE_PKG) {
	$proClassWrapper = 'yrm-pro-option';
}
?>
<?php if(!empty($_GET['saved'])) : ?>
	<div id="default-message" class="updated notice notice-success is-dismissible">
		<p><?php echo _e('Read more updated.', YRM_LANG);?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo _e('Dismiss this notice.', YRM_LANG);?></span></button>
	</div>
<?php endif; ?>
<div class="ycf-bootstrap-wrapper">
	<form method="POST" action="<?php echo admin_url();?>admin-post.php?action=save_new_data">
		<?php
			if(function_exists('wp_nonce_field')) {
				wp_nonce_field('read_more_types_save');
			}
		?>
		<div class="expm-wrapper">
			<div class="titles-wrapper">
				<h2 class="expander-page-title"><?php _e('Change settings', YRM_LANG); ?></h2>
				<div class="button-wrapper">
					<p class="submit">
						<?php if(YRM_PKG == YRM_FREE_PKG): ?>
							<input type="button" class="yrm-upgrade-button-orange yrm-link-button" value="Upgrade to PRO version" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">
						<?php endif;?>
						<input type="submit" class="button-primary" value="<?php _e('Save Changes', YRM_LANG); ?>">
					</p>
				</div>
			</div>
			<div class="clear"></div>
			<div class="row">
				<div class="col-xs-12">
					<input type="text" name="yrm-title" class="form-control input-md" placeholder="Read more title" value="<?php echo esc_attr($this->getTitle()); ?>">
				</div>
			</div>
			<?php foreach($allViews as $view): ?>
				<?php if(file_exists($view)): ?>
					<?php require_once($view); ?>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</form>
</div>