<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['shifts']['location'] = array( 
	'their_class'		=> 'locations',
	'relation_name'		=> 'shift_to_location',
	'their_field'		=>	'to_id',
	'many'				=>	FALSE,
	);
$config['locations']['shifts'] = array( 
	'their_class'		=> 'shifts',
	'relation_name'		=> 'shift_to_location',
	'their_field'		=>	'from_id',
	'many'				=>	TRUE,
	);
