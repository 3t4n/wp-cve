<div class="wrap">
	<h1>Leadlovers</h1>
	<?php settings_errors(); ?>

	<form method="post" action="options.php">
		<?php 
			settings_fields( 'leadlovers_options_group' );
			do_settings_sections( 'leadlovers_plugin' );
			submit_button();
		?>
	</form>
</div>