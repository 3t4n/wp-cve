<?php

defined( 'ABSPATH' ) || exit;

abstract class CPT_Component {
	/**
	 * @return mixed
	 */
	abstract public function init_hooks();
}
