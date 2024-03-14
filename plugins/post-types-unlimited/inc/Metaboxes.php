<?php

namespace PTU;

use PTU\PostTypes;
use PTU\Taxonomies;

\defined( 'ABSPATH' ) || exit;

/**
 * Metaboxes.
 */
class Metaboxes {

	/**
	 * Class version.
	 *
	 * @var   string
	 * @since 1.0
	 */
	public $version = '1.2';

	/**
	 * Default metabox settings.
	 *
	 * @var   array
	 * @since 1.0
	 */
	protected $defaults = array(
		'id'       => '',
		'title'    => '',
		'screen'   => array(),
		'context'  => 'normal',
		'priority' => 'high',
		'classes'  => array(),
		'fields'   => array(),
	);

	/**
	 * Metabox ID.
	 *
	 * @var   string
	 * @since 1.0
	 */
	protected $id = '';

	/**
	 * Array of custom metabox settings.
	 *
	 * @var   array
	 * @since 1.0
	 */
	protected $metabox = array();

	/**
	 * Register this class with the WordPress API.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param array $metabox Array of metabox settings|fields.
	 * @return void
	 */
	public function __construct( array $metabox ) {
		$this->metabox = \wp_parse_args( $metabox, $this->defaults );

		if ( empty( $this->metabox['tabs'] ) || ! $this->validate_screen() ) {
			return;
		}

		foreach ( $this->metabox['screen'] as $screen ) {
			\add_action( "add_meta_boxes_{$screen}", array( $this, 'add_meta_box' ) );
			\add_action( "save_post_{$screen}", array( $this, 'save_meta_box' ) );
		}

		\add_action( 'admin_enqueue_scripts', array( $this, 'load_scripts' ) );
	}

	/**
	 * Validates the screen value.
	 *
	 * @since 1.2
	 *
	 * @access protected
	 * @return void
	 */
	protected function validate_screen(): bool {
		if ( empty( $this->metabox['screen'] ) ) {
			return false;
		}

		$screens = $this->metabox['screen'];

		if ( \is_string( $this->metabox['screen'] ) ) {
			$screens = array( $this->metabox['screen'] );
		}

		foreach ( $screens as $screen_k => $screen_v ) {
			if ( ! \in_array( $screen_v, [ PostTypes::ADMIN_TYPE, Taxonomies::ADMIN_TYPE ] ) ) {
				unset( $screens[ $screen_k ] );
			}
		}

		$this->metabox['screen'] = $screens;

		return ! empty( $screens );
	}

	/**
	 * The function responsible for creating the actual meta boxes.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @return void
	 */
	public function add_meta_box() {
		\add_meta_box(
			$this->metabox['id'],
			$this->metabox['title'],
			array( $this, 'display_meta_box' ),
			$this->metabox['screen'],
			$this->metabox['context'],
			$this->metabox['priority']
		);
	}

	/**
	 * Enqueue scripts and styles needed for the metaboxes.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param string $hook Current admin page hook prefix.
	 * @return void
	 */
	public function load_scripts( $hook ) {
		if ( $hook !== 'post.php' && $hook !== 'post-new.php' ) {
			return;
		}

		global $post;

		if ( ! is_a( $post, 'WP_Post' ) || ! in_array( $post->post_type, $this->metabox['screen'] ) ) {
			return;
		}

		// Enqueue metabox css.
		\wp_enqueue_style(
			'ptu-metaboxes',
			\plugin_dir_url( \dirname( __FILE__ ) ) . 'assets/css/ptu-metaboxes.css',
			array( 'wp-components' ),
			$this->version
		);

		// Enqueue metabox JS.
		\wp_enqueue_script(
			'ptu-metaboxes',
			\plugin_dir_url( \dirname( __FILE__ ) ) . 'assets/js/ptu-metaboxes.js',
			array( 'jquery' ),
			$this->version,
			true
		);

	}

