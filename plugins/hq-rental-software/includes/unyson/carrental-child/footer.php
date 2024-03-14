<?php

/**
 * footer.php
 *
 * The template for displaying the footer.
 */

$footer_copyright = '';
if (defined('FW')) :
    $socials = fw_get_db_settings_option('socials');
    $footer_copyright = fw_get_db_settings_option('footer_text');
endif;
?>
<a href="#" class="scrollup">ScrollUp</a>
<!-- Footer start -->
<footer>
    <div class="container">
        <div class="row">
            <div class="col-md-12 text-center">
                <?php
                wp_nav_menu(array(
                    'menu' => 'footer',
                    'theme_location' => 'footer',
                    'depth' => 1,
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => 'footer-nav',
                    'fallback_cb' => 'wp_bootstrap_navwalker::fallback',
                    'walker' => new wp_bootstrap_navwalker() ));
                ?>

                <div class = "clearfix"></div>
                <p class = "copyright"><?php echo esc_attr($footer_copyright); ?> </p>
                <ul class="list-inline footer-social">
                <li>
                    <a href="https://www.facebook.com/osrentacar" target="_blank">
                        <span class="fa-stack">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fa fa-facebook-f fa-stack-1x fa-inverse"></i>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="https://www.instagram.com/osrentacar" target="_blank">
                        <span class="fa-stack">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fa fa-instagram fa-stack-1x fa-inverse"></i>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="https://twitter.com/rypcar_houser" target="_blank">
                        <span class="fa-stack">
                            <i class="fas fa-circle fa-stack-2x"></i>
                            <i class="fa fa-twitter fa-stack-1x fa-inverse"></i>
                        </span>
                    </a>
                </li>
                </ul>
            </div>
        </div>
    </div>
</footer>
<!--Footer end -->

<?php wp_footer();?>
</body>
</html>