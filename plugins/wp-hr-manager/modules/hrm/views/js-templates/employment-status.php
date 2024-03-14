<div class="status-form-wrap">
     <?php //do_action( 'wphr-hr-employee-form-basic' ); ?>
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Date', 'wphr' ),
            'name'     => 'date',
            'value'    => wphr_format_date( current_time( 'timestamp' ) ),
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Employment Status', 'wphr' ),
            'name'    => 'status',
            'value'   => '',
            'type'    => 'select',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_hr_get_employee_types()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Comment', 'wphr' ),
            'name'        => 'comment',
            'value'       => '',
            'placeholder' => __( 'Optional comment', 'wphr' ),
            'type'        => 'textarea',
            'custom_attr' => array( 'rows' => 4, 'cols' => 25 )
        ) ); ?>

    </div>
  <div class="row"> <ul><?php do_action( 'wphr-hr-employee-job-staus'); ?></ul></div>

    <?php wp_nonce_field( 'employee_update_employment' ); ?>
    <input type="hidden" name="action" id="status-action" value="wphr-hr-emp-update-status">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
</div>
