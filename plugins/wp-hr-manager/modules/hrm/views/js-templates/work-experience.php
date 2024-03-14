<div class="work-exp-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Previous Company', 'wphr' ),
            'name'     => 'company_name',
            'value'    => '{{ data.company_name }}',
            'required' => true,
            'placeholder' => __( 'ABC Corporation', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Role', 'wphr' ),
            'name'        => 'job_title',
            'value'       => '{{ data.job_title }}',
            'required'    => true,
            'placeholder' => __( 'Project Manager', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'From', 'wphr' ),
            'name'        => 'from',
            'value'       => '{{ data.from }}',
            'required'    => true,
            'class'       => 'wphr-date-field',
            'placeholder' => wphr_format_date( '1988-03-18' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'To', 'wphr' ),
            'name'        => 'to',
            'value'       => '{{ data.to }}',
            'required'    => true,
            'class'       => 'wphr-date-field',
            'placeholder' => wphr_format_date( '1988-03-18' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Job Description', 'wphr' ),
            'name'        => 'description',
            'type'        => 'textarea',
            'value'       => '{{ data.description }}',
            'placeholder' => __( 'Details about the job', 'wphr' )
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr-work-exp-form' ); ?>

    <input type="hidden" name="action" value="wphr-hr-create-work-exp">
    <input type="hidden" name="exp_id" value="{{ data.id }}">
    <input type="hidden" name="employee_id" value="{{ data.employee_id }}">
</div>
