<?php
/**
 * Easy Video Reviews - Admin Base
 * Admin Base
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<!-- easy video reviews main wrapper  -->
<div class="easy-video-reviews-wrapper" id="easy-video-reviews-admin">

	<?php $this->render_template( 'admin/' . $args['page'] ); ?>

	<!-- skeleton loader  -->
	<section class="easy-video-reviews-loader absolute w-full h-full left-0 top-0 bg-gray-50 p-5 flex flex-col gap-3 transition duration-300 z-50">
		<?php for ( $i = 0; $i <= 3; $i++ ) { ?>
				<div class="grid grid-cols-3 gap-3 animate-pulse">
					<div class="bg-gray-200 h-8 rounded-md"></div>
					<div class="bg-gray-200 h-8 rounded-md"></div>
					<div class="bg-gray-200 h-8 rounded-md"></div>
				</div>
				<div class="grid grid-cols-2 gap-3 animate-pulse">
					<div class="bg-gray-200 h-8 rounded-md"></div>
					<div class="bg-gray-200 h-8 rounded-md"></div>
				</div>
		<?php } ?>
	</section>

	<!-- WPPOOL Support -->
	<evr-support mail="support@wppool.dev" v-model="temp.showSupport"></evr-support>

</div>


<!-- wrapper ends  -->

<style>
	#wpcontent {
		padding: 0;
		border: none;
		box-shaow: none;
		/* position: relative; */
	}

	@media (max-width: 576px) {
		#wpcontent {
			padding-left: 0px !important;
		}
	}

	#adminmenuwrap {
		margin-top: 0px !important;
	}
</style>

<?php

// EVR Admin Footer.
do_action( 'evr_footer' );

add_filter( 'admin_footer_text', function () {
	return '<div class="text-gray-600 transition duration-150">' . __( 'Thank you for choosing Easy Video Reviews', 'easy-video-reviews' ) . '</div>';
} );


?>
