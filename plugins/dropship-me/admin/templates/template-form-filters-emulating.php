<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>
<div class="import-settings-detach">
    <div class="row pt-3 pb-2 pb-lg-0">
        <div class="col">
            <div class="pl-1 tab-nav-elements btn-friendly" id="range-filters">
				<div class="range-item d-inline-flex pb-2 pb-md-0">
					<label for="originalPriceFrom"><?php _e( 'Supplier price', 'dm' ) ?>:</label>
                    <?php
                        echo $tmpl->text( [
                            'value' => '',
                            'id'    => 'originalPriceFrom_e',
                            'name'  => 'originalPriceFrom',
                            'placeholder' =>  __( 'min', 'dm' )
                        ] ) . ' <label for="originalPriceTo">&ndash;</label> ' . $tmpl->text( [
                            'value' => '',
                            'id'    => 'originalPriceTo_e',
                            'name'  => 'originalPriceTo',
                            'placeholder' =>  __( 'max', 'dm' )
                        ] ) 
                    ?>
                </div>
                <div class="range-item d-inline-flex pb-2 pb-md-0 mobile_volume">
					<label for="volumeFrom"><?php _e( 'Orders', 'dm' ) ?>:</label>
		            <?php
		            echo $tmpl->text( [
				            'value' => '',
				            'id'    => 'volumeFrom_e',
				            'name'  => 'volumeFrom',
				            'placeholder' => __( 'min', 'dm' )
			            ] ) . ' <label for="volumeTo">&ndash;</label> ' . $tmpl->text( [
				            'value' => '',
				            'id'    => 'volumeTo_e',
				            'name'  => 'volumeTo',
				            'placeholder' => __( 'max', 'dm' )
			            ] ) . $tmpl->button( [
				            'form_group' => 'form_group',
							'id'	=>'orders-btn',
				            'class' => 'btn btn-blue ads-no js-apply-range',
				            'value' => __( 'Apply', 'dm' )
			            ] )
		            ?>
                </div>
				<a href="javascript:;" class="d-none d-lg-inline-block clear_all pl-2"><?php _e( 'Clear all', 'dm' ) ?></a>
            </div>
        </div>
    </div>
    <div class="row pt-3 pb-2 py-md-2">
        <div class="col">
            <div class="pl-2 tab-nav-elements btn-friendly" id="more-filters">
                <?php
                echo $tmpl->select( [
                        'value'  => '',
                        'values' => [ '' => __( 'Any', 'dm' ), 'CN' => __( 'China', 'dm' ), 'US' => __( 'United States', 'dm' ), 'EU' => __( 'Europe', 'dm' ) ],
                        'id'     => 'warehouse_e',
                        'name'   => 'warehouse',
                        'label'  => __( 'Shipping from:', 'dm' )
                    ] ) . $tmpl->select( [
                        'value'  => 'US',
                        'icon'   => true,
                        'values' => dm_list_countries(),
                        'id'     => 'to_e',
                        'name'   => 'to',
                        'label'  => __( 'Shipping to:', 'dm' )
                    ] )  . $tmpl->select( [
                        'value'  => '9999',
                        'values' => dm_list_company(),
                        'id'     => 'company_e',
                        'name'   => 'company',
                        'label'  => __( 'Shipping method:', 'dm' )
                    ] ) . $tmpl->checkbox( [
		                'value' => '1',
		                'id'    => 'free_e',
		                'name'  => 'free',
		                'help'  => __( 'Free shipping', 'dm' )
	                ] )
                ?>
            </div>
        </div>
    </div>
    <div class="d-block d-lg-none pl-1 pb-3 pb-md-2">
        <a href="javascript:;" class="clear_all"><?php _e( 'Clear all', 'dm' ) ?></a>
    </div>
</div>