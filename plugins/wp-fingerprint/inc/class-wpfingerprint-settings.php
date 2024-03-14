<?php
class WPFingerprint_Settings{

	private $template_path;
	private $slug = 'wp-fingerprint';
	private $tite = 'Security & Integrity';


	function __construct( $template_path )
	{
		$this->template_path = rtrim($template_path, '/');
	}

	function configure( )
	{
		register_setting($this->get_slug, 'wp_fingerprint_option');

		add_settings_section(
            $this->get_slug() . '-section',
            __('Integirty Check', 'wp_fingerprint'),
            array($this, 'render_section'),
            $this->get_slug()
        );
	}

	public function render_section()
  {
  	$this->render_template('section');
  }

	private function render_template( $template )
	{
		$template_path = $this->template_path . '/' . $template . '.php';
		if( is_readable( $template_path ) )
		{
			include $template_path;
		}
	}

	public static function admin_bar_menu($admin_bar){

		global $wp_admin_bar;
		//Do we have any issues?
		$invalid_plugins = array('results' => null);
		$notification_count = get_option('wpfingerprint_fails');
		$notificaton = '';
		if($notification_count >= 1){

			$notificaton = '<div class="wp-core-ui wp-ui-notification wpfingeprint-update-count" style="display:inline;padding:1px 7px 1px 6px!important;border-radius:50%;color:#fff"><span>'.$notification_count.'</span></div>';
		}
		//Hardcoded for now
		$title = "<span>WP Fingerprint </span>". $notificaton;
		//Generate main menu
		$wp_admin_bar->add_menu( array(
			'id'    => 'wp-fingerprint-menu',
			'title' => $title,
			'href'  => '',
			'meta'  => array( 'tabindex' => '' ),
		) );
			$last_check = time() - get_option('wpfingerprint_last_run');
			if($last_check < 60 ){
				$last_check = $last_check.' seconds ago';
			}else{
				$last_check = floor($last_check/60);
				$last_check = $last_check.' minutes ago';
			}
			if($notification_count >= 1){
				$wp_admin_bar->add_menu( array(
					'parent' => 'wp-fingerprint-menu',
					'id'     => 'wp-fingerprint-notifications-count',
					'title'  => __( 'You have '.$notification_count . ' reported issues. click here for details', 'wp-fingerprint' ),
					'href'   => 'plugins.php',
					'meta'   => array( 'tabindex' => false ),
				) );
			}
			$wp_admin_bar->add_menu( array(
				'parent' => 'wp-fingerprint-menu',
				'id'     => 'wp-fingerprint-notifications',
				'title'  => __( 'WP Fingerprint is protecting your site last checked '.$last_check, 'wp-fingerprint' ),
				'href'   => '',
				'meta'   => array( 'tabindex' => false ),
			) );
			$wp_admin_bar->add_menu( array(
				'parent' => 'wp-fingerprint-menu',
				'id'     => 'wp-fingerprint-recheck',
				'title'  => __( 'Recheck' ),
				'href'   => '#',
				'meta'   => array( 'tabindex' => false ),
			) );

	}

	public static function notices ($plugin_file, $plugin_data, $status )
	{
		$model = new WPFingerprint_Model_Checksums();
		$files = array();
		if(isset($plugin_data['slug']) && isset($plugin_data['Version']))
		{
			$files = $model->get($plugin_data['slug'],$plugin_data['Version']);
		}
		if( !isset($files) || empty($files) ) return;
			$top = '';
			$top .= '<tr id="wpfingerprint-warning" class="plugin-update update-message notice notice-warning notice-alt">';
			$top .=  '<td colspan="3" class="plugin-update colspanchange">';
			$top .=  '<div class="warning inline warning-error warning-alt">';
			$top .=  '<p><strong>WARNING</strong> - WP Fingerprint has detected that the following files may have been tampered with in '.$plugin_data['Name'].'</p>';
			$return = FALSE;
			foreach( $files as $file)
			{
				if($file->plugin == $plugin_data['slug']){
					$return .=  '<p>';
					$return .=  esc_html($file->filename);
					$return .= sprintf( esc_html__( " does not match checksums on %s.", "wpfingerprint"), $file->source );
					$return .= '</p>';
				}
			}
				$bottom = '';
				$bottom .= sprintf( esc_html__( "Last Check: %s", 'wpfingerint'), $file->last_checked );
				$bottom .= '<span style="float:right;"><a href="https://wpfingerprint.com/dealing-with-hacked-wordpress-plugins/">What does this mean?</a></span>';
				$bottom .=  '</div></td></tr>';
				if(isset($return) && $return != FALSE){
					$return = apply_filters('wpfingerprint_output', $return, $plugin_data);
					echo $top.$return.$bottom;
				}
	}

	function notice_type($results)
	{
		$return = false;
		if(array_search('malicious', array_column($results,'type'))) $return = true;
		return $return;
	}

	function recheck_callback()
	{
		wp_schedule_single_event(time(), 'wpfingerprint_run_now');
		echo 'true';
		wp_die();
	}

	public static function admin_bar_footer_js()
	{
		?>
			<script type="text/javascript" >
				 jQuery("li#wp-admin-bar-wp-fingerprint-recheck .ab-item").on( "click", function() {
						var data = {
													'action': 'wp-fingerprint-recheck',
												};
						/* since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php */
						jQuery.post(ajaxurl, data, function(response) {
							 jQuery('#wpbody-content').prepend('<div class="notice notice-success is-dismissible"><p>Queued WP Fingerprint to recheck ALL plugins!</p></div>');
						});

					});
			</script>
		<?php
	}

}
