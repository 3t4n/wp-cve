<div class="wrap">
	<h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
	<form action="options.php" method="post">
		<?php
		settings_fields( self::SETTINGS_PAGE_IDENTIFIER );
		do_settings_sections( 'getyourguide' );
		submit_button();
		?>
	</form>
</div>