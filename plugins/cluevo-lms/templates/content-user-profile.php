<?php
$user = cluevo_get_the_lms_user();
if (!empty($user) && !empty($user->ID)) {
  $displayMode = strtolower(get_option("cluevo-modules-display-mode", "Iframe"));
  $areas = cluevo_get_users_competence_areas();
  $comps = cluevo_get_users_competences();
  get_header();
?>
  <div class="cluevo-content-area-container">
    <div id="primary" class="cluevo content-area">
      <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <?php if (!empty($user)) { ?>
            <header class="cluevo-learner-name">
              <h1 class="entry-title"><?php echo esc_html($user->display_name); ?></h1>
              <?php cluevo_display_template('part-exp-title'); ?>
            </header><!-- .entry-header -->
            <div class="entry-content">
              <p><?php echo esc_html__("This page shows you a summary of your learning progress.", "cluevo"); ?></p>
              <?php do_action('cluevo_user_page_start_content'); ?>
              <?php if (!empty($areas)) { ?>
                <section class="cluevo-user-page-section">
                  <h2 class="cluevo-section-headline"><?php echo esc_html__("Competence Areas", "cluevo"); ?></h2>
                  <div class="cluevo-user-competences-container cluevo-content-list">
                    <?php foreach ($areas as $a) { ?>
                      <?php do_action("cluevo_display_competence_area_tile", $a); ?>
                    <?php } ?>
                  </div>
                </section>
              <?php } ?>
              <?php if (!empty($comps)) { ?>
                <section class="cluevo-user-page-section">
                  <h2 class="cluevo-section-headline"><?php echo esc_html__("Competences", "cluevo"); ?></h2>
                  <div class="cluevo-user-competences-container cluevo-content-list cluevo-content-list-style-row">
                    <?php foreach ($comps as $c) { ?>
                      <?php do_action("cluevo_display_user_competence_tile", $c); ?>
                    <?php } ?>
                  </div>
                </section>
              <?php } ?>
              <section class="cluevo-user-page-section">
                <h2 class="cluevo-section-headline"><?php esc_html_e('Courses', "cluevo"); ?></h2>
                <div class="cluevo-content-list">
                  <?php
                  while (cluevo_have_lms_items()) {
                    cluevo_the_lms_item();
                    cluevo_display_template('part-tree-item');
                  }
                  ?>
                </div>
              </section>
            <?php } ?>
            </div> <!-- entry content -->
        </article>
      </main><!-- #main -->
    </div><!-- #primary -->
  </div>
<?php } else { ?>
<?php
  cluevo_display_notice_html(__("Error", "cluevo"), __("You must be logged in to view this page. Click here to log in: ", "cluevo") . '<a href="' . wp_login_url(get_permalink()) . '">' . __("Login", "cluevo") . '</a>');
}
