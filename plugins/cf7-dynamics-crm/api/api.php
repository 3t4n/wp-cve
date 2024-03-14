<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxcf_dynamics_api')){
    
class vxcf_dynamics_api{
  
    public $info='' ; // info
    public $url='';
    public $id='';
    public $timeout= "30";
    public static $feeds_res;

function __construct($info) {
     
    if(isset($info['data'])){ 
       $this->info= $info['data'];
    if(!empty($this->info['url'])){
     $this->url=trailingslashit($this->info['url']).'api/data/v8.2/';   
    } }
        if(isset($info['id'])){ 
       $this->id= $info['id'];
    }

}
public function handle_code(){
      $info=$this->info;
$client=$this->client_info();
  $res=$token=array();
  if(isset($_REQUEST['code'])){
  $code=vxcf_dynamics::post('code');   
  if(!empty($code)){
  $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],'redirect_uri'=>$client['call_back'],'resource'=>$info['url'],"grant_type"=>"authorization_code","code"=>$code);
  $token=$this->post_crm($client['token'],"post",$body,'');

  }
  }
  if(!is_array($token)){ $token=array(); }
   if(isset($_REQUEST['error_description'])){
   $token['error_description']=vxcf_dynamics::post('error_description');   
  }
  $info['msg']=$info['class']='';
  if(isset($token['refresh_token'])){
    $info['issued_at']=time();
$info["class"]='updated';
  }else if(isset($token['error_description'])){
    $info['msg']=$token['error_description'];
  $info["class"]='error';    
  }
  $info['access_token']=isset($token['access_token']) ? $token['access_token'] : '';
  $info['refresh_token']=isset($token['refresh_token']) ? $token['refresh_token'] : '';
  $info['client_id']=$client['client_id'];
if(!empty($this->id)){
 vxcf_dynamics::update_info(array('data'=>$info),$this->id);
}
//var_dump($token); die();
return $info; 
}
public function client_info(){
      $info=$this->info;
  $client_id= 'dd889b23-f757-45cc-b6aa-a18783d0a25f';
  $client_secret='FvutEuPmOBmBaTuVCBGprFnMlivVNnvRM17s47dGGTg=';
  $call_back="https://www.crmperks.com/google_auth/";
 $login_url='https://login.microsoftonline.com/common/oauth2/authorize';
 $auth_url='https://login.windows.net/common/oauth2/token';
  //custom app
  if(is_array($info)){
      if(!empty($info['custom_app']) && !empty($info['app_id'])  && !empty($info['app_url'])){
     $client_id=$info['app_id'];     
     $client_secret=isset($info['app_secret']) ? $info['app_secret']: '';     
     $call_back=$info['app_url'];     
      }
  if(!empty($info['auth'])){
   $call_back1=trailingslashit($info['auth']);   
   $login_url=$call_back1.'authorize';
   $auth_url=$call_back1.'token';
  }    
  }
  return array("client_id"=>$client_id,"client_secret"=>$client_secret,"call_back"=>$call_back,'login'=>$login_url,'token'=>$auth_url);
  }
public function get_token($refresh=false){
$info=$this->info;
if(isset($info['issued_at']) ){
  $time=time();
  $issued=(int)$info['issued_at']+3550;
  if($issued<$time){
 $refresh=true;     
  }  
}
if($refresh && !empty($info['refresh_token'])){
    $client=$this->client_info(); 
     $body=array("client_id"=>$client['client_id'],"client_secret"=>$client['client_secret'],'refresh_token'=>$info['refresh_token'],'resource'=>$info['url'],"grant_type"=>'refresh_token');
 $token=$this->post_crm($client['token'],'post',$body,'');  
  if(isset($token['access_token'])){
$info['access_token']=$token['access_token'];
    $info['issued_at']=time();
       if(!empty($token['refresh_token'])){
$info['refresh_token']=$token['refresh_token'];
      }
$info["class"]='updated';
  }else if(isset($token['error_description'])){
    $info['msg']=$token['error_description'];
  $info["class"]='error';    
  }
if(!empty($this->id)){
    $this->info=$info;
 vxcf_dynamics::update_info(array('data'=>$info),$this->id);
}
}
$token='';
if(isset($info['access_token'])){
     $token=$info['access_token'];
}
return $token;
}


