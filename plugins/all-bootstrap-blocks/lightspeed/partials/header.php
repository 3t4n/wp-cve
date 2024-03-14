<?php 
if ( ! function_exists( 'lightspeed_header_top_bar' ) ) {
	function lightspeed_header_top_bar()
	{
		$align = '';
		if ( !lightspeed_get_attribute( 'exclude_company', false ) && has_nav_menu( 'top-menu' ) ) $align = 'justify-content-center';
		if ( !lightspeed_get_attribute( 'exclude_company', false ) && !has_nav_menu( 'top-menu' ) ) $align = 'justify-content-end';
		?>
		<?php if ( !lightspeed_get_attribute( 'exclude_top_bar', false ) ) : ?>
			<div class="areoi-top-bar d-none d-lg-block <?php lightspeed_attribute( 'top_bar_background' ) ?> <?php lightspeed_attribute( 'top_bar_border' ) ?>">
				<div class="<?php lightspeed_attribute( 'container', 'container' ) ?>">
					<div class="row justify-content-between align-items-center">
						
						<?php if ( !lightspeed_get_attribute( 'exclude_company', false ) ) : ?>
							<div class="col d-flex">
								<?php lightspeed_contact( '', 'me-2 mb-0', false ) ?>
							</div>
						<?php endif; ?>

						<?php if ( !lightspeed_get_attribute( 'exclude_social', false ) ) : ?>
							<div class="col d-none d-xl-block d-flex <?php echo $align ?>">
								<?php lightspeed_social( '', $align ) ?>
							</div>
						<?php endif; ?>

						<?php if ( has_nav_menu( 'top-menu' ) ) : ?>
							<div class="col">
								<?php wp_nav_menu( array( 'theme_location' => 'top-menu', 'walker' => new AREOI_HAF_Walker_Nav_Menu_Primary, 'container_class' => 'areoi-top-bar-menu' ) ); ?>
							</div>
						<?php endif; ?>

					</div>
				</div>
			</div>
		<?php endif; ?>
		<?php
	}
}
if ( ! function_exists( 'lightspeed_header_basic_modal' ) ) {
	function lightspeed_header_basic_modal( $type )
	{
		?>
		<div class="modal fade" id="modal-<?php echo $type ?>-<?php echo lightspeed_get_block_id() ?>" tabindex="-1" aria-hidden="true">
			<div class="modal-dialog modal-fullscreen">
				<div class="modal-content bg-light">
					<div class="modal-body p-0">

						<div class="row row-cols-1 row-cols-lg-2 h-100 me-0 ms-0">

							<div class="col text-center h-100 d-flex flex-column align-items-center">

								<button class="p-4" type="button" data-bs-dismiss="modal" aria-label="<?php _e( 'Close' ) ?>">
									<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12 19 6.41z"/></svg>
									<span class="d-none d-sm-inline"><?php _e( 'Close' ) ?></span>
								</button>

								<?php if ( $type == 'menu' ) : ?>
									<?php if ( has_nav_menu( 'more-menu' ) ) : ?>
										<div class="areoi-deep-menu h3 mb-0 border-top border-bottom">
											<?php wp_nav_menu( array( 
												'theme_location' => 'more-menu', 
												'menu_class' => 'areoi-primary-menu',
												'walker' => new AREOI_HAF_Walker_Nav_Menu_More 
											) ); ?>
										</div>
									<?php endif; ?>
								<?php else : ?>
									<div class="w-100 areoi-search-menu border-top border-bottom flex-grow-1 d-flex flex-column align-items-lg-center justify-content-center position-relative">
										<div style="max-width: 450px; height: 98%;" class="w-100 h2 mb-4 flex-grow-1 d-flex flex-column align-items-lg-center justify-content-center position-relative overflow-auto">
											<p class="h2"><?php _e( 'Search' ) ?></p>

											<?php lightspeed_search() ?>
										</div>
									</div>
								<?php endif; ?>

								<div class="w-100 overflow-auto">
									<?php if ( has_nav_menu( 'primary-menu' ) ) : ?>
										<div class="pt-4">
											<?php wp_nav_menu( array( 
												'theme_location' => 'primary-menu', 
												'walker' => new AREOI_HAF_Walker_Nav_Menu_Primary, 
												'menu_class' => 'areoi-more-menu d-flex justify-content-start justify-content-md-center' 
											) ); ?>
										</div>
									<?php endif; ?>

									<?php if ( !lightspeed_get_attribute( 'exclude_social', false ) ) : ?>
										<div class="pt-4">
											<?php lightspeed_social( null, 'justify-content-center' ) ?>
										</div>
									<?php endif; ?>

									<?php if ( !lightspeed_get_attribute( 'exclude_company', false ) ) : ?>
										<div class="pt-4">
											<?php lightspeed_contact( '', 'me-2 mb-0' ) ?>
										</div>
									<?php endif; ?>
								</div>
								<div class="pb-4"></div>

							</div>
							
							<div class="d-none d-lg-block col p-0 h-100 bg-primary areoi-feature-menu text-center position-lg-fixed top-0 end-0">
								<?php if ( has_nav_menu( 'feature-menu' ) ) :

									$locations 			= get_nav_menu_locations();
									$menu 				= wp_get_nav_menu_object( $locations['feature-menu'] );
									$full_menu          = wp_get_nav_menu_items( $menu->term_id );
									$menu_total_count 	= 0;
									if ( is_array( $full_menu ) ) {
										$full_menu          = array_values( array_filter( $full_menu, function( $row ) {
											return $row->menu_item_parent === '0';
										} ) );
										$menu_total_count   = count( $full_menu );
									}
								?>

									<div id="<?php echo lightspeed_get_block_id() ?>-feature-<?php echo $type ?>" class="carousel slide h-100" data-bs-ride="carousel" data-bs-interval="3000">

										<?php if ( $menu_total_count > 0 ) : ?>
											<div class="carousel-indicators">
												<?php for ( $i = 0; $i < $menu_total_count; $i++ ) : ?>
													<button 
													type="button" 
													data-bs-target="#<?php echo lightspeed_get_block_id() ?>-feature-<?php echo $type ?>" 
													data-bs-slide-to="<?php echo $i ?>" 
													<?php echo $i == 0 ? 'class="active"' : '' ?> 
													aria-current="true" 
													aria-label="Slide <?php echo $i+1 ?>"
													></button>
												<?php endfor; ?>
											</div>
										<?php endif; ?>

										<?php wp_nav_menu( array( 
											'theme_location' => 'feature-menu', 
											'container' => false,
											'menu_class' => 'carousel-inner h-100',
											'walker' => new AREOI_HAF_Walker_Nav_Menu_Feature_Carousel,
											'link_before' => '<span class="btn btn-light areoi-has-url">',
											'link_after' => '</span>' 
										) ); ?>
										
										<?php if ( $menu_total_count > 0 ) : ?>
											<button class="carousel-control-prev" type="button" data-bs-target="#<?php echo lightspeed_get_block_id() ?>-feature-<?php echo $type ?>" data-bs-slide="prev">
												<span class="carousel-control-prev-icon" aria-hidden="true"></span>
												<span class="visually-hidden">Previous</span>
											</button>
											<button class="carousel-control-next" type="button" data-bs-target="#<?php echo lightspeed_get_block_id() ?>-feature-<?php echo $type ?>" data-bs-slide="next">
												<span class="carousel-control-next-icon" aria-hidden="true"></span>
												<span class="visually-hidden">Next</span>
											</button>
										<?php endif; ?>
									</div>


									
								<?php endif; ?>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
		<?php
	}
}

