<?php
$categories = get_categories( [
	'taxonomy'   => 'plgnoptmzr_categories',
	'type'       => 'plgnoptmzr_filter',
	'parent'     => 0,
	'hide_empty' => 0,
] );

if( $categories ){
    
    usort( $categories, "SOSPO_Admin_Helper::sort__by_cat_name" );
}

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Filter categories", "categories"); ?>
    
    <div class="sos-content">
        
        <div id="filter_options" class="toggle_filter_options" style="padding: 0;"></div>
        <script>jQuery('#filter_options').hide();</script>
        
        <div class="row col-12">
            <div class="col-12">

                <div class="premium-only-box">
                    
                    <p>Categories are only available in the Premium version of Plugin Optimizer.
                    You can purchase Plugin Optimizer Premium at <a href="https://pluginoptimizer.com">Pluginoptimizer.com</a></p>

                </div>

            </div>
        </div>
        
    </div>
</div>
