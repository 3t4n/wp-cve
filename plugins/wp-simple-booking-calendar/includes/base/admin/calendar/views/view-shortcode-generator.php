<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

?>

<div id="wpsbc-modal-add-calendar-shortcode" class="wpsbc-modal">

	<!-- Modal Header -->
	<div class="wpsbc-modal-header">

		<h2>
			<span class="dashicons dashicons-calendar-alt"><!-- --></span>
			<?php echo __( 'Add Calendar', 'wp-simple-booking-calendar' ); ?>
		</h2>

		<span class="wpsbc-modal-close dashicons dashicons-no-alt"><!-- --></span>

	</div>

	<!-- Modal Body -->
	<div class="wpsbc-modal-body">

		<!-- Modal Nav Tab  -->
		<ul class="wpsbc-modal-nav-tab-wrapper">
			<li class="wpsbc-nav-tab wpsbc-modal-nav-tab wpsbc-active" data-tab="calendar-single"><a href="#"><?php echo __( 'Insert Calendar', 'wp-simple-booking-calendar' ); ?></a></li>
			<li class="wpsbc-nav-tab wpsbc-modal-nav-tab" data-tab="calendar-overview"><a href="#"><?php echo __( 'Insert Overview Calendar', 'wp-simple-booking-calendar' ); ?></a></li>
			<li class="wpsbc-nav-tab wpsbc-modal-nav-tab" data-tab="calendar-search-widget"><a href="#"><?php echo __( 'Insert Search Widget', 'wp-simple-booking-calendar' ); ?></a></li>
		</ul>

		<!-- Modal Body Inner -->
		<div class="wpsbc-modal-inner">

			<!-- Modal Tab: Calendar Single -->
			<div class="wpsbc-tab wpsbc-modal-tab wpsbc-active" data-tab="calendar-single">
				
				<h3><?php echo __( 'Insert a Calendar', 'wp-simple-booking-calendar' ); ?></h3>
				<p><?php echo __( 'Select which calendar you wish to insert and use the options to customize it to your needs.', 'wp-simple-booking-calendar' ); ?></p>

				<h4><?php echo __( 'Basic Options', 'wp-simple-booking-calendar' ); ?></h4>
				<hr />

				<!-- Row -->
				<div class="wpsbc-row">

					<!-- Column: Calendar -->
					<div class="wpsbc-col-1-4">

						<?php $calendars = wpsbc_get_calendars(); ?>
						
						<label for="modal-add-calendar-shortcode-calendar"><?php echo __( 'Calendar', 'wp-simple-booking-calendar' ); ?></label>

						<select id="modal-add-calendar-shortcode-calendar" class="wpsbc-shortcode-generator-field-calendar" data-attribute="id">
							<?php
								foreach( $calendars as $calendar )
									echo '<option value="' . $calendar->get('id') . '">' . $calendar->get('name') . '</option>';
							?>
						</select>

					</div>

					<!-- Column: Calendar Title -->
					<div class="wpsbc-col-1-4">
						
						<label for="modal-add-calendar-shortcode-calendar-title"><?php echo __( 'Display Calendar Title', 'wp-simple-booking-calendar' ); ?></label>

						<select id="modal-add-calendar-shortcode-calendar-title" class="wpsbc-shortcode-generator-field-calendar" data-attribute="title">
							<option value="yes"><?php echo __( 'Yes', 'wp-simple-booking-calendar' ); ?></option>
							<option value="no"><?php echo __( 'No', 'wp-simple-booking-calendar' ); ?></option>
						</select>

					</div>

					<!-- Column: Legend -->
					<div class="wpsbc-col-1-4">
						
						<label for="modal-add-calendar-shortcode-legend"><?php echo __( 'Display Legend', 'wp-simple-booking-calendar' ); ?></label>

						<select id="modal-add-calendar-shortcode-legend" class="wpsbc-shortcode-generator-field-calendar" data-attribute="legend">
							<option value="yes"><?php echo __( 'Yes', 'wp-simple-booking-calendar' ); ?></option>
							<option value="no"><?php echo __( 'No', 'wp-simple-booking-calendar' ); ?></option>
						</select>

					</div>

					<!-- Column: Legend Position -->
					<div class="wpsbc-col-1-4">
						
						<label for="modal-add-calendar-shortcode-legend-position"><?php echo __( 'Legend Position', 'wp-simple-booking-calendar' ); ?></label>

						<select id="modal-add-calendar-shortcode-legend-position" class="wpsbc-shortcode-generator-field-calendar" data-attribute="legend_position">
							<option value="side"><?php echo __( 'Side', 'wp-simple-booking-calendar' ); ?></option>
							<option value="top"><?php echo __( 'Top', 'wp-simple-booking-calendar' ); ?></option>
							<option value="bottom"><?php echo __( 'Bottom', 'wp-simple-booking-calendar' ); ?></option>
						</select>

					</div>

				</div><!-- / Row -->

				<!-- Row -->
				<div class="wpsbc-row">

					<!-- Column: Language -->
					<div class="wpsbc-col-1-4">

						<label for="modal-add-calendar-shortcode-language"><?php echo __( 'Language', 'wp-simple-booking-calendar' ); ?></label>

						<select id="modal-add-calendar-shortcode-language" class="wpsbc-shortcode-generator-field-calendar" data-attribute="language">
							
							<option value="auto"><?php echo __( 'Auto (let WP choose)', 'wp-simple-booking-calendar' ); ?></option>

							<?php

								$settings 		  = get_option( 'wpsbc_settings', array() );
								$languages 		  = wpsbc_get_languages();
								$active_languages = ( ! empty( $settings['active_languages'] ) ? $settings['active_languages'] : array() );

								foreach( $active_languages as $code ) {

									echo '<option value="' . esc_attr( $code ) . '">' . ( ! empty( $languages[$code] ) ? $languages[$code] : '' ) . '</option>';

								}

							?>

						</select>

					</div>

				</div><!-- / Row -->

				<hr />

				<!-- Shortcode insert -->
				<a href="#" id="wpsbc-insert-shortcode-single-calendar" class="button button-primary"><?php echo __( 'Insert Calendar', 'wp-simple-booking-calendar' ); ?></a>
				<a href="#" class="button button-secondary wpsbc-modal-close"><?php echo __( 'Cancel', 'wp-simple-booking-calendar' ); ?></a>

			</div>

		
			<!-- Modal Tab: Calendar Overview -->
			<div class="wpsbc-tab wpsbc-modal-tab" data-tab="calendar-overview">
				<a href="<?php echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'upgrade-to-premium' ), admin_url('admin.php') ); ?>" class="wpsbc-wrap-upgrade-cta">
					<span class="wpsbc-wrap-upgrade-cta-button">See details</span>
					<span class="wpsbc-wrap-upgrade-cta-heading">The overview calendar is only available in the premium version.</span>
				</a>
			</div>

			<!-- Modal Tab: Search Widget -->
			<div class="wpsbc-tab wpsbc-modal-tab" data-tab="calendar-search-widget">

				<a href="<?php echo add_query_arg( array( 'page' => 'wpsbc-calendars', 'subpage' => 'upgrade-to-premium' ), admin_url('admin.php') ); ?>" class="wpsbc-wrap-upgrade-cta">
					<span class="wpsbc-wrap-upgrade-cta-button">See details</span>
					<span class="wpsbc-wrap-upgrade-cta-heading">The search widget is only available in the premium version.</span>
				</a>

			</div>

		</div>

	</div>

</div>

<div id="wpsbc-modal-add-calendar-shortcode-overlay" class="wpsbc-modal-overlay"><!-- --></div>