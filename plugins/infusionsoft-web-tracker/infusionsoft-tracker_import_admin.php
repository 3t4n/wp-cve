<style>
input.infusionWebTrackerInput{
    text-align:right;
}


</style>

<?php 
		if($_POST['infusionsoft_tracker_hidden'] == 'Y') {
			//Form data sent
			$apiKey = $_POST['infusionsoft_tracker_apiKey'];
			$appName = $_POST['infusionsoft_tracker_appName'];
			//Test Connection
			require_once('isdk.php');
			// force to download a file #create connection    
			$myApp = new iSDK;
		    
			if ($myApp->connectWithVars($appName, $apiKey, 'i')) {
			    
			    update_option('infusionsoft_tracker_appName', $appName);
			    update_option('infusionsoft_tracker_apiKey', $apiKey);
			    
			    $result = $myApp->getWebTrackingServiceTag();
			    
			    update_option('infusionsoft_tracker_scriptTag', $result);
			    
			    ?>
			    <div class="updated"><p><strong><?php _e('Options saved.' ); ?></strong></p></div>
			    <?php
			}
			else{
			    ?>
			    <div class="updated"><p><strong><?php _e('Error Connecting - Please Try Again' ); ?></strong></p></div>
			    <?php
			}
			
?>
			
			<?php
		} else {
			//Normal page display
                        
                        $appName = get_option('infusionsoft_tracker_appName');
			$apiKey = get_option('infusionsoft_tracker_apiKey');
			
		}
	?>
<style>
    .panel{
	background: #f6f6f6;
	width: 280px;
	height: 200px;
	display: block;
	float: left;
	border: 2px groove #f6f6f6;
	padding: 20px;
    }
    #about{
	width: 598px;
	display: block;
	clear: both;
    }
</style>
<div class="wrap">
    <img alt="Infusionsoft" src="http://help.infusionsoft.com/sites/all/themes/help_infusion12/logo.png">
    <?php    echo "<h2>" . __( 'Infusionsoft Analytics for WordPress', 'infusionsoft_tracker_trdom' ) . "</h2>"; ?>
    <p>Infusionsoft Analytics for WordPress inserts the web tracking code from your Infusionsoft application into your WordPress site.  This is the only officially supported plugin for WordPress that does this.</p>
    <div class="panel" id="apiPanel">
	<h3>Enter Your API Details</h3>		
	<form name="infusionsoft_tracker_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	    <input type="hidden" name="infusionsoft_tracker_hidden" value="Y">
	    
	    <p><?php _e("Infusionsoft Application Name: " ); ?></p>
	    <input class='infusionsoft_tracker' type="text" name="infusionsoft_tracker_appName" value="<?php echo $appName; ?>" size="16"><?php _e(".infusionsoft.com" ); ?>
	    <p><?php _e("Infusionsoft API Key: " ); ?></p>
	    <input class='infusionsoft_trackerInput' type="password" name="infusionsoft_tracker_apiKey" value="<?php echo $apiKey; ?>" size="32"><br />
	    <input type="submit" name="Submit" value="<?php _e('Save', 'infusionsoft_tracker_trdom' ) ?>" />
	
	</form>
    </div>
    <div class="panel" id="video">
	<h3>Need Help?</h3>
	<p>Check out this article in the Infusionsoft User Guide:</p>
	<a href="http://ug.infusionsoft.com/article/AA-01117/178/Marketing/Web-Tracking-Analytics/How-do-I-add-the-Infusionsoft-tracking-code-to-a-WordPress-website.html" target="_blank">Click here for more info</a>
	<p>Check out this article if you need help getting your API Key</p>
	<a href="http://ug.infusionsoft.com/article/AA-00442/0" target="_blank">Click here for more info</a>
    </div>
    <div class="panel" id="about">
	<h3>What is Infusionsoft Analytics?</h3>
	<p>Infusionsoft's Web Analytics allows you to keep tabs on the online actions of individual visitors. You can also track their online activity to get a comprehensive view of their interests and online behaviors.</p>
	<p>It also allows you to gather data about how individual pages on your site are performing. Once you know what's working and what's not, you can optimize your website to increase conversions. </p>
	<p>When people opt-in, a complete history of the pages they've visited on your site will be tied to their contact record.</p>
    </div>
    
</div>
	
