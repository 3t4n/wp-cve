<div class="holiday-form-wrap">

    <div class="row">
        <?php $locations = WPHR\HR_MANAGER\Admin\Models\Company_Locations::select( '*' )->get()->toArray();
            $location = array();
            foreach ($locations as $loc) {
                $location[$loc['id']] = $loc['name'];
            }
         ?>
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

    <div class="row">
        <input type="file" id="wphr-hr-import-by-location" name="ics">
    </div>
    <?php wp_nonce_field( 'wphr-leave-holiday' ); ?>
    <input type="hidden" name="holiday_id" id="wphr-hr-holiday-by-location-id" value="{{ data.id }}">
</div>
