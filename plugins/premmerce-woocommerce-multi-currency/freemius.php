<?php

// Create a helper function for easy SDK access.
function premmerce_pwm_fs()
{
    global  $premmerce_pwm_fs ;
    
    if ( !isset( $premmerce_pwm_fs ) ) {
        // Include Freemius SDK.
        require_once dirname( __FILE__ ) . '/freemius/start.php';
        $premmerce_pwm_fs = fs_dynamic_init( array(
            'id'               => '2132',
            'slug'             => 'premmerce-woocommerce-multi-currency',
            'type'             => 'plugin',
            'public_key'       => 'pk_c018f330d57d260eaaa190955b959',
            'is_premium'       => false,
            'has_addons'       => false,
            'has_paid_plans'   => true,
            'is_org_compliant' => false,
            'trial'            => array(
            'days'               => 7,
            'is_require_payment' => true,
        ),
            'menu'             => array(
            'slug'    => 'premmerce_multicurrency',
            'support' => false,
            'parent'  => array(
            'slug' => 'premmerce',
        ),
        ),
            'is_live'          => true,
        ) );
    }
    
    return $premmerce_pwm_fs;
}

// Init Freemius.
premmerce_pwm_fs();
// Signal that SDK was initiated.
do_action( 'premmerce_pwm_fs_loaded' );