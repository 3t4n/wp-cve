<?php
/**
 * @file
 * Admin configuration management
 */

include_once INTEL_DIR . 'includes/class-intel-form.php';

function intel_get_wizard_state($wizard_info, $default_value = array()) {
  $un = $wizard_info;
  if (is_array($wizard_info)) {
    $un = !empty($wizard_info['un']) ? $wizard_info['un'] : '';
  }

  return get_option('intel_wizard_' . $wizard_info['un'] . '_state', $default_value);
}

function intel_update_wizard_state($wizard_info, $state) {
  $un = $wizard_info;
  if (is_array($wizard_info)) {
    $un = !empty($wizard_info['un']) ? $wizard_info['un'] : '';
  }

  update_option('intel_wizard_' . $wizard_info['un'] . '_state', $state);
}

function intel_wizard_form($form, &$form_state, $wizard_info) {

  add_thickbox();

  $title = !empty($wizard_info['title']) ? $wizard_info['title'] : Intel_Df::t('Setup Wizard');
  intel()->set_page_title($title);

  wp_enqueue_script('intel-wizard', INTEL_URL . 'js/intel.wizard.js', array( 'jquery' ));
  wp_enqueue_style('intel-wizard', INTEL_URL . 'css/intel.wizard.css');

  $form_state['wizard_info'] = $wizard_info;

  $wizard_state = intel_get_wizard_state($wizard_info);
  $form_state['wizard_state'] = &$wizard_state;

  $wizard_step = '';
  $last_step = '';
  $first_step = '';
  $wizard_successes = array();
  $statuses = array();
  // input_setup_step used to maintain setup step when form is submitted
  $input_wizard_step = !empty($form_state['input']['wizard_step']) ? $form_state['input']['wizard_step'] : '';
  foreach ($wizard_info['steps'] as $k => $v) {
    // spoof wizard_step in form_state so form_check can build function properly
    $form_state['wizard_step'] = $k;

    if (!$wizard_step) {
      // get status from _check
      $statuses[$k] = intel_wizard_form_check($form, $form_state);
      if ($input_wizard_step && $input_wizard_step == $k) {
        $wizard_step = $k;
      }
      else {
        if (!empty($statuses[$k]['success'])) {
          $wizard_successes[] = $k;
        }
        else {
          $wizard_step = $k;
        }
      }
    }

    if (!$first_step) {
      $first_step = $k;
    }
    $last_step = $k;
  }
  if (!$wizard_step) {
    $wizard_step = $last_step;
  }

  // maintain current wizard step on form submission
  if (!empty($form_state['input']['wizard_step'])) {
    $wizard_step = $form_state['input']['wizard_step'];
  }

  $wizard_step_completed = !empty($wizard_state['step']) ? $wizard_state['step'] : '';
  $wizard_state['successes'] = $wizard_successes;
  $wizard_state['step'] = $wizard_step;

  // check if step is fixed using query param
  if (!empty($_GET['step'])) {
    $wizard_step = $_GET['step'];
  }
  else {
    // check if on completed step
    if (empty($wizard_state['completed']) && !empty($wizard_info['steps'][$wizard_step]['completed'])) {
      $wizard_state['completed'] = time();
    }
    intel_update_wizard_state($wizard_info, $wizard_state);
  }

  $wrapper = '<div class="intel-wizard intel-wizard-' . Intel_Df::drupal_clean_css_identifier($wizard_info['un']) . ' intel-wizard-' . Intel_Df::drupal_clean_css_identifier($wizard_info['un']) . '-' . $wizard_step . '">';
  $form['wrapper_0'] = array(
    '#type' => 'markup',
    '#markup' => $wrapper,
  );

  if ($wizard_step_completed > 99) {
    $progressper = 100;
  }
  else {
    $progressper = round(100 * count($wizard_successes) /  (count($wizard_info['steps']) - 1));
  }

  //intel_d($wizard_step_completed);
  //intel_d($progressper);

  $progressbar = '';
  $progressbar .= '<style>.bootstrap-wrapper .progress {background-color: #BBB; } </style>';
  $progressbar .= <<<EOT
  <script>
  jQuery(document).ready(function(){
  });
  </script>
EOT;

  //$progressbar .= '<div class="card">';
  $progressbar .= '<div class="clearfix text-uppercase">';
  $progressbar .= '<div class="pull-left">' . Intel_Df::t('Start') . '</div>';
  //$progressbar .= '<div class="pull-left" id="progress-bar-status" style="margin-left: ' . $progressper . '%;">' . 'Almost there!' . '</div>';
  $progressbar .= '<div class="pull-right">' . Intel_Df::t('Finish') . '</div>';
  $progressbar .= '</div>';
  //$progressbar .= '<div class="progress" style="margin-left: 1.2em; margin-right: 1.5em;">';
  $progressbar .= '<div class="progress">';
  $progressbar .= '<div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="' . $progressper . '" aria-valuemin="0" aria-valuemax="100" style="width: ' . ($progressper ? $progressper : 2) . '%;">';
  $progressbar .= $progressper . '%';
  $progressbar .= '</div>';
  //$progressbar .= '<div class="progress-bar text-left" style="width: ' . (100 - $progressper) . '%;">';
  $progressbar .= '<div id="progress-bar-mark">';
  //$progressbar .= 'Almost there';
  $progressbar .= '</div>';
  $progressbar .= '</div>';
  //$progressbar .= '</div>';

  $form['progressbar'] = array(
    '#type' => 'markup',
    '#markup' => $progressbar,
  );

  $form['markup_start'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="row"><div class="col-md-3">',
  );

  $checklist = '';
  $checklist .= '<ul class="list-group checked-list-box wizard-list-group">';
  foreach ($wizard_info['steps'] as $k => $item) {
    $value = $item['title'];
    // enable checklist item links if wizard has been completed to enable people
    // to go back to a prior step.
    //if (!empty($sys_meta['wizard_complete'])) {
    if (in_array($k, $wizard_state['successes'])) {
      $value = Intel_Df::l($item['title'], Intel_Df::current_path(), array('query' => array('step' => $k)));
    }
    $tag = array(
      'tag' => 'li',
      'value' => $value,
      'attributes' => array(
        'class' => array(
          'list-group-item',
        )
      )
    );
    if (in_array($k, $wizard_successes)) {
      $tag['attributes']['class'][] = 'list-group-item-success';
      if ($wizard_step == $k) {
        $tag['attributes']['class'][] = 'active';
      }
      $tag['value'] = '<span class="icon glyphicon glyphicon-check pull-left" aria-hidden="true"></span><div class="text">' . $tag['value'] . '</div>';
      //$tag['value'] = '<span class="glyphicon glyphicon-check" aria-hidden="true"></span> ' . $tag['value'];
    }
    else {
      if ($wizard_step == $k) {
        $tag['attributes']['class'][] = 'active';
      }
      $tag['value'] = '<span class="icon glyphicon glyphicon-unchecked pull-left" aria-hidden="true"></span><div class="text">' . $tag['value'] . '</div>';
    }

    $checklist .= Intel_Df::theme_html_tag($tag);
  }
  $checklist .= '</ul>';
  $form_state['wizard_statuses'] = $statuses;
  $form_state['wizard_step'] = $wizard_step;

  $form['markup_sidebar'] = array(
    '#type' => 'markup',
    '#markup' => $checklist,
  );

  $form['markup_mid'] = array(
    '#type' => 'markup',
    '#markup' => '</div><div class="col-md-9"><div class="card">',
  );


  /*
  $form['markup_mid_header'] = array(
    '#type' => 'markup',
    '#markup' => '<h3 class="card-header mt-0" style="margin-top: 0;">' . $wizard_info[$wizard_step]['text'] . '</h3>',
  );
  */

  $form['markup_mid2'] = array(
    '#type' => 'markup',
    '#markup' => '<div class="card-block">',
  );

  $func = $form_state['wizard_info']['callback_prefix'] . '_' . $wizard_step;
  if (function_exists($func)) {
    $f = $func($form, $form_state);
    $form = Intel_Df::drupal_array_merge_deep($form, $f);
  }

  $form['markup_mid3'] = array(
    '#type' => 'markup',
    '#markup' => '</div><div class="card-footer text-right text-middle clearfix">',
  );



  if (!empty($wizard_info['steps'][$wizard_step]['action_img_src'])) {
    $form['action_img'] = array(
      '#type' => 'markup',
      '#markup' => '<img src="' . $wizard_info['steps'][$wizard_step]['action_img_src'] . '" class="pull-left">',
    );
  }

  if (!isset($wizard_info['steps'][$wizard_step]['submit_button_text']) || !empty($wizard_info['steps'][$wizard_step]['submit_button_text'])) {
    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => !empty($wizard_info['steps'][$wizard_step]['submit_button_text']) ? $wizard_info['steps'][$wizard_step]['submit_button_text'] : Intel_Df::t('Next step'),
      '#name' => 'submit',
      '#prefix' => (!empty($wizard_info['steps'][$wizard_step]['submit_button_pre_text']) ? $wizard_info['steps'][$wizard_step]['submit_button_pre_text'] : Intel_Df::t('when complete, click')) . ' &nbsp; ',
      '#attributes' => array(
        'class' => array(
          'btn',
          'btn-success',
          'text-uppercase',
        )
      ),
    );
  }

  if (!empty($_GET['step'])) {
    $form['submit_step'] = array(
      '#type' => 'submit',
      '#value' => Intel_Df::t('Submit'),
      '#name' => 'submit_step',
      '#prefix' => ' &nbsp; ',
      '#attributes' => array(
        'class' => array(
          'btn',
          'btn-info',
          'text-uppercase',
        )
      ),
    );
  }

  $form['markup_end'] = array(
    '#type' => 'markup',
    '#markup' => '</div></div></div></div></div>',
  );

  $form['wizard_step'] = array(
    '#type' => 'hidden',
    '#value' => $wizard_step,
  );


  $form_state['wizard_step'] = $wizard_step;

  return $form;
}

