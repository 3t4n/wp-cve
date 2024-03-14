<?php if( !defined('ABSPATH') ){ exit();}
global $wpdb;
if(isset($_GET['msg']) && $_GET['msg']=='smap_pack_updated'){
	?>
<div class="system_notice_area_style1" id="system_notice_area">
<?php $smap_word="SMAP";
      $smap_update_msg=sprintf(__('%s Package updated successfully.','social-media-auto-publish'),$smap_word); 
 echo $smap_update_msg; ?>
&nbsp;&nbsp;&nbsp;<span id="system_notice_area_dismiss"><?php _e('Dismiss','social-media-auto-publish'); ?>
</span>
</div>
<?php
}
$free_plugin_source='smap';$xyz_smap_licence_key='';
$domain_name=trim(get_option('siteurl'));
$xyzscripts_hash_val=trim(get_option('xyz_smap_xyzscripts_hash_val'));
$xyzscripts_user_id=trim(get_option('xyz_smap_xyzscripts_user_id'));
$manage_auth_parameters=array(
		'xyzscripts_user_id'=>$xyzscripts_user_id,
		'free_plugin_source'=>$free_plugin_source
);
if ($xyzscripts_user_id=='')
{ ?>
<b> <?php $smap_word_smapsolution="smapsolutions";
	  $smap_auth_msg=sprintf(__('Please authorize %s app under Facebook/LinkedIn settings to access this page.','social-media-auto-publish'),$smap_word_smapsolution); 
     echo $smap_auth_msg; ?> </b>
	<?php return;
}
?>
<style type="text/css">
.widefat {border: 1px solid #eeeeee!important;
margin: 0px !important;
border-bottom: 3px solid #00a0d2 !important;
margin-bottom:5px;}

.widefat th {border:1px solid #ffffff !important; background-color:#00a0d2; color:#ffffff; margin:0px !important;  padding-top: 12px;
padding-bottom: 12px;
text-align: left;}

.widefat td, .widefat th {
color:#2f2f2f ;
	padding: 12px 5px;
	margin: 0px;
}

