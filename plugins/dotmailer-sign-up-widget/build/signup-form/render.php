<?php

namespace Dotdigital_WordPress_Vendor;

/**
 * Block Name: Dotdigital Signup Form
 *
 * @package    Dotdigital_WordPress
 * @var array $attributes
 */
the_widget('DM_Widget', array(), array('showtitle' => $attributes['showtitle'] ?? \false, 'showdesc' => $attributes['showdesc'] ?? \false, 'is_ajax' => $attributes['is_ajax'] ?? \false, 'redirection' => $attributes['redirecturl'] ?? null));
