
		<div class="wpt-image-card-wrapper">
			<?php if ($image): ?>
			<!-- image wrapper -->
			<div class="wpt-image-card-image-wrapper">
				<?php echo et_core_intentionally_unescaped($image, 'html') ?>
			</div>
			<?php endif?>
<?php if ('' !== $title || '' !== $content || '' !== $button): ?>
			<!-- content wrapper -->
			<div class="wpt-image-card-content-wrapper">
				<div class="wpt-image-card-inner-content-wrapper">
<?php if ($title): ?>
<?php echo et_core_intentionally_unescaped($title, 'html'); ?>
<?php endif?>
<?php if ($content): ?>
<?php echo et_core_intentionally_unescaped($content, 'html'); ?>
<?php endif?>
<?php if ($button): ?>
<?php echo et_core_intentionally_unescaped($button, 'html'); ?>
<?php endif?>

				</div>
			</div>
				<?php endif?>
<?php if ($show_button == 'off' && $open_url == 'on'): ?>
				<a href="<?php echo $card_url; // phpcs:ignore    ?>" target='<?php echo $card_url_new_window == 'on' ? 'blank' : ''; ?>' class='wpt-image-card-overlay'></a>
			<?php endif?>
		</div>

