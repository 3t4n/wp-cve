<?php

defined( 'ABSPATH' ) || exit;


function point_maker_textdomain_load() {
	load_plugin_textdomain( 'point-maker', false, dirname( plugin_basename( POINT_MAKER_PLUGIN_FILE ) ) .'/languages/' );
}
add_action( 'plugins_loaded', 'point_maker_textdomain_load');

function point_maker_post_css_js() {

	global $hook_suffix;

	if ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix || 'widgets.php' === $hook_suffix || 'customize.php' === $hook_suffix ){

		require_once POINT_MAKER_DIR . 'inc/functions.php';
		require_once POINT_MAKER_DIR . 'inc/settings/default_type.php';
		require_once POINT_MAKER_DIR . 'inc/colors.php';
		require_once POINT_MAKER_DIR . 'inc/icons.php';

		wp_enqueue_style( 'point_maker_admin', POINT_MAKER_URI . 'css/admin/post.min.css' );

		wp_enqueue_script( 'point_maker_post_base', POINT_MAKER_URI . 'js/admin/post_base.min.js', array(), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_content', POINT_MAKER_URI . 'js/admin/post_content.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_icon', POINT_MAKER_URI . 'js/admin/post_icon.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_title', POINT_MAKER_URI . 'js/admin/post_title.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_style', POINT_MAKER_URI . 'js/admin/post_style.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_submit', POINT_MAKER_URI . 'js/admin/post_submit.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );
		wp_enqueue_script( 'point_maker_post_setup', POINT_MAKER_URI . 'js/admin/post_setup.min.js', array('point_maker_post_base'), POINT_MAKER_VERSION , true );

		//wp_enqueue_script( 'point_maker_icons', POINT_MAKER_URI . 'js/admin/icons.min.js', array('point_maker_admin'), POINT_MAKER_VERSION , true );
		//wp_enqueue_script( 'point_maker_colors', POINT_MAKER_URI . 'js/admin/colors.min.js', array('point_maker_admin'), POINT_MAKER_VERSION , true );

		wp_enqueue_style('point_maker_font_icon', POINT_MAKER_URI . 'css/font/style.min.css',array(),POINT_MAKER_VERSION);
		wp_enqueue_style('point_maker_base', POINT_MAKER_URI . 'css/base.min.css',array(),POINT_MAKER_VERSION);


		$default_type = point_maker_default_type_settings();

		
		foreach ($default_type['type'] as $key => $value) {
			wp_enqueue_style('point_maker_type_'.$key, POINT_MAKER_URI . 'css/'.$key.'.min.css',array('point_maker_base'),POINT_MAKER_VERSION);
		}
		wp_localize_script( 'point_maker_post_base', 'point_maker_type', $default_type['type'] );

		$default_colors = point_maker_base_colors_list();
		wp_localize_script( 'point_maker_post_base', 'point_maker_colors', $default_colors );

		$svg_icons = array();
		foreach ($default_type['type_icons'] as $key => $value) {
			$svg_icons[$key] = point_maker_svg_icon_list($key);
		}
		wp_localize_script( 'point_maker_post_base', 'point_maker_icons', $svg_icons );

		wp_localize_script( 'point_maker_post_base', 'point_maker_translations', point_maker_scriput_translations() );
	}

}
add_action( 'admin_enqueue_scripts', 'point_maker_post_css_js' );


function point_maker_custom_add_quicktags() {

	global $hook_suffix;

	if ( ( 'post.php' == $hook_suffix || 'post-new.php' == $hook_suffix || 'widgets.php' === $hook_suffix  ) && wp_script_is('quicktags')){
		?>
		<script>
			function point_maker_callback() { document.getElementById('point_maker_modal_open').onclick(); }
			QTags.addButton( 'point_maker', '<?php echo '&#xf25a; '.esc_html_x('Point Maker','text_button','point-maker'); ?>', point_maker_callback );
		</script>
		<?php
	}

}
add_action( 'admin_print_footer_scripts', 'point_maker_custom_add_quicktags',11 );


function point_maker_tinymce_button() {

	add_filter( 'mce_buttons', 'point_maker_register_tinymce_button' );
	add_filter( 'mce_external_plugins', 'point_maker_tinymce_button_script' );

}
add_action( 'admin_init', 'point_maker_tinymce_button' );


function point_maker_register_tinymce_button( $buttons ) {
	array_push( $buttons, 'point_maker_button' );
	return $buttons;
}


function point_maker_tinymce_button_script( $plugin_array ) {
	$plugin_array['point_maker_script'] = POINT_MAKER_URI . 'js/admin/tinymce/tinymce.min.js';
	return $plugin_array;
}


add_shortcode('point_maker' , '__return_false' );


function point_maker_post_settings() {

	global $hook_suffix;

	if ( 'post.php' === $hook_suffix || 'post-new.php' === $hook_suffix || 'widgets.php' === $hook_suffix  ){

		require_once POINT_MAKER_DIR . 'inc/admin/post.php';
		point_maker_post_modal();

	}

}
add_action('admin_footer', 'point_maker_post_settings');


add_action('enqueue_block_editor_assets', function(){
	require_once POINT_MAKER_DIR . 'inc/admin/block.php';
	point_maker_block_control_panel();
});


function point_maker_scriput_translations() {
	return array(
		'pop_up_shortcode' => __( 'Shortcode has been inserted', 'point-maker' ),
	);
}

add_action( 'customize_controls_enqueue_scripts' , function(){
	point_maker_post_css_js();
});

add_action( 'customize_register', function(){
	require_once POINT_MAKER_DIR . 'inc/admin/block.php';
	point_maker_block_control_panel();
});
