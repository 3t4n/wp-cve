<?php

if ( ! isset( $field ) ) {
	return;
}
$field_type = $field->get_type();
$option_key = $field->get_option_key();
$value      = $field->get_value();
$editor_id  = str_replace( [ ']', '[' ], "_", $option_key );

if ( $field->get_helper() ): ?>
    <hr>
    <p class="hjqs-ln-helper"><?php echo $field->get_helper(); ?></p>
<?php endif;

wp_editor( $value, $editor_id, [
	'wpautop'       => false,
	'media_buttons' => false,
	'textarea_name' => $option_key,
	'textarea_rows' => 30,
	'tabindex'      => '',
	'editor_css'    => '',
	'editor_class'  => '',
	'teeny'         => false,

	'dfw'       => false,
	'tinymce'   => [
		'forced_root_block' => 'p',
		'toolbar1'          => 'formatselect,bold,italic,underline,strikethrough,|,bullist,numlist,blockquote,hr,link,unlink,|,alignleft,aligncenter,alignright,|,undo, redo',
	],
	'quicktags' => true

] );

