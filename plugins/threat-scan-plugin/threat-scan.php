<?php
/*
Plugin Name: Threat Scan Plugin
Plugin URI: https://www.kpgraham.com
Description: A simple scan of the Wordpress Content and Database looking for possible threats.
Version: 1.3
Author: Keith P. Graham
Author URI: https://www.kpgraham.com

This software is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
*/


add_action( 'admin_menu', 'kpg_threat_scan_admin' );

function kpg_threat_scan_admin() {
	add_options_page('Threat Scan', 'Threat Scan', 'manage_options','threatscan','kpg_threat_scan_options');
}
function kpg_threat_scan_options() {
	// scan db completely
	
	$kpg_threat_opt=$_POST['kpg_threat_opt'];
	$kpg_threat_opt=sanitize_text_field($kpg_threat_opt);
	if (wp_verify_nonce($kpg_threat_opt,'kpg_threat')) {	
		kpg_do_threat_scan();	
	}
	kpg_do_threat_docs();


} // end of function

function kpg_scan_for_eval() {
	// scan content completely
	// WP_CONTENT_DIR is supposed to have the content dir
	$phparray=array();
	$phparray=kpg_scan_for_eval_recurse(WP_CONTENT_DIR,$phparray);
	// phparray should have a list of all of the PHP files
	$disp=false;
	echo "Files: <ol>";
	for ($j=0;$j<count($phparray);$j++) {
		$ansa=kpg_look_in_file($phparray[$j]);
		if (count($ansa)>0) {
			$disp=true;
			echo "<li>".$phparray[$j]." <br> ";
			for ($k=0;$k<count($ansa);$k++) {
				echo $ansa[$k]." <br>"; 
			}
			echo "</li>";
		}
	}
	echo "</ol>";
	return $disp;

} // end of function

