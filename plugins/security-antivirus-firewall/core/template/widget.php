<?php
/*  
 * Security Antivirus Firewall (wpTools S.A.F.)
 * http://wptools.co/wordpress-security-antivirus-firewall
 * Version:           	2.3.5
 * Build:             	77229
 * Author:            	WpTools
 * Author URI:        	http://wptools.co
 * License:           	License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * Date:              	Sat, 01 Dec 2018 19:09:28 GMT
 */

if ( ! defined( 'WPINC' ) )  die;
if ( ! defined( 'ABSPATH' ) ) exit;

?>
<script src="<?php echo WPTSAF_URL;?>assets/dist/js/chart.bundle.min.js"></script>
<div class="activity-block" style="border-bottom: 0 none;">
	
	<h3 style="margin-top: 0px; padding-top: 0px;">
		Even more safe with <strong>premium security</strong> tools from S.A.F.
	</h3>
	
	<div style="width: 49%; float: left;">
		<ul>
			<li>- Advanced <strong>Firewall</strong> functions</li>
			<li>- More advanced <strong>Antivirus</strong> issues details</li>
			<li>- Advanced <strong>IP's manager</strong> </li>
			<li>- Flexible <strong>ban events</strong> management</li>
			<li>- Advanced <strong>bruteforce</strong> behaviour manager</li>
			<li>- Attackers fast IP's ban function</li>
			<li>- Advanced <strong>admin notification</strong> functionality</li>
			<li>- More flexibility <strong>security</strong> reports</li>
			<li>- <strong>Permanent</strong> IP's <strong>ban</strong> function</li>
		</ul>
		<a href="http://wptools.co/links/saf-pro" target="_blank" class="button button-primary">GET PREMIUM FUNCTIONS</a>
	</div>
	<div style="width: 45%; float: left; margin-left: 4%;">
		<canvas id="wptools_saf_dashboard_widget_canvas" ></canvas>
		<p style="text-align: center;">
			<span style="color: #72777c; font-weight: bold;"><?php _e('Total issues detected', 'security-antivirus-firewall'); ?>:</span> 
			<span style="color: #72777c;"><?php echo $totalIssues; ?></span>
		</p>
	</div>
	<div style="clear: both;"></div>

	<h3 class="screen-reader-text"><?php _e('View Security Modules', 'security-antivirus-firewall'); ?></h3>
	
	<ul class="subsubsub" style="float: none; border-top: 1px solid #eee; margin: 12px -12px -12px; padding: 5px 12px 5px;"> 
		<li >
			<span style="color: #72777c; font-weight: bold;"><?php _e('Security Modules Status', 'security-antivirus-firewall'); ?>:</span> 
		</li>
		<li > 
			<span style="color: #72777c;"><a href="<?php echo admin_url('admin.php?page=wptsaf_security_extensions');?>"><?php _e('Enabled');?></a> (<?php echo $enabledCount;?>)</span> | 
		</li>
		<li > 
			<span style="color: #72777c;"><a href="<?php echo admin_url('admin.php?page=wptsaf_security_extensions');?>"><?php _e('Disabled');?></a> (<?php echo $desabledCount;?>)</span> 
		</li>
	</ul>
	
</div>

<script>
    jQuery(document).ready(function(){
		new Chart(document.getElementById("wptools_saf_dashboard_widget_canvas"), {
			type: 'doughnut',
			tooltipFillColor: "rgba(51, 51, 51, 0.55)",
			data:{
				labels: [<?php 
				for ($i=0; $i < count($modulesStats); $i++) { 
					echo '"'.$modulesStats[$i]['title'].'",';
				} ?>],
				datasets: [{
					data: [<?php 
				for ($i=0; $i < count($modulesStats); $i++) { 
					echo '"'.abs($modulesStats[$i]['proc']).'",';
				} ?>],

					backgroundColor: [
						"#0772ea", 
						"#053f80",
						"#8f0cc0",
						"#f29709",
						"#f24509",
						"#e1143b",
						"#991952",
						"#3bdfc2",
						"#77dc14",
						"#db0202",
					],
					hoverBackgroundColor: [
						"#0c7cf9",
						"#185497",
						"#a71bdb",
						"#f9a521",
						"#fa5820",
						"#ec1c43",
						"#ab205e",
						"#47f3d5",
						"#8bf028",
						"#eb1919",
					]
				}]
			},
	        options: {
	        	tooltips: {
			        callbacks: {
			            label: function(tooltipItem, data) {
			            	var ind = tooltipItem.index;
			                return ' '+data.labels[ind]+' '+data.datasets[0].data[ind]+'%';
			            }
			        }
			    },
			    legend:false,
				responsive: true
		    }
        });
      });
    </script>