<?php
    /**
     * needs:
     * $item from db
     * $etsy_link
     */

    $etsy_logo_url = PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/etsy_logo.png';
    $html = "<img src=$etsy_logo_url width='25' height='25'/>"; 
    $status = $etsy_item['status'];
    if(empty($status)){
        $error = $etsy_item['error'];
        $etsy_logo_url = PLATY_SYNCER_ETSY_DIR_URL . 'assets/images/etsy_error.png';
        $html = "<img src=$etsy_logo_url width='25' height='25'/><span style='vertical-align: top'>" .wc_help_tip($error) . "</span>"; 
    }
    if(!empty($etsy_item['etsy_id'])){
        $html  = "<a href='$etsy_link' target='_blank'>$html</a>";
    }
    echo $html;
?>