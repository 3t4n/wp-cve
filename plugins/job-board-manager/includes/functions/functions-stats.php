<?php


if (!defined('ABSPATH')) exit;  // if direct access 


add_action('job_bm_stats_tabs_content_job_posting', 'job_bm_stats_tabs_content_job_posting_date');
add_action('job_bm_stats_tabs_content_application', 'job_bm_stats_tabs_content_job_posting_date');



function job_bm_stats_tabs_content_job_posting_date($tab)
{

    wp_enqueue_style('jquery-ui');
    wp_enqueue_script('jquery-ui-core');
    wp_enqueue_script('jquery-ui-datepicker');

    $date_range = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '7_day';
    $tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'job_posting';

    $before_date = isset($_GET['before']) ? sanitize_text_field($_GET['before']) : '';
    $after_date = isset($_GET['after']) ? sanitize_text_field($_GET['after']) : '';

    $date_format = 'yy-mm-dd';


?>
    <div class="date-range">
        <a class="<?php echo ($date_range == 'year') ? 'active' : ''; ?>" href="<?php echo admin_url(); ?>edit.php?post_type=job&page=job_bm_stats&tab=<?php echo $tab; ?>&date_range=year"><?php echo __('Year', 'job-board-manager'); ?></a>
        <a class="<?php echo ($date_range == 'last_month') ? 'active' : ''; ?>" href="<?php echo admin_url(); ?>edit.php?post_type=job&page=job_bm_stats&tab=<?php echo $tab; ?>&date_range=last_month"><?php echo __('Last Month', 'job-board-manager'); ?></a>
        <a class="<?php echo ($date_range == 'this_month') ? 'active' : ''; ?>" href="<?php echo admin_url(); ?>edit.php?post_type=job&page=job_bm_stats&tab=<?php echo $tab; ?>&date_range=this_month"><?php echo __('This Month', 'job-board-manager'); ?></a>
        <a class="<?php echo ($date_range == 'last_30_day') ? 'active' : ''; ?>" href="<?php echo admin_url(); ?>edit.php?post_type=job&page=job_bm_stats&tab=<?php echo $tab; ?>&date_range=last_30_day"><?php echo __('Last 30 Days', 'job-board-manager'); ?></a>
        <a class=" <?php echo ($date_range == '7_day') ? 'active' : ''; ?>" href="<?php echo admin_url(); ?>edit.php?post_type=job&page=job_bm_stats&tab=<?php echo $tab; ?>&date_range=7_day"><?php echo __('Last 7 Days', 'job-board-manager'); ?></a>

        <form class="date-range-custom <?php echo ($date_range == 'custom') ? 'active' : ''; ?>" style="display:inline-block" method="GET" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
            <label><?php echo __('Custom:', 'job-board-manager'); ?></label>
            <input size="8" title="<?php echo __('Start date', 'job-board-manager'); ?>" type="text" class="job_bm_date" autocomplete="off" name="after" value="<?php echo $after_date; ?>" placeholder="<?php echo date('Y-m-d'); ?>" />
            <input size="8" title="<?php echo __('End date', 'job-board-manager'); ?>" type="text" class="job_bm_date" autocomplete="off" name="before" value="<?php echo $before_date; ?>" placeholder="<?php echo date('Y-m-d'); ?>" />
            <input type="hidden" name="post_type" value="job" />
            <input type="hidden" name="page" value="job_bm_stats" />
            <input type="hidden" name="tab" value="<?php echo esc_attr($tab); ?>" />
            <input type="hidden" name="date_range" value="custom" />
            <input class="button" value="<?php echo __('Submit', 'job-board-manager'); ?>" type="submit">
        </form>


    </div>

    <script>
        jQuery(document).ready(function($) {
            $(".job_bm_date").datepicker({
                dateFormat: "<?php echo $date_format; ?>"
            });
        });
    </script>

<?php


}

add_action('job_bm_stats_tabs_content_job_posting', 'job_bm_stats_tabs_content_job_posting_chart');

