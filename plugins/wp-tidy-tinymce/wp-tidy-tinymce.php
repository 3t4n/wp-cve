<?php
/*
 * Plugin Name: WP Tidy TinyMCE
 * Description: Simple options to tidy up the uncommonly used buttons and controls from WordPress TinyMCE editor
 * Version: 2.0
 * Author: Adam Pope
 * Author URI: http://www.stormconsultancy.co.uk
 * License: MIT
 *
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

// Plugin Version
define('WP_TIDY_TINYMCE_CURRENT_VERSION', '2' );

if ( version_compare( get_bloginfo( 'version' ), '3.9', '<' ) ) {
    include_once('wp-lt-39.php');
}else{

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
    'alignjustify' => "Justify text",
    'outdent' => "Outdent",
    'indent' => "Indent",
    'charmap' => "Special Characters",
    'wp_help' => "Help",
    'pastetext' => "Paste",
    'removeformat' => "Remove formatting",
    'undo' => "Undo",
    'redo' => "Redo",
    'bullist' => "Bullet list",
    'numlist' => "Numbered List",
    'blockquote' => "Block quote",
    'aligncenter' => "Center align",
    'alignright' => "Right align",
    'alignleft' => "Left align",
    'spellchecker' => "Spell checker",
    'link' => 'Link',
    'unlink' => 'Unlink',
    'fullscreen' => 'Fullscreen',
    'hr' => 'Horizontal Rule'
  );

  function delete_button($buttons) {
    global $TINYMCE_ADV_BUTTONS;

    foreach($buttons as $idx => $value){
      $opt = get_option('adv_button_'.$value);

      if($opt == '1'){
        unset($buttons[$idx]);
      }
    }

     return $buttons;
  }

  add_filter('mce_buttons', 'delete_button');
  add_filter('mce_buttons_2', 'delete_button');

  function filter_block_formats( $init ) {
    global $TINYMCE_BLOCK_FORMATS;

    $formats = "";

    foreach($TINYMCE_BLOCK_FORMATS as $k => $v){
      $opt = get_option('blockformat_'.$k);

      if($opt == "1"){
        $formats .= "$v=$k;";
      }
    }

    $formats = rtrim($formats, ";");

    $init['block_formats'] = $formats; //"Paragraph=p; Heading 3=h3; Heading 4=h4";

    return $init;
  }

  add_filter('tiny_mce_before_init', 'filter_block_formats');
}



require_once (dirname(__FILE__).'/wp-tidy-tinymce-options.php');

?>
