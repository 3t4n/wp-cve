<?php



	wp_register_script('mo_lla_charts','https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js');
	wp_register_script('mo_lla_datalabels','https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0');
	wp_print_scripts('mo_lla_charts');
	wp_print_scripts('mo_lla_datalabels');
?>
	<div style="display:flex;">
	<div class="molla-container">
		<div class="molla-circle-container">
				<div class="molla-circle-card">
					<div class="molla-circle-content">
						<?php echo esc_html($lla_attacks_blocked);?>
					</div>	
					<div class="molla-circle-footer">
						Failed Login <a class="dashicons dashicons-admin-generic" href="admin.php?page=reports"></a>
					</div>
				</div>
				<div class="molla-circle-card">
					<div class="molla-circle-content">
						<?php echo esc_html($totalAttacks);?>
					</div>
					<div class="molla-circle-footer">
						Attacks Blocked <a class="dashicons dashicons-admin-generic" href="admin.php?page=reports"></a>
					</div>
				</div>
				<div class="molla-circle-card">
					<div class="molla-circle-content">
						<?php echo esc_html($lla_count_ips_blocked); ?>
					</div>
					<div class="molla-circle-footer">
						Blocked IPs <a class="dashicons dashicons-admin-generic" href="admin.php?page=mo_lla_login_and_spam"></a>
					</div>
					
				</div>
				<div class="molla-circle-card">
					<div class="molla-circle-content">
						<?php echo esc_html($lla_count_ips_whitelisted); ?>
					</div>
					<div class="molla-circle-footer">
						White-listed IPs <a class="dashicons dashicons-admin-generic" href="admin.php?page=mo_lla_login_and_spam"></a>
					</div>
					
				</div>
		</div>
		<div class="molla-graph-container">
			<div id="molla-attacks-div" class="molla-dash-charts molla-card">
				<div class="mo-lla-card-head">
					Total Attacks blocked
				</div>
				<br>
				<div class="mo-lla-card-body">
					<div id="molla-attacks-total" class="molla-summery">
						0
					</div>
					<canvas id="molla-attacks"></canvas>
				</div>
			</div>

			<div class="molla-dash-charts molla-card">
				<div class="mo-lla-card-head">
					IP blocked
				</div>
				<br>
				<div class="mo-lla-card-body">
					<div id='molla-ips-total' class="molla-summery">
						0
					</div>
					<canvas id="molla-ips"></canvas>
				</div>
			</div>


		</div>
	</div>
	<div id="molla-support-section" class="molla-support-section">
	</div>
</div>

<script>

	const dummyyValues = [0,0,0,0,1];
	const dummybarColorsAttack = [
	"#b91d47",
	"#00aba9",
	"#2b5797",
	"#e8c3b9",
	"#0081E3",
	];


	const SQLInjections = '<?php echo esc_attr($sqlC); ?>';
	const fileInclusions = '<?php echo esc_attr($rceC); ?>';
	const rfiLfi = '<?php echo esc_attr($rfiC + $lfiC); ?>';
	const xss = '<?php echo esc_attr($xssC); ?>';

	const totalAttacks = '<?php echo esc_attr($totalAttacks); ?>';

	const xValuesAttack = ["Injections", "File inclusions", "RCE", "XSS"];
	const yValuesAttack = [SQLInjections, fileInclusions, rfiLfi, xss];
	const barColorsAttack = [
	"#b91d47",
	"#00aba9",
	"#2b5797",
	"#e8c3b9",
	];

	const manualBlocked = '<?php echo esc_attr($manualBlocks); ?>';
	const realTime = '<?php echo esc_attr($realTime); ?>';
	const countryBlocked = '<?php echo esc_attr($countryBlocked); ?>';
	const ipblockedByWAF = '<?php echo esc_attr($IPblockedByWAF); ?>';

	const totalIPBlocked = '<?php echo esc_attr($totalIPBlocked); ?>';

	const xValuesIp = ["Manual", "Real time", "Country blocked", "WAF"];
	const yValuesIp = [manualBlocked, realTime, countryBlocked, ipblockedByWAF];
	const barColorsIp = [
	"#b91d47",
	"#00aba9",
	"#2b5797",
	"#e8c3b9",
	];



	Chart.register(ChartDataLabels);

	if(totalAttacks>0)
		molla_chart('molla-attacks','Attacks blocked',xValuesAttack,yValuesAttack,totalAttacks,barColorsAttack);
	else
		molla_chart('molla-attacks','Attacks blocked',xValuesAttack,dummyyValues,totalAttacks,dummybarColorsAttack,true);


	if(totalIPBlocked>0)
		molla_chart('molla-ips','IP blocked',xValuesIp,yValuesIp,totalIPBlocked,barColorsIp);
	else
		molla_chart('molla-ips','IP blocked',xValuesIp,dummyyValues,totalIPBlocked,dummybarColorsAttack,true);


	function molla_chart(moId,moTitle,xArray,yArray,totalAttacks,barColors,isDummy=false,eventArray=['mousemove', 'mouseout', 'click', 'touchstart', 'touchmove']){

		jQuery('#'+moId+'-total').html(totalAttacks);

		var border = 60; 
		if(isDummy){
			jQuery('.molla-card').attr("disabled", "disabled").off('hover');
			border = 90;
			eventArray=[];
		}

		new Chart(moId, {
		type: "doughnut",
		showTooltips:false,
		data: {
			datasets: [{
				backgroundColor: barColors,
				data: yArray
			}],
			labels:xArray
		},
		options: {
			events: eventArray,
			cutout: border,
			title: {
				display: true,
				text: moTitle
			},
			plugins: {
				// Change options for ALL labels of THIS CHART
				datalabels: {
					display : !isDummy,
					color: '#fff',
					labels: {
						title: {
							font: {
							weight: 'bold'
							}
						},
						value: {
							color: 'green'
						}
					}
				},
				legend: {
					display: true,
					position: 'bottom'
				}
			}
			}
		});
	}
 

</script>
