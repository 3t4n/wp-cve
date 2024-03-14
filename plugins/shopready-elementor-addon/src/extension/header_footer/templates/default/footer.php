<?php
if (!defined('ABSPATH')) {
	exit;
}
echo wp_kses_post('<div class="shop--ready-footer-content-wrapper">');
do_action('woo_ready_footer_builder_before');
do_action('woo_ready_footer_builder');
do_action('woo_ready_footer_builder_after');
echo wp_kses_post("</div>");
wp_footer();
?>

</body>

</html>