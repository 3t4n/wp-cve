<?php


if ( ! defined( 'ABSPATH' ) ) exit;



/*-------------------------------------------------------------------------------*/
/* Ajax Settings Page
/*-------------------------------------------------------------------------------*/
function glg_ajax_save_settings() {
	
	$res = array();
	
	
	// run a quick security check
	if( ! check_ajax_referer( 'glg_form_settings', 'security' ) )
		return;
	

	if ( $_POST['fieldsdata'] ) {
		
		foreach ( $_POST['fieldsdata'] as $key => $val ) {
			
			update_option( $val['name'], esc_html( $val['value'] ) );
			
			}
				
			$res['ok'] = true;
				
	}
		


echo json_encode( $res );
wp_die();
	
}

add_action('wp_ajax_glg_ajax_save_settings', 'glg_ajax_save_settings');


/*-------------------------------------------------------------------------------*/
/*  Get Free Plugins ( AJAX )
/*-------------------------------------------------------------------------------*/
function glg_free_plugins_page() {
	
	// run a quick security check
	if( ! check_ajax_referer( 'glg_free_plugins_nonce', 'security' ) )
		return;
	
	ob_start();

	include( ABSPATH . "wp-admin/includes/plugin-install.php" );
	global $tabs, $tab, $paged, $type, $term;
	$tabs = array();
	$tab = "search";
	$per_page = 30;
	$args = array
	(
		"author"=> "GhozyLab",
		"page" => $paged,
		"per_page" => $per_page,
		"fields" => array( "last_updated" => true, "downloaded" => true, "icons" => true ),
		"locale" => get_locale(),
	);
	$args = apply_filters( "install_plugins_table_api_args_$tab", $args );
	$api = plugins_api( "query_plugins", $args );
	$item = $api->plugins;
	
	$plugins_allowedtags = array(
		'a' => array( 'href' => array(), 'title' => array(), 'target' => array() ),
		'abbr' => array( 'title' => array() ), 'acronym' => array( 'title' => array() ),
		'code' => array(), 'pre' => array(), 'em' => array(), 'strong' => array(),
		'div' => array( 'class' => array() ), 'span' => array( 'class' => array() ),
		'p' => array(), 'ul' => array(), 'ol' => array(), 'li' => array(),
		'h1' => array(), 'h2' => array(), 'h3' => array(), 'h4' => array(), 'h5' => array(), 'h6' => array(),
		'img' => array( 'src' => array(), 'class' => array(), 'alt' => array() )
		);

	?>
	<form id="plugin-filter">
    
<div class="wrap">
<div style="margin-top:30px;" class="wp-list-table widefat plugin-install">
	<div id="the-list">
    
		<?php
		foreach ( (array) $item as $plugin ) {
			if ( is_object( $plugin ) ) {
				$plugin = (array) $plugin;
			}

			
			$title = wp_kses( $plugin['name'], $plugins_allowedtags );
			// Remove any HTML from the description.
			$description = strip_tags( $plugin['short_description'] );
			$version = wp_kses( $plugin['version'], $plugins_allowedtags );

			$name = strip_tags( $title . ' ' . $version );

			$author = wp_kses( $plugin['author'], $plugins_allowedtags );
			if ( ! empty( $author ) ) {
				$author = ' <cite>' . sprintf( __( 'By %s' ), $author ) . '</cite>';
			}

			$action_links = array();

			if ( current_user_can( 'install_plugins' ) || current_user_can( 'update_plugins' ) ) {
				$status = install_plugin_install_status( $plugin );

				switch ( $status['status'] ) {
					case 'install':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version. */
							$action_links[] = '<a class="install-now button-secondary glg-button-install" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( __( 'Install %s now' ), $name ) ) . '">' . __( 'Install Now' ) . '</a>';
						}

						break;
					case 'update_available':
						if ( $status['url'] ) {
							/* translators: 1: Plugin name and version */
							$action_links[] = '<a class="button glg-button-update" href="' . $status['url'] . '" aria-label="' . esc_attr( sprintf( __( 'Update %s now' ), $name ) ) . '">' . __( 'Update Now' ) . '</a>';
						}

						break;
					case 'latest_installed':
					case 'newer_installed':
						$action_links[] = '<span class="button button-disabled" title="' . esc_attr__( 'This plugin is already installed and is up to date' ) . ' ">' . _x( 'Installed', 'plugin' ) . '</span>';
						break;
				}
			}

			$details_link   = self_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin=' . $plugin['slug'] .
								'&amp;TB_iframe=true&amp;width=750&amp;height=550' );

			/* translators: 1: Plugin name and version. */
			$action_links[] = '<a href="' . esc_url( $details_link ) . '" class="thickbox" aria-label="' . esc_attr( sprintf( __( 'More information about %s' ), $name ) ) . '" data-title="' . esc_attr( $name ) . '">' . __( 'More Details' ) . '</a>';

			if ( !empty( $plugin['icons']['svg'] ) ) {
				$plugin_icon_url = $plugin['icons']['svg'];
			} elseif ( !empty( $plugin['icons']['2x'] ) ) {
				$plugin_icon_url = $plugin['icons']['2x'];
			} elseif ( !empty( $plugin['icons']['1x'] ) ) {
				$plugin_icon_url = $plugin['icons']['1x'];
			} else {
				$plugin_icon_url = $plugin['icons']['default'];
			}

			/**
			 * Filter the install action links for a plugin.
			 *
			 * @since 2.7.0
			 *
			 * @param array $action_links An array of plugin action hyperlinks. Defaults are links to Details and Install Now.
			 * @param array $plugin       The plugin currently being listed.
			 */
			$action_links = apply_filters( 'plugin_install_action_links', $action_links, $plugin );
		?>
		<div id="<?php echo $plugin["slug"]; ?>" class="plugin-card drop-shadow lifted">
			<div class="plugin-card-top" style="min-height: 190px !important;">
            <?php if ( isset( $plugin["slug"] ) && $plugin["slug"] == 'easy-media-gallery' ) {echo '<div class="most_popular"></div>';} ?>
				<a href="<?php echo esc_url( $details_link ); ?>" class="glg-free-plugin-icon thickbox plugin-icon"><img width="128" height="128" src="<?php echo esc_attr( $plugin_icon_url ) ?>" /></a>
				<div class="name column-name" style="margin-right: 20px !important;">
					<h4 style="font-size:1.4em;"><a href="<?php echo esc_url( $details_link ); ?>" class="thickbox"><?php echo $title; ?></a></h4>
				</div>
				<div class="desc column-description" style="margin-right: 20px !important;">
					<p><?php echo $description; ?></p>
					<p class="authors"><?php echo $author; ?></p>			
				</div>
			</div>
					<div class="glg-button-con">
					<?php
						if ( $action_links ) {
							echo '<ul class="glg-plugin-action-buttons">';
							echo '<li>' . $action_links[0] . '</li>';
							
							switch( $plugin["slug"] ){
								case "easy-media-gallery" :
								echo '<li><a class="button" aria-label="PRO VERSION DEMO" href="http://ghozylab.com/plugins/easy-media-gallery-pro/demo/" target="_blank">PRO VERSION DEMO</a></li>';
								break;
								
								case "image-slider-widget" :
								echo '<li><a class="button" aria-label="PRO VERSION DEMO" href="http://demo.ghozylab.com/plugins/easy-image-slider-plugin/image-slider-with-thumbnails-at-the-bottom/" target="_blank">PRO VERSION DEMO</a></li>';
								break;
								
								case "easy-notify-lite" :
								echo '<li><a class="button" aria-label="PRO VERSION DEMO" href="http://ghozylab.com/plugins/easy-notify-pro/demo/" target="_blank">PRO VERSION DEMO</a></li>';
								break;
								
								case "contact-form-lite" :
								echo '<li><a class="button" aria-label="PRO VERSION DEMO" href="http://demo.ghozylab.com/plugins/easy-contact-form-plugin/contact-form-recaptcha/" target="_blank">PRO VERSION DEMO</a></li>';
								break;
								
								default:
								break;
							}
							
							
							echo '</ul>';
						}
					?>
				</div>
			<div class="plugin-card-bottom">
				<div class="column-updated">
					<strong><?php _e( 'Last Updated:' ); ?></strong> <span title="<?php echo esc_attr( $plugin['last_updated'] ); ?>">
						<?php printf( __( '%s ago' ), human_time_diff( strtotime( $plugin['last_updated'] ) ) ); ?>
					</span>
				</div>
				<div class="column-downloaded">
					<?php echo sprintf( _n( '%s download', '%s downloads', $plugin['downloaded'] ), number_format_i18n( $plugin['downloaded'] ) ); ?>
				</div>
				<div class="column-compatibility">
					<?php
					if ( ! empty( $plugin['tested'] ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin['tested'] ) ), $plugin['tested'], '>' ) ) {
						echo '<span class="compatibility-untested">' . __( 'Untested with your version of WordPress' ) . '</span>';
					} elseif ( ! empty( $plugin['requires'] ) && version_compare( substr( $GLOBALS['wp_version'], 0, strlen( $plugin['requires'] ) ), $plugin['requires'], '<' ) ) {
						echo '<span class="compatibility-incompatible">' . __( '<strong>Incompatible</strong> with your version of WordPress' ) . '</span>';
					} else {
						echo '<span class="compatibility-compatible">' . __( '<strong>Compatible</strong> with your version of WordPress' ) . '</span>';
					}
					?>
				</div>
			</div>
		</div>
		<?php
		}
		?>

     	</div>	
	</div>       
