<!-- awards -->
<?php 
	$service_options = get_option( 'resumecv_service_options');
	if ( resumecv_data($service_options,'show') == 'enable') {
		$service_items = resumecv_data($service_options,'service_items'); 
		
	
	?>
<div class="sidebar-right__content">
	<?php resumecv_output('<h3 class="sidebar-right__h"><span>',resumecv_data($service_options,'title'),'</span></h3>'); ?>
	<?php if (count($service_items)>0) { ?>
		<div class="rcv-service">
			<?php $i = 0; ?>
			<?php foreach ($service_items as $item) { ?>
				<?php 
					$i++;
					if ($i%3==1) {
						echo '<div class="row">';
					}
				?>
					<div class="col-md-4">
						<div class="rcv-service-block">
							<div class="rcv-service__icon">
								<?php resumecv_output('<i class="',resumecv_data($item,'icon'),'"></i>'); ?>
							</div>
							<div class="rcv-service__content">
								<?php resumecv_output('<div class="rcv-service__title">',resumecv_data($item,'title'),'</div>'); ?>
								<?php resumecv_output('<div class="rcv-service__description">',resumecv_data($item,'description'),'</div>'); ?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
				<?php
					if ($i%3==0) {
						echo '</div>';
					}
				?>
			<?php } ?>
			<?php
				if ($i%3!=0) {
					echo '</div>';
				}
			?>
			<div class="clearfix"></div>
		</div>
	<?php } ?>
</div>
<?php
	}
?>
<!-- awards -->
