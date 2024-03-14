<?php 

 function wp3cxw_send_api_request($method, $token, $url, $params='') {
  $reply = array('result'=>false,'error'=>'Request Failed','errorcode'=>'');
  $args = array(
    'timeout' => '20',
    'redirection' => '5',
    'httpversion' => '1.1',
    'blocking' => true,
    'headers' => array(
      '3CX-ApiKey' => $token,
      'Content-Type' => 'application/json',
    ),
    'cookies' => array(),
    'body' => ''
  );		

  if ($method=='get') {
    $response = wp_remote_get($url, $args );		
  } else if ($method=='post') {
    $args['body']=json_encode($params);
    $response = wp_remote_post($url, $args );		 
  } else {
    return;
  }

  $reply['errorcode'] = wp_remote_retrieve_response_code( $response );
  //error_log("Sending API request ".$url.' '.print_r($params,true));
  $body = wp_remote_retrieve_body( $response );
  $res = @json_decode($body, true);
  if ($reply['errorcode']==200){
    if (!empty($res) && is_array($res) && $res['status']=='success') {
      $reply=$res;
      $reply['errorcode']=200;
      $reply['error']=$res['errorInformation'];
      if (!isset($reply['result'])){
        $reply['result']=true;
      }
      unset($reply['errorInformation']);
    }
  }
  else 
  {
    if ($res && $res['errorInformation']) {
      $reply['error']=$res['errorInformation'];
    }
    else 
    {
      if (!empty($reply['errorcode'])){
        $reply['error'] = $reply['errorcode'].' '.wp_remote_retrieve_response_message($response);
      }
      else 
      {
        $reply['error']= $response->get_error_messages();
      }
    }
  }
  return $reply;
 }

  function wp3cxw_GetWebinarTransient($id) {
    $form = wp3cxw_webinar_form($id);
    $properties = $form->get_properties();
    $config = $properties['config'];
    $reply = get_transient('tcxwm_webinar_data_'.$id);
    if ($reply===false) {
      $reply=array('result'=>false, 'error'=>'Request Failed');
      $url = $config['portalfqdn'].'/webmeeting/api/v1/meetings?isWebinar=true&isScheduled=false'; // U1 API COMPAT
      if ($config['extension']!=''){
        $url.='&extension='.$config['extension'];
      }
      if (!empty($config['subject'])) {
        $url.='&subjectContains='.$config['subject'];
      }
      if (!empty($config['days'])) {
        $url.='&daysLimit='.$config['days'];
      }
      $res = wp3cxw_send_api_request('get', $config['apitoken'], $url);

      switch($res['errorcode']){
        case 200:
          if ($res['status']!='success' || empty($res['result'])) {
            $reply['error'] = 'Invalid reply';
          } else {
            $meetings = $res['result']['scheduledMeetings'];
            $reply['meetings'] = array();
            $reply['result']=true;
            $reply['error']='';
            // filter out already expired webinars
            $tz = new DateTimeZone('UTC');
            $now = new DateTime('now', $tz);
            foreach($meetings as $v) {
              $dt = new DateTime($v['datetime'].':00Z', $tz); // server returns UTC date
              $dt = $dt->add(new DateInterval('PT'.$v['duration'].'M'));
              if ($dt>$now){
                $reply['meetings'][]=$v;
              }
            }
          }
          break;
        case 404:
          $reply['meetings']=array();
          $reply['result']=true;
          $reply['error']='';
          break;
        default:
          $reply['error']=wp3cxw_flat_join($res['error']);
          break;
      }

      if (!$reply['result']) {
        error_log('3CX WebMeeting API error: '.$reply['error']);
      }
      else 
      {
        set_transient('tcxwm_webinar_data_'.$id, $reply, $config['cache_expiry']*60);
      }
    }
    return $reply;
  }

  function wp3cxw_subscribe_request(WP_REST_Request $request) {
    $body = $request->get_body_params();
    $status = 403;
    $reply=array(
      'result'=>false,
      'error'=>'Invalid Request'
    );
    $id=false;
    $name=false;
    $email=false;

    // check and sanitize params
    if ($body) {
      if ($body['id'] && intval($body['id']>0)) {
        $id = intval($body['id']);
      }
      if ($body['name']){
        $name=wp3cxw_sanitize_query_var($body['name']);
      }
      if ($body['email'] && is_email($body['email'])) {
        $email=$body['email'];
      }
    }

    if ($id && $name && $email) {
      $webinar=false;
      $form = wp3cxw_webinar_form($id);
      if ($form) {
        $properties = $form->get_properties();
        $config = $properties['config'];
        $res = wp3cxw_GetWebinarTransient($id);
        if ($res && $res['meetings']) {
          foreach($res['meetings'] as $meet) {
            if (md5($id.$meet['meetingid'])==$body['subscribe']) {
              $webinar=$meet;
              break;
            }
          }
        }
      }
      if ($webinar) {
        $parts=array();
        $parts[]=array(
          'name' => $body['name'],
          'email' => $body['email']
        );
        // send subscribe API request to PBX
        $maxpart='';
        if ($config['maxparticipants']>0) {
          $maxpart = '?maxparticipants='.$config['maxparticipants'];
        }
        $url = $config['portalfqdn'].'/webmeeting/api/v1/participants/'.$meet['meetingid'].$maxpart;
        $res = wp3cxw_send_api_request('post', $config['apitoken'], $url, $parts);
        if ($res['result']) {
          $reply['result']=true;
          $reply['error']='';
          $status=200;
        }
        else 
        {
          $reply['error']=$res['error'];
        }
      }
      else 
      {
        $reply['error']="Webinar Not Found";
        $status=404;
      }
    }
    $response = new WP_REST_Response($reply);
    $response->set_status($status);
    return $response;
  }

  function wp3cxw_delete_cache($id) {
    delete_transient('tcxwm_webinar_data_'.$id);
  }
