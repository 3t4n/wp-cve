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
class Blog_Designer_Lite_Public {
	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.8.2
	 */
	public function __construct() {
		add_action( 'wp_head', array( $this, 'bd_ajaxurl' ), 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'bd_front_stylesheet' ) );
		add_filter( 'excerpt_length', array( $this, 'bd_excerpt_length' ), 999 );
		add_shortcode( 'wp_blog_designer', array( $this, 'bd_views' ) );
		add_shortcode( 'wp_blog_designer_ticker', array( $this, 'blog_designer_ticker_view' ) );
		add_action( 'wp_ajax_nopriv_get_loadmore_blog', array( &$this, 'bd_load_onscroll_blog' ), 12 );
		add_action( 'wp_ajax_get_loadmore_blog', array( &$this, 'bd_load_onscroll_blog' ), 12 );

	}
	/**
	 * Enqueue front side required css
	 */
	public function bd_front_stylesheet() {
		$fontawesomeicon = BLOGDESIGNER_DIR . '/admin/css/fontawesome-all.min.css';
		if ( file_exists( $fontawesomeicon ) ) {
			wp_register_style( 'wp-blog-designer-fontawesome-stylesheets', BLOGDESIGNER_URL . '/admin/css/fontawesome-all.min.css', null, '1.0' );
			wp_enqueue_style( 'wp-blog-designer-fontawesome-stylesheets' );
		}
		$designer_css = BLOGDESIGNER_DIR . '/public/css/designer_css.css';
		if ( file_exists( $designer_css ) ) {
			wp_register_style( 'wp-blog-designer-css-stylesheets', plugins_url( 'css/designer_css.css', __FILE__ ), null, '1.0' );
			wp_enqueue_style( 'wp-blog-designer-css-stylesheets' );
		}
		if ( is_rtl() ) {
			wp_register_style( 'wp-blog-designer-rtl-css-stylesheets', plugins_url( 'admin/css/designerrtl_css.css', __FILE__ ), null, '1.0' );
			wp_enqueue_style( 'wp-blog-designer-rtl-css-stylesheets' );
		}
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'masonry' );
		wp_enqueue_script( 'ticker', plugins_url( 'js/ticker.min.js', __FILE__ ), array( 'jquery' ), '1.0', false );
		wp_enqueue_script( 'wp-blog-designer-script', plugins_url( 'js/designer.js', __FILE__ ), array( 'masonry' ), '1.0', false );

		$settings = get_option( 'wp_blog_designer_settings' );
		if ( isset( $settings['template_name'] ) && ( 'crayon_slider' === $settings['template_name'] || 'sallet_slider' === $settings['template_name'] || 'blog-carousel' === $settings['template_name'] ) ) {
			$bd_gallery_slider = dirname( __FILE__ ) . '/css/flexslider.css';
			if ( file_exists( $bd_gallery_slider ) ) {
				wp_enqueue_style( 'bd-galleryslider-stylesheets', plugins_url( 'css/flexslider.css', __FILE__ ), null, '1.0' );
			}
			wp_enqueue_script( 'bd-galleryimage-script', plugins_url( 'js/jquery.flexslider-min.js', __FILE__ ), null, '1.0', false );
		}
	}
	/**
	 * Ajax URL
	 */
	public function bd_ajaxurl() {
		?>
		<script type="text/javascript">
			var ajaxurl = '<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>';
		</script>
		<?php
	}
	/**
	 * Change content length
	 *
	 * @param type $length length.
	 */
	public function bd_excerpt_length( $length ) {
		if ( '' != get_option( 'excerpt_length' ) ) {
			return get_option( 'excerpt_length' );
		} else {
			return 50;
		}
	}
	/**
	 * Isnclude plugin dynamic css
	 */
	public function bd_stylesheet() {
		if ( ! is_admin() ) {
			$stylesheet = BLOGDESIGNER_DIR . 'public/designer-css.php';
			if ( file_exists( $stylesheet ) ) {
				include BLOGDESIGNER_DIR . 'public/designer-css.php';
			}
		}
		if ( ! is_admin() && is_rtl() ) {
			$stylesheet = BLOGDESIGNER_DIR . 'public/designerrtl-css.php';
			if ( file_exists( $stylesheet ) ) {
				include BLOGDESIGNER_DIR . 'public/designerrtl-css.php';
			}
		}
	}
	/**
	 * Return Blog posts
	 */
	public function bd_views() {
		ob_start();
		self::bd_stylesheet();
		add_filter( 'excerpt_more', array( $this, 'bd_remove_continue_reading' ), 50 );
		$settings = get_option( 'wp_blog_designer_settings' );
		if ( ! isset( $settings['template_name'] ) || empty( $settings['template_name'] ) ) {
			$link_message = '';
			if ( is_user_logged_in() ) {
				$link_message = esc_html__( 'please go to ', 'blog-designer' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=designer_settings' ) ) . '" target="_blank">' . esc_html__( 'Blog Designer Panel', 'blog-designer' ) . '</a> , ' . esc_html__( 'select Blog Designs & save settings.', 'blog-designer' );
			}
			return esc_html__( "You haven't created any blog designer shortcode.", 'blog-designer' ) . ' ' . $link_message;
		}
		$theme    = $settings['template_name'];
		$author   = array();
		$cat      = array();
		$tag      = array();
		$category = '';
		if ( isset( $settings['template_category'] ) ) {
			$cat = $settings['template_category'];
		}
		if ( ! empty( $cat ) ) {
			foreach ( $cat as $cat_ojb ) :
				$category .= $cat_ojb . ',';
			endforeach;
			$cat = rtrim( $category, ',' );
		} else {
			$cat = array();
		}
		if ( isset( $settings['template_tags'] ) ) {
			$tag = $settings['template_tags'];
		}
		if ( empty( $tag ) ) {
			$tag = array();
		}
		$tax_query = array();
		if ( ! empty( $cat ) && ! empty( $tag ) ) {
			$cat       = explode( ',', $cat );
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => 'IN',
				),
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => 'IN',
				),
			);
		} elseif ( ! empty( $tag ) ) {
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => 'IN',
				),
			);
		} elseif ( ! empty( $cat ) ) {
			$cat       = explode( ',', $cat );
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => 'IN',
				),
			);
		}
		if ( isset( $settings['template_authors'] ) && '' != $settings['template_authors'] ) {
			$author = $settings['template_authors'];
			$author = implode( ',', $author );
		}
		$posts_per_page = get_option( 'posts_per_page' );
		$paged          = Blog_Designer_Lite_Admin::bd_paged();
		$order          = 'DESC';
		$orderby        = 'date';
		if ( isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
			$orderby = $settings['bdp_blog_order_by'];
		}
		if ( isset( $settings['bdp_blog_order'] ) && isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
			$order = $settings['bdp_blog_order'];
		}
		$args           = array(
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'tax_query'      => $tax_query,
			'author'         => $author,
			'orderby'        => $orderby,
			'order'          => $order,
		);
		$display_sticky = get_option( 'display_sticky' );
		if ( '' != $display_sticky && 1 == $display_sticky ) {
			$args['ignore_sticky_posts'] = 1;
		}

		global $wp_query;
		$temp_query           = $wp_query;
		$loop                 = new WP_Query( $args );
		$wp_query             = $loop;
		$max_num_pages        = $wp_query->max_num_pages;
		$i                    = 1;
		$alter                = 1;
		$class                = '';
		$alter_class          = '';
		$args_kses            = Blog_Designer_Lite_Template::args_kses();
		$main_container_class = isset( $settings['main_container_class'] ) && '' != $settings['main_container_class'] ? esc_attr( $settings['main_container_class'] ) : '';
		$pagination_type      = isset( $settings['pagination_type'] ) && '' != $settings['pagination_type'] ? esc_attr( $settings['pagination_type'] ) : 'paged';
		if ( $loop->have_posts() ) {
			echo '<div class="bdp_wrapper">';
			if ( '' != $main_container_class ) {
				echo '<div class="' . esc_attr( $main_container_class ) . '">';
			}
			if ( $max_num_pages > 1 && 'load_more_btn' === $pagination_type ) {
				echo "<div class='bdp-load-more-pre'>";
			}
			if ( 'timeline' === $theme ) {
				?>
				<div class="timeline_bg_wrap">
					<div class="timeline_back clearfix">
						<?php
			}
			if ( 'boxy-clean' === $theme ) {
				?>
						<div class="blog_template boxy-clean">
							<ul>
						<?php
			}
			if ( 'glossary' === $theme ) {
				?>
								<div class="bdp_post_list glossary_cover">
									<div class="blog_template masonry bdp_glossary">
						<?php
			}
			if ( 'nicy' === $theme ) {
				?>
										<div class="bdp_post_list nicy_cover">
					<?php
			}
			if ( 'crayon_slider' === $theme || 'sallet_slider' === $theme || 'blog-carousel' === $theme ) {
				$slider_navigation         = '';
				$template_slider_scroll    = isset( $settings['template_slider_scroll'] ) ? $settings['template_slider_scroll'] : 1;
				$display_slider_navigation = isset( $settings['display_slider_navigation'] ) ? $settings['display_slider_navigation'] : 1;
				$display_slider_controls   = isset( $settings['display_slider_controls'] ) ? $settings['display_slider_controls'] : 1;
				$slider_autoplay           = isset( $settings['slider_autoplay'] ) ? $settings['slider_autoplay'] : 1;
				$slider_autoplay_intervals = isset( $settings['slider_autoplay_intervals'] ) ? $settings['slider_autoplay_intervals'] : 7000;
				$slider_speed              = isset( $settings['slider_speed'] ) ? $settings['slider_speed'] : 600;
				$template_slider_effect    = isset( $settings['template_slider_effect'] ) ? $settings['template_slider_effect'] : 'slide';
				if ( is_rtl() ) {
					$template_slider_effect = 'fade';
				}
				$slider_column = 1;
				if ( isset( $settings['template_slider_effect'] ) && 'slide' === $settings['template_slider_effect'] ) {
					$slider_column        = isset( $settings['template_slider_columns'] ) ? $settings['template_slider_columns'] : 1;
					$slider_column_ipad   = isset( $settings['template_slider_columns_ipad'] ) ? $settings['template_slider_columns_ipad'] : 1;
					$slider_column_tablet = isset( $settings['template_slider_columns_tablet'] ) ? $settings['template_slider_columns_tablet'] : 1;
					$slider_column_mobile = isset( $settings['template_slider_columns_mobile'] ) ? $settings['template_slider_columns_mobile'] : 1;
				} else {
					$slider_column        = 1;
					$slider_column_ipad   = 1;
					$slider_column_tablet = 1;
					$slider_column_mobile = 1;
				}
				$slider_arrow = isset( $settings['arrow_style_hidden'] ) ? $settings['arrow_style_hidden'] : 'arrow1';
				if ( '' == $slider_arrow ) {
					$prev = "<i class='fas fa-chevron-left'></i>";
					$next = "<i class='fas fa-chevron-right'></i>";
				} else {
					$prev = "<div class='" . $slider_arrow . "'></div>";
					$next = "<div class='" . $slider_arrow . "'></div>";
				}
				?>
				<script type="text/javascript" id="flexslider_script">
					jQuery(document).ready(function () {
						var $maxItems = 1;
						if (jQuery(window).width() > 980) {
							$maxItems = <?php echo esc_attr( $slider_column ); ?>;
						}
						if (jQuery(window).width() > 720 && jQuery(window).width() <= 980) {
							$maxItems = <?php echo esc_attr( $slider_column_ipad ); ?>;
						}
						if (jQuery(window).width() > 480 && jQuery(window).width() <= 720) {
							$maxItems = <?php echo esc_attr( $slider_column_tablet ); ?>;
						}
						if (jQuery(window).width() <= 480) {
							$maxItems = <?php echo esc_attr( $slider_column_mobile ); ?>;
						}
						jQuery('.slider_template').flexslider({
							move: <?php echo esc_attr( $template_slider_scroll ); ?>,
							animation: '<?php echo esc_attr( $template_slider_effect ); ?>',
							itemWidth:10,itemMargin:15,minItems:1,maxItems:$maxItems,
							<?php echo ( 1 == $display_slider_controls ) ? 'directionNav: true,' : 'directionNav: false,'; ?>
							<?php echo ( 1 == $display_slider_navigation ) ? 'controlNav: true,' : 'controlNav: false,'; ?>
							<?php echo ( 1 == $slider_autoplay ) ? 'slideshow: true,' : 'slideshow: false,'; ?>
							<?php echo ( 1 == $slider_autoplay ) ? 'slideshowSpeed:' . esc_attr( $slider_autoplay_intervals ) . ',' : ''; ?>
							<?php echo ( esc_attr( $slider_speed ) ) ? 'animationSpeed:' . esc_attr( $slider_speed ) . ',' : ''; ?>
							prevText: "<?php echo $prev; ?>",
							nextText: "<?php echo $next; ?>",
							rtl: 
							<?php
							if ( is_rtl() ) {
								echo 1;
							} else {
								echo 0;
							}
							?>
						});
					});
				</script>
				<div class="blog_template slider_template <?php echo esc_attr( $theme ); ?> navigation3 <?php echo esc_attr( $slider_navigation ); ?>">
					<?php if ( 'crayon_slider' == $theme && 'design2' == $settings['slider_design_type'] ) { ?>
						<ul class="slides design2">
					<?php } else { ?>
						<ul class="slides">
						<?php
					}
			}
			if ( 'media-grid' === $theme ) {
				?>
				<div class="media-grid-wrapper">
					<?php
			}
			if ( 'blog-grid-box' === $theme ) {
				?>
				<div class="blog_template blog-grid-box">
					<?php
			}
			if ( 'ticker' === $theme ) {
				$ticker_label = ( isset( $settings['ticker_label'] ) && '' != $settings['ticker_label'] ) ? $settings['ticker_label'] : esc_html__( 'Latest Blog', 'blog-designer' );
				?>
				<div class="blog-ticker-wrapper" id="blog-ticker-style-1" data-conf="{&quot;ticker_effect&quot;:&quot;fade&quot;,&quot;autoplay&quot;:&quot;true&quot;,&quot;speed&quot;:3000,&quot;font_style&quot;:&quot;normal&quot;,&quot;scroll_speed&quot;:1}">
					<div class="ticker-title">
						<div class="ticker-style-title"><?php echo esc_attr( $ticker_label ); ?></div>
						<span></span>
					</div>
					<div class="blog-ticker-controls">
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-prev"></span></div>
						<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-next"></span></div>
					</div>
					<div class="blog-tickers">
						<ul>
				<?php
			}
			while ( $loop->have_posts() ) :
				$loop->the_post();
				if ( 'classical' === $theme ) {
					$class = ' classical';
					Blog_Designer_Lite_Template::bd_classical_template( $alter_class );
				} elseif ( 'boxy-clean' === $theme ) {
					$class = ' boxy-clean';
					Blog_Designer_Lite_Template::bd_boxy_clean_template( $settings );
				} elseif ( 'crayon_slider' === $theme ) {
					$class = ' crayon_slider';
					Blog_Designer_Lite_Template::bd_crayon_slider_template( $settings );
				} elseif ( 'sallet_slider' === $theme ) {
					$class = 'sallet_slider';
					Blog_Designer_Lite_Template::bd_sallet_slider_template( $settings );
				} elseif ( 'nicy' === $theme ) {
					$class = ' nicy';
					Blog_Designer_Lite_Template::bd_nicy_template( $settings );
				} elseif ( 'glossary' === $theme ) {
					$class = ' glossary';
					Blog_Designer_Lite_Template::bd_glossary_template( $settings );
				} elseif ( 'lightbreeze' === $theme ) {
					if ( 0 == get_option( 'template_alternativebackground' ) ) {
						if ( 0 == $alter % 2 ) {
							$alter_class = ' alternative-back ';
						} else {
							$alter_class = ' ';
						}
					}
					$class = ' lightbreeze';
					Blog_Designer_Lite_Template::bd_lightbreeze_template( $alter_class );
					$alter ++;
				} elseif ( 'spektrum' === $theme ) {
					$class = ' spektrum';
					Blog_Designer_Lite_Template::bd_spektrum_template();
				} elseif ( 'evolution' === $theme ) {
					if ( 0 == get_option( 'template_alternativebackground' ) ) {
						if ( 0 == $alter % 2 ) {
							$alter_class = ' alternative-back ';
						} else {
							$alter_class = ' ';
						}
					}
					$class = ' evolution';
					Blog_Designer_Lite_Template::bd_evolution_template( $alter_class );
					$alter ++;
				} elseif ( 'timeline' === $theme ) {
					if ( 0 == $alter % 2 ) {
						$alter_class = ' even';
					} else {
						$alter_class = ' odd';
					}
					$class = 'timeline';
					$this_year = get_the_date( 'Y' );
					echo '<div class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $this_year ) . '</span></span></div>';
					Blog_Designer_Lite_Template::bd_timeline_template( $alter_class );
					$alter ++;
				} elseif ( 'news' === $theme ) {
					if ( 0 == get_option( 'template_alternativebackground' ) ) {
						if ( 0 == $alter % 2 ) {
							$alter_class = ' alternative-back';
						} else {
							$alter_class = ' ';
						}
					}
					$class = ' news';
					Blog_Designer_Lite_Template::bd_news_template( $alter_class );
					$alter ++;
				} elseif ( 'media-grid' === $theme ) {
					$class = ' media-grid';
					Blog_Designer_Lite_Template::bd_media_grid_template( $settings );
				} elseif ( 'blog-carousel' === $theme ) {
					$class = ' blog_carousel';
					Blog_Designer_Lite_Template::bd_blog_carousel_template( $settings );
				} elseif ( 'blog-grid-box' === $theme ) {
					$class = ' blog-grid-box';
					Blog_Designer_Lite_Template::bd_blog_grid_box_template( $settings );
				} elseif ( 'ticker' === $theme ) {
					$class = ' ticker';
					Blog_Designer_Lite_Template::bd_ticker_template( $settings );
				}
				echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $settings, $i, $theme, $paged ), $args_kses );
				$i++;
			endwhile;
			if ( 'timeline' === $theme ) {
				?>
											</div>
										</div>
				<?php
			}
			if ( 'glossary' === $theme ) {
				?>
									</div>
								</div>
				<?php
			}
			if ( 'nicy' === $theme ) {
				?>
							</div>
				<?php
			}
			if ( 'boxy-clean' === $theme ) {
				?>
						</ul>
					</div>
				<?php
			}
			if ( 'crayon_slider' === $theme || 'sallet_slider' === $theme || 'blog-carousel' === $theme ) {
				?>
				</ul>
				</div>
				<?php
			}
			if ( 'media-grid' === $theme ) {
				?>
				</div>
				<?php
			}
			if ( 'blog-grid-box' === $theme ) {
				?>
				</div>
				<?php
			}
			if ( 'ticker' === $theme ) {
				?>
						</ul>
					</div>
				</div>
				<?php
			}
			if ( 'crayon_slider' !== $theme && 'sallet_slider' !== $theme && 'blog-carousel' !== $theme && 'ticker' !== $theme && 'paged' === $pagination_type ) {
				echo '<div class="wl_pagination_box bd_pagination_box ' . esc_attr( $class ) . '">';
				echo wp_kses( self::bd_pagination(), $args_kses );
				echo '</div>';
			}
			if ( $max_num_pages > 1 && 'load_more_btn' === $pagination_type ) {
				echo '</div>';
			}
			if ( $max_num_pages > 1 && 'load_more_btn' === $pagination_type && 'crayon_slider' !== $theme && 'sallet_slider' !== $theme && 'blog-carousel' !== $theme && 'ticker' !== $theme ) {
				$is_loadmore_btn = '';
				if ( $max_num_pages > 1 ) {
					$is_loadmore_btn = '';
				} else {
					$is_loadmore_btn = '1';
				}
				$template  = '';
				$template .= '<form name="bdp-load-more-hidden" id="bdp-load-more-hidden">';
				$template .= '<input type="hidden" name="paged" id="paged" value="' . $paged . '" />';
				$template .= '<input type="hidden" name="posts_per_page" id="posts_per_page" value="' . $posts_per_page . '" />';
				$template .= '<input type="hidden" name="max_num_pages" id="max_num_pages" value="' . $max_num_pages . '" />';
				$template .= '<input type="hidden" name="blog_template" id="blog_template" value="' . $theme . '" />';
				$template .= wp_nonce_field( 'blog_nonce_front', 'front_nonce' );
				$template .= '<input type="hidden" name="blog_layout" id="blog_layout" value="blog_layout" />';

				$template .= '<div style="display: none" class="loading-image" ><div class="bdp-circularG-wrapper"><div class="bdp-circularG bdp-circularG_1"></div><div class="bdp-circularG bdp-circularG_2"></div><div class="bdp-circularG bdp-circularG_3"></div><div class="bdp-circularG bdp-circularG_4"></div><div class="bdp-circularG bdp-circularG_5"></div><div class="bdp-circularG bdp-circularG_6"></div><div class="bdp-circularG bdp-circularG_7"></div><div class="bdp-circularG bdp-circularG_8"></div></div></div>';
				$template .= '</form>';
				if ( '' == $is_loadmore_btn ) {
					$template .= '<div class="bdp-load-more text-center" style="float:left;width:100%">';
					$template .= '<a href="javascript:void(0)" class="button bdp-load-more-btn template-1">';

					$template .= ( isset( $settings['loadmore_button_text'] ) && '' != $settings['loadmore_button_text'] ) ? $settings['loadmore_button_text'] : esc_html__( 'Load More', 'blog-designer' );

					$template .= '</a>';
					$template .= '</div>';
				}
				echo $template;
			}
			if ( '' != $main_container_class ) {
				echo '</div>';
			}
			echo '</div>';
		}

		wp_reset_postdata();
		$wp_query = null;
		$wp_query = $temp_query;
		$content  = ob_get_clean();
		return $content;
	}

	/**
	 * Return Blog posts
	 */
	public function blog_designer_ticker_view() {
		ob_start();
		$author   = array();
		$cat      = array();
		$tag      = array();
		$category = '';
		$settings = get_option( 'wp_blog_news_ticker' );
		if ( isset( $settings['template_category'] ) ) {
			$cat = $settings['template_category'];
		}
		if ( ! empty( $cat ) ) {
			foreach ( $cat as $cat_ojb ) :
				$category .= $cat_ojb . ',';
			endforeach;
			$cat = rtrim( $category, ',' );
		} else {
			$cat = array();
		}
		if ( isset( $settings['template_tags'] ) ) {
			$tag = $settings['template_tags'];
		}
		if ( empty( $tag ) ) {
			$tag = array();
		}
		$tax_query = array();
		if ( ! empty( $cat ) && ! empty( $tag ) ) {
			$cat       = explode( ',', $cat );
			$tax_query = array(
				'relation' => 'OR',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => 'IN',
				),
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => 'IN',
				),
			);
		} elseif ( ! empty( $tag ) ) {
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'post_tag',
					'field'    => 'term_id',
					'terms'    => $tag,
					'operator' => 'IN',
				),
			);
		} elseif ( ! empty( $cat ) ) {
			$cat       = explode( ',', $cat );
			$tax_query = array(
				'relation' => 'AND',
				array(
					'taxonomy' => 'category',
					'field'    => 'term_id',
					'terms'    => $cat,
					'operator' => 'IN',
				),
			);
		}
		if ( isset( $settings['template_authors'] ) && '' != $settings['template_authors'] ) {
			$author = $settings['template_authors'];
			$author = implode( ',', $author );
		}
		$posts_per_page = get_option( 'posts_per_page' );
		$paged          = Blog_Designer_Lite_Admin::bd_paged();
		$order          = 'DESC';
		$orderby        = 'date';
		if ( isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
			$orderby = $settings['bdp_blog_order_by'];
		}
		if ( isset( $settings['bdp_blog_order'] ) && isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
			$order = $settings['bdp_blog_order'];
		}
		$args              = array(
			'posts_per_page' => $posts_per_page,
			'paged'          => $paged,
			'tax_query'      => $tax_query,
			'author'         => $author,
			'orderby'        => $orderby,
			'order'          => $order,
		);
		$news_ticker_label = ( isset( $settings['news_ticker_label'] ) && '' != $settings['news_ticker_label'] ) ? $settings['news_ticker_label'] : esc_html__( 'Latest Blog', 'blog-designer' );

		global $wp_query;
		$temp_query = $wp_query;
		$loop       = new WP_Query( $args );
		$wp_query   = $loop;
		$wp_query   = null;
		$wp_query   = $temp_query;
		if ( $loop->have_posts() ) {
			?>
			<div class="blog-ticker-wrapper" id="blog-ticker-style-1" data-conf="{&quot;ticker_effect&quot;:&quot;fade&quot;,&quot;autoplay&quot;:&quot;true&quot;,&quot;speed&quot;:3000,&quot;font_style&quot;:&quot;normal&quot;,&quot;scroll_speed&quot;:1}">
				<div class="ticker-title">
					<div class="ticker-style-title"><?php echo esc_attr( $news_ticker_label ); ?></div>
					<span></span>
				</div>
				<div class="blog-ticker-controls">
					<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-prev"></span></div>
					<div class="blog-ticker-arrows"><span class="blog-ticker-arrow blog-ticker-arrow-next"></span></div>
				</div>
				<div class="blog-tickers">
					<ul>
					<?php
					while ( $loop->have_posts() ) :
						$loop->the_post();
						?>
							<li>
								<a class="blog-ticker-anchor" href="#"><?php echo esc_html( get_the_title() ); ?></a>

							</li>
							<?php
					endwhile;
					?>
					</ul>
				</div>
			</div>
			<?php
		}
		$templatecolor       = ( isset( $settings['template_color'] ) && '' != $settings['template_color'] ) ? $settings['template_color'] : '#2096cd';
		$template_text_color = ( isset( $settings['template_text_color'] ) && '' != $settings['template_text_color'] ) ? $settings['template_text_color'] : '#fff';
		$template_titlecolor = ( isset( $settings['template_titlecolor'] ) && '' != $settings['template_titlecolor'] ) ? $settings['template_titlecolor'] : '#fff';

		?>
		<style type="text/css">
			#blog-ticker-style-1 {
				border-color: <?php echo esc_attr( $templatecolor ); ?>;
			}
			#blog-ticker-style-1 .ticker-title {
				background-color: <?php echo esc_attr( $templatecolor ); ?>;
			}
			#blog-ticker-style-1 .ticker-style-title {
				color: <?php echo esc_attr( $template_text_color ); ?>;
			}
			#blog-ticker-style-1 .ticker-title>span {
				border-color: transparent transparent transparent <?php echo esc_attr( $templatecolor ); ?>;
			}
			#blog-ticker-style-1 .ticker-title>span {
				width: 0;
				position: absolute;
				right: -10px;
				top: 0;
				height: 0;
				border-style: solid;
				border-width: 10px 0 10px 10px;
				bottom: 0;
				border-color: transparent transparent transparent <?php echo esc_attr( $templatecolor ); ?>;
				margin: auto;
			}
			#blog-ticker-style-1 .blog-tickers a:hover {
				color: <?php echo esc_attr( $templatecolor ); ?>;
			}
			#blog-ticker-style-1 .blog-tickers a {
				color: <?php echo esc_attr( $template_titlecolor ); ?>;
			}
			#blog-ticker-style-1 .blog-ticker-controls .blog-ticker-arrows {
				background-color: #f6f6f6;
				border-color: #999999;
			}
			#blog-ticker-style-1 .blog-ticker-controls .blog-ticker-arrows:hover {
				background-color: #eeeeee;
			}
		</style>
		<?php

		$wp_query = null;
		$wp_query = $temp_query;
		$content  = ob_get_clean();
		return $content;
	}
	/**
	 * Remove read more
	 *
	 * @param type $more more.
	 */
	public function bd_remove_continue_reading( $more ) {
		return '';
	}
	/**
	 * Display Pagination
	 *
	 * @param array $args args.
	 */
	public function bd_pagination( $args = array() ) {
		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}
		$navigation   = '';
		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );
		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}
		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';
		$format       = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format      .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';
		// Set up paginated links.
		$links = paginate_links(
			array(
				'base'      => $pagenum_link,
				'format'    => $format,
				'total'     => $GLOBALS['wp_query']->max_num_pages,
				'current'   => $paged,
				'mid_size'  => 1,
				'add_args'  => array_map( 'urlencode', $query_args ),
				'prev_text' => '&larr; ' . esc_html__( 'Previous', 'blog-designer' ),
				'next_text' => esc_html__( 'Next', 'blog-designer' ) . ' &rarr;',
				'type'      => 'list',
			)
		);
		if ( $links ) :
			$navigation .= '<nav class="navigation paging-navigation" role="navigation">';
			$navigation .= $links;
			$navigation .= '</nav>';
		endif;
		return $navigation;
	}
	/**
	 * Get content in posts
	 *
	 * @param type $postid postid.
	 */
	public static function bd_get_content( $postid ) {
		global $post;
		$content           = '';
		$excerpt_length    = get_option( 'excerpt_length' );
		$display_html_tags = get_option( 'display_html_tags', true );
		if ( 0 == get_option( 'rss_use_excerpt' ) ) {
			$content = apply_filters( 'the_content', get_the_content( $postid ) );
			return wp_strip_all_tags( $content );
		} elseif ( get_option( 'excerpt_length' ) > 0 ) {
			if ( 1 == $display_html_tags ) {
				$text = get_the_content( $postid );
				if ( 0 == strpos( _x( 'words', 'Word count type. Do not translate!', 'blog-designer' ), 'characters' ) && preg_match( '/^utf\-?8$/i', get_option( 'blog_charset' ) ) ) {
					$text = trim( preg_replace( "/[\n\r\t ]+/", ' ', $text ), ' ' );
					preg_match_all( '/./u', $text, $words_array );
					$words_array = array_slice( $words_array[0], 0, $excerpt_length + 1 );
					$sep         = '';
				} else {
					$words_array = preg_split( "/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY );
					$sep         = ' ';
				}
				if ( count( $words_array ) > $excerpt_length ) {
					array_pop( $words_array );
					$text            = implode( $sep, $words_array );
					$bp_excerpt_data = $text;
				} else {
					$bp_excerpt_data = implode( $sep, $words_array );
				}
				$first_letter = $bp_excerpt_data;
				if ( preg_match( '#(>|]|^)(([A-Z]|[a-z]|[0-9]|[\p{L}])(.*\R)*(\R)*.*)#m', $first_letter, $matches ) ) {
					$top_content             = str_replace( $matches[2], '', $first_letter );
					$content_change          = ltrim( $matches[2] );
					$bp_content_first_letter = mb_substr( $content_change, 0, 1 );
					if ( ' ' == mb_substr( $content_change, 1, 1 ) ) {
						$bp_remaining_letter = ' ' . mb_substr( $content_change, 2 );
					} else {
						$bp_remaining_letter = mb_substr( $content_change, 1 );
					}
					$spanned_first_letter = '<span class="bp-first-letter">' . $bp_content_first_letter . '</span>';
					$bottom_content       = $spanned_first_letter . $bp_remaining_letter;
					$bp_excerpt_data      = $top_content . $bottom_content;
					$bp_excerpt_data      = self::bp_close_tags( $bp_excerpt_data );
				}
				$content = apply_filters( 'the_content', $bp_excerpt_data );
				return html_entity_decode( $content );
			} else {
				$text            = $post->post_content;
				$text            = str_replace( '<!--more-->', '', $text );
				$text            = apply_filters( 'the_content', $text );
				$text            = str_replace( ']]>', ']]&gt;', $text );
				$bp_excerpt_data = wp_trim_words( $text, $excerpt_length, '' );
				$bp_excerpt_data = apply_filters( 'wp_bd_excerpt_change', $bp_excerpt_data, $postid );
				$content         = $bp_excerpt_data;
				return $content;
			}
		}
	}
	/**
	 * Get html close tag
	 *
	 * @param string $html html.
	 */
	public static function bp_close_tags( $html = '' ) {
		if ( '' == $html ) {
			return;
		}
		// put all opened tags into an array.
		preg_match_all( '#<([a-z]+)( .*)?(?!/)>#iU', $html, $result );
		$openedtags = $result[1];
		// put all closed tags into an array.
		preg_match_all( '#</([a-z]+)>#iU', $html, $result );
		$closedtags = $result[1];
		$len_opened = count( $openedtags );
		// all tags are closed.
		if ( count( $closedtags ) == $len_opened ) {
			return $html;
		}
		$openedtags = array_reverse( $openedtags );
		// close tags.
		for ( $i = 0; $i < $len_opened; $i++ ) {
			if ( ! in_array( $openedtags[ $i ], $closedtags ) ) {
				$html .= '</' . $openedtags[ $i ] . '>';
			} else {
				unset( $closedtags[ array_search( $openedtags[ $i ], $closedtags ) ] );
			}
		}
		return $html;
	}
	/**
	 * Load Onscroll Page.
	 */
	public function bd_load_onscroll_blog() {
		global $wpdb;
		ob_start();
		if ( ( isset( $_POST['front_nonce'] ) && wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['front_nonce'] ) ), 'blog_nonce_front' ) ) ) {
			$layout = isset( $_POST['blog_layout'] ) ? sanitize_text_field( wp_unslash( $_POST['blog_layout'] ) ) : '';
			self::bd_stylesheet();
			add_filter( 'excerpt_more', array( $this, 'bd_remove_continue_reading' ), 50 );
			$settings = get_option( 'wp_blog_designer_settings' );
			if ( ! isset( $settings['template_name'] ) || empty( $settings['template_name'] ) ) {
				$link_message = '';
				if ( is_user_logged_in() ) {
					$link_message = esc_html__( 'please go to ', 'blog-designer' ) . '<a href="' . esc_url( admin_url( 'admin.php?page=designer_settings' ) ) . '" target="_blank">' . esc_html__( 'Blog Designer Panel', 'blog-designer' ) . '</a> , ' . esc_html__( 'select Blog Designs & save settings.', 'blog-designer' );
				}
				return esc_html__( "You haven't created any blog designer shortcode.", 'blog-designer' ) . ' ' . $link_message;
			}
			$theme    = $settings['template_name'];
			$author   = array();
			$cat      = array();
			$tag      = array();
			$category = '';
			if ( isset( $settings['template_category'] ) ) {
				$cat = $settings['template_category'];
			}
			if ( ! empty( $cat ) ) {
				foreach ( $cat as $cat_ojb ) :
					$category .= $cat_ojb . ',';
				endforeach;
				$cat = rtrim( $category, ',' );
			} else {
				$cat = array();
			}
			if ( isset( $settings['template_tags'] ) ) {
				$tag = $settings['template_tags'];
			}
			if ( empty( $tag ) ) {
				$tag = array();
			}
			$tax_query = array();
			if ( ! empty( $cat ) && ! empty( $tag ) ) {
				$cat       = explode( ',', $cat );
				$tax_query = array(
					'relation' => 'OR',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $cat,
						'operator' => 'IN',
					),
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $tag,
						'operator' => 'IN',
					),
				);
			} elseif ( ! empty( $tag ) ) {
				$tax_query = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'post_tag',
						'field'    => 'term_id',
						'terms'    => $tag,
						'operator' => 'IN',
					),
				);
			} elseif ( ! empty( $cat ) ) {
				$cat       = explode( ',', $cat );
				$tax_query = array(
					'relation' => 'AND',
					array(
						'taxonomy' => 'category',
						'field'    => 'term_id',
						'terms'    => $cat,
						'operator' => 'IN',
					),
				);
			}
			if ( isset( $settings['template_authors'] ) && '' != $settings['template_authors'] ) {
				$author = $settings['template_authors'];
				$author = implode( ',', $author );
			}
			$posts_per_page = get_option( 'posts_per_page' );
			$paged          = isset( $_POST['paged'] ) ? ( (int) $_POST['paged'] ) + 1 : '';
			$order          = 'DESC';
			$orderby        = 'date';
			if ( isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
				$orderby = $settings['bdp_blog_order_by'];
			}
			if ( isset( $settings['bdp_blog_order'] ) && isset( $settings['bdp_blog_order_by'] ) && '' != $settings['bdp_blog_order_by'] ) {
				$order = $settings['bdp_blog_order'];
			}
			$args           = array(
				'posts_per_page' => $posts_per_page,
				'paged'          => $paged,
				'tax_query'      => $tax_query,
				'author'         => $author,
				'orderby'        => $orderby,
				'order'          => $order,
			);
			$display_sticky = get_option( 'display_sticky' );
			if ( '' != $display_sticky && 1 == $display_sticky ) {
				$args['ignore_sticky_posts'] = 1;
			}

			global $wp_query;
			$temp_query           = $wp_query;
			$loop                 = new WP_Query( $args );
			$wp_query             = $loop;
			$max_num_pages        = $wp_query->max_num_pages;
			$i                    = 1;
			$alter                = 1;
			$class                = '';
			$alter_class          = '';
			$args_kses            = Blog_Designer_Lite_Template::args_kses();
			$main_container_class = isset( $settings['main_container_class'] ) && '' != $settings['main_container_class'] ? esc_attr( $settings['main_container_class'] ) : '';
			$pagination_type      = isset( $settings['pagination_type'] ) && '' != $settings['pagination_type'] ? esc_attr( $settings['pagination_type'] ) : 'paged';
			if ( $loop->have_posts() ) {
				while ( $loop->have_posts() ) :
					$loop->the_post();
					if ( 'classical' === $theme ) {
						$class = ' classical';
						Blog_Designer_Lite_Template::bd_classical_template( $alter_class );
					} elseif ( 'boxy-clean' === $theme ) {
						$class = ' boxy-clean';
						Blog_Designer_Lite_Template::bd_boxy_clean_template( $settings );
					} elseif ( 'nicy' === $theme ) {
						$class = ' nicy';
						Blog_Designer_Lite_Template::bd_nicy_template( $settings );
					} elseif ( 'glossary' === $theme ) {
						$class = ' glossary';
						Blog_Designer_Lite_Template::bd_glossary_template( $settings );
					} elseif ( 'lightbreeze' === $theme ) {
						if ( 0 == get_option( 'template_alternativebackground' ) ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = ' alternative-back ';
							} else {
								$alter_class = ' ';
							}
						}
						$class = ' lightbreeze';
						Blog_Designer_Lite_Template::bd_lightbreeze_template( $alter_class );
						$alter ++;
					} elseif ( 'spektrum' === $theme ) {
						$class = ' spektrum';
						Blog_Designer_Lite_Template::bd_spektrum_template();
					} elseif ( 'evolution' === $theme ) {
						if ( 0 == get_option( 'template_alternativebackground' ) ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = ' alternative-back ';
							} else {
								$alter_class = ' ';
							}
						}
						$class = ' evolution';
						Blog_Designer_Lite_Template::bd_evolution_template( $alter_class );
						$alter ++;
					} elseif ( 'timeline' === $theme ) {
						if ( 0 == $alter % 2 ) {
							$alter_class = ' even';
						} else {
							$alter_class = ' odd';
						}
						$class = 'timeline';
						$this_year = get_the_date( 'Y' );
						echo '<div class="timeline_year"><span class="year_wrap"><span class="only_year">' . esc_html( $this_year ) . '</span></span></div>';
						Blog_Designer_Lite_Template::bd_timeline_template( $alter_class );
						$alter ++;
					} elseif ( 'news' === $theme ) {
						if ( 0 == get_option( 'template_alternativebackground' ) ) {
							if ( 0 == $alter % 2 ) {
								$alter_class = ' alternative-back';
							} else {
								$alter_class = ' ';
							}
						}
						$class = ' news';
						Blog_Designer_Lite_Template::bd_news_template( $alter_class );
						$alter ++;
					} elseif ( 'media-grid' === $theme ) {
						$class = ' media-grid';
						Blog_Designer_Lite_Template::bd_media_grid_template( $settings );
					} elseif ( 'blog-grid-box' === $theme ) {
						$class = ' blog-grid-box';
						Blog_Designer_Lite_Template::bd_blog_grid_box_template( $settings );
					} elseif ( 'ticker' === $theme ) {
						$class = ' ticker';
						Blog_Designer_Lite_Template::bd_ticker_template( $settings );
					}
					echo wp_kses( apply_filters( 'bdads_do_show_ads', '', $settings, $i, $theme, $paged ), $args_kses );
					$i++;
				endwhile;
			}

			wp_reset_postdata();
			$data = ob_get_clean();
			echo $data;
		}
		die();
	}
}
new Blog_Designer_Lite_Public();
