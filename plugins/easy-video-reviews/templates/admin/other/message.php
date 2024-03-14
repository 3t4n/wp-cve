<?php
/**
 * Easy Video Reviews - Admin Notice
 * Admin Notice
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<div id="evr_notice" style="width: calc(100vw-40px);" class=" mx-auto p-3 rounded-sm shadow my-3 text-base text-center flex items-center justify-center gap-3 border-l-8 bg-white font-medium shadow mx-4 text-slate-700 
<?php
echo 'error' === $args['type'] ? 'border-red-600' : 'border-green-600';
?>
">
	<?php echo esc_html( $args['message'] ); ?>

	<?php if ( isset( $dismissible ) && $dismissible ) : ?>
			<div>
				<button id="evr_notice_handler" class="px-3 py-1 rounded-sm bg-sky-600 hover:bg-sky-700 text-white text-xs transition duration-150">Dismiss</button>
			</div>

			<script>
				var evr_notice = document.getElementById('evr_notice');
				var evr_notice_handler = document.getElementById('evr_notice_handler');

				evr_notice_handler.addEventListener('click', function() {
					evr_notice.remove();
				});
			</script>
	<?php endif; ?>

</div>
