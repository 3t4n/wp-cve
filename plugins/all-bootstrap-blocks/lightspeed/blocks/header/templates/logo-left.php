<?php 

$styles = '';
$scripts = '';

include( AREOI__PLUGIN_LIGHTSPEED_DIR . 'partials/header.php' );

$styles .= '

';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="areoi-header-container <?php lightspeed_attribute( 'position' ) ?> top-0 w-100">

	<?php lightspeed_header_top_bar() ?>

	<div class="areoi-menu-bar <?php lightspeed_attribute( 'main_background' ) ?> <?php lightspeed_attribute( 'main_border' ) ?>">
		<div class="<?php lightspeed_attribute( 'container', 'container' ) ?>">
			<div class="row align-items-center justify-content-between">

				<div class="col">
					<a class="h-100 d-block position-relative" href="<?php echo home_url() ?>" title="<?php echo get_bloginfo( 'name' ) ?>">
						<span class="areoi-menu-logo-default w-100 h-100 d-block"><?php lightspeed_logo( lightspeed_get_default_color( 'logo', lightspeed_get_attribute( 'main_background' ) ) ) ?></span>
						<span class="areoi-menu-logo-dark position-absolute top-0 start-0 w-100 h-100 d-block">
							<?php echo lightspeed_get_logo( lightspeed_get_attribute( 'logo', null ), 'dark' ) ?>
						</span>
					</a>
				</div>

				<div class="col text-end">
					
					<?php if ( !lightspeed_get_attribute( 'exclude_search', false ) ) : ?>
						<button data-bs-target="#modal-search-<?php echo lightspeed_get_block_id() ?>" data-bs-toggle="modal" aria-label="<?php _e( 'Open Search' ) ?>">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/></svg>
							<span class="d-none d-sm-inline"></span>
						</button>
					<?php endif; ?>

					<?php if ( has_nav_menu( 'primary-menu' ) || has_nav_menu( 'more-menu' ) ) : ?>
						<button class="ms-2" data-bs-target="#modal-menu-<?php echo lightspeed_get_block_id() ?>" data-bs-toggle="modal" aria-label="<?php _e( 'Open Full Menu' ) ?>">
							<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="currentColor"><path d="M0 0h24v24H0V0z" fill="none"/><path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/></svg>
							<span class="d-none d-sm-inline"></span>
						</button>
					<?php endif; ?>

				</div>

			</div>
		</div>
	</div>

</div>

<?php lightspeed_header_basic_modal( 'menu' ); ?>

<?php if ( !lightspeed_get_attribute( 'exclude_search', false ) ) : ?>
	<?php lightspeed_header_basic_modal( 'search' ); ?>
<?php endif; ?>

<?php if ( $scripts ) : ?>
	<script><?php echo areoi_minify_js( $scripts ) ?></script>
<?php endif; ?>