// recursive walk of directory structure.
function kpg_scan_for_eval_recurse($dir,$phparray) {
	if (!is_dir($dir))  return $phparray;

	if ($dh = opendir($dir)) {
		while (($file = readdir($dh)) !== false) {
			if (is_dir($dir .'/'. $file)) {
				if ($file!='.' && $file!='..' ) {
					$phparray=kpg_scan_for_eval_recurse($dir .'/'. $file,$phparray);
				}
			} else if ( strpos($file,'.php')>0 ) {
				$phparray[count($phparray)]=$dir .'/'. $file;
			} else {
				//echo "can't find .php in $file <br>";
			}
		}
		closedir($dh);
	}
	return $phparray;

}	
function kpg_look_in_file($file) {
	if (!file_exists($file)) return false;
	$handle=fopen($file,'r');
	$ansa=array();
	$n=0;
	$idx=0;
	if (strpos($file,'threat-scan')>0) return $ansa;
	while (!feof($handle)) {
		$line=fgets($handle);
		$line=htmlentities($line);
		$n++;
		if (!(strpos($line,'eval(')===false)) {
			// bad boy
			$line=kpg_make_red('eval(',$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		if(!(strpos($line,'document.write(unescape(')===false)) {
			// another bad boy
			$line=kpg_make_red('document.write(unescape(',$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		if(!(strpos($line,'try{window.onload')===false)) {
			// another bad boy
			$line=kpg_make_red('try{window.onload',$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		if(!(strpos($line,"escape(document[")===false)) {
			// another bad boy
			$line=kpg_make_red("escape(document[",$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		if(!(strpos($line,"escape(navigator[")===false)) {
			// another bad boy
			$line=kpg_make_red("escape(navigator[",$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		if(!(strpos($line,"setAttribute('src'")===false)) {
			// another bad boy
			$line=kpg_make_red("setAttribute('src'",$line);
			$ansa[$idx]=$n.': '.$line;
			$idx++;
		} 
		
	}
	fclose($handle);
	return $ansa;
}
function kpg_make_red($needle,$haystack) {
	// turns error red
	$j=strpos($haystack,$needle);
	$s=substr_replace($haystack, '</span>', $j+strlen($needle), 0);
	$s=substr_replace($s, '<span style="color:red;">', $j, 0);
	return $s;
}	
function kpg_do_threat_docs() {
	$nonce=wp_create_nonce('kpg_threat');
	?>
	<hr/>
	<h2>Threat Scan</h2>
	<p>
	<form method="post" name="kpg_threat_form" action="">
	<input type="hidden" name="kpg_threat_opt" value="<?php echo $nonce;?>">
	<br><button type="submit" name="kpg_threat_check">Check for Threats</button><br>
	</form>  
	</p>
	<p>This is a very simple threat scan that looks for things out of place in the content directory as well as the database.</p>
	<p>The plugin searches PHP files for the occurrence of the eval() function, which, although a valuable part of PHP is also the door that hackers use in order to infect systems. The eval() function is avoided by many programmers unless there is a real need. It is often used by hackers to hide their malicious code or to inject future threats into infected systems. If you find a theme or a plugin that uses the eval() function it is safer to delete it and ask the author to provide a new version that does not use this function.</p>
	<p>When you scan your system you undoubtedly see the eval used in javascript because it is used in the javascript AJAX and JSON functionality. The appearance of eval in these cases does not mean that there is a possible threat. It just means that you should inspect the code to make sure that it is in a javascript section and not native PHP.</p>
	<p>The plugin continues its scan by checking the database tables for javascript or html where it should not be found.</p>
	<p>Normally, javascript can be found in the post body, but if the script tag is found in a title or a text field where it does not belong it is probably because the script is hiding something, such as a hidden admin user, so that the normal administration pages do not show bad records. The scan looks for this and displays the table and record number where it believes there is something hinky.</p>
	<p>The scan continues looking in the database for certain html in places where it does not belong. Recent threats have been putting html into fields in the options table so that users will be sent to malicious sites. The presence of html in options values is suspect and should be checked.</p>
	<p>The options table will have things placed there by plugins so it is difficult to tell if scripts, iframes, and other html tags are a threat. They will be reported, but they should be checked before deleting the entries.</p>
	<p>This plugin is just a simple scan and does not try to fix any problems. It will show things that may not be threats, but should be checked. If anything shows up you, should try to repair the damage or hire someone to do it. I am not a security expert, but a programmer who discovered these types of things in a friend's blog. After many hours of checking I was able to fix the problem, but a professional could have done it faster and easier, although they would have charged for it.</p>
	<p>You probably do not have a backup to your blog, so if this scan shows you are clean; your next step is to install one of the plugins that does regular backups of your system. Next make sure you have the latest Wordpress version.</p>
	<p>If you think you have problems, the first thing to do is change your user id and password. Next make a backup of the infected system. Any repairs to Wordpress might delete important data so you might lose posts, and the backup will help you recover missing posts.</p>
	<p>The next step is to install the latest version of Wordpress. The new versions usually have fixes for older threats.</p>
	<p>You may want to export your Wordpress posts, make a new clean installation of Wordpress, and then import the old posts.</p>
	<p>If this doesn't work it is time to get a pro involved.<p>
	<p><i>Please note that it lists revisions and autosave posts and pages. These cannot be viewed directly, but should be cleaned up if they are not needed</i></p> 
	<h3>A clean scan does not mean you are safe. Please do Backups and keep your installation up to date!</h3>

	<hr/>
	
	<?php
}	

function kpg_do_threat_scan() {

	global $wpdb;
	global $wp_query;
	$pre=$wpdb->prefix;


	$disp=false;

	// lets try the posts. Looking for script tags in data
	echo "<br><br>Testing Posts<br>";
	$ptab=$pre.'posts';
	$sql= "select ID,post_author,post_title,post_name,guid,post_content,post_mime_type
from $ptab where 
INSTR(LCASE(post_author), '<script') +
INSTR(LCASE(post_title), '<script') +
INSTR(LCASE(post_name), '<script') +
INSTR(LCASE(guid), '<script') +
INSTR(LCASE(post_author), 'eval(') +
INSTR(LCASE(post_title), 'eval(') +
INSTR(LCASE(post_name), 'eval(') +
INSTR(LCASE(guid), 'eval(') +
INSTR(LCASE(post_content), 'eval(') +
INSTR(LCASE(post_content), 'document.write(unescape(') +
INSTR(LCASE(post_content), 'try{window.onload') +
INSTR(LCASE(post_content), 'setAttribute(\'src\'') +
INSTR(LCASE(post_mime_type), 'script') >0
";
	//echo " <br> $sql <br>";

	//?p=16
	$hrul=get_home_url() . "?p=";
	$myrows = $wpdb->get_results( $sql );
	if ($myrows) {
		foreach ($myrows as $myrow) {
			$disp=true;
			$reason='';
			if (strpos(strtolower($myrow->post_author),'<script')!==false) $reason.="post_author:&lt;script "; 
			if (strpos(strtolower($myrow->post_title),'<script')!==false) $reason.="post_title:&lt;script "; 
			if (strpos(strtolower($myrow->post_name),'<script')!==false) $reason.="post_name:&lt;script "; 
			if (strpos(strtolower($myrow->guid),'<script')!==false) $reason.="guid:&lt;script "; 

			if (strpos(strtolower($myrow->post_author),'eval(')!==false) $reason.="post_author:eval() "; 
			if (strpos(strtolower($myrow->post_title),'eval(')!==false) $reason.="post_title:eval() "; 
			if (strpos(strtolower($myrow->post_name),'eval(')!==false) $reason.="post_name:eval() "; 
			if (strpos(strtolower($myrow->guid),'eval(')!==false) $reason.="guid:eval() "; 
			if (strpos(strtolower($myrow->post_content),'eval(')!==false) $reason.="post_content:eval() "; 
			
			if (strpos(strtolower($myrow->post_content),'document.write(unescape(')!==false) $reason.="post_content:document.write(unescape( "; 
			if (strpos(strtolower($myrow->post_content),'try{window.onload')!==false) $reason.="post_content:try{window.onload "; 
			if (strpos(strtolower($myrow->post_content),"setAttribute('src'")!==false) $reason.="post_content:setAttribute('src' "; 
			if (strpos(strtolower($myrow->post_mime_type),'script')!==false) $reason.="post_mime_type:script "; 
			
			
			$elink='<a target="new" href="'.get_permalink($myrow->ID).'">'.$myrow->ID.'</a> ';
			echo "found possible problems in post ($reason) ID: ".$elink.' Link: '.get_permalink($myrow->ID).'<br>';
		}
	} else {
		echo "<br>nothing found in posts<br>";
	}
	echo "<hr/>";
	//comments: comment_ID: author_url, comment_agent, comment_author, comment_email
	$ptab=$pre.'comments';
	echo "<br><br>Testing Comments<br>";
	$sql="select comment_ID,comment_author_url,comment_agent,comment_author,comment_author_email,comment_content
from $ptab where 
INSTR(LCASE(comment_author_url), '<script') +
INSTR(LCASE(comment_agent), '<script') +
INSTR(LCASE(comment_author), '<script') +
INSTR(LCASE(comment_author_email), '<script') +
INSTR(LCASE(comment_author_url), 'eval(') +
INSTR(LCASE(comment_agent), 'eval(') +
INSTR(LCASE(comment_author), 'eval(') +
INSTR(LCASE(comment_author_email), 'eval(') +
INSTR(LCASE(comment_content), '<script') +
INSTR(LCASE(comment_content), 'eval(') +
INSTR(LCASE(comment_content), 'document.write(unescape(') +
INSTR(LCASE(comment_content), 'try{window.onload') +
INSTR(LCASE(comment_content), 'setAttribute(\'src\'') +
INSTR(LCASE(comment_author_url), 'javascript:') >0
";
	$myrows = $wpdb->get_results( $sql );
	if ($myrows) {
		foreach ($myrows as $myrow) {
			$disp=true;
			$reason='';
			if (strpos(strtolower($myrow->comment_author_url),'<script')!==false) $reason.="comment_author_url:&lt;script "; 
			if (strpos(strtolower($myrow->comment_agent),'<script')!==false) $reason.="comment_agent:&lt;script "; 
			if (strpos(strtolower($myrow->comment_author),'<script')!==false) $reason.="comment_author:&lt;script "; 
			if (strpos(strtolower($myrow->comment_author_email),'<script')!==false) $reason.="comment_author_email:&lt;script ";
			if (strpos(strtolower($myrow->comment_content),'<script')!==false) $reason.="comment_content:&lt;script ";
			
			if (strpos(strtolower($myrow->comment_author_url),'eval(')!==false) $reason.="comment_author_url:eval() "; 
			if (strpos(strtolower($myrow->comment_agent),'eval(')!==false) $reason.="comment_agent:eval() "; 
			if (strpos(strtolower($myrow->comment_author),'eval(')!==false) $reason.="comment_author:eval() "; 
			if (strpos(strtolower($myrow->comment_author_email),'eval(')!==false) $reason.="comment_author_email:eval() "; 
			if (strpos(strtolower($myrow->comment_content),'eval(')!==false) $reason.="comment_content:eval() "; 
			
			if (strpos(strtolower($myrow->comment_content),'document.write(unescape(')!==false) $reason.="comment_content:document.write(unescape( ";
			if (strpos(strtolower($myrow->comment_content),'try{window.onload')!==false) $reason.="comment_content:try{window.onload ";
			if (strpos(strtolower($myrow->comment_content),"setAttribute('src'")!==false) $reason.="comment_content:setAttribute('src' ";
			if (strpos(strtolower($myrow->comment_content),'javascript:')!==false) $reason.="comment_content:javascript: ";
			
			
			
			echo "found possible problems in comment ($reason) ID". $myrow->comment_ID.'<br>';
		}
	} else {
		echo "<br>nothing found in Comments<br>";
	}
	echo "<hr/>";
	// links: links_id: link_url, link_image, link_description, link_notes, link_rss,link_rss
	$ptab=$pre.'links';
	echo "<br><br>Testing Links<br>";
	$sql="select link_ID,link_url,link_image,link_description,link_notes
from $ptab where 
INSTR(LCASE(link_url), '<script') +
INSTR(LCASE(link_image), '<script') +
INSTR(LCASE(link_description), '<script') +
INSTR(LCASE(link_notes), '<script') +
INSTR(LCASE(link_rss), '<script') +
INSTR(LCASE(link_url), 'eval(') +
INSTR(LCASE(link_image), 'eval(') +
INSTR(LCASE(link_description), 'eval(') +
INSTR(LCASE(link_notes), 'eval(') +
INSTR(LCASE(link_rss), 'eval(') +
INSTR(LCASE(link_url), 'javascript:') >0
";

	$myrows = $wpdb->get_results( $sql );
	if ($myrows) {
		foreach ($myrows as $myrow) {
			$disp=true;
			$reason=''; 
			if (strpos(strtolower($myrow->link_url),'<script')!==false) $reason.="link_url:&lt;script "; 
			if (strpos(strtolower($myrow->link_image),'<script')!==false) $reason.="link_image:&lt;script "; 
			if (strpos(strtolower($myrow->link_description),'<script')!==false) $reason.="link_description:&lt;script "; 
			if (strpos(strtolower($myrow->link_notes),'<script')!==false) $reason.="link_notes:&lt;script "; 
			if (strpos(strtolower($myrow->link_rss),'<script')!==false) $reason.="link_rss:&lt;script "; 
			
			if (strpos(strtolower($myrow->link_url),'eval(')!==false) $reason.="link_url:eval() "; 
			if (strpos(strtolower($myrow->link_image),'eval(')!==false) $reason.="link_image:eval() "; 
			if (strpos(strtolower($myrow->link_description),'eval(')!==false) $reason.="link_description:eval() "; 
			if (strpos(strtolower($myrow->link_notes),'eval(')!==false) $reason.="link_notes:eval() "; 
			if (strpos(strtolower($myrow->link_rss),'eval(')!==false) $reason.="link_rss:eval() "; 

			if (strpos(strtolower($myrow->link_url),'javascript:')!==false) $reason.="link_url:javascript: "; 
			
			echo "found possible problems in links ($reason) ID:". $myrow->link_ID.'<br>';
		}
	} else {
		echo "<br>nothing found in Links<br>";
	}
	echo "<hr/>";

	//users: ID: user_login,user_nicename, user_email, user_url, display_name
	$ptab=$pre.'users';
	echo "<br><br>Testing Users<br>";
	$sql="select ID,user_login,user_nicename,user_email,user_url,display_name 
from $ptab where 
INSTR(LCASE(user_login), '<script') +
INSTR(LCASE(user_nicename), '<script') +
INSTR(LCASE(user_email), '<script') +
INSTR(LCASE(user_url), '<script') +
INSTR(LCASE(display_name), '<script') +
INSTR(user_login, 'eval(') +
INSTR(user_nicename, 'eval(') +
INSTR(user_email, 'eval(') +
INSTR(user_url, 'eval(') +
INSTR(display_name, 'eval(') +
INSTR(LCASE(user_url), 'javascript:') +
INSTR(LCASE(user_email), 'javascript:')>0
";
	$myrows = $wpdb->get_results( $sql );
	if ($myrows) {
		foreach ($myrows as $myrow) {
			$disp=true;
			$reason='';
			if (strpos(strtolower($myrow->user_login),'<script')!==false) $reason.="user_login:&lt;script "; 
			if (strpos(strtolower($myrow->user_nicename),'<script')!==false) $reason.="user_nicename:&lt;script "; 
			if (strpos(strtolower($myrow->user_email),'<script')!==false) $reason.="user_email:&lt;script "; 
			if (strpos(strtolower($myrow->user_url),'<script')!==false) $reason.="user_url:&lt;script "; 
			if (strpos(strtolower($myrow->display_name),'<script')!==false) $reason.="display_name:&lt;script ";
			
			if (strpos(strtolower($myrow->user_login),'eval(')!==false) $reason.="user_login:eval() "; 
			if (strpos(strtolower($myrow->user_nicename),'eval(')!==false) $reason.="user_nicename:eval() "; 
			if (strpos(strtolower($myrow->user_email),'eval(')!==false) $reason.="user_email:eval() "; 
			if (strpos(strtolower($myrow->user_url),'eval(')!==false) $reason.="user_url:eval() "; 
			if (strpos(strtolower($myrow->display_name),'eval(')!==false) $reason.="display_name:eval() "; 
			
			if (strpos(strtolower($myrow->user_email),'javascript:')!==false) $reason.="user_email:javascript: "; 
			if (strpos(strtolower($myrow->user_url),'javascript:')!==false) $reason.="user_url:javascript: "; 
			echo "found possible problems in Users ($reason) ID:". $myrow->ID.'<br>';
		}
	} else {
		echo "<br>nothing found in Users<br>";
	}
	echo "<hr/>";

	//options: option_id option_value, option_name
	// I may have to update this as new websites show up
	$ptab=$pre.'options';
	echo "<br><br>
<p>Testing Options table for html (only first 500 characters displayed).<br>
<i>Options may contain JavaScript safely, but check just in case</i><br>
</p>";
	$sql="select option_id,option_value,option_name
from $ptab where 
INSTR(LCASE(option_value), '<script') +
INSTR(LCASE(option_value), 'display:none') +
INSTR(LCASE(option_value), 'networkads') +
INSTR(option_value, 'eval(') +
INSTR(LCASE(option_value), 'javascript:') >0
";
	$myrows = $wpdb->get_results( $sql );
	if ($myrows) {
		foreach ($myrows as $myrow) {
			$disp=true;
			// get the option and then red the string
			$id=$myrow->option_id;
			$name=$myrow->option_name;
			$line=$myrow->option_value;
			$line=htmlentities($line);
			$pline=$line;
			if (strlen($line)>500) $pline=substr($pline,0,500).'... (too long)' ;
			$line=strtolower($line);
			$reason='';
			if (!(strpos($line,'&lt;script')===false)) {
				// bad boy
				$line=kpg_make_red('&lt;script',$line);
				$reason.="script tag ";
			} 
			if (!(strpos($line,'networkads')===false)) {
				// bad boy
				$line=kpg_make_red('networkads',$line);
				$reason.="netoworkads ";
			} 
			if (!(strpos($line,'eval(')===false)) {
				// bad boy
				$line=kpg_make_red('eval(',$line);
				$reason.="eval() statement ";
			} 
			if (!(strpos($line,'javascript:')===false)) {
				// bad boy
				$line=kpg_make_red('javascript:',$line);
				$reason.="javascript ";
			} 
			if (!(strpos($line,'display:none')===false)) {
				// bad boy
				$line=kpg_make_red('display:none',$line);
				$reason.="hidden data";
			} 

			echo "<b>found possible problems in Option $name ($reason)</b> option_id:". $myrow->option_id.", value: $pline<br><br>";
		}
	} else {
		echo "<br>nothing found in Options<br>";
	}
	echo "<hr/>";
	echo "<h3>Scanning Themes and Plugins for eval</h3>";

	if (kpg_scan_for_eval()) $disp=true;;

	if ($disp) {
		?>
		<h3>Possible problems found!</h3>
		<p>These are warnings, only. Some content and plugins might not be malicious, but still contain one or more of these indicators. Please investigate all indications of problems. The plugin may err on the side of caution.</p>
		<p>Although there are legitimate reasons for using the eval function, and javascript uses it frequently,
		finding eval in PHP code is in the very least bad practice, and the worst is used to hide malicious code. If eval() comes up in a scan, try to get rid of it. </p>
		<p>Your code could contain 'eval', or 'document.write(unescape(' or 'try{window.onload' or setAttribute('src'. These are markers for problems such as sql injection or cross-browser javascript. &lt;script&gt; tags should might occur in your posts, if you added them, but should not be found anywhere else, except options. Options often have scripts for displaying facebook, twitter, etc. Be careful, though, if one appears in an option. Most of the time it is OK, but make sure.
		<?php
		
	} else {
		?>
		<h3>No problems found!</h3>
		<p>It appears tha there are no eval or suspicious javascript functions in the code in your wp-content directory. That does not mean that you are safe, only that a threat may be well hidden.</p>
		<?php	
	}



}	
?>