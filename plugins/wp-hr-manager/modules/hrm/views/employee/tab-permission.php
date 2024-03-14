<div class="permission-tab-wrap">
    <h3><?php 
_e( 'Permission Management', 'wphr' );

//$leave=get_option('wphr-emp');
$fields = get_option( 'wphr-employee-fields');
$permissionarray = array();

if(!empty($fields))
{
foreach ($fields as $key => $field)
 {
   if($field['tab']=='permission' && $field['section']=='permission')
    {
   array_push($permissionarray, $field);
   }
}

}
?>
</h3>

    <form action="" class="permission-form wphr-form" method="post">

        <?php 
$is_manager = ( user_can( $employee->id, wphr_hr_get_manager_role() ) ? 'on' : 'off' );
$is_receive_mail_for_leaves = $is_manage_leave_of_employees = 'off';
$class = 'manager_services ';

$is_enable_profile_redirect = get_user_meta( $employee->id, 'enable_profile_redirect', true );
$is_enable_profile_redirect = $is_enable_profile_redirect ? 'on' : 'off';

if ( $is_manager == 'on' ) {
    $is_receive_mail_for_leaves = get_user_meta( $employee->id, 'receive_mail_for_leaves', true );
    $is_manage_leave_of_employees = get_user_meta( $employee->id, 'manage_leave_of_employees', true );
    $is_receive_mail_for_leaves = ( $is_receive_mail_for_leaves ? 'on' : 'off' );
    $is_manage_leave_of_employees = ( $is_manage_leave_of_employees ? 'on' : 'off' );
} else {
    $class .= 'wphr-hide';
}

wphr_html_form_input( array(
    'label' => __( 'HR Manager', 'wphr' ),
    'name'  => 'enable_manager',
    'type'  => 'checkbox',
    'tag'   => 'div',
    'value' => $is_manager,
    'help'  => __( 'This Employee is HR Manager', 'wphr' ),
) );
wphr_html_form_input( array(
    'label'         => __( 'Redirect to user profile page', 'wphr' ),
    'name'          => 'enable_profile_redirect',
    'type'          => 'checkbox',
    'tag'           => 'div',
    'value'         => $is_enable_profile_redirect,
    'help'          => __( 'If it is checked then this will redirect to profile page on login.', 'wphr' ),
) );
wphr_html_form_input( array(
    'label'         => __( 'Receive E-mail Notification', 'wphr' ),
    'name'          => 'receive_mail_for_leaves',
    'type'          => 'checkbox',
    'tag'           => 'div',
    'wrapper_class' => $class,
    'value'         => $is_receive_mail_for_leaves,
    'help'          => __( 'If it is checked then this HR manager will receive email notification when any employee post leave request.', 'wphr' ),
) );
wphr_html_form_input( array(
    'label'         => __( 'Manage Leave', 'wphr' ),
    'name'          => 'manage_leave_of_employees',
    'type'          => 'checkbox',
    'tag'           => 'div',
    'wrapper_class' => $class,
    'value'         => $is_manage_leave_of_employees,
    'help'          => __( 'If it is checked then this HR manager can able to view and manage leave requests of all employees', 'wphr' ),
) );
?>
<input type="hidden" name="current_emp_leave_year" value="<?php 
echo  $employee->wphr->leave_year ;
?>" />
<input type="hidden" name="current_emp_apply_leave_year" value="<?php 
echo  $employee->wphr->apply_leave_year ;
?>" />
<?php 
wphr_html_form_input( array(
    'label'    => __( 'Leave Year', 'wphr' ),
    'name'     => 'emp_leave_year',
    'type'     => 'select',
    'tag'      => 'div',
    'options'  => wphr_months_dropdown(),
    'value'    => $employee->wphr->leave_year,
    'required' => true,
) );
wphr_html_form_input( array(
    'label' => __( 'Apply This Leave Year?', 'wphr' ),
    'name'  => 'emp_apply_leave_year',
    'tag'   => 'div',
    'type'  => 'checkbox',
    'value' => $employee->wphr->apply_leave_year,
) );

foreach ($permissionarray as $key => $value)
 {

    ?>

    <label><?php echo $value['label']; ?>:</label>
    <?php
    # code...
    $name = 'additional['.$value['name'].']';
   // $name='additional['.$value['name'].']';
           wphr_html_form_input( array(
            'name'        => $name,
            'required'    => true,
            'placeholder' => __( 'Add a extra field data', 'wphr' ),
            'type'        => 'text',
            'custom_attr' => array( 'rows' => 2, 'cols' => 30 )
        ) ); 
        ?>
    </br>
        <?php
}?>
<h3>Extra Field data</h3>
<?php
    $additional = unserialize( get_user_meta( $employee->id, 'additional', true ));
   // print_r($additional);
foreach ($permissionarray as $key1 => $value1)
 {

    ?>
    
    <label><?php echo $value1['label']; ?>:</label>
<?php }
if($additional)
{
  foreach ($additional as $key2 => $value2) 
  {?>
   <label> <?php
    echo $value2;
    ?>
    </label>
    <?php
    # code...
}  
}

?>



        <?php 
do_action( 'wphr_hr_permission_management', $employee );
?>

        <input type="hidden" name="employee_id" value="<?php 
echo  $employee->id ;
?>
">
        <input type="hidden" name="wphr-action" id="wphr-employee-action" value="wphr-hr-employee-permission">
        
        <?php 
wp_nonce_field( 'wp-wphr-hr-employee-permission-nonce' );
?>
        <?php 
submit_button( __( 'Update Permission', 'wphr' ), 'primary' );

?>

    </form>

</div>
