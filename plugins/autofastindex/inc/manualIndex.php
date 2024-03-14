<!DOCTYPE html>
<html lang="en">
<head>
    <title>Manual Indexing</title>
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
    include_once('bingtest.php');


    try{

        
    $permalink = esc_url_raw(sanitize_text_field($_POST['url']));
    $get = file_get_contents(autoindex_upload . '/settings.json');
    $data = json_decode($get);
    $site = $data->url;   
    $email = $data->email;

    $result = complete($site, $data, $email, $permalink);

    echo "<div class='notice notice-success'>Done, You can see status in log Page.</div>";
    
    }catch(\Error $e){
        addLog($e,'manualIndex');

    }


}


?>

<?php

if (!file_exists(autoindex_upload . '/settings.json')) {

    $message = __('Please Register, ', 'sample-text-domain') . "  <a href='" . admin_url('admin.php?page=AutoFastindex') . "' >Click Here</a>";
    $class = 'notice notice-error';
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);

    exit;
}


if (file_exists(autoindex_upload . '/settings.json')) {
    $get = file_get_contents(autoindex_upload . '/settings.json');
    $data = json_decode($get);
    $email = $data->email;
    $url = $data->url;

}
?>


<div class="container card">
    <center>
    <h2>AutoFast Manual Indexing</h2>
    <form class="form-horizontal" method="post" enctype="multipart/form-data">
      
        <div class="form-group">
            <label class="control-label col-sm-2" for="pwd">Enter your Url:</label>
            <div class="col-sm-10">
                <input type="text" class="form-control" id="pwd"  name="url"
                placeholder="<?php echo esc_html($url); ?>/examplepage">
            </div>
        </div>

    
        <br/>
         <br/>

        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            </div>
        </div>


    </form>
</center>

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
