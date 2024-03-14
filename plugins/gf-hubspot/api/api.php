<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxg_hubspot_api')){
    
class vxg_hubspot_api extends vxg_hubspot{
  
  public $info='' ; // info
  public $error= "";
  public $timeout=30;
  public $url='https://api.hubapi.com/';
  
  function __construct($info) { 
      if(isset($info['data']) && is_array($info['data'])){
  $this->info= $info['data'];
  if(!isset($this->info['portal_id'])){
      $this->info['portal_id']='';
  }
  if(!empty($info['meta']['portal_id'])){
  // $this->info['portal_id']=$info['meta']['portal_id'];   
  }
      }
if(!empty(self::$api_timeout)){
$this->timeout=self::$api_timeout;
}

}
public function get_token(){
$info=$this->info;
//$users=$this->get_users();
$ac=$this->get_account();
 if(!empty($ac['portalId'])){
      $info['portal_id']=$ac['portalId'];
      $info['time_zone']=$ac['timeZone'];
      $info['currency']=$ac['currency'];
      $info['valid_token'] ='true';
  }else{
     unset($info['access_token']); 
       if(is_string($ac)){
   $info['error']=$ac;  
   
    }  
  }

$info['_time']=time(); 
return $info;
}

  public function refresh_token($info=""){
  if(!is_array($info)){
  $info=$this->info;
  }
  if(!isset($info['refresh_token']) || empty($info['refresh_token'])){
   return $info;   
  }
  $client=$this->client_info(); 
  ////////it is oauth    
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"refresh_token","refresh_token"=>$info['refresh_token']);
  $res=$this->post_hubspot('api','',$this->url.'oauth/v1/token',"post",$body);

  $re=json_decode($res,true); 
  if(isset($re['access_token']) && $re['access_token'] !=""){ 
  $info["access_token"]=$re['access_token'];
 // $info["org_id"]=$re['id'];
  $info["class"]='updated';
  $token=$info;
  }else{
  $info['error']=$re['error_description'];
  $info['access_token']="";
   $info["class"]='error';
  $token=array(array('errorCode'=>'406','message'=>$re['error_description']));

  unset($info['valid_token']); 
  }
  $info["token_time"]=time(); //api validity check
  //update hubspot info 
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
  $res=$this->post_hubspot('api','',$this->url.'oauth/v1/token',"post",$body);

  $log_str="Getting access token from code";
   $token=json_decode($res,true); 
   if(!isset($token['access_token'])){
      $log_str.=" =".$res; 
   }
  }
  if(isset($_REQUEST['error'])){
   $token['error_description']=$this->post('error_description');   
  }
  }else{  
  //revoke token on user request
  if(isset($info['instance_url']) && $info['instance_url']!="")
  $res=$this->post_hubspot('api','',$this->url.'/oauth/v1/refresh-tokens/'.$info['refresh_token'],'delete');  
  $log_str="Access token Revoked on Request";
  }

  $info['portal_id']='';
//var_dump($token); die();
  $info['instance_url']=$this->post('instance_url',$token);
  $info['access_token']=$this->post('access_token',$token);
  $info['client_id']=$client['client_id'];
  $info['_id']=$this->post('id',$token);
  $info['refresh_token']=$this->post('refresh_token',$token);
 // $info['issued_at']=round($this->post('issued_at',$token)/1000);
  $info['signature']=$this->post('signature',$token);
  $info['token_time']=time();
  $info['_time']=time();
  $info['error']=$this->post('message',$token);
  $info['api']="api";
  $info["class"]='error';
  $info['valid_token'] ='';
  if(!empty($info['access_token'])){
  $info["class"]='updated';
  $info['valid_token'] ='true';
  $this->info=$info;
  $ac=$this->get_account();
  if(!empty($ac['portalId'])){
      $info['portal_id']=$ac['portalId'];
      $info['time_zone']=$ac['timeZone'];
      $info['currency']=$ac['currency'];
  }
  $this->info=$info;
  }
 
 // $info=$this->validate_api($info);
  $this->update_info( array('data'=> $info) , $id);
  return $info;
  }
