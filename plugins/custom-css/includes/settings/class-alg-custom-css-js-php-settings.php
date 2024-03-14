<?php
/**
 * Custom CSS, JS & PHP - Settings Class
 *
 * @version 2.1.0
 * @since   1.0.0
 *
 * @author  Algoritmika Ltd.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'Alg_Custom_CSS_JS_PHP_Settings' ) ) :

class Alg_Custom_CSS_JS_PHP_Settings {

	/**
	 * Constructor.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (feature) add option to set custom PHP file path (i.e. dir and name) (instead of `uploads_dir/alg-custom-php/custom-php.php`)
	 * @todo    [next] (feature) implement CSS, JS, PHP editors instead of simple textareas
	 * @todo    [next] (dev) allow "tab" in content
	 * @todo    [next] (dev) `require_once( untrailingslashit( plugin_dir_path( ALG_CCJP_PLUGIN_FILE ) ) . '/includes/lib/csstidy-1.3/class.csstidy.php' );` and `$this->csstidy = new csstidy();`
	 */
	function __construct() {
		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'save_options' ) );
			add_action( 'admin_menu', array( $this, 'add_plugin_options_pages' ) );
		}
	}

	/*
	 * add_plugin_options_pages.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 */
	function add_plugin_options_pages() {
		add_submenu_page(
			'tools.php',
			__( 'Custom CSS', 'custom-css' ),
			__( 'Custom CSS', 'custom-css' ),
			'manage_options',
			'alg-custom-css',
			array( $this, 'create_plugin_options_page_css' )
		);
		add_submenu_page(
			'tools.php',
			__( 'Custom JS', 'custom-css' ),
			__( 'Custom JS', 'custom-css' ),
			'manage_options',
			'alg-custom-js',
			array( $this, 'create_plugin_options_page_js' )
		);
		add_submenu_page(
			'tools.php',
			__( 'Custom PHP', 'custom-css' ),
			__( 'Custom PHP', 'custom-css' ),
			'manage_options',
			'alg-custom-php',
			array( $this, 'create_plugin_options_page_php' )
		);
	}

	/**
	 * sanitize_content.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 *
	 * @todo    [next] (dev) `$this->csstidy->parse( $value );` and `$this->csstidy->print->plain();` (but preserve spaces at line start, disable optimization etc.)
	 */
	function sanitize_content( $value ) {
		return stripslashes( $value );
	}

	/**
	 * save_options.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 */
	function save_options() {
		if ( isset( $_POST['alg_ccjp_submit'] ) ) {
			$section = sanitize_text_field( $_POST['alg_ccjp_submit'] );
			// Saving options
			foreach ( $this->get_settings( $section ) as $settings ) {
				if ( in_array( $settings['type'], array( 'title', 'sectionend' ) ) ) {
					continue;
				}
				$id = ALG_CCJP_ID . '_' . $settings['id'];
				switch ( $settings['type'] ) {
					case 'checkbox':
						$value = ( isset( $_POST[ $id ] ) ? 'yes' : 'no' );
						break;
					default: // 'textarea', 'select':
						$value = ( isset( $_POST[ $id ] ) ? $this->sanitize_content( $_POST[ $id ] ) : '' );
						break;
				}
				update_option( $id, $value );
			}
			// (Re)creating / removing PHP file
			if ( 'php' == $section ) {
				$this->handle_custom_php_file();
			}
			// The end
			wp_safe_redirect( remove_query_arg( 'alg_disable_custom_php' ) );
			exit;
		}
	}

	/**
	 * handle_custom_php_file.
	 *
	 * @version 2.0.0
	 * @since   2.0.0
	 */
	function handle_custom_php_file() {
		$do_clean_up = false;
		if ( 'yes' === get_alg_ccjp_option( 'php_enabled', 'no' ) ) {
			$file_content = get_alg_ccjp_option( 'php', '' );
			if ( '' !== $file_content ) {
				$file_path = alg_ccjp()->core->get_custom_php_file_path( true );
				file_put_contents( $file_path, '<?php' . PHP_EOL . $file_content );
			} else {
				$do_clean_up = true;
			}
		} else {
			$do_clean_up = true;
		}
		if ( $do_clean_up ) {
			$file_path = alg_ccjp()->core->get_custom_php_file_path();
			if ( file_exists( $file_path ) ) {
				unlink( $file_path );
			}
			$dir_path  = alg_ccjp()->core->get_custom_php_file_path( false, true );
			if ( file_exists( $dir_path ) ) {
				rmdir( $dir_path );
			}
		}
	}

	/**
	 * create_plugin_options_page_css.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function create_plugin_options_page_css() {
		$this->create_plugin_options_page( 'css' );
	}

	/**
	 * create_plugin_options_page_js.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function create_plugin_options_page_js() {
		$this->create_plugin_options_page( 'js' );
	}

	/**
	 * create_plugin_options_page_php.
	 *
	 * @version 2.1.0
	 * @since   2.1.0
	 */
	function create_plugin_options_page_php() {
		$this->create_plugin_options_page( 'php' );
	}

	/**
	 * create_plugin_options_page.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 */
	function create_plugin_options_page( $section ) {
		$table_data = array();
		foreach ( $this->get_settings( $section ) as $i => $settings ) {
			if ( in_array( $settings['type'], array( 'title', 'sectionend' ) ) ) {
				if ( 'title' === $settings['type'] ) {
					if ( 0 != $i ) {
						$table_data[] = array( '', '<hr>' );
					}
					$table_data[] = array(
						'<h2 style="color:orange;">' . $settings['title'] . '</h2>',
						( isset( $settings['desc_tip'] ) ? '<em>' . $settings['desc_tip'] . '</em>' : '' ),
					);
				}
				continue;
			}
			$id          = ALG_CCJP_ID . '_' . $settings['id'];
			$saved_value = get_alg_ccjp_option( $settings['id'], $settings['default'] );
			switch ( $settings['type'] ) {
				case 'checkbox':
					$table_data[] = array(
						'<label for="' . $id . '"><strong>' . $settings['title'] . '</strong></label>',
						'<input ' . checked( $saved_value, 'yes', false ) . ' type="checkbox" value="1" id="' . $id . '" name="' . $id . '"> ' .
							'<label for="' . $id . '"><strong><em>' . $settings['desc'] . '</em></strong></label>',
					);
					break;
				case 'textarea':
					$table_data[] = array(
						'',
						( isset( $settings['desc_tip'] ) ? $settings['desc_tip'] : '' ) .
						'<textarea style="' . $settings['css'] . '" id="' . $id . '" name="' . $id . '">' . esc_textarea( $saved_value ) . '</textarea>' .
						( isset( $settings['desc'] ) ? '<em>' . $settings['desc'] . '</em>' : '' ),
					);
					break;
				case 'select':
					$select_options = '';
					foreach ( $settings['options'] as $option_id => $option_title ) {
						$select_options .= '<option value="' . $option_id . '" ' . selected( $option_id, $saved_value, false ) . '>' . $option_title . '</option>';
					}
					$table_data[] = array(
						( isset( $settings['title'] ) ? '<strong>' . $settings['title'] . '</strong>' : '' ),
						( isset( $settings['desc_tip'] ) ? $settings['desc_tip'] : '' ) .
						'<select style="' . ( isset( $settings['css'] ) ? $settings['css'] : '' ) . '" id="' . $id . '" name="' . $id . '">' . $select_options . '</select>' .
						( isset( $settings['desc'] ) ? '<em>' . $settings['desc'] . '</em>' : '' ),
					);
					break;
			}
		}
		$menu = array();
		foreach ( array( 'css', 'js', 'php' ) as $_section ) {
			$menu[] = '<a style="text-decoration:none;' . ( $_section == $section ? 'font-weight:bold;' : '' ) . '" href="' . admin_url( 'tools.php?page=alg-custom-' . $_section ) . '">' .
				strtoupper( $_section ) . '</a>';
		}
		$html = '';
		$html .= '<div class="wrap">';
		$html .= '<h1>' . __( 'Custom CSS, JS & PHP', 'custom-css' ) . '</h1>';
		$html .= '<p style="font-style:italic;">' . __( 'Just another custom CSS, JavaScript & PHP tool for WordPress.', 'custom-css' ) . '</p>';
		$html .= '<p>' . implode( ' | ', $menu ) . '</p>';
		$html .= '<form action="' . add_query_arg( '', '' ) . '" method="post">';
		$html .= $this->get_table_html( $table_data, array(
			'table_style'        => 'width:100%;',
			'table_class'        => 'widefat striped',
			'table_heading_type' => 'vertical',
			'columns_styles'     => array( 'width:20%', 'width:80%' )
		) );
		$html .= '<p>' . '<button id="alg_ccjp_submit" name="alg_ccjp_submit" type="submit" class="button button-primary" value="' . $section . '">' .
			__( 'Save changes', 'custom-css' ) . '</button>' . '</p>';
		$html .= '</form>';
		$html .= '</div>';
		echo $html;
	}

	/**
	 * get_settings.
	 *
	 * @version 2.1.0
	 * @since   1.0.0
	 *
	 * @todo    [maybe] (desc) custom PHP: `if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly`
	 */
	function get_settings( $section ) {
		$textarea_style = 'width:100%; min-height:300px; font-family:Courier New,Courier,monospace; color: black;';
		if ( 'css' == $section ) {
			return array(
				array(
					'title'    => __( 'Custom CSS', 'custom-css' ),
					'type'     => 'title',
					'id'       => 'css_options',
				),
				array(
					'title'    => __( 'CSS', 'custom-css' ) . ': ' . __( 'front-end', 'custom-css' ),
					'desc'     => __( 'Enable custom front-end CSS', 'custom-css' ),
					'id'       => 'front_end_css_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'id'       => 'front_end_css_position',
					'default'  => 'head',
					'type'     => 'select',
					'options'  => array(
						'head'   => __( 'Load in header', 'custom-css' ),
						'footer' => __( 'Load in footer', 'custom-css' ),
					),
				),
				array(
					'desc_tip' => __( 'CSS code:', 'custom-css' ),
					'id'       => 'front_end_css',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => $textarea_style,
				),
				array(
					'title'    => __( 'CSS', 'custom-css' ) . ': ' . __( 'back-end (admin)', 'custom-css' ),
					'desc'     => __( 'Enable custom back-end CSS', 'custom-css' ),
					'id'       => 'back_end_css_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'id'       => 'back_end_css_position',
					'default'  => 'head',
					'type'     => 'select',
					'options'  => array(
						'head'   => __( 'Load in header', 'custom-css' ),
						'footer' => __( 'Load in footer', 'custom-css' ),
					),
				),
				array(
					'desc_tip' => __( 'CSS code:', 'custom-css' ),
					'id'       => 'back_end_css',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => $textarea_style,
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'css_options',
				),
			);
		} elseif ( 'js' == $section ) {
			return array(
				array(
					'title'    => __( 'Custom JavaScript', 'custom-css' ),
					'type'     => 'title',
					'id'       => 'js_options',
				),
				array(
					'title'    => __( 'JS', 'custom-css' ) . ': ' . __( 'front-end', 'custom-css' ),
					'desc'     => __( 'Enable custom front-end JS', 'custom-css' ),
					'id'       => 'front_end_js_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'id'       => 'front_end_js_position',
					'default'  => 'head',
					'type'     => 'select',
					'options'  => array(
						'head'   => __( 'Load in header', 'custom-css' ),
						'footer' => __( 'Load in footer', 'custom-css' ),
					),
				),
				array(
					'desc_tip' => __( 'JS code:', 'custom-css' ),
					'id'       => 'front_end_js',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => $textarea_style,
				),
				array(
					'title'    => __( 'JS', 'custom-css' ) . ': ' . __( 'back-end (admin)', 'custom-css' ),
					'desc'     => __( 'Enable custom back-end JS', 'custom-css' ),
					'id'       => 'back_end_js_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'id'       => 'back_end_js_position',
					'default'  => 'head',
					'type'     => 'select',
					'options'  => array(
						'head'   => __( 'Load in header', 'custom-css' ),
						'footer' => __( 'Load in footer', 'custom-css' ),
					),
				),
				array(
					'desc_tip' => __( 'JS code:', 'custom-css' ),
					'id'       => 'back_end_js',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => $textarea_style,
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'js_options',
				),
			);
		} elseif ( 'php' == $section ) {
			$disable_custom_php_link = admin_url( 'tools.php?page=alg-custom-php&alg_disable_custom_php' );
			return array(
				array(
					'title'    => __( 'Custom PHP', 'custom-css' ),
					'type'     => 'title',
					'id'       => 'php_options',
					'desc_tip' => sprintf(
						__( 'Please note that if you enable custom PHP and enter non-valid PHP code here, your site will become unavailable. To fix this you will have to add %s attribute to the URL (you must be logged as shop manager or admin (for this reason custom PHP code is not executed on %s page)).', 'custom-css' ),
							'<code>alg_disable_custom_php</code>', '<strong>wp-login.php</strong>' ) . ' ' .
						sprintf( __( 'E.g.: %s', 'custom-css' ), '<a href="' . $disable_custom_php_link . '">' . $disable_custom_php_link . '</a>' ),
				),
				array(
					'title'    => __( 'PHP', 'custom-css' ),
					'desc'     => __( 'Enable custom PHP', 'custom-css' ),
					'id'       => 'php_enabled',
					'default'  => 'no',
					'type'     => 'checkbox',
				),
				array(
					'desc_tip' => sprintf( __( 'PHP code (without the %s tag):', 'custom-css' ), '<code>' . esc_html( '<?php' ) . '</code>' ),
					'id'       => 'php',
					'default'  => '',
					'type'     => 'textarea',
					'css'      => 'width:100%; min-height:600px; font-family:Courier New,Courier,monospace; color: black;',
					'desc'     => ( file_exists( alg_ccjp()->core->get_custom_php_file_path() ) ?
						sprintf( __( 'Automatically created PHP file: %s', 'custom-css' ), '<code>' . alg_ccjp()->core->get_custom_php_file_path() . '</code>' ) : '' ),
				),
				array(
					'type'     => 'sectionend',
					'id'       => 'php_options',
				),
			);
		}
	}

	/**
	 * get_table_html.
	 *
	 * @version 2.0.0
	 * @since   1.0.0
	 */
	function get_table_html( $data, $args = array() ) {
		$defaults = array(
			'table_class'        => '',
			'table_style'        => '',
			'table_heading_type' => 'horizontal',
			'columns_classes'    => array(),
			'columns_styles'     => array(),
		);
		$args = array_merge( $defaults, $args );
		$table_class = ( '' == $args['table_class'] ? '' : ' class="' . $args['table_class'] . '"' );
		$table_style = ( '' == $args['table_style'] ? '' : ' style="' . $args['table_style'] . '"' );
		$html = '';
		$html .= '<table' . $table_class . $table_style . '>';
		$html .= '<tbody>';
		foreach( $data as $row_number => $row ) {
			$html .= '<tr>';
			foreach( $row as $column_number => $value ) {
				$th_or_td = ( ( 0 === $row_number && 'horizontal' === $args['table_heading_type'] ) || ( 0 === $column_number && 'vertical' === $args['table_heading_type'] ) ?
					'th' : 'td' );
				$column_class = ( isset( $args['columns_classes'][ $column_number ] ) ? ' class="' . $args['columns_classes'][ $column_number ] . '"' : '' );
				$column_style = ( isset( $args['columns_styles'][ $column_number ]  ) ? ' style="' . $args['columns_styles'][ $column_number ]  . '"' : '' );
				$html .= '<'  . $th_or_td . $column_class . $column_style . '>';
				$html .= $value;
				$html .= '</' . $th_or_td . '>';
			}
			$html .= '</tr>';
		}
		$html .= '</tbody>';
		$html .= '</table>';
		return $html;
	}

}

endif;

return new Alg_Custom_CSS_JS_PHP_Settings();
