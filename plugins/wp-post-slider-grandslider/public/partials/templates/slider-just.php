<?php

echo '<style>
.wppsgs-cover-wrap {
    position: relative;
    background-size: cover;
    background-position: 50%;
    min-height: 430px;
    width: 100%;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 1em;
    box-sizing: border-box;
}
.wppsgs-cover-wrap img {
    position: absolute;
    left: 0;
    top: 0;
    right: 0;
    bottom: 0;
    object-fit: cover;
    width: 100%;
    height: 100%;
    max-width: none;
    max-height: none;
}
.wppsgs-cover__inner-container {
    z-index: 1;
    color: #000;
    background: rgb(255 255 255 / 50%);
    max-width: 900px;
    padding: 40px;
    box-shadow: rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px;
    text-shadow: 0 1px 0 rgb(255 255 255 / 40%);
}
.wppsgs-cover__inner-container > * {
    margin: 0;
    padding: 0;
    list-style: none;
    text-decoration: none;
}
.wppsgs-cover__background {
    position: absolute;
    left: 0;
    top: 0;
    background: #000;
    z-index: 1;
    right: 0;
    bottom: 0;
    opacity: .3;
}
.wppsgs-cover__inner-container .wppsgs__post-title {
    font-size: 32px;
    font-weight: bold;
    margin-bottom: 17px;
}
.wppsgs-cover__inner-container p {
    font-size: 18px;
    line-height: 28px;
    margin-bottom: 22px;
}
a.wppsgs__post-link {
    font-size: 18px;
    display: inline-block;
    background: #fff;
    padding: 9px 20px;
    box-shadow: 4px 4px 0 -1px #fff;
    border: 1px solid #9e9e9e;
    transition: .3s;
}
a.wppsgs__post-link:hover {
    background-color: #5027af;
    color: #fff;
}
.wppsgs-cover__inner-container a:hover {
    box-shadow: 0 0;
}
.wppsgs__cat-wrap {
    margin-bottom: 4px;
}
.wppsgs__cat-wrap a {
    display: inline-block;
    text-decoration: none;
    color: #222;
    background: #ffc107;
    font-size: 16px;
    line-height: 16px;
    padding: 4px 10px;
    border-radius: 2px;
    border: 1px solid transparent;
    transition: .3s;
}
.wppsgs__cat-wrap a:hover {
    border: 1px solid #fff;
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
}';
echo $wppsgs_meta_slider_layer ? '' : '.wppsgs-cover__inner-container {background: transparent !important;box-shadow: 0 0 !important;}';
echo '</style>';
$dataParams = array(
    'width'   => $wppsgs_slider_layout_dimensions['width'],
    'height'   => $wppsgs_slider_layout_dimensions['height'],
    'unit'   => $wppsgs_slider_layout_dimensions['unit'], 
    'perPage' => 1,
    'gap'     => 0,
    'type'    => $wppsgs_meta_slider_type,
    'speed'    => $wppsgs_meta_slider_speed,
    'perMove' => $wppsgs_meta_slider_per_move,
    'arrows' => ( '0' != $wppsgs_meta_slider_arrows ) ? true : false,
    'pagination' => ( '0' != $wppsgs_meta_slider_pagination ) ? true : false,
    'autoplay' => ( '0' != $wppsgs_meta_slider_autoplay ) ? true : false,
    'interval' => $wppsgs_meta_slider_autoplay_interval,
    'pauseOnHover' => ( $wppsgs_meta_slider_pauseonhover ) ? true : false,
    'lazyLoad' => $wppsgs_meta_slider_lazyload,
);
echo '<section class="wppsgs-slider-wrapper" data-side="front" data-params="' . htmlspecialchars(json_encode($dataParams), ENT_QUOTES, 'UTF-8') . '">
<div id="wppsgs-slider-' . $post_id . '" class="splide" aria-label="Slider Title">
<div class="splide__track">
    <div class="splide__list">';

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
        <div class="wppsgs-cover-wrap">
            <span aria-hidden="true" class="wppsgs-cover__background"></span>';

	if ( $wppsgs_meta_post_image ) {

		if ( $wppsgs_thumb_id ) {

			echo '<img loading="lazy" width="' . esc_attr( $wppsgs_thumb_attach[1] ) . '" height="' . esc_attr( $wppsgs_thumb_attach[2] ) . '" class="splide__slide-image" alt="' . esc_attr( $wppsgs_thumb_alt ) . '" src="' . esc_url( $wppsgs_thumb_attach[0] ) . '">';
		}
	}

	echo '<div class="wppsgs-cover__inner-container">';

    if ( $wppsgs_meta_post_cat ) {

        $wppsgs_category_name = get_the_category( get_the_ID() );
        if ( $wppsgs_category_name ) {

            echo '<div class="wppsgs__cat-wrap">';
            foreach ( $wppsgs_category_name as $wppsgs_category ) {

                echo '<span class="wppsgs__cat-name">
                        <a href="' . esc_url( get_category_link( $wppsgs_category->cat_ID ) ) . '">' . esc_html( $wppsgs_category->cat_name ) . '</a>
                    </span>';
            }
            echo '</div>';
        }
    }

    if ( $wppsgs_meta_post_title ) {

        echo '<' . esc_html( $wppsgs_meta_post_title_tag ) . ' class="wppsgs__post-title">' . esc_html( get_the_title() ) . '</' . esc_html( $wppsgs_meta_post_title_tag ) . '>';
    }

    if ( $wppsgs_meta_post_excerpt ) {

        $wppsgs_excerpt_trimed = wp_trim_words( get_the_excerpt(), 56, '...' );
        echo '<p>' . esc_html( $wppsgs_excerpt_trimed ) . '</p>';
    }

    if ( $wppsgs_meta_readmore_btn ) {

        echo '<a class="wppsgs__post-link" href="' . esc_url( get_the_permalink() ) . '">Read More</a>';
    }

    echo '</div>
        </div>
    </div>';
}
wp_reset_postdata();

echo '</div>
    </div>
</div>
</section>';
