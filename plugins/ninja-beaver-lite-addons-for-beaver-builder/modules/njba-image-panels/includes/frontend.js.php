(function ($) {
    function njba_render_panels() {
        if ($(window).width() > 768) {
			<?php
			$number_panels = count( $settings->image_panels );
			for( $i = 0; $i < $number_panels; $i ++ ) {
			$panel = $settings->image_panels[ $i ];
			if ( ! is_object( $panel ) ) {
				continue;
			}
			if($panel->link_type === 'none' || $panel->link_type === 'title') { ?>
			<?php if( $number_panels === 2 ) { ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').siblings().css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').on('mouseenter', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').css('width', '70%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').siblings().css('width', '30%');
            });
			<?php } else if( $number_panels > 2 ) { ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').on('mouseenter', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').css('width', '40%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').siblings().css('width', <?php echo 60 / ( $number_panels - 1 ); ?> +'%');
            });
			<?php } ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').on('mouseleave', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-item-<?php echo $i; ?>').siblings().css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
            });
			<?php } else if($panel->link_type === 'panel') { ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').siblings().css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
			<?php if( $number_panels === 2 ) { ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').on('mouseenter', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').css('width', '70%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').siblings().css('width', '30%');
            });
			<?php } else if( $number_panels > 2 ) { ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').on('mouseenter', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').css('width', '40%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').siblings().css('width', <?php echo 60 / ( $number_panels - 1 ); ?> +'%');
            });
			<?php } ?>
            $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').on('mouseleave', function () {
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
                $('.fl-node-<?php echo $id; ?> .njba-image-panels-wrap .njba-panel-link-<?php echo $i; ?>').siblings().css('width', <?php echo 100 / ( $number_panels ); ?> +'%');
            });
			<?php } ?>
			<?php } ?>
        }
    }

    njba_render_panels();
    $(window).resize(function () {
        njba_render_panels();
    });
})(jQuery);
