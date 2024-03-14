<?php
/**
 * Admin controller class.
 */
class Controller_cf7b {
  /**
   * @var $model
   */
  private $model;
  /**
   * @var $view
   */
  private $view;
  public $post_id;

  /* Constructor */
  public function __construct() {
    wp_enqueue_style('cfB_backend');
    require_once(wp_normalize_path( CF7B_BUILDER_INT_DIR . '/admin/models/model.php') );
    require_once(wp_normalize_path( CF7B_BUILDER_INT_DIR . '/admin/views/view.php') );

    if( !$this->cf7b_check_cf7_page_is() ) {
      return;
    }

    $this->model = new Model_cf7b();
    $this->view = new View_cf7b();
    $this->post_id = isset($_GET['post']) ? intval($_GET['post']) : 0;
    $task = isset($_POST['task']) ? sanitize_text_field($_POST['task']) : 'display';
    /* Create first revision */
    if( !$this->cf7b_check_revision() ) {
      $params['post_id'] =  $this->post_id;
      $params['template'] =  $this->model->cf7b_get_form_template( $this->post_id );
      $this->cf7b_create_revision( $params );
    }

    if ( method_exists($this, $task) ) {
      $this->$task();
    }
  }

  /* Check if admin page is cf7 page */
  public function cf7b_check_cf7_page_is() {
    if ( is_admin() && isset($_GET['page']) && (sanitize_text_field($_GET['page']) == 'wpcf7-new' || (sanitize_text_field($_GET['page']) == 'wpcf7')) || (isset($_POST['action']) && sanitize_text_field($_POST['action']) == 'cf7b_ajax') ) {
      return true;
    }
    return false;
  }

  /**
   * Check if form has revision in db
   *
   * @return bool
   */
  public function cf7b_check_revision() {
    if ( $this->post_id == 0 ) {
      return true;
    }
    $result = $this->model->cf7b_check_revision( $this->post_id );
    return $result;
  }

  /**
   * Create revision for forms
   *
   * @param array $params (post_id, temlate)
  */
  public function cf7b_create_revision( $params ) {
    $this->model->cf7b_set_revision( $params );
  }

  public function cf7b_create_popup_revision() {
    $post_id = $this->post_id;
    if($post_id == 0) {
      return;
    }
    $revs = $this->model->cf7b_get_revisions($post_id);
    $data = array();
    foreach ( $revs as $rev ) {
      $data[] = array(
        'id' => $rev['id'],
        'date' => date("Y-m-d",$rev['modified_date']),
        'time' => date("H:m:s",$rev['modified_date']),
        'status' => $rev['active'],
      );
    }

    $this->view->cf7b_popup_revision($data);
  }

  /* Get html form from frontend */
  public function get_preview_form() {
    $permalink = $this->model->get_preview_permalink();
    $data = wp_remote_get( $permalink.'?ver='.time() );
    if( isset($data->errors) || !isset($data['body']) ) {
      return '';
    }
    $html = $data['body'];
    // get wpcf7-form
    preg_match_all('/class="wpcf7-form init"(.*?)<\/form>/is', $html, $out);
    $html = isset($out[0][0]) ? $out[0][0] : $html;
    return $html;
  }

  /**
   * Convert template to html form for visual, ajax run
   * Return as json to ajax response html form and collection array
  */
  public function cf7b_add_template_to_form() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    update_option('cf7b_preview_post_id', $post_id, 1);
    $status = get_option('cf7b_collection'.$post_id);

    $post_template = isset($_POST['template']) ? $this->cf7b_sanitize_html( $_POST['template'] ) : '';

    $builder_active = 0;
    if ( strpos($post_template,'cf7b-section') > -1 ) {
      $builder_active = 1;
    }
    $preview_id = CF7B_Library::cf7b_get_preview_id();
    $this->model->cf7b_update_preview_post($preview_id, $post_template);

