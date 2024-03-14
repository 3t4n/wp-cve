<?php
/**
 *  Form object to easily manage forms.
 *
 * @package SurferSEO
 * @link https://surferseo.com
 */

namespace SurferSEO\Forms;

use SurferSEO\Surferseo;

/**
 * Object to store form data to easily manage forms.
 */
class Surfer_Form {

	const REPO_DB      = 1;
	const REPO_OPTIONS = 2;

	/**
	 * Stores array of fields.
	 *
	 * @var array
	 */
	protected $fields = null;

	/**
	 * Name of the form.
	 *
	 * @var string
	 */
	protected $name = null;

	/**
	 * Method used by the form: GET|POST.
	 *
	 * @var string
	 */
	protected $method = null;

	/**
	 * CSS classes for the form.
	 *
	 * @var string
	 */
	protected $classes = null;

	/**
	 * If there is an error in the form?
	 *
	 * @var bool
	 */
	protected $has_error = false;

	/**
	 * Where to store data - database|options
	 *
	 * @var int
	 */
	protected $repo = null;

	/**
	 * If this form should have submit button.
	 *
	 * @var bool
	 */
	protected $display_submit = true;

	/**
	 * Basic construct.
	 *
	 * @param string $name    - name of the form.
	 * @param string $classes - CSS classes for the form.
	 * @param string $method  - method used by the form GET|POST.
	 */
	public function __construct( $name, $classes = '', $method = 'POST' ) {
		$this->fields  = array();
		$this->name    = $name;
		$this->repo    = self::REPO_DB;
		$this->method  = $method;
		$this->classes = $classes;
	}

	/**
	 * Adds field to form fields list.
	 *
	 * @param Surfer_Form_Element $field - field object.
	 * @return void
	 */
	public function add_field( $field ) {
		$this->fields[ $field->get_name() ] = $field;
	}

	/**
	 * Removes element from fields list.
	 *
	 * @param string $field_name - name of the field.
	 * @return void
	 */
	public function remove_field( $field_name ) {
		if ( isset( $this->fields[ $field_name ] ) ) {
			unset( $this->fields[ $field_name ] );
		}
	}

	/**
	 * Returns selected field element if exists.
	 *
	 * @param string $field_name - name of the field.
	 * @return Surfer_Form_Element|bool
	 */
	public function get_field( $field_name ) {
		if ( isset( $this->fields[ $field_name ] ) ) {
			return $this->fields[ $field_name ];
		}
		return false;
	}

	/**
	 * Returns all fields or empty array.
	 *
	 * @return array
	 */
	public function get_fields() {
		return $this->fields;
	}

	/**
	 * Adds provided values to forms elements.
	 *
	 * @param array $values - array of values to bind into the fields.
	 * @return void
	 */
	public function bind( $values = array() ) {
		if ( is_array( $values ) && count( $values ) > 0 ) {
			foreach ( $this->get_fields() as $field ) {
				if ( 'checkbox' === $field->get_type() ) {
					if ( isset( $values[ $field->get_name() ] ) ) {
						$field->set_value( $values[ $field->get_name() ] );
					} else {
						$field->set_value( false );
					}
				} elseif ( isset( $values[ $field->get_name() ] ) ) {
						$field->set_value( $values[ $field->get_name() ] );
				}
			}
		}
	}

	/**
	 * Validates all the fields in the form.
	 *
	 * @param array $data - data to validate for the fields.
	 * @return bool
	 */
	public function validate( $data ) {
		$valid = true;

		foreach ( $this->fields as $field ) {
			if ( isset( $data[ $field->get_name() ] ) ) {
				$field_validation = $field->validate( $data[ $field->get_name() ] );
				if ( ! $field_validation ) {
					$valid           = false;
					$this->has_error = true;
				}
			}
		}

		return $valid;
	}

	/**
	 * Returns form name.
	 *
	 * @return string
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Returns form method.
	 *
	 * @return string
	 */
	public function get_method() {
		return $this->method;
	}

	/**
	 * Returns form CSS classes
	 *
	 * @return string
	 */
	public function get_classes() {
		return $this->classes;
	}

	/**
	 * Returns has_error param.
	 *
	 * @return bool
	 */
	public function get_error() {
		return $this->has_error;
	}

