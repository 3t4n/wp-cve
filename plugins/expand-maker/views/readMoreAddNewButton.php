<?php
$buttonWidth = $savedObj->getOptionValue('button-width');
$buttonHeight = $savedObj->getOptionValue('button-height');
$fontSize = $savedObj->getOptionValue('font-size');
$yrmBtnHoverAnimate = $savedObj->getOptionValue('yrm-btn-hover-animate');
$yrmCursor = $savedObj->getOptionValue('yrm-cursor');
$yrmBtnFontWeight = $savedObj->getOptionValue('yrm-btn-font-weight');
$yrmEasings = $savedObj->getOptionValue('yrm-animate-easings');
$animationDuration = $savedObj->getOptionValue('animation-duration');
$btnBackgroundColor = $savedObj->getOptionValue('btn-background-color');
$btnTextColor = $savedObj->getOptionValue('btn-text-color');
$expanderFontFamily = $savedObj->getOptionValue('expander-font-family');
$btnBorderRadius = $savedObj->getOptionValue('btn-border-radius');
$horizontal = $savedObj->getOptionValue('horizontal');
$vertical = $savedObj->getOptionValue('vertical');
$hiddenContentBgColor = $savedObj->getOptionValue('hidden-content-bg-color');
$hiddenContentBgColor = $savedObj->getOptionValue('hidden-content-bg-color');
$hiddenContentBgColor = $savedObj->getOptionValue('hidden-content-bg-color');
$hiddenContentTextColor = $savedObj->getOptionValue('hidden-content-text-color');
$hiddenContentTextColor = $savedObj->getOptionValue('hidden-content-text-color');
$hiddenContentPadding = $savedObj->getOptionValue('hidden-content-padding');
$showOnlyDevices = $savedObj->getOptionValue('show-only-devices', true);
$selectedDevices = $savedObj->getOptionValue('yrm-selected-devices');
$hoverEffect = $savedObj->getOptionValue('hover-effect', true);
$btnHoverTextColor = $savedObj->getOptionValue('btn-hover-text-color');
$btnHoverBgColor= $savedObj->getOptionValue('btn-hover-bg-color');
$buttonForPost = $savedObj->getOptionValue('button-for-post', true);
$yrmSelectedPost= $savedObj->getOptionValue('yrm-selected-post');
$hideAfterWordCount = $savedObj->getOptionValue('hide-after-word-count');
$readMoreTitle = $savedObj->getOptionValue('expm-title');

$params = $dataObj::params();
$type = @esc_attr($_GET['yrm_type']);
if(!isset($type)) {
	$type = 'button';
}
$reportButton = '';
$proClassWrapper = '';
if(YRM_PKG == YRM_FREE_PKG) {
	$proClassWrapper = 'yrm-pro-option';
	$reportButton = '<a href="'.YRM_SUPPORT_URL.'" target="_blank"><button type="button" id="yrm-report-problem-button" class="yrm-button-red pull-right">
					<i class="glyphicon glyphicon-alert"></i>
					
					Report issue</button></a>';
}
$allowedTag = ReadMoreAdminHelper::getAllowedTags();
?>
<?php if(!empty($_GET['saved'])) : ?>
	<div id="default-message" class="updated notice notice-success is-dismissible">
		<p>Read more updated.</p><button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
	</div>
<?php endif; ?>
<div class="ycf-bootstrap-wrapper">
	<form method="POST" action="<?php echo admin_url();?>admin-post.php?action=save_data">
		<?php
			if(function_exists('wp_nonce_field')) {
				wp_nonce_field('read_more_save');
			}
		?>
		<input type="hidden" name="read-more-type" value="<?php echo esc_attr($type); ?>">
		<input type="hidden" name="read-more-id" value="<?php echo esc_attr($id); ?>">

		<div class="expm-wrapper">
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
					<input type="text" name="expm-title" class="form-control input-md" placeholder="Read more title" value="<?php echo esc_attr($readMoreTitle); ?>">
				</div>
			</div>
            <div class="row form-group yrm-all-options-wrapper">
                <div class="col-md-6 yrm-all-left-options-wrapper">
                    <?php require_once(YRM_VIEWS_SECTIONS.'/generalOptions.php'); ?>
                    <!-- Advanced option -->
                    <?php require_once(YRM_VIEWS_SECTIONS.'/advancedOptions.php'); ?>
                </div>
                <div class="col-md-6 yrm-all-right-options-wrapper">
                    <?php require_once(YRM_VIEWS_SECTIONS.'/livePreview.php'); ?>
                    <?php if(!ReadMore::RemoveOption('advanced-hidden-options')): ?>
                        <?php require_once(YRM_VIEWS_SECTIONS.'/advancedHiddenContentOptions.php'); ?>
                    <?php endif; ?>
                    <?php
                    require_once YRM_VIEWS_SECTIONS.'customFunctionality.php';
                    ?>
                </div>
            </div>
		</div>
	</form>
	<?php echo ReadMoreFunctions::getFooterReviewBlock();?>
</div>