.widefat tr{ border: 1px solid #ddd;}

.widefat tr:nth-child(even){background-color: #dddddd !important;}

.widefat tr:hover {background-color: #cccccc;}


.delete_auth_entry,.delete_ln_auth_entry,.delete_tw_auth_entry,.delete_ig_auth_entry,.delete_inactive_fb_entry,.delete_inactive_ln_entry,.delete_inactive_ig_entry,.delete_inactive_tw_entry{background-color: #00a0d2;
border: none;
padding: 5px 10px;
color: #fff;
border-radius: 2px;
outline:0;
}

.delete_auth_entry:hover,.delete_ln_auth_entry:hover,.delete_tw_auth_entry:hover,.delete_ig_auth_entry:hover{background-color:#008282;}

.select_box
{
display: block;
padding: 10px;
background-color: #ddd;
color: #2f2f2f;
width: 96.8%;
margin-bottom: 1px;
}
.xyz_smap_plan_div{
float:left;
background-color:#b7b6b6;
border-radius:3px;
padding: 2px;
color: white;
margin-left: 1px;
}
.xyz_smap_plan_label{
	font-size: 13px;
    color: #ffffff;
    font-weight: 500;
    float: left;
    padding: 2px;
    background-color: #30a0d2;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	document.getElementById("xyz_smap_default_fbauth_tab").click();	
	jQuery('#auth_entries_div').show();
	jQuery("#show_all").attr('checked', true);

	jQuery("#show_all").click(function(){
		jQuery('#smap_manage_auth_table tr:has(td.diff_domain)').show();
		jQuery('#smap_manage_auth_table tr:has(td.same_domain)').show();
	});
	jQuery("#show_same_domain").click(function(){
		jQuery('#smap_manage_auth_table tr:has(td.diff_domain)').hide();
		jQuery('#smap_manage_auth_table tr:has(td.same_domain)').show();
	});
	jQuery("#show_diff_domain").click(function(){
		jQuery('#smap_manage_auth_table tr:has(td.diff_domain)').show();
		jQuery('#smap_manage_auth_table tr:has(td.same_domain)').hide();
	});

	jQuery(".delete_auth_entry").off('click').on('click', function() {
	    var auth_id=jQuery(this).attr("data-id");
	    jQuery("#show-del-icon_"+auth_id).hide();
	    jQuery("#ajax-save_"+auth_id).show();
	    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
	    var plugin_src=jQuery(this).attr("data-plugin-src");
	    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
		var account_id =jQuery(this).attr("data-account_id");
	    var xyz_smap_del_entries_nonce= '<?php echo wp_create_nonce('xyz_smap_del_entries_nonce');?>';
	    var dataString = {
	    	action: 'xyz_smap_del_entries',
	    	auth_id: auth_id ,
	    	xyzscripts_id: xyzscripts_id,
	    	xyzscripts_user_hash: xyzscripts_user_hash,
	    	plugin_src:plugin_src,
	    	dataType: 'json',
	    	_wpnonce: xyz_smap_del_entries_nonce
	    };
	    jQuery.post(ajaxurl, dataString ,function(data) {
	    	jQuery("#ajax-save_"+auth_id).hide();
	    	 if(data==1)
			       	alert(xyz_script_smap_var.alert3);
			else{
	    	var data=jQuery.parseJSON(data);
	    	if(data.status==1){
	    		jQuery(".tr_"+auth_id).remove();

	    		if(jQuery('#system_notice_area').length==0)
	    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
	    			jQuery("#system_notice_area").html(xyz_script_smap_var.html1); 
 			  	jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
	    			jQuery("#system_notice_area").show();
	    			jQuery('#system_notice_area_dismiss').click(function() {
	    				jQuery('#system_notice_area').animate({
	    					opacity : 'hide',
	    					height : 'hide'
	    				}, 500);
	    			});

	    	}
	    	else if(data.status==0 )
	    	{
	    		jQuery("#show_err_"+auth_id).append(data.msg );
	    	}
	    }
	    });
				});
/////////////////////////////////LinkedIn Ajax//////////////////////////////////////////////
	jQuery('#ln_auth_entries_div').show();
	jQuery("#ln_show_all").attr('checked', true);

	jQuery("#ln_show_all").click(function(){
		jQuery('#ln_smap_manage_auth_table tr:has(td.ln_diff_domain)').show();
		jQuery('#ln_smap_manage_auth_table tr:has(td.ln_same_domain)').show();
	});
		jQuery("#ln_show_same_domain").click(function(){
			jQuery('#ln_smap_manage_auth_table tr:has(td.ln_diff_domain)').hide();
			jQuery('#ln_smap_manage_auth_table tr:has(td.ln_same_domain)').show();
		});
			jQuery("#ln_show_diff_domain").click(function(){
				jQuery('#ln_smap_manage_auth_table tr:has(td.ln_diff_domain)').show();
				jQuery('#ln_smap_manage_auth_table tr:has(td.ln_same_domain)').hide();
			});
				jQuery(".delete_ln_auth_entry").off('click').on('click', function() {
	    var ln_auth_id=jQuery(this).attr("data-auth_id");
	    var plugin_src=jQuery(this).attr("data-plugin-src");
	    jQuery("#show-del-icon_"+ln_auth_id).hide();
	    jQuery("#ajax-save_"+ln_auth_id).show();
	    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
	    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
		var account_id =jQuery(this).attr("data-ln_account_id");
	    var xyz_smap_del_entries_ln_nonce= '<?php echo wp_create_nonce('xyz_smap_del_entries_ln_nonce');?>';
	    var dataString = {
	    	action: 'xyz_smap_del_ln_entries',
	    	ln_auth_id: ln_auth_id ,
	    	account_id: account_id,
	    	xyzscripts_id: xyzscripts_id,
	    	plugin_src:plugin_src,
	    	xyzscripts_user_hash: xyzscripts_user_hash,
	    	dataType: 'json',
	    	_wpnonce: xyz_smap_del_entries_ln_nonce
	    };
	    jQuery.post(ajaxurl, dataString ,function(data) {
	    	jQuery("#ajax-save_"+ln_auth_id).hide();
	    	 if(data==1)
			       	alert(xyz_script_smap_var.alert3);
			else{
	    	var data=jQuery.parseJSON(data);
	    	if(data.status==1){
	    		jQuery(".tr_"+ln_auth_id).remove();
	    		if(jQuery('#system_notice_area').length==0)
	    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
	    			jQuery("#system_notice_area").html(xyz_script_smap_var.html1); 
 			  	jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
	    			jQuery("#system_notice_area").show();
	    			jQuery('#system_notice_area_dismiss').click(function() {
	    				jQuery('#system_notice_area').animate({
	    					opacity : 'hide',
	    					height : 'hide'
	    				}, 500);
	    			});
	    	}
	    	else if(data.status==0 )
	    	{
	    		jQuery("#show_err_"+ln_auth_id).append(data.msg );
	    	}
	    }
	    });
	});
/////////////////////////////////LinkedIn Ajax//////////////////////////////////////////////	

/////////////////////////////////Twitter Ajax//////////////////////////////////////////////
	jQuery('#tw_auth_entries_div').show();
	jQuery("#tw_show_all").attr('checked', true);

	jQuery("#tw_show_all").click(function(){
		jQuery('#tw_smap_manage_auth_table tr:has(td.tw_diff_domain)').show();
		jQuery('#tw_smap_manage_auth_table tr:has(td.tw_same_domain)').show();
	});
		jQuery("#tw_show_same_domain").click(function(){
			jQuery('#tw_smap_manage_auth_table tr:has(td.tw_diff_domain)').hide();
			jQuery('#tw_smap_manage_auth_table tr:has(td.tw_same_domain)').show();
		});
			jQuery("#tw_show_diff_domain").click(function(){
				jQuery('#tw_smap_manage_auth_table tr:has(td.tw_diff_domain)').show();
				jQuery('#tw_smap_manage_auth_table tr:has(td.tw_same_domain)').hide();
			});
				jQuery(".delete_tw_auth_entry").off('click').on('click', function() {
	    var tw_auth_id=jQuery(this).attr("data-auth_id");
	    var plugin_src=jQuery(this).attr("data-plugin-src");
	    jQuery("#show-del-icon_"+tw_auth_id).hide();
	    jQuery("#ajax-save_"+tw_auth_id).show();
	    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
	    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
		var account_id =jQuery(this).attr("data-tw_account_id");
	    var xyz_smap_del_entries_tw_nonce= '<?php echo wp_create_nonce('xyz_smap_del_entries_tw_nonce');?>';
	    var dataString = {
	    	action: 'xyz_smap_del_tw_entries',
	    	tw_auth_id: tw_auth_id ,
	    	account_id: account_id,
	    	xyzscripts_id: xyzscripts_id,
	    	plugin_src:plugin_src,
	    	xyzscripts_user_hash: xyzscripts_user_hash,
	    	dataType: 'json',
	    	_wpnonce: xyz_smap_del_entries_tw_nonce
	    };
	    jQuery.post(ajaxurl, dataString ,function(data) {
	    	jQuery("#ajax-save_"+tw_auth_id).hide();
	    	 if(data==1)
			       	alert(xyz_script_smap_var.alert3);
			else{
	    	var data=jQuery.parseJSON(data);
	    	if(data.status==1){
	    		jQuery(".tr_"+tw_auth_id).remove();
	    		if(jQuery('#system_notice_area').length==0)
	    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
	    			jQuery("#system_notice_area").html(xyz_script_smap_var.html1); 
 			  	jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
	    			jQuery("#system_notice_area").show();
	    			jQuery('#system_notice_area_dismiss').click(function() {
	    				jQuery('#system_notice_area').animate({
	    					opacity : 'hide',
	    					height : 'hide'
	    				}, 500);
	    			});
	    	}
	    	else if(data.status==0 )
	    	{
	    		jQuery("#show_err_"+tw_auth_id).append(data.msg );
	    	}
	    }
	    });
	});
/////////////////////////////////Twitter Ajax//////////////////////////////////////////////				
			
			
/////////////////////////////////Instagram Ajax//////////////////////////////////////////////
	jQuery('#ig_auth_entries_div').show();
	jQuery("#ig_show_all").attr('checked', true);

	jQuery("#ig_show_all").click(function(){
		jQuery('#ig_smap_manage_auth_table tr:has(td.ig_diff_domain)').show();
		jQuery('#ig_smap_manage_auth_table tr:has(td.ig_same_domain)').show();
	});
		jQuery("#ig_show_same_domain").click(function(){
			jQuery('#ig_smap_manage_auth_table tr:has(td.ig_diff_domain)').hide();
			jQuery('#ig_smap_manage_auth_table tr:has(td.ig_same_domain)').show();
		});
			jQuery("#ig_show_diff_domain").click(function(){
				jQuery('#ig_smap_manage_auth_table tr:has(td.ig_diff_domain)').show();
				jQuery('#ig_smap_manage_auth_table tr:has(td.ig_same_domain)').hide();
			});
				jQuery(".delete_ig_auth_entry").off('click').on('click', function() {
	    var ig_auth_id=jQuery(this).attr("data-auth_id");
	    var plugin_src=jQuery(this).attr("data-plugin-src");
	    jQuery("#show-del-icon_"+ig_auth_id).hide();
	    jQuery("#ajax-save_"+ig_auth_id).show();
	    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
	    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
		var account_id =jQuery(this).attr("data-ig_account_id");
	    var xyz_smap_del_entries_ig_nonce= '<?php echo wp_create_nonce('xyz_smap_del_entries_ig_nonce');?>';
	    var dataString = {
	    	action: 'xyz_smap_del_ig_entries',
	    	ig_auth_id: ig_auth_id ,
	    	account_id: account_id,
	    	xyzscripts_id: xyzscripts_id,
	    	plugin_src:plugin_src,
	    	xyzscripts_user_hash: xyzscripts_user_hash,
	    	dataType: 'json',
	    	_wpnonce: xyz_smap_del_entries_ig_nonce
	    };
	    jQuery.post(ajaxurl, dataString ,function(data) {
	    	jQuery("#ajax-save_"+ig_auth_id).hide();
	    	 if(data==1)
			       	alert(xyz_script_smap_var.alert3);
			else{
	    	var data=jQuery.parseJSON(data);
	    	if(data.status==1){
	    		jQuery(".tr_"+ig_auth_id).remove();
	    		if(jQuery('#system_notice_area').length==0)
	    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
	    			jQuery("#system_notice_area").html(xyz_script_smap_var.html1); 
 			  	jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
	    			jQuery("#system_notice_area").show();
	    			jQuery('#system_notice_area_dismiss').click(function() {
	    				jQuery('#system_notice_area').animate({
	    					opacity : 'hide',
	    					height : 'hide'
	    				}, 500);
	    			});
	    	}
	    	else if(data.status==0 )
	    	{
	    		jQuery("#show_err_"+ig_auth_id).append(data.msg );
	    	}
	    }
	    });
	});
/////////////////////////////////Instagram Ajax//////////////////////////////////////////////				
			
jQuery("input[name='domain_selection']").click(function(){//show_diff_domain
	numOfVisibleRows = jQuery('#smap_manage_auth_table tr:visible').length;
	//if (this.id == 'show_diff_domain') 
	//	{
		if(numOfVisibleRows==1)
		{	
			jQuery('.xyz_smap_manage_auth_th_fb').hide();
			jQuery('#xyz_smap_no_auth_entries').show();
		}
		else{	
			jQuery('.xyz_smap_manage_auth_th_fb').show();
			jQuery('#xyz_smap_no_auth_entries').hide();
		}
//	}
});		
jQuery("input[name='ln_domain_selection']").click(function(){//show_diff_domain
	numOfVisibleLnRows = jQuery('#ln_smap_manage_auth_table tr:visible').length;
	//if (this.id == 'show_diff_domain') 
	//	{
		if(numOfVisibleLnRows==1)
		{	
			jQuery('.xyz_smap_manage_auth_th_ln').hide();
			jQuery('#xyz_smap_no_auth_entries_ln').show();
		}
		else{	
			jQuery('.xyz_smap_manage_auth_th_ln').show();
			jQuery('#xyz_smap_no_auth_entries_ln').hide();
		}
//	}
});	
///////////////////////DELETE INACTIVE FB ACC//////////////////////////////
jQuery(".delete_inactive_fb_entry").off('click').on('click', function() {
    var fb_userid=jQuery(this).attr("data-fbid");
    var tr_iterationid=jQuery(this).attr("data-iterationid");
    jQuery("#show-del-icon-inactive-fb_"+tr_iterationid).hide();
    jQuery("#ajax-save-inactive-fb_"+tr_iterationid).show();
    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
    var xyz_smap_del_fb_entries_nonce= '<?php echo wp_create_nonce('xyz_smap_del_fb_entries_nonce');?>';
    var dataString = {
    	action: 'xyz_smap_del_fb_entries',
    	tr_iterationid: tr_iterationid ,
    	xyzscripts_id: xyzscripts_id,
    	xyzscripts_user_hash: xyzscripts_user_hash,
    	fb_userid: fb_userid,
    	dataType: 'json',
    	_wpnonce: xyz_smap_del_fb_entries_nonce
    };
    jQuery.post(ajaxurl, dataString ,function(data) {
    	jQuery("#ajax-save-inactive-fb_"+tr_iterationid).hide();
    	 if(data==1)
		       	alert(xyz_script_smap_var.alert3);
		else{
    	var data=jQuery.parseJSON(data);
    	if(data.status==1){
    		jQuery(".tr_inactive"+tr_iterationid).remove();
    		if(jQuery('#system_notice_area').length==0)
    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html(xyz_script_smap_var.html4); 
 			jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
    			jQuery("#system_notice_area").show();
    			jQuery('#system_notice_area_dismiss').click(function() {
    				jQuery('#system_notice_area').animate({
    					opacity : 'hide',
    					height : 'hide'
    				}, 500);
    			});
    	}
    	else if(data.status==0 )
    	{
    		jQuery("#show_err_inactive_fb_"+tr_iterationid).append(data.msg );
    	}
    }
				});
  });
//////////////////////////////DELETE INACTIVE LN ACCOUNT///////////
jQuery(".delete_inactive_ln_entry").off('click').on('click', function() {
    var ln_userid=jQuery(this).attr("data-lnid");
    var tr_iterationid=jQuery(this).attr("data-ln_iterationid");
    jQuery("#show-del-icon-inactive-ln_"+tr_iterationid).hide();
    jQuery("#ajax-save-inactive-ln_"+tr_iterationid).show();
    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
    var xyz_smap_del_lnuser_entries_nonce= '<?php echo wp_create_nonce('xyz_smap_del_lnuser_entries_nonce');?>';
    var dataString = {
    	action: 'xyz_smap_del_lnuser_entries',
    	tr_iterationid: tr_iterationid ,
    	xyzscripts_id: xyzscripts_id,
    	xyzscripts_user_hash: xyzscripts_user_hash,
    	ln_userid: ln_userid,
    	dataType: 'json',
    	_wpnonce: xyz_smap_del_lnuser_entries_nonce
    };
    jQuery.post(ajaxurl, dataString ,function(data) {
    	jQuery("#ajax-save-inactive-ln_"+tr_iterationid).hide();
    	 if(data==1)
		       	alert(xyz_script_smap_var.alert3);
		else{

    	var data=jQuery.parseJSON(data);
    	if(data.status==1){
    		jQuery(".tr_inactive"+tr_iterationid).remove();
    		if(jQuery('#system_notice_area').length==0)
    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html(xyz_script_smap_var.html5); 
 			jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
    			jQuery("#system_notice_area").show();
    			jQuery('#system_notice_area_dismiss').click(function() {
    				jQuery('#system_notice_area').animate({
    					opacity : 'hide',
    					height : 'hide'
    				}, 500);
    			});
    	}
    	else if(data.status==0 )
    	{
    		jQuery("#show_err_inactive_ln_"+tr_iterationid).append(data.msg );
    	}
    }
    });
  });
///////////////////////////////////////////////////////////////////
//////////////////////////////DELETE INACTIVE TW ACCOUNT///////////
jQuery(".delete_inactive_tw_entry").off('click').on('click', function() {
    var inactive_tw_userid=jQuery(this).attr("data-twid");
    var tr_iterationid=jQuery(this).attr("data-tw_iterationid");
    jQuery("#show-del-icon-inactive-tw_"+tr_iterationid).hide();
    jQuery("#ajax-save-inactive-tw_"+tr_iterationid).show();
    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
    var xyz_smap_del_twuser_entries_nonce= '<?php echo wp_create_nonce('xyz_smap_del_twuser_entries_nonce');?>';
    var dataString = {
    	action: 'xyz_smap_del_twuser_entries',
    	tr_iterationid: tr_iterationid ,
    	xyzscripts_id: xyzscripts_id,
    	xyzscripts_user_hash: xyzscripts_user_hash,
    	inactive_tw_userid: inactive_tw_userid,
    	dataType: 'json',
    	_wpnonce: xyz_smap_del_twuser_entries_nonce
    };
    jQuery.post(ajaxurl, dataString ,function(data) {
    	jQuery("#ajax-save-inactive-tw_"+tr_iterationid).hide();
    	 if(data==1)
		       	alert(xyz_script_smap_var.alert3);
		else{

    	var data=jQuery.parseJSON(data);
    	if(data.status==1){
    		jQuery(".tr_inactive"+tr_iterationid).remove();
    		if(jQuery('#system_notice_area').length==0)
    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html(xyz_script_smap_var.html6); 
 			jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
    			jQuery("#system_notice_area").show();
    			jQuery('#system_notice_area_dismiss').click(function() {
    				jQuery('#system_notice_area').animate({
    					opacity : 'hide',
    					height : 'hide'
    				}, 500);
    			});
    	}
    	else if(data.status==0 )
    	{
    		jQuery("#show_err_inactive_tw_"+tr_iterationid).append(data.msg );
    	}
    }
    });
  });
///////////////////////////////////////////////////////////////////
//////////////////////////////DELETE INACTIVE IG ACCOUNT///////////
jQuery(".delete_inactive_ig_entry").off('click').on('click', function() {
    var inactive_ig_userid=jQuery(this).attr("data-igid");
    var tr_iterationid=jQuery(this).attr("data-ig_iterationid");
    jQuery("#show-del-icon-inactive-ig_"+tr_iterationid).hide();
    jQuery("#ajax-save-inactive-ig_"+tr_iterationid).show();
    var xyzscripts_user_hash=jQuery(this).attr("data-xyzscripts_hash");
    var xyzscripts_id=jQuery(this).attr("data-xyzscriptsid");
    var xyz_smap_del_iguser_entries_nonce= '<?php echo wp_create_nonce('xyz_smap_del_iguser_entries_nonce');?>';
    var dataString = {
    	action: 'xyz_smap_del_iguser_entries',
    	tr_iterationid: tr_iterationid ,
    	xyzscripts_id: xyzscripts_id,
    	xyzscripts_user_hash: xyzscripts_user_hash,
    	inactive_ig_userid: inactive_ig_userid,
    	dataType: 'json',
    	_wpnonce: xyz_smap_del_iguser_entries_nonce
    };
    jQuery.post(ajaxurl, dataString ,function(data) {
    	jQuery("#ajax-save-inactive-ig_"+tr_iterationid).hide();
    	 if(data==1)
		       	alert(xyz_script_smap_var.alert3);
		else{

    	var data=jQuery.parseJSON(data);
    	if(data.status==1){
    		jQuery(".tr_inactive"+tr_iterationid).remove();
    		if(jQuery('#system_notice_area').length==0)
    			jQuery('body').append('<div class="system_notice_area_style1" id="system_notice_area"></div>');
			jQuery("#system_notice_area").html(xyz_script_smap_var.html7); 
 			jQuery("#system_notice_area").append('<span id="system_notice_area_dismiss"> <?php _e('Dismiss','social-media-auto-publish');?> </span>');
    			jQuery("#system_notice_area").show();
    			jQuery('#system_notice_area_dismiss').click(function() {
    				jQuery('#system_notice_area').animate({
    					opacity : 'hide',
    					height : 'hide'
    				}, 500);
    			});
    	}
    	else if(data.status==0 )
    	{
    		jQuery("#show_err_inactive_ig_"+tr_iterationid).append(data.msg );
    	}
    }
    });
  });
///////////////////////////////////////////////////////////////////

window.addEventListener('message', function(e) {
	ProcessChildMessage_2(e.data);
} , false);
//////////////////////////////////////////////////////////////////
	function ProcessChildMessage_2(message) {
			var obj1=jQuery.parseJSON(message);//console.log(message);
		  	if(obj1.smap_api_upgrade && obj1.success_flag){ 
			   var base = '<?php echo admin_url('admin.php?page=social-media-auto-publish-manage-authorizations&msg=smap_pack_updated');?>';
			  window.location.href = base;
			}
	}
///////////////////////////////////////////////////////////////////
});
function smap_popup_purchase_plan(auth_secret_key,request_hash,media)
{
	var account_id=0;
	var xyz_smap_pre_smapsoln_userid=0;
	var childWindow = null;
	var domain_name='<?php echo urlencode($domain_name); ?>';
	var smap_licence_key='<?php echo $xyz_smap_licence_key;?>';
	var smap_solution_url='<?php echo XYZ_SMAP_SOLUTION_AUTH_URL;?>';
	var xyzscripts_hash_val	='<?php echo $xyzscripts_hash_val;?>';
	var xyzscripts_user_id='<?php echo $xyzscripts_user_id; ?>';
	var smap_plugin_source='<?php echo $free_plugin_source;?>';
if(media=='facebook')
	childWindow=window.open(smap_solution_url+"authorize/facebook.php?smap_id="+xyz_smap_pre_smapsoln_userid+"&account_id="+account_id+"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source="+smap_plugin_source+"&smap_api_upgrade=1&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	else if(media=='linkedin')
		childWindow=window.open(smap_solution_url+"authorize_linkedIn/linkedin.php?smap_ln_auth_id="+xyz_smap_pre_smapsoln_userid+"&account_id="+account_id+"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source="+smap_plugin_source+"&smap_api_upgrade=1&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	else if(media=='instagram')
		childWindow=window.open(smap_solution_url+"authorize-instagram/instagram.php?smap_id=="+xyz_smap_pre_smapsoln_userid+"&account_id="+account_id+"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source="+smap_plugin_source+"&smap_api_upgrade=1&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	else if(media=='twitter')
		childWindow=window.open(smap_solution_url+"authorize-twitter/twitter.php?smap_tw_auth_id="+xyz_smap_pre_smapsoln_userid+"&account_id="+account_id+"&domain_name="+domain_name+"&xyzscripts_user_id="+xyzscripts_user_id+"&smap_licence_key="+smap_licence_key+"&auth_secret_key="+auth_secret_key+"&free_plugin_source="+smap_plugin_source+"&smap_api_upgrade=1&request_hash="+request_hash, "SmapSolutions Authorization", "toolbar=yes,scrollbars=yes,resizable=yes,left=500,width=600,height=600");
	return false;
}
	</script>
	<div>
	<h3> <?php _e('Manage Authorizations','social-media-auto-publish');?> </h3>
	<div class="xyz_smap_tab">
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_facebook_auths')" id="xyz_smap_default_fbauth_tab"> <?php _e('Facebook Authorizations','social-media-auto-publish'); ?> </button>

   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_linkedin_auths')" id="xyz_smap_ln_auth_tab"> <?php _e('LinkedIn Authorizations','social-media-auto-publish'); ?> </button>
   
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_instagram_auths')" id="xyz_smap_ig_auth_tab"> <?php _e('Instagram Authorizations','social-media-auto-publish'); ?> </button>
   
   <button class="xyz_smap_tablinks" onclick="xyz_smap_open_tab(event, 'xyz_smap_twitter_auths')" id="xyz_smap_tw_auth_tab"> <?php _e('Twitter Authorizations','social-media-auto-publish'); ?> </button>
   
</div>
<div id="xyz_smap_facebook_auths" class="xyz_smap_tabcontent">
	<?php

$url=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize/manage-authorizations.php';//manage-authorizations.php';
$content=xyz_smap_post_to_smap_api($manage_auth_parameters,$url,$xyzscripts_hash_val);
$result=json_decode($content,true);
if(!empty($result) && isset($result['status']))
{
	if($result['status']==0)
	{
	$er_msg=$result['msg'];
	echo '<div style="color:red;font-size:15px;padding:3px;">'.$er_msg.'</div>';
	//header("Location:".admin_url('admin.php?page=social-media-auto-publish-manage-authorizations-premium&msg=2&error_msg='.$er_msg));
	}
	if($result['status']==1 || isset($result['package_details'])){
		$auth_entries=$result['msg'];

	?>
		<div id="auth_entries_div" style="margin-bottom: 5px;">
							<br/>
					<?php if(!empty($result) && isset($result['package_details']))
					{
						?><div class="xyz_smap_plan_label"> <?php _e('Current Plan','social-media-auto-publish'); ?> :</div><?php 
						$package_details=$result['package_details'];	?>
						<div class="xyz_smap_plan_div"> <?php _e('Allowed Facebook users','social-media-auto-publish'); ?> : <?php echo $package_details['allowed_fb_user_accounts'];?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('API limit per account','social-media-auto-publish'); ?> :  <?php echo $package_details['allowed_api_calls'];?> <?php _e('per hour','social-media-auto-publish'); ?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Expiry','social-media-auto-publish'); ?>  :  <?php echo date('d/m/Y g:i a', $package_details['expiry_time']);?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Status','social-media-auto-publish'); ?>  :  <?php echo $package_details['package_status'];?> &nbsp;</div>
						<?php 
// 						if ($package_details['package_status']=='Expired')
						{
							$xyz_smap_accountId=$xyz_smap_pre_smapsoln_userid=0;
							$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
							$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_pre_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.$free_plugin_source.'1');
							?>
							<div  class="xyz_smap_plan_div">
							<a href="javascript:smap_popup_purchase_plan('<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>','facebook');void(0);">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp;<?php _e('Upgrade/Renew','social-media-auto-publish'); ?>
							</a> 
							</div>
							<?php 
						}
					}if (is_array($auth_entries) && !empty($auth_entries)){?><br/>
						<span class="select_box" style="float: left;margin-top: 16px;" >
						<input type="radio" name="domain_selection" value="0" id="show_all"> <?php _e('Show all entries','social-media-auto-publish'); ?> 
						<input type="radio" name="domain_selection" value="1" id="show_same_domain"> <?php _e('Show entries from current wp installation','social-media-auto-publish'); ?>  
						<input type="radio" name="domain_selection" value="2" id="show_diff_domain"> <?php _e('Show entries from other wp installations','social-media-auto-publish'); ?> 
						</span>
						<table cellpadding="0" cellspacing="0" class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;" id="smap_manage_auth_table">
						<thead>
						<tr class="xyz_smap_manage_auth_th_fb">
						
						<th scope="col" width="8%"> <?php _e('Facebook username','social-media-auto-publish'); ?> </th>
						<th scope="col" width="10%"> <?php _e('Selected pages','social-media-auto-publish'); ?> </th>
						<th scope="col" width="10%"> <?php _e('Selected groups','social-media-auto-publish'); ?> </th>
						<th scope="col" width="10%"> <?php $smap_wp="WP";
						                                   $smap_wp_url=sprintf(__('%s url','social-media-auto-publish'),$smap_wp); 
						                              echo $smap_wp_url; ?> </th> 
						<th scope="col" width="10%"><?php _e('Plugin','social-media-auto-publish'); ?> </th>
						<th scope="col" width="5%"> <?php $smap_premium_var="(SMAP PREMIUM)"; 
						                                  $smap_premium_title= sprintf(__('Account ID %s','social-media-auto-publish'),$smap_premium_var); 
									                 echo $smap_premium_title; ?> </th>
						<th scope="col" width="5%"> <?php _e('Action','social-media-auto-publish'); ?> </th>
						</tr>
						</thead> <?php
						$i=0;
// 						print_r($auth_entries);
						foreach ($auth_entries as $auth_entries_key => $auth_entries_val)
						{
							/*if ($i==100){
							$auth_entries_val['inactive_fb_userid']=123456;
							$auth_entries_val['inactive_fb_username']='test';
							}*/
							if (isset($auth_entries_val['auth_id']))
						{
							?>
							 <tr class="tr_<?php echo $auth_entries_val['auth_id'];?>">
							 	
							 <td><?php  echo $auth_entries_val['fb_username'];?>
							 	</td>
							<?php if(isset($auth_entries_val['pages'])&& !empty($auth_entries_val['pages'])){?>
							 	<td> <?php echo $auth_entries_val['pages'];?> </td>
							 	<?php }else echo "<td> NA </td>";?>	
							 		<?php if(isset($auth_entries_val['groups'])&& !empty($auth_entries_val['groups'])){?>
							 	<td> <?php echo $auth_entries_val['groups'];?> </td>
							 	<?php }else echo "<td> NA </td>";?>
							 	<?php 	if($auth_entries_val['domain_name']==$domain_name){?>
							 	<td class='same_domain'> <?php echo $auth_entries_val['domain_name'];?> </td>
							 	<?php }
							 	else{?>
							 	<td class='diff_domain'> <?php echo $auth_entries_val['domain_name'];?> </td>
							 	<?php } ?>
							 	<td> <?php
							 	if($auth_entries_val['free_plugin_source']=='fbap')
							 		echo 'WP2SOCIAL AUTO PUBLISH';
							 		elseif ($auth_entries_val['free_plugin_source']=='smap')
							 		echo 'SOCIAL MEDIA AUTO PUBLISH';
							 		elseif ($auth_entries_val['free_plugin_source']=='pls')
							 		echo 'XYZ WP SMAP Premium Plus';
							 		else echo 'XYZ WP SMAP Premium';
							 		?></td>
							 		<td> <?php if($auth_entries_val['smap_pre_account_id']!=0)echo $auth_entries_val['smap_pre_account_id'];
							 		else _e('Not Applicable','social-media-auto-publish');?> </td>
							 		<td>
							 		<?php if ($domain_name==$auth_entries_val['domain_name'] && $free_plugin_source==$auth_entries_val['free_plugin_source'] ) {
							 		?>
							 		<span id='ajax-save_<?php echo $auth_entries_val['auth_id'];?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "></span>
							 		<span id='show-del-icon_<?php echo $auth_entries_val['auth_id'];?>'>
							 		<input type="button" class="delete_auth_entry" data-id=<?php echo $auth_entries_val['auth_id'];?> data-plugin-src=<?php echo $auth_entries_val['free_plugin_source'];?> data-account_id=<?php echo $auth_entries_val['smap_pre_account_id'];?>  data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_entry' value="<?php _e('Delete','social-media-auto-publish');?>" >
							 		</span>
							 		<span id='show_err_<?php echo $auth_entries_val['auth_id'];?>' style="color:red;" ></span>
							 		<?php
							 		?></td>
							 		</tr>
							 		<?php
							 		}
						}
						else if (isset($auth_entries_val['inactive_fb_userid']))
						{
						?>
						 <tr class="tr_inactive<?php echo $i;?>">
						 <td><?php  echo $auth_entries_val['inactive_fb_username'];?><br/> <?php _e('(Inactive)','social-media-auto-publish');?>
						 </td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>
						 <span id='ajax-save-inactive-fb_<?php echo $i;?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "></span>
						 <span id='show-del-icon-inactive-fb_<?php echo $i;?>'>
						 <input type="button" class="delete_inactive_fb_entry" data-iterationid=<?php echo $i;?> data-fbid=<?php echo $auth_entries_val['inactive_fb_userid'];?> data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_entry' value="<?php _e('Delete','social-media-auto-publish');?>" >
						 </span>
						 <span id='show_err_inactive_fb_<?php echo $i;?>' style="color:red;" ></span>
						 </td>
						 </tr>
						<?php 
							$i++;
						}
							
						}///////////////foreach
					?>
					<tr id="xyz_smap_no_auth_entries" style="display: none;"><td> <?php _e('No Authorizations','social-media-auto-publish');?> </td></tr>
					</table>
					<?php }?>
					</div><?php 
}
}
else {
    ?>
	<div> <?php _e('Unable to connect. Please check your curl and firewall settings','social-media-auto-publish');?> </div>
<?php }
?></div>
<!-- linkedin  -->
<div id="xyz_smap_linkedin_auths" class="xyz_smap_tabcontent">
	<?php
$url_ln=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/manage-ln-authorizations.php';
$content_ln=xyz_smap_post_to_smap_api($manage_auth_parameters,$url_ln,$xyzscripts_hash_val);
$result_ln=json_decode($content_ln,true);//print_r($result_ln);//die;
if(!empty($result_ln) && isset($result_ln['status']))
{
	if($result_ln['status']==0)
	{
	$er_msg=$result_ln['msg'];
	echo '<div style="color:red;font-size:15px;">'.$er_msg.'</div>';
	}
	if($result_ln['status']==1 || isset($result_ln['package_details'])){
		$ln_auth_entries=$result_ln['msg'];
?>
		<div id="ln_auth_entries_div" style="margin-bottom: 5px;">
					<br/>
					<?php if(!empty($result_ln) && isset($result_ln['package_details']))
					{
						?><div class="xyz_smap_plan_label"> <?php _e('Current Plan','social-media-auto-publish'); ?>:</div><?php 
						$ln_package_details=$result_ln['package_details'];?>
						<div class="xyz_smap_plan_div"> <?php _e('Allowed LinkedIn users','social-media-auto-publish'); ?> : <?php echo $ln_package_details['allowed_ln_user_accounts'];?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('API limit per account','social-media-auto-publish'); ?>  :  <?php echo $ln_package_details['allowed_lnapi_calls'];?> <?php _e('per day','social-media-auto-publish'); ?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Expiry','social-media-auto-publish'); ?>  :  <?php echo date('d/m/Y g:i a', $ln_package_details['ln_expiry_time']);?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Status','social-media-auto-publish'); ?>  :  <?php echo $ln_package_details['package_status'];?> &nbsp;</div>
						<?php 
// 						if ($ln_package_details['package_status']=='Expired')
						{
							$xyz_smap_accountId=$xyz_smap_pre_smapsoln_userid=0;
							$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
							$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_pre_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.$free_plugin_source.'1');
							?>
							<div  class="xyz_smap_plan_div">
							<a href="javascript:smap_popup_purchase_plan('<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>','linkedin');void(0);">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp; <?php _e('Upgrade/Renew','social-media-auto-publish'); ?> 
							</a> 
							</div>
							<?php 
						}
					}
					if (is_array($ln_auth_entries) && !empty($ln_auth_entries)){
					?><br/>
						<span class="select_box"  style="float: left;margin-top: 16px;" >
						<input type="radio" name="ln_domain_selection" value="0" id="ln_show_all"> <?php _e('Show all entries','social-media-auto-publish'); ?> 
						<input type="radio" name="ln_domain_selection" value="1" id="ln_show_same_domain"> <?php _e('Show entries from current wp installation','social-media-auto-publish'); ?>  
						<input type="radio" name="ln_domain_selection" value="2" id="ln_show_diff_domain" > <?php _e('Show entries from other wp installations','social-media-auto-publish'); ?>
						</span>
						<table cellpadding="0" cellspacing="0" class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;" id="ln_smap_manage_auth_table">
						<thead>
						<tr class="xyz_smap_manage_auth_th_ln">
						<th scope="col" width="13%"> <?php _e('LinkedIn user name','social-media-auto-publish'); ?> </th>
						<th scope="col" width="15%"> <?php _e('Selected pages','social-media-auto-publish'); ?> </th>
<!-- 						<th scope="col" width="10%">Selected groups</th> -->
						<th scope="col" width="10%"> <?php echo $smap_wp_url; ?> </th>
						<th scope="col" width="10%"> <?php _e('Plugin','social-media-auto-publish'); ?> </th>
						<th scope="col" width="5%"> <?php echo $smap_premium_title; ?> </th>
						<th scope="col" width="5%"> <?php _e('Action','social-media-auto-publish'); ?> </th>
						</tr>
						</thead> <?php
						$i=0;
						foreach ($ln_auth_entries as $ln_auth_entries_key => $ln_auth_entries_val)
						{
						    if (isset($ln_auth_entries_val['auth_id'])){
						        ?>
							 <tr class="tr_<?php echo $ln_auth_entries_val['auth_id'];?>">
							 <td><?php  echo $ln_auth_entries_val['ln_username'];?>
							 	</td>
							<?php if(isset($ln_auth_entries_val['pages'])&& !empty($ln_auth_entries_val['pages'])){?>
							 	<td> <?php echo $ln_auth_entries_val['pages'];?> </td>
							 	<?php }else echo "<td> NA </td>";?>
							 	<?php 	if($ln_auth_entries_val['domain_name']==$domain_name){?>
							 	<td class='ln_same_domain'> <?php echo $ln_auth_entries_val['domain_name'];?> </td>
							 	<?php }
							 	else{?>
							 	<td class='ln_diff_domain'> <?php echo $ln_auth_entries_val['domain_name'];?> </td>
							 	<?php } ?>
							 	<td> <?php
							 	if($ln_auth_entries_val['free_plugin_source']=='lnap')
							 		echo 'WP TO LINKEDIN AUTO PUBLISH';
							 		elseif ($ln_auth_entries_val['free_plugin_source']=='smap')
							 		echo 'SOCIAL MEDIA AUTO PUBLISH';
							 		elseif ($ln_auth_entries_val['free_plugin_source']=='pls')
							 		echo 'XYZ WP SMAP Premium Plus';
							 		else echo 'XYZ WP SMAP Premium';
							 		?></td>
							 		<td> <?php if($ln_auth_entries_val['smap_pre_account_id']!=0){echo $ln_auth_entries_val['smap_pre_account_id'];}
							 		else _e('Not Applicable','social-media-auto-publish'); ?> </td>
							 		<td>
							 		<?php
							 		if ($domain_name==$ln_auth_entries_val['domain_name'] && $free_plugin_source==$ln_auth_entries_val['free_plugin_source'] ) {
							 		?>
							 		<span id='ajax-save_<?php echo $ln_auth_entries_val['auth_id'];?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "/></span>
							 		<span id='show-del-icon_<?php echo $ln_auth_entries_val['auth_id'];?>'>
							 		<input type="button" class="delete_ln_auth_entry" data-auth_id=<?php echo $ln_auth_entries_val['auth_id'];?> data-ln_account_id=<?php echo $ln_auth_entries_val['smap_pre_account_id'];?>   data-plugin-src=<?php echo $ln_auth_entries_val['free_plugin_source'];?> data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_ln_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>" >
							 		</span>
							 		<span id='show_err_<?php echo $ln_auth_entries_val['auth_id'];?>' style="color:red;" ></span>
							 		<?php
							 		?></td>
							 		</tr>
							 		<?php
							 		}
							}
							else if (isset($ln_auth_entries_val['inactive_ln_userid']))
							{
								?>
						 <tr class="tr_inactive<?php echo $i;?>">
						 <td><?php  echo $ln_auth_entries_val['inactive_ln_username'];?><br/> <?php _e('(Inactive)','social-media-auto-publish'); ?>
						 </td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>
						 <span id='ajax-save-inactive-ln_<?php echo $i;?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "></span>
						 <span id='show-del-icon-inactive-ln_<?php echo $i;?>'>
						 <input type="button" class="delete_inactive_ln_entry" data-ln_iterationid=<?php echo $i;?> data-lnid=<?php echo $ln_auth_entries_val['inactive_ln_userid'];?>  data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>" >
						 </span>
						 <span id='show_err_inactive_ln_<?php echo $i;?>' style="color:red;" ></span>
						 </td>
						 </tr>
						<?php 
							$i++;
						}
						}///////////////foreach
					?>
					<tr id="xyz_smap_no_auth_entries_ln" style="display: none;"><td> <?php _e('No Authorizations','social-media-auto-publish'); ?> </td></tr>
					</table>
					<br/>
	<?php  }?>
					</div>	<br/><?php
}
}
else { ?>
	<div> <?php _e('Unable to connect. Please check your curl and firewall settings','social-media-auto-publish'); ?> </div>
<?php }
?></div>
<!-- Instagram  -->
<div id="xyz_smap_instagram_auths" class="xyz_smap_tabcontent">
	<?php
	$url_ig=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-instagram/manage-authorizations.php';
// $url_ln=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_linkedIn/manage-ln-authorizations.php';
	$content_ln=xyz_smap_post_to_smap_api($manage_auth_parameters,$url_ig,$xyzscripts_hash_val);
$result_ig=json_decode($content_ln,true);//print_r($result_ig);//die;
//print_r($result_ig);
if(isset($result_ig['status']) && $result_ig['status']==0)
{
    if(isset($result_ig['msg']))
{
    $er_msg=$result_ig['msg'];
    echo '<div style="color:red;font-size:15px;">'.$er_msg.'</div>';
    }
}
if($result_ig['status']==1 || isset($result_ig['package_details'])){
    $ig_auth_entries=$result_ig['msg'];
    ?>
		<div id="ig_auth_entries_div" style="margin-bottom: 5px;">
					<br/>
					<?php if(!empty($result_ig) && isset($result_ig['package_details']))
					{
						?><div class="xyz_smap_plan_label"> <?php _e('Current Plan','social-media-auto-publish'); ?> :</div><?php 
						$ig_package_details=$result_ig['package_details'];?>
						<div class="xyz_smap_plan_div"> <?php _e('Allowed Instagram users','social-media-auto-publish'); ?> : <?php echo $ig_package_details['allowed_ig_user_accounts'];?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('API limit per account','social-media-auto-publish'); ?>  :  <?php echo $ig_package_details['allowed_ig_api_calls'];?> <?php _e('per hour','social-media-auto-publish'); ?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Expiry','social-media-auto-publish'); ?>  :  <?php echo date('d/m/Y g:i a', $ig_package_details['ig_expiry_time']);?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Status','social-media-auto-publish'); ?>  :  <?php echo $ig_package_details['package_status'];?> &nbsp;</div>
						<?php 
// 						if ($ig_package_details['package_status']=='Expired')
						{
							$xyz_smap_accountId=$xyz_smap_pre_smapsoln_userid=0;
							$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
							$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_pre_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.$free_plugin_source.'1');
							?>
							<div  class="xyz_smap_plan_div">
							<a href="javascript:smap_popup_purchase_plan('<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>','instagram');void(0);">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp; <?php _e('Upgrade/Renew','social-media-auto-publish'); ?> 
							</a> 
							</div>
							<?php 
						}
					}
					if (is_array($ig_auth_entries) && !empty($ig_auth_entries)){
					?><br/>
						<span class="select_box"  style="float: left;margin-top: 16px;" >
						<input type="radio" name="ig_domain_selection" value="0" id="ig_show_all"> <?php _e('Show all entries','social-media-auto-publish'); ?> 
						<input type="radio" name="ig_domain_selection" value="1" id="ig_show_same_domain"> <?php _e('Show entries from current wp installation','social-media-auto-publish'); ?>  
						<input type="radio" name="ig_domain_selection" value="2" id="ig_show_diff_domain"> <?php _e('Show entries from other wp installations','social-media-auto-publish'); ?> 
						</span>
						<table cellpadding="0" cellspacing="0" class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;" id="ig_smap_manage_auth_table">
						<thead>
						<tr class="xyz_smap_manage_auth_th_ig">
						<th scope="col" width="13%"> <?php _e('Instagram user name','social-media-auto-publish'); ?> </th>
						<th scope="col" width="15%"> <?php _e('Selected pages','social-media-auto-publish'); ?> </th>
<!-- 						<th scope="col" width="10%">Selected groups</th> -->
						<th scope="col" width="10%"> <?php echo $smap_wp_url; ?> </th>
						<th scope="col" width="10%"> <?php _e('Plugin','social-media-auto-publish'); ?> </th>
						<th scope="col" width="5%"> <?php echo $smap_premium_title; ?> </th>
						<th scope="col" width="5%"> <?php _e('Action','social-media-auto-publish'); ?> </th>
						</tr>
						</thead> <?php
						$i=0;
						foreach ($ig_auth_entries as $ig_auth_entries_key => $ig_auth_entries_val)
						{
// 						    echo 'fdfdf';print_r($ig_auth_entries_val);
							if (isset($ig_auth_entries_val['ig_username'])){
							?>
							 <tr class="tr_<?php echo $ig_auth_entries_val['auth_id'];?>">
							 <td><?php  echo $ig_auth_entries_val['ig_username'];?>
							 	</td>
							<?php if(isset($ig_auth_entries_val['pages'])&& !empty($ig_auth_entries_val['pages'])){?>
							 	<td> <?php echo $ig_auth_entries_val['pages'];?> </td>
							 	<?php }else echo "<td> NA </td>";?>
							 	<?php 	if($ig_auth_entries_val['domain_name']==$domain_name){?>
							 	<td class='ig_same_domain'> <?php echo $ig_auth_entries_val['domain_name'];?> </td>
							 	<?php }
							 	else{?>
							 	<td class='ig_diff_domain'> <?php echo $ig_auth_entries_val['domain_name'];?> </td>
							 	<?php } ?>
							 	<td> <?php
							 /*	if($ig_auth_entries_val['free_plugin_source']=='igap')
							 		echo 'WP TO INSTAGRAM AUTO PUBLISH';*/
							 		if ($ig_auth_entries_val['free_plugin_source']=='smap')
							 		echo 'SOCIAL MEDIA AUTO PUBLISH';
							 		elseif ($ig_auth_entries_val['free_plugin_source']=='pls')
							 		echo 'XYZ WP SMAP Premium Plus';
							 		else echo 'XYZ WP SMAP Premium';
							 		?></td>
							 		<td> <?php if($ig_auth_entries_val['smap_pre_account_id']!=0){echo $ig_auth_entries_val['smap_pre_account_id'];}
							 		else _e('Not Applicable','social-media-auto-publish'); ?> </td>
							 		<td>
							 		<?php
							 		if ($domain_name==$ig_auth_entries_val['domain_name'] && $free_plugin_source==$ig_auth_entries_val['free_plugin_source'] ) {
							 		?>
							 		<span id='ajax-save_<?php echo $ig_auth_entries_val['auth_id'];?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "/></span>
							 		<span id='show-del-icon_<?php echo $ig_auth_entries_val['auth_id'];?>'>
							 		<input type="button" class="delete_ig_auth_entry" data-auth_id=<?php echo $ig_auth_entries_val['auth_id'];?> data-ig_account_id=<?php echo $ig_auth_entries_val['smap_pre_account_id'];?>   data-plugin-src=<?php echo $ig_auth_entries_val['free_plugin_source'];?> data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_ig_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>">
							 		</span>
							 		<span id='show_err_<?php echo $ig_auth_entries_val['auth_id'];?>' style="color:red;" ></span>
							 		</td>
							 		</tr>
							 		<?php
							 		}
							}
							else if (isset($ig_auth_entries_val['inactive_ig_userid']))
							{
								?>
						 <tr class="tr_inactive<?php echo $i;?>">
						 <td><?php  echo $ig_auth_entries_val['inactive_ig_username'];?><br/> <?php _e('(Inactive)','social-media-auto-publish'); ?> 
						 </td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>
						 <span id='ajax-save-inactive-ig_<?php echo $i;?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "></span>
						 <span id='show-del-icon-inactive-ig_<?php echo $i;?>'>
						 <input type="button" class="delete_inactive_ig_entry" data-ig_iterationid=<?php echo $i;?> data-igid=<?php echo $ig_auth_entries_val['inactive_ig_userid'];?>  data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>">
						 </span>
						 <span id='show_err_inactive_ig_<?php echo $i;?>' style="color:red;" ></span>
						 </td>
						 </tr>
						<?php 
							$i++;
						}
						}///////////////foreach
					?>
					<tr id="xyz_smap_no_auth_entries_ig" style="display: none;"><td> <?php _e('No Authorizations','social-media-auto-publish'); ?> </td></tr>
					</table>
					<br/>
	<?php  }?>
					</div>	<br/><?php
}
?></div>
<!-- Twitter  -->
<div id="xyz_smap_twitter_auths" class="xyz_smap_tabcontent">
	<?php
