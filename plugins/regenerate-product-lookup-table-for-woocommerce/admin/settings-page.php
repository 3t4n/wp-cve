<?php

// No direct access to file
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * Display the plugin settings page
 */
function smnwcrpl_display_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	?>

    <div class="smnwcrpl-wrapper">

        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

        <form action="options.php" method="post">

			<?php
			settings_fields( 'smnwcrpl_options' );
			do_settings_sections( 'smnwcrpl' );
			submit_button();
			?>

        </form>

    </div>

	<?php
}