    $html = $this->get_preview_form();
    $cf7b_collection = $this->cf7b_collection( $html, $post_template );
    update_option('cf7b_collection'.$post_id, $cf7b_collection);
    if( !$builder_active ) {
        $newForm = '<div class="cf7b-content">';
        $newForm .= '<div class="cf7b-page">';
        $newForm .= '<div class="cf7b-section">';

        $newForm .= '<div class="cf7b-col-title"><span class="dashicons dashicons-trash"></span></div>';
        foreach ( $cf7b_collection as $data ) {
          $newForm .= '<div class="cf7b-col sortable">';
          $newForm .= $data['html_element'];
          $newForm .= '</div>';
        }
        $newForm .= '</div>'; /* End of section */
        $newForm .= '<div class="cf7b-addColumn"><span class="dashicons dashicons-plus cf7b-add-column" title="Add Column"></span></div>';
        $newForm .= '</div>'; /* End of Page */
        $newForm .= '<div class="cf7b-addPage"><span class="dashicons dashicons-plus cf7b-add-page" title="Add Page"></span></div>';
        $newForm .= '</div>'; /* End of content*/
    } else {
        /* Add page div and convert content id to class during the update 1.0.9 to 1.1.0 */
        if ( strpos($post_template,'cf7b-page') === false ) {
          if(strpos($post_template,"<div id=\"cf7b-content\">") > -1 ) {
            $post_template = str_replace("<div id=\"cf7b-content\">", "<div class=\"cf7b-content\">", $post_template);
          }
          $post_template = str_replace("<div class=\"cf7b-content\">", "<div class=\"cf7b-content\"><div class=\"cf7b-page\">", $post_template);
          $post_template .= "</div>";
        }
        if ( strpos($post_template, '<div class="cf7b-content">') > -1 ) {
            $newForm = $this->convert_to_html($post_template, $cf7b_collection);
        } else {
            $newForm = '<div class="cf7b-content">';
            $newForm .= $this->convert_to_html($post_template, $cf7b_collection);
            $newForm .= '</div>';
        }
    }


