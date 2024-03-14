<?php

class GRWP_Shortcode {

    /**
     * Plugin Options/settings.
     * @var null
     */
    private $options = null;

    public function __construct() {

        $this->options = get_option( 'google_reviews_option_name' );

        add_shortcode('google-reviews', [ $this, 'reviews_shortcode' ] );

    }

    /**
     * Get style override value from shortcode attributes
     * @param array $atts
     * @return string
     */
    private function get_review_style_override( array $atts ) {

        $review_style_override = '';

        if ( isset($atts['style']) ) {
            $override = $atts['style'];

            switch ( $override ) {
                case '1':
                    $review_style_override = 'layout_style-1';
                    break;
                case '2':
                    $review_style_override = 'layout_style-2';
                    break;
                case '3':
                    $review_style_override = 'layout_style-3';
                    break;
                case '4':
                    $review_style_override = 'layout_style-4';
                    break;
                case '5':
                    $review_style_override = 'layout_style-5';
                    break;
                case '6':
                    $review_style_override = 'layout_style-6';
                    break;
                case '7':
                    $review_style_override = 'layout_style-7';
                    break;
                case '8':
                    $review_style_override = 'layout_style-8';
                    break;
            }
        }

        return $review_style_override;

    }


    /**
     * Get type override value from shortcode attributes
     * @param array $atts
     * @return string
     */
    private function get_review_type_override( array $atts ) {

        $result = '';

        if ( isset( $atts['type'] ) ) {

	        switch ( $atts['type'] ) {
		        case 'grid':
					$result = 'grid';
					break;
		        case 'slider':
					$result = 'slider';
					break;
		        case 'badge':
					$result = 'badge';
					break;
	        }

            // $result = $atts['type'] === 'grid' ? 'grid' : 'slider';
        }

        return $result;
    }

    /**
     * Parse shortcode data, return html
     * @param array|null $atts
     * @return string
     */
    public function reviews_shortcode( $atts = null ) : string {

        // get style/type override values
        $review_type_override = '';
        $review_style_override = '';
        $max_reviews = null;
	    $show_place_info = false;

        if ( $atts ) {

            $review_type_override = $this->get_review_type_override( $atts );
            $review_style_override = $this->get_review_style_override( $atts );
			$place_info = isset($atts['place_info']) ? $atts['place_info'] : null;
	        $show_place_info = $place_info === 'true';
            $max_reviews = isset($atts['max_reviews']) ? $atts['max_reviews'] : null;

        }

        // check if style type is overwritten by shortcode attributes
        $style_type = $this->options['layout_style'];

        if ( $review_style_override !== '' ) {

            $style_type = $review_style_override;

        }

        // check if widget type is overwritten by shortcode attributes
        $widget_type = strtolower($this->options['style_2']);
        if ( $review_type_override !== '' ) {

            $widget_type = $review_type_override;

        }

        if ( $widget_type === 'slider' ) {
            $slider = new GRWP_Reviews_Widget_Slider();
            return $slider->render( $style_type, $max_reviews, $show_place_info );
        }

		elseif ( $widget_type === 'badge' ) {
			$badge = new GRWP_Reviews_Widget_Badge();
			return $badge->render( $max_reviews );
		}

        $grid = new GRWP_Reviews_Widget_Grid();
        return $grid->render( $style_type, $max_reviews, $show_place_info );

    }


}
