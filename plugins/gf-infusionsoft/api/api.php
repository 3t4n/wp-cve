<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxg_infusionsoft_api')){
    
class vxg_infusionsoft_api extends vxg_infusionsoft{
  
  public $info='' ; // info
  public $meta='' ; 
  public $error= "";
  public $timeout=30;
  public $url='https://api.infusionsoft.com/';

  
  function __construct($info) { 
      if(isset($info['data'])){
  $this->info= is_array($info['data']) ? $info['data'] : array();
      }
       if(isset($info['meta'])){
  $this->meta= $info['meta'];
      }
if(!empty(self::$api_timeout)){
    $this->timeout=self::$api_timeout;
}

}
  public function get_token(){
    $info=$this->info;

  $users=$this->get_users();

  if(is_array($users) && count($users)>0){
    $info['valid_token']='true';    
    }else{
      unset($info['valid_token']);  
    }
     $info['_time']=time(); 
    return $info;

}
  /**
  * Get New Access Token from infusionsoft
  * @param  array $form_id Form Id
  * @param  array $info (optional) Infusionsoft Credentials of a form
  * @param  array $posted_form (optional) Form submitted by the user,In case of API error this form will be sent to email
  * @return array  Infusionsoft API Access Informations
  */
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
  $re=$this->post_crm('','','post',$body);

 // var_dump($re,$body); die('------');
  if(isset($re['access_token']) && $re['access_token'] !=""){ 
  $info["access_token"]=$re['access_token'];
  $info["refresh_token"]=$re['refresh_token'];
 // $info["org_id"]=$re['id'];
  $info["class"]='updated';
  $token=$info;
  $info["token_time"]=time(); 
  $info['valid_token']='true'; 
  }else{
$error=isset($re['error_description']) ? $re['error_description'] : 'Error while connecting to infusionsoft';
      $info['valid_token']=''; 
  $info['error']=$error;
  $info['access_token']="";
   $info["class"]='error';
  $token=array(array('errorCode'=>'406','message'=>$error));
 // $this->log_msg("Auto Token Error ".$res);
  } 
  //api validity check
  $this->info=$info;
  //update infusionsoft info 
  //got new token , so update it in db
  $this->update_info( array("data"=> $info),$info['id']); 
  return $info; 
  }
  public function handle_code(){
      $info=$this->info;
      $id=$info['id'];
 
        $client=$this->client_info();
  $log_str=array(); $token=array();
  if(isset($_REQUEST['code'])){
  $code=$this->post('code'); 
  
  if(!empty($code)){
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],"redirect_uri"=>$client['call_back'],"grant_type"=>"authorization_code","code"=>$code);
  $token=$this->post_crm('','','post',$body);

  $log_str="Getting access token from code";
   if(!isset($token['access_token'])){
      $log_str.=" =".json_encode($token); 
   }
  }

  }else{  
  //revoke token on user request
  $log_str="Access token Revoked on Request";
    if(isset($_REQUEST['error_description'])){
   $token['message']=$this->post('error_description');   
  }
  }

  $url='';
  if(!empty($token['scope'])){
   $temp=explode('|',$token['scope']);
   if(!empty($temp[1])){
       $url=$temp[1];
   }   
  }

  $info['instance_url']=$url;
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
  $info['valid_token']=''; 
  if(!empty($info['access_token'])){
  $info["class"]='updated';
  $info['valid_token']='true'; 
  }
 // $info=$this->validate_api($info);
  $this->update_info( array('data'=> $info) , $id); //var_dump($token,$info); die();
  return $info;
  }
  /**
  * Get Infusionsoft Client Information
  * @param  array $info (optional) Infusionsoft Client Information Saved in Database
  * @return array Infusionsoft Client Information
  */
  public function client_info(){
      $info=$this->info;

  $apps=array('1'=>array('5qw3p644gf7rwqqnjfyp98hv','8dPGWpQSdt')); //crmcen  --- 
$apps['2']=array('twypxxb4c743nue7b8tkc7hu','jZ2EsYMfaw'); //touf@vir.....
$apps['3']=array('f5ywad7m5nyk98rr3dc3cazv','Bjr8FrjP4V'); //crmcen
$apps['4']=array('u9xph4gfqb2jpguhtac2jxrg','YgrGfepSub'); //crmcen
$apps['5']=array('h3z9uvnra4ph3v2z8tz44j9c','hFeRDbr2WK'); //ta26
$apps['6']=array('2266whpvvhqwjr7xn6hxk9w4','qQhRMzghzN'); //ta26
$apps['7']=array('2hmtdafb23epd3kzdgtsw3dg','VeHRB7AMev'); //bi37
$apps['8']=array('nez5zsf32pj7vm83fw2wwyyq','f2MwH5Ek4H'); //bi35

if(empty($info['dc'])){ $info['dc']='1'; }
$client_id=$apps[$info['dc']][0];
$client_secret=$apps[$info['dc']][1];
  $call_back="https://www.crmperks.com/nimble_auth/"; 
  //custom app
  if(is_array($info)){
      if($this->post('custom_app',$info) == "yes" && $this->post('app_id',$info) !="" && $this->post('app_secret',$info) !="" ){ //&& $this->post('app_url',$info) !=""
     $client_id=$this->post('app_id',$info);     
     $client_secret=$this->post('app_secret',$info);     
     $call_back=$this->post('app_url',$info);     
      }
  }
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back);
  }
  
  /**
  * Get fields from infusionsoft
  * @param  string $form_id Form Id
  * @param  array $form (optional) Form Settings 
  * @param  array $request (optional) custom array or $_REQUEST 
  * @return array Infusionsoft fields
  */
public function get_crm_fields($object){ 

$fields['Contact']='{
    "Email": {
        "group": "email_addresses",
        "id": "email",
        "field": "EMAIL1","req":"true","search":"true"
    },
    "EmailAddress2": {
    "group": "email_addresses",
        "id": "email",
        "field": "EMAIL2"
    },
    "EmailAddress3": {
         "group": "email_addresses",
        "id": "email",
        "field": "EMAIL3"
    },
    "Fax1": {
        "group": "fax_numbers",
        "id": "number",
        "field": "FAX1"
    },
    "Fax1Type": {
      "group": "fax_numbers",
        "id": "type",
        "field": "FAX1"
    },
    "Fax2": {
       "group": "fax_numbers",
        "id": "number",
        "field": "FAX2"
    },
    "Fax2Type": {
       "group": "fax_numbers",
        "id": "type",
        "field": "FAX2"
    },
    "FirstName": {
        "id": "given_name","search":"true"
    },
     "LastName": {
        "id": "family_name","search":"true"
    },
     "Name": {
        "id": "name"
    },
    "MiddleName": {
        "id": "middle_name"
    },
      "Nickname": {
        "id": "preferred_name"
    },
     "SpouseName": {
        "id": "spouse_name"
    },
    "Suffix": {
        "id": "suffix"
    },
    "Prefix": {
        "id": "prefix"
    },
    "Notes": {
        "id": "notes"
    },
    "JobTitle": {
        "id": "job_title"
    },
    "Phone1": {
        "group": "phone_numbers",
        "id": "number",
        "field": "PHONE1"
    },
    "Phone1Ext": {
        "group": "phone_numbers",
        "id": "extension",
        "field": "PHONE1"
    },
    "Phone1Type": {
        "group": "phone_numbers",
        "id": "type",
        "field": "PHONE1"
    },
    "Phone2": {
        "group": "phone_numbers",
        "id": "number",
        "field": "PHONE2"
    },
    "Phone2Ext": {
        "group": "phone_numbers",
        "id": "extension",
        "field": "PHONE2"
    },
    "Phone2Type": {
        "group": "phone_numbers",
        "id": "type",
        "field": "PHONE2"
    },
    "Phone3": {
        "group": "phone_numbers",
        "id": "number",
        "field": "PHONE3"
    },
    "Phone3Ext": {
        "group": "phone_numbers",
        "id": "extension",
        "field": "PHONE3"
    },
    "Phone3Type": {
        "group": "phone_numbers",
        "id": "type",
        "field": "PHONE3"
    },
    "Phone4": {
        "group": "phone_numbers",
        "id": "number",
        "field": "PHONE4"
    },
    "Phone4Ext": {
        "group": "phone_numbers",
        "id": "extension",
        "field": "PHONE4"
    },
    "Phone4Type": {
        "group": "phone_numbers",
        "id": "type",
        "field": "PHONE4"
    },
    "Phone5": {
        "group": "phone_numbers",
        "id": "number",
        "field": "PHONE5"
    },
    "Phone5Ext": {
        "group": "phone_numbers",
        "id": "extension",
        "field": "PHONE5"
    },
    "Phone5Type": {
        "group": "phone_numbers",
        "id": "type",
        "field": "PHONE5"
    },
    "Website": {
        "id": "website"
    },
      "Facebook": {
        "group": "social_accounts",
        "id": "name",
        "field": "Facebook"
    },
      "Twitter": {
        "group": "social_accounts",
        "id": "name",
        "field": "Twitter"
    },
      "LinkedIn": {
        "group": "social_accounts",
        "id": "name",
        "field": "LinkedIn"
    },
      "StreetAddress1": {
        "group": "addresses",
        "id": "line1",
        "field": "BILLING"
    },
    "StreetAddress2": {
        "group": "addresses",
        "id": "line2",
        "field": "BILLING"
    },
    "Address2Street1": {
        "group": "addresses",
        "id": "line1",
        "field": "SHIPPING"
    },
    "Address2Street2": {
        "group": "addresses",
        "id": "line2",
         "field": "SHIPPING"
    },
    "Address3Street1": {
        "group": "addresses",
        "id": "line1",
        "field": "OTHER"
    },
    "Address3Street2": {
        "group": "addresses",
        "id": "line2",
        "field": "OTHER"
    },
    "City": {
        "group": "addresses",
        "id": "locality",
        "field": "BILLING"
    },
    "City2": {
        "group": "addresses",
        "id": "locality",
         "field": "SHIPPING"
    },
    "City3": {
        "group": "addresses",
        "id": "locality",
        "field": "OTHER"
    },
      "State": {
        "group": "addresses",
        "id": "region",
        "field": "BILLING"
    },
    "State2": {
        "group": "addresses",
        "id": "region",
         "field": "SHIPPING"
    },
    "State3": {
        "group": "addresses",
        "id": "region",
        "field": "OTHER"
    },
        "Country": {
        "group": "addresses",
        "id": "country_code",
        "field": "BILLING"
    },
    "Country2": {
        "group": "addresses",
        "id": "country_code",
         "field": "SHIPPING"
    },
    "Country3": {
        "group": "addresses",
        "id": "country_code",
        "field": "OTHER"
    },
    "PostalCode": {
        "group": "addresses",
        "id": "zip_code",
        "field": "BILLING"
    },
    "PostalCode2": {
        "group": "addresses",
        "id": "zip_code",
         "field": "SHIPPING"
    },
    "PostalCode3": {
        "group": "addresses",
        "id": "zip_code",
        "field": "OTHER"
    },
     "ZipFour1": {
        "group": "addresses",
        "id": "zip_four",
        "field": "BILLING"
    },
    "ZipFour2": {
        "group": "addresses",
        "id": "zip_four",
         "field": "SHIPPING"
    },
    "ZipFour3": {
        "group": "addresses",
        "id": "zip_four",
         "field": "OTHER"
    },
    "ContactType": {
        "id": "contact_type"
    },
    "TimeZone": {
        "id": "time_zone"
    },
        "Anniversary": {
        "id": "anniversary",
        "type": "Date"
    },
    "Birthday": {
        "id": "birthday",
        "type": "Date"
    },
       "Language": {
        "id": "preferred_locale"
    },
    "SourceType": {
        "id": "source_type"
    },
    "OptinReason": {
        "id": "opt_in_reason"
    },
    "Files": {
        "id": "files"
    },
    "AffiliateId": {
        "id": "AffiliateId","is_custom":"true"
    },
    "AffiliateCode": {
        "id": "AffiliateCode","is_custom":"true"
    },
    "owner_id": {
        "id": "owner_id"
    }
}';

