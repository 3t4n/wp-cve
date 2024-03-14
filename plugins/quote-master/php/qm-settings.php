<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Generates The Settings Page For The Plugin
 *
 * @since 4.1.0
 */
class QMGlobalSettingsPage
{

	/**
	  * Main Construct Function
	  *
	  * Call functions within class
	  *
	  * @since 4.1.0
	  * @uses QMGlobalSettingsPage::load_dependencies() Loads required filed
	  * @uses QMGlobalSettingsPage::add_hooks() Adds actions to hooks and filters
	  * @return void
	  */
	function __construct()
  {
    $this->load_dependencies();
    $this->add_hooks();
  }

	/**
	  * Load File Dependencies
	  *
	  * @since 4.1.0
	  * @return void
	  */
  private function load_dependencies()
  {

  }

	/**
	  * Add Hooks
	  *
	  * Adds functions to relavent hooks and filters
	  *
	  * @since 4.1.0
	  * @return void
	  */
  private function add_hooks()
  {
		add_action("admin_init", array($this, 'init'));
  }

	/**
	 * Prepares Settings Fields And Sections
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function init()
	{
		register_setting( 'qm-settings-group', 'qm-settings' );
    add_settings_section( 'qm-global-section', 'Main Settings', array($this, 'global_section'), 'qm_global_settings' );
    add_settings_field( 'enable-tweet', 'Allow Users To Tweet Quote', array($this, 'enable_tweet_field'), 'qm_global_settings', 'qm-global-section' );
    add_settings_field( 'chosen-style', 'Style', array($this, 'chosen_style_field'), 'qm_global_settings', 'qm-global-section' );
		add_settings_field( 'custom-style', 'Custom Style', array($this, 'custom_style_template'), 'qm_global_settings', 'qm-global-section' );
	}

	/**
	 * Generates Section Text
	 *
	 * Generates the section text.
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function global_section()
	{
		echo 'These settings will affect the way your quotes look through your entire site.';
		if (isset($_GET["settings-updated"]) && $_GET["settings-updated"])
		{
			echo "<h3 style='color:red;'>Settings have been updated!</h3>";
		}
	}

	/**
	 * Generates Setting Field For Custom Style Template
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function custom_style_template()
	{
		$settings = (array) get_option( 'qm-settings' );
    $template = '.qm_quote{}
      .qm_quote_author{}
      .qm_quote_source{}
      .qm_quote_tweet{}
      .qm_quote p{}
      .qm_quote_widget{}
      .qm_quote_widget_author{}
      .qm_quote_widget_source{}
      .qm_quote_widget_tweet{}';
		if ( isset( $settings['custom_style'] ) ) {
			$template = $settings['custom_style'];
		}
    ?>
    <textarea class="qm_settings_textarea" name="qm-settings[custom_style]" id="qm-settings[custom_style]"><?php echo esc_textarea($template); ?></textarea>
    <?php
	}

  /**
	 * Generates Setting Field For Enable Tweet
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function enable_tweet_field()
	{
		$settings = (array) get_option( 'qm-settings' );
		$enable_tweet = '0';
		if ( isset( $settings['enable_tweet'] ) ) {
			$enable_tweet = $settings['enable_tweet'];
		}
    $checked = '';
    if ( $enable_tweet == '1' ) {
      $checked = " checked='checked'";
    }
    ?>
    <input type="checkbox" name="qm-settings[enable_tweet]" id="qm-settings[enable_tweet]" value="1"<?php echo $checked; ?> />
    <?php
	}

	/**
	 * Generates Setting Field For Style
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public function chosen_style_field()
	{
		$settings = (array) get_option( 'qm-settings' );
		$chosen_style = 'default';
		if (isset($settings['chosen_style']))
		{
			$chosen_style = $settings['chosen_style'];
		}
    ?>
    <input type="radio" name="qm-settings[chosen_style]" id="chosen_style_default" value="default" <?php if ( $chosen_style == 'default' ) { echo 'checked="checked"'; } ?>><label for="chosen_style_default">Default</label><br />
    <input type="radio" name="qm-settings[chosen_style]" id="chosen_style_custom" value="custom" <?php if ( $chosen_style == 'custom' ) { echo 'checked="checked"'; } ?>><label for="chosen_style_custom">Custom (defined below)</label><br />
    <?php
	}

	/**
	 * Generates Settings Page
	 *
	 * @since 4.1.0
	 * @return void
	 */
	public static function display_page()
	{
    wp_enqueue_style( 'qm_admin_style', plugins_url( '../css/admin.css' , __FILE__ ) );
		?>
		<div class="wrap">
        <h2>Settings</h2>
        <form action="options.php" method="POST">
            <?php settings_fields( 'qm-settings-group' ); ?>
            <?php do_settings_sections( 'qm_global_settings' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
		<?php
	}
}

$qmGlobalSettingsPage = new QMGlobalSettingsPage();
?>
