<?php
if(!function_exists('cg_remove_not_required_coded_csvs')){

    function cg_remove_not_required_coded_csvs(){

         if(!current_user_can('manage_options')){
            echo "Logged in user have to be able to manage_options to remove csvs.";die;
        }

        $folderData = scandir(plugin_dir_path( __FILE__).'/../gallery');
        $directory = plugin_dir_path( __FILE__).'/../gallery';

        foreach ($folderData as $item) {
            if(strpos($item,'.csv')){
                unlink($directory.'/'.$item);
            }
        }


        $folderData = scandir(plugin_dir_path( __FILE__).'/../users/admin/users');
        $directory = plugin_dir_path( __FILE__).'/../users/admin/users';
        foreach ($folderData as $item) {
            if(strpos($item,'.csv')){
                unlink($directory.'/'.$item);
            }
        }
    }

}


?>