$fields['Company']='{
    "Email": {
        "id": "email_address","req":"true"
    },
    "Fax": {
        "group": "fax_number",
        "id": "number"
    },
    "FaxType": {
      "group": "fax_number",
        "id": "type"
    },
    "CompanyName": {
        "id": "company_name","req":"true","search":"true"
    },
    "Notes": {
        "id": "notes"
    },
    "Phone": {
        "group": "phone_number",
        "id": "number"
    },
     "PhoneType": {
      "group": "phone_number",
        "id": "type"
    },
    "PhoneExt": {
        "group": "phone_number",
        "id": "extension"
    },
    "Website": {
        "id": "website"
    },
      "StreetAddress": {
        "group": "address",
        "id": "line1"
    },
    "StreetAddress2": {
        "group": "address",
        "id": "line2"
    },
    "City": {
        "group": "address",
        "id": "locality"
    },
      "State": {
        "group": "address",
        "id": "region"
    },
        "Country": {
        "group": "address",
        "id": "country_code"
    },
    "PostalCode": {
        "group": "address",
        "id": "zip_code"
    },
     "ZipFour": {
        "group": "address",
        "id": "zip_four"
    }
}';

$fields['Order']='{
"OrderTitle":{"id":"order_title","req":"true","search":"true"},
"OrderDate":{"id":"order_date","type": "Date","req":"true"},
"OrderType":{"id":"order_type"}, 
"CompanyName": {
        "id": "company","group":"shipping_address"
    },
       "FirstName": {
        "id": "first_name","group":"shipping_address"
    },
     "LastName": {
        "id": "last_name","group":"shipping_address"
    },
    "MiddleName": {
        "id": "middle_name","group":"shipping_address"
    },
    "Phone": {
        "id": "phone","group":"shipping_address"
    },
    "StreetAddress": {
        "group": "shipping_address",
        "id": "line1"
    },
    "StreetAddress2": {
        "group": "shipping_address",
        "id": "line2"
    },
    "City": {
        "group": "shipping_address",
        "id": "locality"
    },
      "State": {
        "group": "shipping_address",
        "id": "region"
    },
        "Country": {
        "group": "shipping_address",
        "id": "country_code"
    },
    "PostalCode": {
        "group": "shipping_address",
        "id": "zip_code"
    },
     "ZipFour": {
        "group": "shipping_address",
        "id": "zip_four"
    },
     "ItemName": {
        "group": "xitem",
        "id": "item_name"
    },
     "ItemSku": {
        "group": "xitem",
        "id": "item_sku"
    },
     "ItemQuantity": {
        "group": "xitem",
        "id": "item_quantity"
    },
     "ItemPrice": {
        "group": "xitem",
        "id": "item_price"
    },
     "ItemDescription": {
        "group": "xitem",
        "id": "item_desc"
    }
    ,"Order_Total": {"id": "vx_order_total"}
    ,"Sales_Affiliate_Id": {"id": "sales_affiliate_id","is_custom":"true"}
    ,"Sales_Affiliate_Code": {"id": "sales_affiliate_code","is_custom":"true"}
    ,"Lead_Affiliate_Id": {"id": "lead_affiliate_id","is_custom":"true"}
    ,"Lead_Affiliate_Code": {"id": "lead_affiliate_code","is_custom":"true"}
    ,"Payment_Method_Type": {"id": "vx_payment_method_type","options":["CREDIT_CARD","CASH","CHECK"]}
    }';
    
$fields['Opportunity']='{
"opportunity_title":{"id":"opportunity_title","req":"true","search":"true"},
"opportunity_notes":{"id":"opportunity_notes"},
"next_action_notes":{"id":"next_action_notes"},
"next_action_date":{"id":"next_action_date","type": "Date"},
"estimated_close_date":{"id":"estimated_close_date","type": "Date"},
"projected_revenue_high":{"id":"projected_revenue_high"},
"projected_revenue_low":{"id":"projected_revenue_low"},
"stage":{"id":"stage","req":"true"},
"affiliate_id":{"id":"affiliate_id"}
    }';

//{"group":"address2","id":"line1"}
$arr=json_decode($fields[$object],true);

   $fields=array(); 
  if(is_array($arr) && count($arr)>0){
      foreach($arr as $k=>$v){
          if(!isset($v['type'])){
           $v['type']='Text';   
          }
        if( in_array($k,array('Phone1Type','Phone2Type','Phone3Type','Phone4Type')) ){
              $v['type']='PhoneType';
              $v['options']=array('Work','Home','Mobile','Other');
          }  
      /*    if($v['id'] == 'prefix'){
              $v['type']='pickList';
              $v['options']=array('Mr.','Mrs.','Dr.','Ms.');
          }else if($v['id'] == 'suffix'){
              $v['type']='pickList';
              $v['options']=array('I','II','III','IV','V','PhD','Jr');
          }
      */
  
          if(!empty($v['options'])){ 
 $ops=$op=array();
 foreach($v['options'] as $vv){
     $ops[]=array('name'=>$vv,'value'=>$vv);
     $op[]=$vv;
 }
$v['options']=$ops;  
$v['eg']=implode(', ',array_slice($op,0,50));    
           }
         if($v['type'] == 'PhoneType'){
             unset($v['options']);
         }  
               if($k == 'stage'){
      $res_stage=$this->post_crm('', 'opportunity/stage_pipeline','get');
      
      if(!empty($res_stage) && is_array($res_stage)){
          $ops=$op=array();
          foreach($res_stage as $stage){ 
          $ops[]=array('name'=>$stage['stage_id'],'value'=>$stage['stage_name']);    
          $op[]=$stage['stage_id'].'='.$stage['stage_name'];
          }
    $v['type']='pickList';      
$v['options']=$ops;  
$v['eg']=implode(', ',array_slice($op,0,50));
      }    
      }
          $v['name']=$k; 
          $v['label']=$k;
          if($v['label'] == 'owner_id'){
           //   $v['label']='Owner ID';
          }
 $fields[$k]=$v; 
      }
  } 
