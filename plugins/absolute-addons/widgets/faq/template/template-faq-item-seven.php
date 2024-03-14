<?php
/**
 * Template Style Eight for FAQ
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

foreach ( $settings['faq'] as $faq ) {
	$aria_expanded = $this->handle_expend_first( 'absp-faq-item', $settings, $aria_expanded );
	?>
	<div <?php $this->print_render_attribute_string( 'absp-faq-item-wrap' ); ?>>
		<article <?php $this->print_render_attribute_string( 'absp-faq-item' ); ?>>
			<h4 class="collapse-head">
				<button type="button" aria-expanded="false">
					<?php absp_render_title( $faq['faq_title'] ); ?>
					<?php if ( 'true' == $settings['faq_show_excerpt'] ) : ?>
					<div class="faq-trim-words"><?php absp_render_excerpt( $faq['faq_content'], $settings['faq_excerpt_length'] ); ?></div>
					<?php endif; ?>
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
