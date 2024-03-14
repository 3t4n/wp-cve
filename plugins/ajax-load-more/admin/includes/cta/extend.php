<?php
/**
 * Custom Repeaters CTA
 *
 * @package AjaxLoadMore
 */

$alm_extend_url = 'https://connekthq.com/plugins/ajax-load-more/add-ons/custom-repeaters/?utm_source=WP%20Admin&utm_medium=CustomRepeaters%20Extend&utm_campaign=Custom%20Repeaters';
?>
<div class="call-out radius-normal">
	<p>
		<i class="fa fa-unlock" aria-hidden="true"></i>
		<span>
		<?php
			// translators: %1$s is the opening <a> tag, %2$s is the closing </a> tag.
			$alm_extend_translation = sprintf( __( 'Unlock additional templates with the %1$sCustom Repeaters%2$s add-on.', 'ajax-load-more' ), '<a href="' . esc_url( $alm_extend_url ) . '">', '</a>' );
			echo wp_kses_post( $alm_extend_translation );
		?>
		</span>
	</p>
	<a class="cnkt-button" href="<?php echo esc_url( $alm_extend_url ); ?>" target="_blank">
		<i class="fa fa-angle-right" aria-hidden="true"></i>
		<?php esc_attr_e( 'Upgrade Now', 'ajax-load-more' ); ?>
	</a>
</div>
