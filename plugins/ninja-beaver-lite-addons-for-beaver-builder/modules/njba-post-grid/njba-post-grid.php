<?php

/**
 * @class NJBA_PostGrid_Module
 */
class NJBA_PostGrid_Module extends FLBuilderModule {
	/**
	 * Constructor function for the module. You must pass the
	 * name, description, dir and url in an array to the parent class.
	 *
	 * @method __construct
	 */
	public function __construct() {
		parent::__construct( array(
			'name'            => __( 'Post Grid', 'bb-njba' ),
			'description'     => __( 'Addon to display post.', 'bb-njba' ),
			'group'           => njba_get_modules_group(),
			'category'        => njba_get_modules_cat( 'content' ),
			'dir'             => NJBA_MODULE_DIR . 'modules/njba-post-grid/',
			'url'             => NJBA_MODULE_URL . 'modules/njba-post-grid/',
			'editor_export'   => true, // Defaults to true and can be omitted.
			'enabled'         => true, // Defaults to true and can be omitted.
			'partial_refresh' => true
		) );
		/**
		 * Use these methods to enqueue css and js already
		 * registered or to register and enqueue your own.
		 */
		// Already registered

		$this->add_css( 'font-awesome' );
		add_action( 'wp_ajax_ct_get_post_tax', array( $this, 'njba_get_post_taxonomies' ) );
		add_action( 'wp_ajax_nopriv_ct_get_post_tax', array( $this, 'njba_get_post_taxonomies' ) );
	}

	/**
	 * Get taxonomies
	 */

	public function njba_get_post_taxonomies() {
		$slug = sanitize_text_field( $_POST['post_type_slug'] );
		// print_r($slug);
		$taxonomies = FLBuilderLoop::taxonomies( $slug );
		$html       = '';
		$html       .= '<option value="none">' . __( 'None', 'bb-njba' ) . '</option>';
		foreach ( $taxonomies as $tax_slug => $tax ) {
			$html .= '<option value="' . $tax_slug . '">' . $tax->label . '</option>';
		}
		echo $html;
	}

	// For Post Image
	public function njba_post_image_render() {
		if ( $this->settings->show_image == 1 ):
			if ( has_post_thumbnail() ) :

				echo '<div class="njba-post-grid-image" itemscope="" itemprop="image" itemtype="http://schema.org/ImageObject">';
				the_post_thumbnail( $this->settings->image_size );
				echo '</div>';
			else:
				echo '<div class="njba-post-grid-image" itemscope="" itemprop="image" itemtype="http://schema.org/ImageObject">
                         <img src="' . NJBA_MODULE_URL . 'modules/njba-post-grid/images/placeholder.jpg" class="njba-image-responsive" />';
				echo '</div>';
			endif;
		endif;
	}

	// For Meta
	public function post_meta( $id ) {

		$post_id = $id;
		echo '<ul>';
		if ( $this->settings->show_author == 1 ) :
			echo '<li><a href="' . get_author_posts_url( get_the_author_meta( $post_id ) ) . '" >' . get_the_author_meta( 'display_name',
					get_the_author_meta( $post_id ) ) . '</a> <span>|</span> </li>';
		endif;
		if ( $this->settings->show_date == 1 ) :

			echo '<li>';
			FLBuilderLoop::post_date( $this->settings->date_format );
			echo '<span>|</span> </li>';
		endif;
		if ( $this->settings->show_post_taxonomies == 1 && $this->settings->post_taxonomies !== 'none' ) {
			$terms      = wp_get_post_terms( get_the_ID(), $this->settings->post_taxonomies );
			$show_terms = array();
			$term_id    = array();
			foreach ( $terms as $term ) {
				$show_terms[] = $term->name;
				$term_id[]    = $term->term_id;
			}
			if ( ! empty( $term->term_id ) && ! empty( $show_terms ) ) {
				$count_id = count( $term_id );
				for ( $i = 0; $i <= $count_id; $i ++ ) {
					if ( $i < $count_id ) {
						echo '<li><a href="' . esc_url( get_category_link( $term_id[ $i ] ) ) . '">' . $show_terms[ $i ] . '</a><span>,</span></li>';
					}
				}
			}
		}
		echo '</ul>';
	}

