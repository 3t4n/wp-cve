<?php
$data = wc_local_pickup()->admin->get_data();
$location_id = get_option('location_defualt', min($data)->id);

$location = wc_local_pickup()->admin->get_data_byid($location_id);

$country_code = isset($location) ? $location->store_country : get_option('woocommerce_default_country');

$split_country = explode( ':', $country_code );
$store_country = isset($split_country[0]) ? $split_country[0] : '';
$store_state   = isset($split_country[1]) ? $split_country[1] : '';

$store_days = isset($location) ? unserialize($location->store_days) : array();
$all_days = array(
	'sunday' => esc_html( 'Sunday', 'default' ),
	'monday' => esc_html( 'Monday', 'default'),
	'tuesday' => esc_html( 'Tuesday', 'default' ),
	'wednesday' => esc_html( 'Wednesday', 'default' ),
	'thursday' => esc_html( 'Thursday', 'default' ),
	'friday' => esc_html( 'Friday', 'default' ),
	'saturday' => esc_html( 'Saturday', 'default' ),
);
$w_day = array_slice($all_days, get_option('start_of_week'));
foreach ($all_days as $key=>$val) {
	$w_day[$key] = $val;
}
foreach ($store_days as $key => $val) {
	if ($w_day[$key]) {
		$w_day[$key] = $val;
	}
}
		
$wclp_default_time_format = isset($location->store_time_format) ? $location->store_time_format : '24';
if ('12' == $wclp_default_time_format) {
	foreach ($w_day as $key=>$val) {	
		if (isset($val['wclp_store_hour'])) {
			$last_digit = explode(':', $val['wclp_store_hour']);
			if ('00' == end($last_digit)) {
				$val['wclp_store_hour'] = gmdate('ga', strtotime($val['wclp_store_hour']));
			} else {
				$val['wclp_store_hour'] = gmdate('g:ia', strtotime($val['wclp_store_hour']));
			}
		}
		if (isset($val['wclp_store_hour_end'])) {
			$last_digit = explode(':', $val['wclp_store_hour_end']);
			if ('00' == end($last_digit)) {
				$val['wclp_store_hour_end'] = gmdate('ga', strtotime($val['wclp_store_hour_end']));
			} else {
				$val['wclp_store_hour_end'] = gmdate('g:ia', strtotime($val['wclp_store_hour_end']));
			}
		}
		$w_day[$key] = $val;				
	}	
}		
if (!empty($w_day)) { 	
	$n = 0;
	$new_array = [];
	$previousValue = [];
	
	foreach ($w_day as $day=>$value) {				
		if (isset($value['checked']) && 1 == $value['checked']) {																	
			if ($value != $previousValue) {
				$n++;
			}
			$new_array[$n][$day] = $value;					
			$previousValue = $value;
		} else {
			$n++;
			$new_array[$n][$day] = '';	
			$previousValue = '';
		}							
	}
}
	
$alp = new WC_Local_Pickup_admin();
$Wclp_Pickup_Instruction_Customizer  = new Wclp_Pickup_Instruction_Customizer();

$hide_instruction_heading = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'hide_instruction_heading', '');

$hide_table_header = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'hide_table_header', '');

$location_box_heading = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_heading', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_heading']);

$header_address_text = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'header_address_text', $Wclp_Pickup_Instruction_Customizer->defaults['header_address_text']);

$header_business_text = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'header_business_text', $Wclp_Pickup_Instruction_Customizer->defaults['header_business_text']);

$header_background_color = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'header_background_color', $Wclp_Pickup_Instruction_Customizer->defaults['header_background_color']);

$header_font_size = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'header_font_size', $Wclp_Pickup_Instruction_Customizer->defaults['header_font_size']);

$location_box_font_size = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_font_size', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_font_size']);

$location_box_content_line_height = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_content_line_height', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_content_line_height']);

$location_box_border_size = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_border_size', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_border_size']);

$location_box_font_color = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_font_color', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_font_color']);

$header_font_color = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'header_font_color', $Wclp_Pickup_Instruction_Customizer->defaults['header_font_color']);

