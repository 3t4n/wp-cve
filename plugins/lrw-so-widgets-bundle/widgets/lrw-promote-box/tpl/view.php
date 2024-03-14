<?php
$src = siteorigin_widgets_get_attachment_image_src( $background, 'full', $background_fallback );
if ( $height && $src ) :
	echo '<div class="lrw-promote-box has-bg promote-align-' . $align . '" style="background-image: url( ' . $src[0] . ' );">';
else :
	echo '<div class="lrw-promote-box promote-align-' . $align . '">';
	if ( $src ) echo '<img src="' . $src[0] . '" class="lrw-promote-image">';
endif;
	echo '<div class="lrw-promote-overlay"></div>';
	echo '<div class="lrw-promote-infos">';
		if ( $title ) echo '<' . $title_type . ' class="lrw-promote-title">' . $title . '</' . $title_type . '>';
		if ( $content ) echo '<div class="lrw-promote-content">' . wp_kses_post( $content ) . '</div>';
		if ( ! empty( $buttons ) ) :
			echo '<div class="lrw-promote-button">';
				foreach ( $buttons as $button ) :
					$this->sub_widget( 'SiteOrigin_Widget_Button_Widget', $args, $button['button'] );
				endforeach;
			echo '</div>';
		endif;
	echo '</div>';
echo '</div>';