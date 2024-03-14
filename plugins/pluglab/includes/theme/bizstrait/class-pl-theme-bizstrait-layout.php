<?php

class PL_Theme_Bizstrait_Layout {








	public function __construct() {
		 // add_action('bizstrait_social_icons', array($this, 'social_icons'), 10, 1);
	}

	public function top_header() {
		if (
			(bool) get_theme_mod( 'hide_show_top_details', '1' )
			||
			(bool) get_theme_mod( 'social_icon_enable_disable', '1' )
		) { ?>

			<div class="topbar">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-6">

							<?php
							if ( (bool) get_theme_mod( 'hide_show_top_details', '1' ) ) {

								$top_mail_icon        = get_theme_mod( 'top_mail_icon', 'fas fa-envelope' );
								$top_header_mail_text = get_theme_mod( 'top_header_mail_text', 'youremail@gmail.com' );
								/*
								* @todo: remove phone number
								*/
								$top_phone_icon        = get_theme_mod( 'top_phone_icon', 'fas fa-phone-alt' );
								$top_header_phone_text = get_theme_mod( 'top_header_phone_text', '134-566-7680' );
								?>
								<ul class="left mail-phone">
									<?php if ( $top_header_mail_text != '' ) { ?>
										<li><i class="<?php echo $top_mail_icon; ?>"></i><a href="mailto: <?php echo sanitize_email( $top_header_mail_text ); ?>"> <?php echo $top_header_mail_text; ?></a></li>
										<?php
									}

									if ( $top_header_phone_text != '' ) {
										?>
										<li><i class="<?php echo $top_phone_icon; ?>"></i><a href="tel: <?php echo sanitize_email( $top_header_phone_text ); ?>"> <?php echo $top_header_phone_text; ?></a></li>
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
									$social_icons = get_theme_mod( 'bizstrait_social_icons', pluglab_get_social_icon_default() );
									$social_icons = json_decode( $social_icons );
									if ( $social_icons != '' ) {
										foreach ( $social_icons as $social_item ) {
											$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
											$social_link = ! empty( $social_item->link ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->link, 'Header section' ) : '';
											?>
											<li><a class="btn-default" href="<?php echo esc_url( $social_link ); ?>"><i class="fab <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
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
			$sec = new PL_Theme_Bizstrait_Slider();
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
			?>

			<div class="section features" id="callout-section">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-6 col-lg-4">
							<div class="media hover_eff feature" style="background-color: #FFD229;">
								<i class="fa <?php echo $callout1_icon; ?> mr-3"></i>
								<div class="media-body">
									<h5 class="mt-0"><?php echo $callout1_title; ?></h5>
									<p><?php echo $callout1_desc; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="media hover_eff feature" style="background-color: #3689FF;">
								<i class="fa <?php echo $callout2_icon; ?> mr-3"></i>
								<div class="media-body">
									<h5 class="mt-0"><?php echo $callout2_title; ?></h5>
									<p><?php echo $callout2_desc; ?></p>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="media hover_eff feature" style="background-color: #FF2D74;">
								<i class="fa <?php echo $callout3_icon; ?> mr-3"></i>
								<div class="media-body">
									<h5 class="mt-0"><?php echo $callout3_title; ?></h5>
									<p><?php echo $callout3_desc; ?></p>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function service() {
		 $is_display_enable = get_theme_mod( 'service_display', true );

		if ( $is_display_enable ) {
			$sec = new PL_Theme_Bizstrait_Service_Section();
		}
	}

	public function portfolio() {
		$is_display_enable = get_theme_mod( 'project_display', true );

		if ( $is_display_enable ) {
			$sec = new PL_Theme_Bizstrait_Portfolio_Section();
		}
	}

	public function about() {
		$is_display_enable = get_theme_mod( 'about_display', true );

		if ( $is_display_enable ) {
			$sec = new PL_Theme_Bizstrait_About_Section();
		}
	}

	public function testimonial() {
		 $is_display_enable = get_theme_mod( 'testimonial_display', true );

		if ( $is_display_enable ) {

			switch ( get_theme_mod( 'testimonial_layout', 1 ) ) {
				case 1:
					$tesCls = 'one';
					break;
				case 2:
					$tesCls = 'two';
					break;
				case 3:
					$tesCls = 'three';
					break;

				default:
					// code...
					break;
			}

			$testimonial_content_raw = get_theme_mod( 'testimonial_repeater', testimonial_default_json() );
			$testimonial_side_img = get_theme_mod( 'testimonial_banner_img', PL_PLUGIN_URL . 'assets/images/testimonial.jpg' );
			$testimonial_content     = json_decode( $testimonial_content_raw );
			?>

			<div class="section testimonials bg-default" id="testimonial-section">
				<div class="container">
					<div class="row align-items-center">
						<div class="col-md-6">
							<div class="left">
								<img src="<?php echo $testimonial_side_img; ?>" class="img-fluid" alt="">

								<?php
								if (
									get_theme_mod( 'testimonial_tagline', __( 'Happy Customers', 'bizstrait' ) ) != ''
									&&
									get_theme_mod( 'testimonial_count', __( '12k', 'bizstrait' ) != '' )
								) :
									?>
									<div class="tooltip-box">
										<h4><?php echo get_theme_mod( 'testimonial_count', __( '12k', 'bizstrait' ) ); ?></h4>
										<span><?php echo get_theme_mod( 'testimonial_tagline', __( 'Happy Customers', 'bizstrait' ) ); ?></span>
									</div>
								<?php endif; ?>
							</div>
						</div>

						<div class="col-md-6">
							<div class="testimonial_crowsel owl-carousel">

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
									<div class="testimonial <?php echo $tesCls; ?> hover_eff">
										<div class="inner">
											<i class="fas fa-quote-right"></i>

											<div class="bottom_text mb-4 mt-4">
												<p><?php echo $testimonial_item_subtitle; ?></p>
											</div>


											<div class="media align-items-center pt-4">
												<img class="mr-3 img-author" src="<?php echo $testimonial_image; ?>" alt="Generic placeholder image">
												<div class="media-body">
													<h6><?php echo $testimonial_text; ?></h6>
													<div class="details"><?php echo $testimonial_text2; ?></div>
												</div>
											</div>
										</div>
									</div>
								<?php } ?>

							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}
	}

	public function blog() {
		$is_display_enable = get_theme_mod( 'blog_display', true );

		if ( $is_display_enable ) {
			$blog_title     = get_theme_mod( 'blog_title', __( 'Our Blog', 'pluglab' ) );
			$blog_sub_title = get_theme_mod( 'blog_sub_title', __( 'Our Latest News', 'pluglab' ) );
			// $blog_desc      = get_theme_mod( 'blog_description', __( 'Committed To Bring Objective And Non-Partisan News Reporting To Our Readers.', 'shapro' ) );
			$blog_cat = get_theme_mod( 'bizstrait_theme_blog_category', '1' );
			// $blog_cat       = get_theme_mod( 'blog_dropdown_select2_control', '1' );
			?>
			<!--section blog-->
			<div class="section bg-grey blog-home" id="blog-section">
				<div class="container">
					<div class="section-heading text-center">
						<?php echo ( $blog_title ) ? "<h3 class='sub-title'>$blog_title</h3>" : ''; ?>
						<?php echo ( $blog_sub_title ) ? "<h2 class='ititle'>$blog_sub_title</h2>" : ''; ?>

					</div>
					<div class="row">
						<?php
						if ( ! empty( $blog_title ) || ! empty( $blog_sub_title ) ) {
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
													?>
													<!-- <span class="date"><a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date() ); ?></time></a></span> -->
												</div>
												<?php
											} else {
												?>
												<!-- <span class="date no-image "><a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date() ); ?></time></a></span> -->
												<?php
											}
											?>
											<!--featured image-->


											<div class="post_content">
												<!-- <span class="right-btn"><a target="_blank" href="<?php the_permalink(); ?>"><i class="fas fa-arrow-right"></i></a></span> -->
												<a target="_blank" href="<?php the_permalink(); ?>"><span class="right-btn"><i class="fas fa-arrow-right"></i></span></a>

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

												<?php the_title( '<h4 class="entry-title"><a href="' . esc_url( get_permalink() ) . '">', '</a></h4>' ); ?>
												<!-- content -->
												<p>
													<?php
													the_content( __('Read More','pluglab') );
													?>
												</p>



												<?php
												if ( (bool) get_theme_mod( 'blog_meta_display', true ) ) {
													?>

													<div class="post_meta">

														<span class="author">
															<i class="far fa-user"></i>
															<!-- <a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo get_avatar( get_the_author_meta( 'ID' ), 32 ); ?></a> -->
															<a href="<?php echo esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ); ?>"><?php echo esc_html( get_the_author() ); ?>
															</a>
														</span>

														<span class="date">
															<i class="far fa-calendar-check"></i>
															<a href="<?php echo esc_url( get_month_link( get_post_time( 'Y' ), get_post_time( 'm' ) ) ); ?>"><time><?php echo esc_html( get_the_date() ); ?></time></a></span>




													</div>
													<?php
												}
												?>


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
								$social_icons = get_theme_mod( 'bizstrait_social_icons', pluglab_get_social_icon_default() );
								$social_icons = json_decode( $social_icons );
								if ( $social_icons != '' ) {
									foreach ( $social_icons as $social_item ) {
										$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
										$social_link = ! empty( $social_item->link ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->link, 'Header section' ) : '';
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
		$bizstrait_cards_title     = get_theme_mod( 'bizstrait_cards_title', 'Contact' );
		$bizstrait_cards_sub_title = get_theme_mod( 'bizstrait_cards_sub_title', 'Our Contacts' );
		?>
		<div class="container">
			<div class="row">

				<div class="col-md-4">

					<div class="section-heading">
						<h3 class="sub-title"><?php echo $bizstrait_cards_title; ?></h3>
						<h2 class="ititle"><?php echo $bizstrait_cards_sub_title; ?></h2>
					</div>

					<?php
					$contact_content_raw = get_theme_mod( 'bizstrait_sidebar_cards', pluglab_contact_info_default() );
					$contact_content     = json_decode( $contact_content_raw );

					$bizstrait_cf7_title     = get_theme_mod( 'bizstrait_cf7_title', 'Get In Touch' );
					$bizstrait_cf7_sub_title = get_theme_mod( 'bizstrait_cf7_sub_title', 'Quick Contact Form' );

					foreach ( $contact_content as $item ) {
						$info_title = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Contact section' ) : '';
						$text       = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Contact section' ) : '';
						$text2      = ! empty( $item->text2 ) ? apply_filters( 'translate_single_string', $item->text2, 'Contact section' ) : '';
						$icon_value = ! empty( $item->icon_value ) ? apply_filters( 'translate_single_string', $item->icon_value, 'Contact section' ) : '';

						?>
						<div class="media hover_eff feature mb-md-4">
							<i class="<?php echo $icon_value; ?> mr-4"></i>
							<div class="media-body">
								<h5 class="mt-0"><?php echo $info_title; ?></h5>
								<p><?php echo $text; ?></p>
								<p><?php echo $text2; ?></p>
							</div>
						</div>
						<?php
					}

					?>
				</div>

				<div class="col-md-8">
					<div class="contact-right">
						<div class="section-heading">
							<h3 class="sub-title"><?php echo $bizstrait_cf7_title; ?></h3>
							<h2 class="ititle"><?php echo $bizstrait_cf7_sub_title; ?></h2>
						</div>
						<?php
						$bizstrait_cf7_shortcode = get_theme_mod( 'cf7_shortcode' );
						echo do_shortcode( $bizstrait_cf7_shortcode );
						?>
					</div>
				</div>

				<!-- row end -->
			</div>
			<!-- container end -->
		</div>

		<div class="mapiframe">
			<?php
			$contact_tmpl_google_map = get_theme_mod( 'contact_tmpl_google_map' );
			echo do_shortcode( $contact_tmpl_google_map );
			?>
		</div>

		<?php
	}

	public function portfolio_template() {
		PL_Theme_Bizstrait_Portfolio_Section::instance();
	}
}
