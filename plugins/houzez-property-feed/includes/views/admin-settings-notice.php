<?php
	if ( isset($_GET['hpfsuccessmessage']) && !empty($_GET['hpfsuccessmessage']) )
	{
		echo '<div class="notice notice-success inline"><p>' . esc_html(sanitize_text_field($_GET['hpfsuccessmessage'])) . '</p></div>';
	}
	if ( isset($_GET['hpferrormessage']) && !empty($_GET['hpferrormessage']) )
	{
		echo '<div class="notice notice-error inline"><p>' . esc_html(sanitize_text_field($_GET['hpferrormessage'])) . '</p></div>';
	}

	// show notice if exports exist but no department statuses set
	if ( isset($_GET['page']) && sanitize_text_field($_GET['page']) == 'houzez-property-feed-export' )
	{
		if ( 
			isset($options['exports']) 
			&& 
			!empty($options['exports'])
			&&
			( !isset($options['sales_statuses']) || ( isset($options['sales_statuses']) && empty($options['sales_statuses']) ) )
			&& 
			( !isset($options['lettings_statuses']) || ( isset($options['lettings_statuses']) && empty($options['lettings_statuses']) ) )
		)
		{
			echo '<div class="notice notice-info inline"><p>' . __( 'Please ensure that <a href="' . admin_url('admin.php?page=houzez-property-feed-export&tab=settings') . '">you have specified</a> which statuses determine whether a property is sales or lettings', 'houzezpropertyfeed' ) . '</p></div>';
		}
	}
?>