<div class="<?php echo $main_class; ?>">
	<div class="pp-heading-wrapper">
		<div class="pp-header-wrapper-inner">
			<?php $module->render_title_prefix(); ?>
			<?php $module->render_title(); ?>
		</div>
		<?php include $module->dir . 'includes/icon-layout.php'; ?>
	</div>
	<div class="pp-infobox-description">
		<div class="pp-description-wrap">
			<?php echo $settings->description; ?>
		</div>
		<?php $module->render_link(); ?>
	</div>
</div>