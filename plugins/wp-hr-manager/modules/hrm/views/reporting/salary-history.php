<?php
global $wpdb;

$all_user_id = $wpdb->get_col( "SELECT user_id FROM {$wpdb->prefix}wphr_hr_employees" );
$date_format = get_option( 'date_format' );
?>
<div class="wrap">
    <h1><?php _e( 'Salary History', 'wphr' ); ?></h1>

    <table class="widefat striped" style="margin-top: 20px;">
        <thead>
            <tr>
                <th><?php _e( 'Employee', 'wphr' ); ?></th>
                <th><?php _e( 'Date', 'wphr' ); ?></th>
                <th><?php _e( 'Pay Rate', 'wphr' ); ?></th>
                <th><?php _e( 'Pay type', 'wphr' ); ?></th>
                <th><?php _e( 'Employee ID', 'wphr' ); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( $all_user_id ) {
                foreach ( $all_user_id as $user_id ) {

                    $employee      = new \WPHR\HR_MANAGER\HRM\Employee( intval( $user_id ) );
                    $compensations = $employee->get_history( 'compensation' );

                    if ( $compensations ) {

                        $line = 0;

                        foreach ( $compensations as $compensation ) {

                            $employee_url = '<a href="'. admin_url( 'admin.php?page=wphr-hr-employee&action=view&id=' . $employee->id ) . '">' . $employee->display_name . '</a>';
                            echo '<tr>';
                            echo '<td>' . ( 0 == $line ? wp_kses_post( $employee_url ) : '' ) . '</td>';
                            echo '<td>' . date( $date_format, strtotime( esc_attr( $compensation->date ) ) ) . '</td>';
                            echo '<td>' . esc_attr( $compensation->type ) . '</td>';
                            echo '<td>' . esc_attr( $compensation->category ) . '</td>';
                            echo '<td>' . esc_attr( $employee->id ) . '</td>';
                            echo '</tr>';

                            $line++;
                        }
                    }
                }
            } else {
                echo '<tr><td colspan="5">' . __( 'No employee found!', 'wphr' ) . '</td></tr>';
            }
            ?>
        </tbody>
    </table>
</div>



