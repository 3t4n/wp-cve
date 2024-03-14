<?php 
/**
 * displaying home page about section
 * 
 *
 * @package Aqwa WordPress Theme
 */

defined( 'ABSPATH' ) || exit;

class Aqwa_Theme_Info{

	public static $default = '';

	public static function init(){	
		
		self::$default = aqwa_info_section_default();	

		// home page about section
		add_action( 'Aqwa_Homepage_Sections', array( __CLASS__, 'add_info_section_homepage'), 11 );

	}
	

	/**
 	* displaying info section homepage 
 	* 
 	*
 	* @package Aqwa WordPress Theme
 	*/
 	public static function add_info_section_homepage(){

 		if( ! get_theme_mod( 'aqwa_display_info_section' ,self::$default['aqwa_display_info_section'] ) ){
 			return;
 		}

 		?>

 		<section class="few-service p-0">
 			<div class="container">
 				<div class="row g-0">
 					<?php 
 					$info_items = get_theme_mod( 'aqwa_info_items', aqwa_default_info_items() );
 					if ( ! empty( $info_items ) ) { 

 						$info_items = json_decode( $info_items );

 						foreach ( $info_items as $item ) {
 							$target = ( !empty( $item->check_value ) ) ? 'target="_blank"' : '';
 							?>
 							<div class="col-lg-4 col-md-6">
 								<div class="card few-service-col">
 									<div class="card-body">
 										<h4 class="card-title"><i class="<?php echo esc_html( $item->icon_value ) ?>"> </i> <?php echo esc_html( $item->title ) ?> </h4>
 										<p class="card-text"><?php echo esc_html( $item->text ) ?></p>
 									</div>
 									<div class="overlay" style="background-image: url(<?php echo esc_url( $item->image_url ) ?> );">
 										<div class="overlay-inner">
 											<h4 class="card-title"><i class="<?php echo esc_html( $item->icon_value ) ?>"> </i> <?php echo esc_html( $item->title ) ?></h4>
 											<a href="<?php echo esc_url( $item->link2 ) ?>" <?php echo esc_attr( $target ) ?> class="btn"><?php echo esc_html( $item->text2 ) ?> <i class="fas fa-long-arrow-alt-right"></i></a>
 										</div>
 									</div>
 								</div>
 							</div>
 							<?php
 						} 
 					}
 					?>
 				</div>
 			</div>
 		</section>
 		<?php
 	}
	
 }

Aqwa_Theme_Info::init();