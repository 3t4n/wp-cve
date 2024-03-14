
<div id="scr_wrap">
<div class="formLayout">
<div id="ws_nav">
  <a href="admin.php?page=wstt_recommendations"><li>Recommended Plugins</li></a>
  <a href="http://web-settler.com/free-support/"><li>Support</li></a>
  <a href="http://web-settler.com/contact/"><li>Hire Me!</li></a>
  <a href="https://wordpress.org/support/view/plugin-reviews/wp-scroll-to-top?rate=5#postform"><li>Write a review</li></a>
</div>
  <h1>Scroll To Top</h1>
  <form method="post" action="options.php">
    
<?php settings_fields( 'scr-setting-group' );?>
<?php do_settings_sections( 'scr-setting-group' );?>
<br>
<br>


    <div id="pre_scr_icons">
<label for="activate_pre_icon">Enable Pre Designed Icon</label>
<select name="activate_pre_icon">
<option value="visible"

<?php selected( 'visible', get_option('activate_pre_icon')); ?>

>Enable</option>
<option value="none"

<?php selected( 'none', get_option('activate_pre_icon')); ?>

>Disable</option>
</select>
<br>
<br>
<label for="scr_pre_icons"><b>Select an Icon</b></label>
 <select name="scr_pre_icons">
<option value="<?php echo WSTT_PLUGIN_URL."/scr_icons/icon-1.png";?>"
<?php selected( WSTT_PLUGIN_URL."/scr_icons/icon-1.png" , get_option('scr_pre_icons')); ?>
        >Icon-1</option>

      <option value="<?php echo WSTT_PLUGIN_URL."/scr_icons/icon-2.png";?>"
<?php selected( WSTT_PLUGIN_URL."/scr_icons/icon-2.png" , get_option('scr_pre_icons')); ?>
        >Icon-2</option>

      <option value="<?php echo WSTT_PLUGIN_URL."/scr_icons/icon-3.png";?>"

<?php selected(WSTT_PLUGIN_URL."/scr_icons/icon-3.png", get_option('scr_pre_icons')); ?>

        >Icon-3</option>
      <option value="<?php echo WSTT_PLUGIN_URL."/scr_icons/icon-4.png";?>"
<?php selected(WSTT_PLUGIN_URL."/scr_icons/icon-4.png", get_option('scr_pre_icons')); ?>


        >Icon-4</option>
      <option value="<?php echo WSTT_PLUGIN_URL."/scr_icons/icon-5.png";?>"
<?php selected(WSTT_PLUGIN_URL."/scr_icons/icon-5.png", get_option('scr_pre_icons')); ?>


        >Icon-5</option>
    </select>
 <br>
 <br>
 <hr>
     </div>
    <br>
<h3>Set Width & Height</h3>
    <label for="scr_width"><b>Icon Width : </b></label>
    <input type="number" required="required" name="scr_width" value="<?php echo get_option('scr_width');?>"/> (px)
   <br>
   <br>
   <label for="scr_height"><b>Icon Height : </b></label>
     <input type="number" required="required" name="scr_height" value="<?php echo get_option('scr_height');?>"/> (px)
     <br>
     <br>
     <hr>
     
   
   <div id="scr_input_wrap">
    <h3>Design your Own Button</h3>
    <label for="activate_text_icon"><b>Enable/Disable Custom Button</b></label>
   <select name="activate_text_icon">
<option value="visible"

<?php selected( 'visible', get_option('activate_text_icon')); ?>

>Enable</option>
<option value="none" 

<?php selected( 'none', get_option('activate_text_icon')); ?>

>Disable</option>
   </select>
   <br>
   <br>

<label for="scr_text">Text :</label>
   <input type="text" name="scr_text" value="<?php echo get_option('scr_text');?>">
   <br>
   <br>
<label for="scr_font_size">Font-Size: </label>
     <input type="text" name="scr_font_size" value="<?php echo get_option('scr_font_size');?>"/>
   <br>
   <br>
   <label for="scr_color"><b>Text-Color :<b/></label>
  <input type="text"  class='wstt_color_picker' name="scr_color" size="7" value="<?php echo get_option('scr_color');?>"/> 
   <br>
   <br>
   <label for="scr_background_color"><b>Background-Color :<b/></label>
   <input type="text"  class='wstt_color_picker' name="scr_background_color" size="7" value="<?php echo get_option('scr_background_color');?>"/>
   <br>
   <br>
   <label for="scr_border_radius"><b>Border Radius :</b></label>
     <input type="text" name="scr_border_radius"  value="<?php echo get_option('scr_border_radius');?>"/>
   <br>
   <br>
   <hr>

   <h3>Positioning</h3>
   <br>
   <label for="scr_position"><b>Left :</b></label>
         <input type="radio" name="scr_position" value="0%" style="width:15px;"
<?php checked( '0%', get_option('scr_position')); ?>

         />
   <br>
   <label for="scr_position"><b>Center :</b></label>
      <input type="radio" name="scr_position" value="45%" style="width:15px;"
      <?php checked( '45%', get_option('scr_position')); ?>

      /> 
   <br>
<label for="scr_position"><b>Right :</b></label>
     <input type="radio" name="scr_position" value="94%" style="width:15px;" 
<?php checked( '94%', get_option('scr_position')); ?>

     />

  </div>
<br>
<br>
<?php submit_button();?>

</form>
</div>

</div>

<style>
#ws_nav{
  margin-right: -50px;
  margin-top: -50px;
  float: right;
  padding: 15px;
  background-color: #efefef;
  border-radius: 5px;
  display: inline-block;
  width:40%;
  text-align: center;
}
#ws_nav a{
  font-family: arial,oswald;
}
#ws_nav  li{
  font-size: 18px;
  color: #333333;
  border-right: 2px solid #949494;
}
#ws_nav  li:hover{
  background: #949494;
  color: #fff;
  cursor: pointer;
  border-radius: 3px;
}
#ws_nav  li{
  margin: 2px;
  padding:10px 10px 15px 10px;
  height: 2%;
  float: left;
  list-style: none;
}
.formLayout
    {
        
        padding: 5%;
        width: 80%;
        margin: 5%;
        background: #fff;
        border:2px solid #e3e3e3;
        border-radius: 10px;
    }
    
    .formLayout label 
    {
        display: block;
        width: 195px;
        text-align: right;
        margin-bottom: 10px;
        margin-left: 20px;
    }
    .formLayout input{
          display: block;
        width: 200px;
        float: left;
        margin-bottom: 10px;

    }
 
    .formLayout label
    {
        text-align: right;
        padding-right: 20px;
        font-size: 16px;
        font-weight: bold;
    }
 
    br
    {
        clear: left;
    }
</style>