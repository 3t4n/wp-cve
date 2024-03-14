<?php get_header();?>
<div class="archive-list">
    <h2 class="err404"><?php _e('404 Page not found!', 'wpcom');?></h2>
    <p class="err404-desc"><?php _e("We're sorry, but the page you're looking for may have been moved or deleted.", 'wpcom');?> <a href="<?php bloginfo('url');?>"><br><?php _e('Go home', 'wpcom');?></a></p>
</div>
<?php get_footer();?>