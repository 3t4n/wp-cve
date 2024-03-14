<?php
	add_action( 'admin_menu', 'igc_add_admin_menu' );
	add_action( 'admin_init', 'igc_settings_init' );
	function igc_add_admin_menu(  ) {
		add_options_page( 'Instant Gzip compression', 'Instant Gzip compression', 'manage_options', 'instant_gzip_compression', 'igc_options_page' );
	}

	
	function igc_settings_init(  ) {
		register_setting( 'pluginPage', 'igc_settings' );
		add_settings_section('igc_pluginPage_section', __( '', 'wordpress' ), 'igc_settings_section_callback', 'pluginPage');
	}

	
	function igc_settings_section_callback(  ) {
		echo '<iframe src="http://www.gziptest.com/" height="400px" width="610px scrolling="no">';
	}

	
	function igc_options_page(  ) {
		?>
		<h2>Instant Gzip compression</h2><br>
		<p>Check if Gzip is enabled on this website!</p>
		<?php
 do_settings_sections( 'pluginPage' ); ?>
	<?php
 } ?>