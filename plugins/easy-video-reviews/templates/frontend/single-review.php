<?php
/**
 * Easy Video Reviews - Single Review
 * Single Review
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );



if ( ! isset( $args ) ) {
	wp_die( esc_html__( 'Video not found', 'easy-video-reviews' ), esc_html__( 'Video not found', 'easy-video-reviews' ) );
}

$review = $args['review'];



?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php the_title(); ?></title>
	<link rel="shortcut icon" href="<?php echo esc_url( get_site_icon_url() ); ?>" type="image/x-icon">
	<?php //phpcs:ignore ?>
    <link rel="stylesheet" href="<?php echo esc_url( EASY_VIDEO_REVIEWS_PUBLIC ); ?>/css/app.min.css">

</head>

<body>

	<?php
	if ( $review && ! empty( $review ) ) :
		?>

			<div class="w-full overflow-hidden <?php echo ! ( $args['is_headless'] ) ? 'p-3' : ''; ?>">
				<div class="text-center bg-gray-100 text-gray-500 <?php echo ! ( $args['is_headless'] ) ? 'max-w-screen-lg mx-auto  my-4  rounded-md' : ''; ?> w-full">

					<div class="evr-review">
						<div class="rounded-md bg-gray-50 overflow-hidden flex flex-col items-center justify-center h-full">
							<div class="relative w-full">
								<div class="z-50 w-full h-full cursor-pointer rounded-sm">
									<?php if ( ( isset ( $review->type ) && 'video' === $review->type ) || ! isset ( $review->type ) ) : ?>
										<video class="z-50 h-full w-full cursor-pointer rounded-sm <?php echo ( $video->meta->flip ? $video->meta->flip : false ) ? esc_html ( 'evr-flip' ) : ''; ?>">
											<source src="<?php echo esc_attr( $review->video_url ); ?>" type="video/mp4">
											<source src="<?php echo esc_attr( $review->video_url ); ?>" type="video/ogg">
											Your browser does not support the video tag.
										</video>
									<?php endif; ?>
									<?php if ( isset ( $review->type ) && 'text' === $review->type ) : ?>
										<p><?php echo esc_html( $review->text_url ); ?></p>
									<?php endif; ?>
									<?php if ( isset ( $review->type ) && 'both' === $review->type ) : ?>
										<video class="z-50 h-full w-full cursor-pointer rounded-sm <?php echo ( $video->meta->flip ? $video->meta->flip : false ) ? esc_html( 'evr-flip' ) : ''; ?>">
											<source src="<?php echo esc_attr( $review->video_url ); ?>" type="video/mp4">
											<source src="<?php echo esc_attr( $review->video_url ); ?>" type="video/ogg">
											Your browser does not support the video tag.
										</video>
										<p><?php echo esc_html( $review->text_url ); ?></p>
									<?php endif; ?>
								</div>
								<div class="absolute top-0 left-0 w-full h-full flex items-center justify-center z-50 evr-review-overlay">
									<a data-play class="evr-review-play cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white" style="color: white">
										<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
											<path d="m11.596 8.697-6.363 3.692c-.54.313-1.233-.066-1.233-.697V4.308c0-.63.692-1.01 1.233-.696l6.363 3.692a.802.802 0 0 1 0 1.393z" />
										</svg>
									</a>
									<a style="display:none" data-stop class="evr-review-stop cursor-pointer opacity-50 hover:opacity-70 transition duration-150 text-white" style="color: white">
										<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-20 w-20" viewBox="0 0 16 16">.
											<path d="M5.5 3.5A1.5 1.5 0 0 1 7 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5zm5 0A1.5 1.5 0 0 1 12 5v6a1.5 1.5 0 0 1-3 0V5a1.5 1.5 0 0 1 1.5-1.5z" />
										</svg>
									</a>
								</div>
							</div>
							<div class="w-full bg-gray-100 py-3 px-4 text-gray-400 text-sm flex items-center justify-between">

								<span><?php echo esc_html( $video->created_at ? $video->created_at : '' ); ?></span>

								<div class="relative">
									<div class="evr-social-share relative inline-flex justify-end items-center">

										<div class="evr-social-share-button flex items-center">
											<a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php echo esc_url( urldecode( $args['page_url'] ) ); ?>" class="sharer flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 mr-2 text-white hover:text-white cursor-pointer">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-4 w-4" viewBox="0 0 24 24">
													<path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
												</svg>
											</a>
											<a target="_blank" href="http://twitter.com/share?text=Video Review&url=<?php echo esc_url( urldecode( $args['page_url'] ) ); ?>" class="sharer flex items-center justify-center h-8 w-8 rounded-full bg-blue-400 mr-2 text-white hover:text-white cursor-pointer">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-4 w-4" viewBox="0 0 24 24">
													<path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
												</svg>
											</a>
											<a target="_blank" href="https://www.linkedin.com/sharing/share-offsite/?url=<?php echo esc_url( home_url( 'video/?id=' . $review->slug ) ); ?>" class="sharer flex items-center justify-center h-8 w-8 rounded-full bg-blue-500 mr-2 text-white hover:text-white top-6 left-4 cursor-pointer">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-4 w-4" viewBox="0 0 24 24">
													<path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z" />
												</svg>
											</a>
											<a target="_blank" href="http://pinterest.com/pin/create/link/?url=<?php echo esc_url( site_url() ); ?>/video/?id=<?php echo esc_url( urldecode( $args['page_url'] ) ); ?>" class="sharer flex items-center justify-center h-8 w-8 rounded-full bg-red-500 mr-2 text-white hover:text-white -top-14 left-14 cursor-pointer">
												<svg xmlns="http://www.w3.org/2000/svg" class="fill-current h-4 w-4" viewBox="0 0 24 24">
													<path d="M12 0c-6.627 0-12 5.372-12 12 0 5.084 3.163 9.426 7.627 11.174-.105-.949-.2-2.405.042-3.441.218-.937 1.407-5.965 1.407-5.965s-.359-.719-.359-1.782c0-1.668.967-2.914 2.171-2.914 1.023 0 1.518.769 1.518 1.69 0 1.029-.655 2.568-.994 3.995-.283 1.194.599 2.169 1.777 2.169 2.133 0 3.772-2.249 3.772-5.495 0-2.873-2.064-4.882-5.012-4.882-3.414 0-5.418 2.561-5.418 5.207 0 1.031.397 2.138.893 2.738.098.119.112.224.083.345l-.333 1.36c-.053.22-.174.267-.402.161-1.499-.698-2.436-2.889-2.436-4.649 0-3.785 2.75-7.262 7.929-7.262 4.163 0 7.398 2.967 7.398 6.931 0 4.136-2.607 7.464-6.227 7.464-1.216 0-2.359-.631-2.75-1.378l-.748 2.853c-.271 1.043-1.002 2.35-1.492 3.146 1.124.347 2.317.535 3.554.535 6.627 0 12-5.373 12-12 0-6.628-5.373-12-12-12z" fill-rule="evenodd" clip-rule="evenodd" />
												</svg>
											</a>

										</div>



									</div>
								</div>
							</div>

						</div>


					</div>

			<?php endif; ?>

			<script>
				(function() {
					const play = document.querySelector('[data-play]');
					const stop = document.querySelector('[data-stop]');
					const video = document.querySelector('video');

					play.addEventListener('click', () => {
						video.play();
						play.style.display = 'none';
						stop.style.display = 'block';
					});

					stop.addEventListener('click', () => {
						video.pause();
						// stop video .
						video.currentTime = 0;
						play.style.display = 'block';
						stop.style.display = 'none';
					});

				})()
			</script>
</body>

</html>


<?php exit; ?>
