<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8"/>
    <title></title>
    <?php wp_enqueue_script("jquery"); ?>
    <?php
    wp_enqueue_style('bootstrap4', plugins_url('../assets/css/bootstrap.css', __FILE__));
    wp_enqueue_style('datatable', plugins_url('../assets/css/DataTable.css', __FILE__));
    wp_enqueue_script('datatable', plugins_url('../assets/js/DataTable.js', __FILE__));
    ?>


</head>

<body>

<?php

$configapi = file_get_contents(plugin_dir_path(__FILE__) . '../api/config.json');
$configapi = json_decode($configapi);
$api_url = $configapi->notice;



if (!file_exists(autoindex_upload . '/settings.json')) {

    $message = __('Please Register, ', 'sample-text-domain') . "  <a href='" . admin_url('admin.php?page=AutoFastindex') . "' >Click Here</a>";
    $class = 'notice notice-error';
    printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), $message);

    exit;
}


?>

<br/>
<table id="example" class="display" cellspacing="0" width="100%">
    <thead>
    <tr>

        <th>id</th>
        <th>Message</th>
        <th>Date</th>
    </tr>
    </thead>


    <tbody>

    <?php

    $postRequest = [
        "notice" => 1

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


    $i = 1;
    foreach (json_decode($apiResponse) as $d) {


        ?>
        <tr>


            <th><?php echo esc_html($i); ?></th>
            <th><?php echo esc_html($d->info); ?></th>
            <th><?php echo esc_html($d->date); ?></th>
        </tr>

        <?php $i++;
    } ?>

    </tbody>
</table>
<script>
    var $j = jQuery.noConflict();
    $j(document).ready(function () {
        $j('#example').DataTable();
    });
</script>

</body>

</html>
