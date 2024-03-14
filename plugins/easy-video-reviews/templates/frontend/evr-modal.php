<?php
/**
 * Easy Video Reviews - Frontend Modal
 * Frontend Modal
 *
 * @package EasyVideoReviews
 */
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit( 1 );
?>
<!-- recorder modal -->
<div data-evr-modal-frame class="data-evr-modal-frame" style="display: none;">
	<div class="evr-modal-frame">
		<div class="evr-overlay"></div>
		<div class="evr-frame-modal-body mx-2">
			<?php
				$this->render_template('frontend/recorder');
			?>
		</div>
	</div>
</div>
<!--  recorder modal -->
