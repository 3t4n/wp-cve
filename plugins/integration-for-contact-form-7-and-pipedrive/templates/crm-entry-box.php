<?php
  if ( ! defined( 'ABSPATH' ) ) {
     exit;
 }  ?>

  <?php
        $comments=false; 
  if( is_array($log_entry) && count($log_entry)>0){
      $objects=$this->get_all_objects();
      $log=$this->verify_log($log_entry,$objects);
      $comments=true;
echo $this->format_log_msg($log);
  }
  if(isset($_GET['vx_debug'])){
  ?>
  <input type="hidden" name="vx_debug" value="<?php echo esc_attr($_REQUEST['vx_debug']); ?>">
  <?php
  }
  ?>
<div class="box_btns_div">
  <button class="button" type="submit" name="<?php echo esc_attr($this->id) ?>_send_btn" value="yes"><?php echo esc_html__("Send to Pipedrive",'cf7-pipedrive')?></button>
  <?php
      if($comments ){
  ?>
  <a href="<?php echo esc_url($log_url); ?>" class="button"><?php echo esc_html__("Go to Logs",'cf7-pipedrive')?></a>
  <?php
      }
  ?>
  </div>
