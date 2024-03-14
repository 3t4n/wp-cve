<?php

namespace LitExtension;

/**
 * Class LitView
 * Function: litView
 */
class LitView
{
	public function litView($file, $_param){
		$filePath = LIT_PATH_PLUGIN . 'views/' . $file . '.phtml';
        $path = realpath($filePath);
		if (!$path) {
			throw new \Exception(sprintf(_('File "%s" not found!'), $filePath));
		}


		@include $path;

	}
}