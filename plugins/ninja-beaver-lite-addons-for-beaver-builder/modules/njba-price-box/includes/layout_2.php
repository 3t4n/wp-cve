<?php
for ( $i = 0; $i < $total_box_content; $i ++ ) :
	$box_content = $settings->price_box_content[ $i ];
	//print_r($box_content);
	if ( $total_box_content == 1 ) {
		$njba_column = 'njba-col-xs-1';
	}
	if ( $total_box_content == 2 ) {
		$njba_column = 'njba-col-xs-1 njba-col-sm-2';
	}
	if ( $total_box_content == 3 ) {
		$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-3';
	}
	if ( $total_box_content == 4 ) {
		$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-4';
	}
	if ( $total_box_content >= 5 ) {
		$njba_column = 'njba-col-xs-1 njba-col-sm-2 njba-col-md-4 njba-col-lg-5';
	}
	?>

    <div class="<?php echo $njba_column; ?> njba-pricing-table <?php if ( $box_content->set_as_featured_box === 'yes' ) {
		echo 'njba-active';
	} ?>">
        <div class="njba-pricing-inner njba-pricing-column-<?php echo $i; ?>">
            <div class="njba-pricing-inner-heading">
				<?php if ( $box_content->set_as_featured_box === 'yes' ) { ?>
                    <div class="njba-label-holder">
						<?php echo $module->njba_icon_module( $box_content ); ?>
                    </div>
				<?php } ?>
				<?php if ( $box_content->title !== '' ) { ?> <h3 class="njba-pricing-inner-heading-title-selector"><?php echo $box_content->title; ?></h3> <?php } ?>
				<?php if ( $box_content->price !== '' ) { ?> <h4 class="njba-pricing-inner-heading-price-selector"><?php echo $box_content->price; ?></h4> <?php } ?>
				<?php if ( $box_content->duration !== '' ) { ?> <span
                        class="duration njba-pricing-inner-heading-duration-selector"><?php echo $box_content->duration; ?></span> <?php } ?>
            </div>
            <div class="njba-pricing-inner-body">
                <ul>
					<?php
					$total_feature = count( $box_content->features );
					for ( $j = 0; $j < $total_feature; $j ++ ) :
						?>
                        <li class="njba-pricing-inner-body-features-selector"><?php echo $box_content->features[ $j ]; ?></li>
					<?php
					endfor;
					?>
                </ul>
				<?php $module->njba_space_bw_btn_pro(); ?>
				<?php if ( $box_content->show_button !== 'no' ) : ?>
					<?php $module->njba_price_box_body_btn( $box_content ); ?>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php
endfor;
?>
