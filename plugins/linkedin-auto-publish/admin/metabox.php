<?php 
if( !defined('ABSPATH') ){ exit();}
add_action( 'add_meta_boxes', 'xyz_lnap_add_custom_box' );
$GLOBALS['edit_flag']=0;
function xyz_lnap_add_custom_box()
{
	$posttype="";
	if(isset($_GET['post_type']))
		$posttype=$_GET['post_type'];
	
	if($posttype=="")
		$posttype="post";
	
if(isset($_GET['action']) && $_GET['action']=="edit" && !empty($_GET['post']))  /// empty check added for fixing client scenario
	{
		$postid=intval($_GET['post']);
		
		
		$get_post_meta=get_post_meta($postid,"xyz_lnap",true);
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

		$xyz_lnap_include_pages=get_option('xyz_lnap_include_pages');
		if($xyz_lnap_include_pages==0)
			return;
	}
	else if($posttype=="post")
	{
		$xyz_lnap_include_posts=get_option('xyz_lnap_include_posts');
		if($xyz_lnap_include_posts==0)
			return;
	}
	else if($posttype!="post")
	{

		$xyz_lnap_include_customposttypes=get_option('xyz_lnap_include_customposttypes');


		$carr=explode(',', $xyz_lnap_include_customposttypes);
		if(!in_array($posttype,$carr))
			return;

	}

	if(get_option('xyz_lnap_lnaf')==0 && get_option('xyz_lnap_lnpost_permission')==1 && (get_option('xyz_lnap_ln_share_post_company')!=''|| get_option('xyz_lnap_lnshare_to_profile')==1 || get_option('xyz_lnap_ln_api_permission')==0))
	add_meta_box( "xyz_lnap", '<strong>WP to LinkedIn Auto Publish </strong>', 'xyz_lnap_addpostmetatags') ;
}

function xyz_lnap_addpostmetatags()
{
	$imgpath= plugins_url()."/linkedin-auto-publish/images/";
	$heimg=$imgpath."support.png";
	$xyz_lnap_catlist=get_option('xyz_lnap_include_categories');
// 	if (is_array($xyz_lnap_catlist))
// 		$xyz_lnap_catlist=implode(',', $xyz_lnap_catlist);
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
                    var xyz_lnap_default_selection_edit="<?php echo esc_html(get_option('xyz_lnap_default_selection_edit'));?>";
                    var xyz_lnap_lnshare_to_profile ='<?php echo get_option('xyz_lnap_lnshare_to_profile');?>';
                    
                    if(xyz_lnap_default_selection_edit==0 && jQuery("input[name='xyz_lnap_lnpost_permission']:checked").val()==1) {
                    
                        document.getElementById("lnmf_lnap").style.display='none';	
                    	document.getElementById("lnmftarea_lnap").style.display='none';
                    		
                    	if(xyz_lnap_lnshare_to_profile==1)
                    	    document.getElementById("shareprivate_lnap").style.display='none';		
                    	    
                    	document.getElementById("lnap_lnpm").style.display='none';		
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_0').prop('checked',true);
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_yes').removeClass('xyz_lnap_toggle_on');
                    	jQuery('#xyz_lnap_lnpost_permission_yes').addClass('xyz_lnap_toggle_off');
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_no').removeClass('xyz_lnap_toggle_off');
                    	jQuery('#xyz_lnap_lnpost_permission_no').addClass('xyz_lnap_toggle_on');                    	                    
                    }
                    else if(xyz_lnap_default_selection_edit==1 && jQuery("input[name='xyz_lnap_lnpost_permission']:checked").val()==0) {
                    
                    	document.getElementById("lnmf_lnap").style.display='';	
                    	document.getElementById("lnmftarea_lnap").style.display='';	
                    	
                    	if(xyz_lnap_lnshare_to_profile==1)
                    	    document.getElementById("shareprivate_lnap").style.display='';	
                    	    
                    	document.getElementById("lnap_lnpm").style.display='';	
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_1').prop('checked',true);
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_no').removeClass('xyz_lnap_toggle_on');
                    	jQuery('#xyz_lnap_lnpost_permission_no').addClass('xyz_lnap_toggle_off');
                    	
                    	jQuery('#xyz_lnap_lnpost_permission_yes').removeClass('xyz_lnap_toggle_off');
                    	jQuery('#xyz_lnap_lnpost_permission_yes').addClass('xyz_lnap_toggle_on');
                    }	
                }
                wasSaving = isSaving;
            },
        }
    })();
    
    wp.data.subscribe( appuntiStatusChange.refreshMetabox );
});
/*************************************************************************************/

