<!-- skillbar -->
<?php 
$skillbar_options = get_option( 'resumecv_skillbar_options');
if ( resumecv_data($skillbar_options,'show') == 'enable') {
	$skillbar_items = resumecv_data($skillbar_options,'skillbar_items'); ?>
	<?php if ($skillbar_items) { 
			$i = 0;
			resumecv_output('<h2 class="sidebar-right__h"><span>','Skills','</span></h2>');
	?>
		
		<?php foreach ($skillbar_items as $item) { ?>
		<?php 
			$i++;
			echo '<div class="row">';
		?>
			<div class="col-md-12">
				<div class="sidebar__content">
					<?php resumecv_output('<h3 class="sidebar__h">',$item['title'],'</h3>'); ?>
					<?php $skillbar = resumecv_data($item,'skillbar'); ?>
					<?php if ($skillbar) { ?>
						<?php foreach ($skillbar as $myitem) { ?>
							<div class="rcv-skillbar">
								<div class="rcv-skillbar__content">
									
								</div>
								<div class="rcv-skillbar__item" style="width:<?php echo esc_attr($myitem['skill-value']); ?>%">
									<?php resumecv_output('<span class="rcv-skillbar__name">',$myitem['skill-name'],'</span> - '); ?>
									<?php resumecv_output('<span class="rcv-skillbar__value">',$myitem['skill-value'],'%</span> '); ?>
								</div>
							</div>
						<?php } ?>
					<?php } ?>
				</div>
			</div>
		<?php
			 echo '</div>';
		?>
		<?php } ?>
	<?php } ?>
<?php } ?>
<!-- skillbar -->