$location_box_border_color = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_border_color', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_border_color']);

$location_box_background_color = $alp->get_option_value_from_array('pickup_instruction_display_settings', 'location_box_background_color', $Wclp_Pickup_Instruction_Customizer->defaults['location_box_background_color']);	
?>
<?php if ('yes' != $hide_instruction_heading) { ?>
	<h2 class="local_pickup_email_title"><?php echo esc_html($location_box_heading); ?></h2>
<?php } ?>

<?php if (class_exists('Advanced_local_pickup_PRO')) { ?>
	<span style="color:<?php echo esc_html($location_box_font_color); ?>;font-size: <?php echo esc_html($location_box_font_size); ?>;"><strong><?php esc_html_e('Products:', 'woocommerce'); ?></strong> <?php echo 'Product Name(SKU) x Qty'; ?></span>
<?php } ?>
<div class="wclp_mail_address">
	<div class="wclp_location_box 
		<?php 
		if (!empty($new_array)) { 
			echo 'wclp_location_box1';
		} 
		?>
		">
		<?php if ('yes' != $hide_table_header) { ?>
			<div class="wclp_location_box_heading">
				<?php esc_html_e($header_address_text, 'woocommerce'); ?>
			</div>
		<?php } ?>
		<?php if (class_exists('Advanced_local_pickup_PRO')) { ?>
				<?php do_action('wclp_location_address_display_html', $location, $store_state, $store_country); ?>
		<?php } else { ?>
			<div class="wclp_location_box_content">
				<p class="wclp_pickup_adress_p">
					<?php 
					if (!empty($location->store_name)) {
						echo esc_html($location->store_name);
						echo ', '; 
					} 
					?>
				</p>
				<p class="wclp_pickup_adress_p">
					<?php 
					if (!empty($location->store_address)) {
						echo esc_html($location->store_address);
						if (!empty($location->store_address_2)) {
							echo ', '; 
						}
					} 
					if (!empty($location->store_address_2)) {
						echo esc_html($location->store_address_2);
						echo ', ';
					}
					?>
				</p>
				<p class="wclp_pickup_adress_p">
					<?php
					if (!empty($location->store_city)) {
						echo esc_html($location->store_city);
						if ('' != $store_state) {
							echo ', ';
						}
					}
					if ('' != $store_state) {
						echo esc_html(WC()->countries->get_states( $store_country )[$store_state]);
					} 
					if ($store_country) {
						echo ', ';
					}
					if ($store_country) {
						echo esc_html(WC()->countries->countries[$store_country]);
						if (!empty($location->store_postcode)) {
							echo ', ';
						}
					} 
					if (!empty($location->store_postcode)) {
						echo esc_html($location->store_postcode);
					}
					?>
				</p>
				
				<?php if (!empty($location->store_phone)) { ?>
					<p class="wclp_pickup_adress_p"><?php echo esc_html($location->store_phone); ?></p>
				<?php } ?>
				<?php if (!empty($location->store_instruction)) { ?>
					<p class="wclp_pickup_adress_p"><?php echo esc_html($location->store_instruction); ?></p>
				<?php } ?>
			</div>
		<?php } ?>
	</div>				
