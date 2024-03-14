<?php
/**
 * Template Style Three for FAQ
 *
 * @package AbsoluteAddons
 */

if ( ! defined( 'ABSPATH' ) ) {
	header( 'Status: 403 Forbidden' );
	header( 'HTTP/1.1 403 Forbidden' );
	die();
}

/**
 * @var bool $has_open
 * @var array $settings
 */
?>
<div <?php $this->print_render_attribute_string( 'absp-faq-item-wrap' ); ?>>
	<article <?php $this->print_render_attribute_string( 'absp-faq-item' ); ?>>
		<h4 class="collapse-head">
			<button type="button" aria-expanded="false">
				<?php the_title();?>
				<?php $this->faq_icon( $settings ); ?>
			</button>
		</h4>
		<div class="collapse-body">
			<?php the_content();?>
		</div>
	</article>
</div>
