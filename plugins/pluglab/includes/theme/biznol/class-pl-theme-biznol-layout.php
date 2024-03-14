<?php

class PL_Theme_Biznol_Layout {

	public function top_header() {
		?>

		<div class="topbar">
			<div class="container">
				<div class="row align-items-center">
					<?php
					$hide_show_top_details = get_theme_mod( 'hide_show_top_details', '1' );
					if ( $hide_show_top_details == '1' ) {

						$top_mail_icon        = get_theme_mod( 'top_mail_icon', 'fa-envelope' );
						$top_header_mail_text = get_theme_mod( 'top_header_mail_text', 'Email: youremail@gmail.com' );
						?>
						<div class="col-md-6">
							<ul class="left">

								<li><a href="mailto: <?php echo sanitize_email( $top_header_mail_text ); ?>"><i class="fa <?php echo $top_mail_icon; ?>"></i> <?php echo $top_header_mail_text; ?></a></li>
							</ul>
						</div>
						<?php
					} $social_icon_enable_disable = get_theme_mod( 'social_icon_enable_disable', '1' );
					?>
					<div class="col-md-6">
						<ul class="right">
							<?php
							$social_icons = get_theme_mod( 'biznol_social_icons', pluglab_get_social_icon_default() );
							if ( $social_icon_enable_disable == '1' ) {
								$social_icons = json_decode( $social_icons );
								if ( $social_icons != '' ) {
									foreach ( $social_icons as $social_item ) {
										$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'biznol_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
										$social_link = ! empty( $social_item->link ) ? apply_filters( 'biznol_translate_single_string', $social_item->link, 'Header section' ) : '';
										?>
										<li><a href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
										<?php
									}
								}
							}
							?>

						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php
	}

	public function slider() {

		$is_display_enable = get_theme_mod( 'slider_display', true );
		if ( $is_display_enable ) {

			$slider_content_raw = get_theme_mod( 'slider_repeater', slider_default_json() );
			$slider_content     = json_decode( $slider_content_raw );
			?>
			<div class="sliderhome owl-carousel owl-theme wow fadeInUpBig" data-wow-delay="0ms" data-wow-duration="1500ms">
			<?php
			foreach ( $slider_content as $item ) {
				// print_r($item);die;
				$slider_image     = ! empty( $item->image_url ) ? apply_filters( 'translate_single_string', $item->image_url, 'Slider section' ) : '';
				$slider_button1   = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Slider section' ) : '';
				$slider_button2   = ! empty( $item->text2 ) ? apply_filters( 'translate_single_string', $item->text2, 'Slider section' ) : '';
				$slider_title     = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Slider section' ) : '';
				$slider_subtitle  = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Slider section' ) : '';
				$slider_link1     = ! empty( $item->link ) ? apply_filters( 'translate_single_string', $item->link, 'Slider section' ) : '';
				$slider_link2     = ! empty( $item->link2 ) ? apply_filters( 'translate_single_string', $item->link2, 'Slider section' ) : '';
				$content_position = ! empty( $item->content_position ) ? apply_filters( 'translate_single_string', $item->content_position, 'Slider section' ) : '';
				$newtab           = ( (bool) $item->newtab ) ? 'target=_blank' : 'target=_self';

				switch ( $content_position ) {
					case 'customizer_repeater_content_left':
						$position_class = 'justify-content-md-start';
						break;
					case 'customizer_repeater_content_center':
						$position_class = 'justify-content-md-center text-center';
						break;
					case 'customizer_repeater_content_right':
						$position_class = 'justify-content-md-end';
						break;
					default:
						$position_class = 'justify-content-md-start';
						break;
				}
				?>

					<!--slider-->

					<div class="slide d-flex align-items-center cover" style="background-image: url(<?php echo $slider_image; ?> );">
						<div class="container">
							<div class="row justify-content-center justify-content-md-start <?php echo $position_class; ?>">
								<div class="col-10 col-md-6 static">
									<div class="owl-slide-text wow fadeInLeft">
										<h2><?php echo $slider_title; ?></h2>
										<p>
								<?php echo $slider_subtitle; ?>
										</p>
										<a href="<?php echo $slider_link1; ?>" <?php echo $newtab; ?> class="btn btn-swipe"><span><?php echo $slider_button1; ?></span></a>
										<!--<a class="btn btn-white owl-slide-animated owl-slide-cta" href="<?php echo $slider_link2; ?>" <?php echo $newtab; ?> role="button"><?php echo $slider_button2; ?></a>-->
									</div>
								</div>
							</div>
						</div>
					</div><!--/owl-slide-->

				<?php
			}
			?>
				</div>
				<?php
		}
	}

	public function service() {

		$is_display_enable = get_theme_mod( 'service_display', true );

		if ( $is_display_enable ) {
			$okd = new PL_Theme_Biznol_Service_Section();
		}
	}