$res=array();
if($object != "Order"){   
$res=$this->post_crm($object,'model','get');
} 
//var_dump($res);
//die(json_encode($arr));
if( is_array($arr) && !empty($res['custom_fields']) ){
foreach($res['custom_fields'] as $v){
$name=preg_replace("/[^a-zA-Z0-9]+/", "", $v['label']);
$field=array('name'=>'_'.$name,'label'=>$v['label'],'id'=>$v['id'],'is_custom'=>'true');
$field['type']=isset($v['field_type']) ? $v['field_type'] : 'Text';
if(!empty($v['options'])){ 
 $ops=$op=array();
 foreach($v['options'] as $vv){
     $ops[]=array('name'=>$vv['id'],'value'=>$vv['label']);
     $op[]=$vv['id'].'='.$vv['label'];
 }
$field['options']=$ops;  
$field['eg']=implode(', ',array_slice($op,0,50));    
           }
           
       $fields[$field['name']]=$field;        
       }    
}
if($object == 'Contact'){
$res=$this->post_crm_arr('query','LeadSource'); 
$arr=$this->key_val_arr($res);
if( is_array($arr) ){
 $ops=$op=array();
    foreach($arr as $v){
    $ops[]=array('name'=>$v['Id'],'value'=>$v['Name']);
     $op[]=$v['Id'].'='.$v['Name'];
    }
$field=array('name'=>'lead_source_id','label'=>'Lead Source','id'=>'lead_source_id');  
$field['type']='LeadSouce';
$field['options']=$ops;
$field['eg']=implode(', ',array_slice($op,0,50));
$fields['lead_source_id']=$field;
} }
$fields['id']=array('name'=>'id','label'=>'ID','type'=>'number','search'=>'true');
//var_dump($fields);
return $fields;
}
    
  /**
  * Get users from infusionsoft
  * @return array Infusionsoft users
  */
public function get_users(){ 

  $res=$this->post_crm('','users','get');

  $users=__('No Users Found');
  
   if( !empty($res['users']) ){
       $users=array();
       foreach($res['users'] as $k=>$v){
$users[$v['id']]=$v['given_name'].' '.$v['family_name'].' ('.$v['email_address'].')';            
       }
   }else if( !empty($res['message']) ){
    $users=$res['message'];   
   }
  
  return $users;
}

public function get_tags(){ 

  $res=$this->post_crm('','tags','get');
  ///seprating fields
  $field_info=__('No Tag Found');
  $tags_all=array();
     if( is_array($res['tags'])){
     $tags_all=$res['tags'];
       if(!empty($res['next'])){
       $url_arr=explode('/',$res['next']);  
       $last_part=end($url_arr);  
$count=!empty($res['count']) ? $res['count'] : 1000;

if($count > 1000){
    $pages=ceil($count/1000); 
    for($i=1; $i<$pages ; $i++){
   $res=$this->post_crm('','tags/?limit=1000&offset='.$i*1000,'get'); 
   if( is_array($res['tags'])){
     $tags_all=array_merge($tags_all,$res['tags']);  
   }    
    }
}
             }  
  $field_info=array();
  foreach($tags_all as $k=>$field){
  $field_info[$field['id']]=$field['name'];     
  }
  }else if( !empty($res['message']) ){
    $field_info=$res['message'];   
   }
   

  return $field_info;
}
  
  /**
  * Posts object to infusionsoft, Creates/Updates Object or add to object feed
  * @param  array $entry_id Needed to update infusionsoft response
  * @return array Infusionsoft Response and Object URL
  */
public function push_object($object,$fields,$meta){ 

/*  $json='{"apply_to_commissions": false,"charge_now":false,"notes": "string","payment_amount": "50","payment_method_type": "ADJUSTMENT"}'; 
    // ,"payment_gateway_id":"M","credit_card_id": 0 ,"date": "2019-04-17T11:12:18.956Z"
    //payment_method_type=CREDIT_CARD , CASH ,CHECK
    $post=json_decode($json,1);
    $path='/orders/{orderId}/transactions';
//$res=$this->post_crm('','orders/13/payments','post',$post);
//==$res=$this->post_crm('','orders/15/transactions','get');
//==$res=$this->post_crm('','orders/15/transactions','get');
$post=array('given_name'=>'Touseef','opt_in_reason'=>'Contact gave explicit permission','duplicate_option'=>'Email','email_addresses'=>array(array('email'=>'bioinfo35@gmail.com','field'=>'EMAIL1')));
//$res=$this->post_crm('','contacts','put',$post); //'email_opted_in'=>true,
//$res=$this->post_crm_arr('optin','','');
//$res=$this->post_crm('','contacts/189','get');
  $post['addresses'][0]['region']='Punjabx';   
 $post['addresses'][0]['country_code']='PAK';   
 $post['addresses'][0]['locality']='lahore';   
 $post['addresses'][0]['line1']='lahore';   
 $post['addresses'][0]['field']='BILLING';   

//$res=$this->post_crm($object,'189','patch',$post);
//var_dump($res); die();
$res=$this->post_crm('','contacts/249','get');
var_dump($res); die();
*/

   $extra=array();
  $portal_id=$id=""; $error=""; $action=""; $link=""; $search=$search_response=$status=""; 
  // entry note
  $entry_exists=false;
  $objects=array('Contact'=>'contacts','Company'=>'companies','Order'=>'orders');
 // $module=$objects[$object];
    $debug = isset($_REQUEST['vx_debug']) && current_user_can('manage_options'); 
    $event=$this->post('event',$meta);

      if( !empty($meta['__vx_entry_note']['Body']) && !empty($meta['send_note_to']) ){
    $fields['Notes']=array('value'=>$meta['__vx_entry_note']['Body'],'label'=>'Notes');
    $meta['__vx_entry_note']=array();
}
if(isset($fields['id'])){
    $id=$fields['id']['value'];
    unset($fields['id']); unset($meta['primary_key']);
}

  if($debug){ ob_start();}
  //check primary key
  if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!="" && !empty($meta['fields'][$meta['primary_key']]['id'])){    
  $search=$fields[$meta['primary_key']]['value'];
  $field=$meta['fields'][$meta['primary_key']]['id'];
$pars='?optional_properties=custom_fields,notes';
if($object == 'Opportunity'){
    $pars=''; $field='search_term';
}else if($object == 'Contact'){
$pars.=',job_title';    
}
if($object == 'Order' && $field == 'order_title'){ 
    $field='JobTitle'; 
$p_search=$this->post_crm_arr('query','Order',array('field'=>$field, 'search'=>$search ));   
$id=$this->get_id_search($p_search); 
//var_dump($p_search,$id); die();
}else{
//if primary key option is not empty and primary key field value is not empty , then check search object
$search_response=$this->post_crm($object,$pars,'get',array($field=>$search)); 
//var_dump($search_response,$field,$search,$object); die();
  if(!empty($search_response) && is_array($search_response)){
      $entry=reset($search_response);
      if(is_array($entry) && !empty($entry[0]['id'])){
          $id=$entry[0]['id'];  
   $entry_exists=true;
if(!(!empty($id) && !empty($meta['disable_entry_note']))){
   if(!empty($entry[0]['notes']) && !empty($fields['Notes']['value']) ){
      $fields['Notes']['value']=trim($entry[0]['notes']."\n".$fields['Notes']['value']); 
   }
}else if( isset($fields['Notes']) ){
   unset($fields['Notes']); 
}
      }
  }
}

  if($debug){
  ?>
  <pre>
  <h3>Search field</h3>
  <p><?php print_r($field) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($search_response) ?></p>
  </pre>    
  <?php
  }
    if($search !=""){
      $extra["body"]=array($field=>$search);
      $extra["response"]=$search_response;
  }

  }

//$id='62045';
//$id='43600';
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
//var_dump($search_response,$id); die('------');
  $method=''; $send_body=false; $res=$post=$payment=array(); 
  $files=array();
  if(!empty($fields['Files'])){
  $files=$this->verify_files($fields['Files']['value']);
  unset($fields['Files']);    
  }
  //if($error ==""){
if($id == ""){
  $action="Added";
    if(empty($meta['new_entry'])){ 
  //create new lead
 $send_body=true;    
  $method='post';
$status="1";
}else{
      $error='Entry does not exist';
  }


}
else{ 

  if($event == 'add_note'){
  $post=array('title'=>$fields['Title']['value'],'body'=>$fields['Body']['value'],'contact_id'=>$id);
  $post['user_id']=$this->get_user_id($meta);  
  $status="1";
  $method='post';  $id=''; 
  }
  else if(in_array($event,array('delete','delete_note'))){
     $method='delete';  
  $action="Deleted";
    $status="5";  
  }
  else{     
  $action="Updated";
if(empty($meta['update'])){
   $method='PATCH'; 
  $send_body=true;  
  if($object == 'Company'){
  $object='company';
  }
}
  $status="2"; $entry_exists=true;
  }
}
$aff_id='';

