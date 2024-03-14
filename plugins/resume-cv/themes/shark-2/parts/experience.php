<!-- experience -->
<?php 
$experience_options = get_option( 'resumecv_experience_options');
if ( resumecv_data($experience_options,'show') == 'enable') {
	$experience_items = resumecv_data($experience_options,'experience_items'); 

?>
	<div class="sidebar-right__content">
		<?php
			resumecv_output('<h3 class="sidebar-right__h"><span>',resumecv_data($experience_options,'title'),'</span></h3>');
		?>
		<div class="timeline">
			<?php
				if ($experience_items) {
					foreach ($experience_items as $item) {
			?>
			
			<div class="rcv-experience">	
				<div class="rcv-education-time">					
					<div class="rcv-education-time__content">					
						<?php resumecv_output('<span class="rcv-education__year">',resumecv_data($item,'start_year'),'</span>'); ?>
						<?php resumecv_output('<span class="rcv-education__year">','-','</span>'); ?>
						<?php resumecv_output('<span class="rcv-education__year">',resumecv_data($item,'end_year'),'</span>'); ?>
					</div>
				</div>
				<div class="rcv-education__content">
					<?php resumecv_output('<h3 class="rcv-education__program">',resumecv_data($item,'position'),'</h3>'); ?>
					<?php resumecv_output('<div class="rcv-education__school">',resumecv_data($item,'company_name'),'</div>'); ?>
					<?php resumecv_output('<div class="rcv-education__school">',resumecv_data($item,'company_address'),'</div>'); ?>
					
					<?php resumecv_output('<div class="rcv-education__description">',resumecv_data($item,'position_description'),'</div>'); ?>
					<?php 
						$accomplishment_list = resumecv_data($item,'accomplishment_list');
						if ($accomplishment_list) {
							resumecv_output('',resumecv_data($item,'accomplishment_title'),'');
							echo '<ul>';
							foreach ($accomplishment_list as $accomplishment_item) {
								echo '<li>'.$accomplishment_item .'</li>';
							}
							echo '</ul>';
						}
					?>
				</div>
			</div>
			<?php
					}
				}
			?>
			
		</div>
	</div>
<?php } ?>
<!-- experience -->
