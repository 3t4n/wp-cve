<?php

/**
 * Tranzly Settings Page
 *
 * @link       https://tranzly.io
 * @since      1.0.0
 *
 * @package    Tranzly
 * @subpackage Tranzly/includes
 */

/**
 * Tranzly Settings Page class.
 *
 * @since      1.0.0
 * @package    Tranzly
 * @subpackage Tranzly/includes
 * @author     Tranzly <https://tranzly.io>
 */
class Tranzly_Settings_Page {

	/**
	 * Tranzly options.
	 *
	 * @since 1.0.0
	 *
	 * @var array
	 */
	private $tranzly_options;

	/**
	 * Constructor.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_action( 'admin_menu', array( $this, 'tranzly_add_menu_page' ) );
		add_action( 'admin_init', array( $this, 'tranzly_page_init' ) );
	}

	/**
	 * Adds the menu page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function tranzly_add_menu_page() {
		add_menu_page(
			esc_html__( 'Tranzly', 'tranzly' ), // Page title.
			esc_html__( 'Tranzly', 'tranzly' ), // Menu title.
			'manage_options', // Capability.
			'tranzly', // Menu slug.
			array( $this, 'tranzly_create_admin_page' ) ,  'dashicons-translation',
            3 // Callback function.
			
			
		
		);

	}





	/**
	 * Gets the settings page url.
	 *
	 * @since 1.0.0
	 *
	 * @return string
	 */
	public function get_settings_page_url() {
		return menu_page_url( 'tranzly', false );
	}

	/**
	 * Gets the tab item url.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item The tab item slug.
	 *
	 * @return string
	 */
	public function get_tab_item_url( $item ) {
		return add_query_arg( 'tab', $item, $this->get_settings_page_url() );
	}

	/**
	 * Gets the tab item class.
	 *
	 * @since 1.0.0
	 *
	 * @param string $item The tab item slug.
	 *
	 * @return string
	 */
	public function get_tab_item_class( $item ) {
		$tabs = $this->get_tabs();
		
		/*Sanitize tabs*/
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field(wp_unslash( $_GET['tab']) ) : '';
		
		$class = 'nav-tab';

		reset( $tabs );
		$first_item = key( $tabs );

		if ( ( ! $tab && is_array( $tabs ) && $item === $first_item ) || $item === $tab ) {
			$class .= ' nav-tab-active';
		}

		return $class;
	}

	/**
	 * Gets the tabs and the fields.
	 *
	 * @since 1.0.0
	 *
	 * @return array
	 */
	 
	 

	public function get_tabs() {
		
		return apply_filters
		
		('tranzly_settings_page_tabs',
			array(
				
			
					'settings'           => array(
					'title'      => esc_html__( 'Settings', 'tranzly' ),
					'submit_btn' => true,
					'fields'     => array(
					
					
						array(
							'id'          => 'deepltitle',
							'title'       => '<h2>'. esc_html__( 'DeepL API Key', 'tranzly' ).'</h2>',
							'type'        => 'html',
						),
					
						array(
							'id'          => 'deepl_api_key',
							'title'       => esc_html__( 'DeepL API Key', 'tranzly' ),
							'type'        => 'text',
							'placeholder' => '',
							'desc' => 'It requires a DeepL Pro API key,if you don\'t have one get it from <a target ="_blank" href="https://www.deepl.com/pro#developer">here.</a>',
						),
						
						array(
							'id'          => 'switchersettings',
							'title'       => '<h2>'. esc_html__( 'Language Selector', 'tranzly' ).'</h2>',
							'type'        => 'html',
							'desc' => esc_html__( 'Language Selector Options', 'tranzly' ),
						),
						
					
						
					
						array(
							'id'          => 'selector_mode',
							'title'       => esc_html__( 'Language Selector', 'tranzly' ),
							'type'        => 'select',
							'options' => array (
							'flags' => 'Flags',
							'text' => 'Text',
							),
							'placeholder' => '',
						),
						
						
						array(
							'id'          => 'selector_position',
							'title'       => esc_html__( 'Selector Position', 'tranzly' ),
							'type'        => 'select',
							'options' => array (
							'before' => 'Before the content',
							'after' => 'After the content'
							),
							//'desc' => '',
						),
						
						array(
							'id'          => 'selector_tab',
							'title'       => esc_html__( 'Selector Link Target', 'tranzly' ),
							'type'        => 'select',
							'options' => array (
							'newtab' => 'New Tab',
							'samewindow' =>'Same Window'
							),
							
						),
						
						
						

						
					
						
						
					),
				),
				
				
				
				
				
				'howto'           => array(
					'title'      => esc_html__( 'How To Use', 'tranzly' ),
					//'submit_btn' => true,
					'fields'     => array(
					
				
						
						
					),
				),
			
				
			)
		);
	}

