<?php
/**
 * Template Style Nine for FAQ
 *
 * @package AbsoluteAddons
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @var array $settings
 */
?>
<div class="faq-info" style="background: url('<?php echo esc_url( $settings['faq_image']['url'] ) ?>') center center / cover;">
	<h2 class="faq-highlight-text"><?php absp_render_title( $settings['faq_highlight_text'] ); ?></h2>
	<span class="faq-sub-text"><?php echo esc_html( $settings['faq_sub_text'] ); ?></span>
</div>
<?php
foreach ( $settings['faq'] as $faq ) {
	$aria_expanded = $this->handle_expend_first( 'absp-faq-item', $settings, $aria_expanded );
?>
	<div <?php $this->print_render_attribute_string( 'absp-faq-item-wrap' ); ?>>
		<article <?php $this->print_render_attribute_string( 'absp-faq-item' ); ?>>
			<h4 class="collapse-head">
				<button type="button" aria-expanded="false">
					<?php absp_render_title( $faq['faq_title'] ); ?>
					<?php $this->faq_icon( $settings ); ?>
				</button>
			</h4>
			<div class="collapse-body">
				<?php absp_render_content( $faq['faq_content'] ); ?>
			</div>
		</article>
	</div>
	<?php
}
