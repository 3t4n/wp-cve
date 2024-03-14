<?php

class PL_Theme_Bizstrait_Custom_Action {

	public function __construct() {
		add_action( 'bizstrait_menu_extra', array( $this, 'menu_extra' ), 10, 1 );
		add_action( 'bizstrait_social_icons', array( $this, 'social_icons' ), 10, 1 );
		add_action( 'bizstrait_about_section', array( $this, 'about_section' ), 10, 1 );
	}

	public function social_icons( $class ) {        ?>
	<ul class="<?php echo $class; ?>">
						<?php
							$social_icons = get_theme_mod( 'bizstrait_social_icons', pluglab_get_social_icon_default() );
							$social_icons = json_decode( $social_icons );
						if ( $social_icons != '' ) {
							foreach ( $social_icons as $social_item ) {
								$social_icon = ! empty( $social_item->icon_value ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->icon_value, 'Header section' ) : '';
								$social_link = ! empty( $social_item->link ) ? apply_filters( 'bizstrait_translate_single_string', $social_item->link, 'Header section' ) : '';
								?>
									<li><a class="btn-default" href="<?php echo esc_url( $social_link ); ?>"><i class="fa <?php echo esc_attr( $social_icon ); ?>"></i></a></li>
									<?php
							}
						}
						?>

					</ul>
		<?php
	}

	function about_section() {

		if ( get_theme_mod( 'tmpl_aboutus_testimonial_enable', 1 ) != 0 ) {
			$about_testimonial = new PL_Theme_Bizstrait_Layout();
			$about_testimonial->Testimonial();
		}

	}

	function menu_extra() {
		if (
			get_theme_mod( 'topbar_search_icon_display', 1 )
			||
			get_theme_mod( 'menu_inline_btn_enable_disable', 1 )
		) {
			?>

			<div class="ms-auto order-md-2 d-none d-lg-block">
				<div class="search">
					<?php if ( (bool) get_theme_mod( 'topbar_search_icon_display', 1 ) ) { ?>
						<!-- <a class="text-white" data-toggle="modal" data-target="#myModal"><i class="fa fa-search"></i></a> -->
						<i tabindex="0" onclick="(function(){jQuery('#myModal').modal('show');return false;})();return false;" class="fa fa-search"></i>
					<?php } ?>
					<?php if ( (bool) get_theme_mod( 'menu_inline_btn_enable_disable', 1 ) && ( get_theme_mod( 'menu_inline_btn_text', __( 'Lorem', 'bizstrait' ) ) != '' ) ) { ?>
						<a target="__blank" href="<?php echo esc_url( get_theme_mod( 'menu_inline_btn_link', '#' ) ); ?>" class="btn btn-search"><?php echo esc_html( get_theme_mod( 'menu_inline_btn_text', __( 'Lorem', 'bizstrait' ) ) ); ?></a>
					<?php } ?>
				</div>
			</div>

			<?php
		}
	}


}
