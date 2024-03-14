<?php
/**
 * Settings section of the plugin.
 *
 * Maintain a list of functions that are used for settings purposes of the plugin
 *
 * @package    Blossom_Recipe
 * @subpackage Blossom_Recipe/admin
 * @author     Blossom <test@test.com>
 */
class Blossom_Recipe_Maker_Settings {


	/**
	 * Settings Tabs.
	 *
	 * @since 1.0.0
	 */
	function br_recipe_settings_options() {

		$tabs = array(
			'Misc'          => 'misc.php',
			'Recipe Search' => 'search.php',
			'Shortcodes'    => 'shortcodes.php',
		);

		$tabs = apply_filters( 'br_recipe_settings_options_tabs', $tabs );

		return $tabs;
	}


	/**
	 * Settings panel of the plugin.
	 *
	 * @since 1.0.0
	 */
	function br_recipe_backend_settings() {
		$submitted_get_data = blossom_recipe_maker_get_submitted_data( 'get' );

		if ( isset( $submitted_get_data['settings-updated'] ) && $submitted_get_data['settings-updated'] == true ) {
			?>
				<div id="setting-error-settings_updated" class="updated settings-error notice is-dismissible"> 
				<p><strong><?php esc_html_e( 'Your settings have been saved.', 'blossom-recipe-maker' ); ?></strong></p><button type="button" class="notice-dismiss"><span class="screen-reader-text"><?php esc_html_e( 'Dismiss this notice.', 'blossom-recipe-maker' ); ?></span></button>
				</div>
			<?php
		}
		?>
		<div class="blossom-recipe-settings-header">
			<i class="dashicons-blossom-receipe"></i>
			<span class="plugin-name">
		<?php esc_html_e( 'Blossom Recipes', 'blossom-recipe-maker' ); ?>
			</span>
			<span class="page-name">
		<?php esc_html_e( 'Settings', 'blossom-recipe-maker' ); ?>
			</span>
		</div>

		<div id="blossom-recipe-settings-navigation">

			<h2 class="nav-tab-wrapper current">

		<?php
		$settings_tab = $this->br_recipe_settings_options();

		$count = 0;
		foreach ( $settings_tab as $key => $value ) {

			$tab_label = preg_replace( '/_/', ' ', $key );
			?>

					<a 
			<?php
			if ( $count == 0 ) {
				?>
						class="nav-tab nav-tab-active"
				 <?php
			} else {
				?>
class="nav-tab" <?php } ?> href="javascript:;"><?php echo esc_attr_e( $tab_label, 'blossom-recipe-maker' ); ?></a>

			<?php
			$count++;

		}
		?>

			</h2>

			<div id="blossom-recipe-settings-tab">

				<form method="POST" name="settingsform" action="options.php" id="settingsform">    

		<?php
		settings_fields( 'br_recipe_settings' );

		do_settings_sections( __FILE__ );

		$option = get_option( 'br_recipe_settings' );

		if ( empty( $option ) ) {
			$option = array();
		}

		$counter = 0;
		?>

					<div id="br-settings-tab-navigation">


		 <?php foreach ( $settings_tab as $key => $value ) { ?>
						
				<?php
				include_once BLOSSOM_RECIPE_MAKER_BASE_PATH . '/includes/backend/settings/' . $value;
				?>
						
						<?php
						$counter++;
		 }
			?>


					</div>

					<div class="br-recipe-settings-submit">
		 <?php submit_button(); ?>
					</div>

				</form>
			</div>
		</div>
		<?php
	}
}