// 	$url_ln=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize_instagram/manage-authorizations.php';
	$url_tw=XYZ_SMAP_SOLUTION_AUTH_URL.'authorize-twitter/manage-tw-authorizations.php';
$content_tw=xyz_smap_post_to_smap_api($manage_auth_parameters,$url_tw,$xyzscripts_hash_val);
$result_tw=json_decode($content_tw,true);//print_r($result_tw);//die;
if(!empty($result_tw) && isset($result_tw['status']))
{
	if($result_tw['status']==0)
	{
	$er_msg=$result_tw['msg'];
	echo '<div style="color:red;font-size:15px;">'.$er_msg.'</div>';
	}
	if($result_tw['status']==1 || isset($result_tw['package_details'])){
		$tw_auth_entries=$result_tw['msg'];
		?>
		<div id="tw_auth_entries_div" style="margin-bottom: 5px;">
					<br/>
					<?php if(!empty($result_tw) && isset($result_tw['package_details']))
					{
						?><div class="xyz_smap_plan_label"> <?php _e('Current Plan','social-media-auto-publish'); ?> :</div><?php 
						$tw_package_details=$result_tw['package_details'];?>
						<div class="xyz_smap_plan_div"> <?php _e('Allowed Twitter users','social-media-auto-publish'); ?> : <?php echo $tw_package_details['allowed_tw_user_accounts'];?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('API limit per account','social-media-auto-publish'); ?>  :  <?php echo $tw_package_details['allowed_twapi_calls'];?> <?php _e('per hour','social-media-auto-publish'); ?> &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Expiry','social-media-auto-publish'); ?>  :  <?php echo date('d/m/Y g:i a', $tw_package_details['tw_expiry_time']);?>  &nbsp;</div>
						<div  class="xyz_smap_plan_div"> <?php _e('Package Status','social-media-auto-publish'); ?>  :  <?php echo $tw_package_details['package_status'];?> &nbsp;</div>
						<?php 
// 						if ($tw_package_details['package_status']=='Expired')
						{
							$xyz_smap_accountId=$xyz_smap_pre_smapsoln_userid=0;
							$request_hash=md5($xyzscripts_user_id.$xyzscripts_hash_val);
							$auth_secret_key=md5('smapsolutions'.$domain_name.$xyz_smap_accountId.$xyz_smap_pre_smapsoln_userid.$xyzscripts_user_id.$request_hash.$xyz_smap_licence_key.$free_plugin_source.'1');
							?>
							<div  class="xyz_smap_plan_div">
							<a href="javascript:smap_popup_purchase_plan('<?php echo $auth_secret_key;?>','<?php echo $request_hash;?>','twitter');void(0);">
							<i class="fa fa-shopping-cart" aria-hidden="true"></i>&nbsp; <?php _e('Upgrade/Renew','social-media-auto-publish'); ?> 
							</a> 
							</div>
							<?php 
						}
					}
					if (is_array($tw_auth_entries) && !empty($tw_auth_entries)){
					?><br/>
						<span class="select_box"  style="float: left;margin-top: 16px;" >
						<input type="radio" name="tw_domain_selection" value="0" id="tw_show_all"> <?php _e('Show all entries','social-media-auto-publish'); ?> 
						<input type="radio" name="tw_domain_selection" value="1" id="tw_show_same_domain"> <?php _e('Show entries from current wp installation','social-media-auto-publish'); ?>  
						<input type="radio" name="tw_domain_selection" value="2" id="tw_show_diff_domain" > <?php _e('Show entries from other wp installations','social-media-auto-publish'); ?> 
						</span>
						<table cellpadding="0" cellspacing="0" class="widefat" style="width: 99%; margin: 0 auto; border-bottom:none;" id="tw_smap_manage_auth_table">
						<thead>
						<tr class="xyz_smap_manage_auth_th_tw">
						<th scope="col" width="13%"> <?php _e('Twitter user name','social-media-auto-publish'); ?> </th>

						<th scope="col" width="10%"> <?php echo $smap_wp_url; ?> </th>
						<th scope="col" width="10%"> <?php _e('Plugin','social-media-auto-publish'); ?> </th>
						<th scope="col" width="5%"> <?php  echo $smap_premium_title; ?> </th>
						<th scope="col" width="5%"> <?php _e('Action','social-media-auto-publish'); ?> </th>
						</tr>
						</thead> <?php
						$i=0;
						foreach ($tw_auth_entries as $tw_auth_entries_key => $tw_auth_entries_val)
						{ 
						    //echo 'dsfsdf';print_r($tw_auth_entries_val);
// 							if (isset($tw_auth_entries_val['auth_id'])){
							    if (isset($tw_auth_entries_val['tw_username'])){
							?>
							 <tr class="tr_<?php echo $tw_auth_entries_val['auth_id'];?>">
							 <td><?php  echo $tw_auth_entries_val['tw_username'];?>
							 	</td>
						
							 	<?php 	if($tw_auth_entries_val['domain_name']==$domain_name){?>
							 	<td class='tw_same_domain'> <?php echo $tw_auth_entries_val['domain_name'];?> </td>
							 	<?php }
							 	else{?>
							 	<td class='tw_diff_domain'> <?php echo $tw_auth_entries_val['domain_name'];?> </td>
							 	<?php } ?>
							 	<td> <?php
							 	if($tw_auth_entries_val['free_plugin_source']=='twap')
							 		echo 'WP TWITTER AUTO PUBLISH';
							 		elseif ($tw_auth_entries_val['free_plugin_source']=='smap')
							 		echo 'SOCIAL MEDIA AUTO PUBLISH';
							 		elseif ($tw_auth_entries_val['free_plugin_source']=='pls')
							 		echo 'XYZ WP SMAP Premium Plus';
							 		else echo 'XYZ WP SMAP Premium';
							 		?></td>
							 		<td> <?php if($tw_auth_entries_val['smap_pre_account_id']!=0){echo $tw_auth_entries_val['smap_pre_account_id'];}
							 		else _e('Not Applicable','social-media-auto-publish'); ?> </td>
							 		<td>
							 		<?php
							 		if ($domain_name==$tw_auth_entries_val['domain_name'] && $free_plugin_source==$tw_auth_entries_val['free_plugin_source'] ) {
							 		?>
							 		<span id='ajax-save_<?php echo $tw_auth_entries_val['auth_id'];?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "/></span>
							 		<span id='show-del-icon_<?php echo $tw_auth_entries_val['auth_id'];?>'>
							 		<input type="button" class="delete_tw_auth_entry" data-auth_id=<?php echo $tw_auth_entries_val['auth_id'];?> data-tw_account_id=<?php echo $tw_auth_entries_val['smap_pre_account_id'];?>   data-plugin-src=<?php echo $tw_auth_entries_val['free_plugin_source'];?> data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_tw_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>" >
							 		</span>
							 		<span id='show_err_<?php echo $tw_auth_entries_val['auth_id'];?>' style="color:red;" ></span>
							 		<?php
							 		?></td>
							 		</tr>
							 		<?php
							 		}
							}
							else if (isset($tw_auth_entries_val['inactive_tw_userid']))
							{
								?>
						 <tr class="tr_inactive<?php echo $i;?>">
						 <td><?php  echo $tw_auth_entries_val['inactive_tw_username'];?><br/> <?php _e('(Inactive)','social-media-auto-publish'); ?> 
						 </td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>-</td>
						 <td>
						 <span id='ajax-save-inactive-tw_<?php echo $i;?>' style="display:none;"><img	title="Deleting entry"	src="<?php echo plugins_url("images/ajax-loader.gif",XYZ_SMAP_PLUGIN_FILE);?>" style="width:20px;height:20px; "></span>
						 <span id='show-del-icon-inactive-tw_<?php echo $i;?>'>
						 <input type="button" class="delete_inactive_tw_entry" data-tw_iterationid=<?php echo $i;?> data-twid=<?php echo $tw_auth_entries_val['inactive_tw_userid'];?>  data-xyzscriptsid="<?php echo $xyzscripts_user_id;?>" data-xyzscripts_hash="<?php echo $xyzscripts_hash_val;?>" name='del_entry' value="<?php _e('Delete','social-media-auto-publish'); ?>" >
						 </span>
						 <span id='show_err_inactive_tw_<?php echo $i;?>' style="color:red;" ></span>
						 </td>
						 </tr>
						<?php 
							$i++;
						}
						}///////////////foreach
					?>
					<tr id="xyz_smap_no_auth_entries_tw" style="display: none;"><td> <?php _e('No Authorizations','social-media-auto-publish'); ?> </td></tr>
					</table>
					<br/>
	<?php  }?>
					</div>	<br/><?php
}
}
else { ?>
	<div> <?php _e('Unable to connect. Please check your curl and firewall settings','social-media-auto-publish'); ?> </div>
<?php } ?>
</div>
</div>
