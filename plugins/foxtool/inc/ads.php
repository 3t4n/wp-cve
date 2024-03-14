<?php
if ( ! defined( 'ABSPATH' ) ) { exit; }
global $foxtool_options;
# code ads click
if (isset($foxtool_options['ads-click1']) && !empty($foxtool_options['ads-click11'])){	
function foxtool_adsclick_footer(){
	global $foxtool_options;
	$mini = isset($foxtool_options['ads-click-c1']) ? '"left=2000,top=2000,width=200,height=100,location=no,toolbar=no,menubar=no,scrollbars=no,resizable=no"' : NULL;
	$hau = !empty($foxtool_options['ads-click-c2']) ? $foxtool_options['ads-click-c2'] : 24;
	?>
	<script>
	(function() {
	var CookieClick = {
			set: function(name, value, days) {
				var domain, domainParts, date, expires, host;
				if (days) {
					date = new Date();
					// set time luu thoi gian
					date.setTime(date.getTime() + (days * <?php echo $hau; ?> * 60 * 60 * 1000));
					expires = "; expires=" + date.toGMTString();
				} else {
					expires = "";
				}
				host = location.host;
				if (host.split('.').length === 1) {
					document.cookie = name + "=" + value + expires + "; path=/";
				} else {
					domainParts = host.split('.');
					domainParts.shift();
					domain = '.' + domainParts.join('.');
					document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;
					if (CookieClick.get(name) == null || CookieClick.get(name) != value) {
						domain = '.' + host;
						document.cookie = name + "=" + value + expires + "; path=/; domain=" + domain;
					}
				}
			},
			get: function(name) {
				var nameEQ = name + "=";
				var ca = document.cookie.split(';');
				for (var i = 0; i < ca.length; i++) {
					var c = ca[i];
					while (c.charAt(0) == ' ') {
						c = c.substring(1, c.length);
					}
					if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
				}
				return null;
			},
			erase: function(name) {
				CookieClick.set(name, '', -1);
			}
		};
	var links = [<?php 
		$lislink = $foxtool_options['ads-click11'];
		$lislink = explode("\n", str_replace("\r", "",  $lislink));
		foreach ($lislink as $link){
			echo "'". $link ."',";
		}
		?>];
	function AffClickHandler(event) {
		if (CookieClick.get('affclick') != 1) {
			CookieClick.set('affclick', 1, 1);
			var randomIndex = Math.floor(Math.random() * links.length);
			var newLink = links[randomIndex];
			var newWindow = window.open(newLink, '_blank', <?php echo $mini; ?>);
		}
		document.removeEventListener('click', AffClickHandler);
	}
	setTimeout(function() {
		document.addEventListener('click', AffClickHandler);
	}, 1000); 
	})();
	</script>
	<?php
}
add_action('wp_footer', 'foxtool_adsclick_footer');
}