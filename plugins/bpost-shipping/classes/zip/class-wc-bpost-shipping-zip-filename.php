<?php
namespace WC_BPost_Shipping\Zip;


/**
 * Class WC_BPost_Shipping_Zip_Filename generates filename for zip archive
 * @package WC_BPost_Shipping\Zip
 */
class WC_BPost_Shipping_Zip_Filename {
	/** filename prefix  */
	const ZIP_BPOST_LABELS_PREFIX_FILENAME = 'bpost_labels_';
	/** format for timestamp */
	const ZIP_BPOST_DATE_FORMAT = 'dmy_His';

	/** @var \DateTime */
	private $datetime;

	/**
	 * WC_BPost_Shipping_Zip_Filename constructor.
	 *
	 * @param \DateTime $datetime
	 */
	public function __construct( \DateTime $datetime ) {
		$this->datetime = $datetime;
	}


	/**
	 * @return string
	 */
	public function get_filename() {
		return self::ZIP_BPOST_LABELS_PREFIX_FILENAME . $this->datetime->format( self::ZIP_BPOST_DATE_FORMAT );
	}

}
