<?php

class GRWP_Reviews_Widget_Slider
    extends
    GRWP_Google_Reviews_Output {

    /**
     * Slider HTML
     * @return string
     */
    public function render( $style_type, $max_reviews = null, $show_place_info = false ) {

        // error handling
        if ( $this->reviews_have_error ) {

            return __( 'No reviews available', 'grwp' );

        }

		$hide_date = '';
		if ( isset($this->options['hide_date_string']) ) {
			if ( $this->options['hide_date_string'] !== '' ) {
				$hide_date = 'hide_date';
			}
		}

	    $google_svg = GR_PLUGIN_DIR_URL . 'dist/images/google-logo-svg.svg';
	    $stars = $this->get_total_stars();

		$output = '';

	    $output = sprintf( '<div id="g-review" class="%s grwp_grid %s">', $style_type, $hide_date );

		if ( $show_place_info ) {

			$this->place_title = $this->place_title === '' ? 'Lorem Ipsum Business Title' : $this->place_title;

			$output .= '<div class="grwp_header">';
			$output .= '<div class="grwp_header-inner">';
			$output .= sprintf( '<h3 class="grwp_business-title">%s</h3>', $this->place_title );
			$output .= sprintf(
				'<span class="grwp_total-rating">%s</span><span class="grwp_5_stars">%s</span>',
				$this->rating_formatted,
				__( 'Out of 5 stars', 'grwp' )
			);
			$output .= $stars;
			$output .= sprintf(
				'<h3 class="grwp_overall">' . __( 'Overall rating out of %s Google reviews', 'grwp' ) . '</h3>',
				$this->total_reviews
			);
			$output .= '</div></div>';

		}

	    $output .= '<div class="grwp_body">';

        // loop through reviews
        $output .= sprintf('<div id="g-review" class="%s">', $style_type);
        $slider_output = '';

        $count = 0;
        foreach ( $this->reviews as $review ) {

            if ( $max_reviews && is_numeric( $max_reviews ) && intval($max_reviews) <= $count ) {
                break;
            }

            $star_output = $this->get_star_output($review);

            $slide_duration = $this->options['slide_duration'] ?? '';

            ob_start();
            require 'partials/slider/markup.php';
            $slider_output .= ob_get_clean();

            $count++;

        }

        ob_start();
        require 'partials/slider/slider-header.php';
        echo wp_kses( $slider_output, $this->allowed_html );
        require 'partials/slider/slider-footer.php';

        $output .= ob_get_clean();

        $output .= '</div></div></div>';

        return wp_kses( $output, $this->allowed_html );

    }

}