function job_bm_stats_tabs_content_job_posting_chart($tab)
{

    $data = array();
    $date_range = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '7_day';

    if ($date_range == '7_day') {

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= 7; $i++) {

            $day = date("d", strtotime($i . " days ago"));
            $month = date("m", strtotime($i . " days ago"));
            $year = date("Y", strtotime($i . " days ago"));

            $month_name = date("M", strtotime($i . " days ago"));

            //var_dump($month_name);

            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $day . '-' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";

        //echo '<pre>'.var_export($data['labels'], true).'</pre>';

        //echo '<pre>'.var_export($data['data'], true).'</pre>';

    } elseif ($date_range == 'last_30_day') {


        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= 30; $i++) {

            $day = date("d", strtotime($i . " days ago"));
            $month = date("m", strtotime($i . " days ago"));
            $year = date("Y", strtotime($i . " days ago"));

            $month_name = date("M", strtotime($i . " days ago"));

            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';

            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'this_month') {

        $total_day = date('t');
        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $total_day; $i++) {

            $day = $i;
            $month = date("m");
            $year = date("Y");

            $month_name = date("M");


            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'last_month') {

        $total_day = date("t", strtotime("1 month ago"));

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $total_day; $i++) {

            $day = $i;
            $month = date("m", strtotime("1 month ago"));
            $year = date("Y");

            $month_name = date("M", strtotime("1 month ago"));


            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';

            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'year') {

        $current_month =  date("m");

        //var_dump(date("M", strtotime("1 month ago")));

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $current_month; $i++) {


            $month = $i;
            $year = date("Y");
            //$monthNum  = 3;
            $month_name = date('M', mktime(0, 0, 0, $month)); // March

            //$month_name = date("M");







            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        //'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $month . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';

            $data['data'] .= "$wp_query->found_posts,";

            wp_reset_query();
            //var_dump();

        }


        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'custom') {

        $data['labels'] = "[";
        $data['data'] = "[";


        $after_date = isset($_GET['after']) ? sanitize_text_field($_GET['after']) : '';
        $after = explode('-', $after_date);

        $after_y = isset($after[0]) ? $after[0] : '';
        $after_m = isset($after[1]) ? $after[1] : '';
        $after_d = isset($after[2]) ? $after[2] : '';


        $before_date = isset($_GET['before']) ? sanitize_text_field($_GET['before']) : '';
        $before = explode('-', $before_date);
        $before_y = isset($before[0]) ? $before[0] : '';
        $before_m = isset($before[1]) ? $before[1] : '';
        $before_d = isset($before[2]) ? $before[2] : '';



        $start    = new DateTime($after_date);
        $start->modify('first day of this month');

        $end      = new DateTime($before_date);
        $end->modify('first day of next month');

        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        //var_dump($interval);



        $i = 0;
        foreach ($period as $dt) {

            $all_month_year[$i] = array($dt->format("Y"), $dt->format("m"));

            //echo $dt->format("Y-m") . "<br>\n";
            $i++;
        }



        //var_dump($interval);

        //var_dump(date("M", strtotime("1 month ago")));


        foreach ($all_month_year as $month_year) {

            $year = $month_year[0];
            $month = $month_year[1];

            $month_name = date('M', mktime(0, 0, 0, $month)); // March


            $args = array(
                'post_type' => 'job',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        //'day'   => $day,
                    )
                ),
            );

            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $month_name . '/' . $year;

            $data['labels'] .= '"' . $label . '",';

            $data['data'] .= "$wp_query->found_posts,";
        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    }
















?>

    <canvas id="job-posting-chart" style="width: 100%; height: 600px !important;"></canvas>

    <script>
        jQuery(document).ready(function($) {

            var ctx = document.getElementById('job-posting-chart');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo $data['labels']; ?>,
                    datasets: [{
                        label: 'job post',
                        data: <?php echo $data['data']; ?>,
                        backgroundColor: 'rgba(0, 115, 169, 0.1)',
                        borderColor: 'rgba(0, 115, 169, 0.8)',
                        pointHoverBackgroundColor: 'rgba(0, 115, 169, 0.1)',
                        pointBorderWidth: 1,
                        pointHitRadius: 5,
                        pointHoverBorderWidth: 15,

                    }]
                },
            });

        })
    </script>

<?php


}


add_action('job_bm_stats_tabs_content_application', 'job_bm_stats_tabs_content_application_chart');

