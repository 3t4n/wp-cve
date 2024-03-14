<?php

use Elementor\Icons_Manager;

$html_tag = $settings['title_tag'];
$class    = 'xpro-post-title';
$class   .= ( $settings['icon']['value'] && $settings['icon_align'] ) ? ' xpro-post-title-icon-' . $settings['icon_align'] : '';

$post_data = get_demo_post_data();

if ( isset( $post_data ) ) {
	$title_text = $post_data->post_title;
}

if ( empty( $title_text ) ) {
	$title_text = get_the_title();
}

if ( $settings['post_link'] === 'yes' ) { ?>
	<a class="xpro-post-title-link" href="<?php echo esc_url( get_permalink() ); ?>">
	<?php
}
?>

<<?php echo esc_attr( $html_tag ); ?> class="<?php echo esc_attr( $class ); ?>">
<?php if ( $settings['icon']['value'] ) : ?>
	<span class="xpro-post-title-icon">
			<?php Icons_Manager::render_icon( $settings['icon'], array( 'aria-hidden' => 'true' ) ); ?>
		</span>
<?php endif; ?>
<span class="xpro-post-title-text">
		<?php xpro_elementor_kses( $title_text ); ?>
	</span>
</<?php echo esc_attr( $html_tag ); ?>>

<?php if ( $settings['post_link'] === 'yes' ) { ?>
	</a>
	<?php
}
