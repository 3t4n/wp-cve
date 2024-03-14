<div class="fl-builder-service-settings">
    <table class="fl-form-table">
		<?php
		$service_type = null; // Get the service type.
		if ( isset( $section['services'] ) && $section['services'] !== 'all' ) {
			$service_type = $section['services'];
		}
		$services = FLBuilderServices::get_services_data( $service_type ); // Get the service data.
		if ( isset( $services['mailpoet'] ) && ! class_exists( 'WYSIJA' ) ) {  // Remove services that don't meet the requirements.
			unset( $services['mailpoet'] );
		}
		$options = array( '' => __( 'Choose...', 'fl-builder' ) ); // Build the select options.
		foreach ( $services as $key => $service ) {
			$options[ $key ] = $service['name'];
		}
		FLBuilder::render_settings_field( 'service', array( // Render the service select.
			'row_class' => 'fl-builder-service-select-row',
			'class'     => 'fl-builder-service-select',
			'type'      => 'select',
			'label'     => __( 'Service Type', 'fl-builder' ),
			'options'   => $options,
			'preview'   => array(
				'type' => 'none'
			)
		), $settings ); ?>
    </table>
</div>
