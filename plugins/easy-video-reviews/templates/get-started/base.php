<?php
/**
 * Easy Video Reviews - Get Started
 * Get Started
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<div class="wrap easy-video-reviews-wrapper prose overflow-hidden" id="easy-video-reviews-get-started">



	<!-- wrapper starts  -->
	<div class="flex items-center justify-center evr-full-height">



		<!-- form  -->
		<v-section class="sm:w-96 relative shadow-lg overflow-hidden opacity-0 transition duration-150" id="auth_form">

			<div class="mb-2 m-0 p-0 uppercase tracking-wide">
				<div class="text-xs font-medium text-gray-400 flex items-center gap-2 h-6"><img class="w-6 h-6" src="<?php echo esc_url( plugin_dir_url( EASY_VIDEO_REVIEWS_FILE ) ); ?>public/images/evr.svg" alt="Easy Video Reviews"> <span class="tracking-widest"><?php esc_html_e( 'Welcome to', 'easy-video-reviews' ); ?></span></div>
				<span class="text-sky-500 text-base font-semibold sm:text-2xl "><?php esc_html_e( 'Easy Video Reviews', 'easy-video-reviews' ); ?></span>
			</div>

			<!-- signup  -->
			<?php $this->render_template( 'get-started/signup-form' ); ?>


			<!-- signin  -->
			<?php $this->render_template( 'get-started/signin-form' ); ?>


			<!-- forgot section  -->
			<?php $this->render_template( 'get-started/forget-password-form' ); ?>

			<!-- <div class="border-b p-3"></div> -->
			<div class="my-3 border-b-2 border-gray-100 block w-full"></div>
			<div class="flex items-center justify-center text-sm gap-2 text-gray-400">
				<a class="cursor-pointer no-underline text-sky-500 hover:text-sky-600 cursor-pointer" @click.prevent="temp.showSupport = 1"><?php esc_html_e( 'Get Support', 'easy-video-reviews' ); ?></a>
				<span>|</span>
				<div><?php echo wp_sprintf( 'Powered by %s', '<a class="no-underline text-gray-400 hover:text-gray-600 font-normal transition duration-100" href="https://wppool.dev/?ref=' . esc_url( home_url() ) . '" target="_blank" rel="nofollow">WPPOOL</a>' ); ?></div>.
			</div>

		</v-section>

		<evr-support mail="support@wppool.dev" v-model="temp.showSupport"></evr-support>

	</div>
	<!-- wrapper ends  -->
</div>
<script>
	var _evr_screen = '<?php echo esc_attr( 'get_started' ); ?>';
</script>
<?php

// EVR Admin Footer.
do_action( 'evr_footer' );

?>
