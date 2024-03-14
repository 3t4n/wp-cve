<?php
$color_rgb = $this->hex_to_rgb( get_option( $this->settings->base . 'color', $this->get_default_value( 'color' ) ) );
$color_rgb = 'rgba(' .$color_rgb['r']. ',' .$color_rgb['g']. ',' .$color_rgb['b']. ', ' .get_option( $this->settings->base . 'opacity', $this->get_default_value( 'opacity' ) ). ')';

$rollover_color_rgb = $this->hex_to_rgb( get_option( $this->settings->base . 'rollover_color', $this->get_default_value( 'rollover_color' ) ) );
$rollover_color_rgb = 'rgba(' .$rollover_color_rgb['r']. ',' .$rollover_color_rgb['g']. ',' .$rollover_color_rgb['b']. ', ' .get_option( $this->settings->base . 'rollover_opacity', $this->get_default_value( 'rollover_opacity' ) ). ')';

$indentation 		= get_option( $this->settings->base . 'indentation', $this->get_default_value( 'indentation' ) );
$bottom_indentation = get_option( $this->settings->base . 'bottom_indentation', $this->get_default_value( 'bottom_indentation' ) );

$width 	= get_option( $this->settings->base . 'width', $this->get_default_value( 'width' ) );
$height = get_option( $this->settings->base . 'height', $this->get_default_value( 'height' ) );
?>

<style>

.otb-beam-me-up-scotty {
	background-color: <?php echo $color_rgb; ?>;
	right: <?php echo $indentation; ?>px;
	bottom: <?php echo $bottom_indentation; ?>px;
}

.otb-beam-me-up-scotty.custom {
	width: <?php echo $width; ?>px;
	height: <?php echo $height; ?>px;
}

.otb-beam-me-up-scotty.custom i {
	line-height: <?php echo $height; ?>px;
}

.otb-beam-me-up-scotty i,
.otb-beam-me-up-scotty:hover i {
	color: <?php echo get_option( $this->settings->base . 'icon_color', $this->get_default_value( 'icon_color' ) ); ?>;
}

.otb-beam-me-up-scotty .rollover {
	background-color: <?php echo $rollover_color_rgb; ?>;
}

</style>
