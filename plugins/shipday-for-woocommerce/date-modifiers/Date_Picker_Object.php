<?php

class Date_Picker_Object {
	protected $pickup_date_time;
	protected $delivery_date_time;
	protected $time_zone;

	protected $delivery_time_flag = false;
	protected $pickup_time_flag = false;

	protected $utc;
	protected $date_format = 'Y-m-d';
	protected $time_format = 'H:i:s';


	public function has_pickup_date() {
		return isset($this->pickup_date_time);
	}
	public function has_pickup_time() {
		return $this->pickup_time_flag;
	}
	public function has_delivery_date() {
		return isset($this->delivery_date_time);
	}
	public function has_delivery_time() {
		return $this->delivery_time_flag;
	}


	public function get_pickup_date() {
		return $this->pickup_date_time->format($this->date_format);
	}
	public function get_pickup_time() {
		return $this->pickup_date_time->format($this->time_format);
	}
	public function get_delivery_date() {
		return $this->delivery_date_time->format($this->date_format);
	}

	public function get_delivery_time() {
		return $this->delivery_date_time->format($this->time_format);
	}
}