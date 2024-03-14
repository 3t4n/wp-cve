<?php
/**
 * My Account navigation
 *
 * This template can be overridden by copying it to yourtheme/masvideos/myaccount/navigation.php.
 *
 * HOWEVER, on occasion MasVideos will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @package MasVideos/Templates
 * @version 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

do_action( 'masvideos_before_account_navigation' );
?>

<nav class="masvideos-MyAccount-navigation">
    <ul>
        <?php foreach ( masvideos_get_account_menu_items() as $endpoint => $label ) : ?>
            <li class="<?php echo masvideos_get_account_menu_item_classes( $endpoint ); ?>">
                <a href="<?php echo esc_url( masvideos_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>

<?php do_action( 'masvideos_after_account_navigation' ); ?>
