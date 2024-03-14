<?php
/**
 * Visitor Graph Widget
 *
 * @package Haruncpi\WpCounter
 * @subpackage Views
 * @author Harun<harun.cox@gmail.com>
 * @link https://learn24bd.com
 * @since 1.2
 */

use Haruncpi\WpCounter\DB;
$vistorData = DB::get_visitor_graph_data();
?>
	<div style="width: 100%">
		<canvas id="canvas" height="107"></canvas>
	</div>

	<script type="text/javascript">
		var randomScalingFactor = function () {
			return Math.round(Math.random() * 100)
		};
		var barChartData = {
			labels: [
				<?php
				foreach ( $vistorData as $data ) {
					echo "'" . $data->visit_date . "'" . ',';
				}
				?>
			],
			datasets: [
				{
					fillColor: "rgba(221,56,45,0.5)",
					strokeColor: "rgba(220,220,220,0.8)",
					highlightFill: "rgba(220,220,220,0.75)",
					highlightStroke: "rgba(220,220,220,1)",
					data: [
						<?php
						foreach ( $vistorData as $data ) {
							echo $data->total . ',';
						}
						?>
					]
				}
			]
		}
		window.onload = function () {
			var ctx = document.getElementById("canvas").getContext("2d");
			window.myBar = new Chart(ctx).Bar(barChartData, {
				responsive: true
			});
		}

	</script>
