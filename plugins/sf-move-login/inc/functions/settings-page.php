<?php
defined( 'ABSPATH' ) || die( 'Cheatin\' uh?' );

/*------------------------------------------------------------------------------------------------*/
/* !ASSETS ====================================================================================== */
/*------------------------------------------------------------------------------------------------*/

add_action( 'admin_enqueue_scripts', 'sfml_enqueue_settings_assets' );
/**
 * Enqueue assets for the settings page.
 *
 * @since 2.5.3
 */
function sfml_enqueue_settings_assets() {
	$suffix  = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
	$version = $suffix ? SFML_VERSION : time();

	wp_enqueue_script( 'move-login-settings',  plugin_dir_url( SFML_FILE ) . 'assets/js/settings' . $suffix . '.js', array( 'jquery' ), $version, true );

	wp_localize_script( 'move-login-settings', 'sfml', array(
		'nonce'       => wp_create_nonce( 'sfml_sanitize_slug' ),
		'error'       => __( 'Error', 'sf-move-login' ),
		'errorReload' => __( 'Error. Please reload the page.', 'sf-move-login' ),
		/* translators: %s is an URL slug name. */
		'forbidden'   => _n( 'The slug %s is forbidden.', 'The slugs %s are forbidden.', 1, 'sf-move-login' ),
		'duplicate'   => _x( 'Duplicate.', 'adjective', 'sf-move-login' ),
	) );
}


add_action( 'admin_print_scripts-settings_page_move-login', 'sfml_print_settings_css' );
/**
 * Print some CSS on the settings page.
 *
 * @since 2.5.3
 */
