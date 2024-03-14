<?php
	$time_slot = get_office_timing();
	$timezone  = get_office_timezone();
    $day_dropdown = function_exists( 'wphr_day_dropdown' ) ? wphr_day_dropdown() : [];
?>
<ul class="edit-address">
    <li class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Location Name', 'wphr' ),
            'name'     => 'location_name',
            'value'    => '{{ data.name }}',
            'required' => true
        ) ); ?>
    </li>

    <li class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Address Line 1', 'wphr' ),
            'name'     => 'address_1',
            'value'    => '{{{ data.address_1 }}}',
            'required' => true
        ) ); ?>
    </li>

    <li class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'Address Line 2', 'wphr' ),
            'name'     => 'address_2',
            'value'    => '{{ data.address_2 }}',
        ) ); ?>
    </li>

    <li class="row">
        <?php wphr_html_form_input( array(
            'label'    => __( 'City', 'wphr' ),
            'name'     => 'city',
            'value'    => '{{ data.city }}',
        ) ); ?>
    </li>

    <li class="row" data-selected="{{ data.country }}">
        <label for="wphr-popup-country"><?php _e( 'Country', 'wphr' ); ?> <span class="required">*</span></label>
        <select name="country" id="wphr-popup-country" class="wphr-country-select select2" data-parent="ul">
            <?php $country = \WPHR\HR_MANAGER\Countries::instance(); ?>
            <?php echo $country->country_dropdown(); ?>
        </select>
    </li>

    <li class="row" data-selected="{{ data.state }}">
        <?php wphr_html_form_input( array(
            'label'   => __( 'Province / State', 'wphr' ),
            'name'    => 'state',
            'id'      => 'wphr-state',
            'type'    => 'select',
            'class'   => 'wphr-state-select',
            'options' => array( 0 => __( '- Select -', 'wphr' ) )
        ) ); ?>
    </li>

    <li class="row">
        <?php wphr_html_form_input( array(
            'label'     => __( 'Postal / Zip Code', 'wphr' ),
            'name'      => 'zip',
            'type'      => 'text',
            'value'     => '{{ data.zip }}',
        ) ); ?>
    </li>

    <li class="row" data-selected="{{ data.office_timezone }}">
        <?php wphr_html_form_input( array(
            'label'     => __( 'TImezone', 'wphr' ),
            'name'      => 'office_timezone',
            'id'	    => 'office_timezone',
			'required'  => true,
            'type'      => 'select',
            'options'   => $timezone
        ) ); ?>
    </li>
	
	<li class="row" data-selected="{{ data.office_start_time }}">
	<?php wphr_html_form_input( array(
                            'label'    => __( 'Office start time', 'wphr' ),
                            'name'     => 'office_start_time',
                            'id'       => 'office_start_time',
							'class'    => 'wphr-office-timing',
							'type'     => 'select',
							'required' => true,
							'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                        ) ); ?>
	</li>
	
	<li class="row" data-selected="{{ data.office_end_time }}">
	<?php wphr_html_form_input( array(
                            'label'    => __( 'Office end time', 'wphr' ),
                            'name'     => 'office_end_time',
                            'id'       => 'office_end_time',
							'class'    => 'wphr-office-timing',
							'type'     => 'select',
							'required' => true,
							'options'  => array( '' => __( '- Select -', 'wphr' ) ) + $time_slot
                        ) ); ?>
	</li>
    <li class="row inline-block" data-selected="{{ data.office_financial_day_start }}">
        <input type="hidden" name="current_office_financial_day_start" value="{{ data.office_financial_day_start }}" />
    <?php wphr_html_form_input( array(
                            'label'    => __( 'Employee Leave Year Starts', 'wphr' ),
                            'name'     => 'office_financial_day_start',
                            'id'       => 'office_financial_day_start',
                            'class'    => 'wphr-office-financial-day-start',
                            'type'     => 'select', 
                            'options'  => $day_dropdown,
                            'value'    => '{{ data.office_financial_day_start }}',
                            'required' => true,
                        ) ); ?>
    </li>
    <li class="row inline-block" data-selected="{{ data.office_financial_year_start }}">
    <input type="hidden" name="current_office_financial_year_start" value="{{ data.office_financial_year_start }}" />
    <?php wphr_html_form_input( array(
                            'label'    => __( ' / ', 'wphr' ),
                            'name'     => 'office_financial_year_start',
                            'id'       => 'office_financial_year_start',
                            'class'    => 'wphr-office-financial-year-start',
                            'type'     => 'select', 
                            'options'  => wphr_months_dropdown(),
                            'value'    => '{{ data.office_financial_year_start }}',
                            'required' => true,
                        ) ); ?>
    </li>
    <li class="row">
        <?php wphr_html_form_input( array(
            'label'     => __( 'Office working hours', 'wphr' ),
            'name'      => 'office_working_hours',
            'type'      => 'text',
            'value'     => '{{ data.office_working_hours }}',
            'required' => true
        ) ); ?>
    </li>
	<?php do_action('location_address_fields'); ?>					
    <input type="hidden" name="location_id" value="{{ data.id }}">
    <input type="hidden" name="company_id" value="{{ data.company_id }}">
    <input type="hidden" name="action" value="wphr-company-location">
    <?php wp_nonce_field( 'wphr-company-location' ); ?>
</ul>
