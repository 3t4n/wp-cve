<?php
/*
 Plugin Name: Casper's Leave Notice
 Plugin URI: https://wordpress.org/plugins/caspers-leave-notice/
 Description: Warns your users when they are leaving your site. You can edit the content of the warning, as well as add exceptions.
 Version: 1.2.3
 Author: Casey James Perno
 Author URI: https://www.caseyjamesperno.com
 License: GPL2
*/

/******* enqueue scripts, styles, and theme/common plugin support *******/
require_once( plugin_dir_path( __FILE__ ) . 'functions/scripts-and-support.php' );
/******* create db options/settings *******/
require_once( plugin_dir_path( __FILE__ ) . 'functions/options.php' );
/******* create the admin page and menu item *******/
require_once( plugin_dir_path( __FILE__ ) . 'functions/admin/admin-page.php' );

function cpln_setup_db_values(){
	//set default values for cpln_content_settings
	if(get_option('cpln_content_settings') == FALSE){
		$siteName = get_bloginfo('name');
		$titleCopy = 'You are now leaving ' . get_bloginfo('name');
		$bodyCopy = $siteName . ' provides links to web sites of other organizations in order to provide visitors with certain information. A link does not constitute an endorsement of content, viewpoint, policies, products or services of that web site. Once you link to another web site not maintained by ' . $siteName . ', you are subject to the terms and conditions of that web site, including but not limited to its privacy policy.';
	
		$cpln_defaults = array(
			'cpln_title_content'	=>	$titleCopy,
			'cpln_body_content'	=>	$bodyCopy
		);
	
		update_option('cpln_content_settings', $cpln_defaults );
	}
}
register_activation_hook( __FILE__, 'cpln_setup_db_values');

//set up notice and content
function cpln_insert_markup(){ 
	$options = get_option('cpln_content_settings');
	$titleCopy = $options['cpln_title_content'];
	$bodyCopy = $options['cpln_body_content']; 
	
	/**
	 * If Auto-Redirect enabled, do two things:
	 * 1) redirect to the intended URL after set amount of time
	 * 2) include a message and countdown on the popup showing how much time is left
	 */
	$timerOptions = get_option('cpln_other_settings');
	$timerEnabled = isset($timerOptions['cpln_redirect_timer_bool']) && $timerOptions['cpln_redirect_timer_bool'];
//	var_dump($timerOptions);
	?>
    
	<div class="cpln-leavenotice">
    	<div class="cpln-position">
        	<div class="cpln-overlay"></div>
            <div class="cpln-tb">
            	<div class="cpln-td">
                	<div class="cpln-content">
                    	<h2><?php echo $titleCopy ?></h2>
                        <p><?php echo $bodyCopy ?></p>
                        <div class="cpln-redirect-box">
							<div class="cpln-redirect-box__content">You will be redirected to<div class="cpln-redirect-link"></div></div>
							<?php //If auto-redirect set true, display countdown
								if($timerEnabled){
									$timer = $timerOptions['cpln_redirect_time'];
									$html = '<div class="cpln-redirect-box__countdown">in ';
									$html .= '<span class="cpln-redirect-box__time" data-start-time="'.$timer.'">';
									$html .= $timer;
									$html .= '</span>';
									$html .= ' seconds...</div>';
									echo $html;
								}
							?>
                        </div>
                        <p>Click the link above to continue or <a class="cpln-cancel" href="#">CANCEL</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php }
add_action('wp_footer','cpln_insert_markup');

//set up domains to exclude universally
function cpln_insert_exclusions(){

	//get data from the dashboard textarea
	$options = get_option('cpln_exclusions');
	$exclusions = $options['cpln_exclusion_list'];
	//strip out whitespace/spaces
	$exclusions = preg_replace('/\s/', '', $exclusions);
	//convert to array, splitting at commas
	$exclusions = explode(",", $exclusions);
	
	//output the following if there is any data...
	if( count($exclusions) > 0 ) {
	?>
    <ul class="cpln-exclude-list" style="display: none;">
        <?php for( $i = 0; $i < count($exclusions); $i++ ) { ?>
			<li class="cpln-exclude"><?php echo $exclusions[$i]; ?></li>
		<?php } ?>
	</ul>
<?php }
}
if( get_option('cpln_exclusions') ){
	add_action('wp_footer','cpln_insert_exclusions');
}