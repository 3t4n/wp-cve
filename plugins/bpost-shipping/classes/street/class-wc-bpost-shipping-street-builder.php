<?php
namespace WC_BPost_Shipping\Street;

/**
 * Class WC_BPost_Shipping_Street_Builder
 * TODO a builder does reference to a pattern with a directory etc, not used in this place
 * TODO may be a factory? check the book
 */
class WC_BPost_Shipping_Street_Builder {

	/** @const string separator between first & second line */
	const INLINE_SEPARATOR = ' - ';

	/** @var  WC_BPost_Shipping_Street_Solver */
	private $bpost_street_solver;

	/**
	 * @param WC_BPost_Shipping_Street_Solver $bpost_street_solver
	 */
	public function __construct( WC_BPost_Shipping_Street_Solver $bpost_street_solver ) {
		$this->bpost_street_solver = $bpost_street_solver;
	}

	/**
	 * @param string $street_line_1
	 * @param string $street_line_2
	 *
	 * @return WC_BPost_Shipping_Street_Result
	 */
	public function get_street_items( $street_line_1, $street_line_2 ) {
		$street_data = $this->bpost_street_solver->get_street_data_from_full_street( $street_line_1 );

		if ( ! empty( $street_line_2 ) ) {
			$street_data->set_street( $street_data->get_street() . self::INLINE_SEPARATOR . $street_line_2 );
		}

		return $street_data;
	}

	/**
	 * @param string $street
	 * @param string $number
	 * @param string $box
	 *
	 * @return array
	 */
	public function get_street_lines( $street, $number, $box ) {

		$street_lines = explode( self::INLINE_SEPARATOR, $street );
		if ( ! isset( $street_lines[1] ) ) {
			$street_lines[1] = ''; // We need 2 lines for the address
		}
		if ( $number ) {
			$street_lines[0] .= ' ' . $number;
		}
		if ( $box ) {
			$street_lines[0] .= ' box ' . $box;
		}

		return $street_lines;
	}
}
