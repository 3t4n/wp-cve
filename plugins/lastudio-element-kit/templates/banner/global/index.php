<?php
/**
 * Loop item template
 */
?>
<figure class="lakit-banner lakit-ef-<?php $this->_html( 'animation_effect', '%s' ); ?>"><?php
	$target = $this->_get_html( 'banner_link_target', ' target="%s"' );
	$rel = $this->_get_html( 'banner_link_rel', ' rel="%s"' );

	$this->_html( 'banner_link', '<a href="%s" class="lakit-banner__link"' . $target . $rel . '>' );
		echo '<div class="lakit-banner__overlay"></div>';
		echo $this->_get_banner_image();
		echo '<div class="lakit-banner__content">';
			echo '<div class="lakit-banner__content-wrap">';
				$title_tag = $this->_get_html( 'banner_title_html_tag', '%s' );
				$title_tag = lastudio_kit_helper()->validate_html_tag( $title_tag );

				$this->_html( 'banner_title', '<' . $title_tag  . ' class="lakit-banner__title">%s</' . $title_tag  . '>' );
				$this->_html( 'banner_text', '<div class="lakit-banner__text">%s</div>' );
			echo '</div>';
		echo '</div>';
	$this->_html( 'banner_link', '</a>' );
?></figure>
