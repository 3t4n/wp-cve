<?php


namespace WC_BPost_Shipping\street;


class WC_BPost_Shipping_Street_Result {
	/** @var string */
	private $street = '';
	/** @var string */
	private $number = '';
	/** @var string */
	private $box = '';
	/** @var int */
	private $score = -1;

	/**
	 * @return string
	 */
	public function get_street() {
		return $this->street;
	}

	/**
	 * @param string $street
	 */
	public function set_street( $street ) {
		$this->increase_score();
		$this->street = $street;
	}

	/**
	 * @return string
	 */
	public function get_number() {
		return $this->number;
	}

	/**
	 * @param string $number
	 */
	public function set_number( $number ) {
		$this->increase_score();
		$this->number = $number;
	}

	/**
	 * @return string
	 */
	public function get_box() {
		return $this->box;
	}

	/**
	 * @param string $box
	 */
	public function set_box( $box ) {
		$this->increase_score();
		$this->box = $box;
	}

	/**
	 * @return int
	 */
	public function get_score() {
		return $this->score;
	}

	private function increase_score() {
		$this->score++;
	}




}
