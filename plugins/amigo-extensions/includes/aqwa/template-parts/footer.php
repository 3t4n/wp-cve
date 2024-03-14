<?php 
/**
 * displaying footer area
 *
 */

defined( 'ABSPATH' ) || exit;

class Aqwa_Theme_Footer{

	public static $default = '';

	public static function init(){	

		self::$default = aqwa_footer_section_default();

		add_action( 'Aqwa_Footer_Contact_Info', array( __CLASS__, 'add_footer_contact_info') );
	}
	

	public static function add_footer_contact_info(){

		$display_footer_contact_detail = get_theme_mod( 'aqwa_display_footer_contact_detail', self::$default['aqwa_display_footer_contact_detail'] );

		if( false == $display_footer_contact_detail ) { 
			return;
		}	
		
		?>
		<div class="footer-contact">
			<div class="container">
				<div class="row">
					<?php 
					$footer_items = get_theme_mod( 'aqwa_footer_contacts_items', aqwa_default_footer_contact_items() );
					if ( ! empty( $footer_items ) ) {
						$footer_items = json_decode( $footer_items );
						foreach ( $footer_items as $item ) { ?>
							<div class="col-md-4 one-footer-contact wow animated fadeInUp" data-wow-delay="0.2s">
								<aside class="footer-contact-col content-center">
									<div class="icon-footer-contact">
										<i class="<?php echo esc_html($item->icon_value) ?>"></i>
									</div>
									<div class="footer-contact-content">
										<p> <?php echo esc_html( $item->title ) ?></p>
										<h5><?php echo esc_html( $item->text ) ?> </h5>
									</div>
								</aside>
							</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		</div>
		<?php
		
	}
}

Aqwa_Theme_Footer::init();
?>