public function get_crm_objects(){

$path='EntityDefinitions?$select=LogicalName,IsChildEntity,IsActivityParty,IsActivity,IsAIRUpdated,IsCustomizable,IsRenameable,IsAvailableOffline,IsManaged,IsPrivate,IsRenameable,IsLogicalEntity,IsCustomEntity,CanCreateForms,CanCreateAttributes,CanBeRelatedEntityInRelationship,IsCustomizable,DisplayCollectionName,LogicalCollectionName';
   // $path.='&$filter=OwnershipType eq Microsoft.Dynamics.CRM.OwnershipTypes\'UserOwned\'';
$arr=$this->post_crm($path,'get');
//echo json_encode($arr); 
//var_dump($arr); die(); 
$res=$normal=array(); 
if(!empty($arr['value'])){
    $n=0;
foreach( $arr['value'] as $v){
    if($v['IsPrivate'] ){ continue; }
  //  if($v['OwnershipType'] != 'UserOwned'){ continue; }
 //if( ($v['IsChildEntity'] == false && $v['IsActivityParty'] === true && $v['IsActivity'] === false && $v['IsAIRUpdated'] == true) || $v['IsManaged'] === false ){ 
 // if( ($v['IsCustomEntity'] == false && $v['IsActivityParty'] == true ) || $v['IsCustomEntity'] == true ){   
// if( ($v['IsCustomEntity'] == false && $v['IsActivity'] == false && $v['IsChildEntity'] == false && $v['IsAvailableOffline'] == true && $v['IsAIRUpdated'] == true) || $v['IsCustomEntity'] == true ){  
 if( ($v['IsCustomEntity'] == false && $v['IsActivity'] == false && $v['IsChildEntity'] == false && $v['IsAvailableOffline'] == true ) || $v['IsCustomEntity'] == true ){  
   //  var_dump($v);
 ///$v['IsCustomEntity'] === false && empty($v['ActivityTypeMask']) &&
  //  var_dump($v['DisplayName']['UserLocalizedLabel']['Label']); 
  if(!empty($v['DisplayCollectionName']['UserLocalizedLabel']['Label'])){
      $label=$v['DisplayCollectionName']['UserLocalizedLabel']['Label'];
      if($v['IsCustomEntity'] == false){
      $normal[$v['LogicalName']]=array('name'=>$v['LogicalCollectionName'],'label'=>$label);    
      }else{
     $l=substr($label,0,1);     
 $res[$l][$v['LogicalName']]=array('name'=>$v['LogicalCollectionName'],'label'=>$label);  
  } }
  $n++;
/// echo $n.'-'.$label.'-'.$v['IsAvailableOffline'].'-'.$v['IsManaged'].'<hr>';
 }
}
ksort($res); 
foreach($res as $v){
    $normal+=$v;
}

//echo json_encode($res);

$res=$normal;   //var_dump($res); 
}else if(!empty($arr['error'])){
  $res=$arr['error'];  
}
else if(!empty($arr['_error']['Description'])){
$res=$arr['_error']['Description'];    
}

 return $res;
}
public function get_object($module){
$path="EntityDefinitions(LogicalName='".$module."')"; //Attributes
$fields_arr=$this->post_crm($path,'get');
return $fields_arr;
}
public function get_crm_fields($module,$fields_type=false){
//return json_decode($json,true);
$path="EntityDefinitions(LogicalName='".$module."')/Attributes"; 
$fields_arr=$this->post_crm($path,'get');
//var_dump($fields_arr); //die();
 //,'msdyn_ordertype','msdyn_contractorganizationalunitid'
$skip=array('accountid','ownerid','customerid','customeridtype','pricelevelid','ispricelocked','msdyn_accountmanagerid'); //
$skip_req=array('msdyn_psastatusreason','msdyn_psastate','msdyn_contractorganizationalunitid','msdyn_ordertype');
$fields=array();
if(!empty($fields_arr['value'])){
foreach($fields_arr['value'] as $v){ //var_dump($v); //die();
//echo json_encode($v['RequiredLevel']['Value']);
  //  echo '<hr>';
    if(isset($v['IsValidForUpdate']) && $v['IsValidForUpdate'] == true && !empty($v['DisplayName']['UserLocalizedLabel']['Label'])){   // &&
       if(in_array($v['LogicalName'],$skip)){ continue; }  //var_dump($v);
     $type=$v['AttributeType'];
     if($type == 'DateTime' && isset($v['Format']) && $v['Format'] == 'DateOnly'){
     $type='Date';    
     }
  $a=array('name'=>$v['LogicalName'],'type'=>$type,'label'=>$v['DisplayName']['UserLocalizedLabel']['Label']);  //

  if(isset($v['IsSearchable']) && $v['AttributeType'] === 'String'){
      $a['search']='true';
  }
   if(isset($v['IsCustomAttribute']) && $v['IsCustomAttribute'] === true){
      $a['is_custom']='1';
  } 
  if($v['RequiredLevel']['Value'] != 'None' && !in_array($v['LogicalName'],$skip_req)){
      $a['req']='true';
  }
  if(!empty($v['MaxLength'])){
      $a['maxlength']=$v['MaxLength'];
  }
  if( $v['LogicalName'] == 'statecode'){
   $a['eg']='0=Active, 1=Submitted, 2=Canceled, 3=Fulfilled, 4=Invoiced';   
  }

if(!empty($v['Targets']) ){ 
//star moved from here because parentcustomerid accepts parentcustomerid_account , it does not accept schema name
 
 if($v['AttributeType'] == 'Lookup'){
  $a['related_to']=$v['Targets'][0]; 
  //star moved to  
  $a['schema_name']= $v['LogicalName'];
  if((isset($v['IntroducedVersion']) && in_array($v['IntroducedVersion'],array('1.0.0.0'))) || (isset($v['IsCustomAttribute']) && $v['IsCustomAttribute'] === true) ){
      $a['schema_name']= $v['SchemaName'] ;   
  }
  //end moved to
  //$a['schema_name']=isset($v['IsManaged']) && $v['IsManaged'] == true ? $v['LogicalName'] : $v['SchemaName'];  //LogicalName , SchemaName   
  //'IntroducedVersion' => string '1.0.0.1' and IsManaged=false then SchemaName otherwise LogicalName
 
 // $a['type']=$a['related_to'].'-'.$a['type'];
 }else if(count($v['Targets'])){
      $name=$a['name']; $label=$a['label'];
     //if($type == 'Lookup'){
     // $name=$v['SchemaName'];  
    // }
    //var_dump($v);
    foreach($v['Targets'] as $target){
      $a['related_to']=$target; 
     $a['name']=$name.'_'.$target;
     $a['label']=$label.'-'.ucfirst($target);
      $fields[$a['name']]=$a;     
    }
  continue;   
 } 
  }  
  
  $fields[$a['name']]=$a;
    
  }
} //var_dump($fields);
 // 
$path="EntityDefinitions(LogicalName='".$module."')/Attributes".'/Microsoft.Dynamics.CRM.PicklistAttributeMetadata?$select=LogicalName&$expand=OptionSet($select=Options),GlobalOptionSet($select=Options)';
$lists=$this->post_crm($path,'get');

$path="EntityDefinitions(LogicalName='".$module."')/Attributes".'/Microsoft.Dynamics.CRM.MultiSelectPicklistAttributeMetadata?$select=LogicalName&$expand=OptionSet($select=Options),GlobalOptionSet($select=Options)';
$list2=$this->post_crm($path,'get');
$list_vals=array();
if(!empty($lists['value'])){ $list_vals=$lists['value']; }
if(!empty($list2['value'])){ $list_vals=array_merge($list_vals,$list2['value']); }

foreach($list_vals as $v){ 
   if(!empty($v['LogicalName']) && isset($fields[$v['LogicalName']])){
       $options=array(); $o=array(); $eg=array(); $no=0;
       if(!empty($v['OptionSet']['Options'])){ $options=$v['OptionSet']['Options']; }
       if(!empty($v['GlobalOptionSet']['Options'])){ $options=$v['GlobalOptionSet']['Options']; }
   foreach($options as $k){
        if(!empty($k['Label']['UserLocalizedLabel']['Label'])){
                 // echo json_encode($k['Label']).'<hr>'; 
            $o[$k['Value']]=$k['Label']['UserLocalizedLabel']['Label'];
        if($no<20){
            $eg[]=$k['Value'].'='.$o[$k['Value']];
        }
        }          
   }

     $fields[$v['LogicalName']]['options']=$o;    
     $fields[$v['LogicalName']]['eg']=implode(', ',$eg);    
   } 
} 
  $fields['vx_list_files']=array('name'=>'vx_list_files',"type"=>'files','label'=>'Files - Related List','is_custom'=>'1');
  $fields['vx_list_files2']=array('name'=>'vx_list_files2',"type"=>'files','label'=>'Files 2 - Related List','is_custom'=>'1');
  $fields['vx_list_files3']=array('name'=>'vx_list_files3',"type"=>'files','label'=>'Files 3 - Related List','is_custom'=>'1');
  $fields['vx_list_files4']=array('name'=>'vx_list_files4',"type"=>'files','label'=>'Files 4 - Related List','is_custom'=>'1');
  $fields['vx_list_files5']=array('name'=>'vx_list_files5',"type"=>'files','label'=>'Files 5 - Related List','is_custom'=>'1');
 // $fields['vx_list_files6']=array('name'=>'vx_list_files6',"type"=>'files','label'=>'Files 6 - Related List','is_custom'=>'1');
 // $fields['vx_list_files7']=array('name'=>'vx_list_files7',"type"=>'files','label'=>'Files 7 - Related List','is_custom'=>'1');
 // $fields['vx_list_files8']=array('name'=>'vx_list_files8',"type"=>'files','label'=>'Files 8 - Related List','is_custom'=>'1');
 // $fields['vx_list_files9']=array('name'=>'vx_list_files9',"type"=>'files','label'=>'Files 9 - Related List','is_custom'=>'1');
 // $fields['vx_list_files10']=array('name'=>'vx_list_files10',"type"=>'files','label'=>'Files 10 - Related List','is_custom'=>'1');
}else  if(!empty($fields_arr['message'])){
  $fields=$fields_arr['message'];  
}
///var_dump($fields);
//$field_options[$k]['options'][]=array('name'=>$v['key'],'value'=>$v['name']);    
return $fields;
}

