<?php
/*
 * Copyright (c) 2012-2014 Storm Consultancy (EU) Ltd,
 * http://www.stormconsultancy.co.uk/
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */

$TINYMCE_BLOCK_FORMATS = array(
    'h1' => 'Heading 1',
    'h2' => 'Heading 2',
    'h3' => 'Heading 3',
    'h4' => 'Heading 4',
    'h5' => 'Heading 5',
    'h6' => 'Heading 6',
    'p' => 'Paragraph',
    'address' => 'Address',
    'pre' => 'Preformatted'
    );

$TINYMCE_ADV_BUTTONS = array(
    'strikethrough' => "Strikethrough",
    'underline' => "Underline",
    'bold' => "Bold",
    'italic' => "Italic",
    'forecolor' => "Font color",
    'justifyfull' => "Justify text",
    'outdent' => "Outdent",
    'indent' => "Indent",
    'charmap' => "Special Characters",
    'help' => "Help",
    'pasteword' => "Paste from Word",
    'pastetext' => "Paste as plain text",
    'removeformat' => "Remove formatting",
    'undo' => "Undo",
    'redo' => "Redo",
    'bullist' => "Bullet list",
    'numlist' => "Numbered List",
    'blockquote' => "Block quote",
    'justifycenter' => "Center align",
    'justifyright' => "Right align",
    'justifyleft' => "Left align",
    'spellchecker' => "Spell checker"
    );

/**
 * Add styles/classes to the "Styles" drop-down
 */
add_filter( 'tiny_mce_before_init', 'wp_tidy_tinymce_before_init' );

function wp_tidy_tinymce_before_init( $settings ) {
  global $TINYMCE_BLOCK_FORMATS;
  global $TINYMCE_ADV_BUTTONS;

  $block_formats = array();
  $buttons = array();

  foreach($TINYMCE_BLOCK_FORMATS as $k => $v){
    $opt = get_option('blockformat_'.$k);

    if($opt == "1"){
      array_push($block_formats, $k);
    }
  }

  foreach($TINYMCE_ADV_BUTTONS as $k => $v){
    $opt = get_option('adv_button_'.$k);

    if($opt == "1"){
      array_push($buttons, $k);
    }
  }

  // Limit the format available
  $settings['theme_advanced_blockformats'] = implode(",", $block_formats);

  // Remove toys we dont want them playing with
  $settings['theme_advanced_disable'] = implode(",", $buttons);

  return $settings;
}
?>
