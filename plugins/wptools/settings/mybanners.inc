<?php
/**
 * @author William Sergio Minossi
 * @copyright 2022
 */
echo '<div id="containerright">';
$termina = get_transient('termina');
$wptools_checkversion = trim($wptools_checkversion);
// Debug
// $termina = false;
if (!$termina) {
     ob_start();
    // Debug
    // $wptools_checkversion = '123';
    if(!empty($wptools_checkversion)){
        $myarray = array(
            'checkversion' => $wptools_checkversion
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
if (empty($wptools_checkversion) or trim($type) == 'news' ) {
        // free always or news
        if ((strtotime($termina) > time()) and !empty($title) and  !empty($image)) {
            // show block...
            echo '<ul>';
            echo '<h2>' . esc_attr($title) . '</h2>';
            //  echo '<li><a href="http://wptoolsPlugin.com/help">OnLine Guide</a></li>';
            // echo '<li><a href="http://billminozzi.com/dove/">Support</a></li>';
            //  echo '<li><a href="http://siterightaway.net/troubleshooting/">Troubleshooting</a></li>';
            echo '<img src="' . esc_url(WPTOOLSIMAGES) . '/' . $image . '" width="250" />';
            if ($type == 'news'){
                echo "<br>";
                echo '<BIG>' . esc_attr($message) . '</BIG>';
            }
            else
                echo '<center><BIG>CODE: ' . esc_attr($code) . '</BIG></center>';
            echo '</ul>';
        } // if termina..
}
if (empty($wptools_checkversion)) {
        // Only Free
        echo '<ul>';
            $x = rand(1, 3);
            if($x == 1)
            $url = WPTOOLSURL."assets/videos/tools1.mp4";
            if($x == 2)
            $url = WPTOOLSURL."assets/videos/tools2.mp4";
            if($x == 3)
            $url = WPTOOLSURL."assets/videos/tools3.mp4";
        ?>
        <video id="bill-banner-2" style="margin:-20px 0px -15px -12px; padding:0px;" width="400" height="230" muted>
            <source src="<?php echo esc_url($url);?>" type="video/mp4">
        </video>
        <li><?php esc_attr_e("Lifetime license with premium enhancements: One-time payment of just $17.99!","wptools");?></li>
        <li><?php esc_attr_e("Take Your Site to the Next Level","wptools");?></li>   
 
        <li><?php esc_attr_e("Improve your WordPress site's performance","wptools");?></li> 
 
        <li><?php esc_attr_e("Dedicated Premium Support","wptools");?></li>
        <li><?php esc_attr_e("More...","wptools");?></li>
        <br />
        <a href="https://wptoolsplugin.com/premium/" class="button button-medium button-primary"><?php _e('Learn More', 'wptools'); ?></a>
        <?php
        echo '</ul>';
}
// Always...
echo '<ul>';
$x = rand(1, 3);
if ($x < 2) {
    echo '<h2>'. esc_attr__("Like This Plugin?","wptools").'</h2>';
    esc_attr_e('If you like this product, please write a few words about it. It will help other people find this useful plugin more quickly. Thank you!', 'wptools');
?>
    <br /><br />
    <a href="http://wptoolsplugin.com/share/" class="button button-medium button-primary"><?php esc_attr_e('Rate or Share', 'wptools'); ?></a>
<?php
} else {
    echo '<h2>'. esc_attr__("Please help us keep the plugin live & up-to-date!","wptools").'</h2>';
    esc_attr_e('If you use & enjoy WP Tools Plugin, please rate it on WordPress.org. It only takes a second and helps us keep the plugin live and maintained. Thank you!', 'wptools');
?>
    <br /><br />
    <a href="https://wordpress.org/support/plugin/wptools/reviews/#new-post" class="button button-medium button-primary"><?php _e('Rate', 'wptools'); ?></a>
<?php
}
echo '</ul>';
echo '</div>'; //containerright
?>