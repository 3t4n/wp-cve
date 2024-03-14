<?php

/*
 * Created on Feb 10, 2013
 * Author: Mohsin Rasool
 * 
 * Copyright 2013 NeuMarkets. All rights reserved.
 * COMPANY PROPRIETARY/CONFIDENTIAL. Use is subject to license terms.
 */

add_filter('wp_head', 'fsi_styles');
function fsi_styles(){
    if(!wpdev_fsi_should_display())
        return false;

    $opacity = get_option('fsi_opacity');
    if(empty($opacity) || !is_numeric($opacity) || $opacity>100)
        $opacity =100;
    
    $fsi_animation = get_option('fsi_animation');
?>
<style type="text/css">
html, body { 
    height: 100%;
}
#fsi-full-bg {
    position: fixed; top: 0; left: 0;width:100%; height:100%;
    z-index:-10;
    
}

#fsi-full-bg img { position: fixed; top: 0; left: 0; z-index:-9; opacity:0}
#fsi-full-bg img:first-child{opacity:1;}
.fsi-full-bg-full-width { width: 100%; }
.fsi-full-bg-full-height { height: 100%; }
#fsi-full-bg-overlay { 
    position:fixed; 
    top:0; 
    left:0;
    height:100%; 
    width:100%; 
    z-index:-9;
    background:url(<?php echo FSI_PLUGIN_URL?>/images/dot_overlay.png) repeat;
}
<?php
    /*if(!$fsi_animation)
        echo '#fsi-full-bg img:first-child{opacity:1}';*/
?>
</style>
<?php

}

?>