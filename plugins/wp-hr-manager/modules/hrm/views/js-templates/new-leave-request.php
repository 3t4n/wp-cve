<?php
$employee_types = wphr_hr_get_assign_policy_from_entitlement( get_current_user_id() );
$types = $employee_types ? $employee_types : [];
$time_slot = get_office_timing();
//print_r($time_slot);
?>
<div class="wphr-hr-leave-request-new">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Leave Type', 'wphr' ),
            'name'     => 'leave_policy',
            'id'       => 'wphr-hr-leave-req-leave-policy',
            'value'    => '',
            'required' => true,
            'type'     => 'select',
            'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $types
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'From', 'wphr' ),
            'name'     => 'leave_from',
            'id'       => 'wphr-hr-leave-req-from-date',
            'value'    => '',
            'required' => true,
            'class'    => 'wphr-leave-date-field',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'To', 'wphr' ),
            'name'     => 'leave_to',
            'id'       => 'wphr-hr-leave-req-to-date',
            'value'    => '',
            'required' => true,
            'class'    => 'wphr-leave-date-field',
        ) ); ?>
    </div>

    <div class="row" style="display: none;">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Hourly Leave', 'wphr' ),
            'name'     => 'hourly_req',
            'id'       => 'wphr-hr-leave-houly-req',
            'value'    => '',
            'type'     => 'checkbox',
        ) ); ?>
    </div>
	
    <div class="row" style="display: none;">
        <?php wphr_html_form_input( array(
            'label'    => __( 'From (Time)', 'wphr' ),
            'name'     => 'from_time',
            'id'       => 'wphr-hr-leave-req-from-time',
            'value'    => '',
            'class'    => 'wphr-leave-time-slot',
            'type'     => 'select',
            'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
        ) ); ?>
    </div>

    <div class="row" style="display: none;">
        <?php wphr_html_form_input( array(
            'label'    => __( 'To (Time)', 'wphr' ),
            'name'     => 'to_time',
            'id'       => 'wphr-hr-leave-req-to-time',
            'value'    => '',
            'class'    => 'wphr-leave-time-slot',
            'type'     => 'select',
            'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
        ) ); ?>
    </div>

    <div class="wphr-hr-leave-req-show-days show-days" style="margin:20px 0px;"></div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Reason', 'wphr' ),
            'name'        => 'leave_reason',
            'type'        => 'textarea',
            'custom_attr' => array( 'cols' => 25, 'rows' => 3 )
        ) ); ?>
    </div>

    <input type="hidden" name="employee_id" id="wphr-hr-leave-req-employee-id" value="<?php echo get_current_user_id(); ?>">
    <input type="hidden" name="action" value="wphr-hr-leave-req-new">
    <?php wp_nonce_field( 'wphr-leave-req-new' ); ?>
</div>
