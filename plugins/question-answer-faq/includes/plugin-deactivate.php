<?php

/**
 * 
 */
class QAPluginDeactivate{
	public static function deactivate() {
        flush_rewrite_rules();
    }
}