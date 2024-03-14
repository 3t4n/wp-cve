<?php
class ViewThemes_cf7b {

  public function __construct() {
    $this->actions = array(
      'duplicate' => array(
        'title' => __('Duplicate', 'cf7b'),
      ),
      'delete' => array(
        'title' => __('Delete', 'cf7b'),
      ),
    );

  }

  /**
   * Display page.
   *
   * @param $params array
  */
  public function display( $params = array() ) {
    if( !CF7B_PRO ) {
      CF7B_Library::buy_pro_banner();
    }
    wp_enqueue_style('cfB_themes');

    $params['actions'] = $this->actions;
    ob_start();
    echo $this->body($params);
    // Pass the content to form.
    $form_attr = array(
      'id' => 'cf7b_themes',
      'name' => 'cf7b_themes',
      'class' => 'cf7b_themes cf7b-form',
      'action' => add_query_arg(array( 'page' => 'themes_cf7b' ), 'admin.php'),
    );
    echo $this->form(ob_get_clean(), $form_attr);
  }

  /**
   * Generate form.
   *
   * @param string $content
   * @param array  $attr
   *
   * @return string Form html.
   */
  protected function form($content = '', $attr = array()) {
    ob_start();
    // Form.
    $action = isset($attr['action']) ? esc_attr($attr['action']) : '';
    $method = isset($attr['method']) ? esc_attr($attr['method']) : 'post';
    $name = isset($attr['name']) ? esc_attr($attr['name']) : 'cf7b_form';
    $id = isset($attr['id']) ? esc_attr($attr['id']) : '';
    $class = isset($attr['class']) ? esc_attr($attr['class']) : 'cf7b_form';
    $style = isset($attr['style']) ? esc_attr($attr['style']) : '';
    $theme_id = isset($attr['theme_id']) ? esc_attr($attr['theme_id']) : '';
    ?>
    <div id="fm-form-admin" class="wrap">
    <?php
    // Generate message container by message id or directly by message.
    $message_id = CF7B_Library::get('message', 0, 'intval');
    $message = CF7B_Library::get('msg', '');
    echo CF7B_Library::message_id($message_id, $message);
    ?>
    <form
      <?php echo $action ? 'action="' . esc_url($action) . '"' : ''; ?>
      <?php echo $method ? 'method="' . esc_html($method) . '"' : ''; ?>
      <?php echo $name ? ' name="' . esc_html($name) . '"' : ''; ?>
      <?php echo $id ? ' id="' . intval($id) . '"' : ''; ?>
      <?php echo $class ? ' class="' . sanitize_html_class($class) . '"' : ''; ?>
      <?php echo $style ? ' style="' . esc_html($style) . '"' : ''; ?>
    ><?php
      echo $content;
      // Add nonce to form.
      //wp_nonce_field(WDFMInstance(self::PLUGIN)->nonce, WDFMInstance(self::PLUGIN)->nonce);
      ?>
      <input id="task" name="task" type="hidden" value=""/>
      <input id="theme_id" name="theme_id" type="hidden" value="<?php echo $theme_id; ?>"/>
    </form>
    </div><?php
    return ob_get_clean();
  }