	public function testimonial() {
		$is_display_enable = get_theme_mod( 'testimonial_display', true );

		if ( $is_display_enable ) {
			$testimonial_title     = get_theme_mod( 'testimonial_title', __( 'Testimonial', 'pluglab' ) );
			$testimonial_sub_title = get_theme_mod( 'testimonial_sub_title', __( 'Our achievement', 'pluglab' ) );

			$testimonial_content_raw = get_theme_mod( 'testimonial_repeater', testimonial_default_json() );
			$testimonial_content     = json_decode( $testimonial_content_raw );
			?>

			<section class="Testimonials wow fadeInUpBig" data-wow-delay="0ms" data-wow-duration="1500ms">
				<div class="container">
					<div class="row">
						<div class="col-md-12">
							<div class="section-heading text-center">
								<h3 class="sub-title"><?php echo $testimonial_title; ?></h3>
								<h2 class="ititle"><?php echo $testimonial_sub_title; ?></h2>
							</div>
						</div>
					</div>
					<div class="owl-Testimonial owl-carousel owl-theme">

						<?php
						foreach ( $testimonial_content as $item ) {
							// print_r($item);

							/*
							 * @todo: remove
							 */
							$testimonial_item_subtitle = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Testimonial section' ) : '';
							$testimonial_choice        = ! empty( $item->choice ) ? apply_filters( 'translate_single_string', $item->choice, 'Testimonial section' ) : '';
							$testimonial_image         = ! empty( $item->image_url ) ? apply_filters( 'translate_single_string', $item->image_url, 'Testimonial section' ) : '';
							$testimonial_icon_value    = ! empty( $item->icon_value ) ? apply_filters( 'translate_single_string', $item->icon_value, 'Testimonial section' ) : '';
							$testimonial_text          = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Testimonial section' ) : '';
							$testimonial_text2         = ! empty( $item->text2 ) ? apply_filters( 'translate_single_string', $item->text2, 'Testimonial section' ) : '';
							// $newtab = ((bool) $item->newtab ) ? 'target=_blank' : 'target=_self';
							?>
							<div class="testimonial">
								<div class="quote-icon">
									<i class="fa fa-quote-right"></i>
								</div>


								<div class="discription">
									<p><?php echo $testimonial_item_subtitle; ?></p>
									<div class="star-icon">
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
										<i class="fa fa-star"></i>
									</div>
								</div>

								<div class="author">
									<img src="<?php echo $testimonial_image; ?>" alt="author">
									<div class="names">
										<h3><?php echo $testimonial_text; ?></h3>
										<p><?php echo $testimonial_text2; ?></p>
									</div>
								</div>


							</div>
						<?php } ?>

					</div>
				</div>
			</section>
			<?php
		}
	}

	public function blog() {
		$is_display_enable = get_theme_mod( 'blog_display', true );

		if ( $is_display_enable ) {
			$blog_title     = get_theme_mod( 'blog_title', __( 'Times Today', 'pluglab' ) );
			$blog_sub_title = get_theme_mod( 'blog_sub_title', __( 'Avantage Blog Posts', 'pluglab' ) );
			$blog_cat       = get_theme_mod( 'biznol_theme_blog_category', 1 );
			?>
			<!--section blog-->
			<section class="latest_news space-module wow fadeInUpBig" data-wow-delay="0ms" data-wow-duration="1500ms" style="background: #eee;">
				<div class="container">
					<?php
					if ( ! empty( $blog_title ) || ! empty( $blog_sub_title ) || ! empty( $blog_desc ) ) {
						;}
					?>
					<div class="section-heading text-center">
						<?php echo ( $blog_title ) ? "<h3 class='sub-title'>$blog_title</h3>" : ''; ?>
						<?php echo ( $blog_sub_title ) ? "<h2 class='ititle'>$blog_sub_title</h2>" : ''; ?>
					</div>

					<?php
					$post_args = array(
						'post_type'      => 'post',
						'posts_per_page' => '3',
						'category__in'   => explode( ',', $blog_cat ),
						'post__not_in'   => get_option( 'sticky_posts' ),
					);
					query_posts( $post_args );
					if ( query_posts( $post_args ) ) {
						echo '<div class="row">'; // parent row of posts
						while ( have_posts() ) :
							the_post(); {
							?>
								<div class="col-md-4">
									<article class="post">

										<!--featured image-->
										<?php if ( has_post_thumbnail() ) : ?>
											<div class="post_img">
												<?php $img_class = array( 'class' => 'img-fluid' ); ?>
												<?php the_post_thumbnail( '', $img_class ); ?>
											</div>
										<?php endif; ?>
										<!--featured image-->


										<div class="post_content">
											<?php
											if ( (bool) get_theme_mod( 'blog_meta_display', true ) ) {
												?>
												<div class="post_meta">

													<span class="date"><a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date(__('M d, Y')) ); ?></time></a></span>

													<span class="author">
														<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?>
														</a>
													</span>

													<span class="comment-links"><a href="<?php the_permalink(); ?>#respond"><?php echo get_comments_number() . '&nbsp;' . __( 'Comment', 'biznol' ); ?></a></span>
													<!--blog categories-->
													<?php
													/**
													 * @todo Even can add this with settins display off
													 */
													// $category_data = get_the_category_list();
													// if (!empty($category_data)) {
													?>
														<!--<span class="categories"><a href="<?php // the_permalink(); ?>"><?php // the_category(', '); ?></a></span>-->
													<?php // } ?>
													<!--blog categories-->

												</div>
												<?php
											}
											?>
											<h4><?php the_title(); ?></h4>
											<p>
											<?php
											/*
											* function defined in biznol
											*/
											if ( function_exists( 'biznol_ExcerptOrContent' ) ) {
												biznol_ExcerptOrContent();
											}
											?>
											</p>
										</div>
									</article>
								</div>
								<?php
								}
						endwhile;
						echo '</div>'; // row end
					}
					?>
				</div>
			</section>
			<!--/section-->
			<?php
		}
	}

}
