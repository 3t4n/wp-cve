<script type="text/javascript">
jQuery(document).ready(function($){
       
       $('.wp-list-table th.column-user').prepend('<input class="wdm_select_all_chk" type="checkbox" style="float: left; margin: 8px 0 0 8px;" />');
       
       var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
       
        $('.wdm_select_all_chk').live("click", function(){ 
            if($(this).is(':checked')){
                $('.wdm_chk_auc_act').attr('checked','checked');
                $('.wdm_select_all_chk').attr('checked','checked');
            }
            else{
                $('.wdm_chk_auc_act').removeAttr('checked');
                $('.wdm_select_all_chk').removeAttr('checked','checked');
            }
        });

       
        $('#wdm_mult_chk_del').live("click",function(){ 
        
            var all_auc = new Array();
        
        $('.wdm_chk_auc_act').each(function(){
            
            if($(this).is(':checked')){
                all_auc.push($(this).val());
            }
            
        });
	
        var aaucs = all_auc.join();
        if(aaucs == '' || aaucs == null){
            alert("<?php _e("Please select auction(s) to delete.", "wdm-ultimate-auction");?>");
            return false;
        }
        else
            var cnf = confirm("<?php _e("Are you sure to delete selected auctions? All data related to the auctions (including bids and attachments) will be deleted.", "wdm-ultimate-auction");?>");
        
        if(cnf == true){
        $('.wdmua_del_stats').html("<?php _e("Deleting", "wdm-ultimate-auction"); echo ' ';?> <img src='<?php echo plugins_url('/img/ajax-loader.gif', dirname(__FILE__) );?>' />");       
	var data = {
		action:'multi_delete_auction',
                del_ids:aaucs,
                force_del:'yes',
                uwaajax_nonce: '<?php echo wp_create_nonce('uwaajax_nonce'); ?>'
                
	    };
	    $.post(ajaxurl, data, function(response) {
                $('.wdmua_del_stats').html('');
                alert(response);
                window.location.reload();
                $('.wdm_select_all_chk, .wdm_chk_auc_act').removeAttr('checked');
	    });
        }
        return false;
	 
        });
       
        });
</script>
       