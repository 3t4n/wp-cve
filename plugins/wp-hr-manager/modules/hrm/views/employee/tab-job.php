<div class="job-tab-wrap">

    <?php $history = $employee->get_history(); 
    

$fields = get_option( 'wphr-employee-fields');
$jobes = array();
$jobes1 = array();
$jobes2 = array();

if(!empty($fields))
{
foreach ($fields as $key => $field)
 {
   if($field['tab']=='job' && $field['section']=='jstatus')
    {
   array_push($jobes, $field);
   }
   elseif ($field['tab']=='job' && $field['section']=='jcompensation')
    {
    array_push($jobes1, $field);
       # code...
   } 

   elseif ($field['tab']=='job' && $field['section']=='jinformation')
    {

    array_push($jobes2, $field);
   }
    
}
}


    ?>

    <?php
    if ( current_user_can( 'wphr_manage_jobinfo' ) ) {
        ?>
        <h3><?php _e( 'Employee Main Status', 'wphr' ); ?></h3>

        <form action="" method="post">
            <?php wphr_html_form_input( array(
                'label'   => __( 'Employee Status : ', 'wphr' ),
                'name'    => 'employee_status',
                'value'   => $employee->wphr->status,
                'class'   => 'select2',
                'type'    => 'select',
                'id'      => 'wphr-hr-employee-status-option',
                'custom_attr' => [ 'data-selected' => $employee->wphr->status ],
                'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_hr_get_employee_statuses()
            ) ); ?>

            <input type="hidden" name="user_id" id="wphr-employee-id" value="<?php echo $employee->id; ?>">
            <input type="hidden" name="action" id="wphr-employee-status-action" value="wphr-hr-employee-status1">
            <?php wp_nonce_field( 'wp-wphr-hr-employee-update-nonce' ); ?>
            <input type="submit" class="button" data-title="<?php _e( 'Terminate Employee', 'wphr' ); ?>" id="wphr-hr-employee-status-update" name="employee_update_status" value="<?php esc_attr_e( 'Update', 'wphr' ); ?>">
        </form>
        <?php
    }
    ?>

    <h3><?php _e( 'Employment Status', 'wphr' ) ?></h3>
    <?php if ( current_user_can( 'wphr_manage_jobinfo' ) ) { ?>
        <a href="#" id="wphr-empl-status" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-status" data-title="<?php _e( 'Employment Status', 'wphr' ); ?>"><?php _e( 'Update Status', 'wphr' ); ?></a>
    <?php } ?>
    <div class="inside">
   <table class="widefat">
        <thead>
            <tr>
                <th><?php _e( 'Date', 'wphr' ) ?></th>
                <th><?php _e( 'Employment Status', 'wphr' ) ?></th>
                <th><?php _e( 'Comment', 'wphr' ) ?></th>
<?php 

foreach ($jobes as $key => $value) {
    # code...
    ?>
<th><?php echo $value['label']; ?></th>
    <?php
}

