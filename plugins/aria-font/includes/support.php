<?php

$news_response = wp_remote_post("https://api.ariawp.com/news/json/index.php", [
        'method' => 'POST',
        'timeout' => 5,
        'redirection' => 5,
        'httpversion' => '1.0',
        'blocking' => true,
        'headers' => [],
        'body' => ['url' => urlencode(get_home_url())],
        'cookies' => []
    ]
);

?>

<div class="wrap" id="support">
    <h2><?php echo _e("Support", "aria-font"); ?></h2>
    <main>
        <section class="about-section">
            <h2><?php _e("Aria Wordpress", "aria-font"); ?></h2>
            <img src="<?php echo ARIAFONTPLUGINURL . "assets/images/logo.png"; ?>" />
            <p><?php _e("Thank you for choosing our plugin to add new fonts to your wordpress website!", "aria-font"); ?></p>
            <p><?php _e("We are a team, that develops plugins and themes for wordpress.", "aria-font"); ?></p>
        </section>
        
        <section class="feed-section">
            <div class="feed-section-inner">
                <h2><?php _e("Latest news", "aria-font"); ?></h2>
                <div class="all-news">
                    <?php
                        $news_loaded = false;

                        if(!is_wp_error($news_response))
                        {
                            $news_body = wp_remote_retrieve_body($news_response);
                            $news_body = json_decode($news_body, true);

                            foreach($news_body["news"] as $news)
                            {
                                ?>
                                    <div class="news">
                                        <div class="news-<?php echo $news["type"]; ?>">
                                            <h1><?php echo $news["title"]; ?></h1>
                                            <p><?php echo $news["body"]; ?></p>
                                        </div>
                                    </div>
                                <?php

                                $news_loaded = true;
                            }
                        }
                        
                        if($news_loaded == false)
                        {
                            ?>
                                <li><?php _e("We are currently unable to load news.", "aria-font"); ?></li>
                            <?php
                        }
                    ?>
                </div>
            </div>
        </section>
    </main>

    <main>
        <section class="community-section">
            <h2><?php _e("Aria Wordpress Community", "aria-font"); ?></h2>
            <p><?php _e("We are glad to let you know that you can ask your questions or report any problem of this plugin or any another things that you want to know about wordpress, in our special community", "aria-font"); ?></p>
            <p><?php echo sprintf(__("You can access to <a href='%s'>Aria Wordpress Community</a> by clicking on it.", "aria-font"), "https://community.ariawp.com/"); ?></p>
        </section>
    </main>
    
    <div>
        <p>© 2023 <a href="https://ariawp.com/"><?php _e("Aria Wordpress", "aria-font"); ?></a>, <?php _e("All rights reserved", "aria-font"); ?></p>
    </div>
</div>

<?php
?>