function displaycheck_lnap()
{
var lcheckid=jQuery("input[name='xyz_lnap_lnpost_permission']:checked").val();
var xyz_lnap_lnshare_to_profile ='<?php echo get_option('xyz_lnap_lnshare_to_profile');?>';
if(lcheckid==1)
{

	
	document.getElementById("lnmf_lnap").style.display='';	
	document.getElementById("lnmftarea_lnap").style.display='';	
	if(xyz_lnap_lnshare_to_profile==1)
	document.getElementById("shareprivate_lnap").style.display='';	
	document.getElementById("lnap_lnpm").style.display='';	
}
else
{
	document.getElementById("lnmf_lnap").style.display='none';	
	document.getElementById("lnmftarea_lnap").style.display='none';	
	if(xyz_lnap_lnshare_to_profile==1)
	document.getElementById("shareprivate_lnap").style.display='none';		
	document.getElementById("lnap_lnpm").style.display='none';		
}

}


</script>
<script type="text/javascript">
function detdisplay_lnap(id)
{
	document.getElementById(id).style.display='';
}
function dethide_lnap(id)
{
	document.getElementById(id).style.display='none';
}

jQuery(document).ready(function() {
	displaycheck_lnap();
	
	 var xyz_lnap_lnpost_permission=jQuery("input[name='xyz_lnap_lnpost_permission']:checked").val();
	 XyzLnapToggleRadio(xyz_lnap_lnpost_permission,'xyz_lnap_lnpost_permission'); 
	var wp_version='<?php echo XYZ_WP_LNAP_WP_VERSION; ?>';
	if (wp_version <= '5.3') {
	jQuery('#category-all').bind("DOMSubtreeModified",function(){
		lnap_get_categorylist(1);
		});
	
	lnap_get_categorylist(1);lnap_get_categorylist(2);
	jQuery('#category-all').on("click",'input[name="post_category[]"]',function() {
		lnap_get_categorylist(1);
				});

	jQuery('#category-pop').on("click",'input[type="checkbox"]',function() {
		lnap_get_categorylist(2);
				});
	/////////gutenberg category selection
	jQuery(document).on('change', 'input[type="checkbox"]', function() {
		lnap_get_categorylist(2);
				});
}
});

