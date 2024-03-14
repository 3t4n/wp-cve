<?php 
if( !defined('ABSPATH') ){ exit();}
add_action( 'add_meta_boxes', 'xyz_smap_add_custom_box' );
$GLOBALS['edit_flag']=0;
function xyz_smap_add_custom_box()
{	
	$posttype="";
	if(isset($_GET['post_type']))
		$posttype=$_GET['post_type'];
	
	if($posttype=="")
		$posttype="post";
	
if(isset($_GET['action']) && $_GET['action']=="edit" && !empty($_GET['post']))  /// empty check added for fixing client scenario
	{
		$postid=intval($_GET['post']);
		
		
		$get_post_meta=get_post_meta($postid,"xyz_smap",true);
		if($get_post_meta==1){
			$GLOBALS['edit_flag']=1;
		}
			
		global $wpdb;
		$table='posts';
		$accountCount = $wpdb->query($wpdb->prepare( 'SELECT * FROM '.$wpdb->prefix.$table.' WHERE id=%d and post_status!=%s LIMIT %d,%d',array($postid,'draft',0,1) )) ;
		if($accountCount>0){
			$GLOBALS['edit_flag']=1;
			}
		$posttype=get_post_type($postid);
	}

	

	if ($posttype=="page")
	{

		$xyz_smap_include_pages=get_option('xyz_smap_include_pages');
		if($xyz_smap_include_pages==0)
			return;
	}
	else if($posttype=="post")
	{
		$xyz_smap_include_posts=get_option('xyz_smap_include_posts');
		if($xyz_smap_include_posts==0)
			return;
	}
	else if($posttype!="post")
	{

		$xyz_smap_include_customposttypes=get_option('xyz_smap_include_customposttypes');
		$carr=explode(',', $xyz_smap_include_customposttypes);
		if(!in_array($posttype,$carr))
			return;

	}
	if((get_option('xyz_smap_af')==0 && get_option('xyz_smap_fb_token')!="" && get_option('xyz_smap_pages_ids')!="" && get_option('xyz_smap_post_permission')==1 && (get_option('xyz_smap_app_sel_mode')==0)) ||
	    (get_option('xyz_smap_twconsumer_id')!="" && get_option('xyz_smap_twconsumer_secret')!="" && get_option('xyz_smap_tw_id')!="" && get_option('xyz_smap_current_twappln_token')!="" && get_option('xyz_smap_twaccestok_secret')!="" && get_option('xyz_smap_twpost_permission')==1)
	    || (get_option('xyz_smap_lnaf')==0 && get_option('xyz_smap_lnpost_permission')==1 && ( get_option('xyz_smap_ln_company_ids')!=''|| get_option('xyz_smap_lnshare_to_profile')==1)) || (get_option('xyz_smap_app_sel_mode')==1 && get_option('xyz_smap_page_names')!="" && get_option('xyz_smap_post_permission')==1)
			|| (get_option('xyz_smap_ig_token')!="" && get_option('xyz_smap_igpost_permission')==1 && get_option('xyz_smap_ig_app_sel_mode')==0) ||(get_option('xyz_smap_smapsoln_userid_ig')!=0 && get_option('xyz_smap_ig_page_names')!='' && get_option('xyz_smap_ig_app_sel_mode')==1 && get_option('xyz_smap_igpost_permission')==1)
	    || (get_option('xyz_smap_smapsoln_userid_tw')!=0 && get_option('xyz_smap_tw_app_sel_mode')==1) ||
	    (get_option('xyz_smap_tbconsumer_id')!="" && get_option('xyz_smap_tbconsumer_secret')!="" && get_option('xyz_smap_current_tbappln_token')!="" && get_option('xyz_smap_tbaccestok_secret')!="" && get_option('xyz_smap_tbpost_permission')==1))
	    add_meta_box( 'xyz_smap', '<strong>Social Media Auto Publish </strong>', 'xyz_smap_addpostmetatags',
	        null, 'normal', 'high',
	        array(
	            '__block_editor_compatible_meta_box' => true,
	        )
	        );
	    
}

