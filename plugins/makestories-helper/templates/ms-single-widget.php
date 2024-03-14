<?php $current_user = wp_get_current_user(); ?>
<div class="story-widget <?php if (user_can( $current_user, 'administrator' )) echo "widget-message" ?>">
    <?php
        $id = uniqid();
        $divId = $meta['divId'][0];
        $scriptCode = $meta['js-block'][0];
        $divBlock = $meta['container'][0];

        $newId = $divId.$id;
        $script = str_replace($divId,$newId,$scriptCode);
        $div = str_replace($divId,$newId,$divBlock);

        $n = explode("let ", $script);
        $result = Array();
        foreach ($n as $val) {
            $pos = strpos($val, " =");
            if ($pos !== false) {
                $result[] = substr($val, 0, $pos);
            }
        }
        $variableName =  $result;
        $randomletter = substr(str_shuffle("abcdefghijklmnopqrstuvwxyz"), 0, 3);
        $newLabel = $variableName[0].$randomletter;
        $scriptNew = str_replace($variableName[0],$newLabel,$script);

        //print the player code
        echo $scriptNew;
        echo $div;
        // echo "<pre>";
        // var_dump($meta);
        // echo "</pre>";
    ?>
</div>