  /**
   * Generate page body.
   *
   * @param $params
   * @return string Body html.
   */
  public function body( $params = array() ) {
/*    $order = $params['order'];
    $orderby = $params['orderby'];*/

    $actions = $params['actions'];
    $page = $params['page'];

/*    $items_per_page = $params['items_per_page'];*/
    $rows_data = $params['rows_data'];
    $page_url = add_query_arg(array(
                                'page' => $page,
/*                                WDFMInstance(self::PLUGIN)->nonce => wp_create_nonce(WDFMInstance(self::PLUGIN)->nonce),*/
                              ), admin_url('admin.php'));
    echo $this->title(array(
                        'title' => 'Themes',
                        'title_class' => 'wd-header',
                        'add_new_button' => array(
                          'href' => add_query_arg(array( 'page' => $page, 'task' => 'edit' ), admin_url('admin.php')),
                        ),
                      ));
    //echo $this->search();
    ?>
    <table class="adminlist table table-striped wp-list-table widefat fixed pages">
      <thead>
      <tr>
        <td id="cb" class="column-cb check-column">
          <label class="screen-reader-text" for="cb-select-all-1"><?php _e('Select all', 'cf7b'); ?></label>
          <input id="check_all" type="checkbox" />
        </td>
        <td><?php echo  __('Title', 'cf7b'); ?></td>
        <td><?php echo  __('Default', 'cf7b'); ?></td>
      </tr>
      </thead>
      <tbody>
      <?php
      if ( $rows_data ) {
        foreach ( $rows_data as $row_data ) {
          $alternate = (!isset($alternate) || $alternate == '') ? 'class="alternate"' : '';
          $edit_url = add_query_arg(array( 'page' => $page, 'task' => 'edit', 'theme_id' => $row_data['id'] ), admin_url('admin.php'));
          $duplicate_url = add_query_arg(array( 'task' => 'duplicate', 'theme_id' => $row_data['id'] ), $page_url);
          $delete_url = add_query_arg(array( 'task' => 'delete', 'theme_id' => $row_data['id'] ), $page_url);
          $default_url = add_query_arg(array( 'task' => 'setdefault', 'theme_id' => $row_data['id'] ), $page_url);
          ?>
          <tr id="tr_<?php echo sanitize_html_class($row_data['id']); ?>" <?php echo (!isset($alternate) || $alternate == '') ? 'class="alternate"' : '' ?>>
            <th class="check-column">
              <input id="check_<?php echo sanitize_html_class($row_data['id']); ?>" name="check[<?php echo intval($row_data['id']); ?>]" type="checkbox" />
            </th>
            <td class="column-primary" data-colname="<?php _e('Title', 'cf7b'); ?>">
              <strong>
                <a href="<?php echo esc_url($edit_url); ?>"><?php echo esc_html($row_data['title']); ?></a>
              </strong>
              <div class="row-actions">
                <span><a href="<?php echo esc_url($edit_url); ?>"><?php _e('Edit', 'cf7b'); ?></a> |</span>
                <span><a href="<?php echo esc_url($duplicate_url); ?>"><?php _e('Duplicate', 'cf7b'); ?></a> |</span>
                <span class="trash"><a onclick="if (!confirm('<?php echo addslashes(__('Do you want to delete selected item?', 'cf7b')); ?>')) {return false;}" href="<?php echo esc_url($delete_url); ?>"><?php _e('Delete', 'cf7b'); ?></a></span>
                <?php if( !CF7B_PRO ) { ?>
                <a href="<?php echo CF7B_UPGRADE_PRO_URL ?>" class="cf7b-upgrade-mini-button" target="_blank">Upgrade Pro</a>
                <?php } ?>
              </div>
              <button class="toggle-row" type="button">
                <span class="screen-reader-text"><?php _e('Show more details', 'cf7b'); ?></span>
              </button>
            </td>
            <td class="col_default" data-colname="<?php _e('Default', 'cf7b'); ?>">
              <?php
              $default = ($row_data['def']) ? 1 : 0;
              $default_image = ($row_data['def']) ? 'default' : 'notdefault';
              if (!$default) {
              ?>
              <a href="<?php echo esc_url($default_url) ?>">
                <?php
                }
                ?>
                <span class="dashicons dashicons-star-filled <?php echo $default ? 'cf7b-theme-def' : 'cf7b-theme-grey' ?>"></span>
                <?php
                if ($default) {
                ?>
              </a>
            <?php
            }
            ?>
            </td>
          </tr>
          <?php
        }
      }
      else {
        //echo WDW_FM_Library(self::PLUGIN)->no_items('themes');
      }
      ?>
      </tbody>
    </table>
    <?php
  }

