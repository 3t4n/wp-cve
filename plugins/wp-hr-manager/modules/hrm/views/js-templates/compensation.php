<div class="compensation-form-wrap">
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
            'label'    => __( 'Pay Rate', 'wphr' ),
            'name'     => 'pay_rate',
            'value'    => '{{ data.work.pay_rate }}',
            'required' => true,
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Pay Type', 'wphr' ),
            'name'    => 'pay_type',
            'value'   => '',
            'type'    => 'select',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_hr_get_pay_type()
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Change Reason', 'wphr' ),
            'name'    => 'change-reason',
            'value'   => '',
            'type'    => 'select',
            'options' => array( 0 => __( '- Select -', 'wphr' ) ) + wphr_hr_get_pay_change_reasons()
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
    <div class="row">
    <ul> <?php //do_action( 'wphr-hr-employee-job-compensation', $employee ); ?>     
    </ul>
    </div>

    <?php wp_nonce_field( 'employee_update_compensation' ); ?>
    <input type="hidden" name="action" id="status-action" value="wphr-hr-emp-update-comp">
    <input type="hidden" name="employee_id" id="emp-id" value="{{ data.id }}">
    

</div>
