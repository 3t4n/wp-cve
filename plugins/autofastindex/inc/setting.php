<!DOCTYPE html>
<html lang="en">
<head>
    <title>Autofast Index </title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_enqueue_script("jquery"); ?>
    <?php wp_enqueue_style('bootstrap4', plugins_url('../assets/css/bootstrap.css', __FILE__)); ?>
    <?php wp_enqueue_style('font', plugins_url('../assets/css/font.css', __FILE__)); ?>
    <style>
.accordion {
  background-color: #eee;
  color: #444;
  cursor: pointer;
  padding: 18px;
  width: 100%;
  border: none;
  text-align: left;
  outline: none;
  font-size: 15px;
  transition: 0.4s;
    margin:5px;
}

.active, .accordion:hover {
  background-color: #ccc; 
}

.panel {
  padding: 0 18px;
  display: none;
  background-color: white;
  overflow: hidden;
}
.checkbox{
    padding: 6px;
    background: #eeeeee;
}
</style>

</head>

<body>


<?php

include_once('logs.php');
$configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
$configapi = json_decode($configapi);
$api_url = $configapi->api_url;




if (isset($_POST['testbing'])) {


    include_once('bingtest.php');

    try{
        $get = file_get_contents(autoindex_upload . '/settings.json');
        $data = json_decode($get);
        $site = $data->url;
        $bingapi = $data->bingapi;
        //  $file=$data->google_json_file;
        $email = $data->email;
    
    
        $result = bing($site, $site, $bingapi, $email,$data);
        echo "<div class='notice notice-success'>" . esc_attr($result->msg) . "</div>";

    }catch(\Error $e){
        addLog($e,'testBing');

    }
}

if (isset($_POST['yandex'])) {
    include_once('bingtest.php');

    try{
        $get = file_get_contents(autoindex_upload . '/settings.json');
        $data = json_decode($get);
        $site = $data->url;
        $userId=$data->yandex_UserId;
        $hostId=$data->yandex_HostId;
        $auth=$data->yandex_AuthKey;
        $email = $data->email;
    
        $result = yandex($site, $site,$userId,$hostId,$auth,$data,$email);
    
    
        echo "<div class='notice notice-success'>" . esc_attr($result->msg) . "</div>";
    
    }catch(\Error $e){
        addLog($e,'testYandex');
    }
}

if (isset($_POST['testgoogle'])) {
    include_once('bingtest.php');

    try{
        $get = file_get_contents(autoindex_upload . '/settings.json');
        $data = json_decode($get);
        $site = $data->url;
        $email = $data->email;
    
        $file = $data->google_json_file;
    
    
        // var_dump($bingurl);
    
        $result = google($site, $data, $email);
        echo "<div class='notice notice-success'>" . esc_attr($result->msg) . "</div>";
    }catch(\Error $e){
        addLog($e,'testGoogle');
    }

}


if (isset($_POST['direct'])) {
    include_once('bingtest.php');
    $get = file_get_contents(autoindex_upload . '/settings.json');
    $data = json_decode($get);
    $site = $data->url;
    $email = $data->email;

    $file = $data->google_json_file;

    $result = direct($site, $site, $data, $email);

   // $result = complete($site, $data, $email,$site);
    echo "<div class='notice notice-success'>" . esc_attr($result->msg) . "</div>";
}



