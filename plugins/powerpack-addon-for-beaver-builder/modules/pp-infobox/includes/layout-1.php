<div class="<?php echo $main_class; ?>">
	<div class="pp-heading-wrapper">
		<?php include $module->dir . 'includes/icon-layout.php'; ?>

		<div class="pp-heading-wrapper-inner">
			<?php $module->render_title_prefix(); ?>
			<?php $module->render_title(); ?>
		</div>
	</div>
	<div class="pp-infobox-description">
		<div class="pp-description-wrap">
			<?php echo $settings->description; ?>
		</div>
		<?php $module->render_link(); ?>
	</div>
</div>