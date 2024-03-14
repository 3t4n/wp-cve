<?php

/**
 * Advanced settings view for the plugin
 *
 * @link       http://www.webfactoryltd.com
 * @since      1.0
 */

if (!defined('WPINC')) {
	die;
}

?>

<div class="signals-tile" id="advanced">
	<div class="signals-tile-body">
		<div class="signals-tile-title"><?php esc_attr_e( 'ADVANCED', 'minimal-coming-soon-maintenance-mode' ); ?></div>
		<p><?php esc_attr_e( 'Please double-check any custom code you enter in the settings below. Any typos or mistakes will affect the appearance of the page.', 'minimal-coming-soon-maintenance-mode' ); ?></p>


		<div class="signals-section-content">
    <div class="signals-double-group signals-clearfix">
			<div class="signals-form-group">
				<label for="signals_csmm_disable" class="signals-strong"><?php esc_attr_e( 'Use Custom HTML only', 'minimal-coming-soon-maintenance-mode' ); ?></label>
				<input type="checkbox" class="signals-form-ios" id="signals_csmm_disable" name="signals_csmm_disable" value="1"<?php checked( '1', $signals_csmm_options['disable_settings'] ); ?>>

				<p class="signals-form-help-block"><?php esc_attr_e( 'If you enable this option, the plugin will ignore everything except the HTML you provide.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				<p class="signals-form-help-block"><?php esc_attr_e( 'Basically, you will have a blank template which you can fill with your provided HTML content. Only basic CSS gets added by the plugin which does the task of browser styling reset. You should style your HTML content either inline or by inserting styling in the custom CSS section. In short, use this option only if you know what you are doing.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
      </div>

      <div class="signals-form-group">
        <label for="csmm_nocache" class="signals-strong pro-option">Send no-cache Headers<sup>PRO</sup></label>
        <input type="checkbox" class="signals-form-ios pro-option skip-save" id="csmm_nocache" name="csmm_nocache" value="1" disabled>

        <p class="signals-form-help-block">If you don't want the coming soon page's preview to be cached by Facebook and other social media enable this option. Once you switch to the normal site social media preview (visible when sharing the site's link) will immediately be refreshed. Normal visitors won't notice any differences with the option enabled. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>
      </div>

			</div>

      <div class="signals-double-group signals-clearfix">
      <div class="signals-form-group">
        <label for="csmm_force_ssl" class="signals-strong pro-option">Force HTTPS<sup>PRO</sup></label>
        <input type="checkbox" class="signals-form-ios pro-option skip-save" id="csmm_force_ssl" name="csmm_force_ssl" value="1" disabled>

        <p class="signals-form-help-block">If you have a valid SSL certificate installed on your site but people are still visiting the non-secure HTTP version you can redirect them to HTTPS. Redirection only works for the coming soon page; not for the entire site. This is a <a href="#pro" class="csmm-change-tab">PRO feature</a>.</p>
      </div>

      <div class="signals-form-group">
        <label for="signals_csmm_disable_adminbar" class="signals-strong"><?php esc_attr_e( 'Disable Maintenance Mode Toolbar Menu', 'minimal-coming-soon-maintenance-mode' ); ?></label>
        <input type="checkbox" class="signals-form-ios" id="signals_csmm_disable_adminbar" name="signals_csmm_disable_adminbar" value="1"<?php checked( '1', $signals_csmm_options['disable_adminbar'] ); ?>>

        <p class="signals-form-help-block"><?php esc_attr_e( 'By default, a helpfull Maintenance Mode menu and status are added to the admin and front-end toolbar. If your toolbar is too crowded, disable the menu.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
      </div>

			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_html" class="signals-strong"><?php esc_attr_e( 'Custom HTML', 'minimal-coming-soon-maintenance-mode' ); ?></label>
				<div id="signals_csmm_html_editor"></div>
				<textarea name="signals_csmm_html" id="signals_csmm_html" rows="8" placeholder="<?php esc_attr_e( 'Custom HTML for the plugin', 'minimal-coming-soon-maintenance-mode' ); ?>"><?php echo esc_textarea(stripslashes( $signals_csmm_options['custom_html'] )); ?></textarea>

				<p class="signals-form-help-block"><?php echo __( 'Custom HTML for the plugin goes over here. Please note that ', 'minimal-coming-soon-maintenance-mode' ) . '<i style="color: #f96773">' . __( '[html], [head], [title], [meta], [body], and similar tags', 'minimal-coming-soon-maintenance-mode' ) . '</i>' . __( ' are not required. Only provide content HTML for the page.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
				<p class="signals-form-help-block"><?php esc_attr_e( 'To insert subscription form anywhere in the HTML, simply use the placeholder <strong>{{form}}</strong> and you are done. This should only be used if you have enabled the above option to use custom HTML only.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
			</div>

			<div class="signals-form-group">
				<label for="signals_csmm_css" class="signals-strong"><?php esc_attr_e( 'Custom CSS', 'minimal-coming-soon-maintenance-mode' ); ?></label>
				<div id="signals_csmm_css_editor"></div>
				<textarea name="signals_csmm_css" id="signals_csmm_css" class="Signals_csmm_Block" rows="8" placeholder="<?php esc_attr_e( 'Custom CSS for the plugin', 'minimal-coming-soon-maintenance-mode' ); ?>"><?php echo esc_textarea(stripslashes( $signals_csmm_options['custom_css'] )); ?></textarea>

				<p class="signals-form-help-block"><?php esc_attr_e( 'Custom CSS for the plugin goes over here.', 'minimal-coming-soon-maintenance-mode' ); ?></p>
			</div>

		</div>
	</div>
</div><!-- #advanced -->
