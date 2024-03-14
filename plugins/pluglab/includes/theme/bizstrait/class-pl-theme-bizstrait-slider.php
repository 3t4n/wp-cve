<?php

class PL_Theme_Bizstrait_Slider {


	protected static $_instance = null;

	/**
	 * Ensures only one instance is loaded or can be loaded.
	 *
	 * @return Main instance.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	function __construct() {
		$this->slider();
	}

	function slider() {
		 $slider_content_raw = get_theme_mod( 'slider_repeater', slider_default_json() );
		$slider_content      = json_decode( $slider_content_raw );
		
		?>


		<div class="sliderhome two owl-carousel owl-theme" id="slider-section">
			<?php

			foreach ( $slider_content as $item ) {
				// print_r($item);die;
				$slider_image    = ! empty( $item->image_url ) ? apply_filters( 'translate_single_string', $item->image_url, 'Slider section' ) : '';
				$slider_button1  = ! empty( $item->text ) ? apply_filters( 'translate_single_string', $item->text, 'Slider section' ) : '';
				$slider_button2  = ! empty( $item->text2 ) ? apply_filters( 'translate_single_string', $item->text2, 'Slider section' ) : '';
				$slider_title    = ! empty( $item->title ) ? apply_filters( 'translate_single_string', $item->title, 'Slider section' ) : '';
				$slider_subtitle = ! empty( $item->subtitle ) ? apply_filters( 'translate_single_string', $item->subtitle, 'Slider section' ) : '';
				$slider_link1    = ! empty( $item->link ) ? apply_filters( 'translate_single_string', $item->link, 'Slider section' ) : '';
				$slider_link2    = ! empty( $item->link2 ) ? apply_filters( 'translate_single_string', $item->link2, 'Slider section' ) : '';
				// $content_position = ! empty( $item->content_position ) ? apply_filters( 'translate_single_string', $item->content_position, 'Slider section' ) : '';
				$newtab = ( (bool) $item->newtab ) ? 'target=_blank' : 'target=_self';

				?>

				<!--slider-->

				<div class="slide two d-flex align-items-center cover" style='background-image: url(<?php echo $slider_image; ?>);'>
					<div class="container">
						<div class="row justify-content-center justify-content-md-center text-center">
							<div class="col-10 col-md-8 col-lg-6 static px-0">
								<div class="owl-slide-text">
									<h2 class="owl-slide-animated owl-slide-title"><?php echo $slider_title; ?></h2>
									<div class="owl-slide-animated owl-slide-subtitle mb-3">
										<?php echo $slider_subtitle; ?>
									</div>

									<?php
									if ( $slider_link1 != '' && $slider_button1 != '' ) {
										?>
										<a class="btn btn-default owl-slide-animated owl-slide-cta" href="<?php echo $slider_link1; ?>" role="button" <?php echo $newtab; ?>><span><?php echo $slider_button1; ?></span></a>
										<?php
									}
									?>

									<?php
									if ( $slider_link2 != '' && $slider_button2 != '' ) {
										?>
										<a class="btn btn-white owl-slide-animated owl-slide-cta" href="<?php echo $slider_link2; ?>" role="button" <?php echo $newtab; ?>><span><?php echo $slider_button2; ?></span></a>
										<?php
									}
									?>
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
