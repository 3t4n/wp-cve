<?php
$params = ReadMoreData::params();
?>
<div class="panel panel-default">
	<div class="panel-heading"><?php _e('Advanced options', YRM_LANG);?></div>
	<div class="panel-body yrm-pro-options-wrapper">
		<?php echo ReadMoreAdminHelper::upgradeContent(); ?>
		<!-- Content start -->
		<?php require_once(dirname(__FILE__)."/subsections/advancedContent.php"); ?>
		<!-- Content end -->
		<!-- Tab Start -->
		<?php require_once(dirname(__FILE__)."/subsections/advancedTab.php"); ?>
	</div>
</div>