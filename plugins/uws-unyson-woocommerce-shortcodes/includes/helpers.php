<?php

function uws_get_product_taxonomies_slug_by_id( $uws_tax_ids ) {
	if ( empty( $uws_tax_ids ) ) {
		return array();
	}

	$uws_args = array(
		'taxonomy'   => 'product_cat',
		'hide_empty' => false,
	);

	$uws_terms = get_terms( $uws_args );

	$uws_taxonomies_slug = array();
	foreach ( $uws_terms as $uws_item ) {
		if ( in_array( $uws_item->term_id, $uws_tax_ids ) ) {
			$uws_taxonomies_slug[ $uws_item->slug ] = $uws_item->slug;
		}
	}

	return $uws_taxonomies_slug;
}


function uws_plugin_notice() {
	if ( get_transient('uws_dismissed_'.'uws_plugin_notice', false) ) {
		return;
	} ?>

    <div class="welcome-panel notice notice notice-error is-dismissible uws-custom-notice" data-notice="uws_plugin_notice">
        <div class="welcome-panel-content">
            <a href="https://flytemplates.com/" target="_blank" rel="nofollow" class="uws-logo">
                <img src="http://demo.flytemplates.com/promo-uws/logo.png" alt="Unyson WooCommerce Shortcodes plugin" />
            </a>
            <h2><?php esc_html_e('Thank you for using Unyson WooCommerce Shortcodes', 'uws'); ?></h2>

            <p class="about-description"><?php esc_html_e('More products free & premium you can get on', 'uws'); ?> <a href="https://flytemplates.com/" target="_blank" rel="nofollow">Flytemplates.com</a></p>
        </div>
    </div>
    <style>
        .uws-custom-notice {
            padding-bottom: 20px;
        }
        .uws-custom-notice h2 {
            margin-bottom: 10px;
        }
        .uws-custom-notice .uws-logo {
            float: left;
            outline: none;
        }
        .uws-custom-notice .uws-logo img {
            max-width: 60px;
            margin-right: 10px;
        }
    </style>
    <script>
      jQuery(document).on('click', '.notice-dismiss', function () {
        // Read the "data-notice" information to track which notice
        // is being dismissed and send it via AJAX
        var type = jQuery(this).closest('.uws-custom-notice').data('notice');
        // Make an AJAX call
        // Since WP 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
        jQuery.ajax(ajaxurl,
          {
            type: 'POST',
            data: {
              action: 'uws_dismissed_notice',
              type: type,
            }
          });
      });
    </script>
<?php
}
add_action('admin_notices', 'uws_plugin_notice');


function uws_dismissed_notice() {
	// Store it in the options table
	if( isset($_POST['type']) && !empty($_POST['type']) ) {
		$type = strip_tags($_POST['type']);
		set_transient( 'uws_dismissed_' . $type, 1, 3600 * 24 * 14 );
	}
}
add_action( 'wp_ajax_uws_dismissed_notice', 'uws_dismissed_notice' );