if($send_body){
if(is_array($fields)){
 if(!empty($fields) && !isset($fields['OptinReason']) && $object == 'Contact' ){
   $fields['OptinReason']=array('value'=>'from Wordpress','label'=>'Optin Reason');  
 }

 if(isset($fields['AffiliateId']['value'])){
  $aff_id=$fields['AffiliateId']['value'];   
 unset($fields['AffiliateId']);
 }
  if(isset($fields['AffiliateCode']['value'])){
  $aff_code=$fields['AffiliateCode']['value'];
  if(empty($aff_id)){
  $aff_id=$this->get_aff_id($aff_code);
  }
  unset($fields['AffiliateCode']);
  }
  if(isset($fields['Lead_Affiliate_Code']['value'])){
  $aff_code=$fields['Lead_Affiliate_Code']['value'];
  
  if(empty($fields['Lead_Affiliate_Id']['value'])){
  $aff_id=$this->get_aff_id($aff_code);
  if(!empty($aff_id)){
  $fields['Lead_Affiliate_Id']=array('value'=>$aff_id);
  }   }
 unset($fields['Lead_Affiliate_Code']);
 }
  if(isset($fields['Sales_Affiliate_Code']['value'])){
  $aff_code=$fields['Sales_Affiliate_Code']['value'];
  
  if(empty($fields['Sales_Affiliate_Id']['value'])){
  $aff_id=$this->get_aff_id($aff_code);
  if(!empty($aff_id)){
  $fields['Sales_Affiliate_Id']=array('value'=>$aff_id);
  }   }
 unset($fields['Sales_Affiliate_Code']);
 }

    $temp_body=array();
foreach($fields as $k=>$v){
   $field=isset($meta['fields'][$k]) ? $meta['fields'][$k] : array();
    if(empty($field)){ continue; }
  
    if($v['value'] == ''){ continue; }
     
    if(in_array($k,array('Country','Country2','Country3'))){
     $v['value']=$this->get_country_code($v['value']);   
    }

    if(in_array($k,array('stage'))){
      $v['value']=array('id'=>$v['value']);  
    }
    if(in_array($k,array('State','State2','State3'))){
     $v['value']=$this->get_state($v['value']);   
    }

if($field['type'] == 'Date'){ 
if($object == 'Order'){
     $v['value']=date('Y-m-d\T23:59:59+00:00',strtotime(str_replace(array("/"),"-",$v['value']))); 
}else{
       $v['value']=date('Y-m-d',strtotime(str_replace(array("/"),"-",$v['value'])));
}
}

if($k == 'Website' || $field['type'] == 'Website'){
    if(strpos($v['value'],'://') === false){
        $v['value']='http://'.$v['value'];
    }
}
if(!empty($field['group'])){
         if(!isset($temp_body[$field['group']])){
     $temp_body[$field['group']]=array();    
         }
         if(!empty($field['field'])){
              if(!isset($temp_body[$field['group']][$field['field']])){
     $temp_body[$field['group']][$field['field']]=array();    
         }
         $temp_body[$field['group']][$field['field']][$field['id']]=$v['value'];    
         }else{
        $temp_body[$field['group']][$field['id']]=$v['value'];       
         }
}else if(in_array($k,array('Sales_Affiliate_Id','Lead_Affiliate_Id')) ){
    $temp_body[$field['id']]=$v['value']; 
} else if(!empty($field['is_custom'])){
          if(!isset($temp_body['custom_fields'])){
     $temp_body['custom_fields']=array();    
         }
       $temp_body['custom_fields'][]=array('content'=>$v['value'],'id'=>$field['id']);   
}else{
     $temp_body[$field['id']]=$v['value'];    
}   
    }
//var_dump($temp_body); die();
foreach($temp_body as $k=>$v){

     if(!empty($v) && in_array($k,array('email_addresses','addresses','phone_numbers','social_accounts'))){
     $op=array(); $field='field';
     if($k == 'social_accounts'){ $field='type'; }
   
foreach($v as $kk=>$vv){
$vv[$field]=$kk;
if(in_array($kk,array('PHONE1','PHONE2','PHONE3','PHONE4')) && !isset($vv['type'])){
 $vv['type']='Work';  
}
$op[]=$vv;         
}
$v=$op;    
}
$post[$k]=$v;

} 
//$post['social_accounts']=array(array('name'=>'facebook-name','type'=>'Facebook'),array('name'=>'facebook-name','type'=>'Twitter'),array('name'=>'facebook-name','type'=>'LinkedIn'),array('name'=>'facebname','type'=>'Instagram'));//,array('name'=>'facebook-name','type'=>'Snapchat'),array('name'=>'facebook-name','type'=>'YouTube'),array('name'=>'facebook-name','type'=>'Pinterest')); 
//
if($object == 'Order'){
 //var_dump($post,$fields,$temp_body); die();   
     //  $post['custom_fields'][]=array('content'=>'test test xxxxxx','id'=>$field['id']);
//echo json_encode($post).'<hr>'.json_decode($fields); die('---------'); 
}
//echo json_encode($post); die();
 //var_dump($post,$id);die();
if(!empty($post['vx_order_total'])){
 $payment['payment_amount']=$post['vx_order_total'];
 unset($post['vx_order_total']);   
 $payment['payment_method_type']='CREDIT_CARD';
if(!empty($post['vx_payment_method_type'])){
 $payment['payment_method_type']=$post['vx_payment_method_type'];
 unset($post['vx_payment_method_type']);   
}
}



 
if(!empty($post['name'])){
  $name_arr=explode(' ',$post['name']);
  if(count($name_arr)>1){
$post['given_name']=trim($name_arr[0]); 
unset($name_arr[0]);     
$post['family_name']=trim(implode(' ',$name_arr));      
  }else{
 $post['family_name']=trim(implode(' ',$name_arr));      
  }  
  unset($post['name']);
} 
    if( !empty($fields['OwnerID']['value']) ){
        if($object == 'Opportunity'){
        $post['user']=array('id'=>$fields['OwnerID']['value']);    
           
        }else{
    $post['owner_id']=$fields['OwnerID']['value'];
}  }
if( !empty($fields['CompanyId']['value']) ){
    $post['company']=array('id'=>$fields['CompanyId']['value']);
} 
if( !empty($fields['ContactId']['value']) ){
      if($object == 'Opportunity'){
       $post['contact']=array('id'=>$fields['ContactId']['value']);      
        }else{
    $post['contact_id']=$fields['ContactId']['value'];
} }

} }
//var_dump($post,$fields); die();
if(!empty($method)){
/// $post['contact_id']='6';   
 //$post['order_date']='2018-11-08T04:24:43.239Z';   
    //if line items
    $products=array();
if(!empty($post['xitem'])){
$item=array();
  foreach($post['xitem'] as $k=>$v){
    $k=ltrim($k,'item_');  
$item[$k]=$v;      
  }
  if(!empty($item['name'])){
  if(empty($item['sku'])){
  $item['sku']= preg_replace("/[^a-zA-Z]+/", "", $item['name']);   
  }  
  $products[$item['sku']]=$item;
  }
  unset($post['xitem']);
}

    if(!empty($meta['order_items'])  ){ //&& empty($id)
          $_order=self::$_order;

     $items=$_order->get_items();

      $order_items=array();
     if(is_array($items) && count($items)>0 ){
      foreach($items as $item_id=>$item){  
     $sku=$product=$title=''; $qty=$product_id=$unit_price=0;
    if(is_array($item)){
       $p_id= !empty($item['variation_id']) ? $item['variation_id'] : $item['product_id'];
       $product_id=$item['product_id'];
   $qty=$item['qty'];
    }else if(method_exists($item,'get_quantity')){
  $qty=$item->get_quantity();     
  $p_id=$item->get_variation_id();
  $product_id=$item->get_product_id();
  if(empty($p_id)){
  $p_id=$product_id;
  }
  $title=$item->get_name();
    }
        $line_desc=array();
    
        $product=wc_get_product($p_id);
        
        if(!$product){ continue; }

        $sku=$product->get_sku(); 
        $unit_price=$product->get_price();
        $unit_price=(float)$_order->get_item_total($item,false,true);
       if(empty($title)){
           $title=$product->get_title();
       }
/*  if(method_exists($product,'get_attributes')){
        $attrs=$product->get_attributes();
        
        foreach($attrs as $k=>$v){
        $attribute_key = str_replace( 'attribute_', '', $k );
        $display_key = wc_attribute_label( $attribute_key, $product );
        $value=$product->get_attribute($attribute_key);  
        $line_desc[]=$display_key.'='.$value;
        } }
 */
  
if(empty($sku)){ $sku=$product_id; }
$p_desc=$product->get_short_description();
$p_desc=str_replace(array("'",'"'),'', $p_desc);
if(strpos($p_desc,'<') !== false){
    $p_desc=$title;
}
$products[$p_id]=array('name'=>$title,'desc'=>$p_desc,'price'=>$unit_price,'sku'=>$sku,'quantity'=>$qty);

        
} }
}

$line_items=array();
foreach($products as $v){
         //add product here
     $p_search=$this->post_crm_arr('query','product',array('sku'=>$v['sku'] ));

      $extra[$v['sku'].'_search']=$p_search;
     $s_id=$this->get_id_search($p_search);
 $p_post=array('ProductName'=>$v['name'],'ProductPrice'=>$v['price'],'Sku'=>$v['sku']);
 if(isset($v['desc'])){
     $p_post['ShortDescription']=$v['desc'];
 }
 
 if(empty($s_id)){
 $p_res=$this->post_crm_arr('add','product',$p_post);
   $extra[$v['sku'].'_post']=$p_post;
   $extra[$v['sku'].'_add']=$p_res;
 $s_id=$this->get_id($p_res);
 }
   if(!empty($s_id)){
$line_items[]=array('quantity'=>$v['quantity'],'price'=>$v['price'],'product_id'=>$s_id);
// $item_post['notes']=$meta['pro_desc'];   //var_dump($item_post);  
  //post to crm    
 }
}

//var_dump($line_items,$products); die();
if(!empty($line_items)){
 $post['order_items']=$line_items;   
 $extra['order_items']=$line_items;
}
if($object == 'Contact' && $method == 'PATCH'){
$mask=array();
foreach($post as $k=>$v){
 $mask[]=$k;   
}
$post['update_mask']=$mask;
}
//var_dump($object); die();
 //$post['order_items']=json_decode('[{"description": "woo salesforce","price": "12","quantity": 2,"product_id": 0}]',1); 
// $post['addresses'][0]['region']='texas';   
//$post='{"opt_in_reason":"added from web"}';
//var_dump($post); die();
//$post=json_decode($post,1);      
//$res=$this->post_crm($object,'75','get',$post); var_dump($res); die();
//$post['custom_fields'][]=array('id'=>1,'content'=>$fields['_Customdrop']['value']);
$res=$this->post_crm($object,$id,$method,$post);
//var_dump($id,json_encode($post),$res,$method,$this->info['access_token']); die();
if(!empty($res['id'])){
 $crm_id=$res['id'];  
 if(!empty($payment)){
 if($object == 'Order'){
   if(!empty($res['total'])){ 
       $payment['payment_amount']=$res['total'];
   }      
 }
  $payment['charge_now']=false;   
  $res_pay=$this->post_crm('','orders/'.$crm_id.'/payments','post',$payment);
$extra['Payment Post']=$payment;  
$extra['Payment Response']=$res_pay;  
 } 
}else if(!empty($res['message'])){
$error=$res['message'];    
}else if(!empty($res['fault']['faultstring'])){
$error=$res['fault']['faultstring'];    
}

if(!empty($crm_id)){
    $id=$crm_id;
   // if(!empty($this->info['instance_url']) && $object !='Note'){
if( $object == 'Contact'){
$link='https://infusionsoft.app/contacts/list/all/contact/'.$id.'/activity';  
if(!empty($aff_id)){
$p_post=array('AffiliateId'=>$aff_id,'ContactId'=>$id,'IPAddress'=>$_SERVER['REMOTE_ADDR'],'DateSet'=>date('Y-m-d')); //,'Type'=>'0' 0=click , 1=permanent
$p_res=$this->post_crm_arr('add','Referral',$p_post);  
}

}
//https://fa122.infusionsoft.com/Company/manageCompany.jsp?view=edit&ID=5
//https://fa122.infusionsoft.com/Contact/manageContact.jsp?view=edit&ID=360775
//order = https://fa122.infusionsoft.com/Job/manageJob.jsp?view=edit&ID=11521

if(!empty($files) && function_exists('file_get_contents') ){
     $upload = wp_upload_dir();
    if(!is_array($files)){ $files=array();  }
    $file_res=array();
foreach($files as $file){
$file=str_replace($upload['baseurl'],$upload['basedir'],$file);
$file_arr=explode('/',$file);
$file_name=$file_arr[count($file_arr)-1];
$file_post=array('file_name'=>$file_name,'contact_id'=>$id,'file_association'=>'CONTACT','is_public'=>false,'file_data'=>base64_encode(file_get_contents($file)) );
$file_res[]=$this->post_crm('','files','post', $file_post); 
} 
 $extra['Add Files']=$file_res;
}

    //assign tag
if(!empty($meta['tag_check']) && !empty($meta['tags_valid'])){
        $tags=is_array($meta['tags_valid']) ? $meta['tags_valid'] : array();
      $tag_res=$this->post_crm('','contacts/'.$id.'/tags','post',array('tagIds'=>$tags) ); 

   $extra['Apply Tag']=$tag_res;
}
 
}
//die();
if(!empty($error)){
   $id=''; $status='';   
}
//echo json_encode($res); var_dump($post,$error);  die();
}

  if($debug){
  ?>
  <pre>
  <h3>Infusionsoft Information</h3>
  <p><?php echo json_encode($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php echo json_encode($post) ?></p>
  <h3>Infusionsoft response</h3>
  <p><?php echo json_encode($res) ?></p>
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

$note_post=array('title'=>$entry_note['Title'],'body'=>$entry_note['Body'],'contact_id'=>$id);
$note_user=$this->get_user_id($meta); 
if(!empty($note_user)){
$note_post['user_id']=$note_user; 
} 
//$note_post['user_id']='';   
$note_res=$this->post_crm('','notes','post',$note_post); 
  $infusionsoft_response['Entry Note']=$note_res;
  $extra['Note Title']=$entry_note['Title'];
  $extra['Note Body']=$entry_note['Body'];
  $extra['Note Id']=$note_res;   
  }  
 }
  return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$res,"extra"=>$extra);
}
public function verify_files($files,$old=array()){
        if(!is_array($files)){
        $files_temp=json_decode($files,true);
     if(is_array($files_temp)){
    $files=$files_temp;     
     }else if(!empty($files)){ //&& filter_var($files,FILTER_VALIDATE_URL)
      $files=array_map('trim',explode(',',$files));   
     }else{
      $files=array();    
     }   
    }
    if(is_array($files) && is_array($old) && !empty($old)){
   $files=array_merge($old,$files);     
    }
  return $files;  
}
public function get_aff_id($code){
    $res=$this->post_crm_arr('query','Affiliate',array('field'=>'AffCode','search'=>$code)); 
$id=$this->get_id_search($res);
return $id;
} 
 public function get_id_search($res){
 $id='';
   if(isset($res['params']['param']['value']['array']['data']['value'][0]['struct']['member']['value']['i4'])){
   $id=$res['params']['param']['value']['array']['data']['value'][0]['struct']['member']['value']['i4'];   
  }else if(isset($res['params']['param']['value']['array']['data']['value']['struct']['member']['value']['i4'])){
   $id=$res['params']['param']['value']['array']['data']['value']['struct']['member']['value']['i4'];   
  }
  
return $id;
}
public function get_id($res){
 $id='';
  if(isset($res['params']['param']['value']['i4'])){
   $id=$res['params']['param']['value']['i4'];
  }else if(isset($res['param']['value']['i4'])){
   $id=$res['param']['value']['i4'];
  }  

return $id;
}
public function get_user_id($meta){
$user_id=0;
if(!empty($meta['user'])){
  $user_id=$meta['user'];  
}
/*else if(!empty($this->meta['users'])){
  $user_id=key($this->meta['users']);  
}else if(!empty($this->info['id']) ){
  $this->meta['users']=$users=$this->get_users();
  $this->update_info( array("meta"=> $this->meta),$this->info['id']); 
  if(is_array($users)){
      $user_id=key($users); 
  }  
}*/     
return $user_id;  
}

