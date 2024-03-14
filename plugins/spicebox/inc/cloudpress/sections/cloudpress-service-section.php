<?php
/**
 * Services section for the homepage.
 */
if ( ! function_exists( 'spiceb_cloudpress_service' ) ) :

	function spiceb_cloudpress_service() {
		$theme = wp_get_theme();
		if ('CloudPress Agency' == $theme->name){
			$service_section_class = 'services2';
		}
		elseif ('CloudPress Business' == $theme->name){
			$service_section_class = 'services3';
		}
		elseif ('CloudPress Dark' == $theme->name){
			$service_section_class = 'services4';
		}
		else {
			$service_section_class = 'services';
		}
		$home_service_section_enabled = get_theme_mod('home_service_section_enabled','on');
		$home_service_section_title = get_theme_mod('home_service_section_title',__('Etiam et Urna?','spicebox'));
		$home_service_section_discription = get_theme_mod('home_service_section_discription',__('Fusce Sed Massa','spicebox'));
		$cloudpress_service_content  = get_theme_mod( 'cloudpress_service_content', spiceb_cloudpress_get_service_default() );
		$section_is_empty = empty( $cloudpress_service_content ) && empty( $home_service_section_discription ) && empty( $home_service_section_title );
		if($home_service_section_enabled !='off')
		{
		?>
			<section class="section-module <?php echo esc_attr($service_section_class);?>" id="services">
				<div class="container">
					<?php if( ($home_service_section_title) || ($home_service_section_discription)!='' ) { ?>
					<div class="row">
						<div class="col-lg-12 col-md-12 col-xs-12">
							<div class="section-header text-center">
								<?php if ( ! empty( $home_service_section_title ) || is_customize_preview() ) : ?>
								<h5 class="section-subtitle">
								<?php echo esc_html($home_service_section_title); ?>
								</h5>
								<?php endif; ?>
								<?php if($home_service_section_discription) {?>
								<h2 class="section-title">
								<?php echo esc_html($home_service_section_discription); ?>
								</h2>
								<?php } ?>
							</div>
						</div>
					</div>
					<?php }
					spiceb_cloudpress_service_content( $cloudpress_service_content );
					?>
				</div>
			</section>
		<?php } }
endif;

function spiceb_cloudpress_service_content( $cloudpress_service_content, $is_callback = false ) {
	$theme = wp_get_theme();
		if ('CloudPress Agency' == $theme->name){
			$service_article_class='';
		}
		else{
			$service_article_class='text-center';
		}
	if ( ! $is_callback ) {
	?>
	<?php
	}
	if ( ! empty( $cloudpress_service_content ) ) :

		$allowed_html = array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		);

		$cloudpress_service_content = json_decode( $cloudpress_service_content );
		if ( ! empty( $cloudpress_service_content ) ) {
			$i = 1;
			echo '<div class="row">';
			foreach ( $cloudpress_service_content as $service_item ) :
				$icon = ! empty( $service_item->icon_value ) ?  $service_item->icon_value : '';
				$image = ! empty( $service_item->image_url ) ?  $service_item->image_url: '';
				$title = ! empty( $service_item->title ) ? $service_item->title : '';
				$text = ! empty( $service_item->text ) ?  $service_item->text : '';
				$link = ! empty( $service_item->link ) ? $service_item->link : '';
				$color = ! empty( $service_item->color ) ? $service_item->color : '';
				$choice = ! empty( $service_item->choice ) ? $service_item->choice : 'customizer_repeater_icon';
				$open_new_tab = ! empty( $service_item->open_new_tab ) ? $service_item->open_new_tab : 'no';

				?>
				<div class="col-md-4 col-sm-6 col-xs-12">
					<article class="post <?php echo $service_article_class;?>">
							<?php if($choice == 'customizer_repeater_image'){ ?>
								<?php if ( ! empty( $image ) ) : ?>
								<figure class="post-thumbnail">

									<?php if ( ! empty( $link ) ) : ?>
										<a href="<?php echo esc_url( $link ); ?>" <?php if($open_new_tab == 'yes'){ echo 'target="_blank"';}?>>
									<?php endif; ?>
									<img class="services_cols_mn_icon"
										 src="<?php echo esc_url( $image ); ?>" <?php if ( ! empty( $title ) ) : ?> alt="<?php echo esc_attr( $title ); ?>" title="<?php echo esc_attr( $title ); ?>" <?php endif; ?> />
									<?php if ( ! empty( $link ) ) : ?>
										</a>
									<?php endif; ?>
								<?php endif; ?>
								</figure>
							<?php } ?>

							<?php if($choice == 'customizer_repeater_icon'){ ?>
								<?php if ( ! empty( $icon ) ) :?>
										<figure class="post-thumbnail">
											<?php if ( ! empty( $link ) ) : ?>
											<a href="<?php echo esc_url( $link ); ?>" <?php if($open_new_tab == 'yes'){ echo 'target="_blank"';}?> >
											<?php endif; ?>

											<i class="fa <?php echo esc_html( $icon ); ?> txt-pink"></i>
											<?php if ( ! empty( $link ) ) : ?>
											</a>
											<?php endif; ?>
										</figure>
								<?php endif; ?>
							<?php } ?>

							<?php if ( ! empty( $title ) ) : ?>
								<div class="entry-header">
									<h5 class="entry-title"><?php if ( ! empty( $link ) ) : ?>
									<a href="<?php echo esc_url( $link ); ?>" <?php if($open_new_tab == 'yes'){ echo 'target="_blank"';}?> ><?php endif; ?><?php echo esc_html( $title ); ?><?php if ( ! empty( $link ) ) : ?></a>
								<?php endif; ?>
									</h5>
								</div>

							<?php endif; ?>
							<?php if ( ! empty( $link ) ) : ?>
						</a>
					<?php endif; ?>
					<?php if ( ! empty( $text ) ) : ?>

						<div class="entry-content">
							<p><?php echo wp_kses( html_entity_decode( $text ), $allowed_html ); ?></p>
						</div>
					<?php endif; ?>
				</article>
			</div>




				<?php
				if ( $i % apply_filters( 'cloudpress_service_per_row_no', 3 ) == 0 ) {
					echo '</div><!-- /.row -->';
					echo '<div class="row">';
				}
				$i++;

			endforeach;
			echo '</div>';
		}// End if().
		endif;
	if ( ! $is_callback ) {
	?>
		<?php
	}
}

/**
 * Get default values for service section.
 *
 * @since 1.1.31
 * @access public
 */
function spiceb_cloudpress_get_service_default() {

	return apply_filters(
		'cloudpress_service_content', json_encode(
			array(
				array(
				'icon_value' => 'fa-laptop',
				'title'      => esc_html__('Suspendisse Tristique', 'spicebox'),
				'text'       => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor', 'spicebox',
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
				array(
				'icon_value' => 'fa fa-cogs',
				'title'      => esc_html__('Blandit-Gravida', 'spicebox'),
				'text'       => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor', 'spicebox',
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
				array(
				'icon_value' => 'fa fa-cog',
				'title'      => esc_html__('Justo Bibendum', 'spicebox'),
				'text'       => 'Sed ut perspiciatis unde omnis iste natus error sit voluptatem amet dolore ut labore et tempor',
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
			)
		)
	);
}

if ( function_exists( 'spiceb_cloudpress_service' ) ) {
	$section_priority = apply_filters( 'cloudpress_section_priority', 3, 'spiceb_cloudpress_service' );
	add_action( 'spiceb_cloudpress_sections', 'spiceb_cloudpress_service', absint( $section_priority ) );
}