function xyz_smap_addpostmetatags()
{
	$imgpath= plugins_url()."/social-media-auto-publish/images/";
	$heimg=$imgpath."support.png";
	$xyz_smap_catlist=get_option('xyz_smap_include_categories');
// 	if (is_array($xyz_smap_catlist))
// 		$xyz_smap_catlist=implode(',', $xyz_smap_catlist);
	?>
<script>

/****************** Code to reload metabox content in Gutenberg editor ******************/
jQuery(document).ready(function($) {
    const appuntiStatusChange = ( function(){
        const isSavingMetaBoxes = wp.data.select( 'core/edit-post' ).isSavingMetaBoxes;
        var wasSaving = false;
        return {
            refreshMetabox: function(){
                var isSaving = isSavingMetaBoxes();
                if ( wasSaving && ! isSaving ) {
                
                    //console.log("Post changed and saved.");                   
                    var xyz_smap_default_selection_edit="<?php echo esc_html(get_option('xyz_smap_default_selection_edit'));?>";
                    var xyz_smap_lnshare_to_profile ='<?php echo get_option('xyz_smap_lnshare_to_profile');?>';
                    
                    //Facebook
                    if(xyz_smap_default_selection_edit==0 && jQuery("input[name='xyz_smap_post_permission']:checked").val()==1) {
                                           
                    	document.getElementById("fpmd").style.display='none';	
            			document.getElementById("fpmf").style.display='none';		
            			document.getElementById("fpmftarea").style.display='none';	
                    	
                    	jQuery('#xyz_smap_post_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_post_permission_yes').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_post_permission_yes').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_post_permission_no').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_post_permission_no').addClass('xyz_smap_toggle_on');                    	                    
                    }
                    else if(xyz_smap_default_selection_edit==1 && jQuery("input[name='xyz_smap_post_permission']:checked").val()==0) {
                                       	
                    	document.getElementById("fpmd").style.display='';	
            			document.getElementById("fpmf").style.display='';	
            			document.getElementById("fpmftarea").style.display='';	
                    	
                    	jQuery('#xyz_smap_post_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_post_permission_no').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_post_permission_no').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_post_permission_yes').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_post_permission_yes').addClass('xyz_smap_toggle_on');
                    }
                    
                    //Instagram
                    if(xyz_smap_default_selection_edit==0 && jQuery("input[name='xyz_smap_igpost_permission']:checked").val()==1) {
                                            	                   	
                    	document.getElementById("igpm").style.display='none';	
            			document.getElementById("igmf").style.display='none';
            			document.getElementById("igmftarea").style.display='none';	            			           				
                    	
                    	jQuery('#xyz_smap_igpost_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_igpost_permission_yes').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_igpost_permission_yes').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_igpost_permission_no').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_igpost_permission_no').addClass('xyz_smap_toggle_on');                    	                    
                    }
                    else if(xyz_smap_default_selection_edit==1 && jQuery("input[name='xyz_smap_igpost_permission']:checked").val()==0) {
                                       	                                      	
                    	document.getElementById("igpm").style.display='';	
            			document.getElementById("igmf").style.display='';	
            			document.getElementById("igmftarea").style.display='';            				           			
                    	
                    	jQuery('#xyz_smap_igpost_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_igpost_permission_no').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_igpost_permission_no').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_igpost_permission_yes').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_igpost_permission_yes').addClass('xyz_smap_toggle_on');
                    }	
                    
                    //Twitter
                    if(xyz_smap_default_selection_edit==0 && jQuery("input[name='xyz_smap_twpost_permission']:checked").val()==1) {
                    
                        document.getElementById("twai").style.display='none';
            			document.getElementById("twmftarea").style.display='none';
            			document.getElementById("twmf").style.display='none';	
                    	
                    	jQuery('#xyz_smap_twpost_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_twpost_permission_yes').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_twpost_permission_yes').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_twpost_permission_no').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_twpost_permission_no').addClass('xyz_smap_toggle_on');                    	                    
                    }
                    else if(xyz_smap_default_selection_edit==1 && jQuery("input[name='xyz_smap_twpost_permission']:checked").val()==0) {
                                                     	
                    	document.getElementById("twai").style.display='';
            			document.getElementById("twmftarea").style.display='';	
            			document.getElementById("twmf").style.display='';	
                    	
                    	jQuery('#xyz_smap_twpost_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_twpost_permission_no').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_twpost_permission_no').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_twpost_permission_yes').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_twpost_permission_yes').addClass('xyz_smap_toggle_on');
                    }
                    
                    //Tumblr
                    if(xyz_smap_default_selection_edit==0 && jQuery("input[name='xyz_smap_tbpost_permission']:checked").val()==1) {
                    
                        document.getElementById("tbmf").style.display='none';
            			document.getElementById("tbmftarea").style.display='none';
            			document.getElementById("tbai").style.display='none';	
                    	
                    	jQuery('#xyz_smap_tbpost_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_tbpost_permission_yes').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_tbpost_permission_yes').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_tbpost_permission_no').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_tbpost_permission_no').addClass('xyz_smap_toggle_on');                    	                    
                    }
                    else if(xyz_smap_default_selection_edit==1 && jQuery("input[name='xyz_smap_tbpost_permission']:checked").val()==0) {
                                                     	
                    	document.getElementById("tbmf").style.display='';
            			document.getElementById("tbmftarea").style.display='';	
            			document.getElementById("tbai").style.display='';	
                    	
                    	jQuery('#xyz_smap_tbpost_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_tbpost_permission_no').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_tbpost_permission_no').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_tbpost_permission_yes').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_tbpost_permission_yes').addClass('xyz_smap_toggle_on');
                    }
                    
                    //LinkedIn
                    if(xyz_smap_default_selection_edit==0 && jQuery("input[name='xyz_smap_lnpost_permission']:checked").val()==1) {
                                            	                   	
                    	document.getElementById("lnmf").style.display='none';	
            			document.getElementById("lnpm").style.display='none';
            			document.getElementById("lnmftarea").style.display='none';	
            			
            			if(xyz_smap_lnshare_to_profile==1)
            				document.getElementById("shareprivate").style.display='none';
                    	
                    	jQuery('#xyz_smap_lnpost_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_lnpost_permission_yes').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_lnpost_permission_yes').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_lnpost_permission_no').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_lnpost_permission_no').addClass('xyz_smap_toggle_on');                    	                    
                    }
                    else if(xyz_smap_default_selection_edit==1 && jQuery("input[name='xyz_smap_lnpost_permission']:checked").val()==0) {
                                       	                                      	
                    	document.getElementById("lnmf").style.display='';	
            			document.getElementById("lnpm").style.display='';	
            			document.getElementById("lnmftarea").style.display='';
            				
            			if(xyz_smap_lnshare_to_profile==1)
            				document.getElementById("shareprivate").style.display='';	
                    	
                    	jQuery('#xyz_smap_lnpost_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_smap_lnpost_permission_no').removeClass('xyz_smap_toggle_on');
                    	jQuery('#xyz_smap_lnpost_permission_no').addClass('xyz_smap_toggle_off');
                    	
                    	jQuery('#xyz_smap_lnpost_permission_yes').removeClass('xyz_smap_toggle_off');
                    	jQuery('#xyz_smap_lnpost_permission_yes').addClass('xyz_smap_toggle_on');
                    }
                }
                wasSaving = isSaving;
            },
        }
    })();
    
    wp.data.subscribe( appuntiStatusChange.refreshMetabox );
});
/*************************************************************************************/

