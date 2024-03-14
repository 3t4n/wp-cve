<?php
global $path_url;
$plugin_support_url       = 'https://wordpress.org/support/plugin/mywp-custom-patterns/' ;
$plugin_documentation_url = 'https://wordpress.org/plugins/mywp-custom-patterns/' ;


?>
<div class="mywp-custom-patterns-admin-header">

    <h1 class="mywp-custom-patterns-admin-header__title">
        <small><?php esc_html_e('MyWP', 'mywp-custom-patterns') ?></small>
        <span><?php esc_html_e('Custom Patterns', 'mywp-custom-patterns') ?></span>
    </h1>

    <div class="mywp-custom-patterns-admin-header__help">
        <p>
            <span><?php esc_html_e('Need some help?', 'mywp-custom-patterns') ?></span>
            <span>
              <?php
              echo sprintf(
              	/* translators: 1: Opening HTML link tag. 2: Closing HTML link tag. 3: Opening HTML link tag. 4: Closing HTML link tag. */
              	__( 'Ask to %1$ssupport%2$s or check our %3$sdocumentation%4$s!', 'mywp-custom-patterns' ),
              	'<a href="'.esc_url( $plugin_support_url ).'" target="_blank" rel="noopener noreferrer" >',
              	'</a>',
              	'<a href="'.esc_url( $plugin_documentation_url ).'" target="_blank" rel="noopener noreferrer" >',
              	'</a>'
              );
              ?>
            </span>
        </p>
        <img class="mywp-custom-patterns-admin-header__help-icon" src="<?php echo esc_url( $path_url . 'img/mywp-help.svg' ) ; ?>">
    </div>
</div>
