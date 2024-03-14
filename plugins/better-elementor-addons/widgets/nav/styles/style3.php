<?php

//Get unique ID.
function better_unique_id( $prefix = '' ) {
    static $id_counter = 0;
    if ( function_exists( 'wp_unique_id' ) ) {
        return wp_unique_id( $prefix );
    }
    return $prefix . (string) ++$id_counter;
}

?>

<nav class="better-navbar navbar navbar-expand-lg bg-transparent change full-width-nav style-2">
    <div class="container">

        <!-- Logo -->
        <a class="logo" href="<?php echo esc_url( home_url( '/' ) ); ?>">
            <img class="white" src="<?php echo esc_url( $settings['better_logo']['url'] ); ?>" alt="logo">
            <img class="dark d-none" src="<?php echo esc_url( $settings['better_logo_dark']['url'] ); ?>" alt="logo">
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="icon-bar"><i class="fas fa-bars"></i></span>
        </button>

        <!-- navbar links -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <?php
            $menu = !empty($settings['menu']) ? $settings['menu'] : 'main-menu';
            wp_nav_menu(array(
                'menu' => $menu,
                'theme_location' => 'menu-1',
                'menu_class' => 'navbar-nav ml-auto',
                'container' => false,
            ));

            $count = 0;
            if ( class_exists( 'WooCommerce' ) ) {
                if ( is_cart() && WC()->cart->cart_contents_count != 0 ) {
                    $count = WC()->cart->cart_contents_count;
                }
                $cart_link = wc_get_cart_url();
            }
            ?>
            <div class="cart">
                <div class="cart-icon">
                    <a class="icon pe-7s-cart cursor-pointer" href="<?php echo esc_url( $cart_link ); ?>" title="<?php esc_attr_e( 'View your shopping cart', 'better-el-addons' ); ?>"></a>
                    <div class="mad-count"><?php echo esc_html( $count ); ?></div>
                </div>
            </div>
            <div class="search">
                <span class="icon pe-7s-search cursor-pointer"></span>
                <div class="search-form text-center">
                    <?php $better_unique_id = better_unique_id( 'search-form-' ); ?>
                    <form role="search" method="get" id="<?php echo esc_attr( $better_unique_id ); ?>" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                        <input type="search" class="focus-input" placeholder="<?php esc_attr_e( 'Type search keyword...', 'better-el-addons' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
                    </form>
                    <span class="close pe-7s-close cursor-pointer"></span>
                </div>
            </div>
        </div>
    </div>
</nav>
