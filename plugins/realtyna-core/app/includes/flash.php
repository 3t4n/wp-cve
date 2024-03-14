<?php
// no direct access
defined('ABSPATH') or die();

if(!class_exists('RTCORE_Flash')):

/**
 * RTCORE Flash Message Class.
 *
 * @class RTCORE_Flash
 * @version	1.0.0
 */
class RTCORE_Flash extends RTCORE_Base
{
    /**
	 * Constructor method
	 */
	public function __construct()
    {
        parent::__construct();
	}

    public static function add($message, $class = 'info')
    {
        $classes = array('error', 'info', 'success', 'warning');
        if(!in_array($class, $classes)) $class = 'info';

        $flash_messages = maybe_unserialize(get_option('rtcore_flash_messages', array()));
        $flash_messages[$class][] = $message;

        update_option('rtcore_flash_messages', $flash_messages);
	}

    public static function show()
    {
        $flash_messages = maybe_unserialize(get_option('rtcore_flash_messages', ''));
        if(!is_array($flash_messages)) return;

        foreach($flash_messages as $class=>$messages)
        {
            foreach($messages as $message) echo '<div class="notice notice-'.$class.' is-dismissible"><p>'.$message.'</p></div>';
        }

        // Clear flash messages
        delete_option('rtcore_flash_messages');
	}
}

endif;