<?php
/*
FAST TUBE VIDEO GALLERY

Fast Tube by Casper
http://blog.caspie.net/
*/
function fast_tube_gallery() { 
	global $wpdb;	
	$pattern = "/\[(?:(?:http:\/\/)?(?:www\.)?youtube\.com\/)?(?:(?:watch\?)?v=|v\/)?([a-zA-Z0-9\-\_]{11})(?:&[a-zA-Z0-9\-\_]+=[a-zA-Z0-9\-\_]+)*\]/";
	$path = trailingslashit( plugins_url( '', __FILE__ ) );
	$limit = 5;
	if(isset($_GET['vp'])) { $limit = $_GET['vp']; }
	if(isset($_POST['vp'])) { $limit = $_POST['vp']; }
?>
<div class="wrap">
<script src="<?php echo $path; ?>js/func.js" type="text/javascript"></script>
<style type="text/css" media="screen">
a {
	text-decoration: none;
}
a:hover {
	text-decoration: underline;
}
div.sminf {
 	font-family: Verdana,Tahoma,Arial;
 	font-size: xx-small;
 	font-weight: bold;
 	color: #555;
	text-transform: uppercase;
	margin: 4px 0 0 0;
 }
div.smti {
	font-size: x-small;
	font-weight: normal;
	text-transform: none;
	color: #111;
}
form.idch {
	margin: 0;
	text-align: right;
	white-space:nowrap;
}
input.smin {
	padding: 4px;
	margin: 10px 0 5px 0;
	border: 1px solid #ccc;
	font-size: small;
	width: 155px;
	color: #333;
}
span.sminf-o {
	color: orange;
	text-transform: none;
}
img.thumb {
	padding: 3px;
	border: 1px solid #ddd;
}
img.thumb:hover {
	background: #ddd;
}
tr.hvr:hover {
	background: #ffffdd;
}
th.ctr {
	text-align: center;
}
td.postid {
	font-weight: bold;
	text-align: center;
	vertical-align: middle;
}
input.ftbutton {
	background: #e0e0e0;
	border: 1px solid #ddd;
	border-left: 1px solid #ccc;
	border-bottom: 1px solid #ccc;
	width: 50px;
	vertical-align:middle;
	font-size: xx-small;
	font-weight: bold;
	color: #666;
}
input.ftbutton:hover {
	background: #fff;
	color: #666;
	border: 1px solid #777;
	border-left: 1px solid #999;
	border-bottom: 1px solid #999;
}
.info {
	border: 1px dotted #ccc;
	background: #fff;
	margin-top: 10px;
	margin-bottom: 10px;
	padding: 10px;
	text-align: justify;
}
</style>
	<h2><img src="<?php echo $path; ?>img/ft.gif" alt="Fast Tube" /> Fast Tube <?php echo FAST_TUBE_VERSION; ?> by <a title="Casper's Blog" href="http://blog.caspie.net/">Casper</a></h2>
	<h6><em>Fast and easy way to insert any amount of YouTube videos right into your blog's posts or pages!</em></h6>
	<input type="button" class="button" onclick="javascript:moreInfo('information');" value="Information" />
	<input type="button" class="button" onclick="javascript:moreInfo('gallery');" value="Fast Tube Gallery" />
	<div id="information" class="info" style="display:none;">
		<div><small><strong>Check out my other WordPress plugins at <a title="Downloads @ Casper's Blog" href="http://blog.caspie.net/downloads/">http://blog.caspie.net/downloads</a>... Nice plugin eh? Feel free to <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&amp;business=R7HRD7C3JDDN8&amp;lc=GB&amp;item_name=WordPress%20Plugins%20by%20Casper&amp;currency_code=EUR&amp;bn=PP%2dDonationsBF%3abtn_donateCC_LG_global%2egif%3aNonHosted" target="_blank">donate</a> if you like it. Thanks and have fun! :)</strong></small></div>
	</div>
	<div id="gallery" class="info" style="display:none;">
		<div><small><strong>With the Video Gallery you can manage your videos added with Fast Tube. On the left side there is a small thumbnail of the video and some additional information, such as the ID of the post or page, the way (the tag) video was added, and when the post or page is created and last modified. On the right there an input field, which hold the actual YouTube VideoID and 3 buttons - Check, Change and Remove.<div style="padding:10px 0;">Note that using Check or Change will change the VideoID background color depending on the result.</div>Legend: <span style="background:green;padding:4px;color:#fff;">VideoID is Valid</span> <span style="background:red;padding:4px;color:#fff;">VideoID is Missing</span> <span style="background:blue;padding:4px;color:#fff;">VideoID is Changed</span></strong></small></div>
	</div>

	<h2 style="float:left;">Video Gallery</h2>
		<div style="float:right;">
	<form method="post" action="">
		<select name="vp" onchange="form.submit();">
		<?php
			$vidspp = array(5,10,15,20);
			foreach($vidspp as $vidpp) {
				$select = $vidpp == $limit ? ' selected="selected"' : '';
				echo '<option value="'.$vidpp.'"'.$select.'>'.$vidpp.' videos/page</option>';
			}
			
		?>
		</select>
	</form>
	</div><div style="clear:both;"></div>
		<table class="widefat">
			<thead>
				<tr>
					<th class="ctr">PostID</th>				
					<th class="ctr">Screenshot</th>
					<th class="ctr" width="100%">Post/Page Title &amp; Additional Information</th>
					<th class="ctr">VideoID</th>			
				</tr>
			</thead>
		<tbody>
<?php
	$next = isset($_GET['next']) ? $_GET['next'] : 0;
	$results = $wpdb->get_results("SELECT id,post_date,post_modified,post_title,post_content,post_type from $wpdb->posts WHERE post_type = 'post' OR post_type = 'page' ORDER by post_date DESC LIMIT $next,18446744073709551615");	
	$z = 1; $idcolor = '';
	$i = $j = 0;
	while(($i < $limit + 1) && ($result = $results[$j])) {
		$j++;
		if(preg_match_all($pattern,$result->post_content,$matches,PREG_SET_ORDER)) {
			$i++;
			if($i >= $limit + 1) break;
			foreach($matches as $match) {
				$m0 = $match[0];
				$m1 = $match[1];
				if(isset($_POST['videoid']) && $_POST['videoid'] == $m1) {
					if(isset($_POST['check'])) {
						$idcolor = @file_get_contents("http://gdata.youtube.com/feeds/api/videos/".$m1) != "Video not found" ? 'green':'red';
						$idcolor = ' style="background:'.$idcolor.';color:white;border-color:black;"';
					}
					if(isset($_POST['change'])) {
						$videoid = trim($_POST['vid']);
						$new_content = str_replace($m1, $videoid, $result->post_content);
						if(strlen($videoid) == 11) {
							$idcolor = ' style="background:blue;color:white;border-color:black;"';
							$m0 = str_replace($m1, $videoid, $m0); $m1 = $videoid;
							$wpdb->query("UPDATE $wpdb->posts SET post_content = '$new_content' WHERE ID = $result->id");							}
					}
					if(isset($_POST['remove'])) {
						$new_content = str_replace($m0, '', $result->post_content); $m0 = 'remove';
						$wpdb->query("UPDATE $wpdb->posts SET post_content = '$new_content' WHERE ID = $result->id");
					}
				}
				else { $idcolor = ''; }
				$z%2 == 0 ? $trclass = ' class="alternate hvr"' : $trclass = ' class="hvr"';
					$m0 === 'remove' ? $trstyle = ' style="display:none;"' : $trstyle = '';
					echo '<tr'.$trclass.$trstyle.'>
							<td class="postid">'.$result->id.'</td>
							<td><a title="'.$result->post_title.'" href="http://i.ytimg.com/vi/'.$m1.'/0.jpg" rel="lightbox"><img class="thumb" src="http://i.ytimg.com/vi/'.$m1.'/'.rand(1,3).'.jpg" alt="Fast Tube" border="0" width="90" height="68" /></a></td>
							<td><img src="'.$path.'img/ft.gif" alt="Fast Tube" style="vertical-align:top;" /> <a class="row-title" href="'.$result->post_type.'.php?action=edit&amp;post='.$result->id.'" title="Edit '.$result->post_title.'" id="'.$result->id.'">'.$result->post_title.'</a>
							<div class="sminf">
							<div class="smti"><em>Video Tag:</em></div>
							&middot; Added as: <span class="sminf-o">'.$m0.'</span></div>
							<div class="sminf">
							<div class="smti"><em>Post Information:</em></div>
							&middot; Created: <span class="sminf-o">'.$result->post_date.'</span> &middot; Updated: <span class="sminf-o">'.$result->post_modified.'</span></div>
							</td><td>
							<form class="idch" name="videoaction" method="post" action="#'.$result->id.'">
							<input class="smin"'.$idcolor.' type="text" name="vid" maxlength="11" value="'.$m1.'" /><input type="hidden" value="'.$result->id.'" name="postid" /><input type="hidden" value="['.$m0.']" name="addedas" /><input type="hidden" value="'.$m1.'" name="videoid" /><br /><input name="check" class="ftbutton" type="submit" value="Check" /><input name="change" class="ftbutton" type="submit" value="Change" /><input name="remove" class="ftbutton" type="submit" value="Remove" />
							</form>
							</td></tr>';
				$z++;
			}
		}
	}
	$next += $j - 1;
	echo '</tbody>
			<tfoot>
				<tr>
					<th class="ctr">PostID</th>				
					<th class="ctr">Screenshot</th>
					<th class="ctr" width="100%">Post/Page Title & Additional Information</th>
					<th class="ctr">VideoID</th>			
				</tr>
			</tfoot>	
		</table>';
		if(isset($_GET['next'])) echo '<a style="float:left;padding:5px;text-decoration:none;" href="javascript:history.back()">&larr; Prev '.$limit.' Videos</a> ';
		if($i > $limit) echo '<a style="float:right;padding:5px;text-decoration:none;" href="admin.php?page=fast-tube/fast-tube-gallery.php&next='.$next.'&vp='.$limit.'">Next '.$limit.' Videos &rarr;</a>';

	echo '</div>';
}
fast_tube_gallery();
?>