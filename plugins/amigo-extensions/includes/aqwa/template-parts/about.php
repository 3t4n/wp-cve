<?php 
/**
 * displaying home page about section
 * 
 */

defined( 'ABSPATH' ) || exit;

class Aqwa_Theme_About{

	public static $default = '';

	public static function init(){	
		
		self::$default = aqwa_about_section_default();

		add_action( 'wp_enqueue_scripts', array( __CLASS__,'inline_css_about_section' ) );

		// home page about section
		add_action( 'Aqwa_Homepage_Sections', array( __CLASS__, 'add_about_section_homepage'), 11 );

	}

	/**
 	* About section inline CSS
 	* 
 	*
 	* @package Aqwa WordPress Theme
 	*/
 	public static function inline_css_about_section(){

 		$element_img = AMIGO_PLUGIN_DIR_URL . 'includes/aqwa/assets/images/elements-01.png';
 		$custom_css = "
 		.about-img::before{
 			background-image: url(".$element_img.");
 		}";

 		wp_add_inline_style( 'aqwa-style', $custom_css );
 	}

	/**
 	* displaying about section 
 	* 
 	*
 	* @package Aqwa WordPress Theme
 	*/
 	public static function add_about_section_homepage(){

 		if( ! get_theme_mod( 'aqwa_display_about_section' ,self::$default['aqwa_display_about_section'] ) ){
 			return;
 		}

 		$title = get_theme_mod( 'aqwa_about_section_title' ,self::$default['aqwa_about_section_title'] );
 		$sub_title = get_theme_mod( 'aqwa_about_section_subtitle' ,self::$default['aqwa_about_section_subtitle'] );
 		$text = get_theme_mod( 'aqwa_about_section_text' ,self::$default['aqwa_about_section_text'] );
 		$image_one = get_theme_mod( 'aqwa_about_section_image_one' ,self::$default['aqwa_about_section_image_one'] );
 		$image_two = get_theme_mod( 'aqwa_about_section_image_two' ,self::$default['aqwa_about_section_image_two'] );
 		$display_youtube = get_theme_mod( 'aqwa_display_about_section_youtube' ,self::$default['aqwa_display_about_section_youtube'] );
 		$youtube_link = get_theme_mod( 'aqwa_about_section_youtube_link' ,self::$default['aqwa_about_section_youtube_link'] );

 		?>
 		<section class="about-section pt-0">
 			<div class="container">
 				<div class="row">

 					<div class="col-lg-6 col-md-12 wow fadeInLeft" data-wow-delay="0.4s">
 						<div class="about-img">
 							<div class="about-big-img">
 								<img src="<?php echo esc_url( $image_one ) ?>" class="img-fluid" alt="img">
 							</div>
 							<?php if($display_youtube){ ?>
 								<div class="about-small-img">
 									<img src="<?php echo esc_url( $image_two ) ?>" class="img-fluid" alt="img">
 									<div class="about-video-overlay">
 										<a href="<?php echo esc_url( $youtube_link ) ?>" class="popup-gmaps video-play-btn"> <i class="fas fa-play"></i> </a>
 									</div> 									
 								</div>
 							<?php } ?>
 						</div>
 					</div>

 					<div class="col-lg-6 col-md-12 wow fadeInRight" data-wow-delay="0.4s">
 						<div class="section-title">
 							<?php if( ! empty( $title ) ){ ?>
 								<h5> <span> <?php echo esc_html( $title ) ?>  </span> </h5>
 							<?php } ?>

 							<?php if( ! empty( $sub_title ) ){ ?>
 								<h2> <?php echo esc_html( $sub_title ) ?> </h2>
 							<?php } ?>

 							<?php if( ! empty( $text ) ){ ?>
 								<p> <?php echo esc_html( $text ) ?></p>
 							<?php } ?>
 						</div>
 						<?php self::about_section_items() ?>
 					</div>
 				</div>
 			</div>
 		</section>
 		<?php
 	}

	/**
 	* displaying about section content items
 	* 
 	*
 	* @package Aqwa WordPress Theme
 	*/

 	public static function about_section_items(){

 		$about_items = get_theme_mod( 'aqwa_about_item', aqwa_default_about_items() );

 		if ( ! empty( $about_items ) ) { 

 			echo '<div class="about-content"><div class="about-list">';

 			$about_items = json_decode( $about_items );

 			foreach ( $about_items as $item ) {	?>
 				<div class="about-list-col">
 					<p> <i class="<?php echo esc_html( $item->icon_value ) ?> icon">  </i> <span> <?php echo esc_html( $item->title ) ?> </span> </p>
 				</div>
 				<?php
 			}
 			echo '</div></div>';
 		}
 	}
 }

 Aqwa_Theme_About::init();
 ?>