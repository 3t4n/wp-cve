<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxg_googlesheets_api')){
    
class vxg_googlesheets_api extends vxg_googlesheets{
  
  public $info='' ; // info
  public $error= "";
  public $timeout=30;
  public $api_version=0;
  public $api_res='';
  public $token_url='https://www.googleapis.com/oauth2/v3/token';
  public $url='https://sheets.googleapis.com/';
  
  
function __construct($info){ 
        if(isset($info['data'])){
  $this->info= $info['data'];
      }
if(!empty(self::$api_timeout)){
    $this->timeout=self::$api_timeout;
}

}
/**
  * Get New Access Token from googlesheets
  * @param  array $form_id Form Id
  * @param  array $info (optional) Google Sheets Credentials of a form
  * @param  array $posted_form (optional) Form submitted by the user,In case of API error this form will be sent to email
  * @return array  Google Sheets API Access Informations
  */
public function get_token($info=""){
  if(!is_array($info)){
  $info=$this->info;
  }
  if(!isset($info['refresh_token']) || empty($info['refresh_token'])){
   return $info;   
  }
  $client=$this->client_info(); 
  ////////it is oauth    
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"refresh_token","refresh_token"=>$info['refresh_token']);
  $res=$this->post_google('token',$this->token_url,"post",$body);
  
  $re=json_decode($res,true); 
  if(isset($re['access_token']) && $re['access_token'] !=""){ 
  $info["access_token"]=$re['access_token'];
    $exp=(int)$this->post('expires_in',$re);
  $info['expires']=time()+$exp;
  $info["class"]='updated';
  $token=$info;
  }else{
  $info['error']=isset($re['error_description']) ? $re['error_description'] : '';
  $info['access_token']="";
   $info["class"]='error';
  $token=array(array('errorCode'=>'406','message'=>$re['error_description']));

  }
  $info["valid_api"]=current_time('timestamp')+86400; //api validity check
  $this->info=$info;
  //update googlesheets info 
  //got new token , so update it in db
  $this->update_info( array("data"=> $info),$info['id']); 
  return $info; 
  }
public function handle_code(){
      $info=$this->info;
      $id=$info['id'];

        $client=$this->client_info();
  $log_str=$res=""; $token=array();
  if(isset($_REQUEST['code'])){
  $code=$this->post('code');   
  if(!empty($code)){
$body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"authorization_code","code"=>$code);
$res=$this->post_google("token","https://www.googleapis.com/oauth2/v3/token","post",$body);
 //var_dump($res); die();
  $log_str="Getting access token from code";
   $token=json_decode($res,true);  

  }
 
  }else if(!empty($info['refresh_token'])){  
  //revoke token on user request
  $res=$this->post_google('token',"https://accounts.google.com/o/oauth2/revoke?token=".$info['refresh_token'],"get");  
  $log_str="Access token Revoked on Request";
  }
 
  if(isset($_REQUEST['error'])){
  if(isset($_REQUEST['error_description'])){
   $token['error_description']=$this->post('error_description'); 
  }else{  
   $token['error_description']=$this->post('error'); 
  }  
  }

  $info['access_token']=$this->post('access_token',$token);
  $info['client_id']=$client['client_id'];
  $info['_id']=$this->post('id',$token);
  $info['refresh_token']=$this->post('refresh_token',$token);
 
  $exp=(int)$this->post('expires_in',$token);
  $info['expires']=time()+$exp;
  $info['error']=$this->post('error_description',$token);
  if(!empty($code) && empty($info['refresh_token'])){
  $info['error']='Go to <a href="https://myaccount.google.com/permissions" target="_blank">https://myaccount.google.com/permissions</a> and select "CRM Perks" or "your custom name" app then click "Remove" button';    
}
$info['time']=current_time('timestamp');
$info["class"]='error';
if(!empty($info['access_token'])){
$info["class"]='updated';
}

  $this->info=$info;
     if(!empty($info['refresh_token'])){
     $arr=$this->post_google_arr('https://www.googleapis.com/oauth2/v2/userinfo'); //?access_token=
     if(!empty($arr['email'])){  $info['email']=$arr['email'];  }  
   }
 // $info=$this->validate_api($info);
  $this->update_info( array('data'=> $info) , $id); 
 //var_dump($info,$arr); //die();
  return $info;
}
/**
  * Posts data to googlesheets, Get New access token on expiration message from googlesheets
  * @param  string $path googlesheets path 
  * @param  string $method CURL method 
  * @param  array $body (optional) if you want to post data
  * @return array Google Sheets Response array
  */
