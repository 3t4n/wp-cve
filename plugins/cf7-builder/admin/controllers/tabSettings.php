<?php

class ControllerTabSettings_cf7b {

  public $view;
  public $model;
  public $cf7b_form_settings;
  public $plugin_dir;
  /* Constructor */
  public function __construct( $params = array() ) {
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/views/tabSettings.php') );
    $this->view = new ViewTabSettings_cf7b();

    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/models/tabSettings.php') );
    $this->model = new ModelTabSettings_cf7b();

    $task = isset($params['cf7b_task']) ? $params['cf7b_task'] : 'display';

    if( method_exists($this, $task) ) {
      $this->$task( $params );
    } else {
      $this->display();
    }
  }

  public function cf7b_set_default() {
    $cf7b_form_settings = get_option('cf7b_form_settings');
    $form_id = CF7B_Library::get('post');

    $default_theme_id = $this->model->get_default_theme_id();
    if ( !empty($cf7b_form_settings) && isset($cf7b_form_settings['form_'.$form_id]) ) {
      $this->cf7b_form_settings = $cf7b_form_settings['form_'.$form_id];
    } else {
      $this->cf7b_form_settings = array(
                                        'theme' => 0,
                                        'action_type' => 0,
                                        'action_value' => ''
                                      );
    }
  }

  public function display() {
    $this->cf7b_set_default();
    $all_themes = $this->model->get_all_themes();

    $settings = array(
      'active_theme' => $this->cf7b_form_settings['theme'],
      'action_type' => isset($this->cf7b_form_settings['action_type']) ? $this->cf7b_form_settings['action_type'] : 0,
      'action_value' => isset($this->cf7b_form_settings['action_value']) ? $this->cf7b_form_settings['action_value'] : '',
      'all_themes' => $all_themes,
      'all_pages' => get_pages(),
      'all_posts' => get_posts(),
    );
    $this->view->display( $settings );
  }

  /**
   * Get value of after submit redirect settings radio type
   *
   * array $params data of all form
   *
   * return string
  */
  public function get_aftersubmit_action_value( $params ) {
    $action_type = isset($params['cf7b_action_after_submit']) ? $params['cf7b_action_after_submit'] : 0;
    switch ($action_type) {
      case 1:
        $action_value = $params['cf7b_aftersubmit_page'];
        break;
      case 2:
        $action_value = $params['cf7b_aftersubmit_post'];
        break;
      case 3:
        $action_value = $params['cf7b_aftersubmit_text'];
        break;
      case 4:
        $action_value = $params['cf7b_aftersubmit_custom'];
        break;
      default:
        $action_value = '';
    }
    return $action_value;
  }

  public function cf7b_save_tabSettings( $params ) {
    $cf7b_form_settings = get_option('cf7b_form_settings');

/*    if( $params['cf7b_active_theme'] == 0 ) {
      $params['cf7b_active_theme'] = $this->model->get_default_theme_id();
    }*/
    $preview_id = CF7B_Library::cf7b_get_preview_id();
    $aftersubmit_action_value = $this->get_aftersubmit_action_value($params);
    if ( empty($cf7b_form_settings) ) {
        $data = array(
          'form_'.$params['post_id'] => array(
            'theme'=>$params['cf7b_active_theme'],
            'action_type' => $params['cf7b_action_after_submit'],
            'action_value' => $aftersubmit_action_value
          ),
          'form_'.$preview_id => array(
            'theme'=>$params['cf7b_active_theme'],
            'action_type' => $params['cf7b_action_after_submit'],
            'action_value' => $aftersubmit_action_value
          )
        );
        update_option('cf7b_form_settings', $data, 1);
    } else {
        $cf7b_form_settings['form_'.$params['post_id']]['theme'] = $params['cf7b_active_theme'];
        $cf7b_form_settings['form_'.$params['post_id']]['action_type'] = $params['cf7b_action_after_submit'];
        $cf7b_form_settings['form_'.$params['post_id']]['action_value'] = $aftersubmit_action_value;

        $cf7b_form_settings['form_'.$preview_id]['theme'] = $params['cf7b_active_theme'];
        $cf7b_form_settings['form_'.$preview_id]['action_type'] = $params['cf7b_action_after_submit'];
        $cf7b_form_settings['form_'.$preview_id]['action_value'] = $aftersubmit_action_value;
        update_option('cf7b_form_settings', $cf7b_form_settings);
    }
  }
}