<!-- Expandable Row Wrapper -->
<div class="bb-er-row">

	<?php if( $rows->settings->er_icon_position == 'top' ): ?>
	 	<?php if( $rows->settings->er_img_type == 'icon' ): ?>
	 		<div>
	 			<i class="bber-icon <?php echo $rows->settings->er_bc_icon; ?>"></i>
	 		</div>
	 	<?php elseif( $rows->settings->er_img_type == 'image' ): ?>
	 		<div class="bber-image">
	 			<img src="<?php echo $rows->settings->er_bc_image_src; ?>" />
	 		</div>
	 	<?php endif ?>
	<?php endif ?>

	<div class="bb-er-title-section">
		<?php if( $rows->settings->er_icon_position == 'left' ): ?>
	 		<?php if( $rows->settings->er_img_type == 'icon' ): ?>
	 			<span>
	 				<i class="bber-icon <?php echo $rows->settings->er_bc_icon; ?>"></i>
	 			</span>
	 		<?php elseif( $rows->settings->er_img_type == 'image' ): ?>
	 			<span class="bber-image">
	 				<img src="<?php echo $rows->settings->er_bc_image_src; ?>" />
	 			</span>
	 		<?php endif ?>
		<?php endif ?>

		<span class="bb-er-title">
			<?php echo htmlspecialchars($rows->settings->er_bc_title); ?>
		</span>

		<?php if( $rows->settings->er_icon_position == 'right' ): ?>
	 		<?php if( $rows->settings->er_img_type == 'icon' ): ?>
	 			<span>
	 				<i class="bber-icon <?php echo $rows->settings->er_bc_icon; ?>"></i>
	 			</span>
	 		<?php elseif( $rows->settings->er_img_type == 'image' ): ?>
	 			<span class="bber-image">
	 				<img src="<?php echo $rows->settings->er_bc_image_src; ?>" />
	 			</span>
	 		<?php endif ?>
		<?php endif ?>
	</div>

	<?php if( $rows->settings->er_icon_position == 'bottom' ): ?>
	 	<?php if( $rows->settings->er_img_type == 'icon' ): ?>
	 		<div>
	 			<i class="bber-icon <?php echo $rows->settings->er_bc_icon; ?>"></i>
	 		</div>
	 	<?php elseif( $rows->settings->er_img_type == 'image' ): ?>
	 		<div class="bber-image">
	 			<img src="<?php echo $rows->settings->er_bc_image_src; ?>" />
	 		</div>
	 	<?php endif ?>
	<?php endif ?>

</div>
<!-- Expandable Row Wrapper End -->