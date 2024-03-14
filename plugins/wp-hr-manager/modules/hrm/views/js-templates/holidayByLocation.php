<div class="holiday-form-wrap">
    <?php  $locations = WPHR\HR_MANAGER\Admin\Models\Company_Locations::select( '*' )->get()->toArray();
        $location = array();
        foreach ($locations as $loc) {
            $location[$loc['id']] = $loc['name'];
        }
     ?>
    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Holiday Name', 'wphr' ),
            'name'     => 'title',
            'id'       => 'wphr-hr-holiday-by-location-title',
            'value'    => '{{ data.title }}',
            'required' => true,
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Start Date', 'wphr' ),
            'name'     => 'start_date',
            'value'    => '{{ data.start_date }}',
            'id'       => 'wphr-hr-holiday-by-location-start',
            'required' => true,
            'class'    => 'wphr-leave-date-picker-from',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Range', 'wphr' ),
            'name'     => 'range',
            'value'    => '{{ data.range }}',
            'id'       => 'wphr-hr-holiday-by-location-range',
            'help'     => __( 'Enable', 'wphr' ),
            'type'     => 'checkbox',
            'class'    => 'wphr-hr-holiday-by-location-date-range',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'End Date', 'wphr' ),
            'name'     => 'end_date',
            'id'       => 'wphr-hr-holiday-by-location-end',
            'value'    => '{{ data.end_date }}',
            'class'    => 'wphr-leave-date-picker-to',
        ) ); ?>
    </div>

    <div class="row">
        <?php wphr_html_form_input( array(
            'type'     => 'textarea',
            'label'    => __( 'Description', 'wphr' ),
            'name'     => 'description',
            'id'       => 'wphr-hr-holiday-by-location-description',
            'value'    => '{{ data.description }}',
            'class'    => 'wphr-hr-leave-holiday-by-location-description'
        ) ); ?>
    </div>

    <div class="row">
        
        <?php  wphr_html_form_input( array(
            'label'    => __( 'Select Location', 'wphr' ),
            'name'     => 'location_id',
            'value'    => '{{ data.location_id }}',
            'class'    => 'wphr-hr-leave-holiday-by-location-country',
            'id'    => 'wphr-hr-leave-holiday-by-location-country',
            'type'     => 'select',
            'options'  => $location
        ) );  ?>

    </div>

    <?php wp_nonce_field( 'wphr-leave-holiday' ); ?>
    <input type="hidden" name="action" id="wphr-hr-holiday-by-location-action" value="wphr_hr_holiday_create">

    <input type="hidden" name="holiday_id" id="wphr-hr-holiday-by-location-id" value="{{ data.id }}">
</div>
