<?php
    /**
     * needs:
     * $item from db
     * $etsy_link
     */

    $woo_logo_url = PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/woo_logo.png';
    $html = "<img src=$woo_logo_url width='30' height='25'/>"; 

    if($woo_link){
        $html  = "<a href='$woo_link' target='_blank' style='margin-left: 10px'>$html</a>";
        echo $html;
    }
    
?>