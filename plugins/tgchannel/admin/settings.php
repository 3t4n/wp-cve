<?php
	if (!get_option('TG_Channel_background')){	
    update_option( 'TG_Channel_background',plugins_url('/css/geometry.png', __FILE__));
	}
	if (!get_option('TG_Channel_background_color')){	
    update_option( 'TG_Channel_background_color','#ffffff');
	}			
	if (!get_option('TG_Channel_Header_color')){	
    update_option( 'TG_Channel_Header_color','#4c70db');
	}
	if (!get_option('TG_Channel_Footer_color')){	
    update_option( 'TG_Channel_Footer_color','#ffffff');
	}	
	if (!get_option('TG_Channel_Header_Font_color')){	
    update_option( 'TG_Channel_Header_Font_color','#ffffff');
	}
	if (!get_option('TG_Channel_Footer_Font_color')){	
    update_option( 'TG_Channel_Footer_Font_color','#4c70db');
	}
	if (!get_option('TG_Channel_Header_Font_size')){	
    update_option( 'TG_Channel_Header_Font_size','20px');
	}
	if (!get_option('TG_Channel_Footer_Font_size')){	
    update_option( 'TG_Channel_Footer_Font_size','17px');
	}
	if (!get_option('TG_Channel_Header_Height')){	
    update_option( 'TG_Channel_Header_Height','50px');
	}
	if (!get_option('TG_Channel_Footer_Height')){	
    update_option( 'TG_Channel_Footer_Height','50px');
	}
	if (!get_option('TG_Channel_Body_height')){	
    update_option( 'TG_Channel_Body_height','400px');
	}
	if (!get_option('TG_Channel_Body_width')){	
    update_option( 'TG_Channel_Body_width','308px');
	}
	if (!get_option('TG_Channel_body_size')){	
    update_option( 'TG_Channel_body_size','small');
	}


?>
 <div class="wrap"> 

  <input id="tab1" type="radio" name="tabs" checked>
  <label for="tab1" style="background: #460088;"><?php echo __('Embed Code Settings', 'TGchannel'); ?></label>

  <section id="freever">

       <div class = "TTMGAset" style="padding-right: 30px;padding-left: 30px;background:white;padding-top: 20px;padding-bottom: 20px;" >
       <form action="options.php" method="post"> 
        
		<?php
        	settings_fields('TGchannel-settings');
	        do_settings_sections('TGchannel-settings');
         ?> 
        
  	<h3><?php echo __('Telegram Settings', 'TGchannel'); ?></h3>       
      <table class="form-table"> 

	   <tr>
        <th > <?php echo __('channel last post link        ', 'TGchannel'); ?> </th> 
          <td>
		 
		 <input name="TG_Channel_link" placeholder="<?php echo __('Paste the last post link in here', 'TGchannel'); ?>"  id="tglink" value="<?php echo esc_attr(get_option('TG_Channel_link')); ?>"  style="width: 384px;" type="text"> <p style="color:green;" id="tglinkres"></p>
	     <button type="button"  onclick="TGchannel_loadDoc()" style="background-image: linear-gradient(to bottom, #460088 0px, #ff9558 100%);color: white;height: 30px;border-color: #ff149300;" ><?php echo __('Validation', 'TGchannel'); ?></button>
        <input name="TG_Channel_name" id="tgname" value="<?php echo esc_attr(get_option('TG_Channel_name')); ?>"  style="width: 191px; display:none;" readonly type="text"> 	
	    <input name="TG_Channel_postid" id="tgpostid" value="<?php echo esc_attr(get_option('TG_Channel_postid')); ?>"  style="width: 191px; display:none;" type="text" readonly> 
		 <p><?php echo __('Go to the target channel in telegram , then right click on last post , and choose *Copy Post Link* . paste the coppied link here and click on Validation', 'TGchannel'); ?></p>			
		 			

         </td>
	   </tr>
	 <br />

	   </table>
		
		


 <table class="form-table">
	 <tr>
      <th > <?php echo __('Auto refresh TGchannel Widget Posts', 'TGchannel'); ?> </th> 
       <td>
	   
    <button type="button" onclick="TGchannel_Turnonup()" id="tgch_activer"><?php echo __('Active TGCH_BOT', 'TGchannel'); ?></button>
	
	<p><?php echo __('If you desire to have the newest posts from your Telegram channel on *TGchannel Widget* ,  Add "@TGCH_BOT" to your Telegram channel as admin and then click on "Active TGCH_BOT"', 'TGchannel'); ?></p>					
	   </td>	  
	</tr>
   <p id ="resultset"></p>	
 </table>

 <hr>
