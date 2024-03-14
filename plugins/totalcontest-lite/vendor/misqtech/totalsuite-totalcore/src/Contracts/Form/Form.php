<?php

namespace TotalContestVendors\TotalCore\Contracts\Form;

use TotalContestVendors\TotalCore\Contracts\Helpers\Arrayable;

/**
 * Interface Form
 * @package TotalContestVendors\TotalCore\Contracts\Form
 */
interface Form extends \ArrayAccess, \Iterator, Arrayable, \Countable {
	public function validate();

	public function isValidated();

	public function errors();

	public function render();

	public function getFormElement();

	public function getSubmitButtonElement();
}