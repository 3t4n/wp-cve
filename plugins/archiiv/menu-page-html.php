<section class="main-page">
	<div class="content-width">
		<h1>Archiiv: Beehiiv Newsletter Integration</h1>
    	<p>To output an email form that connects to your Beehiiv account, enter your Beehiiv API key, your Beehiiv publication ID, and in the field below. <br><a class="contact" target="_blank" href="https://app.beehiiv.com/settings/integrations">Where to find your API Key and publication ID.</a></p>
    	<p><em>Copy this shortcode to utilize the form: <strong>[beehiiv_newsletter]</strong></em></p>

    	<!-- Make a call to the WordPress function for rendering errors when settings are saved. -->
		<?php settings_errors(); ?>
		<!-- Create the form that will be used to render our options -->
		<form method="post" action="options.php">
			<?php settings_fields( 'archiiv' ); ?>
			<?php do_settings_sections( 'archiiv' ); ?>			
			<?php submit_button(); ?>
		</form>

		<div style="margin-top: 5em;">
			<p><em>This plugin is brought to you courtesy of <a href="https://arcbound.com" target="_blank">Arcbound</a></em>.
		</div>
	</div>
</section>