	/**
	 * Renders the content of the meta box.
	 *
	 * @since 1.0
	 *
	 * @access public
	 * @param obj $post Current post being shown in the admin.
	 * @return void
	 */
	public function display_meta_box( $post ) {
		if ( ! is_a( $post, 'WP_Post' ) ) {
			return;
		}

		$tabs = $this->get_tabs();

		if ( ! $tabs ) {
			return;
		}

		\wp_nonce_field( "ptu_metabox_{$this->metabox['id']}", "ptu_metabox_nonce_{$this->metabox['id']}" );

		?>

		<div class="ptu-metabox">
			<div class="ptu-metabox-tabs"><?php
				$first_tab = true;
				foreach ( $tabs as $tab ) {
					$tab_title = $tab['title'] ?? $tab['label'] ?? '';
					$selected  = $first_tab ? 'true' : 'false';
					?>

					<div class="ptu-metabox-tab"<?php $this->tab_conditions_data( $tab ); ?>>
						<a href="#" class="ptu-metabox-tab-link" aria-controls="ptu-metabox-tab--<?php echo \esc_attr( $tab['id'] ); ?>" aria-selected="<?php echo \esc_attr( $selected ); ?>" role="tab"><?php echo \esc_html( $tab_title ); ?></a>
					</div>

					<?php
					if ( $first_tab ) {
						$first_tab = false;
					}
				}
			?></div>
			<div class="ptu-metabox-sections"><?php
				foreach ( $tabs as $tab ) {
					$this->render_tab_content( $tab, $post );
				}
			?></div>
		</div>

	<?php }

	/**
	 * Renders a metabox tab.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function render_tab_content( array $tab, object $post ): void {
		if ( empty( $tab['fields'] ) ) {
			return;
		}

		static $tab_counter = null;

		if ( \is_null( $tab_counter ) ) {
			$tab_counter = 0;
		}

		$tab_counter++;

		$section_class = 'ptu-metabox-section';
		if ( $tab_counter > 1  ) {
			$section_class .= ' hidden';
		}

		?>

		<div id="ptu-metabox-tab--<?php echo \esc_attr( \sanitize_html_class( $tab['id'] ) ); ?>" class="<?php echo \esc_attr( $section_class ); ?>">
			<table class="form-table">

				<?php foreach ( $tab['fields'] as $key => $field ) {

					$defaults = array(
						'name'    => '',
						'id'      => '',
						'type'    => '',
						'desc'    => '',
						'default' => '',
					);

					$field = wp_parse_args( $field, $defaults );
					$field_meta_key = $this->parse_field_id( $field['id'] );

					// Get field values
					$custom_field_keys = \get_post_custom_keys();
					if ( \is_array( $custom_field_keys ) && \in_array( $field_meta_key, $custom_field_keys ) ) {
						$value = \get_post_meta( $post->ID, $field_meta_key, true );
					} else {
						$value = $field['default'];
					}

					?>

					<tr<?php $this->field_conditions_data( $field ); ?>>

						<?php if ( $field['name'] ) { ?>

							<th>

								<?php if ( 'multi_select' !== $field['type'] ) { ?>
									<label class="ptu-metabox-label" for="ptu-metabox-field--<?php echo \esc_attr( $field['id'] ); ?>">
										<strong><?php echo \esc_html( $field['name'] ); ?></strong>
									</label>
								<?php } else { ?>
									<span class="ptu-metabox-label">
										<strong><?php echo \esc_html( $field['name'] ); ?></strong>
									</span>
								<?php } ?>

								<?php if ( ! empty( $field['desc'] ) ) { ?>
									<p class="ptu-metabox-description"><?php echo \wp_kses_post( $field['desc'] ); ?></p>
								<?php } ?>

							</th>

						<?php } ?>

						<?php
						// Output field type.
						$method = "field_{$field['type']}";

						if ( \method_exists( $this, $method ) ) {
							$expand = empty( $field['name'] ) ? ' colspan="2"' : '';
							echo '<td' . $expand . '>' . $this->$method( $field, $value ) . '</td>';
						} ?>

					</tr>

				<?php } ?>

			</table>
		</div>

		<?php
	}

	/**
	 * Outputs conditional logic data attributes for tabs.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function tab_conditions_data( array $tab ): void {
		$this->field_conditions_data( $tab );
	}

	/**
	 * Outputs conditional logic data attributes for fields.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function field_conditions_data( array $field ): void {
		if ( empty( $field['condition'] ) ) {
			return;
		}

		echo ' data-ptu-condition="' . \esc_attr( \wp_json_encode( $field['condition'] ) ) . '"';
	}

	/**
	 * Return the parsed field id.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function parse_field_id( string $field_id ): string {
		return "_ptu_{$field_id}";
	}

	/**
	 * Return tabs.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function get_tabs(): array {
		if ( \is_callable( $this->metabox['tabs'] ) ) {
			$this->metabox['tabs'] = (array) \call_user_func( $this->metabox['tabs'] );
		}
		return $this->metabox['tabs'];
	}

	/**
	 * Returns the metabox fields.
	 *
	 * @access protected
	 * @since 1.2
	 */
	protected function get_fields(): array {
		$fields = [];
		foreach ( $this->get_tabs() as $tab ) {
			if ( isset( $tab['fields'] ) && \is_array( $tab['fields'] ) ) {
				foreach ( $tab['fields'] as $field ) {
					$fields[] = $field;
				}
			}
		}
		return $fields;
	}

