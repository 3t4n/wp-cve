<?php	
class Help {
	
	public static $key = 'emma_help';
	
	function __construct() {
		add_action( 'admin_init', array( &$this, 'register_settings' ) );
	}
	
	function register_settings() {
		// register_setting( $option_group, $option_name, $sanitize_callback );
		register_setting( self::$key, self::$key, array( &$this, 'sanitize_help_settings' ) );
		
		// add_settings_section( $id, $title, $callback, $page );
		add_settings_section( 'section_help', 'Help and Setup Information', array( &$this, 'section_help_desc' ), self::$key );	
	}
	
	function section_help_desc() { ?>
		
		<h3><strong>ACCOUNT INFORMATION TAB</strong></h3>
		<p><strong>Account Login Information:</strong></p>
		<p>Log into your <a href="http://myemma.com/login/" title="Email Marketing Services - Email Marketing Software - Email Marketing | Emma, Inc." target="_blank">Emma account</a> and click on settings button in the upper right hand of your Emma dashboard. Then click "Settings & billing". This will take you to your "Manage your account settings" page. In the Account settings section, the fifth tab is API key. Click on Generate new key to create your API key.</p>
		<p>Once you create the key, you will need to copy your <strong>Account ID</strong>, <strong>Public API Key</strong>, and <strong>Private API</strong> into the corresponding fields in the plugin.</p>
		<p>The plugin will now be able to connect your WordPress site to your Emma account.</p>
		<p><strong>Signup ID (optional) -</strong> Add this ID to target messages based on the signup form members used to join your audience. Click <a href="https://support.e2ma.net/Resource_Center/Account_how-to/customizing-your-signup-form#publish" target="_blank">here</a> for more information.</p>
		<p><strong>Add New Members to Group (optional) –</strong> assign all submissions to a specific group within your Emma account.</p>
		<br />
		
		<h3><strong>FORM SETUP TAB</strong></h3>
		<p><strong>Form Fields -</strong> select which fields you would like to display.</p>
		<p><strong>Set Form Width -</strong> set the width of your form.</p>
		<p><strong>Form Placeholders -</strong> customize the placeholder messages within each field.</p>
		<p><strong>Confirmation Messages –</strong> customize the messages that displays under the form after it has been submitted.</p>
		<p><strong>Confirmation Email –</strong> select whether or not to send a confirmation email, then specify the subject and message of the confirmation email.</p>
		<br />
		
		<h3><strong>FORM CUSTOMIZATION TAB</strong></h3>
		<p><strong>Form Layout –</strong> select how you would like the form to be displayed on your site.</p>
		<p><strong>Form Fields Customization –</strong> customize the styles of your form fields including border width, border color, border type, text color and background color.</p>
		<p><strong>Submit Button –</strong> customize the styles of your form’s submit button including width, text color, background color, border width, border color and border type.</p>
		<p><strong>Submit Button Hover State Customization –</strong> customize the styles of your form’s submit button when users hover on it.</p>
		
		<br />
		<h3>DISPLAYING THE FORM ON YOUR SITE</h3>
		<p>To insert the form as a <strong>widget</strong> on your sidebar, go to Appearance -> Widgets and then move the “Emma for Wordpress Subscription Form” to the widget area where you want the form to appear.</p>
		<p>To insert the form as a <strong>shortcode</strong> within your site, insert [emma_form] within your text editor where you want the form to appear.</p>
	
	<?php }
	
	function sanitize_help_settings() {
		// nothing to sanitize here folks, move along...
	}
	
}