<div class="holiday-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Reason', 'wphr' ),
            'name'     => 'reason',
            'id'       => 'wphr-hr-leave-reject-reason',
            'value'    => '',
            'type'     => 'textarea',
            'required' => true,
        ) ); ?>
        <?php wphr_html_form_input( array(
            'type'     => 'hidden',
            'name'     => 'leave_request_id',
            'value'    => '{{ data.id }}',
        ) ); ?>
        <?php wphr_html_form_input( array(
            'name'     => 'action',
            'type'     => 'hidden',
            'value'    => 'wphr_hr_leave_reject',
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr-leave-reject' ); ?>
    <input type="hidden" name="action" id="wphr-hr-holiday-action" value="wphr_hr_leave_reject">
</div>
