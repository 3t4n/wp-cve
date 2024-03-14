<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.09.2018
 * Time: 8:41
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$status = \dm\dmApi::run();

if( ! isset( $status[ 'status' ] ) || $status[ 'status' ] == 'maintenance' ) {
	$h1          = isset( $status[ 'title' ] ) ? implode( '<br> ', $status[ 'title' ] ) : '';
	$description = isset( $status[ 'description' ] ) ? implode( '<br> ', $status[ 'description' ] ) : '';
	?>
	<div class="maintenance">
		<div class="inner-box text-center">
			<img src="<?php echo DM_URL . '/src/images/icons/refresh.svg' ?>" height="90">
			<h1><?php echo $h1 ?></h1>
			<p><?php echo $description ?></p>
		</div>
	</div>
	<?php
} else {
    
    $tmpl = new \dm\dmTemplate();
    
    $tmpl->template( 'tmpl-form',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-search-view.php' )
    );
    
    $tmpl->template( 'tmpl-search-result',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-search-result.php' )
    );
    
    $tmpl->template( 'tmpl-view-details',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-view-details.php' )
    );
    
    $tmpl->template( 'tmpl-reviews-list',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-reviews-list.php' )
    );
    
    $tmpl->template( 'tmpl-shipping-view',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-shipping-view.php' )
    );
    
    $tmpl->template( 'tmpl-analysis-table',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-analysis-table.php' )
    );
    
    $tmpl->template( 'tmpl-alert-extension',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-alert-extension.php' )
    );
    
    $tmpl->template( 'tmpl-alert-nologin',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-alert-nologin.php' )
    );
    
    $tmpl->template( 'tmpl-not-found',
        $tmpl->get_content_template( DM_PATH . 'admin/templates/template-not-found.php' )
    );
    
    echo $tmpl->get_content_template( DM_PATH . 'admin/templates/template-report-modal.php' );
    
    ?>

    <div class="wrap">
        <?php echo $tmpl->get_content_template( DM_PATH . 'admin/templates/template-global-loader.php' ); ?>
        <div class="container-fluid" id="dm-container"></div>
        <div class="container-fluid" id="dm-container-result"></div>
    </div>
    
    <?php
}