var fcheckid;
var tcheckid;
var lcheckid;
var icheckid;
var tbcheckid;
function displaycheck()
{
	if(document.getElementById("xyz_smap_post_permission_yes") || document.getElementById("xyz_smap_post_permission_no"))
	{
		fcheckid=jQuery("input[name='xyz_smap_post_permission']:checked").val();
		if(fcheckid==1)
		{
			document.getElementById("fpmd").style.display='';	
			document.getElementById("fpmf").style.display='';	
			document.getElementById("fpmftarea").style.display='';	
		}
		else
		{
			document.getElementById("fpmd").style.display='none';	
			document.getElementById("fpmf").style.display='none';		
			document.getElementById("fpmftarea").style.display='none';	
		}
	}

	if(document.getElementById("xyz_smap_twpost_permission_yes")||document.getElementById("xyz_smap_twpost_permission_no"))
	{
var tcheckid=jQuery("input[name='xyz_smap_twpost_permission']:checked").val();
if(tcheckid==1)
		{
			
			document.getElementById("twmf").style.display='';
			document.getElementById("twmftarea").style.display='';	
			document.getElementById("twai").style.display='';	
		}
		else
		{
			
			document.getElementById("twmf").style.display='none';
			document.getElementById("twmftarea").style.display='none';
			document.getElementById("twai").style.display='none';			
		}
	}

	if(document.getElementById("xyz_smap_tbpost_permission_yes")||document.getElementById("xyz_smap_tbpost_permission_yes"))
	{ 
	var tbcheckid=jQuery("input[name='xyz_smap_tbpost_permission']:checked").val();//window.alert(tbcheckid);
	if(tbcheckid==1)
		{
			document.getElementById("tbmf").style.display='';
			document.getElementById("tbmftarea").style.display='';	
			document.getElementById("tbai").style.display='';	
		}
		else
		{
			document.getElementById("tbmf").style.display='none';
			document.getElementById("tbmftarea").style.display='none';
			document.getElementById("tbai").style.display='none';			
		}
	}
	if(document.getElementById("xyz_smap_lnpost_permission_no") ||document.getElementById("xyz_smap_lnpost_permission_yes") )
	{
		lcheckid=jQuery("input[name='xyz_smap_lnpost_permission']:checked").val();
		var xyz_smap_lnshare_to_profile ='<?php echo get_option('xyz_smap_lnshare_to_profile');?>';
		if(lcheckid==1)
		{
		
			document.getElementById("lnmf").style.display='';	
			document.getElementById("lnpm").style.display='';	
			document.getElementById("lnmftarea").style.display='';	
			if(xyz_smap_lnshare_to_profile==1)
			document.getElementById("shareprivate").style.display='';	
		}
		else
		{
			document.getElementById("lnmf").style.display='none';	
			document.getElementById("lnpm").style.display='none';
			document.getElementById("lnmftarea").style.display='none';	
			if(xyz_smap_lnshare_to_profile==1)
			document.getElementById("shareprivate").style.display='none';		
		}
	}
	if(document.getElementById("xyz_smap_igpost_permission_no") ||document.getElementById("xyz_smap_igpost_permission_yes") )
	{
		icheckid=jQuery("input[name='xyz_smap_igpost_permission']:checked").val();
		if(icheckid==1)
		{
		
			document.getElementById("igmf").style.display='';	
			document.getElementById("igpm").style.display='';	
			document.getElementById("igmftarea").style.display='';				
		}
		else
		{
			document.getElementById("igmf").style.display='none';	
			document.getElementById("igpm").style.display='none';
			document.getElementById("igmftarea").style.display='none';						
		}
	}


}


</script>
<script type="text/javascript">
function detdisplay_smap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_smap(id)
{
	document.getElementById(id).style.display='none';
}

/*function drpdisplay()
{
	var shmethod= document.getElementById('xyz_smap_ln_sharingmethod').value;
	if(shmethod==1)	
	{
		document.getElementById('shareprivate').style.display="none";
	}
	else
	{
		document.getElementById('shareprivate').style.display="";
	}
}*/

jQuery(document).ready(function() {
	displaycheck();

	 var xyz_smap_post_permission=jQuery("input[name='xyz_smap_post_permission']:checked").val();
	 XyzSmapToggleRadio(xyz_smap_post_permission,'xyz_smap_post_permission'); 

	 var xyz_smap_twpost_permission=jQuery("input[name='xyz_smap_twpost_permission']:checked").val();
	 XyzSmapToggleRadio(xyz_smap_twpost_permission,'xyz_smap_twpost_permission'); 
	 var xyz_smap_tbpost_permission=jQuery("input[name='xyz_smap_tbpost_permission']:checked").val();
	 XyzSmapToggleRadio(xyz_smap_tbpost_permission,'xyz_smap_tbpost_permission'); 

	 var xyz_smap_lnpost_permission=jQuery("input[name='xyz_smap_lnpost_permission']:checked").val();
	 XyzSmapToggleRadio(xyz_smap_lnpost_permission,'xyz_smap_lnpost_permission'); 
	 
	 var xyz_smap_igpost_permission=jQuery("input[name='xyz_smap_igpost_permission']:checked").val();
	 XyzSmapToggleRadio(xyz_smap_igpost_permission,'xyz_smap_igpost_permission');

	var wp_version='<?php echo XYZ_SMAP_WP_VERSION; ?>';
	if (wp_version <= '5.3') {
			
	jQuery('#category-all').bind("DOMSubtreeModified",function(){
		smap_get_categorylist(1);
		});
	
	smap_get_categorylist(1);smap_get_categorylist(2);
	jQuery('#category-all').on("click",'input[name="post_category[]"]',function() {
		smap_get_categorylist(1);
				});

	jQuery('#category-pop').on("click",'input[type="checkbox"]',function() {
		smap_get_categorylist(2);
				});
	/////////gutenberg category selection
	jQuery(document).on('change', 'input[type="checkbox"]', function() {
		smap_get_categorylist(2);
				});
	}
});

