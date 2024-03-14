<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
wp_enqueue_script('jquery-ui-datepicker');
wp_enqueue_style('majesticsupport-jquery-ui-css', MJTC_PLUGIN_URL . 'includes/css/jquery-ui-smoothness.css');
wp_enqueue_style('majesticsupport-status-graph', MJTC_PLUGIN_URL . 'includes/css/status_graph.css');
wp_enqueue_script('majesticsupport-google-charts', MJTC_PLUGIN_URL . 'includes/js/google-charts.js');
wp_register_script( 'majesticsupport-google-charts-handle', '' );
wp_enqueue_script( 'majesticsupport-google-charts-handle' );
$majesticsupport_js ="
    jQuery(document).ready(function ($) {
        google.load('visualization', '1', {packages:['corechart']});
        google.setOnLoadCallback(drawBarChart);
        function drawBarChart() {
            var data = google.visualization.arrayToDataTable([
                ['". esc_html(__('Status','majestic-support'))."', '". esc_html(__('Tickets By Status','majestic-support'))."', { role: 'style' }],". majesticsupport::$_data['bar_chart']."
            ]);
            var view = new google.visualization.DataView(data);
            view.setColumns([0, 1,
                { calc: 'stringify',
                    sourceColumn: 1,
                    type: 'string',
                    role: 'annotation' },
                2]);

            var options = {
            width: '95%',
            bar: {groupWidth: '95%'},
            legend: { position: 'none' },
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('bar_chart'));
        chart.draw(view, options);
    }

    google.setOnLoadCallback(drawStackChart);
    function drawStackChart() {
        var data = google.visualization.arrayToDataTable([
        ['". esc_html(__('Tickets','majestic-support'))."', '". esc_html(__('Direct','majestic-support'))."', '". esc_html(__('Email','majestic-support'))."', { role: 'annotation' } ],
        ".wp_kses(majesticsupport::$_data['stack_data'], MJTC_ALLOWED_TAGS)."
        ]);

        var view = new google.visualization.DataView(data);
        var options = {
            width: '95%',
            legend: { position: 'top', maxLines: 3 },
            bar: { groupWidth: '75%' },
            isStacked: true,
        };
        var chart = new google.visualization.ColumnChart(document.getElementById('stack_chart'));
        chart.draw(view, options);
    }

    google.setOnLoadCallback(drawPie3d1Chart);
    function drawPie3d1Chart() {
        var data = google.visualization.arrayToDataTable([
          ['". esc_html(__('Departments','majestic-support'))."', '". esc_html(__('Tickets By Department','majestic-support'))."'],
          ".wp_kses(majesticsupport::$_data['pie3d_chart1'], MJTC_ALLOWED_TAGS)."
        ]);

        var options = {
          title: '". esc_html(__('Ticket by departments','majestic-support'))."',
          chartArea :{width:450,height:350,top:80,left:80},
          is3D: true,
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart1'));
        chart.draw(data, options);
    }

    google.setOnLoadCallback(drawPie3d2Chart);
    function drawPie3d2Chart() {
        var data = google.visualization.arrayToDataTable([
          ['". esc_html(__('Priorities','majestic-support'))."', '". esc_html(__('Tickets By Priority','majestic-support'))."'],
          ".wp_kses(majesticsupport::$_data['pie3d_chart2'], MJTC_ALLOWED_TAGS)."
        ]);

        var options = {
            title: '". esc_html(__('Tickets By Priorities','majestic-support'))."',
            chartArea :{width:450,height:350,top:80,left:80},
            is3D: true,
            colors:". majesticsupport::$_data['priorityColorList']."
        };

        var chart = new google.visualization.PieChart(document.getElementById('pie3d_chart2'));
        chart.draw(data, options);
    }

    google.setOnLoadCallback(drawStackChartHorizontal);
    function drawStackChartHorizontal() {
        var data = google.visualization.arrayToDataTable([
            ".
            wp_kses(majesticsupport::$_data['stack_chart_horizontal']['title'], MJTC_ALLOWED_TAGS).",".
            wp_kses(majesticsupport::$_data['stack_chart_horizontal']['data'], MJTC_ALLOWED_TAGS)
            ."
        ]);

        var view = new google.visualization.DataView(data);

        var options = {
            chartArea: {width:'90%'},
            legend: { position: 'top', maxLines: 3 },
            bar: { groupWidth: '75%' },
            isStacked: true,
            colors:['#B82B2B','#621166','#2168A2','#159667'],
        };
        var chart = new google.visualization.AreaChart(document.getElementById('stack_chart_horizontal'));
        chart.draw(view, options);
    }";
    if(isset(majesticsupport::$_data['slice_chart'])) { 
      $majesticsupport_js .="
        google.setOnLoadCallback(drawSliceChart);
        function drawSliceChart() {
            var data = google.visualization.arrayToDataTable([
                ['". esc_html(__('Tickets','majestic-support'))."', '". esc_html(__('Agent Tickets','majestic-support'))."'],
                ".wp_kses(majesticsupport::$_data['slice_chart'], MJTC_ALLOWED_TAGS)."
            ]);

            var options = {};

            var chart = new google.visualization.BarChart(document.getElementById('slice_chart'));
            chart.draw(data, options);
        }";
    }
    $majesticsupport_js .="
  });

";
wp_add_inline_script('majesticsupport-google-charts-handle',$majesticsupport_js);
?>  
<?php MJTC_message::MJTC_getMessage(); ?>
<div id="msadmin-wrapper">
    <div id="msadmin-leftmenu">
        <?php  MJTC_includer::MJTC_getClassesInclude('msadminsidemenu'); ?>
    </div>
    <div id="msadmin-data">
        <?php MJTC_includer::MJTC_getModel('majesticsupport')->getPageTitle('overal_statistics'); ?>
        <div id="msadmin-data-wrp">
            <?php
            $open_percentage = 0;
            $close_percentage = 0;
            $overdue_percentage = 0;
            $answered_percentage = 0;
            $allticket_percentage = 0;
            if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
                $open_percentage = round((majesticsupport::$_data['ticket_total']['openticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                $close_percentage = round((majesticsupport::$_data['ticket_total']['closeticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                $overdue_percentage = round((majesticsupport::$_data['ticket_total']['overdueticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
                $answered_percentage = round((majesticsupport::$_data['ticket_total']['answeredticket'] / majesticsupport::$_data['ticket_total']['allticket']) * 100);
            }
            if(isset(majesticsupport::$_data['ticket_total']) && isset(majesticsupport::$_data['ticket_total']['allticket']) && majesticsupport::$_data['ticket_total']['allticket'] != 0){
                $allticket_percentage = 100;
            }
            ?>
            <div class="mjtc-support-count">
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link mjtc-support-green" href="#" data-tab-number="1" title="<?php echo esc_attr(__('Open Ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($open_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($open_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-open"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-open"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-green">
                            <?php
                                $data = esc_html(__('Open', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['openticket']).' )';
                                echo wp_kses($data, MJTC_ALLOWED_TAGS);

                            ?>
                        </div>
                    </a>
                </div>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link mjtc-support-brown" href="#" data-tab-number="2" title="<?php echo esc_attr(__('answered ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($answered_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($answered_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-answer"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-answer"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-brown">
                            <?php
                                $data = esc_html(__('Answered', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['answeredticket']).' )';
                                echo wp_kses($data, MJTC_ALLOWED_TAGS);
                            ?>
                        </div>
                    </a>
                </div>
                <?php if(in_array('overdue', majesticsupport::$_active_addons)){ ?>
                  <div class="mjtc-support-link">
                      <a class="mjtc-support-link mjtc-support-orange" href="#" data-tab-number="3" title="<?php echo esc_attr(__('overdue ticket','majestic-support')); ?>">
                          <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($overdue_percentage); ?>" >
                              <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($overdue_percentage); ?>">
                                  <div class="circle">
                                      <div class="mask full">
                                           <div class="fill mjtc-support-overdue"></div>
                                      </div>
                                      <div class="mask half">
                                          <div class="fill mjtc-support-overdue"></div>
                                          <div class="fill fix"></div>
                                      </div>
                                      <div class="shadow"></div>
                                  </div>
                                  <div class="inset">
                                  </div>
                              </div>
                          </div>
                          <div class="mjtc-support-link-text mjtc-support-orange">
                                <?php
                                    $data = esc_html(__('Overdue', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['overdueticket']).' )';
                                    echo wp_kses($data, MJTC_ALLOWED_TAGS);
                                ?>
                          </div>
                      </a>
                  </div>
                <?php } ?>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link mjtc-support-red" href="#" data-tab-number="4" title="<?php echo esc_attr(__('Close Ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($close_percentage); ?>" >
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($close_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-close"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-close"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-red">
                            <?php
                                $data = esc_html(__('Closed', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['closeticket']).' )';
                                echo wp_kses($data, MJTC_ALLOWED_TAGS);
                            ?>
                        </div>
                    </a>
                </div>
                <div class="mjtc-support-link">
                    <a class="mjtc-support-link mjtc-support-blue" href="#" data-tab-number="5" title="<?php echo esc_attr(__('all ticket','majestic-support')); ?>">
                        <div class="mjtc-support-cricle-wrp" data-per="<?php echo esc_attr($allticket_percentage); ?>">
                            <div class="mjtc-mr-rp" data-progress="<?php echo esc_attr($allticket_percentage); ?>">
                                <div class="circle">
                                    <div class="mask full">
                                         <div class="fill mjtc-support-allticket"></div>
                                    </div>
                                    <div class="mask half">
                                        <div class="fill mjtc-support-allticket"></div>
                                        <div class="fill fix"></div>
                                    </div>
                                    <div class="shadow"></div>
                                </div>
                                <div class="inset">
                                </div>
                            </div>
                        </div>
                        <div class="mjtc-support-link-text mjtc-support-blue">
                            <?php
                                $data = esc_html(__('All Tickets', 'majestic-support')).' ( '.esc_html(majesticsupport::$_data['ticket_total']['allticket']).' )';
                                echo wp_kses($data, MJTC_ALLOWED_TAGS);
                            ?>
                        </div>
                    </a>
                </div>
            </div>
            <div class="mjtc-admin-report">
                <div class="mjtc-admin-subtitle"><?php echo esc_html(__('Tickets By Status And Priorities','majestic-support')); ?></div>
                <div class="mjtc-admin-rep-graph" id="stack_chart_horizontal" style="float:left; height:400px;width:100%; "></div>
            </div>
            <div class="mjtc-admin-report halfwidth">
            	<div class="mjtc-admin-subtitle box1"><?php echo esc_html(__('Tickets By Departments','majestic-support')); ?></div>
            	<div class="mjtc-admin-rep-graph" id="pie3d_chart1" style="height:400px;width:100%;"></div>
            </div>
            <div class="mjtc-admin-report halfwidth">
            	<div class="mjtc-admin-subtitle box2"><?php echo esc_html(__('Tickets By Priorities','majestic-support')); ?></div>
            	<div class="mjtc-admin-rep-graph" id="pie3d_chart2" style="height:400px;width:100%;"></div>
            </div>
            <div class="mjtc-admin-report halfwidth">
            	<div class="mjtc-admin-subtitle box3"><?php echo esc_html(__('Tickets By Status','majestic-support')); ?></div>
            	<div class="mjtc-admin-rep-graph" id="bar_chart" style="height:400px;width:100%;"></div>
            </div>
            <div class="mjtc-admin-report halfwidth">
              <div class="mjtc-admin-subtitle box4"><?php echo esc_html(__('Tickets By Channel','majestic-support')); ?></div>
              <div class="mjtc-admin-rep-graph" id="stack_chart" style="height:400px;width:100%;"></div>
            </div>
            <?php if(in_array('agent', majesticsupport::$_active_addons)){ ?>
              <div class="mjtc-admin-report">
              	<div class="mjtc-admin-subtitle box4"><?php echo esc_html(__('Tickets By Agents','majestic-support')); ?></div>
              	<div class="mjtc-admin-rep-graph" id="slice_chart" style="height:400px;width:100%;"></div>
              </div>
            <?php } ?>
        </div>
  </div>
</div>
