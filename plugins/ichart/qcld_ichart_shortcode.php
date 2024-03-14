<?php 

global $pre_shortcode_instance;
$pre_shortcode_instance=0;

function qcld_iChart_modal() {

?>

<div class="ichart-chart-field-modal" id="ichart-qcld-chart-field-modal" style="display:none">
  <div class="ichart-chart-field-modal-close">&times;</div>
  <h1 class="ichart-chart-field-modal-title"><?php _e( 'Create iChart' , 'iChart' ); ?></h1>
 <div style="position: absolute;right: 59px;">
<input type="button" value="Generate Chart" class="button button-primary button-large iChartgetallvalue" />
</div>
<div style="margin-top: 31px;margin-bottom: 20px;">
	<p style="font-size: 14px;
    border-bottom: 1px solid #ddd;
    padding-bottom: 10px;
    margin-bottom: 10px;"><?php _e( 'iChart is a Project by' , 'iChart' ); ?> <a href="https://www.quantumcloud.com/" style="text-decoration:none"><?php _e( 'Web Design Company - QuantumCloud' , 'iChart' ); ?></a> &nbsp; | &nbsp; <a style="color: #FCB214;font-size: 16px; font-weight: bold;text-decoration: none;" href="<?php echo qcichart_upgrade_link; ?>" target="_blank" ><?php _e( 'Upgrade to Pro' , 'iChart' ); ?></a></p>

</div>
  <div class="ichart-chart-field-modal-icons">

		<div class="form-group">
			<label for="charttype"><?php _e( 'Select Chart Type' , 'iChart' ); ?></label>

			<select id="ichart-charttype" class="form-control">
				<option value="<?php _e( 'line' , 'iChart' ); ?>"><?php _e( 'Line' , 'iChart' ); ?></option>
				<option value="<?php _e( 'bar' , 'iChart' ); ?>"><?php _e( 'Bar' , 'iChart' ); ?></option>
				<option value="<?php _e( 'radar' , 'iChart' ); ?>"><?php _e( 'Radar' , 'iChart' ); ?></option>
				<option value="<?php _e( 'polarArea' , 'iChart' ); ?>"><?php _e( 'polarArea' , 'iChart' ); ?></option>
				<option value="<?php _e( 'pie' , 'iChart' ); ?>"><?php _e( 'pie' , 'iChart' ); ?></option>
				<option value="<?php _e( 'doughnut' , 'iChart' ); ?>"><?php _e( 'doughnut' , 'iChart' ); ?></option>
				
			</select>
		</div>
		<div class="form-group">
			<label for="datasettitle"><?php _e( 'Chart Title' , 'iChart' ); ?></label>
			<input type="text" id="ichart-charttitle" required/>
		</div>
		<div id="ichart-datasetcontainer" class="datasetcontainer">
			<p class="datasetheading"><?php _e( 'Dataset Area' , 'iChart' ); ?></p>
			<div class="form-group">
				<label for="datasettitle"><?php _e( 'Dataset Name' , 'iChart' ); ?></label>
				<input type="text" id="ichart-datasetname" required/>
			</div>
			<table id="ichart-iChartdatasettable" class="datasettable" cellspacing="0">
				<thead>
				<tr>
						<th class="manage-column column-cb check-column" scope="col"><?php _e( 'Label' , 'iChart' ); ?></th> 
						<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e( 'Value' , 'iChart' ); ?></th>
						<th id="columnname" class="manage-column column-columnname" scope="col"><?php _e( 'Color' , 'iChart' ); ?></th>
				</tr>
				</thead>
				<tfoot>
					<tr>
						<th style="text-align:left" class="manage-column column-cb check-column" scope="col"><input type="button" id="ichart-ichartaddrow" value="+ Add row" class="button button-primary button-large" /></th>
						<th class="manage-column column-columnname" scope="col"></th>
					</tr>
				</tfoot>
				<tbody>
					<tr>
						<td class="check-column" scope="row"><input type="text" name="label[]" /></td>
						<td class="column-columnname"><input type="text" name="value[]" /></td>
						<td class="column-columnname"><input type="text" name="bgcolor[]" class="color-field" /></td>
						
						<td class="column-columnname"><a href="javascript:void(0);" class="button button-secondary iChartremoverow"><?php _e( 'Remove' , 'iChart' ); ?></a></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		<div id="ichart-configurationcontainer" class="configurationcontainer">
			<p class="datasetheading"><?php _e( 'Configuration Area' , 'iChart' ); ?></p>

			<table id="configtable" class="configtable" cellspacing="0">
				<tr>
					<td><?php _e( 'Width' , 'iChart' ); ?></td>
					<td><input type="text" id="ichart-width" name="width" placeholder="500px or 50%" class="" /></td>
				</tr>
				
				<tr>
					<td><?php _e( 'Background Color' , 'iChart' ); ?></td>
					<td><input type="text" id="ichart-backgroundcolor" name="backgroundcolor" class="color-field" /></td>
				</tr>
				<tr>
					<td><?php _e( 'Border Color' , 'iChart' ); ?></td>
					<td><input type="text" id="ichart-bordercolor" name="bordercolor" class="color-field" /></td>
				</tr>
				<tr>
					<td><?php _e( 'Pointer Style' , 'iChart' ); ?></td>
					<td><select name="pointerstyle" id="ichart-pointerstyle">
						<option value="<?php _e( 'circle' , 'iChart' ); ?>"><?php _e( 'Circle' , 'iChart' ); ?></option>
						<option value="<?php _e( 'triangle' , 'iChart' ); ?>triangle"><?php _e( 'Triangle' , 'iChart' ); ?></option>
						<option value="<?php _e( 'rect' , 'iChart' ); ?>"><?php _e( 'Rect' , 'iChart' ); ?></option>
						<option value="<?php _e( 'rectRounded' , 'iChart' ); ?>"><?php _e( 'RectRounded' , 'iChart' ); ?></option>
						<option value="<?php _e( 'rectRot' , 'iChart' ); ?>"><?php _e( 'RectRot' , 'iChart' ); ?></option>
						<option value="<?php _e( 'cross' , 'iChart' ); ?>"><?php _e( 'Cross' , 'iChart' ); ?></option>
						<option value="<?php _e( 'crossRot' , 'iChart' ); ?>"><?php _e( 'CrossRot' , 'iChart' ); ?></option>
						<option value="<?php _e( 'star' , 'iChart' ); ?>"><?php _e( 'Star' , 'iChart' ); ?></option>
						<option value="<?php _e( 'line' , 'iChart' ); ?>"><?php _e( 'Line' , 'iChart' ); ?></option>
						<option value="<?php _e( 'dash' , 'iChart' ); ?>"><?php _e( 'Dash' , 'iChart' ); ?></option>
					</select></td>
				</tr>
				<tr id="linestyle">
					<td><?php _e( 'Line Style' , 'iChart' ); ?></td>
					<td><select name="lstyle" id="ichart-lstyle">
						<option value=""><?php _e( 'Default' , 'iChart' ); ?></option>
						<option value="<?php _e( 'stepped' , 'iChart' ); ?>stepped"><?php _e( 'Stepped' , 'iChart' ); ?></option>
						<option value="<?php _e( 'filled' , 'iChart' ); ?>filled"><?php _e( 'Filled' , 'iChart' ); ?></option>
						<option value="<?php _e( 'dashed' , 'iChart' ); ?>"><?php _e( 'Dashed' , 'iChart' ); ?></option>
					</select></td>
				</tr>
				<tr id="aspectratio">
					<td><?php _e( 'Aspect Ratio' , 'iChart' ); ?></td>
					<td><select name="ichart-aspectratio" id="ichart-aspectratio">
						<option value="1"><?php _e( '1' , 'iChart' ); ?></option>
						<option value="2"><?php _e( '2' , 'iChart' ); ?></option>
						<option value="3"><?php _e( '3' , 'iChart' ); ?></option>
						<option value="4"><?php _e( '4' , 'iChart' ); ?></option>
						<option value="5"><?php _e( '5' , 'iChart' ); ?></option>
						<option value="6"><?php _e( '6' , 'iChart' ); ?></option>
						<option value="7"><?php _e( '7' , 'iChart' ); ?></option>
						<option value="8"><?php _e( '8' , 'iChart' ); ?></option>
						<option value="9"><?php _e( '9' , 'iChart' ); ?></option>
					</select></td>
				</tr>

			</table>
			
		</div>		
		<input type="button" value="Generate Chart" class="button button-primary button-large iChartgetallvalue" />
		<a style="color: #FCB214;font-size: 16px;font-weight: bold;text-decoration: none; display: inline-block; margin: 6px 10px;" href="<?php echo qcichart_upgrade_link; ?>" target="_blank" >Upgrade to Pro</a>

  </div>
  <div class="ichart_shortcode_container" style="display:none;">
		<div class="qcpd_single_field_shortcode">
			<textarea style="width:100%;height:200px" id="ichart_shortcode_container"></textarea>
			<p><b>Copy</b> the shortcode & use it any text block. <button class="ichart_copy_close button button-primary button-small" style="float:right">Copy & Close</button></p>
		</div>
	</div>
</div>

<?php

}
function qc_ichart_clean($string) {
   $string = str_replace(' ', '-', strtolower($string)); // Replaces all spaces with hyphens.
   return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
}
add_action( 'admin_footer', 'qcld_iChart_modal');

