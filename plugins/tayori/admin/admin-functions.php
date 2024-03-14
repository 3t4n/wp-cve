<?php

function tayori_form_validate($data)
{
  if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $status = true;
    if (array_key_exists('form_setup_form', $data)) {
      $keys = array(
        'button_title',
        'button_type',
        'pop_button_type',
        'button_color',
        'button_position_pc',
        'button_position_sp',
        'button_font_color',
        'form_type_pc',
        'form_type_sp',
        'button_icon_transparent_type',
      );

      // check keys.
      $result = array();
      foreach ($keys as $key => $value) {
        if (!array_key_exists($value, $data['form_setup_form'])) {
          $status = false;
        }
        $result[$value] = $data['form_setup_form'][$value];
      }

      $message = array();
      // check data.
      foreach ($keys as $key => $value) {
        if ($value === 'button_title') {
          if (strlen($result[$value]) > 50) {
            $message['button_title'] = __('Error Message Button Title Length', 'tayori');
          }
        }
        if ($value === 'button_type') {
          if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 4)) {
            $message['button_type'] = __('Error Message Button Type Select', 'tayori');
          }
        }
        if ($value === 'pop_button_type') {
          if ($result['button_type'] == 2) {
            if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 10)) {
              $message['pop_button_type'] = __('Error Message Button Type Select', 'tayori');
            }
          }
        }
        if ($value === 'button_color') {
          if (mb_strlen($result[$value]) != 7) {
            $message['button_color'] = __('Error Message Button Color Select', 'tayori');
          }
        }
        if ($value === 'button_font_color') {
          if (mb_strlen($result[$value]) != 7) {
            $message['button_font_color'] = __('Error Message Button Font Color Select', 'tayori');
          }
        }
        if ($value === 'form_type_pc') {
          if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 2)) {
            $message['form_type_pc'] = __('Error Message Form Type Pc Select', 'tayori');
          }
        }
        if ($value === 'form_type_sp') {
          if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 2)) {
            $message['form_type_sp'] = __('Error Message Form Type Sp Select', 'tayori');
          }
        }
        if ($value === 'button_position_pc') {
          if ($data['button_type'] === 3) {
            if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 4)) {
              $message['button_position_pc'] = __('Error Message Button Position Pc Select', 'tayori');
            }
          }
          else {
            if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 2)) {
              $message['button_position_pc'] = __('Error Message Button Position Pc Select', 'tayori');
            }
          }
        }
        if ($value === 'button_position_sp') {
          if ($data['button_type'] === 3) {
            if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 3)) {
              $message['button_position_sp'] = __('Error Message Button Position Sp Select', 'tayori');
            }
          }
          else {
            if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 2)) {
              $message['button_position_sp'] = __('Error Message Button Position Sp Select', 'tayori');
            }
          }
        }
        if ($value === 'button_icon_transparent_type') {
          if (!is_numeric($result[$value]) && ($result[$value] < 1 || $result[$value] > 3)) {
            $message['button_icon_transparent_type'] = __('Error Message Button Icon Transparent Type Select', 'tayori');
          }
        }
      }
      if (count($message) > 0) {
        $status = false;
      }
    }
  }
  return array('data' => $result, 'status' => $status, 'message' => $message);
}

function tayori_mail_validate($data)
{
  if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $status = true;
    if (array_key_exists('form_setup_form', $data)) {
      $keys = array(
        'mail',
      );

      // check keys.
      $result = array();
      foreach ($keys as $key => $value) {
        if (!array_key_exists($value, $data['form_setup_form'])) {
          $status = false;
        }
        $result[$value] = $data['form_setup_form'][$value];
      }

      $message = array();
      // check data.
      foreach ($keys as $key => $value) {
        if ($value === 'mail') {
          if (!tayori_is_valid_email($result[$value])) {
            $message['mail'] = __('Error Message Mail Pattern', 'tayori');
          }
        }
      }
      if (count($message) > 0) {
        $status = false;
      }
    }
  }
  return array('data' => $result, 'status' => $status, 'message' => $message);
}

function tayori_is_valid_email($email) {
  return filter_var($email, FILTER_VALIDATE_EMAIL) && !preg_match('/@\[[^\]]++\]\z/', $email);
}