	// for Excerpt Text
	public function njba_excerpt_text( $post_id ) {
		if ( $this->settings->show_post ) :
			if ( $this->settings->content_type === 'excerpt' ) {
				the_excerpt();
			} else if ( $this->settings->content_type === 'full' ) {

				$content_post = get_post( $post_id );
				$content      = $content_post->post_content;
				echo '<p>' . $content . '</p>';
			} else {
				$more         = '';
				$content_post = get_post( $post_id );
				$content      = $content_post->post_content;

				echo '<p>' . wp_trim_words( $content, $this->settings->post_length, $more ) . '...</p>';

			}
		endif;
	}

	// for Button Render
	public function njba_button_render() {
		if ( $this->settings->show_more_link == 1 ) {
			$btn_settings = array(
				//Button text
				'button_text' => $this->settings->more_link_text,
				//Button Link
				'link'        => get_the_permalink(),

			);
			FLBuilder::render_module_html( 'njba-button', $btn_settings );
		}
	}

}

/**
 * Register the module and its form settings.
 */
FLBuilder::register_module( 'NJBA_PostGrid_Module', array(
	'general' => array( // Tab
		'title'    => __( 'Layout', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'layout' => array( // Section
				'title'  => __( '', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'show_col'       => array(
						'type'    => 'select',
						'label'   => __( 'Show on Row', 'bb-njba' ),
						'default' => '3',
						'options' => array(
							'12' => '1',
							'6'  => '2',
							'4'  => '3',
							'3'  => '4',
							'5'  => '5',
							'2'  => '6',
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'posts_per_page' => array(
						'type'    => 'text',
						'label'   => __( 'Posts Per Page', 'bb-njba' ),
						'default' => '8',
						'size'    => '4'
					),
					'pagination'     => array(
						'type'    => 'select',
						'label'   => __( 'Pagination Style', 'bb-njba' ),
						'default' => 'numbers',
						'options' => array(
							'numbers' => __( 'Numbers', 'bb-njba' ),
							'none'    => _x( 'None', 'Pagination style.', 'bb-njba' ),
						),
						'toggle'  => array(
							'numbers' => array(
								'tabs' => array( 'pagination' )
							),
							'none'    => array(
								'tabs' => array()
							),
						)
					),
				)
			),
			'grid'   => array( // Section
				'title'  => __( 'Grid', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'post_spacing' => array(
						'type'        => 'text',
						'label'       => __( 'Post Spacing', 'bb-njba' ),
						'help'		  => 'All over padding.',
						'default'     => '5',
						'maxlength'   => '3',
						'size'        => '4',
						'description' => 'px'
					),
				)
			),
			'image'  => array(
				'title'  => __( 'Featured Image', 'bb-njba' ),
				'fields' => array(
					'show_image' => array(
						'type'    => 'select',
						'label'   => __( 'Display', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'image_size' )
							)
						)
					),
					'image_size' => array(
						'type'    => 'photo-sizes',
						'label'   => __( 'Size', 'bb-njba' ),
						'default' => 'medium'
					),
				)
			),
			'post'   => array(
				'title'  => __( 'Content', 'bb-njba' ),
				'fields' => array(
					'show_post'      => array(
						'type'    => 'select',
						'label'   => __( 'Display', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'content_type', 'post_length' )
							),
							'0' => array(
								'fields' => array()
							)
						)
					),
					'content_type'   => array(
						'type'    => 'select',
						'label'   => __( 'Type', 'bb-njba' ),
						'default' => 'excerpt',
						'options' => array(
							'excerpt'       => __( 'Excerpt', 'bb-njba' ),
							'full'          => __( 'Full Text', 'bb-njba' ),
							'custom_length' => __( 'Custom', 'bb-njba' )
						),
						'toggle'  => array(
							'custom_length' => array(
								'fields' => array( 'post_length' )
							)
						)
					),
					'post_length'    => array(
						'type'        => 'text',
						'label'       => __( 'Limit', 'bb-njba' ),
						'help'        => __( 'Number of words to be displayed from the post post.', 'bb-njba' ),
						'default'     => '50',
						'maxlength'   => 4,
						'size'        => 5,
						'description' => __( 'words', 'bb-njba' ),
					),
					'show_more_link' => array(
						'type'    => 'select',
						'label'   => __( 'Display Read More Link', 'bb-njba' ),
						'default' => '1',
						'options' => array(
							'1' => __( 'Yes', 'bb-njba' ),
							'0' => __( 'No', 'bb-njba' )
						),
						'toggle'  => array(
							'1' => array(
								'fields' => array( 'more_link_text' )
							),
							'0' => array(
								'fields' => array()
							),
						)
					),
					'more_link_text' => array(
						'type'    => 'text',
						'label'   => __( 'Read More Link Text', 'bb-njba' ),
						'default' => __( 'Read More', 'bb-njba' ),
						'preview' => array(
							'type' => 'none'
						)
					),
				)
			),

		)
	),
	'post'    => array(
		'title' => __( 'Content', 'bb-njba' ),
		'file'  => plugin_dir_path( __FILE__ ) . 'includes/loop-settings.php',
	),

	'pagination' => array(
		'title'    => __( 'Pagination', 'bb-njba' ),
		'sections' => array(
			'pagination_style'  => array(
				'title'  => __( 'General', 'bb-njba' ),
				'fields' => array(
					'pagination_spacing_v'   => array(
						'type'        => 'text',
						'label'       => __( 'Spacing Top / Bottom', 'bb-njba' ),
						'size'        => 5,
						'maxlength'   => 3,
						'default'     => '',
						'description' => 'px',
						'preview'     => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-pagination ul.page-numbers li',
									'property' => 'padding-top',
									'unit'     => 'px'
								),
								array(
									'selector' => '.njba-pagination ul.page-numbers li',
									'property' => 'padding-bottom',
									'unit'     => 'px'
								)
							)
						),
					),
					'pagination_spacing'     => array(
						'type'        => 'text',
						'label'       => __( 'Spacing Left / Right', 'bb-njba' ),
						'size'        => 5,
						'maxlength'   => 3,
						'default'     => '',
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-pagination li a.page-numbers, .njba-pagination li span.page-numbers',
							'property' => 'margin-right',
							'unit'     => 'px'
						),
					),
					'pagination_padding'     => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)

						)
					),
					'pagination_font_family' => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-pagination li a.page-numbers,.njba-pagination li span.page-numbers'
						)
					),
					'pagination_font_size'   => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'description' => 'Please Enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-pagination li a.page-numbers, .njba-pagination li span.page-numbers',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
				)
			),
			'pagination_colors' => array(
				'title'  => __( 'Colors', 'bb-njba' ),
				'fields' => array(
					'pagi_bg_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-pagination li a.page-numbers',
									'property' => 'background',
								)
							)
						)
					),
					'pagi_activebg_color' => array(
						'type'       => 'color',
						'label'      => __( 'Active Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-pagination li span.page-numbers,.njba-pagination li a.page-numbers:hover',
									'property' => 'background',
								)
							)
						)
					),
					'pagi_color'          => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-pagination li a.page-numbers',
									'property' => 'color',
								)
							)
						)
					),
					'pagi_active_color'   => array(
						'type'       => 'color',
						'label'      => __( 'Active Text Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'  => 'css',
							'rules' => array(
								array(
									'selector' => '.njba-pagination li span.page-numbers,.njba-pagination li a.page-numbers:hover',
									'property' => 'color',
								)
							)
						)
					),

				)
			),
			'pagination_border' => array(
				'title'  => __( 'Border', 'bb-njba' ),
				'fields' => array(
					'pagination_border'        => array(
						'type'    => 'select',
						'label'   => __( 'Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
						),
						'toggle'  => array(
							'dashed' => array(
								'fields' => array( 'pagination_border_width', 'pagination_border_color' )
							),
							'dotted' => array(
								'fields' => array( 'pagination_border_width', 'pagination_border_color' )
							),
							'solid'  => array(
								'fields' => array( 'pagination_border_width', 'pagination_border_color' )
							),
						),
					),
					'pagination_border_width'  => array(
						'type'        => 'text',
						'label'       => __( 'Width', 'bb-njba' ),
						'size'        => 5,
						'maxlength'   => 3,
						'default'     => 1,
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-pagination li a.page-numbers,.njba-pagination li span.page-numbers',
							'property' => 'border-width',
							'unit'     => 'px'
						),
					),
					'pagination_border_color'  => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-pagination li a.page-numbers,.njba-pagination li span.page-numbers',
							'property' => 'border-color',
						),
					),
					'pagination_border_radius' => array(
						'type'        => 'text',
						'label'       => __( 'Radius', 'bb-njba' ),
						'size'        => 5,
						'maxlength'   => 3,
						'default'     => 0,
						'description' => 'px',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-pagination li a.page-numbers,.njba-pagination li span.page-numbers',
							'property' => 'border-radius',
							'unit'     => 'px'
						),
					),
				)
			),
		)
	),
	'typography' => array( // Tab
		'title'    => __( 'Typography', 'bb-njba' ), // Tab title
		'sections' => array( // Tab Sections
			'general'              => array( // Section
				'title'  => __( 'Post Title', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'post_title_tag'         => array(
						'type'    => 'select',
						'label'   => __( 'Tag', 'bb-njba' ),
						'default' => 'h1',
						'options' => array(
							'h1'   => __( 'H1', 'bb-njba' ),
							'h2'   => __( 'H2', 'bb-njba' ),
							'h3'   => __( 'H3', 'bb-njba' ),
							'h4'   => __( 'H4', 'bb-njba' ),
							'h5'   => __( 'H5', 'bb-njba' ),
							'h6'   => __( 'H6', 'bb-njba' ),
							'div'  => __( 'Div', 'bb-njba' ),
							'p'    => __( 'p', 'bb-njba' ),
							'span' => __( 'span', 'bb-njba' ),
						),
						'preview' => array(
							'type' => 'none'
						)
					),
					'post_title_alignment'   => array(
						'type'    => 'select',
						'default' => 'left',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
					),
					'post_title_font'        => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
					),
					'post_title_font_size'   => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'maxlength'   => '3',
						'size'        => '5',
						'description' => __( 'Please Enter value in pixels.', 'bb-njba' ),
					),
					'post_title_height'      => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'maxlength'   => '3',
						'size'        => '5',
						'description' => __( 'Please Enter value in pixels.', 'bb-njba' ),
					),
					'post_title_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'post_title_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Hover Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
					),
					'post_title_padding'     => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-left'
							)
						),
					),
				)
			), // Section
			'dateformate'          => array( // Section
				'title'  => __( 'Meta', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'post_date_alignment' => array(
						'type'    => 'select',
						'default' => 'left',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-section-wrapper ul',
							'property' => 'text-align'
						)
					),
					'post_date_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-content-grid-section-wrapper ul li a,.njba-content-grid-section-wrapper ul li,.njba-content-grid-section-wrapper ul li span'
						)
					),
					'post_date_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'description' => __( 'Please Enter value in pixels.', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-section-wrapper ul li a,.njba-content-grid-section-wrapper ul li,.njba-content-grid-section-wrapper ul li span',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'post_date_height'    => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-section-wrapper ul li a,.njba-content-grid-section-wrapper ul li,.njba-content-grid-section-wrapper ul li span',
							'property' => 'line-height',
							'unit'     => 'px'
						),
						'maxlength'   => '3',
						'size'        => '5',
						'description' => 'Please Enter value in pixels.',
					),
					'post_date_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-section-wrapper ul li a,.njba-content-grid-section-wrapper ul li,.njba-content-grid-section-wrapper ul li span',
							'property' => 'color',
						)
					),
					'post_date_padding'   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-left'
							)
						),
					),
				)
			), // Section
			'post_content_section' => array( // Section
				'title'  => __( 'Post Content', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'post_content_alignment' => array(
						'type'    => 'select',
						'default' => 'left',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-vertical-center p',
							'property' => 'text-align'
						)
					),
					'post_content_font'      => array(
						'type'    => 'font',
						'default' => array(
							'family' => 'Default',
							'weight' => 300
						),
						'label'   => __( 'Font', 'bb-njba' ),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-content-grid-vertical-center p'
						)
					),
					'post_content_font_size' => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'maxlength'   => '3',
						'size'        => '5',
						'description' => 'Please Enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-vertical-center p',
							'property' => 'font-size',
							'unit'     => 'px'
						)
					),
					'post_content_height'    => array(
						'type'        => 'njba-simplify',
						'label'       => __( 'Line Height', 'bb-njba' ),
						'default'     => array(
							'desktop' => '',
							'medium'  => '',
							'small'   => ''
						),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-vertical-center p',
							'property' => 'line-height',
							'unit'     => 'px'
						),
						'description' => 'Please Enter value in pixels.',
						'maxlength'   => '3',
						'size'        => '5',
					),
					'post_content_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Color', 'bb-njba' ),
						'default'    => '',
						'show_reset' => true,
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-content-grid-vertical-center p',
							'property' => 'color',
						)
					),
					'post_content_padding'   => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => '',
							'right'  => '',
							'bottom' => '',
							'left'   => ''
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa fa-long-arrow-left'
							)
						),
					),
				)
			), // Section
			'button'               => array( // Section
				'title'  => __( 'Button', 'bb-njba' ), // Section Title
				'fields' => array( // Section Fields
					'alignment'                     => array(
						'type'    => 'select',
						'label'   => __( 'Alignment', 'bb-njba' ),
						'default' => 'left',
						'options' => array(
							'left'   => __( 'Left', 'bb-njba' ),
							'center' => __( 'Center', 'bb-njba' ),
							'right'  => __( 'Right', 'bb-njba' )
						),
						'preview' => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main',
							'property' => 'text-align'
						)
					),
					'button_font_family'            => array(
						'type'    => 'font',
						'label'   => __( 'Font', 'bb-njba' ),
						'default' => array(
							'family' => 'Default',
							'weight' => 'Default'
						),
						'preview' => array(
							'type'     => 'font',
							'selector' => '.njba-btn-main a.njba-btn'
						)
					),
					'button_font_size'              => array(
						'type'        => 'njba-simplify',
						'size'        => '5',
						'label'       => __( 'Font Size', 'bb-njba' ),
						'default'     => array(
							'desktop' => '14',
							'medium'  => '',
							'small'   => ''
						),
						'description' => 'Please Enter value in pixels.',
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'font-size',
							'unit'     => 'px'
						),
					),
					'button_background_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Background Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'F0F0F0',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'background-color',
						),
					),
					'button_background_hover_color' => array(
						'type'       => 'color',
						'label'      => __( 'Background Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'F0F0F0'
					),
					'button_text_color'             => array(
						'type'       => 'color',
						'label'      => __( 'Text Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '333333',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'color',
						),
					),
					'button_text_hover_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Text Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '333333'
					),
					'button_border_style'           => array(
						'type'    => 'select',
						'label'   => __( 'Border Style', 'bb-njba' ),
						'default' => 'none',
						'options' => array(
							'none'   => __( 'None', 'bb-njba' ),
							'solid'  => __( 'Solid', 'bb-njba' ),
							'dotted' => __( 'Dotted', 'bb-njba' ),
							'dashed' => __( 'Dashed', 'bb-njba' ),
							'double' => __( 'Double', 'bb-njba' ),
						),
						'toggle'  => array(
							'solid'  => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'dotted' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'dashed' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
							'double' => array(
								'fields' => array( 'button_border_width', 'button_border_radius', 'button_border_color', 'button_border_hover_color' )
							),
						)
					),
					'button_border_width'           => array(
						'type'        => 'text',
						'label'       => __( 'Border Width', 'bb-njba' ),
						'default'     => '1',
						'size'        => '5',
						'description' => _x( 'px', 'Value unit for spacer width. Such as: "10 px"', 'bb-njba' ),
						'preview'     => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-width',
							'unit'     => 'px'
						),
					),
					'button_border_radius'          => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Border Radius', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top-left'     => 0,
							'top-right'    => 0,
							'bottom-left'  => 0,
							'bottom-right' => 0
						),
						'options'     => array(
							'top-left'     => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'top-right'    => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom-left'  => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'bottom-right' => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)

						)
					),
					'button_border_color'           => array(
						'type'       => 'color',
						'label'      => __( 'Border Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000',
						'preview'    => array(
							'type'     => 'css',
							'selector' => '.njba-btn-main a.njba-btn',
							'property' => 'border-color',
						),
					),
					'button_border_hover_color'     => array(
						'type'       => 'color',
						'label'      => __( 'Border Hover Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => '000000'
					),
					'button_box_shadow'             => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Box Shadow', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'left_right' => 0,
							'top_bottom' => 0,
							'blur'       => 0,
							'spread'     => 0
						),
						'options'     => array(
							'left_right' => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-h'
							),
							'top_bottom' => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa fa-arrows-v'
							),
							'blur'       => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa fa-circle-thin'
							),
							'spread'     => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa fa-circle'
							)
						)
					),
					'button_box_shadow_color'       => array(
						'type'       => 'color',
						'label'      => __( 'Box Shadow Color', 'bb-njba' ),
						'show_reset' => true,
						'default'    => 'ffffff'
					),
					'button_padding'                => array(
						'type'        => 'njba-multinumber',
						'label'       => __( 'Padding', 'bb-njba' ),
						'description' => 'px',
						'default'     => array(
							'top'    => 10,
							'right'  => 15,
							'bottom' => 10,
							'left'   => 15
						),
						'options'     => array(
							'top'    => array(
								'placeholder' => __( 'Top', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-up'
							),
							'right'  => array(
								'placeholder' => __( 'Right', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-right'
							),
							'bottom' => array(
								'placeholder' => __( 'Bottom', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-down'
							),
							'left'   => array(
								'placeholder' => __( 'Left', 'bb-njba' ),
								'icon'        => 'fa-long-arrow-left'
							)
						),
					),
				)
			), // Section
		)
	),
) );
