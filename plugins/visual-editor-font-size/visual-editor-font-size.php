<?php
/*
Plugin Name: Visual Editor Font Size
Plugin URI: http://wordpress.org/extend/plugins/visual-editor-font-size/
Description: Allows you to change the font size of the visual editor
Author: Nikolay Bachiyski
Author URI: http://nikolay.bg/
Version: 0.2
*/

class WriteFieldFontSize {

	function init() {
		if ( is_admin() ) {
			add_action( 'admin_menu', array( &$this, 'register_boxes' ) );
		}
		// TODO: find a way not to monopolize the setup callback
		add_filter( 'tiny_mce_before_init', create_function('$a', '$a["setup"] = "visual_editor_font_size_tinymce_setup"; return $a;'));
	}

	function register_boxes() {
		add_meta_box( 'visual-editor-font-size', 'Visual Editor Font Size', array( &$this, 'box_contents' ), 'post' );
		add_meta_box( 'visual-editor-font-size', 'Visual Editor Font Size', array( &$this, 'box_contents' ), 'page' );
		// TODO: make sure we are hooked after after wp_tiny_mce
		add_action( 'admin_print_footer_scripts', array( &$this, 'set_size' ), 25 + 10 );
	}

	function set_size() {
		echo "
		<script type='text/javascript'>
			function visual_editor_font_size_tinymce_setup(ed) {
				ed.onPostRender.add(function(ed, cm) {
					var new_size = getUserSetting('visual_editor_font_size').replace('_', '.');
					if (new_size) {
						jQuery('#content_ifr').contents().find('#tinymce').css('font-size', new_size);
						jQuery('#visual-editor-font-size-sample').css('font-size', new_size);
					}
				});
				return true;
			}
		</script>
		";
	}

	function box_contents( $output = true ) {
		$contents = <<<HTML
			<script type="text/javascript">
				jQuery(function($) {
					var change_size = function(elem, percent) {
						var new_size = '13px';
						if (!elem.length) {
							alert('Setting the font size works only when the visual editor is active.');
							return;
						}
						if (percent != 0) {
							var current_size = elem.css('font-size');
							var size_and_quantity = current_size.match(/^[\d.]+(.*)$/);
							new_size = (parseFloat(size_and_quantity[0]) * (1 + (percent/100))).toString() + size_and_quantity[1];
						}
						elem.css('font-size', new_size);
						$('#visual-editor-font-size-sample').css('font-size', new_size);
						setUserSetting('visual_editor_font_size', new_size.toString().replace('.', '_'));
					}
					var change_callback_factory = function(coeff) {
						return function() {
							change_size($('#content_ifr').contents().find('#tinymce'), coeff*10);
							return false;
						};
					}

					$('#visual-editor-font-size-increase').click(change_callback_factory(1.0));
					$('#visual-editor-font-size-decrease').click(change_callback_factory(-1.0));
					$('#visual-editor-font-size-revert').click(change_callback_factory(0));
				});
			</script>
			<a id="visual-editor-font-size-increase" href="#" class="button-primary">+</a>
			<a id="visual-editor-font-size-decrease" href="#" class="button-primary">-</a>
			<a id="visual-editor-font-size-revert" href="#">Revert</a>
			&nbsp;&nbsp;&nbsp;
			Sample: <span id="visual-editor-font-size-sample" style="font-family: Georgia;">Current Size</span>
HTML;
		if ($output) echo $contents;
		return $contents;
	}

}
$_visual_editor_font_size = new WriteFieldFontSize();
add_action( 'init', array( $_visual_editor_font_size, 'init' ) );
