<?php
get_header();
?>
<div class="cluevo-content-area-container">
  <div id="primary" class="cluevo content-area">
    <main id="main" class="site-main">
      <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <header class="entry-header">
          <h1 class="entry-title"><?php echo esc_html(get_the_title()); ?></h1>
        </header><!-- .entry-header -->
        <div class="entry-content">
          <?php the_content(); ?>
        </div> <!-- entry content -->
        <?php
        if (current_user_can("administrator")) {
          global $post;

          $intModuleId = cluevo_get_module_id_from_metadata_id($post->ID);
          if (empty($intModuleId)) {
            cluevo_display_notice(__("Error", "cluevo"), __("Module not found", "cluevo"), "error");
          } else {
            $module = cluevo_get_module($intModuleId);
        ?>
            <?php if (has_action("cluevo_module_page_list_items")) { ?>
              <div class="cluevo-module-items">
                <h2><?php esc_html_e("Items", "cluevo"); ?></h2>
                <p><?php esc_html_e("This module is available through the following elements", "cluevo"); ?></p>
                <?php do_action("cluevo_module_page_list_items", $module); ?>
              </div>
            <?php } ?>
          <?php } ?>
        <?php } ?>
      </article>
    </main><!-- #main -->
  </div><!-- #primary -->
</div>

<?php
get_footer();
?>
