<?php

namespace ZPOS;

class Translation
{
	public function __construct()
	{
		load_plugin_textdomain(
			'zpos-wp-api',
			false,
			dirname(plugin_basename(PLUGIN_ROOT_FILE)) . '/lang/'
		);
	}
}