public function get_users(){ 
$arr=$this->post_crm('systemusers','get');
$users=array();   
$msg='No User Found';
if(!empty($arr['value'])){
foreach($arr['value'] as $k=>$v){
if(!empty($v['internalemailaddress']) && isset($v['isdisabled']) && $v['isdisabled'] == false && in_array($v['accessmode'],array(0,1,2))){ //https://msdn.microsoft.com/en-us/library/mt608065.aspx
$name=$v['internalemailaddress'];
if(!empty($v['fullname'])){ $name.='('.$v['fullname'].')'; }
 $users[$v['systemuserid']]=$name;
 }
 } }else if(!empty($arr['message'])){
 $msg=$arr['message'];   
}

  return empty($users) ? $msg : $users;
}
public function get_camps(){ 
$arr=$this->post_crm('campaigns','get');
$camps=array();   
$msg='No Campaign Found';
if(!empty($arr['value'])){
foreach($arr['value'] as $k=>$v){
if(!empty($v['campaignid']) && isset($v['statecode']) && $v['statecode'] == 0){ 
    //https://msdn.microsoft.com/en-us/library/mt607658.aspx
 $camps[$v['campaignid']]=$v['name'];
 }
 } }else if(!empty($arr['message'])){
 $msg=$arr['message'];   
}
return empty($camps) ? $msg : $camps;
}
public function get_pricelists(){ 
$arr=$this->post_crm('pricelevels','get');
$lists=array();   
$msg='No Price List Found';
if(!empty($arr['value'])){
foreach($arr['value'] as $k=>$v){
if(!empty($v['pricelevelid']) && isset($v['statecode']) && $v['statecode'] == 0){ 
    //https://msdn.microsoft.com/en-us/library/mt607658.aspx
 $lists[$v['pricelevelid']]=$v['name'];
 }
 } }else if(!empty($arr['message'])){
 $msg=$arr['message'];   
}
return empty($lists) ? $msg : $lists;
}
public function push_object($module,$fields,$meta){ 
/*    
$post=array('name'=>'john lewsx');
//$post['submitstatus']=4;
//$post['submitstatusdescription']='xxxx';
$post['totalamount']=0;
$post['statecode']='0';
$post['statuscode']=1;
$post['willcall']=false;
$post['ispricelocked']=false;
$post['customerid_contact@odata.bind']='/contacts(c28fd4c3-679f-eb11-b1ac-000d3aaa683a)';
$post['ownerid@odata.bind']='/systemusers(31f56e7c-388a-ea11-a828-000d3a494c15)';
$post['transactioncurrencyid@odata.bind']='/transactioncurrencies(335505f5-427a-e911-a822-000d3a47c671)';
$post['pricelevelid@odata.bind']='/pricelevels(66b6d993-2d81-ea11-a811-000d3a4a162c)';

$post=array('statecode'=>0);

$arr=$this->post_crm('','patch',$post); 
var_dump($arr); die();
 $path='tal_placements?$top=10';
//$path='leads?$top=100';
//$res=$this->post_crm($path,'get'); var_dump($res); die();
$path='leads(4998b265-f040-ea11-a812-000d3a86a3ce)';
$path='contacts(65722bae-db40-ea11-a812-000d3a86a3ce)';
$path='salesorders(c84338fa-c69b-eb11-b1ac-0022487f195a)';
$path='salesorders(364f8d1b-6c9f-eb11-b1ac-000d3aaa683a)';
$lead=array('ParentContactId@odata.bind'=>'/contacts(65722bae-db40-ea11-a812-000d3a86a3ce)');
$con=array('tal_ConfirmedPlacement@odata.bind'=>'/tal_placements(6776f576-780c-ea11-a811-000d3a86a85d)');
$res=$this->post_crm($path,'get');
echo json_encode($res); die();*/
///$res=$this->get_crm_objects(true);
//$res=$this->get_object('ait_country'); //ait_countries
//var_dump($res);  die('--------');
    //  ait_countries 
 //   $res=$this->post_crm('leads?$top=1');
 //   var_dump($res); die();
//$res=$this->post_crm('salesorders(1c51cda1-999a-eb11-b1ac-0022487f195a)');
//var_dump($res,$fields); die();
//check primary key
 $extra=array(); $object=$module; 
 if(!empty($meta['objects'][$module]['name'])){ $object=$meta['objects'][$module]['name']; }
  $debug = isset($_GET['vx_debug']) && current_user_can('manage_options');
  $event= isset($meta['event']) ? $meta['event'] : '';
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';
  
    //remove related list fields
  $files=array();
  for($i=1; $i<11; $i++){
$field_n='vx_list_files';
if($i>1){ $field_n.=$i; }
  if(isset($fields[$field_n])){ 
    $files=$this->verify_files($fields[$field_n]['value'],$files);
    unset($fields[$field_n]);  
  }
}

  if($debug){ ob_start();}
if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!=""){    
$search=$fields[$meta['primary_key']]['value'];
$field=$meta['primary_key'];

$search_response=$this->post_crm($object.'?$top=1&$filter='.urlencode($field.' eq \''. str_replace("'",'',$search)."'"));
//var_dump($search_response); die();
if(!empty($search_response['value'][0][$module.'id'])){
  $id=$search_response['value'][0][$module.'id'];    
}

  if($debug){
  ?>
  <h3>Search field</h3>
  <p><?php print_r($field) ?></p>
  <h3>Search term</h3>
  <p><?php print_r($search) ?></p>
    <h3>POST Body</h3>
  <p><?php print_r($body) ?></p>
  <h3>Search response</h3>
  <p><?php print_r($res) ?></p>  
  <?php
  }

      $extra["body"]=$search;
      $extra["response"]=$search_response;
  }


     if(in_array($event,array('delete_note','add_note'))){    
  if(isset($meta['related_object'])){
    $extra['Note Object']= $meta['related_object'];
  }
  if(isset($meta['note_object_link'])){
    $extra['note_object_link']=$meta['note_object_link'];
  }
}

 $status=$action=$method=""; $send_body=true;
 $entry_exists=false;

