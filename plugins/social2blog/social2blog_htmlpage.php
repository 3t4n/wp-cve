<?php

if( !defined( 'ABSPATH' ) ) exit;

?>

<script>
jQuery( document ).ready(function($) {

	if(lock_xhr == undefined){
		var lock_xhr = true;

		var url = new URL(window.location.href);
		var access_token = url.searchParams.get("access_token");

		if(access_token != null){

// 			var complete_ac = access[1].split("&");
// 			var access_token = complete_ac[0].split("=");


			
			//console.log(access_token);
			jQuery.ajax({
				type: "POST",
				url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "captureAccessTokenFB",
					access_token:access_token
				},
				success: function(result){
					//console.log(result);
					if (result != 0) {
			        	jQuery("#sp_access_token").html(result);
			        	jQuery("#fb_button").removeClass("button-primary connect-fb");
			        	jQuery("#fb_button").addClass("button-secondary remove-fb");
			        	jQuery("#fb_button").html("Scollega");
 			        	window.location = window.location.href.split('?')[0]+"?page=social2blog";
					}else {
						console.log("access token 0");
					}
		    	},
		    	error: function(err){
		        	console.log("Errore nel catturare access token: "+err);
		        	window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL."&error_message=".__( "Errore salvataggio access token.", "social2blog-text" ) ;?>");
		    	}
		    });
		}
	};
		$( ".connect-fb" ).click(function() {
			window.location.href='<?php echo $social2blogfacebook->linkFBButton();?>';
		});

		$( "#mod_debug" ).change(function() {

			var ilk = jQuery("#mod_debug").val();
			jQuery.ajax({url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "moddebug",
					mod_debug: ilk
				},
				success: function(result){
		    	},
		    	error: function(err){
		        	console.log("Errore modalità debug non settata");
		    	}
		    });
		});
		$( ".remove-fb" ).click(function($) {

			jQuery.ajax({url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "removeAccessTokenFB"
				},
				success: function(result){
					console.log(result);
					if (result != 0) {
						jQuery("#fb_button").html("Collega");
						jQuery("#fb_button").removeClass("button-secondary remove-fb");
						jQuery("#fb_button").addClass("button-primary connect-fb");
						jQuery("#sp_access_token").html("");
						window.location = window.location.href.split("#")[0];
					}else {
						console.log("Access_Token non cancellato");
					}
		    	},
		    	error: function(err){
		    		window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL."&error_message=".__( "Errore nell'eliminazione dell'access token.", "social2blog-text" ) ;?>");
		        	console.log("Errore nell'eliminazione dell'access token: "+err);
		    	}
		    });
		});

		$( "#update_content" ).click(function($) {

			jQuery( ".message_update").text( " <?php echo __("Aggiornamento in backround in corso l'operazione potrebbe richiedere molto tempo",  "social2blog-text" ); ?>");
			jQuery.ajax({url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "updatecontent"
				},
				success: function(result){
				//	console.log(result);
					if (result == "ok")
						jQuery( ".message_update").text( " <?php echo __("Contenuti aggiornati",  "social2blog-text" ); ?>");
					else{
						//jQuery( ".message_update").text("Errore: problemi nell'aggiornamento dei contenuti");
			    		window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL."&error_message=".__( "Errore: problemi nell'aggiornamento dei contenuti.", "social2blog-text" ) ;?>");
					}
		    	},
		    	error: function(err){
		        	console.log("Errore: problemi nell'aggiornamento dei contenuti: "+err);
		    	}
		    });
		});

		$( "#sincronizza_server" ).click(function($) {

			//jQuery( ".message_update").text("Aggiornamento in backround in corso l'operazione potrebbe richiedere molto tempo");
			jQuery.ajax({url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "syncserver"
				},
				success: function(result){
					console.log(result);
 					if (result == "ok"){
						window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL ?>");
 					}
 					else{
// 						//jQuery( ".message_update").text("Errore: problemi nell'aggiornamento dei contenuti");
				   		window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL."&error_message=".__( "Errore: problemi con il server.", "social2blog-text" ) ;?>");
 					}
			    },
			    error: function(err){
			       	console.log("Errore: problemi nell'aggiornamento dei contenuti: "+err);
			    }
			});
		});
		$( ".connect-tw" ).click(function() {
			window.location.href='<?php echo $social2blogtwitter->linkTWButton();?>';
		});
		$( ".remove-tw" ).click(function($) {

			jQuery.ajax({url: "<?php echo admin_url()?>admin-ajax.php",
				data: {
					action: "removeOAuthTokenTW"
				},
				success: function(result){
					console.log(result);
					if (result != 0) {
						jQuery("#tw_button").html("Collega");
						jQuery("#tw_button").removeClass("button-secondary remove-tw");
						jQuery("#tw_button").addClass("button-primary connect-ftw");
						jQuery("#sp_oauth_token").html("");
						location.reload();
					}else {
						console.log("OAuth_token non cancellato");
					}
		    	},
		    	error: function(err){
		    		window.location.replace("<?php echo SOCIAL2BLOG_LOCALURL."&error_message=".__( "Errore nell'eliminazione dell'OAuth_token.", "social2blog-text" ) ;?>");
		        	console.log("Errore nell'eliminazione dell'OAuth_token: "+err);
		    	}
		    });
		});
});

