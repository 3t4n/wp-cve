<?php
/*
Plugin Name: Website Copy Protection
Description: Website Copy Protection prevents will protect the content of you website. On activating this plugin, copying the text, image, source code, taking printout, saving the page etc won't be available.  The activation is a one click process and no other settings are required! Deactivation is equally easy. 
Version: 1
Author: Anupam Maity
Author URI: http://www.tcatechnology.com
*/
add_action('wp_head', 'websiteCopyProtection');
function websiteCopyProtection(){
?>
<style>
body{
  -webkit-user-select: none; /*(Chrome/Safari/Opera)*/
  -moz-user-select: none;/*(Firefox)*/
  -ms-user-select: none;/*(IE/Edge)*/
  -khtml-user-select: none;
  -o-user-select: none;
  user-select: none;
}
</style>
<script type="text/javascript">
document.ondragstart=function(){return false}; //for image 
document.oncontextmenu=function(e){return false}; //for right click disable
document.onkeydown = function(e) {
        if (e.ctrlKey && 
		 	(e.keyCode === 65 ||
             e.keyCode === 67 || 
			 e.keyCode === 73 ||
			 e.keyCode === 74 ||
			 e.keyCode === 80 || 
			 e.keyCode === 83 || 
			 e.keyCode === 85 || 
             e.keyCode === 86 || 
             e.keyCode === 117
			 )) {
            return false;
        } 
		if(e.keyCode==18||e.keyCode==123){return false}
};
</script>
<?php } ?>