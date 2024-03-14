<div class="wrap">
	<h1><?php esc_html_e('CCM19 Cookie Consent', 'ccm19-integration'); ?></h1>
	
	<?php if (get_option('ccm19_code') && !$integration_url): ?>
	<div id="ccm19-error" class="notice notice-error is-dismissible"> 
		<p><?php _e('<strong>The code snippet is invalid.</strong> CCM19 is not activated on your website.', 'ccm19-integration'); ?></p>
	</div>
	<?php endif; ?>

	<?php if ($integration_url): ?>
	<div id="ccm19-success" class="notice notice-success is-dismissible"> 
		<p><?php _e('The code snippet is valid. CCM19 is active on your website.', 'ccm19-integration'); ?></p>
	</div>
	<?php endif; ?>


    <?php if(!is_network_admin() ):?>
	<form method="POST" action="options.php?action=ccm19option">
		<?php settings_fields('ccm19-integration'); ?>
		<?php do_settings_sections('ccm19-integration'); ?>

		<?php if ($admin_url): ?>
		<p>
			<a href="<?php echo esc_url($admin_url); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Open CCM19 backend', 'ccm19-integration'); ?>
				<span class="dashicons dashicons-external" style="vertical-align: text-bottom;"></span>
			</a>
		</p>
		<?php endif; ?>
		<?php submit_button(); ?>
	</form>
<?php endif; ?>



    <?php if(is_multisite() && is_network_admin() ):?>
        <div class="wrap">
            <form action="edit.php?action=<?php echo esc_attr( 'ccm19-integration' ); ?>-update" method="POST">

		        <?php
		        settings_fields( 'ccm19-integration' . '-page' );
		        do_settings_sections( 'ccm19-integration' . '-page' ); ?>
                <?php if ($admin_url): ?>
                <p>
                    <a href="<?php echo esc_url($admin_url); ?>" target="_blank" class="button button-secondary"><?php esc_html_e('Open CCM19 backend', 'ccm19-integration'); ?>
                        <span class="dashicons dashicons-external" style="vertical-align: text-bottom;"></span>
                    </a>
                </p>
	            <?php endif; ?>
                <?php submit_button(); ?>
            </form>
        </div>
    <?php endif; ?>
</div>