</div>    
	</form>   

<script type="text/javascript">   

(function($) {

$.fn.glgReOrder = function(array) {
  return this.each(function() {

    if (array) {    
      for(var i=0; i < array.length; i++) 
        array[i] = $('div[id="' + array[i] + '"]');

      $(this).empty();  

      for(var i=0; i < array.length; i++)
        $(this).append(array[i]);      
    }
  });    
}
})(jQuery);


jQuery(document).ready(function($) {
	
	var theListsglg = ['easy-media-gallery', 'contact-form-lite', 'feed-instagram-lite', 'image-slider-widget', 'gallery-lightbox-slider', 'image-carousel','icon', 'easy-notify-lite'];
	
	$('#the-list').glgReOrder(theListsglg);
	
});
</script>   

<?php

$res = ob_get_clean();
echo $res;

wp_die();
	
}

add_action( 'wp_ajax_glg_free_plugins_page', 'glg_free_plugins_page' );


/*-------------------------------------------------------------------------------*/
/*  Get Free Plugins ( AJAX )
/*-------------------------------------------------------------------------------*/
function glg_pro_plugins_page() {
	
	// run a quick security check
	if( ! check_ajax_referer( 'glg_pro_plugins_nonce', 'security' ) )
		return;
	
	if ( false === ( $cache = get_transient( 'glg_premium_plugins' ) ) ) {
		$feed = wp_remote_get( 'http://content.ghozylab.com/feed.php?c=featuredplugins', array( 'sslverify' => false ) );
		if ( ! is_wp_error( $feed ) ) {
			if ( isset( $feed['body'] ) && strlen( $feed['body'] ) > 0 ) {
				$cache = wp_remote_retrieve_body( $feed );
				set_transient( 'glg_premium_plugins', $cache, 3600 );
			}
		} else {
			$cache = '<div class="error"><p>' . __( 'There was an error retrieving the list from the server. Please try again later.', 'gallery-lightbox-slider' ) . '</div>';
		}
	}
	
echo $cache;
wp_die();
	
}

add_action( 'wp_ajax_glg_pro_plugins_page', 'glg_pro_plugins_page' );
