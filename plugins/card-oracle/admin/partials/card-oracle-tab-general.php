<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://cdgraham.com
 * @since      0.7.0
 *
 * @package    Card_Oracle
 * @subpackage Card_Oracle/admin/partials
 */

?>
<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<?php if ( 'general' === $active_tab ) { ?>
		<div id="co_settings_general" class="wrap settingscontent">
			<form method="post" action="<?php echo esc_url( add_query_arg( 'tab', $active_tab, admin_url( 'options.php' ) ) ); ?>">
			<?php
				settings_fields( 'card_oracle_option_general' );
				do_settings_sections( 'card_oracle_option_general' );
				submit_button();
			?>
			</form>

		</div> <!-- active_tab general -->
<?php } ?>