public function get_account(){
    $url='integrations/v1/me';
return $this->post_hubspot_arr($url);
}  
  /**
  * Posts data to hubspot, Get New access token on expiration message from hubspot
  * @param  string $path hubspot path 
  * @param  string $method CURL method 
  * @param  array $body (optional) if you want to post data
  * @return array HubSpot Response array
  */
  public  function post_hubspot_arr($path,$method='get',$body=""){
  $info=$this->info;  
  $get_token=false; 
  $api=$this->post('api',$info);
  $dev_key='';
  
  if($api == 'web'){
    $dev_key=$this->post('api_key',$info);  
  }else{
    
   $token_time=(int)$this->post('token_time',$info);
   $time=time();
   $expiry=$token_time+1797;   //21600 
   if($expiry<$time){
   $info=$this->refresh_token(); 
    
   }
      if(!empty($info['access_token'])){
  $dev_key=$info['access_token'];      
    }   
  }
  if(strpos($path,'https://') === false){
  $path=$this->url.$path;
  }
 
  $hubspot_res=$this->post_hubspot($api,$dev_key,$path,$method,$body); //var_dump($info); die();
  //var_dump($hubspot_res,$path); die();
  $hubspot_response=json_decode($hubspot_res,true); 
  if($api != 'web' && isset($hubspot_response['status']) && $hubspot_response['status'] == 'error' && isset($hubspot_response['category']) && $hubspot_response['category'] == 'EXPIRED_AUTHENTICATION'){
    $info=$this->refresh_token();  
     if(!empty($info['access_token'])){
  $dev_key=$info['access_token'];      
    }
    $hubspot_res=$this->post_hubspot($api,$dev_key,$path,$method,$body); 
  $hubspot_response=json_decode($hubspot_res,true);    
  }///var_dump($hubspot_response); die();
  if(!is_array($hubspot_response)){
      $hubspot_response=wp_strip_all_tags($hubspot_response);
  }
  if(isset($hubspot_response['status']) && $hubspot_response['status'] == 'error' && !empty($hubspot_response['message']) && strpos($hubspot_response['message'],'expired') !== false){ 
  $get_token=true;         
  }


  return $hubspot_response;   
  }
  /**
  * Posts data to hubspot
  * @param  string $dev_key Slesforce Access Token 
  * @param  string $path HubSpot Path 
  * @param  string $method CURL method 
  * @param  string $body (optional) if you want to post data 
  * @return string HubSpot Response JSON
  */
  public function post_hubspot($type,$dev_key,$path,$method,$body=""){

  $header=array(); $pars=array();
  if(is_array($body) && isset($body['grant_type'])){ //getting access token
  $header=array('content-type'=>'application/x-www-form-urlencoded');  
  $body=http_build_query($body); 
  }else{
    if($type == 'web'){
        $pars['hapikey']=$dev_key;  
    }else{  
  $header['Authorization']=' Bearer ' . $dev_key;     
    }

      if($method != "get"){
          if(is_array($body)){
          $body=json_encode($body); }
  $header['content-length']= strlen($body);
  $header['content-type']='application/json';
  }else{
      
    if(is_array($body) && count($body)>0){
    $pars=array_merge($pars,$body);    
    } 
  }
      if(count($pars)>0){
          $mark=strpos($path,'?') === false ? '?' : '&';
  $path.=$mark.http_build_query($pars);      
    }  
    }

$args=array(
  'method' => strtoupper($method),
  'timeout' => $this->timeout,
  'headers' => $header,
//  'body' => $body
  );
if(!empty($body)){
    $args['body']= $body;
}
  $response = wp_remote_post( $path, $args);   //var_dump($response,$path);
 
  if(is_wp_error($response)){
      $error=$response->get_error_message();
   $body=json_encode(array('error'=>$error));   
  }else if(isset($response['body'])){
   $body=$response['body'];    
  }
  $code=wp_remote_retrieve_response_code($response);
    if($code == 204){ $body='{"code":"204","msg":"No Content"}'; }
  if($code == 404){ $body='{"code":"404","msg":"Not Found"}'; }
  if(empty($body) && isset($response['response']) && is_array($response['response'])){
   $body=json_encode($response['response']);   
  }

 
  return $body; 
  }
  /**
  * Get HubSpot Client Information
  * @param  array $info (optional) HubSpot Client Information Saved in Database
  * @return array HubSpot Client Information
  */
  public function client_info(){
      $info=$this->info;
  $client_id='66c8b02a-06d8-47f1-b7c9-a0e0b1b28384';
  $client_secret='3f9b1144-21e4-47a1-bb57-4f25be001727';
  $call_back="https://www.crmperks.com/sf_auth/";
  //custom app
  if(is_array($info)){
      if($this->post('custom_app',$info) == "yes" && $this->post('app_id',$info) !="" && $this->post('app_secret',$info) !="" && $this->post('app_url',$info) !=""){
     $client_id=$this->post('app_id',$info);     
     $client_secret=$this->post('app_secret',$info);     
     $call_back=$this->post('app_url',$info);     
      }
  }
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back);
  }
  public function get_forms(){
 $url='forms/v2/forms';

return $this->post_hubspot_arr($url,'get');   
   
  } 
