<?php

// wp_mail show fail
// IMPORTANT!!!: be carefull to add_action for this!!!! Would be executed for every plugin
// and every fail for whole WordPress instance
if(!function_exists('cg_on_wp_mail_error')){
    function cg_on_wp_mail_error( $wp_error ) {

        global $cgMailAction;
        global $cgMailGalleryId;

        $uploadFolder = wp_upload_dir();
        $galleryLogsFolder = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$cgMailGalleryId.'/logs';
        $galleryErrorsFolder = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$cgMailGalleryId.'/logs/errors';

        if(!is_dir($galleryLogsFolder)){
            mkdir($galleryLogsFolder,0755,true);
        }

        if(!is_dir($galleryErrorsFolder)){
            mkdir($galleryErrorsFolder,0755,true);
        }

        $htaccessFile = $galleryErrorsFolder.'/.htaccess';

        if(!file_exists($htaccessFile)){
$usersTableHtmlStart = <<<HEREDOC
<Files "*.log">
   order deny,allow
   deny from all
</Files>
HEREDOC;

            $fp = fopen($htaccessFile, 'w');
            fwrite($fp, $usersTableHtmlStart);
            fclose($fp);
            chmod($htaccessFile, 0640);// no read for others!!!
        }

        $fileName = md5(wp_salt( 'auth').'---cnglog---'.$cgMailGalleryId);
        $file = $galleryErrorsFolder.'/mail-'.$fileName.'.log';

        $errorsFileContentBefore = '';

        if(file_exists($file)){
            $fp = fopen($file, 'r');
            $errorsFileContentBefore = fread($fp, filesize($file));
            fclose($fp);
        }

        $errorsFileContent = date('Y-m-d H:i:s')." (server-time)\r\n";
        $errorsFileContent = $errorsFileContent.$cgMailAction." - Gallery ID $cgMailGalleryId\r\n";
        $errorsFileContent = $errorsFileContent.'ERROR: '.$wp_error->errors['wp_mail_failed'][0]."\r\n";

        if(!empty($wp_error->errors['wp_mail_failed'][1])){
            $errorsFileContent = $errorsFileContent.'ERROR: '.$wp_error->errors['wp_mail_failed'][1]."\r\n";
        }
        if(!empty($wp_error->errors['wp_mail_failed'][2])){
            $errorsFileContent = $errorsFileContent.'ERROR: '.$wp_error->errors['wp_mail_failed'][2]."\r\n";
        }
        if(!empty($wp_error->errors['wp_mail_failed'][3])){
            $errorsFileContent = $errorsFileContent.'ERROR: '.$wp_error->errors['wp_mail_failed'][3]."\r\n";
        }

        $errorsFileContent = $errorsFileContent.'Send to: '.$wp_error->error_data['wp_mail_failed']['to'][0]."\r\n";
        $errorsFileContent = $errorsFileContent.'Subject: '.$wp_error->error_data['wp_mail_failed']['subject']."\r\n";
        $errorsFileContent = $errorsFileContent.'Headers Mime-Version: '.$wp_error->error_data['wp_mail_failed']['headers']['MIME-Version']."\r\n";
        $errorsFileContent = $errorsFileContent.'phpmailer_exception_code: '.$wp_error->error_data['wp_mail_failed']['phpmailer_exception_code']."\r\n\r\n";
        $errorsFileContent = $errorsFileContent.$errorsFileContentBefore;

        $fp = fopen($file, 'w');
        fwrite($fp, $errorsFileContent);
        fclose($fp);
        chmod($file, 0640);// no read for others!!!
        
    }
}

// wp_mail show fail --- ENDE
