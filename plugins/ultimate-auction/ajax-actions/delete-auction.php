<script type="text/javascript">
jQuery(document).ready(function($){
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       $('#wdm-delete-auction-<?php echo $single_auction->ID;?>').click(function(){
        
        var cnf = confirm("<?php _e("Are you sure to delete this auction? All data related to this auction (including bids and attachments) will be deleted.", "wdm-ultimate-auction");?>");
        
        if(cnf == true){
        $(this).html("<?php _e("Deleting", "wdm-ultimate-auction"); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");
	var data = {
		action:'delete_auction',
                del_id:'<?php echo $single_auction->ID;?>',
                auc_title: '<?php echo esc_js($single_auction->post_title);?>',
                force_del:'yes',
                uwaajax_nonce: '<?php echo wp_create_nonce('uwaajax_nonce'); ?>'

	    };
	    $.post(ajaxurl, data, function(response) {
                $('#wdm-delete-auction-<?php echo $single_auction->ID;?>').html("<?php _e("Delete", "wdm-ultimate-auction");?>");
                alert(response);
                window.location.reload();
	    });
        }
        return false;
	 
        });
       
    });
</script>