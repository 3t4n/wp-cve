<?php

/**
 * @author William Sergio Minossi
 * @copyright 2018
 */

global $antihacker_checkversion;

echo '<div id="containerright">';
   
$termina = get_transient('termina');
$antihacker_checkversion = trim($antihacker_checkversion);
// Debug
// $termina = false;
if (!$termina) {
     ob_start();
    // Debug
    // $antihacker_checkversion = '123';
    if(!empty($antihacker_checkversion)){
        $myarray = array(
            'checkversion' => $antihacker_checkversion
        );
    }
    else
    {
        $myarray = array();  
    }
    $url = "https://billminozzi.com/API/bill-api.php";
    $response = wp_remote_post($url, array(
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => array(),
        'body' => $myarray,
        'cookies' => array()
    ));
    if (is_wp_error($response)) {
        $error_message = $response->get_error_message();
        // echo "Something went wrong: $error_message";
        set_transient('termina', DAY_IN_SECONDS, DAY_IN_SECONDS);
        ob_end_clean();
        return;
    }
    $r = trim($response['body']);
    ob_end_clean();
    $r = json_decode($r, true);
    if($r == NULL or count($r) < 5){
        set_transient('termina', time(), DAY_IN_SECONDS);
        $title = '';
        $image = '';
        return;
    }
    else {
        $type = $r['type'];
        if ($type == 'news')
            $message = $r['message'];
        else
            $code = $r['code'];
        $title = $r['title'];
        $termina = $r['termina'];
        set_transient('termina', $termina, DAY_IN_SECONDS);
        $image = $r['image'];
        set_transient('title', $title, DAY_IN_SECONDS);
        $x = set_transient('type', $type, DAY_IN_SECONDS);
        set_transient('image', $image, DAY_IN_SECONDS);
        if ($type == 'news')
            set_transient('message', $message, DAY_IN_SECONDS);
        else
            set_transient('code', $code, DAY_IN_SECONDS);
    }
} else {
    // termina existe
    $type = get_transient('type');
    if ($type == 'news')
        $message = get_transient('message');
    else
        $code = get_transient('code');
    $title = get_transient('title');
    $termina = get_transient('termina');
    $image = get_transient('image');
}
// Debug
//$termina = time() + DAY_IN_SECONDS;
if (empty($antihacker_checkversion) or trim($type) == 'news' ) {
        // free always or news
        if ((strtotime($termina) > time()) and !empty($title) and  !empty($image)) {
            // show block...
            echo '<ul>';
            echo '<h2>' . esc_attr($title) . '</h2>';
            echo '<img src="' . esc_url(ANTIHACKERIMAGES) . '/' . $image . '" width="250" />';
            if ($type == 'news'){
                echo "<br>";
                echo '<BIG>' . esc_attr($message) . '</BIG>';
            }
            else
                echo '<center><BIG>CODE: ' . esc_attr($code) . '</BIG></center>';
            echo '</ul>';
        } // if termina..
}
if (empty($antihacker_checkversion)) {
        // Only Free
        echo '<ul>';
            $x = rand(1, 3);
            if($x == 1)
            $url = ANTIHACKERURL."assets/videos/ah11.mp4";
            if($x == 2)
             $url = ANTIHACKERURL."assets/videos/ah21.mp4";
            if($x == 3)
             $url = ANTIHACKERURL."assets/videos/ah31.mp4";
        ?>
        <video id="bill-banner-2" style="margin:-20px 0px -15px -12px; padding:0px;" width="400" height="230" muted>
            <source src="<?php  echo esc_url($url);?>" type="video/mp4">
        </video>
        <li><?php esc_attr_e("Go Premium and Get More:","antihacker");?></li>
        <li><?php esc_attr_e("Block Search For Plugin and Theme Vulnerabilities","antihacker");?></li>
        <li><?php esc_attr_e("Block False Googlebot","antihacker");?></li>
        <li><?php esc_attr_e("Block all traffic from Tor and Blank User Agent","antihacker");?></li>
        <li><?php esc_attr_e("Limit Bots Visits and Block HTTP Tools","antihacker");?></li>   
        <li><?php esc_attr_e("Dedicated Premium Support","antihacker");?></li>
        <li><?php esc_attr_e("More...","antihacker");?></li>
        <br />
        <a href="https://antihackerplugin.com/premium/" class="button button-medium button-primary"><?php esc_attr_e('Learn More', 'antihacker'); ?></a>
        <?php
        echo '</ul>';
}
// Always...
echo '<ul>';
$x = rand(1, 3);
if ($x < 2) {
    echo '<h2>'. esc_attr__("Like This Plugin?","antihacker").'</h2>';
    esc_attr_e('If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly.Thank you!', 'antihacker');
?>
    <br /><br />
    <a href="http://antihackerplugin.com/share/" class="button button-medium button-primary"><?php esc_attr_e('Rate or Share', 'antihacker'); ?></a>
<?php
} else {
    echo '<h2>'. esc_attr__("Please help us keep the plugin live & up-to-date","antihacker").'</h2>';
    esc_attr_e('If you use & enjoy Anti Hacker Plugin, please rate it on WordPress.org. It only takes a second and helps us keep the plugin live and maintained. Thank you!', 'antihacker');
?>
    <br /><br />
    <a href="https://wordpress.org/support/plugin/antihacker/reviews/#new-post" class="button button-medium button-primary"><?php esc_attr_e('Rate', 'antihacker'); ?></a>
<?php
}
echo '</ul>';
echo '</div>'; //containerright
?>