public function get_entry($object,$id){

$fields=$this->get_crm_fields($object);
   
$arr=$this->post_crm($object,$id,'get'); 
$entry=array();
if(!empty($arr)){
$temp=array();
    foreach($arr as $k=>$v){
if(!empty($v) && in_array($k,array('email_addresses','addresses','phone_numbers','social_accounts'))){
    $op=array();
    foreach($v as $vv){
     $field='';
       if(!empty($vv['field'])){ $field=$vv['field']; }else if(!empty($vv['type'])){ $field=$vv['type']; }
   $op[$field]=$vv;    
    }
 $temp[$k]=$op;      
}else if($k == 'custom_fields'){
    if(is_array($v)){
    $op=array();
    foreach($v as $vv){
    $op[$vv['id']]=$vv['content'];    
    }
 $temp[$k]=$op; 
    }
}else{
  $temp[$k]=$v;  
}   
}
if(!empty($fields)){
  foreach($fields as $k=>$v){ 
      if(!empty($v['group'])){
      if( in_array($v['group'],array('email_addresses','addresses','phone_numbers','social_accounts'))){
   $field='';
   if(!empty($v['field'])){ $field=$v['field']; }else if(!empty($v['type'])){ $field=$v['type']; }   
  if(isset($temp[$v['group']][$field])){
   $entry[$k]=$temp[$v['group']][$field][$v['id']]; 
     
  } }else if (isset($temp[$k][$v['id']]) ){
    $entry[$k]=$temp[$k][$v['id']];  
  }
      }else if( !empty($v['is_custom'])){ 
      if (isset($temp['custom_fields'][$v['id']]) ){
       $entry[$k]=$temp['custom_fields'][$v['id']];   
      }
  }else{
      $entry[$k]=$temp[$v['id']];      
      } }  
}
}
  
  return $entry;     
  }
public function create_fields_section($fields){
$arr=array(); 
if(!isset($fields['object'])){
    $objects=array(''=>'Select Object','Contact'=>'Contact','Company'=>'Company','Order'=>'Order');
    if(is_array($objects_sf)){
    $objects=array_merge($objects,$objects_sf);
    }
 $arr['gen_sel']['object']=array('label'=>'Select Object','options'=>$objects,'is_ajax'=>true,'req'=>true);   
}else if(!empty($fields['object'])){

  if(isset($fields['fields']) ){
    $crm_fields=$this->get_crm_fields($fields['object']); 

    if(!is_array($crm_fields)){
        $crm_fields=array();
    }
    $add_fields=array();
    if(is_array($fields['fields']) && count($fields['fields'])>0){
        foreach($fields['fields'] as $k=>$v){
           $found=false; 
                foreach($crm_fields as $crm_key=>$val){
                    if(strpos($val['label'],$v)!== false){
                        $found=true; break;
                }
            }
         if(!$found){
       $add_fields[$k]=$v;      
         }   
        }
    }
 $arr['fields']=$add_fields;   
/// $arr['gen_sel']=array('group'=>array('label'=>'Select Custom Field Header','options'=>$groups,'is_ajax'=>false,'req'=>true));   
}
}
    

return $arr;  
}
  /**
  * Posts data to infusionsoft, Get New access token on expiration message from infusionsoft
  * @param  string $path infusionsoft path 
  * @param  string $method CURL method 
  * @param  array $body (optional) if you want to post data
  * @return array Infusionsoft Response array
  */
