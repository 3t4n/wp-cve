<?php
namespace Thim_EL_Kit;

class Structured_Data {

	use SingletonTrait;

	public $data = array();

	public function __construct() {
		add_action( 'thim_ekit/elementor/widgets/breadcrumb', array( $this, 'breadcrumb_structured_data' ) );

		// Output.
		add_action( 'wp_footer', array( $this, 'output_structured_data' ), 10 );
	}

	public function breadcrumb_structured_data( $crumbs ) {
		if ( empty( $crumbs ) || ! is_array( $crumbs ) ) {
			return;
		}

		$markup                    = array();
		$markup['@type']           = 'BreadcrumbList';
		$markup['itemListElement'] = array();

		foreach ( $crumbs as $key => $crumb ) {
			$markup['itemListElement'][ $key ] = array(
				'@type'    => 'ListItem',
				'position' => $key + 1,
				'item'     => array(
					'name' => $crumb[0],
				),
			);

			if ( ! empty( $crumb[1] ) ) {
				$markup['itemListElement'][ $key ]['item'] += array( '@id' => $crumb[1] );
			} elseif ( isset( $_SERVER['HTTP_HOST'], $_SERVER['REQUEST_URI'] ) ) {
				$current_url = set_url_scheme( 'http://' . wp_unslash( $_SERVER['HTTP_HOST'] ) . wp_unslash( $_SERVER['REQUEST_URI'] ) ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized

				$markup['itemListElement'][ $key ]['item'] += array( '@id' => $current_url );
			}
		}

		$this->data[] = apply_filters( 'thim_ekit/elementor/structured_data/breadcrumblist', $markup, $crumbs );
	}

	public function output_structured_data() {
		$data = $this->get_structured_data();

		if ( ! empty( $data ) ) {
			echo '<script type="application/ld+json">' . wp_json_encode( $data ) . '</script>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		}
	}

	public function get_structured_data() {
		$data = array();

		foreach ( $this->data as $value ) {
			$data[ strtolower( $value['@type'] ) ][] = $value;
		}

		foreach ( $data as $type => $value ) {
			$data[ $type ] = count( $value ) > 1 ? array( '@graph' => $value ) : $value[0];
			$data[ $type ] = array( '@context' => 'https://schema.org/' ) + $data[ $type ];
		}

		$types = apply_filters( 'thim_ekit/elementor/structured_data/types', array( 'breadcrumblist' ) );

		$data = $types ? array_values( array_intersect_key( $data, array_flip( $types ) ) ) : array_values( $data );

		if ( ! empty( $data ) ) {
			if ( 1 < count( $data ) ) {
				$data = array( '@context' => 'https://schema.org/' ) + array( '@graph' => $data );
			} else {
				$data = $data[0];
			}
		}

		return $data;
	}
}

Structured_Data::instance();
