<?php

namespace ZPOS\Structure;

class ArrayObject
{
	const BEFORE = 1;
	const AFTER = 2;

	protected $array;

	public function __construct($array)
	{
		$this->array = $array;
		return $this;
	}

	public function get()
	{
		return $this->array;
	}

	public function filter($callback)
	{
		$this->array = call_user_func('array_filter', $this->array, $callback);
		return $this;
	}

	public function map($callback)
	{
		$this->array = call_user_func('array_map', $callback, $this->array, array_keys($this->array));
		return $this;
	}

	public function merge($array, $position = ArrayObject::BEFORE)
	{
		if ($array instanceof ArrayObject) {
			$array = $array->get();
		}

		$data = [$this->array, $array];
		if ($position === ArrayObject::BEFORE) {
			$data = array_reverse($data);
		}
		$this->array = call_user_func_array('array_merge', $data);
		return $this;
	}

	public function values()
	{
		$this->array = array_values($this->array);

		return $this;
	}

	public function setKeys($callback)
	{
		$keys = call_user_func('array_map', $callback, $this->array, array_keys($this->array));
		$this->array = array_combine($keys, $this->array);
		return $this;
	}
}