$styles .= '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .dropdown-menu {
	background: #fff;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .dropdown-menu a {
	color: ' . lightspeed_get_theme_color( 'bg-dark' ) . ';
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .dropdown button {
	transition: all 0.25s ease-in-out;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .dropdown button.show {
	transform: rotate(180deg);
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar {
	padding: ' . ( $padding / 2 ) . 'px 0;
	font-size: 0.9em;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header ul {
	padding: 0;
	margin: 0;
	list-style: none;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar-menu > ul,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-more-menu {
	display: flex;
	justify-content: end;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header #menu-primary-menu {
	justify-content: center;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar-menu > ul > li,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-more-menu > li {
	padding: 0 10px;
	white-space: nowrap;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header #menu-more-menu li {
	padding: 10px;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar-menu > ul > li:last-of-type {
	padding-right: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar a:not(.dropdown-menu a),
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar button {
	color: ' . lightspeed_get_theme_color( lightspeed_get_attribute( 'top_bar_text', lightspeed_get_default_color( 'bg', lightspeed_get_attribute( 'top_bar_background', 'bg-primary' ) ) ) ) . ';
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar {
	padding: 10px 0;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar {
		padding: ' . $padding . 'px 0;
	}
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar a,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar button {
	color: ' . lightspeed_get_theme_color( lightspeed_get_attribute( 'main_text', lightspeed_get_default_color( 'bg', lightspeed_get_attribute( 'main_background', 'bg-primary' ) ) ) ) . ';
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header button:not(.btn, .btn-close, .carousel-indicators button) {
	background: none;
	border: none;
	padding: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar img,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar svg {
	max-height: 30px;
	max-width: 180px;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar img,
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar svg {
		max-height: ' . lightspeed_get_attribute( 'logo_height', '50' ) . 'px;

	}
}

.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .modal,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .modal a,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .modal button:not(.btn) {
	color: ' . lightspeed_get_theme_color( 'bg-dark' ) . ';
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu {
	width: 100%;
	flex-grow: 1;
	position: relative;
	padding: 20px;
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu,
.' . lightspeed_get_block_id() . ' .areoi-search-menu {
	border-color: rgba( 0, 0, 0, 0.1 ) !important;
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu > div,
.' . lightspeed_get_block_id() . ' .areoi-deep-menu .areoi-sub-menu {
	background: ' . lightspeed_get_theme_color( 'bg-light' ) . ';
	width: 100%;
	height: 98%;
	position: absolute;
	top: 0; 
	left: 0;
	display: none;
	text-align: left;
	overflow: auto;
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu > div,
.' . lightspeed_get_block_id() . ' .areoi-deep-menu .areoi-sub-menu.active {
	display: flex;
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu ul {
	max-width: 400px;
	margin: auto !important;
	flex-grow: 1;
}
.' . lightspeed_get_block_id() . ' .areoi-deep-menu ul li {
	display: flex;
	justify-content: space-between;
}

.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu {
	height: 100%;
	overflow: auto;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu li {
	height: 100%;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu a {
	height: 100%;
	position: relative;
	overflow: hidden;
	display: flex;
	align-items: end;
	justify-content: center;
	padding: 80px 40px;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu a span:not(.btn) {
	background-position: center;
	background-size: cover;
	width: 100%;
	height: 100%;
	display: block;
	position: absolute;
	top: 0;
	left: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu a .btn {
	max-width: 80%;
	width: 350px;
	position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-feature-menu .sub-menu {
	display: none;
}

.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar {
	transition: all 0.5s ease-in-out;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .position-fixed .areoi-top-bar {
	height: 50px;
	margin-top: -50px;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-bar,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-top-bar {
	background-color: rgba( 0, 0, 0, 0 );
	border-bottom: 1px solid rgba( 0, 0, 0, 0 );
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar a:not(.dropdown-menu a),
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar button {
	transition: background-color 0.5s ease-in-out, color 0.5s ease-in-out, border-color 0.5s ease-in-out;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-logo-dark {
	opacity: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-logo-dark {
	opacity: 1;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header .areoi-menu-logo-default {
	opacity: 1;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-logo-default {
	opacity: 0;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar {
	background-color: ' . lightspeed_get_theme_color( 'light' ) . ' !important;
	border-color: ' . lightspeed_get_theme_color( 'dark' ) . ' !important;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar,
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar a:not(.dropdown-menu a),
.' . lightspeed_get_block_id() . '.areoi-lightspeed-header.scrolled .areoi-menu-bar button {
	color: ' . lightspeed_get_theme_color( 'dark' ) . ' !important;
}
';

$scripts = "
jQuery(document).ready(function($){
	$( document ).on( 'click', '." . lightspeed_get_block_id() . " .areoi-menu-more-btn', function( e ) {
		e.preventDefault();

		var parent = $( this ).parent( 'li' ),
			sub_menu = parent.find( '> .areoi-sub-menu' );

		sub_menu.addClass( 'active' );
	} );

	$( document ).on( 'click', '." . lightspeed_get_block_id() . " .areoi-menu-back', function( e ) {
		e.preventDefault();

		var parent = $( this ).parent( 'li' ).parent( '.sub-menu' ).parent( '.areoi-sub-menu' );

		parent.removeClass( 'active' );
	} );
});";