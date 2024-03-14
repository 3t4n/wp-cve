<?php
    $time_slot = get_office_timing();
?>
<# if ( data.days ) { #>
    <div class="table-wrap">
        <table class="list-days">
		<# if ( data.leave_count == 0 ) { #>
            <tr>
                <td></td>
                <td><input type="checkbox" value="" class="hourly_leave_handler"> <?php _e( 'Hourly Leave', 'wphr' ); ?></td>
            </tr>
		<# } #>	
        <# _.each( data.days, function(day, index) { #>
            <tr>
                <td>{{ day.date }}</td> 
                <td><input type="text" value="{{ day.count }}" readonly="readonly" size="1"> <?php _e( 'day', 'wphr' ); ?>
                <# if ( day.count == 1 ) { #>
                    
                    <div class="time-slot" style="display: none;">
                        <div>
                            <?php wphr_html_form_input( array(
                                'label'    => __( 'From', 'wphr' ),
                                'name'     => "day_wise_from_times[ {{ day.date }} ]",
                                'id'       => 'from-time',
                                'value'    => '',
                                'class'    => 'time-slot',
                                'type'     => 'select',
                                'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                            ) ); ?>
                        </div>
                        <div>
                            <?php wphr_html_form_input( array(
                                'label'    => __( 'To', 'wphr' ),
                                'name'     => "day_wise_to_times[ {{ day.date }} ]",
                                'id'       => 'to-time',
                                'value'    => '',
                                'class'    => 'time-slot',
                                'type'     => 'select',
                                'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                            ) ); ?>
                        </div>
                    </div>
                <# } #>
                </td>
            </tr>
        <# }) #>
        </table>

        <div class="total"><?php _e( 'Total: ', 'wphr' ); ?> {{ data.total }}</div>
    </div>
<# } #>