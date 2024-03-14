<div class="work-exp-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Name', 'wphr' ),
            'name'     => 'name',
            'value'    => '{{ data.name }}',
            'required' => true,
            'placeholder' => __( 'Name of the person', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Relationship', 'wphr' ),
            'name'        => 'relation',
            'value'       => '{{ data.relation }}',
            'required'    => true,
            'placeholder' => __( 'Father', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Date of Birth', 'wphr' ),
            'name'        => 'dob',
            'value'       => '{{ data.dob }}',
            'class'       => 'wphr-date-field',
            'placeholder' => wphr_format_date( '1988-03-18' )
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr-hr-dependent-form' ); ?>

    <input type="hidden" name="action" value="wphr-hr-create-dependent">
    <input type="hidden" name="dep_id" value="{{ data.id }}">
    <input type="hidden" name="employee_id" value="{{ data.employee_id }}">
</div>
