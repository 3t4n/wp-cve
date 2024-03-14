<style>.form-table th{display:none;} .form-table .large-text{width:100%; height: 400px;}</style>
<div class="wrap">
	<form method="post" action="options.php">
		<?php 	
			settings_fields( 'bs_ads_txt_settings_option_group' );
			do_settings_sections('bs_ads_txt_settings'); 
			submit_button();
		?>
	</form>
</div>
