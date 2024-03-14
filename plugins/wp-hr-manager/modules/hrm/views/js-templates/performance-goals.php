<?php $employee_id = isset( $_GET['id'] ) ? sanitize_text_field( intval( $_GET['id'] ) ) : null; ?>

<div class="performance-form-wrap">
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Set Date', 'wphr' ),
            'name'     => 'performance_date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Completion Date', 'wphr' ),
            'name'     => 'completion_date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field',
            'id'       => 'performance_completion_date'
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Goal Description', 'wphr' ),
            'name'    => 'goal_description',
            'value'   => '',
            'type'    => 'textarea',
            'id'      => 'performance_goal_description',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Employee Assessment', 'wphr' ),
            'name'    => 'employee_assessment',
            'value'   => '',
            'type'    => 'textarea',
            'id'      => 'performance_employee_assessment',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Supervisor', 'wphr' ),
            'name'    => 'supervisor',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_supervisor',
            'options' => wphr_hr_get_employees_dropdown_raw( $employee_id )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Supervisor Assessment', 'wphr' ),
            'name'    => 'supervisor_assessment',
            'value'   => '',
            'type'    => 'textarea',
            'id'      => 'performance_supervisor_assessment',
        ) ); ?>
    </div>
    <div class="row">
        <ul>  <?php do_action( 'wphr-hr-employee-performance-goal' ); ?>
         </ul>
    </div>
    <?php wp_nonce_field( 'employee_update_performance' ); ?>
    <input type="hidden" name="type" value="goals">
    <input type="hidden" name="action" id="performance-goals-action" value="wphr-hr-emp-update-performance-goals">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
</div>
