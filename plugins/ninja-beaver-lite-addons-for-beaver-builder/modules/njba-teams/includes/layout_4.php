<?php
for ( $i = 0;
$i < $number_teams;
$i ++ ) :
$teams = $settings->teams[ $i ];
if ( $settings->teams_layout_view === 'box' )
{
?>
<div class="njba-column-<?php echo $settings->show_col; ?>">
	<?php
	}
	else if ( $settings->teams_layout_view === 'slider' )
	{
	?>
    <div class="njba-slide-<?php echo $i; ?>">
		<?php
		}
		?>
        <div class="njba-team-section ">
            <div class="njba-team-img">
				<?php $module->njba_image_render( $i ); ?>
                <div class="njba-read-more-link">
                    <div class="open-text-vertical">
                        <span class="open-text-vertical-area">
                             <h6><?php echo $teams->member_description; ?></h6>
                             <?php $module->njba_button_render( $i ); ?>
                         </span>
                    </div>
                </div>
                <div class="njba-overlay"></div>
            </div>
            <div class="njba-team-content">
				<?php $module->njba_short_bio( $i ); ?>
                <div class="njba-team-social">
                    <div class="njba-team-social-aminate">
						<?php $module->njba_social_media( $i ); ?>
                    </div>
                </div>
            </div><!--njba-team-content-->
        </div><!--njba-team-section-->
    </div>
	<?php
	endfor;
	?>
				
