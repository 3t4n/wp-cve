<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       $('#auction-resend-<?php echo $single_auction->ID;?>').click(function(){
       
       $('#auction-resend-<?php echo $single_auction->ID;?>').html("<?php _e("Sending", "wdm-ultimate-auction"); ?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__));?>' />");
	
	var data = {
		action:'resend_auction_email',
        a_em:'<?php echo $winner_email;?>',
		a_bid:'<?php echo $winner_bid;?>',
		a_id:'<?php echo $single_auction->ID;?>',
		a_title:'<?php echo esc_js($single_auction->post_title);?>',
		a_cont:'<?php echo esc_js($single_auction->post_content);?>',
		a_url: '<?php echo get_post_meta($single_auction->ID, 'current_auction_permalink',true);?>'
	    };
	    $.post(ajaxurl, data, function(response) {
	      $('#auction-resend-<?php echo $single_auction->ID;?>').html("<?php _e("Resend", "wdm-ultimate-auction");?>");
	      alert(response);
	      window.location.reload();
	    });
	
        return false;
	 
        });
    });
</script>