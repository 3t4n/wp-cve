<?php

echo '<style>
.splide__slide img {
    width: 100%;
    height: 250px;
    object-fit: cover;
    object-position: center;
}
.splide__slide {
    border-radius: 3px;
    border: 1px solid rgba(0,0,0,.2);
    padding: 3px;
}
.splide__slide:hover {
    border-color: rgba(0,0,0,.4);
    box-shadow: 0 0 10px 0 rgba(0,0,0,.15);
}
.splide__slide-image img {
    border-radius: 3px;
}
.splide__slide-content > * {
    margin: 0;
    padding: 0;
    list-style: none;
    text-decoration: none;
}
.splide__slide-content {
    padding: 12px;
}
ul.splide__pagination {
    bottom: 0;
    margin-bottom: -30px;
}
.splide__pagination__page,
.splide__pagination__page.is-active {
    outline: 2px solid #6e41d3;
    outline-offset: 1px;
    margin: 0 6px !important;
}
.splide__pagination__page.is-active {
    background: #5027af !important;
    margin: 0 9px !important;
}
</style>';
$dataParams = array(
    'perPage' => $wppsgs_meta_slider_per_page,
    'gap'     => $wppsgs_meta_carousel_gap,
    'type'    => $wppsgs_meta_slider_type,
    'speed'    => $wppsgs_meta_slider_speed,
    'perMove' => $wppsgs_meta_slider_per_move,
    'arrows' => ( '0' != $wppsgs_meta_slider_arrows ) ? true : false,
    'pagination' => ( '0' != $wppsgs_meta_slider_pagination ) ? true : false,
    'autoplay' => ( '0' != $wppsgs_meta_slider_autoplay ) ? true : false,
    'interval' => $wppsgs_meta_slider_autoplay_interval,
);
echo '<section class="wppsgs-slider-wrapper" data-side="front" data-params="' . htmlspecialchars(json_encode($dataParams), ENT_QUOTES, 'UTF-8') . '">
<div id="wppsgs-slider-' . $post_id . '" class="splide" aria-label="Splide Basic HTML Example">
<div class="splide__track">
        <ul class="splide__list">';

while ( $wppsgs_post_query->have_posts() ) {

	$wppsgs_post_query->the_post();

	if ( $wppsgs_meta_post_image ) {

		$wppsgs_thumb_id = get_post_thumbnail_id( get_the_ID() );
		if ( $wppsgs_thumb_id ) {

				$wppsgs_thumb_attach = wp_get_attachment_image_src( $wppsgs_thumb_id, 'full' );
				$wppsgs_thumb_alt    = get_post_meta( $wppsgs_thumb_id, '_wp_attachment_image_alt', true );
			if ( empty( $wppsgs_thumb_alt ) ) {

				$wppsgs_thumb_alt = get_the_title();
			}
		}
	}

	echo '<div class="splide__slide">
            <div class="splide__slide-image">';

	if ( $wppsgs_meta_post_image ) {

		if ( $wppsgs_thumb_id ) {

			echo '<img loading="lazy" width="' . esc_attr( $wppsgs_thumb_attach[1] ) . '" height="' . esc_attr( $wppsgs_thumb_attach[2] ) . '" class="splide__slide-image" alt="' . esc_attr( $wppsgs_thumb_alt ) . '" src="' . esc_url( $wppsgs_thumb_attach[0] ) . '">';
		}
	}

	$wppsgs_excerpt_trimed = wp_trim_words( get_the_excerpt(), 30, '...' );
		echo '</div>
            <div class="splide__slide-content">
                <p>' . esc_html( $wppsgs_excerpt_trimed ) . '</p>
            </div>
            </div>';

}
		wp_reset_postdata();

	echo '</ul>
</div>
</div>
</section>';
