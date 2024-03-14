<?php

namespace TotalContestVendors\TotalCore\Contracts\Form;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Page
 * @package TotalContestVendors\TotalCore\Contracts\Form
 */
interface Page extends \ArrayAccess, \Iterator, Arrayable, \Countable {
	/**
	 * @return mixed
	 */
	public function validate();

	/**
	 * @return mixed
	 */
	public function errors();

	/**
	 * @return mixed
	 */
	public function render();
}