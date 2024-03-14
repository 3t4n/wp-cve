<?php
/**
 * Customizer repeater control main class.
 *
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class Customizer_Repeater.
 */
class Amigo_Customizer_Repeater extends WP_Customize_Control {

	/**
	 * Repeater id.
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Box title.
	 *
	 * @var array
	 */
	private $boxtitle = array();

	/**
	 * Label for new item.
	 *
	 * @var array
	 */
	private $add_field_label = array();

	/**
	 * Icon container.
	 * Enable/disable input.
	 *
	 * @var string
	 */
	private $customizer_icon_container = '';

	/**
	 * Allowed HTML.
	 * Enable/disable input.
	 *
	 * @var array
	 */
	private $allowed_html = array();

	/**
	 * Image input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_image_control = false;

	/**
	 * Icon input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_icon_control = false;

	/**
	 * Color input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_color_control = false;

	/**
	 * Second color input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_color2_control = false;

	/**
	 * Title input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_title_control = false;

	/**
	 * Subtitle input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_subtitle_control = false;

	/**
	 * Text input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_text_control = false;

	/**
	 * Link input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_link_control = false;

	/**
	 * Second text input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_text2_control = false;

	/**
	 * Second link input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_link2_control = false;

	/**
	 * Shortcode input flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_shortcode_control = false;

	/**
	 * Repeater flag.
	 * Enable/disable input.
	 *
	 * @var bool
	 */
	public $customizer_repeater_repeater_control = false;

	private $customizer_repeater_aligment_control = false;

	private $customizer_repeater_checkbox_control = false;

	/**
	 * Customizer_Repeater constructor.
	 *
	 * @param object $manager Customize manager.
	 * @param array  $id Repeater id.
	 * @param array  $args Args.
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new item', 'amigo-extensions' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'amigo-extensions' );
		if ( ! empty( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['customizer_repeater_image_control'] ) ) {
			$this->customizer_repeater_image_control = $args['customizer_repeater_image_control'];
		}

		if ( ! empty( $args['customizer_repeater_icon_control'] ) ) {
			$this->customizer_repeater_icon_control = $args['customizer_repeater_icon_control'];
		}

		if ( ! empty( $args['customizer_repeater_color_control'] ) ) {
			$this->customizer_repeater_color_control = $args['customizer_repeater_color_control'];
		}

		if ( ! empty( $args['customizer_repeater_color2_control'] ) ) {
			$this->customizer_repeater_color2_control = $args['customizer_repeater_color2_control'];
		}

		if ( ! empty( $args['customizer_repeater_title_control'] ) ) {
			$this->customizer_repeater_title_control = $args['customizer_repeater_title_control'];
		}

		if ( ! empty( $args['customizer_repeater_subtitle_control'] ) ) {
			$this->customizer_repeater_subtitle_control = $args['customizer_repeater_subtitle_control'];
		}

		if ( ! empty( $args['customizer_repeater_text_control'] ) ) {
			$this->customizer_repeater_text_control = $args['customizer_repeater_text_control'];
		}

		if ( ! empty( $args['customizer_repeater_link_control'] ) ) {
			$this->customizer_repeater_link_control = $args['customizer_repeater_link_control'];
		}

		if ( ! empty( $args['customizer_repeater_text2_control'] ) ) {
			$this->customizer_repeater_text2_control = $args['customizer_repeater_text2_control'];
		}

		if ( ! empty( $args['customizer_repeater_link2_control'] ) ) {
			$this->customizer_repeater_link2_control = $args['customizer_repeater_link2_control'];
		}

		if ( ! empty( $args['customizer_repeater_shortcode_control'] ) ) {
			$this->customizer_repeater_shortcode_control = $args['customizer_repeater_shortcode_control'];
		}

		if ( ! empty( $args['customizer_repeater_repeater_control'] ) ) {
			$this->customizer_repeater_repeater_control = $args['customizer_repeater_repeater_control'];
		}

		if ( ! empty( $args['customizer_repeater_aligment_control'] ) ) {
			$this->customizer_repeater_aligment_control = $args['customizer_repeater_aligment_control'];
		}

		if ( ! empty( $args['customizer_repeater_checkbox_control'] ) ) {
			$this->customizer_repeater_checkbox_control = $args['customizer_repeater_checkbox_control'];
		}

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		if ( file_exists( AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-repeater-control/inc/icons.php' ) ) {
			$this->customizer_icon_container = AMIGO_PLUGIN_DIR_PATH . '/lib/customizer-controls/customizer-repeater-control/inc/icons.php';
		}

		$allowed_array1 = wp_kses_allowed_html( 'post' );
		$allowed_array2 = array(
			'input' => array(
				'type'        => array(),
				'class'       => array(),
				'placeholder' => array(),
			),
		);

		$this->allowed_html = array_merge( $allowed_array1, $allowed_array2 );
	}

	/**
	 * Enqueue resources for the control
	 */
	public function enqueue() {		

		$path = AMIGO_PLUGIN_DIR_URL . '/lib/customizer-controls/customizer-repeater-control';
		$theme_name = wp_get_theme();

		if($theme_name == 'Industri'){
			wp_enqueue_style( 'amigo-customizer-repeater-font-awesome', get_template_directory_uri() . '/assets/css/fonts/font-awesome/css/font-awesome.min.css', array(), AMIGO_PLUGIN_VER );
		}else{
			wp_enqueue_style( 'amigo-customizer-repeater-font-awesome', get_template_directory_uri() . '/assets/css/font-awesome/css/font-awesome.css', array(), AMIGO_PLUGIN_VER );
		}		

		wp_enqueue_style( 'amigo-customizer-repeater-admin-stylesheet', $path .'/css/admin-style.css', array(), AMIGO_PLUGIN_VER );

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'amigo-customizer-repeater-script', $path .'/js/customizer_repeater.js', array( 'jquery', 'jquery-ui-draggable', 'wp-color-picker' ), AMIGO_PLUGIN_VER, true );

