<?php $employee_id = isset( $_GET['id'] ) ? intval(sanitize_text_field( $_GET['id'] ) ) : null; ?>

<div class="performance-form-wrap">
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Review Date', 'wphr' ),
            'name'     => 'performance_date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Reporting To', 'wphr' ),
            'name'    => 'reporting_to',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_reporting_to',
            'options' => wphr_hr_get_employees_dropdown_raw( $employee_id )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Job Knowledge', 'wphr' ),
            'name'    => 'job_knowledge',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_job_knowledge',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_performance_rating()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Work Quality', 'wphr' ),
            'name'    => 'work_quality',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_work_quality',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_performance_rating()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Attendence/Punctuality', 'wphr' ),
            'name'    => 'attendance',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_attendance',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_performance_rating()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Communication/Listening', 'wphr' ),
            'name'    => 'communication',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_communication',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_performance_rating()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Dependablity', 'wphr' ),
            'name'    => 'dependablity',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_dependablity',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_performance_rating()
        ) ); ?>
    </div>
    <div class="row">
   <ul> <?php do_action('wphr-hr-employee-performance-review');?></ul>
</div>

    <?php wp_nonce_field( 'employee_update_performance' ); ?>
    <input type="hidden" name="type" value="reviews">
    <input type="hidden" name="action" id="performance-reviews-action" value="wphr-hr-emp-update-performance-reviews">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
</div>
