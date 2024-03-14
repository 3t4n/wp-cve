<?php 
add_action('directorypress_dashboad_panel', 'directorypress_dashboad_panel');
function directorypress_dashboad_panel(){
	$directorypress_theme = wp_get_theme();
	$theme_version = $directorypress_theme->get( 'Version' );
	$theme_name = $directorypress_theme->get( 'Name' );
	$mem_limit = ini_get('memory_limit');
	$mem_limit_byte = wp_convert_hr_to_bytes($mem_limit);
	$upload_max_filesize = ini_get('upload_max_filesize');
	$upload_max_filesize_byte = wp_convert_hr_to_bytes($upload_max_filesize);
	$post_max_size = ini_get('post_max_size');
	$post_max_size_byte = wp_convert_hr_to_bytes($post_max_size);
	$mem_limit_byte_boolean = ($mem_limit_byte < 268435456);
	$upload_max_filesize_byte_boolean = ($upload_max_filesize_byte < 67108864);
	$post_max_size_byte_boolean = ($post_max_size_byte < 67108864);
	$execution_time = ini_get('max_execution_time');
	$execution_time_boolean = ($execution_time < 180);
	$input_vars = ini_get('max_input_vars');
	$input_vars_boolean = ($input_vars < 2000);
	$input_time = ini_get('max_input_time');
	$input_time_boolean = ($input_time < 1000);
	if( class_exists('ZipArchive', false) == false ){
		$ziparchive = 'Disabled';
	} else {
		$ziparchive = 'Enabled';
	}
?>
<div class="wrap about-wrap directorypress-admin-wrap">
	<?php DirectoryPress_Admin_Panel::listing_dashboard_header(); ?>
	<div id="directorypress-dashboard" class="wrap about-wrap directorypress-admin-main">
				<div class="directorypress-box-head">
					<h1><?php _e('Welcome To DirectoryPress', 'DIRECTORYPRESS'); ?></h1>
					<p><?php _e('A very warm welcome to our  respected users for joining the family of DirectoryPress which provides a complete 
solution for the directory listing.', 'DIRECTORYPRESS'); ?></p>
				</div>
		<div class="container">
			<div class="row services-row">
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="panel-Service-box green">
							<div class="panel-service-icon"><i class="directorypress-flaticon-document58"></i></div>
							<div class="panel-service-title"><?php echo esc_html__('Documentation', 'DIRECTORYPRESS'); ?></div>
							<div class="panel-service-content">
								<p><?php echo esc_html__('Since version 3.0, We are providing complete documentation online. Our detailed knowledge base is ready to answer your all queries. Please visit our knowledge base below', 'DIRECTORYPRESS'); ?></p>
								<a href="https://help.designinvento.net/docs/directorypress/" target="_blank"><?php echo esc_html__('knowledge base', 'DIRECTORYPRESS'); ?></a>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="panel-Service-box blue">
							<div class="panel-service-icon"><i class="directorypress-flaticon-document58"></i></div>
							<div class="panel-service-title"><?php echo esc_html__('Support Desk', 'DIRECTORYPRESS'); ?></div>
							<div class="panel-service-content">
								<p><?php echo esc_html__('Although our knowledge base provide complete solutions to any of your query, But do not worry if there is still any problem. You can contact our Premium Support Desk below', 'DIRECTORYPRESS'); ?></p>
								<a href="http://help.designinvento.net/support" target="_blank"><?php echo esc_html__('Support Desk', 'DIRECTORYPRESS'); ?></a>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<div class="panel-Service-box pink">
							<div class="panel-service-icon"><i class="directorypress-flaticon-document58"></i></div>
							<div class="panel-service-title"><?php echo esc_html__('Suggestions', 'DIRECTORYPRESS'); ?></div>
							<div class="panel-service-content">
								<p><?php echo esc_html__('Since version 3.0 Directorypress offer ultimate feature and flexibility, But we are still open for any feature suggestion. you can send us your suggestions by filling the form below', 'DIRECTORYPRESS'); ?></p>
								<a href="http://help.designinvento.net/feature-suggestions" target="_blank"><?php echo esc_html__('Feature Suggestions', 'DIRECTORYPRESS'); ?></a>
							</div>
						</div>
					</div>
					<div class="col-md-4 col-sm-6 col-xs-12">
						<a href="" target="_blank"><img src="<?php echo esc_url(DIRECTORYPRESS_URL .'admin/assets/images/themes.png'); ?>" alt="premium themes" /></a>
					</div>
			</div>
		</div>
	</div>

</div>
<?php }