?>

                <th class="action">&nbsp;</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ( $history['employment'] ) {
                $types = wphr_hr_get_employee_types() + ['terminated' => __( 'Terminated', 'wphr' ) ];
                
                foreach ($history['employment'] as $num => $row) {
                    ?>
                    <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                        <td><?php echo wphr_format_date( $row->date ); ?></td>
                        <td>
                            <?php if ( ! empty( $row->type ) && array_key_exists( $row->type, $types ) ) {
                                echo $types[ $row->type ];
                            } ?>
                        </td>
                        <td><?php echo ( ! empty( $row->comment ) ) ? wp_kses_post( $row->comment ) : '--'; ?></td>
<?php 
foreach ($jobes as $keyr => $valuex) {
    # code...
    ?>
<td>
<?php

$additional = null;
if($row->additional)
{
 $additional = unserialize($row->additional);  
}
if($additional) {
    foreach ($additional as $kxey => $value) {
       if($kxey==$valuex['name']){
        echo $value;
       }
    }
}
?>

</td>
    <?php
}

?>                      <td class="action">
                      
                            <?php if ( current_user_can( 'wphr_manage_jobinfo', $employee->id ) ) : ?>
                                <a href="#" class="remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                            <?php endif; ?>
                        </td>



                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="alternate">
                    <td colspan="4"><?php _e( 'No history found!', 'wphr' ); ?></td>
                </tr>
            <?php } ?>

        </tbody>
        <tbody><tr><td><ul>







            <?php //do_action( 'wphr-hr-employee-single-leave-status', $employee ); ?></ul></td></tr></tbody>
    </table>
    </div>
    <hr />

    <?php if ( current_user_can( 'wphr_edit_employee', $employee->id ) ) : ?>

        <h3><?php _e( 'Compensation', 'wphr' ) ?></h3>
        <?php if ( current_user_can( 'wphr_manage_jobinfo' ) ) { ?>
            <a href="#" id="wphr-empl-compensation" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-compensation" data-title="<?php _e( 'Update Compensation', 'wphr' ); ?>"><?php _e( 'Update Compensation', 'wphr' ); ?></a>
        <?php } ?>
<div class="inside">
        <table class="widefat">
            <thead>
                <tr>
                    <th><?php _e( 'Date', 'wphr' ) ?></th>
                    <th><?php _e( 'Pay Rate', 'wphr' ) ?></th>
                    <th><?php _e( 'Pay Type', 'wphr' ) ?></th>
                    <th><?php _e( 'Change Reason', 'wphr' ) ?></th>
                    <th><?php _e( 'Comment', 'wphr' ) ?></th>
                    <?php 
foreach ($jobes1 as $key => $value) {
    # code...
    ?>
<th><?php echo $value['label']; ?></th>
    <?php
}

?>
                   
                    <th class="action">&nbsp;</th>
                    

                </tr>
            </thead>
            <tbody>
                <?php
                if ( $history['compensation'] ) {
                    $types = wphr_hr_get_pay_type();

                    foreach ($history['compensation'] as $num => $row) {
                        ?>
                        <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                            <td><?php echo wphr_format_date( $row->date ); ?></td>
                            <td><?php echo $row->type; ?></td>
                            <td>
                                <?php if ( ! empty( $row->category ) && array_key_exists( $row->category, $types ) ) {
                                    echo $types[ $row->category ];
                                } ?>
                            </td>
                            <td><?php echo ( ! empty( $row->data ) ) ? $row->data : '--'; ?></td>
                            <td><?php echo ( ! empty( $row->comment ) ) ? wp_kses_post( $row->comment ) : '--'; ?></td>


<?php 
foreach ($jobes1 as $keyr => $valuex) {
    # code...
    ?>
<td>
<?php

$additional = null;
if($row->additional)
{
 $additional = unserialize($row->additional);  
}

if($additional) {
    foreach ($additional as $kxey => $value) {
       if($kxey==$valuex['name']){
        echo $value;
       }
    }
}
?>

</td>
    <?php
}

?>    


                            <td class="action">
                                <?php if ( current_user_can( 'wphr_manage_jobinfo', $employee->id ) ) : ?>
                                    <a href="#" class="remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr class="alternate">
                        <td colspan="6"><?php _e( 'No history found!', 'wphr' ); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
            <tbody><tr><td><ul><?php //do_action( 'wphr-hr-employee-single-leave-jcompensation', $employee ); ?></ul></td></tr></tbody>
        </table>
</div>
        <hr />

    <?php endif; ?>

    <h3><?php _e( 'Job Information', 'wphr' ) ?></h3>
    <?php if ( current_user_can( 'wphr_manage_jobinfo' ) ) { ?>
        <a href="#" id="wphr-empl-jobinfo" class="action button" data-id="<?php echo $employee->id; ?>" data-template="wphr-employment-jobinfo" data-title="<?php _e( 'Update Job Information', 'wphr' ); ?>"><?php _e( 'Update Job Information', 'wphr' ); ?></a>
    <?php } ?>
    <div class="inside">
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e( 'Date', 'wphr' ) ?></th>
                <th><?php _e( 'Location', 'wphr' ) ?></th>
                <th><?php _e( 'Department', 'wphr' ) ?></th>
                <th><?php _e( 'Role', 'wphr' ) ?></th>
                <th><?php _e( 'Reports To', 'wphr' ) ?></th>
                <?php 
foreach ($jobes2 as $key => $value) {
    # code...
    ?>
<th><?php echo $value['label']; ?></th>
    <?php
}

?>
                <th class="action">&nbsp;</th>
            </tr>
        </thead>
        <tbody>

            <?php
            if ( $history['job'] ) {
                $types = wphr_hr_get_pay_type();

                foreach ($history['job'] as $num => $row) {
                    ?>
                    <tr class="<?php echo $num % 2 == 0 ? 'alternate' : 'odd'; ?>">
                        <td><?php echo wphr_format_date( $row->date ); ?></td>
                        <td>
                            <?php echo ( ! empty( $row->type ) ) ? $row->type : wphr_get_company_default_location_name(); ?>
                        </td>
                        <td>
                            <?php echo ( ! empty( $row->category ) ) ? $row->category : '--'; ?>
                        </td>
                        <td>
                            <?php echo ( ! empty( $row->comment ) ) ? $row->comment : '--'; ?>
                        </td>

                        <td>
                            <?php if ( ! empty( $row->data ) ) {
                                $emp = new \WPHR\HR_MANAGER\HRM\Employee( intval( $row->data ) );
                                if ( $emp->id ) {
                                    echo $emp->get_link();
                                }
                            } ?>
                        </td><?php 
foreach ($jobes2 as $keyr => $valuex) {
    # code...
    ?>
<td>
<?php
$additional = null;
if($row->additional){
 $additional = unserialize($row->additional);  
}
if($additional) {
    foreach ($additional as $kxey => $value) {
       if($kxey==$valuex['name']){
        echo $value;
       }
    }
}
?>

</td>
    <?php
}

?> 
  
                        <td class="action">
                            <?php if ( current_user_can( 'wphr_manage_jobinfo', $employee->id ) ) : ?>
                                <a href="#" class="remove" data-id="<?php echo $row->id; ?>"><span class="dashicons dashicons-trash"></span></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php
                }
            } else {
                ?>
                <tr class="alternate">
                    <td colspan="6"><?php _e( 'No history found!', 'wphr' ); ?></td>
                </tr>
            <?php } ?>
        </tbody>
        <tbody><tr><td><ul><?php //do_action( 'wphr-hr-employee-single-leave-jinformation', $employee ); ?></ul></td></tr></tbody>

        
    </table>
    </div>
</div>
