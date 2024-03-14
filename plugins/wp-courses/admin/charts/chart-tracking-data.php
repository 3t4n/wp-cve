<?php 

    $days = (int) get_option('wpc-tracking-overview-days-chart-select', 30);
    if(isset($_POST['wpc-num-days'])) {
        $days = (int) $_POST['wpc-num-days'];
        update_option('wpc-tracking-overview-days-chart-select', (int) $_POST['wpc-num-days']);
    }

    $view = (int) get_option('wpc-tracking-overview-view-chart-select', 0);
    if(isset($_POST['wpc-view'])) {
        $view = (int) $_POST['wpc-view'];
        update_option('wpc-tracking-overview-view-chart-select', (int) $_POST['wpc-view']);
    }

?>

<p>
    <form action="#wpc-tracking-chart" method="post" class="wpc-chart-filter">
        <label for="wpc-num-days">Days: </label>
        <select name="wpc-num-days" id="wpc-num-days">
            <option value="30" <?php selected(esc_attr($days), 30); ?>>30</option>
            <option value="60" <?php selected(esc_attr($days), 60); ?>>60</option>
            <option value="90" <?php selected(esc_attr($days), 90); ?>>90</option>
            <option value="120" <?php selected(esc_attr($days), 120); ?>>120</option>
            <option value="365" <?php selected(esc_attr($days), 365); ?>>365</option>
        </select>
        <label for="wpc-view">Action: </label>
        <select name="wpc-view" id="wpc-view">
            <option value="0" <?php selected(esc_attr($view), 0); ?>><?php esc_html_e('Viewed', 'wp-courses'); ?></option>
            <option value="1" <?php selected(esc_attr($view), 1); ?>><?php esc_html_e('Completed', 'wp-courses'); ?></option>
        </select>
    </form>
</p>

<canvas id="viewsChart" width="400" height="400" style="max-height: 400px;"></canvas>

<script>

jQuery(document).ready(function($){
    $(function() {
        $('#wpc-num-days, #wpc-view, #wpc-active-users-limit, #wpc-active-users-view, #wpc-active-num-days, #wpc-popular-courses-view, #wpc-popular-courses-limit').change(function() {
            this.form.submit();
        });
    });
});

var ctx = document.getElementById('viewsChart');
var myChart = new Chart(ctx, {
    type: 'line',
    data: {
        datasets: [{
            data: <?php echo wp_json_encode(wpc_get_viewed_lessons_per_day($days, $view)); ?>,
            backgroundColor: [
                '#e21672'
            ],
            borderColor: [
                '#e21672'
            ],
            borderWidth: 2
        }]
    },
    options: {
        plugins: {
          legend: {
            position: 'top',
            display: false,
          },
          title: {
            display: false,
            text: 'Viewed Lessons Per Day'
          }
        },
    }
});
</script>