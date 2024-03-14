<?php 
// header office address
add_action( 'Industri_Header_Top_Contacts',  'industri_init_header_top_contacts' );
function industri_init_header_top_contacts(){
	$default = amigo_industri_default_settings();	
	$office_contact_items = get_theme_mod( 'office_contact_items', amigo_industri_default_office_contact_items() ); ?>
	<ul class="header-top-contact">
		<?php if ( ! empty( $office_contact_items ) ) { $office_contact_items = json_decode( $office_contact_items );
			foreach ( $office_contact_items as $item ) { ?>
				<li class="header_top_email"><i class="fa <?php echo esc_html($item->icon_value) ?> faa-shake animated"></i> <?php echo esc_html($item->text) ?></li>

			<?php } ?>
		<?php } ?>
	</ul>	
	<?php
}

// header social media
add_action( 'Industri_Header_SocialMedia',  'industri_init_header_socialmedia' );
function industri_init_header_socialmedia(){
	$default = amigo_industri_default_settings();		
	$show_social_icons = get_theme_mod( 'display_social_icons', $default['display_social_icons'] );
	$social_icons = get_theme_mod( 'social_icons', amigo_industri_default_social_icons() );	
	if($show_social_icons){ ?>
		<div class="social-media">
			<?php if ( ! empty( $social_icons ) ) { $social_icons = json_decode( $social_icons ); 
				foreach ( $social_icons as $icon ) { ?>
					<a href="<?php echo esc_url($icon->link) ?>"> <i class="fa <?php echo esc_html($icon->icon_value) ?>"></i> </a>
				<?php } ?>
			<?php } ?>
		</div>
	<?php } ?>	
	<?php
}

// abover bar button
add_action('Industri_Header_Button','industri_init_header_button');
function industri_init_header_button(){
	$default = amigo_industri_default_settings();		
	$show_header_button = get_theme_mod('display_header_button', $default['display_header_button']);
	$header_button_icon = get_theme_mod('header_button_icon', $default['header_button_icon']);
	$header_button_text = get_theme_mod('header_button_text', $default['header_button_text']);
	$header_button_link = get_theme_mod('header_button_link', $default['header_button_link']);
	?>
	<?php if($show_header_button){ ?>
		<a href="<?php echo esc_url($header_button_link) ?>" id="header-top-right" class="btn btn-theme btn-md"><?php echo esc_html($header_button_text) ?> 
		<?php if(!empty($header_button_icon)){ ?>
			<i class="fa <?php echo esc_html($header_button_icon) ?> faa-passing animated" aria-hidden="true"></i> </a>
		<?php } ?>
	<?php } ?>
	<?php
}

// navbar extra button
add_action( 'Industri_Navbar_Extra_Button', 'industri_navbar_extra_area_area' );
function industri_navbar_extra_area_area(){
	$default = amigo_industri_default_settings();
	$show_cart = get_theme_mod('display_cart_button', $default['display_cart_button']);
	$cart_icon = get_theme_mod('cart_icon', $default['cart_icon']);
	$show_search = get_theme_mod('display_navigation_search_button', $default['display_navigation_search_button']);	
	?>
	<ul class="navbar navbar-right mb-0">
		<?php if($show_search){ ?>
			<li>
				<a href="#search-form" data-bs-toggle="collapse" class="nav-link header_search search-btn" aria-expanded="false"> <i class="fa fa-search"></i> </a>
			</li>
			<div class="collapse" id="search-form">
		<div class="card card-body">
			<form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
				<div class="input-group">
					<input type="text" name="s" class="form-control" placeholder="Search" />
					<button class="btn btn-theme btn-search" type="submit" id="button-addon2"><i class="fa fa-search"></i></button>
					<button class="btn btn-search-close" type="button" data-bs-toggle="collapse" data-bs-target="#search-form" aria-expanded="false" aria-controls="collapseExample">
						<i class="fa fa-times"></i>
					</button>
				</div>
			</form>
		</div>
	</div>


		<?php } ?>

		<?php if($show_cart){ ?>
			<?php if ( class_exists( 'WooCommerce' ) ) { ?>
				<li>
					<a href="javascript:void(0)" class="nav-link cart-btn"> 
						<?php if(!empty($cart_icon)){ ?>
							<i class="fa <?php echo esc_attr($cart_icon) ?>"></i>
						<?php } ?>
						 
						<?php if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
							<?php 
							$count = WC()->cart->cart_contents_count;
							$cart_url = wc_get_cart_url();
							if ( $count > 0 ) { ?>
								<span><?php echo esc_html( $count ); ?></span>
							<?php }else{	?>
								<span><?php echo esc_html__('0', 'amigo-extensions') ?></span>
							<?php } ?>
						<?php } ?>
					</a>
					<div class="woo-cart">
						<ul class="woo-cart-items">
							<?php get_template_part('woocommerce/cart/mini','cart'); ?>
						</ul>
					</div>
				</li>
			<?php } ?>
		<?php } ?>

	

		<li>
			<a href="#nav-sidebar" class="toggle_btn sidebar_toggle collapsed nav-sidebar" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="nav-sidebar">
				<div class="more-solid icon top"></div>
				<div class="more-solid icon center"></div>
				<div class="more-solid icon bottom"></div>
			</a>
		</li>
	</ul>
	<?php
}

// home search area
add_action( 'Industri_Navbar_Search', 'industri_navbar_search_area' );
function industri_navbar_search_area(){
	$default = amigo_industri_default_settings();
	$is_search_button = get_theme_mod( 'display_navigation_search_button', $default['display_navigation_search_button'] );

	if( !$is_search_button ){
		return;
	}
	?>

	
	<?php

}
?>