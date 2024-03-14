<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>
<div class="mobile_detach">
	<div class="row box-shadow mt-3 py-4 import-settings no-gutters">
		<div class="col-12 col-md-6 col-lg-3">
			<?php
			echo $tmpl->switcher( [
					'name'  => 'create_cat_e',
					'id'    => 'create_cat_e',
					'value' => 1,
					'label' => __( 'Create categories from DropshipMe', 'dm' )
				] );
			?>
		</div>
        <div class="col cat_status_e">
            <?php
            echo $tmpl->select( [
                'value'  => '',
                'values' => [ '0' => __( 'Use DropshipMe structure', 'dm'), '1' => __( 'Create child categories', 'dm'), '2' => __( 'Create parent categories', 'dm' )  ],
                'id'     => 'cat_status_e',
                'name'   => 'cat_status_e',
                'label'  => __( '', 'dm' )
            ] );
            ?>
        </div>
		<div class="col-12 col-md-6 col-lg-3">
			<?php
			echo $tmpl->switcher( [
					'name'  => 'attributes_e',
					'id'    => 'attributes_e',
					'value' => 1,
					'label' => __( 'Remove item specifics', 'dm' )
				] );
			?>
		</div>
		<div class="col-12 col-md-6 col-lg-3">
			<?php
			echo $tmpl->switcher( [
					'name'  => 'publish_e',
					'id'    => 'publish_e',
					'value' => 1,
					'label' => __( 'Publish products', 'dm' )
				] );
			?>
		</div>
		<div class="col col-md-6 col-lg-3">
			<?php
			echo $tmpl->switcher( [
					'name'  => 'recommended_price_e',
					'id'    => 'recommended_price_e',
					'value' => 1,
					'label' => __( 'Import with recommended prices', 'dm' )
				] )
			?>
		</div>
	</div>
</div>
