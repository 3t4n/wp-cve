<?php
if(!function_exists('cg_google_sign_in_verification')){
    function cg_google_sign_in_verification($CLIENT_ID,$id_token,$isFromUpload = false){

        try{
            $plugin_file_dir_path = plugin_dir_path(__FILE__);;
            require_once ($plugin_file_dir_path.'../../../contest-gallery-google-sign-in-library/vendor/autoload.php');
        }catch(\Exception $exception){
            echo 'google vendor autoload could not be loaded';
        }

        $client = new Google_Client(['client_id' => $CLIENT_ID]);

        $errorMessage = '';

        $payload = false;

        try{
            $payload = $client->verifyIdToken($id_token);
        }catch(\Exception $exception){
            $errorMessage = $exception->getMessage();
        }

        if ($payload && $payload['aud'] == $CLIENT_ID) {
            return $payload;
        } else {

            if($isFromUpload){
                ?>
                <script data-cg-processing="true">
                    cgJsClass.gallery.upload.doneUploadFailed = true;
                    cgJsClass.gallery.upload.failMessage = <?php echo json_encode("Google client could not be verified, code 511. Please contact administrator. Error Message: ".$errorMessage);?>;
                </script>
                <?php
                echo 'Google client could not be verified, code 511. Please contact administrator. ErrorMessage: '.$errorMessage;
                die;
            }else{

                echo 'Google client could not be verified, code 511. Please contact administrator.';
                ?>

                <script data-cg-processing="true">

                    var errorMessage = <?php echo json_encode($errorMessage);?>;
                    cgJsClass.gallery.function.message.close();
                    cgJsClass.gallery.function.message.showPro(undefined,'Google client could not be verified, code 511. Please contact administrator. Error Message: '+errorMessage);

                </script>

                <?php
                die;

            }

        }

    }
}