$object_url='';
$is_main=false;
$post=$arr=array();
if($id == ""){
    //insert new object
$action="Added";  
if(empty($meta['new_entry'])){
$status="1";
$method='post'; $object_url=$object;
$is_main=true;
}else{
      $error='Entry does not exist';
  }
}else{
 $entry_exists=true;
 if($event == 'add_note'){
 if(!empty($meta['related_object']) && !empty($meta['crm_id'])){
      $parent_object=$meta['related_object']; $object_id=$meta['crm_id'];
        if(!empty($fields['body']['value'])){
         $post['notetext']=$fields['body']['value'];   
 }
 if(!empty($fields['title']['value'])){
         $post['subject']=$fields['title']['value'];   
 }
         $post['objectid_'.$parent_object.'@odata.bind']='/'.$parent_object.'s('.$object_id.')';   
 }

$action="Added";
$status="1";
$object_url='annotations';
$method='post';  
  }
  else if(in_array($event,array('delete','delete_note'))){
 $send_body=false;
 $method='delete'; $obj_s=$event == 'delete_note' ? 'annotations' : $object;
 $object_url=$obj_s.'('.$id.')';
     $action="Deleted";
  $status="5";  
  }
  else{
       $action="Updated"; $status="2";  
 if(empty($meta['update'])){
 $is_main=true;
$object_url=$object.'('.$id.')';
 }
 $method='patch';
 
  }
  }

    if($is_main){

$crm_fields=array();
if(!empty($meta['fields'])){
  $crm_fields=$meta['fields'];  
}
if(is_array($fields) && count($fields)>0){
    foreach($fields as $k=>$v){
        if( empty($crm_fields[$k]['type'])){ //empty($v['value']) ||
            continue;
        }
        $type=$crm_fields[$k]['type'];
        if( !empty($crm_fields[$k]['related_to']) ){ 
            $related_to='';
             if(!empty($meta['objects'][$crm_fields[$k]['related_to']]['name']) ){
                $related_to=$meta['objects'][$crm_fields[$k]['related_to']]['name']; 
             }
             if(!empty($related_to)){
          $related_id=$k;       
           if(!empty($crm_fields[$k]['schema_name'])){
           $related_id=$crm_fields[$k]['schema_name'];    
           }     
    $post[$related_id.'@odata.bind']='/'.$related_to.'('.$v['value'].')';   
             }
          continue;
        } 
 
        switch($type){
           case'DateTime':
    $offset=get_option('gmt_offset');
    $offset=$offset*3600;
     $v['value']=date('Y-m-d\TH:i:s\Z',strtotime(str_replace(array("/"),"-",$v['value']))-$offset); 
         //  $v['value']='2017-10-06T04:03:48Z'; 
           break; 
           case'Boolean':
           if($k == 'donotsendmm'){
           $v['value']=$v['value'] == 'false' || empty($v['value']) ? true : false;     
           }else{
           $v['value']=$v['value'] == 'false' || empty($v['value']) ? false : true; 
           }
           break; 
       case'Money':
           $v['value']=(float)$v['value']; 
           break; 
           case'Date':
       $v['value']=date('Y-m-d',strtotime(str_replace(array("/"),"-",$v['value']))); 
           break;
           case'Decimal':
           $v['value']=(float)$v['value']; 
           break; 
           case'Virtual':
           if(is_array($v['value'])){ $v['value']=implode(',',$v['value']); } 
           break; 
           case'State':
          $v['value']=(float)$v['value']; 
           break;  
        }  
    $post[$k]=$v['value'];    

    }
}
//var_dump($post,$fields,$crm_fields); die();
//assign user 
if(!empty($meta['user']) && !empty($meta['owner'])){
$post['ownerid@odata.bind']='/systemusers('.$meta['user'].')';
$fields['OwnerId']=array('value'=>$meta['user'],'label'=>'Owner ID');     
}  
if(!empty($meta['add_to_camp']) && !empty($meta['campaign'])){
$post['campaignid@odata.bind']='/campaigns('.$meta['campaign'].')';
$fields['CampId']=array('value'=>$meta['campaign'],'label'=>'Campaign Id');     
} 
if(!empty($meta['assign_list']) && !empty($meta['list'])){
$post['pricelevelid@odata.bind']='/pricelevels('.$meta['list'].')';
$fields['pricelevelid']=array('value'=>$meta['list'],'label'=>'Price List');     
}

if(!empty($meta['account_check']) && !empty($meta['object_account'])){
     $account_feed=$meta['object_account'];  
   if( isset(self::$feeds_res[$account_feed]) ){
   $account_res=self::$feeds_res[$account_feed];
  if(!empty($account_res)){
   $fields['AccountId']=array('value'=> $account_res,'label'=>'Account ID');   
  $post['customerid_account@odata.bind']='/accounts('.$account_res.')';
  }    
   }  

  }  
if(!empty($meta['contact_check']) && !empty($meta['object_contact'])){ 
     $contact_feed=$meta['object_contact'];  
       if( isset(self::$feeds_res[$contact_feed]) ){
   $contact_res=self::$feeds_res[$contact_feed];
  if(!empty($contact_res)){
  $fields['ContactId']=array('value'=>$contact_res,'label'=>'Contact ID');     
   $post['customerid_contact@odata.bind']='/contacts('.$contact_res.')';  
  }  
   }
}
    }
    
