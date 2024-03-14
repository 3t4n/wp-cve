<nav class="better-navbar navbar change navbar-expand-lg style-1">
    <div class="container">
        <!-- Logo -->
        <a class="logo" href="#">
            <img class="white" src="<?php echo esc_url($settings['better_logo']['url']) ?>" alt="logo">
            <img class="dark d-none" src="<?php echo esc_url($settings['better_logo_dark']['url']) ?>" alt="logo">
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
            ?>
        </div>
    </div>
</nav>
