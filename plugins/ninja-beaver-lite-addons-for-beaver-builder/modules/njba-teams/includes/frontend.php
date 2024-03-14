<?php
?>
<div class="njba-teams-main njba-teams-layout-<?php echo $settings->team_layout; ?>">
	<?php /* if ( $settings->heading != '') {?>
			<div class="njba-heading">
		    	<<?php echo $settings->heading_tag_selection; ?>> <?php echo $settings->heading; ?> </<?php echo $settings->heading_tag_selection; ?>>
		        <div class="njba-bottom-line"></div>
		    </div>
	    <?php }*/  ?>
    <div class="njba-teams-body">
        <div class="njba-teams-wrapper">
			<?php
			$layout       = $settings->team_layout;
			$number_teams = count( $settings->teams );
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
        </div><!--njba-teams-wrapper-->

    </div><!--njba-teams-body-->
</div><!--njba-teams-main-->
    
	
	
	
