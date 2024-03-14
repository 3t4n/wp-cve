<div class="policy-form-wrap">

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Policy Name', 'wphr' ),
            'name'     => 'name',
            'value'    => '{{ data.name }}',
            'required' => true,
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Description', 'wphr' ),
            'type'     => 'textarea',
            'name'     => 'description',
            'value'    => '{{ data.description }}',
            'placeholder' => __( '(optional)', 'wphr' ),
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Days', 'wphr' ),
            'name'     => 'days',
            'value'    => '{{ data.value }}',
            'required' => true,
            'help'     => __( 'Days in a calendar year.', 'wphr' ),
            'placeholder'     => 20
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Calendar Color', 'wphr' ),
            'name'     => 'color',
            'value'    => '{{ data.color }}',
            'required' => true,
            'class'    => 'wphr-color-picker'
        ) ); ?>
    </div>

   <div class="row" data-selected="{{ data.department }}">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Department', 'wphr' ),
            'name'        => 'department',
            'value'       => '{{ data.department }}',
            'class'       => 'wphr-hrm-select2-add-more wphr-hr-dept-drop-down',
            'custom_attr' => array( 'data-id' => 'wphr-new-dept' ),
            'type'        => 'select',
            'options'     => wphr_hr_get_departments_dropdown_raw( __( 'All Department', 'wphr' ) )
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.designation }}">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Role', 'wphr' ),
            'name'        => 'designation',
            'value'       => '{{ data.designation }}',
            'class'       => 'wphr-hrm-select2-add-more wphr-hr-desi-drop-down',
            'custom_attr' => array( 'data-id' => 'wphr-new-designation' ),
            'type'        => 'select',
            'options'     => wphr_hr_get_designation_dropdown_raw( __( 'All Roles', 'wphr' ) )
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.location }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Location', 'wphr' ),
            'name'    => 'location',
            'value'   => '{{ data.location }}',
            'type'    => 'select',
            'options' => array('-1' => __( 'All Location', 'wphr' ) ) + wphr_company_get_location_dropdown_raw()
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.gender }}">
        <?php wphr_html_form_input( array(
            'label'       => __( 'Gender', 'wphr' ),
            'name'        => 'gender',
            'value'       => '{{ data.gender }}',
            'type'        => 'select',
            'options' => wphr_hr_get_genders( __( 'All', 'wphr' ) )
        ) ); ?>
    </div>

    <div class="row" data-selected="{{ data.marital }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Marital Status', 'wphr' ),
            'name'    => 'maritial',
            'value'   => '{{ data.marital }}',
            'class'   => 'wphr-hrm-select2-add-more wphr-hr-desi-drop-down',
            'type'    => 'select',
            'options' => wphr_hr_get_marital_statuses( __( 'All', 'wphr' ) )
        ) ); ?>
    </div>

    <# if ( data.id ) { #>
        <div class="row">
            <?php wphr_html_form_input( array(
                'label'    => __( 'Effective Date', 'wphr' ),
                'name'     => 'effective_date',
                'value'    => '{{ data.effective_date }}',
                'class'    => 'wphr-leave-date-field',
                'help'    => __( 'If this policy is for a future leave period, you may still want to set this date from now, so you can add leave requests to the policy immediately.  If you set a future date, you won’t be able to add requests until that time.', 'wphr' )
            ) ); ?>
        </div>
    <# } else { #>
        <div class="row">
            <?php
                $financial_year_dates = wphr_get_financial_year_dates();
            ?>
            <?php wphr_html_form_input( array(
                'label'    => __( 'Effective Date', 'wphr' ),
                'name'     => 'effective_date',
                'value'    => wphr_format_date( date( 'Y-m-d', strtotime( $financial_year_dates['start'] ) ) ),
                'class'    => 'wphr-leave-date-field',
                'help'    => __( 'If this policy is for a future leave period, you may still want to set this date from now, so you can add leave requests to the policy immediately.  If you set a future date, you won’t be able to add requests until that time.', 'wphr' )
            ) ); ?>
        </div>
    <# } #>

    <div class="row" data-selected="{{ data.activate }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Activate', 'wphr' ),
            'name'    => 'rateTransitions',
            'value'   => '{{ data.activate }}',
            'class'   => 'wphr-hrm-select2-add-more wphr-hr-desi-drop-down wphr-hr-leave-period',
            'type'    => 'select',
            'help'    => __( '', 'wphr' ),
            'options' => array( '1' => __( 'Immediately apply after hiring', 'wphr' ), '2' => __( 'Apply after X days from hiring', 'wphr' ), '3' => __( 'Manually', 'wphr' ) )
        ) ); ?>
    </div>

    <div class="row showifschedule wphr-hide">
        <?php wphr_html_form_input( array(
            'label'    => __( 'How many days', 'wphr' ),
            'name'     => 'no_of_days',
            'value'    => '{{ data.execute_day }}',
            'help'     => __( 'No of days from hire', 'wphr' ),
            'placeholder' => 60
        ) ); ?>
    </div>
    <# if ( ! data.id ) { #>
        <div class="row hide-if-manual">
            <?php wphr_html_form_input( array(
                'label'    => '&nbsp;',
                'name'     => 'apply',
                'type'     => 'checkbox',
                'help'     => __( 'Apply for existing users', 'wphr' ),
                'value'    => 'on'
            ) ); ?>
        </div>
    <# } #>

    <?php wp_nonce_field( 'wphr-leave-policy' ); ?>
    <input type="hidden" name="action" value="wphr-hr-leave-policy-create">
    <input type="hidden" name="policy-id" value="{{ data.id }}">
</div>