public  function post_crm_arr($service,$table,$extra=''){
  $info=$this->info;  
  $get_token=false;
    if(!function_exists('simplexml_load_string')){
      return array();
  }    
/*    $xml_arr=array('methodName'=>'DataService.'.$service);
    $pars=array(array('type'=>'string', 'value'=>'privateKey'),array('type'=>'string', 'value'=>$table),array('type'=>'int', 'value'=>'10'),array('type'=>'int', 'value'=>'0'),array('type'=>'struct', 'value'=>'%','name'=>'Id'),array('type'=>'array', 'value'=>'privateKey') );  
  */
  $table_s=$table;
  switch($table){
      case'fields': $table_s='DataFormField'; break;
      case'Note': $table_s= 'ContactAction'; break;
      case'Order': $table_s='Job'; break;
      case'product': $table_s='Product'; break;
      case'tags': $table_s='ContactGroup'; break;
      case'tabs': $table_s='DataFormTab'; break;
  }

  switch($service){
      case'query':
      $search_key='Id';
      $search='%';
      if(is_array($extra)){
          if(isset($extra['field'])){ //get fields
       $search_key=$extra['field'];   
       $search=$extra['search'];   
      }else if(isset($extra['sku'])){ //search by sku
        $search_key='Sku';
        $search=$extra['sku'];  
      }else if(isset($extra['id'])){ //get entry
        $search=$extra['id'];

      } }
      
if(in_array($table,array('fields','tabs')) ){
          
       $search_key='FormId';   
      $search='-1';
      if($extra == 'Company'){
          $search='-6';
      }else if($extra == 'Order'){
       $search='-9';   
      }
      }
      
       $body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>DataService.query</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>$table_s</string></value>
    </param>
    <param>
      <value><int>2000</int></value>
    </param>
    <param>
      <value><int>0</int></value>
    </param>
    <param>
      <value><struct>
        <member>
        <name>$search_key</name>
          <value><string>$search</string></value>
        </member>
      </struct></value>
    </param>
    <param>
      <value><array>
        <data>
          <value><string>Id</string></value>";
            if($table == 'fields'){
         $body.="<value><string>Name</string></value>
          <value><string>Label</string></value>
          <value><string>Values</string></value>
          <value><string>DefaultValue</string></value>";
      }else if($table == 'User'){
        $body.="<value><string>FirstName</string></value>
          <value><string>LastName</string></value>      
          <value><string>Email</string></value>";      
      }else if($table == 'LeadSource'){
        $body.="<value><string>Name</string></value>
          <value><string>Description</string></value>      
          <value><string>EndDate</string></value>";      
      }else if($table == 'tags'){
        $body.="<value><string>GroupName</string></value>";      
      }else if(isset($extra['id'])){
          unset($extra['id']);
          foreach($extra as $k=>$v){
         $body.="<value><string>$v</string></value>";       
          }
          
      }
        $body.="</data>
      </array></value>
    </param>
    <param>
      <value><string>Id</string></value>
      </param>
    <param>
      <value><boolean>0</boolean></value>
    </param>
  </params>
</methodCall>";
break;
case'delete':
  $id='';
    if(isset($extra['id'])){
     $id=$extra['id'];
    }
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>DataService.delete</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>$table_s</string></value>
    </param>
    <param>
      <value><int>$id</int></value>
    </param>
  </params>
</methodCall>";

break;
case'apply_tag': 
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>ContactService.addToGroup</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><int>$table</int></value>
    </param>
<param>
<value><int>$extra</int></value>
</param>
  </params>
</methodCall>";
break;
case'add_file': 
$file_arr=explode('/',$extra);
$file_name=$file_arr[count($file_arr)-1];
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>FileService.uploadFile</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
     <param>
      <value><int>$table</int></value>
    </param>
 <param>
 <value><string>".htmlentities($file_name)."</string></value>
 </param>
<param>
<value><string>".htmlentities(base64_encode(file_get_contents($extra)))."</string></value>
</param>
  </params>
</methodCall>";

break;

case'create_field':
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>DataService.addCustomField</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>$table_s</string></value>
    </param>
    <param>
      <value><string>".htmlentities($extra['label'])."</string></value>
    </param>
    <param>
      <value><string>".htmlentities($extra['type'])."</string></value>
    </param>
    <param>
      <value><int>".intval($extra['group'])."</int></value>
    </param>
  </params>
</methodCall>";
break;
case'optin':
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
<methodName>APIEmailService.optIn</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>bioinfo35@gmail.com</string></value>
    </param>
    <param>
      <value><string>permission</string></value>
    </param>
  </params>
</methodCall>";
break;
case'line_items':

$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>InvoiceService.addOrderItem</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><int>$table</int></value>
    </param>
    <param>
      <value><int>".(int)$extra['product_id']."</int></value>
    </param>
    <param>
      <value><int>4</int></value>
    </param>
    <param>
      <value><double>".$extra['price']."</double></value>
    </param>
    <param>
      <value><int>".(int)$extra['qty']."</int></value>
    </param>
    <param>
      <value><string>".$extra['description']."</string></value>
    </param>
    <param>
      <value><string>".$extra['notes']."</string></value>
    </param>
  </params>
</methodCall>";
break;
default:
$body="<?xml version='1.0' encoding='UTF-8'?>
<methodCall>
  <methodName>DataService.$service</methodName>
  <params>
    <param>
      <value><string>privateKey</string></value>
    </param>
    <param>
      <value><string>$table_s</string></value>
    </param>";
    if($service == 'update'){
    $id='';
    if(!empty($extra['Id']['val'])){
     $id=$extra['Id']['val'];
        unset($extra['Id']);
    }
    
  $body.=" <param><value><int>$id</int></value></param>";     
    }
    
    $body.="<param>
      <value><struct>";
    if(is_array($extra)){    
     foreach($extra as $k=>$v){
         $type='string';
         if(is_array($v)){   
     if($v['type'] == 'date'){ $type='dateTime.iso8601';
     $v['val']=date('Ymd\TH:i:s',strtotime($v['val']));
     }else if($v['type'] == 'number'){ $type='i4'; $v['val']=(int)$v['val']; }
     else if($v['type'] == 'bool'){ $type='i4'; $v['val']=!empty($v['val']) ? '1' : '0'; }
     $v=$v['val'];
     }
        $body.="<member><name>$k</name>
          <value><$type>".htmlentities($v)."</$type></value>
        </member>";
    } }
     
      $body.="</struct></value>
    </param>
  </params>
</methodCall>";
//  header('content-type: text/xml'); 
//echo $body;
 //var_dump($extra);
//  die();
break;

  }

   $token_time=$this->post('token_time',$info);
   $time=time();
   $expiry=$token_time+86200;   //86400

   if($expiry<$time){
    $info=$this->refresh_token(); 
    
   }
      if(!empty($info['access_token'])){
  $dev_key=$info['access_token'];      
    }   
  
  $url=$this->url;
  $xml=$this->post_crm_xml($body); 

  $arr=array();
     if(strpos($xml,"<?xml") !== false){
        $xml=simplexml_load_string($xml, NULL, LIBXML_NOCDATA);
        $json=json_encode($xml);
        $arr=json_decode($json,true);
    }


  return $arr;   
  }
  /**
  * Posts data to infusionsoft
  * @param  string $dev_key Slesforce Access Token 
  * @param  string $path Infusionsoft Path 
  * @param  string $method CURL method 
  * @param  string $body (optional) if you want to post data 
  * @return string Infusionsoft Response JSON
  */