if (isset($_POST['submit'])) {

    try{


        $url = esc_url_raw(sanitize_text_field($_POST['url']));
        $bingapi = sanitize_text_field($_POST['bingapi']);
    
        $url = rtrim($url,"/");
       
        $urlcheck=explode('/',"url");
    
        if ($urlcheck[3]) {
           
            echo "<div class='notice notice-error'> Not a Valid Url  eg:( https://example.com )</div>";
        
        }
        else{
            $uploadOk = 1;
            $data = [];
            $data["url"] = $url;
            $data["bingapi"] = $bingapi;
            $data['enable_bing'] = sanitize_text_field($_POST['enable_bing']=='on' ? 1: 0);
            $data['enable_google'] = sanitize_text_field($_POST['enable_google']=='on' ? 1 : 0);
            $data['email'] = sanitize_email($_POST['email']);
            $email = sanitize_email($_POST['email']);
            $data['whatsapp_notification']=sanitize_text_field($_POST['whatsapp_notification']=='on' ? 1 : 0 );
            $data['whatsapp_no']=sanitize_text_field(ltrim($_POST['whatsapp_no'],'+'));
          
            $data['email_notification']=sanitize_text_field($_POST['email_notification']=='on' ? 1 : 0 );
            $data['google_json_text_enable']=sanitize_text_field($_POST['google_json_text_enable']=='on' ? 1 : 0 );
            $data['google_json_file'] = esc_url_raw(sanitize_text_field($_POST['google_json_value']));
            $data['google_json_text']=base64_encode(wp_unslash($_POST['google_json_text']));
            $data['enable_yandex'] = sanitize_text_field($_POST['enable_yandex']=='on' ? 1 : 0);
            $data['yandex_UserId'] = sanitize_text_field($_POST['yandex_UserId']);
            $data['yandex_HostId'] = sanitize_text_field($_POST['yandex_HostId']);
            $data['yandex_AuthKey'] = sanitize_text_field($_POST['yandex_AuthKey']);
            $data['enabledirectIndexing'] = sanitize_text_field($_POST['enabledirectIndexing']=='on' ? 1 : 0 );
            $data['version'] = sanitize_text_field('2.10.7');
            $postRequest = [
                "add" => "3",
                "email" => $email,
                "site" => $url,
                "version" => '2.10.7',
                "data" =>  base64_encode(wp_json_encode($data, JSON_PRETTY_PRINT))
            ];
    
    //        var_dump(wp_json_encode($data, JSON_PRETTY_PRINT));
    
            $args = array(
                'body' => $postRequest,
                'timeout' => '5',
                'redirection' => '5',
                'httpversion' => '1.0',
                'blocking' => true,
                'headers' => array(),
                'cookies' => array(),
            );
    
            $response = wp_remote_post($api_url, $args);
    
            $response=json_decode($response['body']);
    
            file_put_contents(autoindex_upload. '/settings.json', wp_json_encode($data, JSON_PRETTY_PRINT));
    
            if(!$response){
                echo "<div class='notice notice-error'> Try Again!</div>";
    
            }else{
    
                if($response->err==0){
                    echo "<div class='notice notice-success'>" . esc_attr($response->msg) . "</div>";
                }else{
    
                    echo "<div class='notice notice-error'> Try Again!</div>";
                }
            }
        }

    }catch(\Error $e){
        addLog($e,'submit');

    }



}


?>

<?php
if (file_exists(autoindex_upload . '/settings.json')) {
    $get = file_get_contents(autoindex_upload . '/settings.json');
    $data = json_decode($get);
    $email = $data->email;
    $site = $data->url;
    $enablegoogle = $data->enable_google;
    $enablebing = $data->enable_bing;
    $bingapi = $data->bingapi;
    $file = $data->google_json_file;
    $e_notification=$data->email_notification;
    $w_notification=$data->whatsapp_notification;
    $whatsapp_no=$data->whatsapp_no;
    $google_text_enable=$data->google_json_text_enable;
    $google_text=base64_decode($data->google_json_text);
    $enable_yandex=$data->enable_yandex;
    $yandex_UserId=$data->yandex_UserId;
    $yandex_HostId=$data->yandex_HostId;
    $yandex_AuthKey=$data->yandex_AuthKey;
    $enabledirectIndexing=$data->enabledirectIndexing;

}



?>


<div class="container border border-primary" style="margin-top:10px;">
<center>  <h2>AutoFast Indexing Settings</h2> </center>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
    
