<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 *
 * @package    Wp_Revisions_Limit
 * @subpackage Wp_Revisions_Limit/admin/partials
 */
?>
<?php $this->load_options(); ?>
<div class="wrap">
    <h2>WP Revisions Limit</h2>
	<div id="save_message" class="updated settings-error" style="display:none;"></div>
    <form id="wp_revisions_limit" method="post" action="options.php">
    <?php
        // This prints out all hidden setting fields
        settings_fields( 'wp_revisions_limit_group' );
        do_settings_sections( $this->plugin_name );
        submit_button();
    ?>
    </form>
	<script type="text/javascript">
		//Form Validation
		jQuery( document ).ready( function( $ ) {
			$( "form#wp_revisions_limit" ).on( 'submit', function( event ) {
				$( this ).find( "span#error" ).remove();
				var revisions_limit = $( this ).find( "input#revisions_limit" ).val();

				if( revisions_limit == "" || Math.floor( revisions_limit ) != revisions_limit 
					|| !$.isNumeric( revisions_limit )/* || revisions_limit < 0 || revisions_limit > 20*/ ) {
					$( this ).find( "input#revisions_limit" ).after( "<span id=\"error\" style=\"color: red; font-style: italic; margin-left: 10px;\"><?php echo __( 'This value must be a number between 0 and 20.' ) ?></span>" );
				} else {
					$( this ).ajaxSubmit({
						success: function() {
							$( this ).find( "span#error" ).remove();
							$( "#save_message" ).html( "<p><strong>Settings saved.</strong></p>" ).show( 'slow' );
						},
						timeout: 5000
					});

					setTimeout( function () {
						$( "#save_message" ).hide( 'slow' );
						$( "#save_message" ).find( "p" ).remove();

					}, 5000 );
				}
				event.preventDefault();
			});
		});
	</script>
</div>