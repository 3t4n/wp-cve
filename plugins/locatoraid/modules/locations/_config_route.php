<?php if (! defined('ABSPATH')) exit; // Exit if accessed directly
$config['route']['locations']				= '/locations/index/controller';
$config['route']['locations/{id}/edit']		= '/locations/edit/controller';
$config['route']['locations/{id}/update']	= '/locations/edit/controller/update';
$config['route']['locations/{id}/delete']	= '/locations/delete/controller';

$config['route']['locations/add']			= '/locations/new/controller/add';
