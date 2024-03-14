<?php

class PL_Theme_Corposet_Layout {


	public function __construct() {
		// add_action('corposet_social_icons', array($this, 'social_icons'), 10, 1);
	}

	public function top_header($width='container') {
		if (
			(bool) get_theme_mod( 'hide_show_top_details', '1' )
			||
			(bool) get_theme_mod( 'social_icon_enable_disable', '1' )
		) { ?>

			<div class="topbar">
			<div class="<?php echo (!$width) ? 'container' : $width; ?>">
					<div class="row align-items-center">
						<div class="col-md-6">

							<?php
							if ( (bool) get_theme_mod( 'hide_show_top_details', '1' ) ) {

								$top_mail_icon        = get_theme_mod( 'top_mail_icon', 'fa-send-o' );
								$top_header_mail_text = get_theme_mod( 'top_header_mail_text', 'youremail@gmail.com' );
								/*
								* @todo: remove phone number
								*/
								$top_phone_icon        = get_theme_mod( 'top_phone_icon', 'fa-phone' );
								$top_header_phone_text = get_theme_mod( 'top_header_phone_text', '134-566-7680' );
								?>
								<ul class="left mail-phone">
									<?php if ( $top_header_mail_text != '' ) { ?>
										<li><i class="fa <?php echo $top_mail_icon; ?>"></i><a href="mailto: <?php echo sanitize_email( $top_header_mail_text ); ?>"> <?php echo $top_header_mail_text; ?></a></li>
										<?php
									}

									if ( $top_header_phone_text != '' ) {
										?>
										<li><i class="fa <?php echo $top_phone_icon; ?>"></i><a href="tel: <?php echo sanitize_email( $top_header_phone_text ); ?>"> <?php echo $top_header_phone_text; ?></a></li>
									<?php } ?>
								</ul>
								<?php
							}
							?>
						</div>

						<div class="col-md-6">
							<?php if ( (bool) get_theme_mod( 'social_icon_enable_disable', 1 ) ) { ?>
								<ul class="social right">
									<?php
									$social_icons = get_theme_mod( 'corposet_social_icons', pluglab_get_social_icon_default() );
									$social_icons = json_decode( $social_icons );
									if ( $social_icons != '' ) {
										foreach ( $social_icons as $social_item ) {
											$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'corposet_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
											$social_link = ! empty( $social_item->link ) ? apply_filters( 'corposet_translate_single_string', $social_item->link, 'Header section' ) : '';
											?>
											<li><a class="btn-default" href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
											<?php
										}
									}
									?>

								</ul>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			  <?php
		}
	}

	public function slider() {
		$is_display_enable = get_theme_mod( 'slider_display', true );
		if ( $is_display_enable ) {

			$slider_content_raw = get_theme_mod( 'slider_repeater', slider_default_json() );
			$slider_content     = json_decode( $slider_content_raw );
			?>
			<div class="sliderhome owl-carousel owl-theme" id="slider-section">
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
									<div class="owl-slide-text">
										<h2 class="owl-slide-animated owl-slide-title"><?php echo $slider_title; ?></h2>
										<div class="owl-slide-animated owl-slide-subtitle mb-3">
											<?php echo $slider_subtitle; ?>
										</div>
										<a class="btn btn-default owl-slide-animated owl-slide-cta" href="<?php echo $slider_link1; ?>" <?php echo $newtab; ?> role="button"><?php echo $slider_button1; ?></a>
										<a class="btn btn-white owl-slide-animated owl-slide-cta" href="<?php echo $slider_link2; ?>" <?php echo $newtab; ?> role="button"><?php echo $slider_button2; ?></a>

									</div>
								</div>
							</div>
						</div>
					</div>
					<!--/owl-slide-->

					<?php
				}
				?>
			</div>
			<?php
		}
	}

