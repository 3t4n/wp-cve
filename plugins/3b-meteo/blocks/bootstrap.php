<?php
declare(strict_types=1);

use TreBiMeteo\IFrameBuilder;
use TreBiMeteo\PlainQueryBuilder;
use TreBiMeteo\UrlBuilder;

/**
 * @param array<int|string, mixed> $attributes
 */
function remove_from_attributes_value( array &$attributes ) {
	foreach ( $attributes as $key => $attribute ) {
		if ( is_string( $attribute ) ) {
			$attributes[ $key ] = \ltrim( $attribute, '#' );
		}
	}
}

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/block-editor/how-to-guides/block-tutorial/writing-your-first-block-type/
 */
function trebi_meteo_block_init() {

//	require_once TREBIMETEO_PATH . 'legacy/conversion.php';

	\register_block_type_from_metadata(
		__DIR__ . '/old',
		[
			'render_callback' => static function ( array $attributes, string $content, \WP_Block $block ): string {

				remove_from_attributes_value( $attributes );

				$type = [
					'trebi-a1' => 'xssmall',
					'trebi-a2' => 'lsmall',
					'trebi-a3' => 'lbigor',
					'trebi-b1' => 'treale',
					'trebi-b2' => 'ssmall',
					'trebi-c1' => 'lbig',
					'trebi-c2' => 'oraxora',
					'trebi-d1' => 'msmall',
					'trebi-d2' => 'msmacro',
					'trebi-e1' => 'lsmari',
					'trebi-e2' => 'lmari',
					'trebi-f1' => 'mmari',
					'trebi-g1' => 'lsneve',
					'trebi-g2' => 'lneve',
				];

				$type = \array_flip( $type );

				$shortcode = new \TreBiMeteo();
				return $shortcode->trebi_shortcode_handler(
					$attributes,
					null,
					$type[ $attributes['tm'] ] ?? null
				);
			},
		]
	);

	\register_block_type_from_metadata(
		__DIR__ . '/classic',
		[
			'render_callback' => static function ( array $attributes, string $content, \WP_Block $block ): string {

				remove_from_attributes_value( $attributes );

				$url = new UrlBuilder(
					'https://www.3bmeteo.com/moduli_esterni/',
					new PlainQueryBuilder()
				);

				/**
				 * @test italia_7_giorni -> 6 parametri [loc o idreg non deve esistere]
				 * regione_7_giorni_mare
				 * regione_1_giorno
				 * regione_7_giorni
				 */

				$get_loc_or_idreg = static function ( array $attributes ): string {

					if ( $attributes['tm'] === 'italia_7_giorni' ) {
						return '';
					}

					$arr = ['regione_7_giorni_mare','regione_1_giorno','regione_7_giorni'];

					if ( \in_array( $attributes['tm'], $arr ) ) {
						return 'idreg';
					}

					return 'loc';
				};

				$allowed_keys = \array_filter(
					[
						'tm',
						$get_loc_or_idreg( $attributes ),
						'colorfield3', // Color for text in header and footer
						'colorfield1', // Background color for header and footer
						'colorfield4', // Color for text in body
						'colorfield2', // Background color for body
						'lang', // bg header e footer
					]
				);

				$filtered_params = \array_filter(
					$attributes,
					static function ( string $key ) use ( $allowed_keys ): bool {
						return \in_array( $key, $allowed_keys );
					},
					ARRAY_FILTER_USE_KEY
				);

				$url->query( \array_replace( \array_flip( $allowed_keys ), $filtered_params ) );

				$default_iframe_attr = [
					'src'			=> (string) $url,
					'style'			=> \sprintf(
						'max-width:%dpx;min-height:%dpx;border:0;margin:0;padding:0;',
						$attributes['maxWidth'],
						$attributes['minHeight']
					),
				];

				return (string) ( new IFrameBuilder( $default_iframe_attr ) );
			},
		]
	);

	\register_block_type_from_metadata( __DIR__ . '/flex' );
}