public function get_form_fields($object){
        
}  
  /**
  * Get fields from hubspot
  * @param  string $form_id Form Id
  * @param  array $form (optional) Form Settings 
  * @param  array $request (optional) custom array or $_REQUEST 
  * @return array HubSpot fields
  */
  public function get_crm_fields($object){ 

      $h_fields='["company_size","date_of_birth","degree","field_of_study","gender","graduation_date","hs_content_membership_notes","hs_content_membership_status","hs_facebook_ad_clicked","hs_facebookid","hs_google_click_id","hs_googleplusid","hs_lead_status","hs_legal_basis","hs_linkedinid","hs_twitterid","job_function","marital_status","military_status","relationship_status","school","seniority","start_date","work_email","firstname","twitterhandle","followercount","lastname","salutation","twitterprofilephoto","email","hs_persona","fax","address","hubspot_owner_id","city","linkedinbio","twitterbio","state","hs_analytics_source","zip","country","linkedinconnections","hs_language","kloutscoregeneral","jobtitle","photo","message","closedate","lifecyclestage","company","website","numemployees","annualrevenue","industry","associatedcompanyid"]'; 
      //,"mobilephone","phone","fax"
$free_fields=json_decode($h_fields,true);
if(strpos($object,'vxf_') !== false){
         $form_id=substr($object,4);
         $fields=array();
       $url='forms/v2/forms/'.$form_id;
$res=$this->post_hubspot_arr($url,'get');  

if(!empty($res['formFieldGroups'])){
    $form_fields=array();
    foreach($res['formFieldGroups'] as $group){
            if(!empty($group['fields'])){
        foreach($group['fields'] as $v){
      $form_fields[]=$v;
      if(!empty($v['dependentFieldFilters'])){ 
        foreach($v['dependentFieldFilters'] as $vv){
            if(!empty($vv['dependentFormField'])){
          $form_fields[]=$vv['dependentFormField'];      
            }
        }
    }
        }}
    }
    foreach($form_fields as $v){ 
        $field_arr=array('name'=>$v['name'],'label'=>$v['label'],'type'=>$v['fieldType']);
        $field_arr['req']= $v['required'] === true ? 'true' : '';
          if(!empty($v['options'])){
         $ops=$eg=array();
      foreach($v['options'] as $op){
      $ops[]=array('label'=>$op['label'],'value'=>$op['value']);
      $eg[]=$op['value'].'='.$op['label'];
      }
      if(!empty($ops)){
   $field_arr['options']=$ops;  
  $field_arr['eg']=implode(', ', array_slice($eg,0,20));
      }   
  }
  $field_arr['object']=$v['propertyObjectType'];
  if(!in_array($v['name'],$free_fields)){
      $field_arr['is_custom']='true';
  }

  $fields[$v['name']]=$field_arr;    
    
    }
 $fields['vx_consent']=array('name'=>'vx_consent','label'=>'GDPR Consent','type'=>'boolean');   

  $res_def=$this->post_hubspot_arr('email/public/v1/subscriptions','get');   
if(!empty($res_def['subscriptionDefinitions'])){
   foreach($res_def['subscriptionDefinitions'] as $v){
   $fields['vxoptin_'.$v['id']]=array('name'=>'vxoptin_'.$v['id'],'label'=>$v['name'],'type'=>'text');      
   } 
} 

 $fields['vx_url']=array('name'=>'vx_url','label'=>'Page URL','type'=>'URL');   
 $fields['vx_title']=array('name'=>'vx_title','label'=>'Page Title','type'=>'text');   
 $fields['vx_camp_id']=array('name'=>'vx_camp_id','label'=>'SFDC Campaign ID','type'=>'text');   
 $fields['vx_webinar_key']=array('name'=>'vx_webinar_key','label'=>'GoToWebinar key','type'=>'text');   
 $fields['vx_ip']=array('name'=>'vx_ip','label'=>'IP Address','type'=>'text');   
 $fields['skipValidation']=array('name'=>'skipValidation','label'=>'Skip Validation','type'=>'boolean','eg'=>'0 or 1','options'=>array(array('label'=>'True','value'=>'1'),array('label'=>'False','value'=>'0')));   
}else{
    $fields=json_encode($res);
}

//var_dump($fields,$free_fields);
return $fields;     
}
      
  if($object == 'Task'){
    return array('name'=>array('name'=>'name','label'=>'Title','type'=>'Text'),'description'=>array('name'=>'description','label'=>'Note','type'=>'Text','req'=>'true'),'timestamp'=>array('name'=>'timestamp', 'label'=>'Due Date','type'=>'Datetime'));  
  }
        $module='contacts'; $v='v1';  
      if($object == 'Company'){
          $module='companies'; 
      }else if($object == 'Ticket'){
          $module='tickets'; $v='v2';
      }else if($object == 'Deal'){
         $module='deals'; 
      }
      $path='properties/'.$v.'/'.$module.'/properties';
    //  $module='tickets';
$hubspot_response=$this->post_hubspot_arr($path);  
//var_dump($hubspot_response);
  $field_info='No Fields Found';
  if( !empty($hubspot_response['message'])){
   $field_info=$hubspot_response['message'];   
  }else if(isset($hubspot_response[0]) && is_array($hubspot_response[0])){
  $field_info=array();
  foreach($hubspot_response as $k=>$field){

  if(isset($field['readOnlyValue']) && $field['readOnlyValue'] === false ){
  $required=""; 
  if(in_array($field['name'],array('email','name','dealname'))){
  $required="true";   
  } 
  if($object == 'Ticket' && in_array($field['name'],array('subject','content'))){
   $required="true";   
  }
  $type=$field['fieldType'];
  if(isset($field['type']) && in_array($field['fieldType'],array('datetime'))  ){ // 
    $type=$field['type'];  
  }
  $field_arr=array('name'=>$field['name'],"type"=>$type);
  $field_arr['label']=$field['label']; 
  $field_arr['req']=$required;
  if(!empty($field['options'])){
      $ops=$eg=array();
      foreach($field['options'] as $op){
      $ops[]=array('label'=>$op['label'],'value'=>$op['value']);
      $eg[]=$op['value'].'='.$op['label'];
      }
      if(!empty($ops)){
   $field_arr['options']=$ops;  
  $field_arr['eg']=implode(', ', array_slice($eg,0,20));
      }  
  }
  if(! (isset($field['hubspotDefined']) && $field['hubspotDefined'] == true) ){
   $field_arr['is_custom']='true';   
  }
  $field_info[$field['name']]=$field_arr;  
  }    
  }
  if(in_array($module,array('contacts','companies'))){
   $field_info['id']=array('name'=>'id','type'=>'number','label'=>'ID (Do not map this field)','is_custom'=>'true','req'=>'');    
  }
  return $field_info;
  }else{
   return json_encode($hubspot_response);   
  }

  }
    
  /**
  * Get campaigns from hubspot
  * @return array HubSpot campaigns
  */
  public function get_lists(){ 
    $hubspot_response=$this->post_hubspot_arr('contacts/v1/lists/static?count=500');
 // var_dump($hubspot_response);
  ///seprating fields
  $field_info=__('No List Found','gravity-forms-hubspot-crm');
  if(isset($hubspot_response['lists']) && is_array($hubspot_response['lists'])){
  $field_info=array();
  foreach($hubspot_response['lists'] as $k=>$field){
      if($field['dynamic'] === false){
  $field_info[$field['listId']]=$field['name'];
     }     
  }
  }
    if(isset($hubspot_response['message'])){
   $field_info=$hubspot_response['message'];   
  }
  return $field_info;
}
public function get_pipes($object='tickets'){ 
$hubspot_response=$this->post_hubspot_arr('crm-pipelines/v1/pipelines/'.$object);

  ///seprating fields
  $field_info=__('No List Found','gravity-forms-hubspot-crm');
  if(isset($hubspot_response['results']) && is_array($hubspot_response['results'])){
  $field_info=array();
  foreach($hubspot_response['results'] as $k=>$field){
      if(!empty($field['stages']) && $field['active'] === true){
if(!empty($field['stages'])){
foreach($field['stages'] as $stage){
  if(isset($stage['stageId'])){
  $field_info[$field['pipelineId'].'-v_xx-'.$stage['stageId']]=$field['label'].' - '.$stage['label'];
      }   
}
}  }
  }
  }
    if(isset($hubspot_response['message'])){
   $field_info=$hubspot_response['message'];   
  }
  return $field_info;
}


  public function get_flows(){ 
  $hubspot_response=$this->post_hubspot_arr('automation/v3/workflows');  
 // var_dump($hubspot_response); die();
  ///seprating fields
  $field_info=__('No Work Flow Found','gravity-forms-hubspot-crm');
  if(isset($hubspot_response['workflows']) && is_array($hubspot_response['workflows'])){
  $field_info=array();
  foreach($hubspot_response['workflows'] as $k=>$field){
      if($field['enabled'] === true){
  $field_info[$field['id']]=$field['name'];}     
  }
  }
    if(isset($hubspot_response['message'])){
   $field_info=$hubspot_response['message'];   
  }
  return $field_info;
}
  /**
  * Get users from hubspot
  * @return array HubSpot users
  */
  public function get_users(){ 

  $hubspot_response=$this->post_hubspot_arr('owners/v2/owners');

  ///seprating fields
  $field_info=__('No Users Found');
    if(isset($hubspot_response['status']) && $hubspot_response['status'] == 'error' && !empty($hubspot_response['message'])){
   $field_info=$hubspot_response['message'];   
  } else if( isset($hubspot_response) && is_array($hubspot_response)){
  $field_info=array();
  foreach($hubspot_response as $k=>$field){
  $field_info[$field['ownerId']]=$field['firstName'].' '.$field['lastName'].' ( '.$field['email'].' )';     
  }
  }

  return $field_info;
}

  
  /**
  * Posts object to hubspot, Creates/Updates Object or add to object feed
  * @param  array $entry_id Needed to update hubspot response
  * @return array HubSpot Response and Object URL
  */
