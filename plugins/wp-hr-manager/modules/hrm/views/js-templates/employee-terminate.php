<?php $employee_id = isset( $_GET['id'] ) ? intval(sanitize_text_field( $_GET['id'] ) ) : null; ?>

<div class="terminate-form-wrap">
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Termination Date', 'wphr' ),
            'name'     => 'terminate_date',
            'value'    => '{{data.terminate_date}}',
            'required' => true,
            'class'    => 'wphr-date-field'
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.termination_type }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Termination Type', 'wphr' ),
            'name'    => 'termination_type',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'type'    => 'select',
            'required' => true,
            'id'      => 'termination_type',
            'options' => array( '' => __( '- Select -', 'wphr' ) ) + wphr_hr_get_terminate_type()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.termination_reason }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Termination Reason', 'wphr' ),
            'name'    => 'termination_reason',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'required' => true,
            'type'    => 'select',
            'id'      => 'termination_reason',
            'options' => array( '' => __( '- Select -', 'wphr' ) ) + wphr_hr_get_terminate_reason()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.eligible_for_rehire }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Eligible for Rehire', 'wphr' ),
            'name'    => 'eligible_for_rehire',
            'value'   => '',
            'class'   => 'wphr-hrm-select2',
            'required' => true,
            'type'    => 'select',
            'id'      => 'eligible_for_rehire',
            'options' => array( '' => __( '- Select -', 'wphr' ) ) + wphr_hr_get_terminate_rehire_options()
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'employee_update_terminate' ); ?>
    <input type="hidden" name="action" id="employee-terminate-action" value="wphr-hr-emp-update-terminate-reason">
    <input type="hidden" name="employee_id" id="emp-id" value="<?php echo $employee_id; ?>">
</div>
