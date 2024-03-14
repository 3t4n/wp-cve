<?php  
$prev_post = get_previous_post();
$next_post = get_next_post();
$items = array(
	'prev' => array(
		'heading' => $prev_post ? $prev_post->post_title : '',
		'media' => $prev_post ? wp_get_attachment_image_url( get_post_thumbnail_id( $prev_post->ID ), 'single-post-thumbnail' ) : '',
		'permalink' => $prev_post ? get_the_permalink( $prev_post->ID ) : ''
	),
	'next' => array(
		'heading' => $next_post ? $next_post->post_title : '',
		'media' => $next_post ? wp_get_attachment_image_url( get_post_thumbnail_id( $next_post->ID ), 'single-post-thumbnail' ) : '',
		'permalink' => $next_post ? get_the_permalink( $next_post->ID ) : ''
	),
);

if ( !empty( $_GET['context'] ) && $_GET['context'] == 'edit' ) {
	$items = array(
		'prev' => array(
			'heading' => 'Previous post',
			'media' => lightspeed_get_placeholder_images( 1 )['url'],
			'permalink' => ''
		),
		'next' => array(
			'heading' => 'Next post',
			'media' => lightspeed_get_placeholder_images( 3 )['url'],
			'permalink' => ''
		),
	);
}

if ( empty( $items['prev']['media'] ) && areoi2_get_option( 'areoi-lightspeed-company-include-lightspeed', false ) ) {
	$items['prev']['media'] = lightspeed_get_placeholder_images( 1 )['url'];
}
if ( empty( $items['next']['media'] ) && areoi2_get_option( 'areoi-lightspeed-company-include-lightspeed', false ) ) {
	$items['next']['media'] = lightspeed_get_placeholder_images( 3 )['url'];
}

$styles = '';
if ( !$next_post && !$prev_post ) {
	$styles .= '
		.' . lightspeed_get_block_id() . '.areoi-lightspeed-block {
			display: none;
		}
	';
}
?>
<?php if ( $styles ) : ?>
	<style><?php echo areoi_minify_css( $styles ) ?></style>
<?php endif; ?>

<div class="container h-100 position-relative">
	<div class="row h-100 align-items-center">

		<?php foreach ( $items as $item_key => $item ) : ?>
			
			<div class="col-md-6 position-relative areoi-has-url">
				<?php if ( !empty( $item['heading'] ) ) : ?>
				<div class="row align-items-center">
					<div class="col-6 <?php echo $item_key == 'next' ? 'order-1' : ' text-end' ?>">
						<?php lightspeed_heading( 2, $item, 'h3' ) ?>
					</div>
					<div class="col-6">
						<div class="<?php lightspeed_media_col_class() ?> h-100">
							<div class="areoi-media-col-content h-100 rounded overflow-hidden">
								<?php lightspeed_square_spacer() ?>
								<?php if ( $item['media'] ) : ?>
									<img src="<?php echo $item['media'] ?>" alt="<?php echo $item['heading'] ?>" />
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>			
				<a href="<?php echo $item['permalink'] ?>" class="stretched-link" aria-label="View <?php echo $item['heading'] ?>"></a>
				<?php endif; ?>
			</div>
			
		<?php endforeach; ?>

	</div>
</div>