<h2 class="widget-title">
    <?php _e($title, PLUGIN_LOCALE); ?>
</h2>
<?php
if( is_home() ){
        $link = urlencode( get_home_url() );
        $title = urlencode( get_bloginfo( 'title' ) );
    }elseif( is_single() || is_page() ){
        $link = urlencode( get_permalink() );
        $title = urlencode( get_the_title() );
    }
?>
<div id="social-wrapper">
    <a class="fb ms-widget" href="http://www.facebook.com/sharer.php?u=<?php echo $link ?>" target="_blank">
        <i class="fa fa-facebook"></i>
    </a>

    <a class="gp ms-widget" href="https://plus.google.com/share?url=<?php echo $link ?>" target="_blank">
        <i class="fa fa-google-plus"></i>
    </a>

    <a class="ri ms-widget"
       href="http://reddit.com/submit?url=<?php echo $link ?>&title=<?php echo $title ?>"
       target="_blank">
        <i class="fa fa-reddit"></i>
    </a>

    <a class="tw ms-widget" href="https://twitter.com/share?url=<?php echo $link ?>" target="_blank">
        <i class="fa fa-twitter"></i>
    </a>

    <a class="li ms-widget" href="http://www.linkedin.com/shareArticle?url=<?php echo $link ?>" target="_blank">
        <i class="fa fa-linkedin"></i>
    </a>
</div>