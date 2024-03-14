<?php
/**
 * Author: Vitaly Kukin
 * Date: 31.10.2018
 * Time: 17:37
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
	
	
	$tmpl->template( 'tmpl-not-found',
		$tmpl->get_content_template( DM_PATH . 'admin/templates/template-not-found.php' )
	);
	
	//echo $tmpl->get_content_template( DM_PATH . 'admin/templates/template-global-loader.php' );
	
	?>
	
	<div class="wrap">
		
		<h1>
			<?php _e( 'Not Available', 'dm' ) ?>
			<button class="ads-button btn btn-blue btn-sm ads-no" id="js-startCheck">Check Now</button>
		</h1>
		<p class="description">
			<?php _e( ' Products imported from DropshipMe which are no longer available on AliExpress.', 'dm' ) ?>
		</p>
		
		<div class="container-fluid" id="dm-not-container">
			<div class="row no-gutters">
				<div class="col-md-12">
				<?php echo $tmpl->progressbar( [ 'id' => 'checker-progress' ] ); ?>
				</div>
			</div>
		</div>
		<div class="container-fluid" id="dm-not-container-result"></div>
	</div>
	<?php
}