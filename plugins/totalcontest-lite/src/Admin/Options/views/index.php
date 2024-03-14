<div id="totalcontest-options" class="wrap totalcontest-page" ng-app="options" ng-controller="OptionsCtrl as $ctrl">
    <h1 class="totalcontest-page-title"><?php  esc_html_e( 'Options', 'totalcontest' ); ?></h1>

    <div class="totalcontest-tabs-container has-tabs totalcontest-settings totalcontest-options">
        <div class="totalcontest-tabs">
	        <?php $firstTab = key( $tabs ) ?>
	        <?php foreach ( $tabs as $tabId => $tab ): ?>
                <div class="totalcontest-tabs-item <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab-switch="options><?php echo esc_attr( $tabId ); ?>">
                    <span class="dashicons dashicons-<?php echo esc_attr( $tab['icon'] ); ?>"></span>
			        <?php echo esc_html( $tab['label'] ); ?>
                </div>
	        <?php endforeach; ?>
        </div>
        <div class="totalcontest-tabs-content">
		    <?php foreach ( $tabs as $tabId => $tab ): ?>
                <div class="totalcontest-tab-content <?php echo $tabId == $firstTab ? 'active' : ''; ?>" tab="options><?php echo esc_attr( $tabId ); ?>">
				    <?php
				    /**
				     * Fires before options tab content.
				     *
				     * @since 2.0.0
				     */
				    do_action( "totalcontest/actions/before/admin/options/tabs/content/{$tabId}" );

				    $path = empty( $tab['file'] ) ? __DIR__ . "/tabs/{$tabId}.php" : $tab['file'];
				    if ( file_exists( $path ) ):
					    include_once $path;
				    endif;

				    /**
				     * Fires after options tab content.
				     *
				     * @since 2.0.0
				     */
				    do_action( "totalcontest/actions/after/admin/options/tabs/content/{$tabId}" );
				    ?>
                </div>
		    <?php endforeach; ?>
        </div>
    </div>
</div>
