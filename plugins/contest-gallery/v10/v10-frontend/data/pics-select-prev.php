<?php

if($AllowGalleryScript==1){
    if($_GET['3'] or $_GET['3']==0){

        $stepOld = $step;
        $startOld = $start;

        if($start==0){
            $step = $step+1;
        }
        else{
            $step = $step+2;
            $start = $start-1;
        }

    }

}

?>