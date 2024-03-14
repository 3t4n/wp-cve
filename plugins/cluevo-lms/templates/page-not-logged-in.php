<?php
get_header();
global $wp;
$referrer = home_url($wp->request);
?>
<div class="cluevo-content-area-container">
  <div id="primary" class="cluevo content-area">
    <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <header class="entry-header learner-name">
    <h1 class="entry-title"><?php esc_html_e("Oooops! - Access Denied", "cluevo"); ?></h1>
    </header><!-- .entry-header -->
    <div class="entry-content">
      <p class="cluevo error-message"><?php esc_html_e("You have to be logged in to access learning content.", "cluevo"); ?></p>
      <?php cluevo_display_template('part-login-form'); ?>
    </div> <!-- entry content -->
    </article>
    </main><!-- #main -->
  </div><!-- #primary -->
</div>
<?php
get_footer();
?>
