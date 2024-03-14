<?php
/**
 * @ Author: Bill Minozzi
 * @ Copyright: 2024 www.BillMinozzi.com
 * @ Modified time: 2024-01-17
 */
if (!defined('ABSPATH')) {
    die('We\'re sorry, but you can not directly access this file.');
}
global $wpdb;
$table = $wpdb->prefix . "recaptcha_for_all_stats"; 
$table = $wpdb->prefix . "recaptcha_for_all_stats";
$query = "SELECT DATE(date) AS date, challenge, ok FROM $table WHERE date >= CURDATE() - INTERVAL 7 DAY";
$results = $wpdb->get_results($query, OBJECT);



// Verifica se há resultados
if ($results) {
    // Obtém o número de registros
    $num_records = count($results);

            if( $num_records < 1){
                echo '<br>';
                echo esc_attr__('No data is currently available. Please try again later.');
                echo '<br>';
                return;
            }
            

} else {

        echo '<br>';
        echo esc_attr__('No data is currently available. Please try again later.');
        echo '<br>';
        return;
}



$dates = [];
$challengesAssoc = [];
$okAssoc = [];
foreach ($results as $result) {
    $date = (new DateTime($result->date))->format('Y-m-d');
    if (isset($challengesAssoc[$date])) {
        $challengesAssoc[$date] += $result->challenge;
    } else {
        $challengesAssoc[$date] = $result->challenge;
    }
    if (isset($okAssoc[$date])) {
        $okAssoc[$date] += $result->ok;
    } else {
        $okAssoc[$date] = $result->ok;
    }
}
for ($i = 6; $i >= 0; $i--) {
    $currentDate = date('Y-m-d', strtotime("-$i days"));
    $dates[] = $currentDate;
    $challengesData[] = isset($challengesAssoc[$currentDate]) ? $challengesAssoc[$currentDate] : 0;
    $okData[] = isset($okAssoc[$currentDate]) ? $okAssoc[$currentDate] : 0;
}
 $combinedData = array_values(array_unique(array_merge($okData, $challengesData)));
 sort($combinedData);
?>
<canvas id="lineChart" width="600" height="300"></canvas>




<script>
    var ctx = document.getElementById('lineChart').getContext('2d');
    var data = {
        labels: <?php echo json_encode($dates); ?>,
        datasets: [{
            label: 'Challenges',
            data: <?php echo json_encode($challengesData); ?>,
            borderColor: 'red',
            fill: false,
        }, {
            label: 'OK',
            data: <?php echo json_encode($okData); ?>,
            borderColor: 'green',
            fill: false,
        }]
    };
    var ticksData = <?php echo json_encode($combinedData); ?>;
    // ticks: ticksData,
    var options = {
        responsive: false,
        maintainAspectRatio: false,
        scales: {
            x: {
                type: 'category',
                labels: <?php echo json_encode($dates); ?>,
            },
            y: {
                ticks: {
                    stepSize: 1,
                },
                beginAtZero: true, // Prevent negative values on Y-axis
                min: 0, // Set the minimum value to 0
                stepSize: 1, // Define o intervalo entre os números no eixo Y
                suggestedMax: Math.max(...<?php echo json_encode($challengesData); ?>) + 1,
            }
        }
    };
    var lineChart = new Chart(ctx, {
        type: 'line',
        data: data,
        options: options
    });
</script>
<?php