public function push_object($object,$fields,$meta){ 

   $ass=array("fromObjectId"=>'551',"toObjectId"=>'3528102',"category"=> "HUBSPOT_DEFINED","definitionId"=>15);
   $assoc=array("fromObjectId"=>'150264022',"toObjectId"=>'801665692',"category"=> "HUBSPOT_DEFINED","definitionId"=>20);
   
   $ass=array(array('name'=>'dealname','value'=>'First Deal from API'),array('name'=>'amount','value'=>600)); //150264019
   $post=array('properties'=>$ass);
 $product_post=array(array('name'=>'name','value'=>'Firstxx Product from API'),array('name'=>'price','value'=>270),array('name'=>'description','value'=>'<b>product description is here</b>'));
  
   $item_post=array(array('name'=>'name','value'=>'First Product from API'),array('name'=>'price','value'=>200),array('name'=>'quantity','value'=>20),array('name'=>'hs_product_id','value'=>'28874673'));
  
    $deal=array(array('externalPropertyName'=>'name','hubspotPropertyName'=>'dealname','dataType'=>'STRING'),array('externalPropertyName'=>'price','hubspotPropertyName'=>'amount','dataType'=>'STRING'),array('externalPropertyName'=>'dealstage','hubspotPropertyName'=>'dealstage','dataType'=>'STRING'));
     
   $product=array(array('externalPropertyName'=>'name','hubspotPropertyName'=>'name','dataType'=>'STRING'),array('externalPropertyName'=>'price','hubspotPropertyName'=>'price','dataType'=>'STRING'),array('externalPropertyName'=>'description','hubspotPropertyName'=>'description','dataType'=>'STRING'));
   $json='[
        {
          "externalPropertyName": "tax",
          "hubspotPropertyName": "tax",
          "dataType": "NUMBER"
        },
        {
          "externalPropertyName": "qty",
          "hubspotPropertyName": "quantity",
          "dataType": "NUMBER"
        },
        {
          "externalPropertyName": "price",
          "hubspotPropertyName": "price",
          "dataType": "NUMBER"
        },
        {
          "externalPropertyName": "discount",
          "hubspotPropertyName": "discount",
          "dataType": "NUMBER"
        }
      ]';
    $item=json_decode($json,1);  
     $json='[
        {
          "externalPropertyName": "firstname",
          "hubspotPropertyName": "firstname",
          "dataType": "STRING"
        },
        {
          "externalPropertyName": "familyname",
          "hubspotPropertyName": "lastname",
          "dataType": "STRING"
        },
        {
          "externalPropertyName": "customer_email",
          "hubspotPropertyName": "email",
          "dataType": "STRING"
        },
        {
          "externalPropertyName": "phone_number",
          "hubspotPropertyName": "mobilephone",
          "dataType": "STRING"
        }
      ]';
    $con=json_decode($json,1);
 $post=array('enabled'=>true,'mappings'=>array('DEAL'=>array('properties'=>$deal),'CONTACT'=>array('properties'=>$con),'PRODUCT'=>array('properties'=>$product),'LINE_ITEM'=>array('properties'=>$item)));   
 
 $ass='{
  "id": "vx-store",
  "label": "VX Store",
  "adminUri": "http://localhost/wp12"
}';
 

 $ass='{
  "storeId": "vx-store",
  "objectType": "PRODUCT",
  "messages": [
    {
      "action": "UPSERT",
      "changedAt": "1553486400000",
      "externalObjectId": "123",
      "properties": {
        "name": "Jeff aaaxx",
        "price": "562",
        "description": "Jeff aaaxx description is here"
      },
      "associations": {
        "DEAL": [
          "123"
        ]
      }
    }
  ]
}';

  $ass='{
  "storeId": "vx-store",
  "objectType": "DEAL",
  "messages": [
    {
      "action": "UPSERT",
      "changedAt": "1553486400000",
      "externalObjectId": "1234",
      "properties": {
        "name": "Jeff aaaxx",
        "price": "562"
      }
    }
  ]
}';
 

   $ass=json_encode(array($assoc));
   $ass=json_encode($product_post);
   $path='deals/v1/deal';
   
   $path='crm-objects/v1/objects/products/paged?properties=name&name=xxxx';
 $path='crm-objects/v1/objects/line_items';
 $path='extensions/ecomm/v2/settings?appId=39761&hapikey=5837d3ab-02a3-45c3-b533-15dca0039428'; 
// $path='extensions/ecomm/v2/stores'; 
 $path='extensions/ecomm/v2/sync/messages'; 
 $path='crm-objects/v1/objects/line_items'; 
// $path='crm-associations/v1/associations';
 $path='crm-associations/v1/associations/create-batch';
 $path='crm-objects/v1/objects/products';
 //?appId=5961456&hapikey=fc99920a-94d4-4de6-aad3-8d580e60c3ba //&hapikey=fc99920a-94d4-4de6-aad3-8d580e60c3ba  ,  5837d3ab-02a3-45c3-b533-15dca0039428
 // info37 appId=39761&hapikey=5837d3ab-02a3-45c3-b533-15dca0039428
  //$res=$this->post_hubspot_arr($path,'get');
//   $res=$this->post_hubspot_arr($path,'post',$ass);
//   var_dump($res); die();    
   $extra=$contact=array();
  $portal_id=$id=""; $error=""; $action=""; $link=""; $search=$search_response=$status=""; 
  // entry note
  $entry_exists=false;
    $debug = isset($_REQUEST['vx_debug']) && current_user_can('manage_options'); 
    $event=$this->post('event',$meta);

    if(isset($fields['id'])){
      $id=$fields['id']['value']; unset($meta['primary_key']); unset($fields['id']);
  }
  if($debug){ ob_start();}
  //check primary key
  if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!=""){    
  $search=$fields[$meta['primary_key']]['value'];
  $field=$meta['primary_key'];
    if($object == 'Contact' && $field == 'email'){
 $spath='contacts/v1/contact/email/'.$search.'/profile';
$search_response=$this->post_hubspot_arr($spath);
if(!empty($search_response['vid'])){
 $id=$search_response['vid'];   
$contact =$search_response['properties'];   
}

}else if(in_array($object, array('Ticket','Deal','Company'))){
  $sobject='';
  if($object == 'Deal'){ $sobject='deals'; }else if($object == 'Ticket'){ $sobject='tickets'; }else if($object == 'Company'){ $sobject='companies'; }
 $spath='crm/v3/objects/'.$sobject.'/search';
 $sbody=array('filterGroups'=>array(array('filters'=>array(array('propertyName'=>$field,"operator"=> "EQ",'value'=>$search)))));
 $search_response=$hubspot_response=$this->post_hubspot_arr($spath,'post',$sbody); 
 if(!empty($search_response['results'][0]['id'])){
   $search_response=$search_response['results'][0];
   $id=$search_response['id']; 
 }
 //var_dump($search_response); die();
 }else{

  $sbody=array();
  $spath='contacts/v1/search/query';
  if($object == 'Company'){
  $spath='companies/v2/companies/'.$field.'/'.str_replace(array('http://','https://'),'',$search);    
  }else{
  $sbody['q']=$search;    
  }
  //search object
  //if primary key option is not empty and primary key field value is not empty , then check search object
  $search_response=$hubspot_response=$this->post_hubspot_arr($spath,'get',$sbody); 
 // var_dump($search_response,$spath,$sbody); die();

        if($search !=""){
      if(is_array($search_response) && count($search_response)>10){
       $search_response=array_slice($search_response,count($search_response)-10,10);   
      }
  }
  if(isset($hubspot_response[0]['Id'])&& $hubspot_response[0]['Id']!=""){
  //object found, update old object or add to feed
  $id=$hubspot_response[count($hubspot_response)-1]['Id'];
      $entry_exists=true;
  }
  if(!empty($hubspot_response['message'])){
  $error=$hubspot_response['message'];
  }else{
       if($object == 'Company' && isset($hubspot_response[0]['companyId']) ){
           $id=$hubspot_response[0]['companyId'];
           $portal_id=$hubspot_response[0]['portalId'];
       }else  if($object == 'Contact' && isset($hubspot_response['contacts'][0]['vid'])){
      $id=$hubspot_response['contacts'][0]['vid'];     
      $portal_id=$hubspot_response['contacts'][0]['portal-id'];     
       }
  }
    }
     $extra["body"]=$search;
      $extra["response"]=$search_response;   
  if($debug){
  ?>
  <pre>
  <h3>Search field</h3>
  <p><?php print_r($field) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($hubspot_response) ?></p>
  </pre>    
  <?php
  }

  $hubspot_response='';
  }

  if(!empty($meta['crm_id'])){
   $id=$meta['crm_id'];   
  } 

  $note_object='';
     if(in_array($event,array('delete_note','add_note'))){    
  if(isset($meta['related_object'])){
      $note_object=$meta['related_object'];
    $extra['Note Object']= $meta['related_object'];
  }
  if(isset($meta['note_object_link'])){
    $extra['note_object_link']=$meta['note_object_link'];
  }
}

