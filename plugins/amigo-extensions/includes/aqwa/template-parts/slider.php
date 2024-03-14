<?php 
/**
 * displaying home page slider
 * 
 */

if ( ! defined( 'ABSPATH' ) ) { exit; }

class Aqwa_Home_Slider{

	public static $default = '';

	public static function init(){		

		// home slider add
		add_action( 'Aqwa_Homepage_Sections', array( __CLASS__, 'add_slider_section_homepage'), 10 );
	}

	public static function add_slider_section_homepage(){
		// get slider items
		$slider_items = get_theme_mod( 'aqwa_slider_items', aqwa_slider_section_default() );

		// check slider items
		if ( empty( $slider_items ) ) { 
			return;
		}		
		?>
		<main>
			<div class="main-slider">
				<div class="owl-carousel home-slider ">
					<?php 
					// slider setting json decode
					$slider_items = json_decode( $slider_items );

					foreach ( $slider_items as $slide ) {
						$target = ( !empty( $slide->check_value ) ) ? 'target="_blank"' : 'target="_self"';
						?>
						<div class="item slider-item">
							<?php if( !empty( $slide->image_url ) ){ ?>
								<img src="<?php echo esc_url( $slide->image_url ) ?>" class="img-fluid" alt="<?php echo esc_html( $slide->title ) ?>">
							<?php } ?>
							<div class="container slider-overlay content-center">
								<div class="slide-content">

									<?php if( !empty( $slide->title ) ){ ?>
										<h2 class="title" data-animation="fadeInUp" data-delay="200ms"> <?php echo esc_html( $slide->title ) ?></h2>
									<?php } ?>

									<?php if( !empty( $slide->image_url ) ){ ?>
										<p data-animation="fadeInUp" data-delay="500ms">
											<?php echo esc_html( $slide->text ) ?>
										</p>
									<?php } ?>

									<?php if( !empty( $slide->text2 ) ){ ?>
										<div class="slider-btn">
											<a href="<?php echo esc_url( $slide->link2 ) ?>" <?php echo esc_attr( $target ) ?> data-animation="fadeInUp" data-delay="800ms" class="btn btn-theme btn-lg"><?php echo esc_html( $slide->text2 ) ?> <i class="fas fa-long-arrow-alt-right"></i> </a>
										</div>
									<?php } ?>
								</div>
							</div>
						</div>
						<?php
					}
					?>					
				</div>
			</div>
		</main>
		<?php
	}
}

Aqwa_Home_Slider::init();

