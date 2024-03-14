<?php
/**
 * Pages of a single Template
 */

$pluginNameClass = pluginNameClass();
if (class_exists($pluginNameClass)) {
global $template_group;

?>

<div class="wpsection-library">
    <div class="wpsection-library-header">
        <a class="template-back" href="<?php echo esc_url( admin_url( 'admin.php?page=wpsection-library' ) ); ?>"><?php esc_html_e( 'All Templates', 'wpsection' ); ?></a>
        <span class="template-name"><?php echo esc_html( wpsection()->get_settings_atts( 'title', '', $template_group ) ); ?></span>
    </div>

    <div class="wpsection-templates">

		<?php foreach ( wpsection()->get_settings_atts( 'pages', array(), $template_group ) as $template_id => $template ) :
			$is_pro = (bool) wpsection()->get_settings_atts( 'pro', false, $template );
			$is_pro_class = $is_pro ? 'template-pro' : '';
			?>            
            <div class="wpsection-template wpsection-template-page <?php echo esc_attr( $is_pro_class ); ?>">
                <img src="<?php echo esc_url( wpsection()->get_settings_atts( 'thumb', '', $template ) ); ?>"
                     alt="<?php echo esc_html( wpsection()->get_settings_atts( 'title', '', $template ) ) ?>">
                <div class="template-details">
             

<h3><?php echo wp_kses(wp_unslash(wpsection()->get_settings_atts('title', '', $template)), 'post'); ?></h3>

                    <div class="template-info">
                        <a target="_blank" href="<?php echo esc_url( wpsection()->get_settings_atts( 'demo', '', $template ) ); ?>"><?php esc_html_e( 'Preview', 'wpsection' ); ?></a>
                        
                        <div class="wpsection-import"
                             data-template-group="<?php echo esc_attr( wpsection()->get_settings_atts( 'template_group', '', $template_group ) ); ?>"
                             data-template="<?php echo esc_attr( $template_id ); ?>">
							<?php if ( $is_pro ) {
								esc_html_e( 'Premium', 'wpsection' );
							} else {
								esc_html_e( 'Import', 'wpsection' );
							} ?>
                        </div>
                    </div>
                </div>
            </div>
		<?php endforeach; ?>

    </div>

    <div class="wpsection-import-window">
        <div class="wpsection-import"></div>
    </div>
</div>

<?php } ?>