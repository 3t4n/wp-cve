<div class="njba-separator">
	<?php if ( $settings->icon_position === 'left' && $settings->separator_type !== 'separator_normal' && $settings->separator_type !== 'image_separator' ) : ?>
        <div class="njba-icon njba-icon-separator-left">
			<?php echo $module->njba_icon_module( $settings->separator_type ); ?>
            <div class="njba-separator-line njba-side-left">
                <span></span>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( $settings->icon_position === 'center' && $settings->separator_type !== 'separator_normal' && $settings->separator_type !== 'image_separator' ) : ?>
        <div class="njba-icon njba-icon-separator-center">
            <div class="njba-separator-line njba-side-left">
                <span></span>
            </div>
			<?php echo $module->njba_icon_module( $settings->separator_type ); ?>
            <div class="njba-separator-line njba-side-right">
                <span></span>
            </div>
        </div>
	<?php endif; ?>
	<?php if ( $settings->icon_position === 'right' && $settings->separator_type !== 'separator_normal' && $settings->separator_type !== 'image_separator' ) : ?>
        <div class="njba-icon njba-icon-separator-right">
            <div class="njba-separator-line njba-side-right">
                <span></span>
            </div>
			<?php echo $module->njba_icon_module( $settings->separator_type ); ?>
        </div>
	<?php endif; ?>
	<?php if ( $settings->separator_type === 'separator_normal' ) : ?>
        <div class="njba-icon <?php if ( $settings->icon_position === 'left' ) {
			echo 'njba-icon-separator-left';
		} elseif ( $settings->icon_position === 'center' ) {
			echo 'njba-icon-separator-center';
		} else {
			echo 'njba-icon-separator-right';
		} ?>">
            <div class="njba-separator-line">
                <span></span>
            </div>
        </div>
	<?php endif; ?>
    <?php if ( $settings->separator_type === 'image_separator' ) : ?>
        <div class="njba-icon <?php if ( $settings->icon_position === 'left' ) {
            echo 'njba-icon-separator-left';
        } elseif ( $settings->icon_position === 'center' ) {
            echo 'njba-icon-separator-center';
        } else {
            echo 'njba-icon-separator-right';
        } ?>">
            <?php if ( ! empty( $settings->select_image_separator_src ) ) {
                        $src = $settings->select_image_separator_src;
                    } else {
                        $src = FL_BUILDER_URL . 'img/pixel.png';
                    }
                    ?>
            <div class="njba-separator-image">
                <img src="<?php echo $src; ?>">
            </div>
        </div>
    <?php endif; ?>
</div>