	/**
	 * Format the tabs.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function format_tabs() {
		?>
		<nav class="nav-tab-wrapper">
			<?php foreach ( $this->get_tabs() as $name => $data ) : ?>
				<a
					href="<?php echo esc_url( $this->get_tab_item_url( $name ) ); ?>"
					class="<?php echo esc_attr( $this->get_tab_item_class( $name ) ); ?>"
				><?php echo esc_html( $data['title'] ); ?></a>
			<?php endforeach; ?>
		</nav>
		<?php
	}

	/**
	 * Format the progress bar.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function format_progress_bar() {
		?>
		<div class="tranzly-translation-progress">
			<table class="progress-table">
				<tr>
					<td class="progressbar-column">
						<div class="progressbar">
							<div></div>
						</div>
					</td>
					</tr>
					<tr>
					<td class="progress-info">
						<?php
						
						echo esc_html(tranzly_get_placeholder_markup_for_total_translated_posts());
						
						?>
					</td>
				</tr>
			</table>
			<p class="tranzly-success-message"></p>
		</div>
		<?php
	}

	/**
	 * Adds the settings page.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function tranzly_create_admin_page() {
		$this->tranzly_options = get_option( 'tranzly_options' );
		?>
		<div class="wrap">
<h2></h2>
			<?php $this->format_tabs(); ?>

			<form method="post" action="options.php">
				<?php
				settings_fields( 'tranzly_option_group' );

				$tabs = $this->get_tabs();
				
				$tab = isset( $_GET['tab'] ) ? sanitize_text_field( $_GET['tab'] ) : '';
			
				$data = $tab ? $tabs[ $tab ] : array();

				if ( ! $tab ) {
					reset( $tabs );
					$tab  = key( $tabs );
					$data = current( $tabs );
				}
			
					if ( $tab == 'howto' ) {
						echo '<section><h3>' . esc_html(__('How to use Tranzly:', 'tranzly')) . '</h2>';
						echo '<ul  class="presection">
                                <li>'. esc_html(__('On the Settings tab, enter your DeepL Pro API key.', 'tranzly')) . '</li>
                                 <li>'. esc_html(__('Then go to Pages or Posts, Click Edit and apply a new translation, You\'ll find the options on the right sidebar.', 'tranzly')) . '</li>
                                <li>'. esc_html(__('You can generate a new content or save it as draft.', 'tranzly')) . '</li>
                                <li>'. esc_html(__('if you don\'t have a DeepL pro API Key, You can use the manual translation instead.', 'tranzly')) . '</li>
								<li>'. esc_html(__('We are enhancing this plugin in the next releases, so expect more translation API integrations like google translation.', 'tranzly') ) . '</li>
                            </ul>';
							
							
						echo '<section><h3>' . esc_html(__('Terms of use:', 'tranzly')) . '</h2>';
						echo '<ul  class="presection">
                                <li>'. esc_html(__('This plugin is not affiliated with or supported by DeepL, Inc. All logos and trademarks are the property of their respective owners.', 'tranzly')) . '</li>
                                <li>'. esc_html(__('Tranzly is relying on DeepL.com API as a third party provider.Tranzly links the content of the posts and the pages with DeepL API in order to translate the content ', 'tranzly')) . '</li>
                                <li>'. esc_html(__('You use the API of DeepL under the circumstances and the terms of conditions of DeepL, Tranzly is not responsible for breaking the rules set by DeepL , You can find out the DeepL Conditions https://www.deepl.com/pro-license/.', 'tranzly')) . '</li>	
                            </ul>';
					} else {
						
							do_settings_sections( 'tranzly-settings-' . $tab );
					}
					
				
								
					
					
				
					
				if ( isset( $data['submit_btn'] ) && true === $data['submit_btn'] ) {
					
					submit_button( __( 'Save Settings', 'tranzly' ), 'primary savesettings' );
				}
			
				?>
				
			</form>
			<?php
			tranzly_get_error_message_wrapper();
			$this->format_progress_bar();
			?>
		</div>
		<?php
	}

	/**
	 * Adds the settings sections.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_tranzly_settings_sections() {
		foreach ( $this->get_tabs() as $name => $data ) {
			add_settings_section(
				'tranzly_setting_' . $name, // Id.
				'', // Title.
				'', // Callback.
				'tranzly-settings-' . $name // Page.
			);
		}
	}

	/**
	 * Adds the settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function add_tranzly_settings_fields() {
		
		
		
		foreach ( $this->get_tabs() as $name => $data ) {
				foreach ( $data['fields'] as $field ) {
					$field_callback = apply_filters(
						'tranzly_get_' . $field['type'] . '_field_type','get_' . $field['type'] . '_field_type'
					);
					
					
					 if ( isset( $field['title'] ) ) {
						$title = $field['title'];
					 }

					if ( 'btn' === $field['type'] ) {
						$title = '';
					} 
					
					
					$title = apply_filters( 'tranzly_setting_field_title',$title, $field );

					add_settings_field(
						$field['id'], // Id.
						$title, // Title.
						array( $this, $field_callback ), // Callback.
						'tranzly-settings-' . $name, // Page.
						'tranzly_setting_' . $name, // Section.
						$field
					);
				}

		}
	}

	/**
	 * Initialize settings fields.
	 *
	 * @since 1.0.0
	 *
	 * @return void
	 */
	public function tranzly_page_init() {
		register_setting(
			'tranzly_option_group', // Option group.
			'tranzly_options', // Option name.
			array( $this, 'tranzly_sanitize' ) // Sanitize callback function.
		);

		$this->add_tranzly_settings_sections();

		$this->add_tranzly_settings_fields();
	}

