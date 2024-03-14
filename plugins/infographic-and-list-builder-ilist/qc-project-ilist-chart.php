<?php 
defined('ABSPATH') or die("No direct script access!");

if(!function_exists('ilist_modal_chart')){
	function ilist_modal_chart() { 
	?>

	<div class="ilist-chart-field-modal" id="ilist-chart-field-modal" style="display:none">
	  <div class="ilist-chart-field-modal-close">&times;</div>
	  <h1 class="ilist-chart-field-modal-title"><?php esc_html_e( 'Create Chart', 'ilist' ); ?></h1>
	 <div style="position: absolute;right: 59px;">
	 	<a href="<?php echo esc_url('https://wordpress.org/plugins/ichart/'); ?>" target="_blank" class="button button-primary button-large" /> <?php echo esc_html( 'iChart - Stand Alone Version' , 'iList' ); ?></a>
		<input type="button" value="<?php echo esc_attr( 'Generate Chart' , 'iList' ); ?>" class="button button-primary button-large getallvalue" />
	</div>
	  <div class="ilist-chart-field-modal-icons">

			<div class="form-group">
				<label for="charttype"><?php echo esc_html( 'Select Chart Type' , 'iList' ); ?></label>

				<select id="charttype" class="form-control">
					<option value="line"><?php echo esc_html( 'Line' , 'iList' ); ?></option>
					<option value="bar"><?php echo esc_html( 'Bar' , 'iList' ); ?></option>
					<!-- <optgroup label="Pro Features"> -->
					<option  value="radar" ><?php echo esc_html( 'Radar' , 'iList' ); ?></option>
					<option  value="polarArea" ><?php echo esc_html( 'Polar Area' , 'iList' ); ?></option>
					<option  value="pie" ><?php echo esc_html( 'Pie' , 'iList' ); ?></option>
					<option  value="doughnut" ><?php echo esc_html( 'Doughnut' , 'iList' ); ?></option>
					<!-- </optgroup> -->
					
				</select>
			</div>
			<div class="form-group">
				<label for="datasettitle"><?php echo esc_html( 'Chart Title' , 'iList' ); ?></label>
				<input type="text" id="charttitle" required/>
			</div>
			<div id="datasetcontainer" class="datasetcontainer">
				<p class="datasetheading"><?php echo esc_html( 'Dataset Area' , 'iList' ); ?></p>
				<div class="form-group">
					<label for="datasettitle"><?php echo esc_html( 'Dataset Name' , 'iList' ); ?></label>
					<input type="text" id="datasetname" required/>
				</div>
				<table id="datasettable" class="datasettable" cellspacing="0">
					<thead>
					<tr>
							<th class="manage-column column-cb check-column" scope="col"><?php echo esc_html( 'Label' , 'iList' ); ?></th> 
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html( 'Value' , 'iList' ); ?></th>
							<th id="columnname" class="manage-column column-columnname" scope="col"><?php echo esc_html( 'Color' , 'iList' ); ?></th>
					</tr>
					</thead>
					<tfoot>
						<tr>
							<th class="manage-column column-cb check-column" scope="col"><input type="button" id="chartaddrow" value="+ <?php echo esc_html( 'Add row' , 'iList' ); ?>" class="button button-primary button-large" /></th>
							<th class="manage-column column-columnname" scope="col"></th>
						</tr>
					</tfoot>
					<tbody>
						<tr>
							<td class="check-column" scope="row"><input type="text" name="label[]" /></td>
							<td class="column-columnname"><input type="number" name="value[]" /></td>
							<td class="column-columnname"><input type="text" name="bgcolor[]" class="color-field" /></td>
							
							<td class="column-columnname"><a href="javascript:void(0);" class="removerow"><?php echo esc_html( 'Remove' , 'iList' ); ?></a></td>
						</tr>
					</tbody>
				</table>
				
			</div>
			<div id="configurationcontainer" class="configurationcontainer">
				<p class="datasetheading"><?php echo esc_html( 'Configuration Area' , 'iList' ); ?></p>

				<table id="configtable" class="configtable" cellspacing="0">
					<tr>
						<td><?php echo esc_html( 'Background Color' , 'iList' ); ?></td>
						<td><input type="text" id="backgroundcolor" name="backgroundcolor" class="color-field" /></td>
					</tr>
					<tr>
						<td><?php echo esc_html( 'Border Color' , 'iList' ); ?></td>
						<td><input type="text" id="bordercolor" name="bordercolor" class="color-field" /></td>
					</tr>
					<tr>
						<td><?php echo esc_html( 'pointer Style' , 'iList' ); ?></td>
						<td><select name="pointerstyle" id="pointerstyle">
							<option value="circle"><?php echo esc_html( 'circle' , 'iList' ); ?></option>
							<option value="triangle"><?php echo esc_html( 'triangle' , 'iList' ); ?></option>
							<option value="rect"><?php echo esc_html( 'rect' , 'iList' ); ?></option>
							<option value="rectRounded"><?php echo esc_html( 'rectRounded' , 'iList' ); ?></option>
							<option value="rectRot"><?php echo esc_html( 'rectRot' , 'iList' ); ?></option>
							<option value="cross"><?php echo esc_html( 'cross' , 'iList' ); ?></option>
							<option value="crossRot"><?php echo esc_html( 'crossRot' , 'iList' ); ?></option>
							<option value="star"><?php echo esc_html( 'star' , 'iList' ); ?></option>
							<option value="line"><?php echo esc_html( 'line' , 'iList' ); ?></option>
							<option value="dash"><?php echo esc_html( 'dash' , 'iList' ); ?></option>
						</select></td>
					</tr>
					<tr id="linestyle">
						<td><?php echo esc_html( 'Line Style' , 'iList' ); ?></td>
						<td><select name="lstyle" id="lstyle">
							<option value=""><?php echo esc_html( 'Default' , 'iList' ); ?></option>
							<option value="stepped"><?php echo esc_html( 'Stepped' , 'iList' ); ?></option>
							<option value="filled"><?php echo esc_html( 'filled' , 'iList' ); ?></option>
							<option value="dashed"><?php echo esc_html( 'Dashed' , 'iList' ); ?></option>
						</select></td>
					</tr>				
				</table>
				
			</div>		

			<input type="button" value="<?php echo esc_html( 'Generate Chart' , 'iList' ); ?>" class="button button-primary button-large getallvalue" />
			


	  </div>
	</div>

	<?php
	}
}

