<?php // let's check if directory for fonts has already been created
 if (!is_dir(WP_PLUGIN_DIR . "/cufon-fonts"))
 { ?>
		<div id="message" class="updated fade" style="background-color: #FF6633"><p>The first you have to create directory <strong>cufon-fonts</strong> in wp-content/plugins/<br /><br />
		It should look like:  wp-content/plugins/cufon-fonts/ </p></div>

<?php }
 if ($_POST['wpcuf_hidden'] == 'Y')
 { //we have succesfully updated
     //Form data sent
     update_option("wpcuf_code", $_POST['wpcuf_code']);
     $count=0;
   foreach (glob(WP_PLUGIN_DIR . "/cufon-fonts/*") as $path_to_files)
     { 
        $count++;
     update_option("enable_font-$count", $_POST['enable_font-'.$count]); 
     }
?>		
		<div class="updated"><p><strong><?php _e('Options saved.'); ?></strong></p></div>
		<?php } ?>

 
<!-- HERE STARTS THE FORM -->
 
<div class="wrap">

<?php echo "<h2>" . __('All-In-One Cuf&oacute;n plugin', 'wpcuf_trdom') . "</h2>"; ?>

<div style="float: left; width: 30%;">
<h2>Support</h2>
<table class="widefat">
<thead>
 <tr>
    <th>If you like this plugin, maybe you'll be interested in my other plugins.</th>     
 </tr>
</thead>
<tbody>
 <tr>
    <th  style="text-align: center;"><a href="http://lizatom.com"><img src="http://lizatom.com/temp/likethisplugin-aiocufon.png" /></a></th>         
 </tr>
</tbody>
<tfoot>
 <tr>
    <th><a href="http://lizatom.com">Check out my other plugins and themes.</a></th>     
 </tr>
</tfoot>
</table>
</div>

<div style="float: right; width: 68%;">
<h2>Code tips</h2>
<table class="widefat">
<thead>
 <tr>
    <th style="width: 20%;">Action</th>
    <th style="width: 35%;">Code</th>
    <th style="width: 45%;">Preview</th>    
 </tr>
</thead>
<tbody>
<tr>
    <th style="width: 20%;">Set fontFamily</th>
    <th style="width: 35%;"><code>Cufon('h2.codeTips', { fontFamily: '300 Trojans' });</code></th>
    <th style="width: 45%;"><h2 class="codeTips">Lorem Ipsum Dolor Sit</h2></th>    
 </tr>
 <tr>
    <th style="width: 20%;">Set gradient</th>
    <th style="width: 35%;"><code>Cufon.replace('h2.codeTips#tip2', {
	color: '-linear-gradient(white, black)'
});</code></th>
    <th style="width: 45%;"><h2 class="codeTips" id="tip2">Lorem Ipsum Dolor Sit</h2></th>    
 </tr>
 <tr>
    <th style="width: 20%;">Enable hover</th>
    <th style="width: 35%;"><code>Cufon.replace('h2.codeTips#tip3', {	hover: true });</code></th>
    <th style="width: 45%;"><h2 class="codeTips" id="tip3"><a href="#">Lorem Ipsum Dolor Sit</a></h2></th>    
 </tr>
 <tr>
    <th style="width: 20%;">Set Shadow</th>
    <th style="width: 35%;"><code>Cufon.replace('h2.codeTips#tip4', {textShadow: 2px 2px red });</code></th>
    <th style="width: 45%;"><h2 class="codeTips" id="tip4">Lorem Ipsum Dolor Sit</h2></th>    
 </tr>
 <tr>
 <th style="background: #FFFF99;" colspan="3">For more code tips go to <a href="http://lizatom.com/wordpress-plugin/all-in-one-cufon/">the plugin page</a> or official <a href="http://wiki.github.com/sorccu/cufon/styling">Cufon's site</a></th>  
 </tr>
</tbody>
<tfoot>
<tr>
    <th style="width: 20%;">Action</th>
    <th style="width: 35%;">Code</th>
    <th style="width: 45%;">Preview</th>    
 </tr>
 
</tfoot>
</table>
</div>
<div style="clear: both;"></div>
<!-- FORM -->

<form name="wpcuf_form" method="post" action="<?php echo str_replace('%7E', '~', $_SERVER['REQUEST_URI']); ?>">
<!-- list of loaded fonts -->

<h2>The following fonts were detected</h2>

<!-- table starts here -->

<table class="widefat">
<thead>
 <tr>
    <th style="width: 5%;">Enable</th>
    <th style="width: 15%;">fontFamily</th>
    <th style="width: 15%;">File</th>
    <th style="width: 65%;">Preview</th>
 </tr>
</thead>
<?php $cufon_font_location = WP_PLUGIN_URL . '/cufon-fonts';
 $count = 0; ?>
 
 <!-- body of the table starts here -->
 
<tbody>
<?php  foreach (glob(WP_PLUGIN_DIR . "/cufon-fonts/*") as $path_to_files) { ?>
<tr>

<?php
     // let's get some info within the loop
     $count++;
     $file_name = basename($path_to_files);
     $file_content = file_get_contents($path_to_files); //open file and read
     $delimeterLeft = 'font-family":"';
     $delimeterRight = '"';
     $font_name = font_name($file_content, $delimeterLeft, $delimeterRight, $debug = false);
?>
     
     <!-- enable --> 
     <th style='width: 5%;'>
     <input name="enable_font-<?php echo $count ?>" type="checkbox" value="1"
     <?php if (get_option ("enable_font-$count") == "1") { echo "checked"; } ?>  />
     </th>    

     <!-- font name -->
     <th style='width: 15%;'><?php echo $font_name ?></th>
     
     <!-- fiel name -->
     <th style='width: 15%;'><?php echo $file_name . ' (' . filesize($path_to_files)/1000 . ' KB)';?></th>
     
	 <!-- preview -->
     <th style="width: 70%;"><span style="display: block; font-size: 30px;" id="font-<?php echo
     $count; ?>">This is a preview of the <span style="color:  #379BFF;"><?php echo
$font_name; ?></span> font. Some numbers: 0123456789 &amp; so on..</th>
     
</tr> 
<?php } ?>
</tbody>

<!-- foot of the table starts here -->

<tfoot>
 <tr>
    <th style="width: 5%;">Enable</th> 
    <th style="width: 15%;">fontFamily</th>
    <th style="width: 15%;">File</th>
    <th style="width: 65%;">Preview</th>
 </tr>
</tfoot>
</table>  
<?php  // no files found
 if (!isset($path_to_files))
 { ?>
	<div id="message" class="updated fade" style="background-color: #FF6633"><p><strong>You have to upload some fonts to /wp-content/plugins/cufon-fonts/</strong></p></div>
<?php } ?>


    <!-- did we update : hidden -->
    
	<input type="hidden" name="wpcuf_hidden" value="Y">
	
	<!-- cufon code : textarea -->
    
	<?php echo "<h2>" . __('Cuf&oacute;n code', 'wpcuf_trdom') . "</h2>"; ?>
	<p><textarea name="wpcuf_code" rows="20" cols="100" style="width: 100%;"><?php echo
 stripslashes(get_option("wpcuf_code")); ?></textarea>
	</p>	
	
	<!-- submit : button -->
    
		<p class="submit">
	<input type="submit" name="Submit" value="<?php _e('Update Options', 'wpcuf_trdom') ?>" />
	</p>
</form>
</div>