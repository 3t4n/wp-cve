<?php
function acx_popunder_adv_styles() 
{
	wp_register_style('acx_popunder_adv_script', plugins_url('style_admin.css', __FILE__)); 
	wp_enqueue_style('acx_popunder_adv_script');   			
}
add_action('admin_enqueue_scripts', 'acx_popunder_adv_styles'); 

function acx_onclick_popunder_comparison($ad=2)
{
$ad_1 = '
</hr>
<a name="compare"></a><div id="ss_middle_wrapper"> 
		<div id="ss_middle_center"> 
			<div id="ss_middle_inline_block"> 
				<div class="middle_h2_1"> 
					<h2>Limited on Features ?</h2>
					<h3>Compare and Decide</h3>
				</div><!-- middle_h2_1 -->
				
		<div id="ss_features_table"> 
			<div id="ss_table_header"> 
				<div class="tb_h1"> <h3>Feature Group</h3> </div><!-- tb_h1 -->
					<div class="tb_h2"> <h3>Features</h3> </div><!-- tb_h2 -->
					<div class="tb_h3"> <div class="ss_download"> </div><!-- ss_download --> </div><!-- tb_h3 -->
				<div class="tb_h4_ocpu onclick_popunder_tb_h4"> <a href="http://clients.acurax.com/onclick-popunder.php?utm_source=plugin&utm_medium=link_top&utm_campaign=comparison" target="_blank"><div class="ss_buy_now"> </div><!-- ss_buy_now --></a> </div><!-- tb_h4 -->
					</div><!-- ss_table_header -->
						
					<div class="ss_column_holder"> 
					
						<div class="tb_h1 mini"> <h3>Feature Group</h3> </div><!-- tb_h1 -->
						<div class="ss_feature_group" style="padding-top: 180px;"> Functionality
						</div><!-- -->
						<div class="tb_h1 mini"> <h3>Features</h3> </div><!-- tb_h1 -->
						<div class="ss_features"> 
							<ul>
								<li>Can add any number of Urls</li>
									<li>Can configure pop under repeat interval</li>
										<li>Define Number of Impressions Needed for Each URL</li>
										<li>Automated URL Switching Depends on Impressions </li>
									<li>Auto Exclude Fulfilled Impression URLs</li>
									<li>Edit a Configured PopUnder URL</li>
									<li>Update a Configured URL Impressions Needed</li>
									<li>Update a Configured URL Current Impressions Count</li>
									<li>Activate/Deactivate a Configured URL</li>
								<li class="ss_last_one">Disable PopUnder on Mobile Devices</li>
							</ul>
						</div><!-- ss_features -->
						
						<div class="tb_h1 mini"> <h3>FREE &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <span style="color: #ffe400;">PREMIUM</span></h3> </div><!-- tb_h1 -->
						<div class="ss_y_n_holder"> 
								<div class="ss_yes"> </div><!-- ss_yes -->
									<div class="ss_yes"> </div><!-- ss_yes -->
									<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
								<div class="ss_no"> </div><!-- ss_no -->
							<div class="ss_no ss_last_one"> </div><!-- ss_no -->
						</div><!-- ss_y_n_holder -->
						
						<div class="ss_y_n_holder"> 
							<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
									<div class="ss_yes"> </div><!-- ss_yes -->
									<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
								<div class="ss_yes"> </div><!-- ss_yes -->
							<div class="ss_yes ss_last_one"> </div><!-- ss_yes -->
						</div><!-- ss_y_n_holder -->						
						
					</div><!-- column_holder -->
					
					<div class="ss_column_holder"> 
					
						<div class="tb_h1 mini"> <h3>Feature Group</h3> </div><!-- tb_h1 -->
						<div class="ss_feature_group" style="padding-top: 72px;"> Easy to configure </div><!-- -->
						<div class="tb_h1 mini"> <h3>Features</h3> </div><!-- tb_h1 -->
						<div class="ss_features"> 
							<ul>
								<li>Simple Configuration</li>
								<li>Improved User Interface</li>
								<li>Disable URL Without Deleting</li>
								<li class="ss_last_one">Efficient URL Validation</li>
							</ul>
						</div><!-- ss_features -->
						
						<div class="tb_h1 mini"> <h3>FREE &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <span style="color: #ffe400;">PREMIUM</span></h3> </div><!-- tb_h1 -->
						<div class="ss_y_n_holder"> 
							<div class="ss_yes"> </div><!-- ss_yes -->
							<div class="ss_no"> </div><!-- ss_no -->
							<div class="ss_no"> </div><!-- ss_no -->
							<div class="ss_yes ss_last_one"> </div><!-- ss_yes -->
						</div><!-- ss_y_n_holder -->
						
						<div class="ss_y_n_holder"> 
							<div class="ss_yes"> </div><!-- ss_yes -->
							<div class="ss_yes"> </div><!-- ss_yes -->
							<div class="ss_yes"> </div><!-- ss_yes -->
							<div class="ss_yes ss_last_one"> </div><!-- ss_yes -->
						</div><!-- ss_y_n_holder -->						
						
					</div><!-- column_holder -->			
	
				</div><!-- ss_features_table -->		
			<div id="ad_onclick_popunder_2_button_order" style="float: left; width: 100%;">
<a href="http://clients.acurax.com/onclick-popunder.php?utm_source=plugin&utm_medium=link_bottom_1&utm_campaign=comparison" target="_blank"><div id="ad_onclick_popunder_2_button_order_link"></div></a></div> <!-- ad_onclick_popunder_2_button_order --></div></div></div>';
$ad_2='<div id="ad_onclick_popunder_2"> <a href="http://clients.acurax.com/onclick-popunder.php?utm_source=plugin&utm_medium=link_bottom_2&utm_campaign=comparison" target="_blank">
<div id="ad_onclick_popunder_2_button"></div></a> </div> <!-- ad_onclick_popunder_2 --><br>
<div id="ad_onclick_popunder_2_button_order">
<a href="http://clients.acurax.com/onclick-popunder.php?utm_source=plugin&utm_medium=link_bottom_3&utm_campaign=comparison" target="_blank"><div id="ad_onclick_popunder_2_button_order_link"></div></a></div>
<!-- ad_onclick_popunder_2_button_order --> ';
if($ad=="" || $ad == 2) { echo $ad_2; } else if ($ad == 1) { echo $ad_1; } else { echo $ad_2; } 
} // Updated
function acx_popunder_pluign_promotion()
{
$acx_tweet_text_array = array
						(
						"I Use Onclick Popunder wordpress plugin from @acuraxdotcom and you should too",
						"Onclick Popunder wordpress Plugin from @acuraxdotcom is Awesome",
						"Thanks @acuraxdotcom for developing such a wonderful Popunder wordpress plugin",
						"Actually i am looking for a Popunder Plugin like this. Thanks @acuraxdotcom",
						"Its very nice to use Onclick Popunder wordpress Plugin from @acuraxdotcom",
						"I installed Onclick Popunder.. from @acuraxdotcom,  It works wonderful",
						"The Onclick Popunder wordpress plugin looks soo nice.. thanks @acuraxdotcom", 
						"It awesome to use Onclick Popunder wordpress plugin from @acuraxdotcom",
						"Onclick Popunder wordpress Plugin that i use Looks awesome and works terrific",
						"I am using Onclick Popunder wordpress Plugin from @acuraxdotcom I like it!",
						"The Popunder plugin from @acuraxdotcom Its simple looks good and works fine",
						"Ive been using this  Popunder plugin for a while now and it is attractive",
						"Onclick Popunder wordpress plugin is Fantastic Plugin",
						"Onclick Popunder wordpress plugin was easy to use and works great. thank you!",
						"Good and flexible wp Onclick Popunder plugin especially for beginners.",
						"Easily the best Onclick Popunder wordpress plugin of the type I have used ! THANKS! @acuraxdotcom",
						);
$acx_tweet_text = array_rand($acx_tweet_text_array, 1);
$acx_tweet_text = $acx_tweet_text_array[$acx_tweet_text];

    echo '<div id="acx_td" class="error" style="background: none repeat scroll 0pt 0pt infobackground; border: 1px solid inactivecaption; padding: 5px;line-height:16px;">
	<p>It looks like you have been enjoying using Onclick Popunder plugin from <a href="http://www.acurax.com?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ocpu" title="Acurax Web Designing Company" target="_blank">Acurax</a> for atleast 30 days.Would you consider upgrading to <a href="http://clients.acurax.com/onclick-popunder.php/?utm_source=plugin&utm_medium=thirtyday_yellow&utm_campaign=ocpu" title="Premium Onclick popunder" target="_blank">premium version</a> to enjoy more features and help support continued development of the plugin? - Spreading the world about this plugin. Thank you for using the plugin</p>
	<p>
	<a href="https://wordpress.org/support/view/plugin-reviews/acurax-on-click-pop-under" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Rate it 5?\'s on wordpress</a>
	<a href="https://twitter.com/share?url=http://www.acurax.com/products/acurax-click-pop-plugin-wordpress/&text='.$acx_tweet_text.' -" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Tell Your Followers</a>
	<a href="http://clients.acurax.com/onclick-popunder.php?utm_source=plugin&utm_medium=thirtyday&utm_campaign=ocpu" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;" target="_blank">Order Premium Version</a>
	<a onclick="acx_popunder_show_msg();" class="button" style="color:black;text-decoration:none;padding:5px;margin-right:4px;margin-left:20px;">Don\'t Show This Again</a>
	</p>
		</div>';?>
		<div class="error" id="acx_msg" style="display:none; background: none repeat scroll 0pt 0pt infobackground; border: 1px solid inactivecaption; padding: 5px;line-height:16px;">
		Thanks again for using the plugin. we will never show the message again.
		</div>
<script>
function acx_popunder_show_msg()
{
	var order = '&action=acurax_popunder_show_msg'; 
	jQuery.post(ajaxurl, order, function(theResponse)
	{
		document.getElementById("acx_td").style.display = "none";
		document.getElementById("acx_msg").style.display = "block";
	});
} 
</script>
<?php
}
$acurax_popunder_installed_date = get_option('acurax_popunder_installed_date');
if ($acurax_popunder_installed_date == "") { $acurax_popunder_installed_date = time();}
if($acurax_popunder_installed_date < ( time() - 2952000 ))
{
	if (get_option('acurax_popunder_td') != "hide")
	{
		add_action('admin_notices', 'acx_popunder_pluign_promotion');
	}
}
function acurax_popunder_show_msg_callback(){
update_option('acurax_popunder_td', "hide");
	die(); // this is required to return a proper result	
}add_action('wp_ajax_acurax_popunder_show_msg', 'acurax_popunder_show_msg_callback');
function acx_popunder_pluign_finish_version_update()
{
    echo '<div id="message" class="updated">
		  <p><b>Thanks for updating Onclick Popunder plugin... You need to visit <a href="admin.php?page=Acurax-onclick-popunder-Settings&status=updated#updated">Plugin\'s Settings Page</a> to Complete the Updating Process - <a href="admin.php?page=Acurax-onclick-popunder-Settings&status=updated#updated">Click Here Visit Onclick Popunder Plugin Settings</a></b></p>
		  </div>';
}
$acurax_popunder_version = get_option('acurax_popunder_version_p');
if($acurax_popunder_version < ACURAX_POPUNDER_VERSION_P) // << Old Version // Current Verison
{
	add_action('admin_notices', 'acx_popunder_pluign_finish_version_update');
}
?>