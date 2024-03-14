<?php
$groups = get_posts( array(
	'post_type'   => 'plgnoptmzr_group',
	'post_status' => [ 'publish', 'trash' ],
	'numberposts' => - 1,
) );

if( $groups ){
    
    usort( $groups, "SOSPO_Admin_Helper::sort__by_post_title" );
}

?>

<div class="sos-wrap">

    <?php SOSPO_Admin_Helper::content_part__header("Groups", "groups"); ?>
    
    <div class="sos-content">
        
        <?php SOSPO_Admin_Helper::content_part__filter_options( $groups ); ?>
        
        <div class="row col-12">
            <div class="col-12">
                <div class="premium-only-box">
                    
                    <p>Groups are only available in the Premium version of Plugin Optimizer.
                    You can purchase Plugin Optimizer Premium at <a href="https://pluginoptimizer.com">Pluginoptimizer.com</a></p>

                </div>
            </div>
        </div>
    </div>
</div>
