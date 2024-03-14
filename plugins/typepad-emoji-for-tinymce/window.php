<?php
require_once( dirname(dirname(dirname(dirname(__FILE__)))) . '/wp-config.php');
require_once(ABSPATH.'/wp-admin/admin.php');
load_plugin_textdomain('tpademoji', false , 'typepad-emoji-for-tinymce/locale');
?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title><?php _e('TypePad Emoji',"tpademoji"); ?></title>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
	<script language="javascript" type="text/javascript" src="<?php echo plugin_dir_url( __FILE__ ); ?>/InsertEmoji.js"></script>
	<base target="_self" />
	<script type="text/javascript">
//<![CDATA[
	var tpademojidomain = "<?php echo plugin_dir_url( __FILE__ ); ?>";
//]]>
	</script>
</head>
<body id="link" onload="tinyMCEPopup.executeOnLoad('init();');document.body.style.display='';" style="display: none">
	<form action="#">
	<center>
	<img src="palette.gif" width="600" height="288" usemap="#icons" border="0" />
	</center>
	<map name="icons">
		<area shape="rect" coords="6,6,22,22" alt="sun" href="javascript:emojiinsert('01', 'sun.gif')">
		<area shape="rect" coords="32,6,48,22" alt="cloud" href="javascript:emojiinsert('01', 'cloud.gif')">
		<area shape="rect" coords="58,6,74,22" alt="rain" href="javascript:emojiinsert('01', 'rain.gif')">
		<area shape="rect" coords="84,6,100,22" alt="snow" href="javascript:emojiinsert('01', 'snow.gif')">
		<area shape="rect" coords="110,6,126,22" alt="thunder" href="javascript:emojiinsert('01', 'thunder.gif')">
		<area shape="rect" coords="136,6,152,22" alt="typhoon" href="javascript:emojiinsert('01', 'typhoon.gif')">
		<area shape="rect" coords="162,6,178,22" alt="mist" href="javascript:emojiinsert('01', 'mist.gif')">
		<area shape="rect" coords="188,6,204,22" alt="sprinkle" href="javascript:emojiinsert('01', 'sprinkle.gif')">
		<area shape="rect" coords="214,6,230,22" alt="aries" href="javascript:emojiinsert('01', 'aries.gif')">
		<area shape="rect" coords="240,6,256,22" alt="taurus" href="javascript:emojiinsert('01', 'taurus.gif')">
		<area shape="rect" coords="266,6,282,22" alt="gemini" href="javascript:emojiinsert('01', 'gemini.gif')">
		<area shape="rect" coords="292,6,308,22" alt="cancer" href="javascript:emojiinsert('01', 'cancer.gif')">
		<area shape="rect" coords="318,6,334,22" alt="leo" href="javascript:emojiinsert('01', 'leo.gif')">
		<area shape="rect" coords="344,6,360,22" alt="virgo" href="javascript:emojiinsert('01', 'virgo.gif')">
		<area shape="rect" coords="370,6,386,22" alt="libra" href="javascript:emojiinsert('01', 'libra.gif')">
		<area shape="rect" coords="396,6,412,22" alt="scorpius" href="javascript:emojiinsert('01', 'scorpius.gif')">
		<area shape="rect" coords="422,6,438,22" alt="sagittarius" href="javascript:emojiinsert('01', 'sagittarius.gif')">
		<area shape="rect" coords="448,6,464,22" alt="capricornus" href="javascript:emojiinsert('01', 'capricornus.gif')">
		<area shape="rect" coords="474,6,490,22" alt="aquarius" href="javascript:emojiinsert('01', 'aquarius.gif')">
		<area shape="rect" coords="500,6,516,22" alt="pisces" href="javascript:emojiinsert('01', 'pisces.gif')">
		<area shape="rect" coords="526,6,542,22" alt="sports" href="javascript:emojiinsert('01', 'sports.gif')">
		<area shape="rect" coords="552,6,568,22" alt="baseball" href="javascript:emojiinsert('01', 'baseball.gif')">
		<area shape="rect" coords="578,6,594,22" alt="golf.gif" href="javascript:emojiinsert('01', 'golf.gif')">

		<area shape="rect" coords="6,32,22,48" alt="tennis" href="javascript:emojiinsert('02', 'tennis.gif')">
		<area shape="rect" coords="32,32,48,48" alt="soccer" href="javascript:emojiinsert('02', 'soccer.gif')">
		<area shape="rect" coords="58,32,74,48" alt="ski" href="javascript:emojiinsert('02', 'ski.gif')">
		<area shape="rect" coords="84,32,100,48" alt="basketball" href="javascript:emojiinsert('02', 'basketball.gif')">
		<area shape="rect" coords="110,32,126,48" alt="motorsports" href="javascript:emojiinsert('02', 'motorsports.gif')">
		<area shape="rect" coords="136,32,152,48" alt="pocketbell" href="javascript:emojiinsert('02', 'pocketbell.gif')">
		<area shape="rect" coords="162,32,178,48" alt="train" href="javascript:emojiinsert('02', 'train.gif')">
		<area shape="rect" coords="188,32,204,48" alt="subway" href="javascript:emojiinsert('02', 'subway.gif')">
		<area shape="rect" coords="214,32,230,48" alt="bullettrain" href="javascript:emojiinsert('02', 'bullettrain.gif')">
		<area shape="rect" coords="240,32,256,48" alt="car" href="javascript:emojiinsert('02', 'car.gif')">
		<area shape="rect" coords="266,32,282,48" alt="rvcar" href="javascript:emojiinsert('02', 'rvcar.gif')">
		<area shape="rect" coords="292,32,308,48" alt="bus" href="javascript:emojiinsert('02', 'bus.gif')">
		<area shape="rect" coords="318,32,334,48" alt="ship" href="javascript:emojiinsert('02', 'ship.gif')">
		<area shape="rect" coords="344,32,360,48" alt="airplane" href="javascript:emojiinsert('02', 'airplane.gif')">
		<area shape="rect" coords="370,32,386,48" alt="house" href="javascript:emojiinsert('02', 'house.gif')">
		<area shape="rect" coords="396,32,412,48" alt="building" href="javascript:emojiinsert('02', 'building.gif')">
		<area shape="rect" coords="422,32,438,48" alt="postoffice" href="javascript:emojiinsert('02', 'postoffice.gif')">
		<area shape="rect" coords="448,32,464,48" alt="hospital" href="javascript:emojiinsert('02', 'hospital.gif')">
		<area shape="rect" coords="474,32,490,48" alt="bank" href="javascript:emojiinsert('02', 'bank.gif')">
		<area shape="rect" coords="500,32,516,48" alt="atm" href="javascript:emojiinsert('02', 'atm.gif')">
		<area shape="rect" coords="526,32,542,48" alt="hotel" href="javascript:emojiinsert('02', 'hotel.gif')">
		<area shape="rect" coords="552,32,568,48" alt="school" href="javascript:emojiinsert('02', 'school.gif')">
		<area shape="rect" coords="578,32,594,48" alt="gasstation" href="javascript:emojiinsert('02', 'gasstation.gif')">

		<area shape="rect" coords="6,58,22,74" alt="24hours" href="javascript:emojiinsert('03', '24hours.gif')">
		<area shape="rect" coords="32,58,48,74" alt="signaler" href="javascript:emojiinsert('03', 'signaler.gif')">
		<area shape="rect" coords="58,58,74,74" alt="toilet" href="javascript:emojiinsert('03', 'toilet.gif')">
		<area shape="rect" coords="84,58,100,74" alt="parking" href="javascript:emojiinsert('03', 'parking.gif')">
		<area shape="rect" coords="110,58,126,74" alt="boutique" href="javascript:emojiinsert('03', 'boutique.gif')">
		<area shape="rect" coords="136,58,152,74" alt="bar" href="javascript:emojiinsert('03', 'bar.gif')">
		<area shape="rect" coords="162,58,178,74" alt="beer" href="javascript:emojiinsert('03', 'beer.gif')">
		<area shape="rect" coords="188,58,204,74" alt="fastfood" href="javascript:emojiinsert('03', 'fastfood.gif')">
		<area shape="rect" coords="214,58,230,74" alt="cafe" href="javascript:emojiinsert('03', 'cafe.gif')">
		<area shape="rect" coords="240,58,256,74" alt="restaurant" href="javascript:emojiinsert('03', 'restaurant.gif')">
		<area shape="rect" coords="266,58,282,74" alt="karaoke" href="javascript:emojiinsert('03', 'karaoke.gif')">
		<area shape="rect" coords="292,58,308,74" alt="movie" href="javascript:emojiinsert('03', 'movie.gif')">
		<area shape="rect" coords="318,58,334,74" alt="tv" href="javascript:emojiinsert('03', 'tv.gif')">
		<area shape="rect" coords="344,58,360,74" alt="carouselpony" href="javascript:emojiinsert('03', 'carouselpony.gif')">
		<area shape="rect" coords="370,58,386,74" alt="music.gif" href="javascript:emojiinsert('03', 'music.gif')">
		<area shape="rect" coords="396,58,412,74" alt="art.gif" href="javascript:emojiinsert('03', 'art.gif')">
		<area shape="rect" coords="422,58,438,74" alt="drama.gif" href="javascript:emojiinsert('03', 'drama.gif')">
		<area shape="rect" coords="448,58,464,74" alt="event.gif" href="javascript:emojiinsert('03', 'event.gif')">
		<area shape="rect" coords="474,58,490,74" alt="ticket.gif" href="javascript:emojiinsert('03', 'ticket.gif')">
		<area shape="rect" coords="500,58,516,74" alt="smoking.gif" href="javascript:emojiinsert('03', 'smoking.gif')">
		<area shape="rect" coords="526,58,542,74" alt="nosmoking.gif" href="javascript:emojiinsert('03', 'nosmoking.gif')">
		<area shape="rect" coords="552,58,568,74" alt="camera.gif" href="javascript:emojiinsert('03', 'camera.gif')">
		<area shape="rect" coords="578,58,594,74" alt="bag.gif" href="javascript:emojiinsert('03', 'bag.gif')">

		<area shape="rect" coords="6,84,22,100" alt="book" href="javascript:emojiinsert('04', 'book.gif')">
		<area shape="rect" coords="32,84,48,100" alt="ribbon" href="javascript:emojiinsert('04', 'ribbon.gif')">
		<area shape="rect" coords="58,84,74,100" alt="present" href="javascript:emojiinsert('04', 'present.gif')">
		<area shape="rect" coords="84,84,100,100" alt="birthday" href="javascript:emojiinsert('04', 'birthday.gif')">
		<area shape="rect" coords="110,84,126,100" alt="telephone" href="javascript:emojiinsert('04', 'telephone.gif')">
		<area shape="rect" coords="136,84,152,100" alt="mobilephone" href="javascript:emojiinsert('04', 'mobilephone.gif')">
		<area shape="rect" coords="162,84,178,100" alt="memo" href="javascript:emojiinsert('04', 'memo.gif')">
		<area shape="rect" coords="188,84,204,100" alt="yacht" href="javascript:emojiinsert('04', 'yacht.gif')">
		<area shape="rect" coords="214,84,230,100" alt="game" href="javascript:emojiinsert('04', 'game.gif')">
		<area shape="rect" coords="240,84,256,100" alt="cd" href="javascript:emojiinsert('04', 'cd.gif')">
		<area shape="rect" coords="266,84,282,100" alt="heart" href="javascript:emojiinsert('04', 'heart.gif')">
		<area shape="rect" coords="292,84,308,100" alt="spade" href="javascript:emojiinsert('04', 'spade.gif')">
		<area shape="rect" coords="318,84,334,100" alt="diamond" href="javascript:emojiinsert('04', 'diamond.gif')">
		<area shape="rect" coords="344,84,360,100" alt="club.gif" href="javascript:emojiinsert('04', 'club.gif')">
		<area shape="rect" coords="370,84,386,100" alt="eye" href="javascript:emojiinsert('04', 'eye.gif')">
		<area shape="rect" coords="396,84,412,100" alt="ear" href="javascript:emojiinsert('04', 'ear.gif')">
		<area shape="rect" coords="422,84,438,100" alt="rock" href="javascript:emojiinsert('04', 'rock.gif')">
		<area shape="rect" coords="448,84,464,100" alt="scissors" href="javascript:emojiinsert('04', 'scissors.gif')">
		<area shape="rect" coords="474,84,490,100" alt="paper" href="javascript:emojiinsert('04', 'paper.gif')">
		<area shape="rect" coords="500,84,516,100" alt="good" href="javascript:emojiinsert('04', 'good.gif')">
		<area shape="rect" coords="526,84,542,100" alt="punch" href="javascript:emojiinsert('04', 'punch.gif')">
		<area shape="rect" coords="552,84,568,100" alt="foot" href="javascript:emojiinsert('04', 'foot.gif')">
		<area shape="rect" coords="578,84,594,100" alt="shoe" href="javascript:emojiinsert('04', 'shoe.gif')">

		<area shape="rect" coords="6,110,22,126" alt="eyeglass" href="javascript:emojiinsert('05', 'eyeglass.gif')">
		<area shape="rect" coords="32,110,48,126" alt="wheelchair" href="javascript:emojiinsert('05', 'wheelchair.gif')">
		<area shape="rect" coords="58,110,74,126" alt="newmoon" href="javascript:emojiinsert('05', 'newmoon.gif')">
		<area shape="rect" coords="84,110,100,126" alt="moon1" href="javascript:emojiinsert('05', 'moon1.gif')">
		<area shape="rect" coords="110,110,126,126" alt="moon2" href="javascript:emojiinsert('05', 'moon2.gif')">
		<area shape="rect" coords="136,110,152,126" alt="moon3" href="javascript:emojiinsert('05', 'moon3.gif')">
		<area shape="rect" coords="162,110,178,126" alt="fullmoon" href="javascript:emojiinsert('05', 'fullmoon.gif')">
		<area shape="rect" coords="188,110,204,126" alt="dog" href="javascript:emojiinsert('05', 'dog.gif')">
		<area shape="rect" coords="214,110,230,126" alt="cat" href="javascript:emojiinsert('05', 'cat.gif')">
		<area shape="rect" coords="240,110,256,126" alt="freedial" href="javascript:emojiinsert('05', 'freedial.gif')">
		<area shape="rect" coords="266,110,282,126" alt="faxto" href="javascript:emojiinsert('05', 'faxto.gif')">
		<area shape="rect" coords="292,110,308,126" alt="mail" href="javascript:emojiinsert('05', 'mail.gif')">
		<area shape="rect" coords="318,110,334,126" alt="phoneto" href="javascript:emojiinsert('05', 'phoneto.gif')">
		<area shape="rect" coords="344,110,360,126" alt="mailto" href="javascript:emojiinsert('05', 'mailto.gif')">
		<area shape="rect" coords="370,110,386,126" alt="mobaq" href="javascript:emojiinsert('05', 'mobaq.gif')">
		<area shape="rect" coords="396,110,412,126" alt="info01" href="javascript:emojiinsert('05', 'info01.gif')">
		<area shape="rect" coords="422,110,438,126" alt="info02" href="javascript:emojiinsert('05', 'info02.gif')">
		<area shape="rect" coords="448,110,464,126" alt="new" href="javascript:emojiinsert('05', 'new.gif')">
		<area shape="rect" coords="474,110,490,126" alt="by-d" href="javascript:emojiinsert('05', 'by-d.gif')">
		<area shape="rect" coords="500,110,516,126" alt="d-point" href="javascript:emojiinsert('05', 'd-point.gif')">
		<area shape="rect" coords="526,110,542,126" alt="yen" href="javascript:emojiinsert('05', 'yen.gif')">
		<area shape="rect" coords="552,110,568,126" alt="free" href="javascript:emojiinsert('05', 'free.gif')">
		<area shape="rect" coords="578,110,594,126" alt="id" href="javascript:emojiinsert('05', 'id.gif')">

		<area shape="rect" coords="6,136,22,152" alt="tm" href="javascript:emojiinsert('06', 'tm.gif')">
		<area shape="rect" coords="32,136,48,152" alt="copyright" href="javascript:emojiinsert('06', 'copyright.gif')">
		<area shape="rect" coords="58,136,74,152" alt="r-mark" href="javascript:emojiinsert('06', 'r-mark.gif')">
		<area shape="rect" coords="84,136,100,152" alt="empty" href="javascript:emojiinsert('06', 'empty.gif')">
		<area shape="rect" coords="110,136,126,152" alt="secret" href="javascript:emojiinsert('06', 'secret.gif')">
		<area shape="rect" coords="136,136,152,152" alt="ban" href="javascript:emojiinsert('06', 'ban.gif')">
		<area shape="rect" coords="162,136,178,152" alt="pass" href="javascript:emojiinsert('06', 'pass.gif')">
		<area shape="rect" coords="188,136,204,152" alt="full" href="javascript:emojiinsert('06', 'full.gif')">
		<area shape="rect" coords="214,136,230,152" alt="sharp" href="javascript:emojiinsert('06', 'sharp.gif')">
		<area shape="rect" coords="240,136,256,152" alt="one" href="javascript:emojiinsert('06', 'one.gif')">
		<area shape="rect" coords="266,136,282,152" alt="two" href="javascript:emojiinsert('06', 'two.gif')">
		<area shape="rect" coords="292,136,308,152" alt="three" href="javascript:emojiinsert('06', 'three.gif')">
		<area shape="rect" coords="318,136,334,152" alt="four" href="javascript:emojiinsert('06', 'four.gif')">
		<area shape="rect" coords="344,136,360,152" alt="five" href="javascript:emojiinsert('06', 'five.gif')">
		<area shape="rect" coords="370,136,386,152" alt="six" href="javascript:emojiinsert('06', 'six.gif')">
		<area shape="rect" coords="396,136,412,152" alt="seven" href="javascript:emojiinsert('06', 'seven.gif')">
		<area shape="rect" coords="422,136,438,152" alt="eight" href="javascript:emojiinsert('06', 'eight.gif')">
		<area shape="rect" coords="448,136,464,152" alt="nine" href="javascript:emojiinsert('06', 'nine.gif')">
		<area shape="rect" coords="474,136,490,152" alt="zero" href="javascript:emojiinsert('06', 'zero.gif')">
		<area shape="rect" coords="500,136,516,152" alt="heart01" href="javascript:emojiinsert('06', 'heart01.gif')">
		<area shape="rect" coords="526,136,542,152" alt="heart04" href="javascript:emojiinsert('06', 'heart04.gif')">
		<area shape="rect" coords="552,136,568,152" alt="heart02" href="javascript:emojiinsert('06', 'heart02.gif')">
		<area shape="rect" coords="578,136,594,152" alt="heart03" href="javascript:emojiinsert('06', 'heart03.gif')">

		<area shape="rect" coords="6,162,22,178" alt="catface" href="javascript:emojiinsert('07', 'catface.gif')">
		<area shape="rect" coords="32,162,48,178" alt="happy01" href="javascript:emojiinsert('07', 'happy01.gif')">
		<area shape="rect" coords="58,162,74,178" alt="angry" href="javascript:emojiinsert('07', 'angry.gif')">
		<area shape="rect" coords="84,162,100,178" alt="despair" href="javascript:emojiinsert('07', 'despair.gif')">
		<area shape="rect" coords="110,162,126,178" alt="sad" href="javascript:emojiinsert('07', 'sad.gif')">
		<area shape="rect" coords="136,162,152,178" alt="wobbly" href="javascript:emojiinsert('07', 'wobbly.gif')">
		<area shape="rect" coords="162,162,178,178" alt="think" href="javascript:emojiinsert('07', 'think.gif')">
		<area shape="rect" coords="188,162,204,178" alt="confident" href="javascript:emojiinsert('07', 'confident.gif')">
		<area shape="rect" coords="214,162,230,178" alt="coldsweats01" href="javascript:emojiinsert('07', 'coldsweats01.gif')">
		<area shape="rect" coords="240,162,256,178" alt="coldsweats02" href="javascript:emojiinsert('07', 'coldsweats02.gif')">
		<area shape="rect" coords="266,162,282,178" alt="pout" href="javascript:emojiinsert('07', 'pout.gif')">
		<area shape="rect" coords="292,162,308,178" alt="gawk" href="javascript:emojiinsert('07', 'gawk.gif')">
		<area shape="rect" coords="318,162,334,178" alt="lovely" href="javascript:emojiinsert('07', 'lovely.gif')">
		<area shape="rect" coords="344,162,360,178" alt="bleah" href="javascript:emojiinsert('07', 'bleah.gif')">
		<area shape="rect" coords="370,162,386,178" alt="wink" href="javascript:emojiinsert('07', 'wink.gif')">
		<area shape="rect" coords="396,162,412,178" alt="happy02" href="javascript:emojiinsert('07', 'happy02.gif')">
		<area shape="rect" coords="422,162,438,178" alt="bearing" href="javascript:emojiinsert('07', 'bearing.gif')">
		<area shape="rect" coords="448,162,464,178" alt="crying" href="javascript:emojiinsert('07', 'crying.gif')">
		<area shape="rect" coords="474,162,490,178" alt="weep" href="javascript:emojiinsert('07', 'weep.gif')">
		<area shape="rect" coords="500,162,516,178" alt="delicious" href="javascript:emojiinsert('07', 'delicious.gif')">
		<area shape="rect" coords="526,162,542,178" alt="smile" href="javascript:emojiinsert('07', 'smile.gif')">
		<area shape="rect" coords="552,162,568,178" alt="shock" href="javascript:emojiinsert('07', 'shock.gif')">
		<area shape="rect" coords="578,162,594,178" alt="impact" href="javascript:emojiinsert('07', 'impact.gif')">

		<area shape="rect" coords="6,188,22,204" alt="sweat01" href="javascript:emojiinsert('08', 'sweat01.gif')">
		<area shape="rect" coords="32,188,48,204" alt="sweat02" href="javascript:emojiinsert('08', 'sweat02.gif')">
		<area shape="rect" coords="58,188,74,204" alt="dash" href="javascript:emojiinsert('08', 'dash.gif')">
		<area shape="rect" coords="84,188,100,204" alt="sign04" href="javascript:emojiinsert('08', 'sign04.gif')">
		<area shape="rect" coords="110,188,126,204" alt="sign05" href="javascript:emojiinsert('08', 'sign05.gif')">
		<area shape="rect" coords="136,188,152,204" alt="slate" href="javascript:emojiinsert('08', 'slate.gif')">
		<area shape="rect" coords="162,188,178,204" alt="pouch" href="javascript:emojiinsert('08', 'pouch.gif')">
		<area shape="rect" coords="188,188,204,204" alt="pen" href="javascript:emojiinsert('08', 'pen.gif')">
		<area shape="rect" coords="214,188,230,204" alt="shadow" href="javascript:emojiinsert('08', 'shadow.gif')">
		<area shape="rect" coords="240,188,256,204" alt="chair" href="javascript:emojiinsert('08', 'chair.gif')">
		<area shape="rect" coords="266,188,282,204" alt="night" href="javascript:emojiinsert('08', 'night.gif')">
		<area shape="rect" coords="292,188,308,204" alt="soon" href="javascript:emojiinsert('08', 'soon.gif')">
		<area shape="rect" coords="318,188,334,204" alt="on" href="javascript:emojiinsert('08', 'on.gif')">
		<area shape="rect" coords="344,188,360,204" alt="end" href="javascript:emojiinsert('08', 'end.gif')">
		<area shape="rect" coords="370,188,386,204" alt="clock" href="javascript:emojiinsert('08', 'clock.gif')">
		<area shape="rect" coords="396,188,412,204" alt="appli01" href="javascript:emojiinsert('08', 'appli01.gif')">
		<area shape="rect" coords="422,188,438,204" alt="appli02" href="javascript:emojiinsert('08', 'appli02.gif')">
		<area shape="rect" coords="448,188,464,204" alt="t-shirt" href="javascript:emojiinsert('08', 't-shirt.gif')">
		<area shape="rect" coords="474,188,490,204" alt="moneybag" href="javascript:emojiinsert('08', 'moneybag.gif')">
		<area shape="rect" coords="500,188,516,204" alt="rouge" href="javascript:emojiinsert('08', 'rouge.gif')">
		<area shape="rect" coords="526,188,542,204" alt="denim" href="javascript:emojiinsert('08', 'denim.gif')">
		<area shape="rect" coords="552,188,568,204" alt="snowboard" href="javascript:emojiinsert('08', 'snowboard.gif')">
		<area shape="rect" coords="578,188,594,204" alt="bell" href="javascript:emojiinsert('08', 'bell.gif')">

		<area shape="rect" coords="6,214,22,230" alt="door" href="javascript:emojiinsert('09', 'door.gif')">
		<area shape="rect" coords="32,214,48,230" alt="dollar" href="javascript:emojiinsert('09', 'dollar.gif')">
		<area shape="rect" coords="58,214,74,230" alt="pc" href="javascript:emojiinsert('09', 'pc.gif')">
		<area shape="rect" coords="84,214,100,230" alt="loveletter" href="javascript:emojiinsert('09', 'loveletter.gif')">
		<area shape="rect" coords="110,214,126,230" alt="wrench" href="javascript:emojiinsert('09', 'wrench.gif')">
		<area shape="rect" coords="136,214,152,230" alt="pencil" href="javascript:emojiinsert('09', 'pencil.gif')">
		<area shape="rect" coords="162,214,178,230" alt="crown" href="javascript:emojiinsert('09', 'crown.gif')">
		<area shape="rect" coords="188,214,204,230" alt="ring" href="javascript:emojiinsert('09', 'ring.gif')">
		<area shape="rect" coords="214,214,230,230" alt="sandclock" href="javascript:emojiinsert('09', 'sandclock.gif')">
		<area shape="rect" coords="240,214,256,230" alt="bicycle" href="javascript:emojiinsert('09', 'bicycle.gif')">
		<area shape="rect" coords="266,214,282,230" alt="japanesetea" href="javascript:emojiinsert('09', 'japanesetea.gif')">
		<area shape="rect" coords="292,214,308,230" alt="watch" href="javascript:emojiinsert('09', 'watch.gif')">
		<area shape="rect" coords="318,214,334,230" alt="hairsalon" href="javascript:emojiinsert('09', 'hairsalon.gif')">
		<area shape="rect" coords="344,214,360,230" alt="clip" href="javascript:emojiinsert('09', 'clip.gif')">
		<area shape="rect" coords="370,214,386,230" alt="note" href="javascript:emojiinsert('09', 'note.gif')">
		<area shape="rect" coords="396,214,412,230" alt="notes" href="javascript:emojiinsert('09', 'notes.gif')">
		<area shape="rect" coords="422,214,438,230" alt="cute" href="javascript:emojiinsert('09', 'cute.gif')">
		<area shape="rect" coords="448,214,464,230" alt="kissmark" href="javascript:emojiinsert('09', 'kissmark.gif')">
		<area shape="rect" coords="474,214,490,230" alt="annoy" href="javascript:emojiinsert('09', 'annoy.gif')">
		<area shape="rect" coords="500,214,516,230" alt="sleepy" href="javascript:emojiinsert('09', 'sleepy.gif')">
		<area shape="rect" coords="526,214,542,230" alt="shine" href="javascript:emojiinsert('09', 'shine.gif')">
		<area shape="rect" coords="552,214,568,230" alt="run" href="javascript:emojiinsert('09', 'run.gif')">
		<area shape="rect" coords="578,214,594,230" alt="flag" href="javascript:emojiinsert('09', 'flag.gif')">

		<area shape="rect" coords="6,240,22,256" alt="danger" href="javascript:emojiinsert('10', 'danger.gif')">
		<area shape="rect" coords="32,240,48,256" alt="sign01" href="javascript:emojiinsert('10', 'sign01.gif')">
		<area shape="rect" coords="58,240,74,256" alt="sign03" href="javascript:emojiinsert('10', 'sign03.gif')">
		<area shape="rect" coords="84,240,100,256" alt="sign02" href="javascript:emojiinsert('10', 'sign02.gif')">
		<area shape="rect" coords="110,240,126,256" alt="ng" href="javascript:emojiinsert('10', 'ng.gif')">
		<area shape="rect" coords="136,240,152,256" alt="ok" href="javascript:emojiinsert('10', 'ok.gif')">
		<area shape="rect" coords="162,240,178,256" alt="clear" href="javascript:emojiinsert('10', 'clear.gif')">
		<area shape="rect" coords="188,240,204,256" alt="enter" href="javascript:emojiinsert('10', 'enter.gif')">
		<area shape="rect" coords="214,240,230,256" alt="up" href="javascript:emojiinsert('10', 'up.gif')">
		<area shape="rect" coords="240,240,256,256" alt="down" href="javascript:emojiinsert('10', 'down.gif')">
		<area shape="rect" coords="266,240,282,256" alt="recycle" href="javascript:emojiinsert('10', 'recycle.gif')">
		<area shape="rect" coords="292,240,308,256" alt="upwardright" href="javascript:emojiinsert('10', 'upwardright.gif')">
		<area shape="rect" coords="318,240,334,256" alt="downwardright" href="javascript:emojiinsert('10', 'downwardright.gif')">
		<area shape="rect" coords="344,240,360,256" alt="upwardleft" href="javascript:emojiinsert('10', 'upwardleft.gif')">
		<area shape="rect" coords="370,240,386,256" alt="downwardleft" href="javascript:emojiinsert('10', 'downwardleft.gif')">
		<area shape="rect" coords="396,240,412,256" alt="updown" href="javascript:emojiinsert('10', 'updown.gif')">
		<area shape="rect" coords="422,240,438,256" alt="leftright" href="javascript:emojiinsert('10', 'leftright.gif')">
		<area shape="rect" coords="448,240,464,256" alt="key" href="javascript:emojiinsert('10', 'key.gif')">
		<area shape="rect" coords="474,240,490,256" alt="search" href="javascript:emojiinsert('10', 'search.gif')">
		<area shape="rect" coords="500,240,516,256" alt="xmas" href="javascript:emojiinsert('10', 'xmas.gif')">
		<area shape="rect" coords="526,240,542,256" alt="wave" href="javascript:emojiinsert('10', 'wave.gif')">
		<area shape="rect" coords="552,240,568,256" alt="fuji" href="javascript:emojiinsert('10', 'fuji.gif')">
		<area shape="rect" coords="578,240,594,256" alt="clover" href="javascript:emojiinsert('10', 'clover.gif')">

		<area shape="rect" coords="6,266,22,282" alt="cherry" href="javascript:emojiinsert('11', 'cherry.gif')">
		<area shape="rect" coords="32,266,48,282" alt="tulip" href="javascript:emojiinsert('11', 'tulip.gif')">
		<area shape="rect" coords="58,266,74,282" alt="banana" href="javascript:emojiinsert('11', 'banana.gif')">
		<area shape="rect" coords="84,266,100,282" alt="apple" href="javascript:emojiinsert('11', 'apple.gif')">
		<area shape="rect" coords="110,266,126,282" alt="bud" href="javascript:emojiinsert('11', 'bud.gif')">
		<area shape="rect" coords="136,266,152,282" alt="maple" href="javascript:emojiinsert('11', 'maple.gif')">
		<area shape="rect" coords="162,266,178,282" alt="cherryblossom" href="javascript:emojiinsert('11', 'cherryblossom.gif')">
		<area shape="rect" coords="188,266,204,282" alt="riceball" href="javascript:emojiinsert('11', 'riceball.gif')">
		<area shape="rect" coords="214,266,230,282" alt="cake" href="javascript:emojiinsert('11', 'cake.gif')">
		<area shape="rect" coords="240,266,256,282" alt="bottle" href="javascript:emojiinsert('11', 'bottle.gif')">
		<area shape="rect" coords="266,266,282,282" alt="noodle" href="javascript:emojiinsert('11', 'noodle.gif')">
		<area shape="rect" coords="292,266,308,282" alt="bread" href="javascript:emojiinsert('11', 'bread.gif')">
		<area shape="rect" coords="318,266,334,282" alt="snail" href="javascript:emojiinsert('11', 'snail.gif')">
		<area shape="rect" coords="344,266,360,282" alt="chick" href="javascript:emojiinsert('11', 'chick.gif')">
		<area shape="rect" coords="370,266,386,282" alt="penguin" href="javascript:emojiinsert('11', 'penguin.gif')">
		<area shape="rect" coords="396,266,412,282" alt="fish" href="javascript:emojiinsert('11', 'fish.gif')">
		<area shape="rect" coords="422,266,438,282" alt="flair" href="javascript:emojiinsert('11', 'flair.gif')">
		<area shape="rect" coords="448,266,464,282" alt="bomb" href="javascript:emojiinsert('11', 'bomb.gif')">
		<area shape="rect" coords="474,266,490,282" alt="horse" href="javascript:emojiinsert('11', 'horse.gif')">
		<area shape="rect" coords="500,266,516,282" alt="pig" href="javascript:emojiinsert('11', 'pig.gif')">
		<area shape="rect" coords="526,266,542,282" alt="wine" href="javascript:emojiinsert('11', 'wine.gif')">
		<area shape="rect" coords="552,266,568,282" alt="spa" href="javascript:emojiinsert('11', 'spa.gif')">
	</map>
</form>
<div style="float: right; margin:5px 5px 0 0;">
    <input type="button" id="cancel" name="cancel" value="<?php _e("Cancel", 'tpademoji'); ?>" onclick="tinyMCEPopup.close();" />
</div>
</body>
</html>