function lnap_get_categorylist(val)
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
		jQuery('#category-pop input[type="checkbox"]:checked').each(function() {
			cat_list+=this.value+",";flag=false;
		});
		jQuery('.editor-post-taxonomies__hierarchical-terms-choice input[type="checkbox"]:checked').each(function() { //gutenberg category checkbox
			cat_list+=this.value+",";flag=false;
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
			
					<?php }?> flag=false;
			}
	}
	 if (cat_list.charAt(cat_list.length - 1) == ',') {
		 cat_list = cat_list.substr(0, cat_list.length - 1);
		}
		jQuery('#cat_list').val(cat_list);
		
		var xyz_lnap_catlist="<?php echo esc_html($xyz_lnap_catlist);?>";
		if(xyz_lnap_catlist!="All")
		{
			cat_list_array=xyz_lnap_catlist.split(',');
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
				jQuery('#xyz_lnap_Metabox').hide();
			else
				jQuery('#xyz_lnap_Metabox').show();
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
<table class="xyz_lnap_metalist_table">
<input type="hidden" name="cat_list" id="cat_list" value="">
<input type="hidden" name="xyz_lnap_post" id="xyz_lnap_post" value="0" >
<tr id="xyz_lnap_Metabox"><td colspan="2" >
<?php  if(get_option('xyz_lnap_lnpost_permission')==1) {
	$postid=0;
	if (isset($_GET['post']))
		$postid=intval($_GET['post']);
		$post_permission=1;
		$get_post_meta_future_data='';
		if (get_option('xyz_lnap_default_selection_edit')==2 && isset($GLOBALS['edit_flag']) && $GLOBALS['edit_flag']==1 && !empty($postid))
			$get_post_meta_future_data=get_post_meta($postid,"xyz_lnap_future_to_publish",true);
			if (!empty($get_post_meta_future_data)&& isset($get_post_meta_future_data['post_ln_permission']))
			{
				$post_permission=$get_post_meta_future_data['post_ln_permission'];
				$xyz_lnap_ln_shareprivate=$get_post_meta_future_data['xyz_lnap_ln_shareprivate'];
				$xyz_lnap_lnpost_method=$get_post_meta_future_data['xyz_lnap_lnpost_method'];
				$xyz_lnap_lnmessage=$get_post_meta_future_data['xyz_lnap_lnmessage'];
			}
			else {
				$xyz_lnap_lnpost_method=get_option('xyz_lnap_lnpost_method');
				$xyz_lnap_lnmessage=get_option('xyz_lnap_lnmessage');
				$xyz_lnap_ln_shareprivate=get_option('xyz_lnap_ln_shareprivate');
			}
?>
<table class="xyz_lnap_meta_acclist_table"><!-- LI META -->


<tr>
		<td colspan="2" class="xyz_lnap_pleft15 xyz_lnap_meta_acclist_table_td"><strong> <?php _e('LinkedIn','linkedin-auto-publish'); ?> </strong>
		</td>
</tr>

<tr><td colspan="2" valign="top">&nbsp;</td></tr>
	
	
	<tr valign="top">
		<td class="xyz_lnap_pleft15" width="60%"> <?php _e('Enable auto publish posts to my linkedin account','linkedin-auto-publish'); ?> 
		</td>
	 <td  class="switch-field">
		<label id="xyz_lnap_lnpost_permission_yes"><input type="radio" name="xyz_lnap_lnpost_permission" id="xyz_lnap_lnpost_permission_1" value="1" <?php if($post_permission==1) echo 'checked';?>/> <?php _e('Yes','linkedin-auto-publish'); ?> </label>
		<label id="xyz_lnap_lnpost_permission_no"><input type="radio" name="xyz_lnap_lnpost_permission" id="xyz_lnap_lnpost_permission_0" value="0"  <?php if($post_permission==0) echo 'checked';?>/> <?php _e('No','linkedin-auto-publish'); ?> </label>
	 </td>
	</tr>
	<?php if ( get_option('xyz_lnap_lnshare_to_profile')==1){?>
	<tr valign="top" id="shareprivate_lnap">
	<!--<input type="hidden" name="xyz_lnap_ln_sharingmethod" id="xyz_lnap_ln_sharingmethod" value="0"> -->
	<td class="xyz_lnap_pleft15"> <?php _e('Share post content with','linkedin-auto-publish');; ?> </td>
	<td>
		<select id="xyz_lnap_ln_shareprivate" name="xyz_lnap_ln_shareprivate" >
		 <option value="0" <?php  if($xyz_lnap_ln_shareprivate==0) echo 'selected'; ?>> <?php _e('Public','linkedin-auto-publish'); ?> </option>
		 <option value="1" <?php  if($xyz_lnap_ln_shareprivate==1) echo 'selected'; ?>> <?php _e('Connections only','linkedin-auto-publish'); ?> </option>
		</select>
	</td></tr>
<?php }?>
	<tr valign="top" id="lnmf_lnap">
		<td class="xyz_lnap_pleft15"> <?php _e('Message format for posting','linkedin-auto-publish'); ?> <img src="<?php echo $heimg?>" 
						onmouseover="detdisplay_lnap('xyz_lnap_informationdiv')" onmouseout="dethide_lnap('xyz_lnap_informationdiv')" style="width:13px;height:auto;">
						<div id="xyz_lnap_informationdiv" class="lnap_informationdiv"
							style="display: none; font-weight: normal;">
							{POST_TITLE} - <?php _e('Insert the title of your post.','linkedin-auto-publish'); ?><br/>
							{PERMALINK} - <?php _e('Insert the URL where your post is displayed.','linkedin-auto-publish'); ?><br/>
							{POST_EXCERPT} - <?php _e('Insert the excerpt of your post.','linkedin-auto-publish'); ?><br/>
							{POST_CONTENT} - <?php _e('Insert the description of your post.','linkedin-auto-publish'); ?><br/>
							{BLOG_TITLE} - <?php _e('Insert the name of your blog.','linkedin-auto-publish'); ?><br/>
							{USER_NICENAME} - <?php _e('Insert the nicename of the author.','linkedin-auto-publish'); ?><br/>
							{POST_ID} - <?php _e('Insert the ID of your post.','linkedin-auto-publish'); ?><br/>
							{POST_PUBLISH_DATE} - <?php _e('Insert the publish date of your post.','linkedin-auto-publish'); ?><br/>
							{USER_DISPLAY_NAME} - <?php _e('Insert the display name of the author.','linkedin-auto-publish'); ?>
						</div></td>

	<td>
	<select name="xyz_lnap_info" id="xyz_lnap_info" onchange="xyz_lnap_info_insert(this)">
		<option value ="0" selected="selected"> --<?php _e('Select','linkedin-auto-publish'); ?>-- </option>
		<option value ="1">{POST_TITLE}  </option>
		<option value ="2">{PERMALINK} </option>
		<option value ="3">{POST_EXCERPT}  </option>
		<option value ="4">{POST_CONTENT}   </option>
		<option value ="5">{BLOG_TITLE}   </option>
		<option value ="6">{USER_NICENAME}   </option>
		<option value ="7">{POST_ID}   </option>
		<option value ="8">{POST_PUBLISH_DATE}   </option>
		<option value= "9">{USER_DISPLAY_NAME}</option>
		</select> </td></tr>
		
		<tr id="lnmftarea_lnap"><td>&nbsp;</td><td>
		<textarea id="xyz_lnap_lnmessage"  name="xyz_lnap_lnmessage" style="height:80px !important;" ><?php echo esc_textarea($xyz_lnap_lnmessage);?></textarea>
	</td></tr>
	
	<tr valign="top" id="lnap_lnpm">
		<td style="padding-left: 15px;"> <?php _e('Posting method','linkedin-auto-publish'); ?> </td>
		<td>
		<select id="xyz_lnap_lnpost_method" name="xyz_lnap_lnpost_method">
				<option value="1"
	<?php  if($xyz_lnap_lnpost_method==1) echo 'selected'; ?>> <?php _e('Simple text message','linkedin-auto-publish'); ?> </option>
				<option value="2"
				<?php  if($xyz_lnap_lnpost_method==2) echo 'selected'; ?>> <?php _e('Attach your blog post','linkedin-auto-publish'); ?> </option>
				<option value="3"
				<?php  if($xyz_lnap_lnpost_method==3) echo 'selected'; ?>> <?php _e('Text message with image','linkedin-auto-publish'); ?> </option>
		</select>
		</td>
	</tr>
	</table>
	<?php }?>
	</td></tr>
	
	
</table>
<script type="text/javascript">

	var edit_flag="<?php echo $GLOBALS['edit_flag'];?>";
	if(edit_flag==1)
		load_edit_action();
	if(edit_flag!=1)
		load_create_action();
	function load_edit_action()
	{
		document.getElementById("xyz_lnap_post").value=1;
		var xyz_lnap_default_selection_edit="<?php echo esc_html(get_option('xyz_lnap_default_selection_edit'));?>";
		if(xyz_lnap_default_selection_edit=="")
			xyz_lnap_default_selection_edit=0;
		if(xyz_lnap_default_selection_edit==1 || xyz_lnap_default_selection_edit==2)
			return;
		jQuery('#xyz_lnap_lnpost_permission_0').attr('checked',true);
		displaycheck_lnap();


	}
	function load_create_action()
	{
		document.getElementById("xyz_lnap_post").value=1;
		var xyz_lnap_default_selection_create="<?php echo esc_html(get_option('xyz_lnap_default_selection_create'));?>";
		if(xyz_lnap_default_selection_create=="")
			xyz_lnap_default_selection_create=0;
		if(xyz_lnap_default_selection_create==1 || xyz_lnap_default_selection_create==2)
			return;
		jQuery('#xyz_lnap_lnpost_permission_0').attr('checked',true);
		displaycheck_lnap();
	}
function xyz_lnap_info_insert(inf){
		
	    var e = document.getElementById("xyz_lnap_info");
	    var ins_opt = e.options[e.selectedIndex].text;
	    if(ins_opt=="0")
	    	ins_opt="";
	    var str=jQuery("textarea#xyz_lnap_lnmessage").val()+ins_opt;
	    jQuery("textarea#xyz_lnap_lnmessage").val(str);
	    jQuery('#xyz_lnap_info :eq(0)').prop('selected', true);
	    jQuery("textarea#xyz_lnap_lnmessage").focus();

	}
	jQuery("#xyz_lnap_lnpost_permission_no").click(function(){
		displaycheck_lnap();
		XyzLnapToggleRadio(0,'xyz_lnap_lnpost_permission');
		
	});
	jQuery("#xyz_lnap_lnpost_permission_yes").click(function(){
		displaycheck_lnap();
		XyzLnapToggleRadio(1,'xyz_lnap_lnpost_permission');
		
	});
	</script>
<?php 
}
?>