	/**
	 * Sanitizes the user inputs.
	 *
	 * @since 1.0.0
	 *
	 * @param array $input The user submitted data.
	 *
	 * @return array
	 */
	public function tranzly_sanitize( $input ) {
		$sanitary_values = array();

		foreach ( $this->get_tabs() as $tab ) {
			foreach ( $tab['fields'] as $field ) {
				$name = $field['id'];
				$type = $field['type'];

				// if ( 'checkbox' === $type ) {
					// if ( isset( $input[ $name ] ) ) {
						$sanitary_values[ $name ] = sanitize_text_field( $input[ $name ] );
					// } 
				// } 
			}
		}

		$new_options      = array();
		$tranzly_options = get_option( 'tranzly_options' );
		$tranzly_options = $tranzly_options ? get_option( 'tranzly_options' ) : array();

		foreach ( $sanitary_values as $key => $value ) {
			$new_options[ $key ] = $value;
		}

		foreach ( $tranzly_options as $key => $value ) {
			if ( ! array_key_exists( $key, $new_options ) ) {
				$new_options[ $key ] = $value;
			}
		}

		return $new_options;
	}

	/**
	 * Gets the text field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_text_field_type( $field ) {
		$id= $field['id'];

		$value       = isset( $this->tranzly_options[ $id ] ) ? $this->tranzly_options[ $id ] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		?>
		<input
			type="text"
			class="regular-text"
			name="tranzly_options[<?php echo esc_attr( $id ); ?>]"
			id="<?php echo esc_attr( $id ); ?>"
			value="<?php echo esc_attr( $value ); ?>"
			<?php echo $required ? 'required="required"' : ''; ?>
			<?php echo $placeholder ? 'placeholder="' . esc_attr( $placeholder ) . '"' : ''; ?>
		>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html($desc); ?></p>
		<?php endif; ?>
		<?php
	}



/**
	 * Gets the text field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_html_field_type( $field ) {
		 $id= $field['id'];
	

		$value       = isset( $this->tranzly_options[ $id ] ) ? $this->tranzly_options[ $id ] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		?>
		<?php echo  esc_html($placeholder); ?>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html($desc); ?></p>
		<?php endif; ?>
		<?php
	}


/**
	 * Gets the text field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_html2_field_type( $field ) {
		 $id= $field['id'];
	

		$value       = isset( $this->tranzly_options[ $id ] ) ? $this->tranzly_options[ $id ] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		?>
		<?php echo  esc_html($placeholder); ?>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html($desc);  ?></p>
		<?php endif; ?>
		<?php
	}


	/**
	 * Gets the select field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_select_field_type( $field ) {
		$id          = $field['id'];
		$options     = isset( $field['options'] ) ? $field['options'] : array();
		$placeholder = isset( $field['placeholder'] ) ? $field['placeholder'] : '';
		$desc        = isset( $field['desc'] ) ? $field['desc'] : '';
		$value       = isset( $this->tranzly_options[ $id ] ) ? $this->tranzly_options[ $id ] : '';
		$required    = isset( $field['required'] ) ? $field['required'] : '';
		?>
		<select name="tranzly_options[<?php echo esc_attr( $id ); ?>]" id="<?php echo esc_attr( $id ); ?>" <?php echo $required ? 'required="required"' : ''; ?> >
			<?php if ( $placeholder ) : ?>
				<option value=""><?php echo esc_html( $placeholder ); ?></option>
			<?php endif; ?>

			<?php foreach ( $options as $key => $title ) : ?>
				<option
					value="<?php echo esc_attr( $key ); ?>"
					<?php selected( $value, $key ); ?>
				><?php echo esc_html( $title ); ?></option>
			<?php endforeach; ?>
		</select>

		<?php if ( 'post_type' === $field['id'] ) : ?>
			<span class="spinner tranzly-spinner" style="float: none;"></span>
		<?php endif;  ?>

		<?php if ( $desc ) : ?>
			<p class="description"><?php echo esc_html( $desc ); ?></p>
		<?php endif; ?>

		<?php if ( 'post_type' === $field['id'] ) : ?>
			<div class="tranzly-dynamic-taxonomy-filter-wrapper"></div>
		<?php endif; ?>
		<?php
	}

	/**
	 * Gets the btn field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_btn_field_type( $field ) {
		$id    = $field['id'];
		$title = $field['title'];
		?>
		<div class="mylod" id="mylod" style="">
		    <div class="mylodsub">
		        <img class="spinner_img" src="<?php echo esc_url(TRANZLY_PLUGIN_URI)?>../admin/img/world.svg" style="">
		        <p id="cnmsg"><?php esc_html_e( 'AI Translating...' ,'tranzly' ); ?><br><?php esc_html_e( 'Check the status below' ,'tranzly' ); ?></p>
		    </div>
    	</div>
    	
		<button class="button button-primary" id="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $title ); ?></button>
		<?php
		if ( 'translate_posts_btn' === $id ) {
			wp_nonce_field( 'tranzly_process_translation', '_tranzly_nonce', false );
		}
		if ( 'generate_posts_btn' === $id ) {
			wp_nonce_field( 'tranzly_process_translation', '_tranzly_nonce', false );
		}
	}

	/**
	 * Gets the checkbox field type.
	 *
	 * @since 1.0.0
	 *
	 * @param array $field The field data.
	 *
	 * @return void
	 */
	public function get_checkbox_field_type( $field ) {
		$id       = $field['id'];
		$class= $field['classes'];
		$value    = isset( $this->tranzly_options[ $id ] ) ? $this->tranzly_options[ $id ] : '';
		$required = isset( $field['required'] ) ? $field['required'] : '';
		?>
		<input
			type="checkbox"
			name="tranzly_options[<?php echo esc_attr( $id ); ?>]"
			id="<?php echo esc_attr( $id ); ?>"
			class="<?php echo esc_attr( $class ); ?>"
			<?php checked( $value, 'on' );		?>
			<?php echo $required ? 'required="required"' : ''; ?>
		>
		<?php
		
			
	}

}

if ( is_admin() ) {
	new Tranzly_Settings_Page();
}