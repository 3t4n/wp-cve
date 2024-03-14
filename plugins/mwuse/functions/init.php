<?php

function mtw_check_isset_option()
{
	global $mtw_option;
	global $mtw_index_exclude;
	$mtw_option = get_option( 'mtw_option' );
	global $muse_projects;
	$muse_projects = ttr_get_muse_projects();

	if ( ! session_id() ) 
	{
      @session_start();
   	}
   	$_SESSION['mwuse_muse_projects'] = $muse_projects;
	
	global $wp_filesystem;
	WP_Filesystem();

	if( !$mtw_option )
	{
		if( empty($muse_projects) )
		{
			$default_theme = array_keys( $muse_projects )[0];
		}
		else
		{
			$default_theme = '';
		}
		$mtw_option = array(
			'mtw_auto_page' => 'checked',
			'mtw_production_mode' => 'checked',
			'mtw_index_exclude' => $mtw_index_exclude,
			'mtw_default_project' => $default_theme,
			'mtw_api_key' => ''
			);
		update_option( 'mtw_option', $mtw_option );
	}

	if( !isset( $mtw_option['mtw_default_project'] ) && !empty( $muse_projects ) )
	{
		$mtw_option['mtw_default_project'] = array_keys( $muse_projects )[0];
		update_option( 'mtw_option', $mtw_option );
	}

	foreach ($muse_projects as $key => $value) 
	{
		$path_museconfig = TTR_MW_TEMPLATES_PATH . $key . '/scripts/museconfig.js';
		
		$update_script = false;

		$museconfig = $wp_filesystem->get_contents( $path_museconfig );
		$project_url = TTR_MW_TEMPLATES_URL . $key;

		preg_match_all("#\"((?:(?![\"]).)*(scripts(?:(?![\"]).)+\.js(?:(?![\"]).)+))#m", $museconfig, $matches);
		if( $matches[1] )
		{
			foreach ( $matches[1] as $key => $script_url ) 
			{
				$new_url = $project_url . '/' . $matches[2][$key];
				if( $script_url != $new_url )
				{
					$update_script = true;
					$museconfig = str_replace($script_url, $new_url, $museconfig);
				}
			}
		}

		if( $update_script == true && substr_count($museconfig, '{') == substr_count($museconfig, '}') && substr_count($museconfig, '(') == substr_count($museconfig, ')') )
		{
			$wp_filesystem->put_contents($path_museconfig, $museconfig);
		}
	}
}
add_action( 'wp_loaded', 'mtw_check_isset_option', 1 );


function mtw_load_script_vars()
{
	global $projectName;
	?>
	<script type="text/javascript">
	var mtw_theme_url = '<?php echo TTR_MW_TEMPLATES_URL . $projectName . '/'; ?>';
	var previewType = 'wordpress';
	</script>
	<?php
}
add_action( 'wp_head', 'mtw_load_script_vars' );

?>