<?php
/**
 * Author: Vitaly Kukin
 * Date: 13.09.2018
 * Time: 8:42
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

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

echo $tmpl->get_content_template( DM_PATH . 'admin/templates/template-global-loader.php' );

echo $tmpl->get_content_template( DM_PATH . 'admin/templates/template-report-modal.php' );

?>

<div class="wrap">
    <div class="container-fluid" id="dm-container"></div>
    <div class="container-fluid" id="dm-container-result"></div>
</div>
