<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       $('#wdm-end-auction-<?php echo $single_auction->ID;?>').click(function(){
        
        var cnf = confirm("<?php _e("Are you sure to end this auction?", "wdm-ultimate-auction");?>");
        
        if(cnf == true){
        $(this).html("<?php _e("Ending", "wdm-ultimate-auction"); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__));?>' />");       
	var data = {
		action:'end_auction',
                end_id:'<?php echo $single_auction->ID;?>',
                end_title: '<?php echo esc_js($single_auction->post_title);?>',
                uwaajax_nonce: '<?php echo wp_create_nonce('uwaajax_nonce'); ?>' 
                
	    };
	    $.post(ajaxurl, data, function(response) {
                $('#wdm-end-auction-<?php echo $single_auction->ID;?>').html("<?php _e("End Auction", "wdm-ultimate-auction");?>");
                alert(response);
                window.location.reload();
	    });
        }
        return false;
	 
        });
       
    });
</script>