//unset($post['new_SelectedMarket@odata.bind']);
//$post['ParentCustomerId_account@odata.bind']='/accounts(fc23acce-5692-e611-80f3-5065f38ad991)';    
//$post['parentcustomerid']='fc23acce-5692-e611-80f3-5065f38ad991';    
//$post['sis_contactId@odata.bind']='/contacts(57169f2a-d981-e911-a968-000d3a389d2c)';    
//$post['new_selectedmarket@odata.bind']='/new_markets(03981dbd-15ec-e911-a812-000d3a25bdee)';    
$link=""; $error=""; 
if(!empty($object_url)){
//$res=$this->post_crm('contacts(9d71b550-c160-e911-a827-000d3a34ed99)','get');
//$path='EntityDefinitions?$filter=OwnershipType eq Microsoft.Dynamics.CRM.OwnershipTypes\'UserOwned\'';
//$res=$this->post_crm($path,'get');
//var_dump($res); die();
  //unset($post['msdyn_ordertype']);
  //unset($post['emailaddress']);
//$post=json_decode('{"subject":"john lewis","websiteurl":"http:\/\/google.com","jobtitle":"CEO","companyname":"john lewis","emailaddress1":"bioinfo36as232312@gmail.com","lastname":"john lewis","firstname":"john lewis","Telephone1":"8104763057"}',1);  

$arr=$this->post_crm($object_url,$method,$post); 
//echo json_encode($post).'-------'.json_encode($arr); die();
//unset($post['msdyn_ordertype']);
//$res=$this->post_crm('salesorders(00d2798b-959a-eb11-b1ac-0022487f195a)');
//var_dump($arr,$res,$post,$object_url,$fields,$id); die();
if($module == 'incident'){
//var_dump($post,self::$feeds_res); die('--------');   
}
 

if(!empty($arr['id'])){ 
$id=trim(substr($arr['id'],strpos($arr['id'],'(')+1),')');

if(!empty($files)){
    foreach($files as $file){
           if(!empty($file)){
$file_in_base64 = base64_encode( file_get_contents( $file ) );
$file_name = basename($file);
$note_post=array('notetext'=>'','filename'=>$file_name,'isdocument'=>true,'documentbody'=>$file_in_base64);
$note_post['objectid_'.$module.'@odata.bind']='/'.$object.'('.$id.')';   
$object_url='annotations';
$note_response= $this->post_crm( $object_url,'post',$note_post);
  $extra['File Response']=$note_response;
       }    
    }
}
if($object == 'salesorders' && empty($post['statecode'])){
$this->post_crm($object.'('.$id.')','patch',array('statecode'=>0)); 
}

$id_arr=explode('/',$arr['id']);
$extra['crm_id']=end($id_arr);

if(!empty($meta['order_items_arr']) && $method == 'post' ){
    foreach($meta['order_items_arr'] as $item){
   $p_res=$this->post_crm('products?$top=1&$filter='.urlencode('productnumber eq \''. str_replace("'",'',$item['sku'])."'"));
   if(!empty($p_res['value'][0]['productid'])){
   $line=array('productid@odata.bind'=>'/products('.$p_res['value'][0]['productid'].')','uomid@odata.bind'=>'/uoms('.$p_res['value'][0]['_defaultuomid_value'].')','isproductoverridden'=>false);  
//   if(empty($fields['pricelevelid']['value'])){
    $line['ispriceoverridden']=true;   
    $line['priceperunit']=floatval($item['price']);   
 //  }    
   }else{        
 $line=array('productdescription'=>$item['title'],'priceperunit'=>floatval($item['price']),'isproductoverridden'=>true); 
  if(!empty($item['desc'])){
 $line['description']=$item['desc'];    
 }
 //isproductoverridden=true means = write-in  and false means existing
   }
     if(!empty($item['tax'])){
 $line['tax']=floatval($item['tax']);    
 }
   
   $line['quantity']=$item['qty'];
   $line['salesorderid@odata.bind']='/salesorders('.$id.')';

$fields['line_items'][]=$line;       
$extra['line_items'][$item['title']]=$this->post_crm('salesorderdetails','post',$line);    
    }
}
}
else if(!empty($arr['error']['message'])){
$status=$id=''; $error=$arr['error']['message'];
}else if(!empty($arr['message'])){
$status=$id=''; $error=$arr['message'];
}
}
if(!empty($error)){ $error=substr($error,0,250); }

