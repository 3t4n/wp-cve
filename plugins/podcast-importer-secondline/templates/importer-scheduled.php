<?php

$post_list = get_posts( [
  'post_type'    	 => PODCAST_IMPORTER_SECONDLINE_POST_TYPE_IMPORT,
  'posts_per_page' => 9999,
] );

?>
<div data-secondline-import-notification="info"><?php echo esc_html__('Scheduled imports are set for sync once every hour by default. (Can be modified if you are using the Pro version).', 'podcast-importer-secondline' );?></div>

<?php if( !empty( $post_list ) ) : ?>
  <table class="wp-list-table widefat fixed striped table-view-list posts">
    <thead>
      <tr>
        <th><?php echo esc_html__( 'Title', 'podcast-importer-secondline' );?></th>
        <th><?php echo esc_html__( 'Feed Link', 'podcast-importer-secondline' );?></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach( $post_list as $post ) : ?>
        <tr>
          <td><?php echo get_the_title( $post );?></td>
          <td><?php echo get_post_meta($post->ID, 'secondline_rss_feed', true);?></td>
          <td class="secondline_import_buttons_container">
            <?php do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_before_feed_item_operations', $post ); ?>
            <a href="tools.php?page=<?php echo PODCAST_IMPORTER_SECONDLINE_PREFIX; ?>&tab=edit&post_id=<?php echo esc_attr($post->ID); ?>" class="button button-primary">
              <?php echo esc_html__('Edit Import', 'podcast-importer-secondline' );?>
            </a>
            <a href="<?php echo get_delete_post_link( $post->ID, '', true );?>" class="button button-secondary button-delete">
              <?php echo esc_html__('Delete Import', 'podcast-importer-secondline' );?>
            </a>
            <?php do_action( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_after_feed_item_operations', $post ); ?>
          </td>
        </tr>
      <?php endforeach;?>
    </tbody>
  </table>
<?php endif; ?>