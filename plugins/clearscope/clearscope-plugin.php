<?php

class Clearscope_Plugin
{
  private $editor;

  public function __construct()
  {
    add_action('current_screen', array($this, 'set_editor'));
    add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));
    add_action('add_meta_boxes', array($this, 'add_meta_box'));
  }

  public function set_editor()
  {
    if (get_current_screen()->is_block_editor()) {
      $this->editor = 'gutenberg';
    } else {
      $this->editor = 'classic';
    }
  }

  public function enqueue_assets($hook)
  {
    global $wp_version, $clearscope_plugin_version;
    if (!$this->is_loadable()) {
      return;
    }
    $dependencies = array(
      'wp-plugins',
      'wp-element',
      'wp-data'
    );
    // Only load wp-edit-post as dependency on Gutenberg to work around Yoast breaking with wp-edit-post enabled
    // wp-edit-post is only used for PluginSidebar for Gutenberg
    if ($this->editor == 'gutenberg') {
      array_push($dependencies, 'wp-edit-post');
    }
    wp_enqueue_script(
      'clearscope-sidebar',
      'https://www.clearscope.io/addons/wp/sidebar/new.js?post_guid=' . urlencode(get_post()->to_array()['guid']),
      $dependencies
    );
    wp_enqueue_style('clearscope-metabox', plugins_url('css/metabox.css', __FILE__));
    wp_localize_script('clearscope-sidebar', 'wpEnv',
      array(
        'user' => wp_get_current_user()->to_array(),
        'wp_version' => $wp_version,
        'php_version' => phpversion(),
        'plugin_version' => $clearscope_plugin_version,
        'post' => get_post()->to_array(),
        'post_url' => get_edit_post_link(null, 'not display'),
        'editor' => $this->editor
      )
    );
  }

  public function add_meta_box()
  {
    if ($this->editor == 'classic' && $this->is_loadable()) {
      add_meta_box(
        'clearscope-meta',
        'Clearscope',
        array($this, 'meta_box_html')
      );
    }
  }

  public function meta_box_html()
  {
    echo '<iframe id="clearscope-sidebar" style="height:1000px; width: 100%;"></iframe>';
  }

  private function is_loadable()
  {
    global $post;
    if($post){
      $post_type = get_post_type($post->ID);
      $public_post_types = get_post_types( array('public' => true ) );
      $loadable_post_types = array_keys(array_filter( $public_post_types, 'is_post_type_viewable' ));
      return in_array($post_type, $loadable_post_types);
    }
    return false;
  }
}
