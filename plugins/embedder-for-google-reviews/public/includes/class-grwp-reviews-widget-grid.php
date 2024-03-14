<?php

class GRWP_Reviews_Widget_Grid
    extends
    GRWP_Google_Reviews_Output {

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

	    $output = sprintf('<div id="g-review" class="%s grwp_grid %s">', $style_type, $hide_date);

		if ( $show_place_info ) {

			$this->place_title = $this->place_title === '' ? 'Lorem Ipsum Business' : $this->place_title;

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
        $count = 0;
        foreach ( $this->reviews as $review ) {

            if ( $max_reviews && is_numeric( $max_reviews ) && intval($max_reviews) <= $count ) {
                break;
            }

            $star_output = $this->get_star_output($review);

            ob_start();
            require 'partials/badge/markup.php';
            $output .= ob_get_clean();

            $count++;

        }

        $output .= '</div></div>';

        return wp_kses( $output, $this->allowed_html );

    }
}