add_shortcode('qcld-ichart', 'qciChart_textlist_full_shortcode_chart');
function qciChart_textlist_full_shortcode_chart($atts = array()){
	ob_start();
	if(is_admin()){
		return;
	}
	
	global $pre_shortcode_instance;
	extract( shortcode_atts(
		array(
			'label' => 'january,February,March,April',
			'value' => '80,-30,20,-50',
			'type' => 'line',
			'title' => 'Demo Title',
			'datasetname'	=>'Demo',
			'backgroundcolor' => '',
			'width' => '100%',
			'bgcolor' => '',
			'bordercolor' => '',
			'pointerstyle' => 'circle',
			'linestyle' => "",
			'aspectratio' => '1',
		), $atts
	));
	$label = explode(',',$label);
	$label = '"'.implode('","',$label).'"';
	
	$pre_shortcode_instance++;
	$_ex = qc_ichart_clean($title).'-'.$pre_shortcode_instance;

	$yaxis_display = 'false';
	$yaxis_gridline = 'false';
	if( $type == 'line' || $type == 'bar' ){
		$yaxis_display = 'true';
		$yaxis_gridline = 'true';
	}


	$arr = explode (",", $value);
	$values = '';
   	foreach ($arr as $elem) 
      $values .= filter_var( $elem, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION).',' ;
      
      //$values .= filter_var($elem, FILTER_SANITIZE_NUMBER_INT).',' ;

?>
<!-- This site is using iChart - https://www.quantumcloud.com/products/ -->
<div style="width:<?php echo $width; ?>;margin:0 auto;">
	<canvas id="myiChart<?php echo $_ex; ?>"></canvas>
</div>
	
<script>

jQuery(window).on('load',function(){
	var ctx = document.getElementById("myiChart<?php echo $_ex; ?>");

	var myChart = new Chart(ctx, {
	    type: "<?php echo ($type); ?>",
	    data: {
	        labels: [<?php echo ($label); ?>],
	        datasets: [{
	            label: '<?php echo ($datasetname) ?>',
	            data: [<?php echo $values; ?>],
				<?php 
				if(strlen($bgcolor)>2 and $type!='line' and $type!='radar'){
				?>
				backgroundColor: [<?php echo ($bgcolor); ?>],
				<?php
				}else{
				?>
				backgroundColor: '<?php echo ($backgroundcolor); ?>',
				<?php } ?>
				
				<?php if( $type=='line' || $type=='radar'){ ?>
					pointBackgroundColor: [<?php echo ($bgcolor); ?>],
				<?php } ?>
				
	            <?php 
				if($bordercolor!=''){
				?>
				borderColor: '<?php echo ($bordercolor); ?>',
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
	    	aspectRatio: <?php echo $aspectratio; ?>,
	    <?php if( ($type == 'line' || $type == 'bar' || $type == 'radar') && ( $datasetname == '' || empty($datasetname) || !isset($datasetname) )  ){ ?>
		    legend: {
	            display: false
	        },
	    <?php } ?>
			elements: {
						point: {
							pointStyle: '<?php echo ($pointerstyle) ?>'
						}
					},
					title: {
	                        display: true,
	                        text: '<?php echo ($title); ?>'
	                    },
			tooltips: {
	                    mode: 'index',
	                    intersect: false,
	                },
	                hover: {
	                    mode: 'nearest',
	                    intersect: true
	                },
				scales: {
                    yAxes: [{
                        display: <?php echo $yaxis_display; ?>,
                        gridLines: {
                            display: <?php echo $yaxis_gridline; ?>,
                        },
                        ticks: {
                            beginAtZero: true,
                            min: 0
                        }
                    }],
                },

                plugins: {
			      deferred: {
			        delay: 500      // delay of 500 ms after the canvas is considered inside the viewport
			      }
			    }

	    }
	});
});
</script>
<?php
	$content = ob_get_clean();
    return $content;
}
