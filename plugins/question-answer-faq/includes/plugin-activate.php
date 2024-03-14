<?php

/**
 * 
 */
class QAPluginActivate{
	
	public static function activate() {
        // $this->create_mideal_faq();
        flush_rewrite_rules();
    }
}