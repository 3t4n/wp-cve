<div class="holiday-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Holiday Name', 'wphr' ),
            'name'     => 'title',
            'id'       => 'wphr-hr-holiday-title',
            'value'    => '{{ data.title }}',
            'required' => true,
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Start Date', 'wphr' ),
            'name'     => 'start_date',
            'value'    => '{{ data.start_date }}',
            'id'       => 'wphr-hr-holiday-start',
            'required' => true,
            'class'    => 'wphr-leave-date-picker-from',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Range', 'wphr' ),
            'name'     => 'range',
            'value'    => '{{ data.range }}',
            'id'       => 'wphr-hr-holiday-range',
            'help'     => __( 'Enable', 'wphr' ),
            'type'     => 'checkbox',
            'class'    => 'wphr-hr-holiday-date-range',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'End Date', 'wphr' ),
            'name'     => 'end_date',
            'id'       => 'wphr-hr-holiday-end',
            'value'    => '{{ data.end_date }}',
            'class'    => 'wphr-leave-date-picker-to',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'type'     => 'textarea',
            'label'    => __( 'Description', 'wphr' ),
            'name'     => 'description',
            'id'       => 'wphr-hr-holiday-description',
            'value'    => '{{ data.description }}',
            'class'    => 'wphr-hr-leave-holiday-description'
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr-leave-holiday' ); ?>
    <input type="hidden" name="action" id="wphr-hr-holiday-action" value="wphr_hr_holiday_create">
    <input type="hidden" name="holiday_id" id="wphr-hr-holiday-id" value="{{ data.id }}">
</div>
