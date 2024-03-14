<?php 

    $view = (int) get_option('wpc-active-users-view-chart-select', 0);
    if(isset($_POST['wpc-active-users-view'])) {
        $view = (int) $_POST['wpc-active-users-view'];
        update_option('wpc-active-users-view-chart-select', (int) $_POST['wpc-active-users-view']);
    } 

    $limit = (int) get_option('wpc-active-users-limit-chart-select', 10);
    if(isset($_POST['wpc-active-users-limit'])){
        $limit = (int) $_POST['wpc-active-users-limit'];
        update_option('wpc-active-users-limit-chart-select', (int) $_POST['wpc-active-users-limit']);
    }

    $seconds = (int) get_option('wpc-active-users-days-chart-select', 604800);
    if(isset($_POST['wpc-active-num-days'])) {
        $seconds = $_POST['wpc-active-num-days'];
        update_option('wpc-active-users-days-chart-select', (int) $_POST['wpc-active-num-days']);
    }

?>

<p>
    <form action="#wpc-active-users" method="post" class="wpc-chart-filter">
        <label for="wpc-active-users-view">By: </label>
        <select id="wpc-active-users-view" name="wpc-active-users-view">
            <option value="0" <?php selected(esc_attr($view), 0); ?>><?php esc_html_e('Views', 'wp-courses'); ?></option>
            <option value="1" <?php selected(esc_attr($view), 1); ?>><?php esc_html_e('Completions', 'wp-courses'); ?></option>
        </select>
        <label for="wpc-active-users-limit">Limit: </label>
        <select id="wpc-active-users-limit" name="wpc-active-users-limit">
            <option value="10" <?php selected(esc_attr($limit), 10); ?>>10</option>
            <option value="25" <?php selected(esc_attr($limit), 25); ?>>25</option>
            <option value="50" <?php selected(esc_attr($limit), 50); ?>>50</option>
        </select>
        <label for="wpc-active-num-days">Days: </label>
        <select name="wpc-active-num-days" id="wpc-active-num-days">
            <option value="604800" <?php selected(esc_attr($seconds), 604800); ?>>7</option>
            <option value="2629743" <?php selected(esc_attr($seconds), 2629743); ?>>30</option>
            <option value="7889229" <?php selected(esc_attr($seconds), 7889229); ?>>90</option>
            <option value="31556926" <?php selected(esc_attr($seconds), 31556926); ?>>365</option>
        </select>
    </form>
</p>

<p>
    <canvas id="activeUsersChart" width="400" height="400" style="max-height: 400px;"></canvas>
</p>

<script>
var ctx = document.getElementById('activeUsersChart');
var myChart = new Chart(ctx, {
    type: 'bar',
    data: {
        datasets: [{
            data: <?php echo wp_json_encode( wpc_get_active_users( $limit, $view, $seconds ) ); ?>,
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
        plugins: {
          legend: {
            position: 'top',
            display: false,
          },
          title: {
            display: false,
            text: 'Most Active Users'
          }
        },
    }
});
</script>