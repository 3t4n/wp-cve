<?php
/*
Plugin Name: WordPress Random Post
Plugin URI: http://web-dorado.com/products/spider-random-post.html
Description: Spider Random Post allows you to show posts in a random order in a sidebar.
Version: 1.0.4
Author: WebDorado
Author URI: https://web-dorado.com
Author License: GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
*/


$spider_random_article_id=0;


function spider_random_article_scripts() {
    wp_enqueue_script('jquery');       
}    
 
add_action('wp_enqueue_scripts', 'spider_random_article_scripts');


	class spider_random_article extends WP_Widget {

	// Constructor //

		function spider_random_article() {
			$widget_ops = array( 'classname' => 'spider_random_article', 'description' => 'Spider Random Post allows you to show posts in a random order in a sidebar.' ); // Widget Settings
			$control_ops = array( 'id_base' => 'spider_random_article' ); // Widget Control Settings
			parent::__construct('spider_random_article', 'Spider Random Post', $widget_ops, $control_ops ); // Create the widget
		}

	// Extract Args //

		function widget($args, $instance) {
			extract( $args );
			$title=$instance['title'];
	
			$url = plugins_url();
	global $spider_random_article_id;
	// Before widget //
	

			echo $before_widget;

	// Title of widget //

			if ( $title ) { echo $before_title . $title . $after_title; }

	// Widget output //








if($spider_random_article_id==0){
         /////// print script code one time
?>
<script type="text/javascript">

function autoUpdate(id,time,category,limit,style,text_for_insert){

	document.getElementById('randarticle_'+id).innerHTML=text_for_insert;
var t=Math.floor(Math.random()*4+1);


		
		if (style==5){

style=t;
 

}

  if (style == 1){
 jQuery("#randarticle_"+id+"").ready(function()
	{	  
  jQuery("#randarticle_"+id+"").animate({
     
    opacity: 1,
	margin:'0in' ,   
    fontSize: "1em"
    
  },1000 );
});
   setTimeout("style("+id+","+style+","+time+','+category+','+limit+")", time*1000);	
  }
 
 
  if (style == 2){
 jQuery("#randarticle_"+id+"").ready(function()
	{
    jQuery("#randarticle_"+id+"").animate({
     
    opacity: 1,
    
    fontSize: "1.2em"
    
  },700 );
  
  jQuery("#randarticle_"+id+"").animate({
     
    opacity: 1,
    
    fontSize: "1em"
    
  } ,300);
});
   setTimeout("style("+id+","+style+","+time+','+category+','+limit+")", time*1000);
  }
  
  if (style == 3){
 jQuery("#randarticle_"+id+"").ready(function()
	{
   jQuery("#randarticle_"+id+"").animate({
     
    opacity: 1,
    
    fontSize: "1em"
    
  }, 1000 );
  });
   setTimeout("style("+id+","+style+","+time+','+category+','+limit+")", time*1000);	
  }
  
  if (style == 4){
document.getElementById("randarticle_"+id).style.overflow="hidden";
jQuery("#randarticle_"+id+"").ready(function()
	{
  jQuery("#randarticle_"+id+"").animate({
    width: "100%",
    opacity: 1,
    fontSize: "1em"
    
  },1000);
  });
	
   setTimeout("style("+id+","+style+","+time+','+category+','+limit+")", time*1000);	
  }
  
}





function style(id,style,time,category,limit)
{ 
if (style == 1)
{
   jQuery("#randarticle_"+id+"").ready(function()
	{
		
		jQuery("#randarticle_"+id+"").animate({
    
    opacity: 0,
	
    marginLeft: "0.6in",
   fontSize: "1em"
    
  },1000 );
  
		
	});	
}
if (style == 2)
{

   jQuery("#randarticle_"+id+"").ready(function()
	{
		
		jQuery("#randarticle_"+id+"").animate({
    
    opacity: 0,
	
    
   fontSize: "0em"
    
  },1000 );
 
		
	});	
}


if (style == 3)
{
   jQuery("#randarticle_"+id+"").ready(function()
	{
		
		jQuery("#randarticle_"+id+"").animate({
    
    opacity: 0,
	
    
   fontSize: "1em"
    
  }, 1000 );
 
		
	});	
}

if (style == 4)
{
     jQuery("#randarticle_"+id+"").ready(function()
	{
		
 jQuery("#randarticle_"+id).animate({
    width: "0.0%"
    
  }, 1000);
});		


}
document.getElementById("randarticle_"+id).style.overflow="hidden";	
setTimeout("ajax_for_post("+id+","+time+","+category+","+limit+","+style+")",2000);
}

function ajax_for_post(id,time,category,limit,style){
	jQuery.ajax({
 		 url: "<?php echo plugins_url("spider_rand_front_end.php",__FILE__)."?categori_id="; ?>"+category+"&count_pages="+limit+"&randd="+Math.floor(Math.random()*100000000000000)
		}).done(function(responseText) { 
 	 autoUpdate(id,time,category,limit,style,responseText);
});
}

function Update(id,time,category,limit,style)
{

	document.getElementById('randarticle_'+id).style.display='none';
	jQuery.fx.interval = 1;

jQuery("#randarticle_"+id+"").ready(function(){
	
  jQuery("#randarticle_"+id+"").fadeIn( 1000 );
});	
	
var xmlHttp;
	try{	
		xmlHttp=new XMLHttpRequest();// Firefox, Opera 8.0+, Safari
	}
	catch (e){
		try{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP"); // Internet Explorer
		}
		catch (e){
		    try{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
			catch (e){
				alert("No AJAX!?");
				return false;
			}
		}
	}

xmlHttp.onreadystatechange=function(){
	if(xmlHttp.readyState==4){
		document.getElementById('randarticle_'+id).innerHTML=xmlHttp.responseText;
		<?php
if ($instance['AutoUpdate']	==1 )
echo "autoUpdate(id,time,category,limit,style,xmlHttp.responseText);";
?>
	}
}

xmlHttp.open("GET","<?php echo plugins_url("spider_rand_front_end.php",__FILE__)."?categori_id="; ?>"+category+"&count_pages="+limit+"&randd="+Math.floor(Math.random()*100000000000000),true);
xmlHttp.send(null);

}






</script>
<?php 


 }// enf if 
 
 

  ?>
<div  id="randarticle_<?php echo $spider_random_article_id ?>" >
<?php
echo "<script type='text/javascript'> Update(".$spider_random_article_id.",".$instance['Updating_Time'].",0,".$instance['quantity_of_posts'].",".$instance['Style_sra'].");  </script>";
global $spider_random_article_id;
$spider_random_article_id++;?>

</div>
<?php
	// After widget //

			echo $after_widget;
		}






	// Update Settings //
	
	
	
	
	

		function update($new_instance, $old_instance) {
			$instance['title']					 = strip_tags($new_instance['title']);   // title
			$instance['Category']				 = $new_instance['Category']; /// Post quantity
			$instance['quantity_of_posts']		 = $new_instance['quantity_of_posts']; /// Post quantity
			$instance['AutoUpdate']				 = $new_instance['AutoUpdate']; /// apdeate automatic or no
			$instance['Style_sra']	   				 = $new_instance['Style_sra'];  // custom style
			$instance['Updating_Time']	 	  	 = $new_instance['Updating_Time']; /// time for updating posts or post

			return $instance;  /// return new value of parametrs
		}
		
		
		
		

	// Widget Control Panel //

		function form($instance) {
		$url = plugins_url(); //url plugin

		$defaults = array( 'title' => '', 'Category' => '1', 'quantity_of_posts' => '1', 'AutoUpdate' => '1', 'Style_sra' =>'1',  'Updating_Time' => '10');
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>
<table><tr><td style="font-size:16px;">
		<a href="http://web-dorado.com/products/spider-random-post.html" target="_blank" style="color:red; text-decoration:none;">
		<img src="<?php echo plugins_url('header.png',__FILE__) ?>" border="0" alt="www.web-dorado.com" width="215"><br>
		"Select Category" option is disabled. If you wand to show posts from a chosen category,  <br />
Get the full version&nbsp;&nbsp;&nbsp;&nbsp;
		</a>
	</td></tr></table><br /><br />
		<p>
			<label for="<?php echo $this->get_field_id('title'); ?>">Title:</label>
			<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>'" type="text" value="<?php echo $instance['title']; ?>" />
		</p>
       <table width="100%" class="paramlist admintable" cellspacing="1">
<tbody>

<tr>
<td style="width:120px" class="paramlist_key"><span class="editlinktip"><label style="font-size:10px" id="paramsstandcatid-lbl" for="Category" class="hasTip">Select Category</label></span></td>
<td class="paramlist_value">
<select  name="<?php echo $this->get_field_name('Category'); ?>" id="<?php echo $this->get_field_id('category') ?>" disabled="disabled" style="font-size:10px" class="inputbox">
<option value="0">Select Category</option>

<?php 
$categories=get_categories();
$category_count=count($categories);
//for($i=0;$i<$category_count;$i++)
foreach($categories as $categori)
{
?>


<option value="<?php echo $categori->term_id?>" <?php if($instance['Category']==$categori->term_id) echo  'selected="selected"'; ?>><?php echo $categori->name ?></option>

<?php
}
 ?>
</select>

</td>
</tr>
<tr>
<tr><td><br /></td></tr>
<td style="width:120px" class="paramlist_key"><span class="editlinktip"><label style="font-size:10px" id="paramsrand_show-lbl" for="quantity_of_posts">Quantity of Posts:</label></span></td>
<td class="paramlist_value"><input type="text" name="<?php echo $this->get_field_name('quantity_of_posts'); ?>" id="<?php echo $this->get_field_id('quantity_of_posts') ?>" value="<?php echo $instance['quantity_of_posts']; ?>" class="text_area" size="3"></td>
</tr>
<tr><td><br /></td></tr>
<tr>
<td style="width:120px" class="paramlist_key"><span class="editlinktip"><label style="font-size:10px"  for="autoupdate">Auto Update</label></span></td>
<td class="paramlist_value"><span id="cuca"></span>
<input type="radio" name="<?php echo $this->get_field_name('AutoUpdate'); ?>" value="0" <?php if($instance['AutoUpdate']==0) echo 'checked="checked"';?>  onchange="document.getElementById('<?php echo $this->get_field_id('Updating_Time') ?>time_sec').setAttribute('style','display:none')" id="showup0"> No
<input type="radio" name="<?php echo $this->get_field_name('AutoUpdate'); ?>" value="1" <?php if($instance['AutoUpdate']==1) echo 'checked="checked"';?> onchange="document.getElementById('<?php echo $this->get_field_id('Updating_Time') ?>time_sec').removeAttribute('style');" id="showup1"> Yes

        </td>
</tr>
<tr><td><br /></td></tr>
<tr>
<td width="120px" class="paramlist_key"><span class="editlinktip"><label style="font-size:10px" id="paramsstyle-lbl" for="Style_sra">Visualization</label></span></td>
<td class="paramlist_value">
<select name="<?php echo $this->get_field_name('Style_sra'); ?>" id="<?php echo $this->get_field_id('Style_sra') ?>" class="inputbox">
	<option value="1" <?php if($instance['Style_sra']==1) echo 'selected="selected"'; ?>>Style 1</option>
    <option value="2" <?php if($instance['Style_sra']==2) echo 'selected="selected"'; ?>>Style 2</option>
    <option value="3" <?php if($instance['Style_sra']==3) echo 'selected="selected"'; ?>>Style 3</option>
    <option value="4" <?php if($instance['Style_sra']==4) echo 'selected="selected"'; ?>>Style 4</option>
    <option value="5" <?php if($instance['Style_sra']==5) echo 'selected="selected"'; ?>>Random</option>
</select></td>
</tr>
<tr><td><br /></td></tr>
<tr id="<?php echo $this->get_field_id('Updating_Time') ?>time_sec"	<?php if(!$instance['AutoUpdate']==1) echo   'style="display:none"'; ?>>
<td style="width:120px" class="paramlist_key"><span class="editlinktip"><label style="font-size:10px" for="Updating_Time">Time of Update(sec)</label></span></td>
<td class="paramlist_value">
<input type="text" name="<?php echo $this->get_field_name('Updating_Time'); ?>" id="<?php echo $this->get_field_id('Updating_Time') ?>" value="<?php echo $instance['Updating_Time']; ?>" size="3">
        </td>
</tr>
</tbody></table>
         <?php }
		

}

// End class spider_random_article

add_action('widgets_init', create_function('', 'return register_widget("spider_random_article");'));
?>