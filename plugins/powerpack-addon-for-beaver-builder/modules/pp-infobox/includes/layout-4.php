<div class="<?php echo $main_class; ?>">
	<div class="layout-<?php echo $layout; ?>-wrapper">
		<div class="pp-heading-wrapper">
			<?php $module->render_title_prefix(); ?>
			<?php $module->render_title(); ?>

			<div class="pp-infobox-description">
				<div class="pp-description-wrap">
					<?php echo $settings->description; ?>
				</div>
				<?php $module->render_link(); ?>
			</div>
		</div>
		<?php include $module->dir . 'includes/icon-layout.php'; ?>
	</div>
</div>