if(!empty($meta['id'])){
self::$feeds_res[$meta['id']]=$id;
}

  if($debug){
  ?>
  <h3>Account Information</h3>
  <p><?php //print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php print_r($post) ?></p>
  <h3>Fields</h3>
  <p><?php echo json_encode($fields) ?></p>
  <h3>Response</h3>
  <p><?php print_r($response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($module."--------".$action) ?></p>
  <?php
 echo  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option(vxcf_dynamics::$id."_debug",$contents);   
  }
  }
       //add entry note
 if(!empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=vxcf_dynamics::post('disable_entry_note',$meta);
   if(!($entry_exists && !empty($disable_note))){
       $entry_note=$meta['__vx_entry_note'];
       if(!empty($entry_note['body'])){
      $note_post=array('notetext'=>$entry_note['body'],'subject'=>$entry_note['title']);
      $note_post['objectid_'.$module.'@odata.bind']='/'.$object.'('.$id.')';   
$object_url='annotations';
$note_response= $this->post_crm( $object_url,'post',$note_post);
  $extra['Note Body']=$entry_note['body'];
  $extra['Note Response']=$note_response;
       }
   }  
 }

return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra);
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
public function create_fields_section($fields){ 
$arr=array(); 
if(!isset($fields['object'])){
$objects=array(''=>'Select Object','account'=>'Account','contact'=>'Contact','lead'=>'Lead','incident'=>'Case','salesorder'=>'Order');
 $arr['gen_sel']['object']=array('label'=>'Select Object','options'=>$objects,'is_ajax'=>true,'req'=>true);   
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
$info=array('text'=>array('attr'=>'String','format'=>'Text','len'=>200),'textarea'=>array('attr'=>'Memo','format'=>'TextArea','len'=>2000));
$error='Unknow error';
if(!empty($label) && !empty($type) && !empty($object)){
$body=array("AttributeType"=>$info[$type]['attr'], 
/*"Description"=>array("@odata.type"=>"Microsoft.Dynamics.CRM.Label",
"LocalizedLabels"=>array( array("@odata.type"=>"Microsoft.Dynamics.CRM.LocalizedLabel",
"Label"=> "New Test Custom Field",
"LanguageCode"=> 1033 )
  ) ),*/
 "DisplayName"=>array("@odata.type"=>"Microsoft.Dynamics.CRM.Label",
 "LocalizedLabels"=>array(array("@odata.type"=>"Microsoft.Dynamics.CRM.LocalizedLabel",
    "Label"=>$label,
    "LanguageCode"=> 1033 )
  ) ),
 "RequiredLevel"=>array("Value"=>"None","CanBeChanged"=> true),
 "SchemaName"=> 'new_'.$name,
 "@odata.type"=> "Microsoft.Dynamics.CRM.".$info[$type]['attr']."AttributeMetadata",
 "Format"=> $info[$type]['format'],
 "MaxLength"=> $info[$type]['len']
);
$url="EntityDefinitions(LogicalName='".$object."')/Attributes";
    
$arr=$this->post_crm($url,'post',$body); 
    $error='ok';
if(!empty($arr['error']['message'])){
 $error=$arr['error']['message'];       
} }
return $error;    
}



public function post_crm($path,$method='get',$body='',$head_type='json'){

if(empty($head_type)){
$head=array('Content-Type'=>'application/x-www-form-urlencoded');
$url=$path;
}else{
$token=$this->get_token();
   
$head=array('Accept'=>'application/json','Content-Type'=>'application/json; charset=utf-8','Authorization'=>'Bearer '.$token);    
$url=$this->url.$path;
}   

    if(is_array($body)&& count($body)>0)
   { 
       if($head_type == 'json'){
       $body=json_encode($body);
       }else{
     $body=http_build_query($body);      
       }
   }
 
$args = array(
  'body' => $body,
  'headers'=> $head,
  'method' => strtoupper($method), // GET, POST, PUT, DELETE, etc.
  'sslverify' => false,
  'timeout' => 30);
$response = wp_remote_request($url, $args);
//var_dump($response['body'],$head,$url,$body,$head);
$body=array();
if(is_array($response) && isset($response['response']['code'])){
   $code=$response['response']['code'];
   if($code == 204){
       if($method == 'delete'){
    $body=array('success'=>'true');       
       }else{
    $id=wp_remote_retrieve_header($response,'Location');    
  $body=array('id'=>$id);
       }
   }else if($code == 401){
    $body=array('message'=>'Unauthorized: Access is denied');   
   }else{
   $body=json_decode($response['body'],true);    
   }
}else if( is_wp_error( $response ) ) {
$body=array('error'=>$response->get_error_message() );
}  

return $body;
}
public function get_entry($module,$id){


      $arr=$this->post_crm('boxes/'.$id,'get');
if(!empty($arr['fields']) && is_array($arr['fields'])){
    foreach($arr['fields'] as $k=>$v){
     if(!is_array($v)){
     $arr[$k]=$v;    
     }   
    }
}

      return $arr;     
}
}
}
?>