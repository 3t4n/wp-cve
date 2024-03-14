<?php
/*
Plugin Name: Visual Form Builder - Custom Validation Messages
Description: Customize the jQuery validation messages for Visual Form Builder and Visual Form Builder Pro forms
Author: Matthew Muro
Author URI: http://vfb.matthewmuro.com
Version: 1.2
*/

/*
This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; version 2 of the License.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Instantiate new class
$vfb_custom_validation_load = new VFB_Custom_Validation();

// Visual Form Builder class
class VFB_Custom_Validation{

	/**
	 * Constructor. Register core filters and actions.
	 *
	 * @access public
	 */
	public function __construct(){

		$this->message_defaults = array(
			'required'		=> 'This field is required.',
			'email'			=> 'Please enter a valid email address.',
			'url'			=> 'Please enter a valid URL.',
			'date'			=> 'Please enter a valid date.',
			'number'		=> 'Please enter a valid number.',
			'digits'		=> 'Please enter only digits.',
			'equalTo'		=> 'Please enter the same value again.',
			'range'			=> 'Please enter a value between {0} and {1}.',
			'max'			=> 'Please enter a value less than or equal to {0}.',
			'min'			=> 'Please enter a value greater than or equal to {0}.',
			'accept'		=> 'Please enter a value with a valid extension.',
			'phone'			=> 'Please enter a valid phone number. Most US/Canada and International formats accepted.',

			// Pro fields only
			'username'		=> 'Please enter a valid username.',
			'ipv4'			=> 'Please enter a valid IP v4 address.',
			'ipv6'			=> 'Please enter a valid IP v6 address.',
			'rangeWords'	=> 'Please enter between {0} and {1} words.',
			'maxWords'		=> 'Please enter {0} words or less.',
			'minWords'		=> 'Please enter at least {0} words.',
			'maxlength'		=> 'Please enter no more than {0} characters.',
            'minlength'		=> 'Please enter at least {0} characters.',
            'rangelength'	=> 'Please enter a value between {0} and {1} characters long.',
		);

		// Add menu item
		add_action( 'admin_menu', array( &$this, 'admin_menu' ), 20 );

		// Find out which VFB is active
		add_action( 'admin_init', array( &$this, 'check_active_plugin' ) );

		// Add option, if needed
		add_action( 'plugins_loaded', array( &$this, 'add_option_check' ) );

		// Save options
		add_action( 'admin_init', array( &$this, 'save' ) );

		// Save notification
		add_action( 'admin_notices', array( &$this, 'admin_notices' ) );

		// Add inline styles to header
		add_action( 'wp_footer', array( &$this, 'output' ), 20 );
	}

	/**
	 * Check Active Plugin
	 *
	 * Determines which VFB plugin is active and will add
	 * a menu to the appropriate menu.  If the free version AND
	 * Pro versions are both active, add to the Pro
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function check_active_plugin() {
		$active = 'visual-form-builder';

		// If Free is active
		if ( is_plugin_active( 'visual-form-builder/visual-form-builder.php' ) && !is_plugin_active( 'visual-form-builder-pro/visual-form-builder-pro.php' ) )
			$active = 'visual-form-builder';
		// If Pro is active
		elseif ( !is_plugin_active( 'visual-form-builder/visual-form-builder.php' ) && is_plugin_active( 'visual-form-builder-pro/visual-form-builder-pro.php' ) )
			$active = 'visual-form-builder-pro';
		// If both are active
		elseif ( is_plugin_active( 'visual-form-builder/visual-form-builder.php' ) && is_plugin_active( 'visual-form-builder-pro/visual-form-builder-pro.php' ) )
			$active = 'visual-form-builder-pro';

		return $active;
	}

	/**
	 * Add admin menu
	 *
	 * Adds menu item to appropriate plugin
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function add_option_check() {
		// Add a database version to help with upgrades and run SQL install
		if ( !get_option( 'vfb_validation_messages' ) )
			update_option( 'vfb_validation_messages', $this->message_defaults );
	}

	/**
	 * Actions to save data
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function save() {

		if ( !isset( $_REQUEST['vfb-custom-validation-submit'] ) )
			return;

		check_admin_referer( 'vfb-custom-validation-messages' );

		$messages = get_option( 'vfb_validation_messages' );

		// Sanitize data
		$new_data = array_map( 'wp_kses_data', $_REQUEST['vfb-custom-validation'] );
		$new_data = array_map( 'esc_html', $new_data );

		$option = update_option( 'vfb_validation_messages', $new_data );
	}

	/**
	 * Display saving notification
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function admin_notices() {
		if ( !isset( $_REQUEST['vfb-custom-validation-submit'] ) )
			return;

		echo '<div id="message" class="updated"><p>' . __( 'The settings have been successfully saved.' , 'vfb-custom-validation') . '</p></div>';
	}

	/**
	 * Add admin menu
	 *
	 * Adds menu item to appropriate plugin
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function admin_menu() {
		add_submenu_page( $this->check_active_plugin(), __( 'Validation Messages', 'vfb-custom-validation' ), __( 'Validation Messages', 'vfb-custom-validation' ), 'manage_options', 'vfb-custom-validation', array( &$this, 'admin' ) );
	}

	/**
	 * Display Admin
	 *
	 * Displays the admin
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function admin() {

		// Get options, if they exist
		$options = get_option( 'vfb_validation_messages' );

		// Merge with the default messages in case some don't exist from DB
		$messages = wp_parse_args( $options, $this->message_defaults );
	?>
	<div class="wrap">
		<h2><?php _e( 'Validation Messages', 'vfb-custom-validation' ); ?></h2>
		<p><?php _e( 'Customize the default jQuery validation messages for all Visual Form Builder or Visual Form Builder Pro forms.', 'vfb-custom-validation' ); ?></p>
		<form method="post" id="vfb-custom-validation-messages">
		<?php wp_nonce_field( 'vfb-custom-validation-messages' ); ?>

			<table class="form-table">
				<tbody>
					<tr align="top">
						<th>
							<label for="vfb-message-required">
								<?php _e( 'Required', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[required]" id="vfb-message-required" class="regular-text" value="<?php echo $messages['required']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-email">
								<?php _e( 'Email', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[email]" id="vfb-message-email" class="regular-text" value="<?php echo $messages['email']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-url">
								<?php _e( 'URL', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[url]" id="vfb-message-url" class="regular-text" value="<?php echo $messages['url']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-date">
								<?php _e( 'Date', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[date]" id="vfb-message-date" class="regular-text" value="<?php echo $messages['date']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-number">
								<?php _e( 'Number', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[number]" id="vfb-message-number" class="regular-text" value="<?php echo $messages['number']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-digits">
								<?php _e( 'Digits', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[digits]" id="vfb-message-digits" class="regular-text" value="<?php echo $messages['digits']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-range">
								<?php _e( 'Range', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[range]" id="vfb-message-range" class="regular-text" value="<?php echo $messages['range']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} and {1} placeholders.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-max">
								<?php _e( 'Max', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[max]" id="vfb-message-max" class="regular-text" value="<?php echo $messages['max']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-min">
								<?php _e( 'Min', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[min]" id="vfb-message-min" class="regular-text" value="<?php echo $messages['min']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-file-upload">
								<?php _e( 'File Upload', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[accept]" id="vfb-message-file-upload" class="regular-text" value="<?php echo $messages['accept']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-phone">
								<?php _e( 'Phone', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[phone]" id="vfb-message-phone" class="regular-text" value="<?php echo $messages['phone']; ?>" />
						</td>
					</tr>
				</tbody>
			</table>

			<table id="vfb-validation-pro-only" class="form-table" style="display: <?php echo 'visual-form-builder-pro' == $this->check_active_plugin() ? 'block' : 'none'; ?>;">
				<tbody>
					<tr align="top">
						<th>
							<label for="vfb-message-username">
								<?php _e( 'Username', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[username]" id="vfb-message-username" class="regular-text" value="<?php echo $messages['username']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-ipv4">
								<?php _e( 'IPv4', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[ipv4]" id="vfb-message-ipv4" class="regular-text" value="<?php echo $messages['ipv4']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-ipv6">
								<?php _e( 'IPv6', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[ipv6]" id="vfb-message-ipv6" class="regular-text" value="<?php echo $messages['ipv6']; ?>" />
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-rangeWords">
								<?php _e( 'Range Words', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[rangeWords]" id="vfb-message-rangeWords" class="regular-text" value="<?php echo $messages['rangeWords']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} and {1} placeholders.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-maxWords">
								<?php _e( 'Max Words', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[maxWords]" id="vfb-message-maxWords" class="regular-text" value="<?php echo $messages['maxWords']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-minWords">
								<?php _e( 'Min Words', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[minWords]" id="vfb-message-minWords" class="regular-text" value="<?php echo $messages['minWords']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-minlength">
								<?php _e( 'Min Length', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[minlength]" id="vfb-message-minlength" class="regular-text" value="<?php echo $messages['minlength']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-maxlength">
								<?php _e( 'Max Length', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[maxlength]" id="vfb-message-maxlength" class="regular-text" value="<?php echo $messages['maxlength']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} placeholder.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
					<tr align="top">
						<th>
							<label for="vfb-message-rangelength">
								<?php _e( 'Range Length', 'vfb-custom-validation' ); ?>
							</label>
						</th>
						<td>
							<input type="text" name="vfb-custom-validation[rangelength]" id="vfb-message-rangelength" class="regular-text" value="<?php echo $messages['rangelength']; ?>" />
							<p class="description"><?php _e( 'Note: please ensure your message includes the {0} and {1} placeholders.', 'vfb-custom-validation' ); ?></p>
						</td>
					</tr>
				</tbody>
			</table>

			<?php submit_button( __( 'Save Changes', 'vfb-custom-validation' ), 'primary', 'vfb-custom-validation-submit' ); ?>
		</form>
	</div>
	<?php
	}

	/**
	 * Output the custom messages
	 *
	 * Prints messages in the footer
	 * after jQuery validation script has loaded
	 *
	 * @since 1.0
	 * @access public
	 *
	 */
	public function output() {

		// Checks if jQuery Form Validation plugin has printed on page
		$script_printed = wp_script_is( 'jquery-form-validation', 'done' );

		// If no jQuery Validation plugin, don't do anything
		if ( !$script_printed )
			return;

		// Setup output
		$output = array();

		// Get messages
		$options = get_option( 'vfb_validation_messages' );

		$messages = wp_parse_args( $options, $this->message_defaults );
?>
<script>
jQuery(document).ready(function() {
    jQuery.extend(jQuery.validator.messages, {
<?php
	foreach ( $messages as $key => $message ) :
		switch ( $key ) :
			case 'range' :
			case 'max' :
			case 'min' :
			case 'rangeWords' :
			case 'maxWords' :
			case 'minWords' :
			case 'rangelength' :
			case 'maxlength' :
			case 'minlength' :

				$output[] = sprintf( "\t\t" . $key . ': jQuery.validator.format("%s")', esc_html( $message ) );

				break;
			default :

				$output[] = sprintf( "\t\t" . $key . ': "%s"', esc_html( $message ) );

				break;
		endswitch;
	endforeach;

	echo implode( ",\n", $output );
?>
	});
});
</script>
<?php
	}
}