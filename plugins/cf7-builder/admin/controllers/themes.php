<?php

class ControllerThemes_cf7b {

  public $view;
  public $model;
  public $plugin_dir;

  public $default = array(
                      'general' => array(
                                      'general_container_width' => '100',
                                      'general_bg_color'        => '#ffffff',
                                      'general_font_size'       => '12',
                                      'general_font_weight'     => 'normal',
                                      'general_font_color'      => '#000000',
                                      'general_margin'          => '5px',
                                      'general_padding'         => '5px',
                                      'general_border_width'    => '1',
                                      'general_border_style'    => 'solid',
                                      'general_border_color'    => '#dfdfdf',
                                      'general_border_radius'   => '0',
                                      'general_column_margin'   => '10px',
                                      'general_column_padding'  => '10px',
                                    ),
                      'input_fields' => array(
                                          'input_width'         => '100',
                                          'input_height'        => '30',
                                          'input_font_size'     => '12',
                                          'input_font_weight'   => 'normal',
                                          'input_bg_color'      => '#ffffff',
                                          'input_color'         => '#000000',
                                          'input_margin'        => '2px',
                                          'input_padding'       => '2px',
                                          'input_border_width'  => '1',
                                          'input_border_style'  => 'solid',
                                          'input_border_color'  => '#dfdfdf',
                                          'input_border_radius' => '2',
                                          'input_box_shadow'    => 'none',
                                        ),
                      'textarea' => array(
                                      'textarea_width'         => '100',
                                      'textarea_height'        => '50',
                                      'textarea_font_size'     => '12',
                                      'textarea_font_weight'   => 'normal',
                                      'textarea_bg_color'      => '#ffffff',
                                      'textarea_color'         => '#000000',
                                      'textarea_margin'        => '2px',
                                      'textarea_padding'       => '2px',
                                      'textarea_border_width'  => '1',
                                      'textarea_border_style'  => 'solid',
                                      'textarea_border_color'  => '#dfdfdf',
                                      'textarea_border_radius' => '2',
                                      'textarea_box_shadow'    => 'none',
                                    ),
                      'drodown_fields' => array(
                                      'drodown_width'        => '100',
                                      'drodown_height'        => '30',
                                      'drodown_font_size'     => '12',
                                      'drodown_font_weight'   => 'normal',
                                      'drodown_bg_color'      => '#ffffff',
                                      'drodown_color'         => '#000000',
                                      'drodown_margin'        => '2px',
                                      'drodown_padding'       => '2px',
                                      'drodown_border_width'  => '1',
                                      'drodown_border_style'  => 'solid',
                                      'drodown_border_color'  => '#dfdfdf',
                                      'drodown_border_radius' => '2',
                                      'drodown_box_shadow'    => 'none',
                                    ),
                      'radio_fields' => array(
                                      'radio_width'             => '14',
                                      'radio_height'            => '14',
                                      'radio_bg_color'          => '#ffffff',
                                      'radio_margin'            => '0px 10px 0px 0px',
                                      'radio_padding'           => '0px',
                                      'radio_border_width'      => '1',
                                      'radio_border_style'      => 'solid',
                                      'radio_border_color'      => '#000000',
                                      'radio_border_radius'     => '7',
                                      'radio_box_shadow'        => 'none',
                                      'radio_checked_bg_color'  => '#000000',
                                    ),
                      'checkbox_fields' => array(
                                      'checkbox_width'             => '15',
                                      'checkbox_height'            => '15',
                                      'checkbox_bg_color'          => '#ffffff',
                                      'checkbox_margin'            => '2px',
                                      'checkbox_padding'           => '2px',
                                      'checkbox_border_width'      => '0',
                                      'checkbox_border_style'      => 'solid',
                                      'checkbox_border_color'      => '#dfdfdf',
                                      'checkbox_border_radius'     => '2',
                                      'checkbox_box_shadow'        => 'none',
                                      'checkbox_checked_bg_color'  => '#000000',
                                    ),
                      'button_fields' => array(
                                      'button_width'             => '200',
                                      'button_height'            => '20',
                                      'button_font_size'         => '12',
                                      'button_bg_color'          => '#2271b1',
                                      'button_color'             => '#ffffff',
                                      'button_font_weight'       => 'normal',
                                      'button_margin'            => '2px',
                                      'button_padding'           => '2px',
                                      'button_border_width'      => '1',
                                      'button_border_style'      => 'solid',
                                      'button_border_color'      => '#2271b1',
                                      'button_border_radius'     => '2',
                                      'button_box_shadow'        => 'none',
                                      'button_text_align'        => 'center',
                                      'button_hover_font_weight' => 'normal',
                                      'button_hover_bg_color'    => '#135e96',
                                      'button_hover_color'       => '#ffffff',
                                   ),
                      'pagination_fields' => array(
                                      'pagination_width'             => '100',
                                      'pagination_height'            => '20',
                                      'pagination_font_size'         => '12',
                                      'pagination_bg_color'          => '#2271b1',
                                      'pagination_color'             => '#ffffff',
                                      'pagination_font_weight'       => 'normal',
                                      'pagination_margin'            => '2px',
                                      'pagination_padding'           => '2px',
                                      'pagination_border_width'      => '1',
                                      'pagination_border_style'      => 'solid',
                                      'pagination_border_color'      => '#2271b1',
                                      'pagination_border_radius'     => '2',
                                      'pagination_box_shadow'        => 'none',
                                      'pagination_prev_text_align'   => 'center',
                                      'pagination_next_text_align'  => 'center',
                                      'pagination_hover_font_weight' => 'normal',
                                      'pagination_hover_bg_color'    => '#135e96',
                                      'pagination_hover_color'       => '#ffffff',
                                   ),
                      'custom_css' => array('custom_css'=>'')
                    );