function intel_wizard_form_check($form, &$form_state) {
  //$func = $form_state['wizard_info']['callback_prefix'] . '_check_' . $form_state['intel_wizard_step'];
  $status = FALSE;
  $func = !empty($form_state['wizard_info']['callback_prefix']) ? $form_state['wizard_info']['callback_prefix'] : '';
  $func .= '_' . $form_state['wizard_step'] . '_check';
  if (function_exists($func)) {
    $status = $func($form, $form_state);
  }

  return $status;
}

function intel_wizard_form_validate($form, &$form_state) {
  $status = intel_wizard_form_check($form, $form_state);

  $func = !empty($form_state['wizard_info']['callback_prefix']) ? $form_state['wizard_info']['callback_prefix'] : '';
  $func .= '_' . $form_state['wizard_step'] . '_validate';
  if (function_exists($func)) {
    $f = $func($form, $form_state, $status);
  }
}

function intel_wizard_form_submit($form, &$form_state) {
  //$func = $form_state['wizard_info']['callback_prefix'] . '_submit_' . $form_state['intel_wizard_step'];
  $func = !empty($form_state['wizard_info']['callback_prefix']) ? $form_state['wizard_info']['callback_prefix'] : '';
  $func .= '_' . $form_state['wizard_step'] . '_submit';
  if (function_exists($func)) {
    $f = $func($form, $form_state);
  }

  // update wizard state
  intel_update_wizard_state($form_state['wizard_info'], $form_state['wizard_state']);

  if (!empty($_GET['step'])) {
    // determine next step
    $wizard_info = $form_state['wizard_info'];
    $next_step = 0;
    foreach ($wizard_info['steps'] as $k => $v) {
      if ($next_step) {
        $next_step = $k;
        break;
      }
      if ($k == $_GET['step']) {
        $next_step = 1;
      }
    }
    $l_options = array(
      'query' => array(
      )
    );
    $l_options2 = array(
      'query' => array(
        'step' => $next_step,
      )
    );
    $msg = Intel_Df::t('Form was submitted.');
    Intel_Df::drupal_set_message($msg, 'success');
    // if next step clicked, remove step query element
    if ($form_state['clicked_button']['#name'] == 'submit') {
      unset($_GET['step']);
    }
  }

}