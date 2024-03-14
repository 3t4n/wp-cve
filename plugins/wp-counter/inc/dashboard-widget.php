<?php
add_action('wp_dashboard_setup', 'l24bd_wpcounter_dashboard_widget');
function l24bd_wpcounter_dashboard_widget()
{
    wp_add_dashboard_widget(
        'l24bd_wpcounter_dashboard_widget',
        '<span class="dashicons dashicons-chart-area"></span> Visitor Status',
        'l24bd_wpcounter_dashboard_widget_display'
    );
}

function l24bd_wpcounter_dashboard_widget_display()
{ ?>
    <?php $data = l24bd_wpcounter_get_visitor_data(); ?>

    <table class="widefat striped">
        <tr>
            <td><span class="dashicons dashicons-calendar-alt"></span> Today (<?php echo getToday(); ?>)</td>
            <td><?php echo $data['today'] ?></td>
        </tr>
        <tr>
            <td><span class="dashicons dashicons-calendar-alt"></span> Yesterday (<?php echo getYesterday(); ?>)</td>
            <td><?php echo $data['yesterday'] ?></td>
        </tr>
        <tr>
            <td><span class="dashicons dashicons-calendar-alt"></span> This Week
                (<?php echo getLast('week', 'first') . ' - ' . getCurrent('week', 'last'); ?>)
            </td>
            <td><?php echo $data['thisWeek']; ?></td>
        </tr>
        <tr>
            <td><span class="dashicons dashicons-calendar-alt"></span> This Month
                (<?php echo getCurrent('month', 'first') . ' - ' . getCurrent('month', 'last'); ?>)
            </td>
            <td><?php echo $data['thisMonth'] ?></td>
        </tr>
        <tr>
            <td><span class="dashicons dashicons-calendar-alt"></span> Total Visitor</td>
            <td><?php echo $data['totalVisitor'] ?></td>
        </tr>
    </table>
    <?php
}

// visitor graph
add_action('wp_dashboard_setup', 'l24bd_wpcounter_dashboard_visitor_graph_widget');
function l24bd_wpcounter_dashboard_visitor_graph_widget()
{
    wp_add_dashboard_widget(
        'l24bd_wpcounter_dashboard_visitor_graph_widget',
        '<span class="dashicons dashicons-chart-area"></span> Last 7 day(s) status',
        'l24bd_wpcounter_dashboard_visitor_graph_widget_display'
    );
}

function l24bd_wpcounter_dashboard_visitor_graph_widget_display()
{ ?>
    <?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'l24bd_wpcounter_visitors';
    $sql = "SELECT visit_date,SUM(hits) AS total FROM $table_name GROUP BY visit_date ORDER BY visit_date DESC LIMIT 7";
    $vistorData = $wpdb->get_results($sql);
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
                 foreach($vistorData as $data)
                 {
                    echo "'".$data->visit_date."'".',';
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
                             foreach($vistorData as $data)
                             {
                                echo $data->total.',';
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
    <?php
}