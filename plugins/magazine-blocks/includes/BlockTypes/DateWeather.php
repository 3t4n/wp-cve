<?php
/**
 * Date & Weather block.
 *
 * @package Magazine Blocks
 */

namespace MagazineBlocks\BlockTypes;

use MagazineBlocks\Helper;

defined( 'ABSPATH' ) || exit;

/**
 * Button block class.
 */
class DateWeather extends AbstractBlock {

	/**
	 * Block name.
	 *
	 * @var string Block name.
	 */
	protected $block_name = 'date-weather';

	public function render( $attributes, $content, $block ) {
		$client_id = magazine_blocks_array_get( $attributes, 'clientId', '' );

		# The Loop.
		$html = '';

		$html .= '<div class="mzb-date-weather mzb-date-weather-' . $client_id . '">';
		$html .= '<span class="mzb-weather-icon">';
		$html .= '<svg className="mzb-weather-icon mzb-weather-icon--cloud-moon-rain" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
				<path
					d="M14.604 10.57c-.287-1.743-1.637-3.07-3.27-3.07-.513 0-.996.14-1.43.375C9.18 6.745 8.004 6 6.667 6c-2.209 0-4 2.016-4 4.5 0 .023.008.052.008.075C1.15 10.917 0 12.431 0 14.25 0 16.322 1.492 18 3.333 18H14c1.842 0 3.333-1.678 3.333-3.75 0-1.838-1.175-3.36-2.729-3.68zm9.059-.08c-2.934.624-5.625-1.888-5.625-5.193 0-1.903.912-3.656 2.395-4.599.23-.145.171-.534-.087-.585A5.455 5.455 0 0019.242 0c-3.246 0-5.88 2.869-6.017 6.464 1.113.558 2.008 1.584 2.454 2.892 1.546.67 2.667 2.222 2.925 4.069.213.023.417.07.634.07 1.862 0 3.566-.947 4.691-2.498.175-.225-.008-.563-.266-.506zm-8.476 9.108c-.316-.201-.724-.084-.908.282l-1.525 3c-.183.36-.07.815.25 1.022a.615.615 0 00.33.098.66.66 0 00.579-.38l1.524-3c.18-.36.071-.815-.25-1.022zm-4 0c-.316-.201-.724-.084-.908.282l-1.525 3c-.183.36-.07.815.25 1.022a.615.615 0 00.33.098.66.66 0 00.579-.38l1.524-3c.18-.36.071-.815-.25-1.022zm-4 0c-.316-.201-.724-.084-.908.282l-1.525 3c-.183.36-.07.815.25 1.022a.615.615 0 00.33.098.66.66 0 00.579-.38l1.524-3c.18-.36.071-.815-.25-1.022zm-4 0c-.316-.201-.724-.084-.908.282l-1.525 3c-.183.36-.07.815.25 1.022a.615.615 0 00.33.098.66.66 0 00.579-.38l1.524-3c.18-.36.071-.815-.25-1.022z" />
				</svg>';
		$html .= '</span>';
		$html .= '<span class="mzb-temperature">';
		$html .= print_r( Helper::show_temp(), true );
		$html .= '° ';
		$html .= '</span>';
		$html .= '<div class="mzb-weather-date">';
		$html .= print_r( Helper::show_weather(), true );
		$html .= ', ';
		$html .= date( 'F j, Y' );
		$html .= ' in ';
		$html .= print_r( Helper::show_location(), true );
		$html .= '</div>';
		$html .= '</div>';

		return $html;
	}
}
