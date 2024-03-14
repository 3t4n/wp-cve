<?php
/*
 * @link       http://www.apoyl.com
 * @since      1.0.0
 * @package    Apoyl_Baidupush
 * @subpackage Apoyl_Baidupush/public/partials
 * @author     凹凸曼 <jar-c@163.com>
 *
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<script>
    jQuery(document).ready(function() {

        jQuery('.apoyl_baidupush_btn').click(function() {
            var aid=jQuery(this).attr('attraid');
        	jQuery('.apoyl_baidupush_tips').html('<img src="<?php echo  plugin_dir_url(__FILE__).'../img/wpspin.gif';?>" height=15 style="vertical-align:text-bottom;"/>');
        	jQuery.ajax({
  			  type: "POST",
				  url:'<?php echo $ajaxurl;?>',
    			  data:{
        			  'action':'ajaxpush',
    			  	  'aid':aid,
    			  	  'subject':'<?php echo get_the_title();?>',
    			  	  'url':'<?php  echo $url;?>',
    			  	  '_ajax_nonce':'<?php echo $nonce;?>',
    			  },
    			  async: true,
    			  success: function (data) { 
        			  if(data==1){
            			  jQuery('.apoyl_baidupush_tips').replaceWith('<img src="<?php echo  plugin_dir_url(__FILE__).'../img/baidu.png';?>" height=20 title="<?php _e('pushsuccess','apoyl-baidupush')?>" style="vertical-align:text-bottom;" />');
        			  }else{
            			  jQuery('.apoyl_baidupush_tips').html('<?php _e('pushfail','apoyl-baidupush')?>');
        			  }
    			  },
    			  error: function(data){
    				  jQuery('.apoyl_baidupush_tips').html('<?php _e('pushfail','apoyl-baidupush')?>');
    			  }
    			  
    			})	
        });
 
    });
</script>