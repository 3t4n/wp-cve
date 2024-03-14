<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Pojo_Widget_News_Ticker extends Pojo_Widget_Base {

	public function __construct() {
		$this->_form_fields = array();

		$this->_form_fields[] = array(
			'id' => 'title',
			'title' => __( 'Title:', 'pojo-news-ticker' ),
			'std' => '',
		);

		$this->_form_fields[] = array(
			'id' => 'category',
			'title' => __( 'Categories:', 'pojo-news-ticker' ),
			'type' => 'multi_taxonomy',
			'taxonomy' => 'category',
			'std' => array(),
		);

		$this->_form_fields[] = array(
			'id' => 'posts_per_page',
			'title' => __( 'Number Posts:', 'pojo-news-ticker' ),
			'std' => get_option( 'posts_per_page' ),
			'placeholder' => '10',
			'filter' => array( &$this, '_valid_number' ),
		);

		$this->_form_fields[] = array(
			'id' => 'orderby',
			'title' => __( 'Order By:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Date', 'pojo-news-ticker' ),
				'menu_order' => __( 'Menu Order', 'pojo-news-ticker' ),
				'title' => __( 'Title', 'pojo-news-ticker' ),
				'author' => __( 'Author', 'pojo-news-ticker' ),
				'name' => __( 'Post Slug', 'pojo-news-ticker' ),
				'modified' => __( 'Modified', 'pojo-news-ticker' ),
				'comment_count' => __( 'Comment Count', 'pojo-news-ticker' ),
				'ID' => __( 'Post ID', 'pojo-news-ticker' ),
				'rand' => __( 'Random', 'pojo-news-ticker' ),
				'none' => __( 'None', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'order',
			'title' => __( 'Order:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Descending', 'pojo-news-ticker' ),
				'ASC' => __( 'Ascending', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'ticker_transition_style',
			'title' => __( 'Transition Style:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'fade',
			'options' => array(
				'fade' => __( 'Fade', 'pojo-news-ticker' ),
				'slide' => __( 'Slide', 'pojo-news-ticker' ),
				'typing' => __( 'Typing', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'ticker_delay',
			'title' => __( 'Delay', 'pojo-news-ticker' ),
			'std' => '5000',
			'placeholder' => '5000',
			'desc' => __( 'Ticker will rotate every X milliseconds', 'pojo-news-ticker' ),
			'filter' => array( &$this, '_valid_number' ),
		);

		$this->_form_fields[] = array(
			'id' => 'ticker_typing_speed',
			'title' => __( 'Typing Speed', 'pojo-news-ticker' ),
			'std' => '75',
			'placeholder' => '75',
			'desc' => __( 'For Typing effect only (in ms)', 'pojo-news-ticker' ),
			'filter' => array( &$this, '_valid_number' ),
		);
		
		$this->_form_fields[] = array(
			'id' => 'ticker_pause_hover',
			'title' => __( 'Stop On Hover:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'on',
			'options' => array(
				'on' => __( 'On', 'pojo-news-ticker' ),
				'off' => __( 'Off', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);
		
		$this->_form_fields[] = array(
			'id' => 'metadata_date',
			'title' => __( 'Date:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'hide',
			'options' => array(
				'hide' => __( 'Hide', 'pojo-news-ticker' ),
				'show' => __( 'Show', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);
		
		$this->_form_fields[] = array(
			'id' => 'metadata_time',
			'title' => __( 'Time:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'hide',
			'options' => array(
				'hide' => __( 'Hide', 'pojo-news-ticker' ),
				'show' => __( 'Show', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'link_to_post',
			'title' => __( 'Link to Post:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => 'yes',
			'options' => array(
				'yes' => __( 'Yes', 'pojo-news-ticker' ),
				'no' => __( 'No', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'custom_wrapper',
			'title' => __( 'Style', 'pojo-news-ticker' ),
			'type' => 'button_collapse',
			'mode' => 'start',
		);

		$this->_form_fields[] = array(
			'id' => 'content_color',
			'title' => __( 'Color:', 'pojo-news-ticker' ),
			'type' => 'color',
			'std' => '',
			'filter' => 'sanitize_text_field',
		);

		$this->_form_fields[] = array(
			'id' => 'content_font_size',
			'title' => __( 'Font Size:', 'pojo-news-ticker' ),
			'placeholder' => '20px',
			'std' => '',
		);

		$this->_form_fields[] = array(
			'id' => 'content_font_weight',
			'title' => __( 'Font Weight:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => $this->_get_font_weights(),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[ ] = array(
			'id' => 'content_font_transform',
			'title' => __( 'Text Transform:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Default', 'pojo-news-ticker' ),
				'none' => __( 'None', 'pojo-news-ticker' ),
				'uppercase' => __( 'Uppercase', 'pojo-news-ticker' ),
				'lowercase' => __( 'Lowercase', 'pojo-news-ticker' ),
				'capitalize' => __( 'Capitalize', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[ ] = array(
			'id' => 'content_font_style',
			'title' => __( 'Font Style:', 'pojo-news-ticker' ),
			'type' => 'select',
			'std' => '',
			'options' => array(
				'' => __( 'Default', 'pojo-news-ticker' ),
				'normal' => __( 'Normal', 'pojo-news-ticker' ),
				'italic' => __( 'Italic', 'pojo-news-ticker' ),
				'oblique' => __( 'Oblique', 'pojo-news-ticker' ),
			),
			'filter' => array( &$this, '_valid_by_options' ),
		);

		$this->_form_fields[] = array(
			'id' => 'content_line_height',
			'title' => __( 'Line Height:', 'pojo-news-ticker' ),
			'placeholder' => '30px',
			'std' => '',
		);

		$this->_form_fields[] = array(
			'id' => 'content_letter_spacing',
			'title' => __( 'Letter Spacing:', 'pojo-news-ticker' ),
			'placeholder' => '1px',
			'std' => '',
			'filter' => 'sanitize_text_field',
		);	

		$this->_form_fields[] = array(
			'id' => 'custom_wrapper',
			'title' => __( 'Custom', 'pojo-news-ticker' ),
			'type' => 'button_collapse',
			'mode' => 'end',
		);

		parent::__construct(
			'pojo_news_ticker',
			__( 'News Ticker', 'pojo-news-ticker' ),
			array( 'description' => __( 'Display ticker posts by categories', 'pojo-news-ticker' ), )
		);
	}

	public function widget( $args, $instance ) {
		$instance = wp_parse_args( $instance, $this->_get_default_values() );
		$args = $this->_parse_widget_args( $args, $instance );

		$query_args = array(
			'post_type' => 'post',
			'posts_per_page' => $instance['posts_per_page'],
			'order' => $instance['order'],
			'orderby' => $instance['orderby'],
		);

		if ( ! empty( $instance['category'] ) && is_array( $instance['category'] ) ) {
			$query_args['tax_query'] = array(
				array(
					'taxonomy' => 'category',
					'field' => 'id',
					'terms' => $instance['category'],
					'include_children' => false,
				),
			);
		}
		$recent_posts = new WP_Query( $query_args );

		echo $args['before_widget'];

		if ( ! empty( $instance['title'] ) )
			echo $args['before_title'] . $instance['title'] . $args['after_title'];

		if ( ! empty( $instance['category'] ) ) :
			if ( $recent_posts->have_posts() ) :
				$ticker_options = array(
					'delay' => absint( $instance['ticker_delay'] ),
					'typingDelay' => absint( $instance['ticker_typing_speed'] ),
					'effect' => $instance['ticker_transition_style'],
					'pauseHover' => 'on' === $instance['ticker_pause_hover'],
				);
				
				$style = $this->_get_inline_styles( 'content', $instance );
				?>
				<div class="pojo-news-ticker">
					<ul class="ticker-items" data-ticker_options='<?php echo json_encode( $ticker_options ); ?>'>
				<?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
					<li class="ticker-item">
						<?php if ( 'yes' === $instance['link_to_post'] ) : ?>
						<a class="ticker-link" href="<?php the_permalink(); ?>"<?php if ( ! empty( $style ) ) echo ' style="' . $style . '"'; ?>>
						<?php else : ?>
						<span<?php if ( ! empty( $style ) ) echo ' style="' . $style . '"'; ?>>
						<?php endif; ?>
							<?php if ( 'show' === $instance['metadata_date'] ) : ?>
								<span class="ticker-date"><?php echo get_the_date(); ?></span>
							<?php endif; ?>
	
							<?php if ( 'show' === $instance['metadata_time'] ) : ?>
								<span class="ticker-time"><?php echo get_the_time(); ?></span>
							<?php endif; ?>
							<span class="ticker-content"><?php the_title(); ?></span>
						<?php if ( 'yes' === $instance['link_to_post'] ) : ?>
						</a>
						<?php else : ?>
						</span>
						<?php endif; ?>
					</li>
				<?php endwhile; ?>
					</ul>
				</div>
				<?php
				wp_reset_postdata();
			else :
				printf( '<p>%s</p>', __( 'No posts found.', 'pojo-news-ticker' ) );
			endif;
		else :
			printf( '<p>%s</p>', __( 'Content not found. Please select a category.', 'pojo-news-ticker' ) );
		endif;

		echo $args['after_widget'];
	}

	protected function _get_inline_styles( $prefix, $instance ) {
		$properties = array(
			// Option => CSS Property
			'color' => 'color',
			'font_size' => 'font-size',
			'font_weight' => 'font-weight',
			'line_height' => 'line-height',
			'font_style' => 'font-style',
			'font_transform' => 'text-transform',
			'letter_spacing' => 'letter-spacing',
		);

		$inline_style = array();
		foreach ( $properties as $property => $css_property ) {
			if ( ! empty( $instance[ $prefix . '_' . $property ] ) ) {
				$inline_style[] = $css_property . ': ' . $instance[ $prefix . '_' . $property ];
			}
		}

		return implode( '; ', $inline_style );
	}
	
}