<h5 class="card-title">Registration</h5>
       

        <div class="form-group">
            <label class="control-label col-sm-12" for="email">Email: eg (example@gmail.com)</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" id="email" value="<?php echo esc_html($email); ?>"
                       placeholder="Enter email" name="email" required>
            </div>
        </div>
        <div class="form-group">
            <label class="control-label col-sm-12" for="pwd">Site: eg (https://example.com)</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="url" placeholder="https://" name="url"
                       value="<?php echo esc_url_raw($site); ?>" required>
            </div>
        </div>


        <hr/>
     
        <h5 class="card-title">Notifications/Updates</h5>
 
      


        <div class="form-group row">
        
            <div class="col-sm-offset-2 col-sm-6">
                <div class="checkbox">
                  
                <img style="width:48px;" src="<?php echo  plugins_url('autofastindex/assets/images/email.png')  ?>" />
       
                    <?php if ($e_notification == 'on' || $e_notification == 1) { ?>
                        <label><input type="checkbox" name="email_notification" checked> Email Notification</label>
                    <?php } else { ?>
                        <label><input type="checkbox" name="email_notification"> Email Notification</label>

                    <?php } ?>
                  
                </div>

               
                <div class="checkbox">
                 <img style="width:48px;" src="<?php echo  plugins_url('autofastindex/assets/images/whatsapp.png')  ?>" />

                    <?php if ($w_notification == 'on' || $w_notification ==1) { ?>
                        <label><input type="checkbox" id="check" name="whatsapp_notification" checked> whatsapp Notification</label>


                    <?php } else { ?>
                        <label><input type="checkbox" id="check" name="whatsapp_notification"> whatsapp Notification </label>

                    <?php } ?>
                   
                    <br/>

                <b> Whatsapp Contact No. </b>    
                <input type="number" id="whatsapp_no"  name="whatsapp_no" value="<?php echo $whatsapp_no; ?>"  >
                    <p> use country code eg ( 91xxxxxxxxx ) </p>



                </div>

                    

            </div>

            <div class="col-sm-6">

            <img style="width:48px;" src="<?php echo  plugins_url('autofastindex/assets/images/email.png')  ?>" />
           
            <img style="width:48px;" src="<?php echo  plugins_url('autofastindex/assets/images/whatsapp.png')  ?>" />
                    

                     <p>  You can  get daily notification by selecting your channel </p>

                     <p> You will recieve latest google and bing statics data in you mail </p>

                    </div>

        </div>
       
       
       
       <hr/>
        


       <h2>Integrations</h2>

       <div class="accordion">Refer Indexing</div>
        <div class="panel">
           <div class="row col-sm-12">

           <p> We use our official website to indexing your site, We create a seprate page for your site and list all your URL`s , and then submit to Google, Bing and other search engine platforms. </p>
              <br/> 
              <div class="col-sm-6">
              <label class="control-label col-sm-12" for="pwd">Enable Refer Indexing:</label>
               <div class="checkbox">
                    <?php if ($enabledirectIndexing == 'on' || $enabledirectIndexing==1) { ?>
                        <label><input type="checkbox" name="enabledirectIndexing" checked></label>
                    <?php } else { ?>
                        <label><input type="checkbox" name="enabledirectIndexing"></label>

                    <?php } ?>

               </div>
             
        </div>
            </div>
        </div>    



<div class="accordion">Google</div>
<div class="panel">



    <div class="row col-sm-12">

        <div class="col-sm-6">
        <label class="control-label col-sm-12" for="pwd">Enable Google Api:</label>
        <div class="checkbox">
            <?php if ($enablegoogle == 'on' || $enablegoogle==1) { ?>
                <label><input type="checkbox" name="enable_google" checked></label>
            <?php } else { ?>
                <label><input type="checkbox" name="enable_google"></label>

            <?php } ?>
            Check for enable Google Indexing
        </div>


        <br/>
        <br/>



        <div class="row card">

        <div class="form-group" id="google_file">
            <label class="control-label col-sm-12" for="pwd">Upload Google Json:</label>
            <div class="col-sm-10">

                <input id="background_image" name="google_json_value" type="text" name="background_image"
                       value="<?php echo esc_html($file); ?>"/>
                <input id="upload_image_button" type="button" class="button-primary" value="Insert Json File"/>


            </div>
        </div>



            <br/>
            <b>-------------- Or ------------------------- </b>

            <br/>
            <label><input type="checkbox" id="google_json_text_enable" onClick="googleText(this)" name="google_json_text_enable"  ><span> Use Google Json ( check if you are using JSON) </span> </label>




         <div class="form-group" id="google_json_text" >
            <label class="control-label col-sm-12" for="pwd">Google Json Content:</label>
            <div class="col-sm-10">

                <textarea style="height:220px;" class="form-control" id="background_image_t" name="google_json_text"
                          placeholder="Json Content">  <?php echo $google_text;  ?></textarea>

            </div>
        </div>



        </div>
        </div>

        <div class="col-sm-6">

            <div class="responsive-video col-sm-6">
                <label class="control-label col-sm-12" for="pwd"> Need Help to setup? Generate Google Json File </label>

                <iframe width="560" height="315" src="https://www.youtube.com/embed/mB1q5tjs22I" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <a target="_blank"
               href="http://firstpageranker.com">
                More Details </a>
        </div>





    </div>




</div>

<div class="accordion">Bing, Ask, Ecosia and DuckDuckGo</div>
<div class="panel">



    <div class="row col-sm-12">


        <div class="col-sm-6">
            <div class="form-group">
               
                <div class="col-sm-offset-2 col-sm-10">
                <label class="control-label col-sm-12" for="pwd">Enable Bing Api:</label>
                    <div class="checkbox">

                        <?php if ($enablebing == 'on' || $enablebing ==1) { ?>
                            <label><input type="checkbox" id="check" name="enable_bing" checked></label>


                        <?php } else { ?>
                            <label><input type="checkbox" id="check" name="enable_bing"></label>

                        <?php } ?>


                    </div>
                </div>

            </div>


            <div class="form-group">
                <label class="control-label col-sm-2" for="pwd">Bing Api Key:</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="pwd" placeholder="" name="bingapi"
                           value="<?php echo esc_html($bingapi); ?>">
                </div>
            </div>

        </div>


        <div class="col-sm-6">
            <div class="responsive-video col-sm-6">
                <label class="control-label col-sm-12" for="pwd"> Generate Bing Api</label>

                <iframe width="560" height="315" src="https://www.youtube.com/embed/J30T9M1uKss" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <a target="_blank"
               href="http://firstpageranker.com">
                More Details </a>

        </div>




    </div>


</div>

<div class="accordion">Yandex</div>
<div class="panel">

<div class="row col-sm-12">

    <div class="col-sm-6">


        <div class="form-group">
            <label class="control-label col-sm-12" for="pwd">Enable Yandex Api:</label>
           

                <div class="checkbox">

                    <?php if ($enable_yandex == 'on' || $enable_yandex==1) { ?>
                        <label><input type="checkbox" id="check" name="enable_yandex" checked></label>


                    <?php } else { ?>
                        <label><input type="checkbox" id="check" name="enable_yandex"></label>

                    <?php } ?>


                </div>
           

        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">User Id:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd" placeholder="" name="yandex_UserId"
                       value="<?php echo esc_html($yandex_UserId); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Host Id:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd" placeholder="" name="yandex_HostId"
                       value="<?php echo esc_html($yandex_HostId); ?>">
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-12" for="pwd">Authorization key:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd" placeholder="" name="yandex_AuthKey"
                       value="<?php echo esc_html($yandex_AuthKey); ?>">
            </div>
        </div>

    </div>

    <div class="col-sm-6">

        <a target="_blank"
           href="https://firstpageranker.com/yandexApi.php">
            Generate Key </a>
        <br/>

        <a target="_blank"
           href=" https://yandex.com/dev/webmaster/">
            Yandex Webmaster </a>


            <div class="responsive-video col-sm-6">
                <label class="control-label col-sm-12" for="pwd"> Generate Yandex Api</label>

                <iframe width="560" height="315" src="https://www.youtube.com/embed/gJf9rMvH1Bg" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>

            <a target="_blank"
               href="http://firstpageranker.com">
                More Details </a>



    </div>

</div>

</div>



      <!--  <div class="form-group">
            <label class="control-label col-sm-2" for="pwd"></label>
            <b>Steps to Generate Google json key and Bing Api key <a href="https://firstpageranker.com/wpautoindex.php">
                    Click here</a></b>

        </div>

-->


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <br/>
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>
    </form>
    <hr/>
    <div class="row">
        <?php if ($enablebing == 'on' || $enablebing==1) { ?>
            <form method="post" class="col-sm-2">
                <input type="hidden" name="site" value="<?php echo esc_url_raw($site); ?>"/>
                <button type="submit" name="testbing" class="btn btn-primary">Test Bing</button>

            </form>

        <?php } ?>
        <?php if ($enablegoogle == 'on' || $enablegoogle==1) { ?>
            <form method="post" class="col-sm-2">
                <input type="hidden" name="site" value="<?php echo esc_url_raw($site); ?>"/>
                <button type="submit" name="testgoogle" class="btn btn-primary">Test Google</button>

            </form>
        <?php } ?>

        <?php if($enable_yandex == 'on' || $enable_yandex == 1) { ?>

            <form method="post" class="col-sm-2">
                <input type="hidden" name="site" value="<?php echo esc_url_raw($site); ?>"/>
                <button type="submit" name="yandex" class="btn btn-primary">Test Yandex</button>

            </form>

        <?php } ?>

        <?php if($enabledirectIndexing == 'on' || $enabledirectIndexing == 1) { ?>

            <form method="post" class="col-sm-4">
                <input type="hidden" name="site" value="<?php echo esc_url_raw($site); ?>"/>
                <button type="submit" name="direct" class="btn btn-primary">Test Refer Indexing</button>

            </form>

<?php } ?>

        


    </div>

    <hr/>



</div>


<div class="support">
    <center>
        <a href="mailto:wpautoindex@gmail.com">
            <img style="width:100px;" src="https://mpng.subpng.com/20180331/jge/kisspng-email-computer-icons-aol-mail-technical-support-email-5abf162aac53a2.9096086415224724907059.jpg"/>

        </a>
    </center>
</div>

<style>
    .support{
        position: fixed;
        bottom: 80px;
        float: left;

        right: 42px;
        border-radius: 100%;
        align-items: center;
    }
</style>

<script type="text/javascript">

    var $j = jQuery.noConflict();
    $j('#check').change(function (e) {
        $(e).val($j(this).is(':checked'));
        if ($j(e).val() == 1) {
            $j(e).val(0);
        } else {
            $j(e).val(1);
        }

    });


</script>

<?php
if ($google_text_enable == 'on' || $google_text_enable == 1){
?>

<script>



    var $j = jQuery.noConflict();
    $j(document).ready(function ($) {
        $j("#google_json_text_enable").prop("checked", true).trigger("change");

    });

</script>

<?php } ?>

<script type="text/javascript">




    var $j = jQuery.noConflict();

    $j(document).ready(function ($) {
        var mediaUploader;
        $j('#upload_image_button').click(function (e) {
            e.preventDefault();
            if (mediaUploader) {
                mediaUploader.open();
                return;
            }
            mediaUploader = wp.media.frames.file_frame = wp.media({
                title: 'Choose Json',
                button: {
                    text: 'Choose Json File'
                }, multiple: false
            });
            mediaUploader.on('select', function () {
                var attachment = mediaUploader.state().get('selection').first().toJSON();
                $j('#background_image').val(attachment.url);
            });
            mediaUploader.open();
        });
    });

</script>

  <script>
    var $j = jQuery.noConflict();

    $j(document).ready(function ($) {
    var icons = {
      header: "ui-icon-circle-arrow-e",
      activeHeader: "ui-icon-circle-arrow-s"
    };
    $j( "#accordion" ).accordion({
      icons: icons
    });
    $j( "#toggle" ).button().on( "click", function() {
      if ( $j( "#accordion" ).accordion( "option", "icons" ) ) {
        $j( "#accordion" ).accordion( "option", "icons", null );
      } else {
        $j( "#accordion" ).accordion( "option", "icons", icons );
      }
    });
  } );


    function googleText(th){

return true;
        var elm = $j(th);
        if (elm[0].checked) {
            $j("#google_json_text").show();
            $j('#google_file').hide();
        }else{
            $j("#google_json_text").hide();
            $j('#google_file').show();
        }
    }
  </script>

  <script>
var acc = document.getElementsByClassName("accordion");
var i;

for (i = 0; i < acc.length; i++) {
  acc[i].addEventListener("click", function() {
     this.classList.toggle("active");
    var panel = this.nextElementSibling;
    if (panel.style.display === "block") {
      panel.style.display = "none";
    } else {
      panel.style.display = "block";
    }
  });
}

</script>


</body>
</html>