	public function callout() {
		 $is_display_enable = get_theme_mod( 'callout_display', true );
		if ( $is_display_enable ) {
			$callout1_icon  = get_theme_mod( 'callout1_icon', 'fa-bullseye' );
			$callout1_title = get_theme_mod( 'callout1_title', 'Strategy' );
			$callout1_desc  = get_theme_mod( 'callout1_desc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' );
			
			$callout2_icon  = get_theme_mod( 'callout2_icon', 'fa-rocket' );
			$callout2_title = get_theme_mod( 'callout2_title', 'Start Ups' );
			$callout2_desc  = get_theme_mod( 'callout2_desc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' );
			
			$callout3_icon  = get_theme_mod( 'callout3_icon', 'fa-comments' );
			$callout3_title = get_theme_mod( 'callout3_title', 'Organisations' );
			$callout3_desc  = get_theme_mod( 'callout3_desc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' );
			
			$callout4_icon  = get_theme_mod( 'callout4_icon', 'fa-line-chart' );
			$callout4_title = get_theme_mod( 'callout4_title', 'Market Analysis' );
			$callout4_desc  = get_theme_mod( 'callout4_desc', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit.' );

			$no_of_callouts  = get_theme_mod( 'callout_2visible', '3' );
			$row='col-lg-4 col-md-6 col-sm-6';

			switch ($no_of_callouts) {
				case '1':
					$row='col-lg-12 col-md-12 col-sm-12';
					break;
				case '2':
					$row='col-lg-6 col-md-6 col-sm-6';
					break;
				case '3':
					
					break;
				case '4':
					$row='col-lg-6 col-md-6 col-sm-6';
					break;
				
				default:
					# code...
					break;
			}
			$corposet_callout_section_width = get_theme_mod('corposet_callout_width', 'container');
			?>

			<div class="<?php echo $corposet_callout_section_width; ?> section features  tp-80 mb-40" id="callout-section">
				<div class="row align-items-center">

				<?php for ($i=1; $i <= $no_of_callouts; $i++) { 
					 $icon = 'callout' . $i.'_icon';
					 $title = 'callout' . $i.'_title';
					 $desc = 'callout' . $i.'_desc';
					 $active =($i==1 && $no_of_callouts>1) ? 'active' : '';
					?>
					<div class="<?php echo $row; ?>">
						<div class="media hover_eff feature <?php echo $active; ?>">
							<i class="fa <?php echo $$icon; ?> mr-3"></i>
							<div class="media-body">
								<h5 class="mt-0"><?php echo $$title; ?></h5>
								<p><?php echo $$desc; ?></p>
							</div>
						</div>
					</div>
					<?php
				} ?>

				</div>
			</div>
			<?php
		}
	}

	public function service() {
		$is_display_enable = get_theme_mod( 'service_display', true );

		if ( $is_display_enable ) {
			$okd = new PL_Theme_Corposet_Service_Section();
		}
	}

	public function portfolio() {
		$is_display_enable = get_theme_mod( 'portfolio_display', true );

		if ( $is_display_enable ) {
			$okd = new PL_Theme_Corposet_Portfolio_Section();
		}
	}

	public function about() {
		$is_display_enable = get_theme_mod( 'about_display', true );

		if ( $is_display_enable ) {
			$okd = new PL_Theme_Corposet_About_Section();
		}
	}

	public function testimonial() {
		 $is_display_enable = get_theme_mod( 'testimonial_display', true );

		if ( $is_display_enable ) {
			$testimonial_title     = get_theme_mod( 'testimonial_title', __( 'Testimonial', 'pluglab' ) );
			$testimonial_sub_title = get_theme_mod( 'testimonial_sub_title', __( 'Our achievement', 'pluglab' ) );
			$testimonial_desc      = get_theme_mod( 'testimonial_description', 'Business we operate in is like an intricate' );

			$testimonial_content_raw = get_theme_mod( 'testimonial_repeater', testimonial_default_json() );
			$testimonial_content     = json_decode( $testimonial_content_raw );
			?>

			<section class="section testimonials" id="testimonial-section">
				<div class="container">
					<div class="section-heading text-center">
						<h3 class="sub-title"><?php echo $testimonial_title; ?></h3>
						<h2 class="ititle"><?php echo $testimonial_sub_title; ?></h2>
						<p><?php echo $testimonial_desc; ?></p>
					</div>
					<!-- <div class="row"> -->
					<div class="testimonial_crowsel row">

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
							<div class="col-md-6">
								<div class="testimonial hover_eff">
									<div class="inner">
										<div class="media">
											<img class="mr-3 img-author" src="<?php echo $testimonial_image; ?>" alt="image">
											<div class="media-body">
												<h6><?php echo $testimonial_text; ?></h6>
												<div class="details"><?php echo $testimonial_text2; ?></div>
											</div>
											<i class="fa fa-quote-right"></i>
										</div>
										<div class="bottom_text mb-0">
											<p><?php echo $testimonial_item_subtitle; ?></p>
										</div>
									</div>
								</div>
							</div>
						<?php } ?>

					</div>
					<!-- </div> -->
				</div>
			</section>
			<?php
		}
	}