  /**
   * Generate title.
   *
   * @param array $params
   *
   * @return string Title html.
   */
  protected function title( $params = array() ) {
    $title = !empty($params['title']) ? $params['title'] : '';
    $title_class = !empty($params['title_class']) ? $params['title_class'] : '';
    $title_name = !empty($params['title_name']) ? $params['title_name'] : '';
    $title_id = !empty($params['title_id']) ? $params['title_id'] : '';
    $title_value = !empty($params['title_value']) ? $params['title_value'] : '';
    $add_new_button = !empty($params['add_new_button']) ? $params['add_new_button'] : '';

    $attributes = '';
    if ( !empty($add_new_button) && is_array($add_new_button) ) {
      foreach ( $add_new_button as $key => $val ) {
        $attributes .= $key . '="' . $val . '"';
      }
    }
    ob_start();
    ?><div class="wd-page-title <?php echo sanitize_html_class($title_class); ?>">
    <h1 class="wp-heading-inline"><?php echo esc_html($title); ?>
      <?php
      if ( $title_name || $title_id || $title_value ) {
        ?>
        <span id="fm-title-edit">
          <input type="text" id="<?php echo esc_attr($title_id); ?>" name="<?php echo esc_attr($title_name); ?>" value="<?php echo esc_attr($title_value); ?>" />
        </span>
        <?php
      }
      if ( $add_new_button ) {
        ?>
        <a class="page-title-action" <?php echo sanitize_html_class($attributes); ?>>
          <?php _e('Add New', 'cf7b'); ?>
        </a>
        <?php
      }
      ?>
    </h1>
    </div><?php
    return ob_get_clean();
  }



  public function edit( $params = array() ) {
    if( !CF7B_PRO ) {
      CF7B_Library::buy_pro_banner();
    }
    wp_enqueue_script('jquery-ui-tabs');
    wp_enqueue_style('jquery-ui-theme-smoothness', CF7B_URL.'/style/jquery-ui.css');
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'my-script-handle', CF7B_URL.'/script/cf7b_themes.js', array( 'wp-color-picker' ), false, true );
    wp_enqueue_style('cfB_themes');
    wp_enqueue_script('cfB_themes');
    $id = CF7B_Library::get('theme_id',0);
    ?>
    <form method="post" action="admin.php?page=themes_cf7b" id="cf7b-theme-form">
    <div class="wrap">
      <div class="cf7b-themes-header">
        <h1 class="cf7b-page-title">Theme Title</h1>
        <input type="text" id="name" name="theme_title" value="<?php echo esc_attr($params['title']); ?>" class="spider_text_input cf7b_requried">
        <button class="button button-primary" id="cf7b-save-button"><?php echo $id ? 'Update' : 'Save' ?></button>
      </div>
    </div>

