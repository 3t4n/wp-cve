<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

$tmpl = new \dm\dmTemplate();
?>
    <div class="pt-0 pb-2 pb-lg-0">
		<div class="filters-settings-title">
			Filters
			<a href="javascript:;" id="close_filters">	&times;</a>
		</div>
        <div class="firsr_filters">
            <div class="pl-0 tab-nav-elements btn-friendly" id="range-filters">
				<div class="range-item pb-2 pb-md-0">
					<label for="originalPriceFrom" style="display: block;"><?php _e( 'Supplier price', 'dm' ) ?>:</label>
                    <?php
                        echo $tmpl->text( [
                            'value' => '',
                            'id'    => 'originalPriceFrom',
                            'name'  => 'originalPriceFrom',
                            //'placeholder' => '{{currency_symbol}} ' . __( 'min', 'dm' )
                            'placeholder' =>  __( 'min', 'dm' )
                        ] ) . ' <label for="originalPriceTo">&ndash;</label> ' . $tmpl->text( [
                            'value' => '',
                            'id'    => 'originalPriceTo',
                            'name'  => 'originalPriceTo',
                            //'placeholder' => '{{currency_symbol}} ' . __( 'max', 'dm' )
                            'placeholder' =>  __( 'max', 'dm' )
                        ] ) 
                    ?>
                </div>
                <div class="range-item pb-2 pb-md-0 mobile_volume">
					<label for="volumeFrom" style="display: block;"><?php _e( 'Orders', 'dm' ) ?>:</label>
		            <?php
		            echo $tmpl->text( [
				            'value' => '',
				            'id'    => 'volumeFrom',
				            'name'  => 'volumeFrom',
				            'placeholder' => __( 'min', 'dm' )
			            ] ) . ' <label for="volumeTo">&ndash;</label> ' . $tmpl->text( [
				            'value' => '',
				            'id'    => 'volumeTo',
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
            </div>
        </div>
    </div>
    <div class="pt-3 pb-2 py-md-1 mt-1">
        <div class="second_filters">
            <div class="tab-nav-elements btn-friendly" id="more-filters">
                <?php
                echo $tmpl->select( [
                        'value'  => '',
                        'values' => [ '' => __( 'Any', 'dm' ), 'CN' => __( 'China', 'dm' ), 'US' => __( 'United States', 'dm' ), 'EU' => __( 'Europe', 'dm' ) ],
                        'id'     => 'warehouse',
                        'name'   => 'warehouse',
                        'label'  => __( 'Shipping from:', 'dm' )
                    ] ) . $tmpl->select( [
                        'value'  => 'US',
                        'icon'   => true,
                        'values' => dm_list_countries(),
                        'id'     => 'to',
                        'name'   => 'to',
                        'multiple' =>false,
                        'label'  => __( 'Shipping to:', 'dm' )
                    ] )  . $tmpl->select( [
                        'value'  => '9999',
                        'values' => dm_list_company(),
                        'id'     => 'company',
                        'name'   => 'company',
                        'label'  => __( 'Shipping method:', 'dm' )
                    ] ) . $tmpl->checkbox( [
		                'value' => '1',
		                'id'    => 'free',
		                'name'  => 'free',
		                'help'  => __( 'Free shipping', 'dm' )
	                ] )
                ?>
            </div>
			    <div class="d-block mt-3 text-right">
        <a href="javascript:;" class="clear_all"><?php _e( 'Clear all filters', 'dm' ) ?></a>
    </div>
        </div>
    </div>
