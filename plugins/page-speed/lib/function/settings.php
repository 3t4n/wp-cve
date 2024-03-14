<?php
add_action( 'admin_init', 'ps_pagespeed_register_settings' );
function ps_pagespeed_register_settings() {
	
	register_setting( 'ps_pagespeedpro_settings_group', 'ps_pagespeed' );
	
	
}
function ps_pagespeed_general_settings() {
?>	

<style>


.free-version-wrap .free-version{
    display: none !important;
}

.pro-version-wrap .pro-version {
    display: none !important;
} 


    .box-container {
    display: flex;
    flex-wrap: wrap;
}
.box.box-left {
    flex-basis: 70%;
    background: #fff;
    padding: 20px;
    margin: 20px 0px;
        box-sizing: border-box;
}
.box.box-right {
    flex-basis: 30%;
    box-sizing: border-box;
    margin: 10px auto;
    padding: 20px;
}
.pagespeed-accordion > h3 {
    border-top: 1px solid #ddd;
    padding: 10px;
    margin: 0 auto;
        outline: none;
            cursor: pointer;
}
.pagespeed-accordion > h3:hover {
    background: #f1f1f1;
}
.pagespeed-accordion .ui-accordion-content {
    padding: 0px 10px 20px;
}
.pagespeed-accordion h3 .dashicons, .pagespeed-accordion h3 .dashicons-before:before {
    float: right;
}
.red {
    color:red;
}
.green {
    color: green;
}
.pagespeed-footer {
    display: flex;
    flex-wrap: wrap;
}
.pagespeed-button {
    margin-right: 20px;
}
.button.button-pro {
    background: green;
    color: #fff;
}
textarea.inline-css {
    width: 100%;
    height: 200px;
}
input[type="text"] {
    width: 100%;
}
td.td-handel {
    width: 20%;
}
td.td-url {
    width: 80%;
}
</style>

<div class="box-wrap pro-version-wrap">
    <div class="box-container">
        
        
        <div class="box box-left">
            
             <form method="post" action="options.php">

    <?php settings_fields( 'ps_pagespeedpro_settings_group' ); ?>
    <?php do_settings_sections( 'ps_pagespeedpro_settings_group' ); ?>

<h1> Page Speed <span class="green pro-version"> PRO </span></h1> 	

<hr>


<p> <b> Opportunities </b> - These suggestions can help your page load faster. They don't directly affect the Performance score. </p>


<?php $ps_pagespeed = get_option("ps_pagespeed"); ?>



<div class="pagespeed-accordion">
    
        <h3> Enable text compression </h3>
  <div>
    <p> Text-based resources should be served with compression (gzip, deflate or brotli) to minimize total network bytes. <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a> </p>
	
	<p> 
	    <input class="free-version-field" type="checkbox" name="ps_pagespeed[enable_text_compression]" value="on" <?php echo $ps_pagespeed['enable_text_compression']=='on' ? 'checked="checked"' : '' ?> />
	    <b> Enable text compression in your web server configuration. </b> 
	</p>
	
	<p> <b class="red"> NOTE: </b> This option required to resave your permalinks. Go to WP Admin > Settings > Permalinks > and click save.  See <a href="https://wordpress.org/plugins/page-speed/faq/" target="_blank"> FAQ </a> for more details. </p>
	
	
  </div>
  
   <h3> Serve static assets with an efficient cache policy  </h3>
  <div>
    <p> A long cache lifetime can speed up repeat visits to your page.  <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a> </p>
	
	<p> <input class="free-version-field" type="checkbox" name="ps_pagespeed[cache_policy]" value="on" <?php echo $ps_pagespeed['cache_policy']=='on' ? 'checked="checked"' : '' ?> />
	
	<b> Set Expiry date or a maximum age in the HTTP headers. </b> </p> 
	
	<p> <b class="red"> NOTE: </b>  This option required to resave your permalinks. Go to WP Admin > Settings > Permalinks > and click save.  See <a href="https://wordpress.org/plugins/page-speed/faq/" target="_blank"> FAQ </a> for more details. </p>
	
	
  </div>
  
    
    <h3> Eliminate render-blocking resources </h3>
    
    <div>
        
         <p> Resources are blocking the first paint of your page. Consider delivering critical JS/CSS inline and deferring all non-critical JS/styles.  <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a>   </p>
         
         
         
          <h4> Render Blocking Javascript </h4>
          
         
         <p> <input class="free-version-field" type="checkbox" name="ps_pagespeed[render_blocking_resources][javascript]" value="defer"  <?php if ( 'defer' == $ps_pagespeed['render_blocking_resources']['javascript'] ) echo 'checked="checked"'; ?>  />  Defered all scripts from the theme and plugins </p>
          
          
        
        <h4> Render Blocking Stylesheet </h4>
        
        <p> <input class="free-version-field" type="checkbox" name="ps_pagespeed[render_blocking_resources][stylesheet]" value="defer"  <?php if ( 'defer' == $ps_pagespeed['render_blocking_resources']['stylesheet'] ) echo 'checked="checked"'; ?>  />  Preload all stylesheets from the theme and plugins  </p>
        
   <h4> Inline Critical CSS </h4>
	<p> <b> Optional:</b> <a href="https://pegasaas.com/critical-path-css-generator/" target="_blank">  Generate Critical Assets  </a>  Copy and Paste Code Here  </p>
	<p> <textarea name="ps_pagespeed[render_blocking_resources][critical_css]" class="inline-css"> <?php echo $ps_pagespeed['render_blocking_resources']['critical_css']; ?>   </textarea> </p>
    

	
    </div>
    
    <h3> Remove unused JavaScript   </h3>
    
     <div>
         
         <h4> Remove unused JavaScript to reduce bytes consumed by network activity. Learn more. </h4>
         
          <p>  <input class="free-version-field" type="checkbox" name="ps_pagespeed[removed_unused_javascript][emoji]" value="on" <?php echo $ps_pagespeed['removed_unused_javascript']['emoji'] =='on' ? 'checked="checked"' : '' ?> />  Remove  Emoji Script     </p>
    
         
     </div>
    
    <h3> Remove unused CSS </h3>
    
     <div>
         
         <h4> Remove dead rules from stylesheets and defer the loading of CSS not used for above-the-fold content to reduce unnecessary bytes consumed by network activity. Learn more. </h4>
         
          <p>  <input class="free-version-field" type="checkbox" name="ps_pagespeed[removed_unused_css][gutenburg]" value="on" <?php echo $ps_pagespeed['removed_unused_css']['gutenburg'] =='on' ? 'checked="checked"' : '' ?> />  Remove the Gutenberg Block CSS Library  from WordPress   </p>
    
         
     </div>
     
     
    
    
     <h3>  Serve images in next-gen formats <span class="green free-version"> - Pro version </span>  </h3>
  <div>
    <h4>  Image formats like JPEG 2000, JPEG XR, and WebP often provide better compression than PNG or JPEG, which means faster downloads and less data consumption.    <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a>  </h4>
    
    <p>  <input disabled class="pro-version-field" type="checkbox" name="ps_pagespeed[serve_images_in_nextgen_formats][upload_webp]" value="on" <?php echo $ps_pagespeed['serve_images_in_nextgen_formats']['upload_webp'] =='on' ? 'checked="checked"' : '' ?> />   Allow wordpress to upload webp file   </p>
    
    <!---
    <p>  <input type="checkbox" name="ps_pagespeed[serve_images_in_nextgen_formats][browser_support]" value="on" <?php echo $ps_pagespeed['serve_images_in_nextgen_formats']['browser_support'] =='on' ? 'checked="checked"' : '' ?> />  Serves a .webp image instead of jpg/png if a .webp file is available at the same location as the supplied jpg/png.   </p>
   <p> <b class="red"> NOTE: </b> This option required to resave your permalinks. Go to WP Admin > Settings > Permalinks > and click save. See <a href="https://wordpress.org/plugins/page-speed/faq/" target="_blank"> FAQ </a> for more details. </p>
<p> <b class="red"> NOTE: </b> Pls make sure that your fallback image / WEBP image is in the same folder of your  jpg/png. See <a href="https://wordpress.org/plugins/page-speed/faq/" target="_blank"> FAQ </a> for more details. </p>
    ---->
    
 

    
  </div>
    
    
   <h3>  Properly Size Images  <span class="green free-version"> - Pro version </span> </h3>
  <div>
      
     <?php $optimize_images = get_option("optimize_images"); ?>
      
    <p>  Serve images that are appropriately-sized to save cellular data and improve load time.   <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a>   </p>
    
    <p>  <input disabled class="pro-version-field" type="checkbox" name="ps_pagespeed[properly_size_images][srcset_img]" value="on" <?php echo $ps_pagespeed['properly_size_images']['srcset_img'] =='on' ? 'checked="checked"' : '' ?> />   Properly resize images using size attribute.  </p>
    <!-----
    <p>  <input type="checkbox" name="ps_pagespeed[properly_size_images][srcset_bg]" value="on" <?php echo $ps_pagespeed['properly_size_images']['srcset_bg'] =='on' ? 'checked="checked"' : '' ?> />   Properly resize background images using srcset attribute.  </p>
    <p>  <input type="checkbox" name="ps_pagespeed[properly_size_images][responsive_images]" value="on" <?php echo $ps_pagespeed['properly_size_images']['responsive_images'] =='on' ? 'checked="checked"' : '' ?> />   Add addiotnal responsive images  </p>
    --->
    
  </div>
  
  <h3>Defer Offscreen Images  <span class="green free-version"> - Pro version </span> </h3>
  <div>
    <h4>   Consider lazy-loading offscreen and hidden images after all critical resources have finished loading to lower time to interactive.   <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a>  </h4>
    
    <p>  <input disabled class="pro-version-field" type="checkbox" name="ps_pagespeed[defer_offscreen_images][lazyload_img]" value="on" <?php echo $ps_pagespeed['defer_offscreen_images']['lazyload_img'] =='on' ? 'checked="checked"' : '' ?> />   Lazy Load Images  </p>
    <p>  <input disabled class="pro-version-field" type="checkbox" name="ps_pagespeed[defer_offscreen_images][lazyload_bg]" value="on" <?php echo $ps_pagespeed['defer_offscreen_images']['lazyload_bg'] =='on' ? 'checked="checked"' : '' ?> />   Lazy Background Images  </p>
  
    
    
  </div>
  
  
  <h3>  Efficiently encode images  <span class="green free-version"> -  Pro version </span> </h3>
   <div>
     <h4> Optimized images load faster and consume less cellular data.  <a href="https://wordpress.org/plugins/page-speed/" target="_blank"> Learn More </a>  </h4>
     
      <p>  <input disabled class="pro-version-field" type="checkbox" name="ps_pagespeed[efficiently_encode_images][jpg_quality]" value="on" <?php echo $ps_pagespeed['efficiently_encode_images']['jpg_quality'] =='on' ? 'checked="checked"' : '' ?> />   Compress JPG images by 80%. </p>
     
     
   </div>
    
    


</div>


<hr>

<p>  <b> Diagnostics </b> - More information about the performance of your application. These numbers don't directly affect the Performance score.   </p> 


<div class="pagespeed-accordion">
    
    <h3>  Reduce the impact of third-party code <span class="green free-version"> -  Pro version </span>  </h3>
    <div>
        <p> Third-party code can significantly impact load performance. Limit the number of redundant third-party providers and try to load third-party code after your page has primarily finished loading. Learn more. </p>
        <p> 
	        <input disabled class="pro-version-field"  type="checkbox" name="ps_pagespeed[reduce_the_impact_of_third_part_code][iframe]" value="on" <?php echo $ps_pagespeed['reduce_the_impact_of_third_part_code']['iframe'] =='on' ? 'checked="checked"' : '' ?> />
	        <b> Lazy Load Iframe in the above the fold content.   </b> 
	    </p>
	
    </div>
    
</div>
    
    

<div class="pagespeed-footer">
    <div class="pagespeed-button">  <?php submit_button(); ?> </div>
    <div class="pagespeed-button">  <p class="submit"> <a href="https://gutenframestudio.com/product/page-speed/" target="_blank" class="button button-pro free-version"> Lets Go PRO </a>  </p></div> 
</div>




</form>
            
        </div>
        
        <div class="box box-right">
            
            <center> 
            <h3>  Help us to improve this plugin. </h3>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="K88RFNMVRRNCA" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_PH/i/scr/pixel.gif" width="1" height="1" />
</form>
</center>


        </div>
        
       


    </div>
</div>



<script>
    jQuery( document ).ready(function($) {
        
        
    
    
	  $( ".pagespeed-accordion" ).accordion({
	       icons: { "header": "dashicons dashicons-arrow-down", "activeHeader": "dashicons dashicons-arrow-up" },
      heightStyle: "content",
	    active: 100
    });
    
   
    
});

</script>




<?php
}
?>