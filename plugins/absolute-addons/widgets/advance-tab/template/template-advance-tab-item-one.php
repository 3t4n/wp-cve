<?php
/**
 * Template Style One for Advance Tab
 *
 * @package AbsoluteAddons
 */

use Elementor\Control_Media;
use Elementor\Group_Control_Image_Size;
use Elementor\Utils;

?>
<?php if ( ! empty( $tab['advance_tab_gallery'] ) ) : ?>
	<div class="content-left">
		<div class="tab-image-gallery grid-columns-<?php echo esc_html( $tab['gallery_columns'] ); ?>">
			<?php foreach ( $tab['advance_tab_gallery'] as $gallery ) :
				$image_url = Group_Control_Image_Size::get_attachment_image_src( $gallery['id'], 'thumbnail', $tab );
				$image_html = '<img src="' . esc_url( $image_url ) . '" alt="' . esc_attr( Control_Media::get_image_alt( $gallery ) ) . '">';
				if ( 'attachment' == $tab['caption_source'] ) {
					$caption = wp_get_attachment_caption( $gallery['id'] );
				} elseif ( 'custom' == $tab['caption_source'] ) {
					$caption = ! Utils::is_empty( $tab['caption'] ) ? $tab['caption'] : '';
				}

				?>
				<figure class="tab-gallery-item">
					<?php
					echo $image_html; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
					if ( ! empty( $caption ) ) : ?>
						<figcaption class="tab-image-caption wp-caption-text"><?php absp_render_content( $caption ); ?></figcaption>
					<?php endif; ?>
				</figure>
			<?php endforeach; ?>
		</div>
	</div>
<?php endif; ?>
<div class="content-right">
	<?php $this->render_tab_content_title( $tab, $settings ); ?>
	<?php $this->render_tab_content( $tab ); ?>
	<?php $this->render_read_more( 'read-more', $tab, [ 'class' => 'tab-content-button' ] ); ?>
</div>