	/**
	 * Render a text field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_text( $field, $value ) {
		$required    = isset( $field['required'] ) ? ' required' : '';
		$maxlength   = isset( $field['maxlength'] ) ? ' maxlength="' . $field['maxlength'] . '"' : '';
		$placeholder = ! empty( $field['placeholder'] ) ? ' placeholder="' . \esc_attr( $field['placeholder'] ) . '"' : '';
		return '<input id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '" type="text" value="' . \esc_attr( $value ) . '" ' . $required . $maxlength . $placeholder . '>';
	}

	/**
	 * Render a number field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_number( $field, $value ) {
		$step        = $field['step'] ?? 1;
		$min         = $field['min'] ?? 1;
		$max         = $field['max'] ?? 200;
		$placeholder = ! empty( $field['placeholder'] ) ? ' placeholder="' . \esc_attr( $field['placeholder'] ) . '"' : '';
		return '<input id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '" type="number" value="' .  \esc_attr( $value ) . '" step="' . \absint( $step ) . '" min="' . \floatval( $min ) .'" max="' . \floatval( $max ) .'"' . $placeholder . '>';
	}

	/**
	 * Render a textare field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_textarea( $field, $value ) {
		$rows = $field['rows'] ?? 4;
		return '<textarea id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" rows="' . \absint( $rows ) . '" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '">' . \wp_kses_post( $value ) . '</textarea>';
	}

	/**
	 * Render a checkbox field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_checkbox( $field, $value ) {
		$value   = $value ? true : false;
		$checked = checked( $value, true, false );
		return '<input id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '" type="checkbox" ' . $checked . '>';
	}

	/**
	 * Render a select field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_select( $field, $value ) {
		$choices = $field['choices'] ?? array();

		// @todo remove the is_string check and instead do ! is_array()
		if ( \is_string( $choices ) && \is_callable( $choices ) ) {
			$choices = \call_user_func( $choices );
		}

		if ( empty( $choices ) || ! \is_array( $choices ) ) {
			return;
		}

		$output = '<select id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '">';
			foreach ( $choices as $choice_v => $name ) {
				$selected = \selected( $value, $choice_v, false );
				$output .= '<option value="' .  \esc_attr( $choice_v ) . '" ' . $selected . '>' . \esc_attr( $name ) . '</option>';
			}
		$output .= '</select>';

		return $output;
	}

	/**
	 * Render a multi_select field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_multi_select( $field, $value ) {
		$value   = \is_array( $value ) ? $value : array();
		$choices = $field['choices'] ?? array();

		if ( empty( $choices ) ) {
			return;
		}

		$output = '<fieldset>';

		foreach ( $choices as $choice_v => $name ) {

			$field_id = "{$field['id']}_{$choice_v}";

			$selected = \checked( \in_array( $choice_v, $value ), true, false );

			$output .= '<input id="ptu-metabox-field--' . \esc_attr( $field_id ) . '" type="checkbox" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '[]" value="' .  \esc_attr( $choice_v ) . '" ' . $selected . '>';

			$output .= '<label for="ptu-metabox-field--' . \esc_attr( $field_id ) . '">' . \esc_attr( $name ) . '</label>';

			$output .= '<br />';

		}

		$output .= '</fieldset>';

		return $output;
	}

	/**
	 * Render a dashicon field type.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function field_dashicon( $field, $value ) {
		$dashicons = $this->get_dashicons();

		if ( empty( $dashicons ) ) {
			return;
		}

		$output = '';

			$output .= '<div class="ptu-metabox-icon-select">';

				$output .= '<input type="text" name="' . \esc_attr( $this->parse_field_id( $field['id'] ) ) . '" id="ptu-metabox-field--' . \esc_attr( $field['id'] ) . '" value="' . \esc_attr( $value ) .'">';

				$output .= ' <button class="ptu-metabox-icon-select__button button-secondary" type="button">' . \esc_html__( 'Select Icon', 'post-types-unlimited' ) . '</button>';

				$output .= '<br><div class="ptu-metabox-icon-preview">';
					if ( $value ) {
						$output .= '<span class="dashicons dashicons-' . \esc_attr( $value ) . '" aria-hidden="true"></span>';
					}
				$output .= '</div>';

				$output .= '<div class="ptu-metabox-modal components-modal__screen-overlay" style="display:none" data-ptu-icons-list="' . \esc_attr( \wp_json_encode( \array_keys( $dashicons ) ) ) . '">';
					$output .= '<div class="components-modal__frame is-full-screen" tabindex="-1">';
						$output .= '<div class="components-modal__content">';
							$output .= '<div class="components-modal__header">';
								$output .= '<div class="components-search-control__input-wrapper">';
									$output .= '<input class="ptu-metabox-modal__search components-search-control__input" type="search" placeholder="' . esc_html__( 'Search for an icon', 'post-types-unlimited' ) . '"><div class="components-search-control__icon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M13.5 6C10.5 6 8 8.5 8 11.5c0 1.1.3 2.1.9 3l-3.4 3 1 1.1 3.4-2.9c1 .9 2.2 1.4 3.6 1.4 3 0 5.5-2.5 5.5-5.5C19 8.5 16.5 6 13.5 6zm0 9.5c-2.2 0-4-1.8-4-4s1.8-4 4-4 4 1.8 4 4-1.8 4-4 4z"></path></svg></div>';
								$output .= '</div>';
								$output .= '<button class="ptu-metabox-modal__close components-button has-icon" aria-label="' . \esc_html__( 'Close dialog', 'post-types-unlimited' ) . '"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" aria-hidden="true" focusable="false"><path d="M13 11.8l6.1-6.3-1-1-6.1 6.2-6.1-6.2-1 1 6.1 6.3-6.5 6.7 1 1 6.5-6.6 6.5 6.6 1-1z"></path></svg></button>';
							$output .= '</div>';
							$output .= '<div class="ptu-metabox-modal__icons"></div>';
						$output .= '</div>';
					$output .= '</div>';
				$output .= '</div>';

			$output .= '</div>';

		$output .= '</td>';

		return $output;
	}

	/**
	 * Save metabox data.
	 *
	 * @access public
	 * @since 1.0
	 */
	public function save_meta_box( $post_id ) {

		/*
		 * We need to verify this came from our screen and with proper authorization,
		 * because the save_post action can be triggered at other times.
		 */

		// Check if our nonce is set.
		if ( ! isset( $_POST["ptu_metabox_nonce_{$this->metabox['id']}"] ) ) {
			return;
		}

		// Verify that the nonce is valid.
		if ( ! \wp_verify_nonce( $_POST["ptu_metabox_nonce_{$this->metabox['id']}"], "ptu_metabox_{$this->metabox['id']}" ) ) {
			return;
		}

		// If this is an autosave, our form has not been submitted, so we don't want to do anything.
		if ( \defined( '\DOING_AUTOSAVE' ) && \DOING_AUTOSAVE ) {
			return;
		}

		// Check the user's permissions.
		if ( isset( $_POST['post_type'] ) && 'page' === $_POST['post_type'] ) {

			if ( ! \current_user_can( 'edit_page', $post_id ) ) {
				return;
			}

		} else {

			if ( ! \current_user_can( 'edit_post', $post_id ) ) {
				return;
			}

		}

		/* OK, it's safe for us to save the data now. Now we can loop through fields */

		// Get array of fields to save.
		$fields = $this->get_fields();

		// Return if fields are empty.
		if ( empty( $fields ) ) {
			return;
		}

		// Loop through options and validate.
		foreach ( $fields as $field ) {
			if ( empty( $field['id'] ) ) {
				continue;
			}

			// Define loop main vars.
			$value    = '';
			$field_id = $this->parse_field_id( $field['id'] );

			// Make sure field exists and if so validate the data.
			if ( isset( $_POST[ $field_id ] ) ) {

				// Sanitize field before inserting into the database.
				if ( ! empty( $field['sanitize_callback'] ) ) {
					$value = \call_user_func( $field['sanitize_callback'], $field, $_POST[ $field_id ] );
				} else {
					$value = $this->sanitize_value_for_db( $_POST[ $field_id ], $field );
				}

				// Update meta if value exists.
				if ( $value || '0' == $value ) {
					\update_post_meta( $post_id, $field_id, $value );
				}

				// Delete if value is empty.
				else {
					\delete_post_meta( $post_id, $field_id );
				}

			} else {
				if ( 'checkbox' === $field['type'] && ! empty( $field['default'] ) ) {
					\update_post_meta( $post_id, $field_id, false );
				} else {
					\delete_post_meta( $post_id, $field_id );
				}
			}

		}

		// Flush rewrites to prevent issues slug changes.
		// @todo add a hidden field so this only runs when certain fields are modified.
		\update_option( 'ptu_flush_rewrite_rules', true );
	}