<?php 
if (!empty($w_day)) { 	
	if (!empty($new_array)) {	
		$resultEmpty = array_filter(array_map('array_filter', $new_array)); //echo count( $resultEmpty ); 
		?>
	<div class="wclp_location_box 
		<?php 
		if (!empty($new_array)) {
			echo 'wclp_location_box2';
		} 
		?>
		"   
		<?php
		if (0 == count($resultEmpty)) {
			echo 'style="display:none;"';
		} 
		?>
		>
		<?php if ('yes' != $hide_table_header) { ?>
			<div class="wclp_location_box_heading">
				<?php esc_html_e($header_business_text, 'advanced-local-pickup-for-woocommerce'); ?>
			</div>
		<?php } ?>
		<div class="wclp_location_box_content">
			<?php
			foreach ($new_array as $key => $data) {
				if (1 == count($data)) {							
					if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
						reset($data);
						?>
						<p class="wclp_work_hours_p">
							<?php 
							echo esc_html(ucfirst(key($data)), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']);
							do_action('wclp_get_more_work_hours_contents', $data);
							echo '</span>';
							?>
						</p>								
				<?php } } ?>						
				<?php
				if (2 == count($data)) {
					if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
						reset($data);
						$array_key_first = key($data);
						end($data);
						$array_key_last = key($data);
						?>
						<p class="wclp_work_hours_p">
							<?php 
							echo esc_html(ucfirst($array_key_first), 'advanced-local-pickup-for-woocommerce') . '<span> - </span>' . esc_html(ucfirst($array_key_last), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']);
							do_action('wclp_get_more_work_hours_contents', $data);
							echo '</span>';
							?>
						</p>
				<?php } } ?>									
				<?php 
				if (count($data) > 2) { 
					if (isset(reset($data)['wclp_store_hour']) && '' != reset($data)['wclp_store_hour'] && isset(reset($data)['wclp_store_hour_end']) && '' != reset($data)['wclp_store_hour_end']) {
					reset($data);
					$array_key_first = key($data);
					end($data);
					$array_key_last = key($data);		
						?>
						<p class="wclp_work_hours_p">
							<?php 
							echo esc_html(ucfirst($array_key_first), 'advanced-local-pickup-for-woocommerce') . esc_html(' To ', 'advanced-local-pickup-for-woocommerce') . esc_html(ucfirst($array_key_last), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']); 
							do_action('wclp_get_more_work_hours_contents', $data);
							echo '</span>';
							?>
						</p>
						<?php 										
					}
				}	
			}
			if (class_exists('Advanced_local_pickup_PRO')) {
				if (!empty($location->store_holiday_message)) {
					?>
					<p class="wclp_pickup_adress_p"><?php echo esc_html($location->store_holiday_message); ?></p>
					<?php 
				}
			}
			?>
		</div>		
	</div>
<?php } } ?>
</div>
<style>
	body.wclp_preview_body {
		background: #fff;
		padding: 30px;
	}
	.wclp_mail_address{
		margin: 10px 0;
		display: table;
		width: 100%;
	}
	.local_pickup_email_title{
		margin-bottom: 10px !important;
	}
	.wclp_location_box{
		display: table-cell;
		width:50%;	
		border: <?php echo esc_html($location_box_border_size); ?> solid <?php echo esc_html($location_box_border_color); ?> !important;
	}
	.wclp_location_box2{
		border-left: 0 !important;
	}
	.wclp_location_box_heading {
		border-bottom: <?php echo esc_html($location_box_border_size); ?> solid <?php echo esc_html($location_box_border_color); ?> !important;
		border-top:0 !important;
		border-left:0 !important;
		border-right:0 !important;
		padding: 10px;
		font-size: <?php echo esc_html($header_font_size); ?>;
		color: <?php echo esc_html($header_font_color); ?>;
		font-weight: bold;
		background:<?php echo esc_html($header_background_color); ?>;
		text-align: inherit;
	}
	.wclp_location_box_content{												
		padding: 10px;		
	}
	.wclp_work_hours_p{
		margin: 0 !important;
		line-height: <?php echo esc_html($location_box_content_line_height); ?>;
		color: <?php echo esc_html($location_box_font_color); ?>;
		font-size: <?php echo esc_html($location_box_font_size); ?>;
	}
	.wclp_pickup_adress_p{
		margin: 0 !important;
		line-height: <?php echo esc_html($location_box_content_line_height); ?>;
		color: <?php echo esc_html($location_box_font_color); ?>;
		font-size: <?php echo esc_html($location_box_font_size); ?>;
	}
	.wclp_mail_address{
		background: <?php echo esc_html($location_box_background_color); ?>; 
	}
	@media screen and (max-width: 500px) {
	.wclp_location_box2{
		border-left: <?php echo esc_html($location_box_border_size); ?> solid <?php echo esc_html($location_box_border_color); ?> !important;
		border-top: 0 !important;
	}
	.wclp_location_box{
		display: block;
		width: 100%;
	}
	}
</style>