if(!empty($meta['add_pipe']) && !empty($meta['pipe']) ){
      $sep=strpos($meta['pipe'],'-v_xx-') !== false ? '-v_xx-' : '-';
    $exp=explode($sep,$meta['pipe']);
    $fields['hs_pipeline']=array('value'=>$exp[0],'label'=>'Pipeline');
    if(!isset($fields['hs_pipeline_stage'])){
    $fields['hs_pipeline_stage']=array('value'=>$exp[1],'label'=>'Pipeline Stage');
    }
}
if(!empty($meta['add_sales_pipe']) && !empty($meta['sales_pipe']) ){
    $sep=strpos($meta['sales_pipe'],'-v_xx-') !== false ? '-v_xx-' : '-';
    $exp=explode($sep,$meta['sales_pipe']);
    $fields['pipeline']=array('value'=>$exp[0],'label'=>'Pipeline');
    if(!isset($fields['dealstage'])){
    $fields['dealstage']=array('value'=>$exp[1],'label'=>'Deal Stage');
    }
}
 if(!empty($meta['OwnerId']['value'])){
$fields['hubspot_owner_id']=array('value'=>$meta['OwnerId']['value'],'label'=>'Owner');  
 }
//var_dump($fields); 
  $path=''; $send_body=false; $post=array(); //$meta['_vx_contact_id']=array('value' =>301);
  //if($error ==""){
  if($id == ""){
  $action="Added";
 $send_body=true;
  if($object == 'Contact'){
   $path='contacts/v1/contact';   
  }else if($object == 'Ticket'){
   $path='crm-objects/v1/objects/tickets';   
  }else if($object == 'Deal'){
   $path='deals/v1/deal';   
 
  }else if($object == 'Company'){
   $path='companies/v2/companies';   
  }else if($object == 'Task'){ 
   $send_body=false;
   $path='engagements/v1/engagements';
if(!empty($fields['description']['value'])){        
      // && (!empty($meta['_vx_contact_id']) || !empty($meta['_vx_company_id']))
   $post['engagement']=array('type'=>'TASK');   
   
   if(!empty($fields['timestamp']['value'])){
       $t=strtotime($fields['timestamp']['value']);
       if(!empty($t)){
       $offset=get_option('gmt_offset') * 3600;
        $t-=$offset;
        $t=$t.'000';
       }
   $post['engagement']['timestamp']=$t;
   }
   
   $post['metadata']=array('body'=>$fields['description']['value']);
   
   if(!empty($fields['name']['value'])){
   $post['metadata']['subject']=$fields['name']['value'];    
   }
 if(!empty($meta['_vx_contact_id']['value'])){
 $post['associations']['contactIds']=array($meta['_vx_contact_id']['value']);    
 }
 if(!empty($meta['_vx_company_id']['value'])){
 $post['associations']['companyIds']=array($meta['_vx_company_id']['value']);    
 }
 if(!empty($meta['OwnerId']['value'])){
 $post['associations']['ownerIds']=array($meta['OwnerId']['value']);    
 $post['engagement']['ownerId']=$meta['OwnerId']['value'];    
 }  
   }
//var_dump($post,$fields); die();   
  }     
  $method='post';
$status="1";

  }
  else{ 

  if($event == 'add_note'){
   
  $path='engagements/v1/engagements';
  $post['engagement']=array('type'=>'NOTE');
  $post['associations']=array();
  if($note_object == 'Company'){
    $post['associations']['companyIds']=array((int)$id);  
  }else   if($note_object == 'Contact'){
  $post['associations']['contactIds']=array((int)$id);      
  }else   if($note_object == 'Ticket'){
  $post['associations']['ticketIds']=array((int)$id);      
  }
  $post['metadata']=array('body'=>$fields['Body']['value']);         
  $status="1";
  $method='post';  
  }
  else if(in_array($event,array('delete','delete_note'))){
     $method='delete';
     if($event == 'delete'){
         if($object == 'Contact'){
        $path='/contacts/v1/contact/vid/'.$id;     
         }else if($object == 'Company'){
         $path='companies/v2/companies/'.$id;    
         }else if($object == 'Ticket'){
         $path='/crm-objects/v1/objects/tickets/'.$id;    
         }    
     }else{
   $path='engagements/v1/engagements/'.$id;
     }  
  $action="Deleted";

    $status="5";  
  }
  else{    
      
  $action="Updated";
  if($object == 'Contact'){
   $path='contacts/v1/contact/vid/'.$id.'/profile';  
   $method='post'; 
  }else if($object == 'Company'){
   $path='companies/v2/companies/'.$id;   
  $method='put';
  }else if($object == 'Deal'){
   $path='deals/v1/deal/'.$id;   
  $method='put';
  } 
  $status="2"; $send_body=true;
   if(!empty($meta['update'])){ $path=''; }
  }

  }