public  function post_google_arr($path='',$method='get',$body=""){
  $info=$this->info;    
  $get_token=false; 
  $dev_key='';
  $head=array(); 
if(!empty($info['access_token'])){
  $exp=(int)$info['expires']; 
  $dev_key=$info['access_token'];
  if($exp < time()-1){
     // $token=$this->get_token();
     if(!empty($token['access_token'])){
     $dev_key=$token['access_token'];    
     }
  }  
  $google_res=$this->post_google($dev_key,$path,$method,$body,$head); 
  $google_response=json_decode($google_res,true); 
  if(isset($google_response['error']['code']) && $google_response['error']['code'] == 401){
        $token=$this->get_token();
     if(!empty($token['access_token'])){
     $dev_key=$token['access_token'];    
     }
   $google_res=$this->post_google($dev_key,$path,$method,$body,$head); 
  $google_response=json_decode($google_res,true);    
  }
  if(empty($google_response)){ $google_response=$google_res; }
    $this->api_res=$google_res; 
    
  return $google_response;   
}

}
/**
  * Posts data to googlesheets
  * @param  string $dev_key Slesforce Access Token 
  * @param  string $path Google Sheets Path 
  * @param  string $method CURL method 
  * @param  string $body (optional) if you want to post data 
  * @return string Google Sheets Response JSON
  */
public function post_google($dev_key,$path,$method,$body="",$head=''){
  
  if($dev_key == 'token'){
  $header=array('content-type'=>'application/x-www-form-urlencoded');   
  }else if(!empty($dev_key)){
  $header=array("Authorization"=>' Bearer ' . $dev_key,'content-type'=>'application/json');      
  }
    if(!empty($head) && is_array($head)){ $header=array_merge($header,$head);  }
  if(is_array($body)&& count($body)>0)
  { 
 // $body=http_build_query($body);
  }

  $response = wp_remote_post( $path, array(
  'method' => strtoupper($method),
  'timeout' => $this->timeout, //
  'headers' => $header,
  'body' => $body
  ));

  if(is_wp_error($response)){
   $body=json_encode(array('wp_error'=>$response->get_error_message()));   
  }else{
     $body=wp_remote_retrieve_body($response); 
  }
  
 // echo json_encode($header).'-----'.json_encode($response); die();
  return  $body;
}
/**
  * Get Google Sheets Client Information
  * @param  array $info (optional) Google Sheets Client Information Saved in Database
  * @return array Google Sheets Client Information
  */
public function client_info(){
      $info=$this->info;
     $client_id=$this->post('app_id',$info);     
     $client_secret=$this->post('app_secret',$info);     
     $call_back=$this->post('app_url',$info);     
      
  
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back);
  }
/**
  * Get fields from googlesheets
  * @param  string $form_id Form Id
  * @param  array $form (optional) Form Settings 
  * @param  array $request (optional) custom array or $_REQUEST 
  * @return array Google Sheets fields
  */