	/**
	 * Sanitize input values before inserting into the database.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function sanitize_value_for_db( $input, $field ) {
		$type = $field['type'];

		switch ( $type ) {
			case 'text':
				return \sanitize_text_field( $input );
				break;
			case 'number':
				if ( '' !== \trim( $input ) ) {
					return \intval( $input ); // prevent empty field from saving as 0.
				}
				break;
			case 'textarea':
				return \sanitize_textarea_field( $input );
				break;
			case 'dashicon':
				return \array_key_exists( $input, $this->get_dashicons() ) ? \sanitize_textarea_field( $input ) : null;
				break;
			case 'checkbox':
				return isset( $input ) ? true : false;
				break;
			case 'select':
				$choices = $field['choices'] ?? [];
				if ( \is_string( $choices ) && \is_callable( $choices ) ) {
					$choices = \call_user_func( $choices );
				}
				if ( \is_array( $choices ) && \in_array( $input, $choices ) || \array_key_exists( $input, $choices ) ) {
					return \esc_attr( $input );
				}
				break;
			case 'multi_select':
				if ( ! is_array( $input ) ) {
					return $field['default'] ?? array();
				}
				$checks = true;
				foreach( $input as $v ) {
					if ( ! \in_array( $v, $field['choices'] ) && ! \array_key_exists( $v, $field['choices'] ) ) {
						$checks = false;
						break;
					}
				}
				return $checks ? $input : array();
				break;
			default:
				return \sanitize_text_field( $input );
				break;
		}

	}

	/**
	 * Returns an array of dashicons.
	 *
	 * @access protected
	 * @since 1.0
	 */
	protected function get_dashicons() {
		$dashicons = array(
			'admin-appearance' => 'f100',
			'admin-collapse' => 'f148',
			'admin-comments' => 'f117',
			'admin-generic' => 'f111',
			'admin-home' => 'f102',
			'admin-media' => 'f104',
			'admin-network' => 'f112',
			'admin-page' => 'f133',
			'admin-plugins' => 'f106',
			'admin-settings' => 'f108',
			'admin-site' => 'f319',
			'admin-tools' => 'f107',
			'admin-users' => 'f110',
			'align-center' => 'f134',
			'align-full-width' => 'f114',
			'align-pull-left' => 'f10a',
			'align-pull-right' => 'f10b',
			'align-wide' => 'f11b',
			'align-left' => 'f135',
			'align-none' => 'f138',
			'align-right' => 'f136',
			'analytics' => 'f183',
			'arrow-down' => 'f140',
			'arrow-down-alt' => 'f346',
			'arrow-down-alt2' => 'f347',
			'arrow-left' => 'f141',
			'arrow-left-alt' => 'f340',
			'arrow-left-alt2' => 'f341',
			'arrow-right' => 'f139',
			'arrow-right-alt' => 'f344',
			'arrow-right-alt2' => 'f345',
			'arrow-up' => 'f142',
			'arrow-up-alt' => 'f342',
			'arrow-up-alt2' => 'f343',
			'art' => 'f309',
			'awards' => 'f313',
			'backup' => 'f321',
			'block-default' => 'f12b',
			'button' => 'f11a',
			'book' => 'f330',
			'book-alt' => 'f331',
			'businessman' => 'f338',
			'calendar' => 'f145',
			'camera' => 'f306',
			'cart' => 'f174',
			'category' => 'f318',
			'chart-area' => 'f239',
			'chart-bar' => 'f185',
			'chart-line' => 'f238',
			'chart-pie' => 'f184',
			'clock' => 'f469',
			'cloud' => 'f176',
			'cloud-saved' => 'f137',
			'cloud-upload' => 'f13b',
			'cover-image' => 'f13d',
			'columns' => 'f13c',
			'dashboard' => 'f226',
			'desktop' => 'f472',
			'dismiss' => 'f153',
			'download' => 'f316',
			'edit' => 'f464',
			'editor-aligncenter' => 'f207',
			'editor-alignleft' => 'f206',
			'editor-alignright' => 'f208',
			'editor-bold' => 'f200',
			'editor-customchar' => 'f220',
			'editor-distractionfree' => 'f211',
			'editor-help' => 'f223',
			'editor-indent' => 'f222',
			'editor-insertmore' => 'f209',
			'editor-italic' => 'f201',
			'editor-justify' => 'f214',
			'editor-kitchensink' => 'f212',
			'editor-ol' => 'f204',
			'editor-outdent' => 'f221',
			'editor-paste-text' => 'f217',
			'editor-paste-word' => 'f216',
			'editor-quote' => 'f205',
			'editor-removeformatting' => 'f218',
			'editor-rtl' => 'f320',
			'editor-spellcheck' => 'f210',
			'editor-strikethrough' => 'f224',
			'editor-textcolor' => 'f215',
			'editor-ul' => 'f203',
			'editor-underline' => 'f213',
			'editor-unlink' => 'f225',
			'editor-video' => 'f219',
			'exit' => 'f14a',
			'heading' => 'f10e',
			'html' => 'f14b',
			'info-outline' => 'f14c',
			'insert-after' => 'f14d',
			'insert-before' => 'f14e',
			'insert' => 'f10f',
			'remove' => 'f14f',
			'shortcode' => 'f150',
			'email' => 'f465',
			'email-alt' => 'f466',
			'email-alt2' => 'f467',
			'embed-audio' => 'f13e',
			'embed-photo' => 'f144',
			'embed-post' => 'f146',
			'embed-video' => 'f149',
			'exerpt-view' => 'f164',
			'facebook' => 'f304',
			'facebook-alt' => 'f305',
			'feedback' => 'f175',
			'flag' => 'f227',
			'format-aside' => 'f123',
			'format-audio' => 'f127',
			'format-chat' => 'f125',
			'format-gallery' => 'f161',
			'format-image' => 'f128',
			'format-links' => 'f103',
			'format-quote' => 'f122',
			'format-standard' => 'f109',
			'format-status' => 'f130',
			'format-video' => 'f126',
			'forms' => 'f314',
			'googleplus' => 'f462',
			'groups' => 'f307',
			'hammer' => 'f308',
			'id' => 'f336',
			'id-alt' => 'f337',
			'image-crop' => 'f165',
			'image-flip-horizontal' => 'f169',
			'image-flip-vertical' => 'f168',
			'image-rotate-left' => 'f166',
			'image-rotate-right' => 'f167',
			'images-alt' => 'f232',
			'images-alt2' => 'f233',
			'info' => 'f348',
			'leftright' => 'f229',
			'lightbulb' => 'f339',
			'list-view' => 'f163',
			'location' => 'f230',
			'location-alt' => 'f231',
			'lock' => 'f160',
			'marker' => 'f159',
			'menu' => 'f333',
			'migrate' => 'f310',
			'minus' => 'f460',
			'networking' => 'f325',
			'no' => 'f158',
			'no-alt' => 'f335',
			'performance' => 'f311',
			'plus' => 'f132',
			'portfolio' => 'f322',
			'post-status' => 'f173',
			'pressthis' => 'f157',
			'products' => 'f312',
			'redo' => 'f172',
			'rss' => 'f303',
			'screenoptions' => 'f180',
			'search' => 'f179',
			'share' => 'f237',
			'share-alt' => 'f240',
			'share-alt2' => 'f242',
			'shield' => 'f332',
			'shield-alt' => 'f334',
			'slides' => 'f181',
			'smartphone' => 'f470',
			'smiley' => 'f328',
			'sort' => 'f156',
			'sos' => 'f468',
			'star-empty' => 'f154',
			'star-filled' => 'f155',
			'star-half' => 'f459',
			'tablet' => 'f471',
			'tag' => 'f323',
			'testimonial' => 'f473',
			'translation' => 'f326',
			'trash' => 'f182',
			'twitter' => 'f301',
			'undo' => 'f171',
			'update' => 'f463',
			'upload' => 'f317',
			'vault' => 'f178',
			'video-alt' => 'f234',
			'video-alt2' => 'f235',
			'video-alt3' => 'f236',
			'visibility' => 'f177',
			'welcome-add-page' => 'f133',
			'welcome-comments' => 'f117',
			'welcome-edit-page' => 'f119',
			'welcome-learn-more' => 'f118',
			'welcome-view-site' => 'f115',
			'welcome-widgets-menus' => 'f116',
			'wordpress' => 'f120',
			'wordpress-alt' => 'f324',
			'yes' => 'f147',
			'table-col-after' => 'f151',
			'table-col-before' => 'f152',
			'table-col-delete' => 'f15a',
			'table-row-after' => 'f15b',
			'table-row-before' => 'f15c',
			'table-row-delete' => 'f15d',
			'saved' => 'f15e',
			'database-add' => 'f170',
			'database-export' => 'f17a',
			'database-import' => 'f17b',
			'database-remove' => 'f17c',
			'database-view' => 'f17d',
			'database' => 'f17e',
			'airplane' => 'f15f',
			'car' => 'f16b',
			'calculator' => 'f16e',
			'games' => 'f18a',
			'printer' => 'f193',
			'beer' => 'f16c',
			'coffee' => 'f16f',
			'drumstick' => 'f17f',
			'food' => 'f187',
			'bank' => 'f16a',
			'hourglass' => 'f18c',
			'money-alt' => 'f18e',
			'open-folder' => 'f18f',
			'pdf' => 'f190',
			'pets' => 'f191',
			'privacy' => 'f194',
			'superhero' => 'f198',
			'superhero-alt' => 'f197',
			'edit-page' => 'f186',
			'fullscreen-alt' => 'f188',
			'fullscreen-exit-alt' => 'f189',
			// Added in 1.2
			'image-filter' => 'f533',
			'calendar-alt' => 'f508',
			'buddicons-activity' => 'f452',
			'buddicons-friends' => 'f454',
			'buddicons-community' => 'f453',
			'buddicons-forums' => 'f449',
			'buddicons-groups' => 'f456',
			'buddicons-pm' => 'f457',
			'buddicons-replies' => 'f451',
			'buddicons-topics' => 'f450',
			'buddicons-tracking' => 'f455',
			'archive' => 'f480',
			'warning' => 'f534',
			'palmtree' => 'f527',
			'palmtree' => 'f527',
			'album' => 'f514',
			'tickets' => 'f486',
			'tickets-alt' => 'f524',
			'nametag' => 'f486',
			'heart' => 'f487',
			'megaphone' => 'f488',
			'schedule' => 'f489',
			'tide' => 'f10d',
			'code-standards' => 'f13a',
			'universal-access' => 'f483',
			'universal-access-alt' => 'f507',
			'youtube' => 'f19b',
			'reddit' => 'f195',
			'spotify' => 'f196',
			'podio' => 'f19c',
			'clipboard' => 'f481',
			'bell' => 'f16d',
			'businesswoman' => 'f12f',
			'businessperson' => 'f12e',
			'carrot' => 'f511',
			'phone' => 'f525',
			'building' => 'f512',
			'paperclip' => 'f546',
			'color-picker' => 'f131',
			'microphone' =>'f482',
			'editor-code' => 'f475',
			'editor-paragraph' => 'f476',
			'editor-table' => 'f535',
			'ellipsis' => 'f11c',
			'controls-play' => 'f522',
			'controls-volumeon' => 'f521',
			'controls-volumeoff' => 'f520',
			'controls-repeat' => 'f515',
			'media-archive' => 'f501',
			'media-audio' => 'f500',
			'media-code' => 'f499',
			'media-default' => 'f498',
			'media-interactive' => 'f496',
			'media-spreadsheet' => 'f495',
			'media-text' => 'f491',
			'media-video' => 'f490',
			'playlist-audio' => 'f492',
			'playlist-video' => 'f493',
			'filter' => 'f536',
		);
		\ksort( $dashicons );
		return (array) \apply_filters( 'ptu_dashicons_list', $dashicons );
	}

}