//})();
</script>
<div class="wrap">
<h1 class="title_xwp"><img src='<?php echo plugin_dir_url( __FILE__ )."icon.png"?>' style="margin-right: 10px" />
	<span class="logoso">Social</span><span class="logo2">2</span><span class="logowp">Blog</span></h1></h1>
	<h2 class="nav-tab-wrapper">
		<a href="<?php echo SOCIAL2BLOG_LOCALURL?>" class="nav-tab nav-tab-active"><?php echo __( 'Generale', 'social2blog-text' )?></a>
		<a <?php echo $social2blogfacebook->isFBConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-facebook" : ""?> class="nav-tab nav-tab">Facebook</a>
		<a <?php echo $social2blogtwitter->isTWConnected() ? "href=".SOCIAL2BLOG_LOCALURL."-twitter" : ""?> class="nav-tab nav-tab">Twitter</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-instagram"; ?>  class="nav-tab nav-tab">Instagram</a>
		<a <?php echo "href=".SOCIAL2BLOG_LOCALURL."-gallery"; ?>  class="nav-tab nav-tab">Gallery</a>
	</h2>

<?php
$has_fb_tag = $social2blogfacebook->getTags();
$has_tw_tag = $social2blogtwitter->getTags();

$twisconnect = $social2blogtwitter->isTWConnected();
$fbisconnect = $social2blogfacebook->isFBConnected();


$state_secure = (social2blog_retrievestate() === "1") ? false : true;

if ( (!empty($has_fb_tag) or !empty($has_tw_tag) ) and $state_secure ){

	if (!$twisconnect and !$fbisconnect) {

	} else { ?>

		<h3 class="title_xwp"><?php echo __("Aggiornamento contenuti", "social2blog-text") ?></h3>
		<div class="postbox s2b-postbox">
			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __("Scarica i contenuti ora", "social2blog-text")?>:</b>
				</div>
				<div class="input_wrapper">
						<button id="update_content" type="button"  class="primary button"><?php echo __("Aggiorna contenuti", "social2blog-text")?></button>
				</div>
				<div class="message_update"></div>
				<?php
					if ( (empty($has_fb_tag) and $fbisconnect) or (empty($has_tw_tag) and $twisconnect) ){ ?>
					<div class="warning-update"><?php echo __("Social senza #Hashtag. Inserire gli #Hashtag per scaricare correttamente i contenuti.", "social2blog-text") ?></div>
				<?php
					}
				?>
			</div>
		</div>

<?php  } }elseif (!$state_secure){ ?>
		<h3 class="title_xwp"><?php echo __("Sincronizza Server", "social2blog-text") ?></h3>
		<div class="postbox s2b-postbox">
					<div class="inside_xwp">
			<div class="text_label">
				<b><?php echo __("Sincronizza con il server", "social2blog-text")?>:</b>
			</div>
			<div class="input_wrapper">
					<button id="sincronizza_server" type="button"  class="primary button"><?php echo __("Sincronizza Server", "social2blog-text")?></button>
			</div>
			<div class="message_sinc_server"></div>
			</div>
		</div>
<?php  } else {
		if ( (empty($has_fb_tag) and $fbisconnect) or (empty($has_tw_tag) and $twisconnect) ){
		?>
		<h3 class="title_xwp"><?php echo __("Aggiornamento contenuti", "social2blog-text") ?></h3>
		<div class="postbox s2b-postbox">
			<div class="inside_xwp">

					<div class="warning-update"><?php echo __("Social senza #Hashtag. Inserire gli #Hashtag per scaricare correttamente i contenuti.", "social2blog-text") ?></div>
			</div>
		</div>
		<?php
					}

}?>

	<h3 class="title_xwp"><?php echo __("Collega Social Network", "social2blog-text") ?></h3>
		<!-- Facebook -->
	<div id="sp_facebook" class="postbox s2b-postbox">
		<h1 class="hndle">
			<span class="subtitle_xwp">Facebook</span>
		</h1>

		<div class="inside_xwp">
			<div class="text_label">
				<b>Facebook:</b>
			</div>
			<div class="input_wrapper">
				<?php if( $social2blogfacebook->isFBConnected() ){ ?>
					<button id="fb_button" type="button" class="<?php echo $social2blogfacebook->classFBButton();?>" name="sp_facebook" ><?php echo $social2blogfacebook->textButton();?></button>
				<?php } else { ?>
					<button id="fb_button" type="button" class="<?php echo $social2blogfacebook->classFBButton();?> login-fb" name="sp_facebook" >Login with Facebook</button>
				<?php }  ?>
			</div>
		</div>
		<div class="inside_xwp">
			<div class="text_label_AT">
				<b>Access Token:</b>
			</div>
			<div class="input_wrapper">
			<input type="text" name="sp_access_token" class="input_xwp" id="sp_access_token" value="<?php if( $social2blogfacebook->isFBConnected() ){
																											echo substr($social2blogfacebook->getAccess_token(), 0, (strlen( $social2blogfacebook->getAccess_token() )/4) )."[...]";
																											}?>" readonly></input>

			

			</div>
		</div>
		<div class="inside_xwp">
			<div class="input_wrapper">
				<?php if ($social2blogfacebook->getAccess_token_expire() == "∞"): ?>
								<b>Access Token expire date:</b> <?php echo $social2blogfacebook->getAccess_token_expire(); ?>
				<?php else: ?>				
				<b>Access Token expire date:</b> <?php echo date('l jS \of F Y', $social2blogfacebook->getAccess_token_expire()); ?>
				<?php endif;?>
			</div>
		</div>
		<?php if( empty($has_fb_tag) and $fbisconnect ) { ?>
			<div class="inside_xwp">
				<div class="input_wrapper">
					<img src='<?php echo plugin_dir_url( __FILE__ )."setting.png"?>' align="center" style="margin:5px;">
					<strong><?php echo __("Facebook is connected", "social2blog-text") ?></strong> <?php echo __("Please", "social2blog-text") ?> <a href="<?php echo SOCIAL2BLOG_LOCALURL."-facebook"; ?>"><strong><?php echo __("complete configuration", "social2blog-text") ?></strong></a> <?php echo __("to get your posts!", "social2blog-text") ?>
				</div>
	   	</div>
		<?php } ?>
	</div>

