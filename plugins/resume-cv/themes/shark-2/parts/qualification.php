<!-- == qualification == -->
<?php 
$qualification_options = get_option( 'resumecv_qualification_options');
if ( resumecv_data($qualification_options,'show') == 'enable') {
	$qualification_items = resumecv_data($qualification_options,'qualification_items'); 
	resumecv_output('<h2 class="sidebar-right__h"><span>',resumecv_data($qualification_options,'title'),'</span></h2>');
	if ($qualification_items) {
		$i = 0;
		foreach ($qualification_items as $item) {
		$i++;
		if ($i%3==1) echo '<div class="row">';
?>
			<div class="col-md-4">
				<div class="sidebar-right__content">
					<?php resumecv_output('<h3 class="sidebar__h">',$item['title'],'</h3>'); ?>
					<div class="rcv-qualification">
						<ul class="rcv-qualification__list">
							<?php 
								$values = resumecv_data($item,'value');
								foreach ($values as $value) {
									resumecv_output('<li>',$value,'</li>');
								}
							?>
						</ul>
						<div class="clear-fix"></div>
					</div>
				</div>
			</div>
		<?php	if ($i%3==0) echo '</div>'; ?>
		<?php	} ?>
		<?php	if ($i%3!=0) echo '</div>'; ?>
		
	<?php	} ?>
<?php } ?>
<!-- qualification -->
