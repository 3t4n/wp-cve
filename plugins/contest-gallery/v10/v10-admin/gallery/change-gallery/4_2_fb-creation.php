<?php

// Facebook HTMLs erschaffen

$id = $object->id;

$Timestamp = $object->Timestamp;
$NamePic = $object->NamePic;
$WpUpload = $object->WpUpload;

if(empty($uploadFolder)){
    $uploadFolder = wp_upload_dir();
}

if(empty($blog_title)){
    $blog_title = get_bloginfo('name');
}
if(empty($blog_description)){
    $blog_description = get_bloginfo('description');
}

$dirHTML = $uploadFolder['basedir'].'/contest-gallery/gallery-id-'.$GalleryID.'/'.$Timestamp."_".$NamePic."413.html";

$fbImgSrc = wp_get_attachment_image_src($WpUpload, 'large');
$fbImgSrc = $fbImgSrc[0];

if(!file_exists($dirHTML) OR !empty($isCorrectAndImprove)){

    $scrImgMeta624 = $fbImgSrc;
    $scrImgMeta1024 = $fbImgSrc;

    $urlForFacebook= $uploadFolder['baseurl'].'/contest-gallery/gallery-id-'.$GalleryID."/".$Timestamp."_".$NamePic."413.html";

    //$urlForFacebook= $urlSource.'/wp-content/uploads/contest-gallery/gallery-id-'.$GalleryID."/".$Timestamp."_".$NamePic.".html";

    $fields = '<!DOCTYPE html>
                                                <html lang="en">
                                                <head>
                                                <title>'.$blog_title.'</title>
                                                <meta property="og:url"           content="'.$urlForFacebook.'" />
                                                <meta property="og:type"          content="website" />
                                                <meta property="og:title"         content="'.$blog_title.'" />
                                                <meta property="og:description"   content="'.$blog_description.'" />
                                                <meta property="og:image"         content="'.$scrImgMeta624.'" />
                                                <meta charset="utf-8">
                                                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                                                <!--FBLIKE-WIDTH-CORRECTION-START-->
                                                <style>
                                                    .fb_iframe_widget iframe {
                                                        width: unset !important;
                                                    }
                                                </style>
                                                <!--FBLIKE-WIDTH-CORRECTION-END-->
                                                 </head>
                                                <body  onload="checkIfIframe()">
                                                 
                                                 <div id="fb-root"></div>
                                                 
                                                <script>document.write("<script type=\'text/javascript\' src=\'backtogalleryurl.js?v=" + Date.now() + "\'><\/script>");</script>
                                                
                                                <script>
                                                
                                                galleryId = "'.$GalleryID.'";
                                                pictureId = "'.$id.'";
                                                backToGalleryUrl = (typeof backToGalleryUrl == "undefined") ? "" : backToGalleryUrl;
                                                namePic = "'.$NamePic.'";
                                                
                                                window.onmessage = function(event) {
                                                  if (event.data === "reload") {
                                                    location.reload();
                                                  }
                                                };
                                                
                                                function checkIfIframe(galleryId,pictureId,backToGalleryUrl,namePic){
                                                    if (window.frameElement) {
                                                    
                                                    }
                                                    else{
                                                        //http://localhost/fileadmin/test/test-more/test/#!gallery/29/image/113/dummy-576x1024-RedDots
                                                        if(backToGalleryUrl != "" && typeof backToGalleryUrl != \'undefined\'){
                                                           window.location.replace(backToGalleryUrl+"/#!gallery/"+galleryId+"/image/"+pictureId+"/"+namePic);
                                                        }else{
                                                           window.onload = function () {
                                                            document.getElementById("cgimg").innerHTML = "<img src=\''.$scrImgMeta1024.'\' width=\'800px\' />";
                                                            };
                                                        }
                                                        
                                                    }
                                                };
                                                
                                                checkIfIframe(galleryId,pictureId,backToGalleryUrl,namePic);
                                        
                                                var userBrowserLang = navigator.language || navigator.userLanguage;
    
                                                if(userBrowserLang.indexOf("en")==0){var userLang = "en_US";}
                                                else if(userBrowserLang.indexOf("de")==0){var userLang = "de_DE";}
                                                else if(userBrowserLang.indexOf("fr")==0){var userLang = "fr_FR";}
                                                else if(userBrowserLang.indexOf("es")==0){var userLang = "es_ES";}
                                                else if(userBrowserLang.indexOf("pt")==0){var userLang = "pt_PT";}
                                                else if(userBrowserLang.indexOf("nl")==0){var userLang = "nl_NL";}
                                                else if(userBrowserLang.indexOf("ru")==0){var userLang = "ru_RU";}
                                                else if(userBrowserLang.indexOf("zh")==0){var userLang = "zh_CN";}
                                                else if(userBrowserLang.indexOf("ja")==0){var userLang = "ja_JP";}
                                                else{var userLang = "en_US";}
                                                
                                                (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0];
                                                  if (d.getElementById(id)) return;
                                                  js = d.createElement(s); js.id = id;
                                                  js.src = "//connect.facebook.net/"+userLang+"/sdk.js#xfbml=1&version=v3.0";
                                                  fjs.parentNode.insertBefore(js, fjs);
                                                }(document, "script", "facebook-jssdk"));
                                                </script>
                                                
                                                <div class="'.$DataClass.'" data-href="'.$urlForFacebook.'" data-layout="'.$DataLayout.'" data-action="like" data-share="'.$DataShare.'" style="float:left;display:inline;vertical-align: middle;position:absolute;top:0;height: 30px;width:400px;overflow:hidden;"></div>
                                                <div style="margin-top:40px;" id="cgimg"></div>
                                                <div id="cgBackToGallery"></div>
                                                  
                                                </body>
                                                </html>';
    $fp = fopen($dirHTML, 'w');
    fwrite($fp, $fields);
    fclose($fp);

}

