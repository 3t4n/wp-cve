<?php


class APAIU_HTMLParser {
	protected $_openingTag = '{{';
	protected $_closingTag = '}}';
	protected $_emailValues;
	protected $_emailHtml = null;

	/**
	 * HTMLParser constructor.
	 *
	 * @param $content
	 * @param $emailValues
	 */
	public function __construct($content, $emailValues) {
		$this->_emailValues = $emailValues;
		$this->_emailHtml = $content;
	}

	/**
	 * @return mixed|null
	 */
	public function output() {
		$html = $this->_emailHtml;
		foreach ($this->_emailValues as $key => $value) {
			$html = str_replace($this->_openingTag . $key . $this->_closingTag, $value, $html);
		}
		return $html;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return (string) $this->output();
	}
}