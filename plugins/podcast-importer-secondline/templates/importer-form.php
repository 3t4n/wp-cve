<?php
  $post_id = ( isset( $_GET[ 'post_id' ] ) ? intval( $_GET[ 'post_id' ] ) : null );
  $render_data_list = PodcastImporterSecondLine\Helper\FeedForm::get_for_render( $post_id );
  $has_any_advanced = false;// Will be changed during first loop.
?>
<div class="main-container-secondline">
  <form method="POST" class="secondline_import_form">
    <div class="secondline_import_notifications" style="display:none;"></div>
    <div class="secondline_import_wrapper">
      <?php if( $post_id !== null ) : ?>
        <input type="hidden" name="post_id" value="<?php echo esc_attr( $post_id ) ?>">
      <?php endif; ?>
      <?php foreach( $render_data_list as $render_data ) : ?>
        <?php if( isset( $render_data[ 'is_advanced' ] ) && $render_data[ 'is_advanced' ] ) {
                $has_any_advanced = true;
                continue;
              } ?>
        <?php podcast_importer_secondline_load_template( '_form-field.php', [ 'data' => $render_data ] ); ?>
      <?php endforeach; ?>

      <?php if( $has_any_advanced ) : ?>
        <div class="secondline_import_advanced_settings_container">
          <h3 class="secondline_import_advanced_settings_toggle"><i></i><?php echo esc_html__('Advanced Options', 'podcast-importer-secondline' ); ?></h3>
          <div class="secondline_import_advanced_settings">
            <?php foreach( $render_data_list as $render_data ) : ?>
              <?php if( !isset( $render_data[ 'is_advanced' ] ) || !$render_data[ 'is_advanced' ] ) continue; ?>

              <?php podcast_importer_secondline_load_template( '_form-field.php', [ 'data' => $render_data ] ); ?>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endif; ?>

      <?php if( $post_id !== null ) : ?>
        <button class="button button-primary secondline_import_form_submit"><?php echo esc_html__( "Update", 'podcast-importer-secondline' ); ?></button>
      <?php else : ?>
        <button class="button button-primary secondline_import_form_submit"><?php echo esc_html__( "Import", 'podcast-importer-secondline' ); ?></button>
      <?php endif; ?>
    </div>
  </form>
  <?php if( !podcast_importer_secondline_has_premium_theme() && !defined( 'PODCAST_IMPORTER_PRO_SECONDLINE' ) ) : ?>    
    <div class="upgrade-cta">
      <h2><?php echo esc_html__( "Podcast Importer Pro", 'podcast-importer-secondline' ); ?></h2>
      <h5><?php echo esc_html__( "Upgrade to Pro and get additional features:", 'podcast-importer-secondline' ); ?></h5>
      <ul>
        <li><?php echo esc_html__( "Unlimited scheduled imports for podcasts/shows.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Import to any Custom Post Type or Custom Taxonomy.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Set specific import interval times.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Import transcripts from RSS feed.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Import audio player to custom fields.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Import tags and categories from the feeds.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Force a re-sync on all existing episodes (to update metadata)", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Set a global featured image to all imported episodes.", 'podcast-importer-secondline' ); ?></li>
        <li><?php echo esc_html__( "Manual 'Sync' button to sync on demand.", 'podcast-importer-secondline' ); ?></li>
      </ul>
      <a href="https://secondlinethemes.com/podcast-importer-pro">
        <button class="button button-primary secondline_upgrade_cta">
          <?php echo esc_html__( "Upgrade", 'podcast-importer-secondline' ); ?>
        </button>
      </a>
    </div>
  <?php endif;?>
</div>  