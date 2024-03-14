<div class="njba-infobox-two-heading">
    <div class="njba-heading-main">
        <div class="njba-heading">
			<?php echo $module->njbaIconModule( $settings->infobox_two_type ); ?>
            <div class="section-title-details">
				<?php if ( $settings->main_title !== '' ) : ?>
                    <<?php echo $settings->main_title_tag; ?> class="njba-heading-title"><?php echo $settings->main_title; ?></<?php echo $settings->main_title_tag; ?>>
		        <?php endif; ?>

                <?php if ( $settings->sub_title !== '' ) : ?>
                    <div class="njba-heading-sub-title"><?php echo $settings->sub_title; ?></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

