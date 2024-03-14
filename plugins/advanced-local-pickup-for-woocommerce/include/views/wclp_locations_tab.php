<?php 
$data = wc_local_pickup()->admin->get_data();
$location_id = get_option('location_defualt', min($data)->id);
$location = wc_local_pickup()->admin->get_data_byid($location_id);
?>
<section id="wclp_content3" class="wclp_tab_section">
	<div class="wclp_tab_inner_container">         	
		<div class="wclp_outer_form_table">
			<?php
			if ( 'edit' == $section ) { 
				include 'wclp-edit-location-form.php';
			}
			?>
		</div>
	</div>
</section>
