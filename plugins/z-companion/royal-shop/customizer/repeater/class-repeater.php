<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
/**
 * Repeater functionality in customize
 * @package WPZita
 * @subpackage  Royal Shop
 */

if ( ! class_exists( 'WP_Customize_Control' ) ) {
	return null;
}

/**
 * Class Z_COMPANION_Royal_Shop_Repeater
 */
class Z_COMPANION_Royal_Shop_Repeater extends WP_Customize_Control {

	/**
	 * ID of the field
	 *
	 * @var string
	 */
	public $id;

	/**
	 * Repeater box title
	 *
	 * @var array
	 */
	private $boxtitle = array();

	/**
	 * Repeater Add field button label
	 *
	 * @var array
	 */
	private $add_field_label = array();

	/**
	 * Repeater Icon container
	 *
	 * @var string
	 */
	private $customizer_icon_container = '';

	/**
	 * Repeater Allowed HTML tags
	 *
	 * @var array
	 */
	private $allowed_html = array();

	/**
	 * Check if image control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_image_control = false;

	/**
	 * Check if logo image control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_logo_image_control = false;

	/**
	 * Check if icon control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_icon_control = false;

	/**
	 * Check if color control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_color_control = false;

	/**
	 * Check if second color control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_color2_control = false;

	/**
	 * Check if title control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_title_control = false;

	/**
	 * Check if Price control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_price_control = false;

	/**
	 * Check if number control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_number_control = false;

	/**
	 * Check if subtitle control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_subtitle_control = false;

	/**
	 * Check if text control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_text_control = false;

	/**
	 * Check if link control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_link_control = false;

	/**
	 * Check if second text control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_text2_control = false;

	/**
	 * Check if second link control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_link2_control = false;

	/**
	 * Check if shortcode control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_shortcode_control = false;

	/**
	 * Check if internal repeater control is added in the repetear
	 *
	 * @var bool
	 */
	public $customizer_repeater_repeater_control = false;