$is_form=false;
if($send_body){ 
if(strpos($object,'vxf_') !== false){
$is_form=true;
 //,'sfdcCampaignId'=>'','goToWebinarWebinarKey'=>''
/*$post=array(
'context'=>array('pageUri'=>'https://local','pageName'=>'Local Test','hutk'=>'25886241a10e48ad86842f0125b578f7','ipAddress'=>'120.23.56.58'),
'legalConsentOptions'=>array('consent'=>array(
'consentToProcess'=>true,"text"=>'I agree to allow Example Company to store and process my personal data.',
//'communications'=>array(array('value'=>true,'text'=>'Text','subscriptionTypeId'=>'5388809'))
))
);*/

$form_id=substr($object,4);

if(!empty($this->info['portal_id']) && !empty($form_id) && !empty($fields) ){
$path='https://api.hsforms.com/submissions/v3/integration/submit/'.$this->info['portal_id'].'/'.$form_id;
if(!isset($fields['vx_url']) && !empty($meta['_vx_entry']['_vx_url'])){
    $fields['vx_url']=array('value'=>$meta['_vx_entry']['_vx_url'] , 'label'=>'Page URL');
}
if(!isset($fields['vx_title'])){
    $title='';
    if( !empty($meta['_vx_entry']['_vx_title'])){ $title=$meta['_vx_entry']['_vx_title']; }
    else if( !empty($meta['_vx_entry']['_vx_form_name'])){ $title=$meta['_vx_entry']['_vx_form_name']; }
    if(!empty($title)){
    $fields['vx_title']=array('value'=>$title , 'label'=>'Form Name');
    }
}
if(!isset($fields['vx_ip']) && !empty($meta['_vx_entry']['_vx_ip']) && filter_var($meta['_vx_entry']['_vx_ip'], FILTER_VALIDATE_IP)){
    $fields['vx_ip']=array('value'=>$meta['_vx_entry']['_vx_ip'] , 'label'=>'IP Address');
}


$context=array('vx_url'=>'pageUri','vx_title'=>'pageName','vx_ip'=>'ipAddress','vx_camp_id'=>'sfdcCampaignId','vx_webinar_key'=>'goToWebinarWebinarKey');
$com_consent=array();
foreach($fields as $k=>$v){
if(isset($context[$k])){
if($k == 'vx_url' && !empty($v['value'])){
    $v['value'] = strtok($v['value'], '?');
}
$post['context'][$context[$k]]=$v['value'];     
}else if($k == 'skipValidation'){
   $post['skipValidation']=empty($v['value']) ? false : true;    
}else if($k == 'vx_consent'){
$post['legalConsentOptions']=array('consent'=>array(
'consentToProcess'=>!empty($v['value']),
"text"=>'Yes, you can store and process my personal data.',
//"communications"=>array(array('value'=>true,'subscriptionTypeId'=>10577111,'text'=>'Yes, subscribe me'))
)); 
  
}else if(strpos($k,'vxoptin_') === 0){
    $val=!empty($v['value']) ? true : false;
 $com_consent[]=array('value'=>$val,'text'=>$v['value'],'subscriptionTypeId'=>substr($k,8));   
}else{
$type = !empty($meta['fields'][$k]['type']) ? $meta['fields'][$k]['type'] : '';    
$obj_type = !empty($meta['fields'][$k]['object']) ? $meta['fields'][$k]['object'].'.' : '';  
if( !in_array($obj_type,array('TICKET.')) ){ $obj_type=''; }    
$v['value']=$this->verify_val($v['value'],$type);
$post['fields'][]=array('name'=>$obj_type.$k,'value'=>$v['value']);
} }

if(!empty($meta['_vx_entry']['_vx_htuk'])){
    $post['context']['hutk']=$meta['_vx_entry']['_vx_htuk'];
$fields['hutk']=array('value'=>$meta['_vx_entry']['_vx_htuk'] , 'label'=>'Cookie');
}

if(!empty($com_consent) && isset($post['legalConsentOptions'])){
   $post['legalConsentOptions']['consent']['communications']= $com_consent;
}
//var_dump($post,$fields,$meta['_vx_entry']); die();
}
     
}
else{
$key='property';
if( in_array($object,array('Company','Ticket','Deal') ) ){
$key='name';
}

if(is_array($fields)){
foreach($fields as $k=>$v){
$type = !empty($meta['fields'][$k]['type']) ? $meta['fields'][$k]['type'] : '';  
  
$v['value']=$this->verify_val($v['value'],$type);
if($type == 'checkbox' && !empty($meta['fields'][$k]['is_custom']) && !empty($contact[$k]['value'])){
  $v['value']=$contact[$k]['value'].';'.$v['value']; 
  $v['value']=array_unique(explode(';',$v['value']));
  $v['value']=implode(';',$v['value']); 
}
$post[]=array($key=>$k,'value'=>$v['value']);    
} //var_dump($post); die();
}
if($object != 'Ticket'){
$post=array('properties'=>$post);
}
} }
$hubspot_response=array();
if(!empty($path)){
    if($object == 'Deal'){
            if(!empty($meta['_vx_contact_id']['value'])){
 $post['associations']['associatedVids']=array($meta['_vx_contact_id']['value']); 
 $fields['vid']= array('value'=>$meta['_vx_contact_id']['value'],'label'=>'Contact ID');  
 }
 if(!empty($meta['_vx_company_id']['value'])){
 $post['associations']['associatedCompanyIds']=array($meta['_vx_company_id']['value']);  
  $fields['vid-company']= array('value'=>$meta['_vx_company_id']['value'],'label'=>'Company ID');   
 }
    }
 // var_dump($post,$fields); die();  
$post_data=json_encode($post);
//var_dump($path,$post,$object,$id); die('--------');
$hubspot_response=$this->post_hubspot_arr($path,$method,$post_data);
//var_dump($hubspot_response,$post,$fields); die();
if($object == 'Deal'){
//var_dump($post,$hubspot_response); die();
}
}
if(!$is_form){
    $id_key='id';
    if($object == 'Deal'){
        $id_key='dealId';
    }

  if(isset($hubspot_response[$id_key])){
  $id=$hubspot_response[$id_key];

if(!empty($meta['order_items']) && $status == '1'){
  $items=$this->get_wc_items($meta);
  $assoc_items=array();
  foreach($items as $item){
      if(!empty($item['p_id'])){
   $hub_id=get_post_meta($item['p_id'],'vxc_hubspot_id',true);
   if(empty($hub_id)){
    $product_post=array(array('name'=>'name','value'=>$item['title']),array('name'=>'price','value'=>$item['unit_price']),array('name'=>'description','value'=>esc_html($item['desc'])));  
  $path='crm-objects/v1/objects/products';
 $product_res=$this->post_hubspot_arr($path,'post',json_encode($product_post));
 $extra['create product']=$product_res;    
  if(!empty($product_res['objectId'])){
      $hub_id=$product_res['objectId'];
  }
   }
 //create line item
     $item_post=array(array('name'=>'name','value'=>$item['title']),array('name'=>'price','value'=>$item['unit_price']),array('name'=>'quantity','value'=>$item['qty']),array('name'=>'hs_product_id','value'=>$hub_id));  
   $path='crm-objects/v1/objects/line_items'; 
    $product_res=$this->post_hubspot_arr($path,'post',json_encode($item_post)); 
    $extra['item post']=$item_post;      
    $extra['create item']=$product_res;      
  if(!empty($product_res['objectId'])){
      $item_id=$product_res['objectId'];
    $assoc_items[]=array("fromObjectId"=>$item_id,"toObjectId"=>$id,"category"=> "HUBSPOT_DEFINED","definitionId"=>20); 
  }   
      }    
  }
  if(!empty($assoc_items)){
  $path='crm-associations/v1/associations/create-batch'; 
  $product_res=$this->post_hubspot_arr($path,'put',json_encode($assoc_items)); 
  $extra['assign items to deal']=$product_res;   
  }  
}
 // $status="1";
  }else if(isset($hubspot_response['objectId'])){
  $id=$hubspot_response['objectId'];
  $portal_id=$hubspot_response['portalId'];
   $link='https://app.hubspot.com/contacts/'.$portal_id.'/ticket/'.$id.'/'; 
  $status="1";
  }

}else{
 $status="1";   
}
  //
  if(isset($hubspot_response['message'])){
  $error=$hubspot_response['message'];
  if(!empty($hubspot_response['errors'][0]['message'])){
        $error=$hubspot_response['errors'][0]['message'];
    }
  $id=''; $status='';
  }else{
      $portal_id=$this->info['portal_id'];
      if(isset($hubspot_response['vid']) && isset($hubspot_response['portal-id'])){
        $id=$hubspot_response['vid'];
        $portal_id=$hubspot_response['portal-id'];
        
      }else if(isset($hubspot_response['companyId']) && isset($hubspot_response['portalId'])){
       $id=$hubspot_response['companyId'];
        $portal_id=$hubspot_response['portalId'];
      }
      if(isset($hubspot_response['engagement']['id'])){
       $id=$hubspot_response['engagement']['id'];   
      }
      if(!empty($id) && !empty($portal_id)){
   if($object == 'Company'){
     $link='https://app.hubspot.com/sales/'.$portal_id.'/company/'.$id.'/';     
   }else if($object == 'Deal'){
     $link='https://app.hubspot.com/sales/'.$portal_id.'/deal/'.$id.'/';     
   }else if($object == 'Contact'){   
   $link='https://app.hubspot.com/contacts/'.$portal_id.'/contact/'.$id.'/';  
   
   //add to list and work flow
   if(!empty($fields['email']['value']) && !empty($meta['add_flow']) && !empty($meta['flow'])){
       $email=$fields['email']['value'];
    $path='automation/v2/workflows/'.$meta['flow'].'/enrollments/contacts/'.$email ;
   $work_res=$this->post_hubspot_arr($path,'post');
   $extra['Enrol to Work Flow']=$meta['flow'];   
   $extra['Work Flow Response']=$work_res;   
   }
   //add to static list
      if( !empty($meta['add_list']) && !empty($meta['list'])){
          $path='contacts/v1/lists/'.$meta['list'].'/add' ;
   $list_res=$this->post_hubspot_arr($path,'post',array('vids'=>array($id)));
   $extra['Add to List']=$meta['list']; 
   $extra['List Rsponse']=$list_res; 
       
      }
   
   //assign company
   if(!empty($meta['_vx_company_id']['value'])){
       $path='companies/v2/companies/'.$meta['_vx_company_id']['value'].'/contacts/'.$id;
        $comp_res=$this->post_hubspot_arr($path,'put');
   $extra['Assign Company']=$meta['_vx_company_id']['value']; 
   $extra['Company Rsponse']=$comp_res;   
   }
   
   }
   else if($object == 'Ticket'){ 
    if(!empty($meta['_vx_contact_id']['value'])){
       $path='crm-associations/v1/associations';
       $ass=array("fromObjectId"=>$meta['_vx_contact_id']['value'],"toObjectId"=>$id,"category"=> "HUBSPOT_DEFINED","definitionId"=>15);
        $comp_res=$this->post_hubspot_arr($path,'put',$ass);
   $extra['Assign Contact']=$meta['_vx_contact_id']['value'];  
   }
      if(!empty($meta['_vx_company_id']['value'])){
          $ass=array("fromObjectId"=>$meta['_vx_company_id']['value'],"toObjectId"=>$id,"category"=> "HUBSPOT_DEFINED","definitionId"=>25);
   $path='crm-associations/v1/associations';
        $comp_res=$this->post_hubspot_arr($path,'put',$ass);
   $extra['Assign Company']=$meta['_vx_company_id']['value']; 
   }
        
   }
   //
      }
      //
  }

 

  if($debug){
  ?>
  <pre>
  <h3>HubSpot Information</h3>
  <p><?php print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php echo json_encode($fields) ?></p>
  <h3>HubSpot response</h3>
  <p><?php print_r($hubspot_response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($object."--------".$action) ?></p>
  </pre>    
  <?php
  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }
   //add entry note
 if(!empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=$this->post('disable_entry_note',$meta);
   if(!($entry_exists && !empty($disable_note))){
       $entry_note=$meta['__vx_entry_note'];
       
          $path='engagements/v1/engagements';
  $note_post['engagement']=array('type'=>'NOTE');
  $note_post['associations']=array();
  if($object == 'Company'){
    $note_post['associations']['companyIds']=array((int)$id);  
  }else   if($object == 'Contact'){
  $note_post['associations']['contactIds']=array((int)$id);      
  }else   if($object == 'Ticket'){
  $note_post['associations']['ticketIds']=array((int)$id);      
  }else   if($object == 'Deal'){
  $note_post['associations']['dealIds']=array((int)$id);      
  }
  $note_post['metadata']=array('body'=>$entry_note['Body'],'subject'=>$entry_note['Title']); 
  
$note_res=$this->post_hubspot_arr($path,"POST",$note_post); 
  $extra['Note Response']=$note_res;
  $extra['Note Title']=$entry_note['Title'];
  $extra['Note Body']=$entry_note['Body'];

  
   }  
 }

  return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$hubspot_response,"extra"=>$extra);
}
public function verify_val($val,$type){
   // $type=isset($field['type']) ? $field['type'] : '';
            if( $type == 'file'  && is_string($val)){
            $files_temp=json_decode($val,true);
            if(!empty($files_temp)){
             $val=$files_temp;   
            }
    
        }
        if(is_array($val)){
        $val=implode(';',$val);     
        }
        if($type == 'number'){
        $val=filter_var( $val, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION ); 
        }
        if($type == 'booleancheckbox'){
            $val=!empty($val) ? 'true' : 'false';
        }
        if($type == 'date'){
            try{
        $date = new DateTime( $val );
        $date->modify( 'midnight' );
     $val=$date->getTimestamp() * 1000;
            }catch(Exception $e){
                
            }
        }
      if(in_array($type,array('datetime'))){
      $val=strtotime($val).'000';      
      }
return html_entity_decode($val);      
}
public function get_wc_items($meta){
      $_order=self::$_order;
    //  $fees=$_order->get_shipping_total();
    //  $fees=$_order-> get_total_discount();
    //  $fees=$_order-> get_total_tax();

     $items=$_order->get_items(); 
     $products=array();  $order_items=array(); 
if(is_array($items) && count($items)>0 ){
foreach($items as $item_id=>$item){

$sku=$desc=''; $qty=$unit_price=$tax=$total=$p_id=0;
if(method_exists($item,'get_product')){
  // $p_id=$v->get_product_id();  
   $product=$item->get_product();
   if(!$product){ continue; } //product deleted but exists in line items of old order

   $total=(int)$item->get_total();
   $qty = $item->get_quantity();
   $tax = $item->get_total_tax();

   $desc=$product->get_short_description();
   $title=$product->get_title();
   $sku=$product->get_sku();     
   $unit_price=$product->get_price(); 
   $p_id=$product->get_parent_id();
   if(empty($p_id)){
   $p_id=$product->get_id();
   }
   if(empty($total)){ $unit_price=0; } 
          
   }else{ //version_compare( WC_VERSION, '3.0.0', '<' )  , is_array($item) both work
          $line_item=$this->wc_get_data_from_item($item); 
   $p_id= !empty($line_item['variation_id']) ? $line_item['variation_id'] : $line_item['product_id'];
        $line_desc=array();
        if(!isset($products[$p_id])){
        $product=new WC_Product($p_id);
        }else{
         $product=$products[$p_id];   
        }
       if(!$product){ continue; }  
        $qty=$line_item['qty'];
        $products[$p_id]=$product;
        $sku=$product->get_sku(); 
        if(empty($sku) && !empty($line_item['product_id'])){ 
            //if variable product is empty , get simple product sku
            $product_simple=new WC_Product($line_item['product_id']);
            $sku=$product_simple->get_sku(); 
        }
        $unit_price=$product->get_price();
        $title=$product->get_title();
        $desc=$product->get_short_description();
        $p_id=$line_item['product_id'];
          }
  $temp=array('sku'=>$sku,'unit_price'=>$unit_price,'title'=>$title,'qty'=>$qty,'tax'=>$tax,'total'=>$total,'desc'=>$desc,'p_id'=>$p_id);
          if(method_exists($product,'get_stock_quantity')){
   $temp['stock']=$product->get_stock_quantity();
} 
     $order_items[]=$temp;     
      }
} 
     
   return $order_items;       
}

