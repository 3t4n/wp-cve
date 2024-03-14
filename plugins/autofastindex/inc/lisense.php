<!DOCTYPE html>
<html lang="en">
<head>
    <title>Lisense</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php wp_enqueue_script("jquery"); ?>
    <?php
    wp_enqueue_style('bootstrap4', plugins_url('../assets/css/bootstrap.css', __FILE__));
    ?>

</head>
<body>


<?php
include_once('logs.php');
$configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
$configapi = json_decode($configapi);
$api_url = $configapi->license_url;


if (!file_exists(autoindex_upload . '/settings.json')) {

    $message = __('Please Register, ', 'sample-text-domain') . "  <a href='" . admin_url('admin.php?page=AutoFastindex') . "' >Click Here</a>";
    $class = 'notice notice-error';
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);

    exit;
}


if (isset($_POST['submit'])) {

    try{

        $email = sanitize_email($_POST['email']);
        $bingapi = sanitize_text_field($_POST['lisense_key']);
        $url = esc_url_raw(sanitize_text_field($_POST['url']));
    
    
    
        $postRequest = [
            "Lisense" => "3",
            "email" => $email,
            "lisense_key" => $bingapi,
            "url" => $url,
            "date" => date('yy-m-d')
        ];
    
    
        $args = array(
            'body' => $postRequest,
            'timeout' => '5',
            'redirection' => '5',
            'httpversion' => '1.0',
            'blocking' => true,
            'headers' => array(),
            'cookies' => array(),
        );
    
    
        $apiResponse = wp_remote_post($api_url, $args);
    
        $apiResponse = $apiResponse['body'];
    
    
        file_put_contents(autoindex_upload . "/lisense.json", '');
    
        file_put_contents(autoindex_upload . "/lisense.json", $apiResponse);
    
        $apiResponse = json_decode($apiResponse);
    
    
        if ($apiResponse->valid == 0) {
    
            $data['request_notify'] = $apiResponse->msg;
            file_put_contents(autoindex_upload. "/notification.json", wp_json_encode($data));
    
    
            echo "<div class='notice notice-error'>" . esc_attr($apiResponse->msg) . "</div>";
        } else {
            $data['request_notify'] = '';
            file_put_contents(autoindex_upload . "/notification.json", wp_json_encode($data));
    
    
            echo "<div class='notice notice-success'>Success</div>";
    
        }

    }catch(\Error $e){
        addLog($e,'lisense');

    }


}


?>

<?php
if (file_exists(autoindex_upload. '/lisense.json')) {
    $get = file_get_contents(autoindex_upload . '/lisense.json');
    $data = json_decode($get);
    $lisense_key = $data->lisense_key;
    $valid = $data->valid;

}

if (file_exists(autoindex_upload . '/settings.json')) {
    $get = file_get_contents(autoindex_upload . '/settings.json');
    $data = json_decode($get);
    $email = $data->email;
    $url = $data->url;

}
?>


<div class="container">
    <h2>AutoFast Indexing Lisense</h2>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
        <div class="form-group" style="display:none;">
            <label class="control-label col-sm-2" for="email">Email:</label>
            <div class="col-sm-10">
                <input type="email" class="form-control" name="email" id="email" style="display:none;"
                       value="<?php echo esc_html($email); ?>" placeholder="Enter email" name="email">
            </div>
        </div>


        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Lisense Key:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd" placeholder="" name="lisense_key"
                       value="<?php echo esc_html($lisense_key); ?>">
            </div>
        </div>


        <div class="form-group" style="display:none;">
            <label class="control-label col-sm-2" for="pwd">Site Url:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd" placeholder="" style="display:none;" name="url"
                       value="<?php echo esc_url_raw($url); ?>">
            </div>
        </div>

        <label class="control-label col-sm-12" for="pwd">Generate Lisense: <a target="_blank"
                                                                             href="http://firstpageranker.com/payment2.php?url=<?php echo esc_url_raw($url); ?>&&email=<?php echo esc_html($email); ?>">
                Click here </a></label>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-secondary">Submit</button>
            </div>
        </div>
    </form>

    <label class="control-label col-sm-2" for="pwd">Contact Us: <a target="_blank" href="http://firstpageranker.com">
            Click here </a></label>


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
</body>
</html>