	/**
	 * Class constructor
	 */
	public function __construct( $manager, $id, $args = array() ) {
		parent::__construct( $manager, $id, $args );
		/*Get options from customizer.php*/
		$this->add_field_label = esc_html__( 'Add new field', 'z-companion' );
		if ( ! empty( $args['add_field_label'] ) ) {
			$this->add_field_label = $args['add_field_label'];
		}

		$this->boxtitle = esc_html__( 'Customizer Repeater', 'z-companion' );
		if ( ! empty( $args['item_name'] ) ) {
			$this->boxtitle = $args['item_name'];
		} elseif ( ! empty( $this->label ) ) {
			$this->boxtitle = $this->label;
		}

		if ( ! empty( $args['customizer_repeater_image_control'] ) ) {
			$this->customizer_repeater_image_control = $args['customizer_repeater_image_control'];
		}

		//logo image
		if ( ! empty( $args['customizer_repeater_logo_image_control'] ) ) {
			$this->customizer_repeater_logo_image_control = $args['customizer_repeater_logo_image_control'];
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

		if ( ! empty( $args['customizer_repeater_price_control'] ) ) {
			$this->customizer_repeater_price_control = $args['customizer_repeater_price_control'];
		}

		if ( ! empty( $args['customizer_repeater_number_control'] ) ) {
			$this->customizer_repeater_number_control = $args['customizer_repeater_number_control'];
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

		if ( ! empty( $id ) ) {
			$this->id = $id;
		}

		if ( file_exists( Z_COMPANION_PLUGIN_DIR_URL . 'royal-shop/customizer/repeater/icon-picker/icons.php' ) ) {
			$this->customizer_icon_container = 'royal-shop/customizer/repeater/icon-picker/icons';
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
		
		wp_enqueue_style( 'font-awesome', Z_COMPANION_PLUGIN_DIR_URL  . '/assets/fonts/font-awesome/css/font-awesome.css', array(), '1.0.0' );

		wp_enqueue_style( 'royal_shop_customizer-repeater-admin-stylesheet', Z_COMPANION_PLUGIN_DIR_URL  . '/royal-shop/customizer/repeater/style.css', array(), '1.0.0' );

		wp_enqueue_style( 'wp-color-picker' );

		wp_enqueue_script( 'royal_shop_customizer-repeater-script', Z_COMPANION_PLUGIN_DIR_URL  . '/royal-shop/customizer/repeater/script.js', array( 'jquery', 'jquery-ui-draggable', 'wp-color-picker' ), '1.0.0', true );

		wp_enqueue_script( 'royal_shop_customizer-repeater-fontawesome-iconpicker', Z_COMPANION_PLUGIN_DIR_URL  . '/royal-shop/customizer/repeater/icon-picker/icon-picker.js', array( 'jquery' ), '1.0.0', true );

		wp_enqueue_style( 'royal_shop_customizer-repeater-fontawesome-iconpicker-script', Z_COMPANION_PLUGIN_DIR_URL . '/royal-shop/customizer/repeater/icon-picker/icon-picker.css', array(), '1.0.0' );

	}

	/**
	 * Render the control
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
			<?php echo esc_html( $this->add_field_label ); ?>
		</button>
		<?php
	}

	/**
	 * Iterate array
	 *
	 * @param array $array Array to iterate.
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
						$choice = '';
						if ( $this->id === 'open_features_content' ) {
							$choice = 'customizer_repeater_icon';
						}

						$image_url  = '';
						$logo_image_url  = '';
						$icon_value = '';
						$title      = '';
						$price     = '';
						$number     = '';
						$subtitle   = '';
						$text       = '';
						$text2      = '';
						$link2      = '';
						$link       = '';
						$shortcode  = '';
						$repeater   = '';
						$color      = '';
						$color2     = '';
						

						if ( ! empty( $icon->id ) ) {
							$id = $icon->id;
						}

						if ( ! empty( $icon->choice ) ) {
							$choice = $icon->choice;
						}

						if ( ! empty( $icon->image_url ) ) {
							$image_url = $icon->image_url;
						}

						if ( ! empty( $icon->logo_image_url ) ) {
							$logo_image_url = $icon->logo_image_url;
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

						if ( ! empty( $icon->price ) ) {
							$price = $icon->price;
						}

						if ( ! empty( $icon->number ) ) {
							$number = $icon->number;
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

						if ( $this->customizer_repeater_image_control == true && $this->customizer_repeater_icon_control == true ) {
							$this->icon_type_choice( $choice );
						}

						if ( $this->customizer_repeater_image_control == true ) {
							$this->image_control( $image_url, $choice );
						}

						if ( $this->customizer_repeater_logo_image_control == true ) {
							$this->logo_image_control( $logo_image_url, $choice );
						}

						if ( $this->customizer_repeater_icon_control == true ) {
							$this->icon_picker_control( $icon_value, $choice );
						}

						if ( $this->customizer_repeater_color_control == true ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'z-companion' ), $this->id, 'customizer_repeater_color_control' ),
									'class'             => 'customizer-repeater-color-control',
									'type'              => apply_filters( 'open_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
									'sanitize_callback' => 'sanitize_hex_color',
									'choice'            => $choice,
								), $color
							);
						}
						if ( $this->customizer_repeater_color2_control == true ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'z-companion' ), $this->id, 'customizer_repeater_color2_control' ),
									'class'             => 'customizer-repeater-color2-control',
									'type'              => apply_filters( 'open_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
									'sanitize_callback' => 'sanitize_hex_color',
								), $color2
							);
						}
						if ( $this->customizer_repeater_title_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title', 'z-companion' ), $this->id, 'customizer_repeater_title_control' ),
									'class' => 'customizer-repeater-title-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
								), $title
							);
						}

///////////// price///////////
						if ( $this->customizer_repeater_price_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Price', 'z-companion' ), $this->id, 'customizer_repeater_price_control' ),
									'class' => 'customizer-repeater-price-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_price_control' ),
								), $price
							);
						}

///////////// number///////////
						if ( $this->customizer_repeater_number_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Number', 'z-companion' ), $this->id, 'customizer_repeater_number_control' ),
									'class' => 'customizer-repeater-number-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_number_control' ),
								), $number
							);
						}

						if ( $this->customizer_repeater_subtitle_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle', 'z-companion' ), $this->id, 'customizer_repeater_subtitle_control' ),
									'class' => 'customizer-repeater-subtitle-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
								), $subtitle
							);
						}
						if ( $this->customizer_repeater_text_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text', 'z-companion' ), $this->id, 'customizer_repeater_text_control' ),
									'class' => 'customizer-repeater-text-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
								), $text
							);
						}
						if ( $this->customizer_repeater_link_control ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'z-companion' ), $this->id, 'customizer_repeater_link_control' ),
									'class'             => 'customizer-repeater-link-control',
									'sanitize_callback' => 'esc_url_raw',
									'type'              => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
								), $link
							);
						}
						if ( $this->customizer_repeater_text2_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Button Text', 'z-companion' ), $this->id, 'customizer_repeater_text2_control' ),
									'class' => 'customizer-repeater-text2-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text2_control' ),
								), $text2
							);
						}
						if ( $this->customizer_repeater_link2_control ) {
							$this->input_control(
								array(
									'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'z-companion' ), $this->id, 'customizer_repeater_link2_control' ),
									'class'             => 'customizer-repeater-link2-control',
									'sanitize_callback' => 'esc_url_raw',
									'type'              => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
								), $link2
							);
						}
						if ( $this->customizer_repeater_shortcode_control == true ) {
							$this->input_control(
								array(
									'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode', 'z-companion' ), $this->id, 'customizer_repeater_shortcode_control' ),
									'class' => 'customizer-repeater-shortcode-control',
									'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
								), $shortcode
							);
						}
						if ( $this->customizer_repeater_repeater_control == true ) {
							$this->repeater_control( $repeater );
						}
						echo '<input type="hidden" class="social-repeater-box-id" value="';
						if ( ! empty( $id ) ) {
							echo esc_attr( $id );
						}
						echo '">';
						echo '<button type="button" class="social-repeater-general-control-remove-field"';
						if ( $it == 0 ) {
							echo 'style="display:none;"';
						}
						echo '>';
						esc_html_e( 'Delete field', 'z-companion' );
						?>
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
					if ( $this->customizer_repeater_logo_image_control == true ) {
						$this->logo_image_control();
					} 
					if ( $this->customizer_repeater_icon_control == true ) {
						$this->icon_picker_control();
					}
					if ( $this->customizer_repeater_color_control == true ) {
						$this->input_control(
							array(
								'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'z-companion' ), $this->id, 'customizer_repeater_color_control' ),
								'class'             => 'customizer-repeater-color-control',
								'type'              => apply_filters( 'open_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color_control' ),
								'sanitize_callback' => 'sanitize_hex_color',
							)
						);
					}
					if ( $this->customizer_repeater_color2_control == true ) {
						$this->input_control(
							array(
								'label'             => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Color', 'z-companion' ), $this->id, 'customizer_repeater_color2_control' ),
								'class'             => 'customizer-repeater-color2-control',
								'type'              => apply_filters( 'open_repeater_input_types_filter', 'color', $this->id, 'customizer_repeater_color2_control' ),
								'sanitize_callback' => 'sanitize_hex_color',
							)
						);
					}
					if ( $this->customizer_repeater_title_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Title', 'z-companion' ), $this->id, 'customizer_repeater_title_control' ),
								'class' => 'customizer-repeater-title-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_title_control' ),
							)
						);
					}

//price control//////////
						if ( $this->customizer_repeater_price_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Price', 'z-companion' ), $this->id, 'customizer_repeater_price_control' ),
								'class' => 'customizer-repeater-price-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_price_control' ),
							)
						);
					}

//number control//////////
						if ( $this->customizer_repeater_number_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Number', 'z-companion' ), $this->id, 'customizer_repeater_number_control' ),
								'class' => 'customizer-repeater-number-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_number_control' ),
							)
						);
					}


					if ( $this->customizer_repeater_subtitle_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Subtitle', 'z-companion' ), $this->id, 'customizer_repeater_subtitle_control' ),
								'class' => 'customizer-repeater-subtitle-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_subtitle_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_text_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Text', 'z-companion' ), $this->id, 'customizer_repeater_text_control' ),
								'class' => 'customizer-repeater-text-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_link_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'z-companion' ), $this->id, 'customizer_repeater_link_control' ),
								'class' => 'customizer-repeater-link-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_text2_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Button Text', 'z-companion' ), $this->id, 'customizer_repeater_text2_control' ),
								'class' => 'customizer-repeater-text2-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', 'textarea', $this->id, 'customizer_repeater_text2_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_link2_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Link', 'z-companion' ), $this->id, 'customizer_repeater_link2_control' ),
								'class' => 'customizer-repeater-link2-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_link2_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_shortcode_control == true ) {
						$this->input_control(
							array(
								'label' => apply_filters( 'repeater_input_labels_filter', esc_html__( 'Shortcode', 'z-companion' ), $this->id, 'customizer_repeater_shortcode_control' ),
								'class' => 'customizer-repeater-shortcode-control',
								'type'  => apply_filters( 'open_repeater_input_types_filter', '', $this->id, 'customizer_repeater_shortcode_control' ),
							)
						);
					}
					if ( $this->customizer_repeater_repeater_control == true ) {
						$this->repeater_control();
					}
					?>
					<input type="hidden" class="social-repeater-box-id">
					<button type="button" class="social-repeater-general-control-remove-field button" style="display:none;">
						<?php esc_html_e( 'Delete field', 'z-companion' ); ?>
					</button>
				</div>
			</div>
			<?php
		}
	}

	/**
	 * Input control
	 *
	 * @param array  $options Options.
	 * @param string $value Value.
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
					if ( $this->id === 'open_features_content' && $options['choice'] !== 'customizer_repeater_icon' ) {
						$style_to_add = 'display:none';
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
	 * Icon picker control
	 *
	 * @param string $value Value.
	 * @param string $show Show or not.
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
				<?php esc_html_e( 'Icon', 'z-companion' ); ?>
			</span>
			<span class="description customize-control-description">
				<?php
				echo sprintf(
					/* translators: Fontawesome link with full list of icons available */
					esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at Fontawesome Website.', 'z-companion' )
					
				);
				?>
			</span>
			<div class="input-group icp-container">
				<?php
				echo '<input data-placement="bottomRight" class="icp icp-auto" value="';
				if ( ! empty( $value ) ) {
					echo esc_attr( $value );
				}
				echo '" type="text">';
				?>
				<span class="input-group-addon">
					<i class="fa <?php echo esc_attr( $value ); ?>"></i>
				</span>
			<div class="input-group-close">
				<i class="fa fa-times"></i>
			</div>
			
			
			</div>
			<div class="iconpicker-popover popover bottomLeft">
	<div class="arrow"></div>
	<div class="popover-title">
		<input type="search" class="form-control iconpicker-search" placeholder="Type to filter">
	</div>
	<div class="popover-content">
		<div class="iconpicker">
			<div class="iconpicker-items">

				<i data-type="iconpicker-item" title=".fa-behance" class="fa fa-behance"></i>
				<i data-type="iconpicker-item" title=".fa-behance-square" class="fa fa-behance-square"></i>
				<i data-type="iconpicker-item" title=".fa-facebook" class="fa fa-facebook"></i>
				<i data-type="iconpicker-item" title=".fa-facebook-square" class="fa fa-facebook-square"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus" class="fa fa-google-plus"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus-square" class="fa fa-google-plus-square"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin" class="fa fa-linkedin"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin-square" class="fa fa-linkedin-square"></i>
				<i data-type="iconpicker-item" title=".fa-twitter" class="fa fa-twitter"></i>
				<i data-type="iconpicker-item" title=".fa-twitter-square" class="fa fa-twitter-square"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo" class="fa fa-vimeo"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo-square" class="fa fa-vimeo-square"></i>
				<i data-type="iconpicker-item" title=".fa-youtube" class="fa fa-youtube"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-square" class="fa fa-youtube-square"></i>
				<i data-type="iconpicker-item" title=".fa-ambulance" class="fa fa-ambulance"></i>
				<i data-type="iconpicker-item" title=".fa-american-sign-language-interpreting" class="fa fa-american-sign-language-interpreting"></i>
				<i data-type="iconpicker-item" title=".fa-anchor" class="fa fa-anchor"></i>
				<i data-type="iconpicker-item" title=".fa-android" class="fa fa-android"></i>
				<i data-type="iconpicker-item" title=".fa-apple" class="fa fa-apple"></i>
				<i data-type="iconpicker-item" title=".fa-archive" class="fa fa-archive"></i>
				<i data-type="iconpicker-item" title=".fa-area-chart" class="fa fa-area-chart"></i>
				<i data-type="iconpicker-item" title=".fa-asterisk" class="fa fa-asterisk"></i>
				<i data-type="iconpicker-item" title=".fa-automobile" class="fa fa-automobile"></i>
				<i data-type="iconpicker-item" title=".fa-balance-scale" class="fa fa-balance-scale"></i>
				<i data-type="iconpicker-item" title=".fa-ban" class="fa fa-ban"></i>
				<i data-type="iconpicker-item" title=".fa-bank" class="fa fa-bank"></i>
				<i data-type="iconpicker-item" title=".fa-bicycle" class="fa fa-bicycle"></i>
				<i data-type="iconpicker-item" title=".fa-birthday-cake" class="fa fa-birthday-cake"></i>
				<i data-type="iconpicker-item" title=".fa-btc" class="fa fa-btc"></i>
				<i data-type="iconpicker-item" title=".fa-black-tie" class="fa fa-black-tie"></i>
				<i data-type="iconpicker-item" title=".fa-bookmark" class="fa fa-bookmark"></i>
				<i data-type="iconpicker-item" title=".fa-briefcase" class="fa fa-briefcase"></i>
				<i data-type="iconpicker-item" title=".fa-bus" class="fa fa-bus"></i>
				<i data-type="iconpicker-item" title=".fa-cab" class="fa fa-cab"></i>
				<i data-type="iconpicker-item" title=".fa-camera" class="fa fa-camera"></i>
				<i data-type="iconpicker-item" title=".fa-check" class="fa fa-check"></i>
				<i data-type="iconpicker-item" title=".fa-child" class="fa fa-child"></i>
				<i data-type="iconpicker-item" title=".fa-code" class="fa fa-code"></i>
				<i data-type="iconpicker-item" title=".fa-coffee" class="fa fa-coffee"></i>
				<i data-type="iconpicker-item" title=".fa-cog" class="fa fa-cog"></i>
				<i data-type="iconpicker-item" title=".fa-commenting" class="fa fa-commenting"></i>
				<i data-type="iconpicker-item" title=".fa-cube" class="fa fa-cube"></i>
				<i data-type="iconpicker-item" title=".fa-dollar" class="fa fa-dollar"></i>
				<i data-type="iconpicker-item" title=".fa-diamond" class="fa fa-diamond"></i>
				<i data-type="iconpicker-item" title=".fa-envelope" class="fa fa-envelope"></i>
				<i data-type="iconpicker-item" title=".fa-female" class="fa fa-female"></i>
				<i data-type="iconpicker-item" title=".fa-fire-extinguisher" class="fa fa-fire-extinguisher"></i>
				<i data-type="iconpicker-item" title=".fa-glass" class="fa fa-glass"></i>
				<i data-type="iconpicker-item" title=".fa-globe" class="fa fa-globe"></i>
				<i data-type="iconpicker-item" title=".fa-graduation-cap" class="fa fa-graduation-cap"></i>
				<i data-type="iconpicker-item" title=".fa-heartbeat" class="fa fa-heartbeat"></i>
				<i data-type="iconpicker-item" title=".fa-heart" class="fa fa-heart"></i>
				<i data-type="iconpicker-item" title=".fa-hotel" class="fa fa-hotel"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-home" class="fa fa-home"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-legal" class="fa fa-legal"></i>
				<i data-type="iconpicker-item" title=".fa-lock" class="fa fa-lock"></i>
				<i data-type="iconpicker-item" title=".fa-map-signs" class="fa fa-map-signs"></i>
				<i data-type="iconpicker-item" title=".fa-paint-brush" class="fa fa-paint-brush"></i>
				<i data-type="iconpicker-item" title=".fa-plane" class="fa fa-plane"></i>
				<i data-type="iconpicker-item" title=".fa-rocket" class="fa fa-rocket"></i>
				<i data-type="iconpicker-item" title=".fa-puzzle-piece" class="fa fa-puzzle-piece"></i>
				<i data-type="iconpicker-item" title=".fa-shield" class="fa fa-shield"></i>
				<i data-type="iconpicker-item" title=".fa-tag" class="fa fa-tag"></i>
				<i data-type="iconpicker-item" title=".fa-times" class="fa fa-times"></i>
				<i data-type="iconpicker-item" title=".fa-unlock" class="fa fa-unlock"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user-md" class="fa fa-user-md"></i>
				<i data-type="iconpicker-item" title=".fa-video-camera" class="fa fa-video-camera"></i>
				<i data-type="iconpicker-item" title=".fa-wordpress" class="fa fa-wordpress"></i>
				<i data-type="iconpicker-item" title=".fa-wrench" class="fa fa-wrench"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-play" class="fa fa-youtube-play"></i>

			</div> <!-- /.iconpicker-items -->
		</div> <!-- /.iconpicker -->
	</div> <!-- /.popover-content -->
</div> <!-- /.iconpicker-popover -->
		</div>
		<?php
	}

	/**
	 * Image control
	 *
	 * @param string $value Value.
	 * @param string $show Show or not.
	 */
	private function image_control( $value = '', $show = '' ) {
		?>
		<div class="customizer-repeater-image-control" 
		<?php
		if ( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' || ( $this->id === 'open_features_content' && empty( $show ) ) ) {
			echo 'style="display:none;"'; }
		?>
			>
			<span class="customize-control-title">
				<?php esc_html_e( 'Image', 'z-companion' ); ?>
			</span>
			<input type="text" class="widefat custom-media-url" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-custom-media-button" value="<?php esc_attr_e( 'Upload Image', 'z-companion' ); ?>" />
		</div>
		<?php
	}

	/**
	 * Logo Image control
	 *
	 * @param string $value Value.
	 * @param string $show Show or not.
	 */
	private function logo_image_control( $value = '', $show = '' ) {
		?>
		<div class="customizer-repeater-logo-image-control" 
		<?php
		if ( $show === 'customizer_repeater_icon' || $show === 'customizer_repeater_none' || ( $this->id === 'open_features_content' && empty( $show ) ) ) {
			echo 'style="display:none;"'; }
		?>
			>
			<span class="customize-control-title">
				<?php esc_html_e( 'Logo Image', 'z-companion' ); ?>
			</span>
			<input type="text" class="widefat custom-logo-media-url" value="<?php echo esc_attr( $value ); ?>">
			<input type="button" class="button button-secondary customizer-repeater-logo-custom-media-button" value="<?php esc_attr_e( 'Upload Image', 'z-companion' ); ?>" />
		</div>
		<?php
	}

	/**
	 * Icon/Image/None select control
	 *
	 * @param string $value Select control type.
	 */
	private function icon_type_choice( $value = 'customizer_repeater_icon' ) {
		?>
		<span class="customize-control-title">
			<?php esc_html_e( 'Image type', 'z-companion' ); ?>
		</span>
		<select class="customizer-repeater-image-choice">
			<option value="customizer_repeater_icon" <?php selected( $value, 'customizer_repeater_icon' ); ?>><?php esc_html_e( 'Icon', 'z-companion' ); ?></option>
			<option value="customizer_repeater_image" <?php selected( $value, 'customizer_repeater_image' ); ?>><?php esc_html_e( 'Image', 'z-companion' ); ?></option>
			<option value="customizer_repeater_logo_image" <?php selected( $value, 'customizer_repeater_logo_image' ); ?>><?php esc_html_e( 'Logo Image', 'z-companion' ); ?></option>
			<option value="customizer_repeater_none" <?php selected( $value, 'customizer_repeater_none' ); ?>><?php esc_html_e( 'None', 'z-companion' ); ?></option>
		</select>
		<?php
	}

	/**
	 * Repeater control
	 *
	 * @param string $value Value.
	 */
	private function repeater_control( $value = '' ) {
		$social_repeater = array();
		$show_del        = 0;
		?>
		<span class="customize-control-title"><?php esc_html_e( 'Social icons', 'z-companion' ); ?></span>
		<?php
		echo '<span class="description customize-control-description">';
		echo sprintf(
			/* translators: Fontawesome link with full list of icons available */
			esc_html__( 'Note: Some icons may not be displayed here. You can see the full list of icons at Fontawesome website .', 'z-companion' )
			
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
						<?php
						echo '<input data-placement="bottomRight" class="icp icp-auto" value="';
						if ( ! empty( $value ) ) {
							echo esc_attr( $value );
						}
						echo '" type="text">';
						?>
						<span class="input-group-addon"></span>
					</div>
					<div class="iconpicker-popover popover bottomLeft">
	<div class="arrow"></div>
	<div class="popover-title">
		<input type="search" class="form-control iconpicker-search" placeholder="Type to filter">
	</div>
	<div class="popover-content">
		<div class="iconpicker">
			<div class="iconpicker-items">

				<i data-type="iconpicker-item" title=".fa-behance" class="fa fa-behance"></i>
				<i data-type="iconpicker-item" title=".fa-behance-square" class="fa fa-behance-square"></i>
				<i data-type="iconpicker-item" title=".fa-facebook" class="fa fa-facebook"></i>
				<i data-type="iconpicker-item" title=".fa-facebook-square" class="fa fa-facebook-square"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus" class="fa fa-google-plus"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus-square" class="fa fa-google-plus-square"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin" class="fa fa-linkedin"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin-square" class="fa fa-linkedin-square"></i>
				<i data-type="iconpicker-item" title=".fa-twitter" class="fa fa-twitter"></i>
				<i data-type="iconpicker-item" title=".fa-twitter-square" class="fa fa-twitter-square"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo" class="fa fa-vimeo"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo-square" class="fa fa-vimeo-square"></i>
				<i data-type="iconpicker-item" title=".fa-youtube" class="fa fa-youtube"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-square" class="fa fa-youtube-square"></i>
				<i data-type="iconpicker-item" title=".fa-ambulance" class="fa fa-ambulance"></i>
				<i data-type="iconpicker-item" title=".fa-american-sign-language-interpreting" class="fa fa-american-sign-language-interpreting"></i>
				<i data-type="iconpicker-item" title=".fa-anchor" class="fa fa-anchor"></i>
				<i data-type="iconpicker-item" title=".fa-android" class="fa fa-android"></i>
				<i data-type="iconpicker-item" title=".fa-apple" class="fa fa-apple"></i>
				<i data-type="iconpicker-item" title=".fa-archive" class="fa fa-archive"></i>
				<i data-type="iconpicker-item" title=".fa-area-chart" class="fa fa-area-chart"></i>
				<i data-type="iconpicker-item" title=".fa-asterisk" class="fa fa-asterisk"></i>
				<i data-type="iconpicker-item" title=".fa-automobile" class="fa fa-automobile"></i>
				<i data-type="iconpicker-item" title=".fa-balance-scale" class="fa fa-balance-scale"></i>
				<i data-type="iconpicker-item" title=".fa-ban" class="fa fa-ban"></i>
				<i data-type="iconpicker-item" title=".fa-bank" class="fa fa-bank"></i>
				<i data-type="iconpicker-item" title=".fa-bicycle" class="fa fa-bicycle"></i>
				<i data-type="iconpicker-item" title=".fa-birthday-cake" class="fa fa-birthday-cake"></i>
				<i data-type="iconpicker-item" title=".fa-btc" class="fa fa-btc"></i>
				<i data-type="iconpicker-item" title=".fa-black-tie" class="fa fa-black-tie"></i>
				<i data-type="iconpicker-item" title=".fa-bookmark" class="fa fa-bookmark"></i>
				<i data-type="iconpicker-item" title=".fa-briefcase" class="fa fa-briefcase"></i>
				<i data-type="iconpicker-item" title=".fa-bus" class="fa fa-bus"></i>
				<i data-type="iconpicker-item" title=".fa-cab" class="fa fa-cab"></i>
				<i data-type="iconpicker-item" title=".fa-camera" class="fa fa-camera"></i>
				<i data-type="iconpicker-item" title=".fa-check" class="fa fa-check"></i>
				<i data-type="iconpicker-item" title=".fa-child" class="fa fa-child"></i>
				<i data-type="iconpicker-item" title=".fa-code" class="fa fa-code"></i>
				<i data-type="iconpicker-item" title=".fa-coffee" class="fa fa-coffee"></i>
				<i data-type="iconpicker-item" title=".fa-cog" class="fa fa-cog"></i>
				<i data-type="iconpicker-item" title=".fa-commenting" class="fa fa-commenting"></i>
				<i data-type="iconpicker-item" title=".fa-cube" class="fa fa-cube"></i>
				<i data-type="iconpicker-item" title=".fa-dollar" class="fa fa-dollar"></i>
				<i data-type="iconpicker-item" title=".fa-diamond" class="fa fa-diamond"></i>
				<i data-type="iconpicker-item" title=".fa-envelope" class="fa fa-envelope"></i>
				<i data-type="iconpicker-item" title=".fa-female" class="fa fa-female"></i>
				<i data-type="iconpicker-item" title=".fa-fire-extinguisher" class="fa fa-fire-extinguisher"></i>
				<i data-type="iconpicker-item" title=".fa-glass" class="fa fa-glass"></i>
				<i data-type="iconpicker-item" title=".fa-globe" class="fa fa-globe"></i>
				<i data-type="iconpicker-item" title=".fa-graduation-cap" class="fa fa-graduation-cap"></i>
				<i data-type="iconpicker-item" title=".fa-heartbeat" class="fa fa-heartbeat"></i>
				<i data-type="iconpicker-item" title=".fa-heart" class="fa fa-heart"></i>
				<i data-type="iconpicker-item" title=".fa-hotel" class="fa fa-hotel"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-home" class="fa fa-home"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-legal" class="fa fa-legal"></i>
				<i data-type="iconpicker-item" title=".fa-lock" class="fa fa-lock"></i>
				<i data-type="iconpicker-item" title=".fa-map-signs" class="fa fa-map-signs"></i>
				<i data-type="iconpicker-item" title=".fa-paint-brush" class="fa fa-paint-brush"></i>
				<i data-type="iconpicker-item" title=".fa-plane" class="fa fa-plane"></i>
				<i data-type="iconpicker-item" title=".fa-rocket" class="fa fa-rocket"></i>
				<i data-type="iconpicker-item" title=".fa-puzzle-piece" class="fa fa-puzzle-piece"></i>
				<i data-type="iconpicker-item" title=".fa-shield" class="fa fa-shield"></i>
				<i data-type="iconpicker-item" title=".fa-tag" class="fa fa-tag"></i>
				<i data-type="iconpicker-item" title=".fa-times" class="fa fa-times"></i>
				<i data-type="iconpicker-item" title=".fa-unlock" class="fa fa-unlock"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user-md" class="fa fa-user-md"></i>
				<i data-type="iconpicker-item" title=".fa-video-camera" class="fa fa-video-camera"></i>
				<i data-type="iconpicker-item" title=".fa-wordpress" class="fa fa-wordpress"></i>
				<i data-type="iconpicker-item" title=".fa-wrench" class="fa fa-wrench"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-play" class="fa fa-youtube-play"></i>

			</div> <!-- /.iconpicker-items -->
		</div> <!-- /.iconpicker -->
	</div> <!-- /.popover-content -->
</div> <!-- /.iconpicker-popover -->
					<input type="text" class="customizer-repeater-social-repeater-link" placeholder="<?php esc_attr_e( 'Link', 'z-companion' ); ?>">
					<input type="hidden" class="customizer-repeater-social-repeater-id" value="">
					<button class="social-repeater-remove-social-item" style="display:none">
						<?php esc_html_e( 'Remove Icon', 'z-companion' ); ?>
					</button>
				</div>
				<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value=""/>
			</div>
			<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'z-companion' ); ?></button>
			<?php
		} else {
			?>
			<div class="customizer-repeater-social-repeater">
				<?php
				foreach ( $social_repeater as $social_icon ) {
					$show_del ++;
					echo '<div class="customizer-repeater-social-repeater-container">';
						echo '<div class="customizer-repeater-rc input-group icp-container">';
							echo '<input data-placement="bottomRight" class="icp icp-auto" value="';
					if ( ! empty( $social_icon['icon'] ) ) {
						echo esc_attr( $social_icon['icon'] );
					}
							echo '" type="text">';
							echo '<span class="input-group-addon"><i class="fa ' . esc_attr( $social_icon['icon'] ) . '"></i></span>';
						echo '</div>';?>
						<div class="iconpicker-popover popover bottomLeft">
	<div class="arrow"></div>
	<div class="popover-title">
		<input type="search" class="form-control iconpicker-search" placeholder="Type to filter">
	</div>
	<div class="popover-content">
		<div class="iconpicker">
			<div class="iconpicker-items">

				<i data-type="iconpicker-item" title=".fa-behance" class="fa fa-behance"></i>
				<i data-type="iconpicker-item" title=".fa-behance-square" class="fa fa-behance-square"></i>
				<i data-type="iconpicker-item" title=".fa-facebook" class="fa fa-facebook"></i>
				<i data-type="iconpicker-item" title=".fa-facebook-square" class="fa fa-facebook-square"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus" class="fa fa-google-plus"></i>
				<i data-type="iconpicker-item" title=".fa-google-plus-square" class="fa fa-google-plus-square"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin" class="fa fa-linkedin"></i>
				<i data-type="iconpicker-item" title=".fa-linkedin-square" class="fa fa-linkedin-square"></i>
				<i data-type="iconpicker-item" title=".fa-twitter" class="fa fa-twitter"></i>
				<i data-type="iconpicker-item" title=".fa-twitter-square" class="fa fa-twitter-square"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo" class="fa fa-vimeo"></i>
				<i data-type="iconpicker-item" title=".fa-vimeo-square" class="fa fa-vimeo-square"></i>
				<i data-type="iconpicker-item" title=".fa-youtube" class="fa fa-youtube"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-square" class="fa fa-youtube-square"></i>
				<i data-type="iconpicker-item" title=".fa-ambulance" class="fa fa-ambulance"></i>
				<i data-type="iconpicker-item" title=".fa-american-sign-language-interpreting" class="fa fa-american-sign-language-interpreting"></i>
				<i data-type="iconpicker-item" title=".fa-anchor" class="fa fa-anchor"></i>
				<i data-type="iconpicker-item" title=".fa-android" class="fa fa-android"></i>
				<i data-type="iconpicker-item" title=".fa-apple" class="fa fa-apple"></i>
				<i data-type="iconpicker-item" title=".fa-archive" class="fa fa-archive"></i>
				<i data-type="iconpicker-item" title=".fa-area-chart" class="fa fa-area-chart"></i>
				<i data-type="iconpicker-item" title=".fa-asterisk" class="fa fa-asterisk"></i>
				<i data-type="iconpicker-item" title=".fa-automobile" class="fa fa-automobile"></i>
				<i data-type="iconpicker-item" title=".fa-balance-scale" class="fa fa-balance-scale"></i>
				<i data-type="iconpicker-item" title=".fa-ban" class="fa fa-ban"></i>
				<i data-type="iconpicker-item" title=".fa-bank" class="fa fa-bank"></i>
				<i data-type="iconpicker-item" title=".fa-bicycle" class="fa fa-bicycle"></i>
				<i data-type="iconpicker-item" title=".fa-birthday-cake" class="fa fa-birthday-cake"></i>
				<i data-type="iconpicker-item" title=".fa-btc" class="fa fa-btc"></i>
				<i data-type="iconpicker-item" title=".fa-black-tie" class="fa fa-black-tie"></i>
				<i data-type="iconpicker-item" title=".fa-bookmark" class="fa fa-bookmark"></i>
				<i data-type="iconpicker-item" title=".fa-briefcase" class="fa fa-briefcase"></i>
				<i data-type="iconpicker-item" title=".fa-bus" class="fa fa-bus"></i>
				<i data-type="iconpicker-item" title=".fa-cab" class="fa fa-cab"></i>
				<i data-type="iconpicker-item" title=".fa-camera" class="fa fa-camera"></i>
				<i data-type="iconpicker-item" title=".fa-check" class="fa fa-check"></i>
				<i data-type="iconpicker-item" title=".fa-child" class="fa fa-child"></i>
				<i data-type="iconpicker-item" title=".fa-code" class="fa fa-code"></i>
				<i data-type="iconpicker-item" title=".fa-coffee" class="fa fa-coffee"></i>
				<i data-type="iconpicker-item" title=".fa-cog" class="fa fa-cog"></i>
				<i data-type="iconpicker-item" title=".fa-commenting" class="fa fa-commenting"></i>
				<i data-type="iconpicker-item" title=".fa-cube" class="fa fa-cube"></i>
				<i data-type="iconpicker-item" title=".fa-dollar" class="fa fa-dollar"></i>
				<i data-type="iconpicker-item" title=".fa-diamond" class="fa fa-diamond"></i>
				<i data-type="iconpicker-item" title=".fa-envelope" class="fa fa-envelope"></i>
				<i data-type="iconpicker-item" title=".fa-female" class="fa fa-female"></i>
				<i data-type="iconpicker-item" title=".fa-fire-extinguisher" class="fa fa-fire-extinguisher"></i>
				<i data-type="iconpicker-item" title=".fa-glass" class="fa fa-glass"></i>
				<i data-type="iconpicker-item" title=".fa-globe" class="fa fa-globe"></i>
				<i data-type="iconpicker-item" title=".fa-graduation-cap" class="fa fa-graduation-cap"></i>
				<i data-type="iconpicker-item" title=".fa-heartbeat" class="fa fa-heartbeat"></i>
				<i data-type="iconpicker-item" title=".fa-heart" class="fa fa-heart"></i>
				<i data-type="iconpicker-item" title=".fa-hotel" class="fa fa-hotel"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-home" class="fa fa-home"></i>
				<i data-type="iconpicker-item" title=".fa-hourglass" class="fa fa-hourglass"></i>
				<i data-type="iconpicker-item" title=".fa-legal" class="fa fa-legal"></i>
				<i data-type="iconpicker-item" title=".fa-lock" class="fa fa-lock"></i>
				<i data-type="iconpicker-item" title=".fa-map-signs" class="fa fa-map-signs"></i>
				<i data-type="iconpicker-item" title=".fa-paint-brush" class="fa fa-paint-brush"></i>
				<i data-type="iconpicker-item" title=".fa-plane" class="fa fa-plane"></i>
				<i data-type="iconpicker-item" title=".fa-rocket" class="fa fa-rocket"></i>
				<i data-type="iconpicker-item" title=".fa-puzzle-piece" class="fa fa-puzzle-piece"></i>
				<i data-type="iconpicker-item" title=".fa-shield" class="fa fa-shield"></i>
				<i data-type="iconpicker-item" title=".fa-tag" class="fa fa-tag"></i>
				<i data-type="iconpicker-item" title=".fa-times" class="fa fa-times"></i>
				<i data-type="iconpicker-item" title=".fa-unlock" class="fa fa-unlock"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user" class="fa fa-user"></i>
				<i data-type="iconpicker-item" title=".fa-user-md" class="fa fa-user-md"></i>
				<i data-type="iconpicker-item" title=".fa-video-camera" class="fa fa-video-camera"></i>
				<i data-type="iconpicker-item" title=".fa-wordpress" class="fa fa-wordpress"></i>
				<i data-type="iconpicker-item" title=".fa-wrench" class="fa fa-wrench"></i>
				<i data-type="iconpicker-item" title=".fa-youtube-play" class="fa fa-youtube-play"></i>

			</div> <!-- /.iconpicker-items -->
		</div> <!-- /.iconpicker -->
	</div> <!-- /.popover-content -->
</div> <!-- /.iconpicker-popover -->
						<?php echo '<input type="text" class="customizer-repeater-social-repeater-link" placeholder="' . esc_attr__( 'Link', 'z-companion' ) . '" value="';
					if ( ! empty( $social_icon['link'] ) ) {
						echo esc_url( $social_icon['link'] );
					}
						echo '">';
						echo '<input type="hidden" class="customizer-repeater-social-repeater-id" value="';
					if ( ! empty( $social_icon['id'] ) ) {
						echo esc_attr( $social_icon['id'] );
					}
						echo '">';
						echo '<button class="social-repeater-remove-social-item" style="';
					if ( $show_del == 1 ) {
						echo 'display:none';
					}
						echo '">' . esc_html__( 'Remove Icon', 'z-companion' ) . '</button>';
					echo '</div>';
				}
				?>
				<input type="hidden" id="social-repeater-socials-repeater-colector" class="social-repeater-socials-repeater-colector" value="<?php echo esc_textarea( html_entity_decode( $value ) ); ?>" />
			</div>
			<button class="social-repeater-add-social-item button-secondary"><?php esc_html_e( 'Add Icon', 'z-companion' ); ?></button>
			<?php
		}
	}

	
}
