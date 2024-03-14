<?php
/**
 * funfacts section for the homepage.
 */
if ( ! function_exists( 'spiceb_cloudpress_funfact' ) ) :

	function spiceb_cloudpress_funfact() {

		$home_funfact_section_enabled = get_theme_mod('home_funfact_section_enabled','on');
		$cloudpress_funfact_content  = get_theme_mod( 'cloudpress_funfact_content', spiceb_cloudpress_get_funfact_default() );
		if($home_funfact_section_enabled !='off')
		{	
		?>
			<section class="section-module funfact bg-default" id="funfacts">
				<div class="container">		
					<?php  				
					spiceb_cloudpress_funfact_content( $cloudpress_funfact_content );
					?>
				</div>
			</section>
		<?php } }
endif;

function spiceb_cloudpress_funfact_content( $cloudpress_funfact_content, $is_callback = false ) {
	if ( ! $is_callback ) {
	?>
	<?php
	}
	if ( ! empty( $cloudpress_funfact_content ) ) :

		$allowed_html = array(
		'br'     => array(),
		'em'     => array(),
		'strong' => array(),
		'b'      => array(),
		'i'      => array(),
		);
		
		$cloudpress_funfact_content = json_decode( $cloudpress_funfact_content );
		if ( ! empty( $cloudpress_funfact_content ) ) {
			$i = 1;
			echo '<div class="row">';
			foreach ( $cloudpress_funfact_content as $funfact_item ) :
				$icon = ! empty( $funfact_item->icon_value ) ?  $funfact_item->icon_value : '';
				$image = ! empty( $funfact_item->image_url ) ?  $funfact_item->image_url: '';
				$title = ! empty( $funfact_item->title ) ? $funfact_item->title : '';
				$text = ! empty( $funfact_item->text ) ?  $funfact_item->text : '';
				$link = ! empty( $funfact_item->link ) ? $funfact_item->link : '';
				$color = ! empty( $funfact_item->color ) ? $funfact_item->color : '';
				$choice = ! empty( $funfact_item->choice ) ? $funfact_item->choice : 'customizer_repeater_icon';
				$open_new_tab = ! empty( $funfact_item->open_new_tab ) ? $funfact_item->open_new_tab : 'no';
				
				?>
				<div class="col-md-3 col-sm-6 col-xs-12">			
						<div class="funfact-inner text-center">
							<?php if($choice == 'customizer_repeater_icon'){ ?>
							<i class="fa fa <?php echo esc_html( $icon ); ?> funfact-icon"></i>
							<?php } ?>
							<?php if(!empty($title)):?>
							<h2 class="funfact-title"><?php echo esc_html( $title ); ?></h2>
						<?php endif;?>
						<?php if(!empty($text)):?>
							<p class="description"><?php echo wp_kses( html_entity_decode( $text ), $allowed_html ); ?></p>
						<?php endif;?>
						</div>  
					</div>




				<?php
				if ( $i % apply_filters( 'cloudpress_funfact_per_row_no', 4 ) == 0 ) {
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
 * Get default values for funfact section.
 *
 * @since 1.1.31
 * @access public
 */
function spiceb_cloudpress_get_funfact_default() {

	return apply_filters(
		'cloudpress_funfact_content', json_encode(
			array(
				array(
				'icon_value' => 'fa-laptop',
				'title'      => esc_html__( '1250', 'spicebox' ),
				'text'       => esc_html__( 'Excepteur', 'spicebox' ),
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
				array(
				'icon_value' => 'fa fa-cogs',
				'title'      => esc_html__( '879', 'spicebox' ),
				'text'       => esc_html__( 'Incididunt', 'spicebox'),
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
				array(
				'icon_value' => 'fa  fa-handshake-o funfact-icon',
				'title'      => esc_html__( '687', 'spicebox' ),
				'text'       => esc_html__( 'Quis Nostrud ', 'spicebox' ),
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
				array(
				'icon_value' => 'fa fa-smile-o funfact-icon',
				'title'      => esc_html__( '3578', 'spicebox' ),
				'text'       => esc_html__( 'Voluptate Velit', 'spicebox' ),
				'choice'	=> 'customizer_repeater_icon',
				'link'       => '#',
				'open_new_tab' => 'no',
				),
			)
		)
	);
}

if ( function_exists( 'spiceb_cloudpress_funfact' ) ) {
	$section_priority = apply_filters( 'cloudpress_section_priority', 5, 'spiceb_cloudpress_funfact' );
	add_action( 'spiceb_cloudpress_sections', 'spiceb_cloudpress_funfact', absint( $section_priority ) );
}