	/**
	 * Renders form for wp-admin purpose.
	 *
	 * @return void
	 */
	public function render_admin_form() {
		ob_start();
		?>

		<?php foreach ( $this->get_fields() as $field ) : ?>
			<?php if ( 'hidden' === $field->get_type() ) : ?>
				<?php $field->render(); ?>
			<?php endif; ?>
		<?php endforeach; ?>
			<div class="surfer-layout surfer-admin-config-form <?php echo ( Surfer()->get_surfer()->is_surfer_connected() ) ? '' : 'before-connect'; ?>">
				<?php foreach ( $this->get_fields() as $field ) : ?>
					<?php if ( 'hidden' === $field->get_type() ) : ?>
						<?php continue; ?>
					<?php endif; ?>

					<div class="surfer-admin-config-form__single-field-row <?php echo esc_html( $field->get_row_classes() ); ?>">
						<?php if ( $field->has_renderer() ) : ?>
							<div class="surfer-admin-config-form__single-field-row--custom-renderer">
								<?php $field->render(); ?>
							</div>
						<?php else : ?>
							<?php if ( 'header' === $field->get_type() ) : ?>
								<h3 id="<?php echo esc_html( $field->get_name() ); ?>">
									<?php echo esc_html( $field->get_label() ); ?>
								</h3>
								<?php if ( $field->get_hint() ) : ?>
									<span class="surfer-admin-config-form__header_description"><?php echo $field->get_hint(); ?></span>
								<?php endif; ?>
							<?php else : ?>

								<label for="<?php echo esc_html( $field->get_name() ); ?>">
									<?php echo esc_html( $field->get_label() ); ?>
									<?php if ( $field->get_is_required() ) : ?>
										<span style="color: red;">*</span>
									<?php endif; ?>
								</label>

								<div class="surfer_admin_config_form__single_field">
									<?php $field->render(); ?>
									<?php if ( $field->get_hint() ) : ?>
										<br/><small><?php echo $field->get_hint(); ?></small>
									<?php endif; ?>
									<?php if ( count( $field->get_errors() ) > 0 ) : ?>
										<?php foreach ( $field->get_errors() as $error ) : ?>
										<br /><span class="surfer-error"><?php echo esc_html( $error ); ?></span>
										<?php endforeach; ?>
									<?php endif; ?>
								</div>
							<?php endif; ?>
						<?php endif; ?>
					</div>
				<?php endforeach; ?>

				<?php if ( $this->if_display_submit_button() || ( isset( $_GET['developer_mode'] ) && 1 === (int) $_GET['developer_mode'] ) ) : ?>
					<input type="submit" value="Save changes" class="button-primary bottom-submit-button <?php ( ( ! isset( $_GET['developer_mode'] ) || 1 !== (int) $_GET['developer_mode'] ) ) ? 'surfer-connected' : ''; ?>" name="Submit" />
				<?php endif; ?>
			</div>
		<?php
		$html = ob_get_clean();

		echo wp_kses( $html, $this->return_allowed_html_for_forms() );
	}

	/**
	 * Returns array of allowed HTML for form element rendering
	 *
	 * @return array
	 */
	protected function return_allowed_html_for_forms() {
		$allowed_html = array(
			'input'    => array(
				'id'       => array(),
				'name'     => array(),
				'class'    => array(),
				'type'     => array(),
				'value'    => array(),
				'checked'  => array(),
				'selected' => array(),
			),
			'select'   => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'option'   => array(
				'value'    => array(),
				'selected' => array(),
			),
			'textarea' => array(
				'id'    => array(),
				'name'  => array(),
				'class' => array(),
			),
			'a'        => array(
				'href'   => array(),
				'id'     => array(),
				'class'  => array(),
				'target' => array(),
				'rel'    => array(),
			),
			'small'    => array(),
			'br'       => array(),
			'label'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'for'   => array(),
			),
			'span'     => array(
				'id'    => array(),
				'class' => array(),
				'style' => array(),
			),
			'table'    => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'tbody'    => array(),
			'tr'       => array(
				'class'  => array(),
				'id'     => array(),
				'valign' => array(),
				'style'  => array(),
			),
			'th'       => array(
				'class'   => array(),
				'id'      => array(),
				'scope'   => array(),
				'colspan' => array(),
				'style'   => array(),
			),
			'td'       => array(
				'class'   => array(),
				'id'      => array(),
				'colspan' => array(),
				'style'   => array(),
			),
			'h3'       => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'button'   => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'div'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'p'        => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
			),
			'img'      => array(
				'class' => array(),
				'id'    => array(),
				'style' => array(),
				'alt'   => array(),
				'src'   => array(),
			),
			'svg'      => array(
				'width'   => array(),
				'height'  => array(),
				'fill'    => array(),
				'xmlns'   => array(),
				'viewBox' => array(),
			),
			'path'     => array(
				'fill-rule' => array(),
				'clip-rule' => array(),
				'd'         => array(),
			),
		);

		return $allowed_html;
	}

	/**
	 * Save data into repo.
	 *
	 * @param string|bool $tab - config section for saving options.
	 * @return bool
	 */
	public function save( $tab = false ) {
		$save = false;

		if ( self::REPO_OPTIONS === $this->repo ) {
			$save = $this->save_data_into_options( $tab );
		} elseif ( self::REPO_DB === $this->repo ) {
			$save = $this->save_data_into_databse();
		}

		return $save;
	}

	/**
	 * Saves form values into options array.
	 *
	 * @param string|bool $tab - config section for saving options.
	 * @return bool
	 */
	private function save_data_into_options( $tab ) {
		$options = array();
		foreach ( $this->get_fields() as $field ) {
			if ( 'checkbox' === $field->get_type() ) {
				$value = false;
				if ( isset( $_POST[ $field->get_name() ] ) ) {
					$value = $_POST[ $field->get_name() ];
				}

				if ( $value ) {
					$options[ $field->get_name() ] = $value;
				} else {
					$options[ $field->get_name() ] = null;
				}
			} else {
				$options[ $field->get_name() ] = $field->get_value();
			}
		}

		return Surferseo::get_instance()->get_surfer_settings()->save_options( $tab, $options );
	}

	/**
	 * Saves form values into database.
	 *
	 * @return bool
	 */
	private function save_data_into_databse() {
		return false;
	}

	/**
	 * Returns information if submit button should be visible for this form.
	 *
	 * @return bool
	 */
	public function if_display_submit_button() {
		return $this->display_submit;
	}
}
