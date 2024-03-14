<?php

function process_login($url, $parameters) {  

  $userdata = send_get_request($url, $parameters);                        //Get user data from Membee server

  if ($userdata) {            

    $userdata = json_decode($userdata); 

    if ($userdata->UserID) {

      $user_id = prepare_user_sign_in($userdata);

      if ($user_id) {

        sign_user_in($user_id, $userdata->UserID);

      } else {

        return false;

      }  

    } else {

      membee_error_message('Please make sure that Client ID, Application ID and Secret you entered are correct.');

    }    

  } else {    

    return false;

  }

}



function prepare_user_sign_in($userdata) {  

  $user = get_user_by('email', $userdata->Email);
  if ($user) {
    $user_id = $user->ID;
  } else {
    $user_id = username_exists($userdata->UserID);
  }

  if (!$user_id) {    

    $user_id = create_new_user($userdata->UserID, $userdata->Email);

  }  

  if ($user_id) {

    update_user_meta( $user_id, 'last_name', $userdata->LastName);        //updating user details according to received data

    update_user_meta( $user_id, 'first_name', $userdata->FirstName);

    update_user_meta( $user_id, 'nickname', $userdata->FirstName);

    update_user_roles($userdata, $user_id);

  }  

  return $user_id;    

}



function membee_error_message($error_message) {                           //Handling error messages

  global $membee_error_message;

  $membee_error_message .= $error_message.'<br />';  

}



function allow_redirects($allowed) {                                      //allow redirects to membee server

  $allowed[] = 'memberservices.membee.com';

  return $allowed;

}

?>