  /* Constructor */
  public function __construct() {
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/views/themes.php') );
    $this->view = new ViewThemes_cf7b();
    require_once(wp_normalize_path(CF7B_BUILDER_INT_DIR . '/admin/models/themes.php') );
    $this->model = new ModelThemes_cf7b();

    $task = CF7B_Library::get('task', 'display');
    if ( method_exists($this, $task) ) {
      $this->$task();
    }
  }

  public function save_theme() {
    $id = CF7B_Library::get('theme_id',0);
    $title = CF7B_Library::get('theme_title',0);
    $data = array(
            'general' => array(
              'general_container_width' => CF7B_Library::get('general_container_width', ''),
              'general_bg_color'        => CF7B_Library::get('general_bg_color', ''),
              'general_font_size'       => CF7B_Library::get('general_font_size', ''),
              'general_font_weight'     => CF7B_Library::get('general_font_weight', ''),
              'general_font_color'      => CF7B_Library::get('general_font_color', ''),
              'general_margin'          => CF7B_Library::get('general_margin', ''),
              'general_padding'         => CF7B_Library::get('general_padding', ''),
              'general_border_width'    => CF7B_Library::get('general_border_width', ''),
              'general_border_style'    => CF7B_Library::get('general_border_style', ''),
              'general_border_color'    => CF7B_Library::get('general_border_color', ''),
              'general_border_radius'   => CF7B_Library::get('general_border_radius', ''),
              'general_column_margin'   => CF7B_Library::get('general_column_margin', ''),
              'general_column_padding'  => CF7B_Library::get('general_column_padding', ''),
            ),
            'input_fields' => array(
              'input_width'         => CF7B_Library::get('input_width', ''),
              'input_height'        => CF7B_Library::get('input_height', ''),
              'input_font_size'     => CF7B_Library::get('input_font_size', ''),
              'input_font_weight'   => CF7B_Library::get('input_font_weight', ''),
              'input_bg_color'      => CF7B_Library::get('input_bg_color', ''),
              'input_color'         => CF7B_Library::get('input_color', ''),
              'input_margin'        => CF7B_Library::get('input_margin', ''),
              'input_padding'       => CF7B_Library::get('input_padding', ''),
              'input_border_width'  => CF7B_Library::get('input_border_width', ''),
              'input_border_style'  => CF7B_Library::get('input_border_style', ''),
              'input_border_color'  => CF7B_Library::get('input_border_color', ''),
              'input_border_radius' => CF7B_Library::get('input_border_radius', ''),
              'input_box_shadow'    => CF7B_Library::get('input_box_shadow', ''),
            ),
            'textarea' => array(
              'textarea_width'         => CF7B_Library::get('textarea_width', ''),
              'textarea_height'        => CF7B_Library::get('textarea_height', ''),
              'textarea_font_size'     => CF7B_Library::get('textarea_font_size', ''),
              'textarea_font_weight'   => CF7B_Library::get('textarea_font_weight', ''),
              'textarea_bg_color'      => CF7B_Library::get('textarea_bg_color', ''),
              'textarea_color'         => CF7B_Library::get('textarea_color', ''),
              'textarea_margin'        => CF7B_Library::get('textarea_margin', ''),
              'textarea_padding'       => CF7B_Library::get('textarea_padding', ''),
              'textarea_border_width'  => CF7B_Library::get('textarea_border_width', ''),
              'textarea_border_style'  => CF7B_Library::get('textarea_border_style', ''),
              'textarea_border_color'  => CF7B_Library::get('textarea_border_color', ''),
              'textarea_border_radius' => CF7B_Library::get('textarea_border_radius', ''),
              'textarea_box_shadow'    => CF7B_Library::get('textarea_box_shadow', ''),
            ),
            'drodown_fields' => array(
              'drodown_width'         => CF7B_Library::get('drodown_width', ''),
              'drodown_height'        => CF7B_Library::get('drodown_height', ''),
              'drodown_font_size'     => CF7B_Library::get('drodown_font_size', ''),
              'drodown_font_weight'   => CF7B_Library::get('drodown_font_weight', ''),
              'drodown_bg_color'      => CF7B_Library::get('drodown_bg_color', ''),
              'drodown_color'         => CF7B_Library::get('drodown_color', ''),
              'drodown_margin'        => CF7B_Library::get('drodown_margin', ''),
              'drodown_padding'       => CF7B_Library::get('drodown_padding', ''),
              'drodown_border_width'  => CF7B_Library::get('drodown_border_width', ''),
              'drodown_border_style'  => CF7B_Library::get('drodown_border_style', ''),
              'drodown_border_color'  => CF7B_Library::get('drodown_border_color', ''),
              'drodown_border_radius' => CF7B_Library::get('drodown_border_radius', ''),
              'drodown_box_shadow'    => CF7B_Library::get('drodown_box_shadow', ''),
            ),
            'radio_fields' => array(
              'radio_width'             => CF7B_Library::get('radio_width', ''),
              'radio_height'            => CF7B_Library::get('radio_height', ''),
              'radio_bg_color'          => CF7B_Library::get('radio_bg_color', ''),
              'radio_margin'            => CF7B_Library::get('radio_margin', ''),
              'radio_padding'           => CF7B_Library::get('radio_padding', ''),
              'radio_border_width'      => CF7B_Library::get('radio_border_width', ''),
              'radio_border_style'      => CF7B_Library::get('radio_border_style', ''),
              'radio_border_color'      => CF7B_Library::get('radio_border_color', ''),
              'radio_border_radius'     => CF7B_Library::get('radio_border_radius', ''),
              'radio_box_shadow'        => CF7B_Library::get('radio_box_shadow', ''),
              'radio_checked_bg_color'  => CF7B_Library::get('radio_checked_bg_color', ''),
            ),
            'checkbox_fields' => array(
              'checkbox_width'             => CF7B_Library::get('checkbox_width', ''),
              'checkbox_height'            => CF7B_Library::get('checkbox_height', ''),
              'checkbox_bg_color'          => CF7B_Library::get('checkbox_bg_color', ''),
              'checkbox_margin'            => CF7B_Library::get('checkbox_margin', ''),
              'checkbox_padding'           => CF7B_Library::get('checkbox_padding', ''),
              'checkbox_border_width'      => CF7B_Library::get('checkbox_border_width', ''),
              'checkbox_border_style'      => CF7B_Library::get('checkbox_border_style', ''),
              'checkbox_border_color'      => CF7B_Library::get('checkbox_border_color', ''),
              'checkbox_border_radius'     => CF7B_Library::get('checkbox_border_radius', ''),
              'checkbox_box_shadow'        => CF7B_Library::get('checkbox_box_shadow', ''),
              'checkbox_checked_bg_color'  => CF7B_Library::get('checkbox_checked_bg_color', ''),
            ),
            'button_fields' => array(
              'button_width'             =>  CF7B_Library::get('button_width', ''),
              'button_height'            =>  CF7B_Library::get('button_height', ''),
              'button_font_size'         =>  CF7B_Library::get('button_font_size', ''),
              'button_bg_color'          =>  CF7B_Library::get('button_bg_color', ''),
              'button_color'             =>  CF7B_Library::get('button_color', ''),
              'button_font_weight'       =>  CF7B_Library::get('button_font_weight', ''),
              'button_margin'            =>  CF7B_Library::get('button_margin', ''),
              'button_padding'           =>  CF7B_Library::get('button_padding', ''),
              'button_border_width'      =>  CF7B_Library::get('button_border_width', ''),
              'button_border_style'      =>  CF7B_Library::get('button_border_style', ''),
              'button_border_color'      =>  CF7B_Library::get('button_border_color', ''),
              'button_border_radius'     =>  CF7B_Library::get('button_border_radius', ''),
              'button_box_shadow'        =>  CF7B_Library::get('button_box_shadow', ''),
              'button_text_align'        =>  CF7B_Library::get('button_text_align', ''),
              'button_hover_font_weight' =>  CF7B_Library::get('button_hover_font_weight', ''),
              'button_hover_bg_color'    =>  CF7B_Library::get('button_hover_bg_color', ''),
              'button_hover_color'       =>  CF7B_Library::get('button_hover_color', ''),
            ),
            'pagination_fields' => array(
              'pagination_width'             =>  CF7B_Library::get('pagination_width', ''),
              'pagination_height'            =>  CF7B_Library::get('pagination_height', ''),
              'pagination_font_size'         =>  CF7B_Library::get('pagination_font_size', ''),
              'pagination_bg_color'          =>  CF7B_Library::get('pagination_bg_color', ''),
              'pagination_color'             =>  CF7B_Library::get('pagination_color', ''),
              'pagination_font_weight'       =>  CF7B_Library::get('pagination_font_weight', ''),
              'pagination_margin'            =>  CF7B_Library::get('pagination_margin', ''),
              'pagination_padding'           =>  CF7B_Library::get('pagination_padding', ''),
              'pagination_border_width'      =>  CF7B_Library::get('pagination_border_width', ''),
              'pagination_border_style'      =>  CF7B_Library::get('pagination_border_style', ''),
              'pagination_border_color'      =>  CF7B_Library::get('pagination_border_color', ''),
              'pagination_border_radius'     =>  CF7B_Library::get('pagination_border_radius', ''),
              'pagination_box_shadow'        =>  CF7B_Library::get('pagination_box_shadow', ''),
              'pagination_prev_text_align'   =>  CF7B_Library::get('pagination_prev_text_align', ''),
              'pagination_next_text_align'   =>  CF7B_Library::get('pagination_next_text_align', ''),
              'pagination_hover_font_weight' =>  CF7B_Library::get('pagination_hover_font_weight', ''),
              'pagination_hover_bg_color'    =>  CF7B_Library::get('pagination_hover_bg_color', ''),
              'pagination_hover_color'       =>  CF7B_Library::get('pagination_hover_color', ''),
            ),
            'custom_css' => array('custom_css'=>CF7B_Library::get('custom_css', ''))
    );
    $id = $this->model->save_theme( $id, $title, $data );
    $this->create_css($id, $data);
    $redirectUrl = add_query_arg(array(
                                   'page' => 'themes_cf7b',
                                   'task' => 'edit',
                                   'theme_id' => $id,
                                 ), admin_url('admin.php'));
    if (headers_sent()) { ?>
      <script>
        location.href = <?php echo "'".$redirectUrl."'" ?>;
      </script>
    <?php
    }
    else{
      wp_redirect($redirectUrl);
    }
  }

