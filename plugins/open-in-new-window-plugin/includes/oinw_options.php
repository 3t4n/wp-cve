<?php

if (!defined('ABSPATH')) exit; // just in case


function kpg_open_in_new_window_control_2()  {

	if(!current_user_can('manage_options')) {
		die('Access Denied');
	}

	$options=kpg_oinw_get_options();
	extract($options);
// check for update submit
	if (array_key_exists('kpg_oinw_update',$_POST)) {
		$nonce=$_POST['kpg_oinw_update'];
		if (wp_verify_nonce($nonce,'kpg_oinw_update')) {
			if (array_key_exists('checktypes',$_POST)) {
				$checktypes='Y';
			} else {
				$checktypes='N';
			}
			$options['checktypes']=$checktypes;
			update_option('kpg_open_in_new_window_options',$options);
			echo "<h2>Option Updated</h2>";
		}
	}
	
	$nonce=wp_create_nonce('kpg_oinw_update');
	
?>
<div class="wrap">
<h2>Open in new window Plugin</h2>
<p>This plugin installs some javascript on every page. When your page finishes loading, the javascript steps through the links on the page looking for links that lead to other domains. It alters these links so that they will open in a new window.</p>
<p>The javascript does not look in any embedded iframes, so it will not work with some ads and affiliate links. It will also not work where other javascript is executed through an onclick event or the link begins with 'javascript:'</p>

<p>Since the javascript does not run until the web page is completely loaded, links on a page that is slow to load will not open in a new window until the page is fully loaded.</p>
  <hr/>

  <form method="post" action="">
    <input type="hidden" name="action" value="update" />
    <input type="hidden" name="kpg_oinw_update" value="<?php echo $nonce;?>" />
	Open file types such as PDF, MP3, images, archives and video in a new window: 
	<input name="checktypes" type="checkbox" value="Y" <?php if ($checktypes=='Y') {?> checked="checked" <?php } ?>/>
    <br/>
    <p class="submit">
      <input class="button-primary" value="Save Changes" type="submit">
    </p>
  <form>
<div style="background-color:ivory;border:#333333 medium groove;padding:4px;margin-left:4px;">
<p>If you wish to support my programming, anything you can do would be appreciated.</p>
<p>There are four ways to do this.</p>
<p>First, you can go to the <a href="https://wordpress.org/support/view/plugin-reviews/stop-spammer-registrations-plugin" target="_blank">plugin pages on WordPress.org</a> and click on a few stars for the plugin rating, and check off the "it works" information. You might, if so moved, say a few nice words under reviews.</p>
<p>Second, you can link to this website somewhere on your blog. The incoming links help with Google searches.</p>
<p>Third, If you feel that you'd like to encourage me, you could <a href="#books">buy one of my books</a>. I write short stories for fun and I have sold about 50 stories to various magazines. The books are cheap and very interesting.</p>
<p>You can install 
<a href="https://wordpress.org/plugins/astounding-spam-prevention/" target="_blank">Astounding Spam Protection</a> which is an aggressive but safe anti-spam plugin that I helped with.</p>
<p>You can also donate a few dollars. There are three levels of donations. 
<br>First, at $2.47 you can support me. I like this level because it does not put any stress on you. 
I think everyone can afford this without any pain. 
<br>Second, for those who think they owe a little more, I have a $9.97 level. This is for those who have money to burn and drive expensive sports cars. 
<br>Last, there is the $29.97 level. I don't expect anyone to use this level, but there are possibly a few sysops with a company credit card, and an unlimited budget who might sympathize with a fellow coder and click this button.</p>
<p>You can pay using PayPal. All you need is a credit card. There is no account required. Just click and follow the instructions. You can request a refund and I will gladly comply. 
</p>
<table style="border:grey solid thin;min-width:50%">
<thead>
<tr style="background-color:ivory;">
<th>Support Level</th>
<th>PayPal</th>
<th></th>
</tr>
</thead>
<tbody>
<tr>
<td>Level 1) $2.47<br>
Grateful User
</td>
<td>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="FN4WWVTRBSWVL" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>
</td>
<td></td>
</tr>
<tr>
<td>Level 2) $9.97<br>
Generous Benefactor
</td>
<td>
<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="FN4WWVTRBSWVL" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>


</td>
<td></td>
</tr>
<tr>
<td>Level 3) $29.97<br>
Wealthy patron</td>
<td>

<form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
<input type="hidden" name="cmd" value="_s-xclick" />
<input type="hidden" name="hosted_button_id" value="FN4WWVTRBSWVL" />
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
</form>


</td>
<td></td>
</tr>
</tbody>
</table>
<p>I would like to thank you for your support. The very best way to support me is to report all bugs, ask for enhancements, send me your ideas, and drop me a line of encouragement. I will not get rich writing plugins, so you can help me by adding to the fun of coding. I have spent the last 40 years writing programs. I started in 1969 writing FORTRAN on an old IBM 1400, and I have learned every cool language that has appeared since. Programming has given me a good life and I would rather write code than almost anything.</p>
<a name="books"/>
<h2>Buy My Books!</h2>
<a href="http://www.amazon.com/Error-Message-Eyes-Programmers-Digital/dp/1456336584" target="_blank">Error Message Eyes: a Programmer's Guide to the Digital Soul (paperback)</a> - 15 Science Fiction stories of Computers, Robots, Programs and Algorithms.
</p>
<p> <a href="http://www.amazon.com/Error-Message-Eyes-Keith-Graham-ebook/dp/B004C05DTC" target="_blank">Error Message Eyes (kindle).</a> </p>
<p> <a href="http://www.amazon.com/Playing-Help-Me-Style-Sonny-Williamson/dp/1463529961" target="_blank">Playing "Help-Me" In the Style of Sonny Boy Williamson II: A step by step, note for note analysis of some of Sonny Boy's Signature Riffs (Paperback).</a> </p>
<p> <a href="http://www.amazon.com/Playing-Help-Me-Style-Sonny-Williamson-ebook/dp/B004LDLBDW" target="_blank">Playing "Help-Me" In the Style of Sonny Boy Williamson II (Kindle).</a> </p>
<p> <a href="http://www.amazon.com/Frogs-Aspic-Keith-Graham-ebook/dp/B005PQDI5S/" target="_blank">Frogs in Aspic</a> - 16 Strange tales by by Keith P. Graham. Weird stories of Ghosts, Souls, Love and the Psyche (Kindle).
<p>Thanks,</p>
<p>Keith P. Graham</p>
<p></p>
<p>


</div>


</div>

<?php
}