public function get_crm_fields($object,$tab=''){ 

$google_res=$this->post_google_arr($this->url.'v4/spreadsheets/'.$object);  // '?includeGridData=1&ranges=1:1'
//var_dump($google_res);
$sheets=$fields=array();
if(!empty($google_res['sheets'])){
    foreach($google_res['sheets'] as $v){
$title=$v['properties']['title'];
$sheets[$v['properties']['sheetId']]=trim($title);  
if(empty($tab)){ $tab=$title; }      
}
if(!in_array($tab,$sheets)){
$tab=$sheets[0];    
}
$res=$this->post_google_arr($this->url.'v4/spreadsheets/'.$object.'/values/'.urlencode("'".$tab."'!1:1")); 

if(!empty($res['values'][0])){
   foreach($res['values'][0] as $k=>$v){
       if(empty($v)){ $v='Col #'.($k+1); }
$k='field-'.$k;
 $fields[$k]=array('name'=>$k,'label'=>$v,'type'=>'Text');      
   }
return array('tabs'=>$sheets,'fields'=>$fields);      
}else{
    return esc_html__('Sheet header is empty , please add first row  in sheet as column titles ','gravity-forms-googlesheets-crm');
}
}else{
    $msg=__("No Fields Found",'gravity-forms-googlesheets-crm');
 if(isset($google_res['error']['errors'][0]['message'])){
  $msg=$google_res['error']['errors'][0]['message'];    
  }else if(isset($google_res['error']['message'])){
  $msg=$google_res['error']['message'];    
  }else{
  $msg=json_encode($google_res);    
  }
  return $msg;
}  

}

/**
  * Get Objects from googlesheets
  * @return array
  */
public function get_crm_objects($files=array(),$token=''){
/*$q='mimeType contains \'spreadsheet\''; //mimeType='application/vnd.google-apps.spreadsheet'
 if(!empty($this->info['email'])){
    $q.=' and \''.$this->info['email'].'\' in writers';
 }*/
 
// $q="mimeType contains 'spreadsheet'"; 
$q="mimeType='application/vnd.google-apps.spreadsheet'"; //does not show application/vnd.openxmlformats-officedocument.spreadsheetml.sheet because we get "operation not permitted" for these xlxx files when getting header
 if(!empty($this->info['email'])){
  //  $q.=" and '".$this->info['email']."' in writers";
 }
//$q='';
 //$sql=array('q'=>$q,'fields'=>'nextPageToken,nextLink,incompleteSearch, items(id,title,mimeType)');
  $sql=array('q'=>$q,'pageSize'=>1000,'includeItemsFromAllDrives'=>'true','supportsAllDrives'=>'true','orderBy'=>'name'); 
 if(!empty($token)){
    // $sql=array();
     $sql['pageToken']=$token;
 }
 $google_res=$this->post_google_arr('https://www.googleapis.com/drive/v3/files','get',$sql); 
 //$google_res=$this->post_google_arr('https://www.googleapis.com/drive/v2/files','get',$sql); 
 //var_dump($google_res); //die();
//echo json_encode($google_res); die();
  if(isset($google_res['files'])){
  foreach($google_res['files'] as $object){
  $files[$object['id']]=$object['name'];       
  }
  if(count($files)<3000 && !empty($google_res['nextPageToken'])){ 
     $files=$this->get_crm_objects($files,$google_res['nextPageToken']); 
  }
  return $files;
}
  $msg="No Sheet Found"; //json_encode($google_res)
  if(isset($google_res['error']['errors'][0]['message'])){
  $msg=$google_res['error']['errors'][0]['message'];    
  } 
  return $msg;
}  
/**
  * Posts object to googlesheets, Creates/Updates Object or add to object feed
  * @param  array $entry_id Needed to update googlesheets response
  * @return array Google Sheets Response and Object URL
  */