if(!function_exists('qc_sld_clean')){
	function qc_sld_clean($string) {
	   $string = str_replace(' ', '-', strtolower($string)); // Replaces all spaces with hyphens.
		
	   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
	}
}

add_action( 'admin_footer', 'ilist_modal_chart');

add_shortcode('qcld-chart', 'qcilist_textlist_full_shortcode_chart');
if(!function_exists('qcilist_textlist_full_shortcode_chart')){
	function qcilist_textlist_full_shortcode_chart($atts = array()){
		extract( shortcode_atts(
			array(
				'label' 			=> 'january,February,March,April',
				'value' 			=> '80,-30,20,-50',
				'type' 				=> 'line',
				'title' 			=> 'Demo Title',
				'datasetname'		=> 'Demo',
				'backgroundcolor' 	=> '',
				'bgcolor' 			=> '',
				'bordercolor' 		=> '',
				'pointerstyle' 		=> 'circle',
				'linestyle' 		=> ""
			), $atts
		));
		$label = explode(',',$label);
		$label = '"'.implode('","',$label).'"';
		
		
		
		$_ex = qc_sld_clean($title);
	?>

		<canvas id="myChart<?php echo esc_attr($_ex); ?>" ></canvas>

	<script>
	jQuery(document).ready(function($){

		var ctx = document.getElementById("myChart<?php echo esc_attr($_ex); ?>");

		var myChart = new Chart(ctx, {
		    type: "<?php echo esc_attr($type); ?>",
		    data: {
		        labels: [<?php echo ($label); ?>],
		        datasets: [{
		            label: '<?php echo esc_attr($datasetname); ?>',
		            data: [<?php echo esc_attr($value); ?>],
					<?php 
					if($bgcolor!='' and $type!='line' and $type!='radar'){
					?>
					backgroundColor: [<?php echo esc_attr($bgcolor); ?>],
					<?php
					}else{
					?>
					backgroundColor: '<?php echo esc_attr($backgroundcolor); ?>',
					<?php
					}
					?>
					
		            <?php 
					if($bordercolor!=''){
					?>
					borderColor: '<?php echo esc_attr($bordercolor); ?>',
					<?php
					}
					?>
		            pointRadius: 8,
					pointHoverRadius: 11,
		            //borderWidth: 3,
					<?php 
					if($linestyle=='filled'){
					?>
					fill: true,
					<?php
					}elseif($linestyle=='stepped'){
					?>
					steppedLine: true,
					fill: false,
					<?php 
					}elseif($linestyle=='dashed'){
					?>
					borderDash: [5, 5],
					fill: false,
					<?php
					}else{
					?>
					fill: false,
					<?php
					}
					?>

					
		        }]
		    },
		    options: {
				elements: {
							point: {
								pointStyle: '<?php echo esc_attr($pointerstyle); ?>'
							}
						},
						title: {
		                        display: true,
		                        text: '<?php echo esc_attr($title); ?>'
		                    },
				tooltips: {
		                    mode: 'index',
		                    intersect: false,
		                },
		                hover: {
		                    mode: 'nearest',
		                    intersect: true
		                },
					scale: {
		              ticks: {
		                beginAtZero: true
		              }
		            }

		    }
		});

	});
	</script>
	<?php
		
	}
}