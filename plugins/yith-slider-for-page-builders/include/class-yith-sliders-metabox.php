<?php
/**
 * Metabox handling class
 *
 * @package YITH Slider for page builders
 */

/**
 * Class YITH_Sliders_metabox
 *
 * @author Francesco Grasso <francgrasso@yithemes.com>
 */
class YITH_Sliders_Metabox {
	/**
	 * Post type
	 *
	 * @var array $screens
	 */
	private $screens = array(
		'yith_slider',
	);
	/**
	 * Fields
	 *
	 * @var array $screens
	 */
	private $fields = array(
		array(
			'id'    => 'slides-ids-to-include',
			'label' => 'slide IDs to include',
			'type'  => 'text',
		),
	);

	/**
	 * YITH_Sliders_metabox constructor.
	 */
	public function __construct() {
		add_action( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post', array( $this, 'save_post' ) );
		add_action( 'save_post', array( $this, 'save_slider_controls' ) );
	}

	/**
	 * Hooks into WordPress' add_meta_boxes function.
	 * Goes through screens (post types) and adds the meta box.
	 */
	public function add_meta_boxes() {
		foreach ( $this->screens as $screen ) {
			add_meta_box(
				'yith-slider-metabox',
				esc_html__( 'Manage slides', 'yith-slider-for-page-builders' ),
				array( $this, 'add_meta_box_callback' ),
				$screen,
				'normal',
				'high'
			);
			add_meta_box(
				'yith-slider-controls',
				esc_html__( 'Manage slider options', 'yith-slider-for-page-builders' ),
				array( $this, 'add_slider_controls_callback' ),
				$screen,
				'normal',
				'high'
			);
			add_meta_box(
				'slider_background_settings',
				esc_html__( 'Background Settings', 'yith-slider-for-page-builders' ),
				'yith_slider_for_page_builders_single_slide_background_settings_html',
				$screen,
				'side',
				'low'
			);
		}
	}

	/**
	 * Generates the HTML for the meta box
	 *
	 * @param object $post WordPress post object.
	 *
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function add_meta_box_callback( $post ) {
		wp_nonce_field( 'yith_slider_metabox_data', 'yith_slider_metabox_nonce' );
		echo '';
		$this->generate_fields( $post );
	}

	/**
	 * Generates the field's HTML for the meta box.
	 *
	 * @param object $post WordPress post object.
	 *
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function generate_fields( $post ) {
		global $current_screen;

		if ( 'yith_slider' === $current_screen->post_type && 'add' !== $current_screen->action ) {
			$output               = '';
			$add_slide_button_url = add_query_arg(
				array(
					'post_type'     => 'yith_slide',
					'parent_slider' => $post->ID,
				),
				admin_url( 'post-new.php' )
			);
			$add_slide_button     = '<a class="button button-primary button-large" href="' . $add_slide_button_url . '">' . esc_html__( 'Add new slide', 'yith-slider-for-page-builders' ) . '</a>';

			$args     = array(
				'post_parent' => $post->ID,
				'post_type'   => 'yith_slide',
				'numberposts' => -1,
				'post_status' => 'any',
				'orderby'     => 'meta_value_num',
				'order'       => 'ASC',
				'meta_key'    => 'slide_order',
			);
			$children = get_children( $args );

			foreach ( $children as $child ) {
				$id             = $child->ID;
				$edit_link      = get_edit_post_link( $id );
				$delete_link    = get_delete_post_link( $id, '' );
				$duplicate_link = admin_url( 'post.php?action=yith_slider_duplicate_slide&post=' . $id );
				$thumbnail_url  = get_the_post_thumbnail_url( $id, 'yith_slider_thumb' );
				if ( ! $thumbnail_url ) {
					$thumbnail_img = '<img width="300" src="' . YITH_SLIDER_FOR_PAGE_BUILDERS_URL . '/assets/slider-images/placeholder.png">';
				} else {
					$thumbnail_img = '<img src="' . $thumbnail_url . '">';
				}
				$bg_color = get_post_meta( $id, 'single_slide_background_color', true ) ? get_post_meta( $id, 'single_slide_background_color', true ) : '#ffffff';

				$edit_link      = '<a href="' . $edit_link . '" class="edit"><span>' . esc_attr__( 'Edit', 'yith-slider-for-page-builders' ) . '</span><img width="25" src="' . YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/slider-images/options/edit - white.svg"></a>';
				$duplicate_link = '<a href="' . $duplicate_link . '" class="duplicate"><span>' . esc_attr__( 'Duplicate', 'yith-slider-for-page-builders' ) . '</span><img width="25" src="' . YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/slider-images/options/clone - white.svg"></a>';
				$delete_link    = '<a href="' . $delete_link . '" class="delete"><span>' . esc_attr__( 'Delete', 'yith-slider-for-page-builders' ) . '</span><img width="25" src="' . YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/slider-images/options/trash - white.svg"></a>';
				$move_link      = '<a href="#" class="move"><span>' . esc_attr__( 'Drag to order', 'yith-slider-for-page-builders' ) . '</span><img width="25" src="' . YITH_SLIDER_FOR_PAGE_BUILDERS_URL . 'assets/slider-images/options/drag - white.svg"></a>';

				$slide_links = '<div class="slide-actions">' . $edit_link . $move_link . $duplicate_link . $delete_link . '<div>';

				$output .= '<li data-order="" style="background-color:' . $bg_color . '">';
				$output .= $thumbnail_img;
				$output .= $slide_links;
				$output .= '<input type="hidden" name="slide_order[]" value="' . $id . '"/>';
				$output .= '</li>';
			}
			echo '<ul class="yith-slider-slides-list">' . $output . '</ul>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

			echo $add_slide_button; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped

		} else {
			echo esc_html__( 'Add a title and save the slider to add slides', 'yith-slider-for-page-builders' );
		}

	}

	/**
	 * Generates another HTML for the meta box
	 *
	 * @param object $post WordPress post object.
	 *
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function add_slider_controls_callback( $post ) {
		wp_nonce_field( 'yith_slider_control_data', 'yith_slider_control_nonce' );
		echo '';
		$this->add_slider_controls( $post );
	}

	/**
	 * Add all slider controls
	 *
	 * @param object $post WordPress post object.
	 *
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function add_slider_controls( $post ) {
		$animation_type        = get_post_meta( $post->ID, 'yith_slider_control_animation_type', true );
		$navigation_style      = get_post_meta( $post->ID, 'yith_slider_control_navigation_style', true );
		$dots_navigation_style = get_post_meta( $post->ID, 'yith_slider_control_dots_navigation_style', true );
		$autoplay              = get_post_meta( $post->ID, 'yith_slider_control_autoplay', true );
		$autoplay_timing       = get_post_meta( $post->ID, 'yith_slider_control_autoplay_timing', true );
		if ( ! $autoplay_timing || '' === $autoplay_timing ) {
			$autoplay_timing = '3000';
		}
		$infinite_sliding        = get_post_meta( $post->ID, 'yith_slider_control_infinite_sliding', true );
		$slider_layout           = get_post_meta( $post->ID, 'yith_slider_control_slider_layout', true );
		$slide_content_max_width = get_post_meta( $post->ID, 'yith_slider_control_container_max_width', true );
		if ( ! $slide_content_max_width || '' === $slide_content_max_width ) {
			$slide_content_max_width = '1540';
		}
		$slider_height = get_post_meta( $post->ID, 'yith_slider_control_heigth', true );
		if ( ! $slider_height || '' === $slider_height ) {
			$slider_height = '550';
		}
		?>

		<table class="form-table">
			<tbody>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Transition type', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'slide', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_animation_type" value="slide" id="yith_slider_control_animation_type_slide" <?php checked( 'slide', $animation_type ); ?>>
						<label for="yith_slider_control_animation_type_slide">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/animation-type-slide.gif">
							<?php esc_html_e( 'slide', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_animation_type" value="fade" id="yith_slider_control_animation_type_fade" <?php checked( 'fade', $animation_type ); ?>>
						<label for="yith_slider_control_animation_type_fade">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/animation-type-fade.gif">
							<?php esc_html_e( 'fade', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th scope="row">
					<?php esc_html_e( 'Navigation arrows style', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'style 1', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_navigation_style" value="none" id="yith_slider_control_navigation_style_none" <?php checked( 'none', $navigation_style ); ?>>
						<label for="yith_slider_control_navigation_style_none">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-none.jpg">
							<?php esc_html_e( 'none', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_navigation_style" id="yith_slider_control_navigation_style_1" value="style-1" <?php checked( 'style-1', $navigation_style ); ?>>
						<label for="yith_slider_control_navigation_style_1">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-1.jpg">
							<?php esc_html_e( 'style 1', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_navigation_style" id="yith_slider_control_navigation_style_2" value="style-2" <?php checked( 'style-2', $navigation_style ); ?>>
						<label for="yith_slider_control_navigation_style_2">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-2.jpg">
							<?php esc_html_e( 'style 2', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_navigation_style" id="yith_slider_control_navigation_style_3" value="style-3" <?php checked( 'style-3', $navigation_style ); ?>>
						<label for="yith_slider_control_navigation_style_3">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-3.jpg">
							<?php esc_html_e( 'style 3', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_navigation_style" id="yith_slider_control_navigation_style_prev_next_slides" value="prev_next_slides" <?php checked( 'prev_next_slides', $navigation_style ); ?>>
						<label for="yith_slider_control_navigation_style_prev_next_slides">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-preve-next-slide-visible.jpg">
							<?php esc_html_e( 'Prev/Next slides visible', 'yit' ); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Bullet navigation style', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'style 1', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_dots_navigation_style" id="yith_slider_control_dots_navigation_style_none" value="none" <?php checked( 'none', $dots_navigation_style ); ?>>
						<label for="yith_slider_control_dots_navigation_style_none">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/arrow-style-none.jpg">
							<?php esc_html_e( 'none', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_dots_navigation_style" id="yith_slider_control_dots_navigation_style_1" value="style-1" <?php checked( 'style-1', $dots_navigation_style ); ?>>
						<label for="yith_slider_control_dots_navigation_style_1">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/bullets-style-1.jpg">
							<?php esc_html_e( 'style 1', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_dots_navigation_style" id="yith_slider_control_dots_navigation_style_2" value="style-2" <?php checked( 'style-2', $dots_navigation_style ); ?>>
						<label for="yith_slider_control_dots_navigation_style_2">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/bullets-style-2.jpg">
							<?php esc_html_e( 'style 2', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_dots_navigation_style" id="yith_slider_control_dots_navigation_style_3" value="style-3" <?php checked( 'style-3', $dots_navigation_style ); ?>>
						<label for="yith_slider_control_dots_navigation_style_3">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/bullets-style-3.jpg">
							<?php esc_html_e( 'style 3', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_dots_navigation_style" id="yith_slider_control_dots_navigation_style_4" value="style-4" <?php checked( 'style-4', $dots_navigation_style ); ?>>
						<label for="yith_slider_control_dots_navigation_style_4">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/bullets-style-4.jpg">
							<?php esc_html_e( 'style 4', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Autoplay', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'ON', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p>
						<label class="form-switch">
							<input type="checkbox" name="yith_slider_control_autoplay" value="autoplay" <?php checked( 'autoplay', $autoplay ); ?>><span></span>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Autoplay timing', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?> 4 s</small>
				</th>
				<td>
					<p>
						<label class="form-slider">
							<input type="range" min="1000" max="10000" value="<?php echo esc_attr( $autoplay_timing ); ?>" class="slider" step="500" id="yith_slider_control_autoplay_timing" name="yith_slider_control_autoplay_timing">
							<span id="timing_value"></span>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Infinite sliding', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'ON', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p>
						<label class="form-switch">
							<input type="checkbox" name="yith_slider_control_infinite_sliding" value="infinite-sliding" <?php checked( 'infinite-sliding', $infinite_sliding ); ?>>
							<span></span>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Slider content max width', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?> 1540 px</small>
				</th>
				<td>
					<p>
						<label class="form-slider">
							<input type="range" min="640" max="1900" value="<?php echo esc_attr( $slide_content_max_width ); ?>" class="slider" step="10" id="yith_slider_control_container_max_width" name="yith_slider_control_container_max_width">
							<span id="container_max_width"></span>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Slider content height', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?> 550 px</small>
				</th>
				<td>
					<p>
						<label class="form-slider">
							<input type="range" min="50" max="1900" value="<?php echo esc_attr( $slider_height ); ?>" class="slider" step="10" id="yith_slider_control_heigth" name="yith_slider_control_heigth">
							<span id="container_height"></span>
						</label>
					</p>
				</td>
			</tr>
			<tr>
				<th>
					<?php esc_html_e( 'Slider layout', 'yith-slider-for-page-builders' ); ?>
					<small><?php esc_html_e( 'Default value: ', 'yith-slider-for-page-builders' ); ?><?php esc_html_e( 'fluid', 'yith-slider-for-page-builders' ); ?></small>
				</th>
				<td>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_slider_layout" id="yith_slider_control_slider_layout_boxed" value="boxed container" <?php checked( 'boxed container', $slider_layout ); ?>>
						<label for="yith_slider_control_slider_layout_boxed">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/slider-layout-boxed.jpg">
							<?php esc_html_e( 'boxed', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_slider_layout" id="yith_slider_control_slider_layout_fluid" value="fluid" <?php checked( 'fluid', $slider_layout ); ?>>
						<label for="yith_slider_control_slider_layout_fluid">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/slider-layout-fluid.jpg">
							<?php esc_html_e( 'fluid', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_slider_layout" id="yith_slider_control_slider_layout_alignfull" value="alignfull" <?php checked( 'alignfull', $slider_layout ); ?>>
						<label for="yith_slider_control_slider_layout_alignfull">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/slider-layout-full-width.jpg">
							<?php esc_html_e( 'fullwidth', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
					<p class="image-control">
						<input type="radio" name="yith_slider_control_slider_layout" id="yith_slider_control_slider_layout_fullscreen" value="alignfull fullscreen" <?php checked( 'alignfull fullscreen', $slider_layout ); ?>>
						<label for="yith_slider_control_slider_layout_fullscreen">
							<img width="150" src="<?php echo esc_url( YITH_SLIDER_FOR_PAGE_BUILDERS_URL ); ?>assets/slider-images/options/slider-layout-fullscreen.jpg">
							<?php esc_html_e( 'fullscreen', 'yith-slider-for-page-builders' ); ?>
						</label>
					</p>
				</td>
			</tr>
			</tbody>
		</table>
		<?php
	}

	/**
	 * Generates the HTML for table rows.
	 *
	 * @param string $label column name.
	 * @param mixed  $input column content.
	 *
	 * @return string
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function row_format( $label, $input ) {
		return sprintf(
			'<tr><th scope="row">%s</th><td>%s</td></tr>',
			$label,
			$input
		);
	}

	/**
	 * Hooks into WordPress' save_post function
	 *
	 * @param int $post_id Slider post ID.
	 *
	 * @return void
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function save_post( $post_id ) {
		if ( ! isset( $_POST['yith_slider_metabox_nonce'] ) ) {
			return;
		}

		$nonce = sanitize_text_field( wp_unslash( $_POST['yith_slider_metabox_nonce'] ) );
		if ( ! wp_verify_nonce( $nonce, 'yith_slider_metabox_data' ) ) {
			return;
		}

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		foreach ( $this->fields as $field ) {
			if ( isset( $_POST[ $field['id'] ] ) ) {
				switch ( $field['type'] ) {
					case 'email':
						$_POST[ $field['id'] ] = sanitize_email( wp_unslash( $_POST[ $field['id'] ] ) );
						break;
					case 'text':
						$_POST[ $field['id'] ] = sanitize_text_field( wp_unslash( $_POST[ $field['id'] ] ) );
						break;
				}
				update_post_meta( $post_id, 'yith_slider_metabox_' . $field['id'], intval( wp_unslash( $_POST[ $field['id'] ] ) ) );
			} elseif ( 'checkbox' === $field['type'] ) {
				update_post_meta( $post_id, 'yith_slider_metabox_' . $field['id'], '0' );
			}
		}
	}

	/**
	 * Hooks into WordPress' save_post function
	 *
	 * @param int $post_id Slider post ID.
	 *
	 * @author Francesco Grasso <francgrasso@yithemes.com>
	 */
	public function save_slider_controls( $post_id ) {

		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['yith_slider_control_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_nonce'] ) ), 'yith_slider_control_data' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}

		if ( isset( $_POST['slide_order'] ) && is_array( $_POST['slide_order'] ) ) {
			foreach ( $_POST['slide_order'] as $index => $slide_id ) {
				update_post_meta( $slide_id, 'slide_order', $index );
			}
		}
		if ( isset( $_POST['yith_slider_control_animation_type'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_animation_type', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_animation_type'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_navigation_style'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_navigation_style', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_navigation_style'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_dots_navigation_style'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_dots_navigation_style', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_dots_navigation_style'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_autoplay'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_autoplay', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_autoplay'] ) ) ) );
		} else {
			update_post_meta( $post_id, 'yith_slider_control_autoplay', null );
		}
		if ( isset( $_POST['yith_slider_control_autoplay_timing'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_autoplay_timing', esc_attr( intval( wp_unslash( $_POST['yith_slider_control_autoplay_timing'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_container_max_width'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_container_max_width', esc_attr( intval( wp_unslash( $_POST['yith_slider_control_container_max_width'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_heigth'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_heigth', esc_attr( intval( wp_unslash( $_POST['yith_slider_control_heigth'] ) ) ) );
		}
		if ( isset( $_POST['yith_slider_control_infinite_sliding'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_infinite_sliding', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_infinite_sliding'] ) ) ) );
		} else {
			update_post_meta( $post_id, 'yith_slider_control_infinite_sliding', null );
		}
		if ( isset( $_POST['yith_slider_control_slider_layout'] ) ) {
			update_post_meta( $post_id, 'yith_slider_control_slider_layout', esc_attr( sanitize_text_field( wp_unslash( $_POST['yith_slider_control_slider_layout'] ) ) ) );
		} else {
			update_post_meta( $post_id, 'yith_slider_control_slider_layout', null );
		}
	}
}

new YITH_Sliders_Metabox();
