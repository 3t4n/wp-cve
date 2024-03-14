<?php
require_once( dirname( __FILE__ ) . '/class-ib-options.php' );
/**
 * Class to handle the settings page for the Instant Breadcrumbs plugin.
 *
 * @since 1.0
 */
class Instant_Breadcrumbs_Settings
{
	public static function add_page() {
		add_theme_page(
				__( 'Instant Breadcrumbs Settings', 'instant-breadcrumbs' ),
				__( 'Breadcrumbs', 'instant-breadcrumbs' ),
				'manage_options', 'instant-breadcrumbs', array( 'Instant_Breadcrumbs_Settings', 'options_page' )
				);
	}
	public static function options_page() {
		?>
		<div>
		<h2><?php _e( 'Instant Breadcrumbs Plugin Settings', 'instant-breadcrumbs' ); ?></h2>
		<?php
		$open  = "<a href='http://loseyourmarbles.co/instant-breadcrumbs/contribute'>";
		$close = '</a>';
		$image = plugins_url( '/images/flattr-badge-large.png', dirname( __FILE__ ) );
		echo "<p><a href='http://loseyourmarbles.co/instant-breadcrumbs/contribute'><img style='padding-right:15px;' src='" . esc_attr( $image ) . "'/></a>";
		printf( __( 'If you find Instant Breadcrumbs useful, %splease consider making a contribution with Flattr%s to help support the continued development and improvement of the plugin. Thank you.', 'instant-breadcrumbs' ), $open, $close );
		echo '</p>';
		?>
		<?php if ( isset( $_GET['settings-updated'] ) ) { ?>
			<div class='updated'><p>
				<?php _e( 'Changes saved.', 'instant-breadcrumbs' ); ?>
		    	</p></div>
		<?php } ?>
		<form action="options.php" method="post">
		<?php settings_fields( 'ib-options' ); ?>
		<?php self::do_settings_sections( 'instant-breadcrumbs' ); ?>
		<input name="Submit" type="submit" value="<?php esc_attr_e( 'Save Changes', 'instant-breadcrumbs' ); ?>" />
		</form></div>
		<script type="text/javascript">
			function generatorChange() {
				var select = document.getElementById('ib-gen');
				var value = select.options[select.selectedIndex].value;
				var div = document.getElementById('instant-breadcrumbs-generator');
				if(value == 'builtin')
					div.style.display = 'block';
				else
					div.style.display = 'none';
			}
			generatorChange();
		</script>
		<?php
	}
	public static function admin_init() {
		register_setting( 'ib-options', 'ib-options', array( 'Instant_Breadcrumbs_Settings', 'validate' ) );
		add_settings_section(
			'instant-breadcrumbs-which', __( 'Specify Breadcrumb Generator', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'specify_text' ), 'instant-breadcrumbs'
			);
		add_settings_field(
			'ib-gen', __( 'Use breadcrumbs from:', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'gen_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-which'
			);
		add_settings_field(
			'ib-auto', __( 'Automatically add breadcrumbs to menu?', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'auto_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-which'
			);
		add_settings_field(
			'ib-location', __( 'Theme location (leave empty for first menu)', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'location_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-which'
			);
		add_settings_section(
			'instant-breadcrumbs-generator', __( 'Breadcrumb Generator Settings', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'generator_text' ), 'instant-breadcrumbs'
			);
		add_settings_field(
			'ib-front', __( 'Breadcrumb Title for Front Page', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'front_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
		add_settings_field(
			'ib-pages', __( 'Breadcrumb Title for Recent Posts', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'pages_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
		add_settings_field(
			'ib-archive', __( 'Default Breadcrumb Title for Archives', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'archive_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
		add_settings_field(
			'ib-notfound', __( 'Breadcrumb Title for 404', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'notfound_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
		add_settings_field(
			'ib-current', __( 'Default Breadcrumb Title for Current Page', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'current_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
		add_settings_field(
			'ib-strip', __( 'Strip HTML Tags?', 'instant-breadcrumbs' ),
			array( 'Instant_Breadcrumbs_Settings', 'strip_field' ), 'instant-breadcrumbs', 'instant-breadcrumbs-generator'
			);
	}
	public static function specify_text() {
		?>
		<p>
		<?php _e( 'You may use the built-in breadcrumb generator or another supported breadcrumb plugin.', 'instant-breadcrumbs' ); ?>
		</p>
		<?php
	}
	public static function generator_text() {
		?>
		<p>
		<?php _e( 'Specify breadcrumb titles for the following pages. Defaults only apply if the page itself does not have a better name.', 'instant-breadcrumbs' ); ?>
		</p>
		<?php
	}
	public static function gen_field() {
		?>
		<select id='ib-gen' name='ib-options[gen]' onchange='generatorChange()'>
		<?php
		$options = IB_Options::safe_select( 'gen' );
		$value   = $options['value'];
		foreach ( $options['values'] as $item ) {
			echo '<option value="' . esc_attr( $item['value'] ) . '"';
			if ( $item['value'] == $value) echo ' selected="selected"';
			echo '>' . esc_html( $item['text'] ) . '</option>';
		}
		?>
		</select>
		<?php
	}
	public static function auto_field() {
		if ( IB_Options::safe_boolean( 'auto' ) ) { ?>
			<input id='ib-auto' name='ib-options[auto]' type='checkbox' value='on' checked='checked'/>
		<?php } else { ?>
			<input id='ib-auto' name='ib-options[auto]' type='checkbox' value='on'/>
		<?php }
	}
	public static function location_field() {
		?>
		<input id='ib-location' name='ib-options[location]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'location' ) ); ?>'/>
		<?php
	}
	public static function front_field() {
		?>
		<input id='ib-front' name='ib-options[front]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'front' ) ); ?>'/>
		<?php
	}
	public static function pages_field() {
		?>
		<input id='ib-pages' name='ib-options[pages]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'pages' ) ); ?>'/>
		<?php
	}
	public static function archive_field() {
		?>
		<input id='ib-archive' name='ib-options[archive]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'archive' ) ); ?>'/>
		<?php
	}
	public static function notfound_field() {
		?>
		<input id='ib-notfound' name='ib-options[notfound]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'notfound' ) ); ?>'/>
		<?php
	}
	public static function current_field() {
		?>
		<input id='ib-current' name='ib-options[current]' size='32' type='text' value='<?php echo esc_attr( IB_Options::safe_string( 'current' ) ); ?>'/>
		<?php
	}
	public static function strip_field() {
		if ( IB_Options::safe_boolean( 'strip' ) ) { ?>
			<input id='ib-strip' name='ib-options[strip]' type='checkbox' value='on' checked='checked'/>
		<?php } else { ?>
			<input id='ib-strip' name='ib-options[strip]' type='checkbox' value='on'/>
		<?php }
	}
	public static function validate( $input ) {
		$options = get_option( 'ib_options' );
		if ( isset( $input['gen'] ) ) {
			$value  = $input['gen'];
			$values = IB_Options::safe_option_values( 'gen' );
			$index  = array_search( $value, $values['list'] );
			if ($index !== FALSE) $options['gen'] = $value;
		}
		$options['auto'] = isset( $input['auto'] );
		if ( isset( $input['location'] ) ) {
			$value = trim( $input['location'] );
			if ( strlen( $value ) <= 32 ) {
				$options['location'] = $value;
			}
		}
		if ( isset( $input['front'] ) ) {
			$value = trim( $input['front'] );
			if ( strlen( $value ) <= 32 ) {
				$options['front'] = $value;
			}
		}
		if ( isset( $input['pages'] ) ) {
			$value = trim( $input['pages'] );
			if ( strlen( $value ) <= 32 ) {
				$options['pages'] = $value;
			}
		}
		if ( isset( $input['archive'] ) ) {
			$value = trim( $input['archive'] );
			if ( strlen( $value ) <= 32 ) {
				$options['archive'] = $value;
			}
		}
		if ( isset( $input['notfound'] ) ) {
			$value = trim( $input['notfound'] );
			if ( strlen( $value ) <= 32 ) {
				$options['notfound'] = $value;
			}
		}
		if ( isset( $input['current'] ) ) {
			$value = trim( $input['current'] );
			if ( strlen( $value ) <= 32 ) {
				$options['current'] = $value;
			}
		}
		$options['strip'] = isset( $input['strip'] );
		return $options;
	}

	/**
	 * Override of do_settings_sections to wrap each in an id'd div.
	 *
	 * @since 1.1
	 */
	private static function do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
		if ( ! isset( $wp_settings_sections[$page] ) ) return;
		foreach ( (array) $wp_settings_sections[$page] as $section ) {
			echo '<div id="' . esc_attr( $section['id'] ) . '">';
			if ( $section['title'] ) echo '<h3>' . esc_html( $section['title'] ) . '</h3>';
			if ( $section['callback'] ) call_user_func( $section['callback'], $section );
			if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[$page] ) || ! isset( $wp_settings_fields[$page][$section['id']] ) )
				continue;
			echo '<table class="form-table">';
			do_settings_fields( $page, $section['id'] );
			echo '</table></div>';
		}
	}
}