<!-- Twitter -->
	<div id="sp_twitter" class="postbox s2b-postbox">
		<h1 class="hndle">
			<span class="subtitle_xwp">Twitter</span>
		</h1>

		<div class="inside_xwp">
			<div class="text_label">
				<b>Twitter:</b>
			</div>
			<div class="input_wrapper">
				<button id="tw_button" type="button" class="<?php echo $social2blogtwitter->classTWButton();?>" name="sp_twitter" ><?php echo $social2blogtwitter->textButton();?></button>
			</div>
		</div>
		<div class="inside_xwp">
			<div class="text_label_AT">
				<b>Access Token:</b>
			</div>
			<div class="input_wrapper">
			<input type="text" name="sp_access_token" class="input_xwp" id="sp_access_token" value="<?php if( $social2blogtwitter->isTWConnected() ){
																										echo substr($social2blogtwitter->retrieveOAuthToken(), 0, (strlen( $social2blogtwitter->retrieveOAuthToken() )/1.1) )."[...]";}
																								?>" readonly></input>
			</div>
		</div>
		<?php if( empty($has_tw_tag) and $twisconnect ) { ?>
			<div class="inside_xwp">
				<div class="input_wrapper">
					<img src='<?php echo plugin_dir_url( __FILE__ )."setting.png"?>' align="center" style="margin:5px;">
					<strong><?php echo __("Twitter is connected", "social2blog-text") ?></strong> <?php echo __("Please", "social2blog-text") ?> <a href="<?php echo SOCIAL2BLOG_LOCALURL."-twitter"; ?>"><strong><?php echo __("complete configuration", "social2blog-text") ?></strong></a> <?php echo __("to get your posts!", "social2blog-text") ?>
				</div>
			</div>
		<?php } ?>
	  </div>

	<h3 class="title_xwp"><?php echo __("Opzioni", "social2blog-text")?></h3>


		<div class="postbox s2b-postbox">
			<div class="inside_xwp">
				<div class="text_label">
					<b><?php echo __("Debug mode", "social2blog-text")?>:</b>
				</div>
				<div class="input_wrapper">
					<select id="mod_debug">
					<option <?php if (SOCIAL2BLOG_DEBUG == 0) echo "selected" ?>value="0"><?php echo __("Disattiva", "social2blog-text")?></option>
					<option <?php if (SOCIAL2BLOG_DEBUG == 1) echo "selected" ?> value="1"><?php echo __("Attiva", "social2blog-text")?></option>
					</select>
				</div>

				<div class="text_label">
					<b>Api key:</b>
				</div>
				<div class="input_wrapper"><?php echo get_option('social2blog_apikey'); ?></div>
			</div>
		</div>
</div>
