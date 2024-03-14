<?php
/**
 * Plugin Name: Nikan Base Plugin
 * Description: This is a basic plugin that runs custom pulgins of NikanCo. 
 * Version: 4.4
 * Author: NikanCo
 * Author URI: http://nikanadv.ir
 * Text Domain: nik-base
 * License: GPLv2 or later
 * Domain Path: /languages
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if ( ! defined( 'NIKAN_BASE_VERSION' ) ) {
	define( 'NIKAN_BASE_VERSION', 4.4 );
}

$nikan_base_version = get_option('nikan_base_version');
if ($nikan_base_version !== 4.4) {
	update_option('nikan_base_version',4.4);
}

if ( ! defined( 'NIKAN_PLUGIN_PATH' ) ) {
	define( 'NIKAN_PLUGIN_PATH', plugins_url( '/', __FILE__ ) );
}

load_plugin_textdomain('nik-base', FALSE, dirname(plugin_basename(__FILE__)).'/languages/');

class Nikan_Settings_Base {

	private $settings_sections = array();
	private $settings_fields = array();

	public function __construct() {
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
		add_action( 'admin_menu', array( $this, 'adminMenu' ), 0 );		
		add_action( 'wp_before_admin_bar_render', array( $this, 'nikan_add_wp_toolbar_menu' ) );
	}
	
	function nikan_add_wp_toolbar_menu() {

		global $wp_admin_bar;

		if ( current_user_can( 'edit_pages' ) ) {
			
			if ( ! is_admin() ) {
				$this->nikan_add_wp_toolbar_menu_item(
					esc_html__( 'Nikan Plugins','nik-base' ),
					false,
					admin_url( 'admin.php?page=nikan-plugins' ),
					[
						'class' => 'nikan-menu',
					],
					'nikan-plugins'
				);
			}

		}
	}
	
	function nikan_add_wp_toolbar_menu_item( $title, $parent = false, $href = '', $custom_meta = [], $custom_id = '' ) {

		global $wp_admin_bar;

		if ( current_user_can( 'edit_pages' ) ) {
			if ( ! is_super_admin() || ! is_admin_bar_showing() ) {
				return;
			}

			// Set custom ID.
			if ( $custom_id ) {
				$id = $custom_id;
			} else { // Generate ID based on $title.
				$id = strtolower( str_replace( ' ', '-', $title ) );
			}

			// Links from the current host will open in the current window.
			$meta = strpos( $href, site_url() ) !== false ? [] : [
				'target' => '_blank',
			]; // External links open in new tab/window.
			$meta = array_merge( $meta, $custom_meta );

			$wp_admin_bar->add_node(
				[
					'parent' => $parent,
					'id'     => $id,
					'title'  => $title,
					'href'   => $href,
					'meta'   => $meta,
				]
			);
		}

	}
	
	function adminMenu() {
		add_menu_page( __('Nikan Plugins','nik-base') , __('Nikan Plugins','nik-base') , 'manage_options', 'nikan-plugins', array($this,'settingPage'), 'dashicons-smiley', 3);
	}
	
	function settingPage() {
		?>
		<div class="nikan-wrap wrap">
			<div class="nikan-body">

				<header class="nikan-Header">
					<div class="nikan-Header-logo">
						<a href="http://nikanadv.ir" target="_blank"><img src="<?php echo NIKAN_PLUGIN_PATH.'assets/images/logo.png' ?>" alt="<?php echo _e('Nikan Plugins','nik-base'); ?>"></a>
					</div>
					<div class="nikan-Header-nav">
						<?php $this->show_navigation(); ?>
					</div>
					<div class="nikan-Header-footer"><?php echo esc_html( sprintf( __( 'version %s', 'nik-base' ), NIKAN_BASE_VERSION ) ); ?>
					</div>
				</header>

				<section class="nikan-Content">
					<?php $this->show_forms(); ?>
				</section>

				<aside class="nikan-Sidebar">
					
					<div class="nikan-Sidebar-notice"><?php echo _e('Each product has help file and you should read file before submit any ticket.','nik-base'); ?></div>
					
					<div class="nikan-documentation">
						<i class="nikan-icon-book"></i>
						<h3 class="nikan-title2"><?php echo _e('Useful Plugins','nik-base'); ?></h3>
						<p><?php echo _e('You can see other nikan plugins that created for more website performance.','nik-base'); ?></p>
						<a href="https://www.rtl-theme.com/author/nikan/" target="_blank" class="button"><?php echo _e('View Other Products','nik-base'); ?></a>
					</div>
				</aside>
			</div>
			<?php
			/**
			 * Fires after the Settings page content
			 *
			 * @since 3.5
			 */
			do_action( 'nikan_settings_page_footer' );
			?>
		</div>
		<?php
	}
	
	function settingSections() {
		
		$sections = array();

		return apply_filters( 'nikan_settings_sections', $sections );
	}
	
	function settingFields() {
		$settings_fields = array();

		return apply_filters( 'nikan_settings_section_content', $settings_fields );
	}

	function admin_enqueue_scripts() {
		
		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_media();
		wp_enqueue_script( 'wp-color-picker' );
		
		wp_enqueue_style( 'nikan-settings', plugins_url( '/assets/css/admin.css', __FILE__ ), '', NIKAN_BASE_VERSION, false );
	}

	function set_sections( $sections ) {
		$this->settings_sections = $sections;

		return $this;
	}

	function add_section( $section ) {
		$this->settings_sections[] = $section;

		return $this;
	}

	function set_fields( $fields ) {
		$this->settings_fields = $fields;

		return $this;
	}

	function add_field( $section, $field ) {
		$defaults                            = array(
			'name'  => '',
			'label' => '',
			'desc'  => '',
			'type'  => 'text'
		);
		$arg                                 = wp_parse_args( $field, $defaults );
		$this->settings_fields[ $section ][] = $arg;

		return $this;
	}

	function admin_init() {
		foreach ( $this->settings_sections as $section ) {
			if ( false == get_option( $section['id'] ) ) {
				add_option( $section['id'] );
			}
			if ( isset( $section['desc'] ) && ! empty( $section['desc'] ) ) {
				$section['desc'] = '<div class="inside">' . $section['desc'] . '</div>';
				$callback        = create_function( '', 'echo "' . str_replace( '"', '\"', $section['desc'] ) . '";' );
			} else {
				$callback = '__return_false';
			}
			add_settings_section( $section['id'], $section['title'], $callback, $section['id'] );
		}

		foreach ( $this->settings_fields as $section => $field ) {
			foreach ( $field as $option ) {
				$type = isset( $option['type'] ) ? $option['type'] : 'text';
				$args = array(
					'id'                => $option['name'],
					'desc'              => isset( $option['desc'] ) ? $option['desc'] : '',
					'name'              => $option['label'],
					'section'           => $section,
					'size'              => isset( $option['size'] ) ? $option['size'] : null,
					'options'           => isset( $option['options'] ) ? $option['options'] : '',
					'std'               => isset( $option['default'] ) ? $option['default'] : '',
					'sanitize_callback' => isset( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : '',
					'type'				=> $option['type'],
					'class'				=> isset( $option['class'] ) ? $option['class'] : null,
					'subtitle'          => isset( $option['subtitle'] ) ? $option['subtitle'] : '',
					'help'      	    => isset( $option['help'] ) ? $option['help'] : '',
				);
				add_settings_field( $section . '[' . $option['name'] . ']', $option['label'], array(
					$this,
					'callback_' . $type
				), $section, $section, $args );
			}
		}

		foreach ( $this->settings_sections as $section ) {
			register_setting( $section['id'], $section['id'], array( $this, 'sanitize_options' ) );
		}
	}

	function callback_text( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--text">
				<div class="nikan-text">
					<input type="text" id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="<?php echo $value; ?>" placeholder="<?php echo $args['std']; ?>">
				</div>
			</div>	
		</fieldset>
		<?php
	}

	function callback_checkbox( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['subtitle']) && $args['subtitle'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['subtitle']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--checkbox ">
				<div class="nikan-checkbox">
					<input type="hidden" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="off" />
					<input type="checkbox" id="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="on" <?php checked( $value, 'on', true ); ?> >
					<label for="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]"><?php echo $args['desc']; ?></label>
				</div>
			</div>	
		</fieldset>
		<?php
	}

	function callback_multicheck( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--checkbox ">
				<?php 
				foreach ( $args['options'] as $key => $label ) { 
					$checked = isset( $value[ $key ] ) ? $value[ $key ] : '0';
					?>
					<div class="nikan-checkbox">
						<input type="checkbox" id="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>][<?php echo $key; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>][<?php echo $key; ?>]" value="<?php echo $key; ?>" <?php checked( $checked, $key, true ); ?> >
						<label for="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>][<?php echo $key; ?>]"><?php echo $label; ?></label>
					</div>
				<?php } ?>
			</div>	
		</fieldset>
		<?php
	}
	
	public function callback_switch( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['subtitle']) && $args['subtitle'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['subtitle']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--checkbox ">
				<div class="nikan-radio nikan-radio--reverse nikan-radio--tips">
					<input type="hidden" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="off" />
					<input type="checkbox" id="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="on" <?php checked( $value, 'on', true ); ?> >
					<label for="nikan-<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]">
						<span data-l10n-active="<?php _e('On','nik-base'); ?>" data-l10n-inactive="<?php _e('Off','nik-base'); ?>" class="nikan-radio-ui"></span>
						<?php echo $args['desc']; ?>
					</label>
				</div>
			</div>	
		</fieldset>
		<?php
	}

	// no need, check again
	function callback_radio( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		$html  = '';
		foreach ( $args['options'] as $key => $label ) {
			$html .= sprintf( '<input type="radio" class="radio" id="wpuf-%1$s[%2$s][%3$s]" name="%1$s[%2$s]" value="%3$s"%4$s />', $args['section'], $args['id'], $key, checked( $value, $key, false ) );
			$html .= sprintf( '<label for="wpuf-%1$s[%2$s][%4$s]"> %3$s</label><br>', $args['section'], $args['id'], $label, $key );
		}
		$html .= sprintf( '<span class="description"> %s</label>', $args['desc'] );
		echo $html;
	}

	function callback_select( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--select">
				<div class="nikan-select">
					<select id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]">
					<?php foreach ( $args['options'] as $key => $label ) { ?>
						<option value="<?php echo $key; ?>" <?php selected( $value, $key, true ); ?>><?php echo $label; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>	
		</fieldset>
		<?php
	}
	
	function callback_multiselect( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--select">
				<div class="nikan-select">
					<select id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>][]" multiple>
					<?php 
					foreach ( $args['options'] as $key => $label ) { 
						$selected = '';
						if ( is_array($value) ) {
							$selected = in_array( $key, $value ) ? $key : '';
						}
						?>
						<option value="<?php echo $key; ?>" <?php selected( $selected, $key, true ); ?>><?php echo $label; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>	
		</fieldset>
		<?php
	}

	function callback_textarea( $args ) {
		$value = esc_textarea( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--textarea">
				<div class="nikan-textarea">
					<textarea id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" placeholder="<?php echo $args['std']; ?>"><?php echo $value; ?></textarea>
					<?php if ( isset($args['subtitle']) && $args['subtitle'] !== '' ) { ?>
						<div class="nikan-field-description-helper">
							<?php echo $args['subtitle']; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</fieldset>
		<?php
	}

	function callback_html( $args ) {
		$class = '';
		if ( isset($args['name']) && $args['name'] == '' ) {
			$class = 'tab-title';
		}
		?>
		<div class="nikan-notice <?php echo $class; ?>">
			<div class="nikan-notice-container">
				<?php if ( isset($args['name']) && $args['name'] !== '' ) { ?>
					<div class="nikan-notice-title"><?php echo $args['name']; ?></div>
				<?php } ?>
				<div class="nikan-notice-description"><?php echo $args['desc']; ?></div>
			</div>
		</div>
		<?php
	}

	function callback_wysiwyg( $args ) {
		$value = $this->get_option( $args['id'], $args['section'], $args['std'] );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--textarea">
				<div class="nikan-textarea">
					<?php
					wp_editor( $value, $args['section'] . '-' . $args['id'] . '', array(
						'teeny'         => true,
						'textarea_name' => $args['section'] . '[' . $args['id'] . ']',
						'textarea_rows' => 10
					) );
					?>
					<?php if ( isset($args['subtitle']) && $args['subtitle'] !== '' ) { ?>
						<div class="nikan-field-description-helper">
							<?php echo $args['subtitle']; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</fieldset>
		<?php
	}

	function callback_file( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--upload">
				<div class="nikan-text nikan-upload">
					<input type="text" class="nikan-url" id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="<?php echo $value; ?>">
					<input type="button" class="button nikan-browse" value="<?php _e('Choose File','nik-base'); ?>" />
				</div>
			</div>	
		</fieldset>
		<?php
	}
	
	function callback_image( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--upload">
				<div class="nikan-text nikan-upload">
					<input type="text" class="nikan-url" id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="<?php echo $value; ?>">
					<input type="button" class="button nikan-browse" value="<?php _e('Choose Image','nik-base'); ?>" />
				</div>
				<?php if ( $value && $value !== '' ) { ?>
					<img class="nikan-image-holder" src="<?php echo $value; ?>">
				<?php } elseif ( $args['std'] && $args['std'] !== '' ) { ?>
					<img class="nikan-image-holder" src="<?php echo $args['std']; ?>">
				<?php } ?>
			</div>
		</fieldset>
		<?php
	}

	function callback_password( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--text">
				<div class="nikan-text">
					<input type="password" id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="<?php echo $value; ?>">
				</div>
			</div>	
		</fieldset>
		<?php
	}

	function callback_color( $args ) {
		$value = esc_attr( $this->get_option( $args['id'], $args['section'], $args['std'] ) );
		
		?>
		<fieldset class="nikan-fieldsContainer-fieldset">
			<?php if ( isset($args['desc']) && $args['desc'] !== '' ) { ?>
				<div class="nikan-fieldsContainer-description">
					<?php echo $args['desc']; ?>
				</div>
			<?php } ?>
			<div class="nikan-field nikan-field--text">
				<div class="nikan-text">
					<input type="text" class="wp-color-picker-field" id="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" name="<?php echo $args['section']; ?>[<?php echo $args['id']; ?>]" value="<?php echo $value; ?>" data-default-color="<?php echo $args['std']; ?>" data-alpha="true">
				</div>
			</div>	
		</fieldset>
		<?php
	}

	function sanitize_options( $options ) {
		foreach ( $options as $option_slug => $option_value ) {
			$sanitize_callback = $this->get_sanitize_callback( $option_slug );
			if ( $sanitize_callback ) {
				$options[ $option_slug ] = call_user_func( $sanitize_callback, $option_value );
				continue;
			}
		}

		return $options;
	}

	function get_sanitize_callback( $slug = '' ) {
		if ( empty( $slug ) ) {
			return false;
		}
		foreach ( $this->settings_fields as $section => $options ) {
			foreach ( $options as $option ) {
				if ( $option['name'] != $slug ) {
					continue;
				}

				return isset( $option['sanitize_callback'] ) && is_callable( $option['sanitize_callback'] ) ? $option['sanitize_callback'] : false;
			}
		}

		return false;
	}

	function get_option( $option, $section, $default = '' ) {
		$options = get_option( $section );
		if ( isset( $options[ $option ] ) ) {
			return $options[ $option ];
		}

		return $default;
	}

	function show_navigation() {
		$html = '<ul class="nav-tab-wrapper">';
		foreach ( $this->settings_sections as $tab ) {
			$html .= sprintf( '<li><a class="nikan-menuItem icon-%3$s" href="#%1$s" id="%1$s-tab">', $tab['id'], $tab['title'], str_replace('_','-',$tab['id']) );
			$html .= sprintf( '<span class="nikan-menuItem-title">%1$s</span>', $tab['title'], $tab['id'] );
			if ( isset( $tab['subtitle'] ) ) {
				$html .= sprintf( '<span class="nikan-menuItem-description">%1$s</span>', $tab['subtitle'] );
			}
			$html .= '</a></li>';
		}
		$html .= '</ul>';
		echo $html;
	}

	function show_forms() {
		if ( isset( $_GET['settings-updated'] ) && $_GET['settings-updated'] == 'true' ) {
			?>
            <div class="updated">
                <p><?php echo _e('Settings Saved.','nik-base'); ?></p>
            </div>
			<?php
		}
		?>
        <div class="nikan-content-wrap">
			<?php foreach ( $this->settings_sections as $form ) { ?>
                <section id="<?php echo $form['id']; ?>" class="group">
                    <form method="post" action="options.php">
						<?php do_action( 'nikan_setting_form_top_' . $form['id'], $form ); ?>
						<?php settings_fields( $form['id'] ); ?>
						<?php $this->nikan_do_settings_sections( $form['id'] ); ?>
						<?php do_action( 'nikan_setting_form_bottom_' . $form['id'], $form ); ?>
                         <div class="submit-button-row">
							<?php do_action( 'nikan_setting_form_submit_' . $form['id'], $form ); ?>
                        </div>
                    </form>
                </section>
			<?php } ?>
        </div>
		<div class="nikan-Content-tips">
			<div class="nikan-radio nikan-radio--reverse nikan-radio--tips">
				<input type="checkbox" class="nikan-js-tips" id="nikan-js-tips-id" value="1" checked="">
				<label for="nikan-js-tips-id">
					<span data-l10n-active="<?php _e('On','nik-base'); ?>" data-l10n-inactive="<?php _e('Off','nik-base'); ?>" class="nikan-radio-ui"></span>
					<?php _e('Show Sidebar','nik-base'); ?>
				</label>
			</div>
		</div>
		<?php
		$this->script();
	}
	
	public function nikan_do_settings_sections( $page ) {
		global $wp_settings_sections, $wp_settings_fields;
	 
		if ( ! isset( $wp_settings_sections[ $page ] ) ) {
			return;
		}
	 
		foreach ( (array) $wp_settings_sections[ $page ] as $section ) {
	 
			if ( $section['callback'] ) {
				call_user_func( $section['callback'], $section );
			}
	 
			if ( ! isset( $wp_settings_fields ) || ! isset( $wp_settings_fields[ $page ] ) || ! isset( $wp_settings_fields[ $page ][ $section['id'] ] ) ) {
				continue;
			}
			echo '<div class="nikan-sectionHeader"><span class="nikan-title">'.$section['title'].'</span></div>';
			$this->nikan_do_settings_fields( $page, $section['id'] );
		}
	}
	
	function nikan_do_settings_fields( $page, $section ) {
		global $wp_settings_fields;
	 
		if ( ! isset( $wp_settings_fields[ $page ][ $section ] ) ) {
			return;
		}
	 
		foreach ( (array) $wp_settings_fields[ $page ][ $section ] as $field ) {
			$class = '';
	 
			if ( ! empty( $field['args']['class'] ) ) {
				$class = ' class="' . esc_attr( $field['args']['class'] ) . '"';
			} elseif ( $field['args']['type'] == 'html' ) {
				$class = ' class="info-message"';
			}
			
			if ( $field['args']['type'] !== 'html' ) {
			?>
			<div class="nikan-optionHeader ">
				<span class="nikan-title2"><?php echo $field['title']; ?></span>
				<?php if ( isset($field['args']['help']) && $field['args']['help'] !== '' ) { ?>
					<a href="<?php echo $field['args']['help']; ?>" class="nikan-infoAction nikan-infoAction--help nikan-icon-help" target="_blank"><?php _e( 'Need Help?', 'nik-base' ); ?></a>
				<?php } ?>
			</div>
			<?php } ?>
			<div class="nikan-fieldsContainer ">
				<?php call_user_func( $field['callback'], $field['args'] ); ?>
			</div>
			<?php
		}
	}

	function script() {
		?>
        <script type="text/javascript">
            jQuery(document).ready(function ($) {
                $('.wp-color-picker-field').wpColorPicker();
                $('.group').hide();

                var activetab = '';
                if (typeof(localStorage) !== 'undefined') {
                    activetab = localStorage.getItem("activetab");
                }
                if (activetab !== '' && $(activetab).length) {
                    $(activetab).fadeIn();
                } else {
                    $('.group:first').fadeIn();
                }
                $('.group .collapsed').each(function () {
                    $(this).find('input:checked').parent().parent().parent().nextAll().each(
                        function () {
                            if ($(this).hasClass('last')) {
                                $(this).removeClass('hidden');
                                return false;
                            }
                            $(this).filter('.hidden').removeClass('hidden');
                        });
                });

                if (activetab !== '' && $(activetab + '-tab').length) {
                    $(activetab + '-tab').parent().addClass('tab-current');
                }
                else {
                    $('.nav-tab-wrapper li:first').addClass('tab-current');
                }
                $('.nav-tab-wrapper li').click(function (evt) {
                    $('.nav-tab-wrapper li').removeClass('tab-current');
                    $(this).addClass('tab-current').blur();
                    var clicked_group = $(this).find('a').attr('href');
                    if (typeof(localStorage) !== 'undefined') {
                        localStorage.setItem("activetab", $(this).find('a').attr('href'));
                    }

                    $('.group').hide();
                    $(clicked_group).fadeIn();
                    evt.preventDefault();
                });
                var file_frame = null;
                $('.nikan-browse').on('click', function (event) {
                    event.preventDefault();
                    var self = $(this);
                    if (file_frame) {
                        file_frame.open();
                        return false;
                    }
                    file_frame = wp.media.frames.file_frame = wp.media({
                        title: self.data('uploader_title'),
                        button: {
                            text: self.data('uploader_button_text')
                        },
                        multiple: false
                    });
                    file_frame.on('select', function () {
                        attachment = file_frame.state().get('selection').first().toJSON();

                        self.prev('.nikan-url').val(attachment.url);
                    });
                    file_frame.open();
                });
				
				$(".nikan-js-tips").on("change", function () {
					$(this).is(":checked") ? ($(".nikan-Sidebar").css("display", "block")) : ($(".nikan-Sidebar").css("display", "none"));
				});
            });
        </script>
		<?php
	}
}

class Nikan_Settings_Verify {

	private $settings_api;

	function __construct() {

		$this->settings_api = new Nikan_Settings_Base;

		if ( is_admin() ) {
			add_action( 'admin_init', array( $this, 'admin_init' ) );			
		}

	}

	public static function init() {
		static $instance = false;

		return $instance = ( ! $instance ? new Nikan_Settings_Verify() : $instance );
	}
	
	function admin_init() {
		$this->settings_api->set_sections( $this->settings_api->settingSections() );
		$this->settings_api->set_fields( $this->settings_api->settingFields() );
		$this->settings_api->admin_init();
	}
	
}

class Nikan_Setting_Helper {
	function Nikan_Options( $option, $section, $default = '' ) {

		$options = get_option( $section );

		return isset( $options[ $option ] ) ? $options[ $option ] : $default;
	}
	function Nikan_Persian_Num( $number ) {
		$number = str_ireplace( array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), $number );
		return $number;
	}
	function Nikan_English_Num( $number ) {
		
		$number = str_ireplace( array( '۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹' ), array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), $number ); //farsi
		$number = str_ireplace( array( '٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩' ), array( '0', '1', '2', '3', '4', '5', '6', '7', '8', '9' ), $number ); //arabi
		
		return $number;
	}
	function Nikan_get_all_pages($select) {
		
		$page_titles = array();
		
		if ( $select ) {
			$page_titles[-1] = __( 'Select Page', 'nik-base' );
		}
		
		$pages = get_pages();
		
		if ( is_array($pages) ) {
			foreach ( $pages as $page ) {
				$page_titles[$page->ID] = $page->post_title;
			}
		}
		
		return $page_titles;
	}
	function Nikan_get_all_woo_cats($select) {
		
		$terms = get_terms( array(
			'taxonomy' => 'product_cat',
			'hide_empty' => false,
		) );
		
		$woo_categoris = array();
		
		if ( $select ) {
			$woo_categoris[-1] = __( 'Select Category', 'nik-base' );
		}
		if ( is_array($terms) ) {
			foreach ( $terms as $term ) {
				$woo_categoris[$term->term_id] = $term->name;
			}
		}
		
		return $woo_categoris;
	}
	
	function Nikan_get_all_woo_products($select) {
		
		$args = array(
			'post_type'		 => 'product',
			'post_status'	 => 'publish',
			'posts_per_page' => -1
		);

		$the_query = new WP_Query( $args );
		
		$products_titles = array();
		
		if ( $select ) {
			$products_titles[-1] = __( 'Select Product', 'nik-base' );
		}
		
		if($the_query->have_posts()) {
			while($the_query->have_posts()):
				$the_query->the_post(); 
				$products_titles[get_the_id()] = get_the_title();
			endwhile; 
		}
		
		return $products_titles;
	}
	
	function Nikan_format_Bytes ($bytes, $precision = 2) {
		
		$base = log($bytes, 1024);
		$suffixes = array('', __( 'KB', 'nik-base' ), __( 'MB', 'nik-base' ), __( 'GB', 'nik-base' ), __( 'TB', 'nik-base' ));   

		return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
	}
	
	function Nikan_create_page( $page ) {
		$meta_key = '_wp_page_template';
		$page_obj = get_page_by_path( $page['post_title'] );

		if ( ! $page_obj ) {
			$page_id = wp_insert_post( array(
				'post_title'     => $page['post_title'],
				'post_name'      => $page['slug'],
				'post_content'   => $page['content'],
				'post_status'    => 'publish',
				'post_type'      => 'page',
				'comment_status' => 'closed'
			) );

			if ( $page_id && !is_wp_error( $page_id ) ) {
				if ( isset( $page['template'] ) ) {
					update_post_meta( $page_id, $meta_key, $page['template'] );
				}
				return $page_id;
			}
		}
		return false;
	}
	function Nikan_get_roles() {
		global $wp_roles;
		$roles = array();
		if ( $wp_roles ) {
			foreach ( $wp_roles->get_names() as $key => $role ) {
				$roles[ $key ] = translate_user_role( $role );
			}
		}

		return $roles;
	}
	function Nikan_get_orders_statuses($select = false) {
		
		$statuses = wc_get_order_statuses();
		
		if ( $select ) {
			$statuses = array_merge(array('none' => $select), $statuses);
		}
		
		return $statuses;
	}
}

function NIKANHELP() {
	return new Nikan_Setting_Helper();
}

Nikan_Settings_Verify::init();

require_once 'basic-settings-functions.php';

// Elementor
if( ! class_exists( 'Nikan_Base_Elementor_Extensions' ) ) {
	final class Nikan_Base_Elementor_Extensions {

		private static $_instance = null;

		public function __construct() {
			add_action( 'elementor/elements/categories_registered', array( $this, 'nikan_add_widget_categories' ) );
		}

		public static function instance () {
			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}
			return self::$_instance;
		}

		public function nikan_add_widget_categories( $elements_manager ) {
			$elements_manager->add_category(
				'nikan-elements',
				[
					'title' => esc_html__( 'Nikan Elements', 'nik-base' ),
					'icon' => 'fa fa-plug',
				]
			);
		}
	}
}

if ( did_action( 'elementor/loaded' ) ) {
	Nikan_Base_Elementor_Extensions::instance();
}

?>