	public function blog() {
		$is_display_enable = get_theme_mod( 'blog_display', true );

		if ( $is_display_enable ) {
			$blog_title     = get_theme_mod( 'blog_title', __( 'Today Times', 'pluglab' ) );
			$blog_sub_title = get_theme_mod( 'blog_sub_title', __( 'Our Latest News', 'pluglab' ) );
			$blog_desc      = get_theme_mod( 'blog_description', __( 'Committed To Bring Objective And Non-Partisan News Reporting To Our Readers.', 'shapro' ) );
			$blog_cat       = get_theme_mod( 'corposet_theme_blog_category', '1' );
			?>
			<!--section blog-->
			<div class="section bg-grey blog-home" id="blog-section">
				<div class="container">
					<div class="section-heading text-center">
						<?php echo ( $blog_title ) ? "<h3 class='sub-title'>$blog_title</h3>" : ''; ?>
						<?php echo ( $blog_sub_title ) ? "<h2 class='ititle'>$blog_sub_title</h2>" : ''; ?>
						<?php echo ( $blog_desc ) ? "<p>$blog_desc</p>" : ''; ?>
					</div>
					<div class="row">
						<?php
						if ( ! empty( $blog_title ) || ! empty( $blog_sub_title ) || ! empty( $blog_desc ) ) {
						}

						$post_args = array(
							'post_type'      => 'post',
							'posts_per_page' => '3',
							'category__in'   => (array) explode( ',', $blog_cat ),
							// 'category__in'   => explode( ',', $blog_cat ),
							'cat'            => $blog_cat,
							'post__not_in'   => get_option( 'sticky_posts' ),
						);
						query_posts( $post_args );
						if ( query_posts( $post_args ) ) {

							while ( have_posts() ) :
								the_post(); {
								?>
									<div class="col-md-6 col-lg-4  ">
										<div class="blog_post hover_eff mb-4 bg-white">

											<!--featured image-->
											<?php if ( has_post_thumbnail() ) { ?>
												<div class="post_img img_eff">
													<?php
													/**
													 * Image
													 */
													$img_class = array( 'class' => 'img-fluid' );
													the_post_thumbnail( '', $img_class );

													if ( (bool) get_theme_mod( 'blog_meta_display', true ) ) {
														?>
														<span class="date"><a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date() ); ?></time></a></span>
													<?php } ?>
												</div>
												<?php
											} else {
												if ( (bool) get_theme_mod( 'blog_meta_display', true ) ) {
													?>
													<span class="date no-image "><a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date() ); ?></time></a></span>
													<?php
												}
											}
											?>
											<!--featured image-->


											<div class="post_content">
												<?php
												if ( (bool) get_theme_mod( 'blog_meta_display', true ) ) {
													?>

													<div class="post_meta df">

														<span class="author">
															<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?></a>
															<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?>
															</a>
														</span>

														<span class="comment-links"><a href="<?php the_permalink(); ?>#respond"><?php echo get_comments_number(); ?></a></span>
														<!--blog categories-->
														<?php
														/**
														 * @todo Even can add this with settins display off
														 */
														$category_data = get_the_category_list();
														if ( ! empty( $category_data ) ) {
															?>
															<span class="categories"><a href="<?php the_permalink(); ?>"><?php the_category( ', ' ); ?></a></span>
														<?php } ?>
														<!--blog categories-->

													</div>
													<?php
												}
												?>
												<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h4>' ); ?>
												<p>
													<?php
													/*
													* function defined in corposet
													*/
													if ( function_exists( 'corposet_ExcerptOrContent' ) ) {
														corposet_ExcerptOrContent();
													}
													?>
												</p>
											</div>
										</div>
									</div>
								<?php
								}
							endwhile;
						}
						wp_reset_query();
						?>
					</div>
				</div>
			</div>
			<!--/section-->
			<?php
		}
	}

	/*
	 public function social_icons($class)
	{
		//topheader
		?>
		<ul class="<?php echo $class; ?>">
							<?php
								$social_icons = get_theme_mod( 'corposet_social_icons', pluglab_get_social_icon_default() );
								$social_icons = json_decode( $social_icons );
								if ( $social_icons != '' ) {
									foreach ( $social_icons as $social_item ) {
										$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'corposet_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
										$social_link = ! empty( $social_item->link ) ? apply_filters( 'corposet_translate_single_string', $social_item->link, 'Header section' ) : '';
										?>
										<li><a class="btn-default" href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
										<?php
									}
								}
							?>

						</ul>
		<?php
	} */

	public function contactus_template() {
		?>
		<div class="row">

			<!-- <div class="col-md-4"> -->

			<?php
			$contact_content_raw = get_theme_mod( 'corposet_sidebar_cards', pluglab_contact_info_default() );
			$contact_content     = json_decode( $contact_content_raw );

			foreach ( $contact_content as $item ) {
				$info_title    = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Contact section' ) : '';
				$info_subtitle = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Contact section' ) : '';
				$icon_value    = ! empty( $item->icon_value ) ? apply_filters( 'translate_single_string', $item->icon_value, 'Contact section' ) : '';

				?>
				<div class="col-md-4">
					<div class="media hover_eff feature">
						<i class="fa <?php echo $icon_value; ?> mr-3"></i>
						<div class="media-body">
							<h5 class="mt-0"><?php echo $info_title; ?></h5>
							<p><?php echo $info_subtitle; ?></p>
						</div>
					</div>
				</div>
				<?php

			}

			?>



		</div>


		<div class="row mt-5 mb-5">
			<div class="col-md-6">
				<div class="mapiframe">
					<!-- <div style="width: 100%"><iframe width="100%" height="300" src="https://maps.google.com/maps?width=100%&amp;height=300&amp;hl=en&amp;q=1240%20Park%20Avenue%20NYC%2C%20USA%20+(My%20Business%20Name)&amp;ie=UTF8&amp;t=&amp;z=14&amp;iwloc=B&amp;output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a href="https://www.maps.ie/map-my-route/">Draw map route</a></iframe></div></div> -->
					<?php
					$contact_tmpl_google_map = get_theme_mod( 'contact_tmpl_google_map' );
					echo do_shortcode( $contact_tmpl_google_map );
					?>
				</div>
			</div>

			<div class="col-md-6">
				<?php $corposet_cf7_title = get_theme_mod( 'corposet_cf7_title', 'Contact Form' ); ?>
				<h5 class="mb-3"> <?php echo $corposet_cf7_title; ?></h5>
				<?php
				$corposet_cf7_shortcode = get_theme_mod( 'cf7_shortcode' );
				echo do_shortcode( $corposet_cf7_shortcode );
				?>
			</div>

		</div>
		<?php
	}

	public function portfolio_template() {
		PL_Theme_Corposet_Portfolio_Section::instance();
	}
}
