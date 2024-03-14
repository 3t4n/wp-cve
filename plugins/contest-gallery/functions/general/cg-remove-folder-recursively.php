<?php

if(!function_exists('cg_remove_folder_recursively')){
    function cg_remove_folder_recursively($dir){

        // .htaccess or .github folder requires extra glob!
        // all starting with dot requires extra glob!
        $dirContentLikeHtaccess = glob($dir.'/.*');

        foreach($dirContentLikeHtaccess as $item){
            if(is_dir($item)){
                $explode = explode('/',$item);
                $lastPart = end($explode);
                if($lastPart!='.' && $lastPart!='..'){// . and .. as go back folders will be also selected by glob
                    cg_remove_folder_recursively($item);
                }
            }
            else{
                if(is_file($item)){
                    unlink($item);
                }
            }
        }

        $dirContent = glob($dir.'/*');

        foreach($dirContent as $item){

            if(is_dir($item)){
                cg_remove_folder_recursively($item);
            }
            else{
                if(is_file($item)){
                    unlink($item);
                }
            }

        }

        // is_dir check important here!
        if(is_dir($dir)){
            rmdir($dir);
        }

    }
}

?>