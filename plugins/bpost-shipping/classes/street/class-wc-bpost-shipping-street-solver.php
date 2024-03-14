<?php
namespace WC_BPost_Shipping\Street;

/**
 * Class WC_BPost_Shipping_Street_Solver resolves a 'full' address to street, number and box.
 */
class WC_BPost_Shipping_Street_Solver {

	/**
	 * @param string $full_street
	 *
	 * @return WC_BPost_Shipping_Street_Result
	 */
	public function get_street_data_from_full_street( $full_street ) {
		// Use https://www.regex101.com to debug regexp :)

		$regexp_lines = array(
			array( // basic
				'regexp' => '/(.*)/',
				'street' => 1,
				'number' => - 1,
				'box'    => - 1,
			),
			array( // Belgian format. Without "," to separate the street and the number. Can have 2 street numbers (interval)
				'regexp' => '/^(.*[a-zA-Z])\ *((\d+\w*)(\-\d*\w*)?)$/',
				'street' => 1,
				'number' => 2,
				'box'    => - 1,
			),
			array( // Belgian format. "," mandatory to separate the street and the number+box
				'regexp' => '/^(.*\w)\ *\,\ *(\d+\w*)[\/\ ]*(\d*\w*)$/',
				'street' => 1,
				'number' => 2,
				'box'    => 3,
			),
			array( // Belgian format. Without "," to separate the street and the number. Don't detect the box
				'regexp' => '/^(.*[a-zA-Z])\ *(\d+\w*)([\/\ ]*)(\d*\w*)$/',
				'street' => 1,
				'number' => 2,
				'box'    => - 1,
			),
			array( // Belgian format. Without "," to separate the street and the number. Don't detect the box
				'regexp' => '/^(.*[a-zA-Z])\ *(\d+\w*)[\/\ ]+(\d+\w*)$/',
				'street' => 1,
				'number' => 2,
				'box'    => 3,
			),
			array( // Belgian format. Without "," to separate the street and the number. Detect the box
				// Respect the bpost recommendations: http://www.bpost.be/site/en/residential/letters-cards/send/addressing.html#4
				'regexp' => '/^(.*\w)\ *,?\ +(\d+\w*)\ (bte|box|boite|boîte|bus)\ (\d*\w*)$/',
				'street' => 1,
				'number' => 2,
				'box'    => 4,
			),
		);

		$best_result = new WC_BPost_Shipping_Street_Result();

		foreach ( $regexp_lines as $regexp_line ) {
			$result = new WC_BPost_Shipping_Street_Result();

			if ( preg_match( $regexp_line['regexp'], $full_street, $matches ) ) {
				$result = $this->get_result( $matches, $regexp_line );
			}

			if ( $result->get_score() > $best_result->get_score() ) {
				$best_result = $result;
			}
		}

		return $best_result;
	}

	/**
	 * @param array $matches
	 * @param array $regexp_line
	 *
	 * @return WC_BPost_Shipping_Street_Result
	 */
	private function get_result( array $matches, array $regexp_line ) {
		$result = new WC_BPost_Shipping_Street_Result();

		if ( isset( $matches[ $regexp_line['street'] ] ) ) {
			$result->set_street( $matches[ $regexp_line['street'] ] );
		}

		if ( isset( $matches[ $regexp_line['number'] ] ) ) {
			$result->set_number( $matches[ $regexp_line['number'] ] );
		}

		if ( isset( $matches[ $regexp_line['box'] ] ) ) {
			$result->set_box( $matches[ $regexp_line['box'] ] );
		}

		return $result;
	}
}
