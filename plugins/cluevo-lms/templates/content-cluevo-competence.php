<?php
get_header();
global $post;
$competence = cluevo_get_the_competence();
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
        <?php if (!empty($competence->score->value)) { ?>
          <div class="cluevo-competence-score-container">
            <p><?php echo sprintf(esc_html__("Your score for this competence is %d%%", "cluevo"), $competence->score->value * 100); ?></p>
            <?php cluevo_display_progress_bar($competence->score->value, $competence->total_coverage); ?>
          </div>
        <?php } ?>
        <?php if (!empty($competence->modules)) { ?>
          <p><?php esc_html_e("The following modules are available to earn score for this competence", "cluevo"); ?></p>
          <div class="cluevo-competence-items">
            <?php foreach ($competence->modules as $module) { ?>
              <?php if (empty($module->items)) continue; // don't display modules that have no available items 
              ?>
              <div class="cluevo-competence-item">
                <h2><?php echo esc_html($module->module_name); ?></h2>
                <div class="cluevo-competence-item-data">
                  <p><?php echo sprintf(esc_html__("This module has a competence coverage of %s%%", "cluevo"), ($module->competence_coverage * 100)); ?></p>
                  <?php if (!empty($competence->score->modules)) { ?>
                    <?php foreach ($competence->score->modules as $m) { ?>
                      <?php if ($m->id === $module->module_id) { ?>
                        <p><?php echo sprintf(esc_html__("You currently have a score of %s%% out of a possible %s%% via this module", "cluevo"), $m->score * 100, $m->coverage * 100); ?></p>
                        <?php cluevo_display_progress_bar($m->score, $m->coverage); ?>
                      <?php } ?>
                    <?php } ?>
                  <?php } ?>
                </div>
                <p><?php esc_html_e("This module is available via the following elements", "cluevo"); ?></p>
                <?php if (!empty($module->items)) { ?>
                  <?php foreach ($module->items as $item) { ?>
                    <?php if (!empty($item) && $item->access_level > 0) { ?>
                      <div class="cluevo-competence-module-item">
                        <?php
                        $img = null;
                        if (!empty($meta)) {
                          if (has_post_thumbnail($meta->ID))
                            $img = get_the_post_thumbnail($meta->ID);
                        }

                        if (empty($img)) {
                          $imgDir = cluevo_get_conf_const('CLUEVO_IMAGE_URL');
                          $img = '<img src="' . "$imgDir/lms-content-placeholder.jpg" . '" alt="" />';
                        }
                        echo $img;
                        ?>
                        <div class="cluevo-competence-module-item-data">
                          <a href="<?php echo esc_url(cluevo_get_parent_permalink($item)); ?>"><?php echo esc_html($item->name); ?></a>
                          <div class="cluevo-path">
                            <?php if (!empty($item->path->string)) { ?>
                              <?php foreach ($item->path->string as $part) { ?>
                                <div class="cluevo-path-part"><?php echo esc_html($part); ?></div>
                              <?php } ?>
                            <?php } else {
                              echo "&nbsp;";
                            } ?>
                          </div>
                          <div class="cluevo-competence-progress-container">
                            <?php cluevo_display_progress_bar(count($item->completed_children), count($item->children)); ?>
                          </div>
                          <?php if ($item->access) { ?>
                            <div class="cluevo-module-start-button cluevo-competence-module-start-button">
                              <?php do_action("cluevo_display_module_start_link", $item); ?>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    <?php } ?>
                  <?php } ?>
                <?php } else { ?>
                  <?php cluevo_display_notice(__("Info", "cluevo"), __("This module is currently not available through any learning content items that you have access to.", "cluevo")); ?>
                <?php } ?>
              </div>
            <?php } ?>
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
