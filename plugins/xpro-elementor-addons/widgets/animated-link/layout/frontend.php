<?php

use Elementor\Icons_Manager;

$html_tag = ( $settings['link']['url'] ) ? 'a' : 'span';
$attr     = ( $settings['link_css_id'] ) ? ' id="' . $settings['link_css_id'] . '"' : '';
$attr    .= $settings['link']['is_external'] ? ' target="_blank"' : '';
$attr    .= $settings['link']['nofollow'] ? ' rel="nofollow"' : '';
$attr    .= $settings['link']['url'] ? ' href="' . $settings['link']['url'] . '"' : '';
$attr    .= ( $settings['onclick_event'] ) ? ' onclick="' . $settings['onclick_event'] . '"' : '';

if ( $settings['link'] && $settings['link']['custom_attributes'] ) {
	$attributes = explode( ',', $settings['link']['custom_attributes'] );

	foreach ( $attributes as $attribute ) {
		if ( ! empty( $attribute ) ) {
			$custom_attr = explode( '|', $attribute, 2 );
			if ( ! isset( $custom_attr[1] ) ) {
				$custom_attr[1] = '';
			}
			$attr .= ' ' . $custom_attr[0] . '="' . $custom_attr[1] . '"';
		}
	}
}


?>

<<?php echo esc_attr( $html_tag ); ?> <?php echo $attr; ?> data-hover="<?php echo esc_html( $settings['text'] ); ?>" class="xpro-animated-link xpro-animated-link-<?php echo esc_attr( $settings['layouts'] ); ?>">
<?php
if ( 'style-10' === $settings['layouts'] ) {
	$str    = $settings['text'];
	$length = strlen( $str );
	for ( $index = 0; $index < $length; $index ++ ) {
		echo '<span class="animated-link-text" style="transition-delay: ' . $index * 20 . 'ms">' . $str[ $index ] . '</span>';
	}
} else {
	?>
	<span class="animated-link-text"><?php echo esc_html( $settings['text'] ); ?></span>
	<?php
	if ( 'style-21' === $settings['layouts'] ) {
		?>
		<svg class="xpro-animated-link-graphic xpro-animated-link-graphic-stroke xpro-animated-link-graphic-arc" width="100%" height="100%" viewBox="0 0 59 18">
			<path d="M.945.149C12.3 16.142 43.573 22.572 58.785 10.842" pathLength="1"/>
		</svg>
		<?php
	}
}

if ( 'style-22' === $settings['layouts'] ) {
	?>
	<svg class="xpro-animated-link-graphic xpro-animated-link-graphic-stroke xpro-animated-link-graphic-scribble" width="100%" height="100%" viewBox="0 0 101 9">
		<path d="M.426 1.973C4.144 1.567 17.77-.514 21.443 1.48 24.296 3.026 24.844 4.627 27.5 7c3.075 2.748 6.642-4.141 10.066-4.688 7.517-1.2 13.237 5.425 17.59 2.745C58.5 3 60.464-1.786 66 2c1.996 1.365 3.174 3.737 5.286 4.41 5.423 1.727 25.34-7.981 29.14-1.294" pathLength="1"/>
	</svg>
<?php } ?>
<?php
if ( 'style-23' === $settings['layouts'] ) {
	?>
	<svg class="xpro-animated-link-graphic xpro-animated-link-graphic-slide" width="300%" height="100%" viewBox="0 0 1200 60" preserveAspectRatio="none">
		<path d="M0,56.5c0,0,298.666,0,399.333,0C448.336,56.5,513.994,46,597,46c77.327,0,135,10.5,200.999,10.5c95.996,0,402.001,0,402.001,0"></path>
	</svg>
<?php } ?>

</<?php echo esc_attr( $html_tag ); ?>>