<br/> 
 
   	<h3><?php echo __('Style of widget', 'TGchannel'); ?></h3>   
 
 
 <table class="form-table">
 
     <tr>
      <th > <?php echo __("Widget's Body background", 'TGchannel'); ?> </th> 
       <td> 
       <input id="bglink" name="TG_Channel_background" type="text" value="<?php echo esc_attr(get_option('TG_Channel_background')); ?>" />
    <input id="TG_Channel_upload_image_button" class="button" type="button" value="Upload Image" />
	   </td>	  
	</tr>	
	
	
	 <tr>
       <th > <?php echo __("Color of Widget's Body", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_background_color" type="text" value="<?php echo esc_attr(get_option('TG_Channel_background_color')); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>
	

	
	 <tr>
       <th > <?php echo __("Widget Body Size", 'TGchannel'); ?> </th> 
       <td> 
       <?php $options = get_option( 'TG_Channel_body_size' ); ?>
<?php if ('custom' != $options){ 
$rowdisp = "none";
}	
?>		   
	   <div class="sizeofwidget">
        <input type="radio" name="TG_Channel_body_size" value="small"<?php checked( 'small' == $options ); ?> />mini
        <input type="radio" name="TG_Channel_body_size" value="medium"<?php checked( 'medium' == $options ); ?> />medium
        <input type="radio" name="TG_Channel_body_size" value="custom"<?php checked( 'custom' == $options ); ?> />custom
		</div>
	   </td>	  
	</tr>	



     <tr class="bodysize0" style="display:<?php echo $rowdisp;?>;">	 
      <th > <?php echo __("Widget's Body Width", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Body_width" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Body_width')); ?>" />
	   </td>	
	   
	</tr>		


	
	 <tr class="bodysize1">
      <th > <?php echo __("Widget's Body Height", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Body_height" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Body_height')); ?>" />
	   </td>	  
	</tr>	
 
 
	
	<tr>
       <th > <?php echo __("Color of Widget's header", 'TGchannel'); ?> </th> 
       <td> 
        <input name="TG_Channel_Header_color" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Header_color')); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>
	
	
    <tr>
      <th > <?php echo __("Widget's Header font color", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Header_Font_color" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Header_Font_color')); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>		


    <tr>
      <th > <?php echo __("Widget's Header Font size", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Header_Font_size" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Header_Font_size')); ?>" />
	   </td>	  
	</tr>	


    <tr>
      <th > <?php echo __("Widget's Header Height", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Header_Height" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Header_Height')); ?>" />
	   </td>	  
	</tr>		
	
	
    <tr>
      <th > <?php echo __("Color of Widget's Footer", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Footer_color" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Footer_color')); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>
	
	
    <tr>
      <th > <?php echo __("Widget's Footer Font color", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Footer_Font_color" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Footer_Font_color')); ?>" class="my-color-field" data-default-color="#effeff" />
	   </td>	  
	</tr>	
	
	<tr>
      <th > <?php echo __("Widget's Footer Font size", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Footer_Font_size" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Footer_Font_size')); ?>" />
	   </td>	  
	</tr>	

	<tr>
      <th > <?php echo __("Widget's Footer Height", 'TGchannel'); ?> </th> 
       <td> 
       <input name="TG_Channel_Footer_Height" type="text" value="<?php echo esc_attr(get_option('TG_Channel_Footer_Height')); ?>" />
	   </td>	  
	</tr>	
	
	
	
 </table> 


		
<?php submit_button(); ?> 
           
      </form> 







	  
</br> 
<hr>

	<p><?php echo __("Copy and paste the following shortcode directly into the page, post or widget where you'd like your channel to show up : ", 'TGchannel'); ?></p><input type="text" value="[tgchannel]" size="16" readonly="readonly" style="text-align: center;" onclick="this.focus();this.select()" title="To copy, click the field then press Ctrl + C (PC) or Cmd + C (Mac).">
 
 	<p><?php echo __("also you can use **TGchannel Widget** that already exists on  Appearance->widgets", 'TGchannel'); ?></p>

	
</br> 
</br> 
</br> 

<hr> 

<p style="color: #8500a7; text-align: center;" ><?php echo "Developed by S.J.Hosseini in TTMGA company"; ?>

</p>  
    </div> 


<script>

function TGchannel_loadDoc() { 
  
var res = document.getElementById("tglink").value ; 
var ex = res.indexOf(".me/");
	if (ex > 0){
var nameandid = res.split(".me/");
var exnm = nameandid["1"].indexOf("/");	
if (exnm > 0){		
var name = nameandid["1"].split("/")
if (name["1"] != ''){
 document.getElementById("tgname").value = name["0"] ; 
 document.getElementById("tgpostid").value = name["1"] ;
 document.getElementById("tglinkres").innerHTML = "<?php echo __("This link is Valid , click on 'Save Changes' button", 'TGchannel'); ?>" ; 
 
}else{
	alert("invalid post link");
	}
	
}else{
	alert("invalid post link");
	}
		
	}else{
	alert("invalid post link");	
	}
}


function TGchannel_Turnonup() { 
   
var res =  document.getElementById("tgname").value;
var addse2 = "https://www.ttmgabot.com/TGHook/rec.php?" + res + "&" + "<?php echo get_option('siteurl'); ?>" + "&&" + "<?php echo get_bloginfo("language"); ?>" + "&&" + "Active"; 
 
    var xxhttp = new XMLHttpRequest(); 
  xxhttp.onreadystatechange = function() { 
    if (this.readyState == 4 && this.status == 200) { 
var cou1 = this.responseText; 
var tik1 = " 📈 " + "<?php echo __('Status : ', 'TGchannel'); ?> " + cou1+ "";  
 document.getElementById("resultset").innerHTML = tik1 ; 
    } 
  }; 
   
   xxhttp.open("GET", addse2, true); 
  xxhttp.send(); 
  
  
}  

</script>	
  
  
  
  </section>