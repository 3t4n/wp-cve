<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.solwininfotech.com
 * @since      1.8.2
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/public
 */

/**
 * The public-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-specific stylesheet and JavaScript.
 *
 * @package    Blog_Designer
 * @subpackage Blog_Designer/public
 * @author     Solwin Infotech <support@solwininfotech.com>
 */
class Blog_Designer_Lite_Template {
	/**
	 * Hex to rgb
	 *
	 * @param type $color color.
	 * @param tyoe $opacity opacity.
	 */
	public static function bd_hex2rgba( $color, $opacity = false ) {
		$default = 'rgb(0,0,0)';
		// Return default if no color provided.
		if ( empty( $color ) ) {
			return $default;
		}
		// Sanitize $color if "#" is provided.
		if ( '#' === $color[0] ) {
			$color = substr( $color, 1 );
		}
		// Check if color has 6 or 3 characters and get values.
		if ( 6 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5] );
		} elseif ( 3 == strlen( $color ) ) {
			$hex = array( $color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2] );
		} else {
			return $default;
		}
		// Convert hexadec to rgb.
		$rgb = array_map( 'hexdec', $hex );
		// Check if opacity is set(rgba or rgb).
		if ( $opacity ) {
			if ( abs( $opacity ) > 1 ) {
				$opacity = 1.0;
			}
			$output = 'rgba(' . implode( ',', $rgb ) . ',' . $opacity . ')';
		} else {
			$output = 'rgb(' . implode( ',', $rgb ) . ')';
		}
		// Return rgb(a) color string.
		return $output;
	}
	/**
	 * Get template list
	 */
	public static function bd_template_list() {
		$tempate_list = array(
			'boxy'               => array(
				'template_name' => esc_html__( 'Boxy Template', 'blog-designer' ),
				'class'         => 'masonry',
				'image_name'    => 'boxy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-boxy-blog-template/' ),
			),
			'boxy-clean'         => array(
				'template_name' => esc_html__( 'Boxy Clean Template', 'blog-designer' ),
				'class'         => 'grid free',
				'image_name'    => 'boxy-clean.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-boxy-clean-blog-template/' ),
			),
			'brit_co'            => array(
				'template_name' => esc_html__( 'Brit Co Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'brit_co.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-brit-co-blog-template/' ),
			),
			'classical'          => array(
				'template_name' => esc_html__( 'Classical Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'classical.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-classical-blog-template/' ),
			),
			'cool_horizontal'    => array(
				'template_name' => esc_html__( 'Cool Horizontal Template', 'blog-designer' ),
				'class'         => 'timeline slider',
				'image_name'    => 'cool_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-cool-horizontal-timeline-blog-template/' ),
			),
			'crayon_slider'      => array(
				'template_name' => esc_html__( 'Crayon Slider Template', 'blog-designer' ),
				'class'         => 'slider free',
				'image_name'    => 'crayon_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-crayon-slider-blog-template/' ),
			),
			'cover'              => array(
				'template_name' => esc_html__( 'Cover Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'cover.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-cover-blog-template/' ),
			),
			'clicky'             => array(
				'template_name' => esc_html__( 'Clicky Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'clicky.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-clicky-blog-template/' ),
			),
			'deport'             => array(
				'template_name' => esc_html__( 'Deport Template', 'blog-designer' ),
				'class'         => 'magazine',
				'image_name'    => 'deport.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-deport-blog-template/' ),
			),
			'easy_timeline'      => array(
				'template_name' => esc_html__( 'Easy Timeline', 'blog-designer' ),
				'class'         => 'timeline',
				'image_name'    => 'easy_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-easy-timeline-blog-template/' ),
			),
			'elina'              => array(
				'template_name' => esc_html__( 'Elina Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'elina.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-elina-blog-template/' ),
			),
			'evolution'          => array(
				'template_name' => esc_html__( 'Evolution Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'evolution.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-evolution-blog-template/' ),
			),
			'fairy'              => array(
				'template_name' => esc_html__( 'Fairy Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'fairy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-fairy-blog-template/' ),
			),
			'famous'             => array(
				'template_name' => esc_html__( 'Famous Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'famous.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-famous-blog-template/' ),
			),
			'foodbox'            => array(
				'template_name' => esc_html__( 'Food Box Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'foodbox.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-foodbox-blog-template/' ),
			),
			'glamour'            => array(
				'template_name' => esc_html__( 'Glamour Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'glamour.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-glamour-blog-template/' ),
			),
			'glossary'           => array(
				'template_name' => esc_html__( 'Glossary Template', 'blog-designer' ),
				'class'         => 'masonry free',
				'image_name'    => 'glossary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-glossary-blog-template/' ),
			),
			'explore'            => array(
				'template_name' => esc_html__( 'Explore Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'explore.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-explore-blog-template/' ),
			),
			'hoverbic'           => array(
				'template_name' => esc_html__( 'Hoverbic Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'hoverbic.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-hoverbic-blog-template/' ),
			),
			'hub'                => array(
				'template_name' => esc_html__( 'Hub Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'hub.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-hub-blog-template/' ),
			),
			'minimal'            => array(
				'template_name' => esc_html__( 'Minimal Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'minimal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-minimal-grid-blog-template/' ),
			),
			'masonry_timeline'   => array(
				'template_name' => esc_html__( 'Masonry Timeline', 'blog-designer' ),
				'class'         => 'magazine timeline',
				'image_name'    => 'masonry_timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-masonry-timeline-blog-template/' ),
			),
			'invert-grid'        => array(
				'template_name' => esc_html__( 'Invert Grid Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'invert-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-invert-grid-blog-template/' ),
			),
			'lightbreeze'        => array(
				'template_name' => esc_html__( 'Lightbreeze Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'lightbreeze.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-light-breeze-blog-template/' ),
			),
			'my_diary'           => array(
				'template_name' => esc_html__( 'My Diary Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'my_diary.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-my-diary-blog-template/' ),
			),
			'navia'              => array(
				'template_name' => esc_html__( 'Navia Template', 'blog-designer' ),
				'class'         => 'magazine',
				'image_name'    => 'navia.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-navia-blog-template/' ),
			),
			'news'               => array(
				'template_name' => esc_html__( 'News Template', 'blog-designer' ),
				'class'         => 'magazine free',
				'image_name'    => 'news.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-news-blog-template/' ),
			),
			'neaty_block'        => array(
				'template_name' => esc_html__( 'Neaty Block Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'neaty_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-neaty-block-blog-template/' ),
			),
			'offer_blog'         => array(
				'template_name' => esc_html__( 'Offer Blog Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'offer_blog.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-offer-blog-template/' ),
			),
			'overlay_horizontal' => array(
				'template_name' => esc_html__( 'Overlay Horizontal Template', 'blog-designer' ),
				'class'         => 'timeline slider',
				'image_name'    => 'overlay_horizontal.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-overlay-horizontal-timeline-blog-template/' ),
			),
			'nicy'               => array(
				'template_name' => esc_html__( 'Nicy Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'nicy.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-nicy-blog-template/' ),
			),
			'region'             => array(
				'template_name' => esc_html__( 'Region Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'region.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-region-blog-template/' ),
			),
			'roctangle'          => array(
				'template_name' => esc_html__( 'Roctangle Template', 'blog-designer' ),
				'class'         => 'masonry',
				'image_name'    => 'roctangle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-roctangle-blog-template/' ),
			),
			'schedule'           => array(
				'template_name' => esc_html__( 'Schedule Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'schedule.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-schedule-blog-template/' ),
			),
			'sharpen'            => array(
				'template_name' => esc_html__( 'Sharpen Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'sharpen.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sharpen-blog-template/' ),
			),
			'spektrum'           => array(
				'template_name' => esc_html__( 'Spektrum Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'spektrum.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-spektrum-blog-template/' ),
			),
			'soft_block'         => array(
				'template_name' => esc_html__( 'Soft Block Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'soft_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-soft-block-blog-template/' ),
			),
			'story'              => array(
				'template_name' => esc_html__( 'Story Template', 'blog-designer' ),
				'class'         => 'timeline',
				'image_name'    => 'story.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-story-timeline-blog-template/' ),
			),
			'timeline'           => array(
				'template_name' => esc_html__( 'Timeline Template', 'blog-designer' ),
				'class'         => 'timeline free',
				'image_name'    => 'timeline.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-timeline-blog-template/' ),
			),
			'winter'             => array(
				'template_name' => esc_html__( 'Winter Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'winter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-winter-blog-template/' ),
			),
			'wise_block'         => array(
				'template_name' => esc_html__( 'Wise Block Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'wise_block.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-wise-block-blog-template/' ),
			),
			'crayon_slider'      => array(
				'template_name' => esc_html__( 'Crayon Slider Template', 'blog-designer' ),
				'class'         => 'slider free',
				'image_name'    => 'crayon_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-crayon-slider-blog-template/' ),
			),
			'sallet_slider'      => array(
				'template_name' => esc_html__( 'Sallet Slider Template', 'blog-designer' ),
				'class'         => 'slider free',
				'image_name'    => 'sallet_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sallet-slider-blog-template/' ),
			),
			'sunshiny_slider'    => array(
				'template_name' => esc_html__( 'Sunshiny Slider Template', 'blog-designer' ),
				'class'         => 'slider',
				'image_name'    => 'sunshiny_slider.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-sunshiny-slider-blog-template/' ),
			),
			'pretty'             => array(
				'template_name' => esc_html__( 'Pretty Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'pretty.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-pretty-blog-template/' ),
			),
			'tagly'              => array(
				'template_name' => esc_html__( 'Tagly Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'tagly.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-tagly-blog-template/' ),
			),
			'brite'              => array(
				'template_name' => esc_html__( 'Brite Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'brite.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-brite-blog-template/' ),
			),
			'chapter'            => array(
				'template_name' => esc_html__( 'Chapter Template', 'blog-designer' ),
				'class'         => 'grid',
				'image_name'    => 'chapter.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-chapter-blog-template/' ),
			),
			'steps'              => array(
				'template_name' => esc_html__( 'Steps Template', 'blog-designer' ),
				'class'         => 'timeline',
				'image_name'    => 'steps.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-steps-timeline-blog-template/' ),
			),
			'miracle'            => array(
				'template_name' => esc_html__( 'Miracle Template', 'blog-designer' ),
				'class'         => 'full-width',
				'image_name'    => 'miracle.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-miracle-blog-template/' ),
			),
			'media-grid'         => array(
				'template_name' => esc_html__( 'Media Grid Template', 'blog-designer' ),
				'class'         => 'grid free',
				'image_name'    => 'media-grid.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-media-grid-blog-template/' ),
			),
			'blog-carousel'      => array(
				'template_name' => esc_html__( 'Blog Carousel Template', 'blog-designer' ),
				'class'         => 'slider free',
				'image_name'    => 'blog-carousel.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-carousel-blog-template/' ),
			),
			'blog-grid-box'      => array(
				'template_name' => esc_html__( 'Blog Grid Box Template', 'blog-designer' ),
				'class'         => 'grid free',
				'image_name'    => 'blog-grid-box.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-grid-box-template/' ),
			),
			'ticker'             => array(
				'template_name' => esc_html__( 'Ticker Template', 'blog-designer' ),
				'class'         => 'full-width free',
				'image_name'    => 'ticker.jpg',
				'demo_link'     => esc_url( 'https://wpblogdesigner.net/demo/blog-ticker-template/' ),
			),
		);
		ksort( $tempate_list );
		return $tempate_list;
	}

	/**
	 * Column layout template class
	 *
	 * @since 2.0
	 * @global object $settings;
	 * @param object $settings settings.
	 */
	public static function bd_column_class( $settings ) {
		$column_class = '';
		$total_col    = ( isset( $settings['template_columns'] ) && '' != $settings['template_columns'] ) ? $settings['template_columns'] : 2;
		if ( 1 == $total_col ) {
			$col_class = 'one_column';
		}
		if ( 2 == $total_col ) {
			$col_class = 'two_column';
		}
		if ( 3 == $total_col ) {
			$col_class = 'three_column';
		}
		if ( 4 == $total_col ) {
			$col_class = 'four_column';
		}
		$total_col_ipad = ( isset( $settings['template_columns_ipad'] ) && '' != $settings['template_columns_ipad'] ) ? $settings['template_columns_ipad'] : 1;
		if ( 1 == $total_col_ipad ) {
			$col_class_ipad = 'one_column_ipad';
		}
		if ( 2 == $total_col_ipad ) {
			$col_class_ipad = 'two_column_ipad';
		}
		if ( 3 == $total_col_ipad ) {
			$col_class_ipad = 'three_column_ipad';
		}
		if ( 4 == $total_col_ipad ) {
			$col_class_ipad = 'four_column_ipad';
		}
		$total_col_tablet = ( isset( $settings['template_columns_tablet'] ) && '' != $settings['template_columns_tablet'] ) ? $settings['template_columns_tablet'] : 1;
		if ( 1 == $total_col_tablet ) {
			$col_class_tablet = 'one_column_tablet';
		}
		if ( 2 == $total_col_tablet ) {
			$col_class_tablet = 'two_column_tablet';
		}
		if ( 3 == $total_col_tablet ) {
			$col_class_tablet = 'three_column_tablet';
		}
		if ( 4 == $total_col_tablet ) {
			$col_class_tablet = 'four_column_tablet';
		}
		$total_col_mobile = ( isset( $settings['template_columns_mobile'] ) && '' != $settings['template_columns_mobile'] ) ? $settings['template_columns_mobile'] : 1;
		if ( 1 == $total_col_mobile ) {
			$col_class_mobile = 'one_column_mobile';
		}
		if ( 2 == $total_col_mobile ) {
			$col_class_mobile = 'two_column_mobile';
		}
		if ( 3 == $total_col_mobile ) {
			$col_class_mobile = 'three_column_mobile';
		}
		if ( 4 == $total_col_mobile ) {
			$col_class_mobile = 'four_column_mobile';
		}

		$column_class = $col_class . ' ' . $col_class_ipad . ' ' . $col_class_tablet . ' ' . $col_class_mobile;
		return $column_class;
	}
	/**
	 * Html display classical design
	 *
	 * @param type $alterclass alter native class.
	 */
	public static function bd_classical_template( $alterclass ) {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template classical">
			<?php
			if ( has_post_thumbnail() ) {
				?>
				<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div>
				<?php
			}
			?>
			<div class="bd-blog-header">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php
				$display_date          = get_option( 'display_date' );
				$display_author        = get_option( 'display_author' );
				$display_comment_count = get_option( 'display_comment_count' );
				if ( 0 == $display_date || 0 == $display_author || 0 == $display_comment_count ) {
					?>
					<div class="bd-metadatabox"><div class="bd-meta-datas" style="display:inline-block;">
							<?php
							if ( 0 == $display_author && 0 == $display_date ) {
								esc_html_e( 'Posted by ', 'blog-designer' );
								?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>&nbsp;<?php esc_html_e( 'on', 'blog-designer' ); ?>&nbsp;
								<?php
								$date_format = get_option( 'date_format' );
								echo esc_attr( get_the_time( $date_format ) );
							} elseif ( 0 == $display_author ) {
								esc_html_e( 'Posted by ', 'blog-designer' );
								?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>&nbsp;
								<?php
							} elseif ( 0 == $display_date ) {
								esc_html_e( 'Posted on ', 'blog-designer' );
								$date_format = get_option( 'date_format' );
								echo esc_attr( get_the_time( $date_format ) );
							}
							?>
						</div>
						<?php
						if ( 0 == $display_comment_count ) {
							?>
							<div class="bd-metacomments">
								<i class="fas fa-comment"></i><?php comments_popup_link( '0', '1', '%' ); ?>
							</div>
							<?php
						}
						?>
					</div>
					<?php
				}
				if ( 0 == get_option( 'display_category' ) ) {
					?>
					<div><span class="bd-category-link">
							<?php
							echo '<span class="bd-link-label">';
							echo '<i class="fas fa-folder-open"></i>';
							esc_html_e( 'Category', 'blog-designer' );
							echo ':&nbsp;';
							echo '</span>';
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
							endif;
							?>
						</span></div>
					<?php
				}
				if ( 0 == get_option( 'display_tag' ) ) {
					$tags_list = get_the_tag_list( '', ', ' );
					if ( $tags_list ) :
						?>
						<div class="bd-tags">
							<?php
							echo '<span class="bd-link-label">';
							echo '<i class="fas fa-tags"></i>';
							esc_html_e( 'Tags', 'blog-designer' );
							echo ':&nbsp;';
							echo '</span>';
							echo wp_kses( $tags_list, $args_kses );
							$show_sep = true;
							?>
						</div>
						<?php
					endif;
				}
				?>
			</div>
			<div class="bd-post-content">
				<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
				<?php
				if ( 1 == get_option( 'rss_use_excerpt' ) && 1 == get_option( 'read_more_on' ) ) {
					$read_more_class = ( 1 == get_option( 'read_more_on' ) ) ? 'bd-more-tag-inline' : 'bd-more-tag';
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div>
			<div class="bd-post-footer">
				<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
					<div class="social-component">
						<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
							<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
							<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
							<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
						<?php endif; ?>
						<?php
						$pinterestimage = '';
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
							<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
					</div>
				<?php } ?>
				<?php
				if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) {
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo ' <a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div></div>
		<?php
	}
	/**
	 * Html display crayon_slider design
	 */
	public static function bd_crayon_slider_template() {
		$args_kses             = self::args_kses();
		$display_date          = get_option( 'display_date' );
		$display_author        = get_option( 'display_author' );
		$display_category      = get_option( 'display_category' );
		$display_comment_count = get_option( 'display_comment_count' );

		?>
		<li class="blog_template bdp_blog_template crayon_slider">
			<div class="bdp-post-image">
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
					<?php
				} else {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><img alt="<?php echo esc_attr__( 'Feature image not available', 'blog-designer' ); ?>" src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'public/images/No_available_image.png'; ?>" /></a></div> 
					<?php
				}
				?>
			</div>
			<div class="blog_header">
				<div class="post_metadata">
					<?php
					if ( 0 == $display_category ) {
						?>
						<div class="category-link">
							<?php
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo ' ';
								echo wp_kses( str_replace( ',', '', $categories_list ), $args_kses );
								$show_sep = true;
							endif;
							?>
						</div>
						<?php
					}
					?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					if ( 0 == $display_author || 0 == $display_date || 0 == $display_comment_count ) {
						?>
						<div class="metadatabox">
							<?php
							if ( 0 == $display_author || 0 == $display_date ) {
								if ( 0 == $display_author ) {
									?>
									<div class="mauthor">
										<span class="author">
											<i class="fas fa-user"></i>
											<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
										</span>
									</div>
									<?php
								}
								if ( 0 == $display_date ) {
									$date_format = get_option( 'date_format' );
									?>
									<div class="post-date">
										<span class="mdate"><i class="far fa-calendar-alt"></i> <?php echo esc_attr( get_the_time( $date_format ) ); ?></span>
									</div>
									<?php
								}
							}
							if ( 0 == $display_comment_count ) {
								?>
								<div class="post-comment">
									<?php
									comments_popup_link( '<i class="fas fa-comment"></i>' . esc_html__( 'Leave a Comment', 'blog-designer' ), '<i class="fas fa-comment"></i>' . esc_html__( '1 comment', 'blog-designer' ), '<i class="fas fa-comment"></i>% ' . esc_html__( 'comments', 'blog-designer' ), 'comments-link', '<i class="fas fa-comment"></i>' . esc_html__( 'Comments are off', 'blog-designer' ) );
									?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="post_content">
					<div class="post_content-inner">
						<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
						<?php
						if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
							$class = 'bd-more-tag-inline';
							if ( 2 == get_option( 'read_more_on' ) ) {
								$class = 'bd-more-tag';
								echo '<div class="bd-more-next-line">';
							}
							if ( '' != get_option( 'read_more_text' ) ) {
								echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
							} else {
								echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
							}
							if ( 2 == get_option( 'read_more_on' ) ) {
								echo '</div>';
							}
						}
						?>
					</div>
					<?php
					if ( 0 == get_option( 'display_tag' ) ) {
						$tags_list = get_the_tag_list( '', ', ' );
						if ( $tags_list ) :
							?>
							<div class="tags"><i class="fas fa-bookmark"></i>&nbsp;
								<?php
								echo wp_kses( $tags_list, $args_kses );
								$show_sep = true;
								?>
							</div>
							<?php
						endif;
					}
					?>
					<div class='bd_social_share_wrap'>
						<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
							<div class="social-component">
								<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
									<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
									<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
									<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
								<?php endif; ?>
								<?php
								if ( 0 == get_option( 'pinterest_link' ) ) :
									$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
									?>
									<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</li>
		<?php
	}
	/**
	 * Html display sallet_slider design
	 */
	public static function bd_sallet_slider_template() {
		$display_date            = get_option( 'display_date' );
		$display_author          = get_option( 'display_author' );
		$display_category        = get_option( 'display_category' );
		$display_comment_count   = get_option( 'display_comment_count' );
		$settings                = get_option( 'wp_blog_designer_settings' );
		$template_slider_content = isset( $settings['template_slider_content'] ) ? $settings['template_slider_content'] : 'center';
		$args_kses               = self::args_kses();
		?>
		<li class="blog_template bdp_blog_template sallet_slider <?php echo esc_attr( $template_slider_content ); ?>">
			<div class="post_hentry">
				<?php
				if ( has_post_thumbnail() ) {
					?>
						<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div>
					<?php
				} else {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><img alt="<?php echo esc_attr__( 'Feature image not available', 'blog-designer' ); ?>" src="<?php echo esc_url( BLOGDESIGNER_URL ) . 'public/images/No_available_image.png'; ?>" /></a></div> 
					<?php
				}
				?>
			</div>
			<div class="blog_header">
				<div><div><div>
					<?php
					if ( 0 == $display_category ) {
						?>
						<div class="category-link">
							<?php
							$categories_list = get_the_category_list( ' ' );
							if ( $categories_list ) :
								echo ' ';
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
							endif;
							?>
						</div>
						<?php
					}
					?>
					<h2 class="bd-blog-header"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					if ( 0 == $display_author || 0 == $display_date || 0 == $display_comment_count || 0 == get_option( 'display_tag' ) ) {
						?>
						<div class="metadatabox">
							<?php
							if ( 0 == get_option( 'display_tag' ) ) {
								$tags_list = get_the_tag_list( '', ', ' );
								if ( $tags_list ) :
									?>
									<div class="bd-tags">
										<?php
										echo '<span class="bd-link-label">';
										echo '<i class="fas fa-tags"></i>';
										echo '</span>';
										echo wp_kses( $tags_list, $args_kses );
										$show_sep = true;
										?>
									</div>
									<?php
								endif;
							}
							if ( 0 == $display_author || 0 == $display_date ) {
								if ( 0 == $display_author ) {
									?>
									<div class="mauthor">
										<span class="author">
											<i class="fas fa-user"></i>
											<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
										</span>
									</div>
									<?php
								}
								if ( 0 == $display_date ) {
									$date_format = get_option( 'date_format' );
									?>
									<div class="post-date">
										<span class="mdate"><i class="far fa-calendar-alt"></i> <?php echo esc_attr( get_the_time( $date_format ) ); ?></span>
									</div>
									<?php
								}
							}
							if ( 0 == $display_comment_count ) {
								?>
								<div class="post-comment">
									<?php
									comments_popup_link( '<i class="fas fa-comment"></i>' . esc_html__( 'Leave a Comment', 'blog-designer' ), '<i class="fas fa-comment"></i>' . esc_html__( '1 comment', 'blog-designer' ), '<i class="fas fa-comment"></i>% ' . esc_html__( 'comments', 'blog-designer' ), 'comments-link', '<i class="fas fa-comment"></i>' . esc_html__( 'Comments are off', 'blog-designer' ) );
									?>
								</div>
								<?php
							}
							?>
						</div>
						<?php
					}
					?>
					<div class="post_content">
						<div class="post_content-inner">
							<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
							<?php
							if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
								$class = 'bd-more-tag-inline';
								if ( 2 == get_option( 'read_more_on' ) ) {
									$class = 'bd-more-tag';
									echo '<div class="bd-more-next-line">';
								}
								if ( '' != get_option( 'read_more_text' ) ) {
									echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
								} else {
									echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
								}
								if ( 2 == get_option( 'read_more_on' ) ) {
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
					<div class='bd_social_share_wrap'>
						<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
							<div class="social-component">
								<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
									<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
									<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
									<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
								<?php endif; ?>
								<?php
								if ( 0 == get_option( 'pinterest_link' ) ) :
									$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
									?>
									<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
							</div>
						<?php } ?>
					</div>
				</div></div></div>
			</div>
		</li>
		<?php
	}
	/**
	 * Html display boxy-clean design
	 *
	 * @param type $settings Setting.
	 */
	public static function bd_boxy_clean_template( $settings ) {
		$col_class = self::bd_column_class( $settings );
		$args_kses = self::args_kses();
		?>
		<li class="blog_wrap bdp_blog_template <?php echo ( '' != $col_class ) ? esc_attr( $col_class ) : ''; ?> bdp_blog_single_post_wrapp">
			<?php
			$display_date          = get_option( 'display_date' );
			$display_author        = get_option( 'display_author' );
			$display_category      = get_option( 'display_category' );
			$display_comment_count = get_option( 'display_comment_count' );
			?>
			<div class="post-meta">
				<?php
				if ( 0 == $display_date ) {
					$date_format = get_option( 'date_format' );
					?>
					<div class="postdate">
						<span class="month"><?php echo esc_attr( get_the_time( 'M d' ) ); ?></span>
						<span class="year"><?php echo esc_attr( get_the_time( 'Y' ) ); ?></span>
					</div>
					<?php
				}
				if ( 0 == $display_comment_count ) {
					if ( comments_open() ) {
						?>
						<span class="post-comment">
							<i class="fas fa-comment"></i>
							<?php
							comments_popup_link( '0', '1', '%' );
							?>
						</span>  
						<?php
					}
				}
				?>
			</div>
			<div class="post-media">
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
					<?php
				}
				if ( 0 == $display_author ) {
					?>
					<span class="author">
						<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
					</span>
					<?php
				}
				?>
			</div>
			<div class="post_summary_outer">
				<div class="blog_header">
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
				<div class="post_content">
					<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
					<?php
					if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
						$class = 'bd-more-tag-inline';
						if ( 2 == get_option( 'read_more_on' ) ) {
							$class = 'bd-more-tag';
							echo '<div class="bd-more-next-line">';
						}
						if ( '' != get_option( 'read_more_text' ) ) {
							echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
						} else {
							echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
						}
						if ( 2 == get_option( 'read_more_on' ) ) {
							echo '</div>';
						}
					}
					?>
				</div>
			</div>
			<div class="blog_footer">
				<div class="footer_meta">
					<?php
					if ( 0 == $display_category ) {
						?>
						<div class="bd-metacats">
							<i class="fas fa-bookmark"></i>
							<?php
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
							endif;
							?>
						</div>
						<?php
					}
					?>
					<?php
					if ( 0 == get_option( 'display_tag' ) ) {
						$tags_list = get_the_tag_list( '', ', ' );
						if ( $tags_list ) :
							?>
							<div class=""><i class="fas fa-tags"></i>
								<?php
								echo wp_kses( $tags_list, $args_kses );
								$show_sep = true;
								?>
							</div>
							<?php
						endif;
					}
					?>
				</div>
				<div class='bd_social_share_wrap'>
					<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
						<div class="social-component">
							<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
								<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
								<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
								<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
							<?php endif; ?>
							<?php
							if ( 0 == get_option( 'pinterest_link' ) ) :
								$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
								?>
								<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</li>

		<?php
	}

	/**
	 * Html display lightbreeze design
	 *
	 * @param type $alterclass alterclass.
	 */
	public static function bd_lightbreeze_template( $alterclass ) {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template box-template active lightbreeze <?php echo esc_attr( $alterclass ); ?>">
			<?php
			if ( has_post_thumbnail() ) {
				?>
				<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
				<?php
			}
			?>
			<div class="bd-blog-header">
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				<?php
				$display_date          = get_option( 'display_date' );
				$display_author        = get_option( 'display_author' );
				$display_category      = get_option( 'display_category' );
				$display_comment_count = get_option( 'display_comment_count' );
				if ( 0 == $display_date || 0 == $display_author || 0 == $display_category || 0 == $display_comment_count ) {
					?>
					<div class="bd-meta-data-box">
						<?php
						if ( 0 == $display_author ) {
							?>
							<div class="bd-metadate">
								<i class="fas fa-user"></i><?php esc_html_e( 'Posted by ', 'blog-designer' ); ?><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a><br />
							</div>
							<?php
						}
						if ( 0 == $display_date ) {
							$date_format = get_option( 'date_format' );
							?>
							<div class="bd-metauser">
								<span class="mdate"><i class="far fa-calendar-alt"></i> <?php echo esc_attr( get_the_time( $date_format ) ); ?></span>
							</div>
							<?php
						}
						if ( 0 == $display_category ) {
							?>
							<div class="bd-metacats">
								<i class="fas fa-bookmark"></i>&nbsp;
								<?php
								$categories_list = get_the_category_list( ', ' );
								if ( $categories_list ) :
									echo wp_kses( $categories_list, $args_kses );
									$show_sep = true;
								endif;
								?>
							</div>
							<?php
						}
						if ( 0 == $display_comment_count ) {
							?>
							<div class="bd-metacomments"><i class="fas fa-comment"></i><?php comments_popup_link( esc_html__( 'No Comments', 'blog-designer' ), esc_html__( '1 Comment', 'blog-designer' ), '% ' . esc_html__( 'Comments', 'blog-designer' ) ); ?></div>
					<?php } ?>
					</div>
				<?php } ?>
			</div>
			<div class="bd-post-content">
				<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
				<?php
				if ( 1 == get_option( 'rss_use_excerpt' ) && 1 == get_option( 'read_more_on' ) ) {
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div>
			<?php
			if ( 0 == get_option( 'display_tag' ) ) {
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) :
					?>
					<div class="bd-tags"><i class="fas fa-tags"></i>&nbsp;
						<?php
						echo wp_kses( $tags_list, $args_kses );
						$show_sep = true;
						?>
					</div>
					<?php
				endif;
			}
			?>
			<div class="bd-post-footer">
				<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
					<div class="social-component">
						<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
							<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
							<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
							<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
						<?php endif; ?>
						<?php
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
							<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
					<?php endif; ?>
					</div>
				<?php } ?>
				<?php
				if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) {
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div>
		</div> 
		<?php
	}

	/**
	 * Html display spektrum design
	 */
	public static function bd_spektrum_template() {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template spektrum">
		<?php if ( has_post_thumbnail() ) { ?>
				<div class="bd-post-image">
			<?php the_post_thumbnail( 'full' ); ?>
					<div class="overlay">
						<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
					</div>
				</div>
				<?php } ?>
			<div class="spektrum_content_div">
				<div class="bd-blog-header
				<?php
				if ( 0 != get_option( 'display_date' ) ) {
					echo ' disable_date';
				}
				?>
				">
				<?php if ( 0 == get_option( 'display_date' ) ) { ?>
					<p class="date"><span class="number-date"><?php the_time( 'd' ); ?></span><?php the_time( 'F' ); ?></p>
				<?php } ?>
				<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>
				<div class="bd-post-content">
					<?php
					echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() );
					if ( 1 == get_option( 'rss_use_excerpt' ) && get_option( 'excerpt_length' ) > 0 ) {
						if ( 1 == get_option( 'read_more_on' ) ) {
							if ( '' != get_option( 'read_more_text' ) ) {
								echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
							} else {
								echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
							}
						}
					}

					if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) :
						?>
						<span class="details">
							<?php
							global $post;
							if ( 2 == get_option( 'read_more_on' ) ) {
								if ( '' != get_option( 'read_more_text' ) ) {
									echo '<a class="bd-more-tag" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
								} else {
									echo ' <a class="bd-more-tag" href="' . esc_url( get_permalink( $post->ID ) ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
								}
							}
							?>
						</span><?php endif; ?>
				</div>
				<?php
				$display_category      = get_option( 'display_category' );
				$display_author        = get_option( 'display_author' );
				$display_tag           = get_option( 'display_tag' );
				$display_comment_count = get_option( 'display_comment_count' );
				if ( 0 == $display_category || 0 == $display_author || 0 == $display_tag || 0 == $display_comment_count ) {
					?>
					<div class="post-bottom">
							<?php if ( 0 == $display_category ) { ?>
							<span class="bd-categories"><i class="fas fa-bookmark"></i>&nbsp;
								<?php
								$categories_list = get_the_category_list( ', ' );
								if ( $categories_list ) :
									echo '<span class="bd-link-label">';
									esc_html_e( 'Categories', 'blog-designer' );
									echo '</span>';
									echo ' : ';
									echo wp_kses( $categories_list, $args_kses );
									$show_sep = true;
								endif;
								?>
							</span>
								<?php
							}
							if ( 0 == $display_author ) {
								?>
							<span class="post-by"><i class="fas fa-user"></i>&nbsp;<?php esc_html_e( 'Posted by ', 'blog-designer' ); ?><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
							</span>
								<?php
							}
							if ( 0 == $display_tag ) {
								$tags_list = get_the_tag_list( '', ', ' );
								if ( $tags_list ) :
									?>
								<span class="bd-tags"><i class="fas fa-tags"></i>&nbsp;
									<?php
									echo wp_kses( $tags_list, $args_kses );
									$show_sep = true;
									?>
								</span>
									<?php
								endif;
							}
							if ( 0 == $display_comment_count ) {
								?>
								<span class="bd-metacomments"><i class="fas fa-comment"></i>&nbsp;<?php comments_popup_link( esc_html__( 'No Comments', 'blog-designer' ), esc_html__( '1 Comment', 'blog-designer' ), '% ' . esc_html__( 'Comments', 'blog-designer' ) ); ?>
								</span>
								<?php
							}
							?>
					</div>
					<?php } ?>

					<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
					<div class="social-component spektrum-social">
						<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
							<a href="<?php echo esc_url( 'https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() ); ?>" target= _blank class="bd-facebook-share"><i class="fab fa-facebook-f"></i></a>
							<?php
						endif;
						if ( 0 == get_option( 'twitter_link' ) ) :
							?>
							<a href="<?php echo esc_url( 'http://twitter.com/share?&url=' . get_the_permalink() ); ?>" target= _blank class="bd-twitter-share"><i class="fab fa-x-twitter"></i></a>
							<?php
						endif;
						if ( 0 == get_option( 'linkedin_link' ) ) :
							?>
							<a href="<?php echo esc_url( 'http://www.linkedin.com/shareArticle?url=' . get_the_permalink() ); ?>" target= _blank class="bd-linkedin-share"><i class="fab fa-linkedin-in"></i></a>
							<?php
						endif;
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
							<a href="<?php echo esc_url( '//pinterest.com/pin/create/button/?url=' . get_the_permalink() ); ?>" target= _blank class="bd-pinterest-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
					</div>
				<?php } ?>
			</div>
		</div>
		<?php
	}

	/**
	 * Html display evolution design
	 *
	 * @param type $alterclass alterclass.
	 */
	public static function bd_evolution_template( $alterclass ) {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template evolution <?php echo esc_attr( $alterclass ); ?>">
			<?php if ( 0 == get_option( 'display_category' ) ) { ?>
				<div class="bd-categories">
					<?php
					$categories_list = get_the_category_list( ', ' );
					if ( $categories_list ) :
						echo wp_kses( $categories_list, $args_kses );
						$show_sep = true;
					endif;
					?>
				</div>
			<?php } ?>

			<div class="bd-blog-header"><h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2></div>

			<?php
			$display_date          = get_option( 'display_date' );
			$display_author        = get_option( 'display_author' );
			$display_comment_count = get_option( 'display_comment_count' );
			if ( 0 == $display_date || 0 == $display_author || 0 == $display_comment_count ) {
				?>
				<div class="post-entry-meta">
					<?php
					if ( 0 == $display_date ) {
						$date_format = get_option( 'date_format' );
						?>
						<span class="date"><i class="far fa-clock"></i><?php echo esc_attr( get_the_time( $date_format ) ); ?></span>
						<?php
					}
					if ( 0 == $display_author ) {
						?>
						<span class="author"><i class="fas fa-user"></i><?php esc_html_e( 'Posted by ', 'blog-designer' ); ?><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></span>
						<?php
					}
					if ( 0 == $display_comment_count ) {
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comment"><i class="fas fa-comment"></i><?php comments_popup_link( '0', '1', '%' ); ?></span>
							<?php
						endif;
					}
					?>
				</div>
		<?php } ?>

		<?php if ( has_post_thumbnail() ) { ?>
				<div class="bd-post-image">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?>
						<span class="overlay"></span>
					</a>
				</div>
				<?php } ?>
			<div class="bd-post-content">
				<?php
				echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() );
				if ( 1 == get_option( 'rss_use_excerpt' ) && 1 == get_option( 'read_more_on' ) ) {
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div>

			<?php
			$display_tag = get_option( 'display_tag' );
			if ( 0 == $display_tag ) {
				$tags_list = get_the_tag_list( '', ', ' );
				if ( $tags_list ) :
					?>
					<div class="bd-tags">
						<?php
						echo '<span class="bd-link-label">';
						echo '<i class="fas fa-tags"></i>';
						esc_html_e( 'Tags', 'blog-designer' );
						echo ':&nbsp;';
						echo '</span>';
						echo wp_kses( $tags_list, $args_kses );
						$show_sep = true;
						?>
					</div>
					<?php
				endif;
			}
			?>
			<div class="bd-post-footer">
				<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
					<div class="social-component">
						<?php
						if ( 0 == get_option( 'facebook_link' ) ) :
							?>
							<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
							<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
							<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
						<?php endif; ?>																			<?php
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
									<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
					</div>
				<?php } ?>
				<?php
				if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) {
					if ( '' != get_option( 'read_more_text' ) ) {
						echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
					} else {
						echo ' <a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
					}
				}
				?>
			</div></div>
		<?php
	}

	/**
	 * Html display timeline design
	 *
	 * @param type $alterclass alterclass.
	 */
	public static function bd_timeline_template( $alterclass ) {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template timeline blog-wrap <?php echo esc_attr( $alterclass ); ?>">
			<div class="post_hentry"><p><i class="fas" data-fa-pseudo-element=":before"></i></p><div class="post_content_wrap">
					<div class="post_wrapper box-blog">
						<?php if ( has_post_thumbnail() ) { ?>
							<div class="bd-post-image photo">
								<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?>
									<span class="overlay"></span>
								</a>
							</div>
						<?php } ?>
						<div class="desc">
							<h3 class="text-center text-capitalize"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
							<?php
							$display_author        = get_option( 'display_author' );
							$display_comment_count = get_option( 'display_comment_count' );
							$display_date          = get_option( 'display_date' );
							if ( 0 == $display_date || 0 == $display_comment_count || 0 == $display_date ) {
								?>
								<div class="date_wrap">
									<?php if ( 0 == $display_author ) { ?>
										<p class='bd-margin-0'><span title="Posted By <?php the_author(); ?>"><i class="fas fa-user"></i>&nbsp;<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a></span>&nbsp;&nbsp;</p>
										<?php
									} if ( 0 == $display_comment_count ) {
										?>
										<p class='bd-margin-0'><span class="bd-metacomments"><i class="fas fa-comment"></i>&nbsp;<?php comments_popup_link( esc_html__( 'No Comments', 'blog-designer' ), esc_html__( '1 Comment', 'blog-designer' ), '% ' . esc_html__( 'Comments', 'blog-designer' ) ); ?>
											</span></p>
										<?php
									} if ( 0 == $display_date ) {
										?>
										<div class="bd-datetime">
											<span class="month"><?php the_time( 'M' ); ?></span><span class="date"><?php the_time( 'd' ); ?></span>
										</div>
									<?php } ?>
								</div>
							<?php } ?>
							<div class="bd-post-content">
								<?php
								echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() );
								if ( 1 == get_option( 'rss_use_excerpt' ) && get_option( 'excerpt_length' ) > 0 ) {
									if ( 1 == get_option( 'read_more_on' ) ) {
										if ( '' != get_option( 'read_more_text' ) ) {
											echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
										} else {
											echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
										}
									}
								}
								?>
							</div>
							<?php if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) : ?>
								<div class="read_more">
									<?php
									global $post;
									if ( 2 == get_option( 'read_more_on' ) ) {
										if ( '' != get_option( 'read_more_text' ) ) {
											echo '<a class="bd-more-tag" href="' . esc_url( get_permalink( $post->ID ) ) . '"><i class="fas fa-plus"></i> ' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
										} else {
											echo ' <a class="bd-more-tag" href="' . esc_url( get_permalink( $post->ID ) ) . '"><i class="fas fa-plus"></i> ' . esc_html__( 'Read more', 'blog-designer' ) . ' &raquo;</a>';
										}
									}
									?>
								</div>
							<?php endif; ?>
						</div>
					</div>
					<?php if ( 0 == get_option( 'display_category' ) || ( 0 != get_option( 'social_share' ) && ( 0 == get_option( 'display_tag' ) || ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) ) { ?>
						<footer class="blog_footer text-capitalize">
							<?php
							if ( 0 == get_option( 'display_category' ) ) {
								?>
								<p class="bd-margin-0"><span class="bd-categories"><i class="fas fa-folder"></i>
										<?php
										$categories_list = get_the_category_list( ', ' );
										if ( $categories_list ) :
											echo '<span class="bd-link-label">';
											esc_html_e( 'Categories', 'blog-designer' );
											echo ' :&nbsp;';
											echo '</span>';
											echo wp_kses( $categories_list, $args_kses );
											$show_sep = true;
										endif;
										?>
									</span>
								</p>
								<?php
							}
							if ( 0 == get_option( 'display_tag' ) ) {
								$tags_list = get_the_tag_list( '', ', ' );
								if ( $tags_list ) :
									?>
									<p class="bd-margin-0">
										<span class="bd-tags"><i class="fas fa-bookmark"></i>
											<?php
											echo '<span class="bd-link-label">';
											esc_html_e( 'Tags', 'blog-designer' );
											echo ' :&nbsp;';
											echo '</span>';
											echo wp_kses( $tags_list, $args_kses );
											$show_sep = true;
											?>
										</span>
									</p>
									<?php
								endif;
							}
							if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) {
								?>
								<div class="social-component">
									<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
										<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
										<?php
									endif;
									if ( 0 == get_option( 'twitter_link' ) ) :
										?>
										<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
										<?php
									endif;
									if ( 0 == get_option( 'linkedin_link' ) ) :
										?>
										<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
										<?php
									endif;
									if ( 0 == get_option( 'pinterest_link' ) ) :
										$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
										?>
										<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
									<?php endif; ?>
								</div>
								<?php
							}
							?>
						</footer>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Html display glossary design
	 *
	 * @param type $settings settings.
	 */
	public static function bd_glossary_template( $settings ) {
		$col_class             = self::bd_column_class( $settings );
		$display_date          = get_option( 'display_date' );
		$display_author        = get_option( 'display_author' );
		$display_category      = get_option( 'display_category' );
		$display_comment_count = get_option( 'display_comment_count' );
		$args_kses             = self::args_kses();
		?>
		<div class="glossary bdp_blog_template blog_template blog_masonry_item <?php echo ( '' != $col_class ) ? esc_attr( $col_class ) : ''; ?>">
			<div class="blog_item">
				<div class="blog_header">
					<?php
					if ( 0 == $display_date ) {
						$date_format = get_option( 'date_format' );
						?>
						<time datetime="" class="datetime">
						<?php echo esc_attr( get_the_time( 'F jS, Y' ) ); ?>
						</time>
						<?php
					}
					if ( 0 == $display_author ) {
						?>
						<span class="post-author">
							<span> 
							<?php
							if ( 0 == $display_date ) {
								?>
								&nbsp; | &nbsp;<?php } ?> 
								<?php
								echo '<span class="link-lable">' . esc_html__( 'By ', 'blog-designer' ) . '</span>';
								?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>">
									<?php
									the_author();
									?>
								</a>
							</span>
						</span>
						<?php
					}
					if ( 0 == $display_comment_count ) {
						if ( ! post_password_required() && ( comments_open() || get_comments_number() ) ) :
							?>
							<span class="comment"><?php echo ( ( 0 == $display_author && 0 == $display_date ) || ( 0 == $display_author || 0 == $display_date ) ) ? '&nbsp; | &nbsp;' : ''; ?><i class="fas fa-comment"></i><?php comments_popup_link( '0', '1', '%' ); ?></span>
							<?php
						endif;
					}
					?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
				</div>
				<?php
				if ( get_the_content() || has_post_thumbnail() ) {
					?>
					<div class="post_summary_outer">
						<?php
						if ( has_post_thumbnail() ) {
							?>
							<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
							<?php
						}
						if ( get_the_content() ) {
							?>
							<div class="post_content"> 
								<div class="post_content-inner">
									<p><?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?></p>
									<div class="overlay" style="background:<?php echo esc_attr( self::bd_hex2rgba( $settings['template_color'], 0.9 ) ); ?>;">
										<?php if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) { ?>
										<div class="read-more-class">
											<?php
											if ( '' != get_option( 'read_more_text' ) ) {
												echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
											} else {
												echo ' <a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
											}
											?>
										</div>
										<?php } ?>
									</div>
								</div>
							</div>
						<?php } ?>
					</div>
					<?php
				}
				?>
				<?php if ( 0 == $display_category || 0 == get_option( 'display_tag' ) ) { ?>
				<div class="blog_footer">
					<div class="footer_meta">
						<?php
						if ( 0 == $display_category ) {
							?>
							<div class="category-link">
								<span class="link-lable"> <i class="fas fa-folder"></i><?php esc_html_e( 'Category', 'blog-designer' ); ?>&nbsp;:&nbsp; </span>
								<?php
								$categories_list = get_the_category_list( ', ' );
								if ( $categories_list ) :
									echo wp_kses( $categories_list, $args_kses );
									$show_sep = true;
									endif;
								?>
							</div>
							<?php
						}
						if ( 0 == get_option( 'display_tag' ) ) {
							$tags_list = get_the_tag_list( '', ', ' );
							if ( $tags_list ) :
								?>
								<div class="bd-tags"><span class="link-lable"> <i class="fas fa-bookmark"></i><?php esc_html_e( 'Tag', 'blog-designer' ); ?>&nbsp;:&nbsp; </span>
								<?php
								echo wp_kses( $tags_list, $args_kses );
								$show_sep = true;
								?>
								</div>
								<?php
							endif;
						}
						?>
					</div>
				</div>
				<?php } ?>
				<div class='bd_social_share_wrap'>
					<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
						<div class="social-component">
							<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
								<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
								<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
								<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
							<?php endif; ?>
							<?php
							if ( 0 == get_option( 'pinterest_link' ) ) :
								$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
								?>
								<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Html display nicy design
	 *
	 * @param type $settings settings.
	 */
	public static function bd_nicy_template( $settings ) {
		$display_date          = get_option( 'display_date' );
		$display_author        = get_option( 'display_author' );
		$display_comment_count = get_option( 'display_comment_count' );
		$display_category      = get_option( 'display_category' );
		$args_kses             = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template nicy">
			<div class="entry-container">
				<div class="blog_header">
					<div class="pull-left">
						<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						<?php
						if ( 0 == $display_date || 0 == $display_author || 0 == $display_comment_count ) {
							?>
							<div class="metadatabox">
								<?php
								if ( 0 == $display_author || 0 == $display_date ) {
									?>
									<div class="metadata">
									<?php
									if ( 0 == $display_author ) {
										esc_html_e( 'Posted by', 'blog-designer' );
										?>
										&nbsp;<span class="post_author"><a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?></a></span>
										<?php
									}
									if ( 0 == $display_date ) {
										echo ' ';
										esc_html_e( 'on', 'blog-designer' );
										echo ' ';
										$date_format = get_option( 'date_format' );
										echo esc_attr( get_the_time( $date_format ) );
									}
									?>
									</div>
									<?php
								}
								if ( 0 == $display_comment_count ) {
									?>
									<div class="metacomments">
										<?php
										comments_popup_link( '<i class="fas fa-comment"></i>' . esc_html__( 'Leave a Comment', 'blog-designer' ), '<i class="fas fa-comment"></i>' . esc_html__( '1 comment', 'blog-designer' ), '<i class="fas fa-comment"></i>% ' . esc_html__( 'comments', 'blog-designer' ), 'comments-link', '<i class="fas fa-comment"></i>' . esc_html__( 'Comments are off', 'blog-designer' ) );
										?>
									</div>
									<?php
								}
								?>
							</div>
							<?php
						}
						?>
					</div>
					<div class="blog-header-avatar">
						<?php echo get_avatar( get_the_author_meta( 'ID' ), 70 ); ?>
					</div>
				</div>
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div>
					<?php
				}
				?>
				<?php if ( 0 == $display_category || 0 == get_option( 'display_tag' ) ) { ?>
				<div class="post-meta-cats-tags">
					<?php
					if ( 0 == $display_category ) {
						?>
						<div class="category-link">
							<span class="link-lable"> <i class="fas fa-folder"></i>&nbsp;<?php esc_html_e( 'Category', 'blog-designer' ); ?>&nbsp;:&nbsp; </span>
							<?php
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
							endif;
							?>
						</div>
						<?php
					}
					if ( 0 == get_option( 'display_tag' ) ) {
						$tags_list = get_the_tag_list( '', ', ' );
						if ( $tags_list ) :
							?>
							<div class="bd-tags"><span class="link-lable"> <i class="fas fa-bookmark"></i>&nbsp;<?php esc_html_e( 'Tag', 'blog-designer' ); ?>&nbsp;:&nbsp; </span>
							<?php
							echo wp_kses( $tags_list, $args_kses );
							$show_sep = true;
							?>
							</div>
							<?php
						endif;
					}
					?>
				</div>
				<?php } ?>
				<div class="post_content">
					<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
					<?php
					if ( 1 == get_option( 'rss_use_excerpt' ) && 1 == get_option( 'read_more_on' ) ) {
						$read_more_class = ( 1 == get_option( 'read_more_on' ) ) ? 'bd-more-tag-inline' : 'bd-more-tag';
						if ( '' != get_option( 'read_more_text' ) ) {
							echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
						} else {
							echo ' <a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
						}
					}
					?>
				</div>
			</div>
			<div class="entry-meta clearfix">
				<div class="up_arrow"></div>
				<div class="pull-left">
					<?php
					if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) {
						if ( '' != get_option( 'read_more_text' ) ) {
							echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
						} else {
							echo ' <a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
						}
					}
					?>
				</div>
				<div class="pull-right">
				<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
					<div class="social-component">
						<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
							<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
							<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
						<?php endif; ?>
						<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
							<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
						<?php endif; ?>
						<?php
						$pinterestimage = '';
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
							<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
					</div>
				<?php } ?>
				</div>
			</div>
		</div>
		<?php
	}
	/**
	 * Html display news design
	 *
	 * @param type $alter alter class.
	 */
	public static function bd_news_template( $alter ) {
		$args_kses = self::args_kses();
		?>
		<div class="blog_template bdp_blog_template news <?php echo esc_attr( $alter ); ?>">
			<?php
			$full_width_class = ' full_with_class';
			if ( has_post_thumbnail() ) {
				$full_width_class = '';
				?>
				<div class="bd-post-image">
					<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a>
				</div>
				<?php
			}
			?>
			<div class="post-content-div<?php echo esc_attr( $full_width_class ); ?>">
				<div class="bd-blog-header">
					<?php
					$display_date = get_option( 'display_date' );
					if ( 0 == $display_date ) {
						$date_format = get_option( 'date_format' );
						?>
						<p class="bd_date_cover"><span class="date"><?php echo esc_attr( get_the_time( $date_format ) ); ?></span></p><?php } ?><h2 class="title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					$display_author        = get_option( 'display_author' );
					$display_comment_count = get_option( 'display_comment_count' );
					if ( 0 == $display_author || 0 == $display_comment_count ) {
						?>
						<div class="bd-metadatabox">
							<?php
							if ( 0 == $display_author ) {
								?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php the_author(); ?>
								</a>
								<?php
							}
							if ( 0 == $display_comment_count ) {
								comments_popup_link( esc_html__( 'Leave a Comment', 'blog-designer' ), esc_html__( '1 Comment', 'blog-designer' ), '% ' . esc_html__( 'Comments', 'blog-designer' ), 'comments-link', esc_html__( 'Comments are off', 'blog-designer' ) );
							}
							?>
						</div>
					<?php } ?>
				</div>
				<div class="bd-post-content">
					<?php
					echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() );
					if ( 1 == get_option( 'rss_use_excerpt' ) && 1 == get_option( 'read_more_on' ) ) {
						if ( '' != get_option( 'read_more_text' ) ) {
							echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
						} else {
							echo '<a class="bd-more-tag-inline" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
						}
					}
					?>
				</div>
				<?php
				$display_category = get_option( 'display_category' );
				$display_tag      = get_option( 'display_tag' );
				if ( 0 == $display_category || 0 == $display_tag ) {
					?>
					<div class="post_cat_tag">
						<?php if ( 0 == $display_category ) { ?>
							<span class="bd-category-link">
								<?php
								$categories_list = get_the_category_list( ', ' );
								if ( $categories_list ) :
									echo '<i class="fas fa-bookmark"></i>';
									echo wp_kses( $categories_list, $args_kses );
									$show_sep = true;
								endif;
								?>
							</span>
							<?php
						}
						if ( 0 == $display_tag ) {
							$tags_list = get_the_tag_list( '', ', ' );
							if ( $tags_list ) :
								?>
							<span class="bd-tags"><i class="fas fa-tags"></i>&nbsp;
								<?php
								echo wp_kses( $tags_list, $args_kses );
								$show_sep = true;
								?>
							</span>
								<?php
							endif;
						}
						?>
					</div>
					<?php } ?>
				<div class="bd-post-footer">
					<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
						<div class="social-component">
							<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
								<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
								<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a><?php endif; ?>
							<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
								<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
						<?php endif; ?>																		<?php
						if ( 0 == get_option( 'pinterest_link' ) ) :
							$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
							?>
							<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
						</div>
					<?php } ?>
					<?php
					if ( 1 == get_option( 'rss_use_excerpt' ) && 2 == get_option( 'read_more_on' ) ) {
						if ( '' != get_option( 'read_more_text' ) ) {
							echo '<a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
						} else {
							echo ' <a class="bd-more-tag" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Read More', 'blog-designer' ) . '</a>';
						}
					}
					?>
				</div></div></div>
		<?php
	}
	/**
	 * Html display media-grid design
	 *
	 * @param type $settings Setting.
	 */
	public static function bd_media_grid_template( $settings ) {
		$col_class = self::bd_column_class( $settings );
		$args_kses = self::args_kses();
		?>
		<div class="blog_wrap bdp_blog_template media-grid <?php echo ( '' != $col_class ) ? esc_attr( $col_class ) : ''; ?> bdp_blog_single_post_wrapp">
			<div class="post-media">
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
					<?php
				}
				?>
				<?php
				if ( 0 == get_option( 'display_category' ) ) {
					?>
						<div class="bd-metacats">
						<?php
						$categories_list = get_the_category_list( ', ' );
						if ( $categories_list ) :
							echo wp_kses( $categories_list, $args_kses );
							$show_sep = true;
							endif;
						?>
						</div>
						<?php
				}
				?>
			</div>
			<div class="content-container">
				<div class="content-inner">
					<div class="post_summary_outer">
						<div class="blog_header">
							<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
						</div>
						<div class="post_content">
							<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
							<?php
							$display_date          = get_option( 'display_date' );
							$display_author        = get_option( 'display_author' );
							$display_comment_count = get_option( 'display_comment_count' );
							?>
						</div>
						<div class="read-more">
							<?php
							if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
								$class = 'bd-more-tag-inline';
								if ( 2 == get_option( 'read_more_on' ) ) {
									$class = 'bd-more-tag';
									echo '<div class="bd-more-next-line">';
								}
								if ( '' != get_option( 'read_more_text' ) ) {
									echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
								} else {
									echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
								}
								if ( 2 == get_option( 'read_more_on' ) ) {
									echo '</div>';
								}
							}
							?>
						</div>
					</div>
					<div class="post-meta">
						<?php
						if ( 0 == $display_author ) {
							?>
							<span class="author"><?php echo esc_html__( 'Posted by', 'blog-designer' ); ?>
								<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
							</span><br>
							<?php
						}
						if ( 0 == $display_date ) {
							$date_format = get_option( 'date_format' );
							$ar_year     = get_the_time( 'Y' );
							$ar_month    = get_the_time( 'm' );
							$ar_day      = get_the_time( 'd' );
							?>
							<span class="postdate"><?php echo esc_html__( 'On ', 'blog-designer' ); ?>
								<a class="date" href="<?php echo esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ); ?>"><?php echo esc_attr( get_the_time( 'F j, Y' ) ); ?></a>
							</span>
							<?php
						}
						if ( 0 == $display_comment_count ) {
							if ( comments_open() ) {
								?>
								<span class="post-comment">
									<i class="fas fa-comments"></i>
									<?php
									comments_popup_link( '0', '1', '%' );
									?>
								</span>  
								<?php
							}
						}
						if ( 0 == get_option( 'display_tag' ) ) {
							$tags_list = get_the_tag_list( '', ', ' );
							if ( $tags_list ) :
								?>
								<div class="bd-tags"><i class="fas fa-tags"></i>
									<?php
									echo wp_kses( $tags_list, $args_kses );
									$show_sep = true;
									?>
								</div>
								<?php
							endif;
						}
						?>
					</div>
					<div class='bd_social_share_wrap'>
						<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
							<div class="social-component">
								<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
									<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
									<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
									<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
								<?php endif; ?>
								<?php
								if ( 0 == get_option( 'pinterest_link' ) ) :
									$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
									?>
									<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
	/**
	 * Html display blog-carousel design
	 */
	public static function bd_blog_carousel_template() {
		$display_date          = get_option( 'display_date' );
		$display_author        = get_option( 'display_author' );
		$display_category      = get_option( 'display_category' );
		$display_comment_count = get_option( 'display_comment_count' );
		$args_kses             = self::args_kses();
		?>
		<li class="blog_template bdp_blog_template blog_carousel">
			<div class="bdp-post-image">
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
					<?php
				}
				?>
			</div>
			<div class="blog_header">
				<div class="post_metadata">
					<?php
					if ( 0 == $display_category ) {
						?>
						<div class="category-link">
							<?php
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo '<i class="fas fa-bookmark"></i>&nbsp;';
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
							endif;
							?>
						</div>
						<?php
					}
					?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php
					if ( 0 == $display_author || 0 == $display_date || 0 == $display_comment_count ) {
						?>
						<div class="metadatabox">
							<?php
							if ( 0 == $display_author || 0 == $display_date ) {
								if ( 0 == $display_author ) {
									?>
									<div class="mauthor">
										<span class="author">
											<i class="fas fa-user"></i>
											<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>
										</span>
									</div>
									<?php
								}
								if ( 0 == $display_date ) {
									$date_format = get_option( 'date_format' );
									?>
									<div class="post-date">
										<span class="mdate"><i class="far fa-calendar-alt"></i><?php echo esc_attr( get_the_time( $date_format ) ); ?></span>
									</div>
									<?php
								}
							}
							if ( 0 == $display_comment_count ) {
								?>
								<div class="post-comment">
									<?php
									comments_popup_link( '<i class="fas fa-comment"></i>' . esc_html__( 'Leave a Comment', 'blog-designer' ), '<i class="fas fa-comment"></i>' . esc_html__( '1 comment', 'blog-designer' ), '<i class="fas fa-comment"></i>% ' . esc_html__( 'comments', 'blog-designer' ), 'comments-link', '<i class="fas fa-comment"></i>' . esc_html__( 'Comments are off', 'blog-designer' ) );
									?>
								</div>
								<?php
							}
							?>
							<?php
							if ( 0 == get_option( 'display_tag' ) ) {
								$tags_list = get_the_tag_list( '', ', ' );
								if ( $tags_list ) :
									?>
										<div class="tags"><i class="fas fa-tags"></i>&nbsp;
										<?php
										echo wp_kses( $tags_list, $args_kses );
										$show_sep = true;
										?>
										</div>
										<?php
									endif;
							}
							?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="post_content">
					<div class="post_content-inner">
						<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
						<?php
						if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
							$class = 'bd-more-tag-inline';
							if ( 2 == get_option( 'read_more_on' ) ) {
								$class = 'bd-more-tag';
								echo '<div class="bd-more-next-line">';
							}
							if ( '' != get_option( 'read_more_text' ) ) {
								echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
							} else {
								echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
							}
							if ( 2 == get_option( 'read_more_on' ) ) {
								echo '</div>';
							}
						}
						?>
					</div>
				</div>
				<div class='bd_social_share_wrap'>
					<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
						<div class="social-component">
							<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
								<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
								<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
							<?php endif; ?>
							<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
								<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
							<?php endif; ?>
							<?php
							if ( 0 == get_option( 'pinterest_link' ) ) :
								$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
								?>
								<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
						<?php endif; ?>
						</div>
					<?php } ?>
				</div>
			</div>
		</li>
		<?php
	}
	/**
	 * Html display blog-grid-box design
	 *
	 * @param type $settings Setting.
	 */
	public static function bd_blog_grid_box_template( $settings ) {
		$display_date          = get_option( 'display_date' );
		$display_author        = get_option( 'display_author' );
		$display_category      = get_option( 'display_category' );
		$display_comment_count = get_option( 'display_comment_count' );
		$col_class             = self::bd_column_class( $settings );
		$args_kses             = self::args_kses();
		?>
		<div class="post-body-div post-body-div-right">
			<div class="post-body-div-inner">
				<?php
				if ( has_post_thumbnail() ) {
					?>
					<div class="bd-post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'full' ); ?></a></div> 
					<?php
				}
				?>
				<div class="bdp_post_content">
					<?php
					if ( 0 == get_option( 'display_category' ) ) {
						?>
							<div class="bd-metacats">
							<?php
							$categories_list = get_the_category_list( ', ' );
							if ( $categories_list ) :
								echo wp_kses( $categories_list, $args_kses );
								$show_sep = true;
								endif;
							?>
							</div>
							<?php
					}
					?>
					<h2 class="post_title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<div class="metadatabox">
						<?php
						if ( 0 == $display_author || 0 == $display_date ) {
							if ( 0 == $display_author ) {
								?>
								<div class="mauthor">
									<span class="author">
										<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><span><?php the_author(); ?></span></a>&nbsp;
									</span>
								</div>
								<?php
							}
							echo esc_html__( '/', 'blog-deisgner' );
							if ( 0 == $display_date ) {
								$date_format = get_option( 'date_format' );
								$ar_year     = get_the_time( 'Y' );
								$ar_month    = get_the_time( 'm' );
								$ar_day      = get_the_time( 'd' );
								?>
								<div class="post-date">&nbsp;
									<span class="mdate">
										<a class="date" href="<?php echo esc_url( get_day_link( $ar_year, $ar_month, $ar_day ) ); ?>"><?php echo esc_attr( get_the_time( 'F j, Y' ) ); ?></a>
									</span>
								</div>
								<?php
							}
						}
						if ( 0 == $display_comment_count || 0 == get_option( 'display_tag' ) ) {
							if ( 0 == $display_comment_count ) {
								if ( comments_open() ) {
									?>
									<span class="post-comment">
										<i class="fas fa-comments"></i>
										<?php
										comments_popup_link( '0', '1', '%' );
										?>
									</span>  
									<?php
								}
							}
							if ( 0 == get_option( 'display_tag' ) ) {
								$tags_list = get_the_tag_list( '', ', ' );
								if ( $tags_list ) :
									?>
									<div class="bd-tags"><i class="fas fa-tags"></i>
										<?php
										echo wp_kses( $tags_list, $args_kses );
										$show_sep = true;
										?>
									</div>
									<?php
								endif;
							}
						}
						?>
					</div>
					<div class="post_content">
						<div class="post_content-inner">
							<?php echo wp_kses( Blog_Designer_Lite_Public::bd_get_content( get_the_ID() ), self::args_kses() ); ?>
							<?php
							if ( 1 == get_option( 'rss_use_excerpt' ) && 0 != get_option( 'read_more_on' ) ) {
								$class = 'bd-more-tag-inline';
								if ( 2 == get_option( 'read_more_on' ) ) {
									$class = 'bd-more-tag';
									echo '<div class="bd-more-next-line">';
								}
								if ( '' != get_option( 'read_more_text' ) ) {
									echo '<a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_attr( get_option( 'read_more_text' ) ) . ' </a>';
								} else {
									echo ' <a class="' . esc_attr( $class ) . '" href="' . esc_url( get_the_permalink() ) . '">' . esc_html__( 'Continue Reading...', 'blog-designer' ) . '</a>';
								}
								if ( 2 == get_option( 'read_more_on' ) ) {
									echo '</div>';
								}
							}
							?>
						</div>
				</div>
					<div class='bd_social_share_wrap'>
						<?php if ( 0 != get_option( 'social_share' ) && ( ( 0 == get_option( 'facebook_link' ) ) || ( 0 == get_option( 'twitter_link' ) ) || ( 0 == get_option( 'linkedin_link' ) ) || ( 0 == get_option( 'pinterest_link' ) ) ) ) { ?>
							<div class="social-component">
								<?php if ( 0 == get_option( 'facebook_link' ) ) : ?>
									<a data-share="facebook" data-href="https://www.facebook.com/sharer/sharer.php" data-url="<?php the_permalink(); ?>" class="bd-facebook-share bd-social-share"><i class="fab fa-facebook-f"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'twitter_link' ) ) : ?>
									<a data-share="twitter" data-href="https://twitter.com/share" data-text="<?php the_title(); ?>" data-url="<?php the_permalink(); ?>" class="bd-twitter-share bd-social-share"><i class="fab fa-x-twitter"></i></a>
								<?php endif; ?>
								<?php if ( 0 == get_option( 'linkedin_link' ) ) : ?>
									<a data-share="linkedin" data-href="https://www.linkedin.com/shareArticle" data-url="<?php the_permalink(); ?>" class="bd-linkedin-share bd-social-share"><i class="fab fa-linkedin-in"></i></a>
								<?php endif; ?>
								<?php
								if ( 0 == get_option( 'pinterest_link' ) ) :
									$pinterestimage = wp_get_attachment_image_src( get_post_thumbnail_id(), 'full' );
									?>
									<a data-share="pinterest" data-href="https://pinterest.com/pin/create/button/" data-url="<?php the_permalink(); ?>" data-mdia="<?php echo isset( $pinterestimage[0] ) ? esc_url( $pinterestimage[0] ) : ''; ?>" data-description="<?php the_title(); ?>" class="bd-pinterest-share bd-social-share"> <i class="fab fa-pinterest-p"></i></a>
							<?php endif; ?>
							</div>
						<?php } ?>
					</div>
				</div>
			</div>
		</div>

		<?php
	}
	/**
	 * Html display ticker design
	 *
	 * @param type $settings Setting.
	 */
	public static function bd_ticker_template( $settings ) {
		?>
	<li>
		<a class="blog-ticker-anchor" href="<?php esc_attr( the_permalink() ); ?>"><?php echo esc_html( the_title() ); ?></a>
	</li>
		<?php
	}

	/**
	 * Html Keses default.
	 *
	 * @since 1.0
	 * @return array
	 */
	public static function html_kses() {
		$allowed_atts = array(
			'align'      => array(),
			'class'      => array(),
			'type'       => array(),
			'id'         => array(),
			'dir'        => array(),
			'lang'       => array(),
			'style'      => array(),
			'xml:lang'   => array(),
			'src'        => array(),
			'alt'        => array(),
			'href'       => array(),
			'rel'        => array(),
			'rev'        => array(),
			'target'     => array(),
			'novalidate' => array(),
			'type'       => array(),
			'value'      => array(),
			'name'       => array(),
			'tabindex'   => array(),
			'action'     => array(),
			'method'     => array(),
			'for'        => array(),
			'width'      => array(),
			'height'     => array(),
			'data'       => array(),
			'title'      => array(),
		);
		$allowed_tags = wp_kses_allowed_html( 'post' );
		return $allowed_tags;
	}

	/**
	 * Argument for Kses.
	 *
	 * @since    1.0.0
	 * @return  array
	 */
	public static function args_kses() {
		$args_kses = array(
			'div'    => array(
				'class'  => true,
				'id'     => true,
				'style'  => true,
				'script' => true,
			),
			'script' => array(
				'type'    => true,
				'charset' => true,
			),
			'style'  => array(
				'type' => true,
			),
			'iframe' => array(
				'src'          => true,
				'style'        => true,
				'marginwidth'  => true,
				'marginheight' => true,
				'scrolling'    => true,
				'frameborder'  => true,
			),
			'img'    => array(
				'src' => true,
			),
			'a'      => array(
				'href'   => true,
				'class'  => true,
				'script' => true,
				'rel'    => true,
				'style'  => true,
			),
			'ul'     => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'li'     => array(
				'class' => true,
				'id'    => true,
				'style' => true,
			),
			'b'      => array(),
			'br'     => array(),
			'small'  => array(),
			'span'   => array(
				'class'        => true,
				'aria-current' => true,
			),
			'input'  => array(
				'class'       => true,
				'type'        => true,
				'name'        => true,
				'value'       => true,
				'placeholder' => true,
			),
			'label'  => array(),
			'form'   => array(
				'name' => true,
				'id'   => true,
			),
			'nav'    => array(
				'class' => true,
				'role'  => true,
			),

		);
		return $args_kses;
	}

}
