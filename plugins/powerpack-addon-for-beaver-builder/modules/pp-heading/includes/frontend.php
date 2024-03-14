<?php
$is_dual_heading = isset( $settings->dual_heading ) && 'yes' === $settings->dual_heading;
$is_link_enabled = ( isset( $settings->enable_link ) && 'yes' === $settings->enable_link && ! empty( $settings->heading_link ) ) ? true : false;
?>
<div class="pp-heading-content">
	<?php if ( isset( $settings->prefix_text ) && ! empty( $settings->prefix_text ) ) { ?>
		<<?php echo $settings->prefix_tag; ?> class="pp-heading-prefix"><?php echo $settings->prefix_text; ?></<?php echo $settings->prefix_tag; ?>>
	<?php } ?>
	<div class="pp-heading <?php if ( 'inline' == $settings->heading_separator ) { echo 'pp-separator-' . $settings->heading_separator; } ?> pp-<?php echo $settings->heading_alignment; ?><?php echo $is_dual_heading ? ' pp-dual-heading' : ''; ?>">
		<?php if ( 'top' === $settings->heading_separator_postion || 'left' === $settings->heading_separator_postion ) {
			$module->render_separator();
		} ?>

		<<?php echo $settings->heading_tag; ?> class="heading-title<?php echo $module->maybe_text_inline_block() ? ' text-inline-block' : ''; ?>">

			<?php if ( $is_link_enabled ) : ?>
				<a class="pp-heading-link"
					href="<?php echo $settings->heading_link; ?>"
					target="<?php echo $settings->heading_link_target; ?>"
					<?php echo ( isset( $settings->heading_link_nofollow ) && 'on' == $settings->heading_link_nofollow ) ? ' rel="nofollow"' : ''; ?>
					>
			<?php endif; ?>

			<span class="title-text pp-primary-title"><?php echo $settings->heading_title; ?></span>

			<?php if ( $is_dual_heading ) { ?>
				<?php if ( 'block' === $settings->heading_style && 'between' === $settings->heading_separator_postion ) {
					$module->render_separator();
				} ?>
				<span class="title-text pp-secondary-title"><?php echo $settings->heading_title2; ?></span>
			<?php } ?>

			<?php if ( $is_link_enabled ) : ?>
				</a>
			<?php endif; ?>

		</<?php echo $settings->heading_tag; ?>>

		<?php if ( 'middle' === $settings->heading_separator_postion || 'right' === $settings->heading_separator_postion ) {
			// middle == below heading.
			$module->render_separator();
		} ?>

	</div>

	<?php if ( isset( $settings->heading_sub_title ) && ! empty( $settings->heading_sub_title ) ) { ?>
		<div class="pp-sub-heading">
			<?php echo $settings->heading_sub_title; ?>
		</div>
	<?php } ?>

	<?php if ( 'bottom' == $settings->heading_separator_postion ) {
		// bottom == below description.
		$module->render_separator();
	} ?>
</div>
