<?php $employee_id = isset( $_GET['id'] ) ? intval(sanitize_text_field( $_GET['id'] ) ) : null; ?>

<div class="performance-form-wrap">
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Reference Date', 'wphr' ),
            'name'     => 'performance_date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Reviewer', 'wphr' ),
            'name'    => 'reviewer',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'id'      => 'performance_reviewer',
            'options' => wphr_hr_get_employees_dropdown_raw( $employee_id )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Comments', 'wphr' ),
            'name'    => 'comments',
            'value'   => '',
            'type'    => 'textarea',
            'id'      => 'performance_comments',
        ) ); ?>
    </div>
    <ul><?php do_action('wphr-hr-employee-performance-comments');?></ul>
    <?php wp_nonce_field( 'employee_update_performance' ); ?>
    <input type="hidden" name="type" value="comments">
    <input type="hidden" name="action" id="performance-comments-action" value="wphr-hr-emp-update-performance-comments">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
</div>
