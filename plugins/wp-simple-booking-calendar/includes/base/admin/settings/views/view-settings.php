<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

$tabs 		= $this->get_tabs();
$active_tab = ( ! empty( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : 'languages' );
$settings 	= get_option( 'wpsbc_settings', array() );

?>

<div class="wrap wpsbc-wrap">

	<form action="options.php" method="POST">

		<?php settings_fields( 'wpsbc_settings' ); ?>

		<!-- Page Heading -->
		<h1 class="wp-heading-inline"><?php echo __( 'Settings', 'wp-simple-booking-calendar' ); ?></h1>
		<hr class="wp-header-end" />

		<!-- Navigation Tabs -->
		<h2 class="wpsbc-nav-tab-wrapper nav-tab-wrapper">
			<?php

				if( ! empty( $tabs ) ) {
					foreach( $tabs as $tab_slug => $tab_name ) {

						echo '<a href="' . add_query_arg( array( 'page' => 'wpsbc-settings', 'tab' => $tab_slug ), admin_url('admin.php') ) . '" data-tab="' . $tab_slug . '" class="nav-tab wpsbc-nav-tab ' . ( $active_tab == $tab_slug ? 'nav-tab-active' : '' ) . '">' . $tab_name . '</a>';

					}
				}

			?>
		</h2>

		<!-- Tabs Contents -->
		<div class="wpsbc-tab-wrapper">

			<?php

				if( ! empty( $tabs ) ) {

					foreach( $tabs as $tab_slug => $tab_name ) {

						echo '<div class="wpsbc-tab wpsbc-tab-' . $tab_slug . ' ' . ( $active_tab == $tab_slug ? 'wpsbc-active' : '' ) . '" data-tab="' . $tab_slug . '">';

						// Handle general tab
						if( $tab_slug == 'general' ) {

							include 'view-settings-tab-general.php';

						// Handle languages tab
						} else if( $tab_slug == 'languages' ) {

							include 'view-settings-tab-languages.php';
						
						} else if( $tab_slug == 'search-widget' ) {

							include 'view-settings-tab-search-widget.php';

						// Handle dynamic tabs
						} else {

							/**
							 * Action to dynamically add content for each tab
							 *
							 */
							do_action( 'wpsbc_submenu_page_settings_tab_' . $tab_slug );

						}

						echo '</div>';

					}

				}

			?>
		</div>

		<!-- Always update hidden -->
		<input type="hidden" name="wpsbc_settings[always_update]" value="<?php echo ( isset( $wpsbc_settings['always_update'] ) && $wpsbc_settings['always_update'] == 1 ? 0 : 1 ); ?>" />

	</form>

</div>