function sfml_print_settings_css() {
	echo '<style>.dynamic-login-url-slug-error, .sfml-warning { color: red; }</style>';
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS PAGE =============================================================================== */
/*------------------------------------------------------------------------------------------------*/

/**
 * Add settings fields and sections.
 */
function sfml_settings_fields() {
	$instance  = SFML_Options::get_instance();
	$labels    = $instance->get_field_labels( 'slugs' );
	$defaults  = sfml_get_default_options();
	$options   = sfml_get_options();
	$login_url = site_url( '%%slug%%', 'login' );

	// Sections.
	add_settings_section( 'slugs',  __( 'Choose your new URLs', 'sf-move-login' ), false, SFML_Options::OPTION_PAGE );
	add_settings_section( 'access', __( 'Access', 'sf-move-login' ), false, SFML_Options::OPTION_PAGE );

	// Fields.
	foreach ( $labels as $slug => $label ) {
		if ( ! isset( $options[ 'slugs.' . $slug ] ) ) {
			continue;
		}

		// Slugs.
		add_settings_field(
			'slugs-' . $slug,
			$label,
			'sfml_text_field',
			SFML_Options::OPTION_PAGE,
			'slugs',
			array(
				'label_for'      => 'slugs-' . $slug,
				'name'           => 'slugs.' . $slug,
				'value'          => $options[ 'slugs.' . $slug ],
				'default'        => $slug,
				'default_hidden' => true,
				'after'          => "&#160;\n" . '<em class="hide-if-no-js">' . str_replace( '%%slug%%', '<strong id="dynamic-login-url-slug-' . $slug . '" class="dynamic-login-url-slug">' . $options[ 'slugs.' . $slug ] . '</strong>', $login_url ) . '</em> <span id="dynamic-login-url-slug-error-' . $slug . '" class="dynamic-login-url-slug-error"></span>',
				'attributes'     => array(
					'class'       => 'slug-field',
					'title'       => __( 'Only lowercase letters, digits, - and _', 'sf-move-login' ),
					'placeholder' => $slug,
				),
			)
		);
	}

	// Deny access to login form.
	add_settings_field(
		'deny_wp_login_access',
		'<code>wp-login.php</code>',
		'sfml_radio_field',
		SFML_Options::OPTION_PAGE,
		'access',
		array(
			'label_for' => 'deny_wp_login_access',
			'value'     => $options['deny_wp_login_access'],
			'default'   => $defaults['deny_wp_login_access'],
			'values'    => $instance->get_field_labels( 'deny_wp_login_access' ),
			'label'     => '<strong>' . __( 'When a logged out user attempts to access the old login page.', 'sf-move-login' ) . '</strong>',
		)
	);

	// Deny redirect to the login page.
	add_settings_field(
		'deny_admin_access',
		_x( 'Redirects', 'noun', 'sf-move-login' ),
		'sfml_radio_field',
		SFML_Options::OPTION_PAGE,
		'access',
		array(
			'label_for' => 'deny_admin_access',
			'value'     => $options['deny_admin_access'],
			'default'   => $defaults['deny_admin_access'],
			'values'    => $instance->get_field_labels( 'deny_admin_access' ),
			'label'     => '<strong>' . __( 'Instead of redirecting a logged out user to the new login page:', 'sf-move-login' ) . '</strong>',
		)
	);
}


/**
 * If the parent page is 'options-general.php', WP will automaticaly use settings_errors().
 * But the alerts will be displayed before the page title, and then some JS will move it after the title.
 * Under some circumstances (large page, slow browser), the "swap" won't happen fast enough, and the user will see it.
 * For the settings in the network admin, settings_errors() is not used at all.
 * So I prefer use my own settings_errors(), displayed AFTER the title.
 */
function sfml_shunt_options_settings_errors() {
	global $parent_file;
	// Prevent wp-admin/options-head.php to be included.
	$parent_file .= '#sfml'; // WPCS: override ok.
}


/**
 * The settings page.
 */
function sfml_settings_page() {
	global $wp_version;
	?>
	<div class="wrap">
		<?php
		// WordPress 4.3 uses a `<h1>` tag, not a `<h2>` anymore. In the same time, get rid of the old icon.
		if ( version_compare( $wp_version, '4.3-RC1' ) >= 0 ) {
			echo '<h1>Move Login</h1>';
		} else {
			call_user_func( 'screen_icon', 'tools' );
			echo '<h2>Move Login</h2>';
		}

		require( ABSPATH . 'wp-admin/options-head.php' ); ?>

		<form name="<?php echo SFML_Options::OPTION_PAGE ?>" method="post" action="<?php echo esc_url( is_multisite() ? admin_url( 'admin-post.php' ) : admin_url( 'options.php' ) ); ?>" id="<?php echo SFML_Options::OPTION_PAGE ?>">
			<?php
			do_settings_sections( SFML_Options::OPTION_PAGE );
			settings_fields( SFML_Options::OPTION_GROUP );
			submit_button();
			?>
		</form>

		<?php sfml_rewrite_rules_textarea(); ?>
	</div>
	<?php
}


/*------------------------------------------------------------------------------------------------*/
/* !SETTINGS FIELDS ============================================================================= */
/*------------------------------------------------------------------------------------------------*/

/**
 * Text field.
 *
 * @param (array) $args Arguments.
 */
function sfml_text_field( $args ) {
	$id      = ! empty( $args['label_for'] )  ? esc_attr( $args['label_for'] )              : false;
	$name    = ! empty( $args['name'] )       ? esc_attr( $args['name'] )                   : $id;
	$value   = isset( $args['value'] )        ? esc_attr( $args['value'] )                  : '';
	$default = isset( $args['default'] )      ? esc_attr( $args['default'] )                : null;
	$atts    = ! empty( $args['attributes'] ) ? sfml_build_html_atts( $args['attributes'] ) : '';
	$after   = ! empty( $args['after'] )      ? $args['after']                              : '';

	if ( ! $name ) {
		return;
	}

	printf(
		'<input type="text" name="%s"%s%s value="%s"/>',
		SFML_Options::OPTION_NAME . '[' . $name . ']',
		$id ? ' id="' . $id . '"' : '',
		$atts,
		$value
	);

	if ( isset( $default ) ) {
		$class = ! empty( $args['default_hidden'] ) ? 'screen-reader-text' : 'description';
		/* translators: %s is a default option value. */
		echo ' <span class="' . $class . '">' . sprintf( _x( '(default: %s)', 'default value', 'sf-move-login' ), $default ) . '</span>';
	}

	echo $after;
}


/**
 * Radio field.
 *
 * @param (array) $args Arguments.
 */
function sfml_radio_field( $args ) {
	$label_for = ! empty( $args['label_for'] ) ? esc_attr( $args['label_for'] ) : false;
	$name      = ! empty( $args['name'] )      ? esc_attr( $args['name'] )      : $label_for;
	$id        = $label_for                    ? $label_for                     : 'radio-' . $name;
	$value     = isset( $args['value'] )       ? $args['value']                 : '';
	$values    = isset( $args['values'] )      ? $args['values']                : false;
	$default   = isset( $args['default'] )     ? $args['default']               : null;
	$label     = isset( $args['label'] )       ? $args['label']                 : '';

	if ( ! $name || ! $values || ! is_array( $values ) ) {
		return;
	}

	if ( ! is_null( $default ) && ! isset( $values[ $value ] ) ) {
		$value = $default;
	}

	$i = 0;
	echo $label ? '<label for="' . $id . '">' . $label . '</label><br/>' : '';

	foreach ( $values as $input_value => $input_label ) {
		printf(
			'<input type="radio" name="%s" id="%s"%s value="%s"/>',
			SFML_Options::OPTION_NAME . '[' . $name . ']',
			$id . ( $i ? '-' . $i : '' ),
			$input_value === $value ? ' checked="checked"' : '',
			esc_attr( $input_value )
		);
		echo '<label for="' . $id . ( $i ? '-' . $i : '' ) . '">' . $input_label . '</label><br/>';
		$i++;
	}

	if ( isset( $default ) && isset( $values[ $default ] ) ) {
		$class = ! empty( $args['default_hidden'] ) ? 'screen-reader-text' : 'description';
		/* translators: %s is a default option value. */
		echo ' <span class="' . $class . '">' . sprintf( _x( '(default: %s)', 'default value', 'sf-move-login' ), $values[ $default ] ) . '</span>';
	}
}


/**
 * A textarea displaying the rewrite rules.
 */
function sfml_rewrite_rules_textarea() {
	sfml_include_rewrite_file();

	$rules = sfml_rules();

	// Message.
	$base              = wp_parse_url( trailingslashit( get_option( 'home' ) ) );
	$base              = $base['path'];
	$document_root_fix = str_replace( '\\', '/', realpath( $_SERVER['DOCUMENT_ROOT'] ) );
	$abspath_fix       = str_replace( '\\', '/', ABSPATH );
	$home_path         = strpos( $abspath_fix, $document_root_fix ) === 0 ? $document_root_fix . $base : sfml_get_home_path();

	// IIS.
	if ( sfml_is_iis7() ) {
		$file          = 'web.config';
		$file_content  = implode( "\n", sfml_iis7_rewrite_rules( $rules ) );

		$height        = 20;
		$content       = sprintf(
			/* translators: 1 is a file name, 2 is a file path, 3 and 4 are small parts of code. */
			__( 'If the plugin fails to add the new rewrite rules to your %1$s file, add the following to your %1$s file in %2$s, replacing other %3$s rules if they exist, <strong>above</strong> the line reading %4$s:', 'sf-move-login' ),
			"<code>$file</code>",
			"<code>$home_path</code>",
			'<strong>Move Login</strong>',
			'<code>&lt;rule name="WordPress Rule 1" stopProcessing="true"&gt;</code>'
		);
	}
	// Nginx.
	elseif ( sfml_is_nginx() ) {
		$file          = 'nginx.conf';
		$file_content  = implode( "\n", sfml_nginx_rewrite_rules( $rules ) );

		$height        = substr_count( $file_content, "\n" );
		$content       = '<span class="sfml-warning">' . sprintf(
			/* translators: 1 is a file name, 2 is a small part of code. */
			__( 'The plugin can\'t add the new rewrite rules to your %1$s file by itself, you will need to add them manually inside the %2$s block.', 'sf-move-login' ),
			"<code>$file</code>",
			'<code>server</code>'
		) . '</span>';
		// Don't check if the file is writable.
		$file          = false;
	}
	// Apache.
	elseif ( sfml_is_apache() ) {
		$file          = '.htaccess';
		$file_content  = "\n# BEGIN SF Move Login\n";
		$file_content .= implode( "\n", sfml_apache_rewrite_rules( $rules ) );
		$file_content .= "\n# END SF Move Login\n";

		$height        = substr_count( $file_content, "\n" );
		$content       = sprintf(
			/* translators: 1 is a file name, 2 is a file path, 3 and 4 are small parts of code. */
			__( 'If the plugin fails to add the new rewrite rules to your %1$s file, add the following to your %1$s file in %2$s, replacing other %3$s rules if they exist, <strong>above</strong> the line reading %4$s:', 'sf-move-login' ),
			"<code>$file</code>",
			"<code>$home_path</code>",
			'<strong>Move Login</strong>',
			'<code># BEGIN WordPress</code>'
		);
	}
	// Not supported.
	else {
		return;
	}

	// Add a warning if the file is not writable.
	if ( $home_path && $file && ! wp_is_writable( $home_path . $file ) ) {
		$content .= '</p><p class="sfml-warning">' . sprintf(
			/* translators: %s is a file name. */
			__( 'Your %s file is not writable.', 'sf-move-login' ),
			"<code>$file</code>"
		);
	}

	// Add a warning if the plugin is bypassed.
	if ( defined( 'SFML_ALLOW_LOGIN_ACCESS' ) && SFML_ALLOW_LOGIN_ACCESS ) {
		/* translators: 1 is a constant name, 2 is a constant value. */
		$content .= '</p><p class="description">' . sprintf( __( 'The constant %1$s is defined to %2$s, the settings below won\'t take effect.', 'sf-move-login' ), '<code>SFML_ALLOW_LOGIN_ACCESS</code>', '<code>true</code>' );
	}

	$content  = '<p>' . $content . ' <button type="button" id="sfml-file-button" class="button-secondary hide-if-no-js" style="vertical-align:baseline">' . __( 'Show' ) . "</button></p>\n";
	$content .= '<textarea id="sfml-file-content" class="code readonly hide-if-js" readonly="readonly" cols="120" rows="' . $height . '">' . esc_textarea( $file_content ) . "</textarea>\n";
	$content .= '<script type="text/javascript">jQuery( "#sfml-file-button" ).on( "click", function() { jQuery( this ).remove(); jQuery( "#sfml-file-content" ).removeClass( "hide-if-js" ); } );</script>' . "\n";

	echo $content;
}
