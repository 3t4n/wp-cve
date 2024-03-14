<?php
get_header();
?>
<div id="primary" class="cluevo content-area primary">
  <main id="main" class="site-main">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <header class="entry-header learner-name">
        <h1 class="entry-title"><?php esc_html_e("User", "cluevo"); ?></h1>
      </header><!-- .entry-header -->
      <?php
      cluevo_display_template("content-user-profile");
      ?>
    </article>
  </main>
</div>
<?php
get_footer();
?>
