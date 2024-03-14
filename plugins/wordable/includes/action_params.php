<?php

class ActionParams {
  private $wordable_plugin_actions;
  private $params;
  private $errors;

  function __construct($wordable_plugin_actions) {
    $this->wordable_plugin_actions = $wordable_plugin_actions;
  }

  function parse_and_validate($params) {
    $this->params = json_decode(stripslashes($params), true);
    $this->errors = array();

    $this->validate_method();
    $this->parse_and_validate_post();

    if(count($this->errors) == 0) {
      return $this->params;
    }

    throw new Exception(implode("\n", $this->errors));
  }

  function validate_method() {
    if(!$this->params['method']) {
      array_push($this->errors, 'Plugin: Method is required');
    }

    $this->params['method'] = trim($this->params['method']);

    if(!method_exists($this->wordable_plugin_actions, $this->params['method'].'_action')) {
      array_push($this->errors, 'Plugin: Unknown method '.$this->params['method']);
    }
  }

  function parse_and_validate_post() {
    if(!$this->params || !array_key_exists('post', $this->params)) return;

    $this->parse_and_validate_post_author();
    $this->parse_and_validate_post_categories();
    $this->parse_and_validate_post_type();

    // wp_insert_post is pretty sanitized itself
    $this->params['post']['title'] = sanitize_text_field($this->params['post']['title']);

    if($this->params['post']['slug']) {
      $this->params['post']['slug'] = sanitize_text_field($this->params['post']['slug']);
    }
  }

  function parse_and_validate_post_author() {
    if($this->params['post']['author_id']) {
      $author_id = intval($this->params['post']['author_id']);

      if(!$this->author_exists($author_id)) {
        $this->params['post']['author_id'] = null;
      } else {
        $this->params['post']['author_id'] = $author_id;
      }
    }
  }

  function parse_and_validate_post_categories() {
    if(!empty($this->params['post']['categories'])) {
      $this->params['post']['categories'] = $this->filter_unexisting_categories($this->params['post']['categories']);
    }
  }

  function parse_and_validate_post_type() {
    if(!empty($this->params['post']['type'])) {
      if(!in_array($this->params['post']['type'], get_post_types())) {
        $this->params['post']['type'] = 'post';
      }
    }
  }

  // Helpers
  function author_exists($author_id) {
    foreach($this->wordable_plugin_actions->authors() as $author) {
      if($author->ID == $author_id) {
        return true;
      }
    }

    return false;
  }

  function filter_unexisting_categories($categories_ids) {
    $existing_categories_ids = array_map(function($category) { return $category->term_id; }, $this->wordable_plugin_actions->categories());
    return array_intersect($existing_categories_ids, $categories_ids);
  }
}