public function get_entry($object,$id){
        $path='contacts/v1/contact/vid/'.$id.'/profile';
        if($object == 'Company'){
        $path='/companies/v2/companies/'.$id;    
        }else if($object == 'Task'){
        $path='/engagements/v1/engagements/'.$id;    
        }
  $arr=$this->post_hubspot_arr($path);
  $entry=array();  // var_dump(isset($arr['metadata']));
  if(isset($arr['metadata'])){
     $arr['properties']=$arr['metadata']; 
  }
  if(isset($arr['properties']) && is_array($arr['properties']) && count($arr['properties'])>0){
      foreach($arr['properties'] as $k=>$v){
          
          if(isset($v['value']) && !is_array($v['value'])){
           $entry[$k]=$v['value']; 
          }else if(!is_array($v)){
          $entry[$k]=$v;    
          }
 
      }
  }
 // var_dump($arr,$path,$entry); die();
  return $entry;     
  }
public function create_fields_section($fields){
$arr=array(); 
if(!isset($fields['object'])){
    $objects=array(''=>'Select Object','Contact'=>'Contact','Company'=>'Company');
    if(is_array($objects_sf)){
    $objects=array_merge($objects,$objects_sf);
    }
 $arr['gen_sel']['object']=array('label'=>__('Select Object','gravity-forms-hubspot-crm'),'options'=>$objects,'is_ajax'=>true,'req'=>true);   
}else if(isset($fields['fields']) && !empty($fields['object'])){
    // filter fields
    $crm_fields=$this->get_crm_fields($fields['object']); 
    if(!is_array($crm_fields)){
        $crm_fields=array();
    }
    $add_fields=array();
    if(is_array($fields['fields']) && count($fields['fields'])>0){
        foreach($fields['fields'] as $k=>$v){
           $found=false;
                foreach($crm_fields as $crm_key=>$val){
                    if(strpos($crm_key,$k)!== false){
                        $found=true; break;
                }
            }
         //   echo $found.'---------'.$k.'============'.$crm_key.'<hr>';
         if(!$found){
       $add_fields[$k]=$v;      
         }   
        }
    }
 $arr['fields']=$add_fields;   
}

return $arr;  
} 
public function create_field($field){
  //  return 'ok';
 
$name=isset($field['name']) ? $field['name'] : '';
$label=isset($field['label']) ? $field['label'] : '';
$type=isset($field['type']) ? $field['type'] : '';
$object=isset($field['object']) ? $field['object'] : '';

$error='Unknow error';
if(!empty($label) && !empty($type) && !empty($object)){

    $body=array('name'=>$name,'label'=>$label,'groupName'=> strtolower($object).'information','type'=>'string');
    $path='properties/v1/contacts/properties';
    if($object == 'Company'){ 
    $path='properties/v1/companies/properties';
    }
$arr=$this->post_hubspot_arr($path,'POST',$body); 

    $error='ok';
if(!isset($arr['name']) ){
  $error=$arr;  
 if(isset($arr['message'])){
 $error=$arr['message'];    
 }   
}
}
return $error;    
} 
}
}
?>