<div class="njba-pricing-table-wrapper-flude">
    <div class="wrapper">
        <div class="njba-pricing-table-main layout-<?php echo $settings->price_box_layout; ?>">
			<?php
			$layout            = $settings->price_box_layout;
			$total_box_content = count( $settings->price_box_content );
			//echo $total_box_content;
			switch ( $layout ) {
				case '1':
					include( 'layout_1.php' );
					break;
				case '2':
					include( 'layout_2.php' );
					break;
				case '3':
					include( 'layout_3.php' );
					break;
				case '4':
					include( 'layout_4.php' );
					break;
				case '5':
					include( 'layout_5.php' );
					break;
			}
			?>
        </div>
    </div>
</div>
