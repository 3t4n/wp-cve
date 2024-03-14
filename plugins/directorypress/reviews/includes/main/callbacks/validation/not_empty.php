<?php defined('ABSPATH') or die;

	function direviews_validate_not_empty($fieldvalue, $processor) {
		return ! empty($fieldvalue);
	}
