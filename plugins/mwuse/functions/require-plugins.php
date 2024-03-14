<?php

/* No Adobe Muse template */
function no_mtw_template() 
{
	$message = __( '<b>You don\'t have any Adobe Muse HTML site in your mtw-theme folder.</b> <br/> Use <b>FTP</b> to upload in: {folder} <br/><b>OR</b><br/>{zipurl}Upload via ZIP here{/zipurl}', 'mwuse' );

	$message = str_replace('{folder}', TTR_MW_TEMPLATES_PATH.'your-first-project/', $message);
	$message = str_replace('{zipurl}', '<a href="'.get_admin_url().'admin.php?page=mtw-upload">', $message);
	$message = str_replace('{/zipurl}', '</a>', $message);
    ?>
    <div class="error notice">
        <p>
        	<?php echo $message; ?>
        </p>
    </div>
    <?php
}


function mtw_xd_template_instruction()
{
	?>
	<div class="updated notice is-dismissible mtw-notice" id="mtw-xd-template-instruction-notice">
	    <p><?php _e( 'You can use <b>Synchronize Muse and Wordpress Pages</b> for create Wordpress pages automatically', 'mwuse' ); ?></p>
	    <p><a href="https://mwuse.com/shop/xd/"><?php _e( 'Download XD Adobe Muse files to modify this or create our first template.', 'mwuse' ); ?></a></p>
	</div>
	<?php
}


function mtw_notice_dismiss()
{
	?>
	<script type="text/javascript">
	jQuery(document).on( 'click', '.mtw-notice .notice-dismiss', function() {

		noticeID = jQuery(this).parents('.mtw-notice').attr('id');

	    jQuery.post(
		    ajaxurl, 
		    {
		        'action': 'mtw_notice_dismiss_by_id',
		        'data':   noticeID
		    }, 
		    function(response){
		        
		    }
		);

	});
	</script>
	<?php
}
add_action( "admin_footer", "mtw_notice_dismiss" );



function mtw_notice_dismiss_by_id()
{
	$id = sanitize_key( $_POST['data'] );
	update_option( $id, 1 );
	die( $id );
}

add_action( 'wp_ajax_mtw_notice_dismiss_by_id', 'mtw_notice_dismiss_by_id' );
//add_action( 'wp_ajax_nopriv_mtw_notice_dismiss_by_id', 'mtw_notice_dismiss_by_id' );



function muse_to_wordpress_xd_register_required_plugins()
{
	if( is_admin() )
	{
		global $plugin_manager_list;
		global $plugin_manager_api_key;
		global $mtw_server;
		global $mtw_ssl;

		$mtw_option = get_option( 'mtw_option' );
		$plugins = array();
		
		if( !( $mtw_require = maybe_unserialize( get_transient( 'mtw_require' ) ) ) )
		{
			$mtw_require = wp_remote_get( $mtw_server."?plugin-manage=1&ssl=".$mtw_ssl);
			set_transient( maybe_serialize( $mtw_require ) , 'mtw_require', 60 * 60 );
		}		

		if( !is_wp_error($mtw_require) )
		{
			$mtw_require = $mtw_require['body'];
			if( $mtw_require_array = json_decode( $mtw_require, true ) )
			{
				$plugins = array_merge( $plugins , $mtw_require_array );
			}
		}
		

		$plugins_for_widgets = array();
		foreach ($plugins as $key => $plugin) 
		{
			if( $plugin['dependence'] == 'widget' )
			{
				$plugins_for_widgets[$key] = $plugin;
				unset($plugins[$key]);
			}
			elseif (  $plugin['dependence'] == 'function' && !function_exists($plugin['function']) ) 
			{
				unset($plugins[$key]);
			}
		}


		$MTW_plugins_requier_new = array();
		$MTW_pluginss_requier = get_option( "MTW_plugins_requier", array() );
		

		foreach ($MTW_pluginss_requier as $key => $MTW_plugins_requier) 
		{
			if( !empty($MTW_plugins_requier) && file_exists( TTR_MW_TEMPLATES_PATH . $key ) )
			{
				$MTW_plugins_requier_new[$key] =  $MTW_plugins_requier;

				foreach ($MTW_plugins_requier as $key => $MTW_plugin_requier) 
				{
					if( @$plugins_for_widgets[  $MTW_plugin_requier['slug']  ] )
					{
						$plugins[ $MTW_plugin_requier['slug'] ] = $plugins_for_widgets[  $MTW_plugin_requier['slug']  ];
					}
					else
					{
						$plugins[ $MTW_plugin_requier['slug'] ] = $MTW_plugin_requier;
					}
				}
			}
		}

		unset($plugins['mtw-shortcodes'],$plugins['mtw-custom-post-type'],$plugins['mtw-slideshow-attachements'],$plugins['mtw-comment-zone'],$plugins['mtw-grid-list']);


		$plugin_manager_list = $plugins;
		update_option( "MTW_plugins_requier", $MTW_plugins_requier_new );
	}
}
add_action( 'admin_init', 'muse_to_wordpress_xd_register_required_plugins' );


function mtw_check_plugins_required( $dom, $file_url )
{	
	
	$finder = new DomXPath( $dom );

	$results = $finder->query("//meta[@name='plugin_require']");

	$plugin_requier_options[$file_url] = array();

	foreach ($results as $key => $el) 
	{
		$el_args = $el->getAttribute("args");
		$plugin_requier_options[$file_url][] = json_decode( "[".$el_args."]", true )[0];
	}

	$MTW_plugins_requier = get_option( "MTW_plugins_requier", array() );
	$MTW_plugins_requier = array_merge( $MTW_plugins_requier, $plugin_requier_options );
	update_option( "MTW_plugins_requier", $MTW_plugins_requier );
}
add_action( 'DOMDocument_change', 'mtw_check_plugins_required', 10, 2);
add_action( 'DOMDocument_loaded', 'mtw_check_plugins_required', 10, 2);


?>