<?php

if($AllowGalleryScript==1){
    if($_GET['3'] or $_GET['3']==0){

        $step=$stepOld;
        $start=$startOld;
        $stepPlusOne=$step+1;

        if(count($picsSQL)>=$stepOld){
            $lastObjectPicsSQL = array_pop($picsSQL);
        }
        if($start!=0){
            $firstObjectPicsSQL = array_shift($picsSQL);

        }

    }

}


?>