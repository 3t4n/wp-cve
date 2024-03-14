<?php if (!$noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

	        <li class="twitter-one-extend" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>


	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="pinterest-one-extend" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>
      
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>
      
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
      
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>


	        <li class="twitter-extend-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

	        <li class="twitter-extend-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && $nogoogle && $norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && !$nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

 	        <li><a class="metro-specific" target="_blank" href="<?php echo $specificfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>



	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

	        <li class="twitter-one-extend" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>


	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>


	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="pinterest-one-extend" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>



	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>
      
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>


		<li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>
      
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
      
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div>

<!-- Place this tag after the last +1 button tag. -->
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>


	        <li class="twitter-extend-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="metro-linkedin" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="metro-linkedin" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="metro-pinterest" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div>
<script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	</div>
	<?php
		}

	else if ($noyoutube && $nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && !$nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>
	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

	        <li class="twitter-extend-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

		<li class="youtube-one" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && $nogoogle && $norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height-extend" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>
       
	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	</div>
	<?php
		}

	else if (!$noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="googleplus-one" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover" styel="margin-top:10px;">
<div class="g-follow" data-annotation="bubble" data-height="24" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>

	        <li class="twitter-one" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="true" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if (!$noyoutube && $nolinkedin && !$nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="pinterest-one" style="background-color:<?php echo $pinterest_color; ?>;"><div class="pinteresthover"><a data-pin-do="buttonFollow" href="http://pinterest.com/<?php echo $pinterest; ?>">Follow</a><script type="text/javascript">
(function(d){
  var f = d.getElementsByTagName('SCRIPT')[0], p = d.createElement('SCRIPT');
  p.type = 'text/javascript';
  p.async = true;
  p.src = '//assets.pinterest.com/js/pinit.js';
  f.parentNode.insertBefore(p, f);
}(document));
</script></div></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

 	        <li><a class="metro-rss" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

		<li class="metro-youtube" style="background-color:<?php echo $youtube_color; ?>;"><div class="googlehover"><div class="g-ytsubscribe" data-channel="<?php echo $youtube; ?>" data-layout="default"></div></div></li>

	</div>
	<?php
		}

	else if ($noyoutube && !$nolinkedin && $nopinterest  && !$nogoogle && !$norssfeed && $nospecificfeed)
		{
	?>

	<div class="metro-social metro-height" style="width:<?php echo $width; ?>px;">

		<li class="metro-facebook" style="background-color:<?php echo $fb_color; ?>;"><iframe class="fbhover" src="//www.facebook.com/plugins/like.php?href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $facebook; ?>&amp;send=false&amp;layout=box_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp;appId=146463002094104" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:75px; height:65px;" allowTransparency="true"></iframe></li>

		<li class="metro-googleplus" style="background-color:<?php echo $google_color; ?>;"><div class="googlehover">
<div class="g-follow" data-annotation="vertical-bubble" data-height="15" data-href="//plus.google.com/<?php echo $google; ?>" data-rel="publisher"></div></div><script type="text/javascript">
  (function() {
    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
    po.src = 'https://apis.google.com/js/plusone.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
  })();
</script></li>
       
	        <li class="metro-twitter" style="background-color:<?php echo $twitter_color; ?>;"><a href="https://twitter.com/<?php echo $twitter; ?>" class="twitter-follow-button twitterhover" data-show-count="false" data-show-screen-name="false">Follow @<?php echo $twitter; ?></a><script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script></li>

		<?php
		if ( $select_linkedin == 'Member' ) 
		{ 
			echo '<li class="linkedin-one" style="background-color:'.$linkedin_color.';"><a class="linkedinhover" target="_blank" href="'.$linkedin.'"><img src="' . plugins_url( 'images/linkedin.jpg' , __FILE__ ) . '" ></a></li>'; 
		}
		else if ( $select_linkedin == 'Company' )
		{
			echo '<li  class="linkedin-one" style="background-color:'.$linkedin_color.';"><span class="linkedinhover"><script src="//platform.linkedin.com/in.js" type="text/javascript">
 lang: en_US
</script>
<script type="IN/FollowCompany" data-counter="top" data-id="'.$linkedin.'"></script></span></li>';
		}
	   	?>

 	        <li><a class="rss-feed" style="background-color:<?php echo $rss_color; ?>;" target="_blank" href="<?php echo $rssfeed; ?>"></a></li>

	</div>
	<?php
		}
