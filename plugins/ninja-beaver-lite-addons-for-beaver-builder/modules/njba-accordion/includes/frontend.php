<div class="njba-accordion <?php if ( $settings->collapse ) {
	echo ' njba-accordion-collapse';
} ?>">
	<?php for ( $i = 0, $iMax = count( $settings->items ); $i < $iMax; $i ++ ) { ?>
        <div id="njba-accord-<?php echo $id; ?>-<?php echo $i; ?>" class="njba-accordion-item">
            <div class="njba-accordion-button">
			<span class="njba-accordion-button-label">
				<?php if ( $settings->items[ $i ]->accordion_font_icon  !==  '') { ?>
                    <i class="njba-accordion-icon <?php echo $settings->items[ $i ]->accordion_font_icon; ?>"></i>
				<?php } ?>
				<?php if ( isset( $settings->items[ $i ]->label ) ) { ?>
                    <div class="njba-accordion-button-label-selector"> <?php echo $settings->items[ $i ]->label; ?> </div>
				<?php } ?>
			</span>
				<?php if ( $settings->accordion_open_icon !== '' ) { ?>
                    <span class="njba-accordion-button-icon njba-accordion-open <?php echo $settings->accordion_open_icon; ?>"></span>
				<?php } else { ?>
                    <i class="njba-accordion-button-icon njba-accordion-open custom-text-plus">+</i>
				<?php } ?>
				<?php if ( $settings->accordion_close_icon !== '' ) { ?>
                    <span class="njba-accordion-button-icon njba-accordion-close <?php echo $settings->accordion_close_icon; ?>"></span>
				<?php } else { ?>
                    <i class="njba-accordion-button-icon njba-accordion-close custom-text-minus">-</i>
				<?php } ?>
            </div>
            <div class="njba-accordion-content fl-clearfix">
				<?php //echo $settings->items[ $i ]->content; ?>
				<?php echo $module->njba_get_item_content( $settings->items[ $i ] ); ?>
            </div>
        </div>
	<?php } ?>
</div>