public function push_object($object,$temp_fields,$meta){  
  
    
/*    $tab='sheet two';
     $arr=array(array(null, '02-sep-2020 20:20:30','eexxx9999xzz22','zzxxxxxqqqq999qqqqqqq-uuuuuuu')); 
    $post=array('values'=>$arr,"majorDimension"=>"ROWS");
    $res=$this->post_google_arr($this->url.'v4/spreadsheets/'.$object.'/values/'.urlencode("'".$tab."'!1:1").':append?valueInputOption=USER_ENTERED','post',json_encode($post));
    var_dump($res); die();*/


  $fields_info=array(); $fields=array(); $extra=array();
  $id=""; $error=""; $action=""; $link=""; $status=""; 
    $event=$this->post('event',$meta);
  $fields_info=isset($meta['fields']['fields']) && is_array($meta['fields']['fields']) ? $meta['fields']['fields'] : array();
  foreach($fields_info as $k=>$v){

  $value=isset($temp_fields[$k]['value']) ? $temp_fields[$k]['value'] : null; //null

  if(is_array($value) && !empty($value)){
  if(isset($value[0]) && is_array($value[0])){
    foreach($value as $kk=>$vv){
     if(is_array($vv)){ $vv=trim(implode(' - ',$vv)); }
        $value[$kk]=$vv;   
    }  
  }
   $value=trim(implode(', ',$value)); }

   $fields[]=$value;   
  }

  if(!empty($meta['tab'])){
 
  //create new lead
  $type= !empty($meta['data_type']) ? $meta['data_type'] : 'USER_ENTERED';
  $row_type= !empty($meta['row_type']) ? $meta['row_type'] : 'OVERWRITE';
// first cell of header row should be not empty  , other it will continue overwrting first row only
$id= isset($meta['crm_id']) ? $meta['crm_id'] : ''; $google_res=array();
if($id == ''){
     $action="Added";  $status="1";
       $path=$this->url.'v4/spreadsheets/'.$object.'/values/'.urlencode("'".$meta['tab']."'!A2:A").':append?valueInputOption='.$type.'&insertDataOption='.$row_type; 
       //insertDataOption=INSERT_ROWS
       $post=array('values'=>array($fields),"majorDimension"=>"ROWS");
       $google_res=$this->post_google_arr($path,"post",json_encode($post));
}else if(!empty($id) && is_numeric($id)){
 
  // $id_arr=explode(':',$id);
  //  $last_index=preg_replace('/[^0-9]/', '', $id_arr[count($id_arr)-1]);
  //  unset($id_arr[count($id_arr)-1]);
  //  $tab=implode($id_arr).':'.$last_index;
if(in_array($event,array('delete'))){
    $sheet_id='';
if(!empty($meta['fields']['tabs'])){
    $sheet_id=array_search($meta['tab'],$meta['fields']['tabs']);
}
if(!empty($sheet_id)){
     $method="delete";
     $action="Deleted";
  $status="5";  
 // $post=array('requests'=>array(array('deleteDimension'=>array('range'=>array('sheetId'=>$sheet_id,'dimension'=>'ROWS','startIndex'=>intval($id)-1,'endIndex'=>intval($id)))))); $path=$this->url.'v4/spreadsheets/'.$object.':batchUpdate';
 $path=$this->url.'v4/spreadsheets/'.$object.'/values/'.urlencode("'".$meta['tab']."'!A".$id.":ZZZ".$id).':clear'; 
  $google_res=$this->post_google_arr($path,"post",'');
}
}
else{
    //update object
$status="2"; $action="Updated";
$method='put';  $end='Z'; if(count($fields)> 26){ $end='ZZZ'; }
 $path=$this->url.'v4/spreadsheets/'.$object.'/values/'.urlencode("'".$meta['tab']."'!A".$id.":".$end.$id).'?valueInputOption='.$type; 
 $post=array('values'=>array($fields),"majorDimension"=>"ROWS");
$google_res=$this->post_google_arr($path,"put",json_encode($post));
}
}
 ////insertDataOption=INSERT_ROWS
  //insertDataOption=INSERT_ROWS
  // $post=array(); 
//var_dump($meta['tab'],$google_res,$fields,$path,$post); die();
if(is_array($google_res)){
if(isset($google_res['spreadsheetId'])){
    if(!empty($google_res['updates']['updatedRange'])){
      $id_range=$google_res['updates']['updatedRange'];   
      $id_arr=explode(':',$id_range);
    $id=preg_replace('/[^0-9]/', '', $id_arr[count($id_arr)-1]);
    }
$link='https://docs.google.com/spreadsheets/d/'.$google_res['spreadsheetId'];
  
}else if(isset($google_res['error']['message'])){
   $error=$google_res['error']['message'];   
}else{
 $error=json_encode($google_res);   
} 
}else{
 $error=$google_res;  
} 
  }
  return array("error"=>substr($error,0,240),"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$google_res,"extra"=>$extra);
} 
 
 
}
}
?>