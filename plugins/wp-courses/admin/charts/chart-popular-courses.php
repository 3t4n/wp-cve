<?php
    $view = (int) get_option('wpc-popular-courses-view-chart-select', 0);
    if(isset($_POST['wpc-popular-courses-view'])) {
        $view = (int) $_POST['wpc-popular-courses-view'];
        update_option('wpc-popular-courses-view-chart-select', (int) $_POST['wpc-popular-courses-view']);
    } 

    $limit = (int) get_option('wpc-popular-courses-limit-chart-select', 10);
    if(isset($_POST['wpc-popular-courses-limit'])){
        $limit = (int) $_POST['wpc-popular-courses-limit'];
        update_option('wpc-popular-courses-limit-chart-select', (int) $_POST['wpc-popular-courses-limit']);
    }
?>

<?php $percent_viewed = wpc_get_average_percent( $view, $limit ); ?>

<p>
    <form action="#wpc-popular-courses" method="post" class="wpc-chart-filter">
        <label for="wpc-popular-courses-view">By: </label>
        <select id="wpc-popular-courses-view" name="wpc-popular-courses-view">
            <option value="0" <?php selected(esc_attr($view), 0); ?>><?php esc_html_e('Views', 'wp-courses'); ?></option>
            <option value="1" <?php selected(esc_attr($view), 1); ?>><?php esc_html_e('Completions', 'wp-courses'); ?></option>
        </select>
        <label for="wpc-popular-courses-limit">Limit: </label>
        <select id="wpc-popular-courses-limit" name="wpc-popular-courses-limit">
            <option value="10" <?php selected(esc_attr($limit), 10); ?>>10</option>
            <option value="25" <?php selected(esc_attr($limit), 25); ?>>25</option>
            <option value="50" <?php selected(esc_attr($limit), 50); ?>>50</option>
        </select>
    </form>
</p>

<?php if(!empty($percent_viewed)) { ?>

    <p>
        <canvas id="viewedChart" width="400" height="400" style="max-height: 400px;"></canvas>
    </p>

    <script>
    var ctx = document.getElementById('viewedChart');
    var myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo wp_json_encode($percent_viewed[0]); ?>,
            datasets: [{
                data: <?php echo wp_json_encode($percent_viewed[1]); ?>,
                backgroundColor: [
                    '#FCD46C',
                    '#E21772',
                    '#89D6E2',
                    '#59296b',
                    //'#31CC0E'
                ],
                borderWidth: 0
            }]
        },
        options: {
            // indexAxis: 'y',
            scales: {
                x: {
                    display: false,
                }
            },
            plugins: {
              legend: {
                position: 'top',
                display: true,
              },
              title: {
                display: false,
                text: 'Most Popular Courses by Percentage Viewed'
              }
            },
        }
    });
    </script>

<?php } else { echo '<p>No data yet.</p>'; } ?>