		wp_enqueue_script( 'amigo-customizer-repeater-fontawesome-iconpicker', $path .'/js/fontawesome-iconpicker.js', array( 'jquery' ), AMIGO_PLUGIN_VER, true );

		wp_enqueue_style( 'amigo-customizer-repeater-fontawesome-iconpicker-script', $path.'/css/fontawesome-iconpicker.min.css', array(), AMIGO_PLUGIN_VER );
	}

	/**
	 * Render display function.
	 */
	public function render_content() {

		/*Get default options*/
		$this_default = json_decode( $this->setting->default );

		/*Get values (json format)*/
		$values = $this->value();

		/*Decode values*/
		$json = json_decode( $values );

		if ( ! is_array( $json ) ) {
			$json = array( $values );
		} ?>

		<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
		<div class="customizer-repeater-general-control-repeater customizer-repeater-general-control-droppable">
			<?php
			if ( ( count( $json ) == 1 && '' === $json[0] ) || empty( $json ) ) {
				if ( ! empty( $this_default ) ) {
					$this->iterate_array( $this_default );
					?>
					<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?> class="customizer-repeater-colector" value="<?php echo esc_textarea( json_encode( $this_default ) ); ?>"/>
					<?php
				} else {
					$this->iterate_array();
					?>
					<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?> class="customizer-repeater-colector"/>
					<?php
				}
			} else {
				$this->iterate_array( $json );
				?>
				<input type="hidden" id="customizer-repeater-<?php echo esc_attr( $this->id ); ?>-colector" <?php esc_attr( $this->link() ); ?> class="customizer-repeater-colector" value="<?php echo esc_textarea( $this->value() ); ?>"/>
				<?php
			}
			?>
		</div>
		<button type="button" class="button add_field customizer-repeater-new-field">
			<?php echo esc_html__('Add More','amigo-extensions' ); ?>
		</button>


		<?php
	}

	/**
	 * Iterate through array and show repeater items.
	 *
	 * @param array $array Options.
	 */
	private function iterate_array( $array = array() ) {
		/*Counter that helps checking if the box is first and should have the delete button disabled*/
		$it = 0;
		if ( ! empty( $array ) ) {


			foreach ( $array as $icon ) {
				?>
				<div class="customizer-repeater-general-control-repeater-container customizer-repeater-draggable">
					<div class="customizer-repeater-customize-control-title">
						<?php echo esc_html( $this->boxtitle ); ?>
					</div>
					<div class="customizer-repeater-box-content-hidden">
						<?php
						$choice     = '';
						$image_url  = '';
						$icon_value = '';
						$title      = '';
						$subtitle   = '';
						$text       = '';
						$text2      = '';
						$link2      = '';
						$link       = '';
						$shortcode  = '';
						$repeater   = '';
						$color      = '';
						$color2     = '';
						$aligment = '';
						$checkbox_value = '';

						if ( ! empty( $icon->id ) ) {
							$id = $icon->id;
						}
						if ( ! empty( $icon->choice ) ) {
							$choice = $icon->choice;
						}
						if ( ! empty( $icon->image_url ) ) {
							$image_url = $icon->image_url;
						}
						if ( ! empty( $icon->icon_value ) ) {
							$icon_value = $icon->icon_value;
						}
						if ( ! empty( $icon->color ) ) {
							$color = $icon->color;
						}
						if ( ! empty( $icon->color2 ) ) {
							$color2 = $icon->color2;
						}
						if ( ! empty( $icon->title ) ) {
							$title = $icon->title;
						}
						if ( ! empty( $icon->subtitle ) ) {
							$subtitle = $icon->subtitle;
						}
						if ( ! empty( $icon->text ) ) {
							$text = $icon->text;
						}
						if ( ! empty( $icon->link ) ) {
							$link = $icon->link;
						}
						if ( ! empty( $icon->text2 ) ) {
							$text2 = $icon->text2;
						}
						if ( ! empty( $icon->link2 ) ) {
							$link2 = $icon->link2;
						}
						if ( ! empty( $icon->shortcode ) ) {
							$shortcode = $icon->shortcode;
						}

						if ( ! empty( $icon->social_repeater ) ) {
							$repeater = $icon->social_repeater;
						}

						if(!empty($icon->aligment)){
							$aligment = $icon->aligment;
						}

						if(!empty($icon->checkbox_value)){
							$checkbox_value = $icon->checkbox_value;
						}

						if ( $this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true ) {
							$this->icon_type_choice( $choice );
						}
						if ( $this->customizer_repeater_image_control == true ) {
							$this->image_control( $image_url, $choice );
						}
						if ( $this->customizer_repeater_icon_control == true ) {
							$this->icon_picker_control( $icon_value, $choice );
						}
						if ( $this->customizer_repeater_color_control == true ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'amigo-extensions' ), $this->id, 'customizer_repeater_color_control' ),
									'class'             => 'customizer-repeater-color-control',
									'type'              => apply_filters( 'customizer_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
									'sanitize_callback' => 'sanitize_hex_color',
									'choice'            => $choice,
								), $color
							);
						}
						if ( $this->customizer_repeater_color2_control == true ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'amigo-extensions' ), $this->id, 'customizer_repeater_color2_control' ),
									'class'             => 'customizer-repeater-color2-control',
									'type'              => apply_filters( 'customizer_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
									'sanitize_callback' => 'sanitize_hex_color',
								), $color2
							);
						}
						if ( $this->customizer_repeater_title_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title', 'amigo-extensions' ), $this->id, 'customizer_repeater_title_control' ),
									'class' => 'customizer-repeater-title-control',
									'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
								), $title
							);
						}
						if ( $this->customizer_repeater_subtitle_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle', 'amigo-extensions' ), $this->id, 'customizer_repeater_subtitle_control' ),
									'class' => 'customizer-repeater-subtitle-control',
									'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
								), $subtitle
							);
						}
						if ( $this->customizer_repeater_text_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text', 'amigo-extensions' ), $this->id, 'customizer_repeater_text_control' ),
									'class' => 'customizer-repeater-text-control',
									'type'  => apply_filters( 'customizer_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
								), $text
							);
						}

						if ( $this->customizer_repeater_text2_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Button Text', 'amigo-extensions' ), $this->id, 'customizer_repeater_text2_control' ),
									'class' => 'customizer-repeater-text2-control',
									'type'  => apply_filters( 'customizer_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text2_control' ),
								), $text2
							);
						}

						if ( $this->customizer_repeater_link_control ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'amigo-extensions' ), $this->id, 'customizer_repeater_link_control' ),
									'class'             => 'customizer-repeater-link-control',
									'sanitize_callback' => 'esc_url_raw',
									'type'              => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
								), $link
							);
						}
						
						if ( $this->customizer_repeater_link2_control ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'amigo-extensions' ), $this->id, 'customizer_repeater_link2_control' ),
									'class'             => 'customizer-repeater-link2-control',
									'sanitize_callback' => 'esc_url_raw',
									'type'              => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
								), $link2
							);
						}
						if ( $this->customizer_repeater_shortcode_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode', 'amigo-extensions' ), $this->id, 'customizer_repeater_shortcode_control' ),
									'class' => 'customizer-repeater-shortcode-control',
									'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
								), $shortcode
							);
						}

						if($this->customizer_repeater_aligment_control == true){
							$this->aligment( $aligment );
							
						}

						if($this->customizer_repeater_checkbox_control == true){
							$this->check_box( $checkbox_value );
							
						}

						if ( $this->customizer_repeater_repeater_control == true ) {
							$this->repeater_control( $repeater );
						}
						?>

						<input type="hidden" class="social-repeater-box-id" value="
						<?php
						if ( ! empty( $id ) ) {
							echo esc_attr( $id );
						}
						?>
						">
						<button type="button" class="social-repeater-general-control-remove-field" 
						<?php
						if ( $it == 0 ) {
							echo 'style="display:none;"';
						}
						?>
						>
						<?php esc_html_e( 'Delete field', 'amigo-extensions' ); ?>
					</button>

				</div>
			</div>

			<?php
			$it++;
		}
	} else {
		?>
		<div class="customizer-repeater-general-control-repeater-container">
			<div class="customizer-repeater-customize-control-title">
				<?php echo esc_html( $this->boxtitle ); ?>
			</div>
			<div class="customizer-repeater-box-content-hidden">
				<?php
				if ( $this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true ) {
					$this->icon_type_choice();
				}
				if ( $this->customizer_repeater_image_control == true ) {
					$this->image_control();
				}
				if ( $this->customizer_repeater_icon_control == true ) {
					$this->icon_picker_control();
				}				

				

				if ( $this->customizer_repeater_color_control == true ) {
					$this->input_control(
						array(
							'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'amigo-extensions' ), $this->id, 'customizer_repeater_color_control' ),
							'class'             => 'customizer-repeater-color-control',
							'type'              => apply_filters( 'customizer_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
							'sanitize_callback' => 'sanitize_hex_color',
						)
					);
				}
				if ( $this->customizer_repeater_color2_control == true ) {
					$this->input_control(
						array(
							'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'amigo-extensions' ), $this->id, 'customizer_repeater_color2_control' ),
							'class'             => 'customizer-repeater-color2-control',
							'type'              => apply_filters( 'customizer_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
							'sanitize_callback' => 'sanitize_hex_color',
						)
					);
				}
				if ( $this->customizer_repeater_title_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title', 'amigo-extensions' ), $this->id, 'customizer_repeater_title_control' ),
							'class' => 'customizer-repeater-title-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_subtitle_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle', 'amigo-extensions' ), $this->id, 'customizer_repeater_subtitle_control' ),
							'class' => 'customizer-repeater-subtitle-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_text_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text', 'amigo-extensions' ), $this->id, 'customizer_repeater_text_control' ),
							'class' => 'customizer-repeater-text-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_link_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'amigo-extensions' ), $this->id, 'customizer_repeater_link_control' ),
							'class' => 'customizer-repeater-link-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_text2_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text', 'amigo-extensions' ), $this->id, 'customizer_repeater_text2_control' ),
							'class' => 'customizer-repeater-text2-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text2_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_link2_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'amigo-extensions' ), $this->id, 'customizer_repeater_link2_control' ),
							'class' => 'customizer-repeater-link2-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
						)
					);
				}

				if($this->customizer_repeater_aligment_control == true){
					$this->aligment( $aligment );

				}

				if($this->customizer_repeater_checkbox_control == true){
					$this->check_box();

				}

				if ( $this->customizer_repeater_shortcode_control == true ) {
					$this->input_control(
						array(
							'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode', 'amigo-extensions' ), $this->id, 'customizer_repeater_shortcode_control' ),
							'class' => 'customizer-repeater-shortcode-control',
							'type'  => apply_filters( 'customizer_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
						)
					);
				}
				if ( $this->customizer_repeater_repeater_control == true ) {
					$this->repeater_control();
				}
				?>
				<input type="hidden" class="social-repeater-box-id">
				<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
					<?php esc_html_e( 'Delete field', 'amigo-extensions' ); ?>
				</button>
			</div>
		</div>
		<?php
	}
}

	/**
	 * Display repeater input.
	 *
	 * @param array  $options Input options.
	 * @param string $value Input value.
	 */
	private function input_control( $options, $value = '' ) {
		?>

		<?php
		if ( ! empty( $options['type'] ) ) {
			switch ( $options['type'] ) {
				case 'textarea':
				?>
				<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
				<textarea class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"><?php echo ( ! empty( $options['sanitize_callback'] ) ? call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr( $value ) ); ?></textarea>
				<?php
				break;
				case 'color':
				$style_to_add = '';

				if(isset($options['choice'])){
					if ( $options['choice'] !== 'customizer_repeater_icon' ) {
						$style_to_add = 'display:none';
					}
				}
				
				?>
				<span class="customize-control-title" 
				<?php
				if ( ! empty( $style_to_add ) ) {
					echo 'style="' . esc_attr( $style_to_add ) . '"';}
					?>
					><?php echo esc_html( $options['label'] ); ?></span>
					<div class="<?php echo esc_attr( $options['class'] ); ?>" 
						<?php
						if ( ! empty( $style_to_add ) ) {
							echo 'style="' . esc_attr( $style_to_add ) . '"';}
							?>
							>
							<input type="text" value="<?php echo ( ! empty( $options['sanitize_callback'] ) ? call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr( $value ) ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>" />
						</div>
						<?php
						break;
					}
				} else {
					?>
					<span class="customize-control-title"><?php echo esc_html( $options['label'] ); ?></span>
					<input type="text" value="<?php echo ( ! empty( $options['sanitize_callback'] ) ? call_user_func_array( $options['sanitize_callback'], array( $value ) ) : esc_attr( $value ) ); ?>" class="<?php echo esc_attr( $options['class'] ); ?>" placeholder="<?php echo esc_attr( $options['label'] ); ?>"/>
					<?php
				}
			}

	/**
	 * Icon picker input.
	 *
	 * @param string $value Control value.
	 * @param string $show Flag show/hide input if icon is selected.
	 */
	private function icon_picker_control( $value = '', $show = '' ) {
		?>
		<div class="social-repeater-general-control-icon" 
		<?php
		if ( $show === 'customizer_repeater_image' || $show === 'customizer_repeater_none' ) {
			echo 'style="display:none;"'; }
			?>
			>
			<span class="customize-control-title">
				<?php esc_html_e( 'Icon', 'amigo-extensions' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php
				echo sprintf(
					/* translators: %1$s is Fontawesome link */
					esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'amigo-extensions' ),
					sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'amigo-extensions' ) )
				);
				?>
			</span>
			<div class="input-group icp-container">
				<input data-placement="bottomRight" class="icp icp-auto" value="
				<?php
				if ( ! empty( $value ) ) {
					echo esc_attr( $value );}
					?>
					" type="text">
					<span class="input-group-addon">
						<i class="<?php echo esc_attr( $value ); ?>"></i>
					</span>
				</div>
				<?php  require $this->customizer_icon_container; ?>
			</div>
			<?php
		}


		

	/**
	 * Display image upload input.
	 *
	 * @param string $value Input value.
	 * @param string $show Flag show/hide input if image is selected.
	 */
	private function image_control($value = '', $show = '', $class='', $auto='', $sections=''){ 
		if($auto==1)
		{
			$auto="one";
		}

		if($auto==2)
		{
			$auto="two";
		}
		if($auto==3)
		{
			$auto="three";
		}
		if($auto==4)
		{
			$auto="four";
		}
		?>
		<div class="customizer-repeater-image-control" <?php if( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' ) { echo esc_attr('style="display:none;"'); } ?>>
			<span class="customize-control-title">
				<?php esc_html_e('Image','amigo-extensions')?>
			</span>

			<input type="text" class="widefat custom-media-url <?php echo esc_attr($auto);?>" value="<?php echo esc_attr( $value ); ?>">
			
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button <?php echo esc_attr($auto);?>" value="<?php esc_attr_e( 'Upload Image','amigo-extensions' ); ?>" />
		</div>
		<?php
	}

	/**
	 * Choose between icon or image if both inputs are active.
	 *
	 * @param string $value Choice value.
	 */
	private function icon_type_choice( $value = 'customizer_repeater_icon' ) {
		?>
		<span class="customize-control-title">
			<?php esc_html_e( 'Image type', 'amigo-extensions' ); ?>
		</span>
		<select class="customizer-repeater-image-choice">
			<option value="customizer_repeater_icon" <?php selected( $value, 'customizer_repeater_icon' ); ?>><?php esc_html_e( 'Icon', 'amigo-extensions' ); ?></option>
			<option value="customizer_repeater_image" <?php selected( $value, 'customizer_repeater_image' ); ?>><?php esc_html_e( 'Image', 'amigo-extensions' ); ?></option>
			<option value="customizer_repeater_none" <?php selected( $value, 'customizer_repeater_none' ); ?>><?php esc_html_e( 'None', 'amigo-extensions' ); ?></option>
		</select>
		<?php
	}

	private function aligment($value='left'){?>

		<span class="customize-control-title">
			<?php esc_html_e('Slide Align','amigo-extensions'); ?>
		</span>
		<select class="customizer-repeater-slide-align">
			<option value="left" <?php selected($value,'left');?>>
				<?php esc_html_e('Left','amigo-extensions') ?>
			</option>

			<option value="right" <?php selected($value,'right');?>>
				<?php esc_html_e('Right','amigo-extensions') ?>
			</option>

			<option value="center" <?php selected($value,'center');?>>
				<?php esc_html_e('Center','amigo-extensions') ?>
			</option>


		</select>
		
		<?php
	}

	private function check_box($value='no', $class='', $id='' ){
		?>
		<div class="customizer-repeater-checkbox-container">
			<label>
				<?php esc_html_e('Link Open in New Tab : ','amigo-extensions'); ?>
				<span class="switch">
					<input type="checkbox" name="custom_checkbox" value="yes" <?php if($value=='yes'){echo esc_attr('checked');}?> class="customizer-repeater-checkbox <?php echo esc_attr($class);?> <?php echo esc_attr($id);?>">
				</span>
			</label>
		</div>
		<?php
	}

	/**
	 * Repeater input.
	 *
	 * @param string $value Repeater value.
	 */
	private function repeater_control( $value = '' ) {
		$social_repeater = array();
		$show_del        = 0;
		?>
		<span class="customize-control-title"><?php esc_html_e( 'Social icons', 'amigo-extensions' ); ?></span>
		<?php
		echo '<span class="description customize-control-description">';
		echo sprintf(
			/* translators: %1$s is Fontawesome link. */
			esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at %1$s.', 'amigo-extensions' ),
			sprintf( '<a href="http://fontawesome.io/icons/" rel="nofollow">%s</a>', esc_html__( 'http://fontawesome.io/icons/', 'amigo-extensions' ) )
		);
		echo '</span>';
		if ( ! empty( $value ) ) {
			$social_repeater = json_decode( html_entity_decode( $value ), true );
		}
		if ( ( count( $social_repeater ) == 1 && '' === $social_repeater[0] ) || empty( $social_repeater ) ) {
			?>
			<div class="customizer-repeater-social-repeater">
				<div class="customizer-repeater-social-repeater-container">
					<div class="customizer-repeater-rc input-group icp-container">
						<input data-placement="bottomRight" class="icp icp-auto" value="
						<?php
						if ( ! empty( $value ) ) {
							echo esc_attr( $value ); }
							?>
							" type="text">
							<span class="input-group-addon"></span>
						</div>
						<?php  require $this->customizer_icon_container; ?>
						<input type="text" class="customizer-repeater-social-repeater-link" placeholder="<?php esc_attr_e( 'Link', 'amigo-extensions' ); ?>">
						<input type="hidden" class="customizer-repeater-social-repeater-id" value="">
						<button class="social-repeater-remove-social-item" style="display:none">
							<?php esc_html_e( 'Remove Icon', 'amigo-extensions' ); ?>
						</button>
					</div>
					<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
				</div>
				<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'amigo-extensions' ); ?></button>
				<?php
			} else {
				?>
				<div class="customizer-repeater-social-repeater">
					<?php
					foreach ( $social_repeater as $social_icon ) {
						$show_del ++;
						?>
						<div class="customizer-repeater-social-repeater-container">
							<div class="customizer-repeater-rc input-group icp-container">
								<input data-placement="bottomRight" class="icp icp-auto" value="
								<?php
								if ( ! empty( $social_icon['icon'] ) ) {
									echo esc_attr( $social_icon['icon'] ); }
									?>
									" type="text">
									<span class="input-group-addon"><i class="<?php echo esc_attr( $social_icon['icon'] ); ?>"></i></span>
								</div>
								<?php  require $this->customizer_icon_container; ?>
								<input type="text" class="customizer-repeater-social-repeater-link" placeholder="<?php esc_attr_e( 'Link', 'amigo-extensions' ); ?>" value="
								<?php
								if ( ! empty( $social_icon['link'] ) ) {
									echo esc_url( $social_icon['link'] ); }
									?>
									">
									<input type="hidden" class="customizer-repeater-social-repeater-id" value="
									<?php
									if ( ! empty( $social_icon['id'] ) ) {
										echo esc_attr( $social_icon['id'] ); }
										?>
										">
										<button class="social-repeater-remove-social-item" style="
										<?php
										if ( $show_del == 1 ) {
											echo 'display:none'; }
											?>
											"><?php esc_html_e( 'Remove Icon', 'amigo-extensions' ); ?></button>
										</div>
										<?php
									}
									?>
									<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
								</div>
								<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'amigo-extensions' ); ?></button>
								<?php
							}
						}
					}
