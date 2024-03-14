<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       
	var data = {
		action:'send_auction_email',
        auc_email:'<?php echo $winner_email;?>',
		auc_bid:'<?php echo $winner_bid;?>',
		auc_id:'<?php echo $ca->ID;?>',
		auc_title:'<?php echo esc_js($ca->post_title);?>',
		auc_cont:'<?php echo esc_js($ca->post_content);?>',
		auc_url: '<?php echo $return_url;?>'
	    };
	    /*$.post(ajaxurl, data, function(response) {
	      
	    });*/
$.ajax({
          type: 'POST',
          url: ajaxurl,
          data: data,
          async:false,
          success: function(response) {}
        });
    
    });
</script>