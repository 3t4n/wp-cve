<?php
get_header();
global $post;
$competence = null;
$area = cluevo_get_the_competence_area();
?>
<div class="cluevo-content-area-container cluevo-competence-page">
  <div id="primary" class="cluevo content-area">
    <main id="main" class="site-main">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <h1 class="entry-title"><?php echo esc_html(get_the_title()); ?></h1>
        </header><!-- .entry-header -->
        <div class="entry-content">
          <?php the_content(); ?>
        </div> <!-- entry content -->
        <div class="cluevo-competence-score-container">
          <p><?php echo sprintf(esc_html__("Your score for this competence area is %s%%", "cluevo"), round($area->score * 100, 2)); ?></p>
          <?php cluevo_display_progress_bar($area->score, 1); ?>
        </div>
        <?php if (!empty($area->competences)) { ?>
          <p><?php esc_html_e("This competence area covers the following competences. Complete modules within each competence to increase your score in this area.", "cluevo"); ?></p>
          <div class="cluevo-user-competences-container cluevo-content-list">
            <?php foreach ($area->competences as $comp) { ?>
              <?php do_action("cluevo_display_competence_tile", $comp); ?>
            <?php }  ?>
          <?php } else { ?>
            <?php cluevo_display_notice(__("Info", "cluevo"), __("This competence does not have any modules assigned", "cluevo")); ?>
          <?php } ?>
      </article>
    </main><!-- #main -->
  </div><!-- #primary -->
</div>
</div>

<?php
get_footer();
?>
