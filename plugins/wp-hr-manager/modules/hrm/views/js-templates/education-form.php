<div class="work-exp-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'School Name', 'wphr' ),
            'name'     => 'school',
            'value'    => '{{ data.school }}',
            'required' => true,
            'placeholder' => __( 'ABC School', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Degree', 'wphr' ),
            'name'        => 'degree',
            'value'       => '{{ data.degree }}',
            'required'    => true,
            'placeholder' => __( 'Bachelor in Science', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Field of Study', 'wphr' ),
            'name'        => 'field',
            'value'       => '{{ data.field }}',
            'required'    => true,
            'placeholder' => __( 'Physics', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Year of Completion', 'wphr' ),
            'name'        => 'finished',
            'type'        => 'number',
            'value'       => '{{ data.finished }}',
            'required'    => true,
            'placeholder' => date( 'Y' ),
            'custom_attr' => [
                'min'  => 1970,
                'max'  => 2099,
                'step' => 1
            ]
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Notes', 'wphr' ),
            'name'        => 'notes',
            'type'        => 'textarea',
            'value'       => '{{ data.notes }}',
            'placeholder' => __( 'Additional notes', 'wphr' )
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Interests', 'wphr' ),
            'name'        => 'interest',
            'type'        => 'textarea',
            'value'       => '{{ data.interest }}'
        ) ); ?>
    </div>

    <?php wp_nonce_field( 'wphr-hr-education-form' ); ?>

    <input type="hidden" name="action" value="wphr-hr-create-education">
    <input type="hidden" name="edu_id" value="{{ data.id }}">
    <input type="hidden" name="employee_id" value="{{ data.employee_id }}">
</div>
