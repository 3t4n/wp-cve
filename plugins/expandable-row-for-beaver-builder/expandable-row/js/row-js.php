<?php if ( $row->settings->is_enable == 'yes' ): ?>
(function($) {

	<?php if( ! FLBuilderModel::is_builder_active()): ?>

	// generating html structure
	var html = '<div class="bb-er-row">';
	<?php if( $row->settings->er_icon_position == 'top' ): ?>
		<?php if( $row->settings->er_img_type == 'icon' ): ?>
			html += '<div><i class="bber-icon <?php echo $row->settings->er_bc_icon; ?>"></i></div>';
		<?php elseif( $row->settings->er_img_type == 'image' ): ?>
	 		html += '<div class="bber-image"><img src="<?php echo $row->settings->er_bc_image_src; ?>" /></div>';
	 <?php endif ?>
	<?php endif ?>
	 html += '<div class="bb-er-title-section">';
	<?php if( $row->settings->er_icon_position == 'left' ): ?>
	 	<?php if( $row->settings->er_img_type == 'icon' ): ?>
	 		html += '<span><i class="bber-icon <?php echo $row->settings->er_bc_icon; ?>"></i></span>';
	 	<?php elseif( $row->settings->er_img_type == 'image' ): ?>
	 		html += '<span class="bber-image"><img src="<?php echo $row->settings->er_bc_image_src; ?>" /></span>';
	 <?php endif ?>
	<?php endif ?>
	 html += '<span class="bb-er-title"><?php echo htmlspecialchars($row->settings->er_bc_title); ?></span>';
	<?php if( $row->settings->er_icon_position == 'right' ): ?>
	 	<?php if( $row->settings->er_img_type == 'icon' ): ?>
	 		html += '<span><i class="bber-icon <?php echo $row->settings->er_bc_icon; ?>"></i></span>';
	 	<?php elseif( $row->settings->er_img_type == 'image' ): ?>
	 		html += '<span class="bber-image"><img src="<?php echo $row->settings->er_bc_image_src; ?>" /></span>';
	 <?php endif ?>
	<?php endif ?>
	 html += '</div>';
	<?php if( $row->settings->er_icon_position == 'bottom' ): ?>
	 	<?php if( $row->settings->er_img_type == 'icon' ): ?>
	 		html += '<div><i class="bber-icon <?php echo $row->settings->er_bc_icon; ?>"></i></div>';
	 	<?php elseif( $row->settings->er_img_type == 'image' ): ?>
	 		html += '<div class="bber-image"><img src="<?php echo $row->settings->er_bc_image_src; ?>" /></div>';
	 <?php endif ?>
	<?php endif ?>
	 html += '</div>';

	// appending html structure on respective row
	$('.fl-row.fl-node-<?php echo $row->node; ?>').prepend(html);
	<?php endif ?>

	// after click on row
	$('.fl-node-<?php echo $row->node; ?> .bb-er-row').click(function() {

		// appear effect
		<?php if($row->settings->er_effect == 'slide'): ?>
			$('.fl-node-<?php echo $row->node; ?> .fl-row-content-wrap').slideToggle();
		<?php elseif ($row->settings->er_effect == 'fade'): ?>
			$('.fl-node-<?php echo $row->node; ?> .fl-row-content-wrap').fadeToggle();
		<?php endif ?>

		// toggle class
		$('.fl-node-<?php echo $row->node; ?> .bb-er-row').toggleClass("bber-expanded");
		
		// toggle title
		( $('.fl-node-<?php echo $row->node; ?>  .bb-er-title').text() == '<?php echo htmlspecialchars($row->settings->er_bc_title); ?>' ) ? $('.fl-node-<?php echo $row->node; ?>  .bb-er-title').text('<?php echo htmlspecialchars($row->settings->er_ac_title); ?>') : $('.fl-node-<?php echo $row->node; ?>  .bb-er-title').text('<?php echo htmlspecialchars($row->settings->er_bc_title); ?>');

		// toggle icon
		<?php if( $row->settings->er_img_type == 'icon' ): ?>
			$('.fl-node-<?php echo $row->node; ?> .bber-icon').toggleClass("<?php echo $row->settings->er_bc_icon; ?> <?php echo $row->settings->er_ac_icon; ?>");
		<?php endif ?>

		// toggle background
		<?php if ($row->settings->er_bg_type == 'image' ): ?>
			var bg_toggle_src = ( $('.fl-node-<?php echo $row->node; ?> .bb-er-row').css('background-image') == 'url("<?php echo isset($row->settings->er_bc_bg_image_src)? $row->settings->er_bc_bg_image_src : null ; ?>")' ? 'url("<?php echo isset($row->settings->er_ac_bg_image_src)? $row->settings->er_ac_bg_image_src : null ; ?>")' : 'url("<?php echo isset($row->settings->er_bc_bg_image_src)? $row->settings->er_bc_bg_image_src : null ; ?>")' );
			$('.fl-node-<?php echo $row->node; ?> .bb-er-row').css({'background-image' : bg_toggle_src });
		<?php endif ?>

		// toggle image icon
		<?php if( $row->settings->er_img_type == 'image' ): ?>
			var img_toggle_src = ( $('.fl-node-<?php echo $row->node; ?> .bber-image img').attr('src') == '<?php echo isset($row->settings->er_bc_image_src)? $row->settings->er_bc_image_src : null ; ?>' ? '<?php echo isset($row->settings->er_ac_image_src)? $row->settings->er_ac_image_src : null ; ?>' : '<?php echo isset($row->settings->er_bc_image_src)? $row->settings->er_bc_image_src : null ; ?>' );
			$('.fl-node-<?php echo $row->node; ?> .bber-image img').attr('src', img_toggle_src)
		<?php endif ?>

	});

})(jQuery);
<?php endif?>