<?php
  $current_tab = ( $_GET['tab'] ?? null );
  $tabs = [
    'import'  => [
      'title'     => __( "Import Feed", 'podcast-importer-secondline' ),
      'template'  => 'importer-form.php'
    ],
    'scheduled-list'  => [
      'title'     => __( "Scheduled Imports", 'podcast-importer-secondline' ),
      'template'  => 'importer-scheduled.php'
    ],
  ];

  if( isset( $_GET[ 'post_id' ] ) && $current_tab === 'edit' )
    $tabs[ 'edit' ] = [
      'title'     => sprintf( __( "Edit Feed %s", 'podcast-importer-secondline' ), get_the_title( intval( $_GET[ 'post_id' ] ) ) ),
      'template'  => 'importer-form.php'
    ];

  if( !podcast_importer_secondline_has_premium_theme() && !defined( 'PODCAST_IMPORTER_PRO_SECONDLINE' ) )
    $tabs[ 'upgrade' ] = [
      'title'   => __( "Upgrade", 'podcast-importer-secondline' ),
      'template'  => 'upgrade-plugin.php'
    ];    

  $tabs = apply_filters( PODCAST_IMPORTER_SECONDLINE_ALIAS . '_tools_tabs', $tabs );

  if( !isset( $tabs[ $current_tab ] ) )
    $current_tab = array_key_first( $tabs );

?><div class="wrap podcast-importer-secondline">
  <h1>
    <span><?php echo esc_html__('Import a Podcast', 'podcast-importer-secondline' );?></span>
    <?php if( !podcast_importer_secondline_has_premium_theme() ) :?>
    <a href="https://secondlinethemes.com/?utm_source=import-title-notice" target="_blank" class="tagline-powered-by">
      <?php echo esc_html__('Powered by SecondLineThemes', 'podcast-importer-secondline' );?>
    </a>
    <?php endif;?>
  </h1>

  <nav class="nav-tab-wrapper">
    <?php foreach( $tabs as $tab_alias => $tab_information ) : ?>
      <a href="tools.php?page=<?php echo PODCAST_IMPORTER_SECONDLINE_PREFIX; ?>&tab=<?php echo esc_attr($tab_alias) . ( $tab_alias === 'edit' ? '&post_id=' . intval( $_GET[ 'post_id' ] ) : '' ); ?>"
         class="nav-tab<?php echo $tab_alias === $current_tab ? ' nav-tab-active' : '' ?>">
        <?php echo esc_html( $tab_information[ 'title' ] ); ?>
      </a>
    <?php endforeach; ?>
  </nav>

  <?php
    if( isset( $tabs[ $current_tab ][ 'template' ] ) )
      podcast_importer_secondline_load_template( $tabs[ $current_tab ][ 'template' ] );
    else if( isset( $tabs[ $current_tab ][ 'content' ] ) )
      echo ($tabs[ $current_tab ][ 'content' ]);
  ?>
</div>