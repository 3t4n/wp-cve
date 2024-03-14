<?php
if (is_user_logged_in()) {
  wp_logout();
  wp_redirect(get_permalink());
  exit;
}
get_header();
?>
<div class="cluevo-content-area-container">
  <div id="primary" class="cluevo content-area">
    <main id="main" class="site-main">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header">
        <h1 class="entry-title"><?php echo esc_html(get_the_title()); ?></h1>
      </header><!-- .entry-header -->
      <div class="entry-content cluevo">
        <p><?php esc_html_e("You have been signed out.", "cluevo"); ?></p>
        </div> <!-- entry content -->
      </article>
    </main><!-- #main -->
  </div><!-- #primary -->
</div>
<?php
get_footer();
?>
