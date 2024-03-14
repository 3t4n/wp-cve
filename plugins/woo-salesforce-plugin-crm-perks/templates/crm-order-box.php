  <?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 } 
        $comments=false; 
 
  if(isset($notes) && is_array($notes) && count($notes)>0){
      $comments=true;
      ?>
  <style type="text/css">
  ul.vx_notes {
    padding: 2px 0 0
}
ul.vx_notes li .note_content {
    padding: 10px;
    background: #efefef;
    position: relative
}
ul.vx_notes li .note_content p {
    margin: 0;
    padding: 0;
    word-wrap: break-word
}
ul.vx_notes li p.meta {
    padding: 10px;
    color: #999;
    margin: 0;
    font-size: 11px
}
ul.vx_notes li a.delete_note {
    color: #a00
}
ul.vx_notes li .note_content:after {
    content: "";
    display: block;
    position: absolute;
    bottom: -10px;
    left: 20px;
    width: 0;
    height: 0;
    border-width: 10px 10px 0 0;
    border-style: solid;
    border-color: #efefef transparent
}
ul.vx_notes li.customer-note .note_content {
    background: #d7cad2
}
ul.vx_notes li.customer-note .note_content:after {
    border-color: #d7cad2 transparent
}
  </style>    
  <ul class="vx_notes">
  <?php
  foreach($notes as $time=>$note){
  if(!empty($note['time']) && is_array($note)){
  $note['id']=$note['crm_id'];      
  $offset=$this->time_offset();     
  $time=strtotime($this->post('time',$note))+$offset;

  ?>
  <li class="note">
  <div class="note_content">
  <p><?php
  echo $this->format_note($note,true);  ?></p></div>
  <p class="meta"><span class="exact-date"><?php 
  echo "Sent on ".date('d-M-Y @ h:i:s A',$time);
  ?></span></p>
  </li>
  <?php
  }
  }
  ?>
  </ul>
  <?php
  }
  if(isset($_GET['vx_debug'])){
  ?>
  <input type="hidden" name="vx_debug" value="<?php echo esc_attr($this->post('vx_debug')) ?>">
  <?php
  }
  ?>
  <p>
  <button class="button" type="submit" name="<?php echo esc_attr($this->id) ?>_send" title="<?php esc_html_e("Send to Salesforce",'woocommerce-salesforce-crm')?>" value="yes"><?php esc_html_e("Send to Salesforce",'woocommerce-salesforce-crm')?></button>
  <?php
      if($comments ){
  $log_url=admin_url( 'admin.php?page='.$this->id.'_log&order_id='.$post_id); 
  ?>
  <a href="<?php echo esc_url($log_url) ?>" class="button" title="<?php esc_html_e("Go to Logs",'woocommerce-salesforce-crm')?>"><?php esc_html_e("Go to Logs",'woocommerce-salesforce-crm')?></a>
  <?php
      }
  ?>
  </p>