function smap_get_categorylist(val)
{
	var flag=true;
	var cat_list="";var chkdArray=new Array();var cat_list_array=new Array();
	var posttype="<?php echo get_post_type() ;?>";
	if(val==1){
	 jQuery('input[name="post_category[]"]:checked').each(function() {
		 cat_list+=this.value+",";flag=false;
		});
	}else if(val==2)
	{
		jQuery('#category-pop input[type="checkbox"]').each(function() {
			 if(jQuery(this).is(":checked"))
			cat_list+=this.value+",";
				flag=false;
		});
		jQuery('.editor-post-taxonomies__hierarchical-terms-choice input[type="checkbox"]').each(function() { //gutenberg category checkbox
			 if(jQuery(this).is(":checked"))
			cat_list+=this.value+",";
				flag=false;
		});
		if(flag){
		<?php
		if (isset($_GET['post']))
			$postid=intval($_GET['post']);
		if (isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid)){
			$defaults = array('fields' => 'ids');
			$categ_arr=wp_get_post_categories( $postid, $defaults );
			$categ_str=implode(',', $categ_arr);
			?>
			cat_list+='<?php echo $categ_str; ?>';
		<?php }?>
					flag=false;
			}
	}
	 if (cat_list.charAt(cat_list.length - 1) == ',') {
		 cat_list = cat_list.substr(0, cat_list.length - 1);
		}
		jQuery('#cat_list').val(cat_list);
		
		var xyz_smap_catlist="<?php echo esc_html($xyz_smap_catlist);?>";
		if(xyz_smap_catlist!="All")
		{
			cat_list_array=xyz_smap_catlist.split(',');
			var show_flag=1;
			var chkdcatvals=jQuery('#cat_list').val();
			chkdArray=chkdcatvals.split(',');
			
			for(var x=0;x<chkdArray.length;x++) { 
				
				if(inArray(chkdArray[x], cat_list_array))
				{
					show_flag=1;
					break;
				}
				else
				{
					show_flag=0;
					continue;
				}
				
			}

			if(show_flag==0 && posttype=="post")
			{
				jQuery('#xyz_smap_fbMetabox').hide();
				jQuery('#xyz_smap_twMetabox').hide();
				jQuery('#xyz_smap_lnMetabox').hide();
				jQuery('#xyz_smap_igMetabox').hide();
			}
			else
			{
				jQuery('#xyz_smap_fbMetabox').show();
				jQuery('#xyz_smap_twMetabox').show();
				jQuery('#xyz_smap_lnMetabox').show();
				jQuery('#xyz_smap_igMetabox').show();
			}
		}
}
function inArray(needle, haystack) {
    var length = haystack.length;
    for(var i = 0; i < length; i++) {
        if(haystack[i] == needle) return true;
    }
    return false;
}


</script>
<table class="xyz_smap_metalist_table">
<input type="hidden" name="cat_list" id="cat_list" value="">
<input type="hidden" name="xyz_smap_post" id="xyz_smap_post" value="0" >
<?php 