public function post_crm_xml($body=""){
  

      $header=array();   //'content-type'=>'application/x-www-form-urlencoded'
      
   $pars=array();
  if(is_array($body) && isset($body['grant_type'])){ //getting access token
  
  if($body['grant_type'] == 'access_token'){ //refreshing token
  $client=$this->client_info();
  $header['Authorization']=' Bearer ' .base64_encode($client['client_id'].':'.$client['client_secret']); 
  }

  $path=$this->url.'token';
  }else{ //api requests
$key='';
if(!empty($this->info['access_token'])){
    $key=$this->info['access_token'];
}
$path=$this->url.'crm/xmlrpc/v1?access_token='.$key;
  $header['Content-Type']='text/xml';      
    }
    // var_dump($header,$body,$path); die();
$args=array(
  'method' => 'POST',
  'timeout' => $this->timeout,
  'headers' => $header,
 'body' => $body
  );


  $response = wp_remote_post( $path, $args); 
  
//var_dump($response,$path,$method,$body,$args); echo '<hr>'; die();
  return !is_wp_error($response) && isset($response['body']) ? $response['body'] : "";
}
public function key_val_arr($res){ 
 $post=array();

   if(isset($res['params']['param']['value']['array']['data']['value'])){
   $arr=$res['params']['param']['value']['array']['data']['value'];
   if(isset($arr['struct'])){ $arr=array($arr);    }   

 if(is_array($arr) && count($arr)>0){
     foreach($arr as $k=>$v){
         if(isset($v['struct']['member']) && is_array($v['struct']['member'])){ 
             $field=array();
             if(isset($v['struct']['member']['name'])){
                 $v['struct']['member']=array($v['struct']['member']);
             }
         foreach($v['struct']['member'] as $val){
             if(!empty($val['name'])){
    $field[$val['name']]=is_array($val['value']) && isset($val['value']['i4']) ? $val['value']['i4'] : $val['value'];    
    }  
         }
      $post[]=$field;   
         }
       
     }
 }
   }

return $post;
} 
public function create_field($field){

$name=isset($field['name']) ? $field['name'] : '';
$label=isset($field['label']) ? $field['label'] : '';
$type=isset($field['type']) && $field['type'] == 'text' ? 'Text' : 'TextArea';
$object=isset($field['object']) ? $field['object'] : '';
$group=isset($field['group']) ? $field['group'] : '';

$error='Unknow error';
if(!empty($label) && !empty($type) && !empty($object)){

$body=array('label'=>$label,'field_type'=>$type);

$arr=$this->post_crm($object,'model/customFields','post',$body); 
    $error='ok';
if(!empty($arr['message']) && empty($arr['id']) ){ 
 $error=$arr['message'];    
} }
return $error;    
}  
public function post_crm($object,$path,$method,$body=""){
      $header=array();   //'content-type'=>'application/x-www-form-urlencoded'

  if(is_array($body) && isset($body['grant_type'])){ //getting access token  
  if($body['grant_type'] == 'access_token'){ //refreshing token
  $client=$this->client_info();
  $header['Authorization']=' Bearer ' .base64_encode($client['client_id'].':'.$client['client_secret']); 
  }

  $url=$this->url.'token';
  }else{ //api requests
    $token_time=!empty($this->info['token_time']) ? $this->info['token_time'] : 0;
   $time=time();
   $expiry=$token_time+86200;   //86400
  /// $expiry=0;
   if($expiry<$time || empty($this->info['access_token']) ){
    $this->refresh_token(); 
   }
$key='';
if(!empty($this->info['access_token'])){
    $key=$this->info['access_token'];
}

$header['Authorization']='Bearer ' . $key; 
$header['Host']='api.infusionsoft.com'; 
//$header['Accept']='application/json, */*'; 
$url=$this->url.'crm/rest/v1';
$objects=array('Contact'=>'contacts','Company'=>'companies','Order'=>'orders','Note'=>'notes','Opportunity'=>'opportunities');

if(isset($objects[$object])){
    $url.='/'.$objects[$object];
}else{
   $url.='/'.$object; 
}  
if(!empty($path)){
    $url.='/'.$path;   
}

$header['Content-Type']='application/json';      
if(!empty($body) && is_array($body) ){ 
if($method == 'get'){
//$path.='?'.http_build_query($body);
//$body='';
}else{
if(isset($body['update_mask'])){
    $url.='?'.http_build_query(array('update_mask'=>implode(',',$body['update_mask'])));
    unset($body['update_mask']);
}
 $body=json_encode($body); }

}
  }
//var_dump($body,$url);
$args=array(
  'method' => strtoupper($method),
  'timeout' => $this->timeout,
  'headers' => $header,
 'body' => $body
  );
$response = wp_remote_post( $url, $args); 
$body='';
if(is_wp_error($response)){
    $body=$response->get_error_message();
}else{
    $body=wp_remote_retrieve_body( $response ); 
    $temp=json_decode($body,true);
    if(!empty($temp)){ $body=$temp; unset($temp); }
}

///echo $response['body'].$url.'---'.$method.'------'.json_encode($body); die();  
//var_dump($response,$path,$method,$body,$args,$this->info); echo '<hr>'; die();
  return $body;
}
public function get_country_code($country){
 
 if(strlen($country) == 2){
$json='{"AF":"AFG","AX":"ALA","AL":"ALB","DZ":"DZA","AS":"ASM","AD":"AND","AO":"AGO","AI":"AIA","AQ":"ATA","AG":"ATG","AR":"ARG","AM":"ARM","AW":"ABW","AU":"AUS","AT":"AUT","AZ":"AZE","BS":"BHS","BH":"BHR","BD":"BGD","BB":"BRB","BY":"BLR","BE":"BEL","BZ":"BLZ","BJ":"BEN","BM":"BMU","BT":"BTN","BO":"BOL","BQ":"BES","BA":"BIH","BW":"BWA","BV":"BVT","BR":"BRA","IO":"IOT","BN":"BRN","BG":"BGR","BF":"BFA","BI":"BDI","CV":"CPV","KH":"KHM","CM":"CMR","CA":"CAN","KY":"CYM","CF":"CAF","TD":"TCD","CL":"CHL","CN":"CHN","CX":"CXR","CC":"CCK","CO":"COL","KM":"COM","CG":"COG","CD":"COD","CK":"COK","CR":"CRI","CI":"CIV","HR":"HRV","CU":"CUB","CW":"CUW","CY":"CYP","CZ":"CZE","DK":"DNK","DJ":"DJI","DM":"DMA","DO":"DOM","EC":"ECU","EG":"EGY","SV":"SLV","GQ":"GNQ","ER":"ERI","EE":"EST","SZ":"SWZ","ET":"ETH","FK":"FLK","FO":"FRO","FJ":"FJI","FI":"FIN","FR":"FRA","GF":"GUF","PF":"PYF","TF":"ATF","GA":"GAB","GM":"GMB","GE":"GEO","DE":"DEU","GH":"GHA","GI":"GIB","GR":"GRC","GL":"GRL","GD":"GRD","GP":"GLP","GU":"GUM","GT":"GTM","GG":"GGY","GN":"GIN","GW":"GNB","GY":"GUY","HT":"HTI","HM":"HMD","VA":"VAT","HN":"HND","HK":"HKG","HU":"HUN","IS":"ISL","IN":"IND","ID":"IDN","IR":"IRN","IQ":"IRQ","IE":"IRL","IM":"IMN","IL":"ISR","IT":"ITA","JM":"JAM","JP":"JPN","JE":"JEY","JO":"JOR","KZ":"KAZ","KE":"KEN","KI":"KIR","KP":"PRK","KR":"KOR","KW":"KWT","KG":"KGZ","LA":"LAO","LV":"LVA","LB":"LBN","LS":"LSO","LR":"LBR","LY":"LBY","LI":"LIE","LT":"LTU","LU":"LUX","MO":"MAC","MG":"MDG","MW":"MWI","MY":"MYS","MV":"MDV","ML":"MLI","MT":"MLT","MH":"MHL","MQ":"MTQ","MR":"MRT","MU":"MUS","YT":"MYT","MX":"MEX","FM":"FSM","MD":"MDA","MC":"MCO","MN":"MNG","ME":"MNE","MS":"MSR","MA":"MAR","MZ":"MOZ","MM":"MMR","NA":"NAM","NR":"NRU","NP":"NPL","NL":"NLD","NC":"NCL","NZ":"NZL","NI":"NIC","NE":"NER","NG":"NGA","NU":"NIU","NF":"NFK","MK":"MKD","MP":"MNP","NO":"NOR","OM":"OMN","PK":"PAK","PW":"PLW","PS":"PSE","PA":"PAN","PG":"PNG","PY":"PRY","PE":"PER","PH":"PHL","PN":"PCN","PL":"POL","PT":"PRT","PR":"PRI","QA":"QAT","RE":"REU","RO":"ROU","RU":"RUS","RW":"RWA","BL":"BLM","SH":"SHN","KN":"KNA","LC":"LCA","MF":"MAF","PM":"SPM","VC":"VCT","WS":"WSM","SM":"SMR","ST":"STP","SA":"SAU","SN":"SEN","RS":"SRB","SC":"SYC","SL":"SLE","SG":"SGP","SX":"SXM","SK":"SVK","SI":"SVN","SB":"SLB","SO":"SOM","ZA":"ZAF","GS":"SGS","SS":"SSD","ES":"ESP","LK":"LKA","SD":"SDN","SR":"SUR","SJ":"SJM","SE":"SWE","CH":"CHE","SY":"SYR","TW":"TWN","TJ":"TJK","TZ":"TZA","TH":"THA","TL":"TLS","TG":"TGO","TK":"TKL","TO":"TON","TT":"TTO","TN":"TUN","TR":"TUR","TM":"TKM","TC":"TCA","TV":"TUV","UG":"UGA","UA":"UKR","AE":"ARE","GB":"GBR","US":"USA","UM":"UMI","UY":"URY","UZ":"UZB","VU":"VUT","VE":"VEN","VN":"VNM","VG":"VGB","VI":"VIR","WF":"WLF","EH":"ESH","YE":"YEM","ZM":"ZMB","ZW":"ZWE"}';
$arr=json_decode($json,true);
$country=strtoupper($country);
if(isset($arr[$country])){
 $country=$arr[$country];   
}
return $country;        
 }
$json = <<<EOD
{"AFG":"Afghanistan","ALA":"\u00c5land Islands","ALB":"Albania","DZA":"Algeria","ASM":"American Samoa","AND":"Andorra","AGO":"Angola","AIA":"Anguilla","ATA":"Antarctica","ATG":"Antigua and Barbuda","ARG":"Argentina","ARM":"Armenia","ABW":"Aruba","AUS":"Australia","AUT":"Austria","AZE":"Azerbaijan","BHS":"Bahamas","BHR":"Bahrain","BGD":"Bangladesh","BRB":"Barbados","BLR":"Belarus","BEL":"Belgium","BLZ":"Belize","BEN":"Benin","BMU":"Bermuda","BTN":"Bhutan","BOL":"Bolivia (Plurinational State of)","BES":"Bonaire, Sint Eustatius and Saba","BIH":"Bosnia and Herzegovina","BWA":"Botswana","BVT":"Bouvet Island","BRA":"Brazil","IOT":"British Indian Ocean Territory","BRN":"Brunei Darussalam","BGR":"Bulgaria","BFA":"Burkina Faso","BDI":"Burundi","CPV":"Cape Verde","KHM":"Cambodia","CMR":"Cameroon","CAN":"Canada","CYM":"Cayman Islands","CAF":"Central African Republic","TCD":"Chad","CHL":"Chile","CHN":"China","CXR":"Christmas Island","CCK":"Cocos (Keeling) Islands","COL":"Colombia","COM":"Comoros","COG":"Congo, Republic of the","COD":"Congo, Democratic Republic of the","COK":"Cook Islands","CRI":"Costa Rica","CIV":"C\u00f4te d'Ivoire","HRV":"Croatia","CUB":"Cuba","CUW":"Cura\u00e7ao","CYP":"Cyprus","CZE":"Czech Republic","DNK":"Denmark","DJI":"Djibouti","DMA":"Dominica","DOM":"Dominican Republic","ECU":"Ecuador","EGY":"Egypt","SLV":"El Salvador","GNQ":"Equatorial Guinea","ERI":"Eritrea","EST":"Estonia","SWZ":"Swaziland","ETH":"Ethiopia","FLK":"Falkland Islands (Malvinas)","FRO":"Faroe Islands","FJI":"Fiji","FIN":"Finland","FRA":"France","GUF":"French Guiana","PYF":"French Polynesia","ATF":"French Southern Territories","GAB":"Gabon","GMB":"Gambia","GEO":"Georgia","DEU":"Germany","GHA":"Ghana","GIB":"Gibraltar","GRC":"Greece","GRL":"Greenland","GRD":"Grenada","GLP":"Guadeloupe","GUM":"Guam","GTM":"Guatemala","GGY":"Guernsey","GIN":"Guinea","GNB":"Guinea-Bissau","GUY":"Guyana","HTI":"Haiti","HMD":"Heard Island and McDonald Islands","VAT":"Vatican City","HND":"Honduras","HKG":"Hong Kong","HUN":"Hungary","ISL":"Iceland","IND":"India","IDN":"Indonesia","IRN":"Iran (Islamic Republic of)","IRQ":"Iraq","IRL":"Ireland","IMN":"Isle of Man","ISR":"Israel","ITA":"Italy","JAM":"Jamaica","JPN":"Japan","JEY":"Jersey","JOR":"Jordan","KAZ":"Kazakhstan","KEN":"Kenya","KIR":"Kiribati","PRK":"North Korea","KOR":"South Korea","KWT":"Kuwait","KGZ":"Kyrgyzstan","LAO":"Laos","LVA":"Latvia","LBN":"Lebanon","LSO":"Lesotho","LBR":"Liberia","LBY":"Libya","LIE":"Liechtenstein","LTU":"Lithuania","LUX":"Luxembourg","MAC":"Macao","MKD":"Macedonia (the former Yugoslav Republic of)","MDG":"Madagascar","MWI":"Malawi","MYS":"Malaysia","MDV":"Maldives","MLI":"Mali","MLT":"Malta","MHL":"Marshall Islands","MTQ":"Martinique","MRT":"Mauritania","MUS":"Mauritius","MYT":"Mayotte","MEX":"Mexico","FSM":"Micronesia (Federated States of)","MDA":"Moldova (Republic of)","MCO":"Monaco","MNG":"Mongolia","MNE":"Montenegro","MSR":"Montserrat","MAR":"Morocco","MOZ":"Mozambique","MMR":"Myanmar","NAM":"Namibia","NRU":"Nauru","NPL":"Nepal","NLD":"Netherlands","NCL":"New Caledonia","NZL":"New Zealand","NIC":"Nicaragua","NER":"Niger","NGA":"Nigeria","NIU":"Niue","NFK":"Norfolk Island","MNP":"Northern Mariana Islands","NOR":"Norway","OMN":"Oman","PAK":"Pakistan","PLW":"Palau","PSE":"Palestine, State of","PAN":"Panama","PNG":"Papua New Guinea","PRY":"Paraguay","PER":"Peru","PHL":"Philippines","PCN":"Pitcairn","POL":"Poland","PRT":"Portugal","PRI":"Puerto Rico","QAT":"Qatar","REU":"R\u00e9union","ROU":"Romania","RUS":"Russian Federation","RWA":"Rwanda","BLM":"Saint Barth\u00e9lemy","SHN":"Saint Helena, Ascension and Tristan da Cunha","KNA":"Saint Kitts and Nevis","LCA":"Saint Lucia","MAF":"Saint Martin (French part)","SPM":"Saint Pierre and Miquelon","VCT":"Saint Vincent and the Grenadines","WSM":"Samoa","SMR":"San Marino","STP":"Sao Tome and Principe","SAU":"Saudi Arabia","SEN":"Senegal","SRB":"Serbia","SYC":"Seychelles","SLE":"Sierra Leone","SGP":"Singapore","SXM":"Sint Maarten (Dutch part)","SVK":"Slovakia","SVN":"Slovenia","SLB":"Solomon Islands","SOM":"Somalia","ZAF":"South Africa","SGS":"South Georgia and the South Sandwich Islands","SSD":"Sudan, South","ESP":"Spain","LKA":"Sri Lanka","SDN":"Sudan","SUR":"Suriname","SJM":"Svalbard and Jan Mayen","SWE":"Sweden","CHE":"Switzerland","SYR":"Syrian Arab Republic","TWN":"Taiwan, Province of China","TJK":"Tajikistan","TZA":"Tanzania, United Republic of","THA":"Thailand","TLS":"East Timor","TGO":"Togo","TKL":"Tokelau","TON":"Tonga","TTO":"Trinidad and Tobago","TUN":"Tunisia","TUR":"Turkey","TKM":"Turkmenistan","TCA":"Turks and Caicos Islands","TUV":"Tuvalu","UGA":"Uganda","UKR":"Ukraine","ARE":"United Arab Emirates","GBR":"United Kingdom of Great Britain and Northern Ireland","USA":"United States of America","UMI":"United States Minor Outlying Islands","URY":"Uruguay","UZB":"Uzbekistan","VUT":"Vanuatu","VEN":"Venezuela (Bolivarian Republic of)","VNM":"Viet Nam","VGB":"Virgin Islands, British","VIR":"Virgin Islands, U.S.","WLF":"Wallis and Futuna","ESH":"Western Sahara","YEM":"Yemen","ZMB":"Zambia","ZWE":"Zimbabwe","XKX":"Kosovo"}
EOD;
$arr=json_decode($json,true);
$code='';
if(!empty($country)){
foreach($arr as $k=>$v){
        if(strpos($v,$country) !== false){
         $code=$k; break;   
        }
}}
return $code; 
}
public function get_state($code){
    
    $json='{ "AL": "Alabama", "AK": "Alaska", "AS": "American Samoa", "AZ": "Arizona", "AR": "Arkansas", "CA": "California", "CO": "Colorado", "CT": "Connecticut", "DE": "Delaware", "DC": "District Of Columbia", "FM": "Federated States Of Micronesia", "FL": "Florida", "GA": "Georgia", "GU": "Guam", "HI": "Hawaii", "ID": "Idaho", "IL": "Illinois", "IN": "Indiana", "IA": "Iowa", "KS": "Kansas", "KY": "Kentucky", "LA": "Louisiana", "ME": "Maine", "MH": "Marshall Islands", "MD": "Maryland", "MA": "Massachusetts", "MI": "Michigan", "MN": "Minnesota", "MS": "Mississippi", "MO": "Missouri", "MT": "Montana", "NE": "Nebraska", "NV": "Nevada", "NH": "New Hampshire", "NJ": "New Jersey", "NM": "New Mexico", "NY": "New York", "NC": "North Carolina", "ND": "North Dakota", "MP": "Northern Mariana Islands", "OH": "Ohio", "OK": "Oklahoma", "OR": "Oregon", "PW": "Palau", "PA": "Pennsylvania", "PR": "Puerto Rico", "RI": "Rhode Island", "SC": "South Carolina", "SD": "South Dakota", "TN": "Tennessee", "TX": "Texas", "UT": "Utah", "VT": "Vermont", "VI": "Virgin Islands", "VA": "Virginia", "WA": "Washington", "WV": "West Virginia", "WI": "Wisconsin", "WY": "Wyoming" }';
    $arr=json_decode($json,1);
  if(isset($arr[strtoupper($code)])){ $code=$arr[strtoupper($code)]; }
 return ucfirst($code);   
}
  
  
}  
}
?>