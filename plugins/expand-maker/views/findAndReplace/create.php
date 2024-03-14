<?php
$id = 0;
if (!empty($_GET['farId'])) {
    $id = (int)$_GET['farId'];
}
?>
<div class="ycf-bootstrap-wrapper">
	<?php if(!empty($_GET['saved'])) : ?>
		<div id="default-message" class="updated notice notice-success is-dismissible">
			<p><?php echo _e('Settings saved.', YRM_LANG);?></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php echo _e('Dismiss this notice.', YRM_LANG);?></span></button>
		</div>
	<?php endif; ?>
    <div class="expm-wrapper">
    <form method="POST" action="<?php echo admin_url();?>admin-post.php?action=yrm_fr_save_data">
		<?php
		if(function_exists('wp_nonce_field')) {
			wp_nonce_field('read_more_save');
		}
		$reportButton = '';
		if(YRM_PKG == YRM_FREE_PKG) {
			$proClassWrapper = 'yrm-pro-option';
			$reportButton = ReadMoreAdminHelper::reportIssueButton();
		}
		$allowedTag = ReadMoreAdminHelper::getAllowedTags();
		?>
        <div class="titles-wrapper">
            <h2 class="expander-page-title">Change settings <?php echo wp_kses($reportButton, $allowedTag); ?></h2>
            <div class="button-wrapper">
                <p class="submit">
                    <?php if(YRM_PKG == YRM_FREE_PKG): ?>
                        <input type="button" class="yrm-upgrade-button-orange yrm-link-button" value="Upgrade to PRO version" onclick="window.open('<?php echo YRM_PRO_URL; ?>');">
                    <?php endif;?>
                    <input type="submit" class="button-primary yrm-button-primary" value="<?php echo 'Save Changes'; ?>">
                </p>
            </div>
        </div>
        <div class="clear"></div>
        <div class="row form-group">
            <div class="col-xs-12">
                <input type="text" name="yrm-find-title" class="form-control input-md" placeholder="Title" value="<?php echo esc_attr($typeObj->getOptionValue('title')); ?>">
            </div>
        </div>
        <input type="hidden" name="yrm-type" value="far">
        <input type="hidden" name="yrm-id" value="<?php echo esc_attr($id); ?>">
        <?php require_once(dirname(__FILE__).'/rules.php'); ?>
        <?php require_once(YRM_VIEWS_GENERAL.'support.php'); ?>
    </form>
    </div>
</div>