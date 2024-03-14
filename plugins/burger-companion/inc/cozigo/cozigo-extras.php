<?php
// Header Careers
if ( ! function_exists( 'cozipress_header_careers' ) ) {
	function cozipress_header_careers() {
		$hide_show_hdr_careers	=	get_theme_mod('hide_show_hdr_careers','1');	
		$hdr_careers_icon 		=	get_theme_mod('hdr_careers_icon','fa-briefcase');
		$hdr_careers_ttl  		=	get_theme_mod('hdr_careers_ttl','Careers');
		$hdr_careers_url 		=	get_theme_mod('hdr_careers_url','');
		if($hide_show_hdr_careers == '1') : 
			?>
			<aside class="widget widget-contact">
				<div class="contact-area">
					<div class="contact-icon">
						<div class="contact-corn"><i class="fa <?php echo esc_attr( $hdr_careers_icon ); ?>"></i></div>
					</div>
					<div class="contact-info">
						<p class="text careers-ttl "><a href="<?php echo esc_url( $hdr_careers_url ); ?>"><?php echo wp_kses_post( $hdr_careers_ttl ); ?></a></p>
					</div>
				</div>
			</aside>
			<?php
		endif;
	}
}
add_action('cozipress_header_careers', 'cozipress_header_careers');

// Header Email
if ( ! function_exists( 'cozipress_header_email' ) ) {
	function cozipress_header_email() {
		$hide_show_hdr_email	=	get_theme_mod('hide_show_hdr_email','1');	
		$hdr_email_icon 		=	get_theme_mod('hdr_email_icon','fa-envelope-o');
		$hdr_email_ttl  		=	get_theme_mod('hdr_email_ttl','Email Us');
		$hdr_email_url 		    =	get_theme_mod('hdr_email_url','');
		if($hide_show_hdr_email == '1') : 
			?>
			<aside class="widget widget-contact">
				<div class="contact-area">
					<div class="contact-icon">
						<div class="contact-corn"><i class="fa <?php echo esc_attr( $hdr_email_icon ); ?>"></i></div>
					</div>
					<div class="contact-info">
						<p class="text email-ttl"><a href="<?php echo esc_url( $hdr_email_url ); ?>"><?php echo wp_kses_post( $hdr_email_ttl ); ?></a></p>
					</div>
				</div>
			</aside>
			<?php
		endif;
	}
}
add_action('cozipress_header_email', 'cozipress_header_email');

?>