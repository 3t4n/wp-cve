<?php
/**
 * The Template for products comparison page
 * This template can be overridden by copying it to yourtheme/fami-wccp/compare.php.
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

get_header( 'fami-wccp' ); ?>

    <div class="fami-wccp-wrap">
        <div class="container">
			<?php Fami_Woocompare_Helper::get_template_part( 'compare', 'table' ); ?>
        </div>
    </div>

<?php get_footer( 'fami-wccp' );

