<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$calendar_id = absint( ! empty( $_GET['calendar_id'] ) ? $_GET['calendar_id'] : 0 );
$calendar    = wpsbc_get_calendar( $calendar_id );

if( is_null( $calendar ) )
	return;

$current_year  = ( ! empty( $_GET['year'] )  ? absint( $_GET['year'] )  : current_time('Y') );
$current_month = ( ! empty( $_GET['month'] ) ? absint( $_GET['month'] ) : current_time('n') );

$settings = get_option( 'wpsbc_settings', array() );

$removable_query_args = wp_removable_query_args();

?>

<div class="wrap wpsbc-wrap wpsbc-wrap-edit-calendar">

	<form method="POST" action="" autocomplete="off">

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo __( 'Edit Calendar', 'wp-simple-booking-calendar' ); ?><span class="wpsbc-heading-tag"><?php printf( __( 'Calendar ID: %d', 'wp-simple-booking-calendar' ), $calendar_id ); ?></span></h1>

		<!-- Page Heading Actions -->
		<div class="wpsbc-heading-actions">

			<!-- Back Button -->
			<a href="<?php echo add_query_arg( array( 'page' => 'wpsbc-calendars'), admin_url( 'admin.php' ) ); ?>" class="button-secondary"><?php echo __('Back to all calendars', 'wp-simple-booking-calendar') ?></a>

			<!-- Save button -->
			<input type="submit" class="wpsbc-save-calendar button-primary" value="<?php echo __( 'Save Calendar', 'wp-simple-booking-calendar' ); ?>" />
			
		</div>

		<hr class="wp-header-end" />

		<div id="poststuff">

			<!-- Calendar Title -->
			<div id="titlediv">
				<div id="titlewrap">
					<input type="text" name="calendar_name" size="30" value="<?php echo esc_attr( $calendar->get('name') ) ?>" id="title">

					<?php if(isset($settings['active_languages']) && count($settings['active_languages']) > 0): ?>

						<a href="#" class="titlewrap-toggle"><?php echo __('Translate calendar title','wp-simple-booking-calendar') ?> <svg aria-hidden="true" focusable="false" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 320 512" ><path fill="currentColor" d="M31.3 192h257.3c17.8 0 26.7 21.5 14.1 34.1L174.1 354.8c-7.8 7.8-20.5 7.8-28.3 0L17.2 226.1C4.6 213.5 13.5 192 31.3 192z" class=""></path></svg></a>
						<div class="titlewrap-translations">
							<?php foreach($settings['active_languages'] as $language): ?>
								<div class="titlewrap-translation">
									<div class="titlewrap-translation-flag"><img src="<?php echo WPSBC_PLUGIN_DIR_URL; ?>assets/img/flags/<?php echo $language;?>.png" /></div>
									<input type="text" name="calendar_name_translation_<?php echo $language;?>" size="30" value="<?php echo esc_attr( wpsbc_get_calendar_meta($calendar->get('id'), 'calendar_name_translation_' . $language, true) ) ?>" >
								</div>
							<?php endforeach; ?>
						</div>

					<?php endif ?>
				</div>
			</div>

			<div id="post-body" class="metabox-holder columns-2">

				<!-- Main Post Body Content -->
				<div id="post-body-content">
					
					<!-- Availability -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __( 'Edit Dates', 'wp-simple-booking-calendar' ); ?></h3>

						<div class="inside">
							
							<div id="wpsbc-calendar-events">
								<?php
									$calendar_args = array(
										'current_year'  => $current_year,
										'current_month' => $current_month,
										'language' => wpsbc_get_locale()
									);

									$calendar_editor = new WPSBC_Calendar_Editor_Outputter( $calendar, $calendar_args );
									$calendar_editor->display();
								?>
							</div>

						</div>
					</div>

					<?php

						/**
						 * Action hook to add extra form fields to the main calendar edit area
						 *
						 * @param WPSBC_Calendar $calendar
						 *
						 */
						do_action( 'wpsbc_view_edit_calendar_main', $calendar );

					?>

				</div><!-- / Main Post Body Content -->

				<!-- Sidebar Content -->
				<div id="postbox-container-1" class="postbox-container">
		 			
		 			<!-- Calendar -->
		 			<div class="postbox">

						<h3 class="hndle"><?php echo __( 'Calendar', 'wp-simple-booking-calendar' ); ?></h3>

						<div class="inside">
							
							<?php
								$calendar_args = array(
									'current_year'  => $current_year,
									'current_month' => $current_month,
									'show_title'  	=> 0,
									'show_legend' 	=> 0,
									'language' => wpsbc_get_locale()
								);

								$calendar_outputter = new WPSBC_Calendar_Outputter( $calendar, $calendar_args );
								$calendar_outputter->display();
							?>

						</div>
					</div><!-- / Calendar -->

					<!-- Calendar Legend -->
		 			<div class="postbox">

						<h3 class="hndle"><?php echo __( 'Legend', 'wp-simple-booking-calendar' ); ?></h3>

						<div class="inside">
							
							<?php
								$legend_items = wpsbc_get_legend_items( array( 'calendar_id' => $calendar_id ) );

								foreach( $legend_items as $legend_item ) {

									echo '<div class="wpsbc-legend-item">';
										echo wpsbc_get_legend_item_icon( $legend_item->get('id'), $legend_item->get('type'), $legend_item->get('color') );
										echo '<span class="wpsbc-legend-item-name">' . $legend_item->get_name(wpsbc_get_locale()) . '</span>';
									echo '</div>';

								}
							?>

						</div>

						<div class="wpsbc-plugin-card-bottom plugin-card-bottom">
							<a class="button-secondary" href="<?php echo add_query_arg( array( 'subpage' => 'view-legend' ), remove_query_arg( $removable_query_args ) ); ?>"><?php echo __( 'Edit Legend Items', 'wp-simple-booking-calendar' ); ?></a>
						</div>

					</div><!-- / Calendar Legend -->

					<!-- iCal Export -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __( 'iCal Import/Export', 'wp-simple-booking-calendar' ); ?></h3>

						<div class="inside">

							<p><?php echo __( 'To configure the iCal import & export settings and have access to the iCal export link please click the button below.', 'wp-simple-booking-calendar' ); ?></p>

							<a href="<?php echo add_query_arg( array( 'subpage' => 'ical-import-export' ), remove_query_arg( $removable_query_args ) ); ?>" class="button-secondary"><span class="dashicons dashicons-upload"></span> <?php echo __( 'iCal Import/Export', 'wp-simple-booking-calendar' ); ?></a>

						</div>

					</div><!-- / iCal Export -->


					<!-- CSV Export -->
					<div class="postbox">

						<h3 class="hndle"><?php echo __( 'CSV Export', 'wp-simple-booking-calendar' ); ?></h3>

						<div class="inside">

							<p><?php echo __( 'To configure the CSV export settings please click the button below. ', 'wp-simple-booking-calendar' ); ?></p>

							<a href="<?php echo add_query_arg( array( 'subpage' => 'csv-export' ), remove_query_arg( $removable_query_args ) ); ?>" class="button-secondary"><span class="dashicons dashicons-media-spreadsheet"></span> <?php echo __( 'CSV Export', 'wp-simple-booking-calendar' ); ?></a>

						</div>

					</div><!-- / CSV Export -->


					<?php

						/**
						 * Action hook to add extra form fields to the main calendar edit area
						 *
						 * @param WPSBC_Calendar $calendar
						 *
						 */
						do_action( 'wpsbc_view_edit_calendar_sidebar', $calendar );

					?>

				</div><!-- / Sidebar Content -->

			</div><!-- / #post-body -->

		</div><!-- / #poststuff -->

		<!-- Hidden fields -->
		<input type="hidden" name="calendar_id" value="<?php echo $calendar_id; ?>" />

		<!-- Nonce -->
		<?php wp_nonce_field( 'wpsbc_save_calendar', 'wpsbc_token', false ); ?>


		<!-- Save button -->
		<input type="submit" class="wpsbc-save-calendar button-primary" value="<?php echo __( 'Save Calendar', 'wp-simple-booking-calendar' ); ?>" />

		<!-- Save Button Spinner -->
		<div class="wpsbc-save-calendar-spinner spinner"><!-- --></div>

	</form>

</div>