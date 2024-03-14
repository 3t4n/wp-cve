<?php
// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) exit;

if(!class_exists('vxcf_zendesk_api')){
    
class vxcf_zendesk_api extends vxcf_zendesk{
  
      public $token='' ; 
      public $email='' ; 
      public $url='' ; 
    public $info='' ; // info
    public $error= "";
    public $timeout= "30";

function __construct($info) {
     
    if(isset($info['data']['api_key'])){ 
       $this->token= $info['data']['api_key'];
    }
    if(isset($info['data']['email'])){ 
       $this->email= $info['data']['email'];
    }
    if(isset($info['data']['url'])){ 
       $this->url= trailingslashit( $info['data']['url'] );
    }

 $this->info=$info;
    }
public function get_token(){
   $users=$this->get_crm_fields('ticket');
   
 $info=$this->info;
 $info=isset($info['data']) ? $info['data'] : array();
    
    if(is_array($users) && count($users)>0){
    $info['valid_token']='true';    
    }else{
    //
     if(!empty($users) && is_string($users)){        
         $info['msg']=$users;        
     }    
      unset($info['valid_token']);  
    }

    return $info;
}

public function get_crm_fields($module){
    $path=$module.'_fields.json';
 $arr=$this->post_crm($path); //locale_id

 $fields=array();
 $types=array('subject'=>'subject','description'=>'comment','status'=>'status','tickettype'=>'type','priority'=>'priority','group'=>'group_id','assignee'=>'assignee_id');
 $eg=array('tickettype'=>'problem, incident, question, task','priority'=>'urgent, high, normal, low','status'=>'new, open, pending, hold, solved, closed');
 if(isset($arr[$module.'_fields'])){

  $fields=array('email'=>array('name'=>'email','label'=>'Email','type'=>'email','req'=>'true','group'=>'requester'),'name'=>array('name'=>'name','label'=>'Name','req'=>'true','type'=>'name','group'=>'requester'),'phone'=>array('name'=>'phone','label'=>'Phone','type'=>'phone','group'=>'requester','custom'=>true));   
  $fields['organization_id']=array('name'=>'organization_id','label'=>'Organization ID','type'=>'number');
  if($module == 'ticket'){
 $fields['organization_id']=array('name'=>'organization_id','label'=>'Organization ID','type'=>'number');
 $fields['brand_id']=array('name'=>'brand_id','label'=>'Brand ID','type'=>'number','custom'=>true);
 $fields['group_id']=array('name'=>'group_id','label'=>'Group ID','type'=>'number','custom'=>true);  
 $fields['id']=array('name'=>'id','label'=>'ID','type'=>'number','custom'=>true);  
 $fields['email_ccs']=array('name'=>'email_ccs','label'=>'Email CCs','type'=>'comma_separated_emails','custom'=>true);  
 $fields['submitter_id']=array('name'=>'submitter_id','label'=>'Submitter ID','type'=>'number','custom'=>true);  
 $fields['ticket_form_id']=array('name'=>'ticket_form_id','label'=>'Ticket Form ID','type'=>'number','custom'=>true);  
  }
  if($module == 'user'){
$fields=array_map(function($v){unset($v['group']); return $v;},$fields); 
$fields['locale']=array('name'=>'locale','label'=>'Locale','type'=>'locale','eg'=>'en-US,de,en'); 
  }
// var_dump($fields);  
$custom_fields_name= $module == 'user' ? 'user_fields' : 'custom_fields';

   foreach($arr[$module.'_fields'] as $k=>$v){ 

$a=array('label'=>$v['title'],'type'=>$v['type']);
$name=$v['id'];
        if($module == 'user'){
 $name=$v['key'];   
}
  $a['name']=(string)$name;      
    $a['custom']=false;
    if(isset($v['removable']) && $v['removable'] == true){
        $a['custom']=true;
    }    
 if(isset($types[$v['type']])){
  $a['name']=$types[$v['type']];   
 }else{
   $a['group']= $custom_fields_name;  
 }
if($v['type'] == 'assignee'){
   $v['required'] = false; 
}
if( isset($eg[$v['type']]) ){
   $a['eg'] = $eg[$v['type']]; 
}
$ops=array();
if(!empty($v['system_field_options'])){
    $ops=$v['system_field_options'];
}else if(!empty($v['custom_field_options'])){
    $ops=$v['custom_field_options'];
}
if(!empty($ops)){
   $ops_temp=array();
    foreach($ops as $op){
   $ops_temp[]=array('value'=>$op['value'],'name'=>$op['name']);     
    }
    $a['options']=$ops_temp;
}
       if( in_array($a['name'],array('comment')) ||  (isset($v['required']) && $v['required'] === true) ){
           $a['req']='true';
       }
$fields[$a['name']]=$a;
}  
    $fields['tags']=array('name'=>'tags','label'=>'Tags','type'=>'text','custom'=>true);  
    $fields['file']=array('name'=>'file','label'=>'File','type'=>'file_url','custom'=>true);  
    $fields['file2']=array('name'=>'file2','label'=>'File 2','type'=>'file_url','custom'=>true);  
    $fields['file3']=array('name'=>'file3','label'=>'File 3','type'=>'file_url','custom'=>true);  
    $fields['file4']=array('name'=>'file4','label'=>'File 4','type'=>'file_url','custom'=>true);  
    $fields['file5']=array('name'=>'file4','label'=>'File 5','type'=>'file_url','custom'=>true);  
}

//var_dump($fields); //die();
 return $fields;
}

public function get_users(){ 

  $users=$this->post_crm('users.json?role[]=admin&role[]=agent');
  ///seprating fields
  $field_info=__('No Users Found');
    if( !empty($users['users']) ){
    $field_info=array();
  foreach($users['users'] as $k=>$field){
  $field_info[$field['id']]=$field['name'].' ( '.$field['email'].' )';     
  }  
  } 

  return $field_info;
}

public function push_object($module,$fields,$meta){ 

//check primary key
 $extra=array();
  $debug = isset($_REQUEST['vx_debug']) && current_user_can('manage_options');
  $event= isset($meta['event']) ? $meta['event'] : '';
  $id= isset($meta['crm_id']) ? $meta['crm_id'] : '';
  $crm_fields= isset($meta['fields']) ? $meta['fields'] : '';
  $req_id='';

  if($debug){ ob_start();}
if(!empty($meta['primary_key']) && !empty($fields[$meta['primary_key']]['value'])){    

$field=$meta['primary_key'];

$search=trim( $fields[$field]['value'] );
if($field !='id'){
    //search object
$path='search.json?sort_by=updated_at&sort_order=desc&query='.urlencode('type:'.$module.' '.$search);
$search_response=$this->post_crm($path,'get');
if(!empty($search_response['results'])){
    $search_response=$search_response['results'];
        if(isset($search_response[0]['id']) && is_array($search_response[0]) ){
          $id=$search_response[0]['id']; 
     if(isset($search_response[0]['requester_id'])){
      $req_id=$search_response[0]['requester_id'];   
     }
     $search_response=$search_response[0];
 }
}
}else{
  $path=$module.'s/'.$search.'.json';
   $search_response=$this->post_crm($path,'get'); 
 if(!empty($search_response[$module]['id'])){
  $id=$search_response[$module]['id'];   
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


  
$extra["body"]=array($field=>$search);
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
 $files=array();
  for($i=1; $i<6; $i++){
$field_n='file';
if($i>1){ $field_n.=$i; }
  if(isset($fields[$field_n]['value'])){
    $files=$this->verify_files($fields[$field_n]['value'],$files);
    unset($fields[$field_n]);  
  }
}

 $status=$action=""; $send_body=true; $arr=array();
 $entry_exists=false; $object_url='';
 $link=""; $error=""; $method='post';

$post=array();
//var_dump($fields); die();
  foreach($fields as $k=>$field){
    
if(!empty($crm_fields[$k]['type'])){
    $v=$fields[$k]['value'];
    if($k == 'comment'){ 
       $v=nl2br($v);   
      }
 $type=$crm_fields[$k]['type'];
 if($type == 'date'){
     $v=date('Y-m-d',strtotime($v));
 }else if($type == 'checkbox'){
     $v=(bool)$v;
 }else if($type == 'decimal'){
  $v=(float)$v;
 }else if($type == 'integer'){
       if(is_string($v)){
     preg_match_all('!\d+!', $v, $matches);
     $v=(float)implode('',$matches[0]);
     }
 }
  if(!empty($crm_fields[$k]['group'])){
      $g=$crm_fields[$k]['group']; 
      if( $g == 'custom_fields'){
      $v=array('id'=>$k,'value'=>$v);   
  if($module == 'user'){  $g='user_fields';    }
  $post[$g][]=$v; 
      }else{
    $post[$g][$k]=$v;       
      }
    
 }else{
    $post[$k]=$v;
 }   
}    
}
//var_dump($post,$fields); die();

 $object=$module; 
if($id == ""){
    //insert new object
$action="Added";  $status="1";
  $object_url=$module.'s';
  if($module == 'ticket'){
      $statu_s='';
      if(isset($post['status'])){
          $statu_s=$post['status'];
      }else if(isset($meta['status'])){
          $statu_s=$meta['status'];
      }
           $priority='';
      if(isset($post['priority'])){
          $priority=$post['priority'];
      }else if(isset($meta['priority'])){
          $priority=$meta['priority'];
      }
       $type_t='';
      if(isset($post['type'])){
          $type_t=$post['type'];
      }else if(isset($meta['type'])){
          $type_t=$meta['type'];
      }
           $assign_t='';
      if(isset($post['assignee_id'])){
          $assign_t=$post['assignee_id'];
      }else if(!empty($meta['owner']) && !empty($meta['user'])){
          $assign_t=$meta['user'];
      } 
       if(isset($post['email_ccs'])){
           $ccs=array_map('trim',explode(',',$post['email_ccs']));
          $ccs_arr=array();
           foreach($ccs as $cc_email){
            $ccs_arr[]=array('user_email'=>$cc_email);   
           }
           $post['email_ccs']=$ccs_arr;
        //  unset($post['email_ccs']);
      }

    if(!empty($type_t)){
        $post['type']=$type_t;
    }
        if(!empty($priority)){
        $post['priority']=$priority;
    }
    if(!empty($statu_s)){
        $post['status']=$statu_s;
    }
      if(!empty($assign_t)){
        $post['assignee_id']=$assign_t;
    }
  
}

}
else{
 $entry_exists=true;
 $method='put';
    if($event == 'add_note'){

$object_url='tickets/'.$id.'.json';
$post=array('comment'=>array('body'=>$fields['body']['value'],'public'=>false)); //
$object='note';
$action="Added";
$status="1";
$module='ticket';
  
}else if(in_array($event,array('delete'))){
 $method='delete';
 $object_url=$module.'s/'.$id.'.json';
     $action="Deleted";  
  $status="5";  
  }else{
    //update object
 $action="Updated"; $status="2";
     if(empty($meta['update'])){
  $object_url=$module.'s/'.$id;
     }
if(empty($meta['no_reply']) && $module == 'ticket'){
      $object_url_body=$module.'s/'.$id.'.json';      
      //$method='post'; 
      $object='thread';
  $post_body=array('comment'=>array('html_body'=> !empty($post['comment']) ? $post['comment'] : '') );  
  if(!empty($meta['private_ticket'])){
$post_body['comment']['public']=false;
}
unset($post['comment']);
$extra['Post Resply']=$arr=$this->post_crm($object_url_body,$method, array($module=> $post_body ) );  
}
     
  }
}
//var_dump($object_url,$extra); die();
if( !empty($object_url)){ 
if(!empty($post['tags'])){
    $post['tags']=array_filter(explode(',',$post['tags']));
    if($status == '2' && !empty($id)){
$extra['adding tags']=$this->post_crm($module.'s/'.$id.'/tags.json','put',array('tags'=>$post['tags']));
    unset($post['tags']);
    }
}
if(isset($post['comment']) && !is_array($post['comment'])){
    
$post['comment']=array('html_body'=>$post['comment']);    
if(!empty($meta['private_ticket'])){
$post['comment']['public']=false;
}
  if(!empty($req_id)){
  $post['comment']['author_id']=$req_id;    
  }
if(!empty($meta['is_private'])){
   $post['comment']['public']=false;  
}  
}

  if(!empty($meta['submitter'])){
  $post['submitter_id']=$meta['submitter'];    
  }

        //process files attachment
if( !empty($files) && function_exists('file_get_contents') ){
$upload_wp = wp_upload_dir();
foreach($files as $k=>$file){
if(empty($file)){ continue; }
$file=str_replace($upload_wp['baseurl'],$upload_wp['basedir'],$file);

$contents=file_get_contents($file); 

$file_arr=explode('/',$file);
if(!empty($contents) && !empty($file_arr) ){
$filename = sanitize_file_name($file_arr[count($file_arr)-1]);
$path='uploads.json?filename='.$filename;
$upload=$this->post_crm($path,'post',$contents );
$extra['uploading file '.$k]=$file;
$extra['upload response '.$k]=$upload;
if(!empty($upload['upload']['token'])){       
$post['comment']['uploads'][]=$upload['upload']['token']; 
} 
}

} 
unset($post['file']);
}

$arr=$this->post_crm($object_url,$method, array($module=> $post ) );
//var_dump($arr,$object_url,$method,$module,$post); die(); 
if(!empty($arr[$module])){
$arr=$arr[$module];    
}


if(isset($arr['id']) ){
$id = $arr['id'];
if($event !='add_note'){ 
 //only main tickets , not replies
$link=$this->url.'agent/'.$module.'s/'.$id;
}
}else { 

if(isset($arr['details'])){
$error=$arr['details'];         
}else if(!empty($arr['description'])){
$error=$arr['description'];    
}else if(!empty($arr)){
$error='Error: '.json_encode($arr); $status='';    
}
if(!empty($error)){
    if(is_array($error)){$error=json_encode($error);}
     $status=''; $id='';   
}
}
}
//var_dump($status,$error); die();
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
  $contents=trim(ob_get_clean());
  if($contents!=""){
  update_option($this->id."_debug",$contents);   
  }
  }
  
       //add entry note
 if(!empty($status) && !empty($meta['__vx_entry_note']) && !empty($id) ){
 $disable_note=$this->post('disable_entry_note',$meta);
   if(!($entry_exists && !empty($disable_note))){
       $entry_note=$meta['__vx_entry_note'];
       
    $object_url='tickets/'.$id.'.json';
$note=array('ticket'=>array('comment'=> array('html_body'=>$entry_note['body'],'public'=>false)));   

$note_response= $this->post_crm( $object_url, 'put',$note);

  $extra['Note Post']=$note;
  $extra['Note Response']=$note_response;

   }  
 }
//var_dump($status); die();
return array("error"=>$error,"id"=>$id,"link"=>$link,"action"=>$action,"status"=>$status,"data"=>$fields,"response"=>$arr,"extra"=>$extra);
}
public function verify_files($files,$old=array()){
        if(!is_array($files)){
        $files_temp=json_decode($files,true);
        if(!is_array($files_temp)){
 $files_temp=array_filter(array_map('trim',explode(',',$files))); 
}
     if(is_array($files_temp)){
    $files=$files_temp;     
     }else if (!empty($files)){ //&& filter_var($files,FILTER_VALIDATE_URL)
      $files=array($files);   
     }else{
      $files=array();    
     }   
    }
    if(is_array($files) && is_array($old) && !empty($old)){
   $files=array_merge($old,$files);     
    }
  return $files;  
}

public function get_entry($module,$id){

    $url=$module.'s/'.$id.'.json';
     $arr= $this->post_crm($url);
     if(!empty($arr[$module]) && is_array($arr[$module])){
      $arr=$arr[$module];   
     }
  $custom_fields= $module == 'user' ? 'user_fields' : 'custom_fields';   
     if(!empty($arr[$custom_fields])){
 $arr=array_merge($arr,$arr[$custom_fields]);   
}
     if(!empty($arr['requester_id'])){
  $req_id=$arr['requester_id'];
     $url='users/'.$req_id;
     $customer= $this->post_crm($url);
     
       if(!empty($customer['user']) && is_array($customer['user'])){
      $customer=$customer['user'];   
     }  
     if(!empty($customer['user_fields'])){
 $customer=array_merge($customer,$customer['user_fields']);   
}
if(!empty($customer)){
  $arr=array_merge($arr,$customer);    
}
}

//var_dump($arr); die();
  return $arr;     
}

public function post_crm($path,$method='get',$body=''){

    $head=array('Authorization'=>'Basic '.base64_encode($this->email.'/token:'.$this->token));
if(!empty($body)){
    if(is_array($body)){
    $body=json_encode($body);
    $head['Content-Type']='application/json';    
    }else{
   $head['Content-Type']='application/binary';     
    } }
    $path=$this->url.'api/v2/'.$path; //.'.json'
    
            $args = array(
            'body' => $body,
            'headers'=> $head,
            'method' => strtoupper($method), // GET, POST, PUT, DELETE, etc.
           // 'sslverify' => true,
            'timeout' => 20,
        );
$response = wp_remote_request($path, $args);

$json=wp_remote_retrieve_body($response);

 return json_decode($json,true); 

}

}
}
?>