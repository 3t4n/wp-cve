<?php
function click5_getMailPlainTemplate($userName, $eventsCount, $dbRequest){
    $unsubscribeLink = admin_url("admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=alerts");
    $message = "Howdy";
    $message .= $userName.",\r\n\r\n";
    $message .= "You are receiving this email notification because you have opted to be alerted about issues with ".get_bloginfo('name')." (".get_site_url().") website.\r\n\r\n";
    $message .= "Here are the last ".$eventsCount." events from the history log:\r\n\r\n";

    foreach($dbRequest as $content){

        $content->description = str_replace("\n", "",$content->description);
        $historyDate = new DateTime(); 
        $historyDate->setTimezone(new DateTimeZone(wp_timezone_string()));
        $historyDate->setTimestamp(strtotime($content->date));
        $historyDate = $historyDate->format(get_option('date_format')." ".get_option('time_format'));
        $description = str_replace("<br>","\r\n",$content->description);
        $description = str_replace("  "," ",$content->description);

        $historyLog = $content->plugin."\r\n".$description."\r\n".click5_get_user_by($content->user,'login')." on ".$historyDate."\r\n\r\n";
        $historyLog = str_replace("<b>",'',$historyLog);
        $historyLog = str_replace("</b>",'',$historyLog);
        $historyLog = str_replace("<br>","\r\n",$historyLog);
        $historyLog = str_replace(" ",'',$historyLog);
        $historyLog = str_replace("–","–",$historyLog);
        $historyLog = str_replace("—","—",$historyLog);
        $message .= $historyLog;
    }
    $message .= "\r\nWhen seeking help with this issue, you may be asked for some of the following information:\r\n";
    $message .= "WordPress version: ".$GLOBALS['wp_version']."\r\n";
    $message .= "Active theme: ".wp_get_theme()->get("Name")." (version ".wp_get_theme()->get("Version").")"."\r\n";
    $message .= "PHP version: ".phpversion()."\r\n\r\n\r\n";
    $message .= "Recently modified theme files: \r\n";
    if ( ! function_exists( 'list_files' ) )
    {
      require_once( ABSPATH . 'wp-admin/includes/file.php' );
    }
    $files = list_files(get_template_directory());
    $isModified = false;
    $iterator = 0;
    $filesArray = array();
    foreach($files as $file){
      $modifiedTime = filemtime($file);
      $modifiedTime24 = $modifiedTime+24*60*60*30;
      $currentTime = time();

      if($modifiedTime24 >= $currentTime){
        $isModified = true;
        $fileName = str_replace(ABSPATH,"",$file);

        if( strpos(strtolower($file),".ttf") === false &&
            strpos(strtolower($file),".otf") === false &&
            strpos(strtolower($file),".woff") === false &&
            strpos(strtolower($file),".woff2") === false &&
            strpos(strtolower($file),".eot") === false &&
            strpos(strtolower($file),".svg") === false &&
            strpos(strtolower($file),".jpeg") === false &&
            strpos(strtolower($file),".jpg") === false &&
            strpos(strtolower($file),".bmp") === false &&
            strpos(strtolower($file),".gif") === false &&
            strpos(strtolower($file),".png") === false &&
            strpos(strtolower($file),".psd") === false &&
            strpos(strtolower($file),".tiff") === false &&
            strpos(strtolower($file),".heif") === false &&
            strpos(strtolower($file),".webp") === false &&
            strpos(strtolower($file),".webm") === false &&
            strpos(strtolower($file),".raw") === false  )
          $filesArray[$fileName] = $modifiedTime;

      }
    }

    arsort($filesArray);

    foreach($filesArray as $file => $timestamp){
      $modifiedDate = new DateTime(); 
      $modifiedDate = new DateTime(); 
      $modifiedDate->setTimezone(new DateTimeZone(wp_timezone_string()));
      $modifiedDate->setTimestamp($timestamp);
      $modifiedDate = $modifiedDate->format(get_option('date_format')." ".get_option('time_format'));
      $fileName = $file;
      $iterator++;

        $message .= $fileName." (".$modifiedDate.")"."\r\n";

        if($iterator >= 10)
        break;
      }
    

    if($isModified === false){
      $message .= "No theme files have been modified within the last 30 days";
    }
    
    $message .= "\r\n\r\n";
    $message .= "\r\nThank you for choosing our History Log plugin!\r\n\r\nThe click5 Team\r\n\r\n--\r\n";
    $message .= "Don't forget to rate our plugin - https://wordpress.org/support/plugin/history-log-by-click5/reviews/?filter=5";

    $c5SiteMap_Pathpluginurl = WP_PLUGIN_DIR . '/sitemap-by-click5/sitemap-by-click5.php';
    $c5SiteMap_IsInstalled = file_exists( $c5SiteMap_Pathpluginurl );
    if($c5SiteMap_IsInstalled == false)
        $message .= "\r\nGive a try our Sitemap plugin - https://wordpress.org/plugins/sitemap-by-click5/";

    $message .= "\r\nUpdate email preferences - $unsubscribeLink\r\n\r\n\r\n";    

    $message = str_replace("&",'&',$message);
    $message = str_replace("&",'&',$message);
    $message = wp_specialchars_decode( $message, ENT_QUOTES );
    return $message;
}

 function click5_getMailHtmlTemplate($userName, $eventsCount, $dbRequest){
  $pluginAssetsURL = plugin_dir_url("history-log-by-click5/history-log-by-click5.php")."assets";
  $wpVersion = $GLOBALS['wp_version'];
  $activeTheme = wp_get_theme()->get("Name")." (version ".wp_get_theme()->get("Version").")";
  $phpVersion = phpversion();
  $siteURL = get_site_url();
  $siteName = get_bloginfo('name');
  $unsubscribeLink = admin_url("admin.php?page=history-log-by-click5%2Fhistory-log-by-click5.php&tab=alerts");
  $HistoryVersion = click5_history_log_VERSION;
  $getSitemapPlugin = "";
  $c5SiteMap_Pathpluginurl = WP_PLUGIN_DIR . '/sitemap-by-click5/sitemap-by-click5.php';
  $c5SiteMap_IsInstalled = file_exists( $c5SiteMap_Pathpluginurl );
  $paddingBottom = "";
  if($c5SiteMap_IsInstalled == false){
    $getSitemapPlugin = <<<HTML
    <h3 style="/*font-family:'Gellix';*/ font-size: 17px; font-weight:400; color: #fff; padding: 10px 0 20px; text-align: center;">Give a try our <a style="color:#fff; text-decoration: underline;" target="_blank"  href="https://wordpress.org/plugins/sitemap-by-click5/">Sitemap plugin</a></h3>
HTML;
    $paddingBottom = "padding-bottom: 0;";
  }
 
  


    $message = <<<END
  <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
  <html xmlns="http://www.w3.org/1999/xhtml" xmlns:o="urn:schemas-microsoft-com:office:office" style="width:100%;font-family:arial;padding:0;Margin:0">
   <head> 
    <meta charset="UTF-8"> 
    <meta content="width=device-width, initial-scale=1" name="viewport"> 
    <meta name="x-apple-disable-message-reformatting"> 
    <meta http-equiv="X-UA-Compatible" content="IE=edge"> 
    <meta content="telephone=no" name="format-detection"> 
    <title>History Log</title> 
  
    <style type="text/css">
      @font-face {
		    font-family: 'Gellix';
		    src: @import url('{$pluginAssetsURL}/fonts/Gellix-Bold.woff2') format('woff2'),
        @import url('{$pluginAssetsURL}/fonts/Gellix-Bold.woff') format('woff');
		    font-weight: bold;
		    font-style: normal;
		    font-display: swap;
		}
		
		@font-face {
		    font-family: 'Gellix';
		    src: @import url('{$pluginAssetsURL}/fonts/Gellix-LightItalic.woff2') format('woff2'),
        @import url('{$pluginAssetsURL}/fonts/Gellix-LightItalic.woff') format('woff');
		    font-weight: 300;
		    font-style: italic;
		    font-display: swap;
		}
		
		
		@font-face {
		    font-family: 'Gellix';
		    src: @import url('{$pluginAssetsURL}/fonts/Gellix-Medium.woff2') format('woff2'),
        @import url('{$pluginAssetsURL}/fonts/Gellix-Medium.woff') format('woff');
		    font-weight: 500;
		    font-style: normal;
		    font-display: swap;
		}
		
		@font-face {
		    font-family: 'Gellix';
		    src: @import url('{$pluginAssetsURL}/fonts/Gellix-Regular.woff2') format('woff2'),
        @import url('{$pluginAssetsURL}/fonts/Gellix-Regular.woff') format('woff');
		    font-weight: normal;
		    font-style: normal;
		    font-display: swap;
		}
		
		
		
		@font-face {
		    font-family: 'Gellix';
		    src: @import url('{$pluginAssetsURL}/fonts/Gellix-SemiBold.woff2') format('woff2'),
        @import url('{$pluginAssetsURL}/fonts/Gellix-SemiBold.woff') format('woff');
		    font-weight: 600;
		    font-style: normal;
		    font-display: swap;
		}

    a[href^="mailto:"]{
      color: #132129 !important;
    }
		
		table{
			font-family: 'Gellix' !important;
		}

    strong{
      font-weight: bold !important;
    }


		@media only screen and (max-width:600px) {
			
		}
      
  </style> 
  
   </head> 
   <body style="width:100%;font-family:helvetica, 'helvetica neue', arial, verdana, sans-serif;-webkit-text-size-adjust:100%;-ms-text-size-adjust:100%;padding:0;Margin:0"> 
    <div class="" style="background-color:#f6f8fa;"> 
     <table class="es-wrapper" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f6f8fa;border-collapse:collapse;border-spacing:0px;padding:0;Margin:0;width:100%;height:100%;background-repeat:repeat;background-position:center top"> 
       
       <tr>
         <td>
           <table style="max-width: 700px; width: 100%;  margin: auto; ">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style=" ">
                       <h1 style="/*font-family:'Gellix';*/ font-size: 35px; color: #2f343e; margin: 0;" class="logo-h1">History Log</h1>
                       <h2 style="font-weight: 400; color:#5e6f79; font-size: 21px; margin: 0;" class="logo-h2">Alert about <strong style="font-weight: 500;" >{$siteName}</strong> website issue </h2>
                    </td>
                    
                    <td style="text-align: right; padding: 10px 0 0;">
                      <img src="{$pluginAssetsURL}/img/logo.png"/>
                    </td>	
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
           
           <table style="max-width: 700px; width: 100%; margin: auto; background-color: #2cc0f2; border-radius: 2px;">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style=" ">
                       <h2 style="/*font-family:'Gellix';*/ font-size: 23px; font-weight:600; color: #fff; border-bottom: 1px solid rgba(255,255,255,0.3); padding: 20px 0;">Howdy {$userName},</h2>
                       <p style="font-weight: 400; color:#fff; font-size: 16px; line-height: 1.4; padding-bottom: 20px;">You are receiving this email notification because you have opted to be alerted 
  about issues with {$siteName} (<a style="color:#fff;" href="{$siteURL}">{$siteURL}</a>) website.</p>
                    </td>
                    
                      
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
           
           <table style="max-width: 700px; width: 100%;  margin: auto; background-color: #fff; margin-top: 10px; border-radius: 2px;">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style="padding-bottom: 20px;">
                       <h2 style="/*font-family:'Gellix';*/ font-size: 23px; font-weight:600;  color: #1f262a; border-bottom: 1px solid #cbd6df; padding: 32px 0; margin: 0;">Here are the last {$eventsCount} events from the history log</h2>
END;
                        foreach($dbRequest as $content){

                          $content->description = str_replace("\n", "",$content->description);
                          $historyDate = new DateTime(); 
                          $historyDate->setTimezone(new DateTimeZone(wp_timezone_string()));
                          $historyDate->setTimestamp(strtotime($content->date));
                          $historyDate = $historyDate->format(get_option('date_format')." ".get_option('time_format'));
                          $description = str_replace("<br>","",$content->description);
                          $description = str_replace("  "," ",$content->description);
                          $user = click5_get_user_by($content->user,'login');
                          if(strpos(strtolower($description),"critical error:") !== false)
                            $description = str_replace("Critical error:",'<small style="background-color: #e4512f; color: #fff; border-radius: 4px; padding: 2px 9px 3px; font-size: 12px; display: inline; position: relative; top: -3px;">critical error</small>',$description);
                          else if(strpos(strtolower($description),"wp_php_error_message")){
                            $description = str_replace("(wp_php_error_message)",'</p><p style="font-size: 14px; font-weight: 400; color: #132129; margin: 6px 0;"><small style="background-color: #e4512f; color: #fff; border-radius: 4px; padding: 2px 9px 3px; font-size: 11px; display: inline; position: relative; top: -2px; letter-spacing: 0.5px;">wp_php_error_message</small> ',$description);
                            $description = str_replace("Uncaught Error:","<strong> Uncaught Error: </strong>",$description);
                          }else if(strpos(strtolower($description),"(recovery_mode_email)")){
                            $description = '<small style="background-color: #f2a62e; color: #fff; border-radius: 4px; padding: 2px 9px 3px; font-size: 12px; display: inline; position: relative; top: -2px;">recovery_mode_email</small>
                            Your Site is Experiencing a Technical Issue';
                          }else if(strpos(strtolower($description),"alerts email addresses") !== false){
                            $description = str_Replace("<b>","",$description);
                            $description = str_Replace("</b>","",$description);
                            $description = str_Replace("Alerts email addresses have been changed to","Alerts email addresses have been changed to <a style='color:#132129; text-decoration:underline;'>",$description);
                            $description .= "</a>";
                          }

                          $historyLog = <<<END
                          <div style="display: block; border-bottom: 1px solid #ededed; padding: 17px 0; margin: 0;" >
                          <h3 style="font-size: 17px; font-weight: 600; color: #0098d6; margin: 5px 0;">{$content->plugin}</h3>
                            <p style="font-size: 16px; font-weight: 500; color: #132129; margin: 6px 0;">
                             {$description}
                            </p>
                            <small style="font-style: italic; font-weight: 300; color: #2f2f2f; "><strong style="font-style: normal; font-weight: 600;">{$user}</strong> on {$historyDate}</small>
                          </div>
END;

                          $historyLog = str_replace("<b>",'',$historyLog);
                          $historyLog = str_replace("</b>",'',$historyLog);
                          if($content->plugin !== "404 Errors")
                            $historyLog = str_replace("<br>","",$historyLog);
                          $message .= $historyLog;
                        }                       
                          $message .= <<<END
                    </td>
                    
                      
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
           
           
           
           
           
           <table style="max-width: 700px; width: 100%;  margin: auto; background-color: #fff; margin-top: 10px; border-radius: 2px;">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style="padding-bottom: 20px;">
                       <h2 style="/*font-family:'Gellix';*/ font-size: 23px; font-weight:600;  color: #1f262a; border-bottom: 1px solid #cbd6df; padding: 32px 0; margin: 0;">Recently modified theme files</h2>
                       
                       
                       <table width="100%">
END;
                          $iterator = 2;
                          if ( ! function_exists( 'list_files' ) )
                          {
                            require_once( ABSPATH . 'wp-admin/includes/file.php' );
                          }
                          $files = list_files(get_template_directory());
                          $isModified = false;
                          $filesArray = array();
                          foreach($files as $file){
                            $modifiedTime = filemtime($file);
                            $modifiedTime24 = $modifiedTime+24*60*60*30;
                            $currentTime = time();

                            if($modifiedTime24 >= $currentTime){
                              $isModified = true;
                              $fileName = str_replace(ABSPATH,"",$file);
                              $fileName = str_replace("wp-content/themes/".wp_get_theme()->get_template()."/","",$fileName);

                              if( strpos(strtolower($file),".ttf") === false &&
                                  strpos(strtolower($file),".otf") === false &&
                                  strpos(strtolower($file),".woff") === false &&
                                  strpos(strtolower($file),".woff2") === false &&
                                  strpos(strtolower($file),".eot") === false &&
                                  strpos(strtolower($file),".svg") === false &&
                                  strpos(strtolower($file),".jpeg") === false &&
                                  strpos(strtolower($file),".jpg") === false &&
                                  strpos(strtolower($file),".bmp") === false &&
                                  strpos(strtolower($file),".gif") === false &&
                                  strpos(strtolower($file),".png") === false &&
                                  strpos(strtolower($file),".psd") === false &&
                                  strpos(strtolower($file),".tiff") === false &&
                                  strpos(strtolower($file),".heif") === false &&
                                  strpos(strtolower($file),".webp") === false &&
                                  strpos(strtolower($file),".webm") === false &&
                                  strpos(strtolower($file),".raw") === false  )
                                $filesArray[$fileName] = $modifiedTime;
                            }
                          }

                          arsort($filesArray);

                          foreach($filesArray as $file => $timestamp){
                            $modifiedDate = new DateTime(); 
                            $modifiedDate->setTimezone(new DateTimeZone(wp_timezone_string()));
                            $modifiedDate->setTimestamp($timestamp);
                            $modifedDateDATE = $modifiedDate->format(get_option('date_format'));
                            $modifedDateTIME = $modifiedDate->format(get_option('time_format'));
                            $fileName = $file;

                            $trStyle = 'style="background-color: #fafbfc;"';
                              if($iterator%2 == 0){
                                $trStyle = "";
                              }
                              $iterator++;

                              $message .= <<<END
                              <tr {$trStyle}>
                                <td style="font-size: 15px; padding: 10px; ">{$fileName}</td>
                                <td style="text-align: right; font-size: 14px; font-weight: 300; opacity: 0.5; padding: 10px;">{$modifedDateDATE} <span style="display: inline-block;">{$modifedDateTIME}</span></td>
                              </tr> 
END;
                              if($iterator >= 12)
                              break;
                            }

                          if($isModified === false){
                            $message .= '<tr> <td style="font-size: 15px; padding: 10px; ">No theme files have been modified within the last 30 days</td></tr>';
                          }
                            $message .= <<<END
                       </table>     
                    </td>
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
  
           
            <table style="max-width: 700px; width: 100%;  margin: auto; background-color: #fff; margin-top: 10px; border-radius: 2px;">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style="padding-bottom: 20px;">
                       <h2 style="/*font-family:'Gellix';*/ font-size: 23px; font-weight:600;  color: #1f262a; border-bottom: 1px solid #cbd6df; padding: 32px 0; margin: 0;">When seeking help with this issue, you may be asked<br/> 
  for some of the following information: </h2>
                       
                       
                       <table width="100%">
                         <tr>
                           <td style="font-size: 15px; font-weight: 500; padding: 20px 0px; border-bottom: 1px solid #ededed; width: 35%;">WordPress version </td>
                           <td style="text-align: left; font-size: 15px; font-weight: 300;  color: #626262; border-bottom: 1px solid #ededed; width: 65%;">{$wpVersion}</td>
                         </tr> 
                         
                          <tr>
                           <td style="font-size: 15px; font-weight: 500; padding: 20px 0; border-bottom: 1px solid #ededed; width: 35%;">Active theme </td>
                           <td style="text-align: left; font-size: 15px; font-weight: 300;  color: #626262; border-bottom: 1px solid #ededed; width: 65%;">{$activeTheme}</td>
                         </tr>
                         
                         
                          <tr>
                           <td style="font-size: 15px; font-weight: 500; padding: 20px 0;  width: 35%;">PHP version </td>
                           <td style="text-align: left; font-size: 15px; font-weight: 300; color: #626262;   border-bottom: 1px solid #ededed; width: 65%;">{$phpVersion}</td>
                         </tr>
                         
                         
                         
                         
                       </table>     
                    </td>
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
  
           
           <table style="max-width: 700px; width: 100%;  margin: auto; background-color: #2cc0f2; border-radius: 2px; margin-top: 10px; margin-bottom: 30px;">
             <tr>
               <td>
                  <table style="max-width: 600px; width: 96%; margin: auto;">
                  <tr>
                     <td style=" ">
                       <h2 style="/*font-family:'Gellix';*/ font-size: 20px; font-weight:400; color: #fff; padding: 20px 0 0; text-align: center; ">Thank you for choosing our History Log plugin!<br/> 
  <strong style="font-weight: 600;">The click5 Team</strong></h2>
                      
                       <h3 style="/*font-family:'Gellix';*/ font-size: 17px; font-weight:400; color: #fff; padding: 10px 0 20px; text-align: center; $paddingBottom"><a style="color:#fff; text-decoration: underline;" target="_blank" href="https://wordpress.org/support/plugin/history-log-by-click5/reviews/?filter=5">Don't forget to rate our plugin</a></h3>
                       <!--<h3 style="/*font-family:'Gellix';*/ font-size: 17px; font-weight:400; color: #fff; padding: 10px 0 20px; text-align: center; $paddingBottom"><a style="color:#fff; text-decoration: underline;" target="_blank" href="$unsubscribeLink">Unsubscribe</a></h3>-->
                        {$getSitemapPlugin}
                    </td>
                    
                      
                  </tr>
                  </table>
               </td>
             </tr>
           </table>
           <table style="max-width: 700px; width: 100%; margin: auto auto 50px auto;">
            <tr>
              <td style="color: #ababab; font-size: 12px; line-height: 22px; text-align: center;">
                Powered by <a href=" https://www.click5interactive.com/wordpress-history-log-plugin/?utm_source=history-plugin&utm_medium=email-alert&utm_campaign=wp-plugins" style="color: #ababab; text-decoration:underline;">History Log</a> plugin v$HistoryVersion.
                <br>
                <a href="$unsubscribeLink" style="color: #ababab; text-decoration:underline;">Update email preferences</a>
              </td>
            </tr>
          </table>
         </td>
      </tr>
     </table>
    </div>
   </body>
  </html>
END;
return $message;
}
