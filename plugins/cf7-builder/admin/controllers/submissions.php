<?php

class ControllerSubmissions_cf7b {

  public $view;
  public $model;
  public $plugin_dir;
  /* Constructor */
  public function __construct( $task = 'display' ) {
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/views/submissions.php') );
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/models/submissions.php') );
    $this->model = new ModelSubmissions_cf7b();
    $this->view = new ViewSubmissions_cf7b();
    $task = CF7B_Library::get('task', $task);

    if ( method_exists($this, $task) ) {
      $this->$task();
    }
  }

  public function save_submission( $data = array() ) {
    $data['created'] = date("Y-m-d h:i:sa");
    $data['fields'] = json_encode($data['fields']);
    $this->model->save_submission($data);
  }

  public function display( $data = array() ) {
    $order = CF7B_Library::get('order','desc');
    $orderby = CF7B_Library::get('orderby','id');
    $s = CF7B_Library::get('s');
    $data = $this->model->get_list_data( $orderby, $order, $s );
    $this->view->display( $data );
  }

  public function view( $data = array() ) {
    $id = CF7B_Library::get('id', 0, 'intval');
    $submissions = $this->model->getSubmissions( $id );
    $this->view->view( $submissions );
  }

  public function remove_submission( $data = array()) {
    $id = CF7B_Library::get( 'id', 0 );
    $form_id = CF7B_Library::get( 'form_id', 0 );
    $this->model->remove_submission($id);
    wp_redirect( add_query_arg( array( 'page' => 'submissions_cf7b&task=view&id='.$form_id ), admin_url( 'admin.php' ) ) );
  }

  public function remove_submissions( $data = array()) {
    $form_id = CF7B_Library::get( 'id', 0 );
    $this->model->remove_submissions($form_id);
    wp_redirect( add_query_arg( array( 'page' => 'submissions_cf7b' ), admin_url( 'admin.php' ) ) );
  }
}