function job_bm_stats_tabs_content_application_chart($tab)
{

    $data = array();
    $date_range = isset($_GET['date_range']) ? sanitize_text_field($_GET['date_range']) : '7_day';

    if ($date_range == '7_day') {

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= 7; $i++) {

            $day = date("d", strtotime($i . " days ago"));
            $month = date("m", strtotime($i . " days ago"));
            $year = date("Y", strtotime($i . " days ago"));

            $month_name = date("M", strtotime($i . " days ago"));

            //var_dump($month_name);

            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $day . '-' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";

        //echo '<pre>'.var_export($data['labels'], true).'</pre>';

        //echo '<pre>'.var_export($data['data'], true).'</pre>';

    } elseif ($date_range == 'last_30_day') {


        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= 30; $i++) {

            $day = date("d", strtotime($i . " days ago"));
            $month = date("m", strtotime($i . " days ago"));
            $year = date("Y", strtotime($i . " days ago"));

            $month_name = date("M", strtotime($i . " days ago"));

            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';

            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'this_month') {

        $total_day = date('t');
        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $total_day; $i++) {

            $day = $i;
            $month = date("m");
            $year = date("Y");

            $month_name = date("M");


            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'last_month') {

        $total_day = date("t", strtotime("1 month ago"));

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $total_day; $i++) {

            $day = $i;
            $month = date("m", strtotime("1 month ago"));
            $year = date("Y");

            $month_name = date("M", strtotime("1 month ago"));


            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';

            $label =  $day . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';
            $data['data'] .= "$wp_query->found_posts,";


            //$html.=  '{ label: "'.$day.'/'.$month.'", y: '.$wp_query->found_posts.' },';

            //var_dump();

        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'year') {

        $current_month =  date("m");

        //var_dump(date("M", strtotime("1 month ago")));

        $data['labels'] = "[";
        $data['data'] = "[";

        for ($i = 1; $i <= $current_month; $i++) {


            $month = $i;
            $year = date("Y");
            //$monthNum  = 3;
            $month_name = date('M', mktime(0, 0, 0, $month)); // March

            //$month_name = date("M");







            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        //'day'   => $day,
                    ),
                ),
            );
            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $month . '/' . $month_name;

            $data['labels'] .= '"' . $label . '",';

            $data['data'] .= "$wp_query->found_posts,";

            wp_reset_query();
            //var_dump();

        }


        $data['labels'] .= "]";
        $data['data'] .= "]";
    } elseif ($date_range == 'custom') {

        $data['labels'] = "[";
        $data['data'] = "[";


        $after_date = isset($_GET['after']) ? sanitize_text_field($_GET['after']) : '';
        $after = explode('-', $after_date);

        $after_y = isset($after[0]) ? $after[0] : '';
        $after_m = isset($after[1]) ? $after[1] : '';
        $after_d = isset($after[2]) ? $after[2] : '';


        $before_date = isset($_GET['before']) ? sanitize_text_field($_GET['before']) : '';
        $before = explode('-', $before_date);
        $before_y = isset($before[0]) ? $before[0] : '';
        $before_m = isset($before[1]) ? $before[1] : '';
        $before_d = isset($before[2]) ? $before[2] : '';



        $start    = new DateTime($after_date);
        $start->modify('first day of this month');

        $end      = new DateTime($before_date);
        $end->modify('first day of next month');

        $interval = DateInterval::createFromDateString('1 month');
        $period   = new DatePeriod($start, $interval, $end);

        //var_dump($interval);



        $i = 0;
        foreach ($period as $dt) {

            $all_month_year[$i] = array($dt->format("Y"), $dt->format("m"));

            //echo $dt->format("Y-m") . "<br>\n";
            $i++;
        }



        //var_dump($interval);

        //var_dump(date("M", strtotime("1 month ago")));


        foreach ($all_month_year as $month_year) {

            $year = $month_year[0];
            $month = $month_year[1];

            $month_name = date('M', mktime(0, 0, 0, $month)); // March


            $args = array(
                'post_type' => 'application',
                'post_status' => 'publish',
                'posts_per_page' => -1,
                'date_query' => array(
                    array(
                        'year'  => $year,
                        'month' => $month,
                        //'day'   => $day,
                    )
                ),
            );

            $wp_query = new WP_Query($args);
            //echo '{ x: '.($i+1).' , y: '.$wp_query->post_count.' },';


            $label =  $month_name . '/' . $year;

            $data['labels'] .= '"' . $label . '",';

            $data['data'] .= "$wp_query->found_posts,";
        }

        $data['labels'] .= "]";
        $data['data'] .= "]";
    }
















?>

    <canvas id="application-chart" style="width: 100%; height: 600px !important;"></canvas>

    <script>
        jQuery(document).ready(function($) {

            var ctx = document.getElementById('application-chart');
            var myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: <?php echo $data['labels']; ?>,
                    datasets: [{
                        label: 'job post',
                        data: <?php echo $data['data']; ?>,
                        backgroundColor: 'rgba(0, 115, 169, 0.1)',
                        borderColor: 'rgba(0, 115, 169, 0.8)',
                        pointHoverBackgroundColor: 'rgba(0, 115, 169, 0.1)',
                        pointBorderWidth: 1,
                        pointHitRadius: 5,
                        pointHoverBorderWidth: 15,

                    }]
                },
            });

        })
    </script>

<?php


}
