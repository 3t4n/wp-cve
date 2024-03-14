<?php
/*
Plugin Name: Power Code Editor
Plugin URI: http://anetech.eu/power-code-editor/
Description: Helps you more effectively edit your themes or plugins using only a browser, by enabling syntax highlighting in WordPress' integrated source code editors. Supports PHP, HTML, CSS and JavaScript. 
Author: Mayel de Borniol
Version: 1.2.1
Author URI: http://deborniol.com/
*/

if( !class_exists( 'PowerCodeEditor' ) ){

class PowerCodeEditor
{

  function PowerCodeEditor(){
    if( is_admin() )
    {
      if( strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), 'plugin-editor.php' ) !== false || strpos( strtolower( $_SERVER[ 'REQUEST_URI' ] ), 'theme-editor.php' ) !== false ){
        add_filter( 'admin_footer', array( &$this, 'add_pwe' ) );
      }
    }
  }
  
  
  function add_pwe(){
    $url = plugins_url()."/power-code-editor/"; 
    echo <<<DATA
<!-- START Power Code Editor Plugin -->
<script type="text/javascript" src="{$url}js/codemirror.js"></script>
<script type="text/javascript">
/* <![CDATA[ */
( function() {
// get textarea
var pwe_textarea = document.getElementById('newcontent');
if(pwe_textarea ) {
  /// detect filetype
  var pwe_file = document.getElementsByName( 'file' );
  var pwe_file_type = 'php';
  var pwe_parserfile= ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "tokenizephp.js", "parsephp.js", "parsephphtmlmixed.js"];
  var pwe_stylesheet= ["{$url}css/xmlcolors.css", "{$url}css/jscolors.css", "{$url}css/csscolors.css", "{$url}css/phpcolors.css"];

  if( pwe_file && pwe_file.length > 0 ) {
    if( pwe_file[ 0 ].value.match( '\.css' ) ) {
      pwe_file_type = 'css';
      pwe_parserfile= ["parsecss.js"];
      pwe_stylesheet= ["{$url}css/csscolors.css"];
    }
    else if( pwe_file[ 0 ].value.match( '\.js' ) ) {
      pwe_file_type = 'js';
      pwe_parserfile= ["tokenizejavascript.js", "parsejavascript.js"];
      pwe_stylesheet= ["{$url}css/jscolors.css"];
    }
    else if( pwe_file[ 0 ].value.match( '\.html' ) ) {
      pwe_file_type = 'html';
      pwe_parserfile= ["parsexml.js", "parsecss.js", "tokenizejavascript.js", "parsejavascript.js", "parsehtmlmixed.js"];
      pwe_stylesheet= ["{$url}css/xmlcolors.css", "{$url}css/jscolors.css", "{$url}css/csscolors.css"];
    }
  }
  // load editor
  var editor = CodeMirror.fromTextArea(pwe_textarea, {
      parserfile: pwe_parserfile,
       stylesheet: pwe_stylesheet,
       width: "95%",
       height: "500px",
      path: "{$url}js/"
  });
}
} )();
/* ]]> */
</script>
<!-- // END Power Code Editor Plugin -->
DATA;
  }
}

} // end if !exists

add_action( 'plugins_loaded', create_function( '$PowerCodeEditor_anetech', 'global $PowerCodeEditor; $PowerCodeEditor = new PowerCodeEditor();' ) ); 

?>