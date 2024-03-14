<?php
get_header();

global $post;
$levels = CLUEVO_LEARNING_STRUCTURE_LEVELS;
$itemId = cluevo_get_item_id_from_metadata_id($post->ID);
$item = null;
if (!empty($itemId)) {
  $item = cluevo_turbo_get_tree_item($itemId);
}

if (!empty($item)) {
?>
  <div class="cluevo-content-area-container">
    <div id="primary" class="cluevo content-area">
      <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
          <header class="entry-header">
            <h1 class="entry-title"><?php echo esc_html(get_the_title()); ?></h1>
          </header><!-- .entry-header -->
          <div class="entry-content">
            <?php
            if (!empty($item)) {
              if (empty($item->type)) {
                cluevo_display_template('cluevo-tree-item');
              } else {
                if (!empty($item->module) && $item->module > 0) {
                  $template = "cluevo-tree-item-module";
                } else {
                  $template = "cluevo-tree-item-" . $item->type;
                }
                if (file_exists(cluevo_get_conf_const('CLUEVO_PLUGIN_PATH') . "/templates/$template.php")) {
                  cluevo_display_template($template);
                } else {
                  cluevo_display_template('cluevo-tree-item');
                }
              }
            } else {
              cluevo_display_template('cluevo-course-index');
            }
            ?>
          </div> <!-- entry content -->
        </article>
      </main><!-- #main -->
    </div><!-- #primary -->
  </div>

<?php
}
get_footer();
?>
