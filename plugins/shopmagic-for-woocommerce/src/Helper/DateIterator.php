<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

class DateIterator implements \IteratorAggregate {

	/** @var int */
	private $duration;

	public function __construct(int $duration = 30) {
		$this->duration = $duration;
	}

	public function get_date_labels() {
		return array_map(
			static function ( \DateTimeInterface $date ): string {
				return $date->format( WordPressFormatHelper::MYSQL_DATE_FORMAT );
			},
			iterator_to_array( $this->getIterator() )
		);
	}

	/** @return \DatePeriod */
	public function getIterator(): \Traversable {
		$current  = new \DateTimeImmutable('+1 day');
		$interval = $this->duration_to_interval();
		$start    = $current->sub( $interval );

		return new \DatePeriod( $start, new \DateInterval( "P1D" ), $current );
	}

	private function duration_to_interval(): \DateInterval {
		$duration = "P".$this->duration. "D";
		return new \DateInterval($duration);
	}
}
