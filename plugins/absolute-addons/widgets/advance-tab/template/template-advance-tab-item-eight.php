<?php
/**
 * Template Style Eight for Advance Tab
 *
 * @package AbsoluteAddons
 * @var $tab
 */

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;

?>
<?php if ( ! empty( $tab['tab_image']['id'] ) ) : ?>
	<div class="content-left">
		<?php
		$image_url  = Group_Control_Image_Size::get_attachment_image_src( $tab['tab_image']['id'], 'thumbnail', $tab );
		$image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $tab ) ) . '">';
		$caption    = wp_get_attachment_caption( $tab['tab_image']['id'] );
		?>
		<figure class="tab-image">
			<?php echo $image_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			if ( ! empty( $caption ) ) : ?>
				<figcaption class="content-image-caption wp-caption-text"><?php absp_render_content( $caption ); ?></figcaption>
			<?php endif; ?>
		</figure>
	</div>
<?php endif; ?>
<div class="content-right">
	<?php $this->render_tab_content_title( $tab, $settings ); ?>
	<?php $this->render_tab_content( $tab ); ?>
	<?php $this->render_read_more( 'read-more', $tab, [ 'class' => 'tab-content-button' ] ); ?>
</div>
