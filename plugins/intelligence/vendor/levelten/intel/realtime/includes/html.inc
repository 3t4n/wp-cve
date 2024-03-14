<?php
namespace LevelTen\Intel\Realtime;
/**
 * @file
 * @author  Tom McCracken <tomm@getlevelten.com>
 * @version 1.0
 * 
 * @section LICENSE
 * All rights reserved. Do not use without permission.
 * 
 */

function page_init_vars() {
  $a = array(
    'head' => array(
      'title' => '',
      'script' => array(),
      'css' => array(), 
    ),
    'body' => array(),
  );
}

function  render_page($vars) {
  $t = "\n";
  $output = '<!DOCTYPE html>' . $t;
  $output .= '<html>' . $t;
  $output .= '<head>' . $t;
  $output .= render_head($vars, $t);
  $output .= '</head>' . $t;
  $output .= '<body>' . $t;
  $output .= render_body($vars, $t);
  $output .= '</body>' . $t;
  $output .= '</html>';
  print $output;
}

function render_head($vars, $t = "\n") {
  $output = '';
  $output .= '<title>' . (!empty($vars['head']['title']) ? $vars['head']['title'] : '') . '</title>' . $t; 
  if (isset($vars['head']['scripts']) && is_array($vars['head']['scripts'])) {
    foreach ($vars['head']['scripts'] AS $script) {
      $output .= render_dom_element_script() . $t;
    }
  }
  return $output;
}

function render_dom_element_script ($vars) {
  $output .= '<script';
  if (!empty($vars['src'])) {
    $output .= ' src="' . $vars['src'] . '"';
  }
  $output .= '>';
  $output .= (!empty($vars['text']) ? $vars['text'] : '');
  $output .= '</script>';
  return $output;
}

function render_body($vars, $t = "\n") {
  $output = '';
  if (isset($vars['body']) && is_array($vars['body'])) {
    foreach ($vars['body'] AS $element) {
      $output .= (!empty($element['html']) ? $element['html'] : '') . $t; 
    }
  }
  return $output;
}

function page_add_message($message) {
  page_messages('add', $message);
}

function page_get_messages() {
  return page_messages('get');  
}

function page_messages($action = 'add', $message = '') {
  static $messages = array();
  if ($action == 'add') {
    $messages[] = $message; 
  }  
  return $messages;
}

function page_create_element(&$vars, $element, $hb = 'body') {
  if (is_string($element)) {
    $vars[$hb][] = array(
      'html' => $element,
    );
  }
  else {
    $vars[$hb][] = $element;
  }
  return $vars;
}