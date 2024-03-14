<?php


namespace DataPeen\FaceAuth;

use DataPeen\FaceAuth\Helpers;

/**
 * Class LoginPage
 * @package DataPeen\FaceAuth
 */
class LoginPage {


	public static function init()
	{
		new LoginPage();
	}
	public function __construct() {
	    //Only output the face auth if everything is setup
		add_action('login_form',array($this, 'add_login_ui'));
	}

	function add_login_ui(){
		//Output your HTML
        //don't show video login if the page is not on ssl
        if (!is_ssl() || isset($_GET['classic_login']))
            return;


        if (!Helpers::is_site_verified())
            return;
		?>

		<div id="face-factor-login">

			<div id="step-1"> <!-- First step of the chain, get image and username -->
				<div class="camera">
					<video id="video">Video stream not available.</video>
				</div>
				<canvas id="canvas">
				</canvas>
				<div class="output">
					<img id="photo" alt="The screen capture will appear in this box.">
				</div>
				<button class="button-primary button" id="face-factor-login-button">Face login</button>
			</div>


			<div id="step-2"> <!-- second step, confirm the username with user -->
				<p id="confirm-username"></p>
				<button class="button-primary button" id="confirm-username-button"><?php _e('Yes'); ?></button>
				<button class="button-link button" id="reject-username-button"><?php _e('No. That\'s not me.'); ?></button>
				<div class="clearfix"></div>

			</div>

			<div id="step-3">
			</div>

			<div class="clearfix"></div>
            <div><a href="<?php echo wp_login_url(). '?classic_login=1'; ?>" id="use-classic-login">Use password login instead</a></div>

		</div>
		<?php
	}
}
