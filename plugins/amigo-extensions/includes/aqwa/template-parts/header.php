<?php 
/**
 * displaying theme header part
 * 
 */

defined( 'ABSPATH' ) || exit;

class Aqwa_Theme_Header{

	public static $default = '';

	public static function init(){	
		
		self::$default = aqwa_default_settings();	

		add_action( 'Aqwa_Navbar_Search_Button', array( __CLASS__, 'add_navbar_search_button') );
		add_action( 'Aqwa_Overlay_Search_Container', array( __CLASS__, 'add_navbar_search_container') );
		add_action( 'Aqwa_Header_Top_Bar', array( __CLASS__, 'add_header_top_bar') );
		add_action( 'Aqwa_Header_Contact_Details', array( __CLASS__, 'add_header_contact_details') );
	}

	public static function add_navbar_search_button(){

		$display_link_button = get_theme_mod( 'aqwa_display_primary_menu_link_button', self::$default['aqwa_display_primary_menu_link_button'] );
		$link_button_link = get_theme_mod( 'aqwa_primary_menu_link_button_link', self::$default['aqwa_primary_menu_link_button_link'] );
		$link_button_text = get_theme_mod( 'aqwa_primary_menu_link_button_text', self::$default['aqwa_primary_menu_link_button_text'] );	

		$display_search_button = get_theme_mod( 'aqwa_display_primary_menu_search_button', self::$default['aqwa_display_primary_menu_search_button'] );

		?>
		<form class="d-flex" >

			<?php if( $display_link_button ){ ?>
				<a href="<?php echo esc_url( $link_button_link ) ?>" class="btn btn-quote" ><i class="fas fa-long-arrow-alt-right"></i> <?php echo esc_html($link_button_text) ?></a>
			<?php } ?>

			<?php if( $display_search_button ){ ?>
				<a class="btn btn-search" data-bs-toggle="collapse" href="#search-box" role="button" aria-expanded="false"> <i class="fas fa-search"> </i> </a>
			<?php } ?>
		</form>
		<?php

	}

	public static function add_navbar_search_container(){

		$label = get_theme_mod( 'aqwa_primary_menu_search_button_overlay_label', self::$default['aqwa_primary_menu_search_button_overlay_label'] );
		$text = get_theme_mod( 'aqwa_primary_menu_search_button_overlay_text', self::$default['aqwa_primary_menu_search_button_overlay_text'] );
		?>
		<div class="collapse" id="search-box">

			<div class="card card-body">
				<div class="section-title white text-center">
					<?php if( !empty($label) ){ ?>
						<h2> <?php echo esc_html($label) ?> </h2>
					<?php } ?>

					<?php if( !empty($text) ){ ?>
						<p> <?php echo esc_html($text) ?> </p>
					<?php } ?>

				</div>
				<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
					<div class="input-group">
						<input type="text" name="s" class="form-control" placeholder="Enter Your word" />
						<button class="btn btn-search" type="submit">
							<i class="fas fa-search"> </i>
						</button>
						<a class="close" data-bs-toggle="collapse" href="#search-box" aria-expanded="false"> </a>	
					</div>
				</form>
			</div>	
		</div>
		<?php
	}

	public static function add_header_top_bar(){

		$is_header_top_bar = get_theme_mod( 'aqwa_is_header_top_bar', self::$default['aqwa_is_header_top_bar'] );

		if( !$is_header_top_bar ){
			return;
		}

		?>
		<div class="top-bar">
			<div class="container top-bar-flex">

				<?php self::header_top_bar_left_message(); ?>

				<div class="topbar-right">

					<?php self::header_schedule_text(); ?>

					<?php self::header_social_icons(); ?>
				</div>
			</div>
		</div>

		<?php
	}

	public static function header_top_bar_left_message(){
		
		$header_text = get_theme_mod( 'aqwa_header_above_text', self::$default['aqwa_header_above_text']);
		?>
		
		<div class="topbar-left">
			<p> <?php echo wp_kses_post( $header_text ) ?> </p>
		</div>
		<?php
		
	}

	public static function header_schedule_text(){
		
		$header_schedule_text = get_theme_mod( 'aqwa_header_schedule_text', self::$default['aqwa_header_schedule_text'] );		

		?>
		<aside class="widget widget-open-timing">
			<div class="open-timing "> <i class="far fa-clock"> </i> <span> <?php echo esc_html( $header_schedule_text ) ?> </span> </div>
		</aside>
		<?php
		

	}

	public static function header_social_icons(){
		if( false == get_theme_mod( 'aqwa_display_social_icons', self::$default['aqwa_display_social_icons'] ) ) { 
			return;
		}

		$social_icons = get_theme_mod( 'aqwa_social_icons', aqwa_default_social_icons() );

		echo '<aside class="widget social-media"><ul class="nav">';

		if ( ! empty( $social_icons ) ) {

			$social_icons = json_decode( $social_icons );

			foreach ( $social_icons as $icon ) { 
				$target = ( !empty( $icon->check_value ) ) ? 'target="_blank"' : 'target="_self"';
				?>

				<li class="nav-item">
					<a href="<?php echo esc_url($icon->link) ?>" class="nav-link" data-bs-toggle="tooltip" <?php echo esc_attr( $target ) ?>><i class="<?php echo esc_html($icon->icon_value) ?>"></i></a>
				</li>

				<?php
			}

			echo '</ul></aside>';			
		}
	}

	public static function add_header_contact_details(){

		$display_header_contact_detail = get_theme_mod( 'aqwa_display_header_contact_detail', self::$default['aqwa_display_header_contact_detail'] );

		if( false == $display_header_contact_detail ) { 
			return;
		}		

		$header_items = get_theme_mod( 'aqwa_header_contacts_items', aqwa_default_header_contact_items() );


		if ( ! empty( $header_items ) ) {

			echo '<div class="d-lg-flex my-auto h-c">';

			$header_items = json_decode( $header_items );

			foreach ( $header_items as $item ) { ?>

				<aside class="one-contact-area widget widget-contact">
					<div class="contact-area">
						<div class="contact-icon">
							<i class="<?php echo esc_html($item->icon_value) ?>"></i>
						</div>
						<div class="contact-info">
							<h5 class="title"> <?php echo esc_html( $item->title ) ?> </h5>
							<p class="text"> <?php echo esc_html( $item->text ) ?> </p>
						</div>
					</div>
				</aside>
				<?php 
			}

			echo '</div>';
		}
	}
}

Aqwa_Theme_Header::init();
?>