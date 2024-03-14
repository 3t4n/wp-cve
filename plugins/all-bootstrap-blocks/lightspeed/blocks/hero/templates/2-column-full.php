<?php  
$styles = '
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	position: relative;
}
.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
	margin-bottom: -' . $mobile_padding . 'px;
}
@media only screen and (min-width: ' . areoi2_get_option( 'areoi-layout-grid-grid-breakpoint-lg', '992px' ) . ') {
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		margin-bottom: 0px;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media {
		height: 100%;
		position: absolute;
		top: 0;
		' . (lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'left' : 'right') . ': 0;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .areoi-media-col,
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .areoi-media-col-content,
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media .areoi-media-col svg {
		height: 100%;
	}
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media img,
	.' . lightspeed_get_block_id() . '.areoi-lightspeed-block .areoi-hero-media video {
		height: 100%;
	    width: 100%;
	    object-fit: cover;
		position: absolute;
		top: 50%;
		left: 50%;
		transform: translate( -50%, -50% );
	}
}
';
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100">
	<div class="row h-100 align-items-center justify-content-between <?php echo lightspeed_get_attribute( 'alignment', 'start' ) == 'end' ? 'justify-content-lg-end' : '' ?>">
		
		<div class="col-lg-6 col-xl-5 text-center text-lg-start position-relative">
			
			<?php lightspeed_content( 1, 'start', 'col' ) ?>
		</div>

		<?php if ( lightspeed_get_attribute( 'video', null ) || lightspeed_get_attribute( 'image', null ) || ( lightspeed_get_attribute( 'is_post_image', null ) && get_post_thumbnail_id() ) ) : ?>
			<div class="col-lg-6 areoi-hero-media p-0 rounded overflow-hidden">
				<div class="h1 d-lg-none"></div>
				<div class="<?php lightspeed_media_col_class() ?>">
					<div class="areoi-media-col-content">
						<?php lightspeed_spacer( 'square' ) ?>
						<?php lightspeed_media() ?>
					</div>
				</div>
				<?php 
				$primary = lightspeed_get_theme_color( 'primary' );
				$rgb = lightspeed_hex_to_rgb( $primary );
				$contrast = lightspeed_get_default_color( 'bg' );

				$attributes['background_display'] = true;
				$attributes['background_utility'] = !empty( $attributes['background_utility'] ) ? $attributes['background_utility'] : $contrast;
				$attributes['background_image'] = lightspeed_get_attribute( 'image', null );
				$attributes['background_video'] = lightspeed_get_attribute( 'video', null );

				$attributes['background_overlay'] = ( !empty( $attributes['background_overlay'] ) && $attributes['background_display_overlay'] ) ? $attributes['background_overlay'] : array('rgb' => array( 'r'=> $rgb['r'], 'g'=> $rgb['g'], 'b'=> $rgb['b'], 'a'=> '0.4' ) );
				$attributes['background_display_overlay'] = true;
				$attributes['exclude_pattern'] = true;

				lightspeed_set_attribute( 'background_display', $attributes['background_display'] );
				lightspeed_set_attribute( 'background_utility', $attributes['background_utility'] );
				lightspeed_set_attribute( 'background_image', $attributes['background_image'] );
				lightspeed_set_attribute( 'background_video', $attributes['background_video'] );
				lightspeed_set_attribute( 'background_display_overlay', $attributes['background_display_overlay'] );
				lightspeed_set_attribute( 'background_overlay', $attributes['background_overlay'] );
				echo include( AREOI__PLUGIN_DIR . '/blocks/_partials/background.php' ); 
				?>
			</div>
		<?php endif; ?>
	
	</div>
</div>

<?php //lightspeed_stretched_link() ?>