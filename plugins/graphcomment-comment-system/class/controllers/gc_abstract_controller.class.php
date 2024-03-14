<?php

/**
 * @controller Abstract Controller
 *
 * Handle a tab
 */

if (!class_exists('GcController')) {
  abstract class GcController {

    protected $post;

    public function __construct($post) {
      $this->post = $post;
    }

    abstract function handleOptionForm();

  }
}