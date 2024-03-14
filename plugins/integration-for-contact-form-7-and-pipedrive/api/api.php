<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxcf_pipedrive_api')){
    
class vxcf_pipedrive_api extends vxcf_pipedrive{
  
    public $info='' ; // info
    public $url='';
    public $api_key='';
    public $error= "";
    public $timeout= "15";

function __construct($info) {
     
    if(isset($info['data'])){ 
       $this->info= $info;
       if(!empty($info['data']['api_key'])){
        $this->api_key=$info['data']['api_key'];
            $this->url=trailingslashit($info['data']['app_url']).'v1/';
       }
    }
    }
public function get_token(){
  $users=$this->get_users();
    $info=$this->info;
    $info=isset($info['data']) ? $info['data'] : array();
    if(is_array($users) && !empty($users)){
    $info['valid_token']='true';    
    }else {
      unset($info['valid_token']); 
      if(!empty($users) && is_string($users)){
          $info['error']=$users;
      } 
    }
    return $info;

}
public function get_users(){
  $users=$this->post_crm('users','get'); 
$arr=array();
if(!empty($users['data'])){
  foreach($users['data'] as $k=>$v){
  $arr[$v['id']]=$v['name'];    
  }  
}else if(!empty($users['detail'])){
$arr=$users['detail'];  

}else if(!empty($users['error'])){
$arr=$users['error'];  

}
    return $arr;

}

public function get_crm_fields($module,$fields_type=false){

$fields=$this->post_crm($module.'Fields','get');
//var_dump($fields,$module); // die();

$res=array(); $standard=array('name','first_name','last_name','email','label'); 
$skip=array('open_deals_count','activities_count','closed_deals_count','lost_deals_count','won_deals_count','next_activity_date','last_activity_date','update_time','done_activities_count','last_incoming_mail_time','email_messages_count','undone_activities_count','last_outgoing_mail_time','person_name','person_phone','person_email','org_name','org_address','source','product_amount','product_quantity');
if(!empty($fields['data'])){

if($module == 'lead'){
  $deal_fields=$this->post_crm('dealFields','get'); 
  if(!empty($deal_fields['data'])){
      foreach($deal_fields['data'] as $v){
         if(!empty($v['last_updated_by_user_id'])){
         $fields['data'][]=$v;    
         } 
      }
  }
   // 
} 
foreach($fields['data'] as $k=>$v){
   if($module == 'lead'){
    if($v['key'] == 'related_person_id'){
       $v['key']='person_id'; 
    }  if($v['key'] == 'related_org_id'){
       $v['key']='org_id'; 
    }if($v['key'] == 'labels'){
       $v['key']='label_ids'; 
    $arr=$this->post_crm('leadLabels','get');  
    if(!empty($arr['data'])){
   $v['options']=$arr['data'];
    } 
    }
    if($v['key'] == 'deal_value'){
       $v['key']='lead_value'; 
       $v['name']='Lead Value'; 
    }
    if($v['key'] == 'deal_currency'){
       $v['key']='lead_currency'; 
       $v['name']='Lead Currency'; 
    }
    if( in_array($v['key'],array('deal_id','add_time','archive_time') )){
      continue;  
    }
   }
    if(empty($v['id']) && strpos($v['key'],'_currency') === false ){
    continue;    
    }
    if(in_array($v['key'],$skip)){
        continue;
    }
 $label=$v['name'];
 if($v['key'] == 'person_id'){
 $label='Override Person ID';    
 }
 if($v['key'] == 'org_id'){
 $label='Override Organization ID';    
 }  
 
$field=array('label'=>$label,'name'=>$v['key'],'type'=>$v['field_type']);
 if(in_array($v['key'],array('name','title'))){ $field['req']='true';    } 
 if($v['key'] == 'lead_currency'){
  $field['eg']='3 digit currency (USD)';   
 } 
 if($v['key'] == 'stage_id'){
      $stages_arr=$this->post_crm('stages','get');
      if(!empty($stages_arr['data'])){
      $ops=array(); 
      foreach($stages_arr['data'] as $vv){
      $ops[]=array('id'=>$vv['id'],'label'=>$vv['name'].' - '.$vv['pipeline_name']);      
      } 
     $v['options']=$ops;    
      }   
 }
 if(!empty($v['options'])){
     $field_options=array(); $egs=array();
   foreach($v['options'] as $op){
       if(!isset($op['label']) && isset($op['name'])){
           $op['label']=$op['name'];
       }
$field['options'][]=array('label'=>$op['label'],'value'=>$op['id']); 
$egs[]=$op['id'].'='.$op['label'];       
    }
$field['eg']=implode(', ',array_slice($egs,0,30));   
}
if(!in_array($v['key'],$standard)){
    $field['is_custom']='1';
}
 $res[$v['key']]=$field;   
}
if($module != 'lead'){
$res['vx_attachments']=array('label'=>'Attachments - Related List','name'=>'vx_attachments','type'=>'files','maxlength'=>'0','is_custom'=>'1');  
$res['vx_attachments2']=array('label'=>'Attachments - Related List 2','name'=>'vx_attachments2','type'=>'files','maxlength'=>'0','is_custom'=>'1');  
$res['vx_attachments3']=array('label'=>'Attachments - Related List 3','name'=>'vx_attachments3','type'=>'files','maxlength'=>'0','is_custom'=>'1');  
$res['vx_attachments4']=array('label'=>'Attachments - Related List 4','name'=>'vx_attachments4','type'=>'files','maxlength'=>'0','is_custom'=>'1');  
$res['vx_attachments5']=array('label'=>'Attachments - Related List 5','name'=>'vx_attachments5','type'=>'files','maxlength'=>'0','is_custom'=>'1'); 
}

}else if(!empty($fields['detail'])){
$res=$fields['detail'];  
}
return $res;
}
public function verify_files($files,$old=array()){
        if(!is_array($files)){
        $files_temp=json_decode($files,true);
     if(is_array($files_temp)){
    $files=$files_temp;     
     }else if (!empty($files)){ //&& filter_var($files,FILTER_VALIDATE_URL)
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
public function push_object($module,$fields,$meta){ 

 // $arr= $this->post_crm('persons/5'); 
 // var_dump($arr); die();
//check primary key
 $extra=array();

   $files=array(); $link=""; $error="";
  for($i=1; $i<6; $i++){
$field_n='vx_attachments';
if($i>1){ $field_n.=$i; }
  if(isset($fields[$field_n]['value'])){
    $files=$this->verify_files($fields[$field_n]['value'],$files);
    unset($fields[$field_n]);  
  }
} 
 if( $module == 'deal' && isset($fields['owner_id'])){
     $fields['user_id']=$fields['owner_id'];
 }
  $debug = isset($_GET['vx_debug']) && current_user_can('manage_options');
  $event= isset($meta['event']) ? $meta['event'] : '';
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';
  if($debug){ ob_start();}
if(isset($meta['primary_key']) && $meta['primary_key']!="" && isset($fields[$meta['primary_key']]['value']) && $fields[$meta['primary_key']]['value']!=""){    
$search=$fields[$meta['primary_key']]['value'];
$field=$meta['primary_key'];
if(isset($meta['fields'][$field]) && !empty($meta['fields'][$field]['is_custom'])){
 $field='custom_fields';   
}

$search_response=$this->post_crm($module.'s/search','get',array('term'=>$search,'fields'=>$field));
//var_dump($search_response); die();
if(!empty($search_response['data']['items'])){
  $items=$search_response['data']['items'];
  //$item=end($items);
  if(!empty($items[0]['item']['id'])){
  $id=$items[0]['item']['id'];  
  $search_response =$items[0]['item']; 
  }  
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
$post=$old=array();
if($id == ""){
    //insert new object
$action="Added";  $status="1"; $method='post';
if(empty($meta['new_entry'])){
$object_url=$module.'s';
$is_main=true;
}else{
    $status="6";
      $error='Record not found in CRM';
}
}else{
 $entry_exists=true;
    if($event == 'add_note'){
        if(!empty($fields['body']['value'])){
         $post['note']=$fields['body']['value'];   
        }

$action="Note Added"; $status="1";
$object_url='lists/'.$meta['related_object'].'/members/'.$id.'/notes';
$method='post';  
}else if(in_array($event,array('delete','delete_note'))){
 $send_body=false;
 $method='delete'; 
 $object_url='';
     if($event == 'delete_note' && !empty($meta['note_object_link'])){ 
  $object_url='lists/'.$meta['related_object'].'/members/'.$meta['note_object_link'].'/notes/'.$id;
     }else{
     $object_url='lists/'.$meta['object'].'/members/'.$id;
     }
     $action="Deleted";
  $status="5";  

  }else{
$action="Updated"; $status="2";    
if(empty($meta['update']) || !empty($meta['update_empty']) ){ 
 $is_main=true;
$object_url=$module.'s/'.$id;
 $method='put';
 if(!empty($meta['update_empty'])){
 $arr=$this->post_crm($object_url,'get'); 
 if(!empty($arr['data'])){
  foreach($arr['data'] as $k=>$v){
      if(isset($fields[$k]) && isset($fields[$k]['value'])){
          if(in_array($k,array('email','phone'))){
              if(isset($v[0]['value'])){
              $v=$v[0]['value'];    
              }else{
              $v='';    
              }
          }
          if($v!=''){ $old[$k]=$v; } 
      }
  }   
 }   
 }
 } }
}
//var_dump($fields,$arr); die();
if($is_main){

$crm_fields=array();
if(!empty($meta['fields'])){
  $crm_fields=$meta['fields'];  
}

if(is_array($fields) && count($fields)>0){
    foreach($fields as $k=>$v){
  if(!empty($crm_fields[$k]['type']) && !isset($old[$k])){     
    $type=$crm_fields[$k]['type']; 
$val=$v['value'];       
if($k == 'label_ids'){
    $val=array_filter(array_map('trim',explode(',',$val))); 
    
}
if(in_array($k,array('user_id','owner_id','org_id','person_id'))){
    $val=(int)$val;
}
if($type == 'addressss'){

}else if($type == 'date'){
$post[$k]=date('Y-m-d',strtotime($val));
}else if( in_array($type,array('enum')) && !in_array($k,array('marketing_status'))){
$post[$k]=(int)$val;
}else if( in_array($type,array('set')) && $k != 'label_ids' ){ 
   if($val!=''){ 
if(!is_array($val)){ $val=array($val); }
$val=array_map('intval',$val);
$post[$k]=$val;
   }
}else if( in_array($type,array('double','monetary'))){
$post[$k]=(float)$val;
}else{
   
$post[$k]=$val;      
}   }
}
//var_dump($post); die();
$name='';
if(isset($post['first_name'])){
  $name=$post['first_name'];
  unset($post['first_name']);  
}
if(isset($post['last_name'])){
  $name.=' '.$post['last_name'];
  unset($post['last_name']);  
}
$name=trim($name);
if(empty($post['name']) && !empty($name) ){
 $post['name']=$name;   
}

//$post['status']=!empty($meta['status']) ? $meta['status'] : 'subscribed';
//$post['email_type']=!empty($meta['email_type']) ? $meta['email_type'] : 'html';
//$post['language']=!empty($meta['language']) ? $meta['language'] : 'en';

} } 
//var_dump($post); die();

if(!empty($method) && !empty($object_url) && !empty($post) ){
    if($module == 'lead'){
        if(isset($post['org_id'])){
         $post['organization_id']=$post['org_id'];
         unset($post['org_id']);   
        } 
        if(isset($post['lead_value'])){
         $post['value']=array('amount'=>floatval($post['lead_value']),'currency'=>'USD');
if(!empty($post['lead_currency'])){
  $post['value']['currency']=$post['lead_currency'];
    unset($post['lead_currency']);
}
         unset($post['lead_value']);    
        }  
       // var_dump($post); //die();
   $post=json_encode($post);     
    }
$arr= $this->post_crm($object_url, $method, $post);
}
if($module == 'lead'){
//var_dump($object_url,$arr,$post,$method); die();
}
if(!empty($arr['error'])){
       $status=''; $error=$arr['error'];
}else if(!empty($arr['data']['id'])){
$id=$arr['data']['id'];        

if(!empty($files)){ //$related['files']
 $camp_path='files';  
 $upload=wp_upload_dir();  
foreach($files as $k=>$file){
 $file=str_replace($upload['baseurl'],$upload['basedir'],$file);
 $id_name= $module == 'organization' ? 'org_id' : $module.'_id';
$file_post=array('attachments_v2'=>array($file),$id_name=>$id);
$extra['Add Files '.$k]=$this->post_crm($camp_path,'post',$file_post); 

} 
 
}

    }
  if($debug){
  ?>
  <h3>Account Information</h3>
  <p><?php //print_r($this->info) ?></p>
  <h3>Data Sent</h3>
  <p><?php print_r($post) ?></p>
  <h3>Fields</h3>
  <p><?php //echo json_encode($fields) ?></p>
  <h3>Response</h3>
  <p><?php print_r($response) ?></p>
  <h3>Object</h3>
  <p><?php print_r($module."--------".$action) ?></p>
  <?php
// echo  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }
       //add entry note
 if(!empty($meta['__vx_entry_note']) && !empty($id)){
 $disable_note=$this->post('disable_entry_note',$meta);
if(!($entry_exists && !empty($disable_note))){
$entry_note=$meta['__vx_entry_note'];
if(!empty($entry_note['body'])){
$note_post=array('content'=>$entry_note['body'],$module.'_id'=>$id);
$object_url='notes';
$note_response= $this->post_crm( $object_url,'post',$note_post);
  $extra['Note Body']=$entry_note['body'];
  $extra['Note Response']=$note_response;
}
   }  
 }

return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra);
}

public function post_crm($path,$method='get',$body=''){
       
$url=$this->url.$path.'?api_token='.urlencode($this->api_key);   
if(is_array($body)&& count($body)>0)
{ 
       if($method == 'get'){
       $url.='&'.http_build_query($body);  
       $body='';  
       }
}
     $head=array(); 
       if(!empty($body) && !is_array($body)){
       $head['Content-Type']='application/json';   
       }
       
 //  $body['api_token']='7e603dc4e90346a1c2a4e2318cb704bed3b5abf2';    
if(!empty($body) && is_array($body) && isset($body['attachments_v2'])){
     $files = array(); 
if(!empty($body['attachments_v2'])){
$files=$body['attachments_v2'];
unset($body['attachments_v2']);
$file_name='file';
}
$boundary = wp_generate_password( 24 );
$delimiter = '-------------' . $boundary;
$head['Content-Type']='multipart/form-data; boundary='.$delimiter;
$body = $this->build_data_files($boundary, $body, $files,$file_name);
$head['Content-Length']=strlen($body);
$head['Host']='crmperks-sandbox.pipedrive.com';
}    

       $args = array(
  'body' => $body,
  'headers'=> $head,
  'method' => strtoupper($method), // GET, POST, PUT, DELETE, etc.
  'timeout' => 30,
  );
  
  $response = wp_remote_request($url, $args);
  if(is_wp_error($response)) { 
  $error = $response->get_error_message();
  return array('detail'=>$error);
  }
  $body = wp_remote_retrieve_body($response);
$body=json_decode($body,true);

return $body;
}
public function build_data_files($boundary, $fields, $files, $file_name='attachments[]'){
    $data = '';
    $eol = "\r\n";

    $delimiter = '-------------' . $boundary;

    foreach ($fields as $name => $content) {
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="' . $name . "\"".$eol.$eol
            . $content . $eol;
    }

    foreach ($files as $name => $file) {
    $name=basename($file);
   $content = file_get_contents($file);
        $data .= "--" . $delimiter . $eol
            . 'Content-Disposition: form-data; name="'.$file_name.'"; filename="'.$name.'"' . $eol
            . 'Content-Type: '.mime_content_type($file).$eol;
          // . 'Content-Transfer-Encoding: binary'.$eol;

        $data .= $eol;
        $data .= $content . $eol;
    }
    $data .= "--" . $delimiter . "--".$eol;


    return $data;
}
public function get_entry($module,$id){


$arr=$this->post_crm($module.'s/'.$id,'get');
if(!empty($arr['data']) && is_array($arr['data'])){
$arr=$arr['data'];
}

      return $arr;     
}
}
}
?>