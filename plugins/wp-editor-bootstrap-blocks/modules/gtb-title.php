<?php 
/**
 * Bootstrap Blocks for WP Editor Title.
 *
 * @version 1.1.0
 *
 * @package Bootstrap Blocks for WP Editor
 * @author  Virgial Berveling
 * @updated 2019-02-01
 * 
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function load_mod_gtb_title()
{
	GutenbergBootstrap::AddModule('title',array(
		'name' => 'Template title modifier',
		'version'=>'1.1.0'
	));
	function init_mod_gtb_title()
	{
		function gtb_wrap_the_title($title, $id = null)
		{

			global $post;

			$gtb_hide_title = get_post_meta($post->ID, 'gtb_hide_title', true);
			$gtb_wrap_title = get_post_meta($post->ID, 'gtb_wrap_title', true);
			$title_class = get_post_meta($post->ID, 'gtb_class_title', true);
			if (!empty($title_class)) $title_class = ' class="'.esc_attr($title_class).'"';

			if (empty($gtb_hide_title)) : 
					if (!empty($gtb_wrap_title)):
				
			$title =
	'<div class="container">
		<div class="row">
			<div class="col-xl-12">
				<header'.$title_class.'>'.$title.'</header>
			</div>
		</div>
	</div>
	';
				endif;
			else:
				$title = '';
			endif;
			return $title;
		}
	}

	add_action('gtb_init','init_mod_gtb_title');
}
add_action('gtb_bootstrap_modules','load_mod_gtb_title');
