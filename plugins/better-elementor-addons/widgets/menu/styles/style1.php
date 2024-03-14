<div class="better-navigation-menu style-1 navbar-expand-lg navbar">

    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
        aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="icon-bar"><i class="fas fa-bars"></i></span>
    </button>

    <!-- navbar links -->
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <?php
        $menu = !empty($settings['menu']) ? $settings['menu'] : 'main-menu';
        wp_nav_menu(array(
            'menu' => esc_html($menu),
            'theme_location' => 'menu-1',
            'menu_class' => 'navbar-nav ml-auto',
            'container' => false,
        ));
        ?>
    </div>

</div>
