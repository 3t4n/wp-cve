<?php
/**
 * Author: Vitaly Kukin
 * Date: 19.09.2018
 * Time: 8:47
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>
	<div class="py-3 no-gutters">
				<h3 class="import_settings_title">Import settings</h3>
		<div class="col">
			<?php
			echo $tmpl->checkbox( [
					'name'  => 'create_cat',
					'value' => 1,
					'label' => __( 'Create categories from DropshipMe', 'dm' )
				] );
			?>
		</div>
        <div class="col cat_status">
			<?php
			echo $tmpl->select( [
                'value'  => '',
                'values' => [ '0' => __( 'Use DropshipMe structure', 'dm'), '1' => __( 'Create child categories', 'dm'), '2' => __( 'Create parent categories', 'dm' )  ],
                'id'     => 'cat_status',
                'name'   => 'cat_status',
                'label'  => __( '', 'dm' )
                ] );
			?>
		</div>
		<div class="col">
			<?php
			echo $tmpl->checkbox( [
					'name'  => 'attributes',
					'value' => 1,
					'label' => __( 'Remove item specifics', 'dm' )
				] );
			?>
		</div>
		<div class="col">
			<?php
			echo $tmpl->checkbox( [
					'name'  => 'publish',
					'value' => 1,
					'label' => __( 'Publish products', 'dm' )
				] );
			?>
		</div>
		<div class="col">
			<?php
			echo $tmpl->checkbox( [
					'name'  => 'recommended_price',
					'value' => 1,
					'label' => __( 'Import with recommended prices', 'dm' )
				] )
			?>
		</div>
	</div>