if((get_option('xyz_smap_af')==0 && get_option('xyz_smap_fb_token')!="" && get_option('xyz_smap_pages_ids')!="" && get_option('xyz_smap_app_sel_mode')==0)|| (get_option('xyz_smap_app_sel_mode')==1 && get_option('xyz_smap_page_names')!="")&& get_option('xyz_smap_af')==0)
{
	$postid=0;
	if (isset($_GET['post']))
		$postid=intval($_GET['post']);
	$post_permission=get_option('xyz_smap_post_permission');
	$get_post_meta_future_data='';
	if (get_option('xyz_smap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
		$get_post_meta_future_data=get_post_meta($postid,"xyz_smap_fb_future_to_publish",true);
	if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_fb_permission']))
	{
		$post_permission=$get_post_meta_future_data['post_fb_permission'];
		$xyz_fb_po_method=$get_post_meta_future_data['xyz_fb_po_method'];
		$xyz_fb_message=$get_post_meta_future_data['xyz_fb_message'];
	}
	else {
		$xyz_fb_po_method=get_option('xyz_smap_po_method');
		$xyz_fb_message=get_option('xyz_smap_message');
	}
?>

<tr id="xyz_smap_fbMetabox"><td colspan="2" >
<?php  if(get_option('xyz_smap_post_permission')==1) {?>
<table class="xyz_smap_meta_acclist_table"><!-- FB META -->


<tr>
		<td colspan="2" class="xyz_smap_pleft15 xyz_smap_meta_acclist_table_td"><strong> <?php _e('Facebook','social-media-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	<tr valign="top">
		<td class="xyz_smap_pleft15" width="60%"> <?php _e('Enable auto publish post to my facebook account','social-media-auto-publish'); ?> 
		</td>
	 <td  class="switch-field">
		<label id="xyz_smap_post_permission_yes"><input type="radio" name="xyz_smap_post_permission" id="xyz_smap_post_permission_1" value="1" <?php if ($post_permission==1)echo 'checked';?>/> <?php _e('Yes','social-media-auto-publish'); ?> </label>
		<label id="xyz_smap_post_permission_no"><input type="radio" name="xyz_smap_post_permission" id="xyz_smap_post_permission_0" value="0" <?php if ($post_permission==0) echo 'checked';?>/> <?php _e('No','social-media-auto-publish'); ?> </label>
	 </td>
	</tr>
	<tr valign="top" id="fpmd">
		<td class="xyz_smap_pleft15"> <?php _e('Posting method','social-media-auto-publish'); ?> 
		</td>
		<td><select id="xyz_smap_po_method" name="xyz_smap_po_method">
				<option value="3"
				<?php  if($xyz_fb_po_method==3) echo 'selected';?>> <?php _e('Simple text message','social-media-auto-publish'); ?> </option>
				
				<optgroup label="<?php _e('Text message with image','social-media-auto-publish'); ?>">
					<option value="4"
					<?php  if($xyz_fb_po_method==4) echo 'selected';?>> <?php _e('Upload image to app album','social-media-auto-publish'); ?> </option>
					<option value="5"
					<?php  if($xyz_fb_po_method==5) echo 'selected';?>> <?php _e('Upload image to timeline album','social-media-auto-publish'); ?> </option>
				</optgroup>
				
				<optgroup label="<?php _e('Text message with attached link','social-media-auto-publish'); ?>"">
					<option value="1"
					<?php  if($xyz_fb_po_method==1) echo 'selected';?>> <?php _e('Attach your blog post','social-media-auto-publish'); ?> </option>
					<option value="2"
					<?php  if($xyz_fb_po_method==2) echo 'selected';?>>
						 <?php _e('Share a link to your blog post','social-media-auto-publish'); ?> </option>
					</optgroup>
		</select>
		</td>
	</tr>
	<tr valign="top" id="fpmf">
		<td class="xyz_smap_pleft15"> <?php _e('Message format for posting','social-media-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_fb')" onmouseout="dethide_smap('xyz_fb')">
						<div id="xyz_fb" class="smap_informationdiv" style="display: none;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','social-media-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','social-media-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','social-media-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','social-media-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','social-media-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','social-media-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','social-media-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','social-media-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','social-media-auto-publish'); ?>
						</div>
		</td>
	<td>
	<select name="xyz_smap_fb_info" id="xyz_smap_fb_info" onchange="xyz_smap_fb_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','social-media-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr>
		
		<tr id="fpmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_smap_message"  name="xyz_smap_message" style="height:80px !important;" ><?php echo esc_textarea($xyz_fb_message);?></textarea>
	</td></tr>
	
	</table><?php }?>
	</td></tr>
	<?php }
	
	
	
	
	if((get_option('xyz_smap_ig_token')!="" && get_option('xyz_smap_ig_app_sel_mode')==0 && !empty(get_option('xyz_smap_ig_pages_ids')))|| (get_option('xyz_smap_ig_app_sel_mode')==1 && get_option('xyz_smap_ig_page_names')!="" && get_option('xyz_smap_smapsoln_userid_ig')!=0) )
	{
	    
	    $postid=0;
	    if (isset($_GET['post']))
	        $postid=intval($_GET['post']);
	        $post_permission=get_option('xyz_smap_igpost_permission');
	        $get_post_meta_future_data='';
	        if (get_option('xyz_smap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
	            $get_post_meta_future_data=get_post_meta($postid,"xyz_smap_ig_future_to_publish",true);
	            if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_ig_permission']))
	            {
	                $post_permission=$get_post_meta_future_data['post_ig_permission'];
	                $xyz_ig_message=$get_post_meta_future_data['xyz_ig_message'];
	            }
	            else {
	                $xyz_ig_message=get_option('xyz_smap_igmessage');
	            }
	            ?>

<tr id="xyz_smap_igMetabox"><td colspan="2" >
<?php  if(get_option('xyz_smap_igpost_permission')==1) {?>
<table class="xyz_smap_meta_acclist_table"><!-- FB META -->


<tr>
		<td colspan="2" class="xyz_smap_pleft15 xyz_smap_meta_acclist_table_td"><strong> <?php _e('Instagram','social-media-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	<tr valign="top">
		<td class="xyz_smap_pleft15" width="60%"> <?php _e('Enable auto publish post to my instagram account','social-media-auto-publish'); ?> 
		</td>
	 <td  class="switch-field">
		<label id="xyz_smap_igpost_permission_yes"><input type="radio" name="xyz_smap_igpost_permission" id="xyz_smap_igpost_permission_1" value="1" <?php if ($post_permission==1)echo 'checked';?>/> <?php _e('Yes','social-media-auto-publish'); ?> </label>
		<label id="xyz_smap_igpost_permission_no"><input type="radio" name="xyz_smap_igpost_permission" id="xyz_smap_igpost_permission_0" value="0" <?php if ($post_permission==0) echo 'checked';?>/> <?php _e('No','social-media-auto-publish'); ?> </label>
	 </td>
	</tr>
	<tr valign="top" id="igpm">
		<td class="xyz_smap_pleft15"> <?php _e('Posting method','social-media-auto-publish'); ?> 
		</td>
		<td  id="xyz_smap_ig_po_method">
				<?php _e('Text message with image','social-media-auto-publish'); ?> 
				
		</td>
	</tr>
	<tr valign="top" id="igmf">
		<td class="xyz_smap_pleft15"> <?php _e('Message format for posting','social-media-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_ig')" onmouseout="dethide_smap('xyz_ig')">
						<div id="xyz_ig" class="smap_informationdiv" style="display: none;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','social-media-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','social-media-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','social-media-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','social-media-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','social-media-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','social-media-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','social-media-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','social-media-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','social-media-auto-publish'); ?>
						</div>
		</td>
	<td>
	<select name="xyz_smap_ig_info" id="xyz_smap_ig_info" onchange="xyz_smap_ig_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','social-media-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr>
		
		<tr id="igmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_smap_igmessage"  name="xyz_smap_igmessage" style="height:80px !important;" ><?php echo esc_textarea($xyz_ig_message);?></textarea>
	</td></tr>
	
	</table><?php }?>
	</td></tr>
	<?php 
	}
	
	
	
	if((get_option('xyz_smap_twconsumer_id')!="" && get_option('xyz_smap_twconsumer_secret')!="" && get_option('xyz_smap_tw_id')!="" && get_option('xyz_smap_current_twappln_token')!="" && get_option('xyz_smap_twaccestok_secret')!="" && get_option('xyz_smap_tw_app_sel_mode')==0) || (get_option('xyz_smap_tw_app_sel_mode')==1 && get_option('xyz_smap_smapsoln_userid_tw')!=0))
	{
		$postid=0;
		if (isset($_GET['post']))
			$postid=intval($_GET['post']);
		$post_permission=get_option('xyz_smap_twpost_permission');
		$get_post_meta_future_data='';
		if (get_option('xyz_smap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
			$get_post_meta_future_data=get_post_meta($postid,"xyz_smap_tw_future_to_publish",true);
		if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_tw_permission']))
		{
			$post_permission=$get_post_meta_future_data['post_tw_permission'];
			$xyz_tw_img_permissn=$get_post_meta_future_data['xyz_tw_img_permissn'];
			$xyz_tw_message=$get_post_meta_future_data['xyz_tw_message'];
		}
		else {
			$xyz_tw_img_permissn=get_option('xyz_smap_twpost_image_permission');
			$xyz_tw_message=get_option('xyz_smap_twmessage');
		}
	?>
	
	<tr id="xyz_smap_twMetabox"><td colspan="2" >
<?php  if(get_option('xyz_smap_twpost_permission')==1) {?>
<table class="xyz_smap_meta_acclist_table"><!-- TW META -->


<tr>
		<td colspan="2" class="xyz_smap_pleft15 xyz_smap_meta_acclist_table_td"><strong> <?php _e('Twitter','social-media-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	<tr valign="top">
		<td class="xyz_smap_pleft15" width="60%"> <?php _e('Enable auto publish posts to my twitter account','social-media-auto-publish'); ?>
		</td>
 	 <td  class="switch-field">
		<label id="xyz_smap_twpost_permission_yes"><input type="radio" name="xyz_smap_twpost_permission" id="xyz_smap_twpost_permission_1" value="1" <?php  if ($post_permission==1) echo 'checked';?>/> <?php _e('Yes','social-media-auto-publish'); ?> </label>
		<label id="xyz_smap_twpost_permission_no"><input type="radio" name="xyz_smap_twpost_permission" id="xyz_smap_twpost_permission_0" value="0" <?php if ($post_permission==0) echo "checked";?>/> <?php _e('No','social-media-auto-publish'); ?> </label>
	 </td>
	</tr>
	
	<tr valign="top" id="twai">
		<td class="xyz_smap_pleft15"> <?php _e('Attach image to twitter post','social-media-auto-publish'); ?> 
		</td>
		<td><select id="xyz_smap_twpost_image_permission" name="xyz_smap_twpost_image_permission">
				<option value="0"
				<?php  if($xyz_tw_img_permissn==0) echo 'selected';?>>
					 <?php _e('No','social-media-auto-publish'); ?> </option>
				<option value="1"
				<?php  if($xyz_tw_img_permissn==1) echo 'selected';?>> <?php _e('Yes','social-media-auto-publish'); ?> </option>
		</select>
		</td>
	</tr>
	
	<tr valign="top" id="twmf">
		<td class="xyz_smap_pleft15"> <?php _e('Message format for posting','social-media-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_tw')" onmouseout="dethide_smap('xyz_tw')">
						<div id="xyz_tw" class="smap_informationdiv" style="display: none; font-weight: normal;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','social-media-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','social-media-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','social-media-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','social-media-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','social-media-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','social-media-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','social-media-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','social-media-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','social-media-auto-publish'); ?>
						</div>
		</td>
		
		
	<td>
	<select name="xyz_smap_tw_info" id="xyz_smap_tw_info" onchange="xyz_smap_tw_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','social-media-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr>
		
		<tr id="twmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_smap_twmessage"  name="xyz_smap_twmessage" style="height:80px !important;" ><?php echo esc_textarea($xyz_tw_message);?></textarea>
	</td></tr>
	
	</table>
	<?php }?>
	</td></tr>
	<?php }
	if((get_option('xyz_smap_tbconsumer_id')!="" && get_option('xyz_smap_tbconsumer_secret')!="" && get_option('xyz_smap_tb_id')!="" && get_option('xyz_smap_current_tbappln_token')!="" && get_option('xyz_smap_tbaccestok_secret')!=""))
	{
	$post_permission=1;
	if (get_option('xyz_smap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
	    $get_post_meta_future_data=get_post_meta($postid,"xyz_smap_tbap_future_to_publish",true);
	    if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['xyz_smap_tbpost_media_permission']))
	    {
	        $post_permission=$get_post_meta_future_data['post_tb_permission'];
	        $xyz_smap_tbmedia_permission=$get_post_meta_future_data['xyz_smap_tbpost_media_permission'];
	        $xyz_smap_tbmessage=$get_post_meta_future_data['xyz_smap_tbmessage'];
	    }
	    else {
	        $xyz_smap_tbmedia_permission=get_option('xyz_smap_tbpost_media_permission');
	        $xyz_smap_tbmessage=get_option('xyz_smap_tbmessage');
	    }
	    ?>
	   <tr id="xyz_smap_tbMetabox"><td colspan="2" >
<?php  if(get_option('xyz_smap_tbpost_permission')==1) {?>
<table class="xyz_smap_meta_acclist_table"><!-- tb META -->
<tr>
		<td colspan="2" class="xyz_smap_pleft15 xyz_smap_meta_acclist_table_td"><strong> <?php _e('Tumblr','social-media-auto-publish'); ?> </strong>
		</td>
</tr>
<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	<tr valign="top">
		<td class="xyz_smap_pleft15" width="60%"> <?php _e('Enable auto publish posts to my tumblr account','social-media-auto-publish'); ?> 
		</td>
		<td  class="switch-field">
		<label id="xyz_smap_tbpost_permission_yes"><input type="radio" name="xyz_smap_tbpost_permission" id="xyz_smap_tbpost_permission_1" value="1" <?php if ($post_permission==1) echo "checked";?>/> <?php _e('Yes','social-media-auto-publish'); ?> </label>
		<label id="xyz_smap_tbpost_permission_no"><input type="radio" name="xyz_smap_tbpost_permission" id="xyz_smap_tbpost_permission_0" value="0" <?php if ($post_permission==0) echo "checked";?>/> <?php _e('No','social-media-auto-publish'); ?> </label>
	</td>
	</tr>
	<tr valign="top" id="tbai">
		<td class="xyz_smap_pleft15"> <?php _e('Attachment to be posted to Tumblr','social-media-auto-publish'); ?> 
		</td>
		<td><select id="xyz_smap_tbpost_media_permission" name="xyz_smap_tbpost_media_permission">
		<option value="0" <?php  if($xyz_smap_tbmedia_permission==0) echo 'selected';?>> <?php _e('No','social-media-auto-publish'); ?> </option>
		<option value="1" <?php  if($xyz_smap_tbmedia_permission==1) echo 'selected';?>> <?php _e('Image','social-media-auto-publish'); ?> </option>
		<option value="2" <?php  if($xyz_smap_tbmedia_permission==2) echo 'selected';?>> <?php _e('Link','social-media-auto-publish'); ?> </option>
		</select>
		</td>
	</tr>
	<tr valign="top" id="tbmf">
		<td class="xyz_smap_pleft15"> <?php _e('Message format for posting','social-media-auto-publish'); ?>  <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_tb')" onmouseout="dethide_smap('xyz_tb')" style="width:13px;height:auto;">
						<div id="xyz_tb" class="smap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','social-media-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','social-media-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','social-media-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','social-media-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','social-media-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','social-media-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','social-media-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','social-media-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','social-media-auto-publish'); ?>
						</div></td>
	<td>
	<select name="xyz_smap_tb_info" id="xyz_smap_tb_info" onchange="xyz_smap_tb_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','social-media-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr>
		<tr id="tbmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_smap_tbmessage"  name="xyz_smap_tbmessage" style="height:80px !important;" ><?php echo esc_textarea($xyz_smap_tbmessage);?></textarea>
	</td></tr>
	</table>
	<?php }?>
	</td></tr>	
	<?php }?>	
</table>
	
	<?php if(get_option('xyz_smap_lnaf')==0){?>
	
	<tr id="xyz_smap_lnMetabox"><td colspan="2" >
<?php  if(get_option('xyz_smap_lnpost_permission')==1 && ( get_option('xyz_smap_ln_company_ids')!=''|| get_option('xyz_smap_lnshare_to_profile')==1 || get_option('xyz_smap_ln_api_permission')==0)) {
	$postid=0;
	if (isset($_GET['post']))
		$postid=intval($_GET['post']);
		$post_permission=get_option('xyz_smap_lnpost_permission');
		$get_post_meta_future_data='';
	if (get_option('xyz_smap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
		$get_post_meta_future_data=get_post_meta($postid,"xyz_smap_ln_future_to_publish",true);
	if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_ln_permission']))
	{
		$post_permission=$get_post_meta_future_data['post_ln_permission'];
		$xyz_smap_ln_shareprivate=$get_post_meta_future_data['xyz_smap_ln_shareprivate'];
		$xyz_smap_lnmessage=$get_post_meta_future_data['xyz_smap_lnmessage'];
		$xyz_smap_lnpost_method=$get_post_meta_future_data['xyz_smap_lnpost_method'];
	}
	else {
		$xyz_smap_ln_shareprivate=get_option('xyz_smap_ln_shareprivate');
		$xyz_smap_lnmessage=get_option('xyz_smap_lnmessage');
		$xyz_smap_lnpost_method=get_option('xyz_smap_lnpost_method');
	}
	?>
<table class="xyz_smap_meta_acclist_table"><!-- LI META -->


<tr>
		<td colspan="2" class="xyz_smap_pleft15 xyz_smap_meta_acclist_table_td"><strong> <?php _e('LinkedIn','social-media-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	<tr valign="top" >
		<td class="xyz_smap_pleft15" width="60%"> <?php _e('Enable auto publish posts to my linkedin account','social-media-auto-publish'); ?> 
		</td>
	 	  <td  class="switch-field">
			<label id="xyz_smap_lnpost_permission_yes"><input type="radio" name="xyz_smap_lnpost_permission" id="xyz_smap_lnpost_permission_1" value="1" <?php if ($post_permission==1) echo 'checked';?>/> <?php _e('Yes','social-media-auto-publish'); ?> </label>
			<label id="xyz_smap_lnpost_permission_no"><input type="radio" name="xyz_smap_lnpost_permission" id="xyz_smap_lnpost_permission_0" value="0" <?php if ($post_permission==0) echo 'checked';?>/> <?php _e('No','social-media-auto-publish'); ?> </label>
		 </td>
	</tr>
	<?php if ( get_option('xyz_smap_lnshare_to_profile')==1){?>
	<tr valign="top" id="shareprivate">
<!-- 	<input type="hidden" name="xyz_smap_ln_sharingmethod" id="xyz_smap_ln_sharingmethod" value="0"> -->
	<td class="xyz_smap_pleft15"> <?php _e('Share post content with','social-media-auto-publish'); ?> </td>
	<td>
		<select id="xyz_smap_ln_shareprivate" name="xyz_smap_ln_shareprivate" >
		 <option value="0" <?php  if($xyz_smap_ln_shareprivate==0) echo 'selected';?>>
<?php _e('Public','social-media-auto-publish'); ?> </option><option value="1" <?php  if($xyz_smap_ln_shareprivate==1) echo 'selected';?>> <?php _e('Connections only','social-media-auto-publish'); ?> </option></select>
	</td></tr>
	<?php }?>

	<tr valign="top" id="lnmf">
		<td class="xyz_smap_pleft15"> <?php _e('Message format for posting','social-media-auto-publish'); ?> <img src="<?php echo $heimg?>"
						onmouseover="detdisplay_smap('xyz_ln')" onmouseout="dethide_smap('xyz_ln')">
						<div id="xyz_ln" class="smap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','social-media-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','social-media-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','social-media-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','social-media-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','social-media-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','social-media-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','social-media-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','social-media-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','social-media-auto-publish'); ?>
						</div>
		</td>
	<td>
	<select name="xyz_smap_ln_info" id="xyz_smap_ln_info" onchange="xyz_smap_ln_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','social-media-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value ="9">{USER_DISPLAY_NAME}   </option>
		</select> </td></tr>
		
		<tr id="lnmftarea"><td>&nbsp;</td><td>
		<textarea id="xyz_smap_lnmessage"  name="xyz_smap_lnmessage" style="height:80px !important;" ><?php echo esc_textarea($xyz_smap_lnmessage);?></textarea>
	</td></tr>
	<tr valign="top" id="lnpm">
		<td> <?php _e('Posting method','social-media-auto-publish'); ?> </td>
		<td>
		<select id="xyz_smap_lnpost_method" name="xyz_smap_lnpost_method">
				<option value="1"
	<?php  if($xyz_smap_lnpost_method==1) echo 'selected';?>> <?php _e('Simple text message','social-media-auto-publish'); ?> </option>
				<option value="2"
	<?php  if($xyz_smap_lnpost_method==2) echo 'selected';?>> <?php _e('Attach your blog post','social-media-auto-publish'); ?> </option>
				<option value="3"
	<?php  if($xyz_smap_lnpost_method==3) echo 'selected';?>> <?php _e('Text message with image','social-media-auto-publish'); ?> </option>	
		</select>
		</td>
	</tr>
	
	</table>
	<?php }?>
	</td></tr>
	<?php }?>
</table>
<script type="text/javascript">
	displaycheck();



	var edit_flag="<?php echo $GLOBALS['edit_flag'];?>";
	if(edit_flag==1)
		load_edit_action();
	if(edit_flag!=1)
		load_create_action();
	function load_edit_action()
	{
		document.getElementById("xyz_smap_post").value=1;
		var xyz_smap_default_selection_edit="<?php echo esc_html(get_option('xyz_smap_default_selection_edit'));?>";
		if(xyz_smap_default_selection_edit=="")
			xyz_smap_default_selection_edit=0;
		if(xyz_smap_default_selection_edit==1 || xyz_smap_default_selection_edit==2)
			return;
		
		//FB 
			
	jQuery('#xyz_smap_post_permission_0').attr('checked',true);
		displaycheck();
				
        //TW 
			
		jQuery('#xyz_smap_twpost_permission_0').attr('checked',true);
		displaycheck();

		//TB 
		jQuery('#xyz_smap_tbpost_permission_0').attr('checked',true);
		displaycheck();

		//LN								
			
		jQuery('#xyz_smap_lnpost_permission_0').attr('checked',true);
		displaycheck();
		
		//IG
		
		jQuery('#xyz_smap_igpost_permission_0').attr('checked',true);
		displaycheck();
	}
function load_create_action()
	{
		document.getElementById("xyz_smap_post").value=1;
		var xyz_smap_default_selection_create="<?php echo esc_html(get_option('xyz_smap_default_selection_create'));?>";
		if(xyz_smap_default_selection_create=="")
			xyz_smap_default_selection_create=0;
		if(xyz_smap_default_selection_create==1)
			return;
		//FB 
	jQuery('#xyz_smap_post_permission_0').attr('checked',true);
		displaycheck();
        //TW 
		jQuery('#xyz_smap_twpost_permission_0').attr('checked',true);
		displaycheck();

		//TB 
		jQuery('#xyz_smap_tbpost_permission_0').attr('checked',true);
		displaycheck();
		//LN								
		jQuery('#xyz_smap_lnpost_permission_0').attr('checked',true);
		displaycheck();
		//IG
		jQuery('#xyz_smap_igpost_permission_0').attr('checked',true);
		displaycheck();
	}

	

	function xyz_smap_fb_info_insert(inf){
		
	    var e = document.getElementById("xyz_smap_fb_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_smap_message").val()+ins_opt;
	    jQuery("textarea#xyz_smap_message").val(str);
	    jQuery('#xyz_smap_fb_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_smap_message").focus();

	}
	function xyz_smap_tw_info_insert(inf){
		
	    var e = document.getElementById("xyz_smap_tw_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_smap_twmessage").val()+ins_opt;
	    jQuery("textarea#xyz_smap_twmessage").val(str);
	    jQuery('#xyz_smap_tw_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_smap_twmessage").focus();

	}
  function xyz_smap_tb_info_insert(inf){
	    var e = document.getElementById("xyz_smap_tb_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_smap_tbmessage").val()+ins_opt;
	    jQuery("textarea#xyz_smap_tbmessage").val(str);
	    jQuery('#xyz_smap_tb_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_smap_tbmessage").focus();

	}
	function xyz_smap_ln_info_insert(inf){
		
	    var e = document.getElementById("xyz_smap_ln_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_smap_lnmessage").val()+ins_opt;
	    jQuery("textarea#xyz_smap_lnmessage").val(str);
	    jQuery('#xyz_smap_ln_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_smap_lnmessage").focus();

	}
	function xyz_smap_ig_info_insert(inf){
		
	    var e = document.getElementById("xyz_smap_ig_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_smap_igmessage").val()+ins_opt;
	    jQuery("textarea#xyz_smap_igmessage").val(str);
	    jQuery('#xyz_smap_ig_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_smap_igmessage").focus();

	}
	
	
	jQuery("#xyz_smap_twpost_permission_no").click(function(){
		displaycheck();
		XyzSmapToggleRadio(0,'xyz_smap_twpost_permission');
		
	});
	jQuery("#xyz_smap_twpost_permission_yes").click(function(){
		displaycheck();
		XyzSmapToggleRadio(1,'xyz_smap_twpost_permission');
		
	});
    jQuery("#xyz_smap_tbpost_permission_yes").click(function(){
		displaycheck();
		XyzSmapToggleRadio(1,'xyz_smap_tbpost_permission');

	});
	jQuery("#xyz_smap_tbpost_permission_no").click(function(){
		displaycheck();
		XyzSmapToggleRadio(0,'xyz_smap_tbpost_permission');
	});
	jQuery("#xyz_smap_post_permission_no").click(function(){
		displaycheck();
		XyzSmapToggleRadio(0,'xyz_smap_post_permission');
		
	});
	jQuery("#xyz_smap_post_permission_yes").click(function(){
		displaycheck();
		XyzSmapToggleRadio(1,'xyz_smap_post_permission');
		
	});
	jQuery("#xyz_smap_lnpost_permission_no").click(function(){
		displaycheck();
		XyzSmapToggleRadio(0,'xyz_smap_lnpost_permission');
		
	});
	jQuery("#xyz_smap_lnpost_permission_yes").click(function(){
		displaycheck();
		XyzSmapToggleRadio(1,'xyz_smap_lnpost_permission');
		
	});
	jQuery("#xyz_smap_igpost_permission_no").click(function(){
		displaycheck();
		XyzSmapToggleRadio(0,'xyz_smap_igpost_permission');
		
	});
	jQuery("#xyz_smap_igpost_permission_yes").click(function(){
		displaycheck();
		XyzSmapToggleRadio(1,'xyz_smap_igpost_permission');
		
	});
	
	
	</script>
<?php 
}
