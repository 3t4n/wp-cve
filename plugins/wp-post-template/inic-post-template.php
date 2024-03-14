<?php

/*
  Plugin Name: Post Template
  Plugin URI: http://wordpress.org/extend/plugins/wp-post-template/
  Description: WordPress allows users to customize templates for webpages but, it is not so for blog posts. Post Template plug-in from <a href="http://www.indianic.com/">IndiaNIC</a> enables you to use the customized page templates for your blog posts also.
  Author: IndiaNIC
  Version: 1.0
  Author URI: http://profiles.wordpress.org/indianic
 */

class inic_post_template {

  var $pluginPath;
  private $tpl_meta_key;
  private $post_ID;

  public function __construct() {
    $this->tpl_meta_key = 'inic_post_template';
    $this->pluginPath = plugin_dir_path(__FILE__);

    $this->add_action('admin_init');
    $this->add_action('save_post');
    $this->add_filter('single_template', 'filter_single_template');
  }

  function add_action($action, $function = '', $priority = 10, $accepted_args = 1) {
    add_action($action, array(&$this, $function == '' ? $action : $function), $priority, $accepted_args);
  }

  function add_filter($filter, $function = '', $priority = 10, $accepted_args = 1) {
    add_filter($filter, array(&$this, $function == '' ? $filter : $function), $priority, $accepted_args);
  }

  public function admin_init() {
    $post_types = apply_filters('cpt_post_types', array('post'));
    foreach ($post_types as $post_type) {
      $this->add_meta_box('inic_post_template', __('Post Template', 'custom-post-templates'), 'inic_post_template', $post_type, 'side', 'high');
    }
  }

  function add_meta_box($id, $title, $function = '', $page, $context = 'advanced', $priority = 'default') {
    add_meta_box($id, $title, array(&$this, $function == '' ? $id : $function), $page, $context, $priority);
  }

  public function inic_post_template($post) {
    $this->post_ID = $post->ID;
    $template_vars = array();
    $template_vars['templates'] = $this->get_post_templates();
    $template_vars['custom_template'] = $this->get_custom_post_template();
    foreach ($template_vars AS $key => $val) {
      $$key = $val;
    }

    if ($templates) {
      echo '<input type="hidden" name="inic_post_templates_present" value="1" /><select name="inic_post_templates" id="inic_post_templates">';
      $_default_selected = $custom_template ? "" : " selected='selected'";
      echo "<option value=\"default\"{$_default_selected}>Default Template</option>";
      foreach ($templates AS $filename => $name) {
        $_is_selected = $custom_template == $filename ? " selected='selected'" : "";
        echo "<option value=\"{$filename}\"{$_is_selected}>{$name}</option>";
      }
      echo "</select>";
      echo "<p>This themes have custom templates you can use for certain posts that might have additional features or custom layouts.</p>";
    } else {
      echo "<p>This theme has no available custom templates</p>";
    }
  }

  protected function get_post_templates() {
    $theme = wp_get_theme();
    $post_templates = array();
    $files = (array) $theme->get_files('php', 1);

    foreach ($files as $file => $full_path) {
      $headers = get_file_data($full_path, array('Template Name' => 'Template Name'));
      if (empty($headers['Template Name']))
        continue;
      $post_templates[$file] = $headers['Template Name'];
    }

    return $post_templates;
  }

  protected function get_custom_post_template() {
    $custom_template = get_post_meta($this->post_ID, $this->tpl_meta_key, true);
    return $custom_template;
  }

  public function save_post($post_ID) {
    if (!isset($_POST['inic_post_templates_present']) && !$_POST['inic_post_templates_present'])
      return;

    $this->post_ID = $post_ID;
    delete_post_meta($this->post_ID, $this->tpl_meta_key);
    if (isset($_POST['inic_post_templates']) && $_POST['inic_post_templates'] != 'default') {
      add_post_meta($this->post_ID, $this->tpl_meta_key, $_POST['inic_post_templates']);
    }
  }

  public function filter_single_template($template) {
    global $wp_query;
    $this->post_ID = $wp_query->post->ID;
    $template_file = $this->get_custom_post_template();

    if (!$template_file)
      return $template;

    if (file_exists(STYLESHEETPATH . DIRECTORY_SEPARATOR . $template_file)) {
      return STYLESHEETPATH . DIRECTORY_SEPARATOR . $template_file;
    } else if (TEMPLATEPATH . DIRECTORY_SEPARATOR . $template_file) {
      return TEMPLATEPATH . DIRECTORY_SEPARATOR . $template_file;
    }

    return $template;
  }

  function is_post_template($template = '') {
    if (!is_single()) {
      return false;
    }

    global $wp_query;

    $post = $wp_query->get_queried_object();
    $post_template = get_post_meta($post->ID, $this->tpl_meta_key, true);

    if (empty($template)) {
      if (!empty($post_template)) {
        return true;
      }
    } elseif ($template == $post_template) {
      return true;
    }

    return false;
  }

}

$inic_post_template = new inic_post_template();
?>