    <?php
    $this->bodyEdit( $params['options'] );
  }

  public function bodyEdit( $params = array() ) {
  ?>
    <div id="tabs">
      <ul>
        <li><a href="#cf7b_general">General</a></li>
        <li><a href="#cf7b_inputs">Input Fields</a></li>
        <li><a href="#cf7b_textarea">Textarea</a></li>
        <li><a href="#cf7b_drodown">Dropdown Fields</a></li>
        <li><a href="#cf7b_radio">Radio Fields</a></li>
        <li><a href="#cf7b_checkbox">Checkbox Fields</a></li>
        <li><a href="#cf7b_button">Buttons</a></li>
        <li><a href="#cf7b_pagination">Paginations</a></li>
        <li><a href="#cf7b_custom">Custom CSS</a></li>
      </ul>

      <div id="cf7b_general">
        <div class="cf7b-row">
          <label>Container Width</label>
          <input type="text" name="general_container_width" value="<?php echo esc_attr($params['general']['general_container_width']); ?>" />
          <span class="cf7b-um">%</span>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="general_bg_color" value="<?php echo esc_attr($params['general']['general_bg_color']); ?>" class="general_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="general_font_size" value="<?php echo esc_attr($params['general']['general_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="general_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['general']['general_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['general']['general_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['general']['general_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['general']['general_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['general']['general_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Font Color</label>
          <input type="text" name="general_font_color" value="<?php echo esc_attr($params['general']['general_font_color']); ?>" class="general_font_color" />
        </div>

        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="general_margin" value="<?php echo esc_attr($params['general']['general_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="general_padding" value="<?php echo esc_attr($params['general']['general_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <hr>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="general_border_width" min="0" value="<?php echo esc_attr($params['general']['general_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="general_border_style">
            <option value="solid" <?php echo ($params['general']['general_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['general']['general_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['general']['general_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['general']['general_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['general']['general_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['general']['general_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['general']['general_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['general']['general_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['general']['general_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['general']['general_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="general_border_color" value="<?php echo esc_attr($params['general']['general_border_color']); ?>" class="general_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="general_border_radius" value="<?php echo esc_attr($params['general']['general_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <hr>
        <div class="cf7b-row">
          <label>Column Margin</label>
          <input type="text" name="general_column_margin" value="<?php echo esc_attr($params['general']['general_column_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Column Padding</label>
          <input type="text" name="general_column_padding" value="<?php echo esc_attr($params['general']['general_column_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>

      </div>
      <div id="cf7b_inputs">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="input_width" value="<?php echo esc_attr($params['input_fields']['input_width']); ?>">
          <span class="cf7b-um">%</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="input_height" value="<?php echo esc_attr($params['input_fields']['input_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="input_font_size" value="<?php echo esc_attr($params['input_fields']['input_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="input_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['input_fields']['input_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['input_fields']['input_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['input_fields']['input_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['input_fields']['input_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['input_fields']['input_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="input_bg_color" value="<?php echo esc_attr($params['input_fields']['input_bg_color']); ?>" class="input_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Color</label>
          <input type="text" name="input_color" value="<?php echo esc_attr($params['input_fields']['input_color']); ?>" class="input_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="input_margin" value="<?php echo esc_attr($params['input_fields']['input_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="input_padding" value="<?php echo esc_attr($params['input_fields']['input_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="input_border_width" min="0" value="<?php echo esc_attr($params['input_fields']['input_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="input_border_style">
            <option value="solid" <?php echo ($params['input_fields']['input_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['input_fields']['input_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['input_fields']['input_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['input_fields']['input_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['input_fields']['input_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['input_fields']['input_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['input_fields']['input_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['input_fields']['input_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['input_fields']['input_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['input_fields']['input_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="input_border_color" value="<?php echo esc_attr($params['input_fields']['input_border_color']); ?>" class="input_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="input_border_radius" value="<?php echo esc_attr($params['input_fields']['input_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="input_box_shadow" value="<?php echo esc_attr($params['input_fields']['input_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>

      </div>
      <div id="cf7b_textarea">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="textarea_width" value="<?php echo esc_attr($params['textarea']['textarea_width']); ?>">
          <span class="cf7b-um">%</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="textarea_height" value="<?php echo esc_attr($params['textarea']['textarea_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="textarea_font_size" value="<?php echo esc_attr($params['textarea']['textarea_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="textarea_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['textarea']['textarea_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['textarea']['textarea_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['textarea']['textarea_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['textarea']['textarea_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['textarea']['textarea_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="textarea_bg_color" value="<?php echo esc_attr($params['textarea']['textarea_bg_color']); ?>" class="textarea_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Color</label>
          <input type="text" name="textarea_color" value="<?php echo esc_attr($params['textarea']['textarea_color']); ?>" class="textarea_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="textarea_margin" value="<?php echo esc_attr($params['textarea']['textarea_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="textarea_padding" value="<?php echo esc_attr($params['textarea']['textarea_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
          </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="textarea_border_width" min="0" value="<?php echo esc_attr($params['textarea']['textarea_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="textarea_border_style">
            <option value="solid" <?php echo ($params['textarea']['textarea_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['textarea']['textarea_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['textarea']['textarea_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['textarea']['textarea_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['textarea']['textarea_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['textarea']['textarea_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['textarea']['textarea_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['textarea']['textarea_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['textarea']['textarea_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['textarea']['textarea_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="textarea_border_color" value="<?php echo esc_attr($params['textarea']['textarea_border_color']); ?>" class="textarea_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="textarea_border_radius" value="<?php echo esc_attr($params['textarea']['textarea_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="textarea_box_shadow" value="<?php echo esc_attr($params['textarea']['textarea_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>

      </div>
      <div id="cf7b_drodown">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="drodown_width" value="<?php echo esc_attr($params['drodown_fields']['drodown_width']); ?>">
          <span class="cf7b-um">%</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="drodown_height" value="<?php echo esc_attr($params['drodown_fields']['drodown_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="drodown_font_size" value="<?php echo esc_attr($params['drodown_fields']['drodown_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="drodown_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['drodown_fields']['drodown_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['drodown_fields']['drodown_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['drodown_fields']['drodown_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['drodown_fields']['drodown_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['drodown_fields']['drodown_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="drodown_bg_color" value="<?php echo esc_attr($params['drodown_fields']['drodown_bg_color']); ?>" class="drodown_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Color</label>
          <input type="text" name="drodown_color" value="<?php echo esc_attr($params['drodown_fields']['drodown_color']); ?>" class="drodown_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="drodown_margin" value="<?php echo esc_attr($params['drodown_fields']['drodown_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="drodown_padding" value="<?php echo esc_attr($params['drodown_fields']['drodown_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="drodown_border_width" min="0" value="<?php echo esc_attr($params['drodown_fields']['drodown_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="drodown_border_style">
            <option value="solid" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['drodown_fields']['drodown_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="drodown_border_color" value="<?php echo esc_attr($params['drodown_fields']['drodown_border_color']); ?>" class="drodown_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="drodown_border_radius" value="<?php echo esc_attr($params['drodown_fields']['drodown_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="drodown_box_shadow" value="<?php echo esc_attr($params['drodown_fields']['drodown_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>
      </div>
      <div id="cf7b_radio">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="radio_width" value="<?php echo esc_attr($params['radio_fields']['radio_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="radio_height" value="<?php echo esc_attr($params['radio_fields']['radio_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="radio_bg_color" value="<?php echo esc_attr($params['radio_fields']['radio_bg_color']); ?>" class="radio_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Checked Background Color</label>
          <input type="text" name="radio_checked_bg_color" value="<?php echo esc_attr($params['radio_fields']['radio_checked_bg_color']); ?>" class="radio_checked_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="radio_margin" value="<?php echo esc_attr($params['radio_fields']['radio_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="radio_padding" value="<?php echo esc_attr($params['radio_fields']['radio_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="radio_border_width" min="0" value="<?php echo esc_attr($params['radio_fields']['radio_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="radio_border_style">
            <option value="solid" <?php echo ($params['radio_fields']['radio_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['radio_fields']['radio_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['radio_fields']['radio_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['radio_fields']['radio_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['radio_fields']['radio_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['radio_fields']['radio_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['radio_fields']['radio_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['radio_fields']['radio_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['radio_fields']['radio_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['radio_fields']['radio_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="radio_border_color" value="<?php echo esc_attr($params['radio_fields']['radio_border_color']); ?>" class="radio_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="radio_border_radius" value="<?php echo esc_attr($params['radio_fields']['radio_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="radio_box_shadow" value="<?php echo esc_attr($params['radio_fields']['radio_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>
      </div>
      <div id="cf7b_checkbox">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="checkbox_width" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="checkbox_height" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="checkbox_bg_color" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_bg_color']); ?>" class="checkbox_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Checked Background Color</label>
          <input type="text" name="checkbox_checked_bg_color" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_checked_bg_color']); ?>" class="checkbox_checked_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="checkbox_margin" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="checkbox_padding" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="checkbox_border_width" min="0" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="checkbox_border_style">
            <option value="solid" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['checkbox_fields']['checkbox_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="checkbox_border_color" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_border_color']); ?>" class="checkbox_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="checkbox_border_radius" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="checkbox_box_shadow" value="<?php echo esc_attr($params['checkbox_fields']['checkbox_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>

      </div>
      <div id="cf7b_button">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="button_width" value="<?php echo esc_attr($params['button_fields']['button_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="button_height" value="<?php echo esc_attr($params['button_fields']['button_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="button_font_size" value="<?php echo esc_attr($params['button_fields']['button_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Color</label>
          <input type="text" name="button_color" value="<?php echo esc_attr($params['button_fields']['button_color']); ?>" class="button_color" />
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="button_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['button_fields']['button_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['button_fields']['button_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['button_fields']['button_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['button_fields']['button_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['button_fields']['button_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="button_bg_color" value="<?php echo esc_attr($params['button_fields']['button_bg_color']); ?>" class="button_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="button_margin" value="<?php echo esc_attr($params['button_fields']['button_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="button_padding" value="<?php echo esc_attr($params['button_fields']['button_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="button_border_width" min="0" value="<?php echo esc_attr($params['button_fields']['button_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="button_border_style">
            <option value="solid" <?php echo ($params['button_fields']['button_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['button_fields']['button_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['button_fields']['button_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['button_fields']['button_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['button_fields']['button_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['button_fields']['button_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['button_fields']['button_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['button_fields']['button_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['button_fields']['button_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['button_fields']['button_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="button_border_color" value="<?php echo esc_attr($params['button_fields']['button_border_color']); ?>" class="button_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="button_border_radius" value="<?php echo esc_attr($params['button_fields']['button_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="button_box_shadow" value="<?php echo esc_attr($params['button_fields']['button_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>
        <div class="cf7b-row">
          <label>Text align</label>
          <select name="button_text_align">
            <option value="left" <?php echo ($params['button_fields']['button_text_align'] == 'left') ? 'selected' : '' ?>>Left</option>
            <option value="center" <?php echo ($params['button_fields']['button_text_align'] == 'center') ? 'selected' : '' ?>>Center</option>
            <option value="right" <?php echo ($params['button_fields']['button_text_align'] == 'right') ? 'selected' : '' ?>>Right</option>
          </select>
        </div>
        <!-- Hover -->
        <div class="cf7b-row">
          <label>Hover Font Weight</label>
          <select name="hover_button_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['button_fields']['button_hover_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['button_fields']['button_hover_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['button_fields']['button_hover_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['button_fields']['button_hover_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['button_fields']['button_hover_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Hover Background Color</label>
          <input type="text" name="button_hover_bg_color" value="<?php echo esc_attr($params['button_fields']['button_hover_bg_color']); ?>" class="button_hover_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Hover Font Color</label>
          <input type="text" name="button_hover_color" value="<?php echo esc_attr($params['button_fields']['button_hover_color']); ?>" class="button_hover_color" />
        </div>
      </div>
      <div id="cf7b_pagination">
        <div class="cf7b-row">
          <label>Width</label>
          <input type="text" name="pagination_width" value="<?php echo esc_attr($params['pagination_fields']['pagination_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Height</label>
          <input type="text" name="pagination_height" value="<?php echo esc_attr($params['pagination_fields']['pagination_height']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Size</label>
          <input type="text" name="pagination_font_size" value="<?php echo esc_attr($params['pagination_fields']['pagination_font_size']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Font Color</label>
          <input type="text" name="pagination_color" value="<?php echo esc_attr($params['pagination_fields']['pagination_color']); ?>" class="pagination_color" />
        </div>
        <div class="cf7b-row">
          <label>Font Weight</label>
          <select name="pagination_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['pagination_fields']['pagination_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['pagination_fields']['pagination_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['pagination_fields']['pagination_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['pagination_fields']['pagination_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['pagination_fields']['pagination_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Background Color</label>
          <input type="text" name="pagination_bg_color" value="<?php echo esc_attr($params['pagination_fields']['pagination_bg_color']); ?>" class="pagination_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Margin</label>
          <input type="text" name="pagination_margin" value="<?php echo esc_attr($params['pagination_fields']['pagination_margin']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Padding</label>
          <input type="text" name="pagination_padding" value="<?php echo esc_attr($params['pagination_fields']['pagination_padding']); ?>">
          <p class="cf7b-description">Use CSS type values. Ex 5px 3px</p>
        </div>
        <div class="cf7b-row">
          <label>Border Width</label>
          <input type="number" name="pagination_border_width" min="0" value="<?php echo esc_attr($params['pagination_fields']['pagination_border_width']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Border Type</label>
          <select name="pagination_border_style">
            <option value="solid" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'solid') ? 'selected' : '' ?>>Solid</option>
            <option value="dotted" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'dotted') ? 'selected' : '' ?>>Dotted</option>
            <option value="dashed" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'dashed') ? 'selected' : '' ?>>Dashed</option>
            <option value="double" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'double') ? 'selected' : '' ?>>Double</option>
            <option value="groove" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'groove') ? 'selected' : '' ?>>Groove</option>
            <option value="ridge" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'ridge') ? 'selected' : '' ?>>Ridge</option>
            <option value="inset" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'inset') ? 'selected' : '' ?>>Inset</option>
            <option value="outset" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'outset') ? 'selected' : '' ?>>Outset</option>
            <option value="initial" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'initial') ? 'selected' : '' ?>>Initial</option>
            <option value="inherit" <?php echo ($params['pagination_fields']['pagination_border_style'] == 'inherit') ? 'selected' : '' ?>>Inherit</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Border Color</label>
          <input type="text" name="pagination_border_color" value="<?php echo esc_attr($params['pagination_fields']['pagination_border_color']); ?>" class="pagination_border_color" />
        </div>
        <div class="cf7b-row">
          <label>Border Radius</label>
          <input type="number" name="pagination_border_radius" value="<?php echo esc_attr($params['pagination_fields']['pagination_border_radius']); ?>">
          <span class="cf7b-um">px</span>
        </div>
        <div class="cf7b-row">
          <label>Box Shadow</label>
          <input type="text" name="pagination_box_shadow" value="<?php echo esc_attr($params['pagination_fields']['pagination_box_shadow']); ?>" placeholder="e.g. 5px 5px 2px #888888">
        </div>
        <div class="cf7b-row">
          <label>Text align prev</label>
          <select name="pagination_prev_text_align">
            <option value="left" <?php echo ($params['pagination_fields']['pagination_prev_text_align'] == 'left') ? 'selected' : '' ?>>Left</option>
            <option value="center" <?php echo ($params['pagination_fields']['pagination_prev_text_align'] == 'center') ? 'selected' : '' ?>>Center</option>
            <option value="right" <?php echo ($params['pagination_fields']['pagination_prev_text_align'] == 'right') ? 'selected' : '' ?>>Right</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Text align next</label>
          <select name="pagination_next_text_align">
            <option value="left" <?php echo ($params['pagination_fields']['pagination_next_text_align'] == 'left') ? 'selected' : '' ?>>Left</option>
            <option value="center" <?php echo ($params['pagination_fields']['pagination_next_text_align'] == 'center') ? 'selected' : '' ?>>Center</option>
            <option value="right" <?php echo ($params['pagination_fields']['pagination_next_text_align'] == 'right') ? 'selected' : '' ?>>Right</option>
          </select>
        </div>
        <!-- Hover -->
        <div class="cf7b-row">
          <label>Hover Font Weight</label>
          <select name="pagination_hover_font_weight">
            <option value=""></option>
            <option value="normal" <?php echo ($params['pagination_fields']['pagination_hover_font_weight'] == 'normal') ? 'selected' : '' ?>>Normal</option>
            <option value="bold" <?php echo ($params['pagination_fields']['pagination_hover_font_weight'] == 'bold') ? 'selected' : '' ?>>Bold</option>
            <option value="bolder" <?php echo ($params['pagination_fields']['pagination_hover_font_weight'] == 'bolder') ? 'selected' : '' ?>>Bolder</option>
            <option value="lighter" <?php echo ($params['pagination_fields']['pagination_hover_font_weight'] == 'lighter') ? 'selected' : '' ?>>Lighter</option>
            <option value="initial" <?php echo ($params['pagination_fields']['pagination_hover_font_weight'] == 'initial') ? 'selected' : '' ?>>Initial</option>
          </select>
        </div>
        <div class="cf7b-row">
          <label>Hover Background Color</label>
          <input type="text" name="pagination_hover_bg_color" value="<?php echo esc_attr($params['pagination_fields']['pagination_hover_bg_color']); ?>" class="pagination_hover_bg_color" />
        </div>
        <div class="cf7b-row">
          <label>Hover Font Color</label>
          <input type="text" name="pagination_hover_color" value="<?php echo esc_attr($params['pagination_fields']['pagination_hover_color']); ?>" class="pagination_hover_color" />
        </div>
      </div>
      <div id="cf7b_custom">
        <textarea name="custom_css" class="cf7b_theme_custom_css"><?php echo esc_attr($params['custom_css']['custom_css']); ?></textarea>
      </div>
      <input type="hidden" name="task" value="save_theme">
      <input type="hidden" name="theme_active_tab" value="cf7b_general" id="theme_active_tab">
      <input type="hidden" name="theme_id" id="cf7b-theme-id" value="<?php echo CF7B_Library::get('theme_id',0); ?>">
      </form>
    </div>
  <?php
  }


}