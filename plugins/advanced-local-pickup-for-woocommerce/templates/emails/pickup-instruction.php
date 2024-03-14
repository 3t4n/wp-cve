<?php
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
	
$alp = wc_local_pickup()->admin;
$settings = wc_local_pickup()->customizer->customize_setting_options_func('ready_pickup');


$hide_widget_header = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'hide_widget_header', $settings['hide_widget_header']['default'] );

$widget_header_text = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'widget_header_text', $settings['widget_header_text']['default']);

$hide_hours_header = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'hide_hours_header', $settings['hide_hours_header']['default']);

$border_color = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'border_color', $settings['border_color']['default']);

$background_color = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'background_color', $settings['background_color']['default']);

$padding = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'padding', $settings['padding']['default']);

$hide_addres_header = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'hide_addres_header', $settings['hide_addres_header']['default']);

$addres_header_text = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'addres_header_text', $settings['addres_header_text']['default']);

$header_hours_text = $alp->get_option_value_from_array('pickup_instruction_customize_settings', 'header_hours_text', $settings['header_hours_text']['default']);
?>
<?php if ('1' != $hide_widget_header) { ?>
	<h2 class="local_pickup_email_title"><?php esc_html_e($widget_header_text, 'advanced-local-pickup-for-woocommerce'); ?></h2>
<?php } ?>
<div class="wclp_mail_address">
	<div class="wclp_location_box 
		<?php 
		if (!empty($new_array)) { 
			echo 'wclp_location_box1';
		} 
		?>
		">
		<?php if ('1' != $hide_addres_header) { ?>
			<div class="wclp_location_box_heading">
				<?php esc_html_e($addres_header_text, 'advanced-local-pickup-for-woocommerce'); ?>
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
		<?php if ('1' != $hide_hours_header) { ?>
			<div class="wclp_location_box_heading">
				<?php esc_html_e($header_hours_text, 'advanced-local-pickup-for-woocommerce'); ?>
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
							echo esc_html_e(ucfirst(key($data)), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']);
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
							echo esc_html_e(ucfirst($array_key_first), 'advanced-local-pickup-for-woocommerce') . esc_html_e(' - ') . esc_html_e(ucfirst($array_key_last), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']);
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
							echo esc_html_e(ucfirst($array_key_first), 'advanced-local-pickup-for-woocommerce') . esc_html_e(' To ', 'advanced-local-pickup-for-woocommerce') . esc_html_e(ucfirst($array_key_last), 'advanced-local-pickup-for-woocommerce') . ' <span>: ' . esc_html(reset($data)['wclp_store_hour']) . ' - ' . esc_html(reset($data)['wclp_store_hour_end']); 
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
		border: 1px solid <?php echo esc_html($border_color); ?> !important;
	}
	.wclp_location_box2{
		border-left: 0 !important;
	}
	.wclp_location_box_heading {
		border-bottom: 1px solid <?php echo esc_html($border_color); ?> !important;
		border-top:0 !important;
		border-left:0 !important;
		border-right:0 !important;
		padding: <?php echo esc_html($padding); ?>;
		font-size: 16px;
		color: #333;
		font-weight: bold;
		background:<?php echo esc_html($background_color); ?>;
		text-align: inherit;
	}
	.wclp_location_box_content{												
		padding: <?php echo esc_html($padding); ?>;		
	}
	.wclp_work_hours_p{
		margin: 0 !important;
	}
	.wclp_pickup_adress_p{
		margin: 0 !important;
	}
	.wclp_mail_address{
		background: <?php echo esc_html($background_color); ?>; 
	}
	@media screen and (max-width: 500px) {
	.wclp_location_box2{
		border-left: 1px solid <?php echo esc_html($border_color); ?> !important;
		border-top: 0 !important;
	}
	.wclp_location_box{
		display: block;
		width: 100%;
	}
	}
</style>
