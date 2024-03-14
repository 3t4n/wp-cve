<?php $employee_id = isset( $_GET['id'] ) ? intval(sanitize_text_field( $_GET['id'] ) ) : null; ?>

<div class="info-form-wrap">
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Date', 'wphr' ),
            'name'     => 'date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.work.location }}">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Location', 'wphr' ),
            'name'     => 'location',
            'value'    => '',
            'type'    => 'select',
            'options'  => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_company_get_location_dropdown_raw()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.work.department }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Department', 'wphr' ),
            'name'    => 'department',
            'value'   => '',
            'type'    => 'select',
            'options' => wphr_hr_get_departments_dropdown_raw()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.work.designation }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Role', 'wphr' ),
            'name'    => 'designation',
            'value'   => '',
            'type'    => 'select',
            'options' => wphr_hr_get_designation_dropdown_raw()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.work.reporting_to }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Reporting To', 'wphr' ),
            'name'    => 'reporting_to',
            'value'   => '',
            'type'    => 'select',
            'options' => wphr_hr_get_employees_dropdown_raw( $employee_id )
        ) ); ?>
    </div>
    <div class="row">
<ul><?php do_action('wphr-hr-employee-job-information');?></ul>
</div>
    <?php wp_nonce_field( 'employee_update_jobinfo' ); ?>
    <input type="hidden" name="action" id="status-action" value="wphr-hr-emp-update-jobinfo">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
</div>
