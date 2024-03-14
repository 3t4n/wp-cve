<?php
/**
 * The admin settings of the plugin.
 * @since      1.0.0
 */
$themes  = wp_get_themes();
$counter = get_option('universal_honey_pot_counter', 0);
?>
<section class="uhp-settings-title-container">
    <h1><?php _e( "Universal Honey Pot", 'universal-honey-pot' ); ?></h1>
    <!-- counter -->
    <div class="uhp-counter">
        <b class="uhp-counter-number"><?php echo esc_html( $counter ); ?></b>
        <span class="uhp-counter-text"><?php _e( "Spam(s) blocked since installation", 'universal-honey-pot' ); ?></span>
    </div>
</section>

<section class="uhp-supported-plugins-container">
    <?php
    foreach( get_universal_honey_pot_supported_plugins() as $path => $data ){
        if(isset($data['is_theme']) && $data['is_theme'] == true ){
            $installed = false;
            $is_active = false;

            foreach( $themes as $theme ){
                if( $theme->get_stylesheet() == $path ){
                    $installed = true;
                    $is_active = get_template_directory() == $theme->get_stylesheet_directory();
                    break;
                }
            }

        } else {
            $installed = file_exists( WP_PLUGIN_DIR . '/' . $path );
            $is_active = is_plugin_active( $path );
        }
        $comming_soon = $data['comming_soon'] ?? false;
        
        ?>
        <div class="<?php echo esc_attr( $comming_soon ? 'comming-soon' : '' ); ?>">
            <img class="logo" src="<?php echo esc_url( $data['img'] ?? '' ); ?>" alt="Icon <?php echo esc_attr( $data['name'] ?? '' ); ?>" />
            <h3><?php echo esc_html( $data['name'] ?? '' ); ?></h3>
            <hr>
            <?php
            if( $installed ){
                if( $is_active && ! $comming_soon ){
                    ?>
                    <p class="green"><?php _e( 'Is active and protected.', 'universal-honey-pot' ); ?></p>
                    <?php
                } else if( $is_active && $comming_soon ){
                    ?>
                    <p class="orange"><?php _e( 'Is active but not protected yet.', 'universal-honey-pot' ); ?></p>
                    <?php
                } else {
                    ?>
                    <p><?php _e( 'Is installed but not active.', 'universal-honey-pot' ); ?></p>
                    <?php
                }
            } else {
                ?>
                <p>
                    <?php _e( 'Is not installed.', 'universal-honey-pot' ); ?>
                </p>
                <?php
            }
            ?>
        </div>
        <?php
    }
    ?>
</section>

<section class="uhp-credits-container">
    <h2><?php _e( "Credits", 'universal-honey-pot' ); ?></h2>
    <p>
        <?php _e( "This plugin is developed by", 'universal-honey-pot' ); ?>
        <a href="https://webdeclic.com/" target="_blank">Webdeclic</a>.
        <?php _e( "You can support this project here:", 'universal-honey-pot' ); ?>
    </p>
    <p>
        <a class="buymeacoffee" href="https://www.buymeacoffee.com/ludwig" target="_blank"><img src="<?php echo esc_url( UNIVERSAL_HONEY_POT_PLUGIN_URL . 'public/images/buy-me-a-coffee.webp' ); ?>" alt="Buy Me A Coffee"></a>
    </p>
    <p>
        <?php _e( "You can show all Webdeclic's plugins on ", 'universal-honey-pot' ); ?>
        <a href="https://wordpress.org/plugins/search/webdeclic/" target="_blank"><?php _e( "wordpress.org", 'universal-honey-pot' ); ?></a>.
    </p>
</section>