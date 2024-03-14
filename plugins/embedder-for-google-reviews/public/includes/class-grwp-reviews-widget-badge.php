<?php

class GRWP_Reviews_Widget_Badge
	extends
	GRWP_Google_Reviews_Output {

	/**
	 * Slider HTML
	 * @return string
	 */
	public function render( $max_reviews = null ) {

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

        // Check if a user is on a trial or has an activated license
		if ( ! grwp_fs()->can_use_premium_code() ) {
            return '';
        }

        $stars = $this->get_total_stars();
		$this->place_title = $this->place_title === '' ? 'Lorem Ipsum Business' : $this->place_title;

		// loop through reviews
		$output = '<div id="g-review" class="badge">';
        $output .= sprintf('
		<a href="#badge_list"
		   class="g-badge"
		   target="_blank">
			<img src="%sdist/images/google-logo-svg.svg"
			     alt=""
			     class="g-logo"
			/>
			<span class="g-label">%s</span>
            %s
			<span class="g-rating">%s</span>
		</a>',
            GR_PLUGIN_DIR_URL,
            __('Our Google Reviews', 'grwp'),
	        $stars,
            $this->rating_formatted
        );

		$output .= '</div>';
        $output .= sprintf('<div class="g-review-sidebar right hide %s"><div class="grwp-header">', $hide_date);
        $output .= sprintf('<span class="business-title">%s</span>', $this->place_title);
        $output .=  $stars;
        $output .= sprintf('<span class="rating">%s</span>', $this->rating_formatted);
        $output .= '<span class="grwp-close"></span></div>';
        $output .= '<div class="grwp-sidebar-inner">';

		$google_svg = GR_PLUGIN_DIR_URL . 'dist/images/google-logo-svg.svg';

		$count = 0;
		foreach ( $this->reviews as $review ) {
			if ( $max_reviews && is_numeric( $max_reviews ) && intval($max_reviews) <= $count ) {
				break;
			}
			$star_output = $this->get_star_output($review);

			ob_start();
			require 'partials/grid/markup.php';
			$output .= ob_get_clean();

			$count++;
		}

        $output .= '</div></div>';

		return wp_kses( $output, $this->allowed_html );

	}

}
