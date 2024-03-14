<?php
/**
 * Section Utilities config
 * 
 * @package Admin Tweaks
 */

defined( 'ABSPATH' ) || exit;
$starSVG = '<svg viewBox="0 0 576 512" width="100" title="star"><path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" /></svg>';
$adtw_print_utilities_html = <<<HTML
    <style>
        .stars {
            position: relative;
            white-space: nowrap;
            float:left;
        }
        .stars svg { width: 20px; }
        .cover {
            background: white;
            height: 100%;
            overflow: hidden;
            mix-blend-mode: color;
            position: absolute;
            top: 0;
            right: 0;
        }
        svg { fill: gold; }
    </style>
        <h3>Recommended Plugins</h3>
        <p>
        <h4>Code Snippets</h4>
        <div class="stars">
            $starSVG
            $starSVG
            $starSVG
            $starSVG
            $starSVG
            <div class="cover" style="width: 6%;"></div>
        </div>

    </p>
    
HTML;
/*
            <svg viewBox="0 0 576 512" width="100" title="star">
            <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
            </svg><svg viewBox="0 0 576 512" width="100" title="star">
            <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
            </svg><svg viewBox="0 0 576 512" width="100" title="star">
            <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
            </svg><svg viewBox="0 0 576 512" width="100" title="star">
            <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
            </svg><svg viewBox="0 0 576 512" width="100" title="star">
            <path d="M259.3 17.8L194 150.2 47.9 171.5c-26.2 3.8-36.7 36.1-17.7 54.6l105.7 103-25 145.5c-4.5 26.3 23.2 46 46.4 33.7L288 439.6l130.7 68.7c23.2 12.2 50.9-7.4 46.4-33.7l-25-145.5 105.7-103c19-18.5 8.5-50.8-17.7-54.6L382 150.2 316.7 17.8c-11.7-23.6-45.6-23.9-57.4 0z" />
            </svg>

*/
$section = array(
    'title'  => esc_html__( 'Utilities', 'mtt' ),
    'icon' => 'el el-align-center',
    'fields' => array(
        array(
            'id'         => 'opt-raw-documentation',
            'type'       => 'raw',
            'full_width' => true,
            'content'    => $adtw_print_utilities_html,
        ),
    ),
);

\Redux::set_section( $adtw_option, $section );
/*
if ( file_exists( ADTW_PATH . '/README.md' ) ) {
	$section = array(
		'title'  => esc_html__( 'Credits', 'mtt' ),
        'icon' => 'el el-align-center',
		'fields' => array(
			array(
				'id'           => 'opt-raw-documentation',
				'type'         => 'raw',
				'markdown'     => true,
				'content_path' => ADTW_PATH . '/README.md', // FULL PATH, not relative please.
			),
		),
	);

	\Redux::set_section( $adtw_option, $section );
}
*/
