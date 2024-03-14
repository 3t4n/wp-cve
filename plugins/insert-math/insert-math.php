<?php
/*
Plugin Name: Insert math
Plugin URI: https://github.com/CMTV/wordpress-plugin-insert-math
Text Domain: insert-math
Domain Path: /languages
Description: Fast and handy insert any math formulas in your posts.
Version: 2.0
Author: CMTV
License: GPL3
*/

define('INSERT_MATH_MATHJAX_URL', 'https://cdnjs.cloudflare.com/ajax/libs/mathjax/2.7.1/MathJax.js');
define('INSERT_MATH_PLUGIN_URL', plugin_dir_url(__FILE__));

/* ------------------------------------------------------------------------------------------------------------------ */
/* Adding MathJax support, necessary jQuery libraries and styles to both frontend and admin panel */
/* ------------------------------------------------------------------------------------------------------------------ */

function insert_math_enqueue_scripts() {

	/* jQuery and other libraries */
	wp_enqueue_script('jquery');
	wp_enqueue_script('jquery-ui-core');
	wp_enqueue_script('jquery-ui-dialog');
	wp_enqueue_script('iris');

	/* MathJax support */
	wp_enqueue_script('mathjax-config', INSERT_MATH_PLUGIN_URL . 'mathjax/config.js');
	wp_enqueue_script('mathjax', INSERT_MATH_MATHJAX_URL, ['mathjax-config']);
	wp_enqueue_style('mathjax-scrollmath', INSERT_MATH_PLUGIN_URL . 'mathjax/scrollmath.css');

	/* jQuery dialog UI default stylesheet */
	wp_enqueue_style('jquery-ui', INSERT_MATH_PLUGIN_URL . 'jquery-ui-css/jquery-ui.css');

	/* Insert math dialog */
	wp_enqueue_script('insert-math-dialog', INSERT_MATH_PLUGIN_URL . 'dialog/dialog.js', [
	        'mathjax', 'jquery', 'jquery-ui-core', 'jquery-ui-dialog', 'iris'
    ]);
	wp_enqueue_style('insert-math-dialog', INSERT_MATH_PLUGIN_URL . 'dialog/dialog.css');

}
add_action('wp_enqueue_scripts',    'insert_math_enqueue_scripts');
add_action('admin_enqueue_scripts', 'insert_math_enqueue_scripts');

/* ------------------------------------------------------------------------------------------------------------------ */
/* Adding dialog HTML code to both frontend and admin panel */
/* ------------------------------------------------------------------------------------------------------------------ */