    $data = array(
      'form'=>$newForm,
      'data'=>$cf7b_collection
    );
    $jsonData = json_encode($data);
    die($jsonData);
  }

  /**
   * Convert html form inputs to cf7 template tab, ajax run
   * Return as json to ajax response string
  */
  public function cf7b_add_form_to_template() {
    $post_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $html = isset($_POST['htmlForm']) ? $this->cf7b_sanitize_html( $_POST['htmlForm'] ) : '';
    $cf7b_collection = get_option('cf7b_collection'.$post_id);
    $response = $this->cf7b_convert_to_cf7( $html, $cf7b_collection );
    echo $this->cf7b_sanitize_html($response);
    die();
  }

  /**
   * Convert html template to html form
   *
   * @param string $cf7 form template html
   * @param array $cfb_collection

   * @return string real form html
  */
  function convert_to_html( $cf7, $cfb_collection ) {

    $cf7 = stripslashes($cf7);
    foreach ( $cfb_collection as $element_name=>$element ) {
      $el_attr = $element['cf7_template_attributes'];
      /* Add required icon to label for old forms */
      if( $el_attr['required'] === "true" && strpos( $cf7,'cf7b-label-'.$el_attr['name'].' cf7b-required') === false ) {
        $label_class = 'cf7b-label-'.$el_attr['name'];
        $cf7 = str_replace($label_class, $label_class.' cf7b-required', $cf7);
      }

      // get html element without divs
      preg_match('/(?<=<div class="cf7b-group-'. preg_replace('/\[]/i', '\[\]', $element_name) .'">)(.*?)(?=<\/div>)/is', $element['html_element'], $html_element);

      // convert cf7 to html
      $cf7 = str_replace($element['cf7_template'], $html_element[0], $cf7);

      // insert ***
      $cf7 = preg_replace('/(<div class="cf7b-row">)(.*?)(<span class="cf7b-label)/is', "$1<div class=\"cf7b-actions\"><span class=\"dashicons dashicons-edit-large\"></span><span class=\"dashicons dashicons-trash\"></span></div>$3", $cf7);
    }
    return $cf7;
  }

  /**
   * Sanitize string using wp_kses function
   *
   * @param string $html form real html

   * @return string
  */
  public function cf7b_sanitize_html( $html ) {
    $allowed_html = array(
      'input'  => array(
        'type'  => true,
        'name' => true,
        'value' => true,
        'size' => true,
        'class' => true,
        'id' => true,
        'aria-required' => true,
        'aria-invalid' => true,
        'placeholder' => true,
        'min' => true,
        'max' => true,
        'checked' => true,
        'autocomplete' => true,
        'accept' => true,
      ),
      'textarea'  => array(
        'name' => true,
        'cols' => true,
        'rows' => true,
        'aria-required' => true,
        'aria-invalid' => true,
        'placeholder' => true,
        'class' => true,
        'id' => true,

      ),
      'select'  => array(
        'name' => true,
        'aria-required' => true,
        'aria-invalid' => true,
        'placeholder' => true,
        'class' => true,
        'id' => true,
        'disabled' => true,
      ),
      'option'  => array(
        'value' => true,
      ),
      'span'  => array(
        'class' => true,
      ),
      'div'    => array(
        'class'=> true,
        'id'=> true,
      ),
      'label'  => array(
        'class'=> true,
      ),
    );
    $html = wp_kses( stripslashes( $html ), $allowed_html );
    return $html;
  }

  /**
   * Create collection data for form
   *
   * @param string $html form real html
   * @param string $cf7 collection which stored in wp_option
   * @return array new collection for new html ( form el name=>array(html_element,cf7_template,cf7_template_attributes) )
  */
  public function  cf7b_collection( $html, $cf7 ) {


    // find and replace all submit type inputs without attribute name
    $html = preg_replace_callback('/(<input.*?type="submit")((?:(?!name=).)*?>)/i', function($groups){
      static $i = 0;
      $i++;
      return $groups[1] . ' name="cf7b-random-name-'. $i . '"' . $groups[2];
    }, $html);

    // insert a line break after each cf7 template, for the regex patterns to work correctly
    $cf7 = preg_replace('/\[acceptance(?:(?!\[).)*?\[\/acceptance\]|\[.*?\]/is', "$0\n", $cf7);
    // find and replace all cf7 submit type templates without attribute name "cf7b-random-name-*"
    $cf7 = preg_replace_callback('/(\[submit)((?:(?!cf7b-random-name-).)*?\])/i', function($groups){
      static $i = 0;
      $i++;
      return $groups[1] . ' cf7b-random-name-'. $i . $groups[2];
    }, $cf7);


    $collection = array();

    // get all form elements (input, textarea, select) excluding hidden inputs
    preg_match_all('/(<input(?:(?!type="hidden").)*?>)|(<textarea(.*?)<\/textarea>)|(<select(.*?)<\/select>)/is', $html, $elements);

    foreach ($elements[0] as $element){

      // get element name
      preg_match('/name=\"(.*?)\"/is', $element, $element_name);
      $element_name = (isset($element_name[1])) ? $element_name[1] : 'error: element name not found';
      $element_name = str_replace('[]', '', $element_name);
      // get element cf7 template
      preg_match('/(\[acceptance.*?('.preg_replace('/\[\]/i', '', $element_name).').*?\[\/acceptance\])|(\[.*?('.preg_replace('/\[\]/i', '', $element_name).').*?\])/i', $cf7, $cf7_template);
      // get element label
      preg_match('/\<span class\="cf7b-label-'. preg_replace('/\[]/i', '\[\]', $element_name) .'"\>(.*?)\<\/span\>/i', $html, $html_element_label);
      $html_element_label = isset($html_element_label[1]) ? $html_element_label[1] : ucwords(str_replace("-", " ", $element_name));
      // add to collection
      if( !array_key_exists($element_name, $collection) ) {
        $cf7b_required_class = '';
        if ( isset($cf7_template[0]) ) {
          $attr = $this->cf7b_get_cf7_template_attributes($cf7_template[0]);
          $required = $attr['required'];
          if( $required === 'true') {
            $cf7b_required_class = 'cf7b-required';
          }
        }
        // add new
        $collection[$element_name] = array(
          'html_element_label'		=>	$html_element_label,
          'html_element'				=>	'<div class="cf7b-row"><div class="cf7b-actions"><span class="dashicons dashicons-edit-large"></span><span class="dashicons dashicons-trash"></span></div><span class="cf7b-label-'.$element_name.' '.$cf7b_required_class.'">'.$html_element_label.'</span><div class="cf7b-group-'.$element_name.'">' . $element . '</div>'.'</div>',
          'cf7_template'				=>	isset($cf7_template[0]) ? $cf7_template[0] : 'error: cf7 template not found',
          'cf7_template_attributes' 	=>	isset($cf7_template[0]) ? $this->cf7b_get_cf7_template_attributes($cf7_template[0]) : 'error: cf7 template attributes array not found'
        );

      } else {

        // add to existing
        $collection[$element_name]['html_element'] = substr_replace($collection[$element_name]['html_element'], $element , strlen($collection[$element_name]['html_element'])-12, 0);

      }


    }
    return $collection;
  }

  /**
   * Get attributes from template
   *
   * @param string $cf7_template cf7 template ([text* your-name])
   * @return array
  */
  public function cf7b_get_cf7_template_attributes( $cf7_template ){

    // get all attributes in template
    // get all attributes in template
    $cf7_template_attributes = array(

      'type'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/^\[(.*?)[\*\s]/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'required'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/\*/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'name'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/\s(.*?)(\]|\s)/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'values'			=>	call_user_func( function() use ($cf7_template) {
        preg_match_all('/\"(.*?)\"/i', $cf7_template, $out);
        return (isset($out[0])) ? implode("\n", $out[0]) : '';
      }),

      'placeholder'		=>	call_user_func( function() use ($cf7_template) {
        preg_match('/placeholder/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'akismet'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/akismet/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'id'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/id:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'class'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/class:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'min'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/min:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'max'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/max:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'label_first'		=>	call_user_func( function() use ($cf7_template) {
        preg_match('/label_first/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'use_label_element'	=>	call_user_func( function() use ($cf7_template) {
        preg_match('/use_label_element/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'exclusive'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/exclusive/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'optional'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/optional/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'limit'				=>	call_user_func( function() use ($cf7_template) {
        preg_match('/limit:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'filetypes'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/filetypes:(.*?)\s/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'content'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/\](.*?)\[/i', $cf7_template, $out);
        return (isset($out[1])) ? $out[1] : '';
      }),

      'multiple'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/multiple/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

      'include_blank'			=>	call_user_func( function() use ($cf7_template) {
        preg_match('/include_blank/i', $cf7_template, $out);
        return (isset($out[0])) ? 'true' : 'false';
      }),

    );

    return $cf7_template_attributes;
  }

  /**
   * Convert html form inputs to cf7 template, ajax run
   *
   * @param string $html form
   * @param array $cf7b_collection
   *
   * @return string
  */
  public function cf7b_convert_to_cf7( $html, $cf7b_collection ) {
    $html = stripslashes($html);
    // remove cfb actions buttons
    $html = preg_replace('/\<div class\="cf7b-actions"\>\<span class\="dashicons dashicons-edit-large"\>\<\/span\>\<span class\="dashicons dashicons-trash"\>\<\/span\>\<\/div\>/is', '', $html);

    /* remove add column div */
    $html = preg_replace('/\<div class\="cf7b-addColumn"\>\<span class\="dashicons dashicons-plus cf7b-add-column"\>\<\/span\>\<\/div\>/is', '', $html);

    /* remove add page div */
    $html = preg_replace('/\<div class\="cf7b-addPage"\>\<span class\="dashicons dashicons-plus cf7b-add-page"\>\<\/span\>\<\/div\>/is', '', $html);

    /* remove column title div */
    $html = preg_replace('/\<div class\="cf7b-col-title"\>\<span class\="dashicons dashicons-trash"\>\<\/span\>\<\/div\>/is', '', $html);

    // remove drag drop classes
    $html = preg_replace('/\sui-sortable-handle|\ssortable|\sui-sortable/i', '', $html);

    // remove drag drop classes
    $html = preg_replace('/\sui-sortable-handle|\ssortable|\sui-sortable/i', '', $html);

    /* remove empty column */
    $html = preg_replace('/\<div class\="cf7b-col cf7b-unvisible"\>\<\/div\>/is', '', $html);

    foreach ($cf7b_collection as $element_name=>$element){
      // convert to cf7 template
      $html = preg_replace('/(?<=<div class="cf7b-group-'. preg_replace('/\[]/i', '\[\]', $element_name) .'">)(.*?)(?=<\/div>)/is', $element['cf7_template'], $html);
    }
    return $html;
  }

  public function cf7b_change_to_revision() {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $revision_data = $this->model->cf7b_get_revision_by_id( $id );
    echo $this->cf7b_sanitize_html($revision_data['template']);
    die();
  }

  /**
   * Display.
   */
  public function cf7b_display() {
    $view = new View_cf7b();
    $view->cf7b_display();
  }
}