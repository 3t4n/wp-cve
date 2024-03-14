<div class="wrap">
    <h2><?php _e( 'Gender Profile', 'wphr' ); ?></h2>

    <?php
        $gender_all   = wphr_hr_get_gender_count();
        $departments  = wphr_hr_get_departments();
        $gender_ratio = wphr_hr_get_gender_ratio_data();
        $index        = 0;
        $yaxis        = [];
        $male         = [];
        $female       = [];
        $unspecified  = [];
        $gender_data  = [];

        foreach ( $departments as $department ) {

            array_push( $yaxis, [$index, $department->title]);

            $count_by_dept = wphr_hr_get_gender_count( $department->id );

            if ( $count_by_dept ) {

                array_push( $male, [$count_by_dept['male'], $index] );
                array_push( $female, [$count_by_dept['female'], $index] );
                array_push( $unspecified, [$count_by_dept['other'], $index] );
            }

            $gender_data[] = [
                'dept_name' => $department->title,
                'male'      => $count_by_dept['male'],
                'female'    => $count_by_dept['female'],
                'other'     => $count_by_dept['other']
            ];

            $index++;

        }
    ?>

    <div class="wphr-area-left" id="poststuff">

        <?php echo wphr_admin_dash_metabox( __( '<i class="fa fa-bar-chart"></i> Employee Gender Count', 'wphr' ), function() {
            ?>
            <div id="emp-gender-ratio" style="width:50%;height:400px;"></div>
            <?php
            } );
        ?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php _e( 'Gender', 'wphr' ); ?></th>
                    <th><?php _e( 'Count', 'wphr' ); ?></th>
                    <th><?php _e( 'Percentage', 'wphr' ); ?></th>
                </tr>
            </thead>
            <tbody>
            <?php
                foreach ( $gender_ratio as $gender ) {
                    echo '<tr>';
                    echo '<td>' . esc_attr( $gender->gender ) . '</td>';
                    echo '<td>' . esc_attr( $gender->count ) . '</td>';
                    echo '<td>' . esc_attr( $gender->percentage ) . '</td>';
                    echo '</tr>';
                }
            ?>
            </tbody>
        </table>

        <br>

        <?php
        echo wphr_admin_dash_metabox( __( '<i class="fa fa-bar-chart"></i> Employee Gender Ratio By Department', 'wphr' ), function() {
           ?>
            <div id="emp-gender-ratio-by-department" style="width:800px;height:400px;"></div>
           <?php
          } );
        ?>

        <table class="widefat striped">
            <thead>
                <tr>
                    <th><?php _e( 'Department', 'wphr' ); ?></th>
                    <th><?php _e( 'Male', 'wphr' ); ?></th>
                    <th><?php _e( 'Female', 'wphr' ); ?></th>
                    <th><?php _e( 'Unspecified', 'wphr' ); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                    foreach ( $gender_data as $single_data ) {

                        echo '<tr>';
                        echo '<td>' . esc_attr( $single_data['dept_name'] ) . '</td>';
                        echo '<td>' . esc_attr( $single_data['male'] ) . '</td>';
                        echo '<td>' . esc_attr( $single_data['female'] ) . '</td>';
                        echo '<td>' . esc_attr( $single_data['other'] ) . '</td>';
                        echo '</tr>';
                    }
                ?>
            </tbody>
        </table>

    </div>

    <script>
        ;
        (function($){

            var data_gender_all = [
                {label: 'Male', color:'#9dd12b', data: [[1,<?php echo esc_attr( $gender_all['male'] ); ?>]]},
                {label: 'Female', color:'#d4474a', data: [[2,<?php echo esc_attr( $gender_all['female'] ); ?>]]},
                {label: 'Unspecified', color:'#fc9255', data: [[3,<?php echo esc_attr( $gender_all['other'] ); ?>]]},
            ];

            $(document).ready( function(){

                $.plot($("#emp-gender-ratio"), data_gender_all, {

                    series: {
                        pie: {
                            show: true,
                            combine: {
                                color: '#999',
                                threshold: 0.1
                            }
                        }
                    },
                    legend: {
                        show: false
                    },
                });

                var data = [
                    {label: 'Male', color:'#648d9e', data: <?php echo json_encode( $male ); ?>},
                    {label: 'Female', color:'#D797AF', data: <?php echo json_encode( $female ); ?>},
                    {label: 'Unspecified', color:'#AAC6D4', data: <?php echo json_encode( $unspecified ); ?>},
                ];

                $.plot($("#emp-gender-ratio-by-department"), data, {

                    xaxis: {
                        tickSize: 5,
                        axisLabel: "Gender count",
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 14,
                        axisLabelFontFamily: 'Verdana, Arial',
                        axisLabelPadding: 10
                    },
                    series: {
                        stack: 1,
                        bars: {
                            show: true,
                            barWidth: 0.4,
                            fill:1,
                            lineWidth: 0,
                            min: 0,
                            horizontal: 1

                        }
                    },
                    bars: {
                        align: 'center'
                    },
                    yaxis: {
                        tickLength: 0,
                        ticks: <?php echo json_encode( $yaxis ); ?>,
                        axisLabel: "Departments",
                        axisLabelUseCanvas: true,
                        axisLabelFontSizePixels: 14,
                        axisLabelFontFamily: 'Verdana, Arial',
                        axisLabelPadding: 10,
                    },
                    grid: {
                        hoverable: true,
                        clickable: true,
                        borderColor: '#000',
                        borderWidth: {
                            left: 2,
                            bottom: 2,
                            right: 0,
                            top: 0,
                        },
                        backgroundColor: { colors: ["#ffffff", "#F9FBFC"] }
                    }
                });

                $("#emp-gender-ratio-by-department").useToolTip2();
            });

            var previousPoint = null, previousLabel = null;

            $.fn.useToolTip2 = function () {

                $(this).bind("plothover", function (event, pos, item) {
                    if (item) {
                        if ((previousLabel != item.series.label) || (previousPoint != item.dataIndex)) {
                            previousPoint = item.dataIndex;
                            previousLabel = item.series.label;
                            $("#tooltip").remove();

                            var x = item.datapoint[0];
                            var y = item.datapoint[0] - item.datapoint[2];

                            var color = item.series.color;

                            //console.log(item.series.xaxis.ticks[x].label);

                            showTooltip(item.pageX,
                            item.pageY,
                            color,
                            "<strong>" + item.series.yaxis.ticks[item.dataIndex].label + "</strong><br><?php _e( 'Category :', 'wphr' ); ?><strong>" + item.series.label + "</strong><br><?php _e( 'Employee :', 'wphr' ); ?><strong>" + y + "</strong>");
                        }
                    } else {
                        $("#tooltip").remove();
                        previousPoint = null;
                    }
                });
            };

            function showTooltip(x, y, color, contents) {
                $('<div id="tooltip">' + contents + '</div>').css({
                    position: 'absolute',
                    display: 'none',
                    top: y - 40,
                    left: x - 120,
                    border: '2px solid ' + color,
                    padding: '3px',
                    'font-size': '9px',
                    'border-radius': '5px',
                    'background-color': '#fff',
                    'font-family': 'Verdana, Arial, Helvetica, Tahoma, sans-serif',
                    opacity: 0.9
                }).appendTo("body").fadeIn(200);
            }

        })(jQuery);
    </script>
</div>