function insert_math_add_dialog_html() {
?>

<div id="insert_math-dialog" title="<?php _e('Inserting math', 'insert-math'); ?>" data-title="<?php _e('Inserting math', 'insert-math'); ?>" data-title-edit="<?php _e('Editing math', 'insert-math'); ?>">

    <div class="insert_math-display-mode-container insert_math-container">
        <div class="insert_math-label"><?php _e('Insert math as', 'insert-math'); ?></div>
        <div class="insert_math-display-block insert_math-button insert_math-checked"><?php _e('Block', 'insert-math'); ?></div>
        <div class="insert_math-display-inline insert_math-button"><?php _e('Inline', 'insert-math'); ?></div>
    </div>

    <div class="insert_math-additional-settings-container insert_math-container">
        <div class="insert_math-additional-settings-header"><?php _e('Additional settings', 'insert-math'); ?></div>

        <div class="insert_math-additional-settings">

            <div class="insert_math-color-container insert_math-container">
                <div class="insert_math-label"><?php _e('Formula color', 'insert-math'); ?></div>
                <div class="insert_math-color-default insert_math-button insert_math-checked"><?php _e('Text color', 'insert-math'); ?></div>
                <div class="insert_math-color-custom insert_math-button" contenteditable="true" style="color: #333333;">#333333</div>
            </div>

            <div class="insert_math-id-container insert_math-container">
                <label for="insert_math-formula-id" class="insert_math-label"><?php _e('Formula ID', 'insert-math'); ?></label>
                <input type="text" id="insert_math-formula-id">
            </div>

            <div class="insert_math-classes-container insert_math-container">
                <label for="insert_math-formula-classes" class="insert_math-label"><?php _e('Formula classes', 'insert-math'); ?></label>
                <input type="text" id="insert_math-formula-classes">
            </div>

        </div>
    </div>

    <div class="insert_math-expression-container insert_math-container">
        <div class="insert_math-expression-tip">
            <?php printf(__('Type math using %s', 'insert-math'), '<a href="https://en.wikibooks.org/wiki/LaTeX/Mathematics#Symbols" target="_blank">LaTeX</a>'); ?>
        </div>
        <textarea id="insert_math-expression" class="insert_math-expression" rows="3" placeholder="<?php _e('Start typing math here...', 'insert-math'); ?>"></textarea>
    </div>

    <div class="insert_math-preview-container insert_math-container">
        <div class="insert_math-preview-header">
            <?php _e('Preview', 'insert-math'); ?>
            <span class="insert_math-preview-icon dashicons dashicons-update"></span>
        </div>
        <div class="insert_math-preview">
            <div id="insert_math-preview" class="insert_math-preview-math">\({}\)</div>
            <div class="insert_math-preview-empty"><?php _e('Nothing to preview', 'insert-math'); ?></div>
        </div>
    </div>

    <div class="insert_math-insert" data-value="<?php _e('Insert', 'insert-math'); ?>" data-value-edit="<?php _e('Edit', 'insert-math'); ?>"><?php _e('Insert', 'insert-math'); ?></div>

</div>

<?php
}
add_action('wp_footer', 'insert_math_add_dialog_html');
add_action('admin_footer', 'insert_math_add_dialog_html');

/* ------------------------------------------------------------------------------------------------------------------ */
/* TinyMCE */
/* ------------------------------------------------------------------------------------------------------------------ */

/* Passing button icon url and button tooltip to global 'Insert_Math_Dialog' class */
function insert_math_constants_for_tinymce() {
	$constants = [];
	$constants['BUTTON_ICON_URL'] = INSERT_MATH_PLUGIN_URL . 'tinymce/button-icon.svg';
	$constants['BUTTON_TOOLTIP'] = __('Insert/edit math', 'insert-math');

	wp_add_inline_script('insert-math-dialog', 'jQuery(function () { Insert_Math_Dialog.TinyMCE = ' . json_encode($constants) . '; });', 'after');
}
add_action('wp_enqueue_scripts', 'insert_math_constants_for_tinymce');
add_action('admin_enqueue_scripts', 'insert_math_constants_for_tinymce');

/* Registering plugin */
function insert_math_register_tinymce_plugin($plugins) {
    $plugins['insert_math'] = INSERT_MATH_PLUGIN_URL . 'tinymce/plugin.js';
    return $plugins;
}
add_filter('mce_external_plugins', 'insert_math_register_tinymce_plugin');

/* Adding button before 'wp_adv' button */
function insert_math_add_tinymce_button($buttons) {
	array_splice($buttons, array_search('wp_adv', $buttons), 0, 'insert_math-button');
	return $buttons;
}
add_filter('mce_buttons', 'insert_math_add_tinymce_button');

/* Adding stylesheet */
function insert_math_add_tinymce_stylesheet() {
    $stylesheet_url = INSERT_MATH_PLUGIN_URL . 'tinymce/editor.css?' . rand();

	if (is_admin()) {
		add_editor_style($stylesheet_url);
	}

	global $editor_styles;
	if(is_array($editor_styles)) {
		array_push($editor_styles, $stylesheet_url);
	} else {
		$editor_styles = [$stylesheet_url];
	}
}
add_action('wp_head', "insert_math_add_tinymce_stylesheet");
add_action('admin_init', "insert_math_add_tinymce_stylesheet");

/* ------------------------------------------------------------------------------------------------------------------ */
/* Translations */
/* ------------------------------------------------------------------------------------------------------------------ */

function insert_math_load_textdomain() {
	load_plugin_textdomain('insert-math', FALSE, basename(dirname(__FILE__)) . '/languages/');
}
add_action('plugins_loaded', 'insert_math_load_textdomain');