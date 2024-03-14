<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2024 www.BillMinozzi.com
 * @ Modified time: 2021-03-03 09:03:33
 */
// <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
?>


<canvas id="doughnutChart" width="175" height="175"></canvas>
<?php
$total = $fail + $ok;
$failPercentage = ($fail / $total) * 100;
$okPercentage = ($ok / $total) * 100;
?>
<script>
    var ctx = document.getElementById('doughnutChart').getContext('2d');
    var data = {
        labels: ['Fails', 'Solved'],
        datasets: [{
            data: [<?php echo $failPercentage; ?>, <?php echo $okPercentage; ?>],
            backgroundColor: ['#FF0000', '#33CC33'],
        }]
    };
    var options = {
        cutoutPercentage: 70,
        responsive: false,
        maintainAspectRatio: false,
        legend: {
            display: true,
        }
    };
    var doughnutChart = new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: options
    });
</script>
<!--
<style>
    .prg-cont.canvas {
        width: 125px !important;
    }
</style>
<center>
    <div class="prg-cont rad-prg" id="indicatorContainer200" style="width: 125px; height: 125px"></div>
</center>
<script>
    jQuery('#indicatorContainer200').radialIndicator({
        barColor: 'red',
        barWidth: 10,
        initValue: <?php echo esc_attr(($ok / $challenges) * 100); ?>,
        roundCorner: true,
        percentage: true,
        radius: 50,
        barColor: {
            0: '#33CC33',
            60: '#33CC33',
            61: '#FFD700',
            75: '#FF0000',
            100: '#FF0000'
        },
    });
</script>
-->