  public function display() {
    $params['rows_data'] = $this->model->get_themes_list();
    $params['page'] = CF7B_Library::get('page');

    $this->view->display( $params );
  }

  public function edit() {
    $theme_tabs = array(
      'general',
      'input_fields',
      'textarea',
      'drodown_fields',
      'radio_fields',
      'checkbox_fields',
      'button_fields',
      'pagination_fields',
      'custom_css',
    );
    $id = CF7B_Library::get('theme_id');
    $title = CF7B_Library::get('theme_title');

    $params = $this->model->get_theme_data($id);
    if( !empty($params) ) {
        $params['options'] = json_decode($params['options'], 1);
        foreach ( $theme_tabs as $theme_tab ) {
          if( !array_key_exists ( $theme_tab , $params['options'] ) ) {
            $params['options'][$theme_tab] = $this->default[$theme_tab];
          }
        }

    } else {
        $params['title'] = '';
        $params['options'] = $this->default;
    }
    $this->view->edit( $params );
  }

  public function create_css($id, $data) {
    $cf7b_theme = '';
    $cf7b_theme .= $data['custom_css']['custom_css'];
    $cf7b_theme .= ".wpcf7-form .cf7b-content { 
      width: ".$data['general']['general_container_width']."%;
      box-sizing: border-box;
      background-color: ".$data['general']['general_bg_color'].";
      font-size: ".$data['general']['general_font_size']."px;
      font-weight: ".$data['general']['general_font_weight'].";
      color: ".$data['general']['general_font_color'].";
      margin: ".$data['general']['general_margin'].";
      padding: ".$data['general']['general_padding'].";
      border-width: ".$data['general']['general_border_width']."px;
      border-style: ".$data['general']['general_border_style'].";
      border-color: ".$data['general']['general_border_color'].";
      border-radius: ".$data['general']['general_border_radius']."px;
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section { 
      margin: ".$data['general']['general_column_margin'].";
      padding: ".$data['general']['general_column_padding'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=text], 
    .wpcf7-form .cf7b-content .cf7b-section input[type=number],.wpcf7-form .cf7b-content .cf7b-section input[type=email], 
    .wpcf7-form .cf7b-content .cf7b-section input[type=password], .wpcf7-form .cf7b-content .cf7b-section input[type=search]{ 
      width: ".$data['input_fields']['input_width']."%;
      height: ".$data['input_fields']['input_height']."px;
      font-size: ".$data['input_fields']['input_font_size']."px;
      font-weight: ".$data['input_fields']['input_font_weight'].";
      background-color: ".$data['input_fields']['input_bg_color'].";
      color: ".$data['input_fields']['input_color'].";
      margin: ".$data['input_fields']['input_margin'].";
      padding: ".$data['input_fields']['input_padding'].";
      border-width: ".$data['input_fields']['input_border_width']."px;
      border-style: ".$data['input_fields']['input_border_style'].";
      border-color: ".$data['input_fields']['input_border_color'].";
      border-radius: ".$data['input_fields']['input_border_radius']."px;
      box-shadow: ".$data['input_fields']['input_box_shadow'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section textarea { 
      width: ".$data['textarea']['textarea_width']."%;
      height: ".$data['textarea']['textarea_height']."px;
      font-size: ".$data['textarea']['textarea_font_size']."px;
      font-weight: ".$data['textarea']['textarea_font_weight'].";
      background-color: ".$data['textarea']['textarea_bg_color'].";
      color: ".$data['textarea']['textarea_color'].";
      margin: ".$data['textarea']['textarea_margin'].";
      padding: ".$data['textarea']['textarea_padding'].";
      border-width: ".$data['textarea']['textarea_border_width']."px;
      border-style: ".$data['textarea']['textarea_border_style'].";
      border-color: ".$data['textarea']['textarea_border_color'].";
      border-radius: ".$data['textarea']['textarea_border_radius']."px;
      box-shadow: ".$data['textarea']['textarea_box_shadow'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section select { 
      width: ".$data['drodown_fields']['drodown_width']."%;
      height: ".$data['drodown_fields']['drodown_height']."px;
      font-size: ".$data['drodown_fields']['drodown_font_size']."px;
      font-weight: ".$data['drodown_fields']['drodown_font_weight'].";
      background-color: ".$data['drodown_fields']['drodown_bg_color'].";
      color: ".$data['drodown_fields']['drodown_color'].";
      margin: ".$data['drodown_fields']['drodown_margin'].";
      padding: ".$data['drodown_fields']['drodown_padding'].";
      border-width: ".$data['drodown_fields']['drodown_border_width']."px;
      border-style: ".$data['drodown_fields']['drodown_border_style'].";
      border-color: ".$data['drodown_fields']['drodown_border_color'].";
      border-radius: ".$data['drodown_fields']['drodown_border_radius']."px;
      box-shadow: ".$data['drodown_fields']['drodown_box_shadow'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=radio] { 
      visibility: hidden;
      position: absolute;
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=radio]+span.wpcf7-list-item-label:before { 
      display: inline-block;
      width: ".$data['radio_fields']['radio_width']."px;
      height: ".$data['radio_fields']['radio_height']."px;
      background-color: ".$data['radio_fields']['radio_bg_color'].";
      margin: ".$data['radio_fields']['radio_margin'].";
      padding: ".$data['radio_fields']['radio_padding'].";
      border-width: ".$data['radio_fields']['radio_border_width']."px;
      border-style: ".$data['radio_fields']['radio_border_style'].";
      border-color: ".$data['radio_fields']['radio_border_color'].";
      border-radius: ".$data['radio_fields']['radio_border_radius']."px;
      box-shadow: ".$data['radio_fields']['radio_box_shadow'].";
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=radio]:checked+span.wpcf7-list-item-label:before { 
      background-color: ".$data['radio_fields']['radio_checked_bg_color'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=checkbox] { 
      width: ".$data['checkbox_fields']['checkbox_width']."px;
      height: ".$data['checkbox_fields']['checkbox_height']."px;
      background-color: ".$data['checkbox_fields']['checkbox_bg_color'].";
      margin: ".$data['checkbox_fields']['checkbox_margin'].";
      padding: ".$data['checkbox_fields']['checkbox_padding'].";
      border-width: ".$data['checkbox_fields']['checkbox_border_width']."px;
      border-style: ".$data['checkbox_fields']['checkbox_border_style'].";
      border-color: ".$data['checkbox_fields']['checkbox_border_color'].";
      border-radius: ".$data['checkbox_fields']['checkbox_border_radius']."px;
      box-shadow: ".$data['checkbox_fields']['checkbox_box_shadow'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=submit],
     .wpcf7-form .cf7b-content .cf7b-section input[type=button],
     .wpcf7-form .cf7b-content .cf7b-section button { 
        width: ".$data['button_fields']['button_width']."px;
        height: ".$data['button_fields']['button_height']."px;
        font-size: ".$data['button_fields']['button_font_size']."px;
        font-weight: ".$data['button_fields']['button_font_weight'].";
        color: ".$data['button_fields']['button_color'].";
        background-color: ".$data['button_fields']['button_bg_color'].";
        margin: ".$data['button_fields']['button_margin'].";
        padding: ".$data['button_fields']['button_padding'].";
        border-width: ".$data['button_fields']['button_border_width']."px;
        border-style: ".$data['button_fields']['button_border_style'].";
        border-color: ".$data['button_fields']['button_border_color'].";
        border-radius: ".$data['button_fields']['button_border_radius']."px;
        box-shadow: ".$data['button_fields']['button_box_shadow'].";
        text-align: ".$data['button_fields']['button_text_align'].";
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-section input[type=submit]:hover,
     .wpcf7-form .cf7b-content .cf7b-section input[type=button]:hover,
     .wpcf7-form .cf7b-content .cf7b-section button:hover { 
        font-weight: ".$data['button_fields']['button_hover_font_weight'].";
        color: ".$data['button_fields']['button_hover_color'].";
        background-color: ".$data['button_fields']['button_hover_bg_color'].";
    }\n";

    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-prev,
     .wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-next { 
        width: ".$data['pagination_fields']['pagination_width']."px;
        height: ".$data['pagination_fields']['pagination_height']."px;
        font-size: ".$data['pagination_fields']['pagination_font_size']."px;
        font-weight: ".$data['pagination_fields']['pagination_font_weight'].";
        color: ".$data['pagination_fields']['pagination_color'].";
        background-color: ".$data['pagination_fields']['pagination_bg_color'].";
        margin: ".$data['pagination_fields']['pagination_margin'].";
        padding: ".$data['pagination_fields']['pagination_padding'].";
        border-width: ".$data['pagination_fields']['pagination_border_width']."px;
        border-style: ".$data['pagination_fields']['pagination_border_style'].";
        border-color: ".$data['pagination_fields']['pagination_border_color'].";
        border-radius: ".$data['pagination_fields']['pagination_border_radius']."px;
        box-shadow: ".$data['pagination_fields']['pagination_box_shadow'].";
        line-height: ".$data['pagination_fields']['pagination_height']."px;
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-prev { 
        text-align: ".$data['pagination_fields']['pagination_prev_text_align'].";
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-next { 
        text-align: ".$data['pagination_fields']['pagination_next_text_align'].";
    }\n";
    $cf7b_theme .= ".wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-prev:hover,
     .wpcf7-form .cf7b-content .cf7b-pagination-row .cf7b-next:hover { 
        font-weight: ".$data['pagination_fields']['pagination_hover_font_weight'].";
        color: ".$data['pagination_fields']['pagination_hover_color'].";
        background-color: ".$data['pagination_fields']['pagination_hover_bg_color'].";
    }\n";


    $wp_upload_dir = wp_upload_dir();
    $builder_dir = '/cf7-builder/';
    if ( !is_dir( $wp_upload_dir[ 'basedir' ] . $builder_dir ) ) {
      mkdir( $wp_upload_dir[ 'basedir' ] . $builder_dir );
      file_put_contents( $wp_upload_dir[ 'basedir' ] . $builder_dir . 'index.html', CF7B_Library::forbidden_template() );
    }

    $cf7b_style_dir = $wp_upload_dir[ 'basedir' ] . $builder_dir . 'cf7b-theme-style'.$id.'.css';
    clearstatcache();
    file_put_contents( $cf7b_style_dir, $cf7b_theme );
  }

  public function delete() {
    $id = CF7B_Library::get('theme_id');
    $delete = $this->model->delete($id);
    if( $delete ) {
      wp_redirect( add_query_arg( array('page' => 'themes_cf7b', 'task' => 'display'), admin_url('admin.php') ) );
    }
  }

  public function setdefault() {
    $id = CF7B_Library::get('theme_id');
    $default = $this->model->setdefault($id);
    if( $default ) {
      wp_redirect( add_query_arg( array('page' => 'themes_cf7b', 'task' => 'display'), admin_url('admin.php') ) );
    }
  }

  public function duplicate() {
    $id = CF7B_Library::get('theme_id');
    $row = $this->model->get_duplicated_row( $id );
    if ( $row ) {
      $row['title'] = $row['title'] . ' - ' . 'Copy';
      $row['def'] = 0;
      $inserted = $this->model->insert_theme_to_db( $row );
      if ( $inserted !== FALSE ) {
        wp_redirect( add_query_arg( array('page' => 'themes_cf7b', 'task' => 'display'), admin_url('admin.php') ) );
      }
    }
  }

}