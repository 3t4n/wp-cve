<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bricksable_Star_Rating extends \Bricks\Element {
	// Element properties.
	public $category     = 'bricksable';
	public $name         = 'ba-star-rating';
	public $icon         = 'ti-star';
	public $css_selector = '.ba-star-rating';
	public $scripts      = array();
	public $nestable     = false; // true || @since 1.5.

	// Methods: Builder-specific.
	public function get_label() {
		return esc_html__( 'Star Rating', 'bricksable' );
	}
	public function set_control_groups() {
		// Rating.
		$this->control_groups['rating'] = array(
			'title' => esc_html__( 'Rating', 'bricksable' ),
			'tab'   => 'content',
		);
		// Title.
		$this->control_groups['title'] = array(
			'title' => esc_html__( 'Title', 'bricksable' ),
			'tab'   => 'content',
		);
		// Alignment.
		$this->control_groups['alignment'] = array(
			'title' => esc_html__( 'Alignment', 'bricksable' ),
			'tab'   => 'content',
		);
		// Title Styling.
		$this->control_groups['title_styling'] = array(
			'title' => esc_html__( 'Title', 'bricksable' ),
			'tab'   => 'style',
		);
		// Stars Styling.
		$this->control_groups['stars'] = array(
			'title' => esc_html__( 'Stars', 'bricksable' ),
			'tab'   => 'style',
		);
		// Stars Styling.
		$this->control_groups['rating_number_styling'] = array(
			'title' => esc_html__( 'Rating Number', 'bricksable' ),
			'tab'   => 'style',
		);

		unset( $this->control_groups['_typography'] );
	}

	public function set_controls() {

		$this->controls['starRating'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Star Rating', 'bricksable' ),
			'type'    => 'text',
			'min'     => 0,
			'step'    => 1,
			'inline'  => false,
			'default' => '3.5',
		);

		$this->controls['totalStars'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Total Stars', 'bricksable' ),
			'type'    => 'select',
			'options' => array(
				'5'  => esc_html__( '0-5', 'bricksable' ),
				'10' => esc_html__( '0-10', 'bricksable' ),
			),
			'toggle'  => false,
			'default' => '5',
			'inline'  => false,
		);

		$this->controls['showRatingNumber'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Show Rating Number', 'bricksable' ),
			'type'    => 'checkbox',
			'inline'  => true,
			'small'   => true,
			'default' => false,
		);

		$this->controls['hideTotalStar'] = array(
			'tab'      => 'content',
			'group'    => 'rating',
			'label'    => esc_html__( 'Hide Total No. of Stars', 'bricksable' ),
			'type'     => 'checkbox',
			'inline'   => true,
			'small'    => true,
			'default'  => false,
			'required' => array( 'showRatingNumber', '=', true ),
		);

		/*
		$this->controls['separatorText'] = array(
			'tab'      => 'content',
			'group'    => 'rating',
			'label'    => esc_html__( 'Separator Text', 'bricksable' ),
			'type'     => 'text',
			'inline'   => false,
			'default'  => '/',
			'required' => array( 'hideTotalStar', '!=', true ),
		);*/

		// Icon Separator.
		$this->controls['icon_separator'] = array(
			'tab'   => 'content',
			'group' => 'rating',
			'small' => true,
			'type'  => 'separator',
		);

		$this->controls['markedIcon'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Marked icon', 'bricksable' ),
			'type'    => 'icon',
			'css'     => array(
				array(
					'selector' => '&.icon-svg', // NOTE: Undocumented: & = no space (add to element root).
				),
			),
			'default' => array(
				'library' => 'ionicons',
				'icon'    => 'ion-ios-star',
			),
		);

		$this->controls['halfmarkedIcon'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Half marked icon', 'bricksable' ),
			'type'    => 'icon',
			'css'     => array(
				array(
					'selector' => '& .icon-svg', // NOTE: Undocumented: & = no space (add to element root).
				),
			),
			'default' => array(
				'library' => 'ionicons',
				'icon'    => 'ion-ios-star-half',
			),
		);

		$this->controls['icon'] = array(
			'tab'     => 'content',
			'group'   => 'rating',
			'label'   => esc_html__( 'Empty Icon', 'bricksable' ),
			'type'    => 'icon',
			'css'     => array(
				array(
					'selector' => '&.icon-svg', // NOTE: Undocumented: & = no space (add to element root).
				),
			),
			'default' => array(
				'library' => 'ionicons',
				'icon'    => 'ion-ios-star-outline',
			),
		);

		// Title Tag.
		$this->controls['title_tag'] = array(
			'tab'         => 'content',
			'group'       => 'title',
			'label'       => esc_html__( 'Title Tag', 'bricksable' ),
			'type'        => 'select',
			'options'     => array(
				'h1'   => esc_html__( 'Heading 1 (h1)', 'bricksable' ),
				'h2'   => esc_html__( 'Heading 2 (h2)', 'bricksable' ),
				'h3'   => esc_html__( 'Heading 3 (h3)', 'bricksable' ),
				'h4'   => esc_html__( 'Heading 4 (h4)', 'bricksable' ),
				'h5'   => esc_html__( 'Heading 5 (h5)', 'bricksable' ),
				'h6'   => esc_html__( 'Heading 6 (h6)', 'bricksable' ),
				'div'  => esc_html__( 'Division (div)', 'bricksable' ),
				'p'    => esc_html__( 'Paragraph (p)', 'bricksable' ),
				'span' => esc_html__( 'Span (span)', 'bricksable' ),
			),
			'clearable'   => false,
			'pasteStyles' => false,
			'default'     => 'div',
			'description' => esc_html__( 'The Title tag.', 'bricksable' ),
		);

		// Title.
		$this->controls['title'] = array(
			'tab'   => 'content',
			'group' => 'title',
			'label' => esc_html__( 'Title', 'bricksable' ),
			'type'  => 'text',
		);

		// Title Position.
		$this->controls['title_position'] = array(
			'tab'            => 'content',
			'group'          => 'title',
			'label'          => esc_html__( 'Title Position', 'bricksable' ),
			'type'           => 'select',
			'options'        => array(
				'left'   => esc_html__( 'Left', 'bricksable' ),
				'right'  => esc_html__( 'Right', 'bricksable' ),
				'top'    => esc_html__( 'Top', 'bricksable' ),
				'bottom' => esc_html__( 'Bottom', 'bricksable' ),
			),
			'toggle'         => false,
			'placeholder'    => esc_html__( 'left', 'bricksable' ),
			'default'        => 'left',
			'style_transfer' => true,
			'inline'         => true,
			'required'       => array( 'title', '!=', '' ),
		);
		// Alignment.
		$this->controls['align_top_bottom'] = array(
			'tab'         => 'content',
			'group'       => 'alignment',
			'label'       => esc_html__( 'Alignment', 'bricksable' ),
			'type'        => 'align-items',
			'css'         => array(
				array(
					'property' => 'align-items',
					'selector' => '.ba-star-rating-container',
				),
			),
			'default'     => 'center',
			'pasteStyles' => true,
		);
		// Style Tab.
		$this->controls['title_color'] = array(
			'tab'   => 'style',
			'group' => 'title_styling',
			'label' => esc_html__( 'Text Color', 'bricksable' ),
			'type'  => 'color',
			'css'   => array(
				array(
					'property' => 'color',
					'selector' => '.ba-star-rating__title',
				),
			),
		);
		// Typography.
		$this->controls['title_typography'] = array(
			'tab'         => 'style',
			'group'       => 'title_styling',
			'label'       => esc_html__( 'Typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-star-rating__title',
				),
			),
			'exclude'     => array(
				'text-transform',
				'text-decoration',
				'color',
				'text-align',
			),
			'inline'      => true,
			'pasteStyles' => true,
		);
		// Gap.
		$this->controls['title_gap_right'] = array(
			'tab'      => 'style',
			'group'    => 'title_styling',
			'label'    => esc_html__( 'Gap', 'bricksable' ),
			'type'     => 'number',
			'css'      => array(
				array(
					'property' => 'margin-right',
					'selector' => '.ba-star-rating__title',
				),
			),
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
			),
			'default'  => '2',
			'required' => array( 'title_position', '=', 'left' ),
		);
		// Gap.
		$this->controls['title_gap_left'] = array(
			'tab'      => 'style',
			'group'    => 'title_styling',
			'label'    => esc_html__( 'Gap', 'bricksable' ),
			'type'     => 'number',
			'css'      => array(
				array(
					'property' => 'margin-left',
					'selector' => '.ba-star-rating__title',
				),
			),
			'units'    => array(
				'px' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
			),
			'default'  => '2',
			'required' => array( 'title_position', '=', 'right' ),
		);
		$this->controls['iconColor']      = array(
			'tab'      => 'style',
			'group'    => 'stars',
			'label'    => esc_html__( 'Color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.star-rating_star-marked',

				),
				array(
					'property' => 'color',
					'selector' => '.star-rating_star-half-marked',
				),
			),
			'default'  => array(
				'hex' => '#ffc107',
			),
			'required' => array( 'icon.icon', '!=', '' ),
		);

		$this->controls['unMarkedIconColor'] = array(
			'tab'      => 'style',
			'group'    => 'stars',
			'label'    => esc_html__( 'Unmarked Color', 'bricksable' ),
			'type'     => 'color',
			'css'      => array(
				array(
					'property' => 'color',
					'selector' => '.star-rating_star-unmarked',
				),
			),
			'default'  => array(
				'hex' => '#ffc107',
			),
			'required' => array( 'icon.icon', '!=', '' ),
		);

		$this->controls['iconSize'] = array(
			'tab'         => 'style',
			'group'       => 'stars',
			'label'       => esc_html__( 'Icon size', 'bricksable' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'font-size',
					'selector' => 'i',
				),
			),
			'placeholder' => '20px',
			'required'    => array( 'icon.icon', '!=', '' ),
		);

		$this->controls['iconGap'] = array(
			'tab'         => 'style',
			'group'       => 'stars',
			'label'       => esc_html__( 'Gap between stars', 'bricksable' ),
			'type'        => 'number',
			'units'       => true,
			'css'         => array(
				array(
					'property' => 'margin-right',
					'selector' => '.star-rating_star-marked',
				),
				array(
					'property' => 'margin-left',
					'selector' => '.star-rating_star-marked',
				),
				array(
					'property' => 'margin-right',
					'selector' => '.star-rating_star-unmarked',
				),
				array(
					'property' => 'margin-left',
					'selector' => '.star-rating_star-unmarked',
				),
				array(
					'property' => 'margin-right',
					'selector' => '.star-rating_star-half-marked',
				),
				array(
					'property' => 'margin-left',
					'selector' => '.star-rating_star-half-marked',
				),
			),
			'units'       => array(
				'px' => array(
					'min'  => 1,
					'max'  => 50,
					'step' => 1,
				),
			),
			'placeholder' => '0',
			'required'    => array( 'icon.icon', '!=', '' ),
		);

		$this->controls['iconMargin']             = array(
			'tab'   => 'style',
			'group' => 'stars',
			'label' => esc_html__( 'Star margin', 'extras' ),
			'type'  => 'dimensions',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => 'i',
				),
			),
		);
		$this->controls['ratingNumberTypography'] = array(
			'tab'         => 'style',
			'group'       => 'rating_number_styling',
			'label'       => esc_html__( 'Typography', 'bricksable' ),
			'type'        => 'typography',
			'css'         => array(
				array(
					'property' => 'typography',
					'selector' => '.ba-star-rating__number',
				),
			),
			'exclude'     => array(
				'text-align',
			),
			'inline'      => true,
			'pasteStyles' => true,
		);
		$this->controls['ratingNumberBackground'] = array(
			'tab'     => 'style',
			'group'   => 'rating_number_styling',
			'label'   => esc_html__( 'Background', 'bricksable' ),
			'type'    => 'background',
			'css'     => array(
				array(
					'property' => 'background',
					'selector' => '.ba-star-rating__number',
				),
			),
			'exclude' => array(
				'videoUrl',
				'videoScale',
			),
			'inline'  => true,
			'small'   => true,
		);
		$this->controls['ratingNumberBoxShadow']  = array(
			'tab'    => 'style',
			'group'  => 'rating_number_styling',
			'label'  => esc_html__( 'BoxShadow', 'bricksable' ),
			'type'   => 'box-shadow',
			'css'    => array(
				array(
					'property' => 'box-shadow',
					'selector' => '.ba-star-rating__number',
				),
			),
			'inline' => true,
			'small'  => true,
		);
		$this->controls['ratingNumberBorder']     = array(
			'tab'    => 'style',
			'group'  => 'rating_number_styling',
			'label'  => esc_html__( 'Border', 'bricksable' ),
			'type'   => 'border',
			'css'    => array(
				array(
					'property' => 'border',
					'selector' => '.ba-star-rating__number',
				),
			),
			'inline' => true,
			'small'  => true,
		);
		$this->controls['ratingNumberMargin']     = array(
			'tab'   => 'style',
			'group' => 'rating_number_styling',
			'label' => esc_html__( 'Margin', 'bricksable' ),
			'type'  => 'spacing',
			'css'   => array(
				array(
					'property' => 'margin',
					'selector' => '.ba-star-rating__number',
				),
			),
		);
		$this->controls['ratingNumberPadding']    = array(
			'tab'   => 'style',
			'group' => 'rating_number_styling',
			'label' => esc_html__( 'Padding', 'bricksable' ),
			'type'  => 'spacing',
			'css'   => array(
				array(
					'property' => 'padding',
					'selector' => '.ba-star-rating__number',
				),
			),
		);
	}
	// Methods: Frontend-specific.
	public function enqueue_scripts() {
		wp_enqueue_style( 'ba-star-rating' );
	}
	public function render() {

		$this->set_attribute( 'star-rating_star-marked', 'class', 'star-rating_star-marked' );
		$this->set_attribute( 'star-rating_star-half-marked', 'class', 'star-rating_star-half-marked' );
		$this->set_attribute( 'star-rating_star-unmarked', 'class', 'star-rating_star-unmarked' );

		$settings = $this->settings;

		$title_position = $settings['title_position'];
		if ( ! empty( $title_position ) ) {
			$this->set_attribute( '_root', 'class', 'ba-title-' . $title_position );
		}

		$icon            = ! empty( $settings['icon'] ) ? "<div {$this->render_attributes( 'star-rating_star-unmarked' )}>" . self::render_icon( $settings['icon'] ) . '</div>' : false;
		$marked_icon     = ! empty( $settings['markedIcon'] ) ? "<div {$this->render_attributes( 'star-rating_star-marked' )}>" . self::render_icon( $settings['markedIcon'] ) . '</div>' : false;
		$halfmarked_icon = ! empty( $settings['halfmarkedIcon'] ) ? "<div {$this->render_attributes( 'star-rating_star-half-marked' )}>" . self::render_icon( $settings['halfmarkedIcon'] ) . '</div>' : false;

		$star_rating_setting = isset( $settings['starRating'] ) ? $settings['starRating'] : 3.5;
		$total_stars_setting = isset( $settings['totalStars'] ) ? $settings['totalStars'] : 5;

		$star_rating = strstr( $star_rating_setting, '{' ) ? $this->render_dynamic_data( $star_rating_setting, 'text' ) : $star_rating_setting;
		$total_stars = strstr( $total_stars_setting, '{' ) ? $this->render_dynamic_data( $total_stars_setting, 'text' ) : $total_stars_setting;
		$aria_label  = 'Rating: ' . esc_attr( $star_rating ) . ' out of ' . esc_attr( $total_stars ) . ' stars';

		// $separator_text            = empty( $settings['separatorText'] ) ? esc_html( '/' ) : esc_html( $settings['separatorText'] );
		$separator_text_hide_stars = isset( $settings['hideTotalStar'] ) && true === $settings['hideTotalStar'] ? '' : esc_html( '/' );
		$hide_total_star_numbers   = isset( $settings['hideTotalStar'] ) && true === $settings['hideTotalStar'] ? '' : esc_html( $total_stars );
		$this->set_attribute( '_root', 'aria-label', $aria_label );
		$this->set_attribute( '_root', 'role', 'img' );
		$this->set_attribute( '_root', 'data-x-star-rating', $star_rating );
		$title_tag = isset( $settings['title_tag'] ) ? esc_html( $settings['title_tag'] ) : 'div';

		$this->set_attribute( 'rating_number', 'span' );
		$this->set_attribute( 'rating_number', 'class', 'ba-star-rating__number' );
		$star_rating_output = sprintf(
			'%1$s
			<div class="ba-star-wrapper">
			%3$s
			</div>
			%4$s
			%2$s',
			isset( $settings['showRatingNumber'] ) && true === $settings['showRatingNumber'] ? '<div class="ba-star-number-wrapper">' : '',
			isset( $settings['showRatingNumber'] ) && true === $settings['showRatingNumber'] ? '</div>' : '',
			// Show rating stars.
			is_numeric( $star_rating ) && is_numeric( $total_stars ) ? sprintf(
				'%1$s%2$s%3$s
			',
				$total_stars >= $star_rating ? str_repeat( $marked_icon, $star_rating ) : str_repeat( $marked_icon, $total_stars ),
				( $star_rating * 2 ) % 2 !== 0 ? $halfmarked_icon : '',
				$total_stars - $star_rating > 0 ? str_repeat( $icon, $total_stars - $star_rating ) : ''
			) : '',
			isset( $settings['showRatingNumber'] ) && true === $settings['showRatingNumber'] ? '<' . $this->render_attributes( 'rating_number' ) . '>' . esc_html( $star_rating ) . esc_html( $separator_text_hide_stars ) . esc_html( $hide_total_star_numbers ) . '</span>' : ''
		);

		$output  = "<div {$this->render_attributes( '_root' )}>";
		$output .= "<div class='ba-star-rating-container ba-star-rating'>";

		if ( 'left' === $title_position || 'top' === $title_position || 'bottom' === $title_position ) {
			if ( ! empty( $settings['title'] ) ) {
				$this->set_attribute( 'title', $title_tag );
				$this->set_attribute( 'title', 'class', 'ba-star-rating__title' );
				$output .= '<' . $this->render_attributes( 'title' ) . '>';
				$output .= esc_html( $settings['title'] );
				$output .= '</' . esc_html( $title_tag ) . '>';
			}

			// Show rating label.
			$output .= $star_rating_output;
		} elseif ( 'right' === $title_position ) {
				// Show rating number.
				$output .= $star_rating_output;

			if ( ! empty( $settings['title'] ) ) {
				$this->set_attribute( 'title', $title_tag );
				$this->set_attribute( 'title', 'class', 'ba-star-rating__title' );
				$output .= '<' . $this->render_attributes( 'title' ) . '> ';
				$output .= esc_html( $settings['title'] );
				$output .= '</' . esc_html( $title_tag ) . '> ';
			}
		}

		$output .= '</div>';
		$output .= '</div>';

		//phpcs:ignore
		echo $output;
	}
}
