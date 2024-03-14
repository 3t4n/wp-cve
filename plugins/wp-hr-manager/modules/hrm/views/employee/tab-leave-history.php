<?php
if ( $requests ) {

    foreach ($requests as $num => $request) {
        ?>
        <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
            <td>
                <?php
                printf( '%s - %s', wphr_format_date( $request->start_date, 'd M' ), wphr_format_date( $request->end_date, 'd M' ) );
                ?>
            </td>
            <td><?php echo esc_html( $request->policy_name ); ?></td>
            <td><?php echo !empty( $request->reason ) ? esc_html( $request->reason ) : '-'; ?></td>
            <td><?php 
				$start_time = strtotime( $request->start_date );
				$end_time = strtotime( $request->end_date );
				$time_difference = $end_time - $start_time;
				$hours = date('G', $time_difference );
				$minutes = date('i', $time_difference );
				$start_date = date('Y-m-d', $start_time );
				$end_date = date('Y-m-d', $end_time );
				$days = date_diff( date_create($start_date), date_create($end_date) );

				$leave_days = $days->format("%a");
				$display_time = '';
				if( $leave_days == 0 ){
					if( $hours < 23 ){
						if( $hours > 0 ){
							$display_time .= $hours > 1 ? sprintf( __('%d Hours','hrm'), $hours ) : sprintf( __('%d Hour','hrm'), $hours );
						}
						if( $minutes > 0 ){
							$display_time .= ' '. sprintf( __('%d Minutes','hrm'), $minutes );
						}
						$days = $display_time;
					}else{
						$leave_days++;
						$days = $leave_days > 1 ? sprintf( __('%s Days','hrm'), $leave_days ) : sprintf( __('%d Day','hrm'), $leave_days );	
					}
					echo $days;
				}else{
					if( $request->days > 1 ){
						echo sprintf('%d days', number_format_i18n( $request->days ) );
					}else{
						echo sprintf('%d day', number_format_i18n( $request->days ) );
					}
				}
				?></td>
        </tr>

    <?php } ?>

<?php } else { ?>
    <tr class="alternate">
        <td colspan="4">
            <?php _e( 'No history found!', 'wphr' ); ?>
        </td>
    </tr>
<?php } ?>
