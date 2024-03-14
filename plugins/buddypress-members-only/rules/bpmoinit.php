<?php
if (!defined('ABSPATH'))
{
	exit;
}

function bpmoinit()
{
	$bpmofreecurrentversion = get_option ( 'bpmofreecurrentversion' );
	$bpmofreecurrentversion = str_replace ( '.', '', $bpmofreecurrentversion );
	
	$inittime = time ();
	
	if (!(empty($bpmofreecurrentversion)))
	{
			$bpmoinitsetup = update_option ( 'bpmoinitsetup', $inittime );
	}
	else
	{
			$bpmoinitsetup = get_option ( 'bpmoinitsetup' );
			if (empty ( $bpmoinitsetup )) 
			{
				$bpmoregisterpageurl = get_option ( 'bpmoregisterpageurl' );
				if (empty ( $bpmoregisterpageurl )) {
					$bpmoregisterpageurl = get_option ( 'siteurl' ) . '/wp-login.php';
					update_option ( 'bpmoregisterpageurl', $bpmoregisterpageurl );
				}
		
				$bpopenedpageurl = get_option ( 'saved_open_page_url' );
				if (empty ( $bpopenedpageurl )) {
					$bpopenedpageurl = get_option ( 'siteurl' ) . '/wp-login.php';
				}
				update_option ( 'saved_open_page_url', $bpopenedpageurl );
		
				$bprestrictsbuddypresssection = get_option ( 'bprestrictsbuddypresssection' );
				if (empty ( $bprestrictsbuddypresssection )) {
					update_option ( 'bprestrictsbuddypresssection', 'yes' );
				}
				$bpmoinitsetup = update_option ( 'bpmoinitsetup', $inittime );
			}